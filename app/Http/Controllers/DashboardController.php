<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Acara; 
use App\Models\Tamu; 
use App\Models\KehadiranRsvp; 
use App\Models\LogCheckin; 
use Illuminate\Support\Facades\Auth; 

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role;

        // Ambil data statistik umum (diperlukan oleh Admin dan Petugas)
        $totalTamu = Tamu::count(); 
        $totalRsvpConfirmed = KehadiranRsvp::where('status_kehadiran', 'Hadir')->count();
        // Hitung tamu unik yang sudah check-in
        $totalCheckin = LogCheckin::distinct('tamu_id')->count('tamu_id'); 

        // Tentukan view dan data spesifik berdasarkan role
        if ($role === 'Administrator') {
            // Data spesifik Admin (contoh: Total Acara)
            $totalAcara = Acara::count();

            $data = [
                'totalAcara' => $totalAcara,
                'totalTamu' => $totalTamu,
                'totalRsvpConfirmed' => $totalRsvpConfirmed,
                'totalCheckin' => $totalCheckin,
            ];
            
            // Redirect ke view Admin (perlu dibuat: resources/views/dashboard/admin.blade.php)
            return view('dashboard.admin', $data); 

        } elseif ($role === 'Petugas') {
            // Data spesifik Petugas (biasanya sama dengan Admin, tanpa Acara)
            $data = [
                'totalTamu' => $totalTamu,
                'totalRsvpConfirmed' => $totalRsvpConfirmed,
                'totalCheckin' => $totalCheckin,
            ];
            
            // Redirect ke view Petugas (perlu dibuat: resources/views/dashboard/petugas.blade.php)
            return view('dashboard.petugas', $data);

        } else {
            // Harusnya ini gak perlu karena udah dicek di RoleMiddleware, tapi untuk safety
            return abort(403, 'Akses Ditolak.');
        }
    }
}