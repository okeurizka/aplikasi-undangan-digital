<?php

namespace App\Http\Controllers;

use App\Models\Acara;
use App\Models\LogCheckin; // Untuk mencari Tamu berdasarkan kode unik
use App\Models\Tamu; // Untuk menyimpan log check-in
// Untuk menampilkan acara aktif di halaman scanner
use Illuminate\Http\Request; // Untuk mencatat waktu check-in

class CheckinController extends Controller
{
    /**
     * Tampilkan halaman scanner QR Code.
     * Diakses oleh: Petugas.
     */
    public function index()
    {
        // Ambil acara yang statusnya 'Aktif'
        $acaraAktif = Acara::latest()->first();

        // View scanner membutuhkan data acara aktif untuk ditampilkan
        return view('checkin.scanner', compact('acaraAktif'));
    }

    /**
     * Proses check-in tamu berdasarkan kode unik (dari QR Code).
     * Ini adalah endpoint yang dipanggil via AJAX/Fetch oleh scanner JS.
     * Diakses oleh: Petugas.
     */
    public function checkin(Request $request)
    {
        $request->validate([
            'kode_unik' => 'required',
        ]);

        // 1. Ambil data acara yang ditugaskan ke petugas yang sedang login
        $acaraPetugas = Acara::where('petugas_id', auth()->id())->first();

        if (! $acaraPetugas) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak ditugaskan di acara manapun!',
            ], 403);
        }

        // 2. Cari tamu berdasarkan kode_unik
        $tamu = Tamu::where('kode_unik', $request->kode_unik)->first();

        if (! $tamu) {
            return response()->json([
                'success' => false,
                'message' => 'Tamu tidak terdaftar!',
            ], 404);
        }

        // 3. LOGIKA DENY: Cek apakah tamu ini milik acara si petugas?
        if ($tamu->acara_id !== $acaraPetugas->id) {
            return response()->json([
                'success' => false,
                'message' => 'Akses Ditolak! Tamu ini terdaftar di acara lain.',
            ], 403);
        }

        // 4. Cek apakah sudah pernah check-in
        $sudahCheckin = LogCheckin::where('tamu_id', $tamu->id)->exists();
        if ($sudahCheckin) {
            return response()->json([
                'success' => false,
                'message' => 'Tamu '.$tamu->nama_tamu.' sudah masuk sebelumnya.',
            ], 400);
        }

        // 5. Berhasil, simpan log
        LogCheckin::create([
            'tamu_id' => $tamu->id,
            'petugas_id' => auth()->id(),
            'waktu_scan' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil! Selamat datang '.$tamu->nama_tamu,
            'nama' => $tamu->nama_tamu,
        ]);
    }
}
