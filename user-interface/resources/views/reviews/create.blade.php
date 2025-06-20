@extends('layouts.app')
@section('content')
<h2>Tulis Review Buku</h2>
@if($errors->has('review'))
    <div class="alert alert-danger">{{ $errors->first('review') }}</div>
@endif
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<form method="POST" action="/reviews">
    @csrf
    <input type="hidden" name="book_id" value="{{ $bookId }}">
    <div class="mb-3">
        <label for="rating" class="form-label">Rating</label>
        <select class="form-select" id="rating" name="rating" required>
            <option value="">Pilih rating</option>
            @for($i=1; $i<=5; $i++)
                <option value="{{ $i }}">{{ $i }}</option>
            @endfor
        </select>
    </div>
    <div class="mb-3">
        <label for="comment" class="form-label">Komentar</label>
        <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Kirim Review</button>
</form>
@endsection 