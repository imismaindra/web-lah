# SEO Analytics & Monitoring

Dokumen ini menjelaskan event GA4 yang dikirim oleh website Look at History dan pemeriksaan berkala yang masih perlu dilakukan melalui akun Google.

## Event GA4

| Event | Pemicu | Parameter |
|---|---|---|
| `search` | Pengunjung mengirim formulir pencarian | `search_term` (kata kunci pencarian yang dikirim pengunjung) |
| `share` | Pengunjung memilih WhatsApp, Facebook, atau Salin Link pada artikel | `method`, `content_type=article`, `item_id` |
| `sign_up` | Server mengonfirmasi pendaftaran newsletter berhasil | `method=newsletter` |
| `social_profile_click` | Pengunjung membuka tautan profil Instagram dari halaman artikel | `method=Instagram`, `content_type=article`, `item_id` |

Event hanya dikirim jika Measurement ID GA4 dikonfigurasi dan halaman bukan halaman pratinjau. Halaman publik yang menggunakan `noindex` (misalnya hasil pencarian) tetap dapat mengirim event; `noindex` adalah instruksi pengindeksan, bukan penanda bahwa halaman bersifat privat.

Website tetap mempertahankan penghitung view artikel dan tabel `pageviews` internal. Angka tersebut tidak identik dengan metrik GA4 karena definisi, deduplikasi, consent, pemblokir iklan, dan metode pengukuran berbeda.

## Pemeriksaan GA4 setelah rilis

1. Pastikan `GA4_MEASUREMENT_ID=G-1TZSDZL104` tersedia pada environment production.
2. Jalankan `php artisan config:clear` lalu `php artisan config:cache` setelah mengubah environment.
3. Buka GA4 **Reports → Realtime** atau **Admin → DebugView** saat menguji.
4. Uji halaman artikel, tombol WhatsApp/Facebook/Salin Link, formulir pencarian, dan pendaftaran newsletter yang berhasil.
5. Pastikan halaman pratinjau tidak mengirim page_view maupun event GA4.
6. Periksa event `search`, `share`, `sign_up`, dan `social_profile_click` muncul sesuai tindakan. Browser dengan ad blocker atau pengaturan privasi dapat memblokir pengiriman.

## Google Search Console

Search Console tidak dapat diaktifkan hanya melalui perubahan source code. Pemilik properti harus memverifikasi domain melalui DNS atau metode verifikasi yang tersedia di akun Google, lalu:

1. Tambahkan properti domain `lookathistory.web.id`.
2. Selesaikan verifikasi kepemilikan.
3. Kirim sitemap `https://lookathistory.web.id/sitemap.xml`.
4. Pantau **Indexing → Pages**, **Sitemaps**, dan **Performance → Search results**.
5. Periksa halaman yang dikecualikan dan alasan pengecualiannya; jangan menganggap semua URL yang tidak terindeks sebagai error.

## Memantau trafik dari AI dan referral

GA4 menerima informasi sumber/referral ketika browser dan platform pengarah mengirimkannya. Identifikasi AI referral tidak selalu lengkap: sebagian kunjungan dapat muncul sebagai referral biasa, direct, atau tidak teridentifikasi.

Untuk laporan awal, periksa **Reports → Acquisition → Traffic acquisition**, lalu evaluasi dimensi **Session source / medium** dan landing page. Jangan menganggap seluruh trafik direct berasal dari AI atau mengklaim angka AI traffic jika sumbernya tidak dapat diidentifikasi. Jika ingin pengelompokan khusus, buat custom channel group di GA4 berdasarkan domain sumber yang benar-benar muncul pada data.

## Batasan dan privasi

- Jangan menambahkan email, nama, nomor telepon, isi komentar, atau data pribadi lain ke parameter event.
- Event `search` saat ini mengirim parameter `search_term`. Jangan memasukkan data pribadi atau informasi sensitif ke kolom pencarian; jika kebutuhan privasi mengharuskan kata kunci tidak dikirim ke pihak analitik, hapus parameter `search_term` dari implementasi sebelum rilis.
- Jangan menyamakan `pageviews` internal dengan `views` GA4.
- Verifikasi laporan berdasarkan data aktual setelah rilis; source code saja tidak membuktikan bahwa event sudah diterima GA4.
- Koneksi GA4 dan Search Console, izin akun, serta verifikasi domain harus dilakukan oleh pemilik akun.
