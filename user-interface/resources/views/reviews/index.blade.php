@extends('layouts.app')
@section('content')
<h2>Review Buku</h2>
@if($bookId)
    <a href="/reviews/create/{{ $bookId }}" class="btn btn-primary mb-3">Tulis Review</a>
@endif
<table class="table table-striped">
    <thead>
        <tr>
            <th>Nama</th>
            @if(!$bookId)
            <th>Buku</th>
            @endif
            <th>Rating</th>
            <th>Komentar</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        @if(is_array($reviews) && count($reviews) > 0)
            @foreach($reviews as $review)
            <tr>
                <td>{{ $review['user']['name'] ?? '-' }}</td>
                @if(!$bookId)
                <td>{{ $review['book']['title'] ?? '-' }}</td>
                @endif
                <td>{{ $review['rating'] }}/5</td>
                <td>{{ $review['comment'] }}</td>
                <td>{{ $review['created_at'] }}</td>
            </tr>
            @endforeach
        @else
            <tr>
                <td colspan="{{ $bookId ? 4 : 5 }}" class="text-center text-danger">Tidak ada review.</td>
            </tr>
        @endif
    </tbody>
</table>
@endsection 