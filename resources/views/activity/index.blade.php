@extends('layouts.app')

@section('title', 'Activity Log')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3><i class="bi bi-clock-history"></i> Activity Log</h3>
    <div>
        <form action="{{ route('activity.clear') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-danger" onclick="return confirm('Hapus semua log?')">
                <i class="bi bi-trash"></i> Bersihkan Log
            </button>
        </form>
    </div>
</div>

<!-- Filter -->
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" 
                       placeholder="Cari log..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="aksi" class="form-select">
                    <option value="">Semua Aksi</option>
                    @foreach($aksiOptions as $aksi)
                    <option value="{{ $aksi }}" {{ request('aksi') == $aksi ? 'selected' : '' }}>
                        {{ ucfirst($aksi) }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="user_id" class="form-select">
                    <option value="">Semua User</option>
                    @foreach(\App\Models\User::all() as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                    @endforeach
                </select>
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
                        <th>User</th>
                        <th>Modul</th>
                        <th>Aksi</th>
                        <th>Deskripsi</th>
                        <th>IP Address</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $log->user->name ?? 'System' }}</td>
                        <td><span class="badge bg-secondary">{{ $log->modul }}</span></td>
                        <td>
                            <span class="badge bg-{{ $log->aksi_badge }}">
                                {{ ucfirst($log->aksi) }}
                            </span>
                        </td>
                        <td>{{ Str::limit($log->deskripsi, 50) }}</td>
                        <td><code>{{ $log->ip_address }}</code></td>
                        <td>{{ $log->formatted_created_at }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada log</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $logs->withQueryString()->links() }}
    </div>
</div>
@endsection