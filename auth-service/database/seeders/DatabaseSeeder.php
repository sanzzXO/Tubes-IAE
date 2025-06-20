<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil UserSeeder untuk membuat data user sample
        $this->call([
            UserSeeder::class,
        ]);

        // Opsional: Buat data user tambahan untuk testing
        // Uncomment baris di bawah jika ingin data user yang lebih banyak
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
