@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')


<!-- ============================================ -->
<!-- TOGGLE MODE DASHBOARD (Untuk Admin & Manajer) -->
<!-- ============================================ -->
@if($isEksekutif)
<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <i class="bi bi-eye fs-4 me-2"></i>
                    <span class="fw-bold">Mode Dashboard:</span>
                    <span class="badge bg-{{ $mode == 'eksekutif' ? 'primary' : 'secondary' }} ms-2">
                        {{ $mode == 'eksekutif' ? '📊 Eksekutif' : '📋 Standar' }}
                    </span>
                </div>
                <div class="d-flex gap-2">
                    <!-- Tombol Export PDF & Excel (hanya di mode eksekutif) -->
                    @if($mode == 'eksekutif')
                    <a href="{{ route('dashboard.eksekutif.pdf') }}" class="btn btn-danger">
                        <i class="bi bi-file-pdf"></i> Export PDF
                    </a>
                    <a href="{{ route('dashboard.eksekutif.excel') }}" class="btn btn-success">
                        <i class="bi bi-file-excel"></i> Export Excel
                    </a>
                    @endif
                    <a href="{{ route('dashboard', ['mode' => $mode == 'eksekutif' ? 'default' : 'eksekutif']) }}" 
                       class="btn btn-{{ $mode == 'eksekutif' ? 'secondary' : 'primary' }}">
                        <i class="bi bi-{{ $mode == 'eksekutif' ? 'eye-slash' : 'graph-up-arrow' }}"></i>
                        {{ $mode == 'eksekutif' ? 'Switch ke Standar' : 'Switch ke Eksekutif' }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- ============================================ -->
<!-- MODE EKSEKUTIF (Khusus Admin & Manajer) -->
<!-- ============================================ -->
@if($isEksekutif && $mode == 'eksekutif')

    <!-- STATISTIK EKSEKUTIF -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-stats bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Total Laporan</h6>
                            <h2 class="mb-0">{{ $totalLaporan }}</h2>
                        </div>
                        <i class="bi bi-file-earmark-text fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-stats bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Selesai</h6>
                            <h2 class="mb-0">{{ $totalLaporan - $laporanPending }}</h2>
                        </div>
                        <i class="bi bi-check-circle fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-stats bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Pending</h6>
                            <h2 class="mb-0">{{ $laporanPending }}</h2>
                        </div>
                        <i class="bi bi-clock fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-stats bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Persentase Selesai</h6>
                            <h2 class="mb-0">{{ $totalLaporan > 0 ? round((($totalLaporan - $laporanPending) / $totalLaporan) * 100, 1) : 0 }}%</h2>
                        </div>
                        <i class="bi bi-graph-up-arrow fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PERFORMANCE TEKNISI -->
    <div class="row">
        <div class="col-xl-8 mb-3">
            <div class="card">
                <div class="card-header">
                    <h6><i class="bi bi-trophy"></i> Performance Teknisi</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Teknisi</th>
                                    <th>Total</th>
                                    <th>Selesai</th>
                                    <th>Proses</th>
                                    <th>Pending</th>
                                    <th>Efisiensi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($eksekutifData['performanceTeknisi'] as $teknis)
                                @php
                                    $efisiensi = $teknis->total_eksekusi > 0 ? round(($teknis->selesai / $teknis->total_eksekusi) * 100) : 0;
                                    $warna = $efisiensi >= 80 ? 'success' : ($efisiensi >= 50 ? 'warning' : 'danger');
                                @endphp
                                <tr>
                                    <td>{{ $teknis->user->name ?? 'Unknown' }}</td>
                                    <td><span class="badge bg-secondary">{{ $teknis->total_eksekusi }}</span></td>
                                    <td><span class="badge bg-success">{{ $teknis->selesai }}</span></td>
                                    <td><span class="badge bg-info">{{ $teknis->proses }}</span></td>
                                    <td><span class="badge bg-warning">{{ $teknis->pending }}</span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2" style="height: 6px; width: 100px;">
                                                <div class="progress-bar bg-{{ $warna }}" style="width: {{ $efisiensi }}%"></div>
                                            </div>
                                            <span class="fw-bold">{{ $efisiensi }}%</span>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="text-center">Belum ada data</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 mb-3">
            <div class="card">
                <div class="card-header">
                    <h6><i class="bi bi-pie-chart"></i> Inventaris Status</h6>
                </div>
                <div class="card-body">
                    <canvas id="inventarisStatusChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- TREND & LOKASI RAWAN -->
    <div class="row">
        <div class="col-xl-8 mb-3">
            <div class="card">
                <div class="card-header">
                    <h6><i class="bi bi-graph-up"></i> Trend Laporan 6 Bulan</h6>
                </div>
                <div class="card-body">
                    <canvas id="trendChart" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4 mb-3">
            <div class="card">
                <div class="card-header">
                    <h6><i class="bi bi-exclamation-triangle"></i> 5 Lokasi Paling Rawan</h6>
                </div>
                <div class="card-body">
                    @forelse($eksekutifData['lokasiRawan'] as $item)
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span>{{ $item->lokasi->nama_lokasi ?? 'Unknown' }}</span>
                        <span class="badge bg-danger">{{ $item->total }}</span>
                    </div>
                    @empty
                    <div class="text-center">Belum ada data</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

<!-- ============================================ -->
<!-- MODE DEFAULT (Semua User) -->
<!-- ============================================ -->
@else

    <!-- STATISTIK DEFAULT -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-stats bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Total Lokasi</h6>
                            <h2 class="mb-0">{{ $totalLokasi }}</h2>
                        </div>
                        <i class="bi bi-geo-alt fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-stats bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Total Inventaris</h6>
                            <h2 class="mb-0">{{ $totalInventaris }}</h2>
                        </div>
                        <i class="bi bi-box fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-stats bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Total Staff</h6>
                            <h2 class="mb-0">{{ $totalStaff }}</h2>
                            <small>Teknisi: {{ $totalTeknisi }} | Admin: {{ $totalAdmin }}</small>
                        </div>
                        <i class="bi bi-people fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-stats bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Laporan Pending</h6>
                            <h2 class="mb-0">{{ $laporanPending }}</h2>
                        </div>
                        <i class="bi bi-clock fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- GRAFIK BULANAN -->
    <div class="row">
        <div class="col-xl-8 mb-3">
            <div class="card">
                <div class="card-header">
                    <h6><i class="bi bi-calendar3"></i> Grafik Bulanan 2020-2021</h6>
                </div>
                <div class="card-body">
                    <canvas id="bulananChart" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4 mb-3">
            <div class="card">
                <div class="card-header">
                    <h6><i class="bi bi-hdd-stack"></i> Rata-rata Memory</h6>
                </div>
                <div class="card-body text-center">
                    <h2 class="text-primary">{{ $rataRataMemory['rata_rata_formatted'] }}</h2>
                    <small class="text-muted">Dari {{ $rataRataMemory['total_data'] }} data HDD</small>
                    <hr>
                    <div class="row g-1">
                        @foreach($rataRataMemory['distribusi'] as $label => $value)
                        <div class="col">
                            <div class="border rounded p-1">
                                <small class="text-muted d-block">{{ $label }}</small>
                                <strong>{{ $value }}</strong>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- GRAFIK LOKASI SERING RUSAK -->
    <div class="row">
        <div class="col-xl-8 mb-3">
            <div class="card">
                <div class="card-header">
                    <h6><i class="bi bi-building"></i> Lokasi Sering Rusak (5 Tahun)</h6>
                </div>
                <div class="card-body">
                    <canvas id="lokasiSeringRusakChart" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4 mb-3">
            <div class="card">
                <div class="card-header">
                    <h6><i class="bi bi-pie-chart"></i> Status Pekerjaan</h6>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- GRAFIK LAINNYA -->
    <div class="row">
        <div class="col-xl-6 mb-3">
            <div class="card">
                <div class="card-header">
                    <h6><i class="bi bi-graph-up"></i> Kerusakan Per Tahun</h6>
                </div>
                <div class="card-body">
                    <canvas id="kerusakanTahunChart" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6 mb-3">
            <div class="card">
                <div class="card-header">
                    <h6><i class="bi bi-device-hdd"></i> Device Rusak Per Tahun</h6>
                </div>
                <div class="card-body">
                    <canvas id="deviceRusakChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- TABEL RINGKASAN -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6><i class="bi bi-table"></i> Ringkasan Kerusakan per Lokasi</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Lokasi</th>
                                    <th class="text-center">Camera</th>
                                    <th class="text-center">DVR</th>
                                    <th class="text-center">Monitor</th>
                                    <th class="text-center">Kabel Camera</th>
                                    <th class="text-center">Kabel Listrik</th>
                                    <th class="text-center">Konektor</th>
                                    <th class="text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($grafikKerusakanLokasi as $data)
                                <tr>
                                    <td><strong>{{ $data['lokasi'] }}</strong></td>
                                    <td class="text-center">{{ $data['camera'] }}</td>
                                    <td class="text-center">{{ $data['dvr'] }}</td>
                                    <td class="text-center">{{ $data['monitor'] }}</td>
                                    <td class="text-center">{{ $data['kabel_camera'] }}</td>
                                    <td class="text-center">{{ $data['kabel_listrik'] }}</td>
                                    <td class="text-center">{{ $data['konektor'] }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">
                                            {{ $data['camera'] + $data['dvr'] + $data['monitor'] + $data['kabel_camera'] + $data['kabel_listrik'] + $data['konektor'] }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="8" class="text-center">Belum ada data</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

    @if($isEksekutif && $mode == 'eksekutif')
    // ============================================
    // CHART EKSEKUTIF
    // ============================================

    // Inventaris Status Chart
    const invData = @json($eksekutifData['inventarisStatus']);
    const invColors = {
        'aktif': '#2ecc71',
        'rusak': '#e74c3c',
        'perbaikan': '#f39c12',
        'nonaktif': '#95a5a6'
    };
    new Chart(document.getElementById('inventarisStatusChart'), {
        type: 'doughnut',
        data: {
            labels: invData.map(item => item.status),
            datasets: [{
                data: invData.map(item => item.total),
                backgroundColor: invData.map(item => invColors[item.status] || '#95a5a6'),
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12 } }
            },
            cutout: '70%'
        }
    });

    // Trend Chart
    const trendData = @json($eksekutifData['trendBulanan']);
    new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: {
            labels: trendData.map(item => item.bulan),
            datasets: [
                {
                    label: 'Total',
                    data: trendData.map(item => item.total),
                    borderColor: '#3498db',
                    backgroundColor: 'rgba(52, 152, 219, 0.1)',
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Selesai',
                    data: trendData.map(item => item.selesai),
                    borderColor: '#2ecc71',
                    backgroundColor: 'rgba(46, 204, 113, 0.1)',
                    fill: true,
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'top' } },
            scales: { y: { beginAtZero: true } }
        }
    });

    @else

    // ============================================
    // CHART DEFAULT (Sama seperti sebelumnya)
    // ============================================

    // Bulanan Chart
    const bulananData = @json($dataBulanan);
    new Chart(document.getElementById('bulananChart'), {
        type: 'line',
        data: {
            labels: bulananData.bulan,
            datasets: [
                {
                    label: '2020',
                    data: bulananData.tahun_2020,
                    borderColor: '#3498db',
                    backgroundColor: 'rgba(52, 152, 219, 0.1)',
                    fill: true,
                    tension: 0.4
                },
                {
                    label: '2021',
                    data: bulananData.tahun_2021,
                    borderColor: '#e74c3c',
                    backgroundColor: 'rgba(231, 76, 60, 0.1)',
                    fill: true,
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'top' } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 500 } },
                x: { grid: { display: false } }
            }
        }
    });

    // Lokasi Sering Rusak Chart
    const lokasiData = @json($lokasiSeringRusak);
    const colors = {
        '2020': 'rgba(52, 152, 219, 0.8)',
        '2021': 'rgba(231, 76, 60, 0.8)',
        '2022': 'rgba(46, 204, 113, 0.8)',
        '2023': 'rgba(241, 196, 15, 0.8)',
        '2024': 'rgba(155, 89, 182, 0.8)',
    };
    const datasets = [];
    Object.keys(lokasiData.series).sort().forEach((tahun) => {
        datasets.push({
            label: tahun,
            data: lokasiData.series[tahun],
            backgroundColor: colors[tahun] || 'rgba(149, 165, 166, 0.8)',
            borderColor: '#fff',
            borderWidth: 1,
            borderRadius: 4
        });
    });
    new Chart(document.getElementById('lokasiSeringRusakChart'), {
        type: 'bar',
        data: { labels: lokasiData.labels, datasets: datasets },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top', labels: { boxWidth: 15, usePointStyle: true } }
            },
            scales: {
                x: { grid: { display: false }, ticks: { maxRotation: 45, font: { size: 10 } } },
                y: { beginAtZero: true, ticks: { stepSize: 10 } }
            }
        }
    });

    // Status Chart
    const statusData = @json($grafikStatus);
    const statusColors = { 'selesai': '#2ecc71', 'pending': '#f39c12', 'proses': '#3498db' };
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: statusData.map(item => item.status_pekerjaan),
            datasets: [{
                data: statusData.map(item => item.total),
                backgroundColor: statusData.map(item => statusColors[item.status_pekerjaan] || '#95a5a6'),
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 12 } } },
            cutout: '65%'
        }
    });

    // Kerusakan Per Tahun
    const kerusakanData = @json($kerusakanPerTahun);
    new Chart(document.getElementById('kerusakanTahunChart'), {
        type: 'bar',
        data: {
            labels: kerusakanData.tahun,
            datasets: [
                {
                    label: 'Kasus Kerusakan',
                    data: kerusakanData.total_kerusakan,
                    backgroundColor: 'rgba(231, 76, 60, 0.7)',
                    borderColor: '#e74c3c',
                    borderWidth: 1
                },
                {
                    label: 'Unit Rusak',
                    data: kerusakanData.total_rusak,
                    backgroundColor: 'rgba(52, 152, 219, 0.7)',
                    borderColor: '#3498db',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'top' } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 50 } },
                x: { grid: { display: false } }
            }
        }
    });

    // Device Rusak Chart
    const deviceData = @json($deviceRusakPerTahun);
    new Chart(document.getElementById('deviceRusakChart'), {
        type: 'bar',
        data: {
            labels: deviceData.tahun,
            datasets: [
                {
                    label: 'Camera Rusak',
                    data: deviceData.camera_rusak,
                    backgroundColor: 'rgba(231, 76, 60, 0.7)',
                    borderColor: '#e74c3c',
                    borderWidth: 1
                },
                {
                    label: 'HDD Rusak',
                    data: deviceData.hdd_rusak,
                    backgroundColor: 'rgba(241, 196, 15, 0.7)',
                    borderColor: '#f1c40f',
                    borderWidth: 1
                },
                {
                    label: 'Display Rusak',
                    data: deviceData.display_rusak,
                    backgroundColor: 'rgba(52, 152, 219, 0.7)',
                    borderColor: '#3498db',
                    borderWidth: 1
                },
                {
                    label: 'No Display',
                    data: deviceData.no_display,
                    backgroundColor: 'rgba(155, 89, 182, 0.7)',
                    borderColor: '#9b59b6',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'top', labels: { boxWidth: 12, font: { size: 10 } } } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 10 } },
                x: { grid: { display: false } }
            }
        }
    });

    @endif

});
</script>
@endpush

@push('styles')
<style>
    .card-stats {
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        transition: transform 0.2s;
        border: none;
    }
    .card-stats:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .card-stats .card-body {
        padding: 18px 20px;
    }
    .card-stats h2 {
        font-weight: 700;
        font-size: 1.8rem;
    }
    .card-stats .fs-1 {
        font-size: 2.2rem !important;
        opacity: 0.4;
    }
    .card {
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        border: none;
    }
    .card-header {
        background: transparent;
        border-bottom: 1px solid #f0f0f0;
        padding: 12px 18px;
    }
    .card-header h6 {
        margin: 0;
        font-weight: 600;
        color: #2c3e50;
        font-size: 0.9rem;
    }
    .card-header h6 i {
        margin-right: 6px;
        color: #3498db;
    }
    .card-body {
        padding: 15px 18px;
    }
    canvas {
        max-height: 200px;
        min-height: 150px;
    }
    .table-sm td, .table-sm th {
        padding: 5px 10px;
        font-size: 0.85rem;
    }
    .table-sm .badge {
        font-size: 0.75rem;
        padding: 3px 8px;
    }
    .bg-primary { background: linear-gradient(135deg, #4e73df, #224abe) !important; }
    .bg-success { background: linear-gradient(135deg, #1cc88a, #13855c) !important; }
    .bg-info { background: linear-gradient(135deg, #36b9cc, #258391) !important; }
    .bg-warning { background: linear-gradient(135deg, #f6c23e, #dda20a) !important; }
</style>
@endpush