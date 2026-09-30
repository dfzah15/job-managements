<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Inventaris;

class InventarisSeeder extends Seeder
{
    public function run(): void
    {
        $inventaris = [
            [
                'lokasi_id' => 1,
                'nama_inventaris' => 'CCTV Camera 1',
                'jenis' => 'cctv',
                'merk' => 'Hikvision',
                'model' => 'DS-2CD1321',
                'jumlah' => 8,
                'spesifikasi' => '2MP, IR 30m',
                'tanggal_pemasangan' => '2024-01-15',
                'status' => 'aktif'
            ],
            [
                'lokasi_id' => 1,
                'nama_inventaris' => 'DVR 8 Channel',
                'jenis' => 'dvr',
                'merk' => 'Hikvision',
                'model' => 'DS-7208',
                'jumlah' => 1,
                'spesifikasi' => '8 Channel, 2TB HDD',
                'tanggal_pemasangan' => '2024-01-15',
                'status' => 'aktif'
            ],
            [
                'lokasi_id' => 2,
                'nama_inventaris' => 'CCTV Camera 2',
                'jenis' => 'cctv',
                'merk' => 'Dahua',
                'model' => 'DH-IPC-HFW1',
                'jumlah' => 12,
                'spesifikasi' => '4MP, IR 50m',
                'tanggal_pemasangan' => '2024-02-20',
                'status' => 'aktif'
            ],
            [
                'lokasi_id' => 3,
                'nama_inventaris' => 'CCTV Camera 3',
                'jenis' => 'cctv',
                'merk' => 'Samsung',
                'model' => 'SND-6082',
                'jumlah' => 6,
                'spesifikasi' => '2MP, IR 20m',
                'tanggal_pemasangan' => '2024-03-10',
                'status' => 'aktif'
            ],
        ];

        foreach ($inventaris as $data) {
            Inventaris::create($data);
        }
    }
}