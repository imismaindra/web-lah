# Web Lah

Platform blog dan artikel berbasis Laravel. Dibangun dengan Blade + Tailwind CSS, dirancang untuk kemudahan pengelolaan konten.

## Fitur

- **Artikel & Konten** -- CRUD artikel dengan dukungan kategori, era, topik, dan penulis
- **Autentikasi** -- Login, registrasi, verifikasi email, lupa password
- **Sistem Peran** -- Manajemen role pengguna (admin, penulis, pembaca)
- **Panel Admin** -- Dashboard admin untuk mengelola artikel, kategori, pengguna, dan newsletter
- **Komentar & Reaksi** -- Sistem komentar berjenjang dan reaksi (suka) pada artikel
- **Bookmark** -- Penyimpanan artikel favorit oleh pengguna
- **Newsletter** -- Sistem langganan email dengan unsubscribe
- **Pencarian** -- Fitur pencarian artikel
- **SEO** -- Sitemap XML dan RSS feed
- **Responsif** -- Desain mobile-friendly dengan Tailwind CSS v4

## Tech Stack

| Komponen       | Teknologi                          |
| -------------- | ---------------------------------- |
| Backend        | Laravel 13, PHP ^8.3               |
| Database       | MySQL / SQLite                     |
| Frontend       | Blade, Tailwind CSS v4             |
| Build Tool     | Vite 8                             |
| Font           | Instrument Sans (via Bunny)        |
| Permission     | Spatie Laravel Permission          |
| Image          | Intervention Image                 |

## Persiapan

### Prasyarat

- PHP ^8.3
- Composer
- Node.js ^18
- MySQL atau SQLite

### Instalasi

```bash
# Clone repository
git clone <url-repo>
cd web-lah

# Jalankan setup otomatis
composer run setup
```

Perintah `composer run setup` akan menjalankan:
1. `composer install`
2. Menyalin `.env.example` ke `.env` (jika belum ada)
3. Generate application key
4. Menjalankan migrasi database
5. `npm install`
6. `npm run build`

### Konfigurasi Manual

Jika ingin menjalankan langkah secara manual:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
```

Konfigurasi database di file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=web_lah
DB_USERNAME=root
DB_PASSWORD=
```

## Perintah

| Perintah                | Fungsi                                      |
| ----------------------- | ------------------------------------------- |
| `composer run setup`    | Setup awal dari nol                         |
| `composer run dev`      | Jalankan server + queue + logs + Vite        |
| `npm run dev`           | Jalankan Vite dev server saja                |
| `composer run test`     | Jalankan test suite                          |
| `vendor/bin/pint`       | Format kode dengan PHP-CS-Fixer (Laravel Pint) |

## Struktur Direktori

```
web-lah/
├── app/
│   ├── Http/Controllers/   # Controller (Auth, Admin, Public)
│   └── Models/             # Eloquent models
├── config/                 # Konfigurasi aplikasi
├── database/
│   ├── migrations/         # Migrasi database
│   └── seeders/            # Database seeder
├── public/                 # Document root
├── resources/
│   ├── css/                # Stylesheet
│   ├── js/                 # JavaScript
│   └── views/              # Blade templates
├── routes/
│   └── web.php             # Route definisi
├── tests/                  # Unit & feature tests
├── composer.json
├── package.json
└── vite.config.js
```

## Lisensi

MIT License
