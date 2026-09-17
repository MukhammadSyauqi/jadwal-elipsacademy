# Issue #1 — Project Setup, Database & Seeder

## Deskripsi

Inisialisasi project Laravel 11 dan bangun seluruh fondasi database sistem penjadwalan kelas Elips Academy.

Project ini menggunakan 5 tabel utama: `users`, `cabang`, `program`, `tentor`, dan `jadwal`. Tabel `jadwal` adalah pusat sistem yang berelasi ke tiga tabel master (`cabang`, `program`, `tentor`). Tabel `users` hanya untuk autentikasi.

## Scope

### 1. Inisialisasi Project
- Buat project Laravel 11 baru
- Konfigurasi koneksi database MySQL

### 2. Migration
Buat migration untuk 5 tabel dengan urutan:

1. **`users`** — `nama`, `email` (unique), `password`, `role` (enum: admin/superadmin, default: admin)
2. **`cabang`** — `nama_cabang`, `alamat` (nullable), `status` (enum: aktif/nonaktif, default: aktif)
3. **`program`** — `nama_program`, `kategori` (nullable), `status` (enum: aktif/nonaktif, default: aktif)
4. **`tentor`** — `nama`, `no_hp` (nullable), `keahlian` (nullable), `status` (enum: aktif/nonaktif, default: aktif)
5. **`jadwal`** — `cabang_id` (FK), `program_id` (FK), `tentor_id` (FK), `nama_kelas`, `jenis_kelas` (enum: private/business/rombel), `tanggal` (date), `jam_mulai` (time), `jam_selesai` (time), `ruangan` (nullable), `pertemuan` (nullable), `status` (enum: terjadwal/selesai/dibatalkan, default: terjadwal), `catatan` (nullable)

Semua tabel menggunakan `id` BIGINT UNSIGNED + `timestamps`.

> Urutan migration penting karena `jadwal` memiliki foreign key ke `cabang`, `program`, dan `tentor`.

### 3. Index
Tambahkan index sesuai kebutuhan pencarian/filter:
- `jadwal`: index pada `cabang_id`, `program_id`, `tentor_id`, `tanggal`, `jenis_kelas`, `status`
- `users`: index pada `role`
- `cabang`, `program`, `tentor`: index pada `status`

### 4. Model & Relasi
Buat Eloquent Model untuk setiap tabel dengan relasi:
- `Cabang` → hasMany `Jadwal`
- `Program` → hasMany `Jadwal`
- `Tentor` → hasMany `Jadwal`
- `Jadwal` → belongsTo `Cabang`, `Program`, `Tentor`
- Set `$fillable` yang sesuai pada setiap model

### 5. Seeder
Buat seeder dengan data contoh minimal:
- 2 user (1 superadmin, 1 admin)
- 2 cabang (Buduran, Candi)
- 3 program (Microsoft Office, Web Programming, Graphic Design)
- 3 tentor (Budi, Andi, Rina)
- 2-3 jadwal contoh

Password user harus di-hash.

## Referensi
- Struktur tabel lengkap: `PRD & DESIGN.md/Database.md` section 5–17
- Contoh data: `PRD & DESIGN.md/Database.md` section 17
- Contoh SQL: `PRD & DESIGN.md/Database.md` section 18
- Relasi model: `PRD & DESIGN.md/Database.md` section 20

## Acceptance Criteria
- [ ] `php artisan migrate` berhasil tanpa error
- [ ] Semua 5 tabel terbentuk dengan field, type, dan constraint yang benar
- [ ] Foreign key `jadwal` → `cabang`, `program`, `tentor` berjalan
- [ ] `php artisan db:seed` mengisi data contoh
- [ ] Model dan relasi bisa diuji via `php artisan tinker`
