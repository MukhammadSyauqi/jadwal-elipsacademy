<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Master Ruangan Kelas — Superadmin Elips Academy</title>

    <!-- Google Fonts: Inter & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Material Symbols Outlined -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "primary": "#904D00",
                        "primary-container": "#F28E2B",
                        "on-primary": "#ffffff",
                        "on-primary-container": "#5E3000",
                        "secondary": "#944A00",
                        "secondary-container": "#FC8F34",
                        "brand-hover": "#E07D1C",
                        "canvas-parchment": "#F5F5F7",
                        "canvas-pure": "#FFFFFF",
                        "surface-pearl": "#FAFAFC",
                        "surface-container-low": "#F6F3F5",
                        "surface-container": "#F0EDEF",
                        "surface-container-high": "#EAE7EA",
                        "surface-container-highest": "#E4E2E4",
                        "hairline": "#E0E0E0",
                        "ink-body": "#1D1D1F",
                        "ink-muted": "#6E6E73",
                        "ink-subtle": "#86868B",
                        "accent-subtle": "#FEF3C7",
                        "accent-focus": "#F59E0B",
                        "schedule-verified": "#10B981",
                        "schedule-pending": "#6366F1",
                        "schedule-conflict": "#EF4444",
                        "error": "#BA1A1A",
                        "error-container": "#FEE2E2",
                        "brand": {
                            orange: '#F28E2B',
                            hover: '#E07D1C',
                            light: '#FFF7ED',
                            dark: '#904D00',
                        },
                        "surface": {
                            canvas: '#F5F5F7',
                            pearl: '#FAFAFC',
                            card: '#FFFFFF',
                        },
                        "ink": {
                            body: '#1D1D1F',
                            muted: '#6E6E73',
                            subtle: '#86868B',
                        }
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
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 20;
            vertical-align: middle;
        }
        .material-symbols-outlined.fill {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 20;
        }
    </style>
</head>
<body class="bg-surface-pearl font-sans text-ink-body antialiased min-h-screen flex flex-col md:flex-row">

    <!-- Sidebar Superadmin -->
    <aside class="w-full md:w-64 bg-canvas-pure border-r border-hairline flex flex-col shrink-0 md:min-h-screen">
        <!-- Logo Branding -->
        <div class="p-6 border-b border-hairline flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" 
                     alt="Logo Elips Academy" 
                     class="h-9 w-auto object-contain">
                <div class="flex flex-col">
                    <span class="text-xs font-bold uppercase tracking-wider text-primary">Master Panel</span>
                </div>
            </div>
            <span class="md:hidden">
                <button type="button" onclick="document.getElementById('sidebar-nav').classList.toggle('hidden')" class="p-1 rounded text-ink-muted hover:text-ink-body">
                    <span class="material-symbols-outlined">menu</span>
                </button>
            </span>
        </div>

        <!-- Navigation Links -->
        <div id="sidebar-nav" class="hidden md:flex flex-col flex-1 justify-between p-4">
            <nav class="space-y-1.5">
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
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-ink-muted hover:bg-surface-container-low hover:text-ink-body transition-all">
                    <span class="material-symbols-outlined text-[20px]">assessment</span>
                    <span>Rekap Jadwal</span>
                </a>

                <div class="pt-3 pb-1.5 px-3.5 text-[10px] uppercase font-bold tracking-wider text-ink-subtle">
                    Master Data
                </div>

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
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold bg-primary-container text-white shadow-[0_1px_4px_rgba(242,142,43,0.25)] transition-all">
                    <span class="material-symbols-outlined text-[20px]">meeting_room</span>
                    <span>Ruangan</span>
                </a>

                <div class="pt-3 pb-1.5 px-3.5 text-[10px] uppercase font-bold tracking-wider text-ink-subtle">
                    Akses & Keamanan
                </div>

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

            <!-- User Profile & Logout Box -->
            <div class="pt-4 border-t border-hairline mt-auto">
                <div class="flex items-center justify-between gap-2 p-2.5 rounded-xl bg-surface-container-low">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-8 h-8 rounded-full bg-accent-subtle text-primary flex items-center justify-center font-bold text-xs shrink-0">
                            {{ substr($user->nama ?? 'S', 0, 1) }}
                        </div>
                        <div class="truncate">
                            <p class="text-xs font-bold text-ink-body truncate">{{ $user->nama ?? 'Super Admin' }}</p>
                            <span class="text-[10px] font-semibold text-primary uppercase">Superadmin</span>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" 
                                id="btn-logout-sidebar"
                                class="p-1.5 rounded-lg text-ink-muted hover:text-error hover:bg-red-50 transition-colors" 
                                title="Keluar dari Sistem">
                            <span class="material-symbols-outlined text-[18px]">logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Top Header Navigation Bar -->
        <header class="h-16 px-6 md:px-8 bg-canvas-pure border-b border-hairline flex items-center justify-between gap-4 sticky top-0 z-30">
            <div>
                <nav class="flex items-center gap-2 text-xs text-ink-muted">
                    <a href="{{ route('superadmin.dashboard') }}" class="hover:text-primary transition-colors">Portal Superadmin</a>
                    <span class="material-symbols-outlined text-[12px]">chevron_right</span>
                    <span class="font-semibold text-ink-body">Master Data Ruangan</span>
                </nav>
            </div>

            <div class="flex items-center gap-3">
                <button type="button" 
                        id="btnTambahRuangan"
                        onclick="openTambahModal()"
                        class="px-4 py-2 rounded-xl bg-primary hover:bg-primary-container text-white text-xs font-semibold flex items-center gap-2 shadow-xs transition-all active:scale-95 cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    <span>Tambah Ruangan</span>
                </button>
            </div>
        </header>

        <!-- Main Workspace -->
        <main class="p-6 md:p-8 flex-1 flex flex-col gap-6">
            <!-- Flash Message Alerts -->
            @if(session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex items-center justify-between shadow-xs animate-in fade-in" id="alertSuccess">
                    <div class="flex items-center gap-2.5">
                        <span class="material-symbols-outlined text-emerald-600 text-[20px]">check_circle</span>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="document.getElementById('alertSuccess').remove()" class="text-emerald-500 hover:text-emerald-700">
                        <span class="material-symbols-outlined text-[16px]">close</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium flex items-center justify-between shadow-xs animate-in fade-in" id="alertError">
                    <div class="flex items-center gap-2.5">
                        <span class="material-symbols-outlined text-rose-600 text-[20px]">error</span>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button onclick="document.getElementById('alertError').remove()" class="text-rose-500 hover:text-rose-700">
                        <span class="material-symbols-outlined text-[16px]">close</span>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs shadow-xs animate-in fade-in">
                    <div class="flex items-center gap-2 font-bold mb-1">
                        <span class="material-symbols-outlined text-[18px]">warning</span>
                        <span>Terdapat kesalahan pengisian data:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 text-[11px]">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Hero Metric Cards (4 Cards) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="p-4 rounded-2xl bg-canvas-pure border border-hairline shadow-xs">
                    <div class="flex items-center justify-between text-ink-muted text-xs">
                        <span>Total Ruangan</span>
                        <span class="material-symbols-outlined text-primary text-[18px]">meeting_room</span>
                    </div>
                    <div class="text-2xl font-bold text-ink-body mt-2" id="metricTotalRuangan">{{ $totalRuangan }}</div>
                    <span class="text-[11px] text-ink-muted mt-0.5 block">Di seluruh cabang aktif</span>
                </div>

                <div class="p-4 rounded-2xl bg-canvas-pure border border-hairline shadow-xs">
                    <div class="flex items-center justify-between text-ink-muted text-xs">
                        <span>Ruangan Aktif</span>
                        <span class="material-symbols-outlined text-schedule-verified text-[18px]">check_circle</span>
                    </div>
                    <div class="text-2xl font-bold text-schedule-verified mt-2" id="metricRuanganAktif">{{ $ruanganAktif }}</div>
                    <span class="text-[11px] text-ink-muted mt-0.5 block">Tersedia untuk penjadwalan</span>
                </div>

                <div class="p-4 rounded-2xl bg-canvas-pure border border-hairline shadow-xs">
                    <div class="flex items-center justify-between text-ink-muted text-xs">
                        <span>Ruangan Nonaktif</span>
                        <span class="material-symbols-outlined text-ink-subtle text-[18px]">pause_circle</span>
                    </div>
                    <div class="text-2xl font-bold text-ink-muted mt-2" id="metricRuanganNonaktif">{{ $ruanganNonaktif }}</div>
                    <span class="text-[11px] text-ink-muted mt-0.5 block">Pemeliharaan / dinonaktifkan</span>
                </div>

                <div class="p-4 rounded-2xl bg-canvas-pure border border-hairline shadow-xs">
                    <div class="flex items-center justify-between text-ink-muted text-xs">
                        <span>Cabang Terdaftar</span>
                        <span class="material-symbols-outlined text-primary text-[18px]">apartment</span>
                    </div>
                    <div class="text-2xl font-bold text-primary mt-2">{{ $allCabangs->count() }} Cabang</div>
                    <span class="text-[11px] text-ink-muted mt-0.5 block">Lokasi belajar operasional</span>
                </div>
            </div>

            <!-- Toolbar: Search & Filter Bar -->
            <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3 bg-canvas-pure p-3.5 rounded-2xl border border-hairline shadow-xs">
                <!-- Search Input Form -->
                <form method="GET" action="{{ route('superadmin.ruangan.index') }}" class="flex items-center gap-2 flex-1 max-w-md">
                    @if($cabangFilter !== 'all')
                        <input type="hidden" name="cabang_id" value="{{ $cabangFilter }}">
                    @endif
                    @if($statusFilter !== 'all')
                        <input type="hidden" name="status" value="{{ $statusFilter }}">
                    @endif
                    <div class="relative w-full">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-ink-subtle text-[18px]">search</span>
                        <input type="text" 
                               name="q" 
                               value="{{ $search }}" 
                               id="ruanganSearchInput"
                               placeholder="Cari nama ruangan atau cabang..."
                               class="w-full pl-9 pr-8 py-2 rounded-xl bg-canvas-parchment text-xs text-ink-body placeholder:text-ink-subtle border border-hairline focus:bg-canvas-pure focus:outline-none focus:ring-2 focus:ring-primary/20">
                        @if($search !== '')
                            <a href="{{ route('superadmin.ruangan.index', ['cabang_id' => $cabangFilter, 'status' => $statusFilter]) }}" 
                               class="absolute right-2.5 top-1/2 -translate-y-1/2 text-ink-subtle hover:text-ink-body">
                                <span class="material-symbols-outlined text-[16px]">close</span>
                            </a>
                        @endif
                    </div>
                </form>

                <!-- Filter Dropdowns -->
                <div class="flex items-center gap-2 flex-wrap">
                    <form method="GET" action="{{ route('superadmin.ruangan.index') }}" id="filterForm" class="flex items-center gap-2">
                        @if($search !== '')
                            <input type="hidden" name="q" value="{{ $search }}">
                        @endif

                        <!-- Filter Cabang -->
                        <select name="cabang_id" 
                                id="ruanganCabangFilter"
                                onchange="document.getElementById('filterForm').submit()"
                                class="px-3 py-2 rounded-xl bg-canvas-parchment text-xs font-medium text-ink-body border border-hairline focus:outline-none focus:ring-2 focus:ring-primary/20 cursor-pointer">
                            <option value="all" {{ $cabangFilter === 'all' ? 'selected' : '' }}>Semua Cabang</option>
                            @foreach($allCabangs as $c)
                                <option value="{{ $c->id }}" {{ (string)$cabangFilter === (string)$c->id ? 'selected' : '' }}>
                                    Cabang {{ $c->nama_cabang }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Filter Status -->
                        <select name="status" 
                                id="ruanganStatusFilter"
                                onchange="document.getElementById('filterForm').submit()"
                                class="px-3 py-2 rounded-xl bg-canvas-parchment text-xs font-medium text-ink-body border border-hairline focus:outline-none focus:ring-2 focus:ring-primary/20 cursor-pointer">
                            <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>Semua Status</option>
                            <option value="aktif" {{ $statusFilter === 'aktif' ? 'selected' : '' }}>Hanya Aktif</option>
                            <option value="nonaktif" {{ $statusFilter === 'nonaktif' ? 'selected' : '' }}>Hanya Nonaktif</option>
                        </select>
                    </form>

                    @if($search !== '' || $cabangFilter !== 'all' || $statusFilter !== 'all')
                        <a href="{{ route('superadmin.ruangan.index') }}" 
                           class="p-2 rounded-xl text-ink-muted hover:text-ink-body hover:bg-surface-container-low transition-colors"
                           title="Reset filter">
                            <span class="material-symbols-outlined text-[18px]">restart_alt</span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Primary Split View: Table (8 cols) + Inspector Panel (4 cols) -->
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">
                <!-- Ruangan List Table (8 of 12 cols) -->
                <div class="xl:col-span-8 bg-canvas-pure rounded-2xl border border-hairline shadow-xs overflow-hidden flex flex-col">
                    <div class="px-6 py-4 border-b border-hairline flex items-center justify-between bg-surface-pearl/50">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-[20px]">meeting_room</span>
                            <h2 class="text-sm font-bold text-ink-body">Daftar Ruangan Belajar</h2>
                        </div>
                        <span class="text-xs text-ink-muted">Menampilkan <strong class="text-ink-body font-bold">{{ $ruangans->count() }}</strong> ruangan</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse" id="ruanganTable">
                            <thead>
                                <tr class="text-ink-muted text-[11px] uppercase tracking-wider bg-canvas-parchment/60 border-b border-hairline font-semibold">
                                    <th class="py-3 px-5">Nama Ruangan</th>
                                    <th class="py-3 px-5">Cabang</th>
                                    <th class="py-3 px-4 text-center">Kapasitas</th>
                                    <th class="py-3 px-4 text-center">Jadwal Terkait</th>
                                    <th class="py-3 px-4 text-center">Status</th>
                                    <th class="py-3 px-5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-hairline text-xs" id="ruanganTableBody">
                                @forelse($ruangans as $ruangan)
                                    @php
                                        $isSelected = $selectedRuangan && $selectedRuangan->id === $ruangan->id;
                                    @endphp
                                    <tr class="ruangan-row transition-colors cursor-pointer {{ $isSelected ? 'bg-accent-subtle/40' : 'hover:bg-surface-pearl/50' }}"
                                        onclick="window.location='{{ route('superadmin.ruangan.index', array_merge(request()->query(), ['selected' => $ruangan->id])) }}'">
                                        <!-- Nama Ruangan -->
                                        <td class="py-3.5 px-5 font-semibold text-ink-body">
                                            <div class="flex items-center gap-2.5">
                                                <div class="w-8 h-8 rounded-lg bg-surface-container-low text-primary flex items-center justify-center shrink-0">
                                                    <span class="material-symbols-outlined text-[18px]">meeting_room</span>
                                                </div>
                                                <div>
                                                    <span class="font-bold text-ink-body block">{{ $ruangan->nama_ruangan }}</span>
                                                    <span class="text-[10px] text-ink-muted font-mono">ID: #RNG-{{ str_pad($ruangan->id, 3, '0', STR_PAD_LEFT) }}</span>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Cabang -->
                                        <td class="py-3.5 px-5">
                                            <span class="inline-flex items-center gap-1 font-medium text-ink-body">
                                                <span class="material-symbols-outlined text-[15px] text-primary">location_on</span>
                                                Cabang {{ $ruangan->cabang->nama_cabang ?? '-' }}
                                            </span>
                                        </td>

                                        <!-- Kapasitas -->
                                        <td class="py-3.5 px-4 text-center text-ink-muted">
                                            @if($ruangan->kapasitas)
                                                <span class="px-2 py-0.5 rounded-full bg-surface-container-low text-ink-body font-semibold text-[11px]">
                                                    {{ $ruangan->kapasitas }} Orang
                                                </span>
                                            @else
                                                <span class="text-ink-subtle italic">-</span>
                                            @endif
                                        </td>

                                        <!-- Jadwal Terkait -->
                                        <td class="py-3.5 px-4 text-center">
                                            <div class="flex flex-col items-center">
                                                <span class="font-bold text-ink-body text-xs">{{ $ruangan->total_jadwal_count ?? 0 }}</span>
                                                <span class="text-[10px] text-ink-muted">{{ $ruangan->jadwal_aktif_count ?? 0 }} aktif</span>
                                            </div>
                                        </td>

                                        <!-- Status Badge -->
                                        <td class="py-3.5 px-4 text-center">
                                            @if($ruangan->status === 'aktif')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-schedule-verified border border-emerald-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-schedule-verified"></span>
                                                    Aktif
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-surface-container-low text-ink-muted border border-hairline">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-ink-subtle"></span>
                                                    Nonaktif
                                                </span>
                                            @endif
                                        </td>

                                        <!-- Aksi -->
                                        <td class="py-3.5 px-5 text-right" onclick="event.stopPropagation()">
                                            <div class="flex items-center justify-end gap-1">
                                                <!-- Edit Button -->
                                                <button type="button" 
                                                        onclick="openEditModal({{ json_encode($ruangan) }})"
                                                        class="p-1.5 rounded-lg text-ink-muted hover:text-primary hover:bg-surface-container-low transition-colors"
                                                        title="Ubah Ruangan">
                                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                                </button>

                                                <!-- Toggle Status -->
                                                <form action="{{ route('superadmin.ruangan.toggle-status', $ruangan->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="p-1.5 rounded-lg text-ink-muted hover:text-amber-600 hover:bg-amber-50 transition-colors"
                                                            title="{{ $ruangan->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                        <span class="material-symbols-outlined text-[18px]">
                                                            {{ $ruangan->status === 'aktif' ? 'pause_circle' : 'play_circle' }}
                                                        </span>
                                                    </button>
                                                </form>

                                                <!-- Delete Button -->
                                                <button type="button" 
                                                        onclick="openDeleteModal({{ $ruangan->id }}, '{{ addslashes($ruangan->nama_ruangan) }}', {{ $ruangan->total_jadwal_count ?? 0 }})"
                                                        class="p-1.5 rounded-lg text-ink-muted hover:text-error hover:bg-rose-50 transition-colors"
                                                        title="Hapus Ruangan">
                                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-12 text-center text-ink-muted">
                                            <div class="flex flex-col items-center justify-center gap-2">
                                                <span class="material-symbols-outlined text-4xl text-ink-subtle">meeting_room</span>
                                                <p class="font-medium text-sm">Tidak ada ruangan yang ditemukan.</p>
                                                <p class="text-xs">Coba sesuaikan kata kunci pencarian atau filter cabang/status.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Quick Inspector Panel (4 of 12 cols) -->
                <div class="xl:col-span-4 bg-canvas-pure rounded-2xl border border-hairline shadow-xs p-6 flex flex-col gap-5 sticky top-24">
                    @if($selectedRuangan)
                        <div class="flex items-start justify-between border-b border-hairline pb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-orange-100 text-primary flex items-center justify-center font-bold text-xl">
                                    <span class="material-symbols-outlined text-[28px]">meeting_room</span>
                                </div>
                                <div>
                                    <h3 class="font-bold text-base text-ink-body">{{ $selectedRuangan->nama_ruangan }}</h3>
                                    <span class="text-xs text-ink-muted flex items-center gap-1 mt-0.5">
                                        <span class="material-symbols-outlined text-[14px] text-primary">location_on</span>
                                        Cabang {{ $selectedRuangan->cabang->nama_cabang ?? '-' }}
                                    </span>
                                </div>
                            </div>
                            @if($selectedRuangan->status === 'aktif')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-schedule-verified border border-emerald-200">
                                    Aktif
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-surface-container-low text-ink-muted border border-hairline">
                                    Nonaktif
                                </span>
                            @endif
                        </div>

                        <!-- Detail Information Grid -->
                        <div class="grid grid-cols-2 gap-3 text-xs">
                            <div class="p-3 rounded-xl bg-surface-pearl border border-hairline">
                                <span class="text-ink-muted block text-[10px] uppercase font-bold tracking-wider">Kapasitas</span>
                                <span class="font-bold text-ink-body text-sm mt-0.5 block">
                                    {{ $selectedRuangan->kapasitas ? $selectedRuangan->kapasitas . ' Orang' : 'Fleksibel' }}
                                </span>
                            </div>
                            <div class="p-3 rounded-xl bg-surface-pearl border border-hairline">
                                <span class="text-ink-muted block text-[10px] uppercase font-bold tracking-wider">Jadwal Terdaftar</span>
                                <span class="font-bold text-ink-body text-sm mt-0.5 block">
                                    {{ $selectedRuangan->total_jadwal_count ?? 0 }} Sesi
                                </span>
                            </div>
                        </div>

                        <!-- Inline Edit Form -->
                        <div class="space-y-3 pt-2 border-t border-hairline">
                            <h4 class="text-xs font-bold text-ink-body uppercase tracking-wider text-ink-muted">Ubah Data Cepat</h4>
                            <form action="{{ route('superadmin.ruangan.update', $selectedRuangan->id) }}" method="POST" class="space-y-3">
                                @csrf
                                @method('PUT')

                                <div>
                                    <label class="block text-xs font-semibold text-ink-body mb-1">Cabang Penugasan</label>
                                    <select name="cabang_id" class="w-full px-3 py-1.5 rounded-xl bg-surface-pearl text-xs text-ink-body border border-hairline focus:outline-none focus:ring-2 focus:ring-primary/20 cursor-pointer" required>
                                        @foreach($allCabangs as $c)
                                            <option value="{{ $c->id }}" {{ $selectedRuangan->cabang_id === $c->id ? 'selected' : '' }}>
                                                Cabang {{ $c->nama_cabang }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-ink-body mb-1">Nama Ruangan</label>
                                    <input type="text" name="nama_ruangan" value="{{ $selectedRuangan->nama_ruangan }}" class="w-full px-3 py-1.5 rounded-xl bg-surface-pearl text-xs text-ink-body border border-hairline focus:outline-none focus:ring-2 focus:ring-primary/20" required>
                                </div>

                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="block text-xs font-semibold text-ink-body mb-1">Kapasitas</label>
                                        <input type="number" name="kapasitas" value="{{ $selectedRuangan->kapasitas }}" min="1" max="1000" placeholder="Opsional" class="w-full px-3 py-1.5 rounded-xl bg-surface-pearl text-xs text-ink-body border border-hairline focus:outline-none focus:ring-2 focus:ring-primary/20">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-ink-body mb-1">Status</label>
                                        <select name="status" class="w-full px-3 py-1.5 rounded-xl bg-surface-pearl text-xs text-ink-body border border-hairline focus:outline-none focus:ring-2 focus:ring-primary/20 cursor-pointer" required>
                                            <option value="aktif" {{ $selectedRuangan->status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                            <option value="nonaktif" {{ $selectedRuangan->status === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                        </select>
                                    </div>
                                </div>

                                <button type="submit" class="w-full py-2 rounded-xl bg-primary hover:bg-primary-container text-white text-xs font-semibold shadow-xs transition-all active:scale-95 cursor-pointer">
                                    Simpan Pembaruan
                                </button>
                            </form>
                        </div>

                        <!-- Recent Schedules using this room -->
                        <div class="space-y-2 pt-2 border-t border-hairline">
                            <span class="text-xs font-bold text-ink-body uppercase tracking-wider text-ink-muted">Aktivitas Terakhir di Ruangan</span>
                            @if($selectedRuangan->jadwals && $selectedRuangan->jadwals->isNotEmpty())
                                <div class="space-y-2">
                                    @foreach($selectedRuangan->jadwals as $j)
                                        <div class="p-2.5 rounded-xl bg-surface-pearl border border-hairline text-xs flex items-center justify-between">
                                            <div class="min-w-0">
                                                <span class="font-bold text-ink-body block truncate">{{ $j->program->nama_program ?? $j->nama_kelas }}</span>
                                                <span class="text-[10px] text-ink-muted">{{ $j->tanggal ? $j->tanggal->format('d M Y') : '-' }} • {{ substr($j->jam_mulai, 0, 5) }}-{{ substr($j->jam_selesai, 0, 5) }}</span>
                                            </div>
                                            <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-surface-container-low text-ink-muted shrink-0">
                                                {{ $j->tentor->nama ?? 'Tentor' }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="py-4 text-center text-xs text-ink-muted italic">
                                    Belum ada aktivitas jadwal pada ruangan ini.
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="py-12 text-center text-ink-muted">
                            <span class="material-symbols-outlined text-4xl text-ink-subtle mb-1">meeting_room</span>
                            <p class="font-medium text-xs">Pilih salah satu ruangan dari tabel untuk melihat rincian.</p>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    <!-- MODAL: Tambah Ruangan Baru -->
    <div id="modalTambahRuangan" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-ink-body/50 backdrop-blur-xs">
        <div class="bg-canvas-pure rounded-2xl border border-hairline shadow-xl max-w-md w-full overflow-hidden animate-in fade-in zoom-in-95">
            <div class="px-6 py-4 border-b border-hairline flex items-center justify-between bg-surface-pearl">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[20px]">add_circle</span>
                    <h3 class="font-bold text-sm text-ink-body">Tambah Ruangan Baru</h3>
                </div>
                <button type="button" onclick="closeTambahModal()" class="text-ink-muted hover:text-ink-body">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <form action="{{ route('superadmin.ruangan.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label for="create_cabang_id" class="block text-xs font-bold text-ink-body mb-1">Cabang Penugasan <span class="text-red-500">*</span></label>
                    <select id="create_cabang_id" name="cabang_id" required class="w-full px-3.5 py-2 bg-surface-pearl border border-hairline rounded-xl text-xs text-ink-body focus:border-primary focus:bg-canvas-pure outline-none cursor-pointer">
                        <option value="" disabled selected>Pilih Cabang Lokasi</option>
                        @foreach($activeCabangs as $c)
                            <option value="{{ $c->id }}">Cabang {{ $c->nama_cabang }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="create_nama_ruangan" class="block text-xs font-bold text-ink-body mb-1">Nama Ruangan <span class="text-red-500">*</span></label>
                    <input type="text" id="create_nama_ruangan" name="nama_ruangan" placeholder="Contoh: Ruang 1, Lab Multimedia" required class="w-full px-3.5 py-2 bg-surface-pearl border border-hairline rounded-xl text-xs text-ink-body focus:border-primary focus:bg-canvas-pure outline-none">
                </div>

                <div>
                    <label for="create_kapasitas" class="block text-xs font-bold text-ink-body mb-1">Kapasitas Maksimal (Siswa)</label>
                    <input type="number" id="create_kapasitas" name="kapasitas" min="1" max="1000" placeholder="Opsional (misal: 15)" class="w-full px-3.5 py-2 bg-surface-pearl border border-hairline rounded-xl text-xs text-ink-body focus:border-primary focus:bg-canvas-pure outline-none">
                </div>

                <div>
                    <label for="create_status" class="block text-xs font-bold text-ink-body mb-1">Status Operasional <span class="text-red-500">*</span></label>
                    <select id="create_status" name="status" required class="w-full px-3.5 py-2 bg-surface-pearl border border-hairline rounded-xl text-xs text-ink-body focus:border-primary focus:bg-canvas-pure outline-none cursor-pointer">
                        <option value="aktif" selected>Aktif (Dapat Dijadwalkan)</option>
                        <option value="nonaktif">Nonaktif (Pemeliharaan)</option>
                    </select>
                </div>

                <div class="pt-3 border-t border-hairline flex items-center justify-end gap-2">
                    <button type="button" onclick="closeTambahModal()" class="px-4 py-2 rounded-xl bg-surface-pearl hover:bg-surface-container-low text-xs font-semibold text-ink-body transition-colors">
                        Batal
                    </button>
                    <button type="submit" id="submitTambahRuangan" class="px-5 py-2 rounded-xl bg-primary hover:bg-primary-container text-white text-xs font-semibold shadow-xs transition-all active:scale-95">
                        Simpan Ruangan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: Edit Ruangan -->
    <div id="modalEditRuangan" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-ink-body/50 backdrop-blur-xs">
        <div class="bg-canvas-pure rounded-2xl border border-hairline shadow-xl max-w-md w-full overflow-hidden animate-in fade-in zoom-in-95">
            <div class="px-6 py-4 border-b border-hairline flex items-center justify-between bg-surface-pearl">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[20px]">edit</span>
                    <h3 class="font-bold text-sm text-ink-body">Ubah Data Ruangan</h3>
                </div>
                <button type="button" onclick="closeEditModal()" class="text-ink-muted hover:text-ink-body">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <form id="formEditRuangan" action="" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label for="edit_cabang_id" class="block text-xs font-bold text-ink-body mb-1">Cabang Penugasan <span class="text-red-500">*</span></label>
                    <select id="edit_cabang_id" name="cabang_id" required class="w-full px-3.5 py-2 bg-surface-pearl border border-hairline rounded-xl text-xs text-ink-body focus:border-primary focus:bg-canvas-pure outline-none cursor-pointer">
                        @foreach($allCabangs as $c)
                            <option value="{{ $c->id }}">Cabang {{ $c->nama_cabang }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="edit_nama_ruangan" class="block text-xs font-bold text-ink-body mb-1">Nama Ruangan <span class="text-red-500">*</span></label>
                    <input type="text" id="edit_nama_ruangan" name="nama_ruangan" required class="w-full px-3.5 py-2 bg-surface-pearl border border-hairline rounded-xl text-xs text-ink-body focus:border-primary focus:bg-canvas-pure outline-none">
                </div>

                <div>
                    <label for="edit_kapasitas" class="block text-xs font-bold text-ink-body mb-1">Kapasitas Maksimal (Siswa)</label>
                    <input type="number" id="edit_kapasitas" name="kapasitas" min="1" max="1000" class="w-full px-3.5 py-2 bg-surface-pearl border border-hairline rounded-xl text-xs text-ink-body focus:border-primary focus:bg-canvas-pure outline-none">
                </div>

                <div>
                    <label for="edit_status" class="block text-xs font-bold text-ink-body mb-1">Status Operasional <span class="text-red-500">*</span></label>
                    <select id="edit_status" name="status" required class="w-full px-3.5 py-2 bg-surface-pearl border border-hairline rounded-xl text-xs text-ink-body focus:border-primary focus:bg-canvas-pure outline-none cursor-pointer">
                        <option value="aktif">Aktif (Dapat Dijadwalkan)</option>
                        <option value="nonaktif">Nonaktif (Pemeliharaan)</option>
                    </select>
                </div>

                <div class="pt-3 border-t border-hairline flex items-center justify-end gap-2">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 rounded-xl bg-surface-pearl hover:bg-surface-container-low text-xs font-semibold text-ink-body transition-colors">
                        Batal
                    </button>
                    <button type="submit" id="submitEditRuangan" class="px-5 py-2 rounded-xl bg-primary hover:bg-primary-container text-white text-xs font-semibold shadow-xs transition-all active:scale-95">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: Konfirmasi Hapus Ruangan -->
    <div id="modalHapusRuangan" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-ink-body/50 backdrop-blur-xs">
        <div class="bg-canvas-pure rounded-2xl border border-hairline shadow-xl max-w-sm w-full overflow-hidden animate-in fade-in zoom-in-95">
            <div class="p-6 text-center">
                <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-[24px]">delete</span>
                </div>
                <h3 class="text-sm font-bold text-ink-body mb-1">Hapus Ruangan Kelas?</h3>
                <p class="text-xs text-ink-muted mb-4">
                    Apakah Anda yakin ingin menghapus ruangan <strong id="delete_ruangan_name" class="text-ink-body"></strong>?
                </p>
                <div id="delete_warning" class="hidden p-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-[11px] mb-4 text-left">
                    <div class="flex items-center gap-1.5 font-bold mb-0.5">
                        <span class="material-symbols-outlined text-[16px]">warning</span>
                        <span>Ruangan memiliki jadwal!</span>
                    </div>
                    Ruangan ini masih memiliki riwayat jadwal terkait dan tidak dapat dihapus. Silakan pilih opsi nonaktifkan saja.
                </div>

                <div class="flex items-center justify-center gap-2">
                    <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 rounded-xl bg-surface-pearl hover:bg-surface-container-low text-xs font-semibold text-ink-body transition-colors">
                        Batal
                    </button>
                    <form id="formDeleteRuangan" action="" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" id="confirmDeleteRuangan" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold shadow-xs transition-all active:scale-95">
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Interactive Scripts -->
    <script>
        function openTambahModal() {
            const m = document.getElementById('modalTambahRuangan');
            m.classList.remove('hidden');
            m.classList.add('flex');
        }

        function closeTambahModal() {
            const m = document.getElementById('modalTambahRuangan');
            m.classList.add('hidden');
            m.classList.remove('flex');
        }

        function openEditModal(ruangan) {
            document.getElementById('formEditRuangan').action = '/superadmin/ruangan/' + ruangan.id;
            document.getElementById('edit_cabang_id').value = ruangan.cabang_id;
            document.getElementById('edit_nama_ruangan').value = ruangan.nama_ruangan;
            document.getElementById('edit_kapasitas').value = ruangan.kapasitas || '';
            document.getElementById('edit_status').value = ruangan.status;

            const m = document.getElementById('modalEditRuangan');
            m.classList.remove('hidden');
            m.classList.add('flex');
        }

        function closeEditModal() {
            const m = document.getElementById('modalEditRuangan');
            m.classList.add('hidden');
            m.classList.remove('flex');
        }

        function openDeleteModal(id, nama, jadwalCount) {
            document.getElementById('formDeleteRuangan').action = '/superadmin/ruangan/' + id;
            document.getElementById('delete_ruangan_name').textContent = nama;

            const warn = document.getElementById('delete_warning');
            const submitBtn = document.getElementById('confirmDeleteRuangan');

            if (jadwalCount > 0) {
                warn.classList.remove('hidden');
            } else {
                warn.classList.add('hidden');
            }

            const m = document.getElementById('modalHapusRuangan');
            m.classList.remove('hidden');
            m.classList.add('flex');
        }

        function closeDeleteModal() {
            const m = document.getElementById('modalHapusRuangan');
            m.classList.add('hidden');
            m.classList.remove('flex');
        }
    </script>
</body>
</html>
