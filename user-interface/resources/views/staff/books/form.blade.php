@extends('staff.layouts.app')
@section('content')
    <h2>{{ isset($book) ? 'Edit' : 'Tambah' }} Buku</h2>
    <form method="POST" action="{{ isset($book) ? '/staff/books/' . $book['id'] . '/update' : '/staff/books' }}">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Judul</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ $book['title'] ?? '' }}" required>
        </div>
        <div class="mb-3">
            <label for="author" class="form-label">Penulis</label>
            <input type="text" class="form-control" id="author" name="author" value="{{ $book['author'] ?? '' }}" required>
        </div>
        <div class="mb-3">
            <label for="category_id" class="form-label">Kategori</label>
            <select class="form-select" id="category_id" name="category_id" required>
                @foreach($categories as $category)
                    <option value="{{ $category['id'] }}" @if(isset($book) && $book['category_id'] == $category['id']) selected @endif>{{ $category['name'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="description" name="description" rows="3" required>{{ $book['description'] ?? '' }}</textarea>
        </div>
        <div class="mb-3">
            <label for="stock" class="form-label">Stok</label>
            <input type="number" class="form-control" id="stock" name="stock" value="{{ $book['stock'] ?? 0 }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
@endsection 