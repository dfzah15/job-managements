@extends('layouts.app')

@section('title', 'Jadwal Maintenance - ' . ucfirst($jobdesk->name))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>
        <i class="{{ $jobdesk->icon }}"></i> 
        Jadwal Maintenance {{ ucfirst($jobdesk->name) }}
    </h3>
    <div>
        <a href="{{ route('maintenance.create', $jobdesk->slug) }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Jadwal
        </a>
        <a href="{{ route('maintenance.calendar', $jobdesk->slug) }}" class="btn btn-info">
            <i class="bi bi-calendar"></i> Kalender
        </a>
    </div>
</div>

<!-- Filter -->
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    @foreach($statusOptions ?? ['scheduled', 'in_progress', 'completed', 'cancelled'] as $status)
                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="jenis" class="form-select">
                    <option value="">Semua Jenis</option>
                    @foreach($jenisOptions ?? ['rutin', 'khusus', 'tahunan'] as $jenis)
                    <option value="{{ $jenis }}" {{ request('jenis') == $jenis ? 'selected' : '' }}>
                        {{ ucfirst($jenis) }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Filter
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
                        <th>Judul</th>
                        <th>Lokasi</th>
                        <th>Inventaris</th>
                        <th>Jenis</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schedules as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->judul }}</td>
                        <td>{{ $item->lokasi->nama_lokasi ?? '-' }}</td>
                        <td>{{ $item->inventaris->nama_inventaris ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $item->jenis_badge }}">{{ ucfirst($item->jenis) }}</span>
                        </td>
                        <td>{{ $item->tanggal_mulai->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge bg-{{ $item->status_badge }}">
                                {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('maintenance.show', [$jobdesk->slug, $item->id]) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('maintenance.edit', [$jobdesk->slug, $item->id]) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if($item->status != 'completed')
                            <a href="{{ route('maintenance.complete', [$jobdesk->slug, $item->id]) }}" 
                               class="btn btn-sm btn-success" 
                               onclick="return confirm('Selesaikan maintenance ini?')">
                                <i class="bi bi-check"></i>
                            </a>
                            @endif
                            <form action="{{ route('maintenance.destroy', [$jobdesk->slug, $item->id]) }}" method="POST" class="d-inline delete-confirm">
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
                                <i class="bi bi-calendar fs-1 text-muted"></i>
                                <p class="text-muted mt-2">Belum ada jadwal maintenance</p>
                                <a href="{{ route('maintenance.create', $jobdesk->slug) }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-circle"></i> Tambah Jadwal
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $schedules->withQueryString()->links() }}
    </div>
</div>
@endsection