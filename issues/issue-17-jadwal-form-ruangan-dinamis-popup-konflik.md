# Issue #17 — Update Jadwal Form: Ruangan dari Database + Popup Konfirmasi Konflik

## Tujuan

Mengubah form jadwal (create & edit) agar:
1. Dropdown ruangan diambil dari database (bukan hardcoded)
2. Dropdown ruangan berubah dinamis saat cabang berganti (AJAX)
3. Konflik ruangan menjadi **soft warning** dengan popup konfirmasi (bukan hard block)
4. Konflik tentor tetap **hard block** (perilaku saat ini dipertahankan)
5. Menambahkan catatan audit otomatis saat user melakukan force-override konflik ruangan

## Latar Belakang

Saat ini di `AdminJadwalController`:
- Ruangan di-hardcode berdasarkan nama cabang (`stripos`) — tidak fleksibel
- Konflik ruangan langsung throw `ValidationException` — tidak ada opsi override
- User membutuhkan opsi override karena ada kasus 2 murid di jam & ruang yang sama dengan program berbeda

**Dependency:** Issue #14 (relasi user-cabang), Issue #15 (tabel ruangan)

## Scope

### A. Update `AdminJadwalController` — Hapus hardcoded ruangan

File: `app/Http/Controllers/AdminJadwalController.php`

#### `create()`:
```diff
- // Default room options based on branch (HAPUS)
- if ($selectedCabang && stripos($selectedCabang->nama_cabang, 'Candi') !== false) { ... }
+ // Ambil ruangan dari database
+ $ruangans = Ruangan::where('cabang_id', $selectedCabang->id)
+     ->where('status', 'aktif')
+     ->orderBy('nama_ruangan')
+     ->get();
```

#### `edit()`:
- Sama — hapus hardcoded, ambil dari database
- Include ruangan yang saat ini dipakai jadwal meskipun statusnya nonaktif

#### `store()` dan `update()`:
- Ubah validasi `ruangan` string → `ruangan_id` FK exists
- Pisahkan conflict detection:
  - **Tentor conflict** → tetap hard block (throw `ValidationException`)
  - **Room conflict** → cek parameter `force_room`, jika true maka skip room conflict check
- Jika `force_room = true`, tambahkan catatan audit di field `catatan`:
  ```
  [OVERRIDE] Dijadwalkan meskipun ada konflik ruangan dengan kelas [X] pada jam [Y].
  ```

### B. Tambah API Endpoint: Check Room Conflict (AJAX)

```php
public function checkRoomConflict(Request $request): JsonResponse
```

- Endpoint: `POST /admin/jadwal/check-room-conflict`
- Input: `cabang_id`, `ruangan_id`, `tanggal`, `jam_mulai`, `jam_selesai`, `exclude_id` (opsional)
- Output: `{ has_conflict: bool, conflicts: [...] }`
- Setiap conflict item: `{ nama_kelas, program, jam, tentor }`

### C. Tambah API Endpoint: Get Ruangan by Cabang (AJAX)

```php
public function getRuanganByCabang(Request $request): JsonResponse
```

- Endpoint: `GET /api/ruangan?cabang_id=X`
- Output: array ruangan aktif milik cabang tersebut

### D. Update View Create (`admin/jadwal/create.blade.php`)

1. **Dropdown ruangan** dari `$ruangans` (bukan hardcoded array)
2. **AJAX reload ruangan** saat dropdown cabang berubah
3. **Pre-submit conflict check**:
   - Intercept form submit
   - AJAX call ke `/admin/jadwal/check-room-conflict`
   - Jika ada konflik → tampilkan popup:
     ```
     ⚠️ Ruangan Sudah Digunakan

     Ruangan [Ruang 1] sudah dipakai pada jam [12:00 - 14:00]
     oleh kelas [MO-2609-01] (Program: Microsoft Office)

     Apakah Anda yakin ingin menambahkan jadwal pada jam [12:00 - 14:00]
     di [Ruang 1]?

     [Batal]  [Ya, Lanjutkan]
     ```
   - Jika user klik "Ya, Lanjutkan" → tambahkan hidden input `force_room=1` → submit

4. **Tentor conflict** tetap ditangani server-side (hard block → validation error)

### E. Update View Edit (`admin/jadwal/edit.blade.php`)

- Sama seperti create:
  - Dropdown ruangan dari DB
  - AJAX reload saat cabang berubah
  - Pre-submit conflict check
  - Kirimkan `exclude_id` = jadwal ID yang sedang diedit (agar tidak conflict dengan diri sendiri)

### F. Update View Show (`admin/jadwal/show.blade.php`)

- Tampilkan nama ruangan dari relasi `$jadwal->ruanganRef->nama_ruangan` (fallback ke `$jadwal->ruangan` string lama)
- **Untuk role admin**: Tampilkan catatan tanpa prefix `[OVERRIDE]` (filter out di view)
- **Untuk role superadmin**: Tampilkan catatan lengkap termasuk `[OVERRIDE]` sebagai log audit

### G. Routes Tambahan

File: `routes/web.php`

```php
// Dalam group auth + role:admin,superadmin
Route::post('/admin/jadwal/check-room-conflict', [AdminJadwalController::class, 'checkRoomConflict'])
    ->name('admin.jadwal.check-room-conflict');
Route::get('/api/ruangan', [AdminJadwalController::class, 'getRuanganByCabang'])
    ->name('api.ruangan.by-cabang');
```

### H. Refactor `detectConflicts()` method

Pisahkan menjadi 2 method terpisah:
- `detectTentorConflict(array $data, ?int $excludeId = null): void` — selalu hard block
- `detectRoomConflict(array $data, ?int $excludeId = null): void` — bisa di-skip jika `force_room`

## Files yang Terpengaruh

| File | Aksi |
|------|------|
| `app/Http/Controllers/AdminJadwalController.php` | Edit besar: hapus hardcode, tambah API, refactor conflicts |
| `resources/views/admin/jadwal/create.blade.php` | Edit: dropdown dari DB, AJAX, popup konflik |
| `resources/views/admin/jadwal/edit.blade.php` | Edit: dropdown dari DB, AJAX, popup konflik |
| `resources/views/admin/jadwal/show.blade.php` | Edit: tampilkan nama ruangan dari relasi |
| `routes/web.php` | Edit: tambah 2 route baru |

## Acceptance Criteria

- [x] Dropdown ruangan di form create/edit diambil dari database
- [x] Saat cabang berubah, dropdown ruangan ter-reload otomatis via AJAX
- [x] Konflik tentor tetap hard block (validation error, tidak bisa di-override)
- [x] Konflik ruangan menampilkan popup konfirmasi dengan detail jadwal yang konflik
- [x] User bisa klik "Ya, Lanjutkan" untuk force-override konflik ruangan
- [x] Catatan audit `[OVERRIDE]` otomatis ditambahkan saat force-override
- [x] Catatan `[OVERRIDE]` tidak terlihat oleh admin di halaman detail
- [x] Catatan `[OVERRIDE]` terlihat oleh superadmin di halaman detail sebagai log audit
- [x] Form edit mengirim `exclude_id` agar tidak conflict dengan jadwal yang sedang diedit
- [x] API `/api/ruangan?cabang_id=X` mengembalikan data ruangan aktif yang benar
- [x] Tidak ada regression — validasi lain tetap berfungsi normal
