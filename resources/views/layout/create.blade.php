@extends('layouts.app')

@section('title', 'Buat Layout Baru - ' . ucfirst($jobdesk->name))

@section('content')
<div class="card">
    <div class="card-header">
        <h5><i class="bi bi-plus-circle"></i> Buat Layout Baru</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('jobdesk.layout.store', $jobdesk->slug) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Layout <span class="text-danger">*</span></label>
                    <input type="text" name="nama_layout" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Deskripsi</label>
                    <input type="text" name="deskripsi" class="form-control">
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label">Upload Denah (opsional)</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <small class="text-muted">Format: JPG, PNG, SVG. Maksimal 5MB</small>
                </div>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="{{ route('jobdesk.layout.index', $jobdesk->slug) }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Buat Layout</button>
            </div>
        </form>
    </div>
</div>
@endsection