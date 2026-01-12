<?php

namespace App\Http\Controllers;

use App\Models\Acara;
use App\Models\KehadiranRSVP; // Diperlukan untuk menghapus data terkait
use App\Models\Tamu; // Diperlukan untuk menghapus data terkait
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Diperlukan untuk generate kode unik
use Illuminate\Support\Str; // Diperlukan untuk transaksi (opsional, tapi disarankan)

class TamuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $acara_id = $request->input('acara_id');

        $tamus = Tamu::with('acara')
            ->when($search, function ($query) use ($search) {
                $query->where('nama_tamu', 'like', '%'.$search.'%');
            })
            ->when($acara_id, function ($query) use ($acara_id) {
                $query->where('acara_id', $acara_id);
            })
            ->latest()
            ->paginate(10);

        $tamus->appends($request->all());

        $acaras = Acara::all();

        return view('tamu.index', compact('tamus', 'acaras'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil daftar acara yang aktif untuk dipilih di form
        $acaras = Acara::all();

        return view('tamu.create', compact('acaras'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'acara_id' => 'required|exists:acara,id',
            'nama_tamu' => 'required|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'kode_unik' => 'nullable|uuid|unique:tamus,kode_unik',
            'qr_code_string' => 'nullable|string|unique:tamus,qr_code_string',
            'status_undangan' => 'required|in:Diundang,RSVP Confirmed,RSVP Declined,Canceled',
        ]);

        // 2. Generate Kode Unik Tamu (Dipakai buat URL Undangan & QR Code)
        $kode_unik = Str::uuid()->toString();

        // 3. Simpan data
        Tamu::create([
            'nama_tamu' => $request->nama_tamu,
            'alamat' => $request->alamat,
            'kode_unik' => $kode_unik,
            'qr_code_string' => $kode_unik, // Sementara samain dengan kode_unik
            'status_undangan' => $request->status_undangan,
            'acara_id' => $request->acara_id,
        ]);

        return redirect()->route('tamu.index')->with('success', 'Data tamu berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tamu = Tamu::findOrFail($id); // Ambil data tamu berdasarkan ID
        $acaras = Acara::all();

        return view('tamu.edit', compact('tamu', 'acaras'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tamu $tamu)
    {
        // 1. Validasi Input
        $request->validate([
            'acara_id' => 'required|exists:acara,id',
            'nama_tamu' => 'required|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'kode_unik' => 'nullable|uuid|unique:tamus,kode_unik',
            'qr_code_string' => 'nullable|string|unique:tamus,qr_code_string',
            'status_undangan' => 'required|in:Diundang,RSVP Confirmed,RSVP Declined,Canceled',
        ]);

        // 2. Update data
        $tamu->update($request->all());

        return redirect()->route('tamu.index')->with('success', 'Data tamu berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tamu $tamu)
    {
        // 1. Pastikan data terkait (RSVP/Kehadiran) dihapus juga (Cascade Delete)
        // Jika lo udah set ON DELETE CASCADE di migration, ini opsional
        // Kalau belum, kita hapus manual:
        try {
            DB::beginTransaction();

            // Hapus data Kehadiran/RSVP yang terkait dengan tamu ini
            KehadiranRsvp::where('tamu_id', $tamu->id)->delete();

            // Hapus Log Check-in terkait (jika ada)
            // Asumsi: Lo punya model LogCheckin, kalau belum ada, hapus baris ini dulu
            // LogCheckin::where('tamu_id', $tamu->id)->delete();

            $tamu->delete();

            DB::commit();

            return redirect()->route('tamu.index')->with('success', 'Data tamu dan data terkait berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('tamu.index')->with('error', 'Gagal menghapus tamu. Terjadi kesalahan database.');
        }
    }

    /**
     * POLESAN: Lihat Undangan (Frontend buat Tamu)
     * Diakses lewat route: /u/{kode_unik}
     */
    public function showUndangan($kode_unik)
    {
        // Cari tamu berdasarkan UUID kode_unik
        $tamu = Tamu::where('kode_unik', $kode_unik)->with('acara')->firstOrFail();

        // Ambil semua ucapan buat ditampilin di undangan (Wish list)
        $wishes = KehadiranRSVP::whereNotNull('ucapan_doa')->latest()->get();

        return view('frontend.undangan', compact('tamu', 'wishes'));
    }

    /**
     * POLESAN: Input RSVP & Ucapan (Wishes)
     */
    public function submitRsvp(Request $request, $tamu_id)
    {
        $request->validate([
            'status_kehadiran' => 'required|in:Hadir,Tidak Hadir',
            'jumlah_orang' => 'required|integer|min:1',
            'ucapan_doa' => 'nullable|string|max:500',
        ]);

        // Simpan atau update konfirmasi kehadiran
        KehadiranRSVP::updateOrCreate(
            ['tamu_id' => $tamu_id],
            [
                'status_kehadiran' => $request->status_kehadiran,
                'jumlah_orang' => $request->jumlah_orang,
                'ucapan_doa' => $request->ucapan_doa,
                'waktu_input' => now(),
            ]
        );

        return back()->with('success', 'Konfirmasi berhasil dikirim. Makasih ya!');
    }

    /**
     * POLESAN: Halaman Generate QR & Link buat Admin
     */
    public function generateQr(Tamu $tamu)
    {
        // Link undangan yang bakal jadi isi QR Code
        $urlUndangan = route('undangan.show', $tamu->kode_unik);

        return view('tamu.qrcode', compact('tamu', 'urlUndangan'));
    }
}
