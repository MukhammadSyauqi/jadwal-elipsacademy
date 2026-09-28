# Issue #15 — Tabel Ruangan & Model + Seeder

## Tujuan

Membuat tabel database `ruangan` untuk menyimpan data ruangan per cabang, menggantikan hardcoded ruangan di controller. Setiap cabang memiliki jumlah ruangan yang berbeda-beda.

## Latar Belakang

Saat ini ruangan di-hardcode di `AdminJadwalController`:
- Candi → `['Ruang A Candi', 'Lab Multimedia Candi', 'Lab IT Candi']`
- Gubeng → `['Ruang 1', 'Ruang 2', 'Lab Komputer A']`
- Lainnya → `['Ruang 1', 'Ruang 2', 'Lab Komputer A', 'Lab Komputer B', 'Studio Desain']`

Ini harus dipindahkan ke database agar bisa dikelola secara dinamis dan sesuai kebutuhan per cabang.

## Scope

### A. Database Migration: Buat tabel `ruangan`

File: `database/migrations/xxxx_create_ruangan_table.php`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | bigint PK | Auto increment |
| `cabang_id` | foreignId | FK → `cabang`, cascade on delete |
| `nama_ruangan` | string(100) | Nama ruangan |
| `kapasitas` | unsignedInteger | Nullable, opsional |
| `status` | enum(aktif, nonaktif) | Default: aktif |
| `timestamps` | | Created/Updated at |

- Unique constraint: `['cabang_id', 'nama_ruangan']` — tidak boleh duplikat nama di cabang yang sama
- Index pada kolom `status`

### B. Database Migration: Tambah `ruangan_id` ke tabel `jadwal`

File: `database/migrations/xxxx_add_ruangan_id_to_jadwal_table.php`

- Tambah kolom `ruangan_id` (nullable FK → `ruangan`) setelah kolom `ruangan`
- **Pertahankan kolom `ruangan` string lama** untuk backward compatibility
- Data migration: loop jadwal yang ada, matching nama ruangan + cabang_id → set `ruangan_id`
- Jika tidak ada match, buat record ruangan baru secara otomatis

### C. Model `Ruangan`

File: `app/Models/Ruangan.php`

```php
class Ruangan extends Model
{
    protected $table = 'ruangan';
    protected $fillable = ['cabang_id', 'nama_ruangan', 'kapasitas', 'status'];

    public function cabang(): BelongsTo { ... }
    public function jadwals(): HasMany { ... }
}
```

### D. Model `Cabang` — Tambah relasi

File: `app/Models/Cabang.php`

- Tambahkan relasi `ruangans(): HasMany`

### E. Model `Jadwal` — Tambah relasi

File: `app/Models/Jadwal.php`

- Tambahkan `'ruangan_id'` ke `$fillable`
- Tambahkan relasi `ruanganRef(): BelongsTo`

### F. Seeder `RuanganSeeder`

File: `database/seeders/RuanganSeeder.php`

| Cabang | ID | Ruangan |
|--------|----|---------|
| Buduran | 1 | Ruang 1, Ruang 2, Ruang 3 |
| Candi | 2 | Ruang 1, Ruang 2 |
| Gubeng | 3 | Ruang 1, Ruang 2, Ruang 3 |

### G. Update `DatabaseSeeder`

File: `database/seeders/DatabaseSeeder.php`

- Tambahkan `RuanganSeeder` ke urutan seeding (setelah `CabangSeeder`, sebelum `JadwalSeeder`)

### H. Update `JadwalSeeder`

File: `database/seeders/JadwalSeeder.php`

- Update data jadwal agar menggunakan `ruangan_id` yang merujuk ke tabel `ruangan`
- Tetap isi kolom `ruangan` string sebagai fallback

## Files yang Terpengaruh

| File | Aksi |
|------|------|
| `database/migrations/xxxx_create_ruangan_table.php` | Buat baru |
| `database/migrations/xxxx_add_ruangan_id_to_jadwal_table.php` | Buat baru |
| `app/Models/Ruangan.php` | Buat baru |
| `app/Models/Cabang.php` | Edit: tambah relasi ruangans |
| `app/Models/Jadwal.php` | Edit: tambah fillable + relasi |
| `database/seeders/RuanganSeeder.php` | Buat baru |
| `database/seeders/DatabaseSeeder.php` | Edit: tambah RuanganSeeder |
| `database/seeders/JadwalSeeder.php` | Edit: gunakan ruangan_id |

## Acceptance Criteria

- [x] Migration berhasil, tabel `ruangan` terbentuk dengan constraint yang benar
- [x] Kolom `ruangan_id` muncul di tabel `jadwal`
- [x] Seeder berjalan: Candi punya 2 ruangan, Buduran punya 3, Gubeng punya 3
- [x] Model `Ruangan` berfungsi dengan relasi `cabang()` dan `jadwals()`
- [x] Model `Cabang` bisa mengakses `$cabang->ruangans`
- [x] Model `Jadwal` bisa mengakses `$jadwal->ruanganRef`
- [x] Unique constraint berfungsi: tidak bisa buat ruangan duplikat di cabang yang sama
- [x] `php artisan migrate:fresh --seed` berhasil tanpa error
