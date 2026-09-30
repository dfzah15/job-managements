@extends('layouts.app')

@section('title', 'Data Inventaris')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3><i class="bi bi-box"></i> Data Inventaris</h3>
    <a href="{{ route('inventaris.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Inventaris
    </a>
</div>

<!-- Filter dan Pencarian -->
<div class="card mb-3">
    <div class="card-body">
        <form action="{{ route('inventaris.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" 
                       placeholder="Cari nama, merk, model..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="jobdesk_id" class="form-select">
                    <option value="">Semua Jobdesk</option>
                    @foreach($jobdesks as $item)
                        <option value="{{ $item->id }}" {{ request('jobdesk_id') == $item->id ? 'selected' : '' }}>
                            {{ $item->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="jenis" class="form-select">
                    <option value="">Semua Jenis</option>
                    <option value="cctv" {{ request('jenis') == 'cctv' ? 'selected' : '' }}>CCTV</option>
                    <option value="dvr" {{ request('jenis') == 'dvr' ? 'selected' : '' }}>DVR</option>
                    <option value="monitor" {{ request('jenis') == 'monitor' ? 'selected' : '' }}>Monitor</option>
                    <option value="server" {{ request('jenis') == 'server' ? 'selected' : '' }}>Server</option>
                    <option value="genset" {{ request('jenis') == 'genset' ? 'selected' : '' }}>Genset</option>
                    <option value="pompa" {{ request('jenis') == 'pompa' ? 'selected' : '' }}>Pompa</option>
                    <option value="panel" {{ request('jenis') == 'panel' ? 'selected' : '' }}>Panel</option>
                    <option value="kabel" {{ request('jenis') == 'kabel' ? 'selected' : '' }}>Kabel</option>
                    <option value="konektor" {{ request('jenis') == 'konektor' ? 'selected' : '' }}>Konektor</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="rusak" {{ request('status') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                    <option value="perbaikan" {{ request('status') == 'perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Cari
                </button>
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
                        <th>Jobdesk</th>
                        <th>Lokasi</th>
                        <th>Kode</th>
                        <th>Nama Inventaris</th>
                        <th>Jenis</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inventaris as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <span class="badge bg-{{ $item->jobdesk->color ?? 'secondary' }}">
                                <i class="{{ $item->jobdesk->icon ?? 'bi bi-briefcase' }}"></i>
                                {{ $item->jobdesk->name ?? '-' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $item->lokasi->kode_lokasi ?? '-' }}</span>
                            {{ $item->lokasi->nama_lokasi ?? '-' }}
                        </td>
                        <td><code>{{ $item->kode_inventaris }}</code></td>
                        <td>{{ $item->nama_inventaris }}</td>
                        <td>
                            <span class="badge bg-{{ $item->jenis_badge }}">{{ strtoupper($item->jenis) }}</span>
                        </td>
                        <td><span class="badge bg-primary">{{ $item->jumlah }}</span></td>
                        <td>
                            <span class="badge bg-{{ $item->status_badge }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('inventaris.show', $item->id) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('inventaris.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('inventaris.destroy', $item->id) }}" method="POST" class="d-inline delete-confirm">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">
                            <div class="py-4">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                <p class="text-muted mt-2">Belum ada data inventaris</p>
                                <a href="{{ route('inventaris.create') }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-circle"></i> Tambah Inventaris
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $inventaris->withQueryString()->links() }}
    </div>
</div>
@endsection