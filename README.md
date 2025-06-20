# TUBES IAE - Sistem Perpustakaan Digital

## Deskripsi
Sistem perpustakaan digital berbasis microservices yang terdiri dari 4 layanan utama untuk mengelola katalog buku, peminjaman, ulasan, dan autentikasi pengguna.

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

## Cara Menjalankan

### 1. **Clone repository**
   ```bash
   git clone [url-repository]
   cd Tubes-IAE
   ```
2. **Membuat file .env di tiap folder**

3.  **Menjalankan composer install**
   ```bash
   ./composer-install
   ```
2. **Jalankan semua layanan**
   ```bash
   ./run-services.bat
   ```

3. **Atau jalankan manual per layanan**
   ```bash
   cd auth-service && php artisan key:generate && php artisan serve --port=8000
   cd book-catalog-service && php artisan key:generate && php artisan serve --port=8001
   cd borrowing-service && php artisan key:generate && php artisan serve --port=8002
   cd review-service && php artisan key:generate && php artisan serve --port=8003
   cd user-interface && php artisan key:generate && php artisan serve --port==8004
   ```

## Teknologi
- **Backend**: Laravel 12
- **Database**: MySQL
- **Authentication**: Laravel Sanctum
- **API**: RESTful API

## Dokumentasi API
Lihat file `postman_collection.json` untuk contoh request API lengkap.