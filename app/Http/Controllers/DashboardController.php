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

        // Data statistik umum
        $totalTamu = Tamu::count();
        $totalRsvpConfirmed = KehadiranRsvp::where('status_kehadiran', 'Hadir')->count();

        // --- POLESAN: Pakai Stored Procedure sp_rekap_kehadiran ---
        $rekap = DB::select('CALL sp_rekap_kehadiran()');
        $totalCheckin = ! empty($rekap) ? $rekap[0]->total_hadir : 0;

        $data = [
            'totalTamu' => $totalTamu,
            'totalRsvpConfirmed' => $totalRsvpConfirmed,
            'totalCheckin' => $totalCheckin,
        ];

        if ($role === 'Administrator') {
            $data['totalAcara'] = Acara::count();

            return view('dashboard.admin', $data);
        } elseif ($role === 'Petugas') {
            return view('dashboard.petugas', $data);
        }

        return abort(403, 'Akses Ditolak.');
    }
}
