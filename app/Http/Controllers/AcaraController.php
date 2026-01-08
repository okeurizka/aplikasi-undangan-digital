<?php

namespace App\Http\Controllers;

use App\Models\Acara;
use Illuminate\Http\Request;

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
            'nama_mempelai' => 'required|string|max:255',
            'waktu_acara' => 'required|date',
            'lokasi' => 'nullable|string',
            'deskripsi' => 'required|string|max:255',
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
        $acara = Acara::findOrFail($id);

        return view('acara.edit', compact('acara'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Acara $acara)
    {
        // 1. Validasi Input
        $request->validate([
            'nama_mempelai' => 'required|string|max:255',
            'waktu_acara' => 'required|date',
            'lokasi' => 'nullable|string',
            'deskripsi' => 'required|string|max:255',
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
        if ($acara->tamu()->count() > 0) {
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
