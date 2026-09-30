@extends('layouts.app')

@section('title', 'Tambah Lokasi')

@section('content')
<div class="card">
    <div class="card-header">
        <h5><i class="bi bi-plus-circle"></i> Form Tambah Lokasi</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('lokasi.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="kode_lokasi" class="form-label">
                        <i class="bi bi-tag"></i> Kode Lokasi <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control @error('kode_lokasi') is-invalid @enderror" 
                           id="kode_lokasi" name="kode_lokasi" 
                           value="{{ old('kode_lokasi') }}" required 
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
                           value="{{ old('nama_lokasi') }}" required 
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
                              placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
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
                    <i class="bi bi-save"></i> Simpan Lokasi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Informasi Tambahan -->
<div class="card mt-3">
    <div class="card-header">
        <h6><i class="bi bi-info-circle"></i> Informasi Kode Lokasi</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="alert alert-info">
                    <strong>Contoh Format Kode:</strong>
                    <ul class="mb-0 mt-2">
                        <li><code>GA-01</code> = Gedung A Lantai 1</li>
                        <li><code>GA-02</code> = Gedung A Lantai 2</li>
                        <li><code>GB-01</code> = Gedung B Lantai 1</li>
                        <li><code>GP-01</code> = Gudang Pusat</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="alert alert-warning">
                    <strong><i class="bi bi-exclamation-triangle"></i> Catatan:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Kode lokasi harus <strong>unik</strong></li>
                        <li>Gunakan format yang mudah diingat</li>
                        <li>Kode akan digunakan untuk referensi</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection