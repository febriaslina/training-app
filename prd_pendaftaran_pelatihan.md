# Product Requirements Document (PRD)
## Sistem Informasi Layanan [ Balai Pengembangan Kompetensi Perdagangan Yogyakarta]

| | |
|--|--|
| **Nama Sistem** | Sistem Informasi Layanan [ Balai Pengembangan KOmpetensi Perdagangan Yogyakarta] |
| **Tanggal** | [6 Agustus 2026] |
| **Penyusun** | [Lastri Setia] — [Pengolah Data dan Informasi] |
| **Instansi** | [ Balai Pengembangan KOmpetensi Perdagangan Yogyakarta, Kementerian Perdagangan] |

---

## Ringkasan Sistem

> *Jelaskan dalam 2–4 kalimat: apa sistemnya, untuk siapa, dan manfaat utamanya.*

 Sistem Informasi Pelatihan [Balai Pengembangan KOmpetensi Perdagangan Yogyakarta] dibuat untuk Aparatur Sipil Negara, layanan informasi pelatihan yang ada di bapekomdag membutuhkan sistem digital terpadu untuk mengelola layanan **[pelatihan]**. Sistem ini memudahkan klien mendaftar layanan secara online, membantu staf mengelola pelatihan, dan memberikan pimpinan laporan real-time.**.

---

## Arsitektur & Teknologi

### Stack Teknologi

| Komponen | Teknologi | Versi | Keterangan |
|----------|-----------|-------|------------|
| **Bahasa** | PHP | ≥ 8.3 | Versi minimum yang didukung Laravel 13 |
| **Framework** | [Laravel](https://laravel.com/) | v13 | Full-stack PHP framework |
| **Admin Panel** | [Filament](https://filamentphp.com/) | v5 | UI framework berbasis TALL Stack (Tailwind CSS, Alpine.js, Livewire, Laravel) |
| **Database** | PostgreSQL | ≥ 16 | RDBMS utama untuk seluruh data aplikasi |
| **Frontend** | TALL Stack | — | Tailwind CSS + Alpine.js + Livewire (bawaan Filament) |

### Arsitektur Database — PostgreSQL

> *Gunakan PostgreSQL sebagai satu-satunya RDBMS. Manfaatkan fitur-fitur native PostgreSQL berikut sesuai kebutuhan:*

| Fitur PostgreSQL | Kegunaan dalam Sistem |
|------------------|-----------------------|
| **UUID** (`uuid` / `ulid`) | Primary key yang aman untuk entitas publik (invoice, sertifikat) |
| **JSONB** | Menyimpan data dinamis/metadata fleksibel (contoh: detail konfigurasi layanan) |
| **Full-Text Search** (`tsvector`) | Pencarian cepat pada data peserta, layanan, dan laporan |
| **Enum Types** | Status transaksi (`pending`, `confirmed`, `paid`, `completed`, `cancelled`) |
| **Partial Index** | Index kondisional untuk query yang sering diakses |
| **Foreign Key Constraints** | Integritas referensial antar tabel |
| **Timestamp with Time Zone** | Konsistensi waktu lintas zona (penting untuk instansi multi-lokasi) |

### Struktur Panel Filament v5

> *Filament v5 mendukung multi-panel. Definisikan panel sesuai peran pengguna.*

| Panel | Path | Peran Pengguna | Deskripsi |
|-------|------|----------------|-----------|
| **Admin** | `/admin` | Administrator, Operator | Kelola seluruh data, CRUD Resources, manajemen pengguna |
| **Pimpinan** | `/pimpinan` | Pimpinan / Direktur | Dashboard read-only, laporan, dan statistik |
| **Portal Klien** | `/portal` | Klien Individu, Klien Instansi | Pendaftaran layanan, upload dokumen, cek status, download invoice |

### Komponen Filament v5 yang Digunakan

| Komponen | Fungsi |
|----------|--------|
| **Resources** | CRUD untuk setiap entitas utama (Layanan, Pendaftaran, Peserta, Pembayaran, Sertifikat) |
| **Relation Managers** | Mengelola relasi (contoh: Peserta dalam Pendaftaran, Pembayaran dalam Invoice) |
| **Dashboard Widgets** | Stat widgets, chart widgets untuk metrik dan tren |
| **Actions & Modals** | Konfirmasi aksi, form input dalam modal (contoh: approve pendaftaran, kirim invoice) |
| **Notifications** | Notifikasi in-app untuk perubahan status dan event penting |
| **Tables** | Tabel data dengan filter, sort, search, dan bulk actions |
| **Forms** | Form builder dengan validasi, conditional fields, dan file upload |
| **Infolists** | Tampilan detail read-only untuk halaman informasi |
| **Custom Pages** | Halaman khusus (contoh: kalender ketersediaan, cetak laporan) |

---

## 1. Pengguna Sistem

| Peran | Siapa | Yang Mereka Lakukan | Panel Filament |
|-------|-------|---------------------|----------------|
| **Administrator** | [Staf program] | Kelola seluruh data dan hak akses pengguna | Admin |
| **Operator Layanan** | [Staf pelatihan] | Input pendaftaran, konfirmasi, cetak invoice | Admin |
| **Pimpinan** | [Kepala Balai Pengembangan Kompetensi Perdagangan Yogyakarta] | Lihat dashboard dan laporan — hanya baca | Pimpinan |
| **Klien Individu** | [peserta pelatihan] | Daftar layanan, upload dokumen, cek status | Portal Klien |
| **Klien Instansi** | [staf SDM] | Daftarkan rombongan, terima invoice kolektif | Portal Klien |

---

## 2. Layanan yang Dikelola Sistem

> *Untuk setiap layanan: jelaskan alurnya dan data apa yang perlu dicatat.*
> *Aturan bisnis penting wajib dituliskan — ini yang akan menjadi validasi di sistem.*

---

### Layanan 1 — [Pelatihan Teknis]

**Deskripsi:** [Penyelenggaraan pelatihan teknis bagi pegawai instansi lain. Peserta mendaftar, mengikuti pelatihan, dan mendapatkan sertifikat.]

**Alur:**
1. Klien memilih jadwal pelatihan yang tersedia
2. Klien mengisi formulir pendaftaran (nama, asal instansi, jumlah peserta)
3. Operator memverifikasi dan mengkonfirmasi pendaftaran
4. Klien menerima invoice dan melakukan pembayaran
5. Peserta mengikuti pelatihan dan mengisi absensi
6. Sertifikat diterbitkan setelah pelatihan selesai

**Data yang dicatat:** nama peserta · asal instansi · jadwal dipilih · status pembayaran · kehadiran · nomor sertifikat

**Aturan bisnis:**
- Kuota maksimal **[50] peserta** per kelas — jika penuh, pendaftaran otomatis ditutup
- Pembayaran lunas minimal **H-[7]** sebelum pelatihan dimulai
- [konfirmasi pendaftaran dapat via whatssApp yang diinput oleh petugas]

---

### Layanan 2 — [Penyediaan Fasilitas]

**Deskripsi:** [Penggunaan ruang kelas, modul, dan konsumsi untuk peserta.]

**Alur:**
1. Klien mengecek ketersediaan fasilitas di kalender
2. Klien mengajukan permohonan penggunaan dengan detail kegiatan dan tanggal
3. Operator mengkonfirmasi
4. Klien datang sesuai jadwal yang disetujui

**Data yang dicatat:** nama fasilitas · tanggal kegiatan · nama kegiatan · jumlah tamu · total biaya

**Aturan bisnis:**
- Satu fasilitas **tidak bisa daftar dua kali** di tanggal yang sama
- Pembatalan kurang dari **[7] hari** dikenakan biaya [5]%
- [peserta wajib isi konfirmasi kedatangan]

---

### Layanan 3 — [Sertifikasi Pelatihan]

**Deskripsi:** [Layanan penyediaan data sertifikat peserta yang telah lulus]

**Alur:**
1. [Masukkan nama peserta]
2. [Verifikasi nama peserta]
3. [Cek kehadiran peserta]
4. [Cetak sertifikat]

**Data yang dicatat:** [sebutkan data yang perlu disimpan]

**Aturan bisnis:**
- [Sertifikat muncul hanya untuk peserta berstatus LULUS]

---

## 3. Laporan & Dashboard yang Dibutuhkan

### Dashboard Utama (tampil saat login)

| Informasi | Keterangan |
|-----------|------------|
| [Total pelatihan bulan ini] | [Jumlah Peserta yang diterima bulan berjalan] |
| [Jumlah pendaftaran minggu ini] | [Pendaftaran baru 7 hari terakhir per layanan] |
| [Fasilitas aktif hari ini] | [Ruang kelas yang sedang digunakan] |

### Laporan Berkala

| Laporan | Frekuensi | Isi | Format |
|---------|-----------|-----|--------|
| [Rekap Pelatihan] | Bulanan | [Total per jenis pelatihan] | Excel & PDF |
| [Statistik Peserta] | Triwulanan | [Jumlah per jenis pelatihan] | PDF |

---

> **Catatan untuk AI Coding Assistant:**
>
> **Stack & Arsitektur:**
> - Framework: **Laravel 13** dengan **Filament v5** (TALL Stack)
> - Database: **PostgreSQL ≥ 16** — gunakan migration Laravel dengan driver `pgsql`
> - Gunakan **UUID/ULID** sebagai primary key untuk entitas yang diekspos ke publik
> - Gunakan fitur **JSONB** PostgreSQL untuk metadata dinamis via Laravel `$casts`
> - Gunakan **Enum type** PostgreSQL untuk kolom status (atau string enum yang dicasting di Model)
>
> **Mapping PRD → Kode:**
> - Setiap **layanan** di Bagian 2 → 1 modul Filament Resource + set tabel database (migration PostgreSQL)
> - Setiap **alur** → urutan status pada kolom `status` (enum) di tabel transaksi
> - Setiap **data yang dicatat** → kolom-kolom pada migration + `$fillable` di Eloquent Model
> - Setiap **aturan bisnis** → validasi di Form schema Filament + business logic di Model/Action class
> - Setiap **peran pengguna** → Panel Filament terpisah dengan middleware auth + policy authorization
> - Bagian 3 → `StatsOverviewWidget`, `ChartWidget` di dashboard Filament + fitur ekspor PDF/Excel
>
> **Referensi:**
> - Dokumentasi Filament v5: https://filamentphp.com/docs
> - Dokumentasi Laravel: https://laravel.com/docs
> - Dokumentasi PostgreSQL: https://www.postgresql.org/docs/

---
