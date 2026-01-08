<?php

namespace App\Http\Controllers;

use App\Models\Acara;
use App\Models\LogCheckin; // Untuk mencari Tamu berdasarkan kode unik
use App\Models\Tamu; // Untuk menyimpan log check-in
use Carbon\Carbon; // Untuk menampilkan acara aktif di halaman scanner
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

        $tamu = Tamu::where('kode_unik', $request->kode_unik)->first();

        if (! $tamu) {
            return response()->json(['success' => false, 'message' => 'Tamu tidak terdaftar!'], 404);
        }

        $sudahCheckin = LogCheckin::where('tamu_id', $tamu->id)->exists();
        if ($sudahCheckin) {
            return response()->json(['success' => false, 'message' => 'Tamu '.$tamu->nama_tamu.' sudah masuk sebelumnya.'], 400);
        }

        // SIMPAN DATA (Sesuaikan sama tabel lo yang nggak punya acara_id)
        LogCheckin::create([
            'tamu_id' => $tamu->id,
            'petugas_id' => auth()->id(), // Ngambil ID petugas/admin yang login
            'waktu_scan' => Carbon::now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil! Selamat datang '.$tamu->nama_tamu,
            'nama' => $tamu->nama_tamu,
        ]);
    }
}
