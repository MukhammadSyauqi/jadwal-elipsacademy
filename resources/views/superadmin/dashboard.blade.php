<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Superadmin - Elips Academy</title>
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
    <aside class="fixed left-0 top-0 h-full w-64 bg-canvas-pure border-r border-hairline shadow-[0_1px_8px_rgba(0,0,0,0.04)] z-50 flex flex-col justify-between">
        <div class="flex flex-col">
            <!-- Brand -->
            <div class="h-16 px-6 flex items-center gap-3 border-b border-hairline">
                <div class="w-9 h-9 rounded-xl bg-primary-container text-white flex items-center justify-center font-bold text-lg shadow-sm">
                    E
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="font-bold text-ink-body text-base tracking-tight truncate leading-tight">Elips Academy</span>
                    <span class="text-[11px] text-ink-muted uppercase tracking-wider font-semibold">Superadmin Panel</span>
                </div>
            </div>

            <!-- Active Semester Pill -->
            <div class="px-4 py-3">
                <div class="flex items-center gap-2 px-3 py-2 rounded-xl bg-surface-container-low border border-hairline/60">
                    <span class="material-symbols-outlined text-primary-container text-[18px]">verified_user</span>
                    <div class="flex flex-col min-w-0 flex-1">
                        <span class="text-[10px] text-ink-muted uppercase tracking-wider font-semibold">Hak Akses</span>
                        <span class="text-xs text-ink-body font-semibold truncate">Lintas Seluruh Cabang</span>
                    </div>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex flex-col gap-1 px-3 pt-1">
                <a href="{{ route('superadmin.dashboard') }}" 
                   id="nav-superadmin-dashboard"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all bg-primary-container text-white shadow-[0_1px_4px_rgba(242,142,43,0.25)]">
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
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-ink-muted hover:bg-surface-container-low hover:text-ink-body transition-all">
                    <span class="material-symbols-outlined text-[20px]">badge</span>
                    <span>Tentor</span>
                </a>

                <a href="#" 
                   onclick="alert('Modul Manajemen Akun Pengguna akan aktif pada Issue #7'); return false;"
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

    <!-- Main Wrapper -->
    <div class="pl-64 flex flex-col flex-1 min-h-screen">
        <!-- Topbar Header -->
        <header class="sticky top-0 h-16 bg-canvas-pure/90 backdrop-blur-md border-b border-hairline z-40 flex items-center justify-between px-8">
            <!-- Search Bar in Header -->
            <form method="GET" action="{{ route('superadmin.dashboard') }}" class="flex items-center gap-2 flex-1 max-w-md">
                @if($filterSesi && $filterSesi !== 'all')
                    <input type="hidden" name="sesi" value="{{ $filterSesi }}">
                @endif
                <div class="relative w-full">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-ink-subtle text-[18px]">search</span>
                    <input type="text" 
                           name="q" 
                           value="{{ $searchQuery }}" 
                           placeholder="Cari jadwal, tentor, program, cabang..."
                           class="w-full pl-9 pr-4 py-1.5 rounded-full bg-canvas-parchment text-xs text-ink-body placeholder:text-ink-subtle border border-hairline focus:bg-canvas-pure focus:outline-none focus:ring-2 focus:ring-primary-container transition-all">
                </div>
            </form>

            <!-- Actions & Status -->
            <div class="flex items-center gap-4">
                <div class="hidden sm:flex items-center gap-2 px-3 py-1 rounded-full bg-accent-subtle text-primary text-xs font-semibold">
                    <span class="w-2 h-2 rounded-full bg-schedule-verified animate-pulse"></span>
                    <span>Sistem Sinkron Seluruh Cabang</span>
                </div>

                <a href="{{ route('admin.dashboard') }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-surface-container-low hover:bg-surface-container text-xs font-semibold text-ink-body border border-hairline transition-all">
                    <span class="material-symbols-outlined text-[16px] text-primary">storefront</span>
                    <span>Mode Cabang Admin</span>
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

        <!-- Main Dashboard Content -->
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

            <!-- Welcome & Operational Header -->
            <section class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-purple-100 text-purple-800 text-[11px] font-semibold uppercase tracking-wider">
                            Role: Superadmin
                        </span>
                        <span class="text-xs text-ink-subtle">• {{ \Carbon\Carbon::parse($today)->isoFormat('dddd, D MMMM Y') }}</span>
                    </div>
                    <h1 class="text-2xl lg:text-3xl font-bold text-ink-body tracking-tight">
                        Selamat Datang, {{ $user->nama }}!
                    </h1>
                    <p class="text-sm text-ink-muted mt-1">
                        Pusat kendali jadwal operasional seluruh cabang Elips Academy.
                    </p>
                </div>

                <!-- Quick Action Buttons -->
                <div class="flex items-center gap-3 flex-wrap">
                    <a href="{{ route('superadmin.jadwal.index') }}" 
                       id="btnSemuaJadwal"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-full bg-canvas-pure border border-hairline shadow-xs hover:bg-surface-container text-xs font-semibold text-ink-body transition-all">
                        <span class="material-symbols-outlined text-[18px] text-ink-muted">calendar_today</span>
                        <span>Lihat Semua Jadwal</span>
                    </a>
                    <a href="{{ route('admin.jadwal.create') }}?redirect_to={{ urlencode(route('superadmin.dashboard')) }}" 
                       id="btnTambahJadwalSuperadmin"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-full bg-primary-container text-white shadow-sm hover:opacity-95 text-xs font-semibold transition-all">
                        <span class="material-symbols-outlined text-[18px]">add</span>
                        <span>+ Tambah Jadwal Baru</span>
                    </a>
                </div>
            </section>

            <!-- Bento Grid 4 Key Metrics -->
            <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Metric 1: Total Sesi Minggu Ini -->
                <div class="p-5 rounded-2xl bg-canvas-pure border border-hairline shadow-xs flex flex-col justify-between hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between">
                        <span class="text-xs font-semibold text-ink-muted uppercase tracking-wider">Total Sesi Minggu Ini</span>
                        <div class="p-2 rounded-xl bg-surface-container-low text-primary-container">
                            <span class="material-symbols-outlined text-[20px]">view_timeline</span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-bold text-ink-body" id="metricTotalSesi">{{ $totalSesiMingguIni }}</span>
                            <span class="text-xs text-ink-muted">Sesi Terjadwal</span>
                        </div>
                        <div class="flex items-center gap-1.5 mt-2 text-xs text-schedule-verified font-medium">
                            <span class="material-symbols-outlined text-[14px]">check</span>
                            <span>Lintas seluruh cabang aktif</span>
                        </div>
                    </div>
                </div>

                <!-- Metric 2: Okupansi Ruang Hari Ini -->
                <div class="p-5 rounded-2xl bg-canvas-pure border border-hairline shadow-xs flex flex-col justify-between hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between">
                        <span class="text-xs font-semibold text-ink-muted uppercase tracking-wider">Okupansi Ruang Hari Ini</span>
                        <div class="p-2 rounded-xl bg-accent-subtle text-primary">
                            <span class="material-symbols-outlined text-[20px]">meeting_room</span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-bold text-ink-body" id="metricOkupansiRuang">{{ $okupansiPersen }}%</span>
                            <span class="text-xs text-ink-muted">Terpakai</span>
                        </div>
                        <div class="w-full bg-surface-container rounded-full h-1.5 mt-2.5 overflow-hidden">
                            <div class="bg-primary-container h-full rounded-full" style="width: {{ min(100, $okupansiPersen) }}%"></div>
                        </div>
                        <span class="text-[11px] text-ink-muted mt-2 block">{{ $usedRoomsCount }} dari {{ $totalRoomsCount }} Ruang Beroperasi</span>
                    </div>
                </div>

                <!-- Metric 3: Tentor Aktif Hari Ini -->
                <div class="p-5 rounded-2xl bg-canvas-pure border border-hairline shadow-xs flex flex-col justify-between hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between">
                        <span class="text-xs font-semibold text-ink-muted uppercase tracking-wider">Tentor Aktif Hari Ini</span>
                        <div class="p-2 rounded-xl bg-purple-50 text-purple-700">
                            <span class="material-symbols-outlined text-[20px]">school</span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-bold text-ink-body" id="metricTentorAktif">{{ $tentorAktifHariIni }}</span>
                            <span class="text-xs text-ink-muted">dari {{ $totalTentor }} Terdaftar</span>
                        </div>
                        <div class="flex items-center gap-1.5 mt-2 text-xs text-emerald-600 font-medium">
                            <span class="material-symbols-outlined text-[14px]">verified</span>
                            <span>Jadwal mengajar terkonfirmasi</span>
                        </div>
                    </div>
                </div>

                <!-- Metric 4: Integritas Jadwal -->
                <div class="p-5 rounded-2xl bg-canvas-pure border border-hairline shadow-xs flex flex-col justify-between hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between">
                        <span class="text-xs font-semibold text-ink-muted uppercase tracking-wider">Integritas Jadwal</span>
                        <div class="p-2 rounded-xl bg-emerald-50 text-emerald-600">
                            <span class="material-symbols-outlined text-[20px]">verified</span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-bold {{ $conflictsCount === 0 ? 'text-emerald-600' : 'text-red-600' }}" id="metricKonflik">
                                {{ $conflictsCount }}
                            </span>
                            <span class="text-xs text-ink-muted">Konflik Waktu</span>
                        </div>
                        <div class="flex items-center gap-1.5 mt-2 text-xs {{ $conflictsCount === 0 ? 'text-emerald-600' : 'text-red-600' }} font-medium">
                            <span class="w-1.5 h-1.5 rounded-full {{ $conflictsCount === 0 ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                            <span>{{ $conflictsCount === 0 ? 'Bebas Tumpang Tindih' : 'Bentrok terdeteksi' }}</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Dual Column Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                <!-- Left / Primary Column: Today's Timetable Feed (8 Cols) -->
                <section class="lg:col-span-8 space-y-4">
                    <!-- Schedule Card Header & Filter Ribbon -->
                    <div class="p-5 rounded-2xl bg-canvas-pure border border-hairline shadow-xs space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <h2 class="text-lg font-bold text-ink-body">Jadwal Kelas Hari Ini</h2>
                                <p class="text-xs text-ink-muted mt-0.5">Pantauan perpindahan jam, keterisian kelas, dan tentor bertugas.</p>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-surface-container-low text-xs font-semibold text-ink-muted self-start sm:self-auto border border-hairline">
                                {{ \Carbon\Carbon::parse($today)->isoFormat('D MMMM Y') }}
                            </span>
                        </div>

                        <!-- Filter Ribbon & Sesi Switcher -->
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 pt-3 border-t border-hairline">
                            <!-- Period Chips -->
                            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 sm:pb-0">
                                <a href="{{ route('superadmin.dashboard', array_merge(request()->query(), ['sesi' => 'all'])) }}"
                                   class="px-3 py-1.5 rounded-full text-xs font-semibold transition-all {{ $filterSesi === 'all' ? 'bg-ink-body text-white' : 'bg-surface-pearl text-ink-muted hover:text-ink-body border border-hairline' }}">
                                    Semua Sesi ({{ $todaySchedules->count() }})
                                </a>
                                <a href="{{ route('superadmin.dashboard', array_merge(request()->query(), ['sesi' => 'pagi'])) }}"
                                   class="px-3 py-1.5 rounded-full text-xs font-semibold transition-all {{ $filterSesi === 'pagi' ? 'bg-ink-body text-white' : 'bg-surface-pearl text-ink-muted hover:text-ink-body border border-hairline' }}">
                                    Pagi (07:00-11:30)
                                </a>
                                <a href="{{ route('superadmin.dashboard', array_merge(request()->query(), ['sesi' => 'siang'])) }}"
                                   class="px-3 py-1.5 rounded-full text-xs font-semibold transition-all {{ $filterSesi === 'siang' ? 'bg-ink-body text-white' : 'bg-surface-pearl text-ink-muted hover:text-ink-body border border-hairline' }}">
                                    Siang (11:30-15:30)
                                </a>
                                <a href="{{ route('superadmin.dashboard', array_merge(request()->query(), ['sesi' => 'sore'])) }}"
                                   class="px-3 py-1.5 rounded-full text-xs font-semibold transition-all {{ $filterSesi === 'sore' ? 'bg-ink-body text-white' : 'bg-surface-pearl text-ink-muted hover:text-ink-body border border-hairline' }}">
                                    Sore/Malam (15:30+)
                                </a>
                            </div>

                            <!-- Clear Filter if search or session active -->
                            @if($filterSesi !== 'all' || !empty($searchQuery))
                                <a href="{{ route('superadmin.dashboard') }}" class="text-xs text-primary font-semibold hover:underline self-end sm:self-auto">
                                    Reset Filter
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Highlighted Active / Next Session Anchor -->
                    @if($activeLiveSchedule)
                        <div class="p-5 rounded-2xl bg-canvas-pure border-l-4 border-l-primary-container border-y border-r border-hairline shadow-xs">
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-2">
                                    <span class="flex h-2.5 w-2.5 relative">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-container opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-primary-container"></span>
                                    </span>
                                    <span class="text-xs font-bold text-primary">Sesi Fokus / Sedang Berlangsung</span>
                                    <span class="text-xs text-ink-subtle">• {{ substr($activeLiveSchedule->jam_mulai, 0, 5) }} - {{ substr($activeLiveSchedule->jam_selesai, 0, 5) }} WIB</span>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full bg-accent-subtle text-primary font-semibold text-[11px]">
                                    Cabang {{ $activeLiveSchedule->cabang->nama_cabang ?? '-' }}
                                </span>
                            </div>
                            <div class="mt-3 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div>
                                    <h3 class="text-base font-bold text-ink-body">{{ $activeLiveSchedule->nama_kelas }} ({{ $activeLiveSchedule->program->nama_program ?? '-' }})</h3>
                                    <div class="flex items-center gap-2 mt-1 text-xs text-ink-muted flex-wrap">
                                        <span class="font-semibold text-ink-body">{{ $activeLiveSchedule->tentor->nama ?? '-' }}</span>
                                        <span>•</span>
                                        <span>Ruangan: <strong>{{ $activeLiveSchedule->ruangan }}</strong></span>
                                        <span>•</span>
                                        <span>Pertemuan ke-{{ $activeLiveSchedule->pertemuan ?? 1 }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.jadwal.show', $activeLiveSchedule->id) }}" 
                                       class="px-3 py-1.5 rounded-full bg-surface-container hover:bg-surface-container-high text-xs font-semibold text-ink-body transition-colors">
                                        Detail Jadwal
                                    </a>
                                    <a href="{{ route('admin.jadwal.edit', $activeLiveSchedule->id) }}?redirect_to={{ urlencode(route('superadmin.dashboard')) }}" 
                                       class="px-3 py-1.5 rounded-full bg-primary-container text-white text-xs font-semibold hover:opacity-90 transition-all">
                                        Ubah
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Today's Schedules List -->
                    <div class="space-y-2.5" id="todayScheduleContainer">
                        @forelse($todaySchedules as $jadwal)
                            <article class="p-4 rounded-2xl bg-canvas-pure border border-hairline shadow-xs hover:shadow-md transition-all flex flex-col sm:flex-row sm:items-center justify-between gap-4 group">
                                <div class="flex items-start gap-4">
                                    <!-- Time Block -->
                                    <div class="flex flex-col items-center justify-center w-16 py-2 rounded-xl bg-surface-container-low text-ink-body border border-hairline/50 shrink-0">
                                        <span class="text-xs font-bold">{{ substr($jadwal->jam_mulai, 0, 5) }}</span>
                                        <span class="text-[10px] text-ink-subtle">{{ substr($jadwal->jam_selesai, 0, 5) }}</span>
                                        <span class="mt-1 w-1.5 h-1.5 rounded-full {{ $jadwal->status === 'selesai' ? 'bg-emerald-500' : ($jadwal->status === 'dibatalkan' ? 'bg-red-500' : 'bg-primary-container') }}"></span>
                                    </div>

                                    <!-- Content -->
                                    <div class="space-y-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="px-2 py-0.5 rounded-full bg-accent-subtle text-primary text-[10px] font-semibold uppercase">
                                                Cabang {{ $jadwal->cabang->nama_cabang ?? '-' }}
                                            </span>
                                            <span class="text-[11px] text-ink-subtle">
                                                {{ $jadwal->ruangan }} • Pertemuan ke-{{ $jadwal->pertemuan ?? 1 }}
                                            </span>
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $jadwal->status === 'selesai' ? 'bg-emerald-50 text-emerald-700' : ($jadwal->status === 'dibatalkan' ? 'bg-red-50 text-red-700' : 'bg-blue-50 text-blue-700') }}">
                                                {{ ucfirst($jadwal->status) }}
                                            </span>
                                        </div>

                                        <h4 class="text-sm font-bold text-ink-body group-hover:text-primary transition-colors truncate">
                                            {{ $jadwal->nama_kelas }} — {{ $jadwal->program->nama_program ?? '-' }}
                                        </h4>

                                        <div class="flex items-center gap-2 text-xs text-ink-muted">
                                            <span class="font-medium text-ink-body">{{ $jadwal->tentor->nama ?? '-' }}</span>
                                            <span>•</span>
                                            <span class="capitalize">{{ $jadwal->jenis_kelas }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex items-center gap-1.5 self-end sm:self-center shrink-0">
                                    <a href="{{ route('admin.jadwal.show', $jadwal->id) }}" 
                                       title="Lihat Detail"
                                       class="p-2 rounded-full hover:bg-surface-container text-ink-muted hover:text-ink-body transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">visibility</span>
                                    </a>
                                    <a href="{{ route('admin.jadwal.edit', $jadwal->id) }}?redirect_to={{ urlencode(route('superadmin.dashboard')) }}" 
                                       title="Ubah Jadwal"
                                       class="p-2 rounded-full hover:bg-surface-container text-ink-muted hover:text-ink-body transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </a>
                                </div>
                            </article>
                        @empty
                            <div class="p-8 rounded-2xl bg-canvas-pure border border-hairline text-center">
                                <span class="material-symbols-outlined text-4xl text-ink-subtle mb-2">event_busy</span>
                                <h3 class="text-sm font-bold text-ink-body">Tidak ada jadwal ditemukan</h3>
                                <p class="text-xs text-ink-muted mt-1">Tidak ada jadwal pada sesi atau kata kunci pencarian yang dipilih.</p>
                            </div>
                        @endforelse
                    </div>
                </section>

                <!-- Right Column: Room Occupancy, Activity Feed, Academic Shortcuts (4 Cols) -->
                <aside class="lg:col-span-4 space-y-5">
                    <!-- Room Availability Card -->
                    <div class="p-5 rounded-2xl bg-canvas-pure border border-hairline shadow-xs space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-bold text-ink-body">Ketersediaan Ruang</h3>
                                <p class="text-[11px] text-ink-muted">Status okupansi per cabang</p>
                            </div>
                            <!-- Cabang Tab Switches -->
                            <div class="flex items-center bg-surface-container-low p-0.5 rounded-lg text-xs" id="branchRoomTabs">
                                @foreach($roomAvailabilityByCabang as $branchName => $rooms)
                                    <button type="button" 
                                            onclick="switchBranchRooms('{{ Str::slug($branchName) }}')"
                                            id="btnTab-{{ Str::slug($branchName) }}"
                                            class="branch-tab-btn px-2.5 py-1 rounded-md text-[11px] font-semibold transition-all {{ $loop->first ? 'bg-canvas-pure text-ink-body shadow-xs' : 'text-ink-muted hover:text-ink-body' }}">
                                        {{ $branchName }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Room Lists by Branch -->
                        @foreach($roomAvailabilityByCabang as $branchName => $rooms)
                            <div id="branchRooms-{{ Str::slug($branchName) }}" class="branch-rooms-group space-y-2 {{ $loop->first ? '' : 'hidden' }}">
                                @foreach($rooms as $room)
                                    <div class="p-3 rounded-xl bg-surface-container-low border border-hairline/40 flex items-center justify-between">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <span class="w-2.5 h-2.5 rounded-full shrink-0 {{ $room['status'] === 'terpakai' ? 'bg-schedule-conflict' : ($room['status'] === 'segera' ? 'bg-primary-container' : 'bg-schedule-verified') }}"></span>
                                            <div class="min-w-0">
                                                <div class="text-xs font-bold text-ink-body truncate">{{ $room['nama_ruang'] }}</div>
                                                <div class="text-[10px] text-ink-muted truncate">{{ $room['detail'] }}</div>
                                            </div>
                                        </div>
                                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded {{ $room['status'] === 'terpakai' ? 'bg-red-100 text-red-800' : ($room['status'] === 'segera' ? 'bg-orange-100 text-orange-800' : 'bg-emerald-100 text-emerald-800') }}">
                                            {{ $room['status_text'] }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach

                        <!-- Room Legend -->
                        <div class="flex items-center justify-between pt-2 border-t border-hairline text-[10px] text-ink-muted">
                            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-schedule-verified"></span> Kosong</span>
                            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-primary-container"></span> Segera</span>
                            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-schedule-conflict"></span> Terpakai</span>
                        </div>
                    </div>

                    <!-- Recent Changes (Log Perubahan Berdasarkan updated_at) -->
                    <div class="p-5 rounded-2xl bg-canvas-pure border border-hairline shadow-xs space-y-3">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-bold text-ink-body">Perubahan Jadwal Terakhir</h3>
                            <span class="material-symbols-outlined text-ink-subtle text-[18px]">history</span>
                        </div>

                        <div class="space-y-2.5">
                            @forelse($perubahanTerakhir as $log)
                                <div class="p-2.5 rounded-xl bg-surface-container-low border border-hairline/40 text-xs">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="font-bold text-ink-body truncate">{{ $log->nama_kelas }}</span>
                                        <span class="text-[10px] text-ink-subtle shrink-0">{{ $log->updated_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-[11px] text-ink-muted">
                                        Cabang {{ $log->cabang->nama_cabang ?? '-' }} • {{ $log->ruangan }} • Status: <span class="font-semibold text-ink-body">{{ ucfirst($log->status) }}</span>
                                    </p>
                                </div>
                            @empty
                                <p class="text-xs text-ink-muted">Belum ada riwayat aktivitas terbaru.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Academic Shortcuts (Pintasan Cepat Akademik) -->
                    <div class="p-5 rounded-2xl bg-canvas-pure border border-hairline shadow-xs space-y-3">
                        <h3 class="text-sm font-bold text-ink-body">Pintasan Cepat Akademik</h3>
                        <div class="grid grid-cols-1 gap-1.5">
                            <a href="{{ route('admin.jadwal.create') }}?redirect_to={{ urlencode(route('superadmin.dashboard')) }}" 
                               class="flex items-center justify-between p-2.5 rounded-xl bg-surface-container-low hover:bg-surface-container text-xs font-semibold text-ink-body transition-colors">
                                <span class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-primary-container text-[18px]">add_circle</span>
                                    <span>Tambah Jadwal Baru</span>
                                </span>
                                <span class="material-symbols-outlined text-ink-subtle text-[16px]">chevron_right</span>
                            </a>

                            <a href="{{ route('superadmin.jadwal.index') }}" 
                               class="flex items-center justify-between p-2.5 rounded-xl bg-surface-container-low hover:bg-surface-container text-xs font-semibold text-ink-body transition-colors">
                                <span class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-primary text-[18px]">calendar_month</span>
                                    <span>Tabel Semua Jadwal</span>
                                </span>
                                <span class="material-symbols-outlined text-ink-subtle text-[16px]">chevron_right</span>
                            </a>

                            <a href="{{ route('superadmin.cabang.index') }}" 
                               class="flex items-center justify-between p-2.5 rounded-xl bg-surface-container-low hover:bg-surface-container text-xs font-semibold text-ink-body transition-colors">
                                <span class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-indigo-600 text-[18px]">apartment</span>
                                    <span>Master Cabang</span>
                                </span>
                                <span class="material-symbols-outlined text-ink-subtle text-[16px]">chevron_right</span>
                            </a>

                            <a href="{{ route('superadmin.program.index') }}" 
                               class="flex items-center justify-between p-2.5 rounded-xl bg-surface-container-low hover:bg-surface-container text-xs font-semibold text-ink-body transition-colors">
                                <span class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-emerald-600 text-[18px]">menu_book</span>
                                    <span>Master Program Kursus</span>
                                </span>
                                <span class="material-symbols-outlined text-ink-subtle text-[16px]">chevron_right</span>
                            </a>

                            <a href="{{ route('superadmin.tentor.index') }}" 
                               class="flex items-center justify-between p-2.5 rounded-xl bg-surface-container-low hover:bg-surface-container text-xs font-semibold text-ink-body transition-colors">
                                <span class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-amber-600 text-[18px]">badge</span>
                                    <span>Master Tentor</span>
                                </span>
                                <span class="material-symbols-outlined text-ink-subtle text-[16px]">chevron_right</span>
                            </a>
                        </div>
                    </div>

                </aside>

            </div>

        </main>
    </div>

    <!-- Branch Room Switcher Script -->
    <script>
        function switchBranchRooms(slug) {
            document.querySelectorAll('.branch-rooms-group').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.branch-tab-btn').forEach(btn => {
                btn.classList.remove('bg-canvas-pure', 'text-ink-body', 'shadow-xs');
                btn.classList.add('text-ink-muted');
            });

            const activeGroup = document.getElementById('branchRooms-' + slug);
            if (activeGroup) activeGroup.classList.remove('hidden');

            const activeBtn = document.getElementById('btnTab-' + slug);
            if (activeBtn) {
                activeBtn.classList.remove('text-ink-muted');
                activeBtn.classList.add('bg-canvas-pure', 'text-ink-body', 'shadow-xs');
            }
        }
    </script>
</body>
</html>
