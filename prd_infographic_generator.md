# Product Requirements Document (PRD)
## AI Infographic Generator

|                 |                                                             |
| -----------------| -------------------------------------------------------------|
| **Nama Sistem** | AI Infographic Generator — Portal Satu Data Kota Yogyakarta |
| **Tanggal**     | 6 Agustus 2026                                              |
| **Penyusun**    | Rizki Febri Aslina — Penelaah Teknis Kebijakan              |
| **Instansi**    | Dinas Kominfosan Kota Yogyakarta                            |

---

## Ringkasan Sistem

> *Jelaskan dalam 2–4 kalimat: apa sistemnya, untuk siapa, dan manfaat utamanya.*

Pemerintah Kota Yogyakarta membutuhkan sistem digital terpadu untuk mengelola layanan **pembuatan dan publikasi infografis statistik serta narasi data berbasis Artificial Intelligence (AI)** yang terintegrasi dengan Portal Satu Data Kota Yogyakarta. Sistem ini memudahkan Produsen Data (OPD) dan Walidata (Dinas Kominfosan) mengolah himpunan data (dataset) mentah menjadi konten visual dan ringkasan eksekutif secara otomatis, membantu pimpinan memantau diseminasi data visual, serta menyajikan visualisasi data resmi yang mudah dipahami bagi publik/masyarakat. Target: memangkas waktu pemrosesan desain visual infografis dari **5 hari kerja per dataset** menjadi kurang dari **15 menit per dataset**.

---

## Arsitektur & Teknologi

### Stack Teknologi

| Komponen                 | Teknologi                            | Versi  | Keterangan                                                                           |
| --------------------------| --------------------------------------| --------| --------------------------------------------------------------------------------------|
| **Bahasa**               | PHP                                  | ≥ 8.3  | Versi minimum yang didukung Laravel 13                                               |
| **Framework**            | [Laravel](https://laravel.com/)      | v13    | Full-stack PHP framework                                                             |
| **Admin Panel**          | [Filament](https://filamentphp.com/) | v5     | UI framework berbasis TALL Stack (Tailwind CSS, Alpine.js, Livewire, Laravel)        |
| **Database**             | PostgreSQL                           | ≥ 16   | RDBMS utama untuk seluruh data aplikasi                                              |
| **Frontend**             | TALL Stack                           | —      | Tailwind CSS + Alpine.js + Livewire (bawaan Filament)                                |
| **AI Processing Engine** | Python / Microservice (FastAPI)      | ≥ 3.11 | Layanan pemrosesan dataset, peng-generate-an narasi AI (LLM), dan ekspor SVG/PNG/PDF |

### Arsitektur Database — PostgreSQL

> *Gunakan PostgreSQL sebagai satu-satunya RDBMS. Manfaatkan fitur-fitur native PostgreSQL berikut sesuai kebutuhan:*

| Fitur PostgreSQL                  | Kegunaan dalam Sistem                                                                         |
| -----------------------------------| -----------------------------------------------------------------------------------------------|
| **UUID** (`uuid` / `ulid`)        | Primary key yang aman untuk entitas publik (id_infografis, id_template, id_request)           |
| **JSONB**                         | Menyimpan tata letak (layout) infografis, data statistik terstruktur, dan metadata prompt AI  |
| **Full-Text Search** (`tsvector`) | Pencarian cepat pada pustaka infografis, metadata dataset, dan judul laporan                  |
| **Enum Types**                    | Status permintaan (`draft`, `generating`, `review_opd`, `approved`, `published`, `archived`)  |
| **Partial Index**                 | Index kondisional untuk query infografis yang berstatus `published` dan sering diakses publik |
| **Foreign Key Constraints**       | Integritas referensial antar tabel (OPD, Dataset, Template, Infografis, Log Generate)         |
| **Timestamp with Time Zone**      | Konsistensi waktu pembuatan, revisi, dan publikasi infografis                                 |

### Struktur Panel Filament v5

> *Filament v5 mendukung multi-panel. Definisikan panel sesuai peran pengguna.*

| Panel            | Path        | Peran Pengguna                 | Deskripsi                                                         |
| ------------------| -------------| --------------------------------| -------------------------------------------------------------------|
| **Admin**        | `/admin`    | Administrator, Operator        | Kelola seluruh data, CRUD Resources, manajemen pengguna           |
| **Pimpinan**     | `/pimpinan` | Pimpinan / Direktur            | Dashboard read-only, laporan, dan statistik                       |
| **Portal Klien** | `/portal`   | Klien Individu, Klien Instansi | Pendaftaran layanan, upload dokumen, cek status, download invoice |

### Komponen Filament v5 yang Digunakan

| Komponen              | Fungsi                                                                                  |
| -----------------------| -----------------------------------------------------------------------------------------|
| **Resources**         | CRUD untuk setiap entitas utama (Layanan, Pendaftaran, Peserta, Pembayaran, Sertifikat) |
| **Relation Managers** | Mengelola relasi (contoh: Peserta dalam Pendaftaran, Pembayaran dalam Invoice)          |
| **Dashboard Widgets** | Stat widgets, chart widgets untuk metrik dan tren                                       |
| **Actions & Modals**  | Konfirmasi aksi, form input dalam modal (contoh: approve pendaftaran, kirim invoice)    |
| **Notifications**     | Notifikasi in-app untuk perubahan status dan event penting                              |
| **Tables**            | Tabel data dengan filter, sort, search, dan bulk actions                                |
| **Forms**             | Form builder dengan validasi, conditional fields, dan file upload                       |
| **Infolists**         | Tampilan detail read-only untuk halaman informasi                                       |
| **Custom Pages**      | Halaman khusus (contoh: kalender ketersediaan, cetak laporan)                           |

---

## 1. Pengguna Sistem

| Peran             | Siapa                  | Yang Mereka Lakukan                                                                                                       | Panel Filament |
| -------------------| ------------------------| ---------------------------------------------------------------------------------------------------------------------------| ----------------|
| **Administrator** | Dinas Kominfosan       | Kelola konfigurasi engine AI, role management, template visual                                                            | Admin          |
| **Walidata**      | Tim Data dan Statistik | Review kualitas narasi AI & visual infografis, menyetujui (approve) publikasi ke portal publik                            | Walidata       |
| **Pimpinan**      | Kepala OPD             | Melihat dashboard kinerja diseminasi data, tren data daerah, dan persetujuan laporan eksekutif                            | Pimpinan       |
| **Produsen Data** | Staf OPD               | Memilih dataset, generate infografis berbasis AI, melakukan kustomisasi narasi, memeriksa hasil AI sebelum dipublikasikan | Admin OPD      |
| **Publik**        | Masyarakat Umum        | Menjelajah, mengunduh (PNG/PDF/SVG), dan membagikan infografis resmi Pemkot                                               | Publik         |

---

## 2. Layanan yang Dikelola Sistem

> *Untuk setiap layanan: jelaskan alurnya dan data apa yang perlu dicatat.*
> *Aturan bisnis penting wajib dituliskan — ini yang akan menjadi validasi di sistem.*

---

### Layanan 1 — Generate Infografis Statistik Otomatis dari Dataset Satu Data

**Deskripsi:** Menghasilkan infografik otomatis berdasarkan dataset yang dipilih pengguna.

**Alur:**
1. Produsen Data memilih dataset aktif dari Portal Satu Data Kota atau mengunggah file data (CSV/XLSX).
2.  Produsen Data memilih kategori template visual (misal: Komparasi, Tren Waktu, Distribusi Geografis, Matriks Progress)
3. Sistem mengirimkan data ke Engine AI untuk menganalisis statistik kunci, menghasilkan penceritaan data (*data storytelling*), serta memilih chart yang sesuai
4. Sistem menyajikan draf infografis beserta narasi AI pada halaman preview.
5. Produsen Data dapat menyesuaikan narasi atau elemen visual melalui form kustomisasi.
6. Produsen Data mengajukan permohonan publikasi ke Walidata Diskominfo.

**Data yang dicatat:** `id_request` · `id_dataset` · `id_opd` · `prompt_used` · `generated_narrative` (JSONB) · `chart_config` (JSONB) · `status_proses` · `waktu_generate_detik`

**Aturan bisnis:**
- Dataset yang diolah **wajib terverifikasi** (berstatus *valid*) di Portal Satu Data Kota Yogyakarta
- Pemrosesan AI tidak boleh melebihi timeout **60 detik**; jika gagal, sistem menyajikan mekanisme *fallback* pengerjaan manual/retry
- Nilai angka dan data statistik pada infografis **tidak boleh diubah secara manual** di luar angka asli dataset untuk menjaga integritas data

---

### Layanan 2 — Review & Verifikasi Publikasi Infografis (Walidata)

**Deskripsi:** Layanan kontrol kualitas (*quality control*) oleh Walidata/Diskominfo untuk memastikan infografis yang dibuat OPD/Produsen Data memenuhi standar Pemkot Yogyakarta, bebas dari kejanggalan fakta, dan layak dipublikasikan.

**Alur:**
1. Walidata menerima notifikasi masuknya pengajuan infografis dari OPD.
2. Walidata membuka panel review untuk mengecek akurasi narasi AI dan tata letak infografis.
3. Walidata dapat menyetujui (*Approve*) atau mengembalikan dengan catatan revisi (*Reject with Notes*).
4. Jika disetujui, infografis langsung terpublikasi secara otomatis ke Portal Publik Satu Data dan galeri infografis.

**Data yang dicatat:** `id_publikasi` · `id_request` · `id_reviewer` · `catatan_revisi` · `tgl_disetujui` · `versi_gambar` · `url_publik`

**Aturan bisnis:**
- Infografis yang belum disetujui Walidata **tidak boleh dapat diakses oleh publik** (hanya berstatus *draft/review*).
- Setiap penolakan wajib disertai alasan/catatan revisi minimal **20 karakter**

---

### Layanan 3 — Pengelolaan Template Visual & Brand Kit Pemerintah Kota

**Deskripsi:** Layanan untuk mengelola pustaka template infografis visual, skema warna resmi Pemkot, logo instansi, dan aturan tipografi agar seluruh infografis yang dihasilkan konsisten secara visual.

*Alur:**
1. Administrator TI mengunggah atau mengonfigurasi struktur template SVG/HTML dinamis.
2. Administrator menentukan zona dinamis (tempat grafik, logo OPD, teks narasi AI, dan *footer* sumber data).
3. Template dipublikasikan dan siap digunakan oleh Produsen Data di Portal Klien.

**Data yang dicatat:** `id_template` · `nama_template` · `kategori` · `layout_structure` (JSONB) · `brand_colors` (JSONB) · `is_active`

**Aturan bisnis:**
- Template yang sedang digunakan oleh infografis berstatus *published* **tidak boleh dihapus** (*soft delete* saja).
- Setiap template wajib memiliki atribut watermark logo resmi Pemkot Yogyakarta dan pernyataan sumber data Satu Data Kota Yogyakarta.

---

### Layanan 4 — Pembuatan Ringkasan Eksekutif & Narasi Laporan Pimpinan

**Deskripsi:** Layanan otomatisasi untuk mengonsolidasi beberapa infografis terbitan menjadi dokumen *Executive Summary Infographic Bulletin* bulanan/triwulanan untuk Wali Kota dan Pimpinan Daerah.

**Alur:**
1. Pimpinan atau Staf Admin memilih periode laporan dan daftar topik data utama (misal: Kesehatan, Ekonomi, Infrastruktur).
2. AI menyusun narasi rangkuman lintas sektor (*cross-sector narrative summary*) secara otomatis.
3. Sistem menggabungkan visualisasi infografis pilihan ke dalam satu berkas laporan eksekutif berformat PDF interaktif.

**Data yang dicatat:** `id_laporan` · `periode_laporan` · `daftar_infografis_ids` (JSONB) · `executive_summary_text` · `file_pdf_path`

**Aturan bisnis:**
- Laporan pimpinan hanya menyakup infografis yang berstatus **sudah disetujui/terpublikasi**.
---

## 3. Laporan & Dashboard yang Dibutuhkan

### Dashboard Utama (tampil saat login)

| Informasi                        | Keterangan                                                             |
| ----------------------------------| ------------------------------------------------------------------------|
| **Total Infografis Dipublikasi** | Jumlah infografis yang telah rilis dan diakses publik                  |
| **Status Pengajuan Infografis**  | Metrik jumlah draf, dalam proses review, disetujui, dan ditolak        |
| **Cakupan OPD Aktif**            | Persentase OPD yang memanfaatkan generator AI bulan ini                |
| **Penggunaan AI Engine & Token** | Konsumsi kuota API/token LLM dan waktu rata-rata eksekusi per generate |
| **Top Infografis Populer**       | Infografis paling banyak diunduh/dilihat oleh publik                   |

### Laporan Berkala

| Laporan                               | Frekuensi  | Isi                                                                                 | Format      |
| ---------------------------------------| ------------| -------------------------------------------------------------------------------------| -------------|
| **Rekap Diseminasi Data Visual OPD**  | Bulanan    | Jumlah infografis terbit per OPD, kategori data, dan kecepatan persetujuan          | Excel & PDF |
| **Laporan Kinerja AI Engine & Biaya** | Bulanan    | Total generate data, tingkat keberhasilan prompt, dan penggunaan sumber daya server | PDF         |
| **Buletin Eksekutif Data Kota**       | Triwulanan | Kumpulan infografis strategis daerah beserta narasi AI untuk Wali Kota              | PDF         |

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
> - Setiap **layanan** di Bagian 2 → 1 modul Filament Resource (`InfographicRequestResource`, `PublishedInfographicResource`, `InfographicTemplateResource`, `ExecutiveReportResource`)
> - Setiap **alur** → urutan status pada kolom `status` (enum) di tabel `infographic_requests`
> - Setiap **data yang dicatat** → kolom-kolom pada migration + `$fillable` di Eloquent Model
> - Setiap **aturan bisnis** → validasi di Form schema Filament + business logic di Model/Action class (misal: `GenerateAiInfographicAction`)
> - Setiap **peran pengguna** → Panel Filament terpisah (`AdminPanelProvider`, `PimpinanPanelProvider`, `PortalPanelProvider`) dengan middleware auth + policy authorization
> - Bagian 3 → `StatsOverviewWidget`, `ChartWidget` di dashboard Filament + fitur ekspor PDF/Excel
>
> **Referensi:**
> - Dokumentasi Filament v5: https://filamentphp.com/docs
> - Dokumentasi Laravel: https://laravel.com/docs
> - Dokumentasi PostgreSQL: https://www.postgresql.org/docs/

---
