<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tamu; // Untuk mencari Tamu berdasarkan kode unik
use App\Models\LogCheckin; // Untuk menyimpan log check-in
use App\Models\Acara; // Untuk menampilkan acara aktif di halaman scanner
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckinController extends Controller
{
    /**
     * Tampilkan halaman scanner QR Code.
     * Diakses oleh: Petugas.
     */
    public function index()
    {
        // Ambil acara yang statusnya 'Aktif'
        $acaraAktif = Acara::where('status', 'Aktif')->first();

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
        // 1. Validasi Input (hanya butuh kode_unik)
        $request->validate([
            'kode_unik' => 'required|uuid', 
        ]);

        $kode_unik = $request->kode_unik;

        // 2. Cari Tamu berdasarkan kode unik
        $tamu = Tamu::where('kode_unik', $kode_unik)->first();

        // Cek 1: Tamu tidak ditemukan
        if (! $tamu) {
            return response()->json([
                'status' => 'error',
                'message' => 'QR Code tidak valid atau Tamu tidak terdaftar.',
            ], 404);
        }

        // Cek 2: Cek apakah Tamu sudah pernah check-in sebelumnya (untuk acara yang sama)
        $sudahCheckin = LogCheckin::where('tamu_id', $tamu->id)
                                  ->where('acara_id', $tamu->acara_id)
                                  ->exists();

        if ($sudahCheckin) {
            return response()->json([
                'status' => 'warning',
                'message' => 'Tamu a/n ' . $tamu->nama . ' sudah Check-in pada acara ini.',
                'tamu' => $tamu, // Kirim data tamu
            ], 409); // 409 Conflict
        }

        // 3. Simpan Log Check-in (Proses Check-in Berhasil)
        try {
            LogCheckin::create([
                'tamu_id' => $tamu->id,
                'acara_id' => $tamu->acara_id,
                'waktu_checkin' => now(),
                // Opsional: Siapa Petugas yang melakukan check-in
                'petugas_id' => Auth::id(), 
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Check-in ' . $tamu->nama . ' Berhasil!',
                'tamu' => $tamu,
            ], 200);

        } catch (\Exception $e) {
            // Jika ada error database
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan log check-in. Hubungi Admin.',
            ], 500);
        }
    }
}