# Issue #5 — Dashboard & Manajemen Jadwal Superadmin

## Deskripsi

Bangun halaman dashboard dan halaman manajemen jadwal utama untuk role Superadmin. Halaman ini akan memberikan *overview* lengkap terkait operasional dari semua cabang Elips Academy, serta kontrol untuk memantau dan mengelola seluruh jadwal kelas.

## Scope

### 1. Dashboard Superadmin
- Sapaan kepada pengguna ("Halo, [Nama]")
- **Card Ringkasan / Metrik:**
  - Total sesi yang dijadwalkan minggu ini
  - Okupansi atau penggunaan ruang belajar
  - Jumlah pengajar (tentor) yang aktif
  - Integritas jadwal (info terkait penjadwalan tanpa masalah/bentrok)
- **Daftar Ringkas Jadwal Hari Ini:** Menampilkan sekilas jadwal kelas hari ini
- **Status Ketersediaan Ruang:** Informasi ruangan per lantai / cabang
- **Log Perubahan:** Menampilkan list perubahan jadwal terakhir berdasarkan waktu *update*
- **Pintasan Cepat:** Akses cepat ke berbagai modul seperti Master Cabang, Program, atau Tentor

### 2. Halaman Daftar Manajemen Jadwal
- **Tabel Lengkap:**
  Tabel yang mencakup data dari semua cabang, dengan kolom:
  - Kode Kelas & Program
  - Cabang
  - Tentor
  - Jenis Kelas
  - Waktu & Tanggal
  - Ruangan / Pertemuan
  - Status
  - Aksi
- **Pencarian (Search Bar):** Untuk mencari nama kelas, kode, atau tentor.
- **Filter Data:** Filter dropdown untuk Cabang, Jenis Kelas, dan Status.
- **Rentang Waktu Cepat:** Tombol filter waktu (Hari Ini, Besok, Minggu Ini).
- **Pagination:** Data diurutkan berdasarkan waktu secara descending/terbaru dengan pagination.
- **Aksi per Baris (CRUD):**
  Aksi (Detail, Edit, Hapus) memanggil fitur CRUD yang sama seperti pada Issue #4, namun dilakukan dalam konteks Superadmin lintas cabang.

## Referensi
- Dokumentasi: PRD section 9 (Dashboard Superadmin) & section 10 (Manajemen Jadwal)
- Mockup UI:
  - `PRD & DESIGN.md/DESIGN.md SUPERADMIN/dashboard_elips_academy/screen.png`
  - `PRD & DESIGN.md/DESIGN.md SUPERADMIN/dashboard_jadwal_kelas_elips_academy/screen.png`

## Acceptance Criteria
- [x] Dashboard Superadmin berhasil dirender dan hanya bisa diakses oleh user ber-role Superadmin.
- [x] Card ringkasan (metrik) menampilkan data hasil agregasi dari seluruh cabang.
- [x] Daftar jadwal (tabel utama) menampilkan jadwal lintas cabang dengan benar.
- [x] Filter berdasarkan Cabang, Jenis Kelas, Status, serta Search bar bekerja dengan baik.
- [x] Aksi Detail, Edit, dan Batalkan jadwal dari daftar berfungsi normal, mengikuti aturan CRUD dan deteksi bentrok yang sudah ada.
