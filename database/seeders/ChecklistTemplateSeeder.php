<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChecklistTemplate;
use App\Models\Jobdesk;

class ChecklistTemplateSeeder extends Seeder
{
    public function run()
    {
        // ============================================
        // CCTV
        // ============================================
        $cctv = Jobdesk::where('slug', 'cctv')->first();
        if ($cctv) {
            $templates = [
                ['field_name' => 'jumlah_camera', 'field_label' => 'Jumlah Camera', 'field_type' => 'number', 'is_required' => true, 'sort_order' => 1],
                ['field_name' => 'status_hdd', 'field_label' => 'Status HDD', 'field_type' => 'select', 'options' => ['normal' => 'Normal', 'tidak_normal' => 'Tidak Normal'], 'is_required' => true, 'sort_order' => 2],
                ['field_name' => 'kapasitas_hdd', 'field_label' => 'Kapasitas HDD', 'field_type' => 'text', 'is_required' => true, 'placeholder' => 'Contoh: 2TB, 4TB', 'sort_order' => 3],
                ['field_name' => 'jumlah_channel_dvr', 'field_label' => 'Jumlah Channel DVR', 'field_type' => 'number', 'is_required' => true, 'default_value' => 8, 'sort_order' => 4],
                ['field_name' => 'status_display', 'field_label' => 'Status Display', 'field_type' => 'select', 'options' => ['tampil' => '✅ Tampil', 'tidak_tampil' => '⚠️ Tidak Tampil', 'no_display' => '❌ No Display'], 'is_required' => true, 'sort_order' => 5],
                ['field_name' => 'jumlah_kamera_mati', 'field_label' => 'Jumlah Kamera Mati', 'field_type' => 'number', 'is_required' => true, 'default_value' => 0, 'sort_order' => 6],
            ];
            foreach ($templates as $data) {
                $data['jobdesk_id'] = $cctv->id;
                ChecklistTemplate::create($data);
            }
        }

        // ============================================
        // IT
        // ============================================
        $it = Jobdesk::where('slug', 'it')->first();
        if ($it) {
            $templates = [
                ['field_name' => 'server_status', 'field_label' => 'Server Status', 'field_type' => 'select', 'options' => ['online' => '🟢 Online', 'offline' => '🔴 Offline', 'maintenance' => '🟡 Maintenance'], 'is_required' => true, 'sort_order' => 1],
                ['field_name' => 'network_status', 'field_label' => 'Network Connectivity', 'field_type' => 'select', 'options' => ['normal' => 'Normal', 'slow' => 'Slow', 'down' => 'Down'], 'is_required' => true, 'sort_order' => 2],
                ['field_name' => 'backup_status', 'field_label' => 'Backup Status', 'field_type' => 'select', 'options' => ['success' => '✅ Success', 'running' => '🔄 Running', 'failed' => '❌ Failed'], 'is_required' => true, 'sort_order' => 3],
                ['field_name' => 'storage_usage', 'field_label' => 'Storage Usage (%)', 'field_type' => 'number', 'is_required' => true, 'placeholder' => '0-100', 'help_text' => 'Persentase penggunaan storage', 'sort_order' => 4],
                ['field_name' => 'security_patch', 'field_label' => 'Security Patch', 'field_type' => 'checkbox', 'is_required' => false, 'default_value' => '0', 'sort_order' => 5],
                ['field_name' => 'antivirus_update', 'field_label' => 'Antivirus Update', 'field_type' => 'checkbox', 'is_required' => false, 'default_value' => '0', 'sort_order' => 6],
                ['field_name' => 'jumlah_server', 'field_label' => 'Jumlah Server', 'field_type' => 'number', 'is_required' => true, 'sort_order' => 7],
                ['field_name' => 'server_down', 'field_label' => 'Server Down', 'field_type' => 'number', 'is_required' => true, 'default_value' => 0, 'sort_order' => 8],
            ];
            foreach ($templates as $data) {
                $data['jobdesk_id'] = $it->id;
                ChecklistTemplate::create($data);
            }
        }

        // ============================================
        // GENSET
        // ============================================
        $genset = Jobdesk::where('slug', 'genset')->first();
        if ($genset) {
            $templates = [
                ['field_name' => 'ampere', 'field_label' => 'Ampere (A)', 'field_type' => 'number', 'is_required' => true, 'sort_order' => 1],
                ['field_name' => 'hz', 'field_label' => 'Frekuensi (Hz)', 'field_type' => 'number', 'is_required' => true, 'sort_order' => 2],
                ['field_name' => 'oli_mesin', 'field_label' => 'Oli Mesin', 'field_type' => 'select', 'options' => ['normal' => 'Normal', 'rendah' => 'Rendah', 'rusak' => 'Rusak'], 'is_required' => true, 'sort_order' => 3],
                ['field_name' => 'radiator', 'field_label' => 'Radiator', 'field_type' => 'select', 'options' => ['normal' => 'Normal', 'kotor' => 'Kotor', 'bocor' => 'Bocor'], 'is_required' => true, 'sort_order' => 4],
                ['field_name' => 'jam_nyala', 'field_label' => 'Jam Nyala (Hours)', 'field_type' => 'number', 'is_required' => true, 'sort_order' => 5],
                ['field_name' => 'berasap', 'field_label' => 'Berasap', 'field_type' => 'select', 'options' => ['normal' => 'Normal', 'berlebihan' => 'Berlebihan', 'tidak' => 'Tidak Berasap'], 'is_required' => true, 'sort_order' => 6],
                ['field_name' => 'volt_aki', 'field_label' => 'Volt Aki (V)', 'field_type' => 'number', 'is_required' => true, 'sort_order' => 7],
                ['field_name' => 'sisa_bbm', 'field_label' => 'Sisa BBM (%)', 'field_type' => 'number', 'is_required' => true, 'placeholder' => '0-100', 'help_text' => 'Persentase sisa BBM', 'sort_order' => 8],
                ['field_name' => 'panel_control', 'field_label' => 'Panel Control', 'field_type' => 'select', 'options' => ['normal' => 'Normal', 'error' => 'Error', 'rusak' => 'Rusak'], 'is_required' => true, 'sort_order' => 9],
                ['field_name' => 'test_run', 'field_label' => 'Test Run', 'field_type' => 'checkbox', 'is_required' => false, 'default_value' => '0', 'sort_order' => 10],
            ];
            foreach ($templates as $data) {
                $data['jobdesk_id'] = $genset->id;
                ChecklistTemplate::create($data);
            }
        }

        // ============================================
        // ELECTRICAL
        // ============================================
        $electrical = Jobdesk::where('slug', 'electrical')->first();
        if ($electrical) {
            $templates = [
                ['field_name' => 'panel_kandang', 'field_label' => 'Panel Kandang C', 'field_type' => 'select', 'options' => ['normal' => 'Normal', 'tidak_normal' => 'Tidak Normal', 'rusak' => 'Rusak'], 'is_required' => true, 'sort_order' => 1],
                ['field_name' => 'contactor', 'field_label' => 'Contactor', 'field_type' => 'select', 'options' => ['normal' => 'Normal', 'rusak' => 'Rusak', 'aus' => 'Aus'], 'is_required' => true, 'sort_order' => 2],
                ['field_name' => 'mcb', 'field_label' => 'MCB', 'field_type' => 'select', 'options' => ['normal' => 'Normal', 'trip' => 'Trip', 'rusak' => 'Rusak'], 'is_required' => true, 'sort_order' => 3],
                ['field_name' => 'volt', 'field_label' => 'Volt (V)', 'field_type' => 'number', 'is_required' => true, 'sort_order' => 4],
                ['field_name' => 'ampere', 'field_label' => 'Ampere (A)', 'field_type' => 'number', 'is_required' => true, 'sort_order' => 5],
                ['field_name' => 'hz', 'field_label' => 'Frekuensi (Hz)', 'field_type' => 'number', 'is_required' => true, 'sort_order' => 6],
                ['field_name' => 'beban_r', 'field_label' => 'Beban R (kW)', 'field_type' => 'number', 'is_required' => true, 'sort_order' => 7],
                ['field_name' => 'beban_s', 'field_label' => 'Beban S (kW)', 'field_type' => 'number', 'is_required' => true, 'sort_order' => 8],
                ['field_name' => 'beban_t', 'field_label' => 'Beban T (kW)', 'field_type' => 'number', 'is_required' => true, 'sort_order' => 9],
                ['field_name' => 'grounding', 'field_label' => 'Grounding', 'field_type' => 'checkbox', 'is_required' => false, 'default_value' => '0', 'sort_order' => 10],
                ['field_name' => 'kabel_rusak', 'field_label' => 'Kabel Rusak', 'field_type' => 'checkbox', 'is_required' => false, 'default_value' => '0', 'sort_order' => 11],
            ];
            foreach ($templates as $data) {
                $data['jobdesk_id'] = $electrical->id;
                ChecklistTemplate::create($data);
            }
        }

        // ============================================
        // MECHANICAL
        // ============================================
        $mechanical = Jobdesk::where('slug', 'mechanical')->first();
        if ($mechanical) {
            $templates = [
                ['field_name' => 'mesin_berfungsi', 'field_label' => 'Mesin Berfungsi', 'field_type' => 'checkbox', 'is_required' => false, 'default_value' => '0', 'sort_order' => 1],
                ['field_name' => 'oli_mesin', 'field_label' => 'Oli Mesin', 'field_type' => 'select', 'options' => ['normal' => 'Normal', 'rendah' => 'Rendah', 'rusak' => 'Rusak'], 'is_required' => true, 'sort_order' => 2],
                ['field_name' => 'filter_udara', 'field_label' => 'Filter Udara', 'field_type' => 'select', 'options' => ['bersih' => 'Bersih', 'kotor' => 'Kotor', 'perlu_ganti' => 'Perlu Ganti'], 'is_required' => true, 'sort_order' => 3],
                ['field_name' => 'tekanan_angin', 'field_label' => 'Tekanan Angin (PSI)', 'field_type' => 'number', 'is_required' => true, 'sort_order' => 4],
                ['field_name' => 'suhu_mesin', 'field_label' => 'Suhu Mesin (°C)', 'field_type' => 'number', 'is_required' => true, 'sort_order' => 5],
                ['field_name' => 'getaran', 'field_label' => 'Getaran', 'field_type' => 'select', 'options' => ['normal' => 'Normal', 'berlebih' => 'Berlebih'], 'is_required' => true, 'sort_order' => 6],
                ['field_name' => 'pelumasan', 'field_label' => 'Pelumasan', 'field_type' => 'select', 'options' => ['normal' => 'Normal', 'kurang' => 'Kurang', 'berlebihan' => 'Berlebihan'], 'is_required' => true, 'sort_order' => 7],
                ['field_name' => 'belt', 'field_label' => 'Belt / V-Belt', 'field_type' => 'select', 'options' => ['normal' => 'Normal', 'aus' => 'Aus', 'putus' => 'Putus'], 'is_required' => true, 'sort_order' => 8],
            ];
            foreach ($templates as $data) {
                $data['jobdesk_id'] = $mechanical->id;
                ChecklistTemplate::create($data);
            }
        }

        // ============================================
        // PLUMBING
        // ============================================
        $plumbing = Jobdesk::where('slug', 'plumbing')->first();
        if ($plumbing) {
            $templates = [
                ['field_name' => 'tekanan_air', 'field_label' => 'Tekanan Air (Bar)', 'field_type' => 'number', 'is_required' => true, 'sort_order' => 1],
                ['field_name' => 'kebocoran_pipa', 'field_label' => 'Kebocoran Pipa', 'field_type' => 'checkbox', 'is_required' => false, 'default_value' => '0', 'sort_order' => 2],
                ['field_name' => 'pompa_air', 'field_label' => 'Pompa Air', 'field_type' => 'select', 'options' => ['normal' => 'Normal', 'rusak' => 'Rusak', 'overheat' => 'Overheat'], 'is_required' => true, 'sort_order' => 3],
                ['field_name' => 'tangki_air', 'field_label' => 'Tangki Air (Liter)', 'field_type' => 'number', 'is_required' => true, 'sort_order' => 4],
                ['field_name' => 'saluran_buang', 'field_label' => 'Saluran Buang', 'field_type' => 'checkbox', 'is_required' => false, 'default_value' => '0', 'sort_order' => 5],
                ['field_name' => 'filter_air', 'field_label' => 'Filter Air', 'field_type' => 'select', 'options' => ['bersih' => 'Bersih', 'kotor' => 'Kotor', 'perlu_ganti' => 'Perlu Ganti'], 'is_required' => true, 'sort_order' => 6],
                ['field_name' => 'debit_air', 'field_label' => 'Debit Air (L/menit)', 'field_type' => 'number', 'is_required' => true, 'sort_order' => 7],
                ['field_name' => 'kualitas_air', 'field_label' => 'Kualitas Air', 'field_type' => 'select', 'options' => ['bersih' => 'Bersih', 'keruh' => 'Keruh', 'berbau' => 'Berbau'], 'is_required' => true, 'sort_order' => 8],
            ];
            foreach ($templates as $data) {
                $data['jobdesk_id'] = $plumbing->id;
                ChecklistTemplate::create($data);
            }
        }

        echo "✅ Checklist templates berhasil dibuat!\n";
    }
}