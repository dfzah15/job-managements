<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Jobdesk;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // ============================================
        // BUAT JOBDESK DULU (jika belum ada)
        // ============================================
        $jobdesks = [
            ['name' => 'CCTV', 'slug' => 'cctv', 'icon' => 'bi bi-camera', 'color' => 'info'],
            ['name' => 'IT', 'slug' => 'it', 'icon' => 'bi bi-laptop', 'color' => 'primary'],
            ['name' => 'Mechanical', 'slug' => 'mechanical', 'icon' => 'bi bi-gear', 'color' => 'warning'],
            ['name' => 'Electrical', 'slug' => 'electrical', 'icon' => 'bi bi-lightning', 'color' => 'danger'],
            ['name' => 'Plumbing', 'slug' => 'plumbing', 'icon' => 'bi bi-droplet', 'color' => 'info'],
            ['name' => 'Genset', 'slug' => 'genset', 'icon' => 'bi bi-fuel-pump', 'color' => 'warning'],
        ];

        foreach ($jobdesks as $data) {
            Jobdesk::firstOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }

        // ============================================
        // BUAT USER ADMIN
        // ============================================
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'no_telepon' => '081234567890',
                'is_active' => true,
            ]
        );

        // Assign semua jobdesk ke admin
        $allJobdesks = Jobdesk::all();
        $syncData = [];
        foreach ($allJobdesks as $jobdesk) {
            $syncData[$jobdesk->id] = [
                'can_view' => true,
                'can_create' => true,
                'can_edit' => true,
                'can_delete' => true,
                'can_export' => true,
                'can_approve' => true,
            ];
        }
        $admin->jobdesks()->sync($syncData);

        // ============================================
        // BUAT USER DAFFA
        // ============================================
        $daffa = User::firstOrCreate(
            ['email' => 'daffa@gmail.com'],
            [
                'name' => 'Daffa',
                'password' => Hash::make('daffa123'),
                'role' => 'admin',
                'no_telepon' => '081234567891',
                'is_active' => true,
            ]
        );

        // Assign semua jobdesk ke daffa
        $allJobdesks = Jobdesk::all();
        $syncData = [];
        foreach ($allJobdesks as $jobdesk) {
            $syncData[$jobdesk->id] = [
                'can_view' => true,
                'can_create' => true,
                'can_edit' => true,
                'can_delete' => true,
                'can_export' => true,
                'can_approve' => true,
            ];
        }
        $daffa->jobdesks()->sync($syncData);

        // ============================================
        // BUAT USER TEKNISI
        // ============================================
        $teknisi = User::firstOrCreate(
            ['email' => 'teknisi@example.com'],
            [
                'name' => 'Teknisi',
                'password' => Hash::make('password123'),
                'role' => 'teknisi',
                'no_telepon' => '081234567892',
                'is_active' => true,
            ]
        );

        // Assign jobdesk CCTV ke teknisi
        $cctv = Jobdesk::where('slug', 'cctv')->first();
        if ($cctv) {
            $teknisi->jobdesks()->syncWithoutDetaching([
                $cctv->id => [
                    'can_view' => true,
                    'can_create' => true,
                    'can_edit' => true,
                    'can_delete' => false,
                    'can_export' => false,
                    'can_approve' => false,
                ]
            ]);
        }

        echo "✅ Seeder completed!\n";
    }
}