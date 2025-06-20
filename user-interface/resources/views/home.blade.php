@extends('layouts.app')
@section('content')
<div class="jumbotron text-center py-5">
    <h1 class="display-4">Selamat Datang di Perpustakaan Online</h1>
    <p class="lead">Cari, pinjam, dan review buku favoritmu secara mudah dan cepat!</p>
    <hr class="my-4">
    <a class="btn btn-primary btn-lg" href="/books" role="button">Lihat Katalog Buku</a>
    <a class="btn btn-outline-primary btn-lg" href="/login" role="button">Login</a>
    <a class="btn btn-outline-secondary btn-lg" href="/register" role="button">Daftar</a>
</div>
@endsection 