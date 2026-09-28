# Issue #16 — CRUD Ruangan Superadmin

## Tujuan

Membuat halaman manajemen ruangan untuk role Superadmin. Superadmin bisa melihat, menambah, mengedit, mengaktifkan/menonaktifkan, dan menghapus ruangan dari semua cabang.

## Latar Belakang

Setelah tabel `ruangan` dibuat di Issue #15, diperlukan antarmuka untuk mengelola data ruangan. Superadmin memiliki akses penuh ke semua ruangan di seluruh cabang.

**Dependency:** Issue #15 (tabel ruangan harus sudah ada)

## Scope

### A. Controller: `SuperadminRuanganController`

File: `app/Http/Controllers/SuperadminRuanganController.php`

| Method | Route | Deskripsi |
|--------|-------|-----------|
| `index` | `GET /superadmin/ruangan` | List semua ruangan, grouped by cabang, filter & search |
| `store` | `POST /superadmin/ruangan` | Tambah ruangan baru (pilih cabang) |
| `update` | `PUT /superadmin/ruangan/{ruangan}` | Edit nama, kapasitas, status |
| `toggleStatus` | `POST /superadmin/ruangan/{ruangan}/toggle-status` | Aktif ↔ Nonaktif |
| `destroy` | `DELETE /superadmin/ruangan/{ruangan}` | Hapus (protect jika ada jadwal terkait) |

#### Detail `index()`:
- Query semua ruangan dengan relasi `cabang`
- Filter: per cabang (dropdown), status (aktif/nonaktif/semua), search
- Metrics: total ruangan, per cabang count, aktif/nonaktif
- Inspector panel untuk detail ruangan terpilih (termasuk jadwal terakhir yang menggunakan ruangan ini)

#### Detail `store()`:
- Validasi: `cabang_id` required + exists, `nama_ruangan` required + max:100, `kapasitas` nullable integer, unique `[cabang_id, nama_ruangan]`

#### Detail `destroy()`:
- Cek apakah ruangan masih digunakan oleh jadwal aktif
- Jika ya: tampilkan error, sarankan nonaktifkan saja
- Jika tidak: boleh hapus permanen

### B. View: `superadmin/ruangan/index.blade.php`

UI mengikuti pola halaman Master Data yang sudah ada (Cabang, Program, Tentor):

- **Metrics Cards**: Total Ruangan, Ruangan Aktif, Ruangan Nonaktif, jumlah per cabang
- **Filter Bar**: Dropdown cabang + status + search
- **Tabel Ruangan**: Kolom — Nama Ruangan, Cabang, Kapasitas, Status, Aksi
- **Inspector Panel** (kanan): Detail ruangan terpilih + form edit inline
- **Modal Tambah**: Form create ruangan baru dengan pilihan cabang
- **Konfirmasi Hapus**: Dialog konfirmasi sebelum delete

### C. Routes

File: `routes/web.php`

```php
// Dalam group middleware('role:superadmin')
Route::get('/superadmin/ruangan', [SuperadminRuanganController::class, 'index'])->name('superadmin.ruangan.index');
Route::post('/superadmin/ruangan', [SuperadminRuanganController::class, 'store'])->name('superadmin.ruangan.store');
Route::put('/superadmin/ruangan/{ruangan}', [SuperadminRuanganController::class, 'update'])->name('superadmin.ruangan.update');
Route::post('/superadmin/ruangan/{ruangan}/toggle-status', [SuperadminRuanganController::class, 'toggleStatus'])->name('superadmin.ruangan.toggle-status');
Route::delete('/superadmin/ruangan/{ruangan}', [SuperadminRuanganController::class, 'destroy'])->name('superadmin.ruangan.destroy');
```

### D. Navigasi Sidebar Superadmin

File: `resources/views/superadmin/dashboard.blade.php` (dan layout terkait)

- Tambahkan menu "Ruangan" di bawah "Master Data" (sejajar dengan Cabang, Program, Tentor)
- Icon: door/room icon yang konsisten dengan design system

## Files yang Terpengaruh

| File | Aksi |
|------|------|
| `app/Http/Controllers/SuperadminRuanganController.php` | Buat baru |
| `resources/views/superadmin/ruangan/index.blade.php` | Buat baru |
| `routes/web.php` | Edit: tambah routes ruangan |
| `resources/views/superadmin/dashboard.blade.php` | Edit: tambah menu sidebar |
| Semua view superadmin yang memiliki sidebar | Edit: tambah menu |

## Acceptance Criteria

- [x] Halaman `/superadmin/ruangan` bisa diakses dan menampilkan semua ruangan
- [x] Metrics cards menampilkan total, aktif, nonaktif dengan angka yang benar
- [x] Filter per cabang berfungsi — memfilter tabel ruangan
- [x] Search berfungsi — bisa cari berdasarkan nama ruangan
- [x] Tambah ruangan berhasil dengan validasi (nama unik per cabang)
- [x] Edit ruangan berhasil (nama, kapasitas, status)
- [x] Toggle status aktif/nonaktif berfungsi
- [x] Hapus ruangan yang tidak dipakai jadwal berhasil
- [x] Hapus ruangan yang masih dipakai jadwal menampilkan error
- [x] Menu "Ruangan" muncul di sidebar superadmin
- [x] UI konsisten dengan halaman Master Data lainnya (Cabang, Program, Tentor)
