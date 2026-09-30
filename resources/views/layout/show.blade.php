@extends('layouts.app')

@section('title', 'View Layout - ' . $layout->nama_layout)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>
        <i class="{{ $jobdesk->icon }}"></i> 
        {{ $layout->nama_layout }}
    </h3>
    <div>
        <a href="{{ route('jobdesk.layout.design', [$jobdesk->slug, $layout->id]) }}" class="btn btn-info">
            <i class="bi bi-pencil-square"></i> Edit Design
        </a>
        <a href="{{ route('jobdesk.layout.index', $jobdesk->slug) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6>Denah & Layout</h6>
            </div>
            <div class="card-body p-0">
                <div class="position-relative" style="min-height: 600px; background: #f8f9fa; overflow: auto;">
                    @if($layout->image_path)
                        <img src="{{ asset('storage/' . $layout->image_path) }}" 
                             alt="Denah" 
                             style="width: 100%; height: auto;">
                    @else
                        <div class="d-flex align-items-center justify-content-center" style="min-height: 400px;">
                            <p class="text-muted">Belum ada denah diupload</p>
                        </div>
                    @endif
                    
                    <!-- Devices di atas denah -->
                    @foreach($layout->devices as $device)
                    <div style="position: absolute; left: {{ $device->pos_x }}px; top: {{ $device->pos_y }}px; 
                                background: {{ $device->color }}; 
                                padding: 8px 12px; border-radius: 6px; color: white; 
                                font-size: 12px; font-weight: bold;
                                transform: rotate({{ $device->rotation }}deg);
                                display: flex; flex-direction: column; align-items: center;
                                min-width: 60px; border: 2px solid rgba(255,255,255,0.3);
                                box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                        <i class="{{ $device->icon }} fs-4"></i>
                        <span style="font-size: 10px; text-align: center;">{{ $device->nama_device }}</span>
                    </div>
                    @endforeach
                    
                    <!-- Connections -->
                    <svg style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none;">
                        @foreach($layout->connections as $conn)
                            @php
                                $from = $layout->devices->where('id', $conn->device_from_id)->first();
                                $to = $layout->devices->where('id', $conn->device_to_id)->first();
                            @endphp
                            @if($from && $to)
                                <line x1="{{ $from->pos_x + 30 }}" y1="{{ $from->pos_y + 30 }}" 
                                      x2="{{ $to->pos_x + 30 }}" y2="{{ $to->pos_y + 30 }}"
                                      stroke="{{ $conn->warna }}" 
                                      stroke-width="3"
                                      stroke-dasharray="{{ $conn->tipe_kabel == 'fiber' ? '10,5' : '5,5' }}"
                                      stroke-linecap="round"/>
                                <text x="{{ (($from->pos_x + $to->pos_x) / 2) + 30 }}" 
                                      y="{{ (($from->pos_y + $to->pos_y) / 2) - 10 + 30 }}"
                                      font-size="10" fill="#333" text-anchor="middle">
                                    {{ $conn->panjang_meter ? $conn->panjang_meter . 'm' : '' }}
                                </text>
                            @endif
                        @endforeach
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection