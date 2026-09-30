<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jobdesk;

class JobdeskSeeder extends Seeder
{
    public function run()
    {
        $jobdesks = [
            [
                'name' => 'CCTV',
                'slug' => 'cctv',
                'description' => 'Sistem CCTV dan keamanan',
                'icon' => 'bi bi-camera',
                'color' => 'info',
            ],
            [
                'name' => 'IT',
                'slug' => 'it',
                'description' => 'Teknologi Informasi dan Jaringan',
                'icon' => 'bi bi-laptop',
                'color' => 'primary',
            ],
            [
                'name' => 'Mechanical',
                'slug' => 'mechanical',
                'description' => 'Mesin dan peralatan mekanik',
                'icon' => 'bi bi-gear',
                'color' => 'warning',
            ],
            [
                'name' => 'Electrical',
                'slug' => 'electrical',
                'description' => 'Instalasi listrik dan kelistrikan',
                'icon' => 'bi bi-lightning',
                'color' => 'danger',
            ],
            [
                'name' => 'Plumbing',
                'slug' => 'plumbing',
                'description' => 'Instalasi pipa dan air',
                'icon' => 'bi bi-droplet',
                'color' => 'info',
            ],
            [
                'name' => 'Genset',
                'slug' => 'genset',
                'description' => 'Generator set dan daya cadangan',
                'icon' => 'bi bi-fuel-pump',
                'color' => 'warning',
            ],
        ];

        foreach ($jobdesks as $jobdesk) {
            Jobdesk::create($jobdesk);
        }
    }
}