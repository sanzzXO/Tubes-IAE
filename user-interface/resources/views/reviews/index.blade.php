@extends('layouts.app')
@section('content')
<div class="container py-4">
    <div class="card shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="fw-bold mb-0">
                    <i class="fas fa-star text-warning me-2"></i>Review Buku
                </h2>
                @if($bookId)
                    <a href="/reviews/create/{{ $bookId }}" class="btn btn-primary">
                        <i class="fas fa-pen me-1"></i> Tulis Review
                    </a>
                @endif
            </div>
            @if(is_array($reviews) && count($reviews) > 0)
                <div class="list-group list-group-flush">
                    @foreach($reviews as $review)
                        <div class="list-group-item py-4 px-3 mb-2 rounded shadow-sm border-0" style="background: #fafaff;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="fw-bold fs-5">
                                    <i class="fas fa-user-circle me-2 text-primary"></i>{{ $review['user']['name'] ?? '-' }}
                                </div>
                                <div>
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $review['rating'] ? 'text-warning' : 'text-muted' }}"></i>
                                    @endfor
                                    <span class="badge bg-light text-dark ms-2">{{ $review['rating'] }}/5</span>
                                </div>
                            </div>
                            @if(!$bookId)
                                <div class="mb-2">
                                    <span class="badge bg-secondary"><i class="fas fa-book me-1"></i>{{ $review['book']['title'] ?? '-' }}</span>
                                </div>
                            @endif
                            <div class="mb-2 text-muted">{{ $review['comment'] }}</div>
                            <div class="text-end">
                                <small class="text-muted"><i class="fas fa-clock me-1"></i>{{ \Carbon\Carbon::parse($review['created_at'])->translatedFormat('d M Y H:i') }}</small>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-danger py-5">
                    <i class="fas fa-comment-slash fa-2x mb-3"></i>
                    <div>Tidak ada review.</div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 