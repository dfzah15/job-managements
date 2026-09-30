@extends('layouts.app')

@section('title', 'Dashboard Eksekutif')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-graph-up-arrow"></i> Dashboard Eksekutif</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Statistik -->
                    <div class="col-md-3 mb-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h6>Total Laporan</h6>
                                <h2>{{ $totalLaporan ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h6>Selesai</h6>
                                <h2>{{ $totalLaporanSelesai ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <h6>Pending</h6>
                                <h2>{{ $totalLaporanPending ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h6>Proses</h6>
                                <h2>{{ $totalLaporanProses ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Teknisi -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h6>Performance Teknisi</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Teknisi</th>
                                        <th>Total</th>
                                        <th>Selesai</th>
                                        <th>Proses</th>
                                        <th>Pending</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($performanceTeknisi ?? [] as $teknis)
                                    <tr>
                                        <td>{{ $teknis->user->name ?? 'Unknown' }}</td>
                                        <td>{{ $teknis->total_eksekusi }}</td>
                                        <td>{{ $teknis->selesai }}</td>
                                        <td>{{ $teknis->proses }}</td>
                                        <td>{{ $teknis->pending }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Belum ada data</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Grafik sederhana -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6>Trend Laporan 6 Bulan</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="trendChart" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6>Lokasi Paling Rawan</h6>
                            </div>
                            <div class="card-body">
                                @forelse($lokasiRawan ?? [] as $item)
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
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartData = @json($chartData ?? []);
    
    if (chartData.bulan && chartData.bulan.length > 0) {
        new Chart(document.getElementById('trendChart'), {
            type: 'line',
            data: {
                labels: chartData.bulan,
                datasets: [
                    {
                        label: 'Total',
                        data: chartData.total,
                        borderColor: '#3498db',
                        backgroundColor: 'rgba(52, 152, 219, 0.1)',
                        fill: true
                    },
                    {
                        label: 'Selesai',
                        data: chartData.selesai,
                        borderColor: '#2ecc71',
                        backgroundColor: 'rgba(46, 204, 113, 0.1)',
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }
});
</script>
@endpush