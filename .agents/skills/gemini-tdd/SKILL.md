---
name: gemini-tdd
description: >-
  Panduan dan alur eksekusi Test-Driven Development (TDD) bersama Google Gemini / Antigravity untuk penulisan kode berkualitas tinggi melalui siklus Red-Green-Refactor. Gunakan skill ini ketika merancang fitur baru, membuat automated tests (Unit/Feature Test), refaktorisasi, atau ketika pengguna menyebut TDD, gemini tdd, gemini ttd, atau pengujian otomatis.
---

# Gemini TDD (Test-Driven Development) Workflow

Skill ini memandu AI Agent dan Developer dalam menerapkan metodologi **Test-Driven Development (TDD)** secara disiplin, presisi, dan terstruktur pada proyek perangkat lunak.

---

## 1. Siklus Inti TDD: Red - Green - Refactor

```
 ┌────────────────┐        ┌────────────────┐        ┌────────────────┐
 │     1. RED     │ ────>  │    2. GREEN    │ ────>  │  3. REFACTOR   │
 │ Tulis Test &   │        │ Tulis Kode     │        │ Rapikan Kode & │
 │ Pastikan Gagal │        │ Minimal Lolos  │        │ Pertahankan    │
 └────────────────┘        └────────────────┘        │ Status Hijau   │
         ▲                                           └────────────────┘
         └───────────────────────────────────────────────────┘
```

1. **🔴 RED (Fail First)**:
   - Buat test case sebelum menulis kode implementasi.
   - Jalankan runner pengujian dan pastikan test **gagal** (*assertion failure*, bukan *fatal error* tak terduga).
   - Memastikan test benar-benar menguji fungsionalitas yang dituju dan tidak menghasilkan *false positive*.

2. **🟢 GREEN (Make it Pass)**:
   - Tulis kode implementasi seminimal mungkin yang diperlukan untuk membuat test lulus (*pass*).
   - Hindari over-engineering atau menambahkan fitur di luar cakupan test case saat ini.
   - Jalankan kembali runner pengujian hingga seluruh assertion berwarna hijau.

3. **🔵 REFACTOR (Clean & Optimize)**:
   - Tingkatkan kualitas kode: hapus duplikasi (*DRY*), perbaiki penamaan, rapikan struktur, dan optimasi performa.
   - Pertahankan perilaku fungsional (*no regression*).
   - Jalankan seluruh suite pengujian untuk memverifikasi tidak ada yang rusak.

---

## 2. Standar Struktur & Penamaan Test

### A. Penamaan Berkas & Class
- **Unit Test**: diletakkan di `tests/Unit/`, nama diakhiri dengan `Test.php` (contoh: `TiketDtsenTest.php`).
- **Feature / Integration Test**: diletakkan di `tests/Feature/`, menguji alur HTTP, Controller, dan Database (contoh: `PermohonanBagiPakaiTest.php`).

### B. Pola Penamaan Method Pengujian
Gunakan penamaan yang deskriptif dan menjelaskan skenario yang diuji:
- `test_opd_can_submit_permohonan_with_complete_required_documents()`
- `test_permohonan_fails_when_kak_document_is_missing()`
- `test_admin_can_verify_and_approve_valid_ticket()`
- `test_unauthenticated_user_cannot_access_opd_dashboard()`

### C. Struktur AAA (Arrange - Act - Assert)
Setiap test case harus terorganisir ke dalam tiga bagian:
```php
public function test_permohonan_requires_narahubung()
{
    // 1. Arrange: Siapkan data awal & state
    $user = User::factory()->create(['role' => 'opd']);
    $payload = [
        'nama_instansi' => 'Dinas Sosial',
        'nama_narahubung' => '', // Dikosongkan untuk menguji validasi
    ];

    // 2. Act: Eksekusi aksi yang diuji
    $response = $this->actingAs($user)->post(route('opd.tiket.store'), $payload);

    // 3. Assert: Verifikasi hasil yang diharapkan
    $response->assertSessionHasErrors(['nama_narahubung']);
}
```

---

## 3. Alur Praktik TDD untuk Ekosistem Laravel

### A. Konfigurasi Environment Pengujian
- Gunakan database in-memory SQLite untuk kecepatan eksekusi tinggi tanpa mempengaruhi database utama:
  ```xml
  <!-- phpunit.xml -->
  <php>
      <env name="APP_ENV" value="testing"/>
      <env name="DB_CONNECTION" value="sqlite"/>
      <env name="DB_DATABASE" value=":memory:"/>
      <env name="BCRYPT_ROUNDS" value="4"/>
      <env name="CACHE_DRIVER" value="array"/>
      <env name="MAIL_MAILER" value="array"/>
  </php>
  ```
- Selalu gunakan trait `Illuminate\Foundation\Testing\RefreshDatabase` pada feature test yang berinteraksi dengan database.

### B. Pengujian Unggah Berkas & Storage Mock
Gunakan `Storage::fake()` dan `UploadedFile::fake()` agar pengujian tidak menghasilkan sampah file nyata di disk:
```php
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

public function test_upload_berkas_kak_berhasil_tersimpan()
{
    Storage::fake('public');

    $file = UploadedFile::fake()->create('surat_kak.pdf', 1024, 'application/pdf');

    $response = $this->post(route('opd.tiket.store'), [
        'file_kak' => $file,
        // ... field lainnya
    ]);

    Storage::disk('public')->assertExists('dokumen_dtsen/' . $file->hashName());
}
```

---

## 4. Perintah Runner Pengujian

Gunakan perintah CLI berikut untuk menjalankan pengujian:

```bash
# Menjalankan seluruh test suite
.\php\php.exe artisan test

# Menjalankan spesifik test file
.\php\php.exe artisan test tests/Feature/PermohonanBagiPakaiTest.php

# Menjalankan hanya satu method test tertentu
.\php\php.exe artisan test --filter test_permohonan_requires_narahubung

# Menghentikan eksekusi pada kegagalan pertama (Fail Fast)
.\php\php.exe artisan test --stop-on-failure
```

---

## 5. Checklist Verifikasi Kualitas TDD
- [ ] Setiap skenario positif (*Happy Path*) dan skenario negatif (*Edge Cases / Boundary Cases*) telah tercakup.
- [ ] Validasi input (Required, Max Size, Format MIME) memiliki test assertion tersendiri.
- [ ] Hak akses & otorisasi (Role Admin vs OPD vs Tamu) diuji secara eksplisit.
- [ ] Tidak ada test yang bergantung pada urutan eksekusi test lain (*Independent & Isolated*).
- [ ] Runner test dapat dijalankan secara konsisten tanpa flakiness.

