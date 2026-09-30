# Issue #19 — Fitur Kirim Pesan WhatsApp Otomatis ke Tentor

## Tujuan

Menambahkan fitur auto-message WhatsApp pada halaman detail jadwal:
1. Ketika admin/superadmin mengklik nomor HP tentor, otomatis membuka WhatsApp dengan pesan reminder yang sudah terisi template
2. Superadmin dapat mengubah template pesan melalui halaman Pengaturan
3. Tampilan tetap sama — hanya nomor HP berubah jadi link yang bisa diklik

## Latar Belakang

Saat ini di halaman detail jadwal (`show.blade.php`), nomor HP tentor hanya ditampilkan sebagai teks biasa. Admin harus menyalin nomor secara manual, membuka WhatsApp, lalu mengetik pesan reminder. Fitur ini mengotomatisasi proses tersebut sehingga admin cukup klik sekali.

**Dependency:** Tidak ada (standalone feature)

## Template Default

```
Selamat {sesi} kak
Izin Mengingatkan kak, besok hari {hari} Tanggal {tanggal} ada kelas {program} pada pukul {jam} di Elips Academy {cabang}. Terimakasih🙏
```

**Placeholder yang didukung:**

| Placeholder | Keterangan | Contoh |
|---|---|---|
| `{sesi}` | Pagi/Siang/Sore/Malam berdasarkan waktu saat ini | Sore |
| `{hari}` | Hari pelaksanaan jadwal | Kamis |
| `{tanggal}` | Tanggal format DD/MM/YYYY | 01/10/2026 |
| `{jam}` | Jam mulai kelas (HH:mm) | 09:00 |
| `{cabang}` | Nama cabang | Buduran |
| `{tentor}` | Nama tentor | Kak Rini |
| `{program}` | Nama program | Microsoft Office |

## Scope

### A. Migration — Tabel `settings`

File baru: `database/migrations/xxxx_create_settings_table.php`

```php
Schema::create('settings', function (Blueprint $table) {
    $table->id();
    $table->string('key')->unique();
    $table->text('value');
    $table->string('description')->nullable();
    $table->timestamps();
});
```

### B. Model `Setting`

File baru: `app/Models/Setting.php`

- Field fillable: `key`, `value`, `description`
- Static helper `Setting::get($key, $default)` — ambil value berdasarkan key
- Static helper `Setting::set($key, $value)` — update atau create setting

### C. Seeder — Default Template WA

File baru: `database/seeders/SettingSeeder.php`

```php
Setting::updateOrCreate(
    ['key' => 'wa_template'],
    [
        'value' => "Selamat {sesi} kak\nIzin Mengingatkan kak, besok hari {hari} Tanggal {tanggal} ada kelas {program} pada pukul {jam} di Elips Academy {cabang}. Terimakasih🙏",
        'description' => 'Template pesan WhatsApp reminder ke tentor',
    ]
);
```

### D. Update `AdminJadwalController` — Generate WhatsApp Link

File: `app/Http/Controllers/AdminJadwalController.php`

Pada method `show()`:
1. Ambil template dari `Setting::get('wa_template', '...(default)...')`
2. Tentukan `{sesi}` berdasarkan waktu saat ini (Pagi < 12:00, Siang < 15:00, Sore < 18:00, Malam >= 18:00)
3. Replace semua placeholder dengan data dari `$jadwal`
4. Format nomor HP: `08xx` → `628xx` (format internasional WhatsApp)
5. Generate link: `https://wa.me/{phone}?text={urlencode(message)}`
6. Kirim `$waLink` ke view

```php
private function generateWhatsAppLink(Jadwal $jadwal): string
{
    $template = Setting::get('wa_template',
        "Selamat {sesi} kak\nIzin Mengingatkan kak, besok hari {hari} Tanggal {tanggal} ada kelas {program} pada pukul {jam} di Elips Academy {cabang}. Terimakasih🙏"
    );

    $hour = (int) now()->format('H');
    if ($hour < 12) $sesi = 'Pagi';
    elseif ($hour < 15) $sesi = 'Siang';
    elseif ($hour < 18) $sesi = 'Sore';
    else $sesi = 'Malam';

    $replacements = [
        '{sesi}'    => $sesi,
        '{hari}'    => Carbon::parse($jadwal->tanggal)->locale('id')->isoFormat('dddd'),
        '{tanggal}' => Carbon::parse($jadwal->tanggal)->format('d/m/Y'),
        '{jam}'     => substr($jadwal->jam_mulai, 0, 5),
        '{cabang}'  => $jadwal->cabang->nama_cabang ?? 'Cabang',
        '{tentor}'  => $jadwal->tentor->nama ?? 'Tentor',
        '{program}' => $jadwal->program->nama_program ?? 'Program',
    ];

    $message = str_replace(array_keys($replacements), array_values($replacements), $template);

    $phone = preg_replace('/[^0-9]/', '', $jadwal->tentor->no_hp ?? '');
    if (str_starts_with($phone, '0')) {
        $phone = '62' . substr($phone, 1);
    }

    return 'https://wa.me/' . $phone . '?text=' . urlencode($message);
}
```

### E. Update View `show.blade.php` — Link WhatsApp

File: `resources/views/admin/jadwal/show.blade.php`

Ubah bagian tampilan nomor HP tentor (line 252-257):

```diff
 @if($jadwal->tentor && $jadwal->tentor->no_hp)
-    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-white border border-hairline text-xs font-semibold text-ink-body shadow-2xs">
-        <span class="material-symbols-outlined text-[16px] text-emerald-600">call</span>
-        <span>{{ $jadwal->tentor->no_hp }}</span>
-    </div>
+    <a href="{{ $waLink }}"
+       target="_blank" rel="noopener"
+       class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-xs font-semibold text-emerald-700 shadow-2xs transition-all"
+       title="Kirim pesan WhatsApp ke tentor">
+        <span class="material-symbols-outlined text-[16px] text-emerald-600">chat</span>
+        <span>{{ $jadwal->tentor->no_hp }}</span>
+        <span class="material-symbols-outlined text-[14px]">open_in_new</span>
+    </a>
 @endif
```

### F. Controller Pengaturan Template (Superadmin Only)

File baru: `app/Http/Controllers/SuperadminSettingController.php`

- `index()` — Tampilkan halaman pengaturan dengan textarea template + daftar placeholder
- `updateWaTemplate()` — Simpan template baru ke tabel `settings`

### G. View Pengaturan Template (Superadmin Only)

File baru: `resources/views/superadmin/setting/index.blade.php`

Komponen:
- Textarea editable untuk template pesan
- Tabel referensi placeholder yang tersedia (`{sesi}`, `{hari}`, `{tanggal}`, `{jam}`, `{cabang}`, `{tentor}`, `{program}`)
- Tombol "Simpan Template"
- Flash message sukses setelah update
- Desain konsisten dengan halaman superadmin lainnya (sidebar, header, dll)

### H. Routes Baru

File: `routes/web.php`

```php
// Di dalam group middleware('role:superadmin')
Route::get('/superadmin/setting', [SuperadminSettingController::class, 'index'])
    ->name('superadmin.setting.index');
Route::put('/superadmin/setting/wa-template', [SuperadminSettingController::class, 'updateWaTemplate'])
    ->name('superadmin.setting.update-wa-template');
```

### I. Navigasi — Menu Pengaturan di Sidebar Superadmin

Tambahkan menu "Pengaturan" di sidebar semua halaman superadmin:
- Posisi: paling bawah (setelah Manajemen Akun)
- Icon: `settings`
- Link ke: `route('superadmin.setting.index')`
- Active state saat di halaman pengaturan

## Files yang Terpengaruh

| File | Aksi |
|------|------|
| `database/migrations/xxxx_create_settings_table.php` | **Baru** — tabel settings |
| `database/seeders/SettingSeeder.php` | **Baru** — default template WA |
| `app/Models/Setting.php` | **Baru** — model Setting |
| `app/Http/Controllers/SuperadminSettingController.php` | **Baru** — CRUD template |
| `resources/views/superadmin/setting/index.blade.php` | **Baru** — UI pengaturan template |
| `app/Http/Controllers/AdminJadwalController.php` | **Edit** — tambah `generateWhatsAppLink()` di `show()` |
| `resources/views/admin/jadwal/show.blade.php` | **Edit** — ubah span nomor HP → link WA |
| `routes/web.php` | **Edit** — tambah route setting |
| `resources/views/superadmin/dashboard.blade.php` | **Edit** — tambah menu Pengaturan di sidebar |
| `resources/views/superadmin/cabang/index.blade.php` | **Edit** — tambah menu Pengaturan di sidebar |
| `resources/views/superadmin/jadwal/index.blade.php` | **Edit** — tambah menu Pengaturan di sidebar |
| `resources/views/superadmin/program/index.blade.php` | **Edit** — tambah menu Pengaturan di sidebar |
| `resources/views/superadmin/tentor/index.blade.php` | **Edit** — tambah menu Pengaturan di sidebar |
| `resources/views/superadmin/user/index.blade.php` | **Edit** — tambah menu Pengaturan di sidebar |
| `resources/views/superadmin/ruangan/index.blade.php` | **Edit** — tambah menu Pengaturan di sidebar |

## Post-Implementation

```bash
php artisan migrate
php artisan db:seed --class=SettingSeeder
```

## Acceptance Criteria

- [x] Klik nomor HP tentor di halaman detail jadwal → WhatsApp terbuka dengan pesan terisi otomatis
- [x] Pesan sesuai template: sesi, hari, tanggal, program, jam, cabang terisi benar
- [x] Nomor format `08xxxx` otomatis dikonversi ke `628xxxx`
- [x] Superadmin bisa mengubah template dari halaman Pengaturan
- [x] Template baru langsung berlaku setelah disimpan
- [x] Placeholder `{sesi}` mengikuti waktu saat admin mengklik (bukan waktu jadwal)
- [x] Jika tentor tidak punya nomor HP, link WA tidak ditampilkan
- [x] Menu "Pengaturan" muncul di sidebar superadmin
- [x] Tidak ada regression — halaman detail jadwal tetap berfungsi normal
