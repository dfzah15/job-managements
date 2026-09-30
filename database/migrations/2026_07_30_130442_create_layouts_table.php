<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('layouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jobdesk_id')->constrained('jobdesks')->onDelete('cascade');
            $table->string('nama_layout');
            $table->string('slug')->unique();
            $table->text('deskripsi')->nullable();
            $table->string('image_path')->nullable();
            $table->integer('width')->default(1200);
            $table->integer('height')->default(800);
            $table->json('settings')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('layouts');
    }
};