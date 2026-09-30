@extends('layouts.app')

@section('title', 'Profile Saya')

@section('content')
<div class="row">
    <div class="col-md-4 mb-3">
        <!-- Card Profile -->
        <div class="card">
            <div class="card-body text-center">
                <div class="position-relative d-inline-block">
                    @php
                        $user = auth()->user();
                        $foto = $user->foto;
                        $hasFoto = $foto && file_exists(public_path('storage/' . $foto));
                        $defaultAvatarPath = public_path('images/default-avatar.svg');
                        $defaultAvatarExists = file_exists($defaultAvatarPath);
                        $defaultAvatarUrl = $defaultAvatarExists ? asset('images/default-avatar.svg') : 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><circle cx="100" cy="100" r="100" fill="#4A90D9"/><circle cx="100" cy="70" r="45" fill="#FFFFFF"/><ellipse cx="100" cy="175" rx="70" ry="55" fill="#FFFFFF"/><circle cx="85" cy="65" r="6" fill="#4A90D9"/><circle cx="115" cy="65" r="6" fill="#4A90D9"/><path d="M 80 85 Q 100 100 120 85" stroke="#4A90D9" stroke-width="4" fill="none"/><text x="100" y="120" font-family="Arial" font-size="40" fill="#4A90D9" text-anchor="middle" font-weight="bold">?</text></svg>');
                    @endphp
                    
                    @if($hasFoto)
                        <img src="{{ asset('storage/' . $foto) }}?v={{ time() }}" 
                             alt="Profile Photo" 
                             class="rounded-circle img-thumbnail" 
                             style="width: 150px; height: 150px; object-fit: cover;"
                             onerror="this.src='{{ $defaultAvatarUrl }}'">
                    @else
                        <img src="{{ $defaultAvatarUrl }}" 
                             alt="Default Avatar" 
                             class="rounded-circle img-thumbnail" 
                             style="width: 150px; height: 150px; object-fit: cover; background: #f0f0f0;">
                    @endif
                    
                    <button type="button" class="btn btn-sm btn-primary position-absolute bottom-0 end-0 rounded-circle" 
                            style="width: 35px; height: 35px;" 
                            data-bs-toggle="modal" data-bs-target="#fotoModal">
                        <i class="bi bi-camera"></i>
                    </button>
                </div>
                <h4 class="mt-3">{{ $user->name }}</h4>
                <p class="text-muted">
                    <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'teknisi' ? 'warning' : 'info') }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </p>
                <p class="text-muted">
                    <i class="bi bi-envelope"></i> {{ $user->email }}
                </p>
                @if($user->no_telepon)
                    <p class="text-muted">
                        <i class="bi bi-phone"></i> {{ $user->no_telepon }}
                    </p>
                @endif
                <div class="mt-3">
                    <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">
                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                    <small class="text-muted d-block mt-2">
                        Member sejak {{ $user->created_at->format('d F Y') }}
                    </small>
                </div>
            </div>
        </div>

        <!-- Statistik -->
        <div class="card mt-3">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-bar-chart"></i> Statistik Aktivitas</h6>
                <hr>
                @php
                    // Hitung ulang statistik dengan fresh data
                    $totalEksekusi = $user->laporanEksekusi->count();
                    $selesaiEksekusi = $user->laporanEksekusi->where('status_eksekusi', 'selesai')->count();
                    $prosesEksekusi = $user->laporanEksekusi->where('status_eksekusi', 'proses')->count();
                    $pendingEksekusi = $user->laporanEksekusi->where('status_eksekusi', 'pending')->count();
                @endphp
                <div class="row text-center">
                    <div class="col-4">
                        <h5>{{ $totalEksekusi }}</h5>
                        <small class="text-muted">Total</small>
                    </div>
                    <div class="col-4">
                        <h5 class="text-success">{{ $selesaiEksekusi }}</h5>
                        <small class="text-muted">Selesai</small>
                    </div>
                    <div class="col-4">
                        <h5 class="text-warning">{{ $prosesEksekusi + $pendingEksekusi }}</h5>
                        <small class="text-muted">Proses/Pending</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Tab Navigation -->
        <ul class="nav nav-tabs" id="profileTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">
                    <i class="bi bi-person"></i> Profil
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button" role="tab">
                    <i class="bi bi-key"></i> Ganti Password
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity" type="button" role="tab">
                    <i class="bi bi-clock-history"></i> Aktivitas
                </button>
            </li>
        </ul>

        <div class="tab-content" id="profileTabContent">
            <!-- Tab Profil -->
            <div class="tab-pane fade show active" id="profile" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h6><i class="bi bi-pencil"></i> Edit Profil</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="no_telepon" class="form-label">No Telepon</label>
                                    <input type="text" class="form-control @error('no_telepon') is-invalid @enderror" 
                                           id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}">
                                    @error('no_telepon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="role" class="form-label">Role</label>
                                    <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" disabled>
                                    <small class="text-muted">Role tidak dapat diubah sendiri</small>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Update Profil
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tab Ganti Password -->
            <div class="tab-pane fade" id="password" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h6><i class="bi bi-key"></i> Ganti Password</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="current_password" class="form-label">Password Saat Ini <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                       id="current_password" name="current_password" required>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password Baru <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required minlength="8">
                                <small class="text-muted">Minimal 8 karakter</small>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-key"></i> Ganti Password
                                </button>
                            </div>
                        </form>

                        <div class="alert alert-info mt-3">
                            <i class="bi bi-info-circle"></i>
                            <strong>Tips Password Aman:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Gunakan kombinasi huruf besar, huruf kecil, angka, dan simbol</li>
                                <li>Minimal 8 karakter</li>
                                <li>Jangan gunakan password yang sama dengan akun lain</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Aktivitas -->
            <div class="tab-pane fade" id="activity" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h6><i class="bi bi-clock-history"></i> Riwayat Aktivitas</h6>
                    </div>
                    <div class="card-body">
                        @if($user->laporanEksekusi->count() > 0)
                            <div class="timeline">
                                @foreach($user->laporanEksekusi->sortByDesc('created_at')->take(10) as $eksekusi)
                                    <div class="timeline-item mb-3">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <span class="badge bg-{{ $eksekusi->status_eksekusi == 'selesai' ? 'success' : ($eksekusi->status_eksekusi == 'proses' ? 'info' : 'warning') }} rounded-pill">
                                                    {{ ucfirst($eksekusi->status_eksekusi) }}
                                                </span>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-1">
                                                    {{ $eksekusi->laporanAktivitas->keterangan ?? 'Tidak ada keterangan' }}
                                                </h6>
                                                <small class="text-muted">
                                                    <i class="bi bi-geo-alt"></i> {{ $eksekusi->lokasi->nama_lokasi ?? '-' }} |
                                                    <i class="bi bi-calendar"></i> {{ $eksekusi->tanggal_eksekusi->format('d/m/Y') }} |
                                                    <i class="bi bi-clock"></i> {{ $eksekusi->waktu_mulai ?? '-' }}
                                                </small>
                                                @if($eksekusi->hasil)
                                                    <p class="mt-1 mb-0 small">{{ Str::limit($eksekusi->hasil, 100) }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if($user->laporanEksekusi->count() > 10)
                                <div class="text-center mt-3">
                                    <a href="{{ route('laporan-eksekusi.index', ['user_id' => $user->id]) }}" 
                                       class="btn btn-sm btn-link">
                                        Lihat semua ({{ $user->laporanEksekusi->count() }})
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                <p class="text-muted mt-2">Belum ada aktivitas</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Upload Foto -->
<div class="modal fade" id="fotoModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Foto Profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('profile.photo') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="foto" class="form-label">Pilih Foto</label>
                        <input type="file" class="form-control @error('foto') is-invalid @enderror" 
                               id="foto_modal" name="foto" accept="image/*" required>
                        <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB</small>
                        @error('foto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Preview di modal -->
                    <div id="previewContainer" style="display: none; text-align: center; margin-bottom: 10px;">
                        <img id="fotoPreviewModal" src="#" alt="Preview" 
                             style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%; border: 2px solid #ddd;">
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-upload"></i> Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.timeline-item {
    border-left: 3px solid #dee2e6;
    padding-left: 15px;
    padding-bottom: 15px;
}
.timeline-item:last-child {
    border-left: none;
    padding-bottom: 0;
}
</style>

@push('scripts')
<script>
    // Preview foto di modal
    document.getElementById('foto_modal').addEventListener('change', function(e) {
        const reader = new FileReader();
        const preview = document.getElementById('fotoPreviewModal');
        const container = document.getElementById('previewContainer');
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            container.style.display = 'block';
        };
        
        if (e.target.files[0]) {
            reader.readAsDataURL(e.target.files[0]);
        } else {
            container.style.display = 'none';
        }
    });
</script>
@endpush
@endsection