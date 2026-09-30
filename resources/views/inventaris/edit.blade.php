@extends('layouts.app')

@section('title', 'Edit Inventaris')

@section('content')
<div class="card">
    <div class="card-header">
        <h5><i class="bi bi-pencil"></i> Edit Inventaris</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('inventaris.update', $inventaris->id) }}" method="POST">
            @csrf
            @method('PUT')
            
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
                            <option value="{{ $item->id }}" {{ old('lokasi_id', $inventaris->lokasi_id) == $item->id ? 'selected' : '' }}>
                                {{ $item->kode_lokasi }} - {{ $item->nama_lokasi }}
                            </option>
                        @endforeach
                    </select>
                    @error('lokasi_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Nama Inventaris -->
                <div class="col-md-6 mb-3">
                    <label for="nama_inventaris" class="form-label">
                        <i class="bi bi-tag"></i> Nama Inventaris <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control @error('nama_inventaris') is-invalid @enderror" 
                           id="nama_inventaris" name="nama_inventaris" 
                           value="{{ old('nama_inventaris', $inventaris->nama_inventaris) }}" required>
                    @error('nama_inventaris')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Jenis -->
                <div class="col-md-4 mb-3">
                    <label for="jenis" class="form-label">
                        <i class="bi bi-list-ul"></i> Jenis <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('jenis') is-invalid @enderror" 
                            id="jenis" name="jenis" required>
                        <option value="">Pilih Jenis</option>
                        <option value="cctv" {{ old('jenis', $inventaris->jenis) == 'cctv' ? 'selected' : '' }}>CCTV</option>
                        <option value="dvr" {{ old('jenis', $inventaris->jenis) == 'dvr' ? 'selected' : '' }}>DVR</option>
                        <option value="monitor" {{ old('jenis', $inventaris->jenis) == 'monitor' ? 'selected' : '' }}>Monitor</option>
                        <option value="kabel" {{ old('jenis', $inventaris->jenis) == 'kabel' ? 'selected' : '' }}>Kabel</option>
                        <option value="konektor" {{ old('jenis', $inventaris->jenis) == 'konektor' ? 'selected' : '' }}>Konektor</option>
                    </select>
                    @error('jenis')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Merk -->
                <div class="col-md-4 mb-3">
                    <label for="merk" class="form-label">
                        <i class="bi bi-building"></i> Merk
                    </label>
                    <input type="text" class="form-control @error('merk') is-invalid @enderror" 
                           id="merk" name="merk" value="{{ old('merk', $inventaris->merk) }}">
                    @error('merk')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Model -->
                <div class="col-md-4 mb-3">
                    <label for="model" class="form-label">
                        <i class="bi bi-code-square"></i> Model
                    </label>
                    <input type="text" class="form-control @error('model') is-invalid @enderror" 
                           id="model" name="model" value="{{ old('model', $inventaris->model) }}">
                    @error('model')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Jumlah -->
                <div class="col-md-4 mb-3">
                    <label for="jumlah" class="form-label">
                        <i class="bi bi-hash"></i> Jumlah <span class="text-danger">*</span>
                    </label>
                    <input type="number" class="form-control @error('jumlah') is-invalid @enderror" 
                           id="jumlah" name="jumlah" value="{{ old('jumlah', $inventaris->jumlah) }}" required min="1">
                    @error('jumlah')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tanggal Pemasangan -->
                <div class="col-md-4 mb-3">
                    <label for="tanggal_pemasangan" class="form-label">
                        <i class="bi bi-calendar-plus"></i> Tanggal Pemasangan
                    </label>
                    <input type="date" class="form-control @error('tanggal_pemasangan') is-invalid @enderror" 
                           id="tanggal_pemasangan" name="tanggal_pemasangan" 
                           value="{{ old('tanggal_pemasangan', $inventaris->tanggal_pemasangan) }}">
                    @error('tanggal_pemasangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Status -->
                <div class="col-md-4 mb-3">
                    <label for="status" class="form-label">
                        <i class="bi bi-circle"></i> Status <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('status') is-invalid @enderror" 
                            id="status" name="status" required>
                        <option value="aktif" {{ old('status', $inventaris->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="rusak" {{ old('status', $inventaris->status) == 'rusak' ? 'selected' : '' }}>Rusak</option>
                        <option value="perbaikan" {{ old('status', $inventaris->status) == 'perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                        <option value="nonaktif" {{ old('status', $inventaris->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Spesifikasi -->
                <div class="col-12 mb-3">
                    <label for="spesifikasi" class="form-label">
                        <i class="bi bi-gear"></i> Spesifikasi
                    </label>
                    <textarea class="form-control @error('spesifikasi') is-invalid @enderror" 
                              id="spesifikasi" name="spesifikasi" rows="3">{{ old('spesifikasi', $inventaris->spesifikasi) }}</textarea>
                    @error('spesifikasi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Tombol -->
            <div class="d-flex justify-content-between">
                <a href="{{ route('inventaris.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Update Inventaris
                </button>
            </div>
        </form>
    </div>
</div>
@endsection