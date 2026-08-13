# AGENTS.md

Pedoman untuk AI coding agent yang bekerja di repo ini. Tujuan utama: **menghasilkan kode/output berkualitas seperti manusia, bukan AI slop.**

## Anti AI Slop

JANGAN pernah melakukan hal-hal berikut:

- Menambah komentar yang menjelaskan kode yang sudah jelas (mis. `// create a new user`).
- Menulis docblock/footer yang bertele-tele untuk setiap fungsi.
- Menggunakan kalimat generik seperti "Here's the code!", "Let's dive in!", "Great question!".
- Menambahkan emoji di kode, komentar, atau message commit.
- Membuat file dokumentasi (README, docs) tanpa diminta.
- Menulis kode contoh/showcase yang berlebihan di luar skop tugas.
- "Refactor" seluruh file hanya untuk gaya, tanpa alasan fungsional.
- Menghasilkan placeholder tanpa nilai: `// TODO: implement`, `// ...more code here`.
- Meng-commit perubahan yang tidak diminta atau di luar skop tugas.

## Proyek Ini

- **Stack**: Laravel 13 (PHP ^8.3), MySQL, Blade + Tailwind v4 via Vite. **Bukan** Inertia/SPA.
- **Kondisi saat ini**: skeleton default + minimal User–Role many-to-many. Model: `User`, `Role` (helper `hasRole()`). Migrasi pivot `role_user` sudah ada.
- **Routes**: hanya `routes/web.php` (GET `/` → `welcome`). Belum ada `routes/api.php`.
- **UI**: bahasa Indonesia untuk konten; nama kode tetap English.

## Perintah

- **Test**: `composer run test` (jalankan `php artisan config:clear` dulu di script-nya).
- **Format**: `vendor/bin/pint` (tidak ada script `pint` di composer).
- **Dev loop**: `npm run dev` (vite) atau `composer run dev` (server + queue + pail + vite sekaligus).
- **Setup fresh**: `composer run setup`.

## External File Loading

JANGAN pre-load semua referensi di awal. Buka file hanya saat relevan dengan tugas yang sedang dikerjakan (lazy loading):

- Untuk pola arsitektur/repo secara umum: @docs/architecture.md
- Untuk konvensi Laravel & model: @docs/laravel.md
- Untuk konvensi UI/Blade & gaya: @docs/blade-ui.md

File-file di atas DIBUAT SAAT DIBUTUHKAN (mis. saat menyusun arsitektur pertama kali), bukan sekarang. Jika referensi belum ada, selesaikan tugas langsung tanpa membuatnya.

## Cara Bekerja

1. Pahami skop dulu: baca kode/route/model terkait sebelum menulis.
2. Lakukan perubahan minimal yang dibutuhkan — no gold-plating.
3. Verifikasi sebelum selesai: jalankan `vendor/bin/pint` dan `composer run test` bila relevan.
4. Saat diminta menjelaskan output, jawab ringkas dan langsung ke poin.