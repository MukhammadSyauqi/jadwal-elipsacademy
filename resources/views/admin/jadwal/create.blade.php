<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Tambah Jadwal Kelas - Elips Academy</title>
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
            <a href="{{ route('admin.dashboard', ['cabang_id' => $selectedCabang->id ?? 1, 'tanggal' => $selectedDate]) }}" class="flex items-center gap-3 group">
                <div class="w-8 h-8 rounded-full bg-primary-container flex items-center justify-center text-white font-bold text-base shadow-sm">
                    E
                </div>
                <div class="flex flex-col">
                    <span class="text-base font-bold text-[#1D1D1F] tracking-tight leading-none group-hover:text-primary transition-colors">Elips Academy</span>
                    <span class="text-[10px] text-secondary font-semibold uppercase tracking-wider mt-0.5">CABANG {{ strtoupper($selectedCabang->nama_cabang ?? 'BUDURAN') }}</span>
                </div>
            </a>

            <!-- Tengah: Tombol Kembali -->
            <div class="flex items-center">
                <a href="{{ route('admin.dashboard', ['cabang_id' => $selectedCabang->id ?? 1, 'tanggal' => $selectedDate]) }}" 
                   class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-medium text-ink-muted hover:text-ink-body bg-gray-100 hover:bg-gray-200/80 px-3.5 py-1.5 rounded-full transition-all">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    <span>Kembali ke Jadwal</span>
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

    <!-- Konten Utama: Form Tambah Jadwal -->
    <main class="flex-1 max-w-6xl mx-auto w-full px-4 py-8 sm:py-10 flex flex-col items-center">
        <div class="w-full max-w-[700px] flex flex-col gap-6">

            <!-- Header Judul Form -->
            <div class="text-center sm:text-left space-y-1">
                <h1 class="text-2xl sm:text-[28px] font-bold text-[#1D1D1F] tracking-tight">Tambah Jadwal Kelas</h1>
                <p class="text-sm text-ink-muted">Isi data jadwal tatap muka untuk Cabang {{ $selectedCabang->nama_cabang ?? 'Buduran' }}.</p>
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
                                Mohon lengkapi dan periksa isian form:
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
                <form action="{{ route('admin.jadwal.store') }}" method="POST" class="space-y-5" id="formTambahJadwal">
                    @csrf

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
                                        <option value="{{ $cabang->id }}" {{ (old('cabang_id', $selectedCabang->id ?? '') == $cabang->id) ? 'selected' : '' }}>
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
                                    <option value="" disabled {{ old('program_id') ? '' : 'selected' }}>Pilih program kursus...</option>
                                    @foreach($programs as $prog)
                                        <option value="{{ $prog->id }}" {{ old('program_id') == $prog->id ? 'selected' : '' }}>
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
                                   value="{{ old('nama_kelas') }}" 
                                   placeholder="Misal: MO-003 atau Web-01" 
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
                                       value="{{ old('pertemuan', 1) }}" 
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

                    <!-- 3. Jenis Kelas (Pills Segmen) -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-[#1D1D1F] tracking-tight">
                            Jenis Kelas <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-3 gap-2 bg-[#F5F5F7] p-1 rounded-xl">
                            @php $oldJenis = old('jenis_kelas', 'private'); @endphp
                            <label class="cursor-pointer">
                                <input type="radio" name="jenis_kelas" value="private" class="peer sr-only" {{ $oldJenis === 'private' ? 'checked' : '' }}/>
                                <div class="py-2.5 px-3 text-center text-xs font-medium rounded-lg text-ink-muted peer-checked:bg-white peer-checked:text-[#1D1D1F] peer-checked:font-semibold peer-checked:shadow-sm transition-all">
                                    Private
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="jenis_kelas" value="rombel" class="peer sr-only" {{ $oldJenis === 'rombel' ? 'checked' : '' }}/>
                                <div class="py-2.5 px-3 text-center text-xs font-medium rounded-lg text-ink-muted peer-checked:bg-white peer-checked:text-[#1D1D1F] peer-checked:font-semibold peer-checked:shadow-sm transition-all">
                                    Rombel
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="jenis_kelas" value="business" class="peer sr-only" {{ $oldJenis === 'business' ? 'checked' : '' }}/>
                                <div class="py-2.5 px-3 text-center text-xs font-medium rounded-lg text-ink-muted peer-checked:bg-white peer-checked:text-[#1D1D1F] peer-checked:font-semibold peer-checked:shadow-sm transition-all">
                                    Business
                                </div>
                            </label>
                        </div>
                        @error('jenis_kelas')
                            <p class="text-[11px] text-red-600 font-medium">{{ $message }}</p>
                        @enderror
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
                                   value="{{ old('tanggal', $selectedDate) }}" 
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
                                   value="{{ old('jam_mulai', '09:00') }}" 
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
                                   value="{{ old('jam_selesai', '11:00') }}" 
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
                                    <option value="" disabled {{ old('ruangan') ? '' : 'selected' }}>Pilih Ruangan...</option>
                                    @foreach($defaultRuangan as $r)
                                        <option value="{{ $r }}" {{ old('ruangan') === $r ? 'selected' : '' }}>{{ $r }}</option>
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
                                    <option value="" disabled {{ old('tentor_id') ? '' : 'selected' }}>Pilih Tentor Pengajar...</option>
                                    @foreach($tentors as $t)
                                        <option value="{{ $t->id }}" {{ old('tentor_id') == $t->id ? 'selected' : '' }}>
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

                    <!-- 6. Catatan (Opsional) -->
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
                               value="{{ old('catatan') }}" 
                               placeholder="Misal: Materi latihan VLOOKUP &amp; Pivot Table" 
                               class="w-full h-11 px-3.5 rounded-xl bg-[#F5F5F7] border border-transparent text-[#1D1D1F] text-sm placeholder:text-ink-subtle focus:bg-white focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all"/>
                    </div>

                    <!-- Tombol Aksi Bawah -->
                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-[#F0F0F2]">
                        <a href="{{ route('admin.dashboard', ['cabang_id' => $selectedCabang->id ?? 1, 'tanggal' => $selectedDate]) }}" 
                           class="px-5 py-2.5 rounded-full text-xs sm:text-sm font-semibold text-ink-muted hover:text-ink-body hover:bg-gray-100 transition-all text-center">
                            Batal
                        </a>
                        <button type="submit" 
                                id="btnSimpanJadwal"
                                class="px-6 py-2.5 rounded-full bg-primary-container hover:bg-brand-hover active:scale-[0.98] text-white text-xs sm:text-sm font-semibold shadow-sm transition-all flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px]">check</span>
                            <span>Simpan Jadwal</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer Minimalis -->
    <footer class="w-full border-t border-[#EDEDF0] bg-white py-4 mt-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-ink-subtle">
            <div class="flex items-center gap-2">
                <span class="font-medium text-[#1D1D1F]">Elips Academy</span>
                <span>•</span>
                <span>Sistem Informasi Manajemen Cabang {{ $selectedCabang->nama_cabang ?? 'Buduran' }}</span>
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
        })();
    </script>
</body>
</html>
