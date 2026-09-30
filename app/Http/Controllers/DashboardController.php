<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lokasi;
use App\Models\Inventaris;
use App\Models\ChecklistCctv;
use App\Models\LaporanAktivitas;
use App\Models\LaporanEksekusi;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DashboardEksekutifExport;
use PDF;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // ============================================
        // DATA STATISTIK UMUM (untuk semua user)
        // ============================================
        $totalLokasi = Lokasi::count();
        $totalInventaris = Inventaris::count();
        $totalChecklist = ChecklistCctv::count();
        $totalLaporan = LaporanAktivitas::count();
        $laporanPending = LaporanAktivitas::where('status_pekerjaan', 'pending')->count();
        $totalStaff = User::where('is_active', true)->count();
        $totalTeknisi = User::where('role', 'teknisi')->where('is_active', true)->count();
        $totalAdmin = User::where('role', 'admin')->where('is_active', true)->count();

        // ============================================
        // DATA BULANAN (2020-2021)
        // ============================================
        $dataBulanan = $this->getDataBulanan();

        // ============================================
        // GRAFIK KERUSAKAN PER TAHUN SEMUA LOKASI
        // ============================================
        $kerusakanPerTahun = $this->getKerusakanPerTahun();

        // ============================================
        // GRAFIK DEVICE RUSAK PER TAHUN
        // ============================================
        $deviceRusakPerTahun = $this->getDeviceRusakPerTahun();

        // ============================================
        // GRAFIK PENAMBAHAN CAMERA & UPGRADE DVR & MEMORY
        // ============================================
        $penambahanDevice = $this->getPenambahanDevice();

        // ============================================
        // RATA-RATA MEMORY YANG DIGUNAKAN
        // ============================================
        $rataRataMemory = $this->getRataRataMemory();

        // ============================================
        // GRAFIK LOKASI SERING RUSAK 5 TAHUN
        // ============================================
        $lokasiSeringRusak = $this->getLokasiSeringRusak();

        // ============================================
        // DATA UNTUK GRAFIK LAINNYA
        // ============================================
        $grafikStatus = $this->getGrafikStatus();
        $grafikKerusakanLokasi = $this->getGrafikKerusakanLokasi();

        // ============================================
        // DATA EKSEKUTIF (Khusus Admin & Manajer)
        // ============================================
        $isEksekutif = Auth::user() && in_array(Auth::user()->role, ['admin', 'manajer']);
        $eksekutifData = null;

        if ($isEksekutif) {
            $eksekutifData = [
                'performanceTeknisi' => $this->getPerformanceTeknisi(),
                'trendBulanan' => $this->getTrendBulanan(),
                'lokasiRawan' => $this->getLokasiRawan(),
                'inventarisStatus' => $this->getInventarisStatus(),
            ];
        }

        // ============================================
        // MODE DASHBOARD (dari request atau session)
        // ============================================
        $mode = $request->input('mode', session('dashboard_mode', 'default'));
        session(['dashboard_mode' => $mode]);

        return view('dashboard.index', compact(
            'totalLokasi',
            'totalInventaris',
            'totalChecklist',
            'totalLaporan',
            'laporanPending',
            'totalStaff',
            'totalTeknisi',
            'totalAdmin',
            'dataBulanan',
            'kerusakanPerTahun',
            'deviceRusakPerTahun',
            'penambahanDevice',
            'rataRataMemory',
            'lokasiSeringRusak',
            'grafikStatus',
            'grafikKerusakanLokasi',
            'isEksekutif',
            'eksekutifData',
            'mode'
        ));
    }

    // ============================================
    // DATA EKSEKUTIF
    // ============================================

    private function getPerformanceTeknisi()
    {
        return LaporanEksekusi::select(
            'user_id',
            DB::raw('COUNT(*) as total_eksekusi'),
            DB::raw('SUM(CASE WHEN status_eksekusi = "selesai" THEN 1 ELSE 0 END) as selesai'),
            DB::raw('SUM(CASE WHEN status_eksekusi = "proses" THEN 1 ELSE 0 END) as proses'),
            DB::raw('SUM(CASE WHEN status_eksekusi = "pending" THEN 1 ELSE 0 END) as pending')
        )
        ->with('user')
        ->groupBy('user_id')
        ->having('total_eksekusi', '>', 0)
        ->orderBy('selesai', 'desc')
        ->get();
    }

    private function getTrendBulanan()
    {
        return LaporanAktivitas::select(
            DB::raw('DATE_FORMAT(tanggal_laporan, "%Y-%m") as bulan'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN status_pekerjaan = "selesai" THEN 1 ELSE 0 END) as selesai')
        )
        ->where('tanggal_laporan', '>=', now()->subMonths(6))
        ->groupBy('bulan')
        ->orderBy('bulan', 'asc')
        ->get();
    }

    private function getLokasiRawan()
    {
        return LaporanAktivitas::select(
            'lokasi_id',
            DB::raw('COUNT(*) as total')
        )
        ->with('lokasi')
        ->groupBy('lokasi_id')
        ->orderBy('total', 'desc')
        ->limit(5)
        ->get();
    }

    private function getInventarisStatus()
    {
        return Inventaris::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get();
    }

    // ============================================
    // DATA DASHBOARD UMUM
    // ============================================

    private function getDataBulanan()
    {
        $data = LaporanAktivitas::select(
            DB::raw('YEAR(tanggal_laporan) as tahun'),
            DB::raw('MONTH(tanggal_laporan) as bulan'),
            DB::raw('COUNT(*) as total')
        )
        ->whereIn(DB::raw('YEAR(tanggal_laporan)'), [2020, 2021])
        ->groupBy('tahun', 'bulan')
        ->orderBy('tahun', 'asc')
        ->orderBy('bulan', 'asc')
        ->get();

        if ($data->isEmpty()) {
            $bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $data2020 = [1000, 1400, 2050, 1300, 2500, 1900, 1950, 2250, 1350, 1900, 2700, 3000];
            $data2021 = [1350, 1450, 2100, 1500, 2250, 2000, 1750, 2000, 1250, 1900, 2500, 2900];
            
            return [
                'bulan' => $bulan,
                'tahun_2020' => $data2020,
                'tahun_2021' => $data2021,
            ];
        }

        $bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $data2020 = array_fill(0, 12, 0);
        $data2021 = array_fill(0, 12, 0);

        foreach ($data as $item) {
            $index = $item->bulan - 1;
            if ($item->tahun == 2020) {
                $data2020[$index] = $item->total;
            } elseif ($item->tahun == 2021) {
                $data2021[$index] = $item->total;
            }
        }

        return [
            'bulan' => $bulan,
            'tahun_2020' => $data2020,
            'tahun_2021' => $data2021,
        ];
    }

    private function getKerusakanPerTahun()
    {
        $data = LaporanAktivitas::select(
            DB::raw('YEAR(tanggal_laporan) as tahun'),
            DB::raw('COUNT(*) as total_kerusakan'),
            DB::raw('SUM(jumlah_rusak) as total_rusak')
        )
        ->where('jenis_aktivitas', 'perbaikan')
        ->groupBy('tahun')
        ->orderBy('tahun', 'asc')
        ->get();

        if ($data->isEmpty()) {
            return [
                'tahun' => [2020, 2021, 2022, 2023, 2024],
                'total_kerusakan' => [120, 150, 180, 200, 220],
                'total_rusak' => [250, 300, 350, 400, 450],
            ];
        }

        return [
            'tahun' => $data->pluck('tahun')->toArray(),
            'total_kerusakan' => $data->pluck('total_kerusakan')->toArray(),
            'total_rusak' => $data->pluck('total_rusak')->toArray(),
        ];
    }

    private function getDeviceRusakPerTahun()
    {
        $data = ChecklistCctv::select(
            DB::raw('YEAR(tanggal_check) as tahun'),
            DB::raw('SUM(jumlah_kamera_mati) as camera_rusak'),
            DB::raw('SUM(CASE WHEN status_hdd = "tidak_normal" THEN 1 ELSE 0 END) as hdd_rusak'),
            DB::raw('SUM(CASE WHEN status_display = "tidak_tampil" THEN 1 ELSE 0 END) as display_rusak'),
            DB::raw('SUM(CASE WHEN status_display = "no_display" THEN 1 ELSE 0 END) as no_display')
        )
        ->groupBy('tahun')
        ->orderBy('tahun', 'asc')
        ->get();

        if ($data->isEmpty()) {
            return [
                'tahun' => [2020, 2021, 2022, 2023, 2024],
                'camera_rusak' => [45, 50, 55, 60, 40],
                'hdd_rusak' => [20, 25, 30, 35, 20],
                'display_rusak' => [15, 18, 20, 22, 15],
                'no_display' => [10, 12, 15, 18, 10],
            ];
        }

        return [
            'tahun' => $data->pluck('tahun')->toArray(),
            'camera_rusak' => $data->pluck('camera_rusak')->toArray(),
            'hdd_rusak' => $data->pluck('hdd_rusak')->toArray(),
            'display_rusak' => $data->pluck('display_rusak')->toArray(),
            'no_display' => $data->pluck('no_display')->toArray(),
        ];
    }

    private function getPenambahanDevice()
    {
        $cameraData = Inventaris::select(
            DB::raw('YEAR(tanggal_pemasangan) as tahun'),
            DB::raw('SUM(jumlah) as total')
        )
        ->where('jenis', 'cctv')
        ->whereNotNull('tanggal_pemasangan')
        ->groupBy('tahun')
        ->orderBy('tahun', 'asc')
        ->get();

        $dvrData = Inventaris::select(
            DB::raw('YEAR(tanggal_pemasangan) as tahun'),
            DB::raw('SUM(jumlah) as total')
        )
        ->where('jenis', 'dvr')
        ->whereNotNull('tanggal_pemasangan')
        ->groupBy('tahun')
        ->orderBy('tahun', 'asc')
        ->get();

        $memoryData = ChecklistCctv::select(
            DB::raw('YEAR(tanggal_check) as tahun'),
            DB::raw('COUNT(*) as total')
        )
        ->where('status_hdd', 'normal')
        ->groupBy('tahun')
        ->orderBy('tahun', 'asc')
        ->get();

        if ($cameraData->isEmpty() && $dvrData->isEmpty() && $memoryData->isEmpty()) {
            return [
                'tahun' => [2020, 2021, 2022, 2023, 2024],
                'camera' => [50, 60, 70, 80, 90],
                'dvr' => [10, 12, 15, 18, 20],
                'memory' => [100, 110, 120, 130, 140],
            ];
        }

        $allYears = array_unique(array_merge(
            $cameraData->pluck('tahun')->toArray(),
            $dvrData->pluck('tahun')->toArray(),
            $memoryData->pluck('tahun')->toArray()
        ));
        sort($allYears);

        $cameraByYear = [];
        $dvrByYear = [];
        $memoryByYear = [];

        foreach ($allYears as $year) {
            $cameraByYear[$year] = $cameraData->where('tahun', $year)->first()->total ?? 0;
            $dvrByYear[$year] = $dvrData->where('tahun', $year)->first()->total ?? 0;
            $memoryByYear[$year] = $memoryData->where('tahun', $year)->first()->total ?? 0;
        }

        return [
            'tahun' => $allYears,
            'camera' => array_values($cameraByYear),
            'dvr' => array_values($dvrByYear),
            'memory' => array_values($memoryByYear),
        ];
    }

    private function getRataRataMemory()
    {
        $memoryData = ChecklistCctv::select('kapasitas_hdd')
            ->where('status_hdd', 'normal')
            ->get();

        $totalGB = 0;
        $count = $memoryData->count();

        foreach ($memoryData as $item) {
            $kapasitas = $item->kapasitas_hdd;
            if (strpos($kapasitas, 'TB') !== false) {
                $gb = floatval($kapasitas) * 1024;
            } elseif (strpos($kapasitas, 'GB') !== false) {
                $gb = floatval($kapasitas);
            } else {
                $gb = floatval($kapasitas) / 1024;
            }
            $totalGB += $gb;
        }

        $rataRata = $count > 0 ? $totalGB / $count : 0;

        if ($rataRata >= 1024) {
            $rataRataFormatted = number_format($rataRata / 1024, 2) . ' TB';
        } else {
            $rataRataFormatted = number_format($rataRata, 2) . ' GB';
        }

        $memoryDistribution = [
            '<= 1TB' => 0,
            '1-2TB' => 0,
            '2-4TB' => 0,
            '4-8TB' => 0,
            '> 8TB' => 0,
        ];

        foreach ($memoryData as $item) {
            $kapasitas = $item->kapasitas_hdd;
            if (strpos($kapasitas, 'TB') !== false) {
                $gb = floatval($kapasitas) * 1024;
            } else {
                $gb = floatval($kapasitas);
            }

            if ($gb <= 1024) {
                $memoryDistribution['<= 1TB']++;
            } elseif ($gb <= 2048) {
                $memoryDistribution['1-2TB']++;
            } elseif ($gb <= 4096) {
                $memoryDistribution['2-4TB']++;
            } elseif ($gb <= 8192) {
                $memoryDistribution['4-8TB']++;
            } else {
                $memoryDistribution['> 8TB']++;
            }
        }

        if ($count == 0) {
            return [
                'rata_rata' => 2048,
                'rata_rata_formatted' => '2 TB',
                'total_data' => 0,
                'distribusi' => [
                    '<= 1TB' => 5,
                    '1-2TB' => 10,
                    '2-4TB' => 8,
                    '4-8TB' => 3,
                    '> 8TB' => 2,
                ],
            ];
        }

        return [
            'rata_rata' => $rataRata,
            'rata_rata_formatted' => $rataRataFormatted,
            'total_data' => $count,
            'distribusi' => $memoryDistribution,
        ];
    }

    private function getLokasiSeringRusak()
    {
        $tahunSekarang = date('Y');
        $tahunMulai = $tahunSekarang - 5;

        $data = LaporanAktivitas::select(
            'lokasi_id',
            DB::raw('COUNT(*) as total_kerusakan')
        )
        ->where('jenis_aktivitas', 'perbaikan')
        ->whereYear('tanggal_laporan', '>=', $tahunMulai)
        ->with('lokasi')
        ->groupBy('lokasi_id')
        ->orderBy('total_kerusakan', 'desc')
        ->limit(15)
        ->get();

        if ($data->isEmpty()) {
            return [
                'labels' => ['Lokasi A', 'Lokasi B', 'Lokasi C', 'Lokasi D', 'Lokasi E'],
                'series' => [
                    '2020' => [10, 20, 30, 40, 50],
                    '2021' => [15, 25, 35, 45, 55],
                    '2022' => [20, 30, 40, 50, 60],
                    '2023' => [25, 35, 45, 55, 65],
                    '2024' => [30, 40, 50, 60, 70],
                ]
            ];
        }

        $labels = [];
        $series = [
            '2020' => [],
            '2021' => [],
            '2022' => [],
            '2023' => [],
            '2024' => [],
        ];

        foreach ($data as $item) {
            $labels[] = $item->lokasi->nama_lokasi ?? 'Unknown';
            for ($tahun = 2020; $tahun <= 2024; $tahun++) {
                $total = LaporanAktivitas::where('lokasi_id', $item->lokasi_id)
                    ->where('jenis_aktivitas', 'perbaikan')
                    ->whereYear('tanggal_laporan', $tahun)
                    ->count();
                $series[(string)$tahun][] = $total;
            }
        }

        return [
            'labels' => $labels,
            'series' => $series,
        ];
    }

    private function getGrafikStatus()
    {
        $data = LaporanAktivitas::select('status_pekerjaan', DB::raw('count(*) as total'))
            ->groupBy('status_pekerjaan')
            ->get();

        if ($data->isEmpty()) {
            return collect([
                (object) ['status_pekerjaan' => 'selesai', 'total' => 120],
                (object) ['status_pekerjaan' => 'pending', 'total' => 45],
                (object) ['status_pekerjaan' => 'proses', 'total' => 30],
            ]);
        }

        return $data;
    }

    private function getGrafikKerusakanLokasi()
    {
        $data = LaporanAktivitas::select(
            'lokasi_id',
            DB::raw('SUM(CASE WHEN checklist_camera = 1 THEN 1 ELSE 0 END) as camera_rusak'),
            DB::raw('SUM(CASE WHEN checklist_dvr = 1 THEN 1 ELSE 0 END) as dvr_rusak'),
            DB::raw('SUM(CASE WHEN checklist_monitor = 1 THEN 1 ELSE 0 END) as monitor_rusak'),
            DB::raw('SUM(CASE WHEN checklist_kabel_camera = 1 THEN 1 ELSE 0 END) as kabel_camera_rusak'),
            DB::raw('SUM(CASE WHEN checklist_kabel_listrik = 1 THEN 1 ELSE 0 END) as kabel_listrik_rusak'),
            DB::raw('SUM(CASE WHEN checklist_konektor = 1 THEN 1 ELSE 0 END) as konektor_rusak')
        )
        ->with('lokasi')
        ->groupBy('lokasi_id')
        ->get();

        $result = [];
        foreach ($data as $item) {
            $result[] = [
                'lokasi' => $item->lokasi->nama_lokasi ?? 'Unknown',
                'camera' => $item->camera_rusak,
                'dvr' => $item->dvr_rusak,
                'monitor' => $item->monitor_rusak,
                'kabel_camera' => $item->kabel_camera_rusak,
                'kabel_listrik' => $item->kabel_listrik_rusak,
                'konektor' => $item->konektor_rusak,
            ];
        }

        if (empty($result)) {
            return [
                ['lokasi' => 'Gedung A', 'camera' => 10, 'dvr' => 5, 'monitor' => 3, 'kabel_camera' => 8, 'kabel_listrik' => 4, 'konektor' => 6],
                ['lokasi' => 'Gedung B', 'camera' => 8, 'dvr' => 4, 'monitor' => 2, 'kabel_camera' => 6, 'kabel_listrik' => 3, 'konektor' => 5],
                ['lokasi' => 'Gudang', 'camera' => 5, 'dvr' => 2, 'monitor' => 1, 'kabel_camera' => 4, 'kabel_listrik' => 2, 'konektor' => 3],
            ];
        }

        return $result;
    }

    public function getDashboardData()
    {
        $data = [
            'kerusakanPerTahun' => $this->getKerusakanPerTahun(),
            'deviceRusakPerTahun' => $this->getDeviceRusakPerTahun(),
            'penambahanDevice' => $this->getPenambahanDevice(),
            'rataRataMemory' => $this->getRataRataMemory(),
            'grafikStatus' => $this->getGrafikStatus(),
            'grafikKerusakanLokasi' => $this->getGrafikKerusakanLokasi(),
            'dataBulanan' => $this->getDataBulanan(),
            'lokasiSeringRusak' => $this->getLokasiSeringRusak(),
        ];

        return response()->json($data);
    }
    
    public function exportEksekutifPdf()
    {
        // Cek akses
        if (!Auth::check() || !in_array(Auth::user()->role, ['admin', 'manajer'])) {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Ambil data eksekutif
        $data = [
            'totalLaporan' => LaporanAktivitas::count(),
            'totalLaporanSelesai' => LaporanAktivitas::where('status_pekerjaan', 'selesai')->count(),
            'totalLaporanPending' => LaporanAktivitas::where('status_pekerjaan', 'pending')->count(),
            'totalLaporanProses' => LaporanAktivitas::where('status_pekerjaan', 'proses')->count(),
            'persentaseSelesai' => LaporanAktivitas::count() > 0 ? round((LaporanAktivitas::where('status_pekerjaan', 'selesai')->count() / LaporanAktivitas::count()) * 100, 1) : 0,
            'performanceTeknisi' => $this->getPerformanceTeknisi(),
            'trendBulanan' => $this->getTrendBulanan(),
            'lokasiRawan' => $this->getLokasiRawan(),
            'inventarisStatus' => $this->getInventarisStatus(),
            'tanggal' => date('d F Y'),
            'nama' => Auth::user()->name,
        ];

        $pdf = PDF::loadView('dashboard.eksekutif-pdf', compact('data'));
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->download('dashboard-eksekutif-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export Dashboard Eksekutif ke Excel
     */
    public function exportEksekutifExcel()
    {
        // Cek akses
        if (!Auth::check() || !in_array(Auth::user()->role, ['admin', 'manajer'])) {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        return Excel::download(new DashboardEksekutifExport, 'dashboard-eksekutif-' . date('Y-m-d') . '.xlsx');
    }
}