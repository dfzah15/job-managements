<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('checklist_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jobdesk_id')->constrained('jobdesks')->onDelete('cascade');
            $table->foreignId('lokasi_id')->constrained('lokasi')->onDelete('cascade');
            $table->foreignId('inventaris_id')->nullable()->constrained('inventaris')->onDelete('set null');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal_check');
            $table->time('waktu_check')->nullable();
            $table->string('petugas_check');
            $table->text('catatan')->nullable();
            $table->enum('status', ['normal', 'warning', 'danger'])->default('normal');
            $table->json('values')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('checklist_results');
    }
};