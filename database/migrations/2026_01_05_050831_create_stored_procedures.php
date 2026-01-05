<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Procedure buat rekap kehadiran (Menghitung total status 'Hadir')
        DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_rekap_kehadiran;
            CREATE PROCEDURE sp_rekap_kehadiran()
            BEGIN
                SELECT COUNT(*) as total_hadir 
                FROM kehadiran_rsvp 
                WHERE status_kehadiran = 'Hadir';
            END
        ");

        // 2. Procedure buat validasi QR (Cek kode tamu valid atau nggak)
        DB::unprepared('
            DROP PROCEDURE IF EXISTS sp_validasi_qr;
            CREATE PROCEDURE sp_validasi_qr(IN qr_input VARCHAR(255))
            BEGIN
                SELECT * FROM tamu 
                WHERE qr_code_string = qr_input;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_rekap_kehadiran');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_validasi_qr');
    }
};
