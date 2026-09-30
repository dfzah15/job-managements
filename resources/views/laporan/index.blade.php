@extends('layouts.app')

@section('title', 'Laporan Aktivitas - ' . ucfirst($jobdesk->name))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>
        <i class="{{ $jobdesk->icon }}"></i> 
        Laporan Aktivitas {{ ucfirst($jobdesk->name) }}
    </h3>
    <div>
        <a href="{{ route('laporan.create', $jobdesk->slug) }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Laporan
        </a>
        <a href="{{ route('laporan.export.excel', $jobdesk->slug) }}" class="btn btn-success">
            <i class="bi bi-file-earmark-excel"></i> Export Excel
        </a>
        <a href="{{ route('laporan.export.pdf', $jobdesk->slug) }}" class="btn btn-danger">
            <i class="bi bi-file-pdf"></i> Export PDF
        </a>
    </div>
</div>

<!-- Filter dan Pencarian -->
<div class="card mb-3">
    <div class="card-body">
        <form action="{{ route('laporan.index', $jobdesk->slug) }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" 
                       placeholder="Cari laporan..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="lokasi_id" class="form-select">
                    <option value="">Semua Lokasi</option>
                    @foreach($lokasi ?? [] as $item)
                        <option value="{{ $item->id }}" {{ request('lokasi_id') == $item->id ? 'selected' : '' }}>
                            {{ $item->nama_lokasi }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    @foreach($statusOptions ?? ['selesai', 'pending', 'proses'] as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="Dari">
            </div>
            <div class="col-md-2">
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="Sampai">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Lokasi</th>
                        <th>Jenis</th>
                        <th>Keterangan</th>
                        <th>Jml Rusak</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporan as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <span class="badge bg-info">{{ $item->lokasi->kode_lokasi ?? '-' }}</span>
                            {{ $item->lokasi->nama_lokasi ?? '-' }}
                        </td>
                        <td>
                            <span class="badge bg-{{ $item->jenis_aktivitas == 'perbaikan' ? 'danger' : ($item->jenis_aktivitas == 'pemeliharaan' ? 'warning' : 'info') }}">
                                {{ ucfirst($item->jenis_aktivitas) }}
                            </span>
                        </td>
                        <td>{{ Str::limit($item->keterangan, 30) }}</td>
                        <td>
                            @if($item->jumlah_rusak > 0)
                                <span class="badge bg-danger">{{ $item->jumlah_rusak }}</span>
                            @else
                                <span class="badge bg-success">0</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusClass = [
                                    'selesai' => 'success',
                                    'pending' => 'warning',
                                    'proses' => 'info'
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusClass[$item->status_pekerjaan] ?? 'secondary' }}">
                                {{ ucfirst($item->status_pekerjaan) }}
                            </span>
                        </td>
                        <td>{{ $item->tanggal_laporan->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('laporan.show', [$jobdesk->slug, $item->id]) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('laporan.edit', [$jobdesk->slug, $item->id]) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('laporan.destroy', [$jobdesk->slug, $item->id]) }}" method="POST" class="d-inline delete-confirm">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">
                            <div class="py-4">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                <p class="text-muted mt-2">Belum ada laporan aktivitas</p>
                                <a href="{{ route('laporan.create', $jobdesk->slug) }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-circle"></i> Tambah Laporan
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $laporan->withQueryString()->links() }}
    </div>
</div>
@endsection