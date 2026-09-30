@extends('layouts.app')

@section('title', 'Edit Laporan Aktivitas')

@section('content')
<div class="card">
    <div class="card-header">
        <h5><i class="bi bi-pencil"></i> Edit Laporan Aktivitas</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('laporan.update', $laporan) }}" method="POST" enctype="multipart/form-data">
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
                            <option value="{{ $item->id }}" {{ old('lokasi_id', $laporan->lokasi_id) == $item->id ? 'selected' : '' }}>
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
                        <option value="perbaikan" {{ old('jenis_aktivitas', $laporan->jenis_aktivitas) == 'perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                        <option value="pemeliharaan" {{ old('jenis_aktivitas', $laporan->jenis_aktivitas) == 'pemeliharaan' ? 'selected' : '' }}>Pemeliharaan</option>
                        <option value="inspeksi" {{ old('jenis_aktivitas', $laporan->jenis_aktivitas) == 'inspeksi' ? 'selected' : '' }}>Inspeksi</option>
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
                           id="keterangan" name="keterangan" value="{{ old('keterangan', $laporan->keterangan) }}" required>
                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Checklist Pengecekan -->
                <div class="col-12 mb-3">
                    <label class="form-label">
                        <i class="bi bi-list-check"></i> Checklist Pengecekan
                    </label>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="checklist_camera" 
                                       name="checklist_camera" value="1" {{ old('checklist_camera', $laporan->checklist_camera) ? 'checked' : '' }}>
                                <label class="form-check-label" for="checklist_camera">
                                    <i class="bi bi-camera"></i> Camera
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="checklist_dvr" 
                                       name="checklist_dvr" value="1" {{ old('checklist_dvr', $laporan->checklist_dvr) ? 'checked' : '' }}>
                                <label class="form-check-label" for="checklist_dvr">
                                    <i class="bi bi-hdd-stack"></i> DVR
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="checklist_monitor" 
                                       name="checklist_monitor" value="1" {{ old('checklist_monitor', $laporan->checklist_monitor) ? 'checked' : '' }}>
                                <label class="form-check-label" for="checklist_monitor">
                                    <i class="bi bi-display"></i> Monitor
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="checklist_kabel_camera" 
                                       name="checklist_kabel_camera" value="1" {{ old('checklist_kabel_camera', $laporan->checklist_kabel_camera) ? 'checked' : '' }}>
                                <label class="form-check-label" for="checklist_kabel_camera">
                                    <i class="bi bi-arrow-right-circle"></i> Kabel Camera
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="checklist_kabel_listrik" 
                                       name="checklist_kabel_listrik" value="1" {{ old('checklist_kabel_listrik', $laporan->checklist_kabel_listrik) ? 'checked' : '' }}>
                                <label class="form-check-label" for="checklist_kabel_listrik">
                                    <i class="bi bi-power"></i> Kabel Listrik
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="checklist_konektor" 
                                       name="checklist_konektor" value="1" {{ old('checklist_konektor', $laporan->checklist_konektor) ? 'checked' : '' }}>
                                <label class="form-check-label" for="checklist_konektor">
                                    <i class="bi bi-plug"></i> Konektor
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kendala Kerusakan -->
                <div class="col-md-6 mb-3">
                    <label for="kendala_kerusakan" class="form-label">
                        <i class="bi bi-exclamation-triangle"></i> Kendala Kerusakan
                    </label>
                    <textarea class="form-control @error('kendala_kerusakan') is-invalid @enderror" 
                              id="kendala_kerusakan" name="kendala_kerusakan" rows="2">{{ old('kendala_kerusakan', $laporan->kendala_kerusakan) }}</textarea>
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
                              id="solusi" name="solusi" rows="2">{{ old('solusi', $laporan->solusi) }}</textarea>
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
                           id="jumlah_rusak" name="jumlah_rusak" value="{{ old('jumlah_rusak', $laporan->jumlah_rusak) }}" min="0">
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
                        <option value="pending" {{ old('status_pekerjaan', $laporan->status_pekerjaan) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="proses" {{ old('status_pekerjaan', $laporan->status_pekerjaan) == 'proses' ? 'selected' : '' }}>Proses</option>
                        <option value="selesai" {{ old('status_pekerjaan', $laporan->status_pekerjaan) == 'selesai' ? 'selected' : '' }}>Selesai</option>
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
                           value="{{ old('tanggal_laporan', $laporan->tanggal_laporan->format('Y-m-d')) }}" required>
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
                           id="pelapor" name="pelapor" value="{{ old('pelapor', $laporan->pelapor) }}" required>
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
                           id="teknisi" name="teknisi" value="{{ old('teknisi', $laporan->teknisi) }}">
                    @error('teknisi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Upload Gambar -->
                <div class="col-12 mb-3">
                    <label class="form-label">
                        <i class="bi bi-images"></i> Tambah Dokumentasi Gambar
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
                <a href="{{ route('laporan.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Update Laporan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection