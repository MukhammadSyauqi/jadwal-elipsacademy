<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'Rekapitulasi Jadwal Kelas' }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 10mm 12mm 12mm 12mm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1D1D1F;
            font-size: 9pt;
            line-height: 1.3;
            margin: 0;
            padding: 0;
        }
        .header {
            border-bottom: 2px solid #F28E2B;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .header table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-title {
            font-size: 14pt;
            font-weight: bold;
            color: #904D00;
            margin: 0;
        }
        .header-subtitle {
            font-size: 9pt;
            color: #6E6E73;
            margin-top: 2px;
        }
        .meta-box {
            background-color: #F8F9FA;
            border: 1px solid #E0E0E0;
            border-radius: 4px;
            padding: 6px 10px;
            margin-bottom: 12px;
            font-size: 8pt;
        }
        .meta-box table {
            width: 100%;
            border-collapse: collapse;
        }
        .meta-label {
            font-weight: bold;
            color: #6E6E73;
            width: 90px;
        }
        .meta-value {
            color: #1D1D1F;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
        }
        table.data-table th {
            background-color: #F28E2B;
            color: #FFFFFF;
            font-weight: bold;
            text-align: center;
            padding: 6px 4px;
            border: 1px solid #D0741A;
            text-transform: uppercase;
            font-size: 7.5pt;
        }
        table.data-table td {
            padding: 4px 5px;
            border: 1px solid #E0E0E0;
            vertical-align: middle;
        }
        table.data-table tr:nth-child(even) td {
            background-color: #FAFAFC;
        }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 7pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-selesai {
            background-color: #D1FAE5;
            color: #065F46;
        }
        .badge-terjadwal {
            background-color: #E0E7FF;
            color: #3730A3;
        }
        .badge-dibatalkan {
            background-color: #FEE2E2;
            color: #991B1B;
        }
        .summary-box {
            margin-top: 12px;
            padding: 8px 10px;
            background-color: #FFF7ED;
            border: 1px solid #FED7AA;
            border-radius: 4px;
            font-size: 8pt;
        }
        .summary-box table {
            width: 100%;
            border-collapse: collapse;
        }
        .footer {
            margin-top: 15px;
            font-size: 7.5pt;
            color: #86868B;
            border-top: 1px solid #E0E0E0;
            padding-top: 5px;
        }
        .footer table {
            width: 100%;
            border-collapse: collapse;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <table>
            <tr>
                <td style="width: 70%;">
                    <div class="header-title">ELIPS ACADEMY</div>
                    <div class="header-subtitle">Laporan Rekapitulasi Jadwal Kursus & Pembelajaran</div>
                </td>
                <td class="text-right" style="width: 30%;">
                    <div style="font-size: 8pt; color: #86868B;">Sistem Informasi Manajemen</div>
                    <div style="font-size: 8pt; font-weight: bold; color: #1D1D1F;">Dicetak: {{ $printedAt }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Metadata Filters -->
    <div class="meta-box">
        <table>
            <tr>
                <td class="meta-label">Periode:</td>
                <td class="meta-value">{{ $periodeText }}</td>
                <td class="meta-label">Cabang:</td>
                <td class="meta-value">{{ $cabangText }}</td>
                <td class="meta-label">Status:</td>
                <td class="meta-value">{{ $statusText }}</td>
                <td class="meta-label">Jenis Kelas:</td>
                <td class="meta-value">{{ $jenisText }}</td>
            </tr>
        </table>
    </div>

    <!-- Data Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th style="width: 65px;">Tanggal</th>
                <th style="width: 50px;">Hari</th>
                <th style="width: 75px;">Waktu</th>
                <th>Program</th>
                <th style="width: 55px;">Jenis</th>
                <th style="width: 45px;">Mode</th>
                <th>Tentor</th>
                <th>Ruangan</th>
                <th>Cabang</th>
                <th style="width: 55px;">Pertemuan</th>
                <th style="width: 65px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jadwals as $index => $j)
                @php
                    $tanggal = $j->tanggal ? \Carbon\Carbon::parse($j->tanggal) : null;
                    $hari = $tanggal ? $tanggal->locale('id')->isoFormat('dddd') : '-';
                    $tglStr = $tanggal ? $tanggal->format('d/m/Y') : '-';
                    $jam = ($j->jam_mulai ? substr($j->jam_mulai, 0, 5) : '-') . ' - ' . ($j->jam_selesai ? substr($j->jam_selesai, 0, 5) : '-');
                    $status = $j->status ?? 'terjadwal';
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $tglStr }}</td>
                    <td class="text-center">{{ $hari }}</td>
                    <td class="text-center">{{ $jam }}</td>
                    <td class="text-left">{{ $j->program->nama_program ?? '-' }}</td>
                    <td class="text-center">{{ $j->jenis_kelas ?? '-' }}</td>
                    <td class="text-center">{{ $j->mode_kelas ?? '-' }}</td>
                    <td class="text-left">{{ $j->tentor->nama ?? '-' }}</td>
                    <td class="text-left">{{ $j->ruanganRef->nama_ruangan ?? $j->ruangan ?? '-' }}</td>
                    <td class="text-left">{{ $j->cabang->nama_cabang ?? '-' }}</td>
                    <td class="text-center">{{ $j->pertemuan ? 'Ke-' . $j->pertemuan : '-' }}</td>
                    <td class="text-center">
                        @if($status === 'selesai')
                            <span class="badge badge-selesai">Selesai</span>
                        @elseif($status === 'dibatalkan')
                            <span class="badge badge-dibatalkan">Dibatalkan</span>
                        @else
                            <span class="badge badge-terjadwal">Terjadwal</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="text-center" style="padding: 15px; color: #86868B; font-style: italic;">
                        Tidak ada data jadwal ditemukan pada filter ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Summary Box -->
    <div class="summary-box">
        <table>
            <tr>
                <td style="width: 25%; font-weight: bold;">
                    Total Jadwal: {{ $summary['total'] }}
                </td>
                <td style="width: 25%; color: #065F46; font-weight: bold;">
                    Selesai: {{ $summary['selesai'] }}
                </td>
                <td style="width: 25%; color: #3730A3; font-weight: bold;">
                    Terjadwal: {{ $summary['terjadwal'] }}
                </td>
                <td style="width: 25%; color: #991B1B; font-weight: bold;">
                    Dibatalkan: {{ $summary['dibatalkan'] }}
                </td>
            </tr>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <table>
            <tr>
                <td>Laporan ini digenerate secara otomatis oleh Sistem Elips Academy Indonesia.</td>
                <td class="text-right">Halaman 1</td>
            </tr>
        </table>
    </div>

</body>
</html>
