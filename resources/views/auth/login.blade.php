@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg mt-5">
                <div class="card-header bg-primary text-white text-center py-4">
                    <!-- ============================================ -->
                    <!-- LOGO DI HEADER LOGIN -->
                    <!-- ============================================ -->
                    <div class="mb-2">
                        <img src="{{ asset('images/logo.png') }}" 
                             alt="Logo" 
                             style="height: 60px; width: auto; filter: brightness(0) invert(1);">
                    </div>
                    <h5 class="mb-0"><i class="bi bi-box-arrow-in-right"></i> Login</h5>
                    <small>Report System</small>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope"></i> Email
                            </label>
                            <input id="email" type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email') }}" 
                                   required autocomplete="email" autofocus
                                   placeholder="Masukkan email">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="bi bi-key"></i> Password
                            </label>
                            <input id="password" type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   name="password" required autocomplete="current-password"
                                   placeholder="Masukkan password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input class="form-check-input" type="checkbox" name="remember" 
                                   id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                Remember Me
                            </label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            @if (Route::has('password.request'))
                                <a class="btn btn-link btn-sm" href="{{ route('password.request') }}">
                                    Lupa Password?
                                </a>
                            @endif
                            <br>
                            Belum punya akun? <a href="{{ route('register') }}">Register</a>
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