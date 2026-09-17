# Issue #3 — Dashboard Admin

## Deskripsi

Bangun halaman dashboard operasional untuk role Admin. Dashboard ini adalah halaman utama yang ditampilkan setelah Admin login, menampilkan ringkasan dan daftar jadwal kelas hari ini di cabang yang bersangkutan.

## Scope

### 1. Header Aplikasi
- Logo dan identitas Elips Academy
- Informasi cabang yang sedang aktif
- Tanggal hari ini
- Info user yang sedang login (nama) dan tombol Logout (sudah ada dari Issue #2)

### 2. Card Ringkasan Jadwal Hari Ini
- Jumlah total jadwal hari ini
- Jumlah kelas yang sedang berjalan
- Jumlah kelas yang sudah selesai
- Jumlah kelas yang dibatalkan

### 3. Daftar Jadwal Kelas Hari Ini
- Tampilkan jadwal dalam bentuk card/list dengan informasi:
  - Sesi waktu (Pagi/Siang/Sore/Malam) beserta jam mulai-selesai dan durasi
  - Kode program dan jenis kelas (Private/Rombel/Business)
  - Nama kelas dan nomor pertemuan
  - Nama tentor
  - Ruangan
  - Status jadwal (Selesai, Sedang Berlangsung, Akan Datang, Dibatalkan)
- Tombol aksi per jadwal: Lihat Detail, Ubah (link ke halaman yang akan dibuat di Issue #4)

### 4. Filter & Pencarian
- Filter berdasarkan sesi waktu: Semua Kelas, Pagi (08:00-12:00), Siang (13:00-15:00), Sore (15:30-17:30), Malam (18:30-20:30)
- Pencarian sederhana berdasarkan nama kelas atau nama tentor

### 5. Navigasi & Aksi Cepat
- Navigasi waktu: Hari Ini, Besok, Kalender
- Tombol "+ Tambah Jadwal" (untuk sementara bisa link ke halaman placeholder atau `#`)

## Referensi
- Dashboard Admin: `PRD & DESIGN.md/PRD_Sistem_Penjadwalan_Kelas_Elips_Academy.md` section 8
- Mockup: `PRD & DESIGN.md/DESIGN.md ADMIN/dashboard_admin_sederhana_elips_academy/screen.png`

## Acceptance Criteria
- [x] Dashboard Admin tampil sesuai mockup setelah login sebagai Admin
- [x] Card ringkasan menampilkan jumlah jadwal hari ini secara akurat
- [x] Daftar jadwal kelas hari ini menampilkan informasi lengkap per jadwal
- [x] Filter sesi waktu berfungsi untuk memfilter daftar jadwal
- [x] Pencarian berdasarkan nama kelas atau tentor berfungsi
- [x] Navigasi waktu (Hari Ini, Besok) menampilkan jadwal sesuai tanggal
- [x] Halaman hanya bisa diakses oleh user yang sudah login dengan role Admin atau Superadmin
