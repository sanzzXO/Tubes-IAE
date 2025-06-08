@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="display-5 fw-bold text-primary">
                <i class="fas fa-tags me-3"></i>Manajemen Kategori
            </h1>
            <p class="text-muted">Kelola kategori buku untuk organisasi yang lebih baik</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('categories.create') }}" class="btn btn-primary-custom">
                <i class="fas fa-plus me-2"></i>Tambah Kategori
            </a>
        </div>
    </div>

    <!-- Categories Grid -->
    <div class="row g-4">
        @forelse($categories as $category)
            <div class="col-lg-4 col-md-6">
                <div class="card feature-card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-folder text-white" style="font-size: 2rem;"></i>
                        </div>
                        <h5 class="card-title fw-bold">{{ $category->name }}</h5>
                        <p class="card-text text-muted mb-3">{{ $category->description }}</p>
                        <p class="text-primary fw-bold">
                            <i class="fas fa-book me-1"></i>{{ $category->books_count }} Buku
                        </p>
                        <div class="d-grid gap-2">
                            <a href="{{ route('categories.show', $category) }}" class="btn btn-outline-primary">
                                <i class="fas fa-eye me-1"></i>Lihat Buku
                            </a>
                            <div class="btn-group" role="group">
                                <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                            onclick="return confirm('Yakin ingin menghapus?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-tags text-muted mb-3" style="font-size: 4rem;"></i>
                    <h4 class="text-muted">Belum ada kategori</h4>
                    <p class="text-muted">Tambahkan kategori pertama Anda</p>
                    <a href="{{ route('categories.create') }}" class="btn btn-primary-custom">
                        <i class="fas fa-plus me-2"></i>Tambah Kategori
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection