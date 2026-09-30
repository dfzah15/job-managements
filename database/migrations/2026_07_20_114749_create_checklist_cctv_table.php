<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checklist_cctv', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lokasi_id')->constrained('lokasi')->onDelete('cascade');
            $table->foreignId('inventaris_id')->constrained('inventaris')->onDelete('cascade');
            $table->date('tanggal_check');
            $table->time('waktu_check');
            $table->string('petugas_check');
            $table->integer('jumlah_camera');
            $table->enum('status_hdd', ['normal', 'tidak_normal'])->default('normal');
            $table->string('kapasitas_hdd');
            $table->integer('jumlah_channel_dvr');
            $table->enum('status_display', ['tampil', 'tidak_tampil', 'no_display']);
            $table->integer('jumlah_kamera_mati');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checklist_cctv');
    }
};