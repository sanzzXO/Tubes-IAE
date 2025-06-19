<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Borrowing;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Call the BorrowingSeeder to create sample borrowing data
        $this->call([
            BorrowingSeeder::class,
        ]);

        // Optionally create additional random borrowing records for testing
        // Uncomment the line below if you want more test data
        // Borrowing::factory(20)->create();
    }
}
