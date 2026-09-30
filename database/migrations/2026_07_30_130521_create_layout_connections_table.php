<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('layout_connections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('layout_id')->constrained('layouts')->onDelete('cascade');
            $table->foreignId('device_from_id')->constrained('layout_devices')->onDelete('cascade');
            $table->foreignId('device_to_id')->constrained('layout_devices')->onDelete('cascade');
            $table->string('tipe_kabel')->nullable();
            $table->integer('panjang_meter')->nullable();
            $table->string('warna')->default('#2ecc71');
            $table->string('label')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('layout_connections');
    }
};