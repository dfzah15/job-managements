@extends('layouts.app')

@section('title', 'Detail Checklist ' . ucfirst($jobdesk->name))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>
        <i class="{{ $jobdesk->icon }}"></i> 
        Detail Checklist {{ ucfirst($jobdesk->name) }}
    </h3>
    <div>
        <a href="{{ route('jobdesk.checklist.edit', [$jobdesk->slug, $checklist->id]) }}" class="btn btn-primary">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('jobdesk.checklist.index', $jobdesk->slug) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <!-- Informasi Utama -->
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">
                <h6><i class="bi bi-info-circle"></i> Informasi Checklist</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="35%">ID Checklist</th>
                        <td><span class="badge bg-primary">#{{ $checklist->id }}</span></td>
                    </tr>
                    <tr>
                        <th>Lokasi</th>
                        <td>
                            <span class="badge bg-info">{{ $checklist->lokasi->kode_lokasi ?? '-' }}</span>
                            {{ $checklist->lokasi->nama_lokasi ?? '-' }}
                        </td>
                    </tr>
                    <tr>
                        <th>Inventaris</th>
                        <td>{{ $checklist->inventaris->nama_inventaris ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Petugas</th>
                        <td>{{ $checklist->petugas_check }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Check</th>
                        <td>{{ $checklist->tanggal_check->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Waktu Check</th>
                        <td>{{ $checklist->waktu_check ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge bg-{{ $checklist->status == 'normal' ? 'success' : ($checklist->status == 'warning' ? 'warning' : 'danger') }}">
                                {{ ucfirst($checklist->status) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Dibuat</th>
                        <td>{{ $checklist->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Hasil Checklist -->
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">
                <h6><i class="bi bi-list-check"></i> Hasil Checklist</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    @foreach($templates as $template)
                    <tr>
                        <th width="50%">{{ $template->field_label }}</th>
                        <td>
                            @php
                                $value = $checklist->getField($template->field_name);
                            @endphp
                            @if($template->field_type == 'checkbox')
                                {!! $value ? '<span class="badge bg-success">✅ Ya</span>' : '<span class="badge bg-secondary">❌ Tidak</span>' !!}
                            @elseif($template->field_type == 'select')
                                @php
                                    $options = $template->options ?? [];
                                    $label = $options[$value] ?? $value ?? '-';
                                    $isWarning = in_array($value, ['tidak_normal', 'rusak', 'error', 'offline', 'failed', 'down', 'rendah', 'kotor', 'bocor', 'overheat', 'trip', 'aus', 'putus', 'keruh', 'berbau', 'berlebihan', 'tidak', 'kurang', 'berlebihan']);
                                    $isDanger = in_array($value, ['rusak', 'error', 'offline', 'failed', 'down', 'bocor', 'overheat', 'trip', 'putus']);
                                @endphp
                                @if($isDanger)
                                    <span class="badge bg-danger">{{ $label }}</span>
                                @elseif($isWarning)
                                    <span class="badge bg-warning">{{ $label }}</span>
                                @else
                                    <span class="badge bg-success">{{ $label }}</span>
                                @endif
                            @else
                                {{ $value ?? '-' }}
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>

        <!-- Catatan -->
        @if($checklist->catatan)
        <div class="card mt-3">
            <div class="card-header">
                <h6><i class="bi bi-sticky"></i> Catatan</h6>
            </div>
            <div class="card-body">
                {{ $checklist->catatan }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection