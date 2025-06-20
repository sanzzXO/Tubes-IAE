# TUBES IAE - Sistem Perpustakaan Digital

## Deskripsi
Sistem perpustakaan digital berbasis microservices yang terdiri dari 5 layanan utama untuk mengelola katalog buku, peminjaman, ulasan, autentikasi pengguna, dan antarmuka pengguna.

## Layanan yang Tersedia

### 🔐 Auth Service (Port 8000)
- Registrasi dan login pengguna
- Manajemen role (user/staff)
- Sanctum token authentication

### 📚 Book Catalog Service (Port 8001)
- Manajemen katalog buku
- Kategori buku
- Pencarian dan filter buku

### 📖 Borrowing Service (Port 8002)
- Peminjaman dan pengembalian buku
- Perpanjangan masa pinjam
- Notifikasi keterlambatan

### ⭐ Review Service (Port 8003)
- Ulasan dan rating buku
- Moderasi ulasan
- Statistik rating

### 🖥️ User Interface (Port 8004)
- Antarmuka pengguna terpadu
- Akses ke semua layanan microservice
- Dashboard pengguna dan admin

## Cara Menjalankan

### 1. Clone Repository
```bash
git clone [url-repository]
cd Tubes-IAE
```

### 2. Buat File .env di Setiap Layanan
Salin file `.env.example` menjadi `.env` di setiap folder layanan:
```bash
copy auth-service\.env.example auth-service\.env
copy book-catalog-service\.env.example book-catalog-service\.env
copy borrowing-service\.env.example borrowing-service\.env
copy review-service\.env.example review-service\.env
copy user-interface\.env.example user-interface\.env
```

### 3. Install Dependencies dengan Composer
```bash
composer-install.bat
```

### 4. Generate Application Keys dan Jalankan Migrasi
```bash
cd auth-service && php artisan key:generate && php artisan migrate --seed
cd ../book-catalog-service && php artisan key:generate && php artisan migrate --seed
cd ../borrowing-service && php artisan key:generate && php artisan migrate --seed
cd ../review-service && php artisan key:generate && php artisan migrate --seed
cd ../user-interface && php artisan key:generate && php artisan migrate --seed
cd ..
```

### 5. Jalankan Semua Layanan
```bash
run-all-services.bat
```

### 6. Atau Jalankan Layanan Secara Manual
```bash
cd auth-service && php artisan serve --port=8000
cd book-catalog-service && php artisan serve --port=8001
cd borrowing-service && php artisan serve --port=8002
cd review-service && php artisan serve --port=8003
cd user-interface && php artisan serve --port=8004
```

## Teknologi yang Digunakan

- **Backend**: Laravel 12
- **Database**: MySQL
- **Authentication**: Laravel Sanctum
- **API**: RESTful API
- **UI**: Blade + TailwindCSS

## Akses Layanan

| Layanan | URL | Port |
|---------|-----|------|
| Auth Service | http://localhost:8000 | 8000 |
| Book Catalog Service | http://localhost:8001 | 8001 |
| Borrowing Service | http://localhost:8002 | 8002 |
| Review Service | http://localhost:8003 | 8003 |
| User Interface | http://localhost:8004 | 8004 |

## Dokumentasi API

Lihat file `postman_collection.json` untuk contoh request API lengkap. Import file tersebut ke dalam Postman untuk menguji API.

## Struktur Proyek

```
Tubes-IAE/
├── auth-service/          # Layanan autentikasi pengguna
├── book-catalog-service/  # Layanan katalog buku
├── borrowing-service/     # Layanan peminjaman buku
├── review-service/        # Layanan ulasan dan rating
├── user-interface/        # Antarmuka pengguna 
├── composer-install.bat   # Script untuk instalasi dependencies
├── run-all-services.bat   # Script untuk menjalankan semua layanan
└── postman_collection.json # Dokumentasi API
```