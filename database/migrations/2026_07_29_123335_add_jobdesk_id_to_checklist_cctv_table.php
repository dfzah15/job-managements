<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('checklist_cctv', function (Blueprint $table) {
            $table->foreignId('jobdesk_id')->nullable()->after('id')->constrained('jobdesks')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('checklist_cctv', function (Blueprint $table) {
            $table->dropForeign(['jobdesk_id']);
            $table->dropColumn('jobdesk_id');
        });
    }
};