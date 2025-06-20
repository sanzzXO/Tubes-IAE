@extends('layouts.app')

@section('content')
<div class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 text-center">
                <h1 class="display-5 fw-bold mb-3">
                    <i class="fas fa-tachometer-alt me-3"></i>Dashboard
                </h1>
                <p class="lead mb-0">Selamat datang, {{ session('user_name') ?? 'User' }}!</p>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <!-- Quick Stats -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card text-center bg-primary text-white">
                <div class="card-body p-4">
                    <i class="fas fa-bookmark mb-3" style="font-size: 2.5rem;"></i>
                    <h5 class="card-title">Buku Dipinjam</h5>
                    <h2 class="mb-0">{{ $borrowingCount ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center bg-success text-white">
                <div class="card-body p-4">
                    <i class="fas fa-star mb-3" style="font-size: 2.5rem;"></i>
                    <h5 class="card-title">Review Ditulis</h5>
                    <h2 class="mb-0">{{ $reviewCount ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center bg-warning text-white">
                <div class="card-body p-4">
                    <i class="fas fa-clock mb-3" style="font-size: 2.5rem;"></i>
                    <h5 class="card-title">Terlambat</h5>
                    <h2 class="mb-0">{{ $overdueCount ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2 text-primary"></i>Aksi Cepat
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="/books" class="btn btn-outline-primary">
                            <i class="fas fa-search me-2"></i>Cari Buku
                        </a>
                        <a href="/borrowings" class="btn btn-outline-success">
                            <i class="fas fa-list me-2"></i>Lihat Peminjaman
                        </a>
                        <a href="/reviews" class="btn btn-outline-warning">
                            <i class="fas fa-star me-2"></i>Tulis Review
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2 text-info"></i>Informasi Akun
                    </h5>
                </div>
                <div class="card-body">
                    <p><i class="fas fa-user me-2 text-muted"></i><strong>Nama:</strong> {{ session('user_name') }}</p>
                    <p><i class="fas fa-envelope me-2 text-muted"></i><strong>Email:</strong> {{ session('user_email') }}</p>
                    <p><i class="fas fa-calendar me-2 text-muted"></i><strong>Bergabung:</strong> {{ date('d F Y') }}</p>
                    <hr>
                    <div class="d-grid">
                        <form method="POST" action="/logout">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection