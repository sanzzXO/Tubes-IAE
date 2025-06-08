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
                <p class="lead mb-0">Akses ribuan buku dalam format digital dengan mudah dan cepat</p>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Search & Filter -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card search-card">
                <div class="card-body p-4">
                    <form method="GET" action="{{ route('books.index') }}">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label text-muted fw-semibold">Pencarian</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0" 
                                           name="search" 
                                           value="{{ request('search') }}"
                                           placeholder="Cari judul, penulis, atau ISBN...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted fw-semibold">Kategori</label>
                                <select class="form-select" name="category_id">
                                    <option value="">Semua Kategori</option>
                                    @foreach($categories ?? [] as $category)
                                        <option value="{{ $category->id }}" 
                                                {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted fw-semibold">Status</label>
                                <select class="form-select" name="available_only">
                                    <option value="">Semua Status</option>
                                    <option value="1" {{ request('available_only') == '1' ? 'selected' : '' }}>
                                        Tersedia Saja
                                    </option>
                                    <option value="0" {{ request('available_only') == '0' ? 'selected' : '' }}>
                                        Tidak Tersedia
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-grid gap-1">
                                    <button type="submit" class="btn btn-primary-custom">
                                        <i class="fas fa-search me-1"></i>Cari
                                    </button>
                                    @if(request()->hasAny(['search', 'category_id', 'available_only']))
                                        <a href="{{ route('books.index') }}" class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-times me-1"></i>Reset
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Results Info -->
    @if(request()->hasAny(['search', 'category_id', 'available_only']))
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-filter me-2"></i>
                    Menampilkan hasil untuk:
                    @if(request('search'))
                        <span class="badge bg-primary me-1">Pencarian: "{{ request('search') }}"</span>
                    @endif
                    @if(request('category_id'))
                        @php $selectedCategory = $categories->find(request('category_id')) @endphp
                        <span class="badge bg-success me-1">Kategori: {{ $selectedCategory->name ?? 'Unknown' }}</span>
                    @endif
                    @if(request('available_only') == '1')
                        <span class="badge bg-warning me-1">Status: Tersedia Saja</span>
                    @elseif(request('available_only') == '0')
                        <span class="badge bg-danger me-1">Status: Tidak Tersedia</span>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Books Grid -->
    <div class="row g-4">
        @forelse($books ?? [] as $book)
            <div class="col-lg-4 col-md-6">
                <div class="card book-card shadow-sm">
                    @if($book->cover_image)
                        <img src="{{ Storage::url($book->cover_image) }}" 
                             class="book-cover" 
                             alt="{{ $book->title }}">
                    @else
                        <div class="book-cover bg-light d-flex align-items-center justify-content-center">
                            <i class="fas fa-book text-muted" style="font-size: 3rem;"></i>
                        </div>
                    @endif
                    
                    <div class="card-body p-4">
                        <h5 class="card-title fw-bold mb-2">{{ $book->title }}</h5>
                        <p class="book-description text-muted mb-3">
                            {{ Str::limit($book->description ?? 'Tidak ada deskripsi tersedia.', 150) }}
                        </p>
                        <div class="book-meta text-muted mb-3">
                            <p class="mb-1"><i class="fas fa-user me-2"></i><strong>Penulis:</strong> {{ $book->author }}</p>
                            <p class="mb-1"><i class="fas fa-calendar me-2"></i><strong>Tahun Terbit:</strong> {{ $book->publication_year ?? 'N/A' }}</p>
                            <p class="mb-1"><i class="fas fa-tag me-2"></i><strong>Kategori:</strong> {{ $book->category->name ?? 'Uncategorized' }}</p>
                            <p class="mb-0"><i class="fas fa-boxes me-2"></i><strong>Stok:</strong> {{ $book->available }} tersedia dari {{ $book->stock }} total</p>
                        </div>
                        <div class="d-flex justify-content-center align-items-center">
                            @if($book->available > 0)
                                <span class="badge bg-success badge-status rounded-pill">Tersedia</span>
                            @else
                                <span class="badge bg-danger badge-status rounded-pill">Habis</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-book-open text-muted mb-3" style="font-size: 4rem;"></i>
                    <h4 class="text-muted">
                        @if(request()->hasAny(['search', 'category_id', 'available_only']))
                            Tidak ada buku yang sesuai dengan kriteria pencarian
                        @else
                            Belum ada buku dalam katalog
                        @endif
                    </h4>
                    <p class="text-muted">
                        @if(request()->hasAny(['search', 'category_id', 'available_only']))
                            Coba ubah kriteria pencarian Anda
                        @else
                            Katalog sedang dalam proses pengembangan
                        @endif
                    </p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(isset($books) && $books->hasPages())
        <div class="row mt-5">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $books->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    @endif

    <!-- Summary Stats -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="text-center text-muted">
                @if(isset($books))
                    <p class="mb-0">
                        Menampilkan {{ $books->firstItem() ?? 0 }} - {{ $books->lastItem() ?? 0 }} 
                        dari {{ $books->total() }} buku
                    </p>
                @else
                    <p class="mb-0">Menampilkan data contoh - belum terhubung dengan database</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection