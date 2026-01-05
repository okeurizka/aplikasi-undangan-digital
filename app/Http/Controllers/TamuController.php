<?php

namespace App\Http\Controllers;

use App\Models\Acara;
use App\Models\KehadiranRsvp; // Diperlukan saat create/edit untuk dropdown
use App\Models\Tamu; // Diperlukan untuk menghapus data terkait
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Diperlukan untuk generate kode unik
// Diperlukan untuk generate QR Code
use Illuminate\Support\Str; // Diperlukan untuk transaksi (opsional, tapi disarankan)

class TamuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data tamu, dengan eager loading ke model Acara
        $tamus = Tamu::with('acara')->latest()->paginate(10);

        // Ambil data acara untuk dropdown filter atau tampilan
        $acaras = Acara::all();

        return view('tamu.index', compact('tamus', 'acaras'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil daftar acara yang aktif untuk dipilih di form
        $acaras = Acara::where('status', 'Aktif')->get();

        return view('tamu.create', compact('acaras'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
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
        $acaras = Acara::where('status', 'Aktif')->get();

        return view('tamu.edit', compact('tamu', 'acaras'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tamu $tamu)
    {
        // 1. Validasi Input
        $request->validate([
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
}
