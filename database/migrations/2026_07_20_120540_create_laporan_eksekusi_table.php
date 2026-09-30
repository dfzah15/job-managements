<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_eksekusi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lokasi_id')->constrained('lokasi')->onDelete('cascade');
            $table->foreignId('laporan_aktivitas_id')->constrained('laporan_aktivitas')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal_eksekusi');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai')->nullable();
            $table->text('deskripsi_pekerjaan');
            $table->text('hasil')->nullable();
            $table->enum('status_eksekusi', ['pending', 'proses', 'selesai', 'gagal'])->default('pending');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_eksekusi');
    }
};