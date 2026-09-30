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
        Schema::table('laporan_aktivitas', function (Blueprint $table) {
 $table->foreignId('jobdesk_id')->nullable()->after('id')->constrained('jobdesks')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_aktivitas', function (Blueprint $table) {
 $table->foreignId('jobdesk_id')->nullable()->after('id')->constrained('jobdesks')->onDelete('set null');
        });
    }
};
