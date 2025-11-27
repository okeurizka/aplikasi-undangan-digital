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
        schema::create('tamu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acara_id')->constrained('acara')->onDelete('cascade');
            $table->string('nama_tamu');
            $table->string('alamat');
            $table->string('kode_unik')->unique();
            $table->string('qr_code_string')->unique();
            $table->enum('status_undangan', ['Diundang', 'RSVP Confirmed','RSVP Declinded','Canceled'])->default('Diundang');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        schema::dropIfExists('tamu');
    }
};