<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Design Layout - {{ $layout->nama_layout }}</title>
    <style>
        /* === SETUP DOMPDF PAPER & MARGIN === */
        @page {
            margin: 15mm 15mm 15mm 15mm;
            size: A4 portrait;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #2c3e50;
            line-height: 1.4;
        }

        /* === HEADER / KOP LAPORAN === */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .header-title {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            color: #1a252f;
        }
        .header-subtitle {
            font-size: 11px;
            color: #7f8c8d;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 15px;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 4px;
            padding: 8px 12px;
        }
        .meta-table td {
            padding: 3px 6px;
            font-size: 10.5px;
        }

        /* === DENAH / CANVAS PREVIEW SECTION === */
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #2980b9;
            border-bottom: 1px solid #2980b9;
            padding-bottom: 3px;
            margin-top: 15px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .canvas-preview-container {
            width: 100%;
            text-align: center;
            border: 1px solid #bdc3c7;
            background: #fafafa;
            padding: 8px;
            margin-bottom: 15px;
            border-radius: 4px;
        }

        .canvas-preview-container img {
            max-width: 100%;
            max-height: 380px;
            height: auto;
            object-fit: contain;
        }

        /* === TABEL REKAPITULASI (INVENTORY & CONNECTIONS) === */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .data-table th, .data-table td {
            border: 1px solid #dcdde1;
            padding: 6px 8px;
            text-align: left;
            font-size: 10px;
        }

        .data-table th {
            background-color: #34495e;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
        }

        .data-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .badge {
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            color: white;
            display: inline-block;
        }

        /* Category Badges */
        .bg-kelistrikan { background-color: #e74c3c; }
        .bg-jaringan { background-color: #2980b9; }
        .bg-cctv { background-color: #8e44ad; }
        .bg-plumbing { background-color: #27ae60; }
        .bg-default { background-color: #7f8c8d; }

        /* === FOOTER & TANDA TANGAN === */
        .footer-table {
            width: 100%;
            margin-top: 30px;
        }

        .signature-box {
            text-align: center;
            width: 33%;
        }

        .signature-space {
            height: 50px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

    <!-- KOP LAPORAN -->
    <table class="header-table">
        <tr>
            <td style="width: 70%;">
                <div class="header-title">Laporan Desain Layout & Instalasii</div>
                <div class="header-subtitle">Dokumen Teknis Kelistrikan, Jaringan, CCTV & Plumbing Gedung</div>
            </td>
            <td style="width: 30%; text-align: right;">
                <strong>Tanggal:</strong> {{ date('d/m/Y') }}<br>
                <strong>Ref ID:</strong> #LAY-{{ str_pad($layout->id, 5, '0', STR_PAD_LEFT) }}
            </td>
        </tr>
    </table>

    <!-- METADATA LAYOUT -->
    <table class="meta-table">
        <tr>
            <td style="width: 15%;"><strong>Nama Layout</strong></td>
            <td style="width: 35%;">: {{ $layout->nama_layout }}</td>
            <td style="width: 15%;"><strong>Kategori / Divisi</strong></td>
            <td style="width: 35%;">: {{ strtoupper($jobdesk->slug ?? 'General') }}</td>
        </tr>
        <tr>
            <td><strong>Gedung / Lokasi</strong></td>
            <td>: {{ $layout->lokasi ?? 'Main Building' }}</td>
            <td><strong>Total Device</strong></td>
            <td>: {{ count($devices) }} Perangkat</td>
        </tr>
    </table>

    <!-- VISUAL DENAH (EXPORT IMAGE CAPTURE) -->
    <div class="section-title">1. Visualisasi Layout Denah Gedung</div>
    <div class="canvas-preview-container">
        @if(!empty($renderedImageBase64))
            <!-- Gambar hasil export canvas real-time (Base64) -->
            <img src="{{ $renderedImageBase64 }}" alt="Denah Layout Canvas">
        @elseif($layout->image_path && file_exists(public_path('storage/' . $layout->image_path)))
            <!-- Fallback ke denah bawaan jika belum ada export canvas -->
            <img src="{{ public_path('storage/' . $layout->image_path) }}" alt="Denah Background">
        @else
            <div style="padding: 40px; color: #95a5a6;">
                <em>[ Preview visual tidak tersedia / Denah belum diexport ]</em>
            </div>
        @endif
    </div>

    <!-- REKAPITULASI DEVICE (INVENTORY) -->
    <div class="section-title">2. Daftar Perangkat & Lokasi (Device Inventory)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th style="width: 25%;">Nama Device</th>
                <th style="width: 20%;">Kategori / Tipe</th>
                <th style="width: 25%;">Posisi Koordinat (X, Y)</th>
                <th style="width: 25%;">Keterangan / Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($devices as $index => $dev)
                @php
                    $categoryClass = 'bg-default';
                    if (in_array($dev->tipe_device, ['mcb', 'stopkontak', 'saklar', 'lampu'])) $categoryClass = 'bg-kelistrikan';
                    elseif (in_array($dev->tipe_device, ['router', 'switch', 'access_point', 'server'])) $categoryClass = 'bg-jaringan';
                    elseif (in_array($dev->tipe_device, ['cctv', 'dvr_nvr', 'door_access'])) $categoryClass = 'bg-cctv';
                    elseif (in_array($dev->tipe_device, ['pompa', 'tandon', 'valve'])) $categoryClass = 'bg-plumbing';
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td><strong>{{ $dev->nama_device }}</strong></td>
                    <td>
                        <span class="badge {{ $categoryClass }}">
                            {{ strtoupper($dev->tipe_device) }}
                        </span>
                    </td>
                    <td>X: {{ $dev->pos_x }}px, Y: {{ $dev->pos_y }}px @if($dev->rotation) (Rotasi: {{ $dev->rotation }}°) @endif</td>
                    <td>{{ $dev->keterangan ?? 'Terpasang' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #7f8c8d;">Belum ada perangkat yang ditambahkan ke denah.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- REKAPITULASI JALUR KABEL / PERPIPAAN -->
    <div class="section-title">3. Rekapitulasi Jalur Koneksi / Kabel</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th style="width: 35%;">Dari Device (Source)</th>
                <th style="width: 35%;">Menuju Device (Target)</th>
                <th style="width: 25%;">Estimasi Panjang / Label</th>
            </tr>
        </thead>
        <tbody>
            @forelse($connections as $index => $conn)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $conn->sourceDevice->nama_device ?? 'Device #' . $conn->device_from_id }}</td>
                    <td>{{ $conn->targetDevice->nama_device ?? 'Device #' . $conn->device_to_id }}</td>
                    <td>
                        @if($conn->panjang_meter)
                            <strong>{{ $conn->panjang_meter }} Meter</strong>
                        @elseif($conn->label)
                            {{ $conn->label }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #7f8c8d;">Belum ada jalur kabel/koneksi antar perangkat.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- TANDA TANGAN / PENGESAHAN -->
    <table class="footer-table">
        <tr>
            <td class="signature-box">
                Dibuat Oleh,<br>
                <div class="signature-space"></div>
                <strong>( Technical Designer )</strong>
            </td>
            <td class="signature-box">
                Ditinjau Oleh,<br>
                <div class="signature-space"></div>
                <strong>( Supervisor / PM )</strong>
            </td>
            <td class="signature-box">
                Disetujui Oleh,<br>
                <div class="signature-space"></div>
                <strong>( Head of Infrastructure )</strong>
            </td>
        </tr>
    </table>

</body>
</html>