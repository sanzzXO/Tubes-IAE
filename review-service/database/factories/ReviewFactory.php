<?php

namespace Database\Factories;

use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $rating = $this->faker->numberBetween(1, 5);
        
        // Komentar berdasarkan rating
        $comments = [
            5 => [
                'Karya sastra yang luar biasa! Plot yang mendalam dan karakter yang kompleks.',
                'Buku ini benar-benar mengubah cara pandang saya tentang kehidupan.',
                'Salah satu buku terbaik yang pernah saya baca. Sangat direkomendasikan!',
                'Penulisan yang elegan dan cerita yang memukau dari awal sampai akhir.',
                'Klasik yang tak lekang oleh waktu. Setiap halaman penuh makna.',
                'Karakter yang unforgettable dan cerita yang powerful.',
                'Buku yang wajib dibaca oleh semua orang.',
                'Masterpiece yang tidak akan terlupakan.'
            ],
            4 => [
                'Buku yang sangat bagus dengan karakter yang menarik.',
                'Plot yang solid dan penulisan yang mengalir dengan baik.',
                'Membaca buku ini adalah pengalaman yang menyenangkan.',
                'Cerita yang menghibur dengan beberapa twist yang mengejutkan.',
                'Karya yang layak dibaca, meskipun ada beberapa bagian yang lambat.',
                'Karakter yang relatable dan cerita yang engaging.',
                'Buku yang memberikan insight yang berharga.',
                'Penulisan yang smooth dan mudah diikuti.'
            ],
            3 => [
                'Buku yang cukup baik, tapi tidak istimewa.',
                'Cerita lumayan menarik, tapi ada beberapa bagian yang membosankan.',
                'Plot yang standar, tidak ada yang mengejutkan.',
                'Buku yang bisa dibaca untuk mengisi waktu luang.',
                'Karakter yang kurang berkembang, tapi cerita masih bisa diikuti.',
                'Buku yang biasa saja, tidak buruk tapi juga tidak luar biasa.',
                'Cerita yang predictable tapi masih menghibur.',
                'Buku yang cukup untuk menghabiskan waktu.'
            ],
            2 => [
                'Buku ini kurang memuaskan, plot yang tidak jelas.',
                'Karakter yang datar dan cerita yang membingungkan.',
                'Sulit untuk menyelesaikan buku ini karena membosankan.',
                'Penulisan yang kurang menarik dan plot yang lemah.',
                'Tidak sesuai dengan ekspektasi saya.',
                'Buku yang mengecewakan, tidak direkomendasikan.',
                'Cerita yang tidak masuk akal dan karakter yang menjengkelkan.',
                'Waktu yang terbuang untuk membaca buku ini.'
            ],
            1 => [
                'Buku yang sangat mengecewakan, tidak direkomendasikan.',
                'Sulit untuk menemukan hal positif dari buku ini.',
                'Waktu yang terbuang untuk membaca buku ini.',
                'Plot yang tidak masuk akal dan karakter yang menjengkelkan.',
                'Salah satu buku terburuk yang pernah saya baca.',
                'Buku yang tidak layak dibaca sama sekali.',
                'Cerita yang membingungkan dan tidak ada maknanya.',
                'Karya yang gagal total dalam segala aspek.'
            ]
        ];

        // Sample buku untuk variasi
        $books = [
            ['id' => 1, 'title' => 'The Great Gatsby'],
            ['id' => 2, 'title' => 'To Kill a Mockingbird'],
            ['id' => 3, 'title' => '1984'],
            ['id' => 4, 'title' => 'Pride and Prejudice'],
            ['id' => 5, 'title' => 'The Hobbit'],
            ['id' => 6, 'title' => 'Lord of the Rings'],
            ['id' => 7, 'title' => 'Harry Potter and the Sorcerer\'s Stone'],
            ['id' => 8, 'title' => 'The Catcher in the Rye'],
            ['id' => 9, 'title' => 'Animal Farm'],
            ['id' => 10, 'title' => 'Brave New World'],
            ['id' => 11, 'title' => 'The Alchemist'],
            ['id' => 12, 'title' => 'The Little Prince'],
            ['id' => 13, 'title' => 'The Kite Runner'],
            ['id' => 14, 'title' => 'A Thousand Splendid Suns'],
            ['id' => 15, 'title' => 'The Book Thief']
        ];

        $selectedBook = $this->faker->randomElement($books);
        $comment = $this->faker->randomElement($comments[$rating]);

        return [
            'user_id' => $this->faker->numberBetween(1, 50),
            'book_id' => $selectedBook['id'],
            'rating' => $rating,
            'comment' => $comment,
        ];
    }

    /**
     * Indicate that the review has a high rating (4-5 stars).
     */
    public function positive(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => $this->faker->randomElement([4, 5]),
        ]);
    }

    /**
     * Indicate that the review has a low rating (1-2 stars).
     */
    public function negative(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => $this->faker->randomElement([1, 2]),
        ]);
    }

    /**
     * Indicate that the review has a neutral rating (3 stars).
     */
    public function neutral(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => 3,
        ]);
    }

    /**
     * Indicate that the review is for a specific book.
     */
    public function forBook(int $bookId): static
    {
        return $this->state(fn (array $attributes) => [
            'book_id' => $bookId,
        ]);
    }

    /**
     * Indicate that the review is from a specific user.
     */
    public function byUser(int $userId): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $userId,
        ]);
    }
} 