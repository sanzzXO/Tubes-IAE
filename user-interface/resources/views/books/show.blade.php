@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Book Detail Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-4 text-center mb-3">
                    @if(!empty($book['cover_image']))
                        <img src="{{ $book['cover_image'] }}" class="img-fluid rounded shadow" 
                             alt="{{ $book['title'] }}" style="max-height: 400px;">
                    @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center shadow" 
                             style="height: 400px;">
                            <i class="fas fa-book text-muted" style="font-size: 4rem;"></i>
                        </div>
                    @endif
                </div>
                <div class="col-md-8">
                    <h2 class="fw-bold mb-3">{{ $book['title'] }}</h2>
                    <h5 class="text-muted mb-4">
                        <i class="fas fa-user me-2"></i>{{ $book['author'] }}
                    </h5>
                    
                    <div class="row mb-4">
                        <div class="col-sm-6">
                            <p class="mb-2">
                                <i class="fas fa-tag me-2 text-primary"></i>
                                <strong>Kategori:</strong> {{ $book['category'] }}
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <p class="mb-2">
                                <i class="fas fa-calendar me-2 text-primary"></i>
                                <strong>Tahun Terbit:</strong> {{ $book['publication_year'] ?? '-' }}
                            </p>
                        </div>
                    </div>
                    
                    @if(isset($book['description']))
                        <div class="mb-4">
                            <h6 class="fw-bold">
                                <i class="fas fa-align-left me-2 text-primary"></i>Deskripsi:
                            </h6>
                            <p class="text-muted">{{ $book['description'] }}</p>
                        </div>
                    @endif
                    
                    <!-- Status & Actions -->
                    <div class="mb-4">
                        <p class="mb-3">
                            <i class="fas fa-info-circle me-2 text-primary"></i>
                            <strong>Status:</strong> 
                            @if($book['available'])
                                <span class="badge bg-success badge-status ms-2">
                                    <i class="fas fa-check me-1"></i>Tersedia
                                </span>
                            @else
                                <span class="badge bg-danger badge-status ms-2">
                                    <i class="fas fa-times me-1"></i>Dipinjam
                                </span>
                            @endif
                        </p>
                        
                        @if($book['available'])
                            <form method="POST" action="/borrowings" class="mb-3">
                                @csrf
                                <input type="hidden" name="book_id" value="{{ $book['id'] }}">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-bookmark me-2"></i>Pinjam Buku
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Review Section -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">
                <i class="fas fa-star me-2 text-warning"></i>Review & Rating
            </h5>
        </div>
        <div class="card-body p-4">
            <button class="btn btn-primary mb-3" type="button" data-bs-toggle="collapse" 
                    data-bs-target="#reviewForm" aria-expanded="false">
                <i class="fas fa-edit me-2"></i>Tulis Review
            </button>
            
            <div class="collapse mb-4" id="reviewForm">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="/reviews">
                            @csrf
                            <input type="hidden" name="book_id" value="{{ $book['id'] }}">
                            <div class="mb-3">
                                <label for="rating" class="form-label fw-bold">Rating</label>
                                <select class="form-select" id="rating" name="rating" required>
                                    <option value="">Pilih rating</option>
                                    @for($i=1; $i<=5; $i++)
                                        <option value="{{ $i }}">{{ $i }} ⭐</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="comment" class="form-label fw-bold">Komentar</label>
                                <textarea class="form-control" id="comment" name="comment" rows="3" required 
                                          placeholder="Bagikan pengalaman Anda tentang buku ini..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Kirim Review
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <a href="/reviews?book_id={{ $book['id'] }}" class="btn btn-outline-secondary">
                <i class="fas fa-eye me-2"></i>Lihat Semua Review
            </a>
        </div>
    </div>

    <!-- Recent Reviews -->
    @if(isset($book['reviews']) && count($book['reviews']) > 0)
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-comments me-2 text-primary"></i>Review Terbaru
                </h6>
            </div>
            <div class="list-group list-group-flush">
                @foreach(array_slice($book['reviews'], 0, 3) as $review)
                <div class="list-group-item p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1 fw-bold">{{ $review['user_name'] }}</h6>
                            <div class="mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $review['rating'] ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                                <small class="text-muted ms-2">{{ $review['rating'] }}/5</small>
                            </div>
                            <p class="mb-0 text-muted">{{ $review['comment'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection