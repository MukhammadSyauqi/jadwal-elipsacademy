 # Issues — Sistem Penjadwalan Kelas Elips Academy

> Dokumen ini memecah keseluruhan project menjadi issue bertahap.
> Setiap issue adalah satu deliverable yang bisa dikerjakan secara berurutan.
>
> **Referensi utama:**
> - PRD Lengkap: `PRD & DESIGN.md/PRD_Sistem_Penjadwalan_Kelas_Elips_Academy.md`
> - Database PRD: `PRD & DESIGN.md/Database.md`
> - Design System: `PRD & DESIGN.md/DESIGN.md ADMIN/academic_precision_warmth/DESIGN.md`
> - Mockup Admin: folder `PRD & DESIGN.md/DESIGN.md ADMIN/`
> - Mockup Superadmin: folder `PRD & DESIGN.md/DESIGN.md SUPERADMIN/`

---

## Issue #1 — Project Setup, Database & Seeder

**Tujuan:** Inisialisasi project Laravel 11 dan bangun seluruh fondasi database.

**Scope:**
- Buat project Laravel 11 baru
- Buat migration untuk 5 tabel: `users`, `cabang`, `program`, `tentor`, `jadwal`
- Buat Eloquent Model beserta relasi (`hasMany`, `belongsTo`) untuk setiap tabel
- Buat seeder dengan data contoh dari Database.md bagian "Contoh Data" (section 17)
- Pastikan foreign key `jadwal` → `cabang`, `program`, `tentor` berjalan
- Pastikan ENUM, index, dan default value sesuai Database.md

**Referensi:** Database.md section 5–17 (struktur tabel, relasi, SQL, migration, model)

**Hasil akhir:** `php artisan migrate --seed` berhasil, semua tabel terbentuk dan terisi data contoh.

---

## Issue #2 — Autentikasi (Login & Logout)

**Tujuan:** Implementasi login/logout dengan pembatasan akses berdasarkan role.

**Scope:**
- Halaman login sesuai mockup (`halaman_login_elips_academy/screen.png`)
- Login menggunakan email + password
- Setelah login, redirect berdasarkan role (admin → dashboard admin, superadmin → dashboard superadmin)
- Middleware untuk membatasi akses halaman sesuai role
- Logout
- Validasi: email harus terdaftar, password hash, pesan error jika gagal

**Referensi:** PRD section 7 (Autentikasi), section 6 (Matriks Hak Akses)

**Mockup:** `DESIGN.md ADMIN/halaman_login_elips_academy/screen.png`

**Hasil akhir:** User bisa login, diarahkan ke dashboard sesuai role, dan tidak bisa mengakses halaman yang bukan haknya.

---

## Issue #3 — Dashboard Admin

**Tujuan:** Bangun halaman dashboard untuk role Admin.

**Scope:**
- Header: identitas aplikasi, info cabang, info user, logout
- Card ringkasan: jumlah jadwal hari ini, kelas sedang berjalan, kelas selesai, kelas dibatalkan
- Daftar jadwal kelas hari ini dalam bentuk card/list — tampilkan: nama kelas, program, tentor, waktu, cabang, ruangan, jenis kelas, status
- Filter waktu: Semua Kelas, Pagi, Siang, Sore, Malam
- Pencarian sederhana (nama kelas / tentor)
- Tombol aksi cepat: Tambah Jadwal, navigasi Hari Ini / Besok / Kalender

**Referensi:** PRD section 8 (Dashboard Admin)

**Mockup:** `DESIGN.md ADMIN/dashboard_admin_sederhana_elips_academy/screen.png`

**Hasil akhir:** Admin melihat jadwal hari ini langsung setelah login, bisa filter per sesi waktu, dan bisa navigasi ke tambah jadwal.

---

## Issue #4 — CRUD Jadwal (Admin)

**Tujuan:** Implementasi fitur tambah, lihat detail, edit, dan hapus/batalkan jadwal.

**Scope:**

### Tambah Jadwal
- Form: program (dropdown dari master aktif), nama kelas, nomor pertemuan, jenis kelas (private/rombel/business), tanggal, jam mulai, jam selesai, ruangan, tentor (dropdown dari master aktif), catatan
- Validasi wajib sesuai PRD section 11.3
- Validasi `jam_selesai > jam_mulai`
- **Deteksi bentrok tentor** dan **bentrok ruangan** — tampilkan peringatan jika ditemukan
- Feedback: "Jadwal berhasil disimpan"

### Detail Jadwal
- Tampilkan semua informasi jadwal termasuk data relasi (nama program, kategori, alamat cabang, info tentor)
- Aksi: Ubah Jadwal, Hapus, Kembali

### Edit Jadwal
- Form sama dengan tambah, data lama terisi otomatis
- Validasi dan cek bentrok tetap berjalan (exclude jadwal yang sedang diedit)
- Feedback: "Jadwal berhasil diperbarui"

### Hapus / Batalkan
- Utamakan pembatalan (ubah status → `dibatalkan`) daripada delete permanen
- Konfirmasi sebelum aksi

**Referensi:** PRD section 10–14, section 25 (logika bentrok)

**Mockup:**
- `DESIGN.md ADMIN/tambah_jadwal_kelas_sederhana_elips_academy/screen.png`
- `DESIGN.md ADMIN/detail_jadwal_kelas_elips_academy/screen.png`
- `DESIGN.md ADMIN/ubah_jadwal_kelas_elips_academy/screen.png`

**Hasil akhir:** Admin bisa melakukan full CRUD jadwal dengan validasi dan deteksi bentrok.

---

## Issue #5 — Dashboard & Manajemen Jadwal Superadmin

**Tujuan:** Bangun dashboard Superadmin dan halaman daftar jadwal lintas cabang.

**Scope:**

### Dashboard Superadmin
- Sapaan pengguna
- Card ringkasan: total sesi minggu ini, okupansi ruang, pengajar aktif, integritas jadwal
- Jadwal hari ini (daftar ringkas)
- Ketersediaan ruang per lantai/cabang
- Perubahan jadwal terakhir (berdasarkan `updated_at`)
- Pintasan cepat akademik (link ke semua modul)

### Daftar Jadwal (tabel lengkap)
- Tabel dengan kolom: kode kelas, program, cabang, tentor, jenis, waktu & tanggal, ruang/pertemuan, status, aksi
- Search bar
- Filter: cabang, jenis kelas, status
- Rentang cepat: Hari Ini, Besok, Minggu Ini
- Pagination
- Aksi per row: detail, edit, hapus (CRUD jadwal sama dengan Issue #4)

**Referensi:** PRD section 9 (Dashboard Superadmin), section 10 (Manajemen Jadwal)

**Mockup:**
- `DESIGN.md SUPERADMIN/dashboard_elips_academy/screen.png`
- `DESIGN.md SUPERADMIN/dashboard_jadwal_kelas_elips_academy/screen.png`

**Hasil akhir:** Superadmin punya overview lengkap sistem dan bisa mengelola semua jadwal dari semua cabang.

---

## Issue #6 — Manajemen Data Master (Superadmin)

**Tujuan:** Implementasi CRUD untuk data master: Cabang, Program Kursus, dan Tentor.

**Scope:**

### Manajemen Cabang
- Daftar cabang (tabel + detail panel)
- Tambah, edit cabang (nama, alamat, status)
- Aktif/nonaktifkan cabang (jangan hapus permanen jika sudah dipakai jadwal)
- Tampilkan jumlah jadwal aktif terkait

### Manajemen Program Kursus
- Daftar program (tabel + detail panel)
- Tambah, edit program (nama, kategori, status)
- Aktif/nonaktifkan program
- Tampilkan jadwal terkait

### Manajemen Tentor
- Daftar tentor (tabel + detail panel)
- Tambah, edit tentor (nama, no HP, keahlian, status)
- Aktif/nonaktifkan tentor
- Tampilkan beban jadwal dan jadwal hari ini

**Aturan umum:** Data master yang sudah berelasi dengan jadwal tidak boleh di-hard delete. Gunakan status `nonaktif`. Data nonaktif tidak muncul di dropdown saat buat jadwal baru.

**Referensi:** PRD section 15–17

**Mockup:**
- `DESIGN.md SUPERADMIN/manajemen_cabang_elips_academy/screen.png`
- `DESIGN.md SUPERADMIN/manajemen_program_kursus_elips_academy/screen.png`
- `DESIGN.md SUPERADMIN/manajemen_tentor_elips_academy/screen.png`

**Hasil akhir:** Superadmin bisa mengelola seluruh data master dengan proteksi integritas data.

---

## Issue #7 — Manajemen Akun Pengguna (Superadmin)

**Tujuan:** Implementasi pengelolaan akun user oleh Superadmin.

**Scope:**
- Daftar akun pengguna (tabel + detail panel)
- Tambah akun baru (nama, email unik, password, role admin/superadmin)
- Edit akun (ubah nama, email, role)
- Reset password
- Password wajib disimpan dalam bentuk hash
- Validasi email unik

**Referensi:** PRD section 18

**Mockup:** `DESIGN.md SUPERADMIN/manajemen_akun_pengguna_elips_academy/screen.png`

**Hasil akhir:** Superadmin bisa mengelola semua akun user sistem.

---

## Issue #8 — UI Polish, Design System & Responsif

**Tujuan:** Poles seluruh tampilan agar sesuai design system dan responsif di semua device.

**Scope:**
- Terapkan design system "Academic Precision & Warmth" secara konsisten ke seluruh halaman
- Warna, typography (Inter), spacing, border-radius, elevation sesuai DESIGN.md
- Komponen UI: button pill/capsule, search pill, status badge, filter chips, card radius 12-16px
- Responsive breakpoints: Desktop ≥1280px (sidebar + grid), Tablet 768-1279px (collapsible filter), Mobile ≤767px (drawer nav + single column cards)
- Empty state untuk kondisi data kosong
- Notifikasi/feedback setelah aksi (berhasil, gagal, konflik)
- Micro-animation dan hover effects

**Referensi:** PRD section 21-22 (Design System, Komponen UI), section 29 (Responsive), DESIGN.md

**Hasil akhir:** Seluruh aplikasi tampil konsisten, premium, dan responsif sesuai mockup yang diberikan.

---

## Urutan Pengerjaan

```text
#1 Setup & Database
       ↓
#2 Autentikasi
       ↓
#3 Dashboard Admin
       ↓
#4 CRUD Jadwal
       ↓
#5 Dashboard & Jadwal Superadmin
       ↓
#6 Data Master (Cabang, Program, Tentor)
       ↓
#7 Manajemen Akun
       ↓
#8 UI Polish & Responsif
```

> **Catatan:** Issue #8 bisa dikerjakan paralel selama development berlangsung, tidak harus menunggu semua issue selesai. Setiap issue yang selesai sebaiknya sudah mengikuti design system semampu mungkin agar polish di akhir tidak terlalu berat.
