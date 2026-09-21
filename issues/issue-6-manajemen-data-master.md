# Issue #6 — Manajemen Data Master (Superadmin)

## Deskripsi

Implementasi halaman CRUD lengkap untuk mengelola data master: **Cabang**, **Program Kursus**, dan **Tentor**. Ketiga modul ini hanya dapat diakses oleh Superadmin dan menjadi fondasi data referensi yang digunakan seluruh fitur penjadwalan.

## Scope

### 1. Manajemen Cabang
- Halaman daftar cabang dalam bentuk tabel dengan detail panel
- Tambah & edit cabang (nama, alamat, status aktif/nonaktif)
- Aktif/nonaktifkan cabang (bukan hard delete jika sudah memiliki relasi jadwal)
- Tampilkan jumlah jadwal aktif yang terkait pada setiap cabang

### 2. Manajemen Program Kursus
- Halaman daftar program kursus dalam bentuk tabel dengan detail panel
- Tambah & edit program (nama program, kategori, status aktif/nonaktif)
- Aktif/nonaktifkan program
- Tampilkan jumlah jadwal terkait pada setiap program

### 3. Manajemen Tentor
- Halaman daftar tentor dalam bentuk tabel dengan detail panel
- Tambah & edit tentor (nama, nomor HP, keahlian/bidang, status aktif/nonaktif)
- Aktif/nonaktifkan tentor
- Tampilkan beban jadwal (jumlah kelas) dan jadwal hari ini untuk setiap tentor

## Aturan Umum
- Data master yang sudah berelasi dengan jadwal **tidak boleh di-hard delete**. Gunakan mekanisme status `nonaktif`.
- Data master berstatus `nonaktif` **tidak ditampilkan** di dropdown saat membuat/mengedit jadwal baru.
- Setiap halaman harus memiliki search bar dan filter status (aktif/nonaktif/semua).

## Referensi
- Dokumentasi: PRD section 15 (Cabang), section 16 (Program Kursus), section 17 (Tentor)
- Mockup UI:
  - `PRD & DESIGN.md/DESIGN.md SUPERADMIN/manajemen_cabang_elips_academy/screen.png`
  - `PRD & DESIGN.md/DESIGN.md SUPERADMIN/manajemen_program_kursus_elips_academy/screen.png`
  - `PRD & DESIGN.md/DESIGN.md SUPERADMIN/manajemen_tentor_elips_academy/screen.png`

## Acceptance Criteria
- [x] Halaman daftar, tambah, dan edit Cabang berfungsi dengan benar.
- [x] Halaman daftar, tambah, dan edit Program Kursus berfungsi dengan benar.
- [x] Halaman daftar, tambah, dan edit Tentor berfungsi dengan benar.
- [x] Data master yang sudah berelasi dengan jadwal tidak bisa dihapus permanen, hanya dinonaktifkan.
- [x] Data master nonaktif tidak muncul di dropdown form jadwal (create/edit).
- [x] Setiap halaman menampilkan jumlah jadwal terkait sebagai informasi relasi.
- [x] Seluruh halaman hanya bisa diakses oleh user ber-role Superadmin.
