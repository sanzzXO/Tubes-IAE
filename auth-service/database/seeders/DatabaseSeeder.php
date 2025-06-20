<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        echo "🔄 Starting user seeding...\n";
        
        // Hapus user lama
        User::query()->delete();
        echo "🗑️ Old users deleted\n";
        
 
        $users = [
            [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => 'password',
                'role' => 'user'
            ],
            [
                'name' => 'Staff Perpustakaan',
                'email' => 'staff@perpustakaan.com',
                'password' => 'staff123',
                'role' => 'staff'
            ],
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => '12345678',
                'role' => 'user'
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'password' => 'password123',
                'role' => 'user'
            ]
        ];

        foreach ($users as $userData) {
            User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']),
                'role' => $userData['role'],
                'email_verified_at' => now()
            ]);
            
            echo "✅ User created: {$userData['email']} (password: {$userData['password']}) [role: {$userData['role']}]\n";
        }
        
        echo "\n🎉 User seeding completed!\n";
        echo "📊 Total users: " . User::count() . "\n\n";
        
        echo "🔑 Login credentials:\n";
        echo "   Email: test@example.com | Password: password (role: user)\n";
        echo "   Email: admin@perpustakaan.com | Password: admin123 (role: staff)\n";
        echo "   Email: john@example.com | Password: 12345678 (role: user)\n";
    }
}