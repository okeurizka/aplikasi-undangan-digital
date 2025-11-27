<?php

namespace App\Http\Controllers;

use App\Models\Tamu;
use App\Models\Acara; // Diperlukan saat create/edit untuk dropdown
use App\Models\KehadiranRsvp; // Diperlukan untuk menghapus data terkait
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Diperlukan untuk generate kode unik
use SimpleSoftwareIO\QrCode\Facades\QrCode; // Diperlukan untuk generate QR Code
use Illuminate\Support\Facades\DB; // Diperlukan untuk transaksi (opsional, tapi disarankan)

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
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:tamus,email',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
            'acara_id' => 'required|exists:acaras,id', // Pastikan acara_id valid
            'kategori' => 'required|string|in:VIP,Umum,Keluarga', 
        ]);

        // 2. Generate Kode Unik Tamu (Dipakai buat URL Undangan & QR Code)
        $kode_unik = Str::uuid()->toString();

        // 3. Simpan data
        Tamu::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'acara_id' => $request->acara_id,
            'kategori' => $request->kategori,
            'kode_unik' => $kode_unik,
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
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:tamus,email,' . $tamu->id,
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
            'acara_id' => 'required|exists:acaras,id',
            'kategori' => 'required|string|in:VIP,Umum,Keluarga',
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