<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Acara; // Model Acara untuk pilihan filter
use App\Models\Tamu; // Model Tamu
use App\Models\LogCheckin; // Model Log Check-in
use App\Models\KehadiranRsvp; // Model RSVP

class LaporanController extends Controller
{
    /**
     * Tampilkan halaman utama Laporan Rekapitulasi Kehadiran.
     * Diakses oleh: Administrator dan Petugas.
     */
    public function index(Request $request)
    {
        // 1. Ambil semua acara untuk dropdown filter
        $acaras = Acara::orderBy('tanggal', 'desc')->get();
        
        // Tentukan acara yang sedang dipilih (default: acara yang paling baru)
        $selectedAcaraId = $request->input('acara_id', $acaras->first()->id ?? null);

        // 2. Ambil data tamu berdasarkan acara yang dipilih
        $query = Tamu::with(['acara', 'rsvp', 'logCheckin'])
                     ->where('acara_id', $selectedAcaraId)
                     ->latest();

        $tamus = $query->paginate(20);

        // 3. Hitung Rekapitulasi Status Kehadiran untuk acara yang dipilih
        $rekap = $this->hitungRekapitulasi($selectedAcaraId);
        
        // 4. Kirim data ke view
        return view('laporan.index', compact('tamus', 'acaras', 'selectedAcaraId', 'rekap'));
    }

    /**
     * Fungsi internal untuk menghitung rekapitulasi status kehadiran.
     * @param int $acaraId ID Acara yang dihitung.
     * @return array
     */
    private function hitungRekapitulasi($acaraId)
    {
        if (!$acaraId) {
            return [
                'total_tamu' => 0,
                'rsvp_hadir' => 0,
                'rsvp_tidak_hadir' => 0,
                'tamu_checkin' => 0,
                'persentase_checkin' => 0,
            ];
        }

        $totalTamu = Tamu::where('acara_id', $acaraId)->count();
        
        // Hitung status RSVP (Hadir/Tidak Hadir/Belum Pasti)
        $rsvpHadir = KehadiranRsvp::where('acara_id', $acaraId)
                                  ->where('status_kehadiran', 'Hadir')
                                  ->count();
                                  
        $rsvpTidakHadir = KehadiranRsvp::where('acara_id', $acaraId)
                                       ->where('status_kehadiran', 'Tidak Hadir')
                                       ->count();

        // Hitung jumlah tamu yang sudah benar-benar check-in
        $tamuCheckin = LogCheckin::where('acara_id', $acaraId)
                                 ->distinct('tamu_id')
                                 ->count('tamu_id');
        
        // Hitung persentase check-in
        $persentaseCheckin = $totalTamu > 0 ? round(($tamuCheckin / $totalTamu) * 100, 2) : 0;

        return [
            'total_tamu' => $totalTamu,
            'rsvp_hadir' => $rsvpHadir,
            'rsvp_tidak_hadir' => $rsvpTidakHadir,
            'tamu_checkin' => $tamuCheckin,
            'persentase_checkin' => $persentaseCheckin,
        ];
    }
}