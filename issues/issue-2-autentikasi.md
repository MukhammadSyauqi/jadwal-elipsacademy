# Issue #2 — Autentikasi (Login & Logout)

## Deskripsi

Implementasi sistem login dan logout untuk Elips Academy. Sistem hanya memiliki dua role: `admin` dan `superadmin`. Setelah login, user diarahkan ke dashboard sesuai role masing-masing. Halaman-halaman tertentu hanya bisa diakses oleh role yang berhak.

## Scope

### 1. Halaman Login
- Buat halaman login sesuai mockup yang tersedia
- Form login menggunakan **email** dan **password**
- Tampilkan pesan error jika login gagal (email tidak terdaftar atau password salah)
- Jika user sudah login, redirect otomatis ke dashboard (tidak perlu login ulang)

### 2. Redirect Berdasarkan Role
- Setelah login berhasil:
  - Role `admin` → diarahkan ke halaman dashboard admin
  - Role `superadmin` → diarahkan ke halaman dashboard superadmin
- Untuk sementara, halaman dashboard bisa berupa halaman kosong/placeholder dengan teks yang menunjukkan role user yang sedang login

### 3. Middleware Role
- Buat middleware untuk membatasi akses halaman berdasarkan role
- Halaman admin hanya bisa diakses oleh user dengan role `admin` atau `superadmin`
- Halaman superadmin hanya bisa diakses oleh user dengan role `superadmin`
- Jika user mencoba mengakses halaman yang bukan haknya, tampilkan response 403 (Forbidden)

### 4. Logout
- Tombol/link logout yang menghapus session dan mengarahkan kembali ke halaman login

### 5. Proteksi Route
- Semua route selain login harus dilindungi oleh middleware `auth`
- User yang belum login akan otomatis diarahkan ke halaman login

## Referensi
- Autentikasi & role: `PRD & DESIGN.md/PRD_Sistem_Penjadwalan_Kelas_Elips_Academy.md` section 7 (Autentikasi)
- Matriks hak akses: `PRD & DESIGN.md/PRD_Sistem_Penjadwalan_Kelas_Elips_Academy.md` section 6
- Mockup login: `PRD & DESIGN.md/DESIGN.md ADMIN/halaman_login_elips_academy/screen.png`

## Acceptance Criteria
- [x] Halaman login tampil sesuai mockup
- [x] Login berhasil dengan email dan password yang benar
- [x] Login gagal menampilkan pesan error yang jelas
- [x] Redirect setelah login sesuai role (admin → dashboard admin, superadmin → dashboard superadmin)
- [x] Middleware role mencegah akses ke halaman yang bukan hak user (response 403)
- [x] Logout berhasil menghapus session dan redirect ke halaman login
- [x] User yang belum login tidak bisa mengakses halaman selain login
