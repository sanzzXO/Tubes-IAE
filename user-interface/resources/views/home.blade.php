@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">
                    Perpustakaan Online Modern
                </h1>
                <p class="lead mb-4">
                    Temukan, pinjam, dan baca ribuan buku digital dengan mudah. 
                    Akses 24/7 dari mana saja.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a class="btn btn-light btn-lg" href="/books">
                        <i class="fas fa-book me-2"></i>Lihat Katalog
                    </a>
                    @if(!session('user_id'))
                        <a class="btn btn-outline-light btn-lg" href="/register">
                            <i class="fas fa-user-plus me-2"></i>Daftar Gratis
                        </a>
                    @else
                        @if(session('user_role') === 'staff')
                            <a class="btn btn-outline-light btn-lg" href="/staff/dashboard">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        @else
                            <a class="btn btn-outline-light btn-lg" href="/dashboard">
                                <i class="fas fa-tachometer-alt me-2"></i>profile
                            </a>
                        @endif
                    @endif
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <i class="fas fa-book-open text-white" style="font-size: 8rem; opacity: 0.3;"></i>
            </div>
        </div>
    </div>
</div>


<!-- How It Works Section -->
<div class="container py-5">
    <div class="row text-center mb-5">
        <div class="col-12">
            <h2 class="fw-bold mb-3">Cara Menggunakan</h2>
            <p class="text-muted">3 langkah mudah untuk mulai membaca</p>
        </div>
    </div>
    
    <div class="row g-4">
        <div class="col-lg-4 text-center">
            <div class="mb-4">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary text-white" 
                     style="width: 80px; height: 80px; font-size: 2rem; font-weight: bold;">
                    1
                </div>
            </div>
            <h5 class="fw-bold mb-3">Daftar Akun</h5>
            <p class="text-muted">
                Buat akun gratis dengan email Anda. Proses pendaftaran hanya membutuhkan 1 menit.
            </p>
        </div>
        
        <div class="col-lg-4 text-center">
            <div class="mb-4">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success text-white" 
                     style="width: 80px; height: 80px; font-size: 2rem; font-weight: bold;">
                    2
                </div>
            </div>
            <h5 class="fw-bold mb-3">Cari & Pilih Buku</h5>
            <p class="text-muted">
                Gunakan fitur pencarian untuk menemukan buku yang Anda inginkan dari ribuan koleksi.
            </p>
        </div>
        
        <div class="col-lg-4 text-center">
            <div class="mb-4">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-warning text-white" 
                     style="width: 80px; height: 80px; font-size: 2rem; font-weight: bold;">
                    3
                </div>
            </div>
            <h5 class="fw-bold mb-3">Pinjam & Baca</h5>
            <p class="text-muted">
                Klik tombol pinjam dan mulai membaca. Jangan lupa berikan review setelah selesai.
            </p>
        </div>
    </div>
</div>

@endsection