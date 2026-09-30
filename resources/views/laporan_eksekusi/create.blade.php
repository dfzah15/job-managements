@extends('layouts.app')

@section('title', 'Tambah Eksekusi - ' . ucfirst($jobdesk->name))

@section('content')
<div class="card">
    <div class="card-header">
        <h5><i class="{{ $jobdesk->icon }}"></i> Form Tambah Eksekusi {{ ucfirst($jobdesk->name) }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('laporan-eksekusi.store', $jobdesk->slug) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="jobdesk_id" value="{{ $jobdesk->id }}">
            <div class="row">
                <!-- Lokasi -->
                <div class="col-md-6 mb-3">
                    <label for="lokasi_id" class="form-label">Lokasi <span class="text-danger">*</span></label>
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

                <!-- Laporan Aktivitas -->
                <div class="col-md-6 mb-3">
                    <label for="laporan_aktivitas_id" class="form-label">Laporan Aktivitas <span class="text-danger">*</span></label>
                    <select class="form-select @error('laporan_aktivitas_id') is-invalid @enderror" 
                            id="laporan_aktivitas_id" name="laporan_aktivitas_id" required>
                        <option value="">Pilih Laporan</option>
                        @foreach($laporan as $item)
                            <option value="{{ $item->id }}" {{ old('laporan_aktivitas_id') == $item->id ? 'selected' : '' }}>
                                #{{ $item->id }} - {{ $item->keterangan }} ({{ $item->status_pekerjaan }})
                            </option>
                        @endforeach
                    </select>
                    @error('laporan_aktivitas_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Teknisi -->
                <div class="col-md-6 mb-3">
                    <label for="user_id" class="form-label">Teknisi <span class="text-danger">*</span></label>
                    <select class="form-select @error('user_id') is-invalid @enderror" 
                            id="user_id" name="user_id" required>
                        <option value="">Pilih Teknisi</option>
                        @foreach($teknisi as $item)
                            <option value="{{ $item->id }}" {{ old('user_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->name }} ({{ $item->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tanggal Eksekusi -->
                <div class="col-md-6 mb-3">
                    <label for="tanggal_eksekusi" class="form-label">Tanggal Eksekusi <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('tanggal_eksekusi') is-invalid @enderror" 
                           id="tanggal_eksekusi" name="tanggal_eksekusi" 
                           value="{{ old('tanggal_eksekusi', date('Y-m-d')) }}" required>
                    @error('tanggal_eksekusi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Waktu Mulai -->
                <div class="col-md-6 mb-3">
                    <label for="waktu_mulai" class="form-label">Waktu Mulai <span class="text-danger">*</span></label>
                    <input type="time" class="form-control @error('waktu_mulai') is-invalid @enderror" 
                           id="waktu_mulai" name="waktu_mulai" 
                           value="{{ old('waktu_mulai', date('H:i')) }}" required>
                    @error('waktu_mulai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Waktu Selesai -->
                <div class="col-md-6 mb-3">
                    <label for="waktu_selesai" class="form-label">Waktu Selesai</label>
                    <input type="time" class="form-control @error('waktu_selesai') is-invalid @enderror" 
                           id="waktu_selesai" name="waktu_selesai" value="{{ old('waktu_selesai') }}">
                    @error('waktu_selesai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Deskripsi Pekerjaan -->
                <div class="col-12 mb-3">
                    <label for="deskripsi_pekerjaan" class="form-label">Deskripsi Pekerjaan <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('deskripsi_pekerjaan') is-invalid @enderror" 
                              id="deskripsi_pekerjaan" name="deskripsi_pekerjaan" rows="3" required>{{ old('deskripsi_pekerjaan') }}</textarea>
                    @error('deskripsi_pekerjaan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Hasil -->
                <div class="col-12 mb-3">
                    <label for="hasil" class="form-label">Hasil Pekerjaan</label>
                    <textarea class="form-control @error('hasil') is-invalid @enderror" 
                              id="hasil" name="hasil" rows="2">{{ old('hasil') }}</textarea>
                    @error('hasil')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Status Eksekusi -->
                <div class="col-md-6 mb-3">
                    <label for="status_eksekusi" class="form-label">Status Eksekusi <span class="text-danger">*</span></label>
                    <select class="form-select @error('status_eksekusi') is-invalid @enderror" 
                            id="status_eksekusi" name="status_eksekusi" required>
                        <option value="pending" {{ old('status_eksekusi') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="proses" {{ old('status_eksekusi') == 'proses' ? 'selected' : '' }}>Proses</option>
                        <option value="selesai" {{ old('status_eksekusi') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="gagal" {{ old('status_eksekusi') == 'gagal' ? 'selected' : '' }}>Gagal</option>
                    </select>
                    @error('status_eksekusi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Catatan -->
                <div class="col-md-6 mb-3">
                    <label for="catatan" class="form-label">Catatan</label>
                    <textarea class="form-control @error('catatan') is-invalid @enderror" 
                              id="catatan" name="catatan" rows="2">{{ old('catatan') }}</textarea>
                    @error('catatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Upload Gambar -->
                <div class="col-12 mb-3">
                    <label class="form-label">Dokumentasi Gambar</label>
                    <input type="file" class="form-control @error('gambar') is-invalid @enderror" 
                           name="gambar[]" multiple accept="image/*">
                    <small class="text-muted">Upload multiple gambar (JPG, PNG, GIF) maksimal 2MB per file</small>
                    @error('gambar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('laporan-eksekusi.index', $jobdesk->slug) }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan Eksekusi</button>
            </div>
        </form>
    </div>
</div>
@endsection