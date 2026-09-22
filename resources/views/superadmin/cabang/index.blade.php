<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manajemen Cabang — Superadmin Elips Academy</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
    
    <!-- Material Symbols Outlined -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#0284c7',
                        'primary-container': '#0369a1',
                        'on-primary': '#ffffff',
                        'canvas-pure': '#ffffff',
                        'canvas-parchment': '#f8fafc',
                        'surface-pearl': '#f1f5f9',
                        'surface-container-low': '#e2e8f0',
                        'surface-container-high': '#cbd5e1',
                        'hairline': '#e2e8f0',
                        'ink-body': '#0f172a',
                        'ink-muted': '#64748b',
                        'ink-subtle': '#94a3b8',
                        'accent-subtle': '#e0f2fe',
                        'schedule-verified': '#059669',
                        'schedule-pending': '#d97706',
                        'error': '#dc2626',
                        'error-container': '#fee2e2',
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        serif: ['"Playfair Display"', 'serif'],
                    }
                }
            }
        }
    </script>
    <style>
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
    <aside class="w-64 bg-canvas-pure border-r border-hairline flex flex-col justify-between fixed inset-y-0 z-50">
        <div>
            <!-- Brand Logo -->
            <div class="h-16 flex items-center px-6 border-b border-hairline gap-3">
                <div class="w-9 h-9 rounded-xl bg-primary text-white flex items-center justify-center font-bold text-lg shadow-sm">
                    E
                </div>
                <div>
                    <span class="font-bold text-base tracking-tight text-ink-body block leading-tight">Elips Academy</span>
                    <span class="text-[10px] tracking-wider uppercase text-primary font-bold">Portal Superadmin</span>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="p-4 space-y-1.5">
                <a href="{{ route('superadmin.dashboard') }}" 
                   id="nav-superadmin-overview"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-ink-muted hover:bg-surface-container-low hover:text-ink-body transition-all">
                    <span class="material-symbols-outlined text-[20px]">dashboard</span>
                    <span>Overview</span>
                </a>

                <a href="{{ route('superadmin.jadwal.index') }}" 
                   id="nav-superadmin-jadwal"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-ink-muted hover:bg-surface-container-low hover:text-ink-body transition-all">
                    <span class="material-symbols-outlined text-[20px]">calendar_month</span>
                    <span>Jadwal Kelas</span>
                </a>

                <a href="{{ route('superadmin.cabang.index') }}" 
                   id="nav-superadmin-cabang"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold bg-accent-subtle text-primary shadow-xs transition-all">
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

    <!-- Main Content Wrapper -->
    <div class="pl-64 flex flex-col flex-1 min-h-screen">
        <!-- Topbar Header -->
        <header class="sticky top-0 h-16 bg-canvas-pure/90 backdrop-blur-md border-b border-hairline z-40 flex items-center justify-between px-8">
            <div class="flex items-center gap-2 text-xs text-ink-muted">
                <a href="{{ route('superadmin.dashboard') }}" class="hover:text-primary transition-colors">Portal Superadmin</a>
                <span>/</span>
                <span class="text-ink-body font-semibold">Master Data Cabang</span>
            </div>

            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-accent-subtle text-primary border border-primary/20">
                    <span class="w-2 h-2 rounded-full bg-primary"></span>
                    Superadmin Hak Penuh
                </span>
                <button onclick="openCreateModal()" 
                        id="btnOpenCreateModal"
                        class="flex items-center gap-2 px-4 py-2 rounded-full bg-primary text-white text-xs font-semibold hover:bg-primary-container shadow-sm transition-all">
                    <span class="material-symbols-outlined text-[16px]">add</span>
                    <span>Tambah Cabang</span>
                </button>
            </div>
        </header>

        <!-- Main Workspace -->
        <main class="p-8 flex-1 flex flex-col gap-6">
            <!-- Flash Message Alerts -->
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
                        <span>Total Cabang</span>
                        <span class="material-symbols-outlined text-primary text-[18px]">apartment</span>
                    </div>
                    <div class="text-2xl font-bold text-ink-body mt-2">{{ $totalCabang }}</div>
                    <span class="text-[11px] text-ink-muted mt-0.5 block">Lokasi operasional lembaga</span>
                </div>

                <div class="p-4 rounded-2xl bg-canvas-pure border border-hairline shadow-xs">
                    <div class="flex items-center justify-between text-ink-muted text-xs">
                        <span>Cabang Aktif</span>
                        <span class="material-symbols-outlined text-schedule-verified text-[18px]">verified</span>
                    </div>
                    <div class="text-2xl font-bold text-schedule-verified mt-2">{{ $cabangAktif }}</div>
                    <span class="text-[11px] text-ink-muted mt-0.5 block">Dapat dipilih pada form jadwal</span>
                </div>

                <div class="p-4 rounded-2xl bg-canvas-pure border border-hairline shadow-xs">
                    <div class="flex items-center justify-between text-ink-muted text-xs">
                        <span>Cabang Nonaktif</span>
                        <span class="material-symbols-outlined text-ink-subtle text-[18px]">pause_circle</span>
                    </div>
                    <div class="text-2xl font-bold text-ink-muted mt-2">{{ $cabangNonaktif }}</div>
                    <span class="text-[11px] text-ink-muted mt-0.5 block">Status freeze / tidak operasional</span>
                </div>

                <div class="p-4 rounded-2xl bg-canvas-pure border border-hairline shadow-xs">
                    <div class="flex items-center justify-between text-ink-muted text-xs">
                        <span>Proteksi Relasi PRD</span>
                        <span class="material-symbols-outlined text-primary text-[18px]">security</span>
                    </div>
                    <div class="text-2xl font-bold text-primary mt-2">100% Aman</div>
                    <span class="text-[11px] text-ink-muted mt-0.5 block">Soft-delete lock aktif</span>
                </div>
            </div>

            <!-- Toolbar & Filter -->
            <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3 bg-canvas-pure p-3.5 rounded-2xl border border-hairline shadow-xs">
                <!-- Search -->
                <form method="GET" action="{{ route('superadmin.cabang.index') }}" class="flex items-center gap-2 flex-1 max-w-md">
                    @if($statusFilter !== 'all')
                        <input type="hidden" name="status" value="{{ $statusFilter }}">
                    @endif
                    <div class="relative w-full">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-ink-subtle text-[18px]">search</span>
                        <input type="text" 
                               name="q" 
                               value="{{ $search }}" 
                               id="cabangSearchInput"
                               placeholder="Cari nama cabang atau alamat..."
                               class="w-full pl-9 pr-8 py-2 rounded-xl bg-canvas-parchment text-xs text-ink-body placeholder:text-ink-subtle border border-hairline focus:bg-canvas-pure focus:outline-none focus:ring-2 focus:ring-primary/20">
                        @if($search !== '')
                            <a href="{{ route('superadmin.cabang.index', ['status' => $statusFilter]) }}" 
                               class="absolute right-2.5 top-1/2 -translate-y-1/2 text-ink-subtle hover:text-ink-body">
                                <span class="material-symbols-outlined text-[16px]">close</span>
                            </a>
                        @endif
                    </div>
                </form>

                <!-- Filters -->
                <div class="flex items-center gap-2 flex-wrap">
                    <form method="GET" action="{{ route('superadmin.cabang.index') }}" id="statusFilterForm">
                        @if($search !== '')
                            <input type="hidden" name="q" value="{{ $search }}">
                        @endif
                        <select name="status" 
                                onchange="document.getElementById('statusFilterForm').submit()"
                                id="cabangStatusFilter"
                                class="px-3 py-2 rounded-xl bg-canvas-parchment text-xs font-medium text-ink-body border border-hairline focus:outline-none focus:ring-2 focus:ring-primary/20 cursor-pointer">
                            <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>Semua Status Cabang</option>
                            <option value="aktif" {{ $statusFilter === 'aktif' ? 'selected' : '' }}>Hanya Aktif</option>
                            <option value="nonaktif" {{ $statusFilter === 'nonaktif' ? 'selected' : '' }}>Hanya Nonaktif</option>
                        </select>
                    </form>
                </div>
            </div>

            <!-- Primary Split View: List Table + Quick Inspector -->
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">
                <!-- Branch List Table (8 of 12 cols) -->
                <div class="xl:col-span-8 bg-canvas-pure rounded-2xl border border-hairline shadow-xs overflow-hidden flex flex-col">
                    <div class="px-6 py-4 border-b border-hairline flex items-center justify-between bg-surface-pearl/50">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-[20px]">domain</span>
                            <h2 class="text-sm font-bold text-ink-body">Daftar Cabang Lembaga</h2>
                        </div>
                        <span class="text-xs text-ink-muted">Tabel: <code class="text-primary font-mono font-medium">cabang</code></span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse" id="cabangTable">
                            <thead>
                                <tr class="text-ink-muted text-[11px] uppercase tracking-wider bg-canvas-parchment/60 border-b border-hairline font-semibold">
                                    <th class="py-3 px-5">Kode & Cabang</th>
                                    <th class="py-3 px-5">Alamat Operasional</th>
                                    <th class="py-3 px-5 text-center">Jadwal Terkait</th>
                                    <th class="py-3 px-5 text-center">Status</th>
                                    <th class="py-3 px-5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-hairline text-xs" id="cabangTableBody">
                                @forelse($cabangs as $index => $cabang)
                                    @php
                                        $isSelected = $selectedCabang && $selectedCabang->id === $cabang->id;
                                    @endphp
                                    <tr class="cabang-row transition-colors cursor-pointer {{ $isSelected ? 'bg-accent-subtle/40' : 'hover:bg-surface-pearl/50' }}"
                                        onclick="window.location='{{ route('superadmin.cabang.index', ['selected' => $cabang->id, 'q' => $search, 'status' => $statusFilter]) }}'"
                                        id="cabang-row-{{ $cabang->id }}">
                                        <td class="py-3.5 px-5 align-top">
                                            <div class="flex items-start gap-3">
                                                <div class="w-8 h-8 rounded-xl {{ $cabang->status === 'aktif' ? 'bg-accent-subtle text-primary' : 'bg-surface-container-low text-ink-muted' }} flex items-center justify-center font-bold text-xs shrink-0">
                                                    {{ sprintf('%02d', $cabang->id) }}
                                                </div>
                                                <div class="flex flex-col">
                                                    <span class="font-bold text-ink-body flex items-center gap-1.5 text-sm">
                                                        {{ $cabang->nama_cabang }}
                                                        @if($cabang->status === 'aktif')
                                                            <span class="material-symbols-outlined text-schedule-verified text-[14px]">verified</span>
                                                        @endif
                                                    </span>
                                                    <span class="text-[11px] text-ink-muted font-mono">CBG-{{ sprintf('%02d', $cabang->id) }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3.5 px-5 align-top max-w-xs">
                                            <span class="text-ink-body line-clamp-2">{{ $cabang->alamat ?: 'Belum ada alamat terdaftar.' }}</span>
                                        </td>
                                        <td class="py-3.5 px-5 align-top text-center">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold {{ $cabang->jadwal_aktif_count > 0 ? 'bg-accent-subtle text-primary' : 'bg-surface-container-low text-ink-muted' }}">
                                                {{ $cabang->jadwal_aktif_count }} Aktif
                                            </span>
                                            <span class="block text-[10px] text-ink-muted mt-1">Total: {{ $cabang->total_jadwal_count }} sesi</span>
                                        </td>
                                        <td class="py-3.5 px-5 align-top text-center">
                                            @if($cabang->status === 'aktif')
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
                                                <button onclick="openEditModal({{ json_encode($cabang) }})" 
                                                        title="Edit Data Cabang"
                                                        id="btn-edit-cabang-{{ $cabang->id }}"
                                                        class="p-1.5 rounded-lg text-ink-muted hover:text-ink-body hover:bg-surface-container-low transition-colors">
                                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                                </button>

                                                <!-- Toggle Status Form -->
                                                <form method="POST" action="{{ route('superadmin.cabang.toggle-status', $cabang) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            title="{{ $cabang->status === 'aktif' ? 'Nonaktifkan Cabang' : 'Aktifkan Cabang' }}"
                                                            id="btn-toggle-cabang-{{ $cabang->id }}"
                                                            class="p-1.5 rounded-lg {{ $cabang->status === 'aktif' ? 'text-amber-600 hover:bg-amber-50' : 'text-emerald-600 hover:bg-emerald-50' }} transition-colors">
                                                        <span class="material-symbols-outlined text-[18px]">
                                                            {{ $cabang->status === 'aktif' ? 'toggle_on' : 'toggle_off' }}
                                                        </span>
                                                    </button>
                                                </form>

                                                <!-- Delete Button -->
                                                <button onclick="confirmDeleteCabang({{ json_encode($cabang) }}, {{ $cabang->total_jadwal_count }})" 
                                                        title="Hapus Cabang"
                                                        id="btn-delete-cabang-{{ $cabang->id }}"
                                                        class="p-1.5 rounded-lg text-rose-500 hover:bg-rose-50 transition-colors">
                                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-12 px-6 text-center text-ink-muted">
                                            <div class="flex flex-col items-center justify-center gap-2">
                                                <span class="material-symbols-outlined text-4xl text-ink-subtle">apartment</span>
                                                <span class="font-semibold text-sm">Tidak ada data cabang yang ditemukan</span>
                                                <span class="text-xs text-ink-subtle">Coba ubah kata kunci pencarian atau filter status.</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Table Footer info -->
                    <div class="p-4 border-t border-hairline flex items-center justify-between text-xs text-ink-muted bg-canvas-parchment/30">
                        <span>Menampilkan {{ $cabangs->count() }} cabang</span>
                        <span>PRD 14.2: Foreign Key Restrict Berlaku</span>
                    </div>
                </div>

                <!-- Right Column: Inspector Panel (4 of 12 cols) -->
                <div class="xl:col-span-4 sticky top-24 flex flex-col gap-4">
                    @if($selectedCabang)
                        <div class="bg-canvas-pure rounded-2xl border border-hairline p-6 shadow-xs flex flex-col gap-4" id="cabangInspector">
                            <div class="flex items-start justify-between">
                                <div>
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-primary font-mono">
                                        CBG-{{ sprintf('%02d', $selectedCabang->id) }}
                                    </span>
                                    <h3 class="text-lg font-bold text-ink-body mt-0.5" id="inspNamaCabang">{{ $selectedCabang->nama_cabang }}</h3>
                                    <p class="text-xs text-ink-muted mt-1 leading-relaxed" id="inspAlamat">{{ $selectedCabang->alamat ?: 'Alamat belum diatur.' }}</p>
                                </div>
                                <div>
                                    @if($selectedCabang->status === 'aktif')
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

                            <!-- Stat Grid for Selected Branch -->
                            <div class="grid grid-cols-2 gap-3 p-3 rounded-xl bg-canvas-parchment border border-hairline">
                                <div>
                                    <span class="text-[11px] text-ink-muted">Jadwal Aktif</span>
                                    <div class="text-base font-bold text-primary mt-0.5">{{ $selectedCabang->jadwal_aktif_count }} Sesi</div>
                                </div>
                                <div>
                                    <span class="text-[11px] text-ink-muted">Total Riwayat</span>
                                    <div class="text-base font-bold text-ink-body mt-0.5">{{ $selectedCabang->total_jadwal_count }} Kelas</div>
                                </div>
                            </div>

                            <!-- Upcoming / Active Classes -->
                            <div class="flex flex-col gap-2 pt-2 border-t border-hairline">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-bold text-ink-body">Jadwal Mendatang di Cabang Ini</span>
                                    <span class="text-ink-muted">{{ $selectedCabang->jadwals->count() }} Terdaftar</span>
                                </div>

                                @if($selectedCabang->jadwals->isNotEmpty())
                                    <div class="space-y-2 max-h-56 overflow-y-auto pr-1">
                                        @foreach($selectedCabang->jadwals as $j)
                                            <div class="p-2.5 rounded-xl bg-surface-pearl/60 border border-hairline text-xs flex flex-col gap-1">
                                                <div class="flex items-center justify-between">
                                                    <span class="font-bold text-ink-body truncate">{{ $j->nama_kelas }}</span>
                                                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-accent-subtle text-primary font-medium">
                                                        {{ $j->ruangan ?: 'Ruang -' }}
                                                    </span>
                                                </div>
                                                <div class="text-[11px] text-ink-muted flex items-center justify-between">
                                                    <span>{{ $j->program?->nama_program ?? 'Program' }} • {{ $j->tentor?->nama ?? 'Tentor' }}</span>
                                                    <span>{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} WIB</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="p-4 rounded-xl bg-canvas-parchment text-center text-xs text-ink-muted">
                                        Tidak ada jadwal mendatang di cabang ini.
                                    </div>
                                @endif
                            </div>

                            <!-- Inspector Action Buttons -->
                            <div class="grid grid-cols-2 gap-2 pt-2 border-t border-hairline">
                                <button onclick="openEditModal({{ json_encode($selectedCabang) }})"
                                        id="btnInspEdit"
                                        class="px-4 py-2 rounded-xl bg-surface-container-low text-ink-body text-xs font-semibold hover:bg-surface-container-high transition-colors flex items-center justify-center gap-1.5">
                                    <span class="material-symbols-outlined text-[16px]">edit</span>
                                    <span>Edit Cabang</span>
                                </button>
                                <form method="POST" action="{{ route('superadmin.cabang.toggle-status', $selectedCabang) }}">
                                    @csrf
                                    <button type="submit" 
                                            id="btnInspToggle"
                                            class="w-full px-4 py-2 rounded-xl text-xs font-semibold {{ $selectedCabang->status === 'aktif' ? 'bg-amber-50 text-amber-700 hover:bg-amber-100 border border-amber-200' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200' }} transition-colors flex items-center justify-center gap-1.5">
                                        <span class="material-symbols-outlined text-[16px]">
                                            {{ $selectedCabang->status === 'aktif' ? 'pause_circle' : 'play_circle' }}
                                        </span>
                                        <span>{{ $selectedCabang->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                    <!-- Integrity Rule Card -->
                    <div class="p-4 rounded-2xl bg-accent-subtle/50 border border-primary/20 flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary text-[20px] shrink-0 mt-0.5">policy</span>
                        <div class="text-xs text-ink-body leading-relaxed">
                            <span class="font-bold text-primary block mb-0.5">Aturan Integritas PRD 14.2</span>
                            Cabang yang telah memiliki riwayat jadwal terkunci dari penghapusan fisik. Gunakan status nonaktif untuk membekukan pembukaan kelas baru.
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Modal: Tambah / Edit Cabang -->
<div class="fixed inset-0 bg-ink-body/50 backdrop-blur-xs z-50 hidden items-center justify-center p-4 animate-in fade-in" id="cabangModal">
    <div class="bg-canvas-pure rounded-2xl max-w-lg w-full p-6 shadow-xl border border-hairline flex flex-col gap-4">
        <div class="flex items-center justify-between border-b border-hairline pb-3">
            <div>
                <h3 class="font-bold text-base text-ink-body" id="modalCabangTitle">Tambah Cabang Baru</h3>
                <span class="text-xs text-ink-muted">Master data referensi cabang pelatihan</span>
            </div>
            <button onclick="closeCabangModal()" class="p-1 rounded-lg text-ink-muted hover:text-ink-body hover:bg-surface-container-low">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <form id="cabangForm" method="POST" action="{{ route('superadmin.cabang.store') }}" class="space-y-4">
            @csrf
            <div id="methodContainer"></div>

            <div>
                <label for="inputNamaCabang" class="block text-xs font-bold text-ink-body mb-1">Nama Cabang <span class="text-rose-500">*</span></label>
                <input type="text" 
                       name="nama_cabang" 
                       id="inputNamaCabang" 
                       required 
                       placeholder="Contoh: Buduran, Candi, Porong" 
                       class="w-full px-3.5 py-2 rounded-xl bg-canvas-parchment text-xs text-ink-body border border-hairline focus:bg-canvas-pure focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>

            <div>
                <label for="inputAlamatCabang" class="block text-xs font-bold text-ink-body mb-1">Alamat Lengkap</label>
                <textarea name="alamat" 
                          id="inputAlamatCabang" 
                          rows="3" 
                          placeholder="Masukkan nama jalan, nomor, dan kecamatan..."
                          class="w-full px-3.5 py-2 rounded-xl bg-canvas-parchment text-xs text-ink-body border border-hairline focus:bg-canvas-pure focus:outline-none focus:ring-2 focus:ring-primary/20"></textarea>
            </div>

            <div>
                <label for="inputStatusCabang" class="block text-xs font-bold text-ink-body mb-1">Status Operasional</label>
                <select name="status" 
                        id="inputStatusCabang" 
                        class="w-full px-3.5 py-2 rounded-xl bg-canvas-parchment text-xs font-medium text-ink-body border border-hairline focus:outline-none focus:ring-2 focus:ring-primary/20">
                    <option value="aktif">Aktif (Dapat Dijadwalkan)</option>
                    <option value="nonaktif">Nonaktif (Freeze Operasional)</option>
                </select>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-hairline">
                <button type="button" onclick="closeCabangModal()" class="px-4 py-2 rounded-xl bg-surface-container-low text-ink-body text-xs font-semibold hover:bg-surface-container-high transition-colors">
                    Batal
                </button>
                <button type="submit" id="btnSubmitCabangForm" class="px-5 py-2 rounded-xl bg-primary text-white text-xs font-semibold hover:bg-primary-container shadow-sm transition-all">
                    Simpan Cabang
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Konfirmasi Proteksi Hapus Cabang -->
<div class="fixed inset-0 bg-ink-body/50 backdrop-blur-xs z-50 hidden items-center justify-center p-4 animate-in fade-in" id="deleteModal">
    <div class="bg-canvas-pure rounded-2xl max-w-md w-full p-6 shadow-xl border border-hairline flex flex-col gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[24px]" id="deleteModalIcon">warning</span>
            </div>
            <div>
                <h3 class="font-bold text-base text-ink-body" id="deleteModalTitle">Hapus Cabang</h3>
                <span class="text-xs text-ink-muted" id="deleteModalSubtitle">Konfirmasi penghapusan master data</span>
            </div>
        </div>

        <div class="text-xs text-ink-body leading-relaxed" id="deleteModalBody">
            <!-- Dynamic warning message -->
        </div>

        <div class="flex items-center justify-end gap-2 pt-3 border-t border-hairline" id="deleteModalActions">
            <!-- Dynamic actions -->
        </div>
    </div>
</div>

<script>
    const cabangModal = document.getElementById('cabangModal');
    const deleteModal = document.getElementById('deleteModal');
    const cabangForm = document.getElementById('cabangForm');
    const methodContainer = document.getElementById('methodContainer');
    const modalCabangTitle = document.getElementById('modalCabangTitle');
    const inputNamaCabang = document.getElementById('inputNamaCabang');
    const inputAlamatCabang = document.getElementById('inputAlamatCabang');
    const inputStatusCabang = document.getElementById('inputStatusCabang');
    const btnSubmitCabangForm = document.getElementById('btnSubmitCabangForm');

    function openCreateModal() {
        modalCabangTitle.innerText = 'Tambah Cabang Baru';
        cabangForm.action = '{{ route("superadmin.cabang.store") }}';
        methodContainer.innerHTML = '';
        inputNamaCabang.value = '';
        inputAlamatCabang.value = '';
        inputStatusCabang.value = 'aktif';
        btnSubmitCabangForm.innerText = 'Simpan Cabang';
        cabangModal.classList.remove('hidden');
        cabangModal.classList.add('flex');
        inputNamaCabang.focus();
    }

    function openEditModal(cabang) {
        modalCabangTitle.innerText = 'Edit Cabang: ' + cabang.nama_cabang;
        cabangForm.action = '/superadmin/cabang/' + cabang.id;
        methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';
        inputNamaCabang.value = cabang.nama_cabang;
        inputAlamatCabang.value = cabang.alamat || '';
        inputStatusCabang.value = cabang.status;
        btnSubmitCabangForm.innerText = 'Perbarui Cabang';
        cabangModal.classList.remove('hidden');
        cabangModal.classList.add('flex');
        inputNamaCabang.focus();
    }

    function closeCabangModal() {
        cabangModal.classList.add('hidden');
        cabangModal.classList.remove('flex');
    }

    function closeDeleteModal() {
        deleteModal.classList.add('hidden');
        deleteModal.classList.remove('flex');
    }

    function confirmDeleteCabang(cabang, totalJadwal) {
        const bodyEl = document.getElementById('deleteModalBody');
        const actionsEl = document.getElementById('deleteModalActions');
        const titleEl = document.getElementById('deleteModalTitle');
        const iconEl = document.getElementById('deleteModalIcon');

        if (totalJadwal > 0) {
            titleEl.innerText = 'Penghapusan Diblokir (PRD 14.2)';
            iconEl.innerText = 'security';
            iconEl.parentElement.className = 'w-10 h-10 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center shrink-0';
            bodyEl.innerHTML = `
                <div class="p-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 mb-2">
                    <strong>Peringatan Proteksi Relasi:</strong> Cabang <strong>${cabang.nama_cabang}</strong> memiliki <strong>${totalJadwal}</strong> jadwal kelas terkait.
                </div>
                <p>Database dilindungi dengan aturan <code>RESTRICT ON DELETE</code> demi menjaga integritas sertifikat dan data presensi siswa. Cabang ini tidak boleh dihapus fisik.</p>
                <p class="mt-2 font-medium">Solusi yang direkomendasikan:</p>
                <p>Nonaktifkan status cabang agar cabang tidak lagi muncul di pilihan form pembuatan jadwal baru.</p>
            `;
            actionsEl.innerHTML = `
                <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 rounded-xl bg-surface-container-low text-ink-body text-xs font-semibold hover:bg-surface-container-high transition-colors">
                    Tutup
                </button>
                <form method="POST" action="/superadmin/cabang/${cabang.id}/toggle-status" class="inline">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <button type="submit" class="px-4 py-2 rounded-xl bg-amber-600 text-white text-xs font-semibold hover:bg-amber-700 transition-colors">
                        Nonaktifkan Cabang Saja
                    </button>
                </form>
            `;
        } else {
            titleEl.innerText = 'Hapus Cabang Permanen';
            iconEl.innerText = 'delete_forever';
            iconEl.parentElement.className = 'w-10 h-10 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center shrink-0';
            bodyEl.innerHTML = `
                <p>Apakah Anda yakin ingin menghapus cabang <strong>${cabang.nama_cabang}</strong> secara permanen?</p>
                <p class="text-ink-muted mt-1">Cabang ini belum memiliki riwayat jadwal sehingga aman untuk dihapus fisik dari basis data.</p>
            `;
            actionsEl.innerHTML = `
                <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 rounded-xl bg-surface-container-low text-ink-body text-xs font-semibold hover:bg-surface-container-high transition-colors">
                    Batal
                </button>
                <form method="POST" action="/superadmin/cabang/${cabang.id}" class="inline">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" id="btnConfirmHardDelete" class="px-4 py-2 rounded-xl bg-rose-600 text-white text-xs font-semibold hover:bg-rose-700 transition-colors">
                        Hapus Permanen
                    </button>
                </form>
            `;
        }

        deleteModal.classList.remove('hidden');
        deleteModal.classList.add('flex');
    }

    // Close modals on Escape key
    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeCabangModal();
            closeDeleteModal();
        }
    });
</script>

</body>
</html>
