@extends('staff.layouts.app')
@section('content')
    <h2>Manajemen Buku</h2>
    <a href="/staff/books/create" class="btn btn-primary mb-3">Tambah Buku</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Stok</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($books as $book)
            <tr>
                <td>{{ $book['title'] }}</td>
                <td>{{ $book['author'] }}</td>
                <td>{{ $book['stock'] }}</td>
                <td>
                    <a href="/staff/books/{{ $book['id'] }}/edit" class="btn btn-sm btn-warning">Edit</a>
                    <form method="POST" action="/staff/books/{{ $book['id'] }}/delete" style="display:inline-block">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection 