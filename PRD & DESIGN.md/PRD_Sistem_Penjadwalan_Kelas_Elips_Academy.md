# PRD --- Sistem Penjadwalan Kelas Elips Academy

**Versi:** 1.0\
**Status:** Draft\
**Platform:** Web Application\
**Framework:** Laravel 11\
**Database:** PHPMyAdmin\
**Role:** Admin dan Superadmin

------------------------------------------------------------------------

## 1. Ringkasan Produk

Sistem Penjadwalan Kelas Elips Academy adalah aplikasi web internal
untuk mengelola jadwal kelas secara terpusat. Sistem membantu admin dan
superadmin membuat, melihat, mengubah, menghapus, serta memantau jadwal
kelas berdasarkan cabang, program kursus, tentor, tanggal, waktu,
ruangan, jenis kelas, dan status jadwal.

Sistem dibuat sederhana dan fokus pada kebutuhan operasional
penjadwalan. Sistem **bukan LMS**, sehingga tidak menangani proses
pembelajaran siswa secara lengkap seperti absensi, pembayaran, materi,
nilai, progress belajar, atau sertifikat.

PRD ini menggabungkan: 1. kebutuhan dan struktur database pada
`Database.md`; 2. rancangan antarmuka Admin pada desain yang diberikan;
3. rancangan antarmuka Superadmin pada desain yang diberikan.

Database menggunakan lima tabel utama: `users`, `cabang`, `program`,
`tentor`, dan `jadwal`. Tabel `jadwal` menjadi pusat transaksi
penjadwalan, sedangkan `cabang`, `program`, dan `tentor` menjadi data
master. Struktur database tersebut secara eksplisit dirancang sederhana
dan tidak sebagai LMS.

------------------------------------------------------------------------

# 2. Tujuan Produk

## 2.1 Tujuan Utama

Membangun satu sistem yang menjadi pusat pengelolaan jadwal kelas Elips
Academy sehingga:

-   jadwal dapat dibuat dan dikelola secara terstruktur;
-   jadwal dapat dilihat dengan cepat;
-   admin dapat mengelola operasional jadwal;
-   superadmin dapat mengelola data master dan akun;
-   bentrok jadwal tentor dan ruangan dapat terdeteksi;
-   jadwal dapat dicari dan difilter;
-   data historis jadwal tetap tersimpan;
-   sistem mudah dikembangkan menggunakan Laravel 11.

## 2.2 Masalah yang Diselesaikan

Sistem ditujukan untuk mengurangi masalah seperti:

-   pencatatan jadwal yang tersebar;
-   kesulitan mengetahui jadwal kelas pada hari tertentu;
-   kesulitan mencari jadwal berdasarkan tentor atau cabang;
-   risiko satu tentor mendapat dua kelas pada waktu yang sama;
-   risiko satu ruangan digunakan oleh dua kelas pada waktu yang sama;
-   perubahan jadwal yang sulit dilacak secara operasional;
-   pengelolaan data cabang, program, dan tentor yang tidak terpusat.

------------------------------------------------------------------------

# 3. Sasaran Pengguna

## 3.1 Admin

Admin merupakan pengguna operasional yang berfokus pada pengelolaan
jadwal.

Kebutuhan utama Admin:

-   login;
-   melihat dashboard;
-   melihat jadwal hari ini;
-   mencari dan memfilter jadwal;
-   menambah jadwal;
-   melihat detail jadwal;
-   mengubah jadwal;
-   menghapus/membatalkan jadwal sesuai hak akses;
-   melihat informasi bentrok jadwal.

## 3.2 Superadmin

Superadmin memiliki akses pengelolaan sistem yang lebih luas.

Kebutuhan utama Superadmin:

-   login;
-   melihat dashboard;
-   melihat keseluruhan jadwal;
-   menambah, mengubah, dan menghapus jadwal;
-   mengelola cabang;
-   mengelola program kursus;
-   mengelola tentor;
-   mengelola akun pengguna;
-   memantau perubahan jadwal;
-   melihat ringkasan kondisi penjadwalan.

Role hanya terdiri dari `admin` dan `superadmin`, dan role disimpan
langsung pada tabel `users`; tidak diperlukan tabel `roles` terpisah
pada versi awal.

------------------------------------------------------------------------

# 4. Scope Produk

## 4.1 In Scope

### Autentikasi

-   Login pengguna.
-   Logout pengguna.
-   Pembatasan akses berdasarkan role.

### Dashboard

-   Ringkasan jadwal.
-   Jadwal hari ini.
-   Informasi jadwal yang relevan.
-   Pintasan ke fungsi utama.

### Jadwal

-   Daftar jadwal.
-   Pencarian jadwal.
-   Filter jadwal.
-   Tambah jadwal.
-   Detail jadwal.
-   Edit jadwal.
-   Hapus atau pembatalan jadwal.
-   Status jadwal.
-   Deteksi bentrok.

### Data Master

-   Cabang.
-   Program kursus.
-   Tentor.
-   Akun pengguna.

### Tampilan Kalender/Jadwal

-   Tampilan daftar/tabel.
-   Tampilan jadwal berdasarkan tanggal.
-   Komponen jadwal berbentuk card/block.
-   Navigasi tanggal/periode apabila digunakan pada halaman kalender.

## 4.2 Out of Scope

Versi pertama tidak mencakup:

-   data siswa;
-   pendaftaran siswa;
-   pembayaran;
-   absensi;
-   materi pembelajaran;
-   nilai;
-   progress siswa;
-   sertifikat;
-   questionnaire;
-   LMS;
-   payroll tentor;
-   sistem keuangan;
-   notifikasi WhatsApp otomatis;
-   payment gateway;
-   pengelolaan ruangan sebagai master data terpisah.

Hal tersebut sesuai dengan batasan database yang diberikan.

------------------------------------------------------------------------

# 5. Struktur Informasi Produk

Struktur navigasi utama:

``` text
Login
│
├── Dashboard
│
├── Jadwal Kelas
│   ├── Daftar Jadwal
│   ├── Tambah Jadwal
│   ├── Detail Jadwal
│   └── Ubah Jadwal
│
└── Superadmin
    ├── Manajemen Cabang
    ├── Manajemen Program Kursus
    ├── Manajemen Tentor
    └── Manajemen Akun Pengguna
```

Admin menggunakan area operasional jadwal.

Superadmin menggunakan area operasional jadwal sekaligus area
pengelolaan data master dan pengguna.

------------------------------------------------------------------------

# 6. Matriks Hak Akses

  Fitur                     Admin   Superadmin
  ----------------------- ------- ------------
  Login                         ✓            ✓
  Dashboard                     ✓            ✓
  Lihat jadwal                  ✓            ✓
  Cari/filter jadwal            ✓            ✓
  Tambah jadwal                 ✓            ✓
  Detail jadwal                 ✓            ✓
  Ubah jadwal                   ✓            ✓
  Hapus/batalkan jadwal       ✓\*            ✓
  Kelola cabang                \-            ✓
  Kelola program               \-            ✓
  Kelola tentor                \-            ✓
  Kelola akun pengguna         \-            ✓

`*` Hak hapus/batal Admin dapat dibatasi sesuai kebijakan implementasi.
Jika tidak dibutuhkan, Admin hanya dapat membuat dan mengubah jadwal.

------------------------------------------------------------------------

# 7. Modul Autentikasi

## 7.1 Halaman Login

Desain menyediakan halaman login Elips Academy dengan konsep card
terpusat.

Elemen:

-   logo/identitas Elips Academy;
-   judul login;
-   input email;
-   input password;
-   tombol masuk;
-   pesan validasi jika login gagal.

## 7.2 Aturan

-   Email harus terdaftar.
-   Password diverifikasi terhadap password hash.
-   Setelah berhasil login, pengguna diarahkan berdasarkan hak aksesnya.
-   User tidak boleh mengakses halaman yang tidak sesuai role.
-   Session harus diamankan menggunakan mekanisme autentikasi Laravel.

Database menetapkan `email` unik dan password wajib disimpan dalam
bentuk hash. fileciteturn0file0L141-L155

------------------------------------------------------------------------

# 8. Modul Dashboard Admin

Desain Admin menunjukkan dashboard yang sederhana dan berfokus pada
jadwal kelas hari ini.

## 8.1 Komponen

### Header

Menampilkan: - identitas aplikasi; - informasi user; - akses logout.

### Ringkasan Jadwal

Dapat menampilkan: - jumlah jadwal hari ini; - jumlah kelas yang sedang
berjalan; - jumlah kelas selesai; - jumlah jadwal yang dibatalkan atau
bermasalah.

### Jadwal Kelas Hari Ini

Menampilkan daftar kelas, minimal:

-   nama kelas;
-   program;
-   tentor;
-   waktu;
-   cabang;
-   ruangan;
-   jenis kelas;
-   status.

### Aksi Cepat

Minimal:

-   Tambah Jadwal;
-   Lihat Semua Jadwal.

------------------------------------------------------------------------

# 9. Modul Dashboard Superadmin

Desain Superadmin menyediakan dashboard dengan cakupan informasi lebih
luas.

## 9.1 Komponen

### Sapaan Pengguna

Menampilkan nama pengguna yang sedang login.

### Jadwal Hari Ini

Menampilkan ringkasan jadwal kelas pada tanggal berjalan.

### Ketersediaan Ruang

Menampilkan informasi penggunaan ruangan berdasarkan jadwal yang
tersedia.

Karena database versi pertama tidak memiliki tabel `ruangan`, informasi
ruangan diambil dari field `jadwal.ruangan`.

### Perubahan Jadwal Terakhir

Menampilkan informasi perubahan atau pembaruan jadwal berdasarkan data
jadwal.

Jika audit log belum tersedia, komponen ini hanya dapat menampilkan data
berdasarkan `updated_at`, bukan riwayat perubahan lengkap.

### Pintasan Cepat Akademik

Pintasan menuju:

-   tambah jadwal;
-   manajemen jadwal;
-   manajemen cabang;
-   manajemen program;
-   manajemen tentor;
-   manajemen akun.

------------------------------------------------------------------------

# 10. Modul Manajemen Jadwal

Ini merupakan modul inti sistem.

## 10.1 Daftar Jadwal

Halaman daftar jadwal menampilkan jadwal dalam bentuk tabel/list.

Kolom yang direkomendasikan:

  Kolom        Keterangan
  ------------ -------------------------
  Nama Kelas   Identitas kelas
  Program      Program kursus
  Jenis        Private/Business/Rombel
  Tentor       Pengajar
  Cabang       Lokasi
  Tanggal      Tanggal kelas
  Waktu        Jam mulai - selesai
  Ruangan      Ruangan
  Pertemuan    Nomor pertemuan
  Status       Status jadwal
  Aksi         Detail/Edit/Hapus

## 10.2 Pencarian

Pencarian dapat dilakukan berdasarkan:

-   nama kelas;
-   program;
-   tentor;
-   cabang.

## 10.3 Filter

Filter minimal:

-   tanggal;
-   cabang;
-   program;
-   tentor;
-   jenis kelas;
-   status.

Database memang menyediakan index pada field-field tersebut untuk
mendukung pencarian/filter jadwal. fileciteturn0file0L623-L638

------------------------------------------------------------------------

# 11. Modul Tambah Jadwal

## 11.1 Form

Field:

1.  Nama kelas
2.  Jenis kelas
3.  Program
4.  Cabang
5.  Tentor
6.  Tanggal
7.  Jam mulai
8.  Jam selesai
9.  Ruangan
10. Pertemuan
11. Status
12. Catatan

## 11.2 Dropdown

Program, cabang, dan tentor harus menggunakan data master dari database.

Hanya data dengan status `aktif` yang dapat dipilih untuk jadwal baru.

Hal ini mengikuti aturan bahwa cabang, program, dan tentor nonaktif
tidak digunakan untuk jadwal baru. fileciteturn0file0L177-L180
fileciteturn0file0L204-L208 fileciteturn0file0L230-L234

## 11.3 Validasi

### Validasi wajib

-   Nama kelas wajib.
-   Jenis kelas wajib.
-   Program wajib.
-   Cabang wajib.
-   Tentor wajib.
-   Tanggal wajib.
-   Jam mulai wajib.
-   Jam selesai wajib.

### Validasi waktu

``` text
jam_selesai > jam_mulai
```

Aturan ini ditetapkan pada database PRD. fileciteturn0file0L517-L539

### Validasi bentrok tentor

Sistem mencari jadwal lain pada:

``` text
tanggal sama
AND tentor sama
AND waktu beririsan
AND jadwal bukan jadwal yang sedang diedit
```

Jika bentrok ditemukan, sistem menampilkan peringatan.

### Validasi bentrok ruangan

Sistem mencari jadwal lain pada:

``` text
tanggal sama
AND cabang sama
AND ruangan sama
AND waktu beririsan
AND jadwal bukan jadwal yang sedang diedit
```

Jika bentrok ditemukan, sistem menampilkan peringatan.

Pengecekan bentrok dilakukan pada level aplikasi. Database menjaga
integritas foreign key dan struktur data.
fileciteturn0file0L539-L585

------------------------------------------------------------------------

# 12. Modul Detail Jadwal

Halaman detail menampilkan informasi lengkap satu jadwal.

Informasi:

-   nama kelas;
-   program;
-   kategori program;
-   jenis kelas;
-   cabang;
-   alamat cabang;
-   tentor;
-   nomor kontak tentor;
-   keahlian tentor;
-   tanggal;
-   jam mulai;
-   jam selesai;
-   durasi;
-   ruangan;
-   pertemuan;
-   status;
-   catatan.

Aksi:

-   Ubah Jadwal;
-   Hapus/Batalkan;
-   Kembali ke daftar.

------------------------------------------------------------------------

# 13. Modul Ubah Jadwal

Form menggunakan struktur yang sama dengan tambah jadwal.

Saat edit:

-   data lama harus otomatis terisi;
-   user dapat mengubah field yang diperlukan;
-   validasi waktu tetap dilakukan;
-   validasi bentrok tetap dilakukan;
-   jadwal yang sedang diedit tidak dianggap sebagai jadwal bentrok
    dengan dirinya sendiri.

Setelah berhasil:

``` text
Jadwal Berhasil Diperbarui
```

------------------------------------------------------------------------

# 14. Penghapusan dan Pembatalan Jadwal

Untuk menjaga riwayat data, penghapusan permanen sebaiknya dibatasi.

Alternatif utama:

``` text
status = dibatalkan
```

Daripada langsung menghapus record.

Status jadwal yang tersedia:

``` text
terjadwal
selesai
dibatalkan
```

Status tersebut sesuai dengan struktur database yang diberikan.
fileciteturn0file0L246-L260

Jika fitur delete tetap disediakan, Superadmin dapat memiliki hak penuh
untuk menghapus data sesuai kebijakan aplikasi.

------------------------------------------------------------------------

# 15. Modul Manajemen Cabang

**Role:** Superadmin.

Halaman digunakan untuk mengelola cabang Elips Academy.

## 15.1 Data

-   Nama cabang;
-   alamat;
-   status;
-   waktu dibuat;
-   waktu diperbarui.

## 15.2 Aksi

-   tambah;
-   lihat;
-   edit;
-   aktif/nonaktif.

## 15.3 Aturan

Cabang yang sudah digunakan oleh jadwal tidak disarankan dihapus
permanen.

Gunakan:

``` text
status = nonaktif
```

Database memang menetapkan pendekatan tersebut untuk mempertahankan
riwayat jadwal. fileciteturn0file0L168-L180

------------------------------------------------------------------------

# 16. Modul Manajemen Program Kursus

**Role:** Superadmin.

## 16.1 Data

-   Nama program;
-   kategori;
-   status;
-   timestamp.

## 16.2 Aksi

-   tambah;
-   edit;
-   aktif/nonaktif;
-   lihat.

## 16.3 Aturan

Program nonaktif tidak dapat digunakan untuk jadwal baru.

Program lama tetap disimpan apabila pernah digunakan oleh jadwal.
fileciteturn0file0L185-L208

Detail harga, kurikulum, dan spesifikasi program tidak menjadi bagian
dari PRD penjadwalan ini.

------------------------------------------------------------------------

# 17. Modul Manajemen Tentor

**Role:** Superadmin.

## 17.1 Data

-   Nama;
-   nomor HP;
-   keahlian;
-   status.

## 17.2 Aksi

-   tambah;
-   edit;
-   aktif/nonaktif;
-   lihat jadwal tentor.

## 17.3 Aturan

Tentor nonaktif tidak dapat digunakan untuk jadwal baru.

Tentor yang memiliki riwayat jadwal tidak disarankan dihapus permanen.
fileciteturn0file0L212-L234

------------------------------------------------------------------------

# 18. Modul Manajemen Akun Pengguna

**Role:** Superadmin.

## 18.1 Data

-   Nama;
-   email;
-   password;
-   role.

## 18.2 Role

``` text
admin
superadmin
```

## 18.3 Aksi

-   tambah akun;
-   edit akun;
-   ubah role;
-   reset password;
-   nonaktifkan akun jika mekanisme status ditambahkan pada versi
    berikutnya.

Database versi awal belum memiliki field status user, sehingga mekanisme
nonaktif user perlu diputuskan pada implementasi apabila diperlukan.
fileciteturn0file0L133-L155

------------------------------------------------------------------------

# 19. Model Data Produk

Database terdiri dari:

``` text
users
cabang
program
tentor
jadwal
```

Relasi:

``` text
cabang 1 ───── N jadwal
program 1 ──── N jadwal
tentor 1 ───── N jadwal

users
└── autentikasi + hak akses
```

Struktur tersebut sesuai dengan database yang diberikan.
fileciteturn0file0L109-L129

## 19.1 Jadwal sebagai Pusat Sistem

Record jadwal menyimpan:

``` text
cabang_id
program_id
tentor_id
nama_kelas
jenis_kelas
tanggal
jam_mulai
jam_selesai
ruangan
pertemuan
status
catatan
```

Field tersebut menjadi sumber data utama untuk dashboard, daftar jadwal,
detail, kalender, pencarian, filter, dan validasi bentrok.
fileciteturn0file0L238-L260

------------------------------------------------------------------------

# 20. Tampilan Kalender dan Timetable

Desain menggunakan pendekatan timetable yang padat tetapi tetap minimal.

## 20.1 Desktop

Pada layar besar:

-   gunakan grid kalender/timetable;
-   jadwal ditampilkan sebagai block/card;
-   waktu menggunakan angka tabular;
-   filter dapat berada di sisi kiri;
-   area jadwal menjadi area utama.

Desain menetapkan grid desktop 12 kolom dan struktur timetable dengan
rail sekitar 240px untuk filter/instruktur serta area jadwal fluid.

## 20.2 Tablet

-   filter dapat berubah menjadi panel/slide-over;
-   timetable tetap dapat digunakan dengan scroll horizontal;
-   ukuran card disesuaikan.

## 20.3 Mobile

-   jadwal multi-kolom berubah menjadi urutan card berdasarkan hari;
-   navigasi hari menggunakan kontrol sebelumnya/berikutnya;
-   informasi inti tetap terlihat tanpa membuka detail.

------------------------------------------------------------------------

# 21. Design System

Desain yang diberikan menggunakan konsep **Academic Precision & Warmth**
dengan perpaduan modern minimalism dan tactile precision.

## 21.1 Warna

Warna utama:

``` text
Primary Orange: #F28E2B
Primary Active: #E67E22
Text:           #1D1D1F
Muted Text:     #6E6E73
Subtle Text:    #86868B
Background:     #F5F5F7
Card:           #FFFFFF
Divider:        #E0E0E0
Soft Divider:   #F0F0F0
Conflict:       #EF4444
Verified:       #10B981
Pending:        #6366F1
```

## 21.2 Typography

Font utama:

``` text
Inter
```

Bobot utama:

``` text
400 Regular
500 Medium
600 Semibold
```

Waktu jadwal, angka, dan informasi numerik menggunakan tabular numerals
agar kolom waktu tetap sejajar.

## 21.3 Bentuk

-   Tombol utama: pill/capsule.
-   Search: pill.
-   Card: radius 12--16px.
-   Schedule block: radius 8px.
-   Input: sekitar 10px.
-   Modal: radius 12--16px.

## 21.4 Elevation

Antarmuka tidak menggunakan shadow berat.

Prioritas visual:

1.  perbedaan warna surface;
2.  border/hairline;
3.  shadow ringan hanya pada modal/dropdown.

------------------------------------------------------------------------

# 22. Komponen UI Utama

## Navigation

-   sidebar/top navigation;
-   menu aktif;
-   informasi akun;
-   logout.

## Button

### Primary

Orange dengan teks putih.

Contoh:

``` text
+ Tambah Jadwal
```

### Secondary

Background putih dengan border tipis.

### Icon Button

Digunakan untuk:

-   edit;
-   delete;
-   previous;
-   next;
-   calendar/list.

## Search

Input berbentuk pill dengan icon pencarian.

## Filter

Gunakan filter pill/chip untuk:

-   Cabang;
-   Program;
-   Tentor;
-   Jenis kelas;
-   Status.

## Status Badge

``` text
Terjadwal
Selesai
Dibatalkan
```

Status konflik menggunakan warna merah.

------------------------------------------------------------------------

# 23. User Flow Admin

``` text
Login
  ↓
Dashboard Admin
  ↓
Lihat Jadwal
  ├── Cari/Filter
  ├── Detail
  ├── Edit
  └── Tambah
        ↓
   Isi Form
        ↓
Validasi Data
        ↓
Cek Bentrok
   ├── Ada bentrok → Tampilkan peringatan
   └── Tidak bentrok
          ↓
     Simpan Jadwal
          ↓
    Berhasil Disimpan
```

------------------------------------------------------------------------

# 24. User Flow Superadmin

``` text
Login
  ↓
Dashboard Superadmin
  ↓
┌───────────────┬───────────────┬──────────────┬───────────────┐
│ Jadwal        │ Cabang        │ Program      │ Tentor        │
└───────────────┴───────────────┴──────────────┴───────────────┘
  │
  └── Akun Pengguna
```

Superadmin dapat masuk ke modul sesuai kebutuhan tanpa harus melewati
dashboard terlebih dahulu.

------------------------------------------------------------------------

# 25. Logika Bentrok Jadwal

## 25.1 Bentrok Waktu

Dua jadwal dianggap beririsan apabila:

``` text
start_A < end_B
AND
end_A > start_B
```

Dengan tanggal yang sama.

## 25.2 Bentrok Tentor

Kondisi:

``` text
tanggal sama
AND tentor_id sama
AND waktu beririsan
```

Sistem menampilkan:

``` text
Conflict Detected
Tentor sudah memiliki jadwal pada waktu tersebut.
```

## 25.3 Bentrok Ruangan

Kondisi:

``` text
tanggal sama
AND cabang_id sama
AND ruangan sama
AND waktu beririsan
```

Sistem menampilkan:

``` text
Conflict Detected
Ruangan sudah digunakan pada waktu tersebut.
```

Database PRD menyatakan bahwa bentrok ditangani oleh aplikasi, bukan
sebagai constraint database. fileciteturn0file0L539-L585

------------------------------------------------------------------------

# 26. Status dan State Sistem

## Jadwal

  Status       Arti
  ------------ ------------------------------------------
  Terjadwal    Jadwal aktif dan akan/dapat dilaksanakan
  Selesai      Kelas sudah selesai
  Dibatalkan   Kelas tidak dilaksanakan

## Master Data

  Status     Arti
  ---------- ---------------------------------
  Aktif      Dapat digunakan
  Nonaktif   Tidak digunakan untuk data baru

------------------------------------------------------------------------

# 27. Notifikasi dan Feedback

Sistem harus memberikan feedback setelah aksi penting.

Contoh:

### Berhasil

``` text
Jadwal berhasil disimpan.
```

``` text
Jadwal berhasil diperbarui.
```

``` text
Data tentor berhasil diperbarui.
```

### Gagal

``` text
Data belum lengkap.
```

``` text
Jam selesai harus lebih besar dari jam mulai.
```

### Konflik

``` text
Jadwal bentrok dengan jadwal tentor pada 09:00–11:00.
```

``` text
Ruangan sedang digunakan pada waktu tersebut.
```

------------------------------------------------------------------------

# 28. Empty State

Jika tidak ada data:

### Tidak ada jadwal

``` text
Belum ada jadwal
Belum terdapat jadwal pada tanggal/filter yang dipilih.
```

### Tidak ada hasil pencarian

``` text
Jadwal tidak ditemukan
Coba ubah kata kunci atau filter.
```

### Belum ada tentor

``` text
Belum ada data tentor.
```

------------------------------------------------------------------------

# 29. Responsive Requirement

## Desktop ≥ 1280px

-   Sidebar/navigation lengkap.
-   Dashboard menggunakan grid.
-   Timetable dapat menampilkan beberapa hari sekaligus.
-   Filter tersedia sebagai panel.

## Tablet 768--1279px

-   Navigation lebih ringkas.
-   Filter dapat menjadi slide-over.
-   Jadwal dapat di-scroll horizontal.

## Mobile ≤ 767px

-   Navigation dapat menjadi drawer.
-   Dashboard menjadi single column.
-   Timetable berubah menjadi card berdasarkan hari.
-   Tombol aksi tetap mudah dijangkau.

Breakpoints dan pendekatan tersebut mengikuti design system yang
diberikan.

------------------------------------------------------------------------

# 30. Functional Requirements

## FR-01 Authentication

Sistem harus dapat melakukan login menggunakan email dan password.

## FR-02 Role Authorization

Sistem harus membatasi akses berdasarkan:

``` text
admin
superadmin
```

## FR-03 Dashboard

Sistem harus menampilkan dashboard berbeda sesuai kebutuhan role.

## FR-04 Jadwal

Sistem harus dapat:

-   membuat jadwal;
-   melihat jadwal;
-   mengubah jadwal;
-   menghapus/membatalkan jadwal.

## FR-05 Search

Sistem harus dapat mencari jadwal.

## FR-06 Filter

Sistem harus dapat memfilter jadwal berdasarkan field yang tersedia.

## FR-07 Conflict Detection

Sistem harus mendeteksi bentrok tentor dan ruangan.

## FR-08 Master Data

Superadmin harus dapat mengelola:

-   cabang;
-   program;
-   tentor.

## FR-09 User Management

Superadmin harus dapat mengelola akun pengguna.

## FR-10 Status

Sistem harus mendukung status data master dan status jadwal.

------------------------------------------------------------------------

# 31. Non-Functional Requirements

## Performance

-   Halaman daftar jadwal harus tetap responsif saat jumlah jadwal
    bertambah.
-   Query filter menggunakan index yang sesuai.
-   Hindari N+1 query pada relasi jadwal.

## Security

-   Password wajib di-hash.
-   Gunakan authentication Laravel.
-   Gunakan authorization middleware/policy.
-   Validasi input di server.
-   Hindari mass assignment yang tidak dikontrol.
-   Gunakan CSRF protection.

## Reliability

-   Foreign key harus menjaga integritas data.
-   Master data yang digunakan jadwal tidak boleh mudah terhapus.
-   Perubahan jadwal harus memperbarui `updated_at`.

## Maintainability

-   Gunakan migration.
-   Gunakan Eloquent Model.
-   Pisahkan controller berdasarkan modul.
-   Gunakan validation/request class jika diperlukan.
-   Gunakan route middleware berdasarkan role.

------------------------------------------------------------------------

# 32. Struktur Laravel yang Direkomendasikan

``` text
app/
├── Models/
│   ├── User.php
│   ├── Cabang.php
│   ├── Program.php
│   ├── Tentor.php
│   └── Jadwal.php
│
├── Http/
│   ├── Controllers/
│   │   ├── DashboardController.php
│   │   ├── JadwalController.php
│   │   ├── CabangController.php
│   │   ├── ProgramController.php
│   │   ├── TentorController.php
│   │   └── UserController.php
│   │
│   └── Requests/
│       └── JadwalRequest.php
│
resources/
└── views/
    ├── auth/
    ├── admin/
    └── superadmin/
```

Struktur tersebut merupakan rekomendasi implementasi berdasarkan modul
pada desain, bukan bagian dari struktur database wajib.

------------------------------------------------------------------------

# 33. Routing yang Direkomendasikan

``` text
/login

/dashboard

/jadwal
/jadwal/create
/jadwal/{id}
/jadwal/{id}/edit

/superadmin/cabang
/superadmin/program
/superadmin/tentor
/superadmin/users
```

Route Superadmin harus dilindungi middleware role.

------------------------------------------------------------------------

# 34. Acceptance Criteria

## Login

-   [ ] User dapat login dengan akun valid.
-   [ ] User dengan password salah mendapat pesan error.
-   [ ] Admin tidak dapat membuka halaman Superadmin.
-   [ ] Superadmin dapat membuka seluruh modul yang diizinkan.

## Dashboard

-   [ ] Dashboard Admin menampilkan jadwal hari ini.
-   [ ] Dashboard Superadmin menampilkan ringkasan sistem.
-   [ ] Data dashboard berasal dari database.

## Jadwal

-   [ ] User dapat membuat jadwal.
-   [ ] Semua field wajib tervalidasi.
-   [ ] Jam selesai tidak boleh lebih kecil/sama dengan jam mulai.
-   [ ] Program/cabang/tentor nonaktif tidak dapat dipilih untuk jadwal
    baru.
-   [ ] User dapat melihat detail.
-   [ ] User dapat mengubah jadwal.
-   [ ] Jadwal dapat dibatalkan atau dihapus sesuai role.

## Bentrok

-   [ ] Bentrok tentor terdeteksi.
-   [ ] Bentrok ruangan terdeteksi.
-   [ ] Sistem menampilkan informasi jadwal yang bentrok.
-   [ ] Saat edit, jadwal yang sedang diedit tidak dihitung sebagai
    bentrok.

## Master Data

-   [ ] Superadmin dapat mengelola cabang.
-   [ ] Superadmin dapat mengelola program.
-   [ ] Superadmin dapat mengelola tentor.
-   [ ] Data master yang digunakan oleh jadwal tidak hilang secara tidak
    sengaja.

## User

-   [ ] Superadmin dapat membuat akun.
-   [ ] Email akun harus unik.
-   [ ] Role hanya admin/superadmin.
-   [ ] Password tersimpan dalam bentuk hash.

------------------------------------------------------------------------

# 35. Database Requirement

Implementasi wajib mengikuti struktur database sumber.

Tabel:

``` text
users
cabang
program
tentor
jadwal
```

Relasi:

``` text
jadwal.cabang_id  → cabang.id
jadwal.program_id → program.id
jadwal.tentor_id  → tentor.id
```

Foreign key dan prinsip restrict pada data master harus dipertahankan.
fileciteturn0file0L487-L513

Migration dibuat berurutan:

``` text
1. create_users_table
2. create_cabang_table
3. create_program_table
4. create_tentor_table
5. create_jadwal_table
```

Urutan tersebut diperlukan karena `jadwal` bergantung pada tiga tabel
master. fileciteturn0file0L774-L790

------------------------------------------------------------------------

# 36. Batasan Database yang Harus Dipertahankan

Jangan menambahkan tabel berikut hanya untuk kebutuhan UI:

``` text
siswa
ruangan
absensi
pembayaran
materi
nilai
sertifikat
```

Khusus ruangan, versi database saat ini menggunakan:

``` text
jadwal.ruangan
```

sebagai text field dan belum membuat tabel `ruangan` terpisah.
fileciteturn0file0L329-L342

Jika kebutuhan sistem berkembang, perubahan struktur dilakukan pada
PRD/database versi berikutnya.

------------------------------------------------------------------------

# 37. Risiko dan Mitigasi

  -----------------------------------------------------------------------
  Risiko                              Mitigasi
  ----------------------------------- -----------------------------------
  Bentrok jadwal tentor               Conflict detection sebelum simpan

  Bentrok ruangan                     Conflict detection berdasarkan
                                      cabang + ruangan + waktu

  Master data terhapus                Gunakan status aktif/nonaktif

  Admin mengakses modul Superadmin    Middleware role

  Data jadwal sulit dicari            Search + filter + index

  Dashboard lambat                    Optimasi query dan eager loading

  Perubahan jadwal tidak diketahui    Gunakan `updated_at`; audit log
                                      dapat ditambahkan di versi
                                      berikutnya
  -----------------------------------------------------------------------

------------------------------------------------------------------------

# 38. Future Development

Fitur berikut dapat dipertimbangkan pada versi selanjutnya:

-   audit log perubahan jadwal;
-   notifikasi perubahan jadwal;
-   integrasi WhatsApp;
-   tabel ruangan;
-   data siswa;
-   absensi;
-   pembayaran;
-   recurring schedule;
-   export Excel/PDF;
-   print jadwal;
-   laporan jadwal;
-   kalender publik/internal;
-   permission yang lebih granular.

Fitur tersebut tidak termasuk versi 1.0.

------------------------------------------------------------------------

# 39. Definition of Done

Sistem dianggap selesai untuk versi 1.0 apabila:

1.  Database lima tabel berhasil digunakan.
2.  Login Admin dan Superadmin berjalan.
3.  Role authorization berjalan.
4.  Dashboard Admin berjalan.
5.  Dashboard Superadmin berjalan.
6.  CRUD jadwal berjalan.
7.  CRUD/management cabang berjalan untuk Superadmin.
8.  CRUD/management program berjalan untuk Superadmin.
9.  CRUD/management tentor berjalan untuk Superadmin.
10. Management akun berjalan untuk Superadmin.
11. Search dan filter jadwal berjalan.
12. Conflict detection tentor berjalan.
13. Conflict detection ruangan berjalan.
14. Responsive layout mengikuti desain yang diberikan.
15. Validasi server berjalan.
16. Foreign key berjalan.
17. Password tersimpan secara aman.
18. Tidak ada modul LMS yang masuk ke scope versi 1.0.

------------------------------------------------------------------------

# 40. Ringkasan Produk

``` text
ELIPS ACADEMY
│
├── AUTHENTICATION
│   └── Login
│
├── ADMIN
│   ├── Dashboard
│   ├── Jadwal
│   │   ├── List
│   │   ├── Tambah
│   │   ├── Detail
│   │   └── Edit
│   └── Logout
│
└── SUPERADMIN
    ├── Dashboard
    ├── Jadwal
    ├── Cabang
    ├── Program
    ├── Tentor
    ├── Akun Pengguna
    └── Logout
```

## Prinsip utama versi 1.0

> **Sederhana, terpusat, mudah digunakan, dan fokus pada penjadwalan
> kelas.**

Sistem tidak perlu berkembang menjadi LMS. Fokus utama tetap pada
pengelolaan jadwal, data master yang dibutuhkan jadwal, pembagian akses
Admin/Superadmin, dan pencegahan bentrok penjadwalan.
