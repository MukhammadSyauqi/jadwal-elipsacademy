<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Detail Jadwal Kelas - {{ $jadwal->nama_kelas }} - Elips Academy</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-container': '#f28e2b',
                        'primary': '#904d00',
                        'secondary': '#944a00',
                        'ink-body': '#1D1D1F',
                        'ink-muted': '#6E6E73',
                        'ink-subtle': '#86868B',
                        'surface-pearl': '#FAFAFC',
                        'hairline': '#E5E5EA',
                        'brand-hover': '#e07d1a',
                        'schedule-conflict': '#EF4444',
                        'schedule-verified': '#10B981',
                        'schedule-pending': '#6366F1',
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                },
            },
        };
    </script>
    <style>
        ::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="bg-[#F8F9FA] font-sans text-ink-body antialiased min-h-screen flex flex-col justify-between">

    <!-- Header Atas Minimalis -->
    <header class="sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-[#EDEDF0]">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <!-- Brand & Lokasi Cabang -->
            <a href="{{ route('admin.dashboard', ['cabang_id' => $jadwal->cabang_id, 'tanggal' => $jadwal->tanggal->toDateString()]) }}" class="flex items-center gap-3 group">
                <div class="w-8 h-8 rounded-full bg-primary-container flex items-center justify-center text-white font-bold text-base shadow-sm">
                    E
                </div>
                <div class="flex flex-col">
                    <span class="text-base font-bold text-[#1D1D1F] tracking-tight leading-none group-hover:text-primary transition-colors">Elips Academy</span>
                    <span class="text-[10px] text-secondary font-semibold uppercase tracking-wider mt-0.5">CABANG {{ strtoupper($jadwal->cabang->nama_cabang ?? 'BUDURAN') }}</span>
                </div>
            </a>

            <!-- Tengah: Tombol Kembali -->
            <div class="flex items-center">
                <a href="{{ route('admin.dashboard', ['cabang_id' => $jadwal->cabang_id, 'tanggal' => $jadwal->tanggal->toDateString()]) }}" 
                   class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-medium text-ink-muted hover:text-ink-body bg-gray-100 hover:bg-gray-200/80 px-3 sm:px-3.5 py-1.5 rounded-full transition-all">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    <span class="hidden sm:inline">Kembali ke Jadwal</span>
                    <span class="sm:hidden">Kembali</span>
                </a>
            </div>

            <!-- Kanan: Profil & Keluar -->
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2.5">
                    <div class="text-right hidden sm:block leading-tight">
                        <div class="text-xs font-semibold text-[#1D1D1F]">Halo, {{ $user->nama ?? 'Admin' }}</div>
                        <div class="text-[11px] text-ink-subtle uppercase">{{ $user->role ?? 'Admin' }}</div>
                    </div>
                    <div class="w-8 h-8 rounded-full bg-orange-100 text-primary-container flex items-center justify-center font-bold text-xs shadow-sm">
                        {{ strtoupper(substr($user->nama ?? 'A', 0, 2)) }}
                    </div>
                </div>
                <div class="h-4 w-px bg-gray-200 hidden sm:block"></div>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-ink-subtle hover:text-red-600 p-1.5 rounded-lg hover:bg-gray-100 transition-colors" title="Keluar">
                        <span class="material-symbols-outlined text-[20px] block">logout</span>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Konten Utama: Detail Jadwal -->
    <main class="flex-1 max-w-6xl mx-auto w-full px-4 py-8 sm:py-10 flex flex-col items-center">
        <div class="w-full max-w-[760px] flex flex-col gap-6">

            <!-- Flash Message Sukses -->
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4 flex items-center gap-3 shadow-xs">
                    <span class="material-symbols-outlined text-emerald-600 text-2xl">check_circle</span>
                    <div class="text-sm font-semibold">{{ session('success') }}</div>
                </div>
            @endif

            <!-- Kartu Detail Utama -->
            <div class="bg-white rounded-2xl border border-hairline shadow-[0_4px_24px_rgba(0,0,0,0.03)] p-6 sm:p-8 flex flex-col gap-6">

                <!-- Header Kartu: Status & Judul Kelas -->
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 pb-6 border-b border-hairline">
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold uppercase tracking-wider
                                @if($jadwal->jenis_kelas === 'private') bg-blue-50 text-blue-700 border border-blue-200
                                @elseif($jadwal->jenis_kelas === 'business') bg-amber-50 text-amber-800 border border-amber-200
                                @else bg-purple-50 text-purple-700 border border-purple-200
                                @endif">
                                Kelas {{ ucfirst($jadwal->jenis_kelas) }}
                            </span>

                            <span class="px-2.5 py-1 rounded-full bg-gray-100 text-ink-body text-xs font-semibold">
                                Pertemuan Ke-{{ $jadwal->pertemuan ?? 1 }}
                            </span>

                            <span class="text-xs text-ink-subtle">
                                Program: <strong class="text-ink-body">{{ $jadwal->program->kategori ?? 'Kursus' }}</strong>
                            </span>
                        </div>

                        <h1 class="text-2xl sm:text-3xl font-bold text-ink-body tracking-tight">
                            {{ $jadwal->nama_kelas }}
                        </h1>
                        <p class="text-base text-primary font-medium">
                            {{ $jadwal->program->nama_program ?? 'Program Kursus' }}
                        </p>
                    </div>

                    <!-- Status Badge -->
                    <div class="shrink-0">
                        @if($jadwal->status === 'dibatalkan')
                            <span class="px-3 py-1.5 rounded-full bg-red-100 text-red-700 text-xs font-bold flex items-center gap-1.5 border border-red-200">
                                <span class="material-symbols-outlined text-[16px]">cancel</span>
                                Dibatalkan
                            </span>
                        @elseif($jadwal->status === 'selesai')
                            <span class="px-3 py-1.5 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold flex items-center gap-1.5 border border-emerald-200">
                                <span class="material-symbols-outlined text-[16px]">check_circle</span>
                                Selesai
                            </span>
                        @elseif($jadwal->display_status === 'sedang_berlangsung')
                            <span class="px-3 py-1.5 rounded-full bg-amber-100 text-amber-800 text-xs font-bold flex items-center gap-1.5 border border-amber-200">
                                <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                                Sedang Berlangsung
                            </span>
                        @else
                            <span class="px-3 py-1.5 rounded-full bg-indigo-50 text-indigo-700 text-xs font-bold flex items-center gap-1.5 border border-indigo-200">
                                <span class="material-symbols-outlined text-[16px]">schedule</span>
                                Terjadwal
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Grid Informasi: Waktu & Lokasi -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Kartu Waktu Pelaksanaan -->
                    <div class="bg-[#F8F9FA] rounded-xl p-5 flex flex-col gap-4 border border-hairline">
                        <div class="flex items-center gap-2 text-primary font-semibold text-xs uppercase tracking-wider">
                            <span class="material-symbols-outlined text-[20px]">calendar_today</span>
                            <span>Waktu &amp; Sesi Belajar</span>
                        </div>
                        <div class="space-y-3 pl-7">
                            <div>
                                <span class="block text-[11px] font-semibold text-ink-subtle uppercase tracking-wider">Tanggal Pelaksanaan</span>
                                <span class="text-sm font-bold text-ink-body">
                                    {{ \Carbon\Carbon::parse($jadwal->tanggal)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                                </span>
                            </div>
                            <div>
                                <span class="block text-[11px] font-semibold text-ink-subtle uppercase tracking-wider">Jam Belajar</span>
                                <span class="text-sm font-bold text-ink-body">
                                    {{ $jadwal->formatted_jam }} WIB 
                                    <span class="text-xs font-normal text-ink-muted">(Durasi {{ $jadwal->durasi }})</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Kartu Ruangan & Lokasi -->
                    <div class="bg-[#F8F9FA] rounded-xl p-5 flex flex-col gap-4 border border-hairline">
                        <div class="flex items-center gap-2 text-primary font-semibold text-xs uppercase tracking-wider">
                            <span class="material-symbols-outlined text-[20px]">meeting_room</span>
                            <span>Ruangan &amp; Cabang</span>
                        </div>
                        <div class="space-y-3 pl-7">
                            <div>
                                <span class="block text-[11px] font-semibold text-ink-subtle uppercase tracking-wider">Ruangan Kelas</span>
                                <span class="text-sm font-bold text-ink-body">{{ $jadwal->ruangan ?? 'Ruang Kelas' }}</span>
                            </div>
                            <div>
                                <span class="block text-[11px] font-semibold text-ink-subtle uppercase tracking-wider">Lokasi Cabang</span>
                                <span class="text-sm font-bold text-ink-body">Cabang {{ $jadwal->cabang->nama_cabang ?? 'Buduran' }}</span>
                                <span class="block text-xs text-ink-muted">{{ $jadwal->cabang->alamat ?? 'Sidoarjo' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kartu Tentor Pengajar -->
                <div class="bg-[#F8F9FA] rounded-xl p-5 flex flex-col gap-3 border border-hairline">
                    <div class="flex items-center gap-2 text-primary font-semibold text-xs uppercase tracking-wider">
                        <span class="material-symbols-outlined text-[20px]">person_pin_circle</span>
                        <span>Tentor Pengajar</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pl-7">
                        <div class="space-y-1">
                            <div class="text-base font-bold text-ink-body">{{ $jadwal->tentor->nama ?? 'Tentor' }}</div>
                            <div class="text-xs text-ink-muted">
                                Keahlian: <span class="font-medium text-ink-body">{{ $jadwal->tentor->keahlian ?? '-' }}</span>
                            </div>
                        </div>
                        @if($jadwal->tentor && $jadwal->tentor->no_hp)
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-white border border-hairline text-xs font-semibold text-ink-body shadow-2xs">
                                <span class="material-symbols-outlined text-[16px] text-emerald-600">call</span>
                                <span>{{ $jadwal->tentor->no_hp }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Kartu Catatan Sesi -->
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-ink-body font-semibold text-xs uppercase tracking-wider">
                        <span class="material-symbols-outlined text-[18px] text-ink-subtle">edit_note</span>
                        <span>Catatan Sesi &amp; Materi Pertemuan</span>
                    </div>
                    <div class="bg-surface-pearl rounded-xl p-4 sm:p-5 text-sm text-ink-body leading-relaxed border border-hairline">
                        @if(!empty($jadwal->catatan))
                            {{ $jadwal->catatan }}
                        @else
                            <span class="text-ink-subtle italic">Tidak ada catatan tambahan untuk pertemuan ini.</span>
                        @endif
                    </div>
                </div>

                <!-- Tombol Aksi Bawah -->
                <div class="flex flex-col-reverse sm:flex-row sm:items-center justify-between pt-4 border-t border-hairline gap-3">
                    <!-- Tombol Hapus / Batalkan Jadwal -->
                    <div>
                        @if($jadwal->status !== 'dibatalkan')
                            <button type="button" 
                                    id="btnOpenBatalModal"
                                    class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-full text-xs sm:text-sm font-semibold text-red-600 hover:bg-red-50 transition-all border border-transparent hover:border-red-200">
                                <span class="material-symbols-outlined text-[18px]">delete_outline</span>
                                <span>Batalkan Jadwal</span>
                            </button>
                        @else
                            <span class="text-xs text-ink-subtle italic">Jadwal ini telah berstatus dibatalkan.</span>
                        @endif
                    </div>

                    <!-- Tombol Navigasi Kembali & Ubah -->
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.dashboard', ['cabang_id' => $jadwal->cabang_id, 'tanggal' => $jadwal->tanggal->toDateString()]) }}" 
                           class="px-5 py-2.5 rounded-full text-xs sm:text-sm font-semibold text-ink-muted hover:text-ink-body bg-gray-100 hover:bg-gray-200 transition-all">
                            Kembali
                        </a>
                        <a href="{{ route('admin.jadwal.edit', $jadwal->id) }}" 
                           id="btnUbahJadwal"
                           class="inline-flex items-center gap-1.5 px-6 py-2.5 rounded-full bg-primary-container hover:bg-brand-hover active:scale-[0.98] text-white text-xs sm:text-sm font-semibold shadow-sm transition-all">
                            <span class="material-symbols-outlined text-[18px]">edit</span>
                            <span>Ubah Jadwal</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <!-- Modal Konfirmasi Pembatalan Jadwal -->
    <div id="modalBatal" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-xs hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-sm w-full p-6 text-center shadow-xl space-y-4 animate-in fade-in zoom-in duration-200">
            <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto">
                <span class="material-symbols-outlined text-[28px]">warning</span>
            </div>
            <div>
                <h3 class="text-lg font-bold text-[#1D1D1F]">Batalkan Jadwal Kelas?</h3>
                <p class="text-xs text-ink-muted mt-1.5 leading-relaxed">
                    Jadwal <strong>{{ $jadwal->nama_kelas }}</strong> akan diubah statusnya menjadi <em>Dibatalkan</em>. Data historis tetap disimpan dalam sistem.
                </p>
            </div>
            <div class="pt-2 flex items-center gap-2">
                <button type="button" 
                        id="btnCloseBatalModal"
                        class="flex-1 py-2.5 rounded-full bg-gray-100 hover:bg-gray-200 text-ink-body text-xs sm:text-sm font-semibold transition-all">
                    Batal
                </button>
                <form action="{{ route('admin.jadwal.batal', $jadwal->id) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" 
                            id="btnConfirmBatal"
                            class="w-full py-2.5 rounded-full bg-red-600 hover:bg-red-700 text-white text-xs sm:text-sm font-semibold transition-all shadow-sm">
                        Ya, Batalkan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer Minimalis -->
    <footer class="w-full border-t border-[#EDEDF0] bg-white py-4 mt-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-ink-subtle">
            <div class="flex items-center gap-2">
                <span class="font-medium text-[#1D1D1F]">Elips Academy</span>
                <span>•</span>
                <span>Sistem Informasi Manajemen Cabang {{ $jadwal->cabang->nama_cabang ?? 'Buduran' }}</span>
            </div>
            <div>© {{ date('Y') }} Elips Academy Indonesia. Hak Cipta Dilindungi.</div>
        </div>
    </footer>

    <script>
        (function() {
            const btnOpen = document.getElementById('btnOpenBatalModal');
            const btnClose = document.getElementById('btnCloseBatalModal');
            const modal = document.getElementById('modalBatal');

            if (btnOpen && modal) {
                btnOpen.addEventListener('click', () => {
                    modal.classList.remove('hidden');
                });
            }

            if (btnClose && modal) {
                btnClose.addEventListener('click', () => {
                    modal.classList.add('hidden');
                });
            }

            if (modal) {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        modal.classList.add('hidden');
                    }
                });
            }
        })();
    </script>
</body>
</html>
