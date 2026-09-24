# Issue #9 — Redesign Admin Dashboard & Tambah Field Mode Kelas

## Tujuan

Memperbaiki dashboard admin agar lebih fungsional dan informatif:
1. Menambahkan kolom `mode_kelas` (online/offline) ke database untuk menggantikan tampilan kode kelas
2. Dashboard admin menampilkan jadwal **seluruh cabang** tanpa perlu memilih dropdown cabang di header
3. Card ringkasan "Dibatalkan" diganti menjadi "Akan Datang"
4. Menambahkan filter cabang di area filter (sejajar filter hari & sesi)
5. Menampilkan keterangan cabang yang lebih visible di setiap card jadwal
6. Mengganti tampilan kode kelas (`nama_kelas`) di card jadwal dengan badge **Online/Offline**

## Scope

### A. Database & Model
- Buat migration baru: tambah kolom `mode_kelas` enum(`offline`, `online`) default `offline` pada tabel `jadwal`
- Update model `Jadwal` — tambahkan `mode_kelas` ke `$fillable`
- Jalankan `php artisan migrate`

### B. Controller (`AdminDashboardController`)
- Ubah query agar default menampilkan jadwal **semua cabang** (tanpa filter wajib `cabang_id`)
- Tambahkan parameter opsional `cabang_id` sebagai filter (bukan sebagai selector wajib)
- Ubah summary card: ganti `cancelled` → `upcoming` (hitung jadwal dengan `display_status === 'akan_datang'`)
- Kirim data `cabangs` ke view untuk dropdown filter

### C. View (`admin/dashboard.blade.php`)
- **Header:** Hapus dropdown pemilih cabang dari area brand/logo di header
- **Summary Cards:** Card ke-4 diubah dari "Dibatalkan" (merah) → "Akan Datang" (indigo/biru), icon `upcoming`, hitung jadwal yang statusnya akan datang
- **Filter Bar:** Tambahkan dropdown/select filter cabang di samping filter sesi waktu (Pagi, Siang, Sore, Malam)
- **Card Jadwal:**
  - Hapus tag `nama_kelas` (kode kelas) dari tampilan card
  - Ganti dengan badge **Online** / **Offline** berdasarkan `mode_kelas`
  - Pastikan label cabang terlihat jelas di setiap card (tidak hidden di mobile)
- **Subtitle/Deskripsi:** Sesuaikan teks deskripsi karena sekarang menampilkan semua cabang

### D. Validasi & CRUD (`AdminJadwalController`)
- Tambahkan validasi `mode_kelas` di method `store()` dan `update()`: `'mode_kelas' => 'required|in:offline,online'`
- Tambahkan field `mode_kelas` (radio/pill: Offline | Online) di form **create** dan **edit**
- Tampilkan `mode_kelas` di halaman **show** (detail jadwal)

## Files yang Terpengaruh

| File | Aksi |
|------|------|
| `database/migrations/xxxx_add_mode_kelas_to_jadwal.php` | Buat baru |
| `app/Models/Jadwal.php` | Edit `$fillable` |
| `app/Http/Controllers/AdminDashboardController.php` | Edit query & summary |
| `app/Http/Controllers/AdminJadwalController.php` | Edit validasi store/update |
| `resources/views/admin/dashboard.blade.php` | Edit besar (header, cards, filter, jadwal list) |
| `resources/views/admin/jadwal/create.blade.php` | Tambah field mode_kelas |
| `resources/views/admin/jadwal/edit.blade.php` | Tambah field mode_kelas |
| `resources/views/admin/jadwal/show.blade.php` | Tampilkan mode_kelas |

## Acceptance Criteria

- [x] Migration berhasil dijalankan, kolom `mode_kelas` muncul di tabel `jadwal`
- [x] Dashboard admin menampilkan jadwal dari semua cabang saat pertama kali dibuka
- [x] Filter cabang tersedia dan berfungsi menyaring jadwal per cabang
- [x] Card ringkasan ke-4 menampilkan "Akan Datang" dengan jumlah yang benar
- [x] Kode kelas tidak tampil di card jadwal, diganti badge Online/Offline
- [x] Setiap card jadwal menampilkan keterangan cabang yang terlihat jelas
- [x] Form create & edit memiliki field mode_kelas, detail jadwal menampilkannya
- [x] Tidak ada regression — fitur filter sesi, pencarian, navigasi tanggal tetap berfungsi
