<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Acara;
use Illumintate\Support\Facedes\DB;

class AcaraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Tampilkan semua acara, diurutkan berdasarkan tanggal terbaru
        $acaras = Acara::latest()->paginate(10);
        return view('acara.index', compact('acaras'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('acara.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'nama_acara' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'nullable|date_format:H:i|after:waktu_mulai',
            'tempat' => 'required|string|max:255',
            'alamat_lengkap' => 'nullable|string',
            'status' => 'required|in:Aktif,Tidak Aktif', // Status Acara
        ]);

        // 2. Simpan data
        Acara::create($request->all());

        return redirect()->route('acara.index')->with('success', 'Acara baru berhasil ditambahkan!');

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
        return view('acara.edit', compact('acara'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Acara $acara)
    {
        // 1. Validasi Input
        $request->validate([
            'nama_acara' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'nullable|date_format:H:i|after:waktu_mulai',
            'tempat' => 'required|string|max:255',
            'alamat_lengkap' => 'nullable|string',
            'status' => 'required|in:Aktif,Tidak Aktif', 
        ]);

        // 2. Update data
        $acara->update($request->all());

        return redirect()->route('acara.index')->with('success', 'Data acara berhasil diperbarui!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Acara $acara)
    {
        // Sebelum menghapus acara, kita harus memastikan tidak ada Tamu yang terikat
        // Di sini kita cek apakah ada Tamu yang acara_id-nya merujuk ke acara ini
        if ($acara->tamus()->count() > 0) {
            return redirect()->route('acara.index')->with('error', 'Gagal menghapus acara. Masih ada tamu yang terdaftar di acara ini.');
        }

        try {
            $acara->delete();
            return redirect()->route('acara.index')->with('success', 'Acara berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('acara.index')->with('error', 'Gagal menghapus acara. Terjadi kesalahan.');
        }
    }
}