# 💻 Dokumentasi Pengembang & Database (Laravel App)

> **Dokumentasi teknis untuk pengembangan backend, struktur database, routing, serta detail implementasi bisnis logik pada aplikasi SMK Bengkel & Perpustakaan.**

---

## 🛠️ Stack Teknologi

*   **Framework:** Laravel 11.x (PHP 8.2+)
*   **Database:** MySQL / MariaDB
*   **Frontend Engine:** Blade Templating + Vite (Asset Bundler) + Vanilla CSS
*   **Keamanan/Autentikasi:** Laravel Guards (`web` & `perpus`)

---

## 🔑 Mekanisme Autentikasi Multi-Guard

Untuk memisahkan data kredensial antara staf perpustakaan dengan siswa/guru bengkel secara absolut, aplikasi menggunakan sistem **Multi-Guard** pada file `config/auth.php`:

### 1. Guard `web` (Modul Bengkel)
*   **Model:** `App\Models\User`
*   **Tabel:** `users`
*   **Peran (Role):** `admin` & `siswa`
*   **Middleware Peran:** `role:{role_name}` (Memastikan pengguna memiliki peran yang diizinkan untuk rute tersebut).

### 2. Guard `perpus` (Modul Perpustakaan)
*   **Model:** `App\Models\Perpustakaan\PerpusUser`
*   **Tabel:** `perpus_users`
*   **Peran (Role):** `admin` & `siswa`
*   **Middleware Peran:** `role.perpus:{role_name}` (Middleware khusus untuk memproses otorisasi pengguna perpustakaan).

---

## 📊 Struktur Database & Skema Tabel

Aplikasi ini menggunakan 9 tabel utama untuk menyimpan data sistem secara efisien:

### 🔧 Modul Bengkel (Inventaris)

#### 1. Tabel `users`
Menyimpan data otentikasi siswa dan admin modul Bengkel.
*   `id` (BIGINT, PK, Auto Increment)
*   `name` (VARCHAR) – Nama Lengkap
*   `username` (VARCHAR, Unique) – Username login
*   `email` (VARCHAR, Unique) – Email (opsional)
*   `password` (VARCHAR) – Hash password
*   `role` (ENUM: `'admin'`, `'siswa'`) – Peran pengguna
*   `nis` (VARCHAR, Nullable) – Nomor Induk Siswa
*   `kelas` (VARCHAR, Nullable) – Kelas siswa (misal: XII MM 1)
*   `jurusan` (VARCHAR, Nullable) – Program keahlian

#### 2. Tabel `barangs`
Menyimpan katalog alat praktikum bengkel.
*   `id` (BIGINT, PK, Auto Increment)
*   `kode_barang` (VARCHAR, Unique) – Kode unik alat (contoh: `BRG-001`)
*   `nama_barang` (VARCHAR) – Nama alat
*   `stok` (INT) – Stok yang tersedia
*   `kondisi` (ENUM: `'baik'`, `'rusak'`, `'diperbaiki'`) – Kondisi alat
*   `deskripsi` (TEXT, Nullable) – Deskripsi detail alat

#### 3. Tabel `peminjamans`
Mencatat transaksi peminjaman alat bengkel oleh siswa.
*   `id` (BIGINT, PK, Auto Increment)
*   `user_id` (BIGINT, FK -> `users.id`) – Siswa yang meminjam
*   `barang_id` (BIGINT, FK -> `barangs.id`) – Alat yang dipinjam
*   `tanggal_pinjam` (DATE) – Tanggal pengambilan barang
*   `tanggal_kembali` (DATE, Nullable) – Tanggal pengembalian fisik
*   `status` (ENUM: `'dipinjam'`, `'dikembalikan'`)
*   `catatan` (TEXT, Nullable) – Keperluan atau catatan pengembalian

#### 4. Tabel `materis`
Materi pembelajaran bengkel yang diunggah oleh admin.
*   `id` (BIGINT, PK, Auto Increment)
*   `judul` (VARCHAR)
*   `deskripsi` (TEXT, Nullable)
*   `file_path` (VARCHAR, Nullable) – Path berkas modul PDF/materi
*   `created_at`, `updated_at`

---

### 📖 Modul Perpustakaan (SIPB)

#### 5. Tabel `perpus_users`
Menyimpan kredensial anggota dan staf perpustakaan.
*   `id` (BIGINT, PK, Auto Increment)
*   `name` (VARCHAR) – Nama lengkap
*   `username` (VARCHAR, Unique) – Username login
*   `email` (VARCHAR, Nullable)
*   `password` (VARCHAR)
*   `role` (ENUM: `'admin'`, `'siswa'`)
*   `no_anggota` (VARCHAR, Unique) – Nomor kartu perpustakaan
*   `kelas` (VARCHAR, Nullable)
*   `jurusan_id` (BIGINT, FK -> `perpus_jurusans.id`, Nullable)

#### 6. Tabel `perpus_bukus`
Katalog buku perpustakaan.
*   `id` (BIGINT, PK, Auto Increment)
*   `judul` (VARCHAR)
*   `penulis` (VARCHAR)
*   `penerbit` (VARCHAR, Nullable)
*   `tahun` (YEAR, Nullable)
*   `kategori` (VARCHAR, Nullable)
*   `stok` (INT) – Ketersediaan buku fisik
*   `gambar` (VARCHAR, Nullable) – Gambar/sampul buku
*   `deskripsi` (TEXT, Nullable)

#### 7. Tabel `perpus_peminjamans`
Transaksi sirkulasi peminjaman buku perpustakaan.
*   `id` (BIGINT, PK, Auto Increment)
*   `user_id` (BIGINT, FK -> `perpus_users.id`)
*   `buku_id` (BIGINT, FK -> `perpus_bukus.id`)
*   `tanggal_pinjam` (DATE)
*   `batas_kembali` (DATE) – Ditentukan otomatis (tanggal pinjam + 7 hari)
*   `status` (ENUM: `'dipinjam'`, `'dikembalikan'`)
*   `catatan` (TEXT, Nullable)

#### 8. Tabel `perpus_pengembalians`
Catatan pengembalian buku beserta perhitungan denda keterlambatan.
*   `id` (BIGINT, PK, Auto Increment)
*   `peminjaman_id` (BIGINT, FK -> `perpus_peminjamans.id`)
*   `tanggal_kembali` (DATE) – Tanggal fisik buku diterima kembali
*   `hari_terlambat` (INT) – Selisih hari antara `tanggal_kembali` dengan `batas_kembali`
*   `denda` (BIGINT) – Dihitung otomatis: `hari_terlambat * Rp 1.000`

#### 9. Tabel `perpus_jurusans`
Referensi program keahlian / jurusan anggota perpustakaan.
*   `id` (BIGINT, PK, Auto Increment)
*   `nama_jurusan` (VARCHAR) – Nama jurusan (misal: Teknik Komputer dan Jaringan)
*   `singkatan` (VARCHAR) – Kode singkatan (misal: TKJ)

---

## ⚡ Implementasi Bisnis Logik Kritis

### 🛡️ 1. Pencegahan Race Condition dengan DB Transaction & Row Locking (`lockForUpdate`)
Dalam modul Bengkel, ketika siswa mengajukan peminjaman alat secara bersamaan pada detik yang sama, sistem mencegah terjadinya alokasi stok negatif (*minus stock*) dengan menerapkan penguncian baris database tingkat tinggi:

```php
// File: App\Http\Controllers\Siswa\PeminjamanController.php
DB::transaction(function () use ($validated) {
    // 1. Mengunci baris data barang di database untuk memblokir penulisan paralel
    $barang = Barang::lockForUpdate()->findOrFail($validated['barang_id']);

    // 2. Melakukan pengecekan stok di dalam zona transaksi yang terkunci
    if ($barang->stok <= 0) {
        throw new \Exception("Stok barang habis. Peminjaman dibatalkan.");
    }

    // 3. Mengurangi stok dan mencatat peminjaman
    $barang->decrement('stok');
    Peminjaman::create([
        'user_id'   => auth()->id(),
        'barang_id' => $barang->id,
        'tanggal_pinjam' => $validated['tanggal_pinjam'],
        'status'    => 'dipinjam',
    ]);
});
```

---

### 🪙 2. Perhitungan Denda Keterlambatan Perpustakaan
Denda keterlambatan dihitung otomatis menggunakan library penanggalan Carbon. Denda dipatok sebesar **Rp 1.000 / hari** keterlambatan.

```php
// File: App\Http\Controllers\Perpustakaan\Admin\PengembalianController.php
$tanggalKembali = Carbon::parse($request->tanggal_kembali)->startOfDay();
$batasKembali   = $peminjaman->batas_kembali->startOfDay();

$hariTerlambat = 0;
$denda         = 0;

if ($tanggalKembali->gt($batasKembali)) {
    // Menghitung selisih hari absolut
    $hariTerlambat = (int) $tanggalKembali->diffInDays($batasKembali);
    $denda         = $hariTerlambat * 1000;
}
```

---

## 💻 Panduan Menjalankan Unit/Feature Testing

Aplikasi dilengkapi dengan setup pengujian bawaan Laravel. Anda dapat membuat dan menjalankan file pengujian menggunakan PHPUnit:

1.  **Jalankan pengujian menggunakan artisan:**
    ```bash
    php artisan test
    ```
2.  **Untuk membuat pengujian baru:**
    ```bash
    php artisan make:test PeminjamanTest
    ```

---

> [!NOTE]
> **Kontak Kontributor Proyek:**
> Dokumentasi pengembang ini disediakan untuk membantu integrasi fitur baru atau audit sistem secara cepat. Jika ditemukan bug pada implementasi transaksi database, harap periksa isolasi level database Anda (pastikan mesin MySQL menggunakan engine **InnoDB** yang mendukung row-locking).
