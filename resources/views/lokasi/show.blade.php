@extends('layouts.app')

@section('title', 'Detail Lokasi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3><i class="bi bi-geo-alt"></i> Detail Lokasi</h3>
    <div>
        <a href="{{ route('lokasi.edit', $lokasi) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('lokasi.index') }}" class="btn btn-secondary">
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
                        <th width="35%">Kode Lokasi</th>
                        <td><span class="badge bg-info">{{ $lokasi->kode_lokasi }}</span></td>
                    </tr>
                    <tr>
                        <th>Nama Lokasi</th>
                        <td><strong>{{ $lokasi->nama_lokasi }}</strong></td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>{{ $lokasi->alamat ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Dibuat</th>
                        <td>{{ $lokasi->created_at->format('d F Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Terakhir Update</th>
                        <td>{{ $lokasi->updated_at->format('d F Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Statistik -->
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">
                <h6><i class="bi bi-bar-chart"></i> Statistik</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-4">
                        <h3>{{ $lokasi->inventaris->count() }}</h3>
                        <small class="text-muted">Inventaris</small>
                    </div>
                    <div class="col-4">
                        <h3>{{ $lokasi->checklistCctv->count() }}</h3>
                        <small class="text-muted">Checklist</small>
                    </div>
                    <div class="col-4">
                        <h3>{{ $lokasi->laporanAktivitas->count() }}</h3>
                        <small class="text-muted">Laporan</small>
                    </div>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-6">
                        <h5>{{ $lokasi->laporanAktivitas->where('status_pekerjaan', 'pending')->count() }}</h5>
                        <small class="text-warning">Pending</small>
                    </div>
                    <div class="col-6">
                        <h5>{{ $lokasi->laporanAktivitas->where('status_pekerjaan', 'selesai')->count() }}</h5>
                        <small class="text-success">Selesai</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Inventaris -->
    <div class="col-12 mb-3">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6><i class="bi bi-box"></i> Daftar Inventaris</h6>
                <a href="{{ route('inventaris.create', ['lokasi_id' => $lokasi->id]) }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah
                </a>
            </div>
            <div class="card-body">
                @if($lokasi->inventaris->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Jenis</th>
                                    <th>Merk</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lokasi->inventaris as $item)
                                <tr>
                                    <td>{{ $item->nama_inventaris }}</td>
                                    <td><span class="badge bg-secondary">{{ $item->jenis }}</span></td>
                                    <td>{{ $item->merk ?? '-' }}</td>
                                    <td>{{ $item->jumlah }}</td>
                                    <td>
                                        <span class="badge bg-{{ $item->status == 'aktif' ? 'success' : ($item->status == 'rusak' ? 'danger' : 'warning') }}">
                                            {{ $item->status }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="bi bi-inbox text-muted"></i>
                        <p class="text-muted">Belum ada inventaris di lokasi ini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Riwayat Checklist -->
    <div class="col-12 mb-3">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6><i class="bi bi-list-check"></i> Riwayat Checklist</h6>
                <a href="{{ route('checklist.create', ['lokasi_id' => $lokasi->id]) }}" class="btn btn-sm btn-success">
                    <i class="bi bi-plus-circle"></i> Tambah
                </a>
            </div>
            <div class="card-body">
                @if($lokasi->checklistCctv->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Petugas</th>
                                    <th>Jumlah Camera</th>
                                    <th>Status HDD</th>
                                    <th>Kamera Mati</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lokasi->checklistCctv->take(5) as $item)
                                <tr>
                                    <td>{{ $item->tanggal_check->format('d/m/Y') }}</td>
                                    <td>{{ $item->petugas_check }}</td>
                                    <td>{{ $item->jumlah_camera }}</td>
                                    <td>
                                        <span class="badge bg-{{ $item->status_hdd == 'normal' ? 'success' : 'danger' }}">
                                            {{ $item->status_hdd }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($item->jumlah_kamera_mati > 0)
                                            <span class="badge bg-danger">{{ $item->jumlah_kamera_mati }}</span>
                                        @else
                                            <span class="badge bg-success">0</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($lokasi->checklistCctv->count() > 5)
                        <div class="text-center">
                            <a href="{{ route('checklist.index', ['lokasi_id' => $lokasi->id]) }}" class="btn btn-sm btn-link">
                                Lihat semua ({{ $lokasi->checklistCctv->count() }})
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-3">
                        <i class="bi bi-inbox text-muted"></i>
                        <p class="text-muted">Belum ada checklist untuk lokasi ini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection