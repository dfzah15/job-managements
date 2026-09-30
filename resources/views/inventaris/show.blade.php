@extends('layouts.app')

@section('title', 'Detail Inventaris')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3><i class="bi bi-box"></i> Detail Inventaris</h3>
    <div>
        <a href="{{ route('inventaris.edit', $inventaris->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('inventaris.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <!-- Informasi Utama -->
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">
                <h6><i class="bi bi-info-circle"></i> Informasi Utama</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="35%">Nama Inventaris</th>
                        <td><strong>{{ $inventaris->nama_inventaris }}</strong></td>
                    </tr>
                    <tr>
                        <th>Lokasi</th>
                        <td>
                            <span class="badge bg-info">{{ $inventaris->lokasi->kode_lokasi ?? '-' }}</span>
                            {{ $inventaris->lokasi->nama_lokasi ?? '-' }}
                        </td>
                    </tr>
                    <tr>
                        <th>Jenis</th>
                        <td>
                            <span class="badge bg-secondary">{{ strtoupper($inventaris->jenis) }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Merk</th>
                        <td>{{ $inventaris->merk ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Model</th>
                        <td>{{ $inventaris->model ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Jumlah</th>
                        <td><span class="badge bg-primary">{{ $inventaris->jumlah }}</span></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @php
                                $statusColor = [
                                    'aktif' => 'success',
                                    'rusak' => 'danger',
                                    'perbaikan' => 'warning',
                                    'nonaktif' => 'secondary'
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusColor[$inventaris->status] ?? 'secondary' }}">
                                {{ ucfirst($inventaris->status) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Tanggal Pemasangan</th>
                        <td>{{ $inventaris->tanggal_pemasangan ? \Carbon\Carbon::parse($inventaris->tanggal_pemasangan)->format('d/m/Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Dibuat</th>
                        <td>{{ $inventaris->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Terakhir Update</th>
                        <td>{{ $inventaris->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Spesifikasi -->
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">
                <h6><i class="bi bi-gear"></i> Spesifikasi</h6>
            </div>
            <div class="card-body">
                @if($inventaris->spesifikasi)
                    <div class="p-3 bg-light rounded">
                        {!! nl2br(e($inventaris->spesifikasi)) !!}
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-file-earmark-text fs-1"></i>
                        <p class="mt-2">Belum ada spesifikasi</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Statistik -->
        <div class="card mt-3">
            <div class="card-header">
                <h6><i class="bi bi-bar-chart"></i> Statistik</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h4>{{ $inventaris->checklistCctv->count() }}</h4>
                        <small class="text-muted">Total Checklist</small>
                    </div>
                    <div class="col-6">
                        <h4>{{ $inventaris->checklistCctv->where('status_hdd', 'tidak_normal')->count() }}</h4>
                        <small class="text-muted">Masalah Terdeteksi</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Checklist -->
    @if($inventaris->checklistCctv->count() > 0)
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6><i class="bi bi-list-check"></i> Riwayat Checklist</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Petugas</th>
                                <th>Status HDD</th>
                                <th>Display</th>
                                <th>Kamera Mati</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($inventaris->checklistCctv->take(5) as $check)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($check->tanggal_check)->format('d/m/Y') }}</td>
                                <td>{{ $check->petugas_check }}</td>
                                <td>
                                    <span class="badge bg-{{ $check->status_hdd == 'normal' ? 'success' : 'danger' }}">
                                        {{ $check->status_hdd }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $check->status_display == 'tampil' ? 'success' : 'warning' }}">
                                        {{ $check->status_display }}
                                    </span>
                                </td>
                                <td>
                                    @if($check->jumlah_kamera_mati > 0)
                                        <span class="badge bg-danger">{{ $check->jumlah_kamera_mati }}</span>
                                    @else
                                        <span class="badge bg-success">0</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($inventaris->checklistCctv->count() > 5)
                    <div class="text-center">
                        <a href="{{ route('checklist.index', ['inventaris_id' => $inventaris->id]) }}" 
                           class="btn btn-sm btn-link">
                            Lihat semua ({{ $inventaris->checklistCctv->count() }})
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>
@endsection