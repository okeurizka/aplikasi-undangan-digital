<?php

namespace App\Http\Controllers;

use App\Models\Acara;
use Illuminate\Http\Request;

class AcaraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $acaras = Acara::query()
            ->when($search, function ($query) use ($search) {
                $query->where('nama_mempelai', 'like', '%'.$search.'%')
                    ->orWhere('lokasi', 'like', '%'.$search.'%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $acaras->appends($request->all());

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
        $request->validate([
            'nama_mempelai' => 'required|string|max:255',
            'waktu_acara' => 'required|date',
            'lokasi' => 'nullable|string',
            'deskripsi' => 'required|string|max:255',
            // Tambahin unique:acara biar gak error di level database
            'petugas_id' => 'required|exists:users,id|unique:acara,petugas_id',
        ], [
            'petugas_id.unique' => 'Petugas ini sudah bertugas di acara lain!',
        ]);

        Acara::create($request->all());

        return redirect()->route('acara.index')->with('success', 'Acara baru berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Ambil data acara beserta tamu dan rsvp-nya
        $acara = Acara::with(['tamu.rsvp', 'tamu.logCheckins'])->findOrFail($id);

        // Hitung ringkasan statistik buat ditampilin di detail
        $stats = [
            'total_tamu' => $acara->tamu->count(),
            'hadir' => $acara->tamu->filter(fn ($t) => $t->logCheckins->isNotEmpty())->count(),
            'rsvp_hadir' => $acara->tamu->filter(fn ($t) => optional($t->rsvp)->status_kehadiran == 'Hadir')->count(),
        ];

        return view('acara.show', compact('acara', 'stats'));
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
