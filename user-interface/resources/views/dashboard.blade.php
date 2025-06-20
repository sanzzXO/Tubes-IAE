@extends('layouts.app')
@section('content')
<div class="card mb-4">
    <div class="card-body">
        <h3 class="card-title">Halo, {{ $user['name'] ?? 'User' }}</h3>
        <p class="card-text">Email: {{ $user['email'] ?? '-' }}</p>
        <p class="card-text">Role: {{ $user['role'] ?? '-' }}</p>
    </div>
</div>
<div class="row">
    <div class="col-md-4 mb-3">
        <a href="/books" class="btn btn-primary w-100">Katalog Buku</a>
    </div>
    <div class="col-md-4 mb-3">
        <a href="/borrowings" class="btn btn-success w-100">Peminjaman</a>
    </div>
    <div class="col-md-4 mb-3">
        <a href="/reviews" class="btn btn-warning w-100">Review Buku</a>
    </div>
</div>
@endsection 