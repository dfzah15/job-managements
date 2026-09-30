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
        Schema::table('layouts', function (Blueprint $table) {
            // Tambahkan kolom yang hilang
            if (!Schema::hasColumn('layouts', 'last_edited_at')) {
                $table->timestamp('last_edited_at')->nullable();
            }
            
            if (!Schema::hasColumn('layouts', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable();
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('layouts', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('layouts', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
            
            if (!Schema::hasColumn('layouts', 'version')) {
                $table->integer('version')->default(1);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('layouts', function (Blueprint $table) {
            $table->dropColumn([
                'last_edited_at',
                'created_by',
                'updated_by',
                'is_active',
                'version'
            ]);
        });
    }
};