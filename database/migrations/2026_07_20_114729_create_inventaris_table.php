<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventaris', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lokasi_id')->constrained('lokasi')->onDelete('cascade');
            $table->string('nama_inventaris');
            $table->string('jenis'); // cctv, dvr, monitor, kabel, dll
            $table->string('merk')->nullable();
            $table->string('model')->nullable();
            $table->integer('jumlah')->default(1);
            $table->text('spesifikasi')->nullable();
            $table->date('tanggal_pemasangan')->nullable();
            $table->enum('status', ['aktif', 'rusak', 'perbaikan', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventaris');
    }
};