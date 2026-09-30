@extends('layouts.app')

@section('title', 'Data Lokasi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3><i class="bi bi-geo-alt"></i> Data Lokasi</h3>
    <a href="{{ route('lokasi.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Lokasi
    </a>
</div>

<!-- Filter dan Pencarian -->
<div class="card mb-3">
    <div class="card-body">
        <form action="{{ route('lokasi.index') }}" method="GET" class="row g-3">
            <div class="col-md-8">
                <input type="text" name="search" class="form-control" 
                       placeholder="Cari berdasarkan nama atau kode lokasi..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
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
                        <th>Kode</th>
                        <th>Nama Lokasi</th>
                        <th>Alamat</th>
                        <th>Inventaris</th>
                        <th>Checklist</th>
                        <th>Laporan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lokasi as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><span class="badge bg-info">{{ $item->kode_lokasi }}</span></td>
                        <td>{{ $item->nama_lokasi }}</td>
                        <td>{{ Str::limit($item->alamat, 30) }}</td>
                        <td><span class="badge bg-primary">{{ $item->inventaris_count ?? 0 }}</span></td>
                        <td><span class="badge bg-success">{{ $item->checklist_cctv_count ?? 0 }}</span></td>
                        <td><span class="badge bg-warning">{{ $item->laporan_aktivitas_count ?? 0 }}</span></td>
                        <td>
                            <a href="{{ route('lokasi.show', $item) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('lokasi.edit', $item) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('lokasi.destroy', $item) }}" method="POST" class="d-inline delete-confirm">
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
                        <td colspan="8" class="text-center">
                            <div class="py-4">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                <p class="text-muted mt-2">Belum ada data lokasi</p>
                                <a href="{{ route('lokasi.create') }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-circle"></i> Tambah Lokasi
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $lokasi->withQueryString()->links() }}
    </div>
</div>
@endsection