@extends('layouts.app')
@section('content')
<h2>Dashboard Petugas</h2>
<div class="row">
    <div class="col-md-6">
        <h4>Buku</h4>
        <ul class="list-group mb-3">
            @foreach($books as $book)
            <li class="list-group-item">{{ $book['title'] }} - {{ $book['author'] }}</li>
            @endforeach
        </ul>
    </div>
    <div class="col-md-3">
        <h4>Kategori</h4>
        <ul class="list-group mb-3">
            @foreach($categories as $category)
            <li class="list-group-item">{{ $category['name'] }}</li>
            @endforeach
        </ul>
    </div>
    <div class="col-md-3">
        <h4>Anggota</h4>
        <ul class="list-group mb-3">
            @foreach($users as $user)
            <li class="list-group-item">{{ $user['name'] }} ({{ $user['email'] }})</li>
            @endforeach
        </ul>
    </div>
</div>
@endsection 