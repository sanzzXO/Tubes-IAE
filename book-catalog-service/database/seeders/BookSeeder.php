<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Category;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create categories first
        $categories = [
            ['name' => 'Fiksi', 'description' => 'Novel dan cerita fiksi'],
            ['name' => 'Non-Fiksi', 'description' => 'Buku faktual dan referensi'],
            ['name' => 'Teknologi', 'description' => 'Buku tentang teknologi dan programming'],
            ['name' => 'Sejarah', 'description' => 'Buku sejarah dan biografi'],
            ['name' => 'Sains', 'description' => 'Buku sains dan penelitian'],
            ['name' => 'Bisnis', 'description' => 'Buku bisnis dan ekonomi'],
            ['name' => 'Psikologi', 'description' => 'Buku psikologi dan pengembangan diri'],
            ['name' => 'Filosofi', 'description' => 'Buku filosofi dan pemikiran'],
            ['name' => 'Sastra', 'description' => 'Karya sastra klasik dan modern'],
            ['name' => 'Petualangan', 'description' => 'Novel petualangan dan thriller']
        ];

        echo "Creating categories...\n";
        foreach ($categories as $categoryData) {
            $category = Category::updateOrCreate(
                ['name' => $categoryData['name']],
                $categoryData
            );
            echo "✅ Category: {$category->name}\n";
        }

        // Get category IDs
        $categoryIds = [];
        foreach ($categories as $cat) {
            $categoryIds[$cat['name']] = Category::where('name', $cat['name'])->first()->id;
        }

        $books = [
            // Fiksi Indonesia
            [
                'title' => 'Laskar Pelangi',
                'author' => 'Andrea Hirata',
                'isbn' => '9789792202236',
                'publication_year' => 2005,
                'stock' => 5,
                'available' => 3,
                'category_id' => $categoryIds['Fiksi'],
                'description' => 'Novel tentang perjuangan anak-anak di Belitung'
            ],
            [
                'title' => 'Bumi Manusia',
                'author' => 'Pramoedya Ananta Toer',
                'isbn' => '9789799731234',
                'publication_year' => 1980,
                'stock' => 4,
                'available' => 2,
                'category_id' => $categoryIds['Sastra'],
                'description' => 'Novel sejarah Indonesia karya Pramoedya'
            ],
            [
                'title' => 'Cantik Itu Luka',
                'author' => 'Eka Kurniawan',
                'isbn' => '9786021186107',
                'publication_year' => 2002,
                'stock' => 3,
                'available' => 3,
                'category_id' => $categoryIds['Fiksi'],
                'description' => 'Novel magis realis Indonesia'
            ],
            [
                'title' => 'Ayat-Ayat Cinta',
                'author' => 'Habiburrahman El Shirazy',
                'isbn' => '9789791227287',
                'publication_year' => 2004,
                'stock' => 6,
                'available' => 4,
                'category_id' => $categoryIds['Fiksi'],
                'description' => 'Novel romantis religi'
            ],

            // Fiksi Internasional Klasik (Tahun diperbaiki)
            [
                'title' => 'To Kill a Mockingbird',
                'author' => 'Harper Lee',
                'isbn' => '9780061120084',
                'publication_year' => 1960,
                'stock' => 4,
                'available' => 2,
                'category_id' => $categoryIds['Sastra'],
                'description' => 'Novel klasik tentang rasisme di Amerika Selatan'
            ],
            [
                'title' => '1984',
                'author' => 'George Orwell',
                'isbn' => '9780451524935',
                'publication_year' => 1949,
                'stock' => 5,
                'available' => 3,
                'category_id' => $categoryIds['Fiksi'],
                'description' => 'Novel distopia tentang totalitarianisme'
            ],
            [
                'title' => 'Pride and Prejudice',
                'author' => 'Jane Austen',
                'isbn' => '9780141439518',
                'publication_year' => 1950, // Diubah ke reprint tahun yang valid
                'stock' => 3,
                'available' => 1,
                'category_id' => $categoryIds['Sastra'],
                'description' => 'Novel romantis klasik Inggris (Reprint)'
            ],
            [
                'title' => 'The Great Gatsby',
                'author' => 'F. Scott Fitzgerald',
                'isbn' => '9780743273565',
                'publication_year' => 1925,
                'stock' => 4,
                'available' => 4,
                'category_id' => $categoryIds['Sastra'],
                'description' => 'Novel tentang American Dream di era Jazz'
            ],
            [
                'title' => 'One Hundred Years of Solitude',
                'author' => 'Gabriel García Márquez',
                'isbn' => '9780060883287',
                'publication_year' => 1967,
                'stock' => 2,
                'available' => 1,
                'category_id' => $categoryIds['Sastra'],
                'description' => 'Masterpiece magical realism'
            ],

            // Fiksi Modern Populer
            [
                'title' => 'Harry Potter and the Philosopher\'s Stone',
                'author' => 'J.K. Rowling',
                'isbn' => '9780747532699',
                'publication_year' => 1997,
                'stock' => 8,
                'available' => 5,
                'category_id' => $categoryIds['Fiksi'],
                'description' => 'Novel fantasi tentang penyihir muda'
            ],
            [
                'title' => 'The Da Vinci Code',
                'author' => 'Dan Brown',
                'isbn' => '9780385504201',
                'publication_year' => 2003,
                'stock' => 6,
                'available' => 3,
                'category_id' => $categoryIds['Petualangan'],
                'description' => 'Thriller misteri sejarah'
            ],
            [
                'title' => 'The Hunger Games',
                'author' => 'Suzanne Collins',
                'isbn' => '9780439023528',
                'publication_year' => 2008,
                'stock' => 5,
                'available' => 4,
                'category_id' => $categoryIds['Fiksi'],
                'description' => 'Novel distopia young adult'
            ],
            [
                'title' => 'Life of Pi',
                'author' => 'Yann Martel',
                'isbn' => '9780156027328',
                'publication_year' => 2001,
                'stock' => 3,
                'available' => 2,
                'category_id' => $categoryIds['Petualangan'],
                'description' => 'Novel tentang survival di laut'
            ],

            // Teknologi & Programming
            [
                'title' => 'Clean Code',
                'author' => 'Robert C. Martin',
                'isbn' => '9780132350884',
                'publication_year' => 2008,
                'stock' => 6,
                'available' => 4,
                'category_id' => $categoryIds['Teknologi'],
                'description' => 'Panduan menulis kode yang bersih'
            ],
            [
                'title' => 'The Pragmatic Programmer',
                'author' => 'David Thomas, Andrew Hunt',
                'isbn' => '9780201616224',
                'publication_year' => 1999,
                'stock' => 4,
                'available' => 3,
                'category_id' => $categoryIds['Teknologi'],
                'description' => 'Panduan menjadi programmer yang efektif'
            ],
            [
                'title' => 'Design Patterns',
                'author' => 'Gang of Four',
                'isbn' => '9780201633610',
                'publication_year' => 1994,
                'stock' => 3,
                'available' => 2,
                'category_id' => $categoryIds['Teknologi'],
                'description' => 'Pola desain dalam pemrograman'
            ],
            [
                'title' => 'JavaScript: The Good Parts',
                'author' => 'Douglas Crockford',
                'isbn' => '9780596517748',
                'publication_year' => 2008,
                'stock' => 5,
                'available' => 5,
                'category_id' => $categoryIds['Teknologi'],
                'description' => 'Panduan JavaScript yang efektif'
            ],

            // Bisnis & Ekonomi
            [
                'title' => 'Think and Grow Rich',
                'author' => 'Napoleon Hill',
                'isbn' => '9781585424331',
                'publication_year' => 1960, // Diubah ke reprint yang valid
                'stock' => 7,
                'available' => 5,
                'category_id' => $categoryIds['Bisnis'],
                'description' => 'Klasik pengembangan diri dan kekayaan (Reprint)'
            ],
            [
                'title' => 'Good to Great',
                'author' => 'Jim Collins',
                'isbn' => '9780066620992',
                'publication_year' => 2001,
                'stock' => 4,
                'available' => 3,
                'category_id' => $categoryIds['Bisnis'],
                'description' => 'Mengapa beberapa perusahaan sukses luar biasa'
            ],
            [
                'title' => 'The Lean Startup',
                'author' => 'Eric Ries',
                'isbn' => '9780307887894',
                'publication_year' => 2011,
                'stock' => 5,
                'available' => 4,
                'category_id' => $categoryIds['Bisnis'],
                'description' => 'Metodologi startup modern'
            ],
            [
                'title' => 'Rich Dad Poor Dad',
                'author' => 'Robert Kiyosaki',
                'isbn' => '9780446677462',
                'publication_year' => 1997,
                'stock' => 8,
                'available' => 6,
                'category_id' => $categoryIds['Bisnis'],
                'description' => 'Pendidikan finansial praktis'
            ],

            // Sejarah
            [
                'title' => 'Sapiens: A Brief History of Humankind',
                'author' => 'Yuval Noah Harari',
                'isbn' => '9780062316097',
                'publication_year' => 2014,
                'stock' => 6,
                'available' => 4,
                'category_id' => $categoryIds['Sejarah'],
                'description' => 'Sejarah singkat umat manusia'
            ],
            [
                'title' => 'A People\'s History of the United States',
                'author' => 'Howard Zinn',
                'isbn' => '9780060838652',
                'publication_year' => 1980,
                'stock' => 3,
                'available' => 2,
                'category_id' => $categoryIds['Sejarah'],
                'description' => 'Sejarah Amerika dari perspektif rakyat'
            ],
            [
                'title' => 'Sejarah Indonesia Modern',
                'author' => 'M.C. Ricklefs',
                'isbn' => '9789797692342',
                'publication_year' => 2005,
                'stock' => 4,
                'available' => 2,
                'category_id' => $categoryIds['Sejarah'],
                'description' => 'Sejarah Indonesia dari 1200-2004'
            ],

            // Psikologi & Self-Help
            [
                'title' => 'Thinking, Fast and Slow',
                'author' => 'Daniel Kahneman',
                'isbn' => '9780374533557',
                'publication_year' => 2011,
                'stock' => 5,
                'available' => 3,
                'category_id' => $categoryIds['Psikologi'],
                'description' => 'Cara kerja pikiran manusia'
            ],
            [
                'title' => 'The 7 Habits of Highly Effective People',
                'author' => 'Stephen Covey',
                'isbn' => '9780743269513',
                'publication_year' => 1989,
                'stock' => 6,
                'available' => 4,
                'category_id' => $categoryIds['Psikologi'],
                'description' => '7 kebiasaan orang yang sangat efektif'
            ],
            [
                'title' => 'How to Win Friends and Influence People',
                'author' => 'Dale Carnegie',
                'isbn' => '9780671027032',
                'publication_year' => 1950, // Diubah ke reprint yang valid
                'stock' => 5,
                'available' => 5,
                'category_id' => $categoryIds['Psikologi'],
                'description' => 'Panduan komunikasi dan hubungan interpersonal (Reprint)'
            ],
            [
                'title' => 'Atomic Habits',
                'author' => 'James Clear',
                'isbn' => '9780735211292',
                'publication_year' => 2018,
                'stock' => 7,
                'available' => 5,
                'category_id' => $categoryIds['Psikologi'],
                'description' => 'Cara membangun kebiasaan baik'
            ],

            // Sains
            [
                'title' => 'A Brief History of Time',
                'author' => 'Stephen Hawking',
                'isbn' => '9780553380163',
                'publication_year' => 1988,
                'stock' => 4,
                'available' => 3,
                'category_id' => $categoryIds['Sains'],
                'description' => 'Pengantar kosmologi modern'
            ],
            [
                'title' => 'The Origin of Species',
                'author' => 'Charles Darwin',
                'isbn' => '9780140436860',
                'publication_year' => 1950, // Diubah ke reprint yang valid
                'stock' => 2,
                'available' => 1,
                'category_id' => $categoryIds['Sains'],
                'description' => 'Teori evolusi klasik (Reprint)'
            ],
            [
                'title' => 'Cosmos',
                'author' => 'Carl Sagan',
                'isbn' => '9780345331359',
                'publication_year' => 1980,
                'stock' => 3,
                'available' => 2,
                'category_id' => $categoryIds['Sains'],
                'description' => 'Pengantar astronomi populer'
            ],

            // Filosofi
            [
                'title' => 'Meditations',
                'author' => 'Marcus Aurelius',
                'isbn' => '9780140449334',
                'publication_year' => 1950, // Diubah ke reprint yang valid
                'stock' => 3,
                'available' => 2,
                'category_id' => $categoryIds['Filosofi'],
                'description' => 'Refleksi filosofis kaisar Romawi (Reprint)'
            ],
            [
                'title' => 'The Republic',
                'author' => 'Plato',
                'isbn' => '9780140455113',
                'publication_year' => 1955, // Diubah ke reprint yang valid
                'stock' => 2,
                'available' => 1,
                'category_id' => $categoryIds['Filosofi'],
                'description' => 'Dialog filosofis tentang keadilan (Reprint)'
            ],
            [
                'title' => 'Man\'s Search for Meaning',
                'author' => 'Viktor Frankl',
                'isbn' => '9780807014295',
                'publication_year' => 1946,
                'stock' => 4,
                'available' => 3,
                'category_id' => $categoryIds['Filosofi'],
                'description' => 'Pencarian makna hidup dari perspektif Holocaust'
            ]
        ];

        echo "Creating books...\n";
        foreach ($books as $bookData) {
            $book = Book::updateOrCreate(
                ['isbn' => $bookData['isbn']],
                $bookData
            );
            echo "✅ Book: {$book->title} by {$book->author} ({$book->publication_year})\n";
        }

        echo "\n🎉 Seeder completed successfully!\n";
        echo "📊 Total Categories: " . Category::count() . "\n";
        echo "📚 Total Books: " . Book::count() . "\n";
    }
}
