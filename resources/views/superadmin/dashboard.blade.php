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
                        brand: {
                            orange: '#F28E2B',
                            hover: '#E07D1C',
                            light: '#FFF7ED',
                        }
                    }
                }
            }
        };
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-[#F5F5F7] text-slate-800 antialiased">
    <!-- Navigation Header -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo & Brand -->
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Elips Academy" class="h-9 w-auto">
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-purple-50 text-purple-700 border border-purple-200">
                        Area Superadmin
                    </span>
                </div>

                <!-- User Profile & Logout -->
                <div class="flex items-center space-x-4">
                    <div class="hidden sm:flex flex-col text-right">
                        <span class="text-sm font-semibold text-slate-900">{{ $user->nama }}</span>
                        <span class="text-xs text-slate-500">{{ $user->email }}</span>
                    </div>

                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800 uppercase tracking-wider">
                        {{ $user->role }}
                    </span>

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" 
                                id="logoutBtn"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors cursor-pointer border border-red-200">
                            <span class="material-symbols-outlined text-[17px]">logout</span>
                            <span>Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Welcome Banner -->
        <div class="bg-gradient-to-r from-purple-600 via-indigo-600 to-brand-orange rounded-2xl p-6 sm:p-8 text-white shadow-sm mb-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">Selamat Datang, {{ $user->nama }}!</h1>
                    <p class="text-purple-100 mt-1 text-sm sm:text-base">Anda memiliki hak akses penuh sebagai <strong>Superadmin</strong>.</p>
                </div>
                <div class="flex items-center gap-2 bg-white/20 backdrop-blur-sm px-3.5 py-1.5 rounded-xl text-xs font-medium">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    Hak Akses Penuh
                </div>
            </div>
        </div>

        <!-- Info / Placeholder Notice -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm mb-6">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-2xl">admin_panel_settings</span>
                </div>
                <div class="flex-1">
                    <h2 class="text-lg font-bold text-slate-900">Dashboard Superadmin (Placeholder)</h2>
                    <p class="text-sm text-slate-600 mt-1">
                        Autentikasi role-based berhasil. Area ini memiliki hak untuk mengelola Cabang, Program Kursus, Tentor, Akun Pengguna, dan Jadwal Kelas. Modul-modul ini akan diimplementasikan secara modular pada issue berikutnya.
                    </p>
                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                        <div class="p-3 rounded-lg bg-slate-50 border border-slate-100">
                            <span class="text-slate-500 font-medium">Hak Akses:</span>
                            <span class="font-semibold text-slate-800 ml-1">Seluruh Modul & Data Master</span>
                        </div>
                        <div class="p-3 rounded-lg bg-slate-50 border border-slate-100">
                            <span class="text-slate-500 font-medium">Manajemen Pengguna:</span>
                            <span class="font-semibold text-purple-700 ml-1">Aktif</span>
                        </div>
                        <div class="p-3 rounded-lg bg-slate-50 border border-slate-100">
                            <span class="text-slate-500 font-medium">Status Akun:</span>
                            <span class="font-semibold text-emerald-600 ml-1">Superuser Aktif</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-4 text-center text-xs text-slate-500">
        &copy; {{ date('Y') }} Elips Academy Inc. Hak cipta dilindungi.
    </footer>
</body>
</html>
