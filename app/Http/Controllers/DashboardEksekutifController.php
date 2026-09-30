<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanAktivitas;
use App\Models\LaporanEksekusi;
use App\Models\Inventaris;
use App\Models\User;
use App\Models\ChecklistCctv;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardEksekutifController extends Controller
{
    public function index()
    {
        try {
            // ============================================
            // 1. STATISTIK UTAMA
            // ============================================
            $totalLaporan = LaporanAktivitas::count();
            $totalLaporanSelesai = LaporanAktivitas::where('status_pekerjaan', 'selesai')->count();
            $totalLaporanPending = LaporanAktivitas::where('status_pekerjaan', 'pending')->count();
            $totalLaporanProses = LaporanAktivitas::where('status_pekerjaan', 'proses')->count();
            
            $persentaseSelesai = $totalLaporan > 0 ? round(($totalLaporanSelesai / $totalLaporan) * 100, 1) : 0;

            // ============================================
            // 2. PERFORMANCE TEKNISI
            // ============================================
            $performanceTeknisi = LaporanEksekusi::select(
                'user_id',
                DB::raw('COUNT(*) as total_eksekusi'),
                DB::raw('SUM(CASE WHEN status_eksekusi = "selesai" THEN 1 ELSE 0 END) as selesai'),
                DB::raw('SUM(CASE WHEN status_eksekusi = "proses" THEN 1 ELSE 0 END) as proses'),
                DB::raw('SUM(CASE WHEN status_eksekusi = "pending" THEN 1 ELSE 0 END) as pending'),
                DB::raw('AVG(TIMESTAMPDIFF(HOUR, waktu_mulai, waktu_selesai)) as rata_rata_waktu')
            )
            ->with('user')
            ->groupBy('user_id')
            ->having('total_eksekusi', '>', 0)
            ->orderBy('selesai', 'desc')
            ->get();

            // ============================================
            // 3. TREND BULANAN (6 bulan terakhir)
            // ============================================
            $trendBulanan = LaporanAktivitas::select(
                DB::raw('DATE_FORMAT(tanggal_laporan, "%Y-%m") as bulan'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status_pekerjaan = "selesai" THEN 1 ELSE 0 END) as selesai')
            )
            ->where('tanggal_laporan', '>=', now()->subMonths(6))
            ->groupBy('bulan')
            ->orderBy('bulan', 'asc')
            ->get();

            // ============================================
            // 4. KERUSAKAN PER JENIS
            // ============================================
            $kerusakanPerJenis = LaporanAktivitas::select(
                'jenis_aktivitas',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('jenis_aktivitas')
            ->orderBy('total', 'desc')
            ->get();

            // ============================================
            // 5. LOKASI RAWAN (Top 5)
            // ============================================
            $lokasiRawan = LaporanAktivitas::select(
                'lokasi_id',
                DB::raw('COUNT(*) as total')
            )
            ->with('lokasi')
            ->groupBy('lokasi_id')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

            // ============================================
            // 6. RATA-RATA WAKTU PENYELESAIAN
            // ============================================
            $rataWaktu = LaporanEksekusi::whereNotNull('waktu_selesai')
                ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, waktu_mulai, waktu_selesai)) as rata_rata'))
                ->first();

            // ============================================
            // 7. INVENTARIS STATUS
            // ============================================
            $inventarisStatus = Inventaris::select('status', DB::raw('COUNT(*) as total'))
                ->groupBy('status')
                ->get();

            // ============================================
            // 8. DATA UNTUK GRAFIK
            // ============================================
            $chartData = [
                'bulan' => $trendBulanan->pluck('bulan'),
                'total' => $trendBulanan->pluck('total'),
                'selesai' => $trendBulanan->pluck('selesai'),
            ];

            // ============================================
            // 9. PERFORMANCE BULANAN
            // ============================================
            $performanceBulanan = LaporanEksekusi::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as bulan'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status_eksekusi = "selesai" THEN 1 ELSE 0 END) as selesai')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('bulan')
            ->orderBy('bulan', 'asc')
            ->get();

            return view('dashboard.eksekutif', compact(
                'totalLaporan',
                'totalLaporanSelesai',
                'totalLaporanPending',
                'totalLaporanProses',
                'persentaseSelesai',
                'performanceTeknisi',
                'chartData',
                'kerusakanPerJenis',
                'lokasiRawan',
                'rataWaktu',
                'inventarisStatus',
                'performanceBulanan'
            ));

        } catch (\Exception $e) {
            // Log error
            Log::error('Dashboard Eksekutif Error: ' . $e->getMessage());
            
            // Jika error, redirect ke dashboard biasa
            return redirect()->route('dashboard')
                ->with('error', 'Terjadi kesalahan pada Dashboard Eksekutif: ' . $e->getMessage());
        }
    }

    /**
     * Export Laporan Eksekutif ke PDF
     */
    public function exportPdf()
    {
        try {
            $data = $this->getDataForExport();
            $pdf = PDF::loadView('dashboard.eksekutif-pdf', compact('data'));
            return $pdf->download('laporan-eksekutif-' . date('Y-m-d') . '.pdf');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.eksekutif')
                ->with('error', 'Gagal export PDF: ' . $e->getMessage());
        }
    }

    private function getDataForExport()
    {
        return [
            'total_laporan' => LaporanAktivitas::count(),
            'total_selesai' => LaporanAktivitas::where('status_pekerjaan', 'selesai')->count(),
            'performance_teknisi' => LaporanEksekusi::select(
                'user_id',
                DB::raw('COUNT(*) as total_eksekusi'),
                DB::raw('SUM(CASE WHEN status_eksekusi = "selesai" THEN 1 ELSE 0 END) as selesai')
            )
            ->with('user')
            ->groupBy('user_id')
            ->get(),
            'trend_bulanan' => LaporanAktivitas::select(
                DB::raw('DATE_FORMAT(tanggal_laporan, "%Y-%m") as bulan'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('bulan')
            ->orderBy('bulan', 'desc')
            ->limit(6)
            ->get(),
        ];
    }
}