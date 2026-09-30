@extends('layouts.app')

@section('title', 'Detail Laporan Eksekusi - ' . ucfirst($jobdesk->name ?? ''))

@section('content')
@if(isset($eksekusi) && isset($jobdesk))
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>
        <i class="{{ $jobdesk->icon ?? 'bi bi-play-circle' }}"></i> 
        Detail Laporan Eksekusi {{ ucfirst($jobdesk->name ?? '') }}
    </h3>
    <div>
        <a href="{{ route('laporan-eksekusi.edit', [$jobdesk->slug, $eksekusi->id]) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('laporan-eksekusi.index', $jobdesk->slug) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <!-- Informasi Utama -->
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">
                <h6><i class="bi bi-info-circle"></i> Informasi Eksekusi</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="35%">ID Eksekusi</th>
                        <td><span class="badge bg-primary">#{{ $eksekusi->id }}</span></td>
                    </tr>
                    <tr>
                        <th>Lokasi</th>
                        <td>
                            <span class="badge bg-info">{{ $eksekusi->lokasi->kode_lokasi ?? '-' }}</span>
                            {{ $eksekusi->lokasi->nama_lokasi ?? '-' }}
                        </td>
                    </tr>
                    <tr>
                        <th>Laporan Aktivitas</th>
                        <td>
                            <a href="{{ route('laporan.show', [$jobdesk->slug, $eksekusi->laporan_aktivitas_id]) }}">
                                #{{ $eksekusi->laporan_aktivitas_id }} - {{ $eksekusi->laporanAktivitas->keterangan ?? '-' }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>Teknisi</th>
                        <td>{{ $eksekusi->user->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Eksekusi</th>
                        <td>{{ $eksekusi->tanggal_eksekusi->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <th>Waktu Mulai</th>
                        <td>{{ $eksekusi->waktu_mulai ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Waktu Selesai</th>
                        <td>{{ $eksekusi->waktu_selesai ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Status Eksekusi</th>
                        <td>
                            @php
                                $statusColor = [
                                    'pending' => 'warning',
                                    'proses' => 'info',
                                    'selesai' => 'success',
                                    'gagal' => 'danger'
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusColor[$eksekusi->status_eksekusi] ?? 'secondary' }}">
                                {{ ucfirst($eksekusi->status_eksekusi) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Dibuat</th>
                        <td>{{ $eksekusi->created_at->format('d F Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Terakhir Update</th>
                        <td>{{ $eksekusi->updated_at->format('d F Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Deskripsi & Hasil -->
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">
                <h6><i class="bi bi-card-text"></i> Detail Pekerjaan</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="fw-bold">Deskripsi Pekerjaan:</label>
                    <p>{{ $eksekusi->deskripsi_pekerjaan }}</p>
                </div>

                @if($eksekusi->hasil)
                <div class="mb-3">
                    <label class="fw-bold text-success">Hasil Pekerjaan:</label>
                    <p>{{ $eksekusi->hasil }}</p>
                </div>
                @endif

                @if($eksekusi->catatan)
                <div class="mb-3">
                    <label class="fw-bold text-warning">Catatan:</label>
                    <p>{{ $eksekusi->catatan }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        @if($eksekusi->status_eksekusi == 'pending')
        <div class="card mt-3">
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('laporan-eksekusi.start', [$jobdesk->slug, $eksekusi->id]) }}" 
                       class="btn btn-success" 
                       onclick="return confirm('Mulai eksekusi ini?')">
                        <i class="bi bi-play-fill"></i> Mulai Eksekusi
                    </a>
                </div>
            </div>
        </div>
        @endif

        @if($eksekusi->status_eksekusi == 'proses')
        <div class="card mt-3">
            <div class="card-body">
                <form action="{{ route('laporan-eksekusi.complete', [$jobdesk->slug, $eksekusi->id]) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="hasil" class="form-label">Hasil Pekerjaan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="hasil" name="hasil" rows="2" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="catatan" class="form-label">Catatan</label>
                        <textarea class="form-control" id="catatan" name="catatan" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100" onclick="return confirm('Selesaikan eksekusi ini?')">
                        <i class="bi bi-check-circle"></i> Selesaikan Eksekusi
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>

    <!-- Gambar Dokumentasi -->
    @if($eksekusi->gambar && $eksekusi->gambar->count() > 0)
    <div class="col-12 mb-3">
        <div class="card">
            <div class="card-header">
                <h6><i class="bi bi-images"></i> Dokumentasi Gambar ({{ $eksekusi->gambar->count() }})</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($eksekusi->gambar as $gambar)
                        <div class="col-md-3 mb-3">
                            <div class="card">
                                <img src="{{ asset('storage/' . $gambar->path) }}" 
                                     alt="{{ $gambar->keterangan ?? 'Dokumentasi' }}" 
                                     class="card-img-top" 
                                     style="height: 200px; object-fit: cover; cursor: pointer;"
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
</div>
@else
<div class="alert alert-danger">
    <i class="bi bi-exclamation-triangle"></i> 
    <strong>Error:</strong> Data tidak ditemukan.
    <br>
    <small>Pastikan data dengan ID ini ada di database.</small>
</div>
<div class="mt-3">
    <a href="{{ route('laporan-eksekusi.index', request()->segment(1)) }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>
@endif

<style>
    .table-borderless td, .table-borderless th {
        padding: 8px 0;
    }
</style>
@endsection