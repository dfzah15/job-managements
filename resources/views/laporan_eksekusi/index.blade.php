@extends('layouts.app')

@section('title', 'Laporan Eksekusi - ' . ucfirst($jobdesk->name))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>
        <i class="{{ $jobdesk->icon }}"></i> 
        Laporan Eksekusi {{ ucfirst($jobdesk->name) }}
    </h3>
    <a href="{{ route('laporan-eksekusi.create', $jobdesk->slug) }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Eksekusi
    </a>
</div>

<!-- Filter dan Pencarian -->
<div class="card mb-3">
    <div class="card-body">
        <form action="{{ route('laporan-eksekusi.index', $jobdesk->slug) }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" 
                       placeholder="Cari eksekusi..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    @foreach($statusOptions ?? ['pending', 'proses', 'selesai', 'gagal'] as $status)
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
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Cari
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
                        <th>Laporan</th>
                        <th>Teknisi</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Gambar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($eksekusi as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <span class="badge bg-info">{{ $item->lokasi->kode_lokasi ?? '-' }}</span>
                            {{ $item->lokasi->nama_lokasi ?? '-' }}
                        </td>
                        <td>{{ Str::limit($item->laporanAktivitas->keterangan ?? '-', 30) }}</td>
                        <td>{{ $item->user->name ?? '-' }}</td>
                        <td>{{ $item->tanggal_eksekusi->format('d/m/Y') }}</td>
                        <td>
                            @php
                                $statusColor = [
                                    'pending' => 'warning',
                                    'proses' => 'info',
                                    'selesai' => 'success',
                                    'gagal' => 'danger'
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusColor[$item->status_eksekusi] ?? 'secondary' }}">
                                {{ ucfirst($item->status_eksekusi) }}
                            </span>
                        </td>
                        <td>
                            @if($item->gambar->count() > 0)
                                <span class="badge bg-primary">{{ $item->gambar->count() }} foto</span>
                            @else
                                <span class="badge bg-secondary">0</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('laporan-eksekusi.show', [$jobdesk->slug, $item->id]) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if($item->status_eksekusi == 'pending')
                                <a href="{{ route('laporan-eksekusi.start', [$jobdesk->slug, $item->id]) }}" class="btn btn-sm btn-success" onclick="return confirm('Mulai eksekusi?')">
                                    <i class="bi bi-play"></i>
                                </a>
                            @endif
                            <a href="{{ route('laporan-eksekusi.edit', [$jobdesk->slug, $item->id]) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('laporan-eksekusi.destroy', [$jobdesk->slug, $item->id]) }}" method="POST" class="d-inline delete-confirm">
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
                                <p class="text-muted mt-2">Belum ada data eksekusi</p>
                                <a href="{{ route('laporan-eksekusi.create', $jobdesk->slug) }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-circle"></i> Tambah Eksekusi
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $eksekusi->withQueryString()->links() }}
    </div>
</div>
@endsection