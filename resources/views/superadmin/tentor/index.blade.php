<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manajemen Tentor — Superadmin Elips Academy</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
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
<body class="bg-canvas-parchment text-ink-body antialiased selection:bg-accent-subtle selection:text-primary font-sans min-h-screen">

<div class="flex min-h-screen">
    <!-- Left Navigation Sidebar -->
    <aside id="sidebarNav" class="w-64 bg-canvas-pure border-r border-hairline flex flex-col justify-between fixed inset-y-0 left-0 z-50 -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out shadow-[0_1px_8px_rgba(0,0,0,0.04)]">
        <div>
            <!-- Brand Logo -->
            <div class="h-16 flex items-center justify-between px-6 border-b border-hairline">
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
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold bg-primary-container text-white shadow-[0_1px_4px_rgba(242,142,43,0.25)] transition-all">
                    <span class="material-symbols-outlined text-[20px]">badge</span>
                    <span>Tentor</span>
                </a>

                <a href="{{ route('superadmin.user.index') }}" 
                   id="nav-superadmin-user"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-ink-muted hover:bg-surface-container-low hover:text-ink-body transition-all">
                    <span class="material-symbols-outlined text-[20px]">manage_accounts</span>
                    <span>Akun Pengguna</span>
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

    <!-- Main Content Wrapper -->
    <div class="lg:pl-64 flex flex-col flex-1 min-h-screen w-full">
        <!-- Topbar Header -->
        <header class="sticky top-0 h-16 bg-canvas-pure/90 backdrop-blur-md border-b border-hairline z-40 flex items-center justify-between px-4 sm:px-6 lg:px-8 gap-3">
            <div class="flex items-center gap-2.5 text-xs text-ink-muted">
                <button type="button" onclick="toggleSidebar()" class="lg:hidden p-2 -ml-2 rounded-xl text-ink-muted hover:bg-surface-container-low hover:text-ink-body transition-colors shrink-0" aria-label="Buka Menu">
                    <span class="material-symbols-outlined text-[24px]">menu</span>
                </button>
                <a href="{{ route('superadmin.dashboard') }}" class="hover:text-primary transition-colors hidden sm:inline">Portal Superadmin</a>
                <span class="hidden sm:inline">/</span>
                <span class="text-ink-body font-semibold truncate">Master Data Tentor Pengajar</span>
            </div>

            <div class="flex items-center gap-2 sm:gap-3">
                <span class="hidden md:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-accent-subtle text-primary border border-primary/20">
                    <span class="w-2 h-2 rounded-full bg-primary-container"></span>
                    Superadmin
                </span>
                <button onclick="openCreateTentorModal()" 
                        id="btnOpenCreateTentorModal"
                        class="flex items-center gap-1.5 sm:gap-2 px-3.5 sm:px-4 py-2 rounded-full bg-primary-container text-white text-xs font-semibold hover:bg-brand-hover shadow-sm transition-all active:scale-95">
                    <span class="material-symbols-outlined text-[16px]">person_add</span>
                    <span>Tambah Tentor</span>
                </button>
            </div>
        </header>

        <!-- Main Workspace -->
        <main class="p-8 flex-1 flex flex-col gap-6">
            <!-- Flash Alerts -->
            @if(session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex items-center justify-between shadow-xs animate-in fade-in">
                    <div class="flex items-center gap-2.5">
                        <span class="material-symbols-outlined text-emerald-600 text-[20px]">check_circle</span>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                        <span class="material-symbols-outlined text-[16px]">close</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium flex items-center justify-between shadow-xs animate-in fade-in">
                    <div class="flex items-center gap-2.5">
                        <span class="material-symbols-outlined text-rose-600 text-[20px]">error</span>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">
                        <span class="material-symbols-outlined text-[16px]">close</span>
                    </button>
                </div>
            @endif

            <!-- Hero Metric Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="p-4 rounded-2xl bg-canvas-pure border border-hairline shadow-xs">
                    <div class="flex items-center justify-between text-ink-muted text-xs">
                        <span>Total Tentor</span>
                        <span class="material-symbols-outlined text-primary text-[18px]">badge</span>
                    </div>
                    <div class="text-2xl font-bold text-ink-body mt-2">{{ $totalTentor }}</div>
                    <span class="text-[11px] text-ink-muted mt-0.5 block">Instruktur & pengajar terdaftar</span>
                </div>

                <div class="p-4 rounded-2xl bg-canvas-pure border border-hairline shadow-xs">
                    <div class="flex items-center justify-between text-ink-muted text-xs">
                        <span>Tentor Aktif</span>
                        <span class="material-symbols-outlined text-schedule-verified text-[18px]">verified</span>
                    </div>
                    <div class="text-2xl font-bold text-schedule-verified mt-2">{{ $tentorAktif }}</div>
                    <span class="text-[11px] text-ink-muted mt-0.5 block">Tersedia untuk penetapan jadwal</span>
                </div>

                <div class="p-4 rounded-2xl bg-canvas-pure border border-hairline shadow-xs">
                    <div class="flex items-center justify-between text-ink-muted text-xs">
                        <span>Mengajar Hari Ini</span>
                        <span class="material-symbols-outlined text-primary text-[18px]">co_present</span>
                    </div>
                    <div class="text-2xl font-bold text-primary mt-2">{{ $tentorMengajarHariIni }}</div>
                    <span class="text-[11px] text-ink-muted mt-0.5 block">{{ $totalSesiHariIni }} sesi kelas aktif hari ini</span>
                </div>

                <div class="p-4 rounded-2xl bg-canvas-pure border border-hairline shadow-xs">
                    <div class="flex items-center justify-between text-ink-muted text-xs">
                        <span>Tentor Nonaktif</span>
                        <span class="material-symbols-outlined text-ink-subtle text-[18px]">person_off</span>
                    </div>
                    <div class="text-2xl font-bold text-ink-muted mt-2">{{ $tentorNonaktif }}</div>
                    <span class="text-[11px] text-ink-muted mt-0.5 block">Cuti / nonaktif sementara</span>
                </div>
            </div>

            <!-- Toolbar & Filter Bar -->
            <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3 bg-canvas-pure p-3.5 rounded-2xl border border-hairline shadow-xs">
                <!-- Search -->
                <form method="GET" action="{{ route('superadmin.tentor.index') }}" class="flex items-center gap-2 flex-1 max-w-md">
                    @if($statusFilter !== 'all')
                        <input type="hidden" name="status" value="{{ $statusFilter }}">
                    @endif
                    <div class="relative w-full">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-ink-subtle text-[18px]">search</span>
                        <input type="text" 
                               name="q" 
                               value="{{ $search }}" 
                               id="tentorSearchInput"
                               placeholder="Cari nama tentor, WhatsApp, atau keahlian..."
                               class="w-full pl-9 pr-8 py-2 rounded-xl bg-canvas-parchment text-xs text-ink-body placeholder:text-ink-subtle border border-hairline focus:bg-canvas-pure focus:outline-none focus:ring-2 focus:ring-primary/20">
                        @if($search !== '')
                            <a href="{{ route('superadmin.tentor.index', ['status' => $statusFilter]) }}" 
                               class="absolute right-2.5 top-1/2 -translate-y-1/2 text-ink-subtle hover:text-ink-body">
                                <span class="material-symbols-outlined text-[16px]">close</span>
                            </a>
                        @endif
                    </div>
                </form>

                <!-- Filters -->
                <div class="flex items-center gap-2 flex-wrap">
                    <form method="GET" action="{{ route('superadmin.tentor.index') }}" id="tentorStatusFilterForm">
                        @if($search !== '')
                            <input type="hidden" name="q" value="{{ $search }}">
                        @endif
                        <select name="status" 
                                onchange="document.getElementById('tentorStatusFilterForm').submit()"
                                id="tentorStatusFilter"
                                class="px-3 py-2 rounded-xl bg-canvas-parchment text-xs font-medium text-ink-body border border-hairline focus:outline-none focus:ring-2 focus:ring-primary/20 cursor-pointer">
                            <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>Semua Status Tentor</option>
                            <option value="aktif" {{ $statusFilter === 'aktif' ? 'selected' : '' }}>Hanya Aktif</option>
                            <option value="nonaktif" {{ $statusFilter === 'nonaktif' ? 'selected' : '' }}>Hanya Nonaktif</option>
                        </select>
                    </form>
                </div>
            </div>

            <!-- Primary Split View: List Table + Quick Inspector -->
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">
                <!-- Tentor List Table (8 of 12 cols) -->
                <div class="xl:col-span-8 bg-canvas-pure rounded-2xl border border-hairline shadow-xs overflow-hidden flex flex-col">
                    <div class="px-6 py-4 border-b border-hairline flex items-center justify-between bg-surface-pearl/50">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-[20px]">groups</span>
                            <h2 class="text-sm font-bold text-ink-body">Daftar Tentor Pengajar</h2>
                        </div>
                        <span class="text-xs text-ink-muted">Tabel: <code class="text-primary font-mono font-medium">tentor</code></span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse" id="tentorTable">
                            <thead>
                                <tr class="text-ink-muted text-[11px] uppercase tracking-wider bg-canvas-parchment/60 border-b border-hairline font-semibold">
                                    <th class="py-3 px-5">Tentor Pengajar</th>
                                    <th class="py-3 px-5">No. WhatsApp / HP</th>
                                    <th class="py-3 px-5">Bidang Keahlian</th>
                                    <th class="py-3 px-5 text-center">Beban Jadwal</th>
                                    <th class="py-3 px-5 text-center">Status</th>
                                    <th class="py-3 px-5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-hairline text-xs" id="tentorTableBody">
                                @forelse($tentors as $tentor)
                                    @php
                                        $isSelected = $selectedTentor && $selectedTentor->id === $tentor->id;
                                    @endphp
                                    <tr class="tentor-row transition-colors cursor-pointer {{ $isSelected ? 'bg-accent-subtle/40' : 'hover:bg-surface-pearl/50' }}"
                                        onclick="window.location='{{ route('superadmin.tentor.index', ['selected' => $tentor->id, 'q' => $search, 'status' => $statusFilter]) }}'"
                                        id="tentor-row-{{ $tentor->id }}">
                                        <td class="py-3.5 px-5 align-top">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-full {{ $tentor->status === 'aktif' ? 'bg-primary text-white' : 'bg-surface-container-low text-ink-muted' }} flex items-center justify-center font-bold text-xs shrink-0 shadow-xs">
                                                    {{ strtoupper(substr($tentor->nama, 0, 1)) }}
                                                </div>
                                                <div class="flex flex-col min-w-0">
                                                    <span class="font-bold text-ink-body text-sm flex items-center gap-1.5 truncate">
                                                        {{ $tentor->nama }}
                                                        @if($tentor->status === 'aktif')
                                                            <span class="material-symbols-outlined text-schedule-verified text-[14px]">verified</span>
                                                        @endif
                                                    </span>
                                                    <span class="text-[11px] text-ink-muted font-mono">TNR-{{ sprintf('%03d', $tentor->id) }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3.5 px-5 align-top">
                                            @if($tentor->no_hp)
                                                <div class="flex flex-col">
                                                    <span class="font-mono text-ink-body">{{ $tentor->no_hp }}</span>
                                                    <span class="text-[10px] text-schedule-verified flex items-center gap-0.5">
                                                        <span class="material-symbols-outlined text-[12px]">chat</span> WhatsApp
                                                    </span>
                                                </div>
                                            @else
                                                <span class="text-ink-subtle">-</span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-5 align-top max-w-[200px]">
                                            <span class="text-ink-body line-clamp-2">{{ $tentor->keahlian ?: 'Umum / Segala Bidang' }}</span>
                                        </td>
                                        <td class="py-3.5 px-5 align-top text-center">
                                            <div class="flex flex-col items-center">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold {{ $tentor->jadwal_aktif_count > 0 ? 'bg-accent-subtle text-primary' : 'bg-surface-container-low text-ink-muted' }}">
                                                    {{ $tentor->jadwal_aktif_count }} Aktif
                                                </span>
                                                @if($tentor->jadwal_hari_ini_count > 0)
                                                    <span class="text-[10px] font-bold text-schedule-verified mt-1">
                                                        {{ $tentor->jadwal_hari_ini_count }} Sesi Hari Ini
                                                    </span>
                                                @else
                                                    <span class="text-[10px] text-ink-muted mt-1">Total: {{ $tentor->total_jadwal_count }} sesi</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-3.5 px-5 align-top text-center">
                                            @if($tentor->status === 'aktif')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-schedule-verified border border-emerald-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-schedule-verified"></span>
                                                    Aktif
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-500 border border-slate-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                                    Nonaktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-5 align-top text-right">
                                            <div class="flex items-center justify-end gap-1" onclick="event.stopPropagation()">
                                                <!-- Edit Button -->
                                                <button onclick="openEditTentorModal({{ json_encode($tentor) }})" 
                                                        title="Edit Tentor"
                                                        id="btn-edit-tentor-{{ $tentor->id }}"
                                                        class="p-1.5 rounded-lg text-ink-muted hover:text-ink-body hover:bg-surface-container-low transition-colors">
                                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                                </button>

                                                <!-- Toggle Status Form -->
                                                <form method="POST" action="{{ route('superadmin.tentor.toggle-status', $tentor) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            title="{{ $tentor->status === 'aktif' ? 'Nonaktifkan Tentor' : 'Aktifkan Tentor' }}"
                                                            id="btn-toggle-tentor-{{ $tentor->id }}"
                                                            class="p-1.5 rounded-lg {{ $tentor->status === 'aktif' ? 'text-amber-600 hover:bg-amber-50' : 'text-emerald-600 hover:bg-emerald-50' }} transition-colors">
                                                        <span class="material-symbols-outlined text-[18px]">
                                                            {{ $tentor->status === 'aktif' ? 'toggle_on' : 'toggle_off' }}
                                                        </span>
                                                    </button>
                                                </form>

                                                <!-- Delete Button -->
                                                <button onclick="confirmDeleteTentor({{ json_encode($tentor) }}, {{ $tentor->total_jadwal_count }})" 
                                                        title="Hapus Tentor"
                                                        id="btn-delete-tentor-{{ $tentor->id }}"
                                                        class="p-1.5 rounded-lg text-rose-500 hover:bg-rose-50 transition-colors">
                                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-12 px-6 text-center text-ink-muted">
                                            <div class="flex flex-col items-center justify-center gap-2">
                                                <span class="material-symbols-outlined text-4xl text-ink-subtle">badge</span>
                                                <span class="font-semibold text-sm">Tidak ada tentor pengajar yang cocok</span>
                                                <span class="text-xs text-ink-subtle">Coba ubah kata kunci pencarian atau filter status.</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="p-4 border-t border-hairline flex items-center justify-between text-xs text-ink-muted bg-canvas-parchment/30">
                        <span>Menampilkan {{ $tentors->count() }} tentor pengajar</span>
                        <span>Proteksi Relasi Data Aktif</span>
                    </div>
                </div>

                <!-- Right Column: Inspector Panel (4 of 12 cols) -->
                <div class="xl:col-span-4 sticky top-24 flex flex-col gap-4">
                    @if($selectedTentor)
                        <div class="bg-canvas-pure rounded-2xl border border-hairline p-6 shadow-xs flex flex-col gap-4" id="tentorInspector">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center font-bold text-base shadow-sm">
                                        {{ strtoupper(substr($selectedTentor->nama, 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="text-[11px] font-bold uppercase tracking-wider text-primary font-mono">
                                            TNR-{{ sprintf('%03d', $selectedTentor->id) }}
                                        </span>
                                        <h3 class="text-base font-bold text-ink-body" id="inspNamaTentor">{{ $selectedTentor->nama }}</h3>
                                        <span class="text-xs text-ink-muted block mt-0.5">{{ $selectedTentor->no_hp ?: 'Belum ada nomor kontak' }}</span>
                                    </div>
                                </div>
                                <div>
                                    @if($selectedTentor->status === 'aktif')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-schedule-verified border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-schedule-verified"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-500 border border-slate-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                            Nonaktif
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Specialization Badge Box -->
                            <div class="p-3 rounded-xl bg-canvas-parchment border border-hairline">
                                <span class="text-[11px] font-semibold text-ink-muted block mb-1">Bidang Keahlian / Silabus:</span>
                                <span class="text-xs font-medium text-ink-body">{{ $selectedTentor->keahlian ?: 'Umum / Semua Bidang Studi' }}</span>
                            </div>

                            <!-- Teaching Load Metrics -->
                            <div class="grid grid-cols-2 gap-3 p-3 rounded-xl bg-canvas-parchment border border-hairline">
                                <div>
                                    <span class="text-[11px] text-ink-muted">Kelas Hari Ini</span>
                                    <div class="text-base font-bold text-schedule-verified mt-0.5">{{ $selectedTentorTodayClasses->count() }} Sesi</div>
                                </div>
                                <div>
                                    <span class="text-[11px] text-ink-muted">Total Beban Aktif</span>
                                    <div class="text-base font-bold text-primary mt-0.5">{{ $selectedTentor->jadwal_aktif_count }} Kelas</div>
                                </div>
                            </div>

                            <!-- Today's Schedule -->
                            <div class="flex flex-col gap-2 pt-2 border-t border-hairline">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-bold text-ink-body">Jadwal Mengajar Hari Ini</span>
                                    <span class="text-ink-muted">{{ $selectedTentorTodayClasses->count() }} Kelas</span>
                                </div>

                                @if($selectedTentorTodayClasses->isNotEmpty())
                                    <div class="space-y-2 max-h-56 overflow-y-auto pr-1">
                                        @foreach($selectedTentorTodayClasses as $j)
                                            <div class="p-2.5 rounded-xl bg-emerald-50/60 border border-emerald-200/60 text-xs flex flex-col gap-1">
                                                <div class="flex items-center justify-between">
                                                    <span class="font-bold text-ink-body truncate">{{ $j->nama_kelas }}</span>
                                                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-semibold">
                                                        {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}
                                                    </span>
                                                </div>
                                                <div class="text-[11px] text-ink-muted flex items-center justify-between">
                                                    <span>{{ $j->cabang?->nama_cabang ?? 'Cabang' }} • {{ $j->ruangan ?: 'Ruang -' }}</span>
                                                    <span class="font-medium text-emerald-700">{{ $j->program?->nama_program ?? 'Program' }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="p-4 rounded-xl bg-canvas-parchment text-center text-xs text-ink-muted">
                                        Tidak ada jadwal mengajar pada hari ini.
                                    </div>
                                @endif
                            </div>

                            <!-- Inspector Action Buttons -->
                            <div class="grid grid-cols-2 gap-2 pt-2 border-t border-hairline">
                                <button onclick="openEditTentorModal({{ json_encode($selectedTentor) }})"
                                        id="btnInspEditTentor"
                                        class="px-4 py-2 rounded-xl bg-surface-container-low text-ink-body text-xs font-semibold hover:bg-surface-container-high transition-colors flex items-center justify-center gap-1.5">
                                    <span class="material-symbols-outlined text-[16px]">edit</span>
                                    <span>Edit Tentor</span>
                                </button>
                                <form method="POST" action="{{ route('superadmin.tentor.toggle-status', $selectedTentor) }}">
                                    @csrf
                                    <button type="submit" 
                                            id="btnInspToggleTentor"
                                            class="w-full px-4 py-2 rounded-xl text-xs font-semibold {{ $selectedTentor->status === 'aktif' ? 'bg-amber-50 text-amber-700 hover:bg-amber-100 border border-amber-200' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200' }} transition-colors flex items-center justify-center gap-1.5">
                                        <span class="material-symbols-outlined text-[16px]">
                                            {{ $selectedTentor->status === 'aktif' ? 'pause_circle' : 'play_circle' }}
                                        </span>
                                        <span>{{ $selectedTentor->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                    <!-- Integrity Rule Card -->
                    <div class="p-4 rounded-2xl bg-accent-subtle/50 border border-primary/20 flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary text-[20px] shrink-0 mt-0.5">policy</span>
                        <div class="text-xs text-ink-body leading-relaxed">
                            <span class="font-bold text-primary block mb-0.5">Aturan Integritas Data</span>
                            Tentor yang telah memiliki riwayat mengajar terlindungi dari penghapusan permanen. Nonaktifkan tentor untuk membekukan penugasan kelas baru.
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-canvas-pure border-t border-hairline py-4 px-6 sm:px-8 text-center text-xs text-ink-muted mt-auto">
            <p>&copy; {{ date('Y') }} Elips Academy. Sistem Manajemen Jadwal Kuliah & Kursus.</p>
        </footer>
    </div>
</div>

<!-- Modal: Tambah / Edit Tentor -->
<div class="fixed inset-0 bg-ink-body/50 backdrop-blur-xs z-50 hidden items-center justify-center p-4 animate-in fade-in" id="tentorModal">
    <div class="bg-canvas-pure rounded-2xl max-w-lg w-full p-6 shadow-xl border border-hairline flex flex-col gap-4">
        <div class="flex items-center justify-between border-b border-hairline pb-3">
            <div>
                <h3 class="font-bold text-base text-ink-body" id="modalTentorTitle">Tambah Tentor Baru</h3>
                <span class="text-xs text-ink-muted">Master data instruktur pengajar kursus</span>
            </div>
            <button onclick="closeTentorModal()" class="p-1 rounded-lg text-ink-muted hover:text-ink-body hover:bg-surface-container-low">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <form id="tentorForm" method="POST" action="{{ route('superadmin.tentor.store') }}" class="space-y-4">
            @csrf
            <div id="tentorMethodContainer"></div>

            <div>
                <label for="inputNamaTentor" class="block text-xs font-bold text-ink-body mb-1">Nama Lengkap Tentor <span class="text-rose-500">*</span></label>
                <input type="text" 
                       name="nama" 
                       id="inputNamaTentor" 
                       required 
                       placeholder="Contoh: Andi Pratama, S.Kom" 
                       class="w-full px-3.5 py-2 rounded-xl bg-canvas-parchment text-xs text-ink-body border border-hairline focus:bg-canvas-pure focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>

            <div>
                <label for="inputNoHpTentor" class="block text-xs font-bold text-ink-body mb-1">Nomor WhatsApp / HP</label>
                <input type="text" 
                       name="no_hp" 
                       id="inputNoHpTentor" 
                       placeholder="Contoh: 081234567890" 
                       class="w-full px-3.5 py-2 rounded-xl bg-canvas-parchment text-xs text-ink-body border border-hairline focus:bg-canvas-pure focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>

            <div>
                <label for="inputKeahlianTentor" class="block text-xs font-bold text-ink-body mb-1">Bidang Keahlian / Spesialisasi</label>
                <input type="text" 
                       name="keahlian" 
                       id="inputKeahlianTentor" 
                       placeholder="Contoh: Web Programming (HTML, CSS, PHP, Laravel)" 
                       class="w-full px-3.5 py-2 rounded-xl bg-canvas-parchment text-xs text-ink-body border border-hairline focus:bg-canvas-pure focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>

            <div>
                <label for="inputStatusTentor" class="block text-xs font-bold text-ink-body mb-1">Status Ketersediaan</label>
                <select name="status" 
                        id="inputStatusTentor" 
                        class="w-full px-3.5 py-2 rounded-xl bg-canvas-parchment text-xs font-medium text-ink-body border border-hairline focus:outline-none focus:ring-2 focus:ring-primary/20">
                    <option value="aktif">Aktif (Dapat Diberi Jadwal Mengajar)</option>
                    <option value="nonaktif">Nonaktif (Cuti / Tidak Mengajar)</option>
                </select>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-hairline">
                <button type="button" onclick="closeTentorModal()" class="px-4 py-2 rounded-xl bg-surface-container-low text-ink-body text-xs font-semibold hover:bg-surface-container-high transition-colors">
                    Batal
                </button>
                <button type="submit" id="btnSubmitTentorForm" class="px-5 py-2 rounded-xl bg-primary text-white text-xs font-semibold hover:bg-primary-container shadow-sm transition-all">
                    Simpan Tentor
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Konfirmasi Proteksi Hapus Tentor -->
<div class="fixed inset-0 bg-ink-body/50 backdrop-blur-xs z-50 hidden items-center justify-center p-4 animate-in fade-in" id="deleteTentorModal">
    <div class="bg-canvas-pure rounded-2xl max-w-md w-full p-6 shadow-xl border border-hairline flex flex-col gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[24px]" id="deleteTentorModalIcon">warning</span>
            </div>
            <div>
                <h3 class="font-bold text-base text-ink-body" id="deleteTentorModalTitle">Hapus Tentor</h3>
                <span class="text-xs text-ink-muted">Konfirmasi penghapusan tentor pengajar</span>
            </div>
        </div>

        <div class="text-xs text-ink-body leading-relaxed" id="deleteTentorModalBody">
            <!-- Dynamic warning -->
        </div>

        <div class="flex items-center justify-end gap-2 pt-3 border-t border-hairline" id="deleteTentorModalActions">
            <!-- Dynamic actions -->
        </div>
    </div>
</div>

<script>
    const tentorModal = document.getElementById('tentorModal');
    const deleteTentorModal = document.getElementById('deleteTentorModal');
    const tentorForm = document.getElementById('tentorForm');
    const tentorMethodContainer = document.getElementById('tentorMethodContainer');
    const modalTentorTitle = document.getElementById('modalTentorTitle');
    const inputNamaTentor = document.getElementById('inputNamaTentor');
    const inputNoHpTentor = document.getElementById('inputNoHpTentor');
    const inputKeahlianTentor = document.getElementById('inputKeahlianTentor');
    const inputStatusTentor = document.getElementById('inputStatusTentor');
    const btnSubmitTentorForm = document.getElementById('btnSubmitTentorForm');

    function openCreateTentorModal() {
        modalTentorTitle.innerText = 'Tambah Tentor Pengajar Baru';
        tentorForm.action = '{{ route("superadmin.tentor.store") }}';
        tentorMethodContainer.innerHTML = '';
        inputNamaTentor.value = '';
        inputNoHpTentor.value = '';
        inputKeahlianTentor.value = '';
        inputStatusTentor.value = 'aktif';
        btnSubmitTentorForm.innerText = 'Simpan Tentor';
        tentorModal.classList.remove('hidden');
        tentorModal.classList.add('flex');
        inputNamaTentor.focus();
    }

    function openEditTentorModal(tentor) {
        modalTentorTitle.innerText = 'Edit Tentor: ' + tentor.nama;
        tentorForm.action = '/superadmin/tentor/' + tentor.id;
        tentorMethodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';
        inputNamaTentor.value = tentor.nama;
        inputNoHpTentor.value = tentor.no_hp || '';
        inputKeahlianTentor.value = tentor.keahlian || '';
        inputStatusTentor.value = tentor.status;
        btnSubmitTentorForm.innerText = 'Perbarui Tentor';
        tentorModal.classList.remove('hidden');
        tentorModal.classList.add('flex');
        inputNamaTentor.focus();
    }

    function closeTentorModal() {
        tentorModal.classList.add('hidden');
        tentorModal.classList.remove('flex');
    }

    function closeDeleteTentorModal() {
        deleteTentorModal.classList.add('hidden');
        deleteTentorModal.classList.remove('flex');
    }

    function confirmDeleteTentor(tentor, totalJadwal) {
        const bodyEl = document.getElementById('deleteTentorModalBody');
        const actionsEl = document.getElementById('deleteTentorModalActions');
        const titleEl = document.getElementById('deleteTentorModalTitle');
        const iconEl = document.getElementById('deleteTentorModalIcon');

        if (totalJadwal > 0) {
            titleEl.innerText = 'Penghapusan Diblokir';
            iconEl.innerText = 'security';
            iconEl.parentElement.className = 'w-10 h-10 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center shrink-0';
            bodyEl.innerHTML = `
                <div class="p-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 mb-2">
                    <strong>Peringatan Proteksi Relasi:</strong> Tentor <strong>${tentor.nama}</strong> memiliki <strong>${totalJadwal}</strong> jadwal mengajar terkait.
                </div>
                <p>Database dilindungi dengan aturan <code>RESTRICT ON DELETE</code> untuk menjaga histori absensi, nilai, dan rekam jejak mengajar. Data tentor ini tidak boleh dihapus fisik.</p>
                <p class="mt-2 font-medium">Solusi yang direkomendasikan:</p>
                <p>Nonaktifkan status tentor agar tentor tidak lagi muncul di formulir pembuatan jadwal baru.</p>
            `;
            actionsEl.innerHTML = `
                <button type="button" onclick="closeDeleteTentorModal()" class="px-4 py-2 rounded-xl bg-surface-container-low text-ink-body text-xs font-semibold hover:bg-surface-container-high transition-colors">
                    Tutup
                </button>
                <form method="POST" action="/superadmin/tentor/${tentor.id}/toggle-status" class="inline">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <button type="submit" class="px-4 py-2 rounded-xl bg-amber-600 text-white text-xs font-semibold hover:bg-amber-700 transition-colors">
                        Nonaktifkan Tentor Saja
                    </button>
                </form>
            `;
        } else {
            titleEl.innerText = 'Hapus Tentor Permanen';
            iconEl.innerText = 'delete_forever';
            iconEl.parentElement.className = 'w-10 h-10 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center shrink-0';
            bodyEl.innerHTML = `
                <p>Apakah Anda yakin ingin menghapus tentor <strong>${tentor.nama}</strong> secara permanen?</p>
                <p class="text-ink-muted mt-1">Tentor ini belum memiliki riwayat jadwal mengajar sehingga aman untuk dihapus permanen.</p>
            `;
            actionsEl.innerHTML = `
                <button type="button" onclick="closeDeleteTentorModal()" class="px-4 py-2 rounded-xl bg-surface-container-low text-ink-body text-xs font-semibold hover:bg-surface-container-high transition-colors">
                    Batal
                </button>
                <form method="POST" action="/superadmin/tentor/${tentor.id}" class="inline">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" id="btnConfirmHardDeleteTentor" class="px-4 py-2 rounded-xl bg-rose-600 text-white text-xs font-semibold hover:bg-rose-700 transition-colors">
                        Hapus Permanen
                    </button>
                </form>
            `;
        }

        deleteTentorModal.classList.remove('hidden');
        deleteTentorModal.classList.add('flex');
    }

    function toggleSidebar() {
        const sb = document.getElementById('sidebarNav');
        const bd = document.getElementById('sidebarBackdrop');
        if (sb.classList.contains('-translate-x-full')) {
            sb.classList.remove('-translate-x-full');
            bd.classList.remove('hidden');
        } else {
            sb.classList.add('-translate-x-full');
            bd.classList.add('hidden');
        }
    }

    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeTentorModal();
            closeDeleteTentorModal();
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
