<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manajemen Akun Pengguna — Superadmin Elips Academy</title>

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
                        'primary': '#904d00',
                        'primary-container': '#f28e2b',
                        'on-primary-container': '#5e3000',
                        'brand-hover': '#e07d1a',
                        'canvas-pure': '#ffffff',
                        'canvas-parchment': '#F5F5F7',
                        'surface-pearl': '#FAFAFC',
                        'surface-container-low': '#f6f3f5',
                        'surface-container': '#f0edef',
                        'surface-container-high': '#eae7ea',
                        'hairline': '#E0E0E0',
                        'ink-body': '#1D1D1F',
                        'ink-muted': '#6E6E73',
                        'ink-subtle': '#86868B',
                        'accent-subtle': '#FEF3C7',
                        'accent-focus': '#F59E0B',
                        'schedule-verified': '#10B981',
                        'schedule-pending': '#6366F1',
                        'error': '#dc2626',
                        'error-container': '#fee2e2',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
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
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-primary-container text-white flex items-center justify-center font-bold text-lg shadow-sm">
                        E
                    </div>
                    <div>
                        <span class="font-bold text-base tracking-tight text-ink-body block leading-tight">Elips Academy</span>
                        <span class="text-[10px] tracking-wider uppercase text-primary font-bold">Portal Superadmin</span>
                    </div>
                </div>
                <button type="button" onclick="toggleSidebar()" class="lg:hidden p-1.5 rounded-lg text-ink-muted hover:bg-surface-container-low transition-colors" aria-label="Tutup Menu">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
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
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold bg-accent-subtle text-primary shadow-xs transition-all">
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
                        {{ strtoupper(substr($currentUser->nama, 0, 1)) }}
                    </div>
                    <div class="flex flex-col min-w-0">
                        <span class="text-xs font-semibold text-ink-body truncate">{{ $currentUser->nama }}</span>
                        <span class="text-[10px] text-ink-muted truncate">{{ $currentUser->email }}</span>
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
                    <span class="text-primary font-bold">AKUN PENGGUNA</span>
                    <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                    <span class="text-ink-body font-semibold">USERS</span>
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
        </header>

        <!-- Main Body -->
        <main class="p-4 sm:p-6 lg:p-8 space-y-6">
            <!-- Flash Message -->
            @if(session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between shadow-xs animate-fade-in" id="flashSuccess">
                    <div class="flex items-center gap-2.5">
                        <span class="material-symbols-outlined text-schedule-verified">check_circle</span>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                    <button onclick="document.getElementById('flashSuccess').remove()" class="text-emerald-600 hover:text-emerald-900">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm flex items-center justify-between shadow-xs animate-fade-in" id="flashError">
                    <div class="flex items-center gap-2.5">
                        <span class="material-symbols-outlined text-error">error</span>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                    <button onclick="document.getElementById('flashError').remove()" class="text-red-600 hover:text-red-900">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm shadow-xs animate-fade-in">
                    <div class="flex items-center gap-2 mb-2 font-semibold">
                        <span class="material-symbols-outlined text-error text-[18px]">warning</span>
                        <span>Terjadi kesalahan validasi data:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-1 text-xs">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Title & Top CTA -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-ink-body">Manajemen Akun Pengguna</h1>
                    <p class="text-sm text-ink-muted mt-1">
                        Kelola akun akses sistem, pembagian role hak akses (Admin & Superadmin), dan audit keamanan autentikasi Elips Academy.
                    </p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <button type="button" 
                            id="btnTambahUser"
                            onclick="openTambahModal()"
                            class="px-4 py-2.5 rounded-full bg-primary-container hover:bg-brand-hover text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition-all active:scale-95 cursor-pointer">
                        <span class="material-symbols-outlined text-[18px]">person_add</span>
                        <span>+ Tambah Akun Pengguna</span>
                    </button>
                </div>
            </div>

            <!-- 4 Metric Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Metric 1: Total Pengguna -->
                <div class="p-5 bg-canvas-pure rounded-2xl border border-hairline shadow-xs flex flex-col justify-between relative overflow-hidden">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs uppercase font-bold tracking-wider text-ink-muted">Total Pengguna</span>
                        <span class="w-8 h-8 rounded-full bg-surface-pearl flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined text-[18px]">group</span>
                        </span>
                    </div>
                    <div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-extrabold text-ink-body font-sans">{{ $totalUsers }}</span>
                            <span class="text-xs text-ink-muted font-medium">Akun Terdaftar</span>
                        </div>
                        <div class="mt-2.5 pt-2 border-t border-hairline flex items-center justify-between text-xs text-ink-muted">
                            <span>{{ $totalSuperadmin }} Superadmin</span>
                            <span class="w-1 h-1 rounded-full bg-hairline"></span>
                            <span>{{ $totalAdmin }} Admin Cabang</span>
                        </div>
                    </div>
                </div>

                <!-- Metric 2: Superadmin -->
                <div class="p-5 bg-canvas-pure rounded-2xl border border-hairline shadow-xs flex flex-col justify-between relative overflow-hidden">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs uppercase font-bold tracking-wider text-ink-muted">Role Superadmin</span>
                        <span class="w-8 h-8 rounded-full bg-amber-50 flex items-center justify-center text-amber-600">
                            <span class="material-symbols-outlined text-[18px]">shield_person</span>
                        </span>
                    </div>
                    <div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-extrabold text-amber-600 font-sans">{{ $totalSuperadmin }}</span>
                            <span class="text-xs text-ink-muted font-medium">Akun Master</span>
                        </div>
                        <div class="mt-2.5 pt-2 border-t border-hairline flex items-center gap-1.5 text-xs text-emerald-600 font-medium">
                            <span class="material-symbols-outlined text-[14px]">public</span>
                            <span>Akses Global Penuh</span>
                        </div>
                    </div>
                </div>

                <!-- Metric 3: Admin Operasional -->
                <div class="p-5 bg-canvas-pure rounded-2xl border border-hairline shadow-xs flex flex-col justify-between relative overflow-hidden">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs uppercase font-bold tracking-wider text-ink-muted">Admin Operasional</span>
                        <span class="w-8 h-8 rounded-full bg-orange-50 flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined text-[18px]">badge</span>
                        </span>
                    </div>
                    <div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-extrabold text-ink-body font-sans">{{ $totalAdmin }}</span>
                            <span class="text-xs text-ink-muted font-medium">Akun Cabang</span>
                        </div>
                        <div class="mt-2.5 pt-2 border-t border-hairline flex items-center gap-1.5 text-xs text-primary font-medium">
                            <span class="material-symbols-outlined text-[14px]">domain</span>
                            <span>Penugasan Operasional</span>
                        </div>
                    </div>
                </div>

                <!-- Metric 4: Keamanan Autentikasi -->
                <div class="p-5 bg-canvas-pure rounded-2xl border border-hairline shadow-xs flex flex-col justify-between relative overflow-hidden">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs uppercase font-bold tracking-wider text-ink-muted">Keamanan Autentikasi</span>
                        <span class="w-8 h-8 rounded-full bg-emerald-50 flex items-center justify-center text-schedule-verified">
                            <span class="material-symbols-outlined text-[18px]">lock</span>
                        </span>
                    </div>
                    <div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-extrabold text-schedule-verified font-sans">100%</span>
                            <span class="text-xs text-ink-muted font-medium">Bcrypt / Hashed</span>
                        </div>
                        <div class="mt-2.5 pt-2 border-t border-hairline flex items-center gap-1.5 text-xs text-ink-muted font-medium">
                            <span class="material-symbols-outlined text-[14px] text-schedule-verified">verified_user</span>
                            <span>Email Unique Indexed</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter & Search Toolbar -->
            <div class="bg-canvas-pure p-4 rounded-2xl border border-hairline shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-3">
                <form method="GET" action="{{ route('superadmin.user.index') }}" class="flex-1 flex flex-col sm:flex-row items-center gap-3">
                    <!-- Search Input -->
                    <div class="w-full sm:w-80 relative flex items-center">
                        <span class="material-symbols-outlined absolute left-3 text-ink-subtle text-[18px]">search</span>
                        <input type="text" 
                               name="q"
                               id="searchInput"
                               value="{{ $search }}"
                               placeholder="Cari nama pengguna atau email..." 
                               class="w-full pl-9 pr-3 py-2 bg-surface-pearl text-ink-body text-xs rounded-full border border-hairline outline-none focus:border-primary focus:bg-canvas-pure transition-all">
                    </div>

                    <!-- Filter Role -->
                    <div class="relative w-full sm:w-auto">
                        <select name="role" 
                                id="filterRole"
                                onchange="this.form.submit()"
                                class="appearance-none pl-3.5 pr-8 py-2 bg-surface-pearl text-ink-body text-xs font-medium rounded-full border border-hairline outline-none cursor-pointer hover:bg-surface-container-low transition-colors w-full sm:w-auto">
                            <option value="all" {{ $roleFilter === 'all' ? 'selected' : '' }}>Semua Role</option>
                            <option value="superadmin" {{ $roleFilter === 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                            <option value="admin" {{ $roleFilter === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        <span class="material-symbols-outlined absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none text-ink-subtle text-[16px]">expand_more</span>
                    </div>

                    <button type="submit" class="hidden">Cari</button>
                </form>

                <!-- Count Info & Reset -->
                <div class="flex items-center gap-2 shrink-0 self-end sm:self-center">
                    <span class="text-xs text-ink-muted">
                        Menampilkan <strong class="text-ink-body font-bold">{{ $users->count() }}</strong> dari {{ $totalUsers }} akun
                    </span>
                    @if($search !== '' || $roleFilter !== 'all')
                        <a href="{{ route('superadmin.user.index') }}" 
                           class="w-8 h-8 rounded-full bg-surface-pearl hover:bg-surface-container-low flex items-center justify-center text-ink-muted transition-colors"
                           title="Reset filter">
                            <span class="material-symbols-outlined text-[16px]">refresh</span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Split Content Layout (7 Cols Left, 5 Cols Right) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Kolom Kiri: Tabel Users (7 Cols) -->
                <div class="lg:col-span-7 flex flex-col gap-4">
                    <div class="bg-canvas-pure rounded-2xl border border-hairline shadow-xs overflow-hidden">
                        <!-- Table Header Bar -->
                        <div class="px-5 py-3.5 bg-surface-pearl border-b border-hairline flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-[18px]">table_rows</span>
                                <span class="text-xs font-bold text-ink-body">Tabel SQL: <code class="font-mono text-primary font-bold">users</code></span>
                                <span class="px-1.5 py-0.5 rounded bg-surface-container-low text-[10px] font-semibold text-ink-muted uppercase">Laravel 11 Casts</span>
                            </div>
                            <div class="flex items-center gap-1.5 text-ink-muted text-xs">
                                <span class="material-symbols-outlined text-[14px] text-schedule-verified">lock_reset</span>
                                <span>Password Cast: Hashed</span>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse" id="userTable">
                                <thead>
                                    <tr class="border-b border-hairline text-[11px] font-bold uppercase tracking-wider text-ink-muted bg-canvas-pure">
                                        <th class="py-3 px-4">Pengguna</th>
                                        <th class="py-3 px-3">Email</th>
                                        <th class="py-3 px-3">Role</th>
                                        <th class="py-3 px-4 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-hairline text-xs">
                                    @forelse($users as $u)
                                        @php
                                            $isSelected = $selectedUser && $selectedUser->id === $u->id;
                                            $isSuper = $u->role === 'superadmin';
                                            $initials = strtoupper(substr($u->nama, 0, 2));
                                            if (str_contains($u->nama, ' ')) {
                                                $parts = explode(' ', $u->nama);
                                                $initials = strtoupper(substr($parts[0], 0, 1) . substr(end($parts), 0, 1));
                                            }
                                        @endphp
                                        <tr class="transition-colors cursor-pointer group {{ $isSelected ? 'bg-accent-subtle/40 hover:bg-accent-subtle/50' : 'hover:bg-surface-pearl' }}"
                                            onclick="window.location.href='{{ route('superadmin.user.index', array_merge(request()->query(), ['selected' => $u->id])) }}'">
                                            <!-- Pengguna -->
                                            <td class="py-3.5 px-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs shrink-0 {{ $isSuper ? 'bg-amber-100 text-amber-800' : 'bg-orange-100 text-primary' }}">
                                                        {{ $initials }}
                                                    </div>
                                                    <div class="flex flex-col min-w-0">
                                                        <div class="flex items-center gap-1">
                                                            <span class="font-bold text-ink-body truncate">{{ $u->nama }}</span>
                                                            @if($u->id === $currentUser->id)
                                                                <span class="px-1 py-0.2 rounded bg-emerald-100 text-emerald-800 text-[9px] font-bold">Anda</span>
                                                            @endif
                                                        </div>
                                                        <span class="font-mono text-[10px] text-ink-subtle">ID: #USR-{{ str_pad($u->id, 3, '0', STR_PAD_LEFT) }}</span>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Email -->
                                            <td class="py-3.5 px-3">
                                                <div class="flex flex-col">
                                                    <span class="text-ink-body font-medium truncate">{{ $u->email }}</span>
                                                    <span class="text-[10px] text-schedule-verified flex items-center gap-0.5">
                                                        <span class="material-symbols-outlined text-[12px]">check_circle</span> Unik
                                                    </span>
                                                </div>
                                            </td>

                                            <!-- Role -->
                                            <td class="py-3.5 px-3">
                                                @if($isSuper)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                        SUPERADMIN
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-orange-50 text-primary border border-orange-200">
                                                        ADMIN
                                                    </span>
                                                @endif
                                            </td>

                                            <!-- Aksi -->
                                            <td class="py-3.5 px-4 text-right" onclick="event.stopPropagation()">
                                                <div class="flex items-center justify-end gap-1">
                                                    <!-- Edit Modal Trigger -->
                                                    <button type="button" 
                                                            onclick="openEditModal({{ json_encode($u) }})"
                                                            title="Edit Akun"
                                                            class="w-7 h-7 rounded-full hover:bg-surface-container-low text-ink-muted hover:text-primary flex items-center justify-center transition-colors cursor-pointer">
                                                        <span class="material-symbols-outlined text-[16px]">edit</span>
                                                    </button>

                                                    <!-- Reset Password Modal Trigger -->
                                                    <button type="button" 
                                                            onclick="openResetPasswordModal({{ $u->id }}, '{{ addslashes($u->nama) }}')"
                                                            title="Reset Password"
                                                            class="w-7 h-7 rounded-full hover:bg-amber-50 text-ink-muted hover:text-amber-600 flex items-center justify-center transition-colors cursor-pointer">
                                                        <span class="material-symbols-outlined text-[16px]">key</span>
                                                    </button>

                                                    <!-- Delete Modal Trigger -->
                                                    @if($u->id !== $currentUser->id)
                                                        <button type="button" 
                                                                onclick="openDeleteModal({{ $u->id }}, '{{ addslashes($u->nama) }}')"
                                                                title="Hapus Akun"
                                                                class="w-7 h-7 rounded-full hover:bg-red-50 text-ink-muted hover:text-error flex items-center justify-center transition-colors cursor-pointer">
                                                            <span class="material-symbols-outlined text-[16px]">delete</span>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-12 text-center text-ink-muted">
                                                <div class="flex flex-col items-center justify-center gap-2">
                                                    <span class="material-symbols-outlined text-4xl text-ink-subtle">search_off</span>
                                                    <p class="font-medium">Tidak ada data pengguna ditemukan.</p>
                                                    <p class="text-xs">Coba sesuaikan kata kunci pencarian atau filter role.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Table Footer -->
                        <div class="px-5 py-3 bg-canvas-pure border-t border-hairline flex items-center justify-between text-xs text-ink-muted">
                            <span>Menampilkan {{ $users->count() }} dari {{ $totalUsers }} pengguna</span>
                            <span class="font-medium text-schedule-verified flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">shield</span> Database Protected
                            </span>
                        </div>
                    </div>

                    <!-- Security Log Callout -->
                    <div class="p-4 bg-canvas-pure rounded-2xl border border-hairline shadow-xs flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <span class="material-symbols-outlined text-schedule-verified text-[20px]">policy</span>
                            <span class="text-xs text-ink-body">
                                <strong>Laravel Security Policy:</strong> Proteksi pencegahan penghapusan akun aktif sendiri diterapkan secara server-side.
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Panel Quick Detail User (5 Cols) -->
                <div class="lg:col-span-5 flex flex-col gap-4">
                    @if($selectedUser)
                        @php
                            $isSelSuper = $selectedUser->role === 'superadmin';
                            $selInitials = strtoupper(substr($selectedUser->nama, 0, 2));
                            if (str_contains($selectedUser->nama, ' ')) {
                                $sp = explode(' ', $selectedUser->nama);
                                $selInitials = strtoupper(substr($sp[0], 0, 1) . substr(end($sp), 0, 1));
                            }
                        @endphp
                        <div class="bg-canvas-pure rounded-2xl border border-hairline shadow-xs p-6 flex flex-col gap-5 sticky top-24">
                            <!-- Header Profil User Terpilih -->
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center font-bold text-xl shadow-xs {{ $isSelSuper ? 'bg-amber-500 text-white' : 'bg-primary-container text-white' }}">
                                        {{ $selInitials }}
                                    </div>
                                    <div class="flex flex-col min-w-0">
                                        <div class="flex items-center gap-1.5">
                                            <h3 class="font-bold text-base text-ink-body truncate">{{ $selectedUser->nama }}</h3>
                                            @if($isSelSuper)
                                                <span class="material-symbols-outlined text-[16px] text-amber-500 fill" title="Superadmin Access">verified</span>
                                            @endif
                                        </div>
                                        <span class="text-xs text-ink-muted truncate">{{ $selectedUser->email }}</span>
                                        <div class="flex items-center gap-2 mt-1.5">
                                            @if($isSelSuper)
                                                <span class="px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 text-[10px] font-bold border border-amber-200">
                                                    SUPERADMIN
                                                </span>
                                            @else
                                                <span class="px-2 py-0.5 rounded-full bg-orange-50 text-primary text-[10px] font-bold border border-orange-200">
                                                    ADMIN CABANG
                                                </span>
                                            @endif

                                            @if($selectedUser->id === $currentUser->id)
                                                <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-schedule-verified text-[10px] font-semibold border border-emerald-100">
                                                    • Sesi Aktif Anda
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Quick Pills -->
                            <div class="grid grid-cols-2 gap-2">
                                <button type="button" 
                                        onclick="openResetPasswordModal({{ $selectedUser->id }}, '{{ addslashes($selectedUser->nama) }}')"
                                        class="px-3 py-2 rounded-xl bg-surface-pearl hover:bg-surface-container-low text-ink-body text-xs font-semibold flex items-center justify-center gap-1.5 transition-colors cursor-pointer border border-hairline">
                                    <span class="material-symbols-outlined text-[16px] text-primary">key</span>
                                    <span>Reset Password</span>
                                </button>
                                <button type="button" 
                                        onclick="openEditModal({{ json_encode($selectedUser) }})"
                                        class="px-3 py-2 rounded-xl bg-surface-pearl hover:bg-surface-container-low text-ink-body text-xs font-semibold flex items-center justify-center gap-1.5 transition-colors cursor-pointer border border-hairline">
                                    <span class="material-symbols-outlined text-[16px] text-ink-muted">edit</span>
                                    <span>Ubah Data</span>
                                </button>
                            </div>

                            <!-- Seksi Matrix Permission Hak Akses (PRD Section 4) -->
                            <div class="space-y-2 pt-1 border-t border-hairline">
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-ink-muted">
                                        Matrix Permission (PRD Poin 4)
                                    </span>
                                    <span class="text-[11px] font-bold {{ $isSelSuper ? 'text-amber-600' : 'text-primary' }}">
                                        {{ $isSelSuper ? 'Akses Penuh (Superadmin)' : 'Akses Terbatas (Admin)' }}
                                    </span>
                                </div>

                                <div class="bg-surface-pearl rounded-xl p-3 space-y-2 text-xs border border-hairline">
                                    <!-- Perm 1 -->
                                    <div class="flex items-center justify-between py-0.5">
                                        <div class="flex items-center gap-2">
                                            <span class="material-symbols-outlined text-[16px] text-schedule-verified">check_circle</span>
                                            <span class="text-ink-body font-medium">Manajemen Jadwal (CRUD)</span>
                                        </div>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $isSelSuper ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">
                                            {{ $isSelSuper ? 'Semua Cabang' : 'Cabang Aktif' }}
                                        </span>
                                    </div>

                                    <!-- Perm 2 -->
                                    <div class="flex items-center justify-between py-0.5">
                                        <div class="flex items-center gap-2">
                                            @if($isSelSuper)
                                                <span class="material-symbols-outlined text-[16px] text-schedule-verified">check_circle</span>
                                                <span class="text-ink-body font-medium">Data Master (Cabang, Program, Tentor)</span>
                                            @else
                                                <span class="material-symbols-outlined text-[16px] text-ink-subtle">cancel</span>
                                                <span class="text-ink-muted line-through">Data Master</span>
                                            @endif
                                        </div>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $isSelSuper ? 'bg-amber-100 text-amber-800' : 'bg-surface-container-low text-ink-muted' }}">
                                            {{ $isSelSuper ? 'Full Akses' : 'Tidak Ada Izin' }}
                                        </span>
                                    </div>

                                    <!-- Perm 3 -->
                                    <div class="flex items-center justify-between py-0.5">
                                        <div class="flex items-center gap-2">
                                            @if($isSelSuper)
                                                <span class="material-symbols-outlined text-[16px] text-schedule-verified">check_circle</span>
                                                <span class="text-ink-body font-medium">Kelola Akun Pengguna</span>
                                            @else
                                                <span class="material-symbols-outlined text-[16px] text-ink-subtle">cancel</span>
                                                <span class="text-ink-muted line-through">Kelola Akun Pengguna</span>
                                            @endif
                                        </div>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $isSelSuper ? 'bg-amber-100 text-amber-800' : 'bg-surface-container-low text-ink-muted' }}">
                                            {{ $isSelSuper ? 'Superadmin Only' : 'Tidak Ada Izin' }}
                                        </span>
                                    </div>

                                    <!-- Perm 4 -->
                                    <div class="flex items-center justify-between py-0.5">
                                        <div class="flex items-center gap-2">
                                            <span class="material-symbols-outlined text-[16px] text-schedule-verified">check_circle</span>
                                            <span class="text-ink-body font-medium">Filter & Deteksi Konflik</span>
                                        </div>
                                        <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 text-[10px] font-bold">
                                            Aktif
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Seksi Keamanan & Autentikasi -->
                            <div class="space-y-2 pt-1 border-t border-hairline">
                                <span class="text-[11px] font-bold uppercase tracking-wider text-ink-muted">
                                    Keamanan & Autentikasi (PRD Poin 6)
                                </span>

                                <div class="bg-surface-pearl rounded-xl p-3 space-y-2 text-xs border border-hairline">
                                    <div class="flex items-center justify-between">
                                        <span class="text-ink-muted">Enkripsi Password:</span>
                                        <span class="font-mono font-bold text-schedule-verified text-[11px]">Bcrypt ($2y$12$...)</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-ink-muted">Email Unique Key:</span>
                                        <span class="font-semibold text-ink-body">MySQL InnoDB Unique</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-ink-muted">Dibuat Pada:</span>
                                        <span class="text-ink-body font-medium">{{ $selectedUser->created_at ? $selectedUser->created_at->format('d M Y, H:i') : '-' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-ink-muted">Pembaruan Terakhir:</span>
                                        <span class="text-ink-body font-medium">{{ $selectedUser->updated_at ? $selectedUser->updated_at->format('d M Y, H:i') : '-' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Action CTA Buttons -->
                            <div class="pt-2 flex items-center gap-2">
                                <button type="button" 
                                        onclick="openEditModal({{ json_encode($selectedUser) }})"
                                        class="flex-1 py-2.5 rounded-full bg-primary hover:bg-primary-container text-white text-xs font-semibold flex items-center justify-center gap-1.5 shadow-xs transition-all active:scale-95 cursor-pointer">
                                    <span class="material-symbols-outlined text-[16px]">edit</span>
                                    <span>Ubah Data Akun</span>
                                </button>

                                @if($selectedUser->id !== $currentUser->id)
                                    <button type="button" 
                                            onclick="openDeleteModal({{ $selectedUser->id }}, '{{ addslashes($selectedUser->nama) }}')"
                                            class="px-4 py-2.5 rounded-full bg-red-50 hover:bg-red-100 text-error text-xs font-semibold flex items-center justify-center gap-1.5 transition-colors cursor-pointer border border-red-200">
                                        <span class="material-symbols-outlined text-[16px]">delete</span>
                                        <span>Hapus</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="bg-canvas-pure rounded-2xl border border-hairline shadow-xs p-8 text-center text-ink-muted">
                            <span class="material-symbols-outlined text-4xl text-ink-subtle mb-2">person_search</span>
                            <p class="font-medium text-sm">Pilih salah satu pengguna dari tabel untuk melihat detail.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Bottom Guidelines & Architecture Info (PRD Poin 4 & 6) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                <div class="p-5 bg-canvas-pure rounded-2xl border border-hairline shadow-xs space-y-2">
                    <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                            <span class="material-symbols-outlined text-[18px]">admin_panel_settings</span>
                        </span>
                        <div>
                            <h4 class="text-sm font-bold text-ink-body">Kebijakan Pembagian Role Sistem (PRD Poin 4)</h4>
                            <p class="text-[11px] text-ink-muted">Hierarki Hak Akses: Superadmin vs Admin Cabang</p>
                        </div>
                    </div>
                    <p class="text-xs text-ink-muted leading-relaxed">
                        Role <code class="font-mono font-bold text-amber-700">superadmin</code> memegang kendali penuh lintas cabang (Buduran & Candi), mencakup penambahan akun pengguna, kontrol data master program kursus, cabang, dan tentor. Sementara role <code class="font-mono font-bold text-primary">admin</code> difokuskan pada kegiatan operasional penjadwalan harian cabang masing-masing.
                    </p>
                </div>

                <div class="p-5 bg-canvas-pure rounded-2xl border border-hairline shadow-xs space-y-2">
                    <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-xl bg-emerald-50 flex items-center justify-center text-schedule-verified">
                            <span class="material-symbols-outlined text-[18px]">security</span>
                        </span>
                        <div>
                            <h4 class="text-sm font-bold text-ink-body">Integritas & Keamanan Akun Laravel 11 (PRD Poin 6)</h4>
                            <p class="text-[11px] text-ink-muted">Enkripsi Password, Unique Key, & Proteksi Server-Side</p>
                        </div>
                    </div>
                    <p class="text-xs text-ink-muted leading-relaxed">
                        Kolom <code class="font-mono font-bold text-ink-body">email</code> dijamin unik melalui Unique Constraint MySQL untuk mencegah duplikasi identitas. Seluruh password diamankan secara mutlak menggunakan hashing satu arah (<code class="font-mono font-bold text-emerald-700">Hash::make()</code>) tanpa pernah disimpan dalam plain text.
                    </p>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- MODAL: Tambah Akun Pengguna -->
<div id="modalTambahUser" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-ink-body/50 backdrop-blur-xs animate-in fade-in">
    <div class="bg-canvas-pure rounded-2xl border border-hairline shadow-xl max-w-md w-full overflow-hidden animate-scale-up">
        <div class="px-6 py-4 border-b border-hairline flex items-center justify-between bg-surface-pearl">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[20px]">person_add</span>
                <h3 class="font-bold text-sm text-ink-body">Tambah Akun Pengguna Baru</h3>
            </div>
            <button onclick="closeTambahModal()" class="text-ink-muted hover:text-ink-body">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <form method="POST" action="{{ route('superadmin.user.store') }}" class="p-6 space-y-4">
            @csrf
            <div>
                <label for="create_nama" class="block text-xs font-bold text-ink-body mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" 
                       id="create_nama" 
                       name="nama" 
                       required 
                       placeholder="Misal: Dewi Lestari" 
                       class="w-full px-3.5 py-2 bg-surface-pearl border border-hairline rounded-xl text-xs text-ink-body focus:border-primary focus:bg-canvas-pure outline-none">
            </div>

            <div>
                <label for="create_email" class="block text-xs font-bold text-ink-body mb-1">Alamat Email <span class="text-red-500">*</span></label>
                <input type="email" 
                       id="create_email" 
                       name="email" 
                       required 
                       placeholder="nama@elipsacademy.com" 
                       class="w-full px-3.5 py-2 bg-surface-pearl border border-hairline rounded-xl text-xs text-ink-body focus:border-primary focus:bg-canvas-pure outline-none">
                <p class="text-[10px] text-ink-muted mt-1">Harus unik dan belum pernah didaftarkan.</p>
            </div>

            <div>
                <label for="create_password" class="block text-xs font-bold text-ink-body mb-1">Password <span class="text-red-500">*</span></label>
                <input type="password" 
                       id="create_password" 
                       name="password" 
                       required 
                       minlength="6"
                       placeholder="Minimal 6 karakter" 
                       class="w-full px-3.5 py-2 bg-surface-pearl border border-hairline rounded-xl text-xs text-ink-body focus:border-primary focus:bg-canvas-pure outline-none">
            </div>

            <div>
                <label for="create_role" class="block text-xs font-bold text-ink-body mb-1">Role Pengguna <span class="text-red-500">*</span></label>
                <select id="create_role" 
                        name="role" 
                        required 
                        class="w-full px-3.5 py-2 bg-surface-pearl border border-hairline rounded-xl text-xs text-ink-body focus:border-primary focus:bg-canvas-pure outline-none cursor-pointer">
                    <option value="admin">Admin (Operasional Jadwal)</option>
                    <option value="superadmin">Superadmin (Akses Penuh Master & Akun)</option>
                </select>
            </div>

            <div class="pt-3 border-t border-hairline flex items-center justify-end gap-2">
                <button type="button" 
                        onclick="closeTambahModal()" 
                        class="px-4 py-2 rounded-xl bg-surface-pearl hover:bg-surface-container-low text-xs font-semibold text-ink-body transition-colors">
                    Batal
                </button>
                <button type="submit" 
                        id="submitTambahUser"
                        class="px-5 py-2 rounded-xl bg-primary hover:bg-primary-container text-white text-xs font-semibold shadow-xs transition-all active:scale-95">
                    Simpan Akun
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: Edit Akun Pengguna -->
<div id="modalEditUser" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-ink-body/50 backdrop-blur-xs animate-in fade-in">
    <div class="bg-canvas-pure rounded-2xl border border-hairline shadow-xl max-w-md w-full overflow-hidden animate-scale-up">
        <div class="px-6 py-4 border-b border-hairline flex items-center justify-between bg-surface-pearl">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[20px]">edit</span>
                <h3 class="font-bold text-sm text-ink-body">Ubah Data Akun Pengguna</h3>
            </div>
            <button onclick="closeEditModal()" class="text-ink-muted hover:text-ink-body">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <form id="formEditUser" method="POST" action="" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="edit_nama" class="block text-xs font-bold text-ink-body mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" 
                       id="edit_nama" 
                       name="nama" 
                       required 
                       class="w-full px-3.5 py-2 bg-surface-pearl border border-hairline rounded-xl text-xs text-ink-body focus:border-primary focus:bg-canvas-pure outline-none">
            </div>

            <div>
                <label for="edit_email" class="block text-xs font-bold text-ink-body mb-1">Alamat Email <span class="text-red-500">*</span></label>
                <input type="email" 
                       id="edit_email" 
                       name="email" 
                       required 
                       class="w-full px-3.5 py-2 bg-surface-pearl border border-hairline rounded-xl text-xs text-ink-body focus:border-primary focus:bg-canvas-pure outline-none">
            </div>

            <div>
                <label for="edit_role" class="block text-xs font-bold text-ink-body mb-1">Role Pengguna <span class="text-red-500">*</span></label>
                <select id="edit_role" 
                        name="role" 
                        required 
                        class="w-full px-3.5 py-2 bg-surface-pearl border border-hairline rounded-xl text-xs text-ink-body focus:border-primary focus:bg-canvas-pure outline-none cursor-pointer">
                    <option value="admin">Admin (Operasional Jadwal)</option>
                    <option value="superadmin">Superadmin (Akses Penuh Master & Akun)</option>
                </select>
            </div>

            <div class="pt-3 border-t border-hairline flex items-center justify-end gap-2">
                <button type="button" 
                        onclick="closeEditModal()" 
                        class="px-4 py-2 rounded-xl bg-surface-pearl hover:bg-surface-container-low text-xs font-semibold text-ink-body transition-colors">
                    Batal
                </button>
                <button type="submit" 
                        id="submitEditUser"
                        class="px-5 py-2 rounded-xl bg-primary hover:bg-primary-container text-white text-xs font-semibold shadow-xs transition-all active:scale-95">
                    Perbarui Akun
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: Reset Password -->
<div id="modalResetPassword" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-ink-body/50 backdrop-blur-xs animate-in fade-in">
    <div class="bg-canvas-pure rounded-2xl border border-hairline shadow-xl max-w-md w-full overflow-hidden animate-scale-up">
        <div class="px-6 py-4 border-b border-hairline flex items-center justify-between bg-surface-pearl">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-amber-600 text-[20px]">key</span>
                <h3 class="font-bold text-sm text-ink-body">Reset Password Pengguna</h3>
            </div>
            <button onclick="closeResetPasswordModal()" class="text-ink-muted hover:text-ink-body">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <form id="formResetPassword" method="POST" action="" class="p-6 space-y-4">
            @csrf
            <div>
                <p class="text-xs text-ink-muted mb-2">
                    Mengatur ulang kata sandi untuk akun: <strong id="reset_user_name" class="text-ink-body font-bold"></strong>
                </p>
            </div>

            <div>
                <label for="reset_password" class="block text-xs font-bold text-ink-body mb-1">Password Baru <span class="text-red-500">*</span></label>
                <input type="password" 
                       id="reset_password" 
                       name="password" 
                       required 
                       minlength="6"
                       placeholder="Minimal 6 karakter" 
                       class="w-full px-3.5 py-2 bg-surface-pearl border border-hairline rounded-xl text-xs text-ink-body focus:border-primary focus:bg-canvas-pure outline-none">
            </div>

            <div>
                <label for="reset_password_confirmation" class="block text-xs font-bold text-ink-body mb-1">Ulangi Password Baru <span class="text-red-500">*</span></label>
                <input type="password" 
                       id="reset_password_confirmation" 
                       name="password_confirmation" 
                       required 
                       minlength="6"
                       placeholder="Ketik ulang password baru" 
                       class="w-full px-3.5 py-2 bg-surface-pearl border border-hairline rounded-xl text-xs text-ink-body focus:border-primary focus:bg-canvas-pure outline-none">
            </div>

            <div class="pt-3 border-t border-hairline flex items-center justify-end gap-2">
                <button type="button" 
                        onclick="closeResetPasswordModal()" 
                        class="px-4 py-2 rounded-xl bg-surface-pearl hover:bg-surface-container-low text-xs font-semibold text-ink-body transition-colors">
                    Batal
                </button>
                <button type="submit" 
                        id="submitResetPassword"
                        class="px-5 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold shadow-xs transition-all active:scale-95">
                    Reset Password
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: Hapus Akun Pengguna -->
<div id="modalDeleteUser" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-ink-body/50 backdrop-blur-xs animate-in fade-in">
    <div class="bg-canvas-pure rounded-2xl border border-hairline shadow-xl max-w-sm w-full overflow-hidden animate-scale-up">
        <div class="p-6 text-center space-y-3">
            <div class="w-12 h-12 rounded-full bg-red-100 text-error flex items-center justify-center mx-auto">
                <span class="material-symbols-outlined text-[26px]">warning</span>
            </div>
            <h3 class="text-base font-bold text-ink-body">Konfirmasi Hapus Akun</h3>
            <p class="text-xs text-ink-muted">
                Apakah Anda yakin ingin menghapus akun pengguna <strong id="delete_user_name" class="text-ink-body"></strong>? Tindakan ini tidak dapat dibatalkan.
            </p>
        </div>

        <form id="formDeleteUser" method="POST" action="" class="p-4 bg-surface-pearl border-t border-hairline flex items-center justify-end gap-2">
            @csrf
            @method('DELETE')
            <button type="button" 
                    onclick="closeDeleteModal()" 
                    class="px-4 py-2 rounded-xl bg-canvas-pure hover:bg-surface-container-low text-xs font-semibold text-ink-body border border-hairline transition-colors">
                Batal
            </button>
            <button type="submit" 
                    id="submitDeleteUser"
                    class="px-5 py-2 rounded-xl bg-error hover:bg-red-700 text-white text-xs font-semibold shadow-xs transition-all active:scale-95">
                Hapus Akun
            </button>
        </form>
    </div>
</div>

<script>
    // Modal Functions
    function openTambahModal() {
        const m = document.getElementById('modalTambahUser');
        m.classList.remove('hidden');
        m.classList.add('flex');
    }
    function closeTambahModal() {
        const m = document.getElementById('modalTambahUser');
        m.classList.add('hidden');
        m.classList.remove('flex');
    }

    function openEditModal(user) {
        document.getElementById('formEditUser').action = '/superadmin/user/' + user.id;
        document.getElementById('edit_nama').value = user.nama;
        document.getElementById('edit_email').value = user.email;
        document.getElementById('edit_role').value = user.role;
        const m = document.getElementById('modalEditUser');
        m.classList.remove('hidden');
        m.classList.add('flex');
    }
    function closeEditModal() {
        const m = document.getElementById('modalEditUser');
        m.classList.add('hidden');
        m.classList.remove('flex');
    }

    function openResetPasswordModal(userId, userName) {
        document.getElementById('formResetPassword').action = '/superadmin/user/' + userId + '/reset-password';
        document.getElementById('reset_user_name').textContent = userName;
        document.getElementById('reset_password').value = '';
        document.getElementById('reset_password_confirmation').value = '';
        const m = document.getElementById('modalResetPassword');
        m.classList.remove('hidden');
        m.classList.add('flex');
    }
    function closeResetPasswordModal() {
        const m = document.getElementById('modalResetPassword');
        m.classList.add('hidden');
        m.classList.remove('flex');
    }

    function openDeleteModal(userId, userName) {
        document.getElementById('formDeleteUser').action = '/superadmin/user/' + userId;
        document.getElementById('delete_user_name').textContent = userName;
        const m = document.getElementById('modalDeleteUser');
        m.classList.remove('hidden');
        m.classList.add('flex');
    }
    function closeDeleteModal() {
        const m = document.getElementById('modalDeleteUser');
        m.classList.add('hidden');
        m.classList.remove('flex');
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

    // Close on backdrop click or Escape key
    window.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeTambahModal();
            closeEditModal();
            closeResetPasswordModal();
            closeDeleteModal();
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
