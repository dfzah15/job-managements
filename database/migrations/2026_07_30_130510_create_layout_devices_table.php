<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('layout_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('layout_id')->constrained('layouts')->onDelete('cascade');
            $table->foreignId('inventaris_id')->nullable()->constrained('inventaris')->onDelete('set null');
            $table->string('nama_device');
            $table->string('tipe_device');
            $table->integer('pos_x')->default(0);
            $table->integer('pos_y')->default(0);
            $table->integer('rotation')->default(0);
            $table->string('icon')->default('bi bi-camera');
            $table->string('color')->default('#3498db');
            $table->text('keterangan')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('layout_devices');
    }
};