<?php

namespace Database\Factories;

use App\Models\Borrowing;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Borrowing>
 */
class BorrowingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $borrowedDate = $this->faker->dateTimeBetween('-30 days', 'now');
        $dueDate = Carbon::parse($borrowedDate)->addDays(14); // 14 days loan period
        $status = $this->faker->randomElement(['borrowed', 'returned', 'overdue']);
        
        // Determine returned date based on status
        $returnedDate = null;
        $fineAmount = 0.00;
        
        if ($status === 'returned') {
            $returnedDate = $this->faker->dateTimeBetween($borrowedDate, $dueDate);
            // Calculate fine if returned after due date
            if (Carbon::parse($returnedDate) > $dueDate) {
                $overdueDays = Carbon::parse($returnedDate)->diffInDays($dueDate);
                $fineAmount = $overdueDays * 1000; // 1000 per day fine
            }
        } elseif ($status === 'overdue') {
            $fineAmount = Carbon::now()->diffInDays($dueDate) * 1000;
        }

        // Sample books for variety
        $books = [
            ['id' => 'BK001', 'title' => 'The Great Gatsby', 'isbn' => '978-0743273565'],
            ['id' => 'BK002', 'title' => 'To Kill a Mockingbird', 'isbn' => '978-0446310789'],
            ['id' => 'BK003', 'title' => '1984', 'isbn' => '978-0451524935'],
            ['id' => 'BK004', 'title' => 'Pride and Prejudice', 'isbn' => '978-0141439518'],
            ['id' => 'BK005', 'title' => 'The Hobbit', 'isbn' => '978-0547928241'],
            ['id' => 'BK006', 'title' => 'Lord of the Rings', 'isbn' => '978-0547928210'],
            ['id' => 'BK007', 'title' => 'Harry Potter', 'isbn' => '978-0439708180'],
            ['id' => 'BK008', 'title' => 'The Catcher in the Rye', 'isbn' => '978-0316769488'],
        ];

        $selectedBook = $this->faker->randomElement($books);

        return [
            'user_id' => 'USR' . str_pad($this->faker->numberBetween(1, 100), 3, '0', STR_PAD_LEFT),
            'book_id' => $selectedBook['id'],
            'isbn' => $selectedBook['isbn'],
            'book_title' => $selectedBook['title'],
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'borrowed_date' => $borrowedDate,
            'due_date' => $dueDate,
            'returned_date' => $returnedDate,
            'status' => $status,
            'fine_amount' => $fineAmount,
            'extension_count' => $this->faker->numberBetween(0, 3),
            'notes' => $this->faker->optional(0.3)->sentence(),
        ];
    }

    /**
     * Indicate that the borrowing is currently active (borrowed status).
     */
    public function borrowed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'borrowed',
            'returned_date' => null,
            'fine_amount' => 0.00,
        ]);
    }

    /**
     * Indicate that the borrowing is overdue.
     */
    public function overdue(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'overdue',
            'returned_date' => null,
            'due_date' => Carbon::now()->subDays($this->faker->numberBetween(1, 10)),
            'fine_amount' => $this->faker->numberBetween(1000, 10000),
        ]);
    }

    /**
     * Indicate that the borrowing has been returned.
     */
    public function returned(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'returned',
            'returned_date' => $this->faker->dateTimeBetween($attributes['borrowed_date'], $attributes['due_date']),
        ]);
    }
}
