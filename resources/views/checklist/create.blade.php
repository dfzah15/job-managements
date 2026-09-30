@extends('layouts.app')

@section('title', 'Tambah Checklist ' . ucfirst($jobdesk->name))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex align-items-center">
                        <i class="{{ $jobdesk->icon }} me-2 fs-4"></i>
                        <h5 class="mb-0">Form Checklist {{ ucfirst($jobdesk->name) }}</h5>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <form action="{{ route('jobdesk.checklist.store', $jobdesk->slug) }}" method="POST">
                        @csrf
                        
                        <!-- Informasi Utama -->
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <h6 class="border-bottom pb-2 text-secondary">
                                    <i class="fas fa-info-circle me-1"></i> Informasi Dasar
                                </h6>
                            </div>
                            
                            <!-- Lokasi -->
                            <div class="col-md-6">
                                <label for="lokasi_id" class="form-label fw-semibold">
                                    Lokasi <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('lokasi_id') is-invalid @enderror" 
                                        id="lokasi_id" name="lokasi_id" required>
                                    <option value="">-- Pilih Lokasi --</option>
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

                            <!-- Inventaris -->
                            <div class="col-md-6">
                                <label for="inventaris_id" class="form-label fw-semibold">
                                    Inventaris
                                </label>
                                <select class="form-select @error('inventaris_id') is-invalid @enderror" 
                                        id="inventaris_id" name="inventaris_id">
                                    <option value="">-- Pilih Inventaris --</option>
                                    @foreach($inventaris as $item)
                                        <option value="{{ $item->id }}" {{ old('inventaris_id') == $item->id ? 'selected' : '' }}>
                                            {{ $item->nama_inventaris }} - {{ $item->jenis }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('inventaris_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tanggal Check -->
                            <div class="col-md-4">
                                <label for="tanggal_check" class="form-label fw-semibold">
                                    Tanggal Check <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control @error('tanggal_check') is-invalid @enderror" 
                                       id="tanggal_check" name="tanggal_check" 
                                       value="{{ old('tanggal_check', date('Y-m-d')) }}" required>
                                @error('tanggal_check')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Waktu Check -->
                            <div class="col-md-4">
                                <label for="waktu_check" class="form-label fw-semibold">
                                    Waktu Check <span class="text-danger">*</span>
                                </label>
                                <input type="time" class="form-control @error('waktu_check') is-invalid @enderror" 
                                       id="waktu_check" name="waktu_check" 
                                       value="{{ old('waktu_check', date('H:i')) }}" required>
                                @error('waktu_check')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Petugas Check -->
                            <div class="col-md-4">
                                <label for="petugas_check" class="form-label fw-semibold">
                                    Petugas Check <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('petugas_check') is-invalid @enderror" 
                                       id="petugas_check" name="petugas_check" 
                                       value="{{ old('petugas_check', auth()->user()->name ?? '') }}" required>
                                @error('petugas_check')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Checklist Dinamis -->
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <h6 class="border-bottom pb-2 text-secondary">
                                    <i class="fas fa-clipboard-list me-1"></i> Data Checklist
                                </h6>
                            </div>
                            
                            @foreach($templates as $template)
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <label class="form-label fw-semibold">
                                        {{ $template->field_label }}
                                        @if($template->is_required)
                                            <span class="text-danger">*</span>
                                        @endif
                                    </label>

                                    @switch($template->field_type)
                                        @case('text')
                                            <input type="text" class="form-control" 
                                                   name="values[{{ $template->field_name }}]" 
                                                   value="{{ old('values.' . $template->field_name, $template->default_value) }}"
                                                   placeholder="{{ $template->placeholder ?? '' }}">
                                            @break

                                        @case('number')
                                            <input type="number" class="form-control" 
                                                   name="values[{{ $template->field_name }}]" 
                                                   value="{{ old('values.' . $template->field_name, $template->default_value) }}"
                                                   placeholder="{{ $template->placeholder ?? '' }}"
                                                   step="any">
                                            @break

                                        @case('select')
                                            <select class="form-select" name="values[{{ $template->field_name }}]">
                                                <option value="">-- Pilih --</option>
                                                @foreach($template->options as $key => $label)
                                                    <option value="{{ $key }}" {{ old('values.' . $template->field_name, $template->default_value) == $key ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @break

                                        @case('checkbox')
                                            <div class="form-check mt-2">
                                                <input type="checkbox" class="form-check-input" 
                                                       name="values[{{ $template->field_name }}]" value="1"
                                                       id="{{ $template->field_name }}"
                                                       {{ old('values.' . $template->field_name, $template->default_value) ? 'checked' : '' }}>
                                                <label class="form-check-label fw-normal" for="{{ $template->field_name }}">
                                                    {{ $template->field_label }}
                                                </label>
                                            </div>
                                            @break

                                        @case('textarea')
                                            <textarea class="form-control" name="values[{{ $template->field_name }}]" 
                                                      rows="2" placeholder="{{ $template->placeholder ?? '' }}">{{ old('values.' . $template->field_name, $template->default_value) }}</textarea>
                                            @break

                                        @default
                                            <input type="text" class="form-control" 
                                                   name="values[{{ $template->field_name }}]" 
                                                   value="{{ old('values.' . $template->field_name, $template->default_value) }}">
                                    @endswitch

                                    @if($template->help_text)
                                        <small class="text-muted d-block mt-1">
                                            <i class="fas fa-info-circle fa-xs"></i> {{ $template->help_text }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Catatan -->
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <h6 class="border-bottom pb-2 text-secondary">
                                    <i class="fas fa-pencil-alt me-1"></i> Informasi Tambahan
                                </h6>
                            </div>
                            
                            <div class="col-12">
                                <label for="catatan" class="form-label fw-semibold">
                                    Catatan
                                </label>
                                <textarea class="form-control" id="catatan" name="catatan" rows="3" 
                                          placeholder="Tambahkan catatan jika diperlukan...">{{ old('catatan') }}</textarea>
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="row">
                            <div class="col-12">
                                <hr>
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('jobdesk.checklist.index', $jobdesk->slug) }}" 
                                       class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-1"></i> Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="fas fa-save me-1"></i> Simpan Checklist
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection