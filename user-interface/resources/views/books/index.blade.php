@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 text-center">
                <h1 class="display-5 fw-bold mb-3">
                    <i class="fas fa-books me-3"></i>Katalog Digital
                </h1>
                <p class="lead mb-0">Temukan dan pinjam buku favoritmu</p>
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

    @error('borrow')
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @enderror

    <!-- Search & Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form method="GET" action="/books">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-search me-1"></i>Pencarian
                                </label>
                                <input type="text" class="form-control" name="search" 
                                       value="{{ request('search') }}" 
                                       placeholder="Cari judul, penulis, atau ISBN...">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-filter me-1"></i>Kategori
                                </label>
                                <select class="form-select" name="category_id">
                                    <option value="">Semua Kategori</option>
                                    @if(isset($categories) && is_array($categories))
                                        @foreach($categories as $category)
                                            <option value="{{ $category['id'] ?? '' }}" 
                                                {{ request('category_id') == ($category['id'] ?? '') ? 'selected' : '' }}>
                                                {{ $category['name'] ?? 'Unknown' }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-1"></i>Cari
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Results Info -->
    @if(isset($books) && is_array($books))
        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="text-muted mb-0">
                        <i class="fas fa-book me-1"></i>
                        Menampilkan {{ count($books) }} buku
                        @if(request('search'))
                            untuk "<strong>{{ request('search') }}</strong>"
                        @endif
                        @if(request('category_id'))
                            @php
                                $selectedCategory = collect($categories)->firstWhere('id', request('category_id'));
                            @endphp
                            di kategori "<strong>{{ $selectedCategory['name'] ?? 'Unknown' }}</strong>"
                        @endif
                    </p>
                    @if(request('search') || request('category_id'))
                        <a href="/books" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-times me-1"></i>Reset Filter
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Books Grid -->
    @if(isset($books) && is_array($books) && count($books) > 0)
        <div class="row g-4">
            @foreach($books as $book)
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 shadow-sm book-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title mb-0 flex-grow-1">
                                {{ Str::limit($book['title'] ?? 'No Title', 50) }}
                            </h5>
                            @if(isset($book['available']) && $book['available'] > 0)
                                <span class="badge bg-success ms-2">Tersedia</span>
                            @else
                                <span class="badge bg-danger ms-2">Habis</span>
                            @endif
                        </div>
                        
                        <p class="text-muted mb-2">
                            <i class="fas fa-user me-1"></i>
                            {{ $book['author'] ?? 'Unknown Author' }}
                        </p>
                        
                        <p class="text-muted mb-3">
                            <i class="fas fa-calendar me-1"></i>
                            {{ $book['publication_year'] ?? 'Unknown Year' }}
                        </p>
                        
                        @if(isset($book['category']))
                            <div class="mb-3">
                                <span class="badge bg-secondary">
                                    <i class="fas fa-tag me-1"></i>
                                    {{ is_array($book['category']) ? ($book['category']['name'] ?? 'Unknown') : $book['category'] }}
                                </span>
                            </div>
                        @endif

                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="fas fa-warehouse me-1"></i>
                                Stok: {{ $book['stock'] ?? 0 }} | 
                                Tersedia: {{ $book['available'] ?? 0 }}
                            </small>
                        </div>
                        
                        @if(isset($book['description']))
                            <p class="card-text text-muted small mb-3">
                                {{ Str::limit($book['description'], 100) }}
                            </p>
                        @endif
                        
                        <div class="mt-auto d-flex gap-2">
                            <a href="/books/{{ $book['id'] ?? '#' }}" class="btn btn-outline-primary btn-sm flex-grow-1">
                                <i class="fas fa-eye me-1"></i>Detail
                            </a>
                            @if(isset($book['is_borrowed_by_user']) && $book['is_borrowed_by_user'])
                                <form method="POST" action="/borrowings/{{ $book['borrowing_id'] }}/return">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">Kembalikan</button>
                                </form>
                            @else
                                <form method="POST" action="/borrowings">
                                    @csrf
                                    <input type="hidden" name="book_id" value="{{ $book['id'] }}">
                                    <button type="submit" class="btn btn-success btn-sm"
                                        @if(!is_numeric($book['available']) || $book['available'] < 1) disabled @endif>
                                        Pinjam
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-book-open text-muted" style="font-size: 4rem; opacity: 0.5;"></i>
            </div>
            <h4 class="text-muted mb-3">Tidak ada buku ditemukan</h4>
            <p class="text-muted mb-4">
                @if(request('search') || request('category_id'))
                    Coba ubah kata kunci pencarian atau kategori yang dipilih
                @else
                    Koleksi buku sedang dalam proses pembaruan
                @endif
            </p>
            @if(request('search') || request('category_id'))
                <a href="/books" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-2"></i>Lihat Semua Buku
                </a>
            @endif
        </div>
    @endif
</div>
@endsection

@section('styles')
<style>
.book-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.book-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.badge {
    font-size: 0.75em;
}
</style>
@endsection