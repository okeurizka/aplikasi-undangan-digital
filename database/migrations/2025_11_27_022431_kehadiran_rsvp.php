<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kehadiran_rsvp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tamu_id')->constrained('tamu')->onDelete('cascade');
            $table->enum('status_kehadiran', ['Hadir', 'Tidak Hadir'])->default('Hadir');
            $table->integer('jumlah_orang');
            $table->text('ucapan_doa');
            $table->datetime('waktu_input');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kehadiran_rsvp');
    }
};
