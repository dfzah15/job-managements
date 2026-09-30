<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard Eksekutif</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            padding: 20px;
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
            color: #2c3e50;
        }
        .header p {
            color: #7f8c8d;
            font-size: 11px;
            margin-top: 5px;
        }
        .section-title {
            background: #3498db;
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            margin: 15px 0 10px 0;
            font-size: 13px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 10px;
        }
        table th {
            background: #2c3e50;
            color: white;
            padding: 6px 10px;
            text-align: left;
            border: 1px solid #2c3e50;
        }
        table td {
            padding: 5px 10px;
            border: 1px solid #ddd;
        }
        table tr:nth-child(even) {
            background: #f8f9fa;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin: 10px 0;
        }
        .stat-box {
            background: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            text-align: center;
        }
        .stat-box .number {
            font-size: 24px;
            font-weight: bold;
            color: #3498db;
        }
        .stat-box .label {
            font-size: 10px;
            color: #7f8c8d;
            margin-top: 3px;
        }
        .stat-box.primary .number { color: #3498db; }
        .stat-box.success .number { color: #27ae60; }
        .stat-box.warning .number { color: #f39c12; }
        .stat-box.info .number { color: #1abc9c; }
        .footer {
            margin-top: 20px;
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            font-size: 9px;
            color: #7f8c8d;
        }
        .text-center { text-align: center; }
        .text-muted { color: #7f8c8d; }
    </style>
</head>
<body>
    <!-- HEADER -->
    <div class="header">
        <h1>Dashboard Eksekutif</h1>
        <p>Dicetak oleh: {{ $data['nama'] }} | {{ $data['tanggal'] }}</p>
    </div>

    <!-- STATISTIK UTAMA -->
    <div class="section-title">📊 Statistik Utama</div>
    <div class="stats-grid">
        <div class="stat-box primary">
            <div class="number">{{ $data['totalLaporan'] }}</div>
            <div class="label">Total Laporan</div>
        </div>
        <div class="stat-box success">
            <div class="number">{{ $data['totalLaporanSelesai'] }}</div>
            <div class="label">Selesai</div>
        </div>
        <div class="stat-box warning">
            <div class="number">{{ $data['totalLaporanPending'] }}</div>
            <div class="label">Pending</div>
        </div>
        <div class="stat-box info">
            <div class="number">{{ $data['persentaseSelesai'] }}%</div>
            <div class="label">Persentase Selesai</div>
        </div>
    </div>

    <!-- PERFORMANCE TEKNISI -->
    <div class="section-title">🏆 Performance Teknisi</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Teknisi</th>
                <th>Total Eksekusi</th>
                <th>Selesai</th>
                <th>Proses</th>
                <th>Pending</th>
                <th>Efisiensi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['performanceTeknisi'] as $index => $teknis)
            @php
                $efisiensi = $teknis->total_eksekusi > 0 ? round(($teknis->selesai / $teknis->total_eksekusi) * 100) : 0;
                $warna = $efisiensi >= 80 ? '#27ae60' : ($efisiensi >= 50 ? '#f39c12' : '#e74c3c');
            @endphp
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $teknis->user->name ?? 'Unknown' }}</td>
                <td class="text-center">{{ $teknis->total_eksekusi }}</td>
                <td class="text-center">{{ $teknis->selesai }}</td>
                <td class="text-center">{{ $teknis->proses }}</td>
                <td class="text-center">{{ $teknis->pending }}</td>
                <td>
                    <span style="color: {{ $warna }}; font-weight: bold;">
                        {{ $efisiensi }}%
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- LOKASI RAWAN -->
    <div class="section-title">📍 5 Lokasi Paling Rawan</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Lokasi</th>
                <th>Total Laporan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['lokasiRawan'] as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->lokasi->nama_lokasi ?? 'Unknown' }}</td>
                <td class="text-center">{{ $item->total }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- STATUS INVENTARIS -->
    <div class="section-title">📦 Status Inventaris</div>
    <table>
        <thead>
            <tr>
                <th>Status</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['inventarisStatus'] as $item)
            <tr>
                <td>{{ ucfirst($item->status) }}</td>
                <td class="text-center">{{ $item->total }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- FOOTER -->
    <div class="footer">
        <p>Dokumen ini dicetak dari sistem Job Management</p>
        <p>&copy; {{ date('Y') }} - Dicetak pada {{ date('d F Y H:i:s') }}</p>
    </div>
</body>
</html>