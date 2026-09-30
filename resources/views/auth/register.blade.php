@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg mt-4">
                <div class="card-header bg-success text-white text-center py-3">
                    <!-- ============================================ -->
                    <!-- LOGO DI HEADER REGISTER -->
                    <!-- ============================================ -->
                    <div class="mb-2">
                        <img src="{{ asset('images/logo.png') }}" 
                             alt="Logo" 
                             style="height: 50px; width: auto; filter: brightness(0) invert(1);">
                    </div>
                    <h5 class="mb-0"><i class="bi bi-person-plus"></i> Register</h5>
                    <small>Buat akun baru</small>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">
                                <i class="bi bi-person"></i> Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <input id="name" type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   name="name" value="{{ old('name') }}" 
                                   required autocomplete="name" autofocus
                                   placeholder="Masukkan nama lengkap">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope"></i> Email <span class="text-danger">*</span>
                            </label>
                            <input id="email" type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email') }}" 
                                   required autocomplete="email"
                                   placeholder="Masukkan email">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="bi bi-key"></i> Password <span class="text-danger">*</span>
                            </label>
                            <input id="password" type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   name="password" required autocomplete="new-password"
                                   placeholder="Minimal 8 karakter">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password-confirm" class="form-label">
                                <i class="bi bi-key-fill"></i> Konfirmasi Password <span class="text-danger">*</span>
                            </label>
                            <input id="password-confirm" type="password" 
                                   class="form-control" 
                                   name="password_confirmation" 
                                   required autocomplete="new-password"
                                   placeholder="Ulangi password">
                        </div>

                        <div class="mb-3">
                            <label for="no_telepon" class="form-label">
                                <i class="bi bi-phone"></i> No Telepon
                            </label>
                            <input id="no_telepon" type="text" 
                                   class="form-control @error('no_telepon') is-invalid @enderror" 
                                   name="no_telepon" value="{{ old('no_telepon') }}"
                                   placeholder="Contoh: 081234567890">
                            @error('no_telepon')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-person-plus"></i> Register
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            Sudah punya akun? <a href="{{ route('login') }}">Login</a>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center text-muted py-2">
                    <small>© {{ date('Y') }} Job Management System v2.0</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection