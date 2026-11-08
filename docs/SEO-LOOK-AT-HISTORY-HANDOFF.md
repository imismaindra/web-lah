# Dokumentasi Proyek SEO --- Look at History

> **Dokumen handoff / kelanjutan pekerjaan**\
> **Website:** https://lookathistory.web.id/\
> **Repository:** https://github.com/imismaindra/web-lah\
> **Tanggal pembaruan:** 8 November 2026\
> **Status keseluruhan:** Sprint 4 sedang berjalan --- implementasi inti
> GA4 sudah dilakukan, tetapi validasi event lanjutan, audit Search
> Console, analisis referral AI, dan dashboard belum selesai.

Dokumen ini dibuat agar pekerjaan SEO Look at History dapat dilanjutkan
di percakapan ChatGPT lain atau dikerjakan secara mandiri tanpa harus
mengulang seluruh riwayat diskusi. Status di bawah membedakan hal yang
**sudah diterapkan**, **sudah diuji**, dan **belum dibuktikan**.

------------------------------------------------------------------------

## 1. Ringkasan eksekutif

Proyek ini bertujuan meningkatkan kualitas SEO teknis, visibilitas
pencarian, pemahaman performa konten, dan kemampuan pengambilan
keputusan berbasis data untuk website Look at History.

Pekerjaan dibagi menjadi sprint agar perubahan dapat dilacak dan diuji.
Sprint 1--3 telah diselesaikan menurut riwayat proyek. Sprint 4 berfokus
pada analytics dan monitoring. Implementasi GA4 dasar serta pelacakan
pencarian sudah berjalan dan event `search` telah terlihat di GA4
Realtime. Namun, Sprint 4 **belum boleh dianggap selesai**, karena
sejumlah event lain belum dikonfirmasi penerimaannya di GA4, audit
Search Console belum dituntaskan, referral dari platform AI belum
dianalisis, dan dashboard belum dibuat.

### Status sprint

  -----------------------------------------------------------------------
  Sprint                  Fokus                   Status
  ----------------------- ----------------------- -----------------------
  Sprint 1                Technical SEO           Selesai menurut riwayat
                                                  proyek

  Sprint 2                Advanced SEO            Selesai menurut riwayat
                                                  proyek

  Sprint 3                GEO/AEO dan fondasi     Selesai menurut riwayat
                          integrasi GA4           proyek

  Sprint 4                Analytics & Monitoring  **Berjalan --- belum
                                                  selesai**

  Sprint 5                SEO Performance &       Belum dimulai; ruang
                          Content Optimization    lingkup awal sudah
                                                  diusulkan
  -----------------------------------------------------------------------

> Catatan: status Sprint 1--3 mengikuti catatan pekerjaan sebelumnya.
> Jika dibutuhkan audit ulang menyeluruh, periksa repository dan website
> produksi, jangan hanya mengandalkan label status dokumen ini.

------------------------------------------------------------------------

## 2. Identitas dan konfigurasi proyek

  Item                                Nilai
  ----------------------------------- ---------------------------------
  Nama proyek                         Look at History
  Website produksi                    `https://lookathistory.web.id/`
  Repository GitHub                   `imismaindra/web-lah`
  Platform aplikasi                   Laravel Blade
  Hosting                             cPanel
  GA4 Property ID                     `562886771`
  GA4 Property name                   `Look at History`
  GA4 Measurement ID                  `G-1TZSDZL104`
  GA4 hostname yang teramati          `lookathistory.web.id`
  GA4 stream name yang teramati       `Web Look at History`
  Zona waktu laporan yang digunakan   `Asia/Jakarta`

**Penting:** Property ID dan Measurement ID adalah dua identitas yang
berbeda. Property ID digunakan untuk memilih properti pada
konektor/laporan; Measurement ID digunakan pada konfigurasi tag website.

------------------------------------------------------------------------

## 3. Tujuan utama proyek

### Tujuan jangka menengah

1.  Memastikan website dapat dirayapi dan diindeks dengan benar oleh
    mesin pencari.
2.  Mengurangi masalah SEO teknis yang menghambat halaman penting.
3.  Memperkuat struktur konten, metadata, schema, dan internal linking.
4.  Mengukur perilaku pengunjung menggunakan GA4, bukan sekadar memasang
    tag.
5.  Mengetahui konten dan kanal yang menghasilkan kunjungan serta
    interaksi.
6.  Mengidentifikasi peluang keyword dan konten berdasarkan data aktual.
7.  Membuat proses monitoring yang dapat dilanjutkan secara berkala.

### Hasil akhir yang diharapkan

-   Fondasi technical SEO tervalidasi.
-   Halaman penting memiliki struktur SEO yang konsisten.
-   Data GA4 akurat dan event penting dapat dilaporkan.
-   Masalah indexing Search Console dipahami dan diprioritaskan.
-   Traffic dari mesin pencari dan referral AI dapat dianalisis.
-   Dashboard SEO/analytics tersedia untuk memantau perkembangan.
-   Backlog optimasi konten tersusun berdasarkan dampak dan bukti data.

------------------------------------------------------------------------

## 4. Roadmap dan progress

### Sprint 1 --- Technical SEO

**Tujuan:** membangun fondasi teknis agar crawler memahami halaman
website dan URL yang benar.

Ruang lingkup yang tercatat: - Pemeriksaan `robots.txt`. - Pemeriksaan
canonical URL. - Pemeriksaan sitemap. - Pemeriksaan metadata dan preview
teknis. - Pemeriksaan dasar kemampuan crawling dan indexability.

**Status:** selesai menurut riwayat proyek.

**Validasi yang tetap dapat dilakukan saat audit akhir:** - Buka
`https://lookathistory.web.id/robots.txt`. - Buka sitemap produksi dan
pastikan URL yang dimuat memang URL kanonis. - Periksa source HTML
halaman utama dan artikel. - Pastikan canonical menunjuk ke URL yang
benar dan tidak mengarah ke halaman yang salah. - Pastikan halaman yang
memang harus diindeks tidak terkena `noindex`.

### Sprint 2 --- Advanced SEO

**Tujuan:** memperbaiki struktur SEO di tingkat halaman dan hubungan
antarhalaman.

Ruang lingkup yang tercatat: - Sitemap dan `lastmod`. - Filter
taksonomi/kategori. - Schema markup. - Internal linking. - Struktur
heading. - Konsistensi elemen SEO di halaman.

**Status:** selesai menurut riwayat proyek.

**Validasi akhir yang disarankan:** - Validasi schema pada homepage dan
artikel. - Pastikan struktur heading logis dan tidak melompat tanpa
alasan. - Pastikan artikel terkait dan tautan internal relevan. -
Pastikan halaman kategori/tag tidak menghasilkan duplikasi yang tidak
perlu. - Periksa pagination, halaman pencarian, dan URL parameter.

### Sprint 3 --- GEO/AEO dan fondasi pengukuran

**Tujuan:** meningkatkan kesiapan konten untuk mesin pencari berbasis AI
dan membangun fondasi pengukuran.

Ruang lingkup yang tercatat: - Arahan crawler AI. - Kebijakan
editorial/author. - Referensi sumber. - Template berdasarkan search
intent. - Integrasi dasar GA4.

**Status:** selesai menurut riwayat proyek.

**Catatan:** Integrasi GA4 dasar kemudian menjadi bagian dari pekerjaan
validasi Sprint 4. Jangan menyamakan "tag sudah terpasang" dengan
"seluruh event sudah tervalidasi".

### Sprint 4 --- Analytics & Monitoring

**Tujuan:** memastikan data pengunjung dan interaksi tercatat dengan
benar, lalu mengubah data itu menjadi laporan yang dapat digunakan.

Ruang lingkup: 1. Integrasi GA4. 2. Validasi `page_view`. 3. Pelacakan
pencarian internal. 4. Pelacakan interaksi penting seperti share, klik
profil sosial, dan pendaftaran newsletter. 5. Pemeriksaan data historis
GA4. 6. Audit Search Console. 7. Pemeriksaan referral dari platform AI.
8. Pembuatan dashboard. 9. Dokumentasi dan verifikasi akhir.

**Status:** berjalan, belum selesai.

Rincian status aktual dijelaskan pada bagian 5.

### Sprint 5 --- SEO Performance & Content Optimization

**Status:** belum dimulai. Ruang lingkup berikut adalah proposal awal,
bukan pekerjaan yang sudah selesai atau sudah disetujui sebagai
implementasi final.

Tujuan: - Menemukan peluang keyword dan topik. - Mengidentifikasi
artikel yang perlu diperbarui. - Meningkatkan CTR dari hasil
pencarian. - Memperkuat internal linking berdasarkan hubungan topik. -
Menemukan content gap. - Menyusun prioritas optimasi berdasarkan data
Search Console dan GA4. - Memantau perubahan performa setelah optimasi.

Sprint 5 sebaiknya dimulai setelah data Sprint 4 cukup dapat dipercaya.
Jangan membuat keputusan konten berdasarkan laporan analytics yang masih
belum lengkap.

------------------------------------------------------------------------

## 5. Detail pekerjaan Sprint 4

### 5.1 Integrasi GA4

**Status: berhasil terpasang dan data dasar terbaca.**

Informasi: - Measurement ID: `G-1TZSDZL104`. - Property ID:
`562886771`. - Property yang benar: `Look at History`. - Hostname yang
terbaca: `lookathistory.web.id`. - Stream name yang terbaca:
`Web Look at History`.

Pemeriksaan sebelumnya menemukan tag GA4 dimuat di website. Event dasar
`page_view` juga pernah terlihat.

### 5.2 Event `search`

**Status: implementasi dan penerimaan Realtime sudah dikonfirmasi.**

Pekerjaan terkait: - PR #8 menambahkan atribut pelacakan pencarian pada
elemen pencarian desktop dan navigasi mobile. - PR #9 memperbaiki
potensi race condition saat form pencarian melakukan navigasi.

PR #9: - URL: https://github.com/imismaindra/web-lah/pull/9 - Branch:
`seo/sprint-4-search-navigation-fix` - Merge commit:
`debcbb1f925efdec6ecff3216788d15ab2c79edf` - GitHub Actions run ID:
`42461192011` - Status run: completed, conclusion success. - Deployment
cPanel pada pemeriksaan sebelumnya dinyatakan berhasil.

Mekanisme yang diterapkan pada form pencarian: 1. Listener menangkap
submit. 2. Nilai `q` dibaca dan di-trim. 3. Form dicegah submit
langsung. 4. Event GA4 `search` dikirim dengan parameter `search_term`.
5. Callback atau timeout digunakan agar navigasi tetap dilanjutkan. 6.
Jika `gtag` tidak tersedia, form tetap memiliki fallback submit.

Hasil uji produksi yang dikirim pengguna melalui screenshot GA4
Realtime:

  Event                     Jumlah yang terlihat saat pengujian
  ----------------------- -------------------------------------
  `page_view`                                                12
  `view_search_results`                                      12
  `form_submit`                                               7
  `search`                                                    7
  `form_start`                                                6

Angka tersebut adalah hasil pengujian saat itu, bukan metrik performa
permanen atau total traffic website.

**Kesimpulan:** event `search` telah terbukti masuk ke GA4 Realtime.
Parameter `search_term` juga pernah diverifikasi dalam pengujian
sebelumnya.

### 5.3 Event lain yang sudah ada di kode

Audit repository sebelumnya menemukan implementasi berikut:

  ------------------------------------------------------------------------------
  Event                    Elemen/aksi       Temuan kode       Status penerimaan
                                                               GA4
  ------------------------ ----------------- ----------------- -----------------
  `share`                  WhatsApp,         Atribut dan       Belum
                           Facebook, salin   listener          dikonfirmasi
                           tautan            pelacakan         
                                             tersedia          

  `social_profile_click`   Klik tautan       Atribut event     Belum
                           Instagram         tersedia          dikonfirmasi

  `sign_up`                Newsletter        Event dipicu      Belum
                           berhasil          setelah server    dikonfirmasi
                           didaftarkan       mengonfirmasi     
                                             pendaftaran       
                                             sukses            
  ------------------------------------------------------------------------------

Contoh atribut yang ditemukan: - Tombol salin tautan memiliki
`data-ga4-event="share"`. - Metode berbagi disimpan melalui
`data-ga4-method`. - Tipe konten dan ID artikel disertakan. - Tautan
Instagram memiliki `data-ga4-event="social_profile_click"`. - Event
newsletter dikirim setelah respons sukses dari server.

**Penting:** keberadaan kode bukan bukti bahwa event sudah diterima GA4.
Masing-masing event perlu diuji di Realtime/DebugView atau laporan event
GA4.

### 5.4 Dokumentasi event

PR #10: - URL: https://github.com/imismaindra/web-lah/pull/10 - Branch:
`docs/ga4-event-documentation-sync` - Merge commit:
`10fa5e53a5be6b21fd256ed1cfb732afb18fa7cd` - Status: merged. - Waktu
merge yang tercatat: `2026-11-08T08:57:42Z`.

Isi perubahan: - Menyelaraskan dokumentasi event GA4 dengan implementasi
`search_term`. - Menambahkan peringatan privasi agar data sensitif tidak
dikirim melalui parameter pencarian. - Memperbaiki typo dokumentasi.

PR #10 hanya mengubah dokumentasi. PR ini tidak mengubah kode produksi
atau konfigurasi GA4.

### 5.5 Koneksi Supermetrics

**Status: koneksi terautentikasi dan data dasar dapat dibaca.**

Hasil pemeriksaan sebelumnya: - Data source ID: `GAWA`. - Authentication
status: `AUTHENTICATED`. - Connector: Google Analytics 4. -
Akun/properti yang ditemukan: `562886771` --- `Look at History`. - Field
discovery berhasil menemukan dimensi dan metrik. - Field yang relevan
antara lain: - `date` - `eventName` - `eventCount` - `streamName` -
`hostName` - `searchTerm`

Koneksi ini penting untuk analisis laporan dan pembuatan dashboard.
Koneksi berhasil tidak otomatis berarti semua laporan sudah benar;
kombinasi dimensi, metrik, filter, dan rentang tanggal tetap perlu
diperiksa.

### 5.6 Data historis yang berhasil dibaca

Beberapa permintaan laporan dengan rentang relatif seperti
`last_7_days`, `last_30_days`, dan `last_90_days` sebelumnya
menghasilkan data kosong. Namun, permintaan dengan tanggal kalender
eksplisit berhasil.

Rentang yang berhasil: - Mulai: `2026-01-01` - Akhir: `2026-11-08` -
Zona waktu: `Asia/Jakarta`

Hasil agregat yang terbaca:

  Event                 Event count
  ------------------- -------------
  `page_view`                    11
  `first_visit`                   5
  `session_start`                 5
  `scroll`                        1
  `user_engagement`               1

Untuk rentang `2026-11-01` sampai `2026-11-08`, data yang terbaca
terkonsentrasi pada `2026-11-08`:

  Tanggal      Event                 Jumlah
  ------------ ------------------- --------
  2026-11-08   `first_visit`              5
  2026-11-08   `page_view`               11
  2026-11-08   `scroll`                   1
  2026-11-08   `session_start`            5
  2026-11-08   `user_engagement`          1

Dimensi stream dan hostname juga menunjukkan: - Stream:
`Web Look at History` - Hostname: `lookathistory.web.id`

**Interpretasi yang benar:** - Koneksi dapat membaca data dari properti
dan stream yang benar. - Data yang muncul masih sangat terbatas. - Angka
di atas tidak cukup untuk menyimpulkan performa website secara umum. -
Data event kustom seperti `search`, `share`, `social_profile_click`, dan
`sign_up` belum muncul pada laporan historis yang diperiksa. - Event
`search` sudah terlihat di Realtime, sehingga laporan historis perlu
diperiksa lagi setelah pemrosesan data GA4 memadai.

GA4 dapat memerlukan waktu sebelum data tersedia di laporan standar.
Karena itu, jangan langsung menyimpulkan implementasi gagal hanya karena
laporan historis belum menampilkan event yang baru diuji. Namun, jangan
pula menganggap event sudah valid hanya berdasarkan kode.

------------------------------------------------------------------------

## 6. Progress Sprint 4 --- checklist aktual

Legenda: - `[x]` selesai / sudah dibuktikan. - `[~]` sebagian selesai
atau belum seluruhnya tervalidasi. - `[ ]` belum dikerjakan atau belum
ada bukti penyelesaian.

### Implementasi dan pengujian

-   [x] Memastikan properti GA4 yang benar.
-   [x] Memastikan Measurement ID website.
-   [x] Memastikan tag GA4 dimuat.
-   [x] Memastikan `page_view` pernah tercatat.
-   [x] Menambahkan pelacakan pencarian.
-   [x] Memperbaiki navigasi form pencarian agar event tidak mudah
    terlewat.
-   [x] Merge PR #9.
-   [x] Memastikan GitHub Actions untuk PR #9 berhasil.
-   [x] Memastikan event `search` terlihat di GA4 Realtime.
-   [x] Memeriksa parameter `search_term` dalam pengujian sebelumnya.
-   [x] Menyelaraskan dokumentasi event melalui PR #10.
-   [x] Merge PR #10.
-   [x] Menghubungkan Supermetrics ke properti GA4 yang benar.
-   [x] Membaca data dasar melalui Supermetrics.
-   [x] Memverifikasi hostname dan stream.
-   \[\~\] Event `share` sudah ada di kode, penerimaan GA4 belum
    dikonfirmasi.
-   \[\~\] Event `social_profile_click` sudah ada di kode, penerimaan
    GA4 belum dikonfirmasi.
-   \[\~\] Event `sign_up` sudah ada di kode, penerimaan GA4 belum
    dikonfirmasi.
-   \[\~\] Laporan historis GA4 sudah terbaca, tetapi event kustom belum
    terlihat dan perlu pemeriksaan lanjutan.

### Monitoring dan pelaporan

-   [ ] Validasi akhir seluruh event kustom.
-   [ ] Audit Search Console.
-   [ ] Pemeriksaan referral dari platform AI.
-   [ ] Menentukan metrik dan struktur dashboard.
-   [ ] Membangun dashboard.
-   [ ] Menyelesaikan dokumentasi hasil uji dan keputusan.
-   [ ] Melakukan pemeriksaan akhir seluruh alur website yang relevan.

### Kesimpulan progress

Sprint 4 sudah melewati tahap pemasangan GA4 dan implementasi pelacakan
pencarian. Fokus berikutnya bukan mengulang instalasi GA4, melainkan
**menutup celah validasi event dan menyelesaikan pekerjaan monitoring**.

------------------------------------------------------------------------

## 7. Aksi selanjutnya --- urutan kerja yang disarankan

Urutan ini dibuat agar pekerjaan tidak lompat ke dashboard atau optimasi
konten sebelum data dasarnya cukup tepercaya.

### Prioritas 1 --- Validasi event kustom GA4

Target: membuktikan event interaksi benar-benar diterima GA4.

Langkah: 1. Uji tombol share WhatsApp. 2. Uji tombol share Facebook. 3.
Uji tombol salin tautan. 4. Uji klik tautan Instagram. 5. Uji
pendaftaran newsletter menggunakan data uji yang aman. 6. Periksa GA4
Realtime atau DebugView. 7. Pastikan nama event dan parameter yang
relevan terbaca. 8. Catat hasil aktual, bukan hanya hasil yang
diharapkan.

Tabel pencatatan:

  ------------------------------------------------------------------------------------
  Skenario       Event yang diharapkan    Hasil aktual   Status         Catatan
  -------------- ------------------------ -------------- -------------- --------------
  Share WhatsApp `share`                  Belum diuji    Pending        Periksa metode
                                          ulang                         share

  Share Facebook `share`                  Belum diuji    Pending        Periksa metode
                                          ulang                         share

  Copy link      `share`                  Belum diuji    Pending        Periksa
                                          ulang                         `copy_link`

  Klik Instagram `social_profile_click`   Belum diuji    Pending        Pastikan tidak
                                          ulang                         terblokir
                                                                        navigasi

  Newsletter     `sign_up`                Belum diuji    Pending        Event harus
  sukses                                  ulang                         setelah server
                                                                        sukses
  ------------------------------------------------------------------------------------

Jangan mengirim alamat email, nomor telepon, nama lengkap, atau data
pribadi ke parameter GA4. Parameter event tidak boleh berisi informasi
identitas pribadi.

**Kriteria selesai:** semua skenario yang relevan menghasilkan event
yang terlihat di GA4 dan parameter penting sesuai implementasi.

### Prioritas 2 --- Audit Search Console

Target: mengetahui kondisi indexing dan menentukan URL yang harus
diperbaiki.

Catatan data historis Search Console: - 35 halaman terindeks. - 28
halaman tidak terindeks. - Total yang tercatat: 63 URL. - 16 URL
berstatus "Ditemukan --- saat ini tidak diindeks". - 6 URL duplikat
dengan Google memilih canonical sendiri. - 3 URL merupakan halaman
redirect. - 3 URL duplikat tanpa canonical pilihan pengguna.

Angka tersebut adalah snapshot historis dari pembahasan sebelumnya,
bukan klaim kondisi Search Console hari ini. Ambil data terbaru sebelum
membuat perubahan.

Langkah: 1. Ambil laporan Page indexing terbaru. 2. Ekspor atau catat
URL pada setiap kategori masalah. 3. Kelompokkan URL: artikel, kategori,
tag, pencarian internal, pagination, dan jenis lainnya. 4. Prioritaskan
artikel penting yang belum diindeks. 5. Periksa kualitas konten,
internal link, canonical, sitemap, dan status HTTP. 6. Jangan mengubah
semua URL sekaligus. 7. Buat daftar tindakan per URL atau kelompok URL.

Keputusan terdahulu: periksa 16 URL "Ditemukan --- saat ini tidak
diindeks" terlebih dahulu dan bedakan artikel penting dari halaman
taksonomi/pencarian/pagination sebelum mengubah kode Laravel.

**Kriteria selesai:** tersedia daftar URL, penyebab yang paling mungkin,
prioritas, dan tindakan yang disetujui.

### Prioritas 3 --- Analisis referral AI

Target: mengetahui apakah website memperoleh kunjungan dari layanan AI
atau situs rujukan terkait.

Langkah: 1. Periksa dimensi sumber/medium dan referral di GA4. 2. Cari
sumber yang relevan, misalnya layanan AI atau domain yang memang muncul
pada data. 3. Bedakan referral aktual dari direct traffic atau referral
yang tidak jelas. 4. Catat sesi, pengguna, landing page, engagement, dan
konversi/interaksi jika tersedia. 5. Jangan mengklaim ada traffic AI
bila datanya tidak muncul.

**Kriteria selesai:** ada ringkasan referral AI berdasarkan data; jika
tidak ditemukan, tulis "belum terdeteksi pada periode pemeriksaan",
bukan "tidak pernah ada".

### Prioritas 4 --- Dashboard analytics

Target: menyajikan metrik inti dalam satu tempat.

Dashboard minimal yang disarankan:

**Ringkasan traffic** - Users - Sessions - Views - Engaged sessions /
engagement rate (jika tersedia dan definisinya jelas) - Tren harian atau
mingguan

**Akuisisi** - Default channel group - Source / medium - Organic
Search - Referral - Direct - Referral dari platform AI jika
teridentifikasi

**Konten** - Landing page - Page path - Views per artikel - Engagement
per halaman - Artikel yang memperoleh traffic organik

**Interaksi** - `search` - `share` - `social_profile_click` - `sign_up`

**SEO Search Console** (jika konektor/data tersedia) - Clicks -
Impressions - CTR - Average position - Queries - Pages

Dashboard harus menyebutkan rentang tanggal, zona waktu, sumber data,
dan definisi metrik. Jangan mencampur data GA4 dan Search Console
seolah-olah metriknya memiliki definisi yang sama.

**Kriteria selesai:** dashboard dapat dibuka, rentang tanggal dapat
diubah, metrik utama terisi, dan angka dapat ditelusuri ke sumber data.

### Prioritas 5 --- Final verification Sprint 4

Lakukan pengujian lintas halaman: - Homepage. - Halaman artikel. -
Halaman kategori. - Halaman pagination kedua. - Halaman pencarian. -
Halaman preview jika ada dan memang digunakan.

Periksa: - Tag GA4 tidak terpasang ganda. - `page_view` tidak tercatat
ganda untuk satu pemuatan halaman normal. - Event pencarian berjalan. -
Event interaksi berjalan. - Tidak ada parameter berisi informasi
pribadi. - Canonical, robots, dan metadata tetap benar. - Tidak ada
error baru setelah perubahan.

**Kriteria Sprint 4 selesai:** event utama tervalidasi, Search Console
telah diaudit, referral AI telah diperiksa, dashboard tersedia atau
keputusan eksplisit dibuat bila dashboard ditunda, dan hasil pengujian
terdokumentasi.

------------------------------------------------------------------------

## 8. Rencana awal Sprint 5

Sprint 5 baru dimulai setelah Sprint 4 cukup stabil.

### Tujuan

Meningkatkan performa konten berdasarkan peluang pencarian dan perilaku
pengguna.

### Paket pekerjaan

1.  **Keyword opportunity**
    -   Temukan query dengan impressions tinggi tetapi CTR rendah.
    -   Temukan query dengan posisi mendekati halaman pertama.
    -   Identifikasi query yang belum memiliki artikel yang tepat.
2.  **Content gap**
    -   Bandingkan topik yang sudah dimiliki dengan query yang
        benar-benar muncul.
    -   Temukan pertanyaan pembaca yang belum dijawab.
    -   Hindari membuat artikel duplikatif hanya untuk mengejar variasi
        keyword.
3.  **Content refresh**
    -   Prioritaskan artikel dengan impressions tetapi CTR atau posisi
        yang lemah.
    -   Periksa akurasi fakta, tanggal, sumber, struktur, dan relevansi.
    -   Perbarui judul dan deskripsi berdasarkan search intent, bukan
        clickbait yang menyesatkan.
4.  **Internal linking**
    -   Hubungkan artikel yang memiliki hubungan topik.
    -   Gunakan anchor text yang deskriptif.
    -   Pastikan artikel penting dapat dijangkau dari halaman lain.
5.  **CTR optimization**
    -   Evaluasi judul dan meta description.
    -   Bandingkan impressions, clicks, CTR, dan posisi.
    -   Ubah secara terukur agar dampaknya dapat dibandingkan.
6.  **Monitoring**
    -   Simpan baseline sebelum perubahan.
    -   Catat tanggal perubahan.
    -   Bandingkan performa setelah periode yang cukup.
    -   Jangan menganggap korelasi sebagai bukti sebab-akibat.

### Output Sprint 5

-   Daftar keyword prioritas.
-   Daftar content gap.
-   Daftar artikel yang harus diperbarui.
-   Rencana internal linking.
-   Daftar perubahan title/meta.
-   Baseline dan hasil monitoring.

------------------------------------------------------------------------

## 9. Aturan kerja dan pengamanan perubahan

Aturan berikut penting untuk kelanjutan proyek:

1.  **Jangan menganggap implementasi = validasi.** Kode yang ada harus
    diuji pada website/GA4.
2.  **Jangan mengarang hasil.** Jika data tidak tersedia, tulis belum
    diketahui atau belum terverifikasi.
3.  **Jangan mengubah produksi tanpa persetujuan.** Periksa branch,
    diff, dan tes sebelum merge/deploy.
4.  **Jangan merge PR yang gagal tes hanya demi melanjutkan sprint.**
    Cari penyebab kegagalan dan perbaiki.
5.  **Pisahkan perubahan kode dan dokumentasi.** PR dokumentasi tidak
    boleh dianggap memperbaiki implementasi.
6.  **Jangan mengirim PII ke GA4.** Search term dan parameter event
    harus bebas dari data pribadi/sensitif.
7.  **Gunakan rentang tanggal eksplisit saat laporan relatif
    menghasilkan data kosong.** Catat timezone dan filter.
8.  **Jangan menyimpulkan performa dari sampel kecil.** Data historis
    yang tercatat pada pemeriksaan ini masih sangat terbatas.
9.  **Jaga ruang lingkup sprint.** Selesaikan validasi dan monitoring
    sebelum beralih ke optimasi konten.
10. **Dokumentasikan setiap pengujian.** Minimal skenario, hasil yang
    diharapkan, hasil aktual, status, dan catatan.

------------------------------------------------------------------------

## 10. Referensi repository dan pekerjaan GitHub

  ------------------------------------------------------------------------------------------------
  Item                    Referensi                                        Keterangan
  ----------------------- ------------------------------------------------ -----------------------
  Repository              https://github.com/imismaindra/web-lah           Source code utama

  PR #9                   https://github.com/imismaindra/web-lah/pull/9    Perbaikan navigasi
                                                                           pencarian dan
                                                                           pengiriman event

  PR #10                  https://github.com/imismaindra/web-lah/pull/10   Sinkronisasi
                                                                           dokumentasi event GA4

  Dokumentasi analytics   `docs/seo-analytics-monitoring.md`               Dokumentasi event dan
                                                                           monitoring

  Partial SEO             `resources/views/partials/seo.blade.php`         Elemen SEO dan sebagian
                                                                           atribut interaksi

  Artikel                 `resources/views/artikel.blade.php`              Tampilan artikel

  Homepage                `resources/views/welcome.blade.php`              Tampilan homepage
  ------------------------------------------------------------------------------------------------

Nama file dan kondisi kode harus diverifikasi kembali pada branch `main`
terbaru sebelum perubahan baru dilakukan.

------------------------------------------------------------------------

## 11. Cara melanjutkan pekerjaan di ChatGPT lain atau secara mandiri

### Prompt handoff

Salin prompt berikut ketika melanjutkan pekerjaan pada percakapan baru:

> Saya sedang melanjutkan proyek SEO website Look at History. Baca
> seluruh dokumen `SEO-LOOK-AT-HISTORY-HANDOFF.md` terlebih dahulu dan
> jadikan dokumen itu sebagai konteks awal proyek.
>
> Website: https://lookathistory.web.id/\
> Repository: https://github.com/imismaindra/web-lah\
> Stack: Laravel Blade, hosting cPanel.\
> GA4 Property ID: 562886771.\
> GA4 Measurement ID: G-1TZSDZL104.
>
> Roadmap: Sprint 1 Technical SEO (selesai menurut catatan), Sprint 2
> Advanced SEO (selesai menurut catatan), Sprint 3 GEO/AEO dan fondasi
> GA4 (selesai menurut catatan), Sprint 4 Analytics & Monitoring (masih
> berjalan), Sprint 5 SEO Performance & Content Optimization (belum
> dimulai).
>
> Fokus saat ini adalah menuntaskan Sprint 4. GA4 dasar sudah terpasang,
> event `search` sudah terlihat di Realtime, PR #9 dan PR #10 sudah
> merged, serta koneksi Supermetrics ke properti yang benar sudah
> terautentikasi. Event `share`, `social_profile_click`, dan `sign_up`
> ditemukan di kode tetapi penerimaannya di GA4 belum dibuktikan.
> Laporan historis GA4 masih terbatas. Audit Search Console, referral
> AI, dan dashboard belum selesai.
>
> Lanjutkan dari bagian "Aksi selanjutnya" pada dokumen. Kerjakan
> pemeriksaan yang dapat dilakukan langsung melalui repository atau
> konektor yang tersedia. Jangan meminta saya mengulang pemeriksaan yang
> sudah dapat kamu lakukan sendiri. Bedakan fakta, dugaan, dan hal yang
> belum terverifikasi. Jangan mengubah produksi, merge, atau deploy
> tanpa persetujuan eksplisit. Jangan mengarang data atau hasil
> pengujian. Berikan langkah yang konkret dan dokumentasikan setiap
> perubahan.

### Sebelum melanjutkan

1.  Pastikan branch lokal/repository berada pada `main` terbaru.
2.  Periksa apakah PR #9 dan #10 masih berada dalam riwayat `main`.
3.  Baca `docs/seo-analytics-monitoring.md`.
4.  Pastikan properti GA4 yang dipilih tetap `562886771`.
5.  Ambil data terbaru sebelum menyimpulkan kondisi analytics atau
    Search Console.
6.  Mulai dari validasi event kustom yang belum terbukti.

------------------------------------------------------------------------

## 12. Log perubahan dokumen

  -----------------------------------------------------------------------
  Tanggal                             Perubahan
  ----------------------------------- -----------------------------------
  2026-11-08                          Membuat dokumentasi handoff
                                      berdasarkan progres sprint,
                                      implementasi GA4, hasil pengujian
                                      Realtime, status PR, koneksi
                                      Supermetrics, serta backlog Sprint
                                      4 dan Sprint 5.

  -----------------------------------------------------------------------

## 13. Ringkasan satu halaman

**Posisi saat ini:** Sprint 4 --- Analytics & Monitoring.

**Yang sudah terbukti:** - GA4 terpasang dan menerima data dasar. -
Event `search` terlihat di GA4 Realtime. - PR #9 sudah merged dan
pipeline deployment sebelumnya sukses. - PR #10 dokumentasi sudah
merged. - Supermetrics terautentikasi ke GA4 Property
`Look at History`. - Data dasar dari stream `Web Look at History` dan
hostname `lookathistory.web.id` dapat dibaca.

**Yang belum terbukti atau belum selesai:** - Event `share`,
`social_profile_click`, dan `sign_up` diterima GA4. - Laporan historis
sudah memuat event kustom secara konsisten. - Audit indexing Search
Console terbaru. - Referral AI. - Dashboard. - Final verification Sprint
4.

**Aksi berikutnya:** 1. Validasi seluruh event kustom. 2. Audit data
indexing Search Console. 3. Periksa referral AI. 4. Buat dashboard
analytics. 5. Jalankan final verification dan tutup Sprint 4. 6. Mulai
Sprint 5 berdasarkan data yang sudah tervalidasi.

------------------------------------------------------------------------

**Akhir dokumen.**