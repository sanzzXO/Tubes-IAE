<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Manajemen Perpustakaan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <i class="fas fa-book-reader text-2xl text-indigo-600 mr-2"></i>
                        <h1 class="text-xl font-bold text-gray-800">Sistem Manajemen Perpustakaan</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <i class="fas fa-user-circle text-gray-600 mr-2"></i>
                            <span class="text-gray-700">{{ Auth::user()->name }}</span>
                        </div>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center text-gray-600 hover:text-red-600 transition duration-300">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <div class="border-4 border-dashed border-gray-200 rounded-lg h-96 p-4">
                    <h2 class="text-2xl font-bold mb-4">Selamat Datang, {{ Auth::user()->name }}!</h2>
                    <p class="text-gray-600">
                        Anda telah berhasil masuk ke Sistem Manajemen Perpustakaan.
                    </p>
                </div>
            </div>
        </main>
    </div>
</body>
</html> 