<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fiction = Category::where('slug', 'fiction')->first();
        $nonFiction = Category::where('slug', 'non-fiction')->first();
        $sciTech = Category::where('slug', 'science-technology')->first();
        $biography = Category::where('slug', 'biography')->first();

        $books = [
            [
                'title' => 'To Kill a Mockingbird',
                'author' => 'Harper Lee',
                'isbn' => '978-0-061-12008-4',
                'description' => 'A gripping, heart-wrenching, and wholly remarkable tale of coming-of-age in a South poisoned by virulent prejudice. Through the young eyes of Scout and Jem Finch, Harper Lee explores the irrationality of adult attitudes toward race and class.',
                'category_id' => $fiction->id,
                'stock' => 5,
                'available' => 4,
                'publication_year' => 1960,
                'publisher' => 'J.B. Lippincott & Co.'
            ],
            [
                'title' => '1984',
                'author' => 'George Orwell',
                'isbn' => '978-0-452-28424-1',
                'description' => 'A dystopian social science fiction novel and cautionary tale about the dangers of totalitarianism. Winston Smith works for the Ministry of Truth in London, chief city of Airstrip One, rewriting history to fit the party\'s propaganda.',
                'category_id' => $fiction->id,
                'stock' => 8,
                'available' => 6,
                'publication_year' => 1949,
                'publisher' => 'Secker & Warburg'
            ],
            [
                'title' => 'Clean Code',
                'author' => 'Robert C. Martin',
                'isbn' => '978-0-132-35088-4',
                'description' => 'A handbook of agile software craftsmanship that teaches how to write clean code. Even bad code can function, but if code isn\'t clean, it can bring a development organization to its knees.',
                'category_id' => $sciTech->id,
                'stock' => 10,
                'available' => 8,
                'publication_year' => 2008,
                'publisher' => 'Prentice Hall'
            ],
            [
                'title' => 'Sapiens: A Brief History of Humankind',
                'author' => 'Yuval Noah Harari',
                'isbn' => '978-0-062-31609-5',
                'description' => 'A narrative of humanity\'s creation and evolution that explores how biology and history have defined us and enhanced our understanding of what it means to be human.',
                'category_id' => $nonFiction->id,
                'stock' => 6,
                'available' => 0,
                'publication_year' => 2011,
                'publisher' => 'DVir Publishing House'
            ],
            [
                'title' => 'Steve Jobs',
                'author' => 'Walter Isaacson',
                'isbn' => '978-1-451-64853-9',
                'description' => 'The exclusive biography of Steve Jobs based on more than forty interviews with the Apple founder, as well as interviews with family members, friends, adversaries, competitors, and colleagues.',
                'category_id' => $biography->id,
                'stock' => 4,
                'available' => 3,
                'publication_year' => 2011,
                'publisher' => 'Simon & Schuster'
            ],
            [
                'title' => 'The Pragmatic Programmer',
                'author' => 'David Thomas, Andrew Hunt',
                'isbn' => '978-0-201-61622-4',
                'description' => 'Your journey to mastery of software development and becoming a better programmer. Filled with practical advice on everything from personal responsibility and career development to architectural techniques.',
                'category_id' => $sciTech->id,
                'stock' => 7,
                'available' => 7,
                'publication_year' => 1999,
                'publisher' => 'Addison-Wesley'
            ]
        ];

        foreach ($books as $book) {
            Book::updateOrCreate(
                ['isbn' => $book['isbn']],
                $book
            );
        }
    }
}
