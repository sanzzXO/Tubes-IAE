<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample book data (simulating data from book-catalog-service)
        $books = [
            [
                'id' => 1,
                'title' => 'The Great Gatsby'
            ],
            [
                'id' => 2,
                'title' => 'To Kill a Mockingbird'
            ],
            [
                'id' => 3,
                'title' => '1984'
            ],
            [
                'id' => 4,
                'title' => 'Pride and Prejudice'
            ],
            [
                'id' => 5,
                'title' => 'The Hobbit'
            ],
            [
                'id' => 6,
                'title' => 'Lord of the Rings'
            ],
            [
                'id' => 7,
                'title' => 'Harry Potter and the Sorcerer\'s Stone'
            ],
            [
                'id' => 8,
                'title' => 'The Catcher in the Rye'
            ]
        ];

        // Sample user data (simulating data from auth-service)
        $users = [
            [
                'id' => 1,
                'name' => 'John Doe'
            ],
            [
                'id' => 2,
                'name' => 'Jane Smith'
            ],
            [
                'id' => 3,
                'name' => 'Bob Johnson'
            ],
            [
                'id' => 4,
                'name' => 'Alice Brown'
            ],
            [
                'id' => 5,
                'name' => 'Charlie Wilson'
            ],
            [
                'id' => 6,
                'name' => 'Diana Miller'
            ],
            [
                'id' => 7,
                'name' => 'Edward Davis'
            ],
            [
                'id' => 8,
                'name' => 'Fiona Garcia'
            ]
        ];

        // Sample review comments untuk berbagai rating
        $reviewComments = [
            5 => [
                'Karya sastra yang luar biasa! Plot yang mendalam dan karakter yang kompleks.',
                'Buku ini benar-benar mengubah cara pandang saya tentang kehidupan.',
                'Salah satu buku terbaik yang pernah saya baca. Sangat direkomendasikan!',
                'Penulisan yang elegan dan cerita yang memukau dari awal sampai akhir.',
                'Klasik yang tak lekang oleh waktu. Setiap halaman penuh makna.'
            ],
            4 => [
                'Buku yang sangat bagus dengan karakter yang menarik.',
                'Plot yang solid dan penulisan yang mengalir dengan baik.',
                'Membaca buku ini adalah pengalaman yang menyenangkan.',
                'Cerita yang menghibur dengan beberapa twist yang mengejutkan.',
                'Karya yang layak dibaca, meskipun ada beberapa bagian yang lambat.'
            ],
            3 => [
                'Buku yang cukup baik, tapi tidak istimewa.',
                'Cerita lumayan menarik, tapi ada beberapa bagian yang membosankan.',
                'Plot yang standar, tidak ada yang mengejutkan.',
                'Buku yang bisa dibaca untuk mengisi waktu luang.',
                'Karakter yang kurang berkembang, tapi cerita masih bisa diikuti.'
            ],
            2 => [
                'Buku ini kurang memuaskan, plot yang tidak jelas.',
                'Karakter yang datar dan cerita yang membingungkan.',
                'Sulit untuk menyelesaikan buku ini karena membosankan.',
                'Penulisan yang kurang menarik dan plot yang lemah.',
                'Tidak sesuai dengan ekspektasi saya.'
            ],
            1 => [
                'Buku yang sangat mengecewakan, tidak direkomendasikan.',
                'Sulit untuk menemukan hal positif dari buku ini.',
                'Waktu yang terbuang untuk membaca buku ini.',
                'Plot yang tidak masuk akal dan karakter yang menjengkelkan.',
                'Salah satu buku terburuk yang pernah saya baca.'
            ]
        ];

        // Create sample review records
        $reviews = [
            // Reviews untuk The Great Gatsby
            [
                'user_id' => 1,
                'book_id' => 1,
                'rating' => 5,
                'comment' => 'Karya sastra yang luar biasa! Fitzgerald berhasil menggambarkan era Jazz Age dengan sempurna. Plot yang mendalam dan karakter Jay Gatsby yang kompleks membuat buku ini tak terlupakan.'
            ],
            [
                'user_id' => 2,
                'book_id' => 1,
                'rating' => 4,
                'comment' => 'Buku klasik yang layak dibaca. Cerita tentang American Dream yang hancur sangat relevan hingga saat ini.'
            ],
            [
                'user_id' => 3,
                'book_id' => 1,
                'rating' => 3,
                'comment' => 'Buku yang cukup baik, tapi beberapa bagian terasa lambat. Karakter Daisy kurang berkembang.'
            ],

            // Reviews untuk To Kill a Mockingbird
            [
                'user_id' => 4,
                'book_id' => 2,
                'rating' => 5,
                'comment' => 'Buku yang mengajarkan tentang keadilan dan toleransi. Atticus Finch adalah karakter yang menginspirasi.'
            ],
            [
                'user_id' => 5,
                'book_id' => 2,
                'rating' => 5,
                'comment' => 'Karya sastra yang powerful tentang rasisme dan ketidakadilan. Setiap orang harus membaca buku ini.'
            ],
            [
                'user_id' => 6,
                'book_id' => 2,
                'rating' => 4,
                'comment' => 'Cerita yang menyentuh hati dari perspektif anak-anak. Sangat relevan untuk dibaca di masa sekarang.'
            ],

            // Reviews untuk 1984
            [
                'user_id' => 7,
                'book_id' => 3,
                'rating' => 5,
                'comment' => 'Distopia yang menakutkan dan visioner. Orwell berhasil memprediksi banyak hal tentang totalitarianisme.'
            ],
            [
                'user_id' => 8,
                'book_id' => 3,
                'rating' => 4,
                'comment' => 'Buku yang membuat berpikir tentang kebebasan dan kontrol pemerintah. Sangat relevan di era digital.'
            ],
            [
                'user_id' => 1,
                'book_id' => 3,
                'rating' => 3,
                'comment' => 'Konsep yang menarik tapi eksekusinya agak membosankan di beberapa bagian.'
            ],

            // Reviews untuk Pride and Prejudice
            [
                'user_id' => 2,
                'book_id' => 4,
                'rating' => 5,
                'comment' => 'Romansa klasik yang elegan. Austen adalah master dalam menggambarkan karakter dan hubungan manusia.'
            ],
            [
                'user_id' => 3,
                'book_id' => 4,
                'rating' => 4,
                'comment' => 'Cerita cinta yang timeless. Elizabeth Bennet adalah karakter wanita yang kuat dan independen.'
            ],
            [
                'user_id' => 4,
                'book_id' => 4,
                'rating' => 2,
                'comment' => 'Terlalu banyak deskripsi dan dialog yang panjang. Sulit untuk tetap fokus.'
            ],

            // Reviews untuk The Hobbit
            [
                'user_id' => 5,
                'book_id' => 5,
                'rating' => 5,
                'comment' => 'Petualangan fantasi yang epik! Tolkien menciptakan dunia yang kaya dan karakter yang memikat.'
            ],
            [
                'user_id' => 6,
                'book_id' => 5,
                'rating' => 4,
                'comment' => 'Cerita yang menghibur untuk semua umur. Bilbo Baggins adalah karakter yang relatable.'
            ],
            [
                'user_id' => 7,
                'book_id' => 5,
                'rating' => 3,
                'comment' => 'Buku yang bagus tapi terlalu banyak deskripsi tentang perjalanan.'
            ],

            // Reviews untuk Lord of the Rings
            [
                'user_id' => 8,
                'book_id' => 6,
                'rating' => 5,
                'comment' => 'Mahakarya fantasi yang tak tertandingi. Tolkien adalah jenius dalam world-building.'
            ],
            [
                'user_id' => 1,
                'book_id' => 6,
                'rating' => 4,
                'comment' => 'Epik fantasi yang luar biasa, meskipun kadang terlalu detail.'
            ],
            [
                'user_id' => 2,
                'book_id' => 6,
                'rating' => 1,
                'comment' => 'Terlalu panjang dan membosankan. Sulit untuk menyelesaikan buku ini.'
            ],

            // Reviews untuk Harry Potter
            [
                'user_id' => 3,
                'book_id' => 7,
                'rating' => 5,
                'comment' => 'Buku yang membawa saya ke dunia sihir! Rowling menciptakan dunia yang ajaib dan karakter yang unforgettable.'
            ],
            [
                'user_id' => 4,
                'book_id' => 7,
                'rating' => 5,
                'comment' => 'Perfect untuk pembaca segala umur. Cerita yang menghibur dan penuh imajinasi.'
            ],
            [
                'user_id' => 5,
                'book_id' => 7,
                'rating' => 4,
                'comment' => 'Buku yang menyenangkan untuk dibaca. Karakter Harry, Ron, dan Hermione sangat memorable.'
            ],

            // Reviews untuk The Catcher in the Rye
            [
                'user_id' => 6,
                'book_id' => 8,
                'rating' => 4,
                'comment' => 'Buku yang menggambarkan kebingungan remaja dengan sangat baik. Holden Caulfield adalah karakter yang kompleks.'
            ],
            [
                'user_id' => 7,
                'book_id' => 8,
                'rating' => 3,
                'comment' => 'Cerita yang menarik tapi karakter utama kadang menjengkelkan.'
            ],
            [
                'user_id' => 8,
                'book_id' => 8,
                'rating' => 2,
                'comment' => 'Buku yang membingungkan dan tidak ada plot yang jelas.'
            ]
        ];

        // Insert all review records
        foreach ($reviews as $review) {
            Review::create($review);
        }

        $this->command->info('ReviewSeeder: Berhasil membuat ' . count($reviews) . ' data review sample.');
    }
} 