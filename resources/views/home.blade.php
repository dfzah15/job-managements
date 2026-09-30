@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-house-door"></i> Selamat Datang</h5>
            </div>
            <div class="card-body text-center py-5">
                <h2>Selamat Datang di Job Management System</h2>
                <p class="text-muted">Sistem manajemen perbaikan dan pemeliharaan inventaris</p>
                <div class="mt-4">
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">
                        <i class="bi bi-speedometer2"></i> Ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection