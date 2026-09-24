<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Ubah Jadwal Kelas - {{ $jadwal->nama_kelas }} - Elips Academy</title>
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
            <a href="{{ auth()->user()?->role === 'superadmin' ? route('superadmin.dashboard') : route('admin.dashboard', array_filter(['cabang_id' => $jadwal->cabang_id, 'tanggal' => $jadwal->tanggal ? $jadwal->tanggal->toDateString() : null])) }}" class="flex items-center gap-3 group">
                <img src="{{ asset('images/logo.png') }}" alt="Elips Academy" class="h-8 w-auto object-contain transition-transform duration-200 group-hover:scale-105">
                <span class="sr-only">Elips Academy</span>
                <span class="text-[10px] text-secondary font-semibold uppercase tracking-wider bg-orange-50 border border-orange-200/60 px-2 py-0.5 rounded-full hidden sm:inline">CABANG {{ strtoupper($jadwal->cabang->nama_cabang ?? 'BUDURAN') }}</span>
            </a>

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

    <!-- Konten Utama: Form Ubah Jadwal -->
    <main class="flex-1 max-w-6xl mx-auto w-full px-4 py-8 sm:py-10 flex flex-col items-center">
        <div class="w-full max-w-[700px] flex flex-col gap-6">

            <!-- Tombol Navigasi Kembali -->
            <div>
                <a href="{{ request('redirect_to', route('admin.jadwal.show', $jadwal->id)) }}" 
                   class="inline-flex items-center gap-1.5 text-xs font-semibold text-ink-muted hover:text-ink-body bg-white hover:bg-surface-pearl border border-hairline px-3.5 py-1.5 rounded-full shadow-2xs transition-all">
                    <span class="material-symbols-outlined text-base">arrow_back</span>
                    <span>Kembali ke Detail</span>
                </a>
            </div>

            <!-- Header Judul Form -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 text-center sm:text-left">
                <div class="space-y-1">
                    <h1 class="text-2xl sm:text-[28px] font-bold text-[#1D1D1F] tracking-tight">Ubah Jadwal Kelas</h1>
                    <p class="text-sm text-ink-muted">Perbarui data jadwal tatap muka untuk Cabang {{ $jadwal->cabang->nama_cabang ?? 'Buduran' }}.</p>
                </div>
                <div class="self-center sm:self-auto">
                    @if($jadwal->status === 'dibatalkan')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                            <span class="material-symbols-outlined text-[14px]">cancel</span>
                            <span>Dibatalkan</span>
                        </span>
                    @elseif($jadwal->status === 'selesai')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold">
                            <span class="material-symbols-outlined text-[14px]">check_circle</span>
                            <span>Selesai</span>
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold">
                            <span class="material-symbols-outlined text-[14px]">schedule</span>
                            <span>Terjadwal</span>
                        </span>
                    @endif
                </div>
            </div>

            <!-- Alert Bentrok / Error Validasi -->
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-2xl p-4 sm:p-5 flex items-start gap-3 shadow-xs animate-shake">
                    <span class="material-symbols-outlined text-red-600 text-2xl shrink-0 mt-0.5">error</span>
                    <div class="flex-1 text-sm text-red-800">
                        <div class="font-bold text-red-900 mb-1">
                            @if($errors->has('tentor_id') || $errors->has('ruangan'))
                                Peringatan: Konflik Jadwal Terdeteksi!
                            @else
                                Mohon periksa kembali isian form:
                            @endif
                        </div>
                        <ul class="list-disc list-inside space-y-1 text-xs sm:text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Kartu Form Utama -->
            <div class="bg-white rounded-2xl border border-hairline shadow-[0_4px_24px_rgba(0,0,0,0.03)] p-6 sm:p-8">
                <form action="{{ route('admin.jadwal.update', $jadwal->id) }}" method="POST" class="space-y-5" id="formUbahJadwal">
                    @csrf
                    @method('PUT')

                    <!-- 1. Cabang & Program Kursus -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Cabang -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-[#1D1D1F] tracking-tight" for="selectCabang">
                                Cabang <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="cabang_id" 
                                        id="selectCabang" 
                                        class="w-full h-11 pl-3.5 pr-10 rounded-xl bg-[#F5F5F7] border @error('cabang_id') border-red-500 @else border-transparent @enderror text-[#1D1D1F] text-sm focus:bg-white focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all appearance-none cursor-pointer" 
                                        required>
                                    @foreach($cabangs as $cabang)
                                        <option value="{{ $cabang->id }}" {{ (old('cabang_id', $jadwal->cabang_id) == $cabang->id) ? 'selected' : '' }}>
                                            Cabang {{ $cabang->nama_cabang }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-ink-subtle pointer-events-none text-[20px]">expand_more</span>
                            </div>
                            @error('cabang_id')
                                <p class="text-[11px] text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Program Kursus -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-[#1D1D1F] tracking-tight" for="selectProgram">
                                Program Kursus <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="program_id" 
                                        id="selectProgram" 
                                        class="w-full h-11 pl-3.5 pr-10 rounded-xl bg-[#F5F5F7] border @error('program_id') border-red-500 @else border-transparent @enderror text-[#1D1D1F] text-sm focus:bg-white focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all appearance-none cursor-pointer" 
                                        required>
                                    @foreach($programs as $prog)
                                        <option value="{{ $prog->id }}" {{ old('program_id', $jadwal->program_id) == $prog->id ? 'selected' : '' }}>
                                            {{ $prog->nama_program }} ({{ ucfirst($prog->kategori) }})
                                        </option>
                                    @endforeach
                                </select>
                                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-ink-subtle pointer-events-none text-[20px]">expand_more</span>
                            </div>
                            @error('program_id')
                                <p class="text-[11px] text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- 2. Kode Kelas & Nomor Pertemuan -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-[#1D1D1F] tracking-tight" for="inputKodeKelas">
                                Nama / Kode Kelas <span class="text-red-500">*</span>
                            </label>
                            <input name="nama_kelas" 
                                   id="inputKodeKelas" 
                                   type="text" 
                                   value="{{ old('nama_kelas', $jadwal->nama_kelas) }}" 
                                   placeholder="Misal: MO-003" 
                                   class="w-full h-11 px-3.5 rounded-xl bg-[#F5F5F7] border @error('nama_kelas') border-red-500 @else border-transparent @enderror text-[#1D1D1F] text-sm placeholder:text-ink-subtle focus:bg-white focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all" 
                                   required/>
                            @error('nama_kelas')
                                <p class="text-[11px] text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-[#1D1D1F] tracking-tight" for="inputPertemuan">
                                Nomor Pertemuan <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center gap-2">
                                <button type="button" id="btnMinus" class="w-11 h-11 shrink-0 rounded-xl bg-[#F5F5F7] hover:bg-[#EAEAEA] active:scale-95 text-ink-body flex items-center justify-center transition-all border border-hairline">
                                    <span class="material-symbols-outlined text-[18px]">remove</span>
                                </button>
                                <input name="pertemuan" 
                                       id="inputPertemuan" 
                                       type="number" 
                                       min="1" 
                                       max="100" 
                                       value="{{ old('pertemuan', $jadwal->pertemuan ?? 1) }}" 
                                       class="w-full h-11 text-center font-semibold rounded-xl bg-[#F5F5F7] border @error('pertemuan') border-red-500 @else border-transparent @enderror text-[#1D1D1F] text-sm focus:bg-white focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all" 
                                       required/>
                                <button type="button" id="btnPlus" class="w-11 h-11 shrink-0 rounded-xl bg-[#F5F5F7] hover:bg-[#EAEAEA] active:scale-95 text-ink-body flex items-center justify-center transition-all border border-hairline">
                                    <span class="material-symbols-outlined text-[18px]">add</span>
                                </button>
                            </div>
                            @error('pertemuan')
                                <p class="text-[11px] text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- 3. Jenis & Mode Kelas -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Jenis Kelas (Pills Segmen) -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-[#1D1D1F] tracking-tight">
                                Jenis Kelas <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-3 gap-1.5 bg-[#F5F5F7] p-1 rounded-xl">
                                @php $oldJenis = old('jenis_kelas', $jadwal->jenis_kelas); @endphp
                                <label class="cursor-pointer">
                                    <input type="radio" name="jenis_kelas" value="private" class="peer sr-only" {{ $oldJenis === 'private' ? 'checked' : '' }}/>
                                    <div class="py-2.5 px-2 text-center text-xs font-medium rounded-lg text-ink-muted peer-checked:bg-white peer-checked:text-[#1D1D1F] peer-checked:font-semibold peer-checked:shadow-sm transition-all">
                                        Private
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="jenis_kelas" value="rombel" class="peer sr-only" {{ $oldJenis === 'rombel' ? 'checked' : '' }}/>
                                    <div class="py-2.5 px-2 text-center text-xs font-medium rounded-lg text-ink-muted peer-checked:bg-white peer-checked:text-[#1D1D1F] peer-checked:font-semibold peer-checked:shadow-sm transition-all">
                                        Rombel
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="jenis_kelas" value="business" class="peer sr-only" {{ $oldJenis === 'business' ? 'checked' : '' }}/>
                                    <div class="py-2.5 px-2 text-center text-xs font-medium rounded-lg text-ink-muted peer-checked:bg-white peer-checked:text-[#1D1D1F] peer-checked:font-semibold peer-checked:shadow-sm transition-all">
                                        Business
                                    </div>
                                </label>
                            </div>
                            @error('jenis_kelas')
                                <p class="text-[11px] text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Mode Kelas (Offline / Online) -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-[#1D1D1F] tracking-tight">
                                Mode Kelas <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-1.5 bg-[#F5F5F7] p-1 rounded-xl">
                                @php $oldMode = old('mode_kelas', $jadwal->mode_kelas ?? 'offline'); @endphp
                                <label class="cursor-pointer">
                                    <input type="radio" name="mode_kelas" value="offline" class="peer sr-only" {{ $oldMode === 'offline' ? 'checked' : '' }}/>
                                    <div class="py-2.5 px-3 text-center text-xs font-medium rounded-lg text-ink-muted peer-checked:bg-white peer-checked:text-[#1D1D1F] peer-checked:font-semibold peer-checked:shadow-sm transition-all flex items-center justify-center gap-1.5">
                                        <span class="material-symbols-outlined text-[15px]">domain</span>
                                        Offline
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="mode_kelas" value="online" class="peer sr-only" {{ $oldMode === 'online' ? 'checked' : '' }}/>
                                    <div class="py-2.5 px-3 text-center text-xs font-medium rounded-lg text-ink-muted peer-checked:bg-white peer-checked:text-[#1D1D1F] peer-checked:font-semibold peer-checked:shadow-sm transition-all flex items-center justify-center gap-1.5">
                                        <span class="material-symbols-outlined text-[15px]">videocam</span>
                                        Online
                                    </div>
                                </label>
                            </div>
                            @error('mode_kelas')
                                <p class="text-[11px] text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- 4. Tanggal & Waktu -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-1">
                        <div class="sm:col-span-1 space-y-1.5">
                            <label class="block text-xs font-semibold text-[#1D1D1F] tracking-tight" for="inputTanggal">
                                Tanggal <span class="text-red-500">*</span>
                            </label>
                            <input name="tanggal" 
                                   id="inputTanggal" 
                                   type="date" 
                                   value="{{ old('tanggal', $jadwal->tanggal ? $jadwal->tanggal->toDateString() : '') }}" 
                                   class="w-full h-11 px-3 rounded-xl bg-[#F5F5F7] border @error('tanggal') border-red-500 @else border-transparent @enderror text-[#1D1D1F] text-sm focus:bg-white focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all" 
                                   required/>
                            @error('tanggal')
                                <p class="text-[11px] text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-[#1D1D1F] tracking-tight" for="inputJamMulai">
                                Jam Mulai <span class="text-red-500">*</span>
                            </label>
                            <input name="jam_mulai" 
                                   id="inputJamMulai" 
                                   type="time" 
                                   value="{{ old('jam_mulai', substr($jadwal->jam_mulai, 0, 5)) }}" 
                                   class="w-full h-11 px-3 rounded-xl bg-[#F5F5F7] border @error('jam_mulai') border-red-500 @else border-transparent @enderror text-[#1D1D1F] text-sm focus:bg-white focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all" 
                                   required/>
                            @error('jam_mulai')
                                <p class="text-[11px] text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-[#1D1D1F] tracking-tight" for="inputJamSelesai">
                                Jam Selesai <span class="text-red-500">*</span>
                            </label>
                            <input name="jam_selesai" 
                                   id="inputJamSelesai" 
                                   type="time" 
                                   value="{{ old('jam_selesai', substr($jadwal->jam_selesai, 0, 5)) }}" 
                                   class="w-full h-11 px-3 rounded-xl bg-[#F5F5F7] border @error('jam_selesai') border-red-500 @else border-transparent @enderror text-[#1D1D1F] text-sm focus:bg-white focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all" 
                                   required/>
                            @error('jam_selesai')
                                <p class="text-[11px] text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- 5. Ruangan & Tentor -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-[#1D1D1F] tracking-tight" for="selectRuangan">
                                Ruangan <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="ruangan" 
                                        id="selectRuangan" 
                                        class="w-full h-11 pl-3.5 pr-9 rounded-xl bg-[#F5F5F7] border @error('ruangan') border-red-500 @else border-transparent @enderror text-[#1D1D1F] text-sm focus:bg-white focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all appearance-none cursor-pointer" 
                                        required>
                                    @foreach($defaultRuangan as $r)
                                        <option value="{{ $r }}" {{ old('ruangan', $jadwal->ruangan) === $r ? 'selected' : '' }}>{{ $r }}</option>
                                    @endforeach
                                </select>
                                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-ink-subtle pointer-events-none text-[20px]">expand_more</span>
                            </div>
                            @error('ruangan')
                                <p class="text-[11px] text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-[#1D1D1F] tracking-tight" for="selectTentor">
                                Tentor Pengajar <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="tentor_id" 
                                        id="selectTentor" 
                                        class="w-full h-11 pl-3.5 pr-9 rounded-xl bg-[#F5F5F7] border @error('tentor_id') border-red-500 @else border-transparent @enderror text-[#1D1D1F] text-sm focus:bg-white focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all appearance-none cursor-pointer" 
                                        required>
                                    @foreach($tentors as $t)
                                        <option value="{{ $t->id }}" {{ old('tentor_id', $jadwal->tentor_id) == $t->id ? 'selected' : '' }}>
                                            {{ $t->nama }} ({{ $t->keahlian }})
                                        </option>
                                    @endforeach
                                </select>
                                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-ink-subtle pointer-events-none text-[20px]">expand_more</span>
                            </div>
                            @error('tentor_id')
                                <p class="text-[11px] text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- 6. Status Jadwal -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-[#1D1D1F] tracking-tight" for="selectStatus">
                            Status Jadwal <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select name="status" 
                                    id="selectStatus" 
                                    class="w-full h-11 pl-3.5 pr-9 rounded-xl bg-[#F5F5F7] border @error('status') border-red-500 @else border-transparent @enderror text-[#1D1D1F] text-sm focus:bg-white focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all appearance-none cursor-pointer" 
                                    required>
                                <option value="terjadwal" {{ old('status', $jadwal->status) === 'terjadwal' ? 'selected' : '' }}>Terjadwal (Aktif)</option>
                                <option value="selesai" {{ old('status', $jadwal->status) === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="dibatalkan" {{ old('status', $jadwal->status) === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-ink-subtle pointer-events-none text-[20px]">expand_more</span>
                        </div>
                        @error('status')
                            <p class="text-[11px] text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 7. Catatan (Opsional) -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-semibold text-[#1D1D1F] tracking-tight" for="inputCatatan">
                                Catatan Sesi &amp; Materi
                            </label>
                            <span class="text-[11px] text-ink-subtle">Opsional</span>
                        </div>
                        <input name="catatan" 
                               id="inputCatatan" 
                               type="text" 
                               value="{{ old('catatan', $jadwal->catatan) }}" 
                               placeholder="Misal: Materi latihan VLOOKUP &amp; Pivot Table" 
                               class="w-full h-11 px-3.5 rounded-xl bg-[#F5F5F7] border border-transparent text-[#1D1D1F] text-sm placeholder:text-ink-subtle focus:bg-white focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all"/>
                    </div>

                    <!-- Tombol Aksi Bawah -->
                    <div class="pt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between border-t border-[#F0F0F2] gap-3">
                        <div>
                            @if($jadwal->status !== 'dibatalkan')
                                <button type="button" 
                                        id="btnOpenBatalModal"
                                        class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-red-600 hover:bg-red-50 px-3.5 py-2.5 rounded-full transition-all">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                    <span>Batalkan Jadwal</span>
                                </button>
                            @endif
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.jadwal.show', $jadwal->id) }}" 
                               class="px-5 py-2.5 rounded-full text-xs sm:text-sm font-semibold text-ink-muted hover:text-ink-body hover:bg-gray-100 transition-all text-center">
                                Batal
                            </a>
                            <button type="submit" 
                                    id="btnSimpanPerubahan"
                                    class="px-6 py-2.5 rounded-full bg-primary-container hover:bg-brand-hover active:scale-[0.98] text-white text-xs sm:text-sm font-semibold shadow-sm transition-all flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[18px]">check</span>
                                <span>Simpan Perubahan</span>
                            </button>
                        </div>
                    </div>
                </form>
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
            const btnMinus = document.getElementById('btnMinus');
            const btnPlus = document.getElementById('btnPlus');
            const inputPertemuan = document.getElementById('inputPertemuan');

            if (btnMinus && inputPertemuan) {
                btnMinus.addEventListener('click', () => {
                    let val = parseInt(inputPertemuan.value) || 1;
                    if (val > 1) {
                        inputPertemuan.value = val - 1;
                    }
                });
            }

            if (btnPlus && inputPertemuan) {
                btnPlus.addEventListener('click', () => {
                    let val = parseInt(inputPertemuan.value) || 1;
                    inputPertemuan.value = val + 1;
                });
            }

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
