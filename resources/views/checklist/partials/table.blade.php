<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Lokasi</th>
                <th>Petugas</th>
                <th>Tanggal</th>
                <th>Jumlah Camera</th>
                <th>Status HDD</th>
                <th>Display</th>
                <th>Kamera Mati</th>
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
                <td><span class="badge bg-primary">{{ $item->jumlah_camera }}</span></td>
                <td>
                    <span class="badge bg-{{ $item->status_hdd == 'normal' ? 'success' : 'danger' }}">
                        {{ $item->status_hdd }}
                    </span>
                </td>
                <td>
                    <span class="badge bg-{{ $item->status_display == 'tampil' ? 'success' : ($item->status_display == 'tidak_tampil' ? 'warning' : 'danger') }}">
                        {{ $item->status_display }}
                    </span>
                </td>
                <td>
                    @if($item->jumlah_kamera_mati > 0)
                        <span class="badge bg-danger">{{ $item->jumlah_kamera_mati }}</span>
                    @else
                        <span class="badge bg-success">0</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('checklist.show', $item) }}" class="btn btn-sm btn-info" title="Detail">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="{{ route('checklist.edit', $item) }}" class="btn btn-sm btn-warning" title="Edit">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form action="{{ route('checklist.destroy', $item) }}" method="POST" class="d-inline delete-confirm">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
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
                        <p class="text-muted mt-2">Belum ada data checklist</p>
                        <a href="{{ route('checklist.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle"></i> Tambah Checklist
                        </a>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $checklist->withQueryString()->links() }}