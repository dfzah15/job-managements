@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="row">
    <!-- ============================================ -->
    <!-- FORM EDIT USER -->
    <!-- ============================================ -->
    <div class="col-md-12 mb-3">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-pencil"></i> Edit User</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Nama -->
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">
                                <i class="bi bi-person"></i> Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
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
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Role -->
                        <div class="col-md-4 mb-3">
                            <label for="role" class="form-label">
                                <i class="bi bi-tag"></i> Role <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('role') is-invalid @enderror" 
                                    id="role" name="role" required
                                    onchange="toggleRole(this.value)">
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="manajer" {{ old('role', $user->role) == 'manajer' ? 'selected' : '' }}>Manajer</option>
                                <option value="teknisi" {{ old('role', $user->role) == 'teknisi' ? 'selected' : '' }}>Teknisi</option>
                                <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                            </select>
                            <small class="text-muted" id="roleInfo">
                                <i class="bi bi-info-circle"></i> 
                                @if($user->role == 'admin')
                                    <span class="text-warning">Admin memiliki akses ke semua jobdesk secara otomatis</span>
                                @else
                                    User hanya bisa mengakses jobdesk yang di-assign
                                @endif
                            </small>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="col-md-4 mb-3">
                            <label for="is_active" class="form-label">
                                <i class="bi bi-circle"></i> Status
                            </label>
                            <select class="form-select" id="is_active" name="is_active">
                                <option value="1" {{ old('is_active', $user->is_active) ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('is_active', $user->is_active) ? '' : 'selected' }}>Nonaktif</option>
                            </select>
                        </div>

                        <!-- No Telepon -->
                        <div class="col-md-4 mb-3">
                            <label for="no_telepon" class="form-label">
                                <i class="bi bi-phone"></i> No Telepon
                            </label>
                            <input type="text" class="form-control @error('no_telepon') is-invalid @enderror" 
                                   id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}">
                            @error('no_telepon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Foto -->
                        <div class="col-12 mb-3">
                            <label class="form-label">
                                <i class="bi bi-camera"></i> Foto Profil
                            </label>
                            <div class="mb-2">
                                @php
                                    $foto = $user->foto;
                                    $hasFoto = $foto && file_exists(public_path('storage/' . $foto));
                                    $defaultAvatar = asset('images/default-avatar.svg');
                                    $fotoUrl = $hasFoto ? asset('storage/' . $foto) : $defaultAvatar;
                                @endphp
                                <img src="{{ $fotoUrl }}" 
                                     alt="Foto" 
                                     class="rounded-circle" 
                                     id="fotoPreview"
                                     style="width: 100px; height: 100px; object-fit: cover; border: 2px solid #ddd;">
                            </div>
                            <input type="file" class="form-control @error('foto') is-invalid @enderror" 
                                   id="foto" name="foto" accept="image/*">
                            <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB</small>
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password (Opsional) -->
                        <div class="col-12 mt-3">
                            <hr>
                            <h6><i class="bi bi-key"></i> Ganti Password (Kosongkan jika tidak ingin mengubah)</h6>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="password" class="form-label">Password Baru</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" minlength="8">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- ASSIGN JOBDESK (Hanya untuk Non-Admin) -->
<!-- ============================================ -->
@if($user->role != 'admin')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-grid"></i> Assign Jobdesk & Permission</h5>
                <small class="text-muted">
                    <i class="bi bi-info-circle"></i> 
                    Pilih jobdesk dan permission untuk user ini
                </small>
            </div>
            <div class="card-body">
                <form action="{{ route('users.assign-jobdesk', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        @foreach($jobdesks as $jobdesk)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card h-100 {{ $user->jobdesks->contains($jobdesk->id) ? 'border-primary' : '' }}">
                                <div class="card-body">
                                    <!-- Checkbox Jobdesk -->
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" 
                                               name="jobdesks[]" value="{{ $jobdesk->id }}"
                                               id="jobdesk_{{ $jobdesk->id }}"
                                               {{ $user->jobdesks->contains($jobdesk->id) ? 'checked' : '' }}
                                               onchange="togglePermission(this, {{ $jobdesk->id }})">
                                        <label class="form-check-label fw-bold" for="jobdesk_{{ $jobdesk->id }}">
                                            <i class="{{ $jobdesk->icon }}"></i> {{ $jobdesk->name }}
                                        </label>
                                    </div>
                                    
                                    <!-- Permission -->
                                    @php
                                        $pivot = $user->jobdesks->where('id', $jobdesk->id)->first();
                                        $hasAccess = $user->jobdesks->contains($jobdesk->id);
                                    @endphp
                                    <div class="permission-group mt-2" id="permission_{{ $jobdesk->id }}" 
                                         style="{{ $hasAccess ? '' : 'display: none;' }}">
                                        <small class="text-muted d-block mb-1">Permission:</small>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" 
                                                           name="permissions[{{ $jobdesk->id }}][can_view]" value="1"
                                                           id="view_{{ $jobdesk->id }}"
                                                           {{ ($pivot && $pivot->pivot->can_view) ? 'checked' : '' }}
                                                           {{ $hasAccess ? '' : 'disabled' }}>
                                                    <label class="form-check-label small" for="view_{{ $jobdesk->id }}">
                                                        <i class="bi bi-eye"></i> View
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" 
                                                           name="permissions[{{ $jobdesk->id }}][can_create]" value="1"
                                                           id="create_{{ $jobdesk->id }}"
                                                           {{ ($pivot && $pivot->pivot->can_create) ? 'checked' : '' }}
                                                           {{ $hasAccess ? '' : 'disabled' }}>
                                                    <label class="form-check-label small" for="create_{{ $jobdesk->id }}">
                                                        <i class="bi bi-plus-circle"></i> Create
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" 
                                                           name="permissions[{{ $jobdesk->id }}][can_edit]" value="1"
                                                           id="edit_{{ $jobdesk->id }}"
                                                           {{ ($pivot && $pivot->pivot->can_edit) ? 'checked' : '' }}
                                                           {{ $hasAccess ? '' : 'disabled' }}>
                                                    <label class="form-check-label small" for="edit_{{ $jobdesk->id }}">
                                                        <i class="bi bi-pencil"></i> Edit
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" 
                                                           name="permissions[{{ $jobdesk->id }}][can_delete]" value="1"
                                                           id="delete_{{ $jobdesk->id }}"
                                                           {{ ($pivot && $pivot->pivot->can_delete) ? 'checked' : '' }}
                                                           {{ $hasAccess ? '' : 'disabled' }}>
                                                    <label class="form-check-label small" for="delete_{{ $jobdesk->id }}">
                                                        <i class="bi bi-trash"></i> Delete
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" 
                                                           name="permissions[{{ $jobdesk->id }}][can_export]" value="1"
                                                           id="export_{{ $jobdesk->id }}"
                                                           {{ ($pivot && $pivot->pivot->can_export) ? 'checked' : '' }}
                                                           {{ $hasAccess ? '' : 'disabled' }}>
                                                    <label class="form-check-label small" for="export_{{ $jobdesk->id }}">
                                                        <i class="bi bi-download"></i> Export
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" 
                                                           name="permissions[{{ $jobdesk->id }}][can_approve]" value="1"
                                                           id="approve_{{ $jobdesk->id }}"
                                                           {{ ($pivot && $pivot->pivot->can_approve) ? 'checked' : '' }}
                                                           {{ $hasAccess ? '' : 'disabled' }}>
                                                    <label class="form-check-label small" for="approve_{{ $jobdesk->id }}">
                                                        <i class="bi bi-check-circle"></i> Approve
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save"></i> Simpan Jobdesk Assignment
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

<!-- ============================================ -->
<!-- INFORMASI JOBDESK USER -->
<!-- ============================================ -->
<div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h6><i class="bi bi-info-circle"></i> 
                    @if($user->role == 'admin')
                        Jobdesk Admin (Akses Semua)
                    @else
                        Jobdesk User Saat Ini
                    @endif
                </h6>
            </div>
            <div class="card-body">
                @if($user->role == 'admin')
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle"></i> 
                        <strong>Admin</strong> - Memiliki akses ke <strong>SEMUA jobdesk</strong> dengan permission penuh.
                        <br>
                        <small>Admin dapat mengakses semua menu dari semua jobdesk yang tersedia.</small>
                    </div>
                    
                    <!-- Tampilkan semua jobdesk yang tersedia untuk admin -->
                    <div class="row mt-2">
                        <div class="col-12">
                            <strong class="text-muted">Semua Jobdesk Tersedia:</strong>
                        </div>
                        @foreach($jobdesks as $jobdesk)
                        <div class="col-md-2 col-lg-2 col-4 mb-2">
                            <div class="badge bg-{{ $jobdesk->color_badge }} p-2 w-100 text-center">
                                <i class="{{ $jobdesk->icon }}"></i> {{ $jobdesk->name }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                @elseif($user->jobdesks->count() > 0)
                    <div class="row">
                        @foreach($user->jobdesks as $jobdesk)
                        <div class="col-md-3 col-lg-2 mb-2">
                            <div class="badge bg-{{ $jobdesk->color_badge }} p-2 d-flex justify-content-between align-items-center w-100">
                                <span>
                                    <i class="{{ $jobdesk->icon }}"></i> {{ $jobdesk->name }}
                                </span>
                                <span class="badge bg-light text-dark" title="Permission">
                                    {{ $jobdesk->pivot->can_view ? 'V' : '' }}
                                    {{ $jobdesk->pivot->can_create ? 'C' : '' }}
                                    {{ $jobdesk->pivot->can_edit ? 'E' : '' }}
                                    {{ $jobdesk->pivot->can_delete ? 'D' : '' }}
                                    {{ $jobdesk->pivot->can_export ? 'X' : '' }}
                                    {{ $jobdesk->pivot->can_approve ? 'A' : '' }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i> 
                            Keterangan: V=View, C=Create, E=Edit, D=Delete, X=Export, A=Approve
                        </small>
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i> 
                        User ini belum memiliki akses ke jobdesk manapun. 
                        <br>
                        <small>Silahkan assign jobdesk di atas.</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- JAVASCRIPT -->
<!-- ============================================ -->
@push('scripts')
<script>
    // Preview foto
    document.getElementById('foto').addEventListener('change', function(e) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('fotoPreview').src = e.target.result;
        };
        if (e.target.files[0]) {
            reader.readAsDataURL(e.target.files[0]);
        }
    });

    // Toggle permission group
    function togglePermission(checkbox, jobdeskId) {
        const permissionGroup = document.getElementById('permission_' + jobdeskId);
        const inputs = permissionGroup.querySelectorAll('input[type="checkbox"]');
        
        if (checkbox.checked) {
            permissionGroup.style.display = 'block';
            inputs.forEach(function(input) {
                input.disabled = false;
            });
        } else {
            permissionGroup.style.display = 'none';
            inputs.forEach(function(input) {
                input.checked = false;
                input.disabled = true;
            });
        }
    }

    // Toggle role info
    function toggleRole(role) {
        const roleInfo = document.getElementById('roleInfo');
        if (role === 'admin') {
            roleInfo.innerHTML = '<span class="text-warning"><i class="bi bi-info-circle"></i> Admin memiliki akses ke semua jobdesk secara otomatis</span>';
            // Refresh halaman untuk menampilkan/menyembunyikan form assign jobdesk
            location.reload();
        } else {
            roleInfo.innerHTML = '<span class="text-muted"><i class="bi bi-info-circle"></i> User hanya bisa mengakses jobdesk yang di-assign</span>';
            // Refresh halaman untuk menampilkan/menyembunyikan form assign jobdesk
            location.reload();
        }
    }

    // Inisialisasi saat halaman load
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('input[name="jobdesks[]"]').forEach(function(checkbox) {
            const jobdeskId = checkbox.value;
            const permissionGroup = document.getElementById('permission_' + jobdeskId);
            
            if (permissionGroup) {
                if (!checkbox.checked) {
                    permissionGroup.style.display = 'none';
                    permissionGroup.querySelectorAll('input[type="checkbox"]').forEach(function(input) {
                        input.disabled = true;
                    });
                }
            }
        });
    });
</script>
@endpush
@endsection