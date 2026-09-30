<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekapitulasi Jadwal Bulanan - Elips Academy</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "primary": "#904D00",
                        "primary-container": "#F28E2B",
                        "on-primary": "#ffffff",
                        "brand-hover": "#E07D1C",
                        "canvas-parchment": "#F5F5F7",
                        "canvas-pure": "#FFFFFF",
                        "surface-pearl": "#FAFAFC",
                        "surface-container-low": "#F6F3F5",
                        "hairline": "#E0E0E0",
                        "ink-body": "#1D1D1F",
                        "ink-muted": "#6E6E73",
                        "ink-subtle": "#86868B",
                        "accent-subtle": "#FEF3C7",
                        "brand-orange": "#F28E2B",
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    }
                }
            }
        };
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-canvas-parchment font-sans text-ink-body antialiased min-h-screen flex flex-col justify-between">

    <!-- Header Navigation -->
    <header class="bg-canvas-pure border-b border-hairline sticky top-0 z-30 shadow-[0_1px_8px_rgba(0,0,0,0.02)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 group">
                    <img src="{{ asset('images/logo.png') }}" alt="Elips Academy" class="h-7 w-auto object-contain transition-transform group-hover:scale-105">
                </a>
                <div class="h-4 w-px bg-hairline hidden sm:block"></div>
                <div class="hidden sm:flex items-center gap-1.5 text-xs text-ink-muted">
                    <span>OPERASIONAL</span>
                    <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                    <span class="font-bold text-primary">REKAPITULASI BULANAN</span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <!-- Back to Dashboard -->
                <a href="{{ route('admin.dashboard') }}" 
                   id="btnKembaliDashboard"
                   class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-surface-pearl hover:bg-surface-container-low text-xs font-semibold text-ink-body border border-hairline transition-all">
                    <span class="material-symbols-outlined text-[18px]">dashboard</span>
                    <span class="hidden sm:inline">Dashboard</span>
                </a>

                <!-- User Profile & Logout -->
                <div class="flex items-center gap-2 pl-2 border-l border-hairline">
                    <div class="w-8 h-8 rounded-full bg-brand-orange text-white flex items-center justify-center font-bold text-xs shadow-sm">
                        {{ strtoupper(substr($user->nama, 0, 1)) }}
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="p-1.5 text-ink-muted hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors" title="Keluar">
                            <span class="material-symbols-outlined text-[18px]">logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-6 sm:py-8 flex-1 space-y-6">

        <!-- Page Header & Branch Badge -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-800 text-[11px] font-bold uppercase tracking-wider border border-amber-200">
                        {{ $currentCabang ? 'Cabang ' . $currentCabang->nama_cabang : 'Semua Cabang' }}
                    </span>
                    @if($isCurrentMonth)
                        <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-semibold border border-emerald-200">
                            Bulan Berjalan (s/d Hari Ini)
                        </span>
                    @endif
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-ink-body tracking-tight mt-1 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[28px]">assessment</span>
                    Rekapitulasi Jadwal Bulanan
                </h1>
                <p class="text-xs text-ink-muted mt-0.5">
                    Periode: <strong>{{ $months[$bulan] }} {{ $tahun }}</strong> ({{ $startDate->format('d/m/Y') }} – {{ $endDate->format('d/m/Y') }})
                </p>
            </div>

            <!-- Export Excel Button -->
            <div class="flex items-center gap-2 self-start sm:self-center">
                <a href="{{ route('admin.rekap.export-excel', ['bulan' => $bulan, 'tahun' => $tahun]) }}" 
                   id="btnExportExcelAdmin"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white text-xs sm:text-sm font-semibold shadow-sm transition-all"
                   title="Download file Excel">
                    <span class="material-symbols-outlined text-[18px]">download</span>
                    <span>Export Excel</span>
                </a>
            </div>
        </div>

        <!-- Filter Bar Card -->
        <div class="bg-white rounded-2xl border border-hairline p-4 sm:p-5 shadow-xs">
            <form method="GET" action="{{ route('admin.rekap.index') }}" class="flex flex-wrap items-end gap-3 sm:gap-4">
                <!-- Bulan -->
                <div class="flex flex-col gap-1 min-w-[150px] flex-1 sm:flex-initial">
                    <label for="filterBulan" class="text-xs font-semibold text-ink-muted">Pilih Bulan</label>
                    <select name="bulan" id="filterBulan" class="px-3.5 py-2 rounded-xl bg-surface-pearl border border-hairline text-xs font-medium text-ink-body focus:outline-none focus:ring-2 focus:ring-primary-container">
                        @foreach($months as $num => $namaBulan)
                            <option value="{{ $num }}" {{ $bulan == $num ? 'selected' : '' }}>
                                {{ $namaBulan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tahun -->
                <div class="flex flex-col gap-1 min-w-[120px] flex-1 sm:flex-initial">
                    <label for="filterTahun" class="text-xs font-semibold text-ink-muted">Pilih Tahun</label>
                    <select name="tahun" id="filterTahun" class="px-3.5 py-2 rounded-xl bg-surface-pearl border border-hairline text-xs font-medium text-ink-body focus:outline-none focus:ring-2 focus:ring-primary-container">
                        @foreach($years as $yr)
                            <option value="{{ $yr }}" {{ $tahun == $yr ? 'selected' : '' }}>
                                {{ $yr }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        id="btnTampilkanRekap"
                        class="px-5 py-2 rounded-xl bg-primary text-white hover:bg-brand-hover text-xs font-semibold transition-all flex items-center gap-1.5 shadow-2xs">
                    <span class="material-symbols-outlined text-[16px]">filter_alt</span>
                    <span>Tampilkan</span>
                </button>
            </form>
        </div>

        <!-- Summary Statistics Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 sm:gap-4">
            <!-- Total -->
            <div class="bg-white rounded-xl p-4 border border-hairline shadow-xs flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-orange-50 text-brand-orange flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[20px]">calendar_month</span>
                </div>
                <div>
                    <span class="text-[11px] font-semibold text-ink-muted uppercase tracking-wider block">Total Jadwal</span>
                    <span class="text-xl font-bold text-ink-body">{{ $summary['total'] }}</span>
                </div>
            </div>

            <!-- Selesai -->
            <div class="bg-white rounded-xl p-4 border border-hairline shadow-xs flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[20px]">check_circle</span>
                </div>
                <div>
                    <span class="text-[11px] font-semibold text-ink-muted uppercase tracking-wider block">Selesai</span>
                    <span class="text-xl font-bold text-emerald-600">{{ $summary['selesai'] }}</span>
                </div>
            </div>

            <!-- Terjadwal -->
            <div class="bg-white rounded-xl p-4 border border-hairline shadow-xs flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[20px]">schedule</span>
                </div>
                <div>
                    <span class="text-[11px] font-semibold text-ink-muted uppercase tracking-wider block">Terjadwal</span>
                    <span class="text-xl font-bold text-indigo-600">{{ $summary['terjadwal'] }}</span>
                </div>
            </div>

            <!-- Dibatalkan -->
            <div class="bg-white rounded-xl p-4 border border-hairline shadow-xs flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[20px]">cancel</span>
                </div>
                <div>
                    <span class="text-[11px] font-semibold text-ink-muted uppercase tracking-wider block">Dibatalkan</span>
                    <span class="text-xl font-bold text-red-600">{{ $summary['dibatalkan'] }}</span>
                </div>
            </div>

            <!-- Tentor Terlibat -->
            <div class="bg-white rounded-xl p-4 border border-hairline shadow-xs flex items-center gap-3 col-span-2 sm:col-span-1">
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[20px]">badge</span>
                </div>
                <div>
                    <span class="text-[11px] font-semibold text-ink-muted uppercase tracking-wider block">Tentor</span>
                    <span class="text-xl font-bold text-purple-600">{{ $summary['tentor_count'] }}</span>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-2xl border border-hairline shadow-[0_2px_16px_rgba(0,0,0,0.02)] overflow-hidden">
            <div class="px-6 py-4 border-b border-hairline flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[20px]">table_rows</span>
                    <h2 class="text-sm font-bold text-ink-body">Rincian Data Jadwal</h2>
                </div>
                <span class="text-xs text-ink-muted">{{ $jadwals->count() }} sesi ditemukan</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-surface-pearl border-b border-hairline text-ink-muted font-semibold uppercase tracking-wider">
                            <th class="px-4 py-3 text-center w-12">No</th>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Hari</th>
                            <th class="px-4 py-3">Waktu</th>
                            <th class="px-4 py-3">Program</th>
                            <th class="px-4 py-3 text-center">Jenis</th>
                            <th class="px-4 py-3 text-center">Mode</th>
                            <th class="px-4 py-3">Tentor</th>
                            <th class="px-4 py-3">Ruangan</th>
                            <th class="px-4 py-3 text-center">Pertemuan</th>
                            <th class="px-4 py-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-hairline">
                        @forelse($jadwals as $idx => $j)
                            @php
                                $tanggal = $j->tanggal ? \Carbon\Carbon::parse($j->tanggal) : null;
                                $hari = $tanggal ? $tanggal->locale('id')->isoFormat('dddd') : '-';
                                $tglStr = $tanggal ? $tanggal->format('d/m/Y') : '-';
                                $jam = ($j->jam_mulai ? substr($j->jam_mulai, 0, 5) : '-') . ' - ' . ($j->jam_selesai ? substr($j->jam_selesai, 0, 5) : '-');
                                $status = $j->status ?? 'terjadwal';
                            @endphp
                            <tr class="hover:bg-surface-pearl/70 transition-colors">
                                <td class="px-4 py-3 text-center text-ink-muted">{{ $idx + 1 }}</td>
                                <td class="px-4 py-3 font-medium text-ink-body">{{ $tglStr }}</td>
                                <td class="px-4 py-3 text-ink-muted">{{ $hari }}</td>
                                <td class="px-4 py-3 font-mono text-[11px] text-ink-body">{{ $jam }}</td>
                                <td class="px-4 py-3 font-semibold text-ink-body">{{ $j->program->nama_program ?? '-' }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-surface-container-low text-ink-body">
                                        {{ $j->jenis_kelas ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $j->mode_kelas === 'Online' ? 'bg-sky-50 text-sky-700' : 'bg-slate-100 text-slate-700' }}">
                                        {{ $j->mode_kelas ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-ink-body font-medium">{{ $j->tentor->nama ?? '-' }}</td>
                                <td class="px-4 py-3 text-ink-muted">{{ $j->ruanganRef->nama_ruangan ?? $j->ruangan ?? '-' }}</td>
                                <td class="px-4 py-3 text-center text-ink-muted">{{ $j->pertemuan ? 'Ke-' . $j->pertemuan : '-' }}</td>
                                <td class="px-4 py-3 text-center">
                                    @if($status === 'selesai')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Selesai
                                        </span>
                                    @elseif($status === 'dibatalkan')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-50 text-red-700 border border-red-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                            Dibatalkan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                            Terjadwal
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="px-6 py-12 text-center text-ink-subtle">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <span class="material-symbols-outlined text-[36px] text-ink-subtle">event_busy</span>
                                        <p class="text-sm font-medium">Tidak ada jadwal pada periode {{ $months[$bulan] }} {{ $tahun }}.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="border-t border-hairline py-4 px-4 sm:px-6 lg:px-8 text-center text-xs text-ink-subtle bg-canvas-pure">
        © {{ date('Y') }} Elips Academy Indonesia. Rekapitulasi Jadwal Bulanan.
    </footer>

</body>
</html>
