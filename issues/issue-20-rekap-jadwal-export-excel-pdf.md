# Issue #20 — Fitur Rekap Jadwal Bulanan + Export Excel/PDF

## Tujuan

Menambahkan halaman rekap jadwal dengan kemampuan export:
1. **Admin** — melihat dan export rekap jadwal **per bulan** (termasuk bulan berjalan) ke **Excel**
2. **Superadmin** — melihat dan export rekap jadwal dengan **rentang tanggal bebas** ke **Excel dan PDF**

## Latar Belakang

Saat ini belum ada fitur untuk melihat ringkasan/rekap jadwal dalam rentang waktu tertentu. Admin dan superadmin harus melihat jadwal satu per satu di dashboard. Fitur ini memungkinkan:
- Admin melihat rekapan bulanan (misal: September meskipun baru tgl 17, maka data 1-17 yang tampil)
- Superadmin melihat rekapan lintas bulan/tahun (misal: 17/09/2026 s/d 19/02/2027)
- Keduanya bisa export data ke file

**Dependency:** Tidak ada (standalone feature), tetapi sebaiknya dikerjakan setelah Issue #19

## Scope

### A. Install Dependencies

```bash
composer require phpoffice/phpspreadsheet
composer require barryvdh/laravel-dompdf
```

| Package | Kegunaan |
|---|---|
| `phpoffice/phpspreadsheet` | Generate file Excel (.xlsx) |
| `barryvdh/laravel-dompdf` | Generate file PDF (khusus superadmin) |

### B. Controller — Admin Rekap (Bulanan)

File baru: `app/Http/Controllers/AdminRekapController.php`

#### `index(Request $request)`
- Filter: dropdown **Bulan** (Januari-Desember) + dropdown **Tahun** (default: bulan & tahun saat ini)
- Rentang otomatis: tanggal 1 s/d akhir bulan
- **Jika bulan berjalan**: end date = hari ini (bukan akhir bulan)
- Query: `Jadwal::with([...])` filtered by `cabang_id` admin + rentang tanggal
- Hitung summary statistik: total, selesai, terjadwal, dibatalkan, per jenis kelas, tentor unik
- Kirim data ke view

#### `exportExcel(Request $request)`
- Query sama seperti `index()` 
- Generate file Excel menggunakan PhpSpreadsheet
- Return response download `.xlsx`

### C. Controller — Superadmin Rekap (Custom Date Range)

File baru: `app/Http/Controllers/SuperadminRekapController.php`

#### `index(Request $request)`
- Filter bebas:
  - **Tanggal Mulai** (date picker) — default: awal bulan ini
  - **Tanggal Selesai** (date picker) — default: hari ini
  - **Cabang** (dropdown: Semua / per cabang)
  - **Status** (dropdown: Semua / Terjadwal / Selesai / Dibatalkan)
  - **Jenis Kelas** (dropdown: Semua / Private / Rombel / Business)
- Query: `Jadwal::with([...])` + semua filter di atas
- Hitung summary: total, per status, per cabang, per jenis kelas, tentor unik
- Kirim data ke view

#### `exportExcel(Request $request)`
- Query sama seperti `index()` dengan filter yang sama
- Generate file Excel menggunakan PhpSpreadsheet
- Return response download `.xlsx`

#### `exportPdf(Request $request)`
- Query sama seperti `index()` dengan filter yang sama
- Generate PDF menggunakan laravel-dompdf
- Return response download `.pdf`

### D. Shared Trait — Excel Builder

File baru: `app/Http/Controllers/Traits/ExcelExportTrait.php`

Method `buildExcel(Collection $jadwals, string $filename)`:
- Header row dengan styling (bold, background orange brand, border)
- Kolom: No, Tanggal, Hari, Jam Mulai, Jam Selesai, Program, Jenis Kelas, Mode Kelas, Tentor, Ruangan, Cabang, Pertemuan Ke, Status, Catatan
- **Tidak perlu kolom Nama Kelas**
- Auto-size kolom
- Baris summary di bawah tabel:
  - "Total Jadwal: X"
  - "Selesai: X | Terjadwal: X | Dibatalkan: X"
  - "Dicetak pada: DD/MM/YYYY HH:mm WIB"
- Return response download

### E. PDF Template (Superadmin Only)

File baru: `resources/views/superadmin/rekap/pdf.blade.php`

Layout print-friendly:
- Header: Logo Elips Academy + judul "Rekapitulasi Jadwal"
- Info rentang tanggal + filter yang dipilih
- Tabel data jadwal (kolom sama seperti Excel, tanpa Nama Kelas)
- Baris summary di bawah tabel
- Footer: tanggal cetak

### F. View — Admin Rekap

File baru: `resources/views/admin/rekap/index.blade.php`

Komponen UI:
- **Filter Bar:** Dropdown Bulan + Dropdown Tahun + Tombol "Tampilkan"
- **Summary Cards:** Total jadwal, Selesai, Terjadwal, Dibatalkan, Tentor aktif
- **Tabel Rekap:** Tanggal, Hari, Jam, Program, Tentor, Ruangan, Status
- **Tombol Export:** "📥 Export Excel"
- Desain konsisten dengan halaman admin lainnya (header, footer)

### G. View — Superadmin Rekap

File baru: `resources/views/superadmin/rekap/index.blade.php`

Komponen UI:
- **Filter Bar:** Date picker Mulai + Selesai, Dropdown Cabang, Status, Jenis Kelas, Tombol "Tampilkan"
- **Summary Cards:** Total + breakdown per cabang, per status, per jenis kelas
- **Tabel Rekap:** Tanggal, Hari, Jam, Program, Tentor, Ruangan, Cabang, Status
- **Tombol Export:** "📥 Export Excel" + "📄 Export PDF"
- Desain konsisten dengan halaman superadmin lainnya (sidebar, header)

### H. Routes Baru

File: `routes/web.php`

```php
// Di dalam group middleware('role:admin,superadmin')
Route::get('/admin/rekap', [AdminRekapController::class, 'index'])
    ->name('admin.rekap.index');
Route::get('/admin/rekap/export-excel', [AdminRekapController::class, 'exportExcel'])
    ->name('admin.rekap.export-excel');

// Di dalam group middleware('role:superadmin')
Route::get('/superadmin/rekap', [SuperadminRekapController::class, 'index'])
    ->name('superadmin.rekap.index');
Route::get('/superadmin/rekap/export-excel', [SuperadminRekapController::class, 'exportExcel'])
    ->name('superadmin.rekap.export-excel');
Route::get('/superadmin/rekap/export-pdf', [SuperadminRekapController::class, 'exportPdf'])
    ->name('superadmin.rekap.export-pdf');
```

### I. Navigasi — Menu Rekap di Dashboard

#### Admin Dashboard
File: `resources/views/admin/dashboard.blade.php`
- Tambahkan tombol/link "📊 Rekap Bulanan" di area header/navigasi dashboard

#### Superadmin Sidebar
File: Semua view superadmin
- Tambahkan menu "Rekap Jadwal" di sidebar
- Posisi: setelah "Jadwal Kelas" (urutan: Dashboard → Jadwal Kelas → **Rekap Jadwal** → Master Data → ...)
- Icon: `analytics` atau `assessment`
- Link ke: `route('superadmin.rekap.index')`
- Active state saat di halaman rekap

## Format Tabel Rekap (View & Excel)

| No | Tanggal | Hari | Jam Mulai | Jam Selesai | Program | Jenis Kelas | Mode | Tentor | Ruangan | Cabang | Pertemuan | Status | Catatan |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| 1 | 01/09/2026 | Selasa | 09:00 | 11:00 | Microsoft Office | Private | Offline | Rini | Ruang 1 | Buduran | 1 | Selesai | - |
| 2 | 01/09/2026 | Selasa | 13:00 | 15:00 | Web Programming | Rombel | Offline | Adi | Ruang 2 | Buduran | 3 | Selesai | - |

> **Catatan:** Kolom "Nama Kelas" tidak ditampilkan di tabel rekap.

## Files yang Terpengaruh

| File | Aksi |
|------|------|
| `composer.json` | **Edit** — tambah phpoffice/phpspreadsheet + barryvdh/laravel-dompdf |
| `app/Http/Controllers/AdminRekapController.php` | **Baru** — controller rekap admin |
| `app/Http/Controllers/SuperadminRekapController.php` | **Baru** — controller rekap superadmin |
| `app/Http/Controllers/Traits/ExcelExportTrait.php` | **Baru** — shared trait Excel builder |
| `resources/views/admin/rekap/index.blade.php` | **Baru** — UI rekap admin |
| `resources/views/superadmin/rekap/index.blade.php` | **Baru** — UI rekap superadmin |
| `resources/views/superadmin/rekap/pdf.blade.php` | **Baru** — template PDF export |
| `routes/web.php` | **Edit** — tambah routes rekap |
| `resources/views/admin/dashboard.blade.php` | **Edit** — tambah link navigasi Rekap |
| `resources/views/superadmin/dashboard.blade.php` | **Edit** — tambah menu Rekap di sidebar |
| `resources/views/superadmin/cabang/index.blade.php` | **Edit** — tambah menu Rekap di sidebar |
| `resources/views/superadmin/jadwal/index.blade.php` | **Edit** — tambah menu Rekap di sidebar |
| `resources/views/superadmin/program/index.blade.php` | **Edit** — tambah menu Rekap di sidebar |
| `resources/views/superadmin/tentor/index.blade.php` | **Edit** — tambah menu Rekap di sidebar |
| `resources/views/superadmin/user/index.blade.php` | **Edit** — tambah menu Rekap di sidebar |
| `resources/views/superadmin/ruangan/index.blade.php` | **Edit** — tambah menu Rekap di sidebar |

## Post-Implementation

```bash
composer require phpoffice/phpspreadsheet
composer require barryvdh/laravel-dompdf
```

## Acceptance Criteria

### Admin:
- [x] Halaman Rekap Bulanan dapat diakses dari dashboard admin
- [x] Default tampilan: bulan & tahun saat ini
- [x] Bulan berjalan menampilkan data dari tanggal 1 s/d hari ini
- [x] Bulan yang sudah lewat menampilkan data 1 s/d akhir bulan
- [x] Admin hanya melihat data cabangnya sendiri
- [x] Summary cards menampilkan total, selesai, terjadwal, dibatalkan dengan benar
- [x] Tabel rekap menampilkan kolom: Tanggal, Hari, Jam, Program, Tentor, Ruangan, Status
- [x] Klik "Export Excel" → file .xlsx terdownload dengan data sesuai filter
- [x] File Excel memiliki header styled, data lengkap, dan summary di bawah

### Superadmin:
- [x] Halaman Rekap Jadwal dapat diakses dari sidebar superadmin
- [x] Filter tanggal mulai & selesai berfungsi (custom range, lintas bulan/tahun)
- [x] Filter cabang, status, jenis kelas berfungsi
- [x] Bisa melihat rekap 1 hari saja (tanggal mulai = tanggal selesai)
- [x] Bisa melihat rekap lintas bulan/tahun (misal: 17/09/2026 s/d 19/02/2027)
- [x] Summary cards menampilkan breakdown yang benar
- [x] Klik "Export Excel" → file .xlsx terdownload dengan semua data sesuai filter
- [x] Klik "Export PDF" → file .pdf terdownload dengan layout print-friendly
- [x] Menu "Rekap Jadwal" muncul di sidebar superadmin di semua halaman
- [x] Tidak ada regression — fitur lain tetap berfungsi normal
