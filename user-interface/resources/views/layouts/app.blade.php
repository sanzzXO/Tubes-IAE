<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container">
    <a class="navbar-brand" href="/">Perpustakaan Online</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="/books">Katalog Buku</a></li>
        <li class="nav-item"><a class="nav-link" href="/borrowings">Peminjaman</a></li>
        <li class="nav-item"><a class="nav-link" href="/reviews">Review</a></li>
        @if(session('user_id'))
            <li class="nav-item"><a class="nav-link" href="/dashboard">Profil</a></li>
            <li class="nav-item">
                <form method="POST" action="/logout" style="display:inline;">
                    @csrf
                    <button class="nav-link btn btn-link" type="submit" style="padding:0;">Logout</button>
                </form>
            </li>
        @else
            <li class="nav-item"><a class="nav-link" href="/login">Login</a></li>
            <li class="nav-item"><a class="nav-link" href="/register">Daftar</a></li>
        @endif
      </ul>
    </div>
  </div>
</nav>
<div class="container">
    @yield('content')
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 