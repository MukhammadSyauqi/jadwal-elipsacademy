# Issue #10 — Perbaikan Layout CRUD Admin & Tombol Navigasi

## Tujuan

Memperbaiki tata letak halaman CRUD jadwal (create, edit, show) pada role admin:
1. Memindahkan tombol "Kembali ke Jadwal" dari **header** ke **area konten** (di atas judul halaman)
2. Merapikan header agar lebih bersih — hanya logo, profil, dan logout

## Scope

### A. Pindahkan Tombol "Kembali"
Pada 3 halaman CRUD admin (`create`, `edit`, `show`), tombol "Kembali ke Jadwal" / "Kembali ke Detail" saat ini berada di tengah header (antara logo dan profil user). Pindahkan ke area konten utama:

- Hapus tombol kembali dari `<header>`
- Letakkan sebagai elemen pertama di dalam `<main>`, tepat sebelum judul halaman (`<h1>`)
- Gunakan style yang konsisten: pill button dengan icon `arrow_back`

### B. Rapikan Header CRUD
Setelah tombol kembali dipindahkan, header CRUD menjadi lebih simpel:
- Kiri: Logo (akan diubah di Issue #11)
- Kanan: Info profil user + tombol logout
- Tidak ada elemen di tengah

## Files yang Terpengaruh

| File | Aksi |
|------|------|
| `resources/views/admin/jadwal/create.blade.php` | Pindah tombol kembali dari header ke main |
| `resources/views/admin/jadwal/edit.blade.php` | Pindah tombol kembali dari header ke main |
| `resources/views/admin/jadwal/show.blade.php` | Pindah tombol kembali dari header ke main |

## Acceptance Criteria

- [ ] Tombol "Kembali" tidak lagi ada di header pada ketiga halaman CRUD
- [ ] Tombol "Kembali" muncul di area konten, di atas judul halaman
- [ ] Klik tombol kembali mengarah ke halaman yang benar (dashboard / detail)
- [ ] Header tampil bersih: hanya logo di kiri dan profil+logout di kanan
