<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Area - Perpustakaan Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
      /* Memberi padding atas pada body seukuran tinggi navbar */
      body {
        padding-top: 56px;
      }
      .sidebar {
        position: fixed;
        top: 56px; /* Mulai di bawah navbar */
        bottom: 0;
        left: 0;
        z-index: 100; 
        padding: 20px 0;
        box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
        width: 240px; /* Lebar sidebar yang tetap */
      }
      .sidebar .nav-link {
        font-weight: 500;
      }
       /* Konten utama didorong ke kanan selebar sidebar */
      .main-content {
        margin-left: 240px; /* Sama dengan lebar sidebar */
        padding: 30px;
      }
    </style>
</head>
<body>

<!-- Navbar Atas (Fixed) -->
<nav class="navbar navbar-dark bg-primary fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="/staff/dashboard">Staff Area</a>
        <ul class="navbar-nav ms-auto flex-row">
            <li class="nav-item me-3"><a class="nav-link" href="/">Lihat Website</a></li>
            <li class="nav-item">
                <form method="POST" action="/logout" style="display:inline;">
                    @csrf
                    <button class="nav-link btn btn-link" type="submit">Logout</button>
                </form>
            </li>
        </ul>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Kiri (Fixed) -->
        <nav class="col-md-3 col-lg-2 d-none d-md-block bg-light sidebar">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link {{ request()->is('staff/dashboard') ? 'active' : '' }}" href="/staff/dashboard">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->is('staff/books*') ? 'active' : '' }}" href="/staff/books">Manajemen Buku</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->is('staff/categories*') ? 'active' : '' }}" href="/staff/categories">Manajemen Kategori</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->is('staff/users*') ? 'active' : '' }}" href="/staff/users">Manajemen User</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->is('staff/borrowings*') ? 'active' : '' }}" href="/staff/borrowings">Manajemen Peminjaman</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->is('staff/reviews*') ? 'active' : '' }}" href="/staff/reviews">Manajemen Review</a></li>
                </ul>
            </div>
        </nav>

        <!-- Konten Utama -->
        <main class="main-content">
            @yield('content')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 