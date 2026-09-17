<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Sistem Penjadwalan Kelas Elips Academy</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            orange: '#F28E2B',
                            hover: '#E07D1C',
                            light: '#FFF7ED',
                            dark: '#904D00',
                        },
                        surface: {
                            canvas: '#F5F5F7',
                            pearl: '#FAFAFC',
                            card: '#FFFFFF',
                        },
                        ink: {
                            body: '#1D1D1F',
                            muted: '#6E6E73',
                            subtle: '#86868B',
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
<body class="min-h-screen flex flex-col bg-surface-canvas text-ink-body antialiased selection:bg-brand-orange selection:text-white">

    <!-- Top Minimal Navigation Bar -->
    <header class="w-full flex items-center justify-between px-6 lg:px-12 py-5">
        <div class="flex items-center space-x-2"></div>
        <div class="flex items-center space-x-2.5">
            <span class="text-[13px] font-medium text-ink-muted hidden sm:inline-block">Sistem Penjadwalan Kelas</span>
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-1 flex items-center justify-center px-4 py-8">
        <div class="relative w-full max-w-[440px]">
            <!-- Subtle Ambient Glow -->
            <div class="absolute -top-16 left-1/2 -translate-x-1/2 w-72 h-72 bg-brand-orange/15 rounded-full blur-3xl pointer-events-none -z-10"></div>

            <!-- Main Card -->
            <div class="w-full bg-surface-card rounded-[22px] shadow-sm border border-black/[0.04] p-6 sm:p-[38px] transition-all duration-300">
                
                <!-- Brand Header -->
                <div class="flex flex-col items-center text-center">
                    <div class="w-full flex items-center justify-center mb-4">
                        <img src="{{ asset('images/logo.png') }}" 
                             alt="Elips Academy" 
                             class="h-11 sm:h-12 w-auto object-contain transition-transform duration-300 hover:scale-[1.02]">
                    </div>

                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#F6F3F5] text-ink-muted text-[11px] font-semibold tracking-wider uppercase mb-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-brand-orange"></span>
                        Sistem Penjadwalan Terpadu
                    </div>

                    <h1 class="text-2xl font-bold text-ink-body tracking-tight">
                        Masuk ke Akun Anda
                    </h1>
                    <p class="mt-1.5 text-sm text-ink-muted max-w-[320px] leading-relaxed">
                        Gunakan kredensial akademik Anda untuk mengakses jadwal kuliah dan kelas.
                    </p>
                </div>

                <!-- Session Flash Messages -->
                @if (session('success'))
                    <div class="mt-5 rounded-xl p-3.5 text-sm flex items-start gap-2.5 bg-emerald-50 border border-emerald-200 text-emerald-800">
                        <span class="material-symbols-outlined text-[19px] text-emerald-600 shrink-0 mt-0.5">check_circle</span>
                        <div class="flex-1 text-xs leading-relaxed font-medium">
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mt-5 rounded-xl p-3.5 text-sm flex items-start gap-2.5 bg-red-50 border border-red-200 text-red-800" role="alert">
                        <span class="material-symbols-outlined text-[19px] text-red-600 shrink-0 mt-0.5">error</span>
                        <div class="flex-1 text-xs leading-relaxed font-medium">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Authentication Form -->
                <form class="mt-6 flex flex-col space-y-4" method="POST" action="{{ route('login') }}" id="loginForm" onsubmit="handleFormSubmit(event)">
                    @csrf

                    <!-- Email Field -->
                    <div class="flex flex-col space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label class="text-[13px] font-medium text-ink-body" for="email">
                                Email
                            </label>
                            <span class="text-[11px] font-medium text-ink-subtle">Wajib</span>
                        </div>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3.5 text-ink-subtle text-[20px] pointer-events-none select-none">mail</span>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autocomplete="email" 
                                   autofocus 
                                   placeholder="Masukkan email Anda" 
                                   class="w-full h-11 pl-10 pr-3.5 bg-surface-pearl rounded-xl text-sm text-ink-body border border-slate-200 placeholder:text-ink-subtle/70 outline-none transition-all duration-200 focus:bg-white focus:border-brand-orange focus:ring-2 focus:ring-brand-orange/20">
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="flex flex-col space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label class="text-[13px] font-medium text-ink-body" for="password">
                                Kata Sandi
                            </label>
                            <span class="text-[11px] font-medium text-ink-subtle">Wajib</span>
                        </div>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3.5 text-ink-subtle text-[20px] pointer-events-none select-none">lock</span>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   required 
                                   autocomplete="current-password" 
                                   placeholder="Masukkan kata sandi portal" 
                                   class="w-full h-11 pl-10 pr-11 bg-surface-pearl rounded-xl text-sm text-ink-body border border-slate-200 placeholder:text-ink-subtle/70 outline-none transition-all duration-200 focus:bg-white focus:border-brand-orange focus:ring-2 focus:ring-brand-orange/20">
                            <button type="button" 
                                    id="togglePasswordBtn" 
                                    onclick="togglePasswordVisibility()" 
                                    aria-label="Tampilkan / Sembunyikan Kata Sandi" 
                                    class="absolute right-2.5 w-7 h-7 flex items-center justify-center rounded-lg text-ink-subtle hover:text-ink-body hover:bg-slate-100 transition-colors">
                                <span class="material-symbols-outlined text-[19px]" id="passwordToggleIcon">visibility</span>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me Checkbox -->
                    <div class="flex items-center justify-between pt-1">
                        <label class="relative flex items-center gap-2.5 cursor-pointer select-none group">
                            <input type="checkbox" id="remember" name="remember" value="1" {{ old('remember') ? 'checked' : '' }} class="peer sr-only">
                            <div class="w-4 h-4 rounded border border-slate-300 bg-surface-pearl peer-checked:bg-brand-orange peer-checked:border-brand-orange peer-focus-visible:ring-2 peer-focus-visible:ring-brand-orange/30 flex items-center justify-center transition-all">
                                <span class="material-symbols-outlined text-white text-[13px] opacity-0 peer-checked:opacity-100 font-bold transition-opacity">check</span>
                            </div>
                            <span class="text-[13px] text-ink-muted group-hover:text-ink-body transition-colors">
                                Ingat Saya di perangkat ini
                            </span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            id="submitBtn" 
                            class="w-full h-11 mt-2 rounded-full bg-brand-orange hover:bg-brand-hover active:scale-[0.99] text-white font-medium text-[15px] flex items-center justify-center gap-2 shadow-sm shadow-brand-orange/20 transition-all duration-150 cursor-pointer">
                        <span id="btnText">Masuk ke Dashboard</span>
                        <span class="material-symbols-outlined text-[19px]" id="btnIcon">arrow_forward</span>
                    </button>
                </form>

                <!-- Security Notice -->
                <div class="mt-6 pt-5 border-t border-slate-100 flex items-center justify-center gap-2 text-center text-ink-subtle">
                    <span class="material-symbols-outlined text-[16px] text-emerald-600 shrink-0">verified_user</span>
                    <p class="text-[11px] font-medium leading-tight">
                        Akses terbatas hanya untuk staf Elips Academy.
                    </p>
                </div>
            </div>

            <!-- Supplementary Micro-Footer Metadata -->
            <div class="mt-4 text-center flex items-center justify-center gap-4 text-[11px] text-ink-subtle font-medium">
                <span class="flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Sistem Operasional Normal
                </span>
                <span>•</span>
                <span>Protokol Enkripsi TLS 1.3</span>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full px-6 lg:px-12 py-4 flex flex-col sm:flex-row items-center justify-between gap-2 text-ink-muted text-xs">
        <span>&copy; {{ date('Y') }} Elips Academy Inc. All rights reserved.</span>
        <span>Sistem Penjadwalan Terpadu v1.0</span>
    </footer>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('passwordToggleIcon');
            if (!passwordInput || !icon) return;

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.textContent = 'visibility_off';
            } else {
                passwordInput.type = 'password';
                icon.textContent = 'visibility';
            }
        }

        function handleFormSubmit(event) {
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnIcon = document.getElementById('btnIcon');

            if (submitBtn && btnText && btnIcon) {
                btnText.textContent = "Memverifikasi...";
                btnIcon.textContent = "sync";
                btnIcon.classList.add("animate-spin");
                submitBtn.disabled = true;
                submitBtn.classList.add("opacity-85", "cursor-wait");
                submitBtn.form.submit();
            }
        }
    </script>
</body>
</html>
