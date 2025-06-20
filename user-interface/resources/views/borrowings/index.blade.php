@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 text-center">
                <h1 class="display-5 fw-bold mb-3">
                    <i class="fas fa-bookmark me-3"></i>Daftar Peminjaman
                </h1>
                <p class="lead mb-0">Riwayat peminjaman buku Anda</p>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <!-- Alert Messages -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-4">
            @if(is_array($borrowings) && count($borrowings) > 0)
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th><i class="fas fa-book me-1 text-primary"></i>Judul Buku</th>
                                <th><i class="fas fa-calendar-alt me-1 text-success"></i>Tanggal Pinjam</th>
                                <th><i class="fas fa-calendar-check me-1 text-warning"></i>Tanggal Kembali</th>
                                <th><i class="fas fa-info-circle me-1 text-info"></i>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($borrowings as $borrowing)
                            <tr>
                                <td class="fw-semibold">{{ $borrowing['book_title'] ?? '-' }}</td>
                                <td>{{ $borrowing['created_at'] ?? '-' }}</td>
                                <td>
                                    @if(isset($borrowing['is_returned']) && $borrowing['is_returned'])
                                        {{ $borrowing['updated_at'] ?? '-' }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if(isset($borrowing['is_returned']) && $borrowing['is_returned'])
                                        <span class="badge bg-success badge-status"><i class="fas fa-check me-1"></i>Sudah Dikembalikan</span>
                                    @else
                                        <span class="badge bg-warning text-dark badge-status"><i class="fas fa-clock me-1"></i>Belum Dikembalikan</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-book-open text-muted" style="font-size: 4rem; opacity: 0.5;"></i>
                    </div>
                    <h4 class="text-muted mb-3">Tidak ada data peminjaman</h4>
                    <p class="text-muted mb-4">Anda belum pernah meminjam buku.</p>
                    <a href="/books" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Lihat Katalog Buku
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 