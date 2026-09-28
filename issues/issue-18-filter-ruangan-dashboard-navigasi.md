# Issue #18 — Filter Ruangan di Admin Dashboard + Navigasi Superadmin

## Tujuan

1. Menambahkan filter ruangan di halaman admin dashboard agar admin bisa menyaring jadwal berdasarkan ruangan
2. Menambahkan menu "Ruangan" di sidebar navigasi superadmin

## Latar Belakang

Berdasarkan feedback user:
- Admin **tidak perlu halaman CRUD ruangan terpisah** — cukup filter ruangan di dashboard
- Menu "Ruangan" hanya ditambahkan di sidebar **superadmin** (bukan admin)

**Dependency:** Issue #14, #15, #16, #17 (semua harus sudah selesai)

## Scope

### A. Filter Ruangan di Admin Dashboard

File: `app/Http/Controllers/AdminDashboardController.php`

- Tambahkan parameter query `ruangan_id` sebagai filter opsional
- Load daftar ruangan milik cabang admin (atau semua ruangan jika superadmin):
  ```php
  if ($user->cabang_id) {
      $ruangans = Ruangan::where('cabang_id', $user->cabang_id)
          ->where('status', 'aktif')
          ->orderBy('nama_ruangan')->get();
  } else {
      $ruangans = Ruangan::where('status', 'aktif')
          ->orderBy('nama_ruangan')->get();
  }
  ```
- Kirim `$ruangans` dan `$selectedRuanganId` ke view

File: `resources/views/admin/dashboard.blade.php`

- Tambahkan dropdown/filter chip "Ruangan" di filter bar (sejajar dengan filter Cabang, Sesi)
- Opsi: "Semua Ruangan" + daftar ruangan
- Saat cabang berubah di filter, ruangan juga harus ter-update (resubmit filter atau AJAX)

### B. Tampilkan Nama Ruangan di Card Jadwal

File: `resources/views/admin/dashboard.blade.php`

- Update card jadwal: tampilkan nama ruangan dari relasi `$jadwal->ruanganRef->nama_ruangan`
- Fallback ke `$jadwal->ruangan` (string lama) jika `ruangan_id` belum di-set

### C. Menu Navigasi Superadmin

File: Semua view superadmin yang memiliki sidebar

- Tambahkan menu "Ruangan" di bawah section "Master Data"
- Posisi: setelah Tentor (urutan: Cabang → Program → Tentor → **Ruangan**)
- Icon: konsisten dengan design system (door/grid icon)
- Link ke: `route('superadmin.ruangan.index')`
- Active state saat berada di halaman ruangan

### D. Update Superadmin Dashboard — Sidebar

File: `resources/views/superadmin/dashboard.blade.php`

Menu sidebar yang sudah ada:
1. Dashboard
2. Jadwal Kelas
3. Master Data: Cabang, Program Kursus, Tentor
4. Manajemen Akun

Menjadi:
1. Dashboard
2. Jadwal Kelas
3. Master Data: Cabang, Program Kursus, Tentor, **Ruangan**
4. Manajemen Akun

## Files yang Terpengaruh

| File | Aksi |
|------|------|
| `app/Http/Controllers/AdminDashboardController.php` | Edit: tambah filter ruangan |
| `resources/views/admin/dashboard.blade.php` | Edit: tambah dropdown filter + tampilkan nama ruangan |
| `resources/views/superadmin/dashboard.blade.php` | Edit: tambah menu sidebar |
| `resources/views/superadmin/cabang/index.blade.php` | Edit: tambah menu sidebar |
| `resources/views/superadmin/jadwal/index.blade.php` | Edit: tambah menu sidebar |
| `resources/views/superadmin/program/index.blade.php` | Edit: tambah menu sidebar |
| `resources/views/superadmin/tentor/index.blade.php` | Edit: tambah menu sidebar |
| `resources/views/superadmin/user/index.blade.php` | Edit: tambah menu sidebar |
| `resources/views/superadmin/ruangan/index.blade.php` | Edit: sidebar active state |

## Acceptance Criteria

- [ ] Filter ruangan muncul di admin dashboard (sejajar dengan filter lain)
- [ ] Filter ruangan berfungsi — menyaring jadwal per ruangan
- [ ] Opsi "Semua Ruangan" menampilkan semua jadwal (tanpa filter ruangan)
- [ ] Nama ruangan tampil di card jadwal (bukan string hardcoded lama)
- [ ] Menu "Ruangan" muncul di sidebar superadmin di semua halaman
- [ ] Menu "Ruangan" memiliki active state yang benar saat berada di halaman ruangan
- [ ] Admin **tidak** memiliki menu CRUD ruangan terpisah
- [ ] Tidak ada regression — filter sesi, cabang, search tetap berfungsi
