@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Katalog Buku</h2>
    <form class="d-flex align-items-center" method="GET" action="/books">
        <input class="form-control me-2" type="search" name="q" placeholder="Cari buku..." value="{{ $query ?? '' }}">
        <select class="form-select me-2" name="category" style="width:auto;">
            <option value="">Semua Kategori</option>
            @foreach($categories ?? [] as $cat)
                <option value="{{ $cat['id'] }}" @if(isset($category) && $category == $cat['id']) selected @endif>{{ $cat['name'] }}</option>
            @endforeach
        </select>
        <button class="btn btn-outline-primary" type="submit">Cari</button>
    </form>
</div>
@if(is_array($books) && count($books) > 0 && isset($books[0]['title']))
<div class="row row-cols-1 row-cols-md-3 g-4">
    @foreach($books as $book)
    <div class="col">
        <div class="card h-100 shadow-sm">
            @if(!empty($book['cover_image']))
                <img src="{{ $book['cover_image'] }}" class="card-img-top" alt="{{ $book['title'] }}" style="object-fit:cover; height:220px;">
            @else
                <div class="bg-light d-flex align-items-center justify-content-center" style="height:220px;">
                    <span class="text-muted">Tidak ada cover</span>
                </div>
            @endif
            <div class="card-body d-flex flex-column">
                <h5 class="card-title">{{ $book['title'] }}</h5>
                <p class="card-text mb-1">{{ $book['author'] }}</p>
                <p class="card-text mb-1 text-muted">{{ $book['publication_year'] ?? '-' }}</p>
                <p class="card-text mb-2">
                    @if(is_array($book['category']) && isset($book['category']['name']))
                        <span class="badge bg-secondary">{{ $book['category']['name'] }}</span>
                    @else
                        <span class="badge bg-secondary">{{ $book['category'] ?? '-' }}</span>
                    @endif
                </p>
                <p class="mb-2">
                    @if(is_numeric($book['available']))
                        @if($book['available'] > 0)
                            <span class="text-success fw-bold">Tersedia ({{ $book['available'] }})</span>
                        @else
                            <span class="text-danger fw-bold">Dipinjam semua</span>
                        @endif
                    @else
                        <span class="text-success fw-bold">Tersedia</span>
                    @endif
                </p>
                <div class="mt-auto d-flex gap-2">
                    @if(isset($book['is_borrowed_by_user']) && $book['is_borrowed_by_user'])
                        <form method="POST" action="/borrowings/return/{{ $book['borrowing_id'] }}">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Kembalikan</button>
                        </form>
                    @else
                        <form method="POST" action="/borrowings">
                            @csrf
                            <input type="hidden" name="book_id" value="{{ $book['id'] }}">
                            <button type="submit" class="btn btn-success btn-sm" @if(!is_numeric($book['available']) || $book['available'] < 1) disabled @endif>Pinjam</button>
                        </form>
                    @endif
                    <a href="/reviews/create/{{ $book['id'] }}" class="btn btn-warning btn-sm">Review</a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
    <div class="alert alert-danger text-center">Tidak ada data buku atau terjadi kesalahan koneksi ke layanan katalog.</div>
@endif
@if(session('success'))
    <script>
        alert(@json(session('success')));
        window.location.href = '/books';
    </script>
@endif
@if($errors->has('return'))
    <div class="alert alert-danger text-center">{{ $errors->first('return') }}</div>
@endif
@endsection 