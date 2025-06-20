<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        /* Custom Navbar */
        .navbar-custom {
            background: var(--primary-gradient) !important;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
            padding: 1rem 0;
        }
        
        .navbar-custom .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: white !important;
        }
        
        .navbar-custom .nav-link {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 500;
            margin: 0 0.5rem;
            padding: 0.5rem 1rem !important;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .navbar-custom .nav-link:hover,
        .navbar-custom .nav-link.active {
            color: white !important;
            background: rgba(255,255,255,0.2);
            transform: translateY(-1px);
        }

        /* Page Headers */
        .page-header {
            background: var(--primary-gradient);
            color: white;
            padding: 4rem 0;
            position: relative;
            overflow: hidden;
        }
        
        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="20" r="3" fill="rgba(255,255,255,0.1)"/><circle cx="40" cy="70" r="2" fill="rgba(255,255,255,0.1)"/></svg>');
            animation: float 20s infinite linear;
        }
        
        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            100% { transform: translateY(-100px) rotate(360deg); }
        }

        /* Cards */
        .search-card, .feature-card {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            border-radius: 1rem;
            transition: transform 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.15);
        }

        /* Buttons */
        .btn-primary-custom {
            background: var(--primary-gradient);
            border: none;
            border-radius: 0.75rem;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(102, 126, 234, 0.4);
        }
        
        .btn-success-custom {
            background: var(--success-gradient);
            border: none;
            border-radius: 0.75rem;
            color: white;
        }
        
        .btn-warning-custom {
            background: var(--secondary-gradient);
            border: none;
            border-radius: 0.75rem;
            color: white;
        }

        /* Book Cards */
        .book-card {
            transition: all 0.3s ease;
            border-radius: 1rem;
            overflow: hidden;
            border: none;
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
        }
        
        .book-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.15);
        }
        
        .book-cover {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        /* Badges */
        .badge-status {
            font-size: 0.8rem;
            padding: 0.5rem 1rem;
            border-radius: 1rem;
        }

        /* Hero Section Animations */
        .hero-content {
            animation: fadeInUp 1s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Floating Elements */
        .floating-book {
            position: absolute;
            opacity: 0.1;
            animation: floatBook 15s infinite ease-in-out;
        }
        
        @keyframes floatBook {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(10deg); }
        }

        /* Footer */
        .footer-custom {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 2rem 0;
            margin-top: 4rem;
        }
    </style>
</head>
<body>
    <!-- Custom Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom mb-0">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-book-reader me-2"></i>Perpustakaan Online
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">
                            <i class="fas fa-home me-1"></i>Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('books*') ? 'active' : '' }}" href="/books">
                            <i class="fas fa-books me-1"></i>Katalog Buku
                        </a>
                    </li>
                    @if(session('user_id'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('borrowings*') ? 'active' : '' }}" href="/borrowings">
                                <i class="fas fa-bookmark me-1"></i>Peminjaman
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('reviews*') ? 'active' : '' }}" href="/reviews">
                                <i class="fas fa-star me-1"></i>Review
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('dashboard*') ? 'active' : '' }}" href="/dashboard">
                                <i class="fas fa-user me-1"></i>Profil
                            </a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="/logout" style="display:inline;">
                                @csrf
                                <button class="nav-link btn btn-link text-white p-0" style="border: none; background: none;">
                                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                                </button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="/login">
                                <i class="fas fa-sign-in-alt me-1"></i>Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/register">
                                <i class="fas fa-user-plus me-1"></i>Daftar
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- Footer -->
    <footer class="footer-custom">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-book-reader me-2"></i>Perpustakaan Online</h5>
                    <p class="mb-0">Sistem manajemen perpustakaan digital modern</p>
                </div>
                <div class="col-md-6 text-end">
                    <p class="mb-0">&copy; 2025 Perpustakaan Online. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>