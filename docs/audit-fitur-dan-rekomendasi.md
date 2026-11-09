# Audit Fitur, Optimalisasi, dan Rekomendasi Roadmap — Look at History

Dokumen ini merupakan hasil audit komprehensif terhadap arsitektur teknis, fungsionalitas editorial, pengalaman pembaca (UX), SEO, serta kesiapan infrastruktur pada repositori **Look at History** (`web-lah`).

---

## 1. Ringkasan Eksekutif & Metodologi Audit

Audit dilakukan dengan memeriksa langsung basis kode Laravel 13: route (`routes/web.php`, `routes/console.php`), model Eloquent, controller admin & publik, service (`AnalyticsTracker`, `BotDetector`, `ImageOptimizer`, `InternalLinker`), skema migrasi database, dan view Blade/Tailwind v4.

### Temuan Utama:
1. **Arsitektur Slug & Routing Terkunci**: Tidak ada input custom slug; slug digenerate otomatis dari judul pada lifecycle Eloquent (`booted()`). Jika judul artikel diedit, slug otomatis berubah sehingga merusak URL lama (menghasilkan 404 tanpa 301 redirect), memutus backlink, dan merusak indeks Google. Belum ada penanganan tabrakan slug (*collision*) yang menyebabkan fatal query exception saat ada judul identik.
2. **Bottleneck Skalabilitas & Proses Sinkron**: Pengiriman newsletter di `Admin\NewsletterController` masih berjalan sinkron (`Mail::send()` dalam perulangan `foreach` tanpa queue worker), berisiko tinggi HTTP timeout (504/500). Formulir kontak (`/kontak`) mengirim email mentah tanpa menyimpan data ke database (pesan hilang jika mail server bermasalah).
3. **Dead Code & Fitur Setengah Jalan**: Service `ImageOptimizer` memiliki fungsi pembuatan responsive images (`optimizeResponsive()`), namun di controller hanya dipanggil `optimizeAndStore()` (satu ukuran tunggal WebP 1200px), sehingga `<picture>`/`srcset` tidak pernah dipakai di frontend.
4. **Internal Linking Masih Statis**: Class `InternalLinker` menggunakan array PHP hardcoded berisi ~30 kata kunci dan slug tetap, sehingga artikel baru tidak terhubung otomatis dan rentan broken link jika slug target berubah.
5. **Ketiadaan Fitur Esensial Platform Sejarah**: Belum ada modul sitasi/daftar referensi ilmiah, garis waktu peristiwa (*interactive timeline*), peta lokasi sejarah, slider komparasi foto arsip (*before/after*), sistem revisi draft (*version history*), dan alur review editorial (*writer submit -> editor review -> publish*).

---

## 2. Audit Arsitektur Slug & Routing

### 2.1. Masalah Saat Ini

#### A. Ketiadaan Input Custom Slug
Pada model `Artikel`, `Kategori`, `Era`, `Topik`, dan `Penulis`, slug digenerate otomatis menggunakan event model Eloquent:
```php
// app/Models/Artikel.php
static::creating(function (Artikel $artikel) {
    $artikel->slug = Str::slug($artikel->judul);
    // ...
});
```
Formulir create dan edit (`resources/views/admin/artikel/create.blade.php` dan `edit.blade.php`) tidak menyediakan kolom input slug. Penulis tidak dapat memperpendek slug demi estetika atau optimasi keyword SEO (misalnya judul 15 kata diringkas menjadi 4 kata kunci utama pada slug).

#### B. Rusaknya URL saat Judul Berubah (URL Breaking & SEO Degradation)
Pada event updating:
```php
// app/Models/Artikel.php
static::updating(function (Artikel $artikel) {
    if ($artikel->isDirty('judul')) {
        $artikel->slug = Str::slug($artikel->judul).($artikel->trashed() ? '-'.$artikel->id : '');
    }
    // ...
});
```
Jika penulis hanya memperbaiki typo kecil pada judul artikel yang sudah tayang dan terindeks di Google Search Console, slug langsung berubah seketika. URL lama langsung menghasilkan **HTTP 404 Not Found**, mematikan traffic organik, merusak bookmark user, dan memutus tautan media sosial.

#### C. Kerentanan Crash Database Akibat Judul Duplikat (*Collision*)
Kolom `slug` pada tabel `artikels` didefinisikan sebagai `UNIQUE`. Jika ada dua artikel dengan judul yang sama atau sangat mirip (misalnya: "Perang Diponegoro" atau "Runtuhnya Majapahit"), fungsi `Str::slug()` akan menghasilkan string yang sama. Database MySQL akan langsung melempar:
```text
SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry 'perang-diponegoro' for key 'artikels_slug_unique'
```
Aplikasi tidak memiliki mekanisme penambahan suffix numerik otomatis (`-1`, `-2`, dst.).

#### D. Konflik dengan Soft Deletes
Model `Artikel` menggunakan trait `SoftDeletes`. Namun index `unique` di MySQL berlaku untuk seluruh baris, termasuk baris yang memiliki `deleted_at IS NOT NULL`. Jika artikel lama dihapus (soft-delete), penulis tidak akan pernah bisa membuat artikel baru dengan judul yang sama karena MySQL menolak duplikasi slug tersebut.

### 2.2. Solusi Teknis yang Direkomendasikan

1. **Tambahkan Kolom Input Slug pada Form Editorial**:
   - Buat field `slug` opsional di form tambah/edit artikel.
   - Sediakan JavaScript preview real-time yang menyarankan slug berbasis judul, tetapi memberi kebebasan penulis untuk mengeditnya secara manual.
   - Validasi backend: `nullable|string|max:255|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/`.

2. **Otomatisasi Penanganan Duplikasi Slug (*Unique Slug Generator*)**:
   Implementasikan helper/service yang mengecek keberadaan slug secara rekursif sebelum disimpan:
   ```php
   public static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
   {
       $baseSlug = Str::slug($title);
       $slug = $baseSlug;
       $counter = 1;

       while (static::where('slug', $slug)->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))->withTrashed()->exists()) {
           $slug = "{$baseSlug}-{$counter}";
           $counter++;
       }

       return $slug;
   }
   ```

3. **Sistem Redirect Otomatis 301 (*Slug Redirects*)**:
   Buat tabel migrasi `url_redirects`:
   ```text
   - id: bigint primary key
   - source_path: varchar(255) unique index (misal: "artikel/slug-lama")
   - target_path: varchar(255) (misal: "artikel/slug-baru")
   - status_code: smallint default 301
   - hit_count: bigint default 0
   - created_at, updated_at
   ```
   Setiap kali slug berubah pada model `Artikel`, catat slug lama ke dalam tabel ini. Pasang middleware atau fallback route yang mengarahkan pengunjung secara permanen (HTTP 301) ke URL baru.

---

## 3. Audit Fitur yang Ada (Analisis Sub-optimal & Perbaikan)

| Komponen / Fitur | Status Saat Ini | Masalah / Keterbatasan | Rekomendasi Solusi & Peningkatan |
|---|---|---|---|
| **Internal Linking** (`InternalLinker.php`) | Statis (Array PHP konstan berisi ~30 item) | Harus ubah file kode setiap ingin tambah keyword; target slug di-hardcode sehingga jika slug target berganti, link menjadi 404; tidak ada bobot prioritas dinamis. | Pindahkan ke tabel database `internal_links` (`keyword`, `artikel_id`, `priority`, `max_occurrences`). Berikan UI manajemen tautan internal di panel admin. Hubungkan secara relasional dengan model `Artikel`. |
| **Optimasi Gambar** (`ImageOptimizer.php`) | Setengah matang | Metode `optimizeResponsive()` sudah ditulis tetapi tidak pernah dipakai di controller. Semua gambar diproses single size (1200px WebP). Gambar dalam konten artikel yang dibatalkan tersimpan selamanya sebagai file yatim (*orphaned files*). | Jalankan `optimizeResponsive()` saat upload gambar unggulan artikel. Terapkan tag `<picture>` dengan `srcset` (400w, 800w, 1200w) pada Blade. Buat command pruning untuk menghapus file gambar konten yang tidak terikat pada artikel apapun. |
| **Pengiriman Buletin** (`Admin\NewsletterController.php`) | Sinkron (`Mail::send()` dalam loop) | Jika subscriber berjumlah 100+ orang, eksekusi HTTP akan mencapai batas `max_execution_time` (30 detik) dan gagal paruh jalan. Tidak ada riwayat pengiriman (*campaign logs*), metrik terbuka (*open rate*), maupun pengujian email (*test send*). | Pindahkan pengiriman ke Job Queue ber-chunking (`SendNewsletterCampaignJob` per 50 email). Tambahkan tabel `newsletters` untuk menyimpan arsip subjek, konten, tanggal kirim, dan total penerima. Tambahkan tombol "Kirim Uji Coba ke Email Admin". |
| **Formulir Kontak** (`KontakController.php`) | Stateless (Hanya kirim email mentah via `Mail::raw`) | Pesan pengunjung tidak disimpan di database. Jika terjadi kegagalan SMTP/mail provider, pesan hilang permanen. Tidak ada proteksi spam (honeypot/CAPTCHA) selain throttle dasar. | Buat model & tabel `kontak_pesans` (`nama`, `email`, `subjek`, `pesan`, `dibaca`, `dibalas`). Sediakan menu "Pesan Masuk" di dashboard admin. Pasang field honeypot tak terlihat untuk menangkal spam bot. |
| **Pencarian Publik** (`SearchController.php`) | MySQL Fulltext dasar | Tidak ada filter taksonomi (era, kategori, topik) di halaman cari; tidak ada live search (AJAX preview); query SQLite fallback tidak menyeleksi relevansi; tidak ada pencatatan kata kunci pencarian kosong (*zero-result searches*). | Sediakan filter faceted di halaman hasil cari (kategori, era, rentang waktu). Sediakan endpoint AJAX ringan `/api/search/suggest` untuk modal pencarian cepat (`Ctrl+K`/`Cmd+K`). Catat query pencarian tanpa hasil ke database untuk evaluasi konten baru. |
| **Moderasi Komentar** (`Admin\KomentarController.php`) | Approval manual tunggal | Admin harus menyetujui komentar satu per satu tanpa opsi aksi massal (*bulk approve / bulk delete*). Komentator tidak menerima notifikasi email saat komentarnya dibalas atau disetujui. | Tambahkan checkbox aksi massal (*Bulk Action*). Tambahkan deteksi kata kasar/spam otomatis (*profanity filter*). Kirim email notifikasi ke komentator jika komentarnya mendapat balasan. |
| **Manajemen Pengguna** (`Admin\PenggunaController.php`) | Sangat terbatas | Tidak memiliki paginasi (`User::get()`), tidak ada pencarian pengguna, tidak ada UI untuk mengubah hak akses (*role*) atau mereset password pengguna dari dashboard. | Tambahkan paginasi, pencarian nama/email, modal assign role (admin, penulis, pembaca), dan toggle status aktif akun. |
| **Dashboard Analytics** (`Admin\DashboardController.php`) | Query N+1 dalam perulangan hari/bulan | Perulangan 7 hari menjalankan 7 kali query `whereDate()` (yang menonaktifkan indeks kolom); perulangan 6 bulan menjalankan 6 query. Data `referer` dan `user_agent` di tabel `pageviews` sudah tersimpan namun tidak divisualisasikan. | Ubah menjadi agregasi query tunggal (`GROUP BY DATE(created_at)`). Tambahkan visualisasi Top Referrer (Google, AI Search, Medsos), Distribusi Perangkat (Mobile vs Desktop), dan Top 10 Artikel Terpopuler Pekan Ini. |
| **Sistem Bookmark** (`BookmarkController.php`) | Rawan null reference | Query `$user->bookmarks()->with('artikel.kategori')` tidak menyaring artikel yang statusnya draft atau telah di-soft-delete, sehingga jika ada artikel terkait yang dihapus, view `bookmark.blade.php` memicu error properti pada objek null. | Tambahkan filter relasi: `whereHas('artikel', fn($q) => $q->published())` pada controller bookmark. |
| **Ubah Email Profil** (`ProfilController.php`) | Bypass verifikasi | Saat user mengubah alamat email di form profil, field `email_verified_at` tidak di-reset ke `null`, dan notifikasi verifikasi ulang tidak dikirimkan. Akun tetap berstatus *verified* meskipun menggunakan email baru yang belum dibuktikan kepemilikannya. | Tambahkan hook di `ProfilController`: jika email berganti, set `email_verified_at = null` dan picu `sendEmailVerificationNotification()`. |

---

## 4. Audit Fitur yang Seharusnya Ada (Missing Core Features)

Berikut adalah kapabilitas standar CMS editorial modern yang saat ini belum tersedia di Look at History:

### 4.1. Media Library Terpusat (Asset Manager)
* **Kondisi saat ini**: Gambar diunggah langsung ke form artikel atau melalui rich text editor (Quill) ke direktori `storage/artikel/konten` tanpa katalog.
* **Kebutuhan**:
  - Modal Media Library untuk memilih gambar yang sudah pernah diunggah sebelumnya tanpa duplikasi file di disk.
  - Form metadata gambar: Alt Text SEO, Judul, Keterangan (*Caption*), dan Kredit Sumber/Arsip Sejarah (misal: "Arsip Nasional RI, 1947" atau "Wikimedia Commons - Public Domain").
  - Penghapusan gambar otomatis yang aman: membersihkan file fisik saat artikel dihapus secara permanen (*force delete*), bukan saat *soft delete*.

### 4.2. Draft Auto-save & Riwayat Revisi Konten (*Version History*)
* **Kondisi saat ini**: Tidak ada penyimpanan otomatis. Jika koneksi terputus, sesi expired, atau tab tertutup saat menulis artikel panjang, seluruh teks hilang.
* **Kebutuhan**:
  - Auto-save ke `localStorage` browser setiap 15 detik sebagai perlindungan darurat di sisi klien.
  - Endpoint AJAX background draft auto-save di server setiap 60 detik.
  - Tabel `artikel_revisions` (`artikel_id`, `user_id`, `konten`, `ringkasan`, `created_at`) untuk mencatat riwayat perubahan dan menyediakan tombol perbandingan versi (*diff view*) serta tombol pemulihan (*rollback*).

### 4.3. Alur Kerja Editorial Multi-level (*Editorial Workflow*)
* **Kondisi saat ini**: Penulis yang disetujui dapat langsung mengubah status artikel menjadi `published` dan menayangkannya ke situs publik tanpa review.
* **Kebutuhan**:
  - Status artikel bertingkat:
    1. `draft`: Dikerjakan oleh penulis.
    2. `pending_review`: Penulis mengajukan artikel untuk ditinjau editor.
    3. `needs_revision`: Editor mengembalikan artikel beserta catatan perbaikan.
    4. `approved` / `scheduled`: Disetujui editor dan dijadwalkan tayang.
    5. `published`: Tayang di situs publik.
    6. `archived`: Diarsipkan dari peredaran publik.
  - Kolom catatan editorial (*editorial notes*) antara Editor dan Penulis.

### 4.4. Pusat Pengaturan Situs Dinamis (*Site Settings Manager*)
* **Kondisi saat ini**: Nilai kontak, deskripsi meta situs, link media sosial (Instagram, Facebook), dan kode AdSense tersebar secara hardcoded di template Blade.
* **Kebutuhan**:
  - Menu "Pengaturan Situs" di admin:
    - Informasi Umum: Nama Situs, Tagline, Logo Light/Dark, Favicon.
    - Media Sosial: URL Instagram, X (Twitter), YouTube, TikTok, Facebook, Threads.
    - Kode Integrasi: Google AdSense Publisher ID, Google Analytics ID, Meta Pixel (jika dibutuhkan).
    - Kontak: Alamat email resmi, nomor WhatsApp redaksi, alamat kantor.

### 4.5. Log Aktivitas & Jejak Audit (*Activity Logs*)
* **Kondisi saat ini**: Tidak ada riwayat aktivitas sistem. Jika sebuah artikel terhapus, kategori berganti, atau status akun berubah, tidak diketahui siapa admin/penulis yang melakukannya.
* **Kebutuhan**:
  - Tabel `activity_logs` (`user_id`, `action`, `subject_type`, `subject_id`, `ip_address`, `changes_json`, `created_at`).
  - Tampilan log di dashboard admin untuk pemantauan keamanan operasional.

### 4.6. Cadangan Data Mandiri (*Backup & Export Tool*)
* **Kondisi saat ini**: Pencadangan hanya bergantung pada cPanel hosting.
* **Kebutuhan**:
  - Opsi ekspor data artikel ke format JSON / CSV / Markdown melalui admin panel.
  - Fitur backup terjadwal database MySQL via artisan command atau integrasi paket `spatie/laravel-backup`.

---

## 5. Fitur Niche Khusus Media & Literasi Sejarah

Sebagai platform bertema sejarah dunia dan nusantara, Look at History memiliki peluang besar untuk menyajikan fitur pembeda yang meningkatkan otoritas konten (*E-E-A-T*) dan retensi pembaca:

### 5.1. Garis Waktu Interaktif (*Historical Interactive Timeline*)
* **Konsep**: Komponen visual interaktif di halaman artikel atau halaman Era yang menampilkan urutan kronologi peristiwa penting tahun demi tahun.
* **Penerapan Teknis**:
  - Field data JSON pada artikel: `timeline_events: [{year: "1755", event: "Penandatanganan Perjanjian Giyanti"}, ...]`.
  - Komponen Blade horizontal/vertikal interaktif dengan penanda tahun dan ringkasan peristiwa.

### 5.2. Peta Sejarah & Titik Koordinat Peristiwa (*Historical Geolocation*)
* **Konsep**: Menghubungkan artikel dengan koordinat geografis tempat peristiwa bersejarah terjadi (misal: lokasi benteng, candi, ibu kota kerajaan kuno, medan pertempuran).
* **Penerapan Teknis**:
  - Kolom `latitude` & `longitude` pada model `Artikel`.
  - Integrasi peta berbasis Leaflet.js / OpenStreetMap ringan (tanpa biaya API berbayar) di bawah artikel untuk melihat lokasi peristiwa di peta modern.

### 5.3. Sistem Catatan Kaki & Sitasi Ilmiah (*Footnotes & Citations*)
* **Konsep**: Standar tulisan sejarah menuntut adanya sumber rujukan (buku arsip, prasasti, jurnal ilmiah).
* **Penerapan Teknis**:
  - Syntax parser di Blade/PHP: `[^1]` otomatis dikonversi menjadi link referensi kecil yang ketika di-hover/klik memunculkan popover kutipan dan menggulir ke daftar pustaka di kaki artikel.
  - Schema.org markup `citation` pada JSON-LD artikel.

### 5.4. Slider Komparasi Foto Arsip (*Before & After / Dulu vs Sekarang*)
* **Konsep**: Menampilkan perbandingan foto arsip sejarah masa lalu (misal Batavia tahun 1910) dengan kondisi tempat yang sama di era modern (Jakarta tahun 2026).
* **Penerapan Teknis**:
  - Komponen Blade ringan berbantu CSS clip-path & handle geser JavaScript (tanpa library berat).

### 5.5. Glosarium & Penjelasan Istilah Otomatis (*Historical Glossary Popover*)
* **Konsep**: Tulisan sejarah sarat dengan istilah arkais atau terminologi khusus (misal: *Cultuurstelsel*, *Pax Neerlandica*, *Tridharma*, *Hanseatic League*).
* **Penerapan Teknis**:
  - Tabel `glossary_terms` (`term`, `definition`).
  - Filter konten yang mendeteksi istilah ini dan membungkusnya dengan elemen `<abbr>` atau tooltip ringkas berisi definisi saat kursor diarahkan ke kata tersebut.

### 5.6. Peningkatan Kenyamanan Membaca (*Reader Comfort & Accessibility*)
* **Penyetel Tipografi**: Tombol pengubah ukuran font (A- / A+) dan pemilihan font pembaca (Serif untuk nuansa buku klasik vs Sans-Serif untuk layar modern).
* **Audio Text-to-Speech (TTS) / Narasi**: Pemutar audio sintetis atau berkas rekaman suara narator agar pembaca dapat mendengarkan cerita sejarah sambil beraktivitas.
* **Format Cetak Ramah Kertas (*Clean Print Stylesheet*)**: `@media print` CSS yang menghilangkan navbar, iklan AdSense, footer, dan tombol interaksi sehingga pembaca atau pelajar dapat mencetak artikel sebagai materi riset fisik dengan bersih.

---

## 6. Audit Teknis, Performa, & Keamanan

### 6.1. Caching Query Database
* **Temuan**: Query pada halaman utama (`welcome.blade.php`), sitemap (`/sitemap.xml`), dan sidebar taksonomi dieksekusi langsung ke MySQL pada setiap request tanpa caching.
* **Rekomendasi**:
  - Gunakan `Cache::remember('homepage_featured', 3600, fn() => ...)` untuk artikel unggulan, daftar kategori, era, dan topik terpopuler.
  - Terapkan cache invalidation di Observer model `Artikel`: bersihkan cache berelasi setiap kali ada artikel yang dibuat, diperbarui, atau dihapus.

### 6.2. Sanitasi Konten HTML & Pencegahan Stored XSS
* **Temuan**: Konten dari editor Quill disimpan dalam format HTML mentah dan dirender di publik menggunakan tag `{!! $articleContent !!}` tanpa pembersihan (HTMLPurifier). Jika akun penulis dikompromikan, skrip berbahaya dapat disisipkan ke dalam konten artikel.
* **Rekomendasi**: Pasang sanitasi tag ketat menggunakan library sanitasi (seperti `mews/purifier` atau helper DOMDocument kustom) sebelum menyimpan atau sebelum merender konten ke browser.

### 6.3. Efisiensi Skema Database
* Kolom `Pageview`: Pastikan index gabungan `INDEX pageviews_created_artikel_idx (created_at, artikel_id)` tersedia untuk mempercepat kalkulasi grafik analitik harian tanpa membaca seluruh tabel.
* Kolom `Slug`: Ubah skema index unik slug agar kompatibel dengan SoftDeletes, atau gunakan format slug unik permanen.

---

## 7. Matriks Prioritas & Roadmap Rekomendasi (Framework MoSCoW)

Untuk mempermudah eksekusi tanpa membebani kapasitas tim, seluruh rekomendasi dikelompokkan ke dalam 4 kuadran prioritas:

```
                      DAMPAK TINGGI (HIGH IMPACT)
             ┌─────────────────────────┬─────────────────────────┐
             │  PRIORITAS 1: SEGERA    │  PRIORITAS 2: STRATEGIS │
             │  • Custom Slug + 301    │  • Media Library        │
             │  • Collision Handler    │  • Draft Auto-save      │
             │  • Async Queue Buletin  │  • Revision History     │
             │  • Database Inbox Pesan │  • Dynamic Internal Link│
             │  • Responsive Images    │  • Interactive Timeline │
RENDAH USAHA ├─────────────────────────┼─────────────────────────┤ TINGGI USAHA
(LOW EFFORT) │  PRIORITAS 3: CEPAT     │  PRIORITAS 4: INOVASI   │ (HIGH EFFORT)
             │  • Sanitasi XSS Konten  │  • Audio Player (TTS)   │
             │  • Filter Faceted Cari  │  • Peta Sejarah (Map)   │
             │  • Bookmark Null-Safe   │  • Glosarium Popover    │
             │  • Print Stylesheet     │  • Slider Foto Komparasi│
             │  • Tipografi A-/A+      │  • Multi-level Workflow │
             └─────────────────────────┴─────────────────────────┘
                      DAMPAK MODERAT / NICHE
```

### Rincian Pembagian Sprint Implementasi:

#### Sprint A — Keutuhan URL, SEO, & Stabilitas Fondasi (Must Have)
1. **Custom Slug & Collision Handler**: Input slug mandiri di form artikel dan pembuatan otomatis suffix unik jika terjadi kesamaan slug.
2. **Sistem Redirect 301**: Tabel migrasi `url_redirects` dan penanganan perpindahan slug lama ke slug baru agar tidak memicu 404.
3. **Queue Asinkron Buletin**: Pindahkan pengiriman email newsletter ke antrean database background job.
4. **Inbox Formulir Kontak**: Tabel penyimpanan pesan masuk publik ke database dan halaman manajemen pesan di admin.
5. **Aktivasi Gambar Responsif**: Menghubungkan fungsi `optimizeResponsive()` ke Blade menggunakan tag `<picture>`.

#### Sprint B — Efisiensi Editorial & Pengayaan Konten (Should Have)
1. **Media Library Terpusat**: Panel arsip foto, atribusi sumber sejarah, dan alt-text manager.
2. **Draft Auto-save & Versi Revisi**: Penyimpanan otomatis draft dan pemulihan revisi artikel.
3. **Tautan Internal Dinamis (Database-driven)**: Menggantikan array statis `InternalLinker.php` dengan tabel basis data dan modul admin.
4. **Garis Waktu Peristiwa (Interactive Timeline)**: Komponen kronologi sejarah di Blade artikel.
5. **Sitasi & Catatan Kaki Interaktif**: Dukungan penomoran referensi ilmiah dan daftar pustaka.

#### Sprint C — Pengalaman Pembaca & Komunitas (Could Have)
1. **Modal Pencarian Cepat (Quick Search `Ctrl+K`)**: Live suggestion artikel berbasis AJAX.
2. **Fitur Pembaca Literasi**: Pengatur ukuran font (A-/A+), pilihan font serif/sans, dan print-friendly stylesheet.
3. **Notifikasi Interaksi**: Pemberitahuan email saat komentar pembaca mendapat balasan.
4. **Slider Komparasi Foto Arsip**: Komponen visual interaktif foto masa lalu vs masa kini.
5. **Optimasi Dashboard Analitik**: Visualisasi sumber perujuk (AI referral, Search, Social) dan agregasi query cepat.

---
*Dokumen ini disusun sebagai panduan teknis pengembangan fitur berkelanjutan untuk repositori Look at History.*
