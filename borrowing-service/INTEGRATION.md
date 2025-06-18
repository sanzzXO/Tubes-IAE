# Borrowing Service Integration Guide

## Overview

Borrowing Service telah diintegrasikan dengan Book Catalog Service dan Auth Service untuk menyediakan fungsionalitas peminjaman buku yang lengkap.

## Konfigurasi Environment

Tambahkan konfigurasi berikut ke file `.env`:

```env
# Microservices Configuration
BOOK_CATALOG_SERVICE_URL=http://localhost:8001/api
BOOK_CATALOG_SERVICE_TOKEN=book-catalog-service-token
BOOK_CATALOG_SERVICE_TIMEOUT=15

AUTH_SERVICE_URL=http://localhost:8000/api
AUTH_SERVICE_TOKEN=auth-service-token
AUTH_SERVICE_TIMEOUT=10

USER_AUTH_SERVICE_URL=http://localhost:8000/api
USER_AUTH_SERVICE_TOKEN=auth-service-token
USER_AUTH_SERVICE_TIMEOUT=10
```

## Integrasi dengan Book Catalog Service

### Endpoints yang Digunakan

1. **GET /books/{id}** - Mendapatkan detail buku
2. **PUT /books/{id}** - Update stok buku
3. **GET /books** - Pencarian buku
4. **GET /health** - Health check

### Fitur Integrasi

- **Cek Ketersediaan Buku**: Memverifikasi stok buku sebelum peminjaman
- **Update Stok**: Mengurangi/menambah stok saat peminjaman/pengembalian
- **Cache Management**: Caching data buku untuk performa
- **Error Handling**: Penanganan error yang robust

### Contoh Penggunaan

```php
// Mendapatkan detail buku
$book = $bookService->getBook($bookId);

// Cek ketersediaan
$isAvailable = $bookService->isBookAvailable($bookId);

// Update stok
$bookService->decreaseStock($bookId, 1);
$bookService->increaseStock($bookId, 1);
```

## Integrasi dengan Auth Service

### Endpoints yang Digunakan

1. **GET /users/{id}** - Mendapatkan detail user
2. **GET /users/email/{email}** - Mendapatkan user berdasarkan email
3. **POST /validate-token** - Validasi token user
4. **GET /health** - Health check

### Fitur Integrasi

- **Validasi User**: Memverifikasi keberadaan dan status user
- **User Status Check**: Memastikan user aktif sebelum peminjaman
- **Cache Management**: Caching data user untuk performa
- **Token Validation**: Validasi token untuk keamanan

### Contoh Penggunaan

```php
// Mendapatkan detail user
$user = $userService->getUser($userId);

// Cek status user
$isActive = $userService->isUserActive($userId);

// Validasi token
$tokenData = $userService->validateToken($token);
```

## Testing Endpoints

### 1. Test Book Catalog Service
```bash
GET /api/test-book-service
```

### 2. Test Auth Service
```bash
GET /api/test-auth-service
```

### 3. Test Book Retrieval
```bash
GET /api/test-book/{id}
```

### 4. Test User Retrieval
```bash
GET /api/test-user/{id}
```

### 5. Test All Integrations
```bash
GET /api/test-integration
```

## Flow Peminjaman Buku

1. **Validasi Input**: Memvalidasi data peminjaman
2. **Cek Buku**: Memverifikasi keberadaan dan ketersediaan buku
3. **Cek User**: Memverifikasi keberadaan dan status user
4. **Cek Batasan**: Memastikan user tidak melebihi batas peminjaman
5. **Buat Peminjaman**: Menyimpan data peminjaman
6. **Update Stok**: Mengurangi stok buku
7. **Clear Cache**: Membersihkan cache terkait

## Flow Pengembalian Buku

1. **Validasi Peminjaman**: Memverifikasi data peminjaman
2. **Hitung Denda**: Menghitung denda jika terlambat
3. **Update Status**: Mengubah status menjadi 'returned'
4. **Update Stok**: Menambah stok buku
5. **Clear Cache**: Membersihkan cache terkait

## Error Handling

### Book Service Errors
- Buku tidak ditemukan
- Stok tidak mencukupi
- Gagal update stok
- Service tidak tersedia

### User Service Errors
- User tidak ditemukan
- User tidak aktif
- Token tidak valid
- Service tidak tersedia

### Response Format
```json
{
    "success": false,
    "message": "Error description",
    "errors": {
        "field": ["validation errors"]
    }
}
```

## Monitoring dan Logging

### Log Levels
- **INFO**: Operasi normal (fetch data, update stok)
- **WARNING**: Operasi gagal tapi dapat dihandle
- **ERROR**: Error kritis yang memerlukan perhatian

### Metrics yang Dimonitor
- Response time ke external services
- Success/failure rate
- Cache hit/miss ratio
- Error frequency

## Security Considerations

1. **Service Authentication**: Menggunakan token untuk autentikasi antar service
2. **Input Validation**: Validasi semua input dari user
3. **Rate Limiting**: Pembatasan request untuk mencegah abuse
4. **Error Sanitization**: Tidak mengekspos informasi sensitif dalam error

## Troubleshooting

### Service Tidak Tersedia
1. Cek konfigurasi URL dan token
2. Verifikasi service berjalan
3. Cek network connectivity
4. Review logs untuk error detail

### Data Tidak Konsisten
1. Clear cache yang terkait
2. Verifikasi data di service source
3. Cek timestamp data
4. Review sync mechanism

### Performance Issues
1. Monitor response time
2. Optimize cache strategy
3. Review timeout settings
4. Check service load

## Best Practices

1. **Always Cache**: Cache data yang sering diakses
2. **Handle Errors**: Implement proper error handling
3. **Validate Data**: Validate all input and output
4. **Monitor Health**: Regular health checks
5. **Log Everything**: Comprehensive logging
6. **Test Integration**: Regular integration testing 