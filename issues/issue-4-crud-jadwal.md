# Issue #4 — CRUD Jadwal (Admin)

## Deskripsi

Implementasi fitur lengkap pengelolaan jadwal kelas untuk role Admin: Tambah, Lihat Detail, Edit, dan Hapus/Batalkan jadwal. Fitur ini merupakan modul inti operasional sistem penjadwalan Elips Academy.

## Scope

### 1. Tambah Jadwal
- Halaman form untuk membuat jadwal baru
- Field: Program Kursus (dropdown master aktif), Nama/Kode Kelas, Nomor Pertemuan, Jenis Kelas (Private/Rombel/Business), Tanggal, Jam Mulai, Jam Selesai, Ruangan, Tentor Pengajar (dropdown master aktif), Catatan (opsional)
- Validasi server-side: semua field wajib terisi kecuali catatan, `jam_selesai > jam_mulai`
- **Deteksi bentrok tentor**: cek apakah tentor sudah punya jadwal lain di tanggal dan waktu yang beririsan
- **Deteksi bentrok ruangan**: cek apakah ruangan sudah terpakai di tanggal dan waktu yang beririsan
- Tampilkan peringatan jika bentrok ditemukan
- Redirect ke dashboard dengan flash message sukses setelah berhasil simpan

### 2. Detail Jadwal
- Halaman read-only yang menampilkan semua informasi jadwal beserta data relasi (nama program, kategori, cabang, tentor)
- Tombol aksi: Ubah Jadwal, Hapus Jadwal, Kembali ke Dashboard

### 3. Edit Jadwal
- Form yang sama dengan Tambah Jadwal, data lama terisi otomatis (pre-filled)
- Validasi dan cek bentrok tetap berjalan, tetapi **exclude jadwal yang sedang diedit** agar tidak dianggap bentrok dengan dirinya sendiri
- Redirect ke detail jadwal dengan flash message sukses setelah berhasil update

### 4. Hapus / Batalkan Jadwal
- Utamakan pembatalan (ubah status ke `dibatalkan`) daripada delete permanen
- Tampilkan dialog konfirmasi sebelum aksi dilakukan
- Redirect ke dashboard dengan flash message setelah berhasil

## Referensi
- Tambah Jadwal: `PRD & DESIGN.md/PRD_Sistem_Penjadwalan_Kelas_Elips_Academy.md` section 11
- Detail Jadwal: PRD section 12
- Edit Jadwal: PRD section 13
- Hapus/Batalkan: PRD section 14
- Logika Bentrok: PRD section 25
- Mockup Tambah: `PRD & DESIGN.md/DESIGN.md ADMIN/tambah_jadwal_kelas_sederhana_elips_academy/screen.png`
- Mockup Detail: `PRD & DESIGN.md/DESIGN.md ADMIN/detail_jadwal_kelas_elips_academy/screen.png`
- Mockup Edit: `PRD & DESIGN.md/DESIGN.md ADMIN/ubah_jadwal_kelas_elips_academy/screen.png`

## Acceptance Criteria
- [ ] Admin dapat membuat jadwal baru melalui form yang sesuai mockup
- [ ] Validasi server-side berfungsi (field wajib, jam_selesai > jam_mulai)
- [ ] Deteksi bentrok tentor berfungsi dan menampilkan peringatan
- [ ] Deteksi bentrok ruangan berfungsi dan menampilkan peringatan
- [ ] Halaman detail jadwal menampilkan informasi lengkap beserta data relasi
- [ ] Admin dapat mengedit jadwal dengan data lama terisi otomatis
- [ ] Edit jadwal tidak dianggap bentrok dengan dirinya sendiri
- [ ] Admin dapat membatalkan jadwal (status → dibatalkan) dengan konfirmasi
- [ ] Semua aksi hanya bisa dilakukan oleh user dengan role Admin atau Superadmin
- [ ] Tombol "Tambah Jadwal" dan "Ubah" di dashboard Issue #3 terhubung ke halaman yang benar
