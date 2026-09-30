<?php

namespace App\Exports;

use App\Models\LaporanAktivitas;
use App\Models\LaporanEksekusi;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DashboardEksekutifExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    public function array(): array
    {
        $data = [];
        
        // Header info
        $data[] = ['LAPORAN DASHBOARD EKSEKUTIF'];
        $data[] = ['Dicetak: ' . date('d F Y H:i')];
        $data[] = [];
        
        // Statistik Utama
        $data[] = ['STATISTIK UTAMA'];
        $data[] = ['Total Laporan', LaporanAktivitas::count()];
        $data[] = ['Selesai', LaporanAktivitas::where('status_pekerjaan', 'selesai')->count()];
        $data[] = ['Pending', LaporanAktivitas::where('status_pekerjaan', 'pending')->count()];
        $data[] = ['Proses', LaporanAktivitas::where('status_pekerjaan', 'proses')->count()];
        $data[] = [];
        
        // Performance Teknisi
        $data[] = ['PERFORMANCE TEKNISI'];
        $data[] = ['Teknisi', 'Total Eksekusi', 'Selesai', 'Proses', 'Pending'];
        
        $performance = LaporanEksekusi::select(
            'user_id',
            \Illuminate\Support\Facades\DB::raw('COUNT(*) as total_eksekusi'),
            \Illuminate\Support\Facades\DB::raw('SUM(CASE WHEN status_eksekusi = "selesai" THEN 1 ELSE 0 END) as selesai'),
            \Illuminate\Support\Facades\DB::raw('SUM(CASE WHEN status_eksekusi = "proses" THEN 1 ELSE 0 END) as proses'),
            \Illuminate\Support\Facades\DB::raw('SUM(CASE WHEN status_eksekusi = "pending" THEN 1 ELSE 0 END) as pending')
        )
        ->with('user')
        ->groupBy('user_id')
        ->having('total_eksekusi', '>', 0)
        ->orderBy('selesai', 'desc')
        ->get();

        foreach ($performance as $item) {
            $data[] = [
                $item->user->name ?? 'Unknown',
                $item->total_eksekusi,
                $item->selesai,
                $item->proses,
                $item->pending,
            ];
        }
        $data[] = [];
        
        // Lokasi Rawan
        $data[] = ['5 LOKASI PALING RAWAN'];
        $data[] = ['Lokasi', 'Total Laporan'];
        
        $lokasiRawan = LaporanAktivitas::select(
            'lokasi_id',
            \Illuminate\Support\Facades\DB::raw('COUNT(*) as total')
        )
        ->with('lokasi')
        ->groupBy('lokasi_id')
        ->orderBy('total', 'desc')
        ->limit(5)
        ->get();

        foreach ($lokasiRawan as $item) {
            $data[] = [
                $item->lokasi->nama_lokasi ?? 'Unknown',
                $item->total,
            ];
        }
        $data[] = [];
        
        // Inventaris Status
        $data[] = ['STATUS INVENTARIS'];
        $data[] = ['Status', 'Jumlah'];
        
        $inventarisStatus = \App\Models\Inventaris::select('status', \Illuminate\Support\Facades\DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get();

        foreach ($inventarisStatus as $item) {
            $data[] = [
                ucfirst($item->status),
                $item->total,
            ];
        }
        
        return $data;
    }

    public function headings(): array
    {
        return [];
    }

    public function styles(Worksheet $sheet)
    {
        // Style untuk title
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        
        // Style untuk sub header
        $sheet->getStyle('A4')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A11')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A19')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A24')->getFont()->setBold(true)->setSize(12);
        
        // Style untuk header tabel
        $sheet->getStyle('A5:E5')->getFont()->setBold(true);
        $sheet->getStyle('A12:E12')->getFont()->setBold(true);
        $sheet->getStyle('A20:B20')->getFont()->setBold(true);
        $sheet->getStyle('A25:B25')->getFont()->setBold(true);
        
        // Border untuk data
        $sheet->getStyle('A5:E' . $sheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    }
}