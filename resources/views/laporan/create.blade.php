@extends('layouts.app')

@section('title', 'Tambah Laporan - ' . ucfirst($jobdesk->name))

@section('content')
<div class="card">
    <div class="card-header">
        <h5><i class="{{ $jobdesk->icon }}"></i> Form Laporan {{ ucfirst($jobdesk->name) }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('laporan.store', $jobdesk->slug) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <!-- Lokasi -->
                <div class="col-md-6 mb-3">
                    <label for="lokasi_id" class="form-label">
                        <i class="bi bi-geo-alt"></i> Lokasi <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('lokasi_id') is-invalid @enderror" 
                            id="lokasi_id" name="lokasi_id" required>
                        <option value="">Pilih Lokasi</option>
                        @foreach($lokasi as $item)
                            <option value="{{ $item->id }}" {{ old('lokasi_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->kode_lokasi }} - {{ $item->nama_lokasi }}
                            </option>
                        @endforeach
                    </select>
                    @error('lokasi_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Jenis Aktivitas -->
                <div class="col-md-6 mb-3">
                    <label for="jenis_aktivitas" class="form-label">
                        <i class="bi bi-tag"></i> Jenis Aktivitas <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('jenis_aktivitas') is-invalid @enderror" 
                            id="jenis_aktivitas" name="jenis_aktivitas" required>
                        <option value="">Pilih Jenis</option>
                        <option value="perbaikan" {{ old('jenis_aktivitas') == 'perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                        <option value="pemeliharaan" {{ old('jenis_aktivitas') == 'pemeliharaan' ? 'selected' : '' }}>Pemeliharaan</option>
                        <option value="inspeksi" {{ old('jenis_aktivitas') == 'inspeksi' ? 'selected' : '' }}>Inspeksi</option>
                    </select>
                    @error('jenis_aktivitas')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Keterangan -->
                <div class="col-12 mb-3">
                    <label for="keterangan" class="form-label">
                        <i class="bi bi-card-text"></i> Keterangan <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control @error('keterangan') is-invalid @enderror" 
                           id="keterangan" name="keterangan" value="{{ old('keterangan') }}" required>
                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Kendala Kerusakan -->
                <div class="col-md-6 mb-3">
                    <label for="kendala_kerusakan" class="form-label">
                        <i class="bi bi-exclamation-triangle"></i> Kendala Kerusakan
                    </label>
                    <textarea class="form-control @error('kendala_kerusakan') is-invalid @enderror" 
                              id="kendala_kerusakan" name="kendala_kerusakan" rows="2">{{ old('kendala_kerusakan') }}</textarea>
                    @error('kendala_kerusakan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Solusi -->
                <div class="col-md-6 mb-3">
                    <label for="solusi" class="form-label">
                        <i class="bi bi-check-circle"></i> Solusi
                    </label>
                    <textarea class="form-control @error('solusi') is-invalid @enderror" 
                              id="solusi" name="solusi" rows="2">{{ old('solusi') }}</textarea>
                    @error('solusi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Jumlah Rusak -->
                <div class="col-md-4 mb-3">
                    <label for="jumlah_rusak" class="form-label">
                        <i class="bi bi-hash"></i> Jumlah Rusak
                    </label>
                    <input type="number" class="form-control @error('jumlah_rusak') is-invalid @enderror" 
                           id="jumlah_rusak" name="jumlah_rusak" value="{{ old('jumlah_rusak', 0) }}" min="0">
                    @error('jumlah_rusak')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Status Pekerjaan -->
                <div class="col-md-4 mb-3">
                    <label for="status_pekerjaan" class="form-label">
                        <i class="bi bi-circle"></i> Status Pekerjaan <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('status_pekerjaan') is-invalid @enderror" 
                            id="status_pekerjaan" name="status_pekerjaan" required>
                        <option value="pending" {{ old('status_pekerjaan') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="proses" {{ old('status_pekerjaan') == 'proses' ? 'selected' : '' }}>Proses</option>
                        <option value="selesai" {{ old('status_pekerjaan') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    @error('status_pekerjaan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tanggal Laporan -->
                <div class="col-md-4 mb-3">
                    <label for="tanggal_laporan" class="form-label">
                        <i class="bi bi-calendar"></i> Tanggal Laporan <span class="text-danger">*</span>
                    </label>
                    <input type="date" class="form-control @error('tanggal_laporan') is-invalid @enderror" 
                           id="tanggal_laporan" name="tanggal_laporan" 
                           value="{{ old('tanggal_laporan', date('Y-m-d')) }}" required>
                    @error('tanggal_laporan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Pelapor -->
                <div class="col-md-6 mb-3">
                    <label for="pelapor" class="form-label">
                        <i class="bi bi-person"></i> Pelapor <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control @error('pelapor') is-invalid @enderror" 
                           id="pelapor" name="pelapor" value="{{ old('pelapor', auth()->user()->name ?? '') }}" required>
                    @error('pelapor')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Teknisi -->
                <div class="col-md-6 mb-3">
                    <label for="teknisi" class="form-label">
                        <i class="bi bi-tools"></i> Teknisi
                    </label>
                    <input type="text" class="form-control @error('teknisi') is-invalid @enderror" 
                           id="teknisi" name="teknisi" value="{{ old('teknisi') }}">
                    @error('teknisi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Upload Gambar -->
                <div class="col-12 mb-3">
                    <label class="form-label">
                        <i class="bi bi-images"></i> Dokumentasi Gambar
                    </label>
                    <input type="file" class="form-control @error('gambar') is-invalid @enderror" 
                           name="gambar[]" multiple accept="image/*">
                    <small class="text-muted">Upload multiple gambar (JPG, PNG, GIF) maksimal 2MB per file</small>
                    @error('gambar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('laporan.index', $jobdesk->slug) }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan Laporan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection