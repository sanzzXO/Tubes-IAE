<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Review;
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

        // Panggil ReviewSeeder untuk membuat data review sample
        $this->call([
            ReviewSeeder::class,
        ]);

        // Opsional: Buat data review tambahan untuk testing
        // Uncomment baris di bawah jika ingin data review yang lebih banyak
        // Review::factory(15)->create();
    }
}
