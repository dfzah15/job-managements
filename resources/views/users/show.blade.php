@extends('layouts.app')

@section('title', 'Detail User')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3><i class="bi bi-person"></i> Detail User</h3>
    <div>
        <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body text-center">
                @php
                    $foto = $user->foto;
                    $hasFoto = $foto && file_exists(public_path('storage/' . $foto));
                    $defaultAvatar = asset('images/default-avatar.svg');
                    $fotoUrl = $hasFoto ? asset('storage/' . $foto) : $defaultAvatar;
                @endphp
                <img src="{{ $fotoUrl }}" 
                     alt="Foto" 
                     class="rounded-circle img-thumbnail" 
                     style="width: 150px; height: 150px; object-fit: cover;">
                <h4 class="mt-3">{{ $user->name }}</h4>
                <p>
                    <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'teknisi' ? 'warning' : 'info') }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </p>
                <p>
                    @if($user->is_active)
                        <span class="badge bg-success">Aktif</span>
                    @else
                        <span class="badge bg-danger">Nonaktif</span>
                    @endif
                </p>
            </div>
        </div>
    </div>

    <div class="col-md-8 mb-3">
        <div class="card">
            <div class="card-header">
                <h6><i class="bi bi-info-circle"></i> Informasi User</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="30%">ID</th>
                        <td>#{{ $user->id }}</td>
                    </tr>
                    <tr>
                        <th>Nama Lengkap</th>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th>Role</th>
                        <td>
                            <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'teknisi' ? 'warning' : 'info') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>No Telepon</th>
                        <td>{{ $user->no_telepon ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($user->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Nonaktif</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Terdaftar</th>
                        <td>{{ $user->created_at->format('d F Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Terakhir Update</th>
                        <td>{{ $user->updated_at->format('d F Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h6><i class="bi bi-bar-chart"></i> Statistik Aktivitas</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-4">
                        <h4>{{ $user->laporanEksekusi->count() }}</h4>
                        <small class="text-muted">Total Eksekusi</small>
                    </div>
                    <div class="col-4">
                        <h4>{{ $user->laporanEksekusi->where('status_eksekusi', 'selesai')->count() }}</h4>
                        <small class="text-muted">Selesai</small>
                    </div>
                    <div class="col-4">
                        <h4>{{ $user->laporanEksekusi->where('status_eksekusi', 'proses')->count() }}</h4>
                        <small class="text-muted">Proses</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection