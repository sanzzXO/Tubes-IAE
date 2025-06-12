<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Perpustakaan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <i class="fas fa-book-reader text-2xl text-indigo-600 mr-2"></i>
                    <h1 class="text-xl font-bold text-gray-800">Sistem Manajemen Perpustakaan</h1>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <div class="flex items-center">
                            <i class="fas fa-user-circle text-gray-600 mr-2"></i>
                            <span class="text-gray-700">{{ Auth::user()->name }}</span>
                        </div>
                        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-indigo-600 transition duration-300 flex items-center">
                            <i class="fas fa-tachometer-alt mr-2"></i>
                            Dashboard
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center text-gray-600 hover:text-red-600 transition duration-300">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                Keluar
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600 transition duration-300">Masuk</a>
                        <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-300">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 pb-8 sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                    <div class="sm:text-center lg:text-left">
                        <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                            <span class="block">Selamat Datang di</span>
                            <span class="block text-indigo-600">Perpustakaan Digital</span>
                        </h1>
                        <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                            Temukan ribuan buku, kelola peminjaman, dan nikmati pengalaman membaca yang lebih baik dengan sistem manajemen perpustakaan modern kami.
                        </p>
                        <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                            @auth
                                <div class="rounded-md shadow">
                                    <a href="{{ route('dashboard') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10">
                                        <i class="fas fa-tachometer-alt mr-2"></i>
                                        Ke Dashboard
                                    </a>
                                </div>
                            @else
                                <div class="rounded-md shadow">
                                    <a href="{{ route('register') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10">
                                        Mulai Sekarang
                                    </a>
                                </div>
                                <div class="mt-3 sm:mt-0 sm:ml-3">
                                    <a href="#fitur" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 md:py-4 md:text-lg md:px-10">
                                        Pelajari Lebih Lanjut
                                    </a>
                                </div>
                            @endauth
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
            <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full" src="https://images.unsplash.com/photo-1507842217343-583bb7270b66?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1000&q=80" alt="Library">
        </div>
    </div>

    <!-- Features Section -->
    <div id="fitur" class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:text-center">
                <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">Fitur</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Semua yang Anda Butuhkan
                </p>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                    Sistem kami menyediakan berbagai fitur untuk memudahkan pengelolaan perpustakaan Anda.
                </p>
            </div>

            <div class="mt-10">
                <div class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">
                    <!-- Feature 1 -->
                    <div class="relative">
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <i class="fas fa-book text-xl"></i>
                        </div>
                        <div class="ml-16">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Katalog Digital</h3>
                            <p class="mt-2 text-base text-gray-500">
                                Akses ribuan buku dalam format digital dengan mudah dan cepat.
                            </p>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="relative">
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <i class="fas fa-calendar-check text-xl"></i>
                        </div>
                        <div class="ml-16">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Manajemen Peminjaman</h3>
                            <p class="mt-2 text-base text-gray-500">
                                Kelola peminjaman buku dengan sistem yang terintegrasi dan mudah digunakan.
                            </p>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="relative">
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <i class="fas fa-search text-xl"></i>
                        </div>
                        <div class="ml-16">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Pencarian Cepat</h3>
                            <p class="mt-2 text-base text-gray-500">
                                Temukan buku yang Anda cari dengan cepat menggunakan fitur pencarian canggih.
                            </p>
                        </div>
                    </div>

                    <!-- Feature 4 -->
                    <div class="relative">
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <i class="fas fa-bell text-xl"></i>
                        </div>
                        <div class="ml-16">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Notifikasi Otomatis</h3>
                            <p class="mt-2 text-base text-gray-500">
                                Dapatkan pengingat untuk pengembalian buku dan informasi penting lainnya.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    @guest
    <div class="bg-indigo-700">
        <div class="max-w-2xl mx-auto text-center py-16 px-4 sm:py-20 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                <span class="block">Siap untuk Memulai?</span>
                <span class="block">Daftar sekarang dan nikmati semua fitur.</span>
            </h2>
            <p class="mt-4 text-lg leading-6 text-indigo-200">
                Bergabunglah dengan ribuan pengguna yang telah merasakan kemudahan mengelola perpustakaan digital.
            </p>
            <a href="{{ route('register') }}" class="mt-8 w-full inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50 sm:w-auto">
                Daftar Gratis
            </a>
        </div>
    </div>
    @endguest

    <!-- Footer -->
    <footer class="bg-white">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 md:flex md:items-center md:justify-between lg:px-8">
            <div class="flex justify-center space-x-6 md:order-2">
                <a href="#" class="text-gray-400 hover:text-gray-500">
                    <i class="fab fa-facebook text-xl"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-gray-500">
                    <i class="fab fa-instagram text-xl"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-gray-500">
                    <i class="fab fa-twitter text-xl"></i>
                </a>
            </div>
            <div class="mt-8 md:mt-0 md:order-1">
                <p class="text-center text-base text-gray-400">
                    &copy; 2024 Sistem Manajemen Perpustakaan. All rights reserved.
                </p>
            </div>
        </div>
    </footer>
</body>
</html>
