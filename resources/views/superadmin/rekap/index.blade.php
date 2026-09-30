<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekapitulasi Jadwal - Superadmin - Elips Academy</title>
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
                        "schedule-verified": "#10B981",
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
<body class="bg-canvas-parchment font-sans text-ink-body antialiased min-h-screen flex">

    <!-- Sidebar Navigation -->
    <aside id="sidebarNav" class="fixed left-0 top-0 h-full w-64 bg-canvas-pure border-r border-hairline shadow-[0_1px_8px_rgba(0,0,0,0.04)] z-50 flex flex-col justify-between -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
        <div class="flex flex-col">
            <!-- Brand -->
            <div class="h-16 px-6 flex items-center justify-between border-b border-hairline">
                <a href="{{ route('superadmin.dashboard') }}" class="flex items-center gap-2.5 group">
                    <img src="{{ asset('images/logo.png') }}" alt="Elips Academy" class="h-7 w-auto object-contain transition-transform duration-200 group-hover:scale-105">
                    <span class="text-[10px] text-ink-muted uppercase tracking-wider font-semibold">Superadmin Panel</span>
                </a>
                <button type="button" onclick="toggleSidebar()" class="lg:hidden p-1.5 rounded-lg text-ink-muted hover:bg-surface-container-low transition-colors" aria-label="Tutup Menu">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <!-- Hak Akses Indicator -->
            <div class="px-4 py-3">
                <div class="flex items-center gap-2 px-3 py-2 rounded-xl bg-surface-container-low border border-hairline/60">
                    <span class="material-symbols-outlined text-primary-container text-[18px]">verified_user</span>
                    <div class="flex flex-col min-w-0 flex-1">
                        <span class="text-[10px] text-ink-muted uppercase tracking-wider font-semibold">Hak Akses</span>
                        <span class="text-xs text-ink-body font-semibold truncate">Lintas Seluruh Cabang</span>
                    </div>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex flex-col gap-1 px-3 pt-1">
                <a href="{{ route('superadmin.dashboard') }}" 
                   id="nav-superadmin-dashboard"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-ink-muted hover:bg-surface-container-low hover:text-ink-body transition-all">
                    <span class="material-symbols-outlined text-[20px]">grid_view</span>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('superadmin.jadwal.index') }}" 
                   id="nav-superadmin-jadwal"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-ink-muted hover:bg-surface-container-low hover:text-ink-body transition-all">
                    <span class="material-symbols-outlined text-[20px]">calendar_month</span>
                    <span>Jadwal Kelas</span>
                </a>

                <a href="{{ route('superadmin.rekap.index') }}" 
                   id="nav-superadmin-rekap"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold bg-primary-container text-white shadow-[0_1px_4px_rgba(242,142,43,0.25)] transition-all">
                    <span class="material-symbols-outlined text-[20px]">assessment</span>
                    <span>Rekap Jadwal</span>
                </a>

                <a href="{{ route('superadmin.cabang.index') }}" 
                   id="nav-superadmin-cabang"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-ink-muted hover:bg-surface-container-low hover:text-ink-body transition-all">
                    <span class="material-symbols-outlined text-[20px]">apartment</span>
                    <span>Cabang</span>
                </a>

                <a href="{{ route('superadmin.program.index') }}" 
                   id="nav-superadmin-program"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-ink-muted hover:bg-surface-container-low hover:text-ink-body transition-all">
                    <span class="material-symbols-outlined text-[20px]">menu_book</span>
                    <span>Program Kursus</span>
                </a>

                <a href="{{ route('superadmin.tentor.index') }}" 
                   id="nav-superadmin-tentor"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-ink-muted hover:bg-surface-container-low hover:text-ink-body transition-all">
                    <span class="material-symbols-outlined text-[20px]">badge</span>
                    <span>Tentor</span>
                </a>

                <a href="{{ route('superadmin.ruangan.index') }}" 
                   id="nav-superadmin-ruangan"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-ink-muted hover:bg-surface-container-low hover:text-ink-body transition-all">
                    <span class="material-symbols-outlined text-[20px]">meeting_room</span>
                    <span>Ruangan</span>
                </a>

                <a href="{{ route('superadmin.user.index') }}" 
                   id="nav-superadmin-user"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-ink-muted hover:bg-surface-container-low hover:text-ink-body transition-all">
                    <span class="material-symbols-outlined text-[20px]">manage_accounts</span>
                    <span>Akun Pengguna</span>
                </a>

                <a href="{{ route('superadmin.setting.index') }}" 
                   id="nav-superadmin-setting"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-ink-muted hover:bg-surface-container-low hover:text-ink-body transition-all">
                    <span class="material-symbols-outlined text-[20px]">settings</span>
                    <span>Pengaturan</span>
                </a>
            </nav>
        </div>

        <!-- User Profile & Logout Box -->
        <div class="p-4 border-t border-hairline">
            <div class="flex items-center justify-between gap-2 p-2.5 rounded-xl bg-surface-container-low">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold text-xs shrink-0">
                        {{ strtoupper(substr($user->nama, 0, 1)) }}
                    </div>
                    <div class="flex flex-col min-w-0">
                        <span class="text-xs font-semibold text-ink-body truncate">{{ $user->nama }}</span>
                        <span class="text-[10px] text-ink-muted truncate">{{ $user->email }}</span>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" 
                            id="sidebarLogoutBtn"
                            title="Keluar"
                            class="p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors cursor-pointer">
                        <span class="material-symbols-outlined text-[18px]">logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebarBackdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-black/40 backdrop-blur-xs z-40 hidden lg:hidden transition-opacity"></div>

    <!-- Main Content Area -->
    <div class="lg:pl-64 flex-1 flex flex-col min-w-0 w-full">
        <!-- Top App Bar -->
        <header class="h-16 bg-canvas-pure border-b border-hairline sticky top-0 z-40 px-4 sm:px-6 lg:px-8 flex items-center justify-between gap-3">
            <div class="flex items-center gap-2.5">
                <button type="button" onclick="toggleSidebar()" class="lg:hidden p-2 -ml-2 rounded-xl text-ink-muted hover:bg-surface-container-low hover:text-ink-body transition-colors shrink-0" aria-label="Buka Menu">
                    <span class="material-symbols-outlined text-[24px]">menu</span>
                </button>
                <div class="flex items-center gap-1.5 sm:gap-2 text-xs font-medium text-ink-muted">
                    <span class="hidden sm:inline">ELIPS MASTER</span>
                    <span class="material-symbols-outlined text-[14px] hidden sm:inline">chevron_right</span>
                    <span class="text-primary font-bold">REKAPITULASI</span>
                    <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                    <span class="text-ink-body font-semibold">JADWAL KELAS</span>
                </div>
            </div>

            <div class="flex items-center gap-2 sm:gap-4">
                <div class="hidden md:flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-100 text-schedule-verified text-xs font-semibold">
                    <span class="w-2 h-2 rounded-full bg-schedule-verified animate-pulse"></span>
                    <span>Auth Guard Active</span>
                </div>
                <div class="h-4 w-px bg-hairline hidden md:block"></div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-accent-subtle text-primary border border-primary/20">
                    Superadmin
                </span>
            </div>
        </header>

        <!-- Main Body -->
        <main class="p-4 sm:p-6 lg:p-8 space-y-6">

            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-ink-body tracking-tight flex items-center gap-2.5">
                        <span class="material-symbols-outlined text-primary-container text-[28px]">assessment</span>
                        Rekapitulasi Jadwal Kelas
                    </h1>
                    <p class="text-xs text-ink-muted mt-1">Laporan dan analisis jadwal lintas cabang dengan filter rentang tanggal fleksibel.</p>
                </div>

                <!-- Export Action Buttons -->
                <div class="flex items-center gap-2.5 flex-wrap">
                    <a href="{{ route('superadmin.rekap.export-excel', request()->all()) }}" 
                       id="btnExportExcelSuperadmin"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white text-xs font-semibold shadow-xs transition-all"
                       title="Export Data ke Excel (.xlsx)">
                        <span class="material-symbols-outlined text-[18px]">table_view</span>
                        <span>Export Excel</span>
                    </a>

                    <a href="{{ route('superadmin.rekap.export-pdf', request()->all()) }}" 
                       id="btnExportPdfSuperadmin"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-red-600 hover:bg-red-700 active:scale-95 text-white text-xs font-semibold shadow-xs transition-all"
                       title="Export Laporan ke PDF (.pdf)">
                        <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span>
                        <span>Export PDF</span>
                    </a>
                </div>
            </div>

            <!-- Filter Card -->
            <div class="bg-white rounded-2xl border border-hairline p-5 shadow-xs">
                <form method="GET" action="{{ route('superadmin.rekap.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3.5 items-end">
                    <!-- Tanggal Mulai -->
                    <div>
                        <label for="tanggal_mulai" class="block text-xs font-semibold text-ink-muted mb-1">Tanggal Mulai</label>
                        <input type="date" 
                               name="tanggal_mulai" 
                               id="tanggal_mulai" 
                               value="{{ $filters['tanggal_mulai'] }}"
                               class="w-full px-3 py-2 rounded-xl bg-surface-pearl border border-hairline text-xs font-medium text-ink-body focus:outline-none focus:ring-2 focus:ring-primary-container">
                    </div>

                    <!-- Tanggal Selesai -->
                    <div>
                        <label for="tanggal_selesai" class="block text-xs font-semibold text-ink-muted mb-1">Tanggal Selesai</label>
                        <input type="date" 
                               name="tanggal_selesai" 
                               id="tanggal_selesai" 
                               value="{{ $filters['tanggal_selesai'] }}"
                               class="w-full px-3 py-2 rounded-xl bg-surface-pearl border border-hairline text-xs font-medium text-ink-body focus:outline-none focus:ring-2 focus:ring-primary-container">
                    </div>

                    <!-- Cabang -->
                    <div>
                        <label for="cabang_id" class="block text-xs font-semibold text-ink-muted mb-1">Cabang</label>
                        <select name="cabang_id" id="cabang_id" class="w-full px-3 py-2 rounded-xl bg-surface-pearl border border-hairline text-xs font-medium text-ink-body focus:outline-none focus:ring-2 focus:ring-primary-container">
                            <option value="semua">Semua Cabang</option>
                            @foreach($cabangs as $cb)
                                <option value="{{ $cb->id }}" {{ $filters['cabang_id'] == $cb->id ? 'selected' : '' }}>
                                    {{ $cb->nama_cabang }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-xs font-semibold text-ink-muted mb-1">Status</label>
                        <select name="status" id="status" class="w-full px-3 py-2 rounded-xl bg-surface-pearl border border-hairline text-xs font-medium text-ink-body focus:outline-none focus:ring-2 focus:ring-primary-container">
                            <option value="semua">Semua Status</option>
                            @foreach($statusList as $st)
                                <option value="{{ $st }}" {{ $filters['status'] == $st ? 'selected' : '' }}>
                                    {{ ucfirst($st) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Jenis Kelas -->
                    <div>
                        <label for="jenis_kelas" class="block text-xs font-semibold text-ink-muted mb-1">Jenis Kelas</label>
                        <select name="jenis_kelas" id="jenis_kelas" class="w-full px-3 py-2 rounded-xl bg-surface-pearl border border-hairline text-xs font-medium text-ink-body focus:outline-none focus:ring-2 focus:ring-primary-container">
                            <option value="semua">Semua Jenis</option>
                            @foreach($jenisKelasList as $jk)
                                <option value="{{ $jk }}" {{ $filters['jenis_kelas'] == $jk ? 'selected' : '' }}>
                                    {{ $jk }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2">
                        <button type="submit" 
                                id="btnFilterRekap"
                                class="flex-1 py-2 px-4 rounded-xl bg-primary text-white hover:bg-brand-hover text-xs font-semibold transition-all flex items-center justify-center gap-1.5 shadow-2xs">
                            <span class="material-symbols-outlined text-[16px]">filter_alt</span>
                            <span>Filter</span>
                        </button>

                        <a href="{{ route('superadmin.rekap.index') }}" 
                           class="p-2 rounded-xl border border-hairline bg-surface-pearl hover:bg-surface-container-low text-ink-muted transition-all" 
                           title="Reset Filter">
                            <span class="material-symbols-outlined text-[18px]">restart_alt</span>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Summary Statistics Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 sm:gap-4">
                <div class="bg-white rounded-xl p-4 border border-hairline shadow-xs flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-orange-50 text-brand-orange flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[20px]">calendar_month</span>
                    </div>
                    <div>
                        <span class="text-[11px] font-semibold text-ink-muted uppercase tracking-wider block">Total Jadwal</span>
                        <span class="text-xl font-bold text-ink-body">{{ $summary['total'] }}</span>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-4 border border-hairline shadow-xs flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[20px]">check_circle</span>
                    </div>
                    <div>
                        <span class="text-[11px] font-semibold text-ink-muted uppercase tracking-wider block">Selesai</span>
                        <span class="text-xl font-bold text-emerald-600">{{ $summary['selesai'] }}</span>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-4 border border-hairline shadow-xs flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[20px]">schedule</span>
                    </div>
                    <div>
                        <span class="text-[11px] font-semibold text-ink-muted uppercase tracking-wider block">Terjadwal</span>
                        <span class="text-xl font-bold text-indigo-600">{{ $summary['terjadwal'] }}</span>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-4 border border-hairline shadow-xs flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[20px]">cancel</span>
                    </div>
                    <div>
                        <span class="text-[11px] font-semibold text-ink-muted uppercase tracking-wider block">Dibatalkan</span>
                        <span class="text-xl font-bold text-red-600">{{ $summary['dibatalkan'] }}</span>
                    </div>
                </div>

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
                                <th class="px-4 py-3">Cabang</th>
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
                                    <td class="px-4 py-3 text-ink-body">{{ $j->cabang->nama_cabang ?? '-' }}</td>
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
                                    <td colspan="12" class="px-6 py-12 text-center text-ink-subtle">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <span class="material-symbols-outlined text-[36px] text-ink-subtle">event_busy</span>
                                            <p class="text-sm font-medium">Tidak ada jadwal ditemukan pada kriteria filter ini.</p>
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
        <footer class="mt-auto border-t border-hairline px-4 sm:px-6 lg:px-8 py-4 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-ink-subtle">
            <div class="flex items-center gap-2">
                <span class="font-medium text-ink-body">Elips Academy</span>
                <span>•</span>
                <span>Sistem Informasi Manajemen</span>
            </div>
            <div>© {{ date('Y') }} Elips Academy Indonesia. Rekapitulasi Lintas Cabang.</div>
        </footer>
    </div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebarNav');
        const backdrop = document.getElementById('sidebarBackdrop');
        sidebar.classList.toggle('-translate-x-full');
        backdrop.classList.toggle('hidden');
    }

    window.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            const sb = document.getElementById('sidebarNav');
            const bd = document.getElementById('sidebarBackdrop');
            if (sb && !sb.classList.contains('-translate-x-full') && window.innerWidth < 1024) {
                sb.classList.add('-translate-x-full');
                bd.classList.add('hidden');
            }
        }
    });
</script>

</body>
</html>
