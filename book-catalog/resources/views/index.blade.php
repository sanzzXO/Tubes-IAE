<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Books Catalog</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased">
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold mb-6">Books Catalog</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($books as $book)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-2">{{ $book->title }}</h2>
                    <p class="text-gray-600 mb-2">Author: {{ $book->author }}</p>
                    <p class="text-gray-600 mb-2">ISBN: {{ $book->isbn }}</p>
                    <p class="text-gray-600 mb-2">Category: {{ $book->category }}</p>
                    <p class="text-gray-600">Stock: {{ $book->stock }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </body>
</html>