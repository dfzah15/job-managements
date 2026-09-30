@extends('layouts.app')

@section('title', 'Edit Lokasi')

@section('content')
<div class="card">
    <div class="card-header">
        <h5><i class="bi bi-pencil"></i> Edit Lokasi</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('lokasi.update', $lokasi) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="kode_lokasi" class="form-label">
                        <i class="bi bi-tag"></i> Kode Lokasi <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control @error('kode_lokasi') is-invalid @enderror" 
                           id="kode_lokasi" name="kode_lokasi" 
                           value="{{ old('kode_lokasi', $lokasi->kode_lokasi) }}" required 
                           placeholder="Contoh: GA-01">
                    <small class="text-muted">Kode unik untuk lokasi</small>
                    @error('kode_lokasi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="nama_lokasi" class="form-label">
                        <i class="bi bi-building"></i> Nama Lokasi <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control @error('nama_lokasi') is-invalid @enderror" 
                           id="nama_lokasi" name="nama_lokasi" 
                           value="{{ old('nama_lokasi', $lokasi->nama_lokasi) }}" required 
                           placeholder="Contoh: Gedung A - Lantai 1">
                    @error('nama_lokasi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 mb-3">
                    <label for="alamat" class="form-label">
                        <i class="bi bi-geo-alt"></i> Alamat
                    </label>
                    <textarea class="form-control @error('alamat') is-invalid @enderror" 
                              id="alamat" name="alamat" rows="3" 
                              placeholder="Masukkan alamat lengkap">{{ old('alamat', $lokasi->alamat) }}</textarea>
                    @error('alamat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('lokasi.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Update Lokasi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Informasi Lokasi -->
<div class="card mt-3">
    <div class="card-header">
        <h6><i class="bi bi-info-circle"></i> Informasi Lokasi</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="text-center">
                    <h5>{{ $lokasi->inventaris_count ?? 0 }}</h5>
                    <small class="text-muted">Total Inventaris</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <h5>{{ $lokasi->checklist_cctv_count ?? 0 }}</h5>
                    <small class="text-muted">Total Checklist</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <h5>{{ $lokasi->laporan_aktivitas_count ?? 0 }}</h5>
                    <small class="text-muted">Total Laporan</small>
                </div>
            </div>
        </div>
        <div class="alert alert-info mt-3">
            <i class="bi bi-clock-history"></i>
            Dibuat: {{ $lokasi->created_at->format('d F Y H:i') }} | 
            Terakhir Update: {{ $lokasi->updated_at->format('d F Y H:i') }}
        </div>
    </div>
</div>
@endsection