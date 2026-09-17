# PRD — Database Sistem Penjadwalan Kelas Elips Academy

**Dokumen:** Product Requirements Document (PRD)  
**Versi:** 1.0  
**Status:** Draft  
**Fokus:** Struktur dan kebutuhan database  
**Platform target:** Web Application  
**Database:** PHPMyAdmin  
**Framework target:** Laravel 11  
**Catatan:** PRD ini hanya membahas kebutuhan database untuk sistem penjadwalan kelas. Detail kebutuhan fitur/program aplikasi dibuat dalam PRD terpisah.

---

## 1. Ringkasan

Sistem ini merupakan website internal Elips Academy yang digunakan untuk mengelola dan menampilkan jadwal kelas secara terpusat.

Database dirancang sederhana agar sesuai dengan kebutuhan utama sistem, yaitu:

- mengelola akun pengguna;
- mengelola data cabang;
- mengelola data program kursus;
- mengelola data tentor;
- mengelola jadwal kelas;
- menyediakan data yang dapat digunakan untuk pencarian, filter, kalender, dan pengecekan bentrok jadwal.

Database **tidak dirancang sebagai LMS**. Oleh karena itu, fitur seperti siswa, absensi, pembayaran, materi pembelajaran, progress belajar, sertifikat, dan nilai tidak termasuk dalam scope database versi ini.

---

## 2. Tujuan Database

Database harus mampu:

1. Menyimpan data pengguna sistem dengan dua role: `admin` dan `superadmin`.
2. Menyimpan data cabang Elips Academy.
3. Menyimpan data program kursus.
4. Menyimpan data tentor.
5. Menyimpan jadwal kelas secara terstruktur.
6. Menghubungkan jadwal dengan cabang, program, dan tentor.
7. Mendukung pencarian dan filter jadwal.
8. Mendukung pengecekan jadwal yang berpotensi bentrok.
9. Menyediakan struktur yang sederhana dan mudah dipelihara.
10. Dapat digunakan sebagai dasar migration dan model pada Laravel 11.

---

## 3. Scope

### 3.1 In Scope

Database mencakup:

- User dan role.
- Cabang.
- Program kursus.
- Tentor.
- Jadwal kelas.
- Status data.
- Timestamp pembuatan dan perubahan data.
- Relasi antar tabel.
- Validasi dasar untuk menjaga integritas data.

### 3.2 Out of Scope

Tidak termasuk dalam database versi ini:

- Data siswa.
- Pendaftaran siswa.
- Pembayaran.
- Absensi.
- Materi pembelajaran.
- Nilai.
- Progress siswa.
- Sertifikat.
- Questionnaire.
- LMS.
- Sistem payroll tentor.
- Sistem keuangan.
- Notifikasi WhatsApp otomatis.
- Integrasi payment gateway.

Jika kebutuhan tersebut muncul di masa depan, tabel dapat ditambahkan melalui pengembangan versi berikutnya.

---

# 4. Role Pengguna

Database hanya menggunakan dua role.

| Role | Deskripsi |
|---|---|
| `superadmin` | Memiliki akses pengelolaan sistem secara penuh |
| `admin` | Mengelola operasional jadwal dan data yang diberikan akses |

Role disimpan langsung pada tabel `users`.

Tidak diperlukan tabel `roles` terpisah untuk versi awal karena hanya terdapat dua role.

### Nilai yang diperbolehkan

```text
admin
superadmin
```

---

# 5. Struktur Database

Database terdiri dari lima tabel utama:

```text
users
cabang
program
tentor
jadwal
```

Relasi utamanya:

```text
cabang 1 ──────── N jadwal
program 1 ─────── N jadwal
tentor 1 ──────── N jadwal
```

Sedangkan `users` digunakan untuk autentikasi dan hak akses.

---

# 6. Tabel `users`

## Tujuan

Menyimpan akun yang dapat mengakses sistem.

## Struktur

| Field | Type | Null | Key | Default | Keterangan |
|---|---|---:|---|---|---|
| `id` | BIGINT UNSIGNED | NO | PK | - | ID pengguna |
| `nama` | VARCHAR(100) | NO | - | - | Nama pengguna |
| `email` | VARCHAR(100) | NO | UNIQUE | - | Email login |
| `password` | VARCHAR(255) | NO | - | - | Password yang sudah di-hash |
| `role` | ENUM('admin','superadmin') | NO | INDEX | `admin` | Hak akses pengguna |
| `created_at` | TIMESTAMP | YES | - | NULL | Waktu dibuat |
| `updated_at` | TIMESTAMP | YES | - | NULL | Waktu diperbarui |

## Aturan

- Email harus unik.
- Password wajib disimpan dalam bentuk hash.
- Role hanya boleh `admin` atau `superadmin`.
- User yang sudah tidak digunakan dapat dinonaktifkan pada pengembangan berikutnya apabila dibutuhkan.

---

# 7. Tabel `cabang`

## Tujuan

Menyimpan informasi cabang Elips Academy yang digunakan sebagai lokasi pelaksanaan kelas.

## Struktur

| Field | Type | Null | Key | Default | Keterangan |
|---|---|---:|---|---|---|
| `id` | BIGINT UNSIGNED | NO | PK | - | ID cabang |
| `nama_cabang` | VARCHAR(100) | NO | - | - | Nama cabang |
| `alamat` | TEXT | YES | - | NULL | Alamat cabang |
| `status` | ENUM('aktif','nonaktif') | NO | INDEX | `aktif` | Status cabang |
| `created_at` | TIMESTAMP | YES | - | NULL | Waktu dibuat |
| `updated_at` | TIMESTAMP | YES | - | NULL | Waktu diperbarui |

## Aturan

- Nama cabang wajib diisi.
- Cabang nonaktif tidak digunakan untuk membuat jadwal baru.
- Data cabang lama tidak langsung dihapus jika masih memiliki riwayat jadwal; status dapat diubah menjadi `nonaktif`.

---

# 8. Tabel `program`

## Tujuan

Menyimpan daftar program kursus yang digunakan oleh jadwal.

> Detail spesifikasi program, kategori program, harga, kurikulum, dan fitur pengelolaan program dibahas dalam PRD Program terpisah.

## Struktur minimum

| Field | Type | Null | Key | Default | Keterangan |
|---|---|---:|---|---|---|
| `id` | BIGINT UNSIGNED | NO | PK | - | ID program |
| `nama_program` | VARCHAR(150) | NO | - | - | Nama program |
| `kategori` | VARCHAR(100) | YES | INDEX | NULL | Kategori program |
| `status` | ENUM('aktif','nonaktif') | NO | INDEX | `aktif` | Status program |
| `created_at` | TIMESTAMP | YES | - | NULL | Waktu dibuat |
| `updated_at` | TIMESTAMP | YES | - | NULL | Waktu diperbarui |

## Aturan

- Nama program wajib diisi.
- Program nonaktif tidak digunakan untuk jadwal baru.
- Program tetap disimpan jika pernah digunakan oleh jadwal sebelumnya.

---

# 9. Tabel `tentor`

## Tujuan

Menyimpan data tentor yang mengajar kelas.

## Struktur

| Field | Type | Null | Key | Default | Keterangan |
|---|---|---:|---|---|---|
| `id` | BIGINT UNSIGNED | NO | PK | - | ID tentor |
| `nama` | VARCHAR(150) | NO | - | - | Nama tentor |
| `no_hp` | VARCHAR(20) | YES | - | NULL | Nomor telepon/WhatsApp |
| `keahlian` | VARCHAR(255) | YES | - | NULL | Keahlian tentor |
| `status` | ENUM('aktif','nonaktif') | NO | INDEX | `aktif` | Status tentor |
| `created_at` | TIMESTAMP | YES | - | NULL | Waktu dibuat |
| `updated_at` | TIMESTAMP | YES | - | NULL | Waktu diperbarui |

## Aturan

- Nama tentor wajib diisi.
- Tentor nonaktif tidak dapat dipilih untuk jadwal baru.
- Data tentor yang memiliki riwayat jadwal sebaiknya tidak dihapus secara permanen.

---

# 10. Tabel `jadwal`

## Tujuan

Merupakan tabel utama sistem. Tabel ini menyimpan setiap jadwal kelas yang dilaksanakan oleh Elips Academy.

## Struktur

| Field | Type | Null | Key | Default | Keterangan |
|---|---|---:|---|---|---|
| `id` | BIGINT UNSIGNED | NO | PK | - | ID jadwal |
| `cabang_id` | BIGINT UNSIGNED | NO | FK, INDEX | - | Cabang pelaksanaan |
| `program_id` | BIGINT UNSIGNED | NO | FK, INDEX | - | Program kursus |
| `tentor_id` | BIGINT UNSIGNED | NO | FK, INDEX | - | Tentor pengajar |
| `nama_kelas` | VARCHAR(100) | NO | INDEX | - | Nama/kode kelas |
| `jenis_kelas` | ENUM('private','business','rombel') | NO | INDEX | - | Jenis kelas |
| `tanggal` | DATE | NO | INDEX | - | Tanggal pelaksanaan |
| `jam_mulai` | TIME | NO | - | - | Jam mulai |
| `jam_selesai` | TIME | NO | - | - | Jam selesai |
| `ruangan` | VARCHAR(100) | YES | - | NULL | Nama/keterangan ruangan |
| `pertemuan` | INT UNSIGNED | YES | - | NULL | Nomor pertemuan |
| `status` | ENUM('terjadwal','selesai','dibatalkan') | NO | INDEX | `terjadwal` | Status jadwal |
| `catatan` | TEXT | YES | - | NULL | Catatan tambahan |
| `created_at` | TIMESTAMP | YES | - | NULL | Waktu dibuat |
| `updated_at` | TIMESTAMP | YES | - | NULL | Waktu diperbarui |

---

# 11. Penjelasan Field Jadwal

### `nama_kelas`

Digunakan sebagai identitas kelas yang tampil pada website.

Contoh:

```text
MO-001
WP-001
GD-001
DM-001
```

Nama kelas tidak harus sama dengan nama program.

### `jenis_kelas`

Nilai yang diperbolehkan:

```text
private
business
rombel
```

### `tanggal`

Menentukan tanggal pelaksanaan kelas.

Format:

```text
YYYY-MM-DD
```

### `jam_mulai`

Menentukan waktu dimulainya kelas.

Contoh:

```text
09:00:00
```

### `jam_selesai`

Menentukan waktu berakhirnya kelas.

Contoh:

```text
11:00:00
```

Validasi:

```text
jam_selesai > jam_mulai
```

### `ruangan`

Untuk versi sederhana, ruangan disimpan langsung sebagai teks.

Contoh:

```text
Ruang 1
Ruang 2
Lab Komputer
Kelas Private
```

Tidak diperlukan tabel `ruangan` khusus pada versi awal.

### `pertemuan`

Digunakan apabila sebuah kelas memiliki beberapa pertemuan.

Contoh:

```text
Pertemuan 1
Pertemuan 2
Pertemuan 3
...
Pertemuan 9
```

Field ini boleh `NULL` jika sistem tidak membutuhkan nomor pertemuan pada jadwal tertentu.

### `status`

Nilai:

```text
terjadwal
selesai
dibatalkan
```

---

# 12. Relasi Antar Tabel

## `cabang` → `jadwal`

Satu cabang dapat memiliki banyak jadwal.

```text
cabang.id
     ↓
jadwal.cabang_id
```

Relasi:

```text
1 : N
```

---

## `program` → `jadwal`

Satu program dapat digunakan pada banyak jadwal.

```text
program.id
     ↓
jadwal.program_id
```

Relasi:

```text
1 : N
```

---

## `tentor` → `jadwal`

Satu tentor dapat mengajar banyak jadwal.

```text
tentor.id
     ↓
jadwal.tentor_id
```

Relasi:

```text
1 : N
```

---

# 13. ERD

```text
┌──────────────────┐
│      users       │
├──────────────────┤
│ PK id            │
│ nama             │
│ email            │
│ password         │
│ role             │
└──────────────────┘


┌──────────────────┐
│      cabang      │
├──────────────────┤
│ PK id            │
│ nama_cabang      │
│ alamat           │
│ status           │
└────────┬─────────┘
         │
         │ 1:N
         ▼
┌──────────────────────────┐
│          jadwal          │
├──────────────────────────┤
│ PK id                    │
│ FK cabang_id             │
│ FK program_id            │
│ FK tentor_id             │
│ nama_kelas               │
│ jenis_kelas              │
│ tanggal                  │
│ jam_mulai                │
│ jam_selesai              │
│ ruangan                  │
│ pertemuan                │
│ status                   │
│ catatan                  │
└───────┬───────────┬──────┘
        │           │
      N:1         N:1
        │           │
        ▼           ▼
┌──────────────┐ ┌──────────────┐
│   program    │ │    tentor    │
├──────────────┤ ├──────────────┤
│ PK id        │ │ PK id        │
│ nama_program │ │ nama         │
│ kategori     │ │ no_hp        │
│ status       │ │ keahlian     │
└──────────────┘ │ status       │
                 └──────────────┘
```

---

# 14. Aturan Integritas Data

## 14.1 Foreign Key

Tabel `jadwal` memiliki foreign key:

```text
jadwal.cabang_id  → cabang.id
jadwal.program_id → program.id
jadwal.tentor_id  → tentor.id
```

## 14.2 Penghapusan Data

Disarankan menggunakan prinsip **restrict** atau mencegah penghapusan data master yang masih digunakan oleh jadwal.

Contoh:

Jika `tentor.id = 5` masih digunakan pada jadwal, data tentor tersebut tidak boleh dihapus secara permanen.

Solusi:

```text
status = nonaktif
```

Dengan cara ini riwayat jadwal tetap aman.

---

# 15. Validasi Jadwal

Sistem harus melakukan validasi sebelum menyimpan atau mengubah jadwal.

## 15.1 Validasi waktu

```text
jam_selesai > jam_mulai
```

Contoh valid:

```text
09:00 - 11:00
```

Contoh tidak valid:

```text
11:00 - 09:00
```

## 15.2 Bentrok tentor

Satu tentor tidak boleh memiliki dua jadwal yang waktunya bertabrakan pada tanggal yang sama.

Contoh:

```text
Tentor Budi
09:00 - 11:00
Microsoft Office

Tentor Budi
10:00 - 12:00
Web Programming
```

Sistem harus menandai sebagai bentrok.

## 15.3 Bentrok ruangan

Pada cabang yang sama, satu ruangan tidak boleh digunakan oleh dua jadwal yang waktunya bertabrakan.

Contoh:

```text
Candi - Ruang 1
09:00 - 11:00
```

tidak boleh bersamaan dengan:

```text
Candi - Ruang 1
10:00 - 12:00
```

## 15.4 Bentrok tidak selalu berarti data tidak dapat disimpan

Pada versi awal, pengecekan bentrok dapat dilakukan di level aplikasi.

Sistem dapat:

1. mendeteksi bentrok;
2. menampilkan informasi bentrok;
3. meminta konfirmasi atau mencegah penyimpanan sesuai aturan aplikasi.

Database bertugas menjaga integritas relasi, sedangkan logika bentrok dikerjakan oleh aplikasi.

---

# 16. Index Database

Index yang disarankan:

### `users`

```text
PRIMARY KEY (id)
UNIQUE (email)
INDEX (role)
```

### `cabang`

```text
PRIMARY KEY (id)
INDEX (status)
```

### `program`

```text
PRIMARY KEY (id)
INDEX (status)
INDEX (kategori)
```

### `tentor`

```text
PRIMARY KEY (id)
INDEX (status)
```

### `jadwal`

```text
PRIMARY KEY (id)
INDEX (cabang_id)
INDEX (program_id)
INDEX (tentor_id)
INDEX (tanggal)
INDEX (jenis_kelas)
INDEX (status)
```

Untuk pencarian jadwal berdasarkan tanggal dan cabang, dapat dipertimbangkan composite index:

```text
INDEX (tanggal, cabang_id)
```

---

# 17. Contoh Data

## Users

| id | nama | email | role |
|---:|---|---|---|
| 1 | Super Admin | superadmin@elipsacademy.com | superadmin |
| 2 | Admin Candi | admin.candi@elipsacademy.com | admin |

## Cabang

| id | nama_cabang | status |
|---:|---|---|
| 1 | Buduran | aktif |
| 2 | Candi | aktif |

## Program

| id | nama_program | kategori | status |
|---:|---|---|---|
| 1 | Microsoft Office | Office | aktif |
| 2 | Web Programming | Programming | aktif |
| 3 | Graphic Design | Design | aktif |

## Tentor

| id | nama | keahlian | status |
|---:|---|---|---|
| 1 | Budi | Microsoft Office | aktif |
| 2 | Andi | Programming | aktif |
| 3 | Rina | Graphic Design | aktif |

## Jadwal

| id | cabang | program | tentor | kelas | jenis | tanggal | waktu | ruangan | pertemuan | status |
|---:|---|---|---|---|---|---|---|---|---:|---|
| 1 | Candi | Microsoft Office | Budi | MO-001 | private | 2026-09-20 | 09:00-11:00 | Ruang 1 | 1 | terjadwal |
| 2 | Buduran | Web Programming | Andi | WP-001 | rombel | 2026-09-20 | 13:00-15:00 | Ruang 2 | 1 | terjadwal |

---

# 18. Contoh SQL Sederhana

```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'superadmin') NOT NULL DEFAULT 'admin',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

```sql
CREATE TABLE cabang (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama_cabang VARCHAR(100) NOT NULL,
    alamat TEXT NULL,
    status ENUM('aktif', 'nonaktif') NOT NULL DEFAULT 'aktif',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

```sql
CREATE TABLE program (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama_program VARCHAR(150) NOT NULL,
    kategori VARCHAR(100) NULL,
    status ENUM('aktif', 'nonaktif') NOT NULL DEFAULT 'aktif',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

```sql
CREATE TABLE tentor (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(150) NOT NULL,
    no_hp VARCHAR(20) NULL,
    keahlian VARCHAR(255) NULL,
    status ENUM('aktif', 'nonaktif') NOT NULL DEFAULT 'aktif',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

```sql
CREATE TABLE jadwal (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    cabang_id BIGINT UNSIGNED NOT NULL,
    program_id BIGINT UNSIGNED NOT NULL,
    tentor_id BIGINT UNSIGNED NOT NULL,

    nama_kelas VARCHAR(100) NOT NULL,
    jenis_kelas ENUM('private', 'business', 'rombel') NOT NULL,

    tanggal DATE NOT NULL,
    jam_mulai TIME NOT NULL,
    jam_selesai TIME NOT NULL,

    ruangan VARCHAR(100) NULL,
    pertemuan INT UNSIGNED NULL,

    status ENUM('terjadwal', 'selesai', 'dibatalkan')
        NOT NULL DEFAULT 'terjadwal',

    catatan TEXT NULL,

    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    CONSTRAINT fk_jadwal_cabang
        FOREIGN KEY (cabang_id)
        REFERENCES cabang(id),

    CONSTRAINT fk_jadwal_program
        FOREIGN KEY (program_id)
        REFERENCES program(id),

    CONSTRAINT fk_jadwal_tentor
        FOREIGN KEY (tentor_id)
        REFERENCES tentor(id)
);
```

---

# 19. Laravel Migration

Struktur database harus dapat diterjemahkan menjadi migration Laravel 11.

Urutan migration yang disarankan:

```text
1. create_users_table
2. create_cabang_table
3. create_program_table
4. create_tentor_table
5. create_jadwal_table
```

Tabel `jadwal` dibuat setelah tabel master karena memiliki foreign key ke:

```text
cabang
program
tentor
```

---

# 20. Model Laravel

Model utama:

```text
User
Cabang
Program
Tentor
Jadwal
```

Relasi model:

### Cabang

```php
public function jadwals()
{
    return $this->hasMany(Jadwal::class);
}
```

### Program

```php
public function jadwals()
{
    return $this->hasMany(Jadwal::class);
}
```

### Tentor

```php
public function jadwals()
{
    return $this->hasMany(Jadwal::class);
}
```

### Jadwal

```php
public function cabang()
{
    return $this->belongsTo(Cabang::class);
}

public function program()
{
    return $this->belongsTo(Program::class);
}

public function tentor()
{
    return $this->belongsTo(Tentor::class);
}
```

---

# 21. Kriteria Penerimaan Database

Database dianggap memenuhi kebutuhan apabila:

- [ ] User dapat dibedakan berdasarkan role `admin` dan `superadmin`.
- [ ] Email user bersifat unik.
- [ ] Data cabang dapat disimpan.
- [ ] Data program dapat disimpan.
- [ ] Data tentor dapat disimpan.
- [ ] Jadwal dapat menyimpan tanggal dan waktu.
- [ ] Jadwal dapat dikaitkan dengan cabang.
- [ ] Jadwal dapat dikaitkan dengan program.
- [ ] Jadwal dapat dikaitkan dengan tentor.
- [ ] Jadwal dapat memiliki jenis `private`, `business`, atau `rombel`.
- [ ] Jadwal dapat memiliki status `terjadwal`, `selesai`, atau `dibatalkan`.
- [ ] Jadwal dapat memiliki nomor pertemuan.
- [ ] Data master yang sudah digunakan pada jadwal tidak mudah hilang karena penghapusan.
- [ ] Database mendukung pencarian/filter jadwal berdasarkan tanggal, cabang, program, tentor, jenis kelas, dan status.
- [ ] Struktur dapat digunakan pada Laravel 11 dan MySQL/MariaDB.

---

# 22. Prinsip Desain Database

Database versi 1.0 menggunakan prinsip:

### Sederhana

Hanya menyimpan data yang benar-benar dibutuhkan untuk sistem penjadwalan.

### Terstruktur

Data master dipisahkan dari data transaksi/jadwal.

### Tidak berlebihan

Tidak membuat tabel untuk siswa, absensi, pembayaran, ruangan, atau modul LMS yang belum dibutuhkan.

### Mudah dikembangkan

Jika kebutuhan bertambah, tabel baru dapat ditambahkan tanpa mengubah struktur dasar secara besar-besaran.

---

# 23. Ringkasan Struktur Final

```text
DATABASE ELIPS ACADEMY
│
├── users
│   ├── id
│   ├── nama
│   ├── email
│   ├── password
│   └── role
│
├── cabang
│   ├── id
│   ├── nama_cabang
│   ├── alamat
│   └── status
│
├── program
│   ├── id
│   ├── nama_program
│   ├── kategori
│   └── status
│
├── tentor
│   ├── id
│   ├── nama
│   ├── no_hp
│   ├── keahlian
│   └── status
│
└── jadwal
    ├── id
    ├── cabang_id
    ├── program_id
    ├── tentor_id
    ├── nama_kelas
    ├── jenis_kelas
    ├── tanggal
    ├── jam_mulai
    ├── jam_selesai
    ├── ruangan
    ├── pertemuan
    ├── status
    └── catatan
```

## Kesimpulan

Untuk kebutuhan **website penjadwalan kelas sederhana Elips Academy**, struktur lima tabel (`users`, `cabang`, `program`, `tentor`, `jadwal`) sudah cukup.

Tabel `jadwal` menjadi pusat sistem, sedangkan `cabang`, `program`, dan `tentor` menjadi data master. `users` hanya digunakan untuk autentikasi dan pembagian hak akses `admin` serta `superadmin`.

Detail pengelolaan **program kursus** sengaja tidak diperluas dalam dokumen ini karena akan dibuat dalam PRD terpisah.
