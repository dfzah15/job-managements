<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Aktivitas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            padding: 15px;
            background: #fff;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #2c3e50;
        }
        .header p {
            color: #7f8c8d;
            font-size: 11px;
            margin-top: 5px;
        }
        .info-laporan {
            background: #f8f9fa;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #3498db;
        }
        .info-laporan table {
            width: 100%;
        }
        .info-laporan td {
            padding: 3px 10px;
            font-size: 10px;
        }
        .info-laporan .label {
            font-weight: bold;
            width: 100px;
        }
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 10px;
        }
        table.data th {
            background: #2c3e50;
            color: white;
            padding: 6px 8px;
            text-align: left;
            border: 1px solid #2c3e50;
        }
        table.data td {
            padding: 5px 8px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        table.data tr:nth-child(even) {
            background: #f8f9fa;
        }
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            display: inline-block;
        }
        .badge-success {
            background: #27ae60;
            color: white;
        }
        .badge-danger {
            background: #e74c3c;
            color: white;
        }
        .badge-warning {
            background: #f39c12;
            color: white;
        }
        .badge-info {
            background: #3498db;
            color: white;
        }
        .badge-secondary {
            background: #95a5a6;
            color: white;
        }
        .kendala-box {
            padding: 4px 8px;
            border-radius: 3px;
            margin: 3px 0;
            font-size: 10px;
        }
        .kendala-danger {
            background: #fde8e8;
            border-left: 3px solid #e74c3c;
        }
        .kendala-success {
            background: #e8f8f0;
            border-left: 3px solid #27ae60;
        }
        .kendala-info {
            background: #e8f4f8;
            border-left: 3px solid #3498db;
        }
        .kendala-warning {
            background: #fef9e8;
            border-left: 3px solid #f39c12;
        }
        .text-center {
            text-align: center;
        }
        .text-muted {
            color: #7f8c8d;
        }
        .gambar-thumb {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 3px;
            border: 1px solid #ddd;
            margin: 1px;
        }
        .gambar-container {
            display: flex;
            flex-wrap: wrap;
            gap: 3px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            font-size: 9px;
            color: #7f8c8d;
        }
        .page-break {
            page-break-after: always;
        }
        .mt-10 { margin-top: 10px; }
        .mb-10 { margin-bottom: 10px; }
        .fw-bold { font-weight: bold; }
        .small { font-size: 9px; }
    </style>
</head>
<body>

    <!-- HEADER -->
    <div class="header">
        <h1>Laporan Aktivitas Perbaikan</h1>
        <p>Dicetak: {{ date('d F Y H:i') }}</p>
    </div>

    <!-- INFORMASI LAPORAN -->
    <div class="info-laporan">
        <table>
            <tr>
                <td class="label">Total Laporan</td>
                <td>: <strong>{{ $laporan->count() }}</strong></td>
                <td class="label">Status Selesai</td>
                <td>: <strong>{{ $laporan->where('status_pekerjaan', 'selesai')->count() }}</strong></td>
            </tr>
            <tr>
                <td class="label">Pending</td>
                <td>: <strong>{{ $laporan->where('status_pekerjaan', 'pending')->count() }}</strong></td>
                <td class="label">Proses</td>
                <td>: <strong>{{ $laporan->where('status_pekerjaan', 'proses')->count() }}</strong></td>
            </tr>
        </table>
    </div>

    <!-- TABLE DATA LAPORAN -->
    <table class="data">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="8%">Lokasi</th>
                <th width="8%">Jenis</th>
                <th width="12%">Keterangan</th>
                <th width="18%">Kendala</th>
                <th width="15%">Solusi</th>
                <th width="8%">Status</th>
                <th width="15%">Gambar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporan as $index => $item)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>
                    <span class="badge badge-info">{{ $item->lokasi->kode_lokasi ?? '-' }}</span>
                    <br>
                    <small>{{ $item->lokasi->nama_lokasi ?? '-' }}</small>
                </td>
                <td>
                    <span class="badge 
                        @if($item->jenis_aktivitas == 'perbaikan') badge-danger
                        @elseif($item->jenis_aktivitas == 'pemeliharaan') badge-warning
                        @else badge-info @endif">
                        {{ ucfirst($item->jenis_aktivitas) }}
                    </span>
                </td>
                <td>{{ $item->keterangan }}</td>
                <td>
                    <div class="kendala-box kendala-danger">
                        {{ $item->kendala_kerusakan ?? '-' }}
                    </div>
                    @if($item->jumlah_rusak > 0)
                        <small class="text-muted">Jumlah rusak: {{ $item->jumlah_rusak }}</small>
                    @endif
                </td>
                <td>
                    <div class="kendala-box kendala-success">
                        {{ $item->solusi ?? 'Belum ada solusi' }}
                    </div>
                </td>
                <td>
                    <span class="badge 
                        @if($item->status_pekerjaan == 'selesai') badge-success
                        @elseif($item->status_pekerjaan == 'pending') badge-warning
                        @else badge-info @endif">
                        {{ ucfirst($item->status_pekerjaan) }}
                    </span>
                    <br>
                    <small>{{ $item->tanggal_laporan->format('d/m/Y') }}</small>
                    <br>
                    <small>Pelapor: {{ $item->pelapor }}</small>
                </td>
                <td>
                    @if(isset($item->gambar) && $item->gambar->count() > 0)
                        <div class="gambar-container">
                            @foreach($item->gambar->take(4) as $gambar)
                                @php
                                    $imagePath = public_path('storage/' . $gambar->path);
                                    $imageExists = file_exists($imagePath);
                                @endphp
                                @if($imageExists)
                                    <img src="{{ public_path('storage/' . $gambar->path) }}" 
                                         alt="Foto" 
                                         class="gambar-thumb"
                                         onerror="this.style.display='none'">
                                @endif
                            @endforeach
                            @if($item->gambar->count() > 4)
                                <span class="badge badge-secondary">+{{ $item->gambar->count() - 4 }}</span>
                            @endif
                        </div>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Belum ada data laporan</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- CHECKLIST SUMMARY -->
    <div style="margin-top: 15px;">
        <h4 style="font-size:13px; margin-bottom:8px;">Ringkasan Checklist Pengecekan</h4>
        <table style="width:100%; border-collapse:collapse; font-size:10px;">
            <thead>
                <tr>
                    <th style="border:1px solid #ddd; padding:4px 8px; background:#f8f9fa; text-align:left;">Komponen</th>
                    <th style="border:1px solid #ddd; padding:4px 8px; background:#f8f9fa; text-align:center;">Dicek</th>
                    <th style="border:1px solid #ddd; padding:4px 8px; background:#f8f9fa; text-align:center;">Tidak Dicek</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = $laporan->count();
                    $components = [
                        'Camera' => 'checklist_camera',
                        'DVR' => 'checklist_dvr',
                        'Monitor' => 'checklist_monitor',
                        'Kabel Camera' => 'checklist_kabel_camera',
                        'Kabel Listrik' => 'checklist_kabel_listrik',
                        'Konektor' => 'checklist_konektor',
                    ];
                @endphp
                @foreach($components as $label => $field)
                @php
                    $checked = $laporan->where($field, true)->count();
                    $unchecked = $total - $checked;
                @endphp
                <tr>
                    <td style="border:1px solid #ddd; padding:4px 8px;">{{ $label }}</td>
                    <td style="border:1px solid #ddd; padding:4px 8px; text-align:center;">
                        <span class="badge badge-success">{{ $checked }}</span>
                    </td>
                    <td style="border:1px solid #ddd; padding:4px 8px; text-align:center;">
                        <span class="badge badge-danger">{{ $unchecked }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <p>Dokumen ini dicetak dari sistem Job Management</p>
        <p>&copy; {{ date('Y') }} - Dicetak pada {{ date('d F Y H:i:s') }}</p>
    </div>

</body>
</html>