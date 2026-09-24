<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Jadwal Kelas - Elips Academy</title>
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
                        "canvas-parchment": "#F5F5F7",
                        "canvas-pure": "#FFFFFF",
                        "ink-body": "#1D1D1F",
                        "ink-muted": "#6E6E73",
                        "ink-subtle": "#86868B",
                        "primary": "#904d00",
                        "primary-container": "#f28e2b",
                        "on-primary-container": "#5e3000",
                        "surface-container-low": "#f6f3f5",
                        "surface-container": "#f0edef",
                        "surface-container-high": "#eae7ea",
                        "surface-pearl": "#FAFAFC",
                        "hairline": "#E0E0E0",
                        "accent-subtle": "#FEF3C7",
                        "accent-focus": "#F59E0B",
                        "schedule-verified": "#10B981",
                        "schedule-pending": "#6366F1",
                        "schedule-conflict": "#EF4444",
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
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
                <a href="{{ route('superadmin.dashboard') }}" class="flex items-center gap-3 group">
                    <img src="{{ asset('images/logo.png') }}" alt="Elips Academy" class="h-8 w-auto object-contain transition-transform duration-200 group-hover:scale-105">
                    <span class="sr-only">Elips Academy</span>
                </a>
                <button type="button" onclick="toggleSidebar()" class="lg:hidden p-1.5 rounded-lg text-ink-muted hover:bg-surface-container-low transition-colors" aria-label="Tutup Menu">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <!-- Cabang Overview Indicator -->
            <div class="px-4 py-3">
                <div class="px-3 py-2 rounded-xl bg-surface-container-low border border-hairline/60 flex items-center justify-between text-xs">
                    <span class="text-[11px] font-bold text-primary uppercase tracking-wider">Cabang</span>
                    <span class="font-semibold text-ink-body">Buduran & Candi</span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex flex-col gap-1 px-3 pt-1">
                <a href="{{ route('superadmin.dashboard') }}" 
                   id="nav-superadmin-dashboard"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-ink-muted hover:bg-surface-container-low hover:text-ink-body transition-all">
                    <span class="material-symbols-outlined text-[20px]">grid_view</span>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('superadmin.jadwal.index') }}" 
                   id="nav-superadmin-jadwal"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all bg-accent-subtle text-primary shadow-xs">
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

        <!-- User Profile & Logout -->
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

    <!-- Main Content Container -->
    <div class="lg:pl-64 flex flex-col flex-1 min-h-screen w-full">
        <!-- Topbar Header -->
        <header class="sticky top-0 h-16 bg-canvas-pure/90 backdrop-blur-md border-b border-hairline z-40 flex items-center justify-between px-4 sm:px-6 lg:px-8 gap-3">
            <div class="flex items-center gap-3">
                <button type="button" onclick="toggleSidebar()" class="lg:hidden p-2 -ml-2 rounded-xl text-ink-muted hover:bg-surface-container-low hover:text-ink-body transition-colors shrink-0" aria-label="Buka Menu">
                    <span class="material-symbols-outlined text-[24px]">menu</span>
                </button>
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-surface-container-low text-ink-muted border border-hairline text-xs">
                    <span class="material-symbols-outlined text-primary text-[18px]">store</span>
                    <span class="font-semibold text-ink-body">Cabang: Buduran & Candi</span>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-purple-100 text-purple-800 text-xs font-semibold uppercase tracking-wider">
                    Superadmin
                </span>

                <a href="{{ route('admin.dashboard') }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-surface-container-low hover:bg-surface-container text-xs font-semibold text-ink-body border border-hairline transition-all">
                    <span class="material-symbols-outlined text-[16px] text-primary">visibility</span>
                    <span>View Admin Mode</span>
                </a>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" 
                            id="topbarLogoutBtn"
                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-red-50 hover:bg-red-100 text-xs font-semibold text-red-600 border border-red-200 transition-all cursor-pointer">
                        <span class="material-symbols-outlined text-[16px]">logout</span>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </header>

        <!-- Main Body -->
        <main class="p-6 lg:p-8 space-y-6 max-w-7xl w-full mx-auto flex-1">

            <!-- Flash Alert Messages -->
            @if(session('success'))
                <div class="flex items-center justify-between p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm animate-fade-in shadow-xs">
                    <div class="flex items-center gap-2.5">
                        <span class="material-symbols-outlined text-emerald-600 text-[20px]">check_circle</span>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>
            @endif

            <!-- Sub-Header & Primary Actions Bar -->
            <section class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-2xl lg:text-3xl font-bold text-ink-body tracking-tight">Jadwal Kelas Elips Academy</h1>
                        <span class="px-2.5 py-0.5 rounded-full bg-accent-subtle text-primary text-xs font-semibold">Aktif</span>
                    </div>
                    <p class="text-sm text-ink-muted mt-1">Kelola dan pantau jadwal kelas aktif per cabang dan program kursus secara terstruktur.</p>
                </div>

                <div class="flex items-center gap-3 self-start md:self-auto">
                    <a href="{{ route('admin.jadwal.create') }}?redirect_to={{ urlencode(route('superadmin.jadwal.index')) }}" 
                       id="btnCreateSchedule"
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-primary-container text-white shadow-sm hover:opacity-95 text-xs font-semibold transition-all">
                        <span class="material-symbols-outlined text-[18px]">add</span>
                        <span>+ Buat Jadwal Baru</span>
                    </a>
                </div>
            </section>

            <!-- Metric Summary Bento Cards -->
            <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Metric 1: Jadwal Hari Ini -->
                <div class="p-5 rounded-2xl bg-canvas-pure border border-hairline shadow-xs flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold text-ink-muted">Jadwal Hari Ini</span>
                        <div class="w-8 h-8 rounded-full bg-accent-subtle flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined text-[18px]">calendar_today</span>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-bold text-ink-body" id="metricTableHariIni">{{ $totalJadwalHariIni }}</span>
                            <span class="text-xs text-schedule-verified font-medium">Selesai: {{ $selesaiHariIni }}</span>
                        </div>
                        <div class="w-full bg-surface-container h-1.5 rounded-full mt-2 overflow-hidden">
                            <div class="bg-primary-container h-full rounded-full" style="width: {{ $totalJadwalHariIni > 0 ? min(100, round(($selesaiHariIni / $totalJadwalHariIni) * 100)) : 0 }}%"></div>
                        </div>
                        <span class="text-[10px] text-ink-muted mt-1.5 block">{{ max(0, $totalJadwalHariIni - $selesaiHariIni) }} sesi terjadwal tersisa</span>
                    </div>
                </div>

                <!-- Metric 2: Cabang Aktif -->
                <div class="p-5 rounded-2xl bg-canvas-pure border border-hairline shadow-xs flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold text-ink-muted">Cabang Aktif</span>
                        <div class="w-8 h-8 rounded-full bg-surface-container-low flex items-center justify-center text-ink-body">
                            <span class="material-symbols-outlined text-[18px]">storefront</span>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-bold text-ink-body">{{ $cabangs->count() }}</span>
                            <span class="text-xs text-ink-muted">Lokasi Aktif</span>
                        </div>
                        <p class="text-[11px] text-ink-body font-medium mt-1">
                            {{ $cabangs->pluck('nama_cabang')->join(' • ') }}
                        </p>
                        <span class="text-[10px] text-emerald-600 flex items-center gap-1 mt-1 font-medium">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Semua cabang beroperasi normal
                        </span>
                    </div>
                </div>

                <!-- Metric 3: Program Kursus -->
                <div class="p-5 rounded-2xl bg-canvas-pure border border-hairline shadow-xs flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold text-ink-muted">Program Kursus</span>
                        <div class="w-8 h-8 rounded-full bg-surface-container-low flex items-center justify-center text-ink-body">
                            <span class="material-symbols-outlined text-[18px]">school</span>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-bold text-ink-body">{{ $programs->count() }}</span>
                            <span class="text-xs text-ink-muted">Kategori Pokok</span>
                        </div>
                        <p class="text-[11px] text-ink-body font-medium mt-1 truncate">
                            {{ $programs->pluck('nama_program')->join(' • ') }}
                        </p>
                        <span class="text-[10px] text-ink-subtle mt-1 block">Tersedia di semua cabang</span>
                    </div>
                </div>

                <!-- Metric 4: Tentor Mengajar -->
                <div class="p-5 rounded-2xl bg-canvas-pure border border-hairline shadow-xs flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold text-ink-muted">Tentor Mengajar</span>
                        <div class="w-8 h-8 rounded-full bg-surface-container-low flex items-center justify-center text-ink-body">
                            <span class="material-symbols-outlined text-[18px]">person_check</span>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-bold text-ink-body">{{ $tentorMengajarCount }}</span>
                            <span class="text-xs text-ink-muted">dari {{ $totalTentorsCount }} Terdaftar</span>
                        </div>
                        <div class="flex items-center -space-x-1.5 mt-2">
                            @foreach($tentors->take(3) as $t)
                                <div class="w-6 h-6 rounded-full bg-primary-container text-white flex items-center justify-center text-[10px] font-bold ring-2 ring-white">
                                    {{ strtoupper(substr($t->nama, 0, 1)) }}
                                </div>
                            @endforeach
                            @if($tentors->count() > 3)
                                <div class="w-6 h-6 rounded-full bg-surface-container-high text-ink-muted flex items-center justify-center text-[9px] font-medium ring-2 ring-white">
                                    +{{ $tentors->count() - 3 }}
                                </div>
                            @endif
                        </div>
                        <span class="text-[10px] text-ink-subtle mt-1.5 block truncate">
                            {{ $tentors->pluck('nama')->take(3)->join(', ') }}
                        </span>
                    </div>
                </div>
            </section>

            <!-- Real-time Verification & Conflict Check Strip -->
            <div class="bg-canvas-pure rounded-xl p-4 border border-hairline shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[20px]">check_circle</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-ink-body">Validasi Jadwal: Sinkron & Aman</span>
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        </div>
                        <span class="text-[11px] text-ink-muted">
                            Pemeriksaan relasi tentor, cabang & waktu (jam_selesai > jam_mulai) tidak menemukan bentrok ruang yang tidak tertangani.
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-1.5 text-ink-subtle text-[11px] self-end sm:self-center">
                    <span class="material-symbols-outlined text-[16px]">history</span>
                    <span>Otomatis dicek per perubahan</span>
                </div>
            </div>

            <!-- Filter & Search Ribbon -->
            <form method="GET" action="{{ route('superadmin.jadwal.index') }}" id="filterForm" class="bg-canvas-pure rounded-2xl p-5 border border-hairline shadow-xs space-y-3">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                    <!-- Search Input -->
                    <div class="lg:col-span-2 relative flex items-center">
                        <span class="material-symbols-outlined absolute left-3 text-ink-muted text-[18px]">search</span>
                        <input type="text" 
                               name="q" 
                               id="filter-search"
                               value="{{ $searchQuery }}" 
                               placeholder="Cari kode kelas, nama program, atau tentor..."
                               class="w-full h-10 pl-9 pr-4 rounded-full bg-canvas-parchment text-xs text-ink-body border border-hairline focus:bg-canvas-pure focus:outline-none focus:ring-2 focus:ring-primary-container transition-all">
                    </div>

                    <!-- Cabang Dropdown -->
                    <div class="relative flex items-center">
                        <select name="cabang_id" 
                                id="filter-cabang"
                                onchange="this.form.submit()"
                                class="w-full h-10 px-4 rounded-full bg-canvas-parchment text-xs font-medium text-ink-body border border-hairline focus:bg-canvas-pure focus:outline-none focus:ring-2 focus:ring-primary-container appearance-none cursor-pointer">
                            <option value="all" {{ $selectedCabangId === 'all' ? 'selected' : '' }}>Semua Cabang</option>
                            @foreach($cabangs as $c)
                                <option value="{{ $c->id }}" {{ $selectedCabangId == $c->id ? 'selected' : '' }}>
                                    Cabang {{ $c->nama_cabang }}
                                </option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined absolute right-3 text-ink-muted pointer-events-none text-[18px]">expand_more</span>
                    </div>

                    <!-- Jenis Kelas Dropdown -->
                    <div class="relative flex items-center">
                        <select name="jenis_kelas" 
                                id="filter-jenis"
                                onchange="this.form.submit()"
                                class="w-full h-10 px-4 rounded-full bg-canvas-parchment text-xs font-medium text-ink-body border border-hairline focus:bg-canvas-pure focus:outline-none focus:ring-2 focus:ring-primary-container appearance-none cursor-pointer">
                            <option value="all" {{ $selectedJenisKelas === 'all' ? 'selected' : '' }}>Semua Jenis Kelas</option>
                            <option value="private" {{ $selectedJenisKelas === 'private' ? 'selected' : '' }}>Private</option>
                            <option value="rombel" {{ $selectedJenisKelas === 'rombel' ? 'selected' : '' }}>Rombel</option>
                            <option value="business" {{ $selectedJenisKelas === 'business' ? 'selected' : '' }}>Business</option>
                        </select>
                        <span class="material-symbols-outlined absolute right-3 text-ink-muted pointer-events-none text-[18px]">expand_more</span>
                    </div>

                    <!-- Status Dropdown -->
                    <div class="relative flex items-center">
                        <select name="status" 
                                id="filter-status"
                                onchange="this.form.submit()"
                                class="w-full h-10 px-4 rounded-full bg-canvas-parchment text-xs font-medium text-ink-body border border-hairline focus:bg-canvas-pure focus:outline-none focus:ring-2 focus:ring-primary-container appearance-none cursor-pointer">
                            <option value="all" {{ $selectedStatus === 'all' ? 'selected' : '' }}>Semua Status</option>
                            <option value="terjadwal" {{ $selectedStatus === 'terjadwal' ? 'selected' : '' }}>Terjadwal</option>
                            <option value="selesai" {{ $selectedStatus === 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="dibatalkan" {{ $selectedStatus === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                        <span class="material-symbols-outlined absolute right-3 text-ink-muted pointer-events-none text-[18px]">expand_more</span>
                    </div>
                </div>

                <!-- Rentang Cepat Horizon & Reset -->
                <div class="flex flex-wrap items-center justify-between gap-3 pt-2 border-t border-hairline">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-[11px] font-bold text-ink-muted uppercase tracking-wider mr-1">Rentang Cepat:</span>
                        
                        <a href="{{ route('superadmin.jadwal.index', array_merge(request()->except(['range', 'tanggal', 'page']), ['range' => 'semua'])) }}"
                           class="px-3 py-1 rounded-full text-xs font-semibold transition-all {{ $selectedRange === 'semua' ? 'bg-ink-body text-white' : 'bg-surface-pearl text-ink-muted hover:text-ink-body border border-hairline' }}">
                            Semua
                        </a>
                        <a href="{{ route('superadmin.jadwal.index', array_merge(request()->except(['range', 'tanggal', 'page']), ['range' => 'hari_ini'])) }}"
                           class="px-3 py-1 rounded-full text-xs font-semibold transition-all {{ $selectedRange === 'hari_ini' ? 'bg-ink-body text-white' : 'bg-surface-pearl text-ink-muted hover:text-ink-body border border-hairline' }}">
                            Hari Ini
                        </a>
                        <a href="{{ route('superadmin.jadwal.index', array_merge(request()->except(['range', 'tanggal', 'page']), ['range' => 'besok'])) }}"
                           class="px-3 py-1 rounded-full text-xs font-semibold transition-all {{ $selectedRange === 'besok' ? 'bg-ink-body text-white' : 'bg-surface-pearl text-ink-muted hover:text-ink-body border border-hairline' }}">
                            Besok
                        </a>
                        <a href="{{ route('superadmin.jadwal.index', array_merge(request()->except(['range', 'tanggal', 'page']), ['range' => 'minggu_ini'])) }}"
                           class="px-3 py-1 rounded-full text-xs font-semibold transition-all {{ $selectedRange === 'minggu_ini' ? 'bg-ink-body text-white' : 'bg-surface-pearl text-ink-muted hover:text-ink-body border border-hairline' }}">
                            Minggu Ini
                        </a>
                    </div>

                    <div class="flex items-center gap-3">
                        @if($selectedCabangId !== 'all' || $selectedJenisKelas !== 'all' || $selectedStatus !== 'all' || $selectedRange !== 'semua' || !empty($searchQuery))
                            <a href="{{ route('superadmin.jadwal.index') }}" 
                               class="text-xs text-red-600 hover:text-red-800 font-semibold flex items-center gap-1">
                                <span class="material-symbols-outlined text-[16px]">restart_alt</span>
                                <span>Reset Filter</span>
                            </a>
                        @endif
                        <button type="submit" class="px-4 py-1.5 rounded-full bg-ink-body text-white text-xs font-semibold hover:bg-slate-800 transition-all">
                            Terapkan
                        </button>
                    </div>
                </div>
            </form>

            <!-- Schedules Table -->
            <div class="bg-canvas-pure rounded-2xl border border-hairline shadow-xs overflow-hidden flex flex-col">
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-left border-collapse" id="table-jadwal">
                        <thead>
                            <tr class="bg-surface-container-low text-ink-muted text-[11px] uppercase tracking-wider border-b border-hairline">
                                <th class="py-3 px-4 font-bold">Kode & Kelas</th>
                                <th class="py-3 px-4 font-bold">Program Kursus</th>
                                <th class="py-3 px-4 font-bold">Cabang</th>
                                <th class="py-3 px-4 font-bold">Tentor</th>
                                <th class="py-3 px-4 font-bold">Jenis</th>
                                <th class="py-3 px-4 font-bold">Waktu & Tanggal</th>
                                <th class="py-3 px-4 font-bold">Ruang / Pertemuan</th>
                                <th class="py-3 px-4 font-bold">Status</th>
                                <th class="py-3 px-4 text-right font-bold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-hairline/60 text-xs">
                            @forelse($jadwals as $item)
                                <tr class="hover:bg-surface-pearl/80 transition-colors group">
                                    <!-- Kode & Kelas -->
                                    <td class="py-3 px-4">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-ink-body text-sm">{{ $item->nama_kelas }}</span>
                                            <span class="text-[11px] text-ink-muted truncate max-w-[160px]">{{ $item->catatan ?? '-' }}</span>
                                        </div>
                                    </td>

                                    <!-- Program Kursus -->
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-primary-container shrink-0"></span>
                                            <span class="font-semibold text-ink-body">{{ $item->program->nama_program ?? '-' }}</span>
                                        </div>
                                    </td>

                                    <!-- Cabang -->
                                    <td class="py-3 px-4">
                                        <span class="px-2.5 py-0.5 rounded-full bg-surface-container-low border border-hairline text-ink-body font-semibold text-[11px]">
                                            {{ $item->cabang->nama_cabang ?? '-' }}
                                        </span>
                                    </td>

                                    <!-- Tentor -->
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-full bg-primary-container text-white flex items-center justify-center font-bold text-[10px] shrink-0">
                                                {{ strtoupper(substr($item->tentor->nama ?? 'T', 0, 1)) }}
                                            </div>
                                            <span class="font-medium text-ink-body">{{ $item->tentor->nama ?? '-' }}</span>
                                        </div>
                                    </td>

                                    <!-- Jenis -->
                                    <td class="py-3 px-4">
                                        <span class="px-2.5 py-0.5 rounded-full bg-accent-subtle text-primary text-[10px] font-bold capitalize">
                                            {{ $item->jenis_kelas }}
                                        </span>
                                    </td>

                                    <!-- Waktu & Tanggal -->
                                    <td class="py-3 px-4">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-ink-body">{{ substr($item->jam_mulai, 0, 5) }} - {{ substr($item->jam_selesai, 0, 5) }}</span>
                                            <span class="text-[11px] text-ink-muted">{{ $item->tanggal ? $item->tanggal->format('d M Y') : '-' }}</span>
                                        </div>
                                    </td>

                                    <!-- Ruang / Pertemuan -->
                                    <td class="py-3 px-4">
                                        <div class="flex flex-col">
                                            <span class="font-semibold text-ink-body">{{ $item->ruangan }}</span>
                                            <span class="text-[11px] text-ink-muted">Pertemuan ke-{{ $item->pertemuan ?? 1 }}</span>
                                        </div>
                                    </td>

                                    <!-- Status -->
                                    <td class="py-3 px-4">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold {{ $item->status === 'selesai' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($item->status === 'dibatalkan' ? 'bg-red-50 text-red-700 border border-red-200' : 'bg-blue-50 text-blue-700 border border-blue-200') }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $item->status === 'selesai' ? 'bg-emerald-500' : ($item->status === 'dibatalkan' ? 'bg-red-500' : 'bg-blue-500') }}"></span>
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>

                                    <!-- Aksi -->
                                    <td class="py-3 px-4 text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <!-- Detail -->
                                            <a href="{{ route('admin.jadwal.show', $item->id) }}" 
                                               title="Lihat Detail"
                                               class="p-1.5 rounded-lg bg-surface-container-low hover:bg-canvas-pure text-ink-muted hover:text-ink-body border border-hairline/60 transition-all">
                                                <span class="material-symbols-outlined text-[16px]">visibility</span>
                                            </a>

                                            <!-- Edit -->
                                            <a href="{{ route('admin.jadwal.edit', $item->id) }}?redirect_to={{ urlencode(route('superadmin.jadwal.index')) }}" 
                                               title="Edit Jadwal"
                                               class="p-1.5 rounded-lg bg-surface-container-low hover:bg-canvas-pure text-ink-muted hover:text-ink-body border border-hairline/60 transition-all">
                                                <span class="material-symbols-outlined text-[16px]">edit</span>
                                            </a>

                                            <!-- Cancel / Batalkan (if not already dibatalkan) -->
                                            @if($item->status !== 'dibatalkan')
                                                <button type="button" 
                                                        onclick="openCancelModal({{ $item->id }}, '{{ $item->nama_kelas }}')"
                                                        title="Batalkan Jadwal"
                                                        class="p-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 transition-all">
                                                    <span class="material-symbols-outlined text-[16px]">cancel</span>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="py-12 text-center text-ink-muted">
                                        <div class="flex flex-col items-center justify-center">
                                            <span class="material-symbols-outlined text-4xl text-ink-subtle mb-2">event_busy</span>
                                            <span class="text-sm font-semibold text-ink-body">Tidak ada data jadwal yang cocok dengan filter</span>
                                            <span class="text-xs text-ink-muted mt-0.5">Silakan sesuaikan kata kunci pencarian atau ganti filter di atas.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                @if($jadwals->hasPages())
                    <div class="p-4 border-t border-hairline bg-surface-pearl flex items-center justify-between">
                        {{ $jadwals->links() }}
                    </div>
                @endif
            </div>

        </main>
    </div>

    <!-- Modal Konfirmasi Pembatalan Jadwal -->
    <div id="cancelModal" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-slate-200">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-2xl">warning</span>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900">Batalkan Jadwal Kelas?</h3>
                    <p class="text-xs text-slate-500">Status jadwal akan diubah menjadi 'Dibatalkan'.</p>
                </div>
            </div>
            
            <p class="text-sm text-slate-600 mb-6">
                Apakah Anda yakin ingin membatalkan jadwal <strong id="cancelModalClassName" class="text-slate-900"></strong>? Data jadwal tetap tersimpan sebagai riwayat dan tidak bentrok dengan jadwal lain.
            </p>

            <form id="cancelForm" method="POST" action="">
                @csrf
                <input type="hidden" name="redirect_to" value="{{ route('superadmin.jadwal.index') }}">
                <div class="flex items-center justify-end gap-3">
                    <button type="button" 
                            onclick="closeCancelModal()"
                            class="px-4 py-2 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors cursor-pointer">
                        Tutup
                    </button>
                    <button type="submit" 
                            id="confirmCancelBtn"
                            class="px-4 py-2 text-xs font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl transition-colors cursor-pointer shadow-sm">
                        Ya, Batalkan Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal & Drawer Logic -->
    <script>
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

        function openCancelModal(jadwalId, className) {
            document.getElementById('cancelModalClassName').textContent = className;
            document.getElementById('cancelForm').action = '/admin/jadwal/' + jadwalId + '/batal';
            document.getElementById('cancelModal').classList.remove('hidden');
        }

        function closeCancelModal() {
            document.getElementById('cancelModal').classList.add('hidden');
        }

        // Close modal or drawer on Escape key
        window.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeCancelModal();
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
