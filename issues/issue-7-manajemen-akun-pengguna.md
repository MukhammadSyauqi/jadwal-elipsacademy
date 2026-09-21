# Issue #7 — Manajemen Akun Pengguna (Superadmin)

## Deskripsi Singkat
Implementasikan fitur pengelolaan akun pengguna (user) yang hanya dapat diakses oleh role Superadmin. Superadmin harus bisa menambah, mengubah, dan melihat daftar user (berperan sebagai Admin atau Superadmin) di dalam sistem.

## Requirement Utama (High-Level)
1. **Daftar Akun Pengguna:** 
   - Tampilkan seluruh user terdaftar dalam bentuk list/tabel beserta panel detail di sebelah kanan (Split Layout).
   
2. **Tambah Akun Baru:**
   - Input: Nama, Email, Password, dan Role (Admin / Superadmin).
   - Validasi: Email harus unik di database dan formatnya harus valid.
   - Keamanan: Password wajib di-hash menggunakan fungsi hash standar framework (misalnya Bcrypt di Laravel).

3. **Edit Akun Pengguna:**
   - Memungkinkan perubahan informasi Nama, Email, dan Role.
   - Pengecekan unik pada Email tetap berlaku (kecuali email pengguna itu sendiri).

4. **Reset Password:**
   - Fasilitas untuk mengubah atau mereset password akun tertentu oleh Superadmin (password baru juga wajib di-hash).

## Referensi & Pedoman UI
- **PRD:** Mengacu pada PRD Section 18.
- **Mockup:** Mengacu pada gambar referensi `SUPERADMIN/manajemen_akun_pengguna_elips_academy/screen.png` yang dijelaskan di `DESIGN.md`.
- **Desain UI:** Gunakan desain yang konsisten dengan manajemen data master yang telah ada (menggunakan Tailwind CSS, warna tema `primary`, status badge, dsb).

## Target Output
Dokumen ini menjadi acuan untuk implementasi CRUD User (seperti pembuatan controller, route, validasi, dan view terkait) yang rapi, aman, dan siap digunakan oleh pengguna Superadmin.
