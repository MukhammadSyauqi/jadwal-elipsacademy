# Issue #13 — Konsistensi Tailwind Config & Final Polish

## Tujuan

Menyamakan konfigurasi design token dan melakukan final polish di seluruh aplikasi agar tampilan benar-benar konsisten dan rapi.

## Scope

### A. Konsistensi Tailwind Config

Saat ini setiap file Blade mendefinisikan `tailwind.config` secara inline dengan perbedaan kecil antar halaman (ada yang punya token tertentu, ada yang tidak). Hal ini menyebabkan inkonsistensi warna dan spacing.

**Yang harus dilakukan:**
- Audit semua `tailwind.config` yang ada di setiap file Blade
- Buat satu set **color tokens lengkap** yang menjadi standar dan pastikan semua halaman menggunakan set yang sama
- Pastikan token yang dipakai di halaman admin dan superadmin identik

### B. Superadmin Dashboard & Jadwal — Mode Kelas

Setelah Issue #9 menambahkan `mode_kelas` ke database, pastikan halaman **superadmin** juga menampilkan informasi ini:
- Superadmin dashboard — jadwal list hari ini: tampilkan badge Online/Offline
- Superadmin jadwal index (tabel): tambahkan kolom atau badge mode kelas
- `SuperadminDashboardController` dan `SuperadminJadwalController`: pastikan data `mode_kelas` tersedia

### C. Final Visual Audit

Lakukan pengecekan akhir pada semua halaman:
- Semua perubahan dari Issue #9–#12 terintegrasi dengan baik
- Tidak ada tampilan yang "pecah" atau tidak konsisten setelah semua perubahan
- Responsive layout tetap berfungsi di mobile, tablet, dan desktop
- Flash message (success/error) tampil dan bisa ditutup dengan benar

## Files yang Terpengaruh

| File | Aksi |
|------|------|
| Semua file `.blade.php` | Samakan `tailwind.config` |
| `resources/views/superadmin/dashboard.blade.php` | Tampilkan badge mode_kelas di jadwal list |
| `resources/views/superadmin/jadwal/index.blade.php` | Tampilkan mode_kelas di tabel |
| `app/Http/Controllers/SuperadminDashboardController.php` | Pastikan mode_kelas tersedia |
| `app/Http/Controllers/SuperadminJadwalController.php` | Pastikan mode_kelas tersedia |

## Acceptance Criteria

- [x] Semua halaman menggunakan set color tokens yang identik di tailwind.config
- [x] Superadmin dashboard dan jadwal index menampilkan badge Online/Offline
- [x] Tidak ada visual regression setelah semua issue #9–#12 dikerjakan
- [x] Aplikasi responsif di semua ukuran layar
- [x] Semua flash message berfungsi dengan benar

## Catatan

> Issue ini sebaiknya dikerjakan **paling terakhir** setelah Issue #9, #10, #11, dan #12 selesai, karena bersifat final integration dan polish.

## Urutan Pengerjaan Issue #9–#13

```
#9 Redesign Admin Dashboard & Mode Kelas (database + admin views)
       ↓
#10 Perbaikan Layout CRUD Admin (tombol kembali)
       ↓
#11 Logo Image & Hapus PRD (semua role)
       ↓
#12 Fix Icon & Konsistensi UI (semua role)
       ↓
#13 Tailwind Config & Final Polish (integrasi akhir)
```

> Issue #10, #11, #12 bisa dikerjakan **paralel** setelah #9 selesai. Issue #13 dikerjakan setelah semuanya selesai.
