<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('layouts', function (Blueprint $table) {
            // Cek apakah kolom sudah ada sebelum menambahkan
            if (!Schema::hasColumn('layouts', 'last_edited_at')) {
                $table->timestamp('last_edited_at')->nullable();
            }
            
            if (!Schema::hasColumn('layouts', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable();
            }
            
            if (!Schema::hasColumn('layouts', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable();
            }
            
            if (!Schema::hasColumn('layouts', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
            
            if (!Schema::hasColumn('layouts', 'version')) {
                $table->integer('version')->default(1);
            }
        });
    }

    public function down(): void
    {
        Schema::table('layouts', function (Blueprint $table) {
            $columns = ['last_edited_at', 'created_by', 'updated_by', 'is_active', 'version'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('layouts', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};