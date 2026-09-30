<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_aktivitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lokasi_id')->constrained('lokasi')->onDelete('cascade');
            $table->string('jenis_aktivitas'); // perbaikan, pemeliharaan, inspeksi
            $table->string('keterangan');
            $table->boolean('checklist_camera')->default(false);
            $table->boolean('checklist_dvr')->default(false);
            $table->boolean('checklist_monitor')->default(false);
            $table->boolean('checklist_kabel_camera')->default(false);
            $table->boolean('checklist_kabel_listrik')->default(false);
            $table->boolean('checklist_konektor')->default(false);
            $table->text('kendala_kerusakan')->nullable();
            $table->integer('jumlah_rusak')->default(0);
            $table->enum('status_pekerjaan', ['selesai', 'pending', 'proses'])->default('pending');
            $table->text('solusi')->nullable();
            $table->date('tanggal_laporan');
            $table->string('pelapor');
            $table->string('teknisi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_aktivitas');
    }
};