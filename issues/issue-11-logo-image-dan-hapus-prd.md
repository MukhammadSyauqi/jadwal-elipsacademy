# Issue #11 — Ganti Logo ke Image & Hapus Referensi PRD di Seluruh Tampilan

## Tujuan

1. Mengganti **semua logo teks/icon** ("E", icon `school`, tulisan "Elips Academy" sebagai brand) dengan **logo image** (`/images/logo.png`) di seluruh halaman dan role
2. Klik logo harus mengarahkan ke **dashboard utama** sesuai role user
3. Menghapus **semua referensi "PRD"** yang terexpose di tampilan frontend superadmin

## Scope

### A. Ganti Logo — Semua Role

Logo image sudah tersedia di `/public/images/logo.png`. Ganti semua instance logo placeholder dengan `<img>`:

**Admin pages (header logo):**
- Ganti icon `school` + teks "Elips Academy" / lingkaran "E" → `<img src="/images/logo.png" alt="Elips Academy" class="h-8 w-auto">`
- Bungkus dengan `<a>` yang mengarah ke dashboard sesuai role (admin → `admin.dashboard`, superadmin → `superadmin.dashboard`)

**Superadmin pages (sidebar logo):**
- Ganti kotak "E" di sidebar → `<img>` logo
- Sidebar brand harus menjadi link ke `superadmin.dashboard`

**Halaman Login:**
- Gunakan logo image juga jika saat ini masih menggunakan teks/placeholder

### B. Hapus Referensi PRD — Superadmin Views

Terdapat **16 referensi PRD** di 4 file superadmin yang harus dihilangkan atau diganti dengan teks yang user-friendly:

| Teks Lama | Ganti Menjadi |
|-----------|---------------|
| `PRD 14.2: Foreign Key Restrict Berlaku` | `Proteksi Relasi Data Aktif` |
| `Aturan Integritas PRD 14.2` | `Aturan Integritas Data` |
| `Penghapusan Diblokir (PRD 14.2)` | `Penghapusan Diblokir` |
| `Proteksi Relasi PRD` | `Proteksi Relasi Data` |
| `Kebijakan Pembagian Role Sistem (PRD Poin 4)` | `Kebijakan Pembagian Role Sistem` |
| `Matrix Permission (PRD Poin 4)` | `Matrix Permission Hak Akses` |
| `Keamanan & Autentikasi (PRD Poin 6)` | `Keamanan & Autentikasi` |
| `Integritas & Keamanan Akun Laravel 11 (PRD Poin 6)` | `Integritas & Keamanan Akun` |
| Seksi yang memiliki komentar HTML `<!-- PRD Section X -->` | Hapus komentar PRD |

## Files yang Terpengaruh

| File | Aksi |
|------|------|
| `resources/views/admin/dashboard.blade.php` | Ganti logo header |
| `resources/views/admin/jadwal/create.blade.php` | Ganti logo header |
| `resources/views/admin/jadwal/edit.blade.php` | Ganti logo header |
| `resources/views/admin/jadwal/show.blade.php` | Ganti logo header |
| `resources/views/superadmin/dashboard.blade.php` | Ganti logo sidebar |
| `resources/views/superadmin/jadwal/index.blade.php` | Ganti logo sidebar |
| `resources/views/superadmin/cabang/index.blade.php` | Ganti logo sidebar + hapus PRD (4x) |
| `resources/views/superadmin/program/index.blade.php` | Ganti logo sidebar + hapus PRD (3x) |
| `resources/views/superadmin/tentor/index.blade.php` | Ganti logo sidebar + hapus PRD (3x) |
| `resources/views/superadmin/user/index.blade.php` | Ganti logo sidebar + hapus PRD (6x) |
| `resources/views/auth/login.blade.php` | Ganti logo jika masih placeholder |

## Acceptance Criteria

- [ ] Semua halaman menggunakan `logo.png` sebagai brand, bukan teks/icon placeholder
- [ ] Klik logo di admin header mengarah ke `admin.dashboard` (atau `superadmin.dashboard` jika role superadmin)
- [ ] Klik logo di sidebar superadmin mengarah ke `superadmin.dashboard`
- [ ] Tidak ada teks "PRD", "PRD 14.2", "PRD Poin 4", "PRD Poin 6" yang terlihat di frontend
- [ ] Logo tampil proporsional di semua ukuran layar (desktop & mobile)
