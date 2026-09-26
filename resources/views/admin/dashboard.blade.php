<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Elips Academy</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
        /* Custom date picker icon styling */
        input[type="date"]::-webkit-calendar-picker-indicator {
            cursor: pointer;
            opacity: 0.6;
        }
        input[type="date"]::-webkit-calendar-picker-indicator:hover {
            opacity: 1;
        }
    </style>
</head>
<body class="bg-canvas-parchment font-sans text-on-surface antialiased min-h-screen flex flex-col">

    <!-- 1. Header Aplikasi -->
    <header class="sticky top-0 bg-canvas-pure/95 backdrop-blur-xl border-b border-hairline shadow-[0_1px_8px_rgba(0,0,0,0.04)] z-40 px-4 sm:px-8 py-3">
        <div class="max-w-6xl mx-auto flex items-center justify-between gap-4">
            <!-- Brand -->
            <div class="flex items-center gap-4">
                <a href="{{ auth()->user()?->role === 'superadmin' ? route('superadmin.dashboard') : route('admin.dashboard') }}" class="flex items-center gap-2.5 group">
                    <img src="{{ asset('images/logo.png') }}" alt="Elips Academy" class="h-8 w-auto object-contain transition-transform duration-200 group-hover:scale-105">
                    <span class="sr-only">Elips Academy</span>
                </a>

                <div class="h-5 w-px bg-hairline hidden sm:block"></div>

                <!-- Today Date in Header -->
                <div class="hidden sm:flex items-center gap-1.5 text-ink-muted text-xs">
                    <span class="material-symbols-outlined text-sm text-primary">calendar_today</span>
                    <span class="font-medium text-ink-body">{{ $formattedDate }}</span>
                </div>
            </div>

            <!-- User Profile & Logout -->
            <div class="flex items-center gap-3 sm:gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-brand-orange text-white flex items-center justify-center font-bold text-xs shadow-sm">
                        {{ strtoupper(substr($user->nama, 0, 1)) }}
                    </div>
                    <div class="hidden md:flex flex-col text-left leading-tight">
                        <span class="font-semibold text-xs text-ink-body">Selamat Datang, {{ $user->nama }}</span>
                        <span class="text-[10px] text-ink-muted uppercase tracking-wider font-semibold">{{ $user->role }}</span>
                    </div>
                </div>

                <!-- Logout Form -->
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" 
                            id="logoutBtn"
                            class="flex items-center gap-1 px-3 py-1.5 rounded-full bg-surface-pearl text-ink-muted hover:text-red-600 hover:bg-red-50 transition-all text-xs font-medium border border-hairline"
                            title="Keluar dari akun">
                        <span class="material-symbols-outlined text-sm">logout</span>
                        <span class="hidden sm:inline">Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="w-full flex-1 py-6 sm:py-8 px-4 sm:px-8">
        <div class="max-w-6xl mx-auto flex flex-col gap-6">

            <!-- Flash Message Sukses -->
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4 flex items-center justify-between gap-3 shadow-xs">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-emerald-600 text-2xl">check_circle</span>
                        <div class="text-sm font-semibold">{{ session('success') }}</div>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 p-1 rounded-lg hover:bg-emerald-100 transition-colors">
                        <span class="material-symbols-outlined text-lg block">close</span>
                    </button>
                </div>
            @endif

            <!-- 2. Sapaan & Navigasi Waktu -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex flex-col gap-1">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-full bg-accent-subtle text-secondary text-[11px] font-bold uppercase tracking-wider border border-amber-200">
                            Operasional Harian
                        </span>
                        <span class="text-ink-subtle text-xs">•</span>
                        <span class="text-ink-muted text-xs font-medium">{{ $formattedDate }}</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-ink-body tracking-tight">
                        Jadwal Kelas {{ $isToday ? 'Hari Ini' : ($isTomorrow ? 'Besok' : '') }}
                    </h1>
                    <p class="text-sm text-ink-muted">
                        Semua sesi kelas yang berlangsung {{ $isToday ? 'hari ini' : ($isTomorrow ? 'besok' : 'pada tanggal ini') }} @if($selectedCabang) di Cabang <strong>{{ $selectedCabang->nama_cabang }}</strong> @else di <strong>Semua Cabang</strong> @endif.
                    </p>
                </div>

                <!-- Date Navigation & Tambah Jadwal Quick Action -->
                <div class="flex items-center gap-2 flex-wrap">
                    <div class="flex items-center bg-surface-pearl p-1 rounded-full border border-hairline shadow-sm">
                        <!-- Hari Ini Button -->
                        <a href="{{ route('admin.dashboard', array_merge(request()->query(), ['tanggal' => $todayDate])) }}" 
                           class="px-3 py-1 rounded-full text-xs font-semibold transition-all {{ $isToday ? 'bg-ink-body text-white shadow-xs' : 'text-ink-muted hover:text-ink-body' }}">
                            Hari Ini
                        </a>
                        
                        <!-- Besok Button -->
                        <a href="{{ route('admin.dashboard', array_merge(request()->query(), ['tanggal' => $tomorrowDate])) }}" 
                           class="px-3 py-1 rounded-full text-xs font-semibold transition-all {{ $isTomorrow ? 'bg-ink-body text-white shadow-xs' : 'text-ink-muted hover:text-ink-body' }}">
                            Besok
                        </a>
                        
                        <!-- Kalender Date Picker Form -->
                        <form method="GET" action="{{ route('admin.dashboard') }}" class="inline-flex items-center">
                            @if($selectedCabang)
                                <input type="hidden" name="cabang_id" value="{{ $selectedCabang->id }}">
                            @endif
                            @if($sesi && $sesi !== 'semua')
                                <input type="hidden" name="sesi" value="{{ $sesi }}">
                            @endif
                            @if(!empty($q))
                                <input type="hidden" name="q" value="{{ $q }}">
                            @endif
                            <label class="px-2.5 py-1 rounded-full text-xs font-semibold transition-all flex items-center gap-1 cursor-pointer {{ (!$isToday && !$isTomorrow) ? 'bg-ink-body text-white' : 'text-ink-muted hover:text-ink-body' }}">
                                <span class="material-symbols-outlined text-sm">calendar_month</span>
                                <input type="date" 
                                       name="tanggal" 
                                       value="{{ $selectedDate }}" 
                                       onchange="this.form.submit()" 
                                       class="bg-transparent border-none text-xs focus:outline-none cursor-pointer w-24 sm:w-28 {{ (!$isToday && !$isTomorrow) ? 'text-white' : 'text-ink-body' }}"
                                       title="Pilih tanggal tertentu">
                            </label>
                        </form>
                    </div>

                    <!-- Tambah Jadwal Button -->
                    <a href="{{ route('admin.jadwal.create', array_filter(['cabang_id' => $selectedCabang?->id, 'tanggal' => $selectedDate])) }}" 
                       id="btnTambahJadwal"
                       class="px-4 py-2 rounded-full bg-primary-container text-white font-semibold text-xs sm:text-sm flex items-center gap-1.5 hover:bg-brand-hover shadow-sm active:scale-95 transition-all">
                        <span class="material-symbols-outlined text-base">add</span>
                        <span>Tambah Jadwal</span>
                    </a>
                </div>
            </div>

            <!-- 3. Card Ringkasan Jadwal Hari Ini (Summary Cards) -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
                <!-- Total Jadwal Card -->
                <div class="bg-canvas-pure rounded-xl p-4 border border-hairline shadow-xs flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-surface-container-low text-ink-body flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-xl">event_note</span>
                    </div>
                    <div class="flex flex-col min-w-0">
                        <span class="text-[11px] font-medium text-ink-muted uppercase tracking-wider truncate">Total Jadwal</span>
                        <span class="text-xl font-bold text-ink-body leading-tight">{{ $summary['total'] }}</span>
                    </div>
                </div>

                <!-- Sedang Berlangsung Card -->
                <div class="bg-canvas-pure rounded-xl p-4 border border-amber-200 bg-amber-50/30 shadow-xs flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 relative">
                        <span class="material-symbols-outlined text-xl">play_circle</span>
                        @if($summary['ongoing'] > 0)
                            <span class="absolute top-1 right-1 w-2 h-2 rounded-full bg-brand-orange animate-ping"></span>
                        @endif
                    </div>
                    <div class="flex flex-col min-w-0">
                        <span class="text-[11px] font-medium text-amber-900 uppercase tracking-wider truncate">Sedang Berlangsung</span>
                        <span class="text-xl font-bold text-amber-800 leading-tight">{{ $summary['ongoing'] }}</span>
                    </div>
                </div>

                <!-- Selesai Card -->
                <div class="bg-canvas-pure rounded-xl p-4 border border-emerald-200 bg-emerald-50/30 shadow-xs flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-xl">task_alt</span>
                    </div>
                    <div class="flex flex-col min-w-0">
                        <span class="text-[11px] font-medium text-emerald-900 uppercase tracking-wider truncate">Selesai</span>
                        <span class="text-xl font-bold text-emerald-800 leading-tight">{{ $summary['completed'] }}</span>
                    </div>
                </div>

                <!-- Akan Datang Card -->
                <div class="bg-canvas-pure rounded-xl p-4 border border-indigo-200 bg-indigo-50/30 shadow-xs flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-100 text-schedule-pending flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-xl">upcoming</span>
                    </div>
                    <div class="flex flex-col min-w-0">
                        <span class="text-[11px] font-medium text-indigo-900 uppercase tracking-wider truncate">Akan Datang</span>
                        <span class="text-xl font-bold text-indigo-800 leading-tight">{{ $summary['upcoming'] }}</span>
                    </div>
                </div>
            </div>

            <!-- 4. Filter Cabang, Sesi & Pencarian -->
            <div class="bg-canvas-pure rounded-xl p-3 sm:p-4 shadow-xs flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3 border border-hairline">
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    <!-- Cabang Dropdown Filter -->
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="inline-flex items-center shrink-0">
                        <input type="hidden" name="tanggal" value="{{ $selectedDate }}">
                        @if($sesi && $sesi !== 'semua')
                            <input type="hidden" name="sesi" value="{{ $sesi }}">
                        @endif
                        @if(!empty($q))
                            <input type="hidden" name="q" value="{{ $q }}">
                        @endif
                        <div class="relative w-full sm:w-auto inline-flex items-center">
                            <span class="material-symbols-outlined absolute left-2.5 text-primary text-base pointer-events-none">location_on</span>
                            <select name="cabang_id" onchange="this.form.submit()" class="w-full sm:w-auto pl-8 pr-7 py-1.5 bg-surface-pearl hover:bg-surface-container rounded-full text-xs font-semibold text-ink-body border border-hairline focus:outline-none focus:border-brand-orange cursor-pointer appearance-none transition-all">
                                <option value="">Semua Cabang</option>
                                @foreach($cabangs as $c)
                                    <option value="{{ $c->id }}" {{ $selectedCabang && $selectedCabang->id === $c->id ? 'selected' : '' }}>
                                        Cabang {{ $c->nama_cabang }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="material-symbols-outlined absolute right-2 text-ink-subtle text-sm pointer-events-none">expand_more</span>
                        </div>
                    </form>

                    <div class="hidden sm:block h-5 w-px bg-hairline"></div>

                    <!-- Sesi Filter Tabs -->
                    <div class="flex items-center gap-1.5 overflow-x-auto pb-1 sm:pb-0" id="schedule-filters">
                        @php
                            $filters = [
                                'semua' => 'Semua Sesi (' . $sessionCounts['semua'] . ')',
                                'pagi' => 'Pagi (08:00 - 12:00)',
                                'siang' => 'Siang (13:00 - 15:00)',
                                'sore' => 'Sore (15:30 - 17:30)',
                                'malam' => 'Malam (18:30 - 20:30)',
                            ];
                        @endphp

                        @foreach($filters as $key => $label)
                            <a href="{{ route('admin.dashboard', array_merge(request()->query(), ['sesi' => $key])) }}" 
                               class="px-3 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-all {{ ($sesi === $key) ? 'bg-ink-body text-white shadow-xs' : 'bg-surface-pearl text-ink-muted hover:text-ink-body hover:bg-surface-container border border-hairline' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Search Input Form -->
                <form method="GET" action="{{ route('admin.dashboard') }}" class="relative min-w-[240px]">
                    @if($selectedCabang)
                        <input type="hidden" name="cabang_id" value="{{ $selectedCabang->id }}">
                    @endif
                    <input type="hidden" name="tanggal" value="{{ $selectedDate }}">
                    @if($sesi && $sesi !== 'semua')
                        <input type="hidden" name="sesi" value="{{ $sesi }}">
                    @endif

                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-ink-subtle text-base">search</span>
                    <input type="text" 
                           name="q" 
                           value="{{ $q }}" 
                           placeholder="Cari program, tentor, atau cabang..." 
                           class="w-full bg-surface-pearl rounded-full pl-9 pr-8 py-1.5 text-xs text-ink-body placeholder:text-ink-subtle focus:outline-none focus:bg-canvas-pure border border-hairline focus:border-brand-orange transition-all">
                    
                    @if(!empty($q))
                        <a href="{{ route('admin.dashboard', array_merge(request()->query(), ['q' => null])) }}" 
                           class="absolute right-2.5 top-1/2 -translate-y-1/2 text-ink-subtle hover:text-ink-body text-xs"
                           title="Hapus pencarian">
                            <span class="material-symbols-outlined text-sm">close</span>
                        </a>
                    @endif
                </form>
            </div>

            <!-- Active Filter Indicators if Search, Sesi, or Cabang is active -->
            @if(!empty($q) || ($sesi && $sesi !== 'semua') || $selectedCabang)
                <div class="flex items-center gap-2 text-xs text-ink-muted px-1 flex-wrap">
                    <span>Filter aktif:</span>
                    @if($selectedCabang)
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-orange-50 text-secondary border border-amber-200 font-semibold">
                            <span class="material-symbols-outlined text-[13px] text-primary">location_on</span>
                            Cabang: {{ $selectedCabang->nama_cabang }}
                            <a href="{{ route('admin.dashboard', array_merge(request()->query(), ['cabang_id' => null])) }}" class="hover:text-amber-900" title="Hapus filter cabang">
                                <span class="material-symbols-outlined text-xs">close</span>
                            </a>
                        </span>
                    @endif
                    @if(!empty($q))
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                            Pencarian: "<strong>{{ $q }}</strong>"
                            <a href="{{ route('admin.dashboard', array_merge(request()->query(), ['q' => null])) }}" class="hover:text-blue-900">
                                <span class="material-symbols-outlined text-xs">close</span>
                            </a>
                        </span>
                    @endif
                    @if($sesi && $sesi !== 'semua')
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200 uppercase tracking-wider font-semibold">
                            Sesi: {{ $sesi }}
                            <a href="{{ route('admin.dashboard', array_merge(request()->query(), ['sesi' => 'semua'])) }}" class="hover:text-amber-900">
                                <span class="material-symbols-outlined text-xs">close</span>
                            </a>
                        </span>
                    @endif
                    <a href="{{ route('admin.dashboard', ['tanggal' => $selectedDate]) }}" class="text-brand-orange hover:underline font-medium ml-1">
                        Reset Filter
                    </a>
                </div>
            @endif

            <!-- 5. Daftar Jadwal Kelas Hari Ini (Schedule List View) -->
            <div class="flex flex-col gap-3">
                @forelse($jadwalList as $jadwal)
                    @php
                        $displayStatus = $jadwal->display_status;
                        $isLive = ($displayStatus === 'sedang_berlangsung');
                        $isFinished = ($displayStatus === 'selesai');
                        $isCancelled = ($displayStatus === 'dibatalkan');
                    @endphp

                    <div class="bg-canvas-pure rounded-xl p-4 sm:p-5 shadow-xs border transition-all hover:bg-surface-pearl flex flex-col md:flex-row md:items-center justify-between gap-4 {{ $isLive ? 'border-2 border-primary-container shadow-sm ring-2 ring-primary-container/10' : 'border-hairline' }}">
                        
                        <!-- Left & Middle: Time and Details -->
                        <div class="flex items-start sm:items-center gap-4">
                            <!-- Time Badge Box -->
                            <div class="flex flex-col items-center justify-center px-3 py-2 rounded-lg min-w-[105px] shrink-0 {{ $isLive ? 'bg-accent-subtle text-secondary' : ($isCancelled ? 'bg-red-50 text-red-700' : 'bg-surface-container-low text-ink-body') }}">
                                <span class="text-[10px] uppercase font-bold tracking-wider flex items-center gap-1 {{ $isLive ? 'text-secondary' : 'text-ink-muted' }}">
                                    @if($isLive)
                                        <span class="w-1.5 h-1.5 rounded-full bg-primary-container animate-pulse"></span>
                                        LIVE
                                    @else
                                        {{ ucfirst($jadwal->sesi) }}
                                    @endif
                                </span>
                                <span class="font-bold text-sm sm:text-base text-ink-body mt-0.5">
                                    {{ $jadwal->formatted_jam }}
                                </span>
                                <span class="text-[10px] font-medium {{ $isLive ? 'text-secondary font-bold' : 'text-ink-subtle' }}">
                                    {{ $isLive ? 'Sedang Jalan' : $jadwal->durasi }}
                                </span>
                            </div>

                            <!-- Class Information Details -->
                            <div class="flex flex-col gap-1">
                                <!-- Tags & Meta -->
                                <div class="flex items-center gap-2 flex-wrap">
                                    <!-- Mode Kelas (Online / Offline) -->
                                    @if(($jadwal->mode_kelas ?? 'offline') === 'online')
                                        <span class="inline-flex items-center gap-1 text-[11px] px-2.5 py-0.5 rounded-full font-bold bg-sky-50 text-sky-700 border border-sky-200">
                                            <span class="material-symbols-outlined text-[13px]">videocam</span>
                                            Online
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-[11px] px-2.5 py-0.5 rounded-full font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="material-symbols-outlined text-[13px]">domain</span>
                                            Offline
                                        </span>
                                    @endif
                                    
                                    <!-- Jenis Kelas -->
                                    <span class="text-[11px] px-2 py-0.5 rounded-full bg-surface-pearl text-ink-muted border border-hairline capitalize font-medium">
                                        {{ $jadwal->jenis_kelas }}
                                    </span>

                                    <!-- Pertemuan -->
                                    @if($jadwal->pertemuan)
                                        <span class="text-[11px] text-ink-subtle font-medium">
                                            Pertemuan {{ $jadwal->pertemuan }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Class / Program Title -->
                                <h2 class="font-bold text-base text-ink-body leading-snug">
                                    {{ $jadwal->program->nama_program ?? 'Program Pelatihan' }}
                                    @if($jadwal->catatan && !str_starts_with($jadwal->catatan, $jadwal->program->nama_program ?? ''))
                                        <span class="text-xs font-normal text-ink-muted">— {{ $jadwal->catatan }}</span>
                                    @endif
                                </h2>

                                <!-- Tentor, Room & Cabang Info -->
                                <div class="flex items-center gap-2 sm:gap-3 text-xs text-ink-muted flex-wrap mt-0.5">
                                    <!-- Cabang Label (Always Visible) -->
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-primary">
                                        <span class="material-symbols-outlined text-[15px] text-primary">location_on</span>
                                        <span>Cabang {{ $jadwal->cabang->nama_cabang ?? '-' }}</span>
                                    </span>

                                    <span class="text-ink-subtle">•</span>

                                    <!-- Tentor -->
                                    <span class="flex items-center gap-1 {{ $isLive ? 'text-ink-body font-semibold' : '' }}">
                                        <span class="material-symbols-outlined text-sm {{ $isLive ? 'text-primary' : 'text-ink-subtle' }}">person</span>
                                        <span>{{ $jadwal->tentor->nama ?? 'Tentor Belum Ditentukan' }}</span>
                                    </span>

                                    <span class="text-ink-subtle">•</span>

                                    <!-- Ruangan -->
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm text-ink-subtle">meeting_room</span>
                                        <span>{{ $jadwal->ruangan ?? 'Ruang Belum Ditentukan' }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Status Badge & Actions -->
                        <div class="flex items-center justify-between md:justify-end gap-3 pt-3 md:pt-0 border-t md:border-t-0 border-hairline/60">
                            <!-- Status Badge -->
                            <div>
                                @if($isLive)
                                    <span class="px-2.5 py-1 rounded-full bg-accent-subtle text-secondary text-[11px] font-bold flex items-center gap-1.5 border border-amber-200">
                                        <span class="w-2 h-2 rounded-full bg-secondary-container animate-ping"></span>
                                        Sedang Berlangsung
                                    </span>
                                @elseif($isFinished)
                                    <span class="px-2.5 py-1 rounded-full bg-surface-container-low text-ink-muted text-[11px] font-bold flex items-center gap-1">
                                        <span class="w-2 h-2 rounded-full bg-schedule-verified"></span>
                                        Selesai
                                    </span>
                                @elseif($isCancelled)
                                    <span class="px-2.5 py-1 rounded-full bg-red-50 text-red-700 text-[11px] font-bold flex items-center gap-1 border border-red-200">
                                        <span class="w-2 h-2 rounded-full bg-schedule-conflict"></span>
                                        Dibatalkan
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full bg-indigo-50 text-schedule-pending text-[11px] font-bold flex items-center gap-1 border border-indigo-100">
                                        <span class="w-2 h-2 rounded-full bg-schedule-pending"></span>
                                        Akan Datang
                                    </span>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center gap-1.5">
                                @if($isLive)
                                    <a href="{{ route('admin.jadwal.show', $jadwal->id) }}" 
                                       class="px-3 py-1.5 rounded-full bg-primary text-white text-xs font-semibold hover:opacity-90 shadow-xs transition-all inline-flex items-center gap-1">
                                        <span>Kelola Kelas</span>
                                    </a>
                                @else
                                    <a href="{{ route('admin.jadwal.show', $jadwal->id) }}" 
                                       class="px-3 py-1.5 rounded-full bg-surface-pearl text-ink-body text-xs font-semibold hover:bg-surface-container border border-hairline transition-all inline-flex items-center gap-1">
                                        <span>Lihat Detail</span>
                                    </a>
                                @endif

                                <a href="{{ route('admin.jadwal.edit', $jadwal->id) }}" 
                                   class="px-3 py-1.5 rounded-full text-ink-muted hover:text-ink-body hover:bg-surface-pearl text-xs font-semibold transition-all inline-flex items-center gap-1">
                                    <span>Ubah</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Empty State -->
                    <div class="bg-canvas-pure rounded-2xl border border-hairline p-8 sm:p-12 text-center flex flex-col items-center justify-center">
                        <div class="w-16 h-16 rounded-2xl bg-surface-pearl text-ink-subtle flex items-center justify-center mb-4 border border-hairline">
                            <span class="material-symbols-outlined text-3xl">event_busy</span>
                        </div>
                        <h3 class="text-lg font-bold text-ink-body">Tidak Ada Jadwal Kelas</h3>
                        <p class="text-sm text-ink-muted max-w-md mt-1 mb-6">
                            Tidak ada jadwal kelas yang ditemukan untuk kriteria filter atau tanggal yang dipilih @if($selectedCabang) di Cabang {{ $selectedCabang->nama_cabang }} @else di Semua Cabang @endif.
                        </p>
                        <div class="flex items-center gap-3 flex-wrap justify-center">
                            @if(!$isToday)
                                <a href="{{ route('admin.dashboard', ['tanggal' => $todayDate]) }}" 
                                   id="btnKembaliHariIni"
                                   class="px-4 py-2 rounded-full bg-ink-body text-white text-xs font-semibold hover:opacity-90 transition-all">
                                    Kembali ke Hari Ini
                                </a>
                            @endif
                            @if(!empty($q) || ($sesi && $sesi !== 'semua') || $selectedCabang)
                                <a href="{{ route('admin.dashboard', ['tanggal' => $selectedDate]) }}" 
                                   class="px-4 py-2 rounded-full bg-surface-pearl text-ink-body border border-hairline text-xs font-semibold hover:bg-surface-container transition-all">
                                    Reset Filter
                                </a>
                            @endif
                            <a href="{{ route('admin.jadwal.create', array_filter(['cabang_id' => $selectedCabang?->id, 'tanggal' => $selectedDate])) }}" 
                               class="px-4 py-2 rounded-full bg-primary-container text-white text-xs font-semibold hover:bg-brand-hover transition-all">
                                Tambah Jadwal Baru
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-canvas-pure border-t border-hairline py-4 px-4 text-center text-xs text-ink-muted mt-auto">
        <div class="max-w-6xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2">
            <span>&copy; {{ date('Y') }} Elips Academy. Sistem Manajemen Jadwal Operasional.</span>
            <span class="text-[11px] text-ink-subtle">Role: Admin Cabang • Versi 1.0</span>
        </div>
    </footer>

    <!-- Interactive Detail & Action Modals -->
    <div id="generalModal" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-hairline flex flex-col gap-4 animate-in fade-in zoom-in-95 duration-150">
            <div class="flex items-start justify-between gap-2">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-orange-100 text-brand-orange flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-lg">info</span>
                    </span>
                    <h4 id="modalTitle" class="text-base font-bold text-ink-body">Pemberitahuan</h4>
                </div>
                <button type="button" onclick="closeGeneralModal()" class="text-ink-subtle hover:text-ink-body">
                    <span class="material-symbols-outlined text-xl">close</span>
                </button>
            </div>
            <p id="modalBody" class="text-sm text-ink-muted">Deskripsi aksi.</p>
            <div class="flex justify-end pt-2">
                <button type="button" onclick="closeGeneralModal()" class="px-4 py-1.5 rounded-full bg-ink-body text-white text-xs font-semibold hover:opacity-90">
                    Mengerti
                </button>
            </div>
        </div>
    </div>

    <!-- Class Detail Modal -->
    <div id="detailModal" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl border border-hairline flex flex-col gap-4 animate-in fade-in zoom-in-95 duration-150">
            <div class="flex items-start justify-between gap-2 border-b border-hairline pb-3">
                <div>
                    <div class="flex items-center gap-2">
                        <span id="dtKodeKelas" class="text-xs font-bold px-2 py-0.5 rounded-full bg-primary-container text-white"></span>
                        <span id="dtStatus" class="text-xs font-bold px-2 py-0.5 rounded-full bg-surface-pearl text-ink-muted"></span>
                    </div>
                    <h3 id="dtProgram" class="text-lg font-bold text-ink-body mt-1"></h3>
                </div>
                <button type="button" onclick="closeDetailModal()" class="text-ink-subtle hover:text-ink-body">
                    <span class="material-symbols-outlined text-xl">close</span>
                </button>
            </div>

            <div class="grid grid-cols-2 gap-3 text-xs">
                <div class="p-3 rounded-xl bg-surface-pearl border border-hairline">
                    <span class="text-ink-muted font-medium block">Waktu & Durasi:</span>
                    <span id="dtWaktu" class="font-bold text-ink-body text-sm mt-0.5 block"></span>
                </div>
                <div class="p-3 rounded-xl bg-surface-pearl border border-hairline">
                    <span class="text-ink-muted font-medium block">Ruangan:</span>
                    <span id="dtRuangan" class="font-bold text-ink-body text-sm mt-0.5 block"></span>
                </div>
                <div class="p-3 rounded-xl bg-surface-pearl border border-hairline col-span-2">
                    <span class="text-ink-muted font-medium block">Tentor Pengampu:</span>
                    <span id="dtTentor" class="font-bold text-ink-body text-sm mt-0.5 block"></span>
                </div>
                <div class="p-3 rounded-xl bg-surface-pearl border border-hairline col-span-2" id="dtCatatanWrapper">
                    <span class="text-ink-muted font-medium block">Catatan Kelas:</span>
                    <span id="dtCatatan" class="text-ink-body mt-0.5 block"></span>
                </div>
            </div>

            <div class="flex justify-between items-center pt-2 border-t border-hairline">
                <span class="text-[11px] text-ink-subtle">Elips Academy Schedule Management</span>
                <button type="button" onclick="closeDetailModal()" class="px-4 py-1.5 rounded-full bg-ink-body text-white text-xs font-semibold hover:opacity-90">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Scripts for modal popups -->
    <script>
        function openActionModal(title, message) {
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('modalBody').textContent = message;
            document.getElementById('generalModal').classList.remove('hidden');
        }

        function closeGeneralModal() {
            document.getElementById('generalModal').classList.add('hidden');
        }

        function openDetailModal(kode, program, tentor, ruangan, waktu, durasi, status, catatan) {
            document.getElementById('dtKodeKelas').textContent = kode;
            document.getElementById('dtProgram').textContent = program;
            document.getElementById('dtStatus').textContent = status;
            document.getElementById('dtWaktu').textContent = waktu + ' (' + durasi + ')';
            document.getElementById('dtRuangan').textContent = ruangan || '-';
            document.getElementById('dtTentor').textContent = tentor || 'Belum ditentukan';
            document.getElementById('dtCatatan').textContent = catatan || 'Tidak ada catatan khusus untuk sesi ini.';
            document.getElementById('detailModal').classList.remove('hidden');
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeGeneralModal();
                closeDetailModal();
            }
        });
    </script>
</body>
</html>

