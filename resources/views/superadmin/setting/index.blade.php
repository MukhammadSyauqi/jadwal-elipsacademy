<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan - Superadmin - Elips Academy</title>
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
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold bg-primary-container text-white shadow-[0_1px_4px_rgba(242,142,43,0.25)] transition-all">
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
                    <span class="text-primary font-bold">PENGATURAN</span>
                    <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                    <span class="text-ink-body font-semibold">TEMPLATE</span>
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

            @if($errors->any())
                <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm shadow-xs animate-fade-in">
                    <div class="flex items-center gap-2 mb-2 font-semibold">
                        <span class="material-symbols-outlined text-error">error</span>
                        <span>Terdapat kesalahan:</span>
                    </div>
                    <ul class="list-disc list-inside text-xs space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-ink-body tracking-tight flex items-center gap-2.5">
                        <span class="material-symbols-outlined text-primary-container text-[28px]">settings</span>
                        Pengaturan Sistem
                    </h1>
                    <p class="text-xs text-ink-muted mt-1">Kelola konfigurasi dan template pesan untuk seluruh cabang.</p>
                </div>
            </div>

            <!-- WhatsApp Template Section -->
            <div class="bg-white rounded-2xl border border-hairline shadow-[0_2px_16px_rgba(0,0,0,0.03)] overflow-hidden">
                <!-- Section Header -->
                <div class="px-6 py-5 border-b border-hairline bg-gradient-to-r from-emerald-50/50 to-white">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shadow-sm">
                            <span class="material-symbols-outlined text-[22px]">chat</span>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-ink-body">Template Pesan WhatsApp</h2>
                            <p class="text-xs text-ink-muted mt-0.5">Template pesan reminder yang dikirim ke tentor saat admin mengklik nomor WA di halaman detail jadwal.</p>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('superadmin.setting.update-wa-template') }}" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="space-y-2">
                        <label for="wa_template" class="block text-sm font-semibold text-ink-body">
                            Template Pesan
                        </label>
                        <textarea 
                            id="wa_template" 
                            name="wa_template" 
                            rows="5"
                            class="w-full px-4 py-3 rounded-xl border border-hairline bg-surface-pearl text-sm text-ink-body placeholder:text-ink-subtle focus:outline-none focus:ring-2 focus:ring-primary-container focus:border-primary-container transition-all resize-y font-mono leading-relaxed"
                            placeholder="Masukkan template pesan WhatsApp..."
                        >{{ old('wa_template', $waTemplate) }}</textarea>
                        <p class="text-[11px] text-ink-subtle">Gunakan placeholder di bawah untuk mengisi data secara otomatis. Maksimum 2000 karakter.</p>
                    </div>

                    <!-- Placeholder Reference Table -->
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 text-xs font-semibold text-ink-body uppercase tracking-wider">
                            <span class="material-symbols-outlined text-[16px] text-ink-subtle">info</span>
                            <span>Placeholder yang Tersedia</span>
                        </div>
                        <div class="overflow-x-auto rounded-xl border border-hairline">
                            <table class="w-full text-xs">
                                <thead>
                                    <tr class="bg-surface-container-low border-b border-hairline">
                                        <th class="px-4 py-2.5 text-left font-semibold text-ink-body uppercase tracking-wider">Placeholder</th>
                                        <th class="px-4 py-2.5 text-left font-semibold text-ink-body uppercase tracking-wider">Keterangan</th>
                                        <th class="px-4 py-2.5 text-left font-semibold text-ink-body uppercase tracking-wider">Contoh</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-hairline">
                                    <tr class="hover:bg-surface-pearl/60 transition-colors">
                                        <td class="px-4 py-2.5 font-mono font-semibold text-emerald-700">{sesi}</td>
                                        <td class="px-4 py-2.5 text-ink-muted">Pagi/Siang/Sore/Malam berdasarkan waktu saat ini</td>
                                        <td class="px-4 py-2.5 text-ink-body font-medium">Sore</td>
                                    </tr>
                                    <tr class="hover:bg-surface-pearl/60 transition-colors">
                                        <td class="px-4 py-2.5 font-mono font-semibold text-emerald-700">{hari}</td>
                                        <td class="px-4 py-2.5 text-ink-muted">Hari pelaksanaan jadwal</td>
                                        <td class="px-4 py-2.5 text-ink-body font-medium">Kamis</td>
                                    </tr>
                                    <tr class="hover:bg-surface-pearl/60 transition-colors">
                                        <td class="px-4 py-2.5 font-mono font-semibold text-emerald-700">{tanggal}</td>
                                        <td class="px-4 py-2.5 text-ink-muted">Tanggal format DD/MM/YYYY</td>
                                        <td class="px-4 py-2.5 text-ink-body font-medium">01/10/2026</td>
                                    </tr>
                                    <tr class="hover:bg-surface-pearl/60 transition-colors">
                                        <td class="px-4 py-2.5 font-mono font-semibold text-emerald-700">{jam}</td>
                                        <td class="px-4 py-2.5 text-ink-muted">Jam mulai kelas (HH:mm)</td>
                                        <td class="px-4 py-2.5 text-ink-body font-medium">09:00</td>
                                    </tr>
                                    <tr class="hover:bg-surface-pearl/60 transition-colors">
                                        <td class="px-4 py-2.5 font-mono font-semibold text-emerald-700">{cabang}</td>
                                        <td class="px-4 py-2.5 text-ink-muted">Nama cabang</td>
                                        <td class="px-4 py-2.5 text-ink-body font-medium">Buduran</td>
                                    </tr>
                                    <tr class="hover:bg-surface-pearl/60 transition-colors">
                                        <td class="px-4 py-2.5 font-mono font-semibold text-emerald-700">{tentor}</td>
                                        <td class="px-4 py-2.5 text-ink-muted">Nama tentor</td>
                                        <td class="px-4 py-2.5 text-ink-body font-medium">Kak Rini</td>
                                    </tr>
                                    <tr class="hover:bg-surface-pearl/60 transition-colors">
                                        <td class="px-4 py-2.5 font-mono font-semibold text-emerald-700">{program}</td>
                                        <td class="px-4 py-2.5 text-ink-muted">Nama program kursus</td>
                                        <td class="px-4 py-2.5 text-ink-body font-medium">Microsoft Office</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="submit" 
                                id="btnSimpanTemplate"
                                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-primary-container hover:bg-brand-hover active:scale-[0.98] text-white text-sm font-semibold shadow-sm transition-all">
                            <span class="material-symbols-outlined text-[18px]">save</span>
                            <span>Simpan Template</span>
                        </button>
                    </div>
                </form>
            </div>

        </main>

        <!-- Footer -->
        <footer class="mt-auto border-t border-hairline px-4 sm:px-6 lg:px-8 py-4 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-ink-subtle">
            <div class="flex items-center gap-2">
                <span class="font-medium text-ink-body">Elips Academy</span>
                <span>•</span>
                <span>Sistem Informasi Manajemen</span>
            </div>
            <div>© {{ date('Y') }} Elips Academy Indonesia. Hak Cipta Dilindungi.</div>
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
