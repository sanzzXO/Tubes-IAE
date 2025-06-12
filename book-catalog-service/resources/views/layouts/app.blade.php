<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Sistem Manajemen Perpustakaan') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6366f1;
            --secondary-color: #8b5cf6;
            --accent-color: #06b6d4;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.25rem;
        }
        
        .book-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 15px;
            overflow: hidden;
            height: 100%;
        }
        
        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(99, 102, 241, 0.15);
        }
        
        .book-cover {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }
        
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            color: white;
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(99, 102, 241, 0.3);
            color: white;
        }
        
        .search-card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
        
        .page-header {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            padding: 60px 0;
        }
        
        .badge-status {
            font-size: 0.75rem;
            padding: 6px 12px;
        }

        .book-description {
            font-size: 0.85rem;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .book-meta {
            font-size: 0.8rem;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand text-primary" href="{{ route('home') }}">
                <i class="fas fa-book me-2"></i>
                Sistem Manajemen Perpustakaan
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') || request()->routeIs('books.index') ? 'fw-bold text-primary' : 'text-muted' }}" 
                           href="{{ route('home') }}">
                            <i class="fas fa-books me-1"></i>Katalog Buku
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('categories.*') ? 'fw-bold text-primary' : 'text-muted' }}" 
                           href="{{ route('categories.index') }}">
                            <i class="fas fa-tags me-1"></i>Kategori
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'fw-bold text-primary' : 'text-muted' }}" 
                           href="{{ route('dashboard') }}">
                            <i class="fas fa-chart-bar me-1"></i>Dashboard
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>