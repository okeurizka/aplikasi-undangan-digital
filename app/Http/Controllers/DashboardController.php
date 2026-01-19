<?php

namespace App\Http\Controllers;

use App\Models\Acara;
use App\Models\KehadiranRsvp;
use App\Models\Tamu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // WAJIB ADA buat manggil Procedure

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role;

        if ($role === 'Administrator') {
            // Admin tetep lihat global
            $totalTamu = Tamu::count();
            $totalRsvpConfirmed = KehadiranRsvp::where('status_kehadiran', 'Hadir')->count();
            $rekap = DB::select('CALL sp_rekap_kehadiran()');

            return view('dashboard.admin', [
                'totalTamu' => $totalTamu,
                'totalRsvpConfirmed' => $totalRsvpConfirmed,
                'totalCheckin' => ! empty($rekap) ? $rekap[0]->total_hadir : 0,
                'totalAcara' => Acara::count(),
            ]);
        }

        if ($role === 'Petugas') {
            // 1. Cari dulu acara yang ditugaskan ke petugas ini
            $acara = Acara::where('petugas_id', $user->id)->first();

            if (! $acara) {
                // Kalau petugas belum dapet jatah acara
                return view('dashboard.petugas', [
                    'totalTamu' => 0,
                    'totalRsvpConfirmed' => 0,
                    'totalCheckin' => 0,
                    'namaAcara' => 'Belum ada acara',
                ]);
            }

            // 2. Filter data cuma buat acara si petugas
            $totalTamu = Tamu::where('acara_id', $acara->id)->count();
            $totalRsvpConfirmed = KehadiranRsvp::whereHas('tamu', function ($q) use ($acara) {
                $q->where('acara_id', $acara->id);
            })->where('status_kehadiran', 'Hadir')->count();

            // 3. Rekap kehadiran per acara (pake kueri manual atau modif SP lo)
            $totalCheckin = \App\Models\LogCheckin::whereHas('tamu', function ($q) use ($acara) {
                $q->where('acara_id', $acara->id);
            })->count();

            return view('dashboard.petugas', [
                'totalTamu' => $totalTamu,
                'totalRsvpConfirmed' => $totalRsvpConfirmed,
                'totalCheckin' => $totalCheckin,
                'namaAcara' => $acara->nama_mempelai, // Biar petugas tau dia lagi login di acara siapa
            ]);
        }

        return abort(403, 'Akses Ditolak.');
    }
}
