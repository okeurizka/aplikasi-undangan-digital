<?php

namespace App\Http\Controllers;

use App\Models\Acara;
use App\Models\LogCheckin; // Untuk mencari Tamu berdasarkan kode unik
use App\Models\Tamu; // Untuk menyimpan log check-in
use Illuminate\Http\Request; // Untuk menampilkan acara aktif di halaman scanner
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        // 1. Validasi Input
        $request->validate([
            'kode_unik' => 'required', // UUID formatnya string unik
        ]);

        $kode_unik = $request->kode_unik;

        // 2. Cari Tamu pakai STORED PROCEDURE (Sesuai instruksi soal)
        // Kita panggil procedure yang udah kita buat di migration tadi
        $results = DB::select('CALL sp_validasi_qr(?)', [$kode_unik]);

        // Karena DB::select balikinnya array, kita ambil index ke-0
        $tamu = ! empty($results) ? $results[0] : null;

        // Cek 1: Tamu tidak ditemukan
        if (! $tamu) {
            return response()->json([
                'status' => 'error',
                'message' => 'QR Code tidak valid atau Tamu tidak terdaftar.',
            ], 404);
        }

        // Cek 2: Cek apakah Tamu sudah pernah check-in
        // Tips: Karena hasil Stored Procedure itu stdClass, aksesnya pakai ->
        $sudahCheckin = LogCheckin::where('tamu_id', $tamu->id)
            ->exists();

        if ($sudahCheckin) {
            return response()->json([
                'status' => 'warning',
                'message' => 'Tamu a/n '.$tamu->nama_tamu.' sudah Check-in sebelumnya.',
                'tamu' => $tamu,
            ], 409);
        }

        // 3. Simpan Log Check-in
        try {
            LogCheckin::create([
                'tamu_id' => $tamu->id,
                'petugas_id' => Auth::id(),
                'waktu_scan' => now(), // Sesuaikan nama kolom di migration lo
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Check-in '.$tamu->nama_tamu.' Berhasil!',
                'tamu' => $tamu,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal simpan data: '.$e->getMessage(),
            ], 500);
        }
    }
}
