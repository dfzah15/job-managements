<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Ubah enum role untuk menambahkan 'manajer'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'teknisi', 'user', 'manajer') DEFAULT 'user'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'teknisi', 'user') DEFAULT 'user'");
    }
};