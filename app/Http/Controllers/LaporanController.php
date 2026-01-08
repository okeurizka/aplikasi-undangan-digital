<?php

namespace App\Http\Controllers;

use App\Models\Acara;
use App\Models\KehadiranRsvp;
use App\Models\LogCheckin;
use App\Models\Tamu;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Gunakan 'waktu_acara' sesuai migration kita sebelumnya
        $acaras = Acara::orderBy('waktu_acara', 'desc')->get();

        $selectedAcaraId = $request->input('acara_id', $acaras->first()->id ?? null);

        $query = Tamu::with(['acara', 'rsvp', 'logCheckins'])
            ->where('acara_id', $selectedAcaraId)
            ->latest();

        $tamus = $query->paginate(20);

        $rekap = $this->hitungRekapitulasi($selectedAcaraId);

        return view('laporan.index', compact('tamus', 'acaras', 'selectedAcaraId', 'rekap'));
    }

    private function hitungRekapitulasi($acaraId)
    {
        if (! $acaraId) {
            return [
                'total_tamu' => 0, 'rsvp_hadir' => 0, 'rsvp_tidak_hadir' => 0,
                'tamu_checkin' => 0, 'persentase_checkin' => 0,
            ];
        }

        // 1. Total Tamu (Ini aman karena di tabel tamu ada acara_id)
        $totalTamu = Tamu::where('acara_id', $acaraId)->count();

        // 2. Hitung RSVP Hadir
        $rsvpHadir = KehadiranRsvp::whereHas('tamu', function ($q) use ($acaraId) {
            $q->where('acara_id', $acaraId);
        })->where('status_kehadiran', 'Hadir')->count();

        // 3. Hitung RSVP Tidak Hadir
        $rsvpTidakHadir = KehadiranRsvp::whereHas('tamu', function ($q) use ($acaraId) {
            $q->where('acara_id', $acaraId);
        })->where('status_kehadiran', 'Tidak Hadir')->count();

        // 4. Hitung Tamu yang Check-in (Pake whereHas karena acara_id ada di tabel tamu)
        $tamuCheckin = LogCheckin::whereHas('tamu', function ($q) use ($acaraId) {
            $q->where('acara_id', $acaraId);
        })->distinct('tamu_id')->count('tamu_id');

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
