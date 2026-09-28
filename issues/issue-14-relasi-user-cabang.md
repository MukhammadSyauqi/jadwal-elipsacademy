# Issue #14 — Relasi User ↔ Cabang (1 Cabang = 1 Akun Admin)

## Tujuan

Menambahkan relasi antara tabel `users` dan `cabang` agar setiap akun admin terikat ke satu cabang tertentu. Superadmin tidak terikat cabang (cabang_id = NULL) dan bisa mengakses semua cabang.

## Latar Belakang

Saat ini tabel `users` tidak memiliki kolom `cabang_id`, sehingga sistem tidak bisa membedakan admin cabang mana yang login. Penentuan cabang saat ini dilakukan secara heuristik melalui pencocokan nama user (`stripos($user->nama, $c->nama_cabang)`) yang tidak reliable.

## Scope

### A. Database Migration

File: `database/migrations/xxxx_add_cabang_id_to_users_table.php`

- Tambah kolom `cabang_id` (nullable) ke tabel `users`
- Foreign key constraint ke tabel `cabang` dengan `nullOnDelete()`
- `cabang_id = NULL` → Superadmin (akses semua cabang)
- `cabang_id = N` → Admin cabang tertentu

### B. Model `User`

File: `app/Models/User.php`

- Tambahkan `'cabang_id'` ke `$fillable`
- Tambahkan relasi `cabang(): BelongsTo`
- Tambahkan helper method: `getCabangNameAttribute()` untuk fallback display

### C. Model `Cabang`

File: `app/Models/Cabang.php`

- Tambahkan relasi `users(): HasMany`

### D. Seeder `UserSeeder`

File: `database/seeders/UserSeeder.php`

- Tambahkan `cabang_id` ke setiap user:
  - `Super Admin` → `cabang_id: null`
  - `Admin Candi` → `cabang_id: 2` (Candi)
  - `Admin Gubeng` → `cabang_id: 3` (Gubeng) — **Nama diubah dari "Staff Gubeng"**
  - `Admin Buduran` → `cabang_id: 1` (Buduran)
- Email `Admin Gubeng` diubah menjadi `admin.gubeng@elipsacademy.com`

### E. Update `SuperadminUserController`

File: `app/Http/Controllers/SuperadminUserController.php`

- `index()`: Load relasi cabang, kirim `$cabangs` ke view
- `store()`: Validasi `cabang_id` — nullable untuk superadmin, required untuk admin
- `update()`: Validasi `cabang_id` dengan logic yang sama

### F. Update View User Management

File: `resources/views/superadmin/user/index.blade.php`

- Tambahkan dropdown `cabang_id` di form create/edit user
- Tampilkan nama cabang di tabel daftar user dan inspector panel
- Logika: jika role = superadmin, cabang_id otomatis NULL (dropdown disabled)

### G. Update `AdminDashboardController`

File: `app/Http/Controllers/AdminDashboardController.php`

- Jika user adalah admin (`$user->cabang_id` != null):
  - Hanya tampilkan cabang miliknya di filter
  - Auto-set `selectedCabang` ke cabangnya
  - Sembunyikan dropdown "Semua Cabang" (hanya 1 opsi)
- Jika user adalah superadmin:
  - Tetap bisa lihat semua cabang (behavior saat ini)

### H. Update `AdminJadwalController`

File: `app/Http/Controllers/AdminJadwalController.php`

- `create()`: Hapus logika heuristik `stripos($user->nama, $c->nama_cabang)`, ganti dengan `$user->cabang_id`
- Jika admin: pre-select dan lock cabang ke cabang miliknya
- Jika superadmin: bisa pilih cabang bebas (tetap seperti saat ini)

## Files yang Terpengaruh

| File | Aksi |
|------|------|
| `database/migrations/xxxx_add_cabang_id_to_users_table.php` | Buat baru |
| `app/Models/User.php` | Edit: tambah fillable + relasi |
| `app/Models/Cabang.php` | Edit: tambah relasi users |
| `database/seeders/UserSeeder.php` | Edit: tambah cabang_id, rename Gubeng |
| `app/Http/Controllers/SuperadminUserController.php` | Edit: validasi + pass cabangs |
| `resources/views/superadmin/user/index.blade.php` | Edit: form + tampilan cabang |
| `app/Http/Controllers/AdminDashboardController.php` | Edit: auto-filter by cabang |
| `app/Http/Controllers/AdminJadwalController.php` | Edit: hapus heuristik stripos |

## Acceptance Criteria

- [x] Migration berhasil, kolom `cabang_id` muncul di tabel `users`
- [x] Seeder berjalan: setiap admin ter-assign ke cabang yang benar
- [x] Nama "Staff Gubeng" diubah menjadi "Admin Gubeng", email menjadi `admin.gubeng@elipsacademy.com`
- [x] Superadmin bisa membuat user admin dan memilih cabang yang dikelola
- [x] Admin yang login hanya melihat jadwal di cabangnya sendiri
- [x] Form create jadwal otomatis pre-select cabang sesuai akun admin
- [x] Superadmin tetap bisa melihat dan mengelola semua cabang
- [x] Tidak ada regression — login, CRUD jadwal, dashboard tetap berfungsi
