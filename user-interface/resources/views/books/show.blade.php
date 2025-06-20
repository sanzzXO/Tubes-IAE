@extends('layouts.app')
@section('content')
<div class="card mb-3">
    <div class="card-body">
        <h3 class="card-title">{{ $book['title'] }}</h3>
        <h5 class="card-subtitle mb-2 text-muted">{{ $book['author'] }}</h5>
        <p class="card-text">Kategori: {{ $book['category'] }}</p>
        <p class="card-text">Deskripsi: {{ $book['description'] ?? '-' }}</p>
        <p class="card-text">Status: <strong>{{ $book['available'] ? 'Tersedia' : 'Dipinjam' }}</strong></p>
        @if($book['available'])
        <form method="POST" action="/borrowings" class="mb-3">
            @csrf
            <input type="hidden" name="book_id" value="{{ $book['id'] }}">
            <button type="submit" class="btn btn-success">Pinjam Buku</button>
        </form>
        @endif
        <button class="btn btn-outline-primary mb-2" type="button" data-bs-toggle="collapse" data-bs-target="#reviewForm" aria-expanded="false" aria-controls="reviewForm">
            Tulis Review
        </button>
        <div class="collapse mb-3" id="reviewForm">
            <form method="POST" action="/reviews">
                @csrf
                <input type="hidden" name="book_id" value="{{ $book['id'] }}">
                <div class="mb-2">
                    <label for="rating" class="form-label">Rating</label>
                    <select class="form-select" id="rating" name="rating" required>
                        <option value="">Pilih rating</option>
                        @for($i=1; $i<=5; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="mb-2">
                    <label for="comment" class="form-label">Komentar</label>
                    <textarea class="form-control" id="comment" name="comment" rows="2" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Kirim Review</button>
            </form>
        </div>
        <a href="/reviews?book_id={{ $book['id'] }}" class="btn btn-outline-secondary mb-2">Lihat Semua Review</a>
    </div>
</div>
@if(isset($book['reviews']) && count($book['reviews']) > 0)
<div class="card mb-3">
    <div class="card-header">Review Singkat</div>
    <ul class="list-group list-group-flush">
        @foreach(array_slice($book['reviews'], 0, 3) as $review)
        <li class="list-group-item">
            <strong>{{ $review['user_name'] }}</strong> - {{ $review['rating'] }}/5<br>
            <span>{{ $review['comment'] }}</span>
        </li>
        @endforeach
    </ul>
</div>
@endif
@endsection 