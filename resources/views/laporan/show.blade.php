@extends('layouts.app')

@section('title', 'Detail Laporan Aktivitas - ' . ucfirst($jobdesk->name))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>
        <i class="{{ $jobdesk->icon }}"></i> 
        Detail Laporan Aktivitas {{ ucfirst($jobdesk->name) }}
    </h3>
    <div>
        <a href="{{ route('laporan.edit', [$jobdesk->slug, $laporan->id]) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('laporan.index', $jobdesk->slug) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <!-- Informasi Utama -->
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">
                <h6><i class="bi bi-info-circle"></i> Informasi Laporan</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="35%">ID Laporan</th>
                        <td><span class="badge bg-primary">#{{ $laporan->id }}</span></td>
                    </tr>
                    <tr>
                        <th>Lokasi</th>
                        <td>
                            <span class="badge bg-info">{{ $laporan->lokasi->kode_lokasi ?? '-' }}</span>
                            {{ $laporan->lokasi->nama_lokasi ?? '-' }}
                        </td>
                    </tr>
                    <tr>
                        <th>Jenis Aktivitas</th>
                        <td>
                            <span class="badge bg-{{ $laporan->jenis_aktivitas == 'perbaikan' ? 'danger' : ($laporan->jenis_aktivitas == 'pemeliharaan' ? 'warning' : 'info') }}">
                                {{ ucfirst($laporan->jenis_aktivitas) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Keterangan</th>
                        <td><strong>{{ $laporan->keterangan }}</strong></td>
                    </tr>
                    <tr>
                        <th>Tanggal Laporan</th>
                        <td>{{ $laporan->tanggal_laporan->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <th>Pelapor</th>
                        <td>{{ $laporan->pelapor }}</td>
                    </tr>
                    <tr>
                        <th>Teknisi</th>
                        <td>{{ $laporan->teknisi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Status Pekerjaan</th>
                        <td>
                            @php
                                $statusClass = [
                                    'selesai' => 'success',
                                    'pending' => 'warning',
                                    'proses' => 'info'
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusClass[$laporan->status_pekerjaan] ?? 'secondary' }}">
                                {{ ucfirst($laporan->status_pekerjaan) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Jumlah Rusak</th>
                        <td>
                            @if($laporan->jumlah_rusak > 0)
                                <span class="badge bg-danger">{{ $laporan->jumlah_rusak }}</span>
                            @else
                                <span class="badge bg-success">0</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Dibuat</th>
                        <td>{{ $laporan->created_at->format('d F Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Terakhir Update</th>
                        <td>{{ $laporan->updated_at->format('d F Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Checklist & Detail -->
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">
                <h6><i class="bi bi-list-check"></i> Checklist Pengecekan</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="50%">Camera</th>
                        <td>
                            @if($laporan->checklist_camera)
                                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Dicek</span>
                            @else
                                <span class="badge bg-secondary"><i class="bi bi-x-circle"></i> Tidak</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>DVR</th>
                        <td>
                            @if($laporan->checklist_dvr)
                                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Dicek</span>
                            @else
                                <span class="badge bg-secondary"><i class="bi bi-x-circle"></i> Tidak</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Monitor</th>
                        <td>
                            @if($laporan->checklist_monitor)
                                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Dicek</span>
                            @else
                                <span class="badge bg-secondary"><i class="bi bi-x-circle"></i> Tidak</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Kabel Camera</th>
                        <td>
                            @if($laporan->checklist_kabel_camera)
                                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Dicek</span>
                            @else
                                <span class="badge bg-secondary"><i class="bi bi-x-circle"></i> Tidak</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Kabel Listrik</th>
                        <td>
                            @if($laporan->checklist_kabel_listrik)
                                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Dicek</span>
                            @else
                                <span class="badge bg-secondary"><i class="bi bi-x-circle"></i> Tidak</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Konektor</th>
                        <td>
                            @if($laporan->checklist_konektor)
                                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Dicek</span>
                            @else
                                <span class="badge bg-secondary"><i class="bi bi-x-circle"></i> Tidak</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Kendala & Solusi -->
        <div class="card mt-3" id="kendala-solusi-container">
            <div class="card-header">
                <h6><i class="bi bi-clipboard"></i> Kendala & Solusi</h6>
            </div>
            <div class="card-body">
                @if($laporan->kendala_kerusakan)
                    <div class="alert alert-danger alert-kendala-solusi" role="alert">
                        <strong><i class="bi bi-exclamation-triangle"></i> Kendala Kerusakan:</strong>
                        <p class="mb-0 mt-1">{{ $laporan->kendala_kerusakan }}</p>
                    </div>
                @else
                    <div class="alert alert-info alert-kendala-solusi" role="alert">
                        <i class="bi bi-info-circle"></i> Tidak ada kendala yang dilaporkan
                    </div>
                @endif

                @if($laporan->solusi)
                    <div class="alert alert-success alert-kendala-solusi" role="alert">
                        <strong><i class="bi bi-check-circle"></i> Solusi:</strong>
                        <p class="mb-0 mt-1">{{ $laporan->solusi }}</p>
                    </div>
                @else
                    <div class="alert alert-warning alert-kendala-solusi" role="alert">
                        <i class="bi bi-clock"></i> Belum ada solusi yang diberikan
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Gambar Dokumentasi -->
    @if(isset($laporan->gambar) && $laporan->gambar->count() > 0)
    <div class="col-12 mb-3">
        <div class="card">
            <div class="card-header">
                <h6><i class="bi bi-images"></i> Dokumentasi Gambar</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($laporan->gambar as $gambar)
                        <div class="col-md-3 mb-3">
                            <div class="card">
                                <img src="{{ asset('storage/' . $gambar->path) }}" 
                                     alt="{{ $gambar->keterangan ?? 'Dokumentasi' }}" 
                                     class="card-img-top" 
                                     style="height: 200px; object-fit: cover;"
                                     data-bs-toggle="modal" 
                                     data-bs-target="#gambarModal{{ $gambar->id }}">
                                <div class="card-body p-2">
                                    <small class="text-muted d-block">
                                        <i class="bi bi-file-image"></i> {{ $gambar->nama_file }}
                                    </small>
                                    <small class="text-muted d-block">
                                        <i class="bi bi-hdd"></i> {{ $gambar->size_formatted ?? $gambar->size . ' B' }}
                                    </small>
                                    @if($gambar->is_compressed)
                                        <span class="badge bg-success">Compressed</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Modal Gambar -->
                        <div class="modal fade" id="gambarModal{{ $gambar->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">{{ $gambar->keterangan ?? 'Dokumentasi' }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <img src="{{ asset('storage/' . $gambar->path) }}" 
                                             alt="{{ $gambar->keterangan ?? 'Dokumentasi' }}" 
                                             class="img-fluid">
                                    </div>
                                    <div class="modal-footer">
                                        <small class="text-muted me-auto">
                                            <i class="bi bi-hdd"></i> {{ $gambar->size_formatted ?? $gambar->size . ' B' }}
                                            @if($gambar->is_compressed)
                                                | <i class="bi bi-compress"></i> Compressed
                                            @endif
                                        </small>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Laporan Eksekusi Terkait -->
    @if(isset($laporan->laporanEksekusi) && $laporan->laporanEksekusi->count() > 0)
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6><i class="bi bi-play-circle"></i> Laporan Eksekusi Terkait</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Teknisi</th>
                                <th>Tanggal Eksekusi</th>
                                <th>Status</th>
                                <th>Hasil</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($laporan->laporanEksekusi as $eksekusi)
                            <tr>
                                <td>{{ $eksekusi->user->name ?? '-' }}</td>
                                <td>{{ $eksekusi->tanggal_eksekusi->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $eksekusi->status_eksekusi == 'selesai' ? 'success' : ($eksekusi->status_eksekusi == 'proses' ? 'info' : 'warning') }}">
                                        {{ ucfirst($eksekusi->status_eksekusi) }}
                                    </span>
                                </td>
                                <td>{{ Str::limit($eksekusi->hasil ?? '-', 50) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
    .table-borderless td, .table-borderless th {
        padding: 8px 0;
    }
</style>
@endsection