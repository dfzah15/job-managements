<?php

namespace App\Exports;

use App\Models\LaporanAktivitas;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanAktivitasExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = LaporanAktivitas::with('lokasi');

        if (!empty($this->filters['lokasi_id'])) {
            $query->where('lokasi_id', $this->filters['lokasi_id']);
        }

        if (!empty($this->filters['status'])) {
            $query->where('status_pekerjaan', $this->filters['status']);
        }

        if (!empty($this->filters['date_from'])) {
            $query->whereDate('tanggal_laporan', '>=', $this->filters['date_from']);
        }

        if (!empty($this->filters['date_to'])) {
            $query->whereDate('tanggal_laporan', '<=', $this->filters['date_to']);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Lokasi',
            'Jenis Aktivitas',
            'Keterangan',
            'Checklist Camera',
            'Checklist DVR',
            'Checklist Monitor',
            'Checklist Kabel Camera',
            'Checklist Kabel Listrik',
            'Checklist Konektor',
            'Kendala Kerusakan',
            'Jumlah Rusak',
            'Status Pekerjaan',
            'Solusi',
            'Tanggal Laporan',
            'Pelapor',
            'Teknisi',
            'Dibuat Tanggal'
        ];
    }

    public function map($laporan): array
    {
        return [
            $laporan->id,
            $laporan->lokasi->nama_lokasi ?? '-',
            $laporan->jenis_aktivitas,
            $laporan->keterangan,
            $laporan->checklist_camera ? 'Ya' : 'Tidak',
            $laporan->checklist_dvr ? 'Ya' : 'Tidak',
            $laporan->checklist_monitor ? 'Ya' : 'Tidak',
            $laporan->checklist_kabel_camera ? 'Ya' : 'Tidak',
            $laporan->checklist_kabel_listrik ? 'Ya' : 'Tidak',
            $laporan->checklist_konektor ? 'Ya' : 'Tidak',
            $laporan->kendala_kerusakan,
            $laporan->jumlah_rusak,
            $laporan->status_pekerjaan,
            $laporan->solusi,
            $laporan->tanggal_laporan->format('d/m/Y'),
            $laporan->pelapor,
            $laporan->teknisi,
            $laporan->created_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}