@extends('layouts.app')

@section('title', 'Layout & Wiring Diagram - ' . ucfirst($jobdesk->name))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>
        <i class="{{ $jobdesk->icon }}"></i> 
        Layout & Wiring Diagram {{ ucfirst($jobdesk->name) }}
    </h3>
    <a href="{{ route('jobdesk.layout.create', $jobdesk->slug) }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Buat Layout Baru
    </a>
</div>

<div class="row">
    @forelse($layouts as $layout)
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">{{ $layout->nama_layout }}</h6>
                <span class="badge bg-info">{{ $layout->devices->count() }} Device</span>
            </div>
            <div class="card-body">
                @if($layout->image_path)
                    <img src="{{ asset('storage/' . $layout->image_path) }}" 
                         alt="{{ $layout->nama_layout }}" 
                         class="img-fluid rounded" 
                         style="height: 150px; width: 100%; object-fit: cover;">
                @else
                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                         style="height: 150px; background: #f8f9fa;">
                        <i class="bi bi-image fs-1 text-muted"></i>
                    </div>
                @endif
                <p class="mt-2 mb-1 small text-muted">{{ $layout->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <div>
                        <span class="badge bg-success">{{ $layout->connections->count() }} Koneksi</span>
                    </div>
                    <div>
                        <a href="{{ route('jobdesk.layout.design', [$jobdesk->slug, $layout->id]) }}" class="btn btn-sm btn-info">
                            <i class="bi bi-pencil-square"></i> Design
                        </a>
                        <a href="{{ route('jobdesk.layout.show', [$jobdesk->slug, $layout->id]) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-eye"></i> View
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-map fs-1 text-muted"></i>
                <p class="text-muted mt-2">Belum ada layout</p>
                <!-- ✅ PERBAIKAN: Pakai route jobdesk.layout.create -->
                <a href="{{ route('jobdesk.layout.create', $jobdesk->slug) }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Buat Layout Pertama
                </a>
            </div>
        </div>
    </div>
    @endforelse
</div>
{{ $layouts->links() }}
@endsection