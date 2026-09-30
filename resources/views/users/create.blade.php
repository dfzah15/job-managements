@extends('layouts.app')

@section('title', 'Tambah User Baru')

@section('content')
<div class="card">
    <div class="card-header">
        <h5><i class="bi bi-person-plus"></i> Form Tambah User</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <!-- Nama -->
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">
                        <i class="bi bi-person"></i> Nama Lengkap <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}" required 
                           placeholder="Masukkan nama lengkap">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">
                        <i class="bi bi-envelope"></i> Email <span class="text-danger">*</span>
                    </label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ old('email') }}" required 
                           placeholder="Masukkan email">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">
                        <i class="bi bi-key"></i> Password <span class="text-danger">*</span>
                    </label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                           id="password" name="password" required minlength="8"
                           placeholder="Minimal 8 karakter">
                    <small class="text-muted">Minimal 8 karakter</small>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Konfirmasi Password -->
                <div class="col-md-6 mb-3">
                    <label for="password_confirmation" class="form-label">
                        <i class="bi bi-key-fill"></i> Konfirmasi Password <span class="text-danger">*</span>
                    </label>
                    <input type="password" class="form-control" 
                           id="password_confirmation" name="password_confirmation" required
                           placeholder="Ulangi password">
                </div>

                <!-- Role -->
                <div class="col-md-6 mb-3">
                    <label for="role" class="form-label">
                        <i class="bi bi-tag"></i> Role <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('role') is-invalid @enderror" 
                            id="role" name="role" required>
                        <option value="">Pilih Role</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="teknisi" {{ old('role') == 'teknisi' ? 'selected' : '' }}>Teknisi</option>
                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- No Telepon -->
                <div class="col-md-6 mb-3">
                    <label for="no_telepon" class="form-label">
                        <i class="bi bi-phone"></i> No Telepon
                    </label>
                    <input type="text" class="form-control @error('no_telepon') is-invalid @enderror" 
                           id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}"
                           placeholder="Contoh: 08123456789">
                    @error('no_telepon')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Foto -->
                <div class="col-12 mb-3">
                    <label for="foto" class="form-label">
                        <i class="bi bi-camera"></i> Foto Profil
                    </label>
                    <input type="file" class="form-control @error('foto') is-invalid @enderror" 
                           id="foto" name="foto" accept="image/*">
                    <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB</small>
                    @error('foto')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Preview Foto -->
                <div class="col-12 mb-3" id="previewContainer" style="display: none;">
                    <label class="form-label">Preview Foto:</label>
                    <br>
                    <img id="fotoPreview" src="#" alt="Preview" 
                         style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%; border: 2px solid #ddd;">
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan User
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Informasi Role -->
<div class="card mt-3">
    <div class="card-header">
        <h6><i class="bi bi-info-circle"></i> Informasi Role</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="alert alert-danger">
                    <strong><i class="bi bi-shield-lock"></i> Admin</strong>
                    <p class="mb-0 small">Akses penuh ke semua fitur termasuk manajemen user</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="alert alert-warning">
                    <strong><i class="bi bi-tools"></i> Teknisi</strong>
                    <p class="mb-0 small">Akses ke laporan eksekusi dan checklist</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="alert alert-info">
                    <strong><i class="bi bi-person"></i> User</strong>
                    <p class="mb-0 small">Akses terbatas, hanya melihat dan membuat laporan</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Preview foto sebelum upload
    document.getElementById('foto').addEventListener('change', function(e) {
        const reader = new FileReader();
        const preview = document.getElementById('fotoPreview');
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