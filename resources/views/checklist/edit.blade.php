@extends('layouts.app')

@section('title', 'Edit Checklist ' . ucfirst($jobdesk->name))

@section('content')
<div class="card">
    <div class="card-header">
        <h5><i class="{{ $jobdesk->icon }}"></i> Edit Checklist {{ ucfirst($jobdesk->name) }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('jobdesk.checklist.update', [$jobdesk->slug, $checklist->id]) }}" method="POST">
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
                            <option value="{{ $item->id }}" {{ old('lokasi_id', $checklist->lokasi_id) == $item->id ? 'selected' : '' }}>
                                {{ $item->kode_lokasi }} - {{ $item->nama_lokasi }}
                            </option>
                        @endforeach
                    </select>
                    @error('lokasi_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Inventaris -->
                <div class="col-md-6 mb-3">
                    <label for="inventaris_id" class="form-label">
                        <i class="bi bi-box"></i> Inventaris
                    </label>
                    <select class="form-select @error('inventaris_id') is-invalid @enderror" 
                            id="inventaris_id" name="inventaris_id">
                        <option value="">Pilih Inventaris</option>
                        @foreach($inventaris as $item)
                            <option value="{{ $item->id }}" {{ old('inventaris_id', $checklist->inventaris_id) == $item->id ? 'selected' : '' }}>
                                {{ $item->nama_inventaris }} - {{ $item->jenis }} ({{ $item->merk ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                    @error('inventaris_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tanggal Check -->
                <div class="col-md-4 mb-3">
                    <label for="tanggal_check" class="form-label">
                        <i class="bi bi-calendar"></i> Tanggal Check <span class="text-danger">*</span>
                    </label>
                    <input type="date" class="form-control @error('tanggal_check') is-invalid @enderror" 
                           id="tanggal_check" name="tanggal_check" 
                           value="{{ old('tanggal_check', $checklist->tanggal_check->format('Y-m-d')) }}" required>
                    @error('tanggal_check')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Waktu Check -->
                <div class="col-md-4 mb-3">
                    <label for="waktu_check" class="form-label">
                        <i class="bi bi-clock"></i> Waktu Check <span class="text-danger">*</span>
                    </label>
                    <input type="time" class="form-control @error('waktu_check') is-invalid @enderror" 
                           id="waktu_check" name="waktu_check" 
                           value="{{ old('waktu_check', $checklist->waktu_check) }}" required>
                    @error('waktu_check')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Petugas Check -->
                <div class="col-md-4 mb-3">
                    <label for="petugas_check" class="form-label">
                        <i class="bi bi-person"></i> Petugas Check <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control @error('petugas_check') is-invalid @enderror" 
                           id="petugas_check" name="petugas_check" 
                           value="{{ old('petugas_check', $checklist->petugas_check) }}" required>
                    @error('petugas_check')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- ============================================ -->
                <!-- DINAMIS CHECKLIST PER JOBDESK -->
                <!-- ============================================ -->
                @foreach($templates as $template)
                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        {{ $template->field_label }}
                        @if($template->is_required)
                            <span class="text-danger">*</span>
                        @endif
                    </label>

                    @php
                        $oldValue = old('values.' . $template->field_name, $checklist->getField($template->field_name));
                    @endphp

                    @switch($template->field_type)
                        @case('text')
                            <input type="text" class="form-control" 
                                   name="values[{{ $template->field_name }}]" 
                                   value="{{ $oldValue }}"
                                   placeholder="{{ $template->placeholder ?? '' }}">
                            @break

                        @case('number')
                            <input type="number" class="form-control" 
                                   name="values[{{ $template->field_name }}]" 
                                   value="{{ $oldValue }}"
                                   placeholder="{{ $template->placeholder ?? '' }}"
                                   step="any">
                            @break

                        @case('select')
                            <select class="form-select" name="values[{{ $template->field_name }}]">
                                <option value="">Pilih</option>
                                @foreach($template->options as $key => $label)
                                    <option value="{{ $key }}" {{ $oldValue == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @break

                        @case('checkbox')
                            <div class="form-check mt-2">
                                <input type="checkbox" class="form-check-input" 
                                       name="values[{{ $template->field_name }}]" value="1"
                                       id="edit_{{ $template->field_name }}"
                                       {{ $oldValue ? 'checked' : '' }}>
                                <label class="form-check-label" for="edit_{{ $template->field_name }}">
                                    Ya
                                </label>
                            </div>
                            @break

                        @case('textarea')
                            <textarea class="form-control" name="values[{{ $template->field_name }}]" 
                                      rows="2" placeholder="{{ $template->placeholder ?? '' }}">{{ $oldValue }}</textarea>
                            @break

                        @default
                            <input type="text" class="form-control" name="values[{{ $template->field_name }}]" value="{{ $oldValue }}">
                    @endswitch

                    @if($template->help_text)
                        <small class="text-muted d-block">{{ $template->help_text }}</small>
                    @endif
                </div>
                @endforeach

                <!-- Catatan -->
                <div class="col-12 mb-3">
                    <label for="catatan" class="form-label">
                        <i class="bi bi-sticky"></i> Catatan
                    </label>
                    <textarea class="form-control @error('catatan') is-invalid @enderror" 
                              id="catatan" name="catatan" rows="3">{{ old('catatan', $checklist->catatan) }}</textarea>
                    @error('catatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Tombol -->
            <div class="d-flex justify-content-between">
                <a href="{{ route('jobdesk.checklist.index', $jobdesk->slug) }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Update Checklist
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Informasi Tambahan -->
<div class="card mt-3">
    <div class="card-header">
        <h6><i class="bi bi-info-circle"></i> Informasi Checklist</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="alert alert-info">
                    <strong><i class="bi bi-check-circle"></i> Kriteria Normal:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Semua item dalam kondisi normal</li>
                        <li>Tidak ada indikasi kerusakan</li>
                        <li>Semua nilai dalam batas normal</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="alert alert-warning">
                    <strong><i class="bi bi-exclamation-triangle"></i> Perlu Perhatian:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Ada item yang perlu diperiksa ulang</li>
                        <li>Beberapa nilai di luar batas normal</li>
                        <li>Perlu tindakan pencegahan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection