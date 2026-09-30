<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Lokasi;

class LokasiSeeder extends Seeder
{
    public function run(): void
    {
        $lokasi = [
            ['nama_lokasi' => 'Gedung A - Lantai 1', 'kode_lokasi' => 'GA-01', 'alamat' => 'Jl. Merdeka No. 1'],
            ['nama_lokasi' => 'Gedung A - Lantai 2', 'kode_lokasi' => 'GA-02', 'alamat' => 'Jl. Merdeka No. 1'],
            ['nama_lokasi' => 'Gedung B - Lantai 1', 'kode_lokasi' => 'GB-01', 'alamat' => 'Jl. Sudirman No. 2'],
            ['nama_lokasi' => 'Gudang Pusat', 'kode_lokasi' => 'GP-01', 'alamat' => 'Jl. Industri Raya No. 5'],
        ];

        foreach ($lokasi as $data) {
            Lokasi::create($data);
        }
    }
}