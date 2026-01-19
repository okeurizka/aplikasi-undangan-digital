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
        Schema::create('acara', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('petugas_id')->unique();
            $table->foreign('petugas_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('nama_mempelai');
            $table->dateTime('waktu_acara');
            $table->string('lokasi');
            $table->text('deskripsi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acara');
    }
};
