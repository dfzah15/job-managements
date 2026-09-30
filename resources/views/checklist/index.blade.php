@extends('layouts.app')

@section('title', 'Checklist ' . ucfirst($jobdesk->name))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>
        <i class="{{ $jobdesk->icon }}"></i> 
        Checklist {{ ucfirst($jobdesk->name) }}
    </h3>
    <a href="{{ route('jobdesk.checklist.create', $jobdesk->slug) }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Checklist
    </a>
</div>

<!-- Filter dan Pencarian -->
<div class="card mb-3">
    <div class="card-body">
        <form action="{{ route('jobdesk.checklist.index', $jobdesk->slug) }}" method="GET" class="row g-3">
            <div class="row g-2 align-items-center">
                <!-- Input Search -->
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" 
                        placeholder="Cari petugas, lokasi..." value="{{ request('search') }}">
                </div>

                <!-- Dropdown Lokasi -->
                <div class="col-md-3">
                    <select name="lokasi_id" class="form-select">
                        <option value="">Semua Lokasi</option>
                        @foreach($lokasi as $item)
                            <option value="{{ $item->id }}" {{ request('lokasi_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->nama_lokasi }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Input Tanggal -->
                <div class="col-md-2">
                    <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}" placeholder="Tanggal">
                </div>

                <!-- Tombol Cari -->
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Cari
                    </button>
                </div>
                    <div class="col-md-2">
                        <a href="{{ route('jobdesk.checklist.create', $jobdesk->slug) }}" class="btn btn-primary w-100 text-nowrap">
                            <i class="bi bi-plus-circle"></i> Tambah Checklist
                        </a>
                    </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Lokasi</th>
                        <th>Petugas</th>
                        <th>Tanggal</th>
                        <!-- Field dinamis sesuai jobdesk -->
                        @php
                            $templates = App\Models\ChecklistTemplate::where('jobdesk_id', $jobdesk->id)
                                                                     ->orderBy('sort_order')
                                                                     ->limit(4)
                                                                     ->get();
                        @endphp
                        @foreach($templates as $template)
                            <th>{{ Str::limit($template->field_label, 15) }}</th>
                        @endforeach
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($checklist as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <span class="badge bg-info">{{ $item->lokasi->kode_lokasi ?? '-' }}</span>
                            {{ $item->lokasi->nama_lokasi ?? '-' }}
                        </td>
                        <td>{{ $item->petugas_check }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_check)->format('d/m/Y') }}</td>
                        <!-- Tampilkan nilai field dinamis -->
                        @foreach($templates as $template)
                            <td>
                                @php
                                    $value = $item->getField($template->field_name);
                                @endphp
                                @if($template->field_type == 'checkbox')
                                    {!! $value ? '<span class="badge bg-success">✅</span>' : '<span class="badge bg-secondary">❌</span>' !!}
                                @elseif($template->field_type == 'select')
                                    @php
                                        $options = $template->options ?? [];
                                        $label = $options[$value] ?? $value ?? '-';
                                        $isDanger = in_array($value, ['rusak', 'error', 'offline', 'failed', 'down', 'bocor', 'overheat', 'trip', 'putus', 'tidak_normal', 'tidak', 'berlebihan', 'keruh', 'berbau']);
                                        $isWarning = in_array($value, ['warning', 'slow', 'running', 'perlu_ganti', 'kotor', 'rendah', 'kurang', 'berlebihan', 'berasap']);
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
                        @endforeach
                        <td>
                            <span class="badge bg-{{ $item->status == 'normal' ? 'success' : ($item->status == 'warning' ? 'warning' : 'danger') }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('jobdesk.checklist.show', [$jobdesk->slug, $item->id]) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('jobdesk.checklist.edit', [$jobdesk->slug, $item->id]) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                                <form action="{{ route('jobdesk.checklist.destroy', [$jobdesk->slug, $item->id]) }}"
                                    method="POST"
                                    class="d-inline">

                                    @csrf
                                    @method('DELETE')

                                    <button type="button"
                                            class="btn btn-sm btn-danger"
                                            onclick="hapusChecklist(this.form)">

                                        <i class="bi bi-trash"></i>

                                    </button>

                                </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ 6 + $templates->count() }}" class="text-center">
                            <div class="py-4">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                <p class="text-muted mt-2">Belum ada data checklist</p>
                                <!-- <a href="{{ route('jobdesk.checklist.create', $jobdesk->slug) }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-circle"></i> Tambah Checklist
                                </a> -->
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $checklist->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection