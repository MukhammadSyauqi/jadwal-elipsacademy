# Issue #12 — Perbaikan Icon Dobel, Konsistensi UI & Polish Superadmin

## Tujuan

Memperbaiki berbagai inkonsistensi visual yang tersebar di seluruh aplikasi:
1. Menghilangkan **icon yang dobel/redundan** di semua role
2. Memperbaiki **posisi icon** yang kurang pas
3. Menyamakan **konsistensi UI** di sidebar dan halaman superadmin
4. Merapikan elemen-elemen visual yang belum sinkron

## Scope

### A. Fix Icon Dobel & Redundan

Audit dan perbaiki icon-icon berikut:

| Lokasi | Masalah | Solusi |
|--------|---------|-------|
| Admin dashboard — Tombol "+ Tambah Jadwal" | Icon `add` (➕) + teks `"+ Tambah Jadwal"` → simbol + muncul dua kali | Hapus "+" dari teks, jadikan `"Tambah Jadwal"` saja atau hapus icon |
| Superadmin dashboard — Tombol "+ Tambah Jadwal Baru" | Sama: icon + teks "+" redundan | Sama: pilih salah satu |
| Cek semua tombol aksi di seluruh halaman | Pastikan tidak ada icon + teks yang menyampaikan hal yang sama | Fix jika ditemukan |

### B. Konsistensi Sidebar Superadmin

Saat ini sidebar superadmin memiliki inkonsistensi antar halaman:

| Aspek | Dashboard | Jadwal Index | Cabang/Program/Tentor/User |
|-------|-----------|-------------|----------------------------|
| Subtitle sidebar | "Superadmin Panel" | "Course Scheduler" | Perlu dicek |
| Active nav style | `bg-primary-container text-white` | `bg-accent-subtle text-primary` | Perlu dicek |
| Cabang indicator | "Hak Akses: Lintas Seluruh Cabang" | "Cabang: Buduran & Candi" (hardcoded) | Perlu dicek |

**Yang harus disamakan:**
- Subtitle sidebar: gunakan **"Superadmin Panel"** di semua halaman
- Active nav style: gunakan **satu style konsisten** (rekomendasikan `bg-primary-container text-white`)
- Cabang indicator: samakan format, hindari hardcode nama cabang

### C. Polish Tambahan

- Pastikan **footer** ada di semua halaman dan konsisten formatnya
- Pastikan teks yang tidak perlu ditampilkan ke end-user (istilah teknis, komentar developer) sudah dibersihkan
- Cek bahwa semua icon Material Symbols yang digunakan memang sesuai konteksnya

## Files yang Terpengaruh

| File | Aksi |
|------|------|
| `resources/views/admin/dashboard.blade.php` | Fix icon dobel di tombol Tambah Jadwal |
| `resources/views/superadmin/dashboard.blade.php` | Fix icon dobel + fix sidebar |
| `resources/views/superadmin/jadwal/index.blade.php` | Fix sidebar (subtitle, active state, indicator) |
| `resources/views/superadmin/cabang/index.blade.php` | Fix sidebar konsistensi |
| `resources/views/superadmin/program/index.blade.php` | Fix sidebar konsistensi |
| `resources/views/superadmin/tentor/index.blade.php` | Fix sidebar konsistensi |
| `resources/views/superadmin/user/index.blade.php` | Fix sidebar konsistensi |

## Acceptance Criteria

- [ ] Tidak ada icon yang muncul dobel (icon + teks mengatakan hal yang sama)
- [ ] Semua sidebar superadmin memiliki subtitle, active nav style, dan indicator cabang yang sama
- [ ] Footer konsisten di semua halaman
- [ ] Tidak ada teks teknis/developer-facing yang terexpose ke user
- [ ] Semua icon relevan dengan konteks penggunaannya
