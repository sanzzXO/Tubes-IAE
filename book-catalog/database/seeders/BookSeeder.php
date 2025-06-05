<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            [
                'title' => 'Harry Potter and the Philosopher\'s Stone',
                'author' => 'J.K. Rowling',
                'isbn' => '9780747532743',
                'description' => 'The first book in the Harry Potter series',
                'category' => 'Fantasy',
                'stock' => 10
            ],
            [
                'title' => 'The Lord of the Rings',
                'author' => 'J.R.R. Tolkien',
                'isbn' => '9780618640157',
                'description' => 'Epic high-fantasy novel',
                'category' => 'Fantasy',
                'stock' => 8
            ],
            [
                'title' => 'To Kill a Mockingbird',
                'author' => 'Harper Lee',
                'isbn' => '9780446310789',
                'description' => 'Classic of modern American literature',
                'category' => 'Literary Fiction',
                'stock' => 15
            ],
            [
                'title' => 'The Da Vinci Code',
                'author' => 'Dan Brown',
                'isbn' => '9780307474278',
                'description' => 'Mystery thriller novel',
                'category' => 'Mystery',
                'stock' => 12
            ],
            [
                'title' => '1984',
                'author' => 'George Orwell',
                'isbn' => '9780451524935',
                'description' => 'Dystopian social science fiction',
                'category' => 'Science Fiction',
                'stock' => 7
            ]
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}