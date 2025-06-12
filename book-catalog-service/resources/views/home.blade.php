@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="hero-section d-flex align-items-center">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">
                    Selamat Datang di<br>
                    <span style="color: #a855f7;">Perpustakaan Digital</span>
                </h1>
                <p class="lead mb-4">
                    Temukan ribuan buku, kelola peminjaman, dan nikmati pengalaman membaca yang lebih baik dengan sistem manajemen perpustakaan modern kami.
                </p>
                <a href="{{ route('books.index') }}" class="btn btn-primary-custom btn-lg me-3">
                    <i class="fas fa-arrow-right me-2"></i>Ke Dashboard
                </a>
                <a href="{{ route('books.index') }}" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-search me-2"></i>Jelajahi Buku
                </a>
            </div>
            <div class="col-lg-6 text-center d-none d-lg-block">
                <i class="fas fa-book-open" style="font-size: 12rem; opacity: 0.3;"></i>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="section-title display-6 fw-bold text-center mb-2">
                    <span class="text-muted">FITUR</span>
                </h2>
                <h3 class="text-center text-dark mb-5">Semua yang Anda Butuhkan</h3>
                <p class="text-center text-muted mb-5">
                    Sistem kami menyediakan berbagai fitur untuk memudahkan pengelolaan<br>
                    perpustakaan Anda.
                </p>
            </div>
        </div>
        
        <div class="row g-4">
            <!-- Katalog Digital -->
            <div class="col-md-6 col-lg-4">
                <div class="card feature-card h-100 p-4 text-center">
                    <div class="feature-icon">
                        <i class="fas fa-books text-white" style="font-size: 2rem;"></i>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title fw-bold">Katalog Digital</h5>
                        <p class="card-text text-muted">
                            Akses ribuan buku dalam format digital dengan mudah dan cepat. 
                            Sistem pencarian yang canggih membantu Anda menemukan buku yang diinginkan.
                        </p>
                        <a href="{{ route('books.index') }}" class="btn btn-outline-primary">
                            Jelajahi Katalog <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Manajemen Peminjaman -->
            <div class="col-md-6 col-lg-4">
                <div class="card feature-card h-100 p-4 text-center">
                    <div class="feature-icon">
                        <i class="fas fa-user-friends text-white" style="font-size: 2rem;"></i>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title fw-bold">Manajemen Peminjaman</h5>
                        <p class="card-text text-muted">
                            Kelola peminjaman buku dengan sistem yang terintegrasi dan mudah. 
                            Pantau status peminjaman dan pengembalian secara real-time.
                        </p>
                        <a href="#" class="btn btn-outline-primary">
                            Kelola Peminjaman <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Dashboard Analytics -->
            <div class="col-md-6 col-lg-4">
                <div class="card feature-card h-100 p-4 text-center">
                    <div class="feature-icon">
                        <i class="fas fa-chart-bar text-white" style="font-size: 2rem;"></i>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title fw-bold">Dashboard Analytics</h5>
                        <p class="card-text text-muted">
                            Dapatkan insight mendalam tentang aktivitas perpustakaan dengan 
                            dashboard yang informatif dan mudah dipahami.
                        </p>
                        <a href="#" class="btn btn-outline-primary">
                            Lihat Dashboard <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Kategori Management -->
            <div class="col-md-6 col-lg-4">
                <div class="card feature-card h-100 p-4 text-center">
                    <div class="feature-icon">
                        <i class="fas fa-tags text-white" style="font-size: 2rem;"></i>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title fw-bold">Manajemen Kategori</h5>
                        <p class="card-text text-muted">
                            Organisasi buku berdasarkan kategori untuk memudahkan pencarian 
                            dan pengelolaan koleksi perpustakaan.
                        </p>
                        <a href="{{ route('categories.index') }}" class="btn btn-outline-primary">
                            Kelola Kategori <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Search & Filter -->
            <div class="col-md-6 col-lg-4">
                <div class="card feature-card h-100 p-4 text-center">
                    <div class="feature-icon">
                        <i class="fas fa-search text-white" style="font-size: 2rem;"></i>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title fw-bold">Pencarian Canggih</h5>
                        <p class="card-text text-muted">
                            Fitur pencarian dan filter yang powerful memungkinkan Anda 
                            menemukan buku berdasarkan judul, penulis, atau kategori.
                        </p>
                        <a href="{{ route('books.index') }}" class="btn btn-outline-primary">
                            Coba Pencarian <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Real-time Updates -->
            <div class="col-md-6 col-lg-4">
                <div class="card feature-card h-100 p-4 text-center">
                    <div class="feature-icon">
                        <i class="fas fa-sync-alt text-white" style="font-size: 2rem;"></i>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title fw-bold">Update Real-time</h5>
                        <p class="card-text text-muted">
                            Sistem update otomatis memastikan informasi ketersediaan buku 
                            dan status peminjaman selalu akurat dan terkini.
                        </p>
                        <a href="#" class="btn btn-outline-primary">
                            Lihat Status <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 mb-4">
                <div class="p-4">
                    <h2 class="display-4 fw-bold text-primary" id="books-count">{{ $stats['books'] ?? 0 }}</h2>
                    <p class="text-muted">Total Buku</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="p-4">
                    <h2 class="display-4 fw-bold text-success" id="available-count">{{ $stats['available'] ?? 0 }}</h2>
                    <p class="text-muted">Buku Tersedia</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="p-4">
                    <h2 class="display-4 fw-bold text-info" id="categories-count">{{ $stats['categories'] ?? 0 }}</h2>
                    <p class="text-muted">Kategori</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="p-4">
                    <h2 class="display-4 fw-bold text-warning" id="borrowed-count">{{ $stats['borrowed'] ?? 0 }}</h2>
                    <p class="text-muted">Sedang Dipinjam</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection