<?php

namespace Database\Seeders;

use App\Models\Borrowing;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BorrowingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample book data (simulating data from book-catalog-service)
        $books = [
            [
                'id' => 'BK001',
                'title' => 'The Great Gatsby',
                'isbn' => '978-0743273565'
            ],
            [
                'id' => 'BK002',
                'title' => 'To Kill a Mockingbird',
                'isbn' => '978-0446310789'
            ],
            [
                'id' => 'BK003',
                'title' => '1984',
                'isbn' => '978-0451524935'
            ],
            [
                'id' => 'BK004',
                'title' => 'Pride and Prejudice',
                'isbn' => '978-0141439518'
            ],
            [
                'id' => 'BK005',
                'title' => 'The Hobbit',
                'isbn' => '978-0547928241'
            ]
        ];

        // Sample user data (simulating data from auth-service)
        $users = [
            [
                'id' => 'USR001',
                'name' => 'John Doe',
                'email' => 'john.doe@example.com'
            ],
            [
                'id' => 'USR002',
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com'
            ],
            [
                'id' => 'USR003',
                'name' => 'Bob Johnson',
                'email' => 'bob.johnson@example.com'
            ],
            [
                'id' => 'USR004',
                'name' => 'Alice Brown',
                'email' => 'alice.brown@example.com'
            ],
            [
                'id' => 'USR005',
                'name' => 'Charlie Wilson',
                'email' => 'charlie.wilson@example.com'
            ]
        ];

        // Create sample borrowing records
        $borrowings = [
            // Currently borrowed books
            [
                'user_id' => 'USR001',
                'book_id' => 'BK001',
                'isbn' => '978-0743273565',
                'book_title' => 'The Great Gatsby',
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'borrowed_date' => Carbon::now()->subDays(5),
                'due_date' => Carbon::now()->addDays(9),
                'returned_date' => null,
                'status' => 'borrowed',
                'fine_amount' => 0.00,
                'extension_count' => 0,
                'notes' => 'First time borrowing this book'
            ],
            [
                'user_id' => 'USR002',
                'book_id' => 'BK002',
                'isbn' => '978-0446310789',
                'book_title' => 'To Kill a Mockingbird',
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'borrowed_date' => Carbon::now()->subDays(3),
                'due_date' => Carbon::now()->addDays(11),
                'returned_date' => null,
                'status' => 'borrowed',
                'fine_amount' => 0.00,
                'extension_count' => 1,
                'notes' => 'Extended once due to exam period'
            ],
            [
                'user_id' => 'USR003',
                'book_id' => 'BK003',
                'isbn' => '978-0451524935',
                'book_title' => '1984',
                'name' => 'Bob Johnson',
                'email' => 'bob.johnson@example.com',
                'borrowed_date' => Carbon::now()->subDays(1),
                'due_date' => Carbon::now()->addDays(13),
                'returned_date' => null,
                'status' => 'borrowed',
                'fine_amount' => 0.00,
                'extension_count' => 0,
                'notes' => null
            ],

            // Overdue books
            [
                'user_id' => 'USR004',
                'book_id' => 'BK004',
                'isbn' => '978-0141439518',
                'book_title' => 'Pride and Prejudice',
                'name' => 'Alice Brown',
                'email' => 'alice.brown@example.com',
                'borrowed_date' => Carbon::now()->subDays(20),
                'due_date' => Carbon::now()->subDays(3),
                'returned_date' => null,
                'status' => 'overdue',
                'fine_amount' => 3000.00,
                'extension_count' => 2,
                'notes' => 'Multiple extensions requested'
            ],
            [
                'user_id' => 'USR005',
                'book_id' => 'BK005',
                'isbn' => '978-0547928241',
                'book_title' => 'The Hobbit',
                'name' => 'Charlie Wilson',
                'email' => 'charlie.wilson@example.com',
                'borrowed_date' => Carbon::now()->subDays(25),
                'due_date' => Carbon::now()->subDays(8),
                'returned_date' => null,
                'status' => 'overdue',
                'fine_amount' => 8000.00,
                'extension_count' => 0,
                'notes' => 'No response to overdue notifications'
            ],

            // Returned books
            [
                'user_id' => 'USR001',
                'book_id' => 'BK002',
                'isbn' => '978-0446310789',
                'book_title' => 'To Kill a Mockingbird',
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'borrowed_date' => Carbon::now()->subDays(30),
                'due_date' => Carbon::now()->subDays(16),
                'returned_date' => Carbon::now()->subDays(15),
                'status' => 'returned',
                'fine_amount' => 1000.00,
                'extension_count' => 0,
                'notes' => 'Returned with minor damage'
            ],
            [
                'user_id' => 'USR002',
                'book_id' => 'BK003',
                'isbn' => '978-0451524935',
                'book_title' => '1984',
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'borrowed_date' => Carbon::now()->subDays(35),
                'due_date' => Carbon::now()->subDays(21),
                'returned_date' => Carbon::now()->subDays(20),
                'status' => 'returned',
                'fine_amount' => 0.00,
                'extension_count' => 1,
                'notes' => 'Returned in good condition'
            ],
            [
                'user_id' => 'USR003',
                'book_id' => 'BK001',
                'isbn' => '978-0743273565',
                'book_title' => 'The Great Gatsby',
                'name' => 'Bob Johnson',
                'email' => 'bob.johnson@example.com',
                'borrowed_date' => Carbon::now()->subDays(40),
                'due_date' => Carbon::now()->subDays(26),
                'returned_date' => Carbon::now()->subDays(25),
                'status' => 'returned',
                'fine_amount' => 0.00,
                'extension_count' => 0,
                'notes' => 'Returned on time'
            ]
        ];

        // Insert all borrowing records
        foreach ($borrowings as $borrowing) {
            Borrowing::create($borrowing);
        }

        $this->command->info('BorrowingSeeder: Created ' . count($borrowings) . ' sample borrowing records.');
    }
} 