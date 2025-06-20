<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample user data dengan berbagai role
        $users = [
            // Staff users
            [
                'name' => 'Admin Perpustakaan',
                'email' => 'admin@perpustakaan.com',
                'password' => Hash::make('password123'),
                'role' => 'staff',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Librarian Jane',
                'email' => 'jane.librarian@perpustakaan.com',
                'password' => Hash::make('password123'),
                'role' => 'staff',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Staff Peminjaman',
                'email' => 'staff.peminjaman@perpustakaan.com',
                'password' => Hash::make('password123'),
                'role' => 'staff',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Manager Perpustakaan',
                'email' => 'manager@perpustakaan.com',
                'password' => Hash::make('password123'),
                'role' => 'staff',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Staff Katalog',
                'email' => 'katalog@perpustakaan.com',
                'password' => Hash::make('password123'),
                'role' => 'staff',
                'email_verified_at' => now(),
            ],

            // Regular users
            [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Bob Johnson',
                'email' => 'bob.johnson@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Alice Brown',
                'email' => 'alice.brown@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Charlie Wilson',
                'email' => 'charlie.wilson@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Diana Miller',
                'email' => 'diana.miller@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Edward Davis',
                'email' => 'edward.davis@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Fiona Garcia',
                'email' => 'fiona.garcia@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'George Martinez',
                'email' => 'george.martinez@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Helen Anderson',
                'email' => 'helen.anderson@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Ivan Taylor',
                'email' => 'ivan.taylor@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Julia White',
                'email' => 'julia.white@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Kevin Lee',
                'email' => 'kevin.lee@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Lisa Rodriguez',
                'email' => 'lisa.rodriguez@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Michael Thompson',
                'email' => 'michael.thompson@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Nancy Clark',
                'email' => 'nancy.clark@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Oliver Lewis',
                'email' => 'oliver.lewis@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Patricia Hall',
                'email' => 'patricia.hall@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Quentin Young',
                'email' => 'quentin.young@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Rachel King',
                'email' => 'rachel.king@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Samuel Wright',
                'email' => 'samuel.wright@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Tina Lopez',
                'email' => 'tina.lopez@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Ulysses Hill',
                'email' => 'ulysses.hill@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Victoria Scott',
                'email' => 'victoria.scott@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'William Green',
                'email' => 'william.green@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Xena Adams',
                'email' => 'xena.adams@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Yuki Baker',
                'email' => 'yuki.baker@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Zack Carter',
                'email' => 'zack.carter@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        ];

        // Insert all user records
        foreach ($users as $user) {
            User::create($user);
        }

        $this->command->info('UserSeeder: Berhasil membuat ' . count($users) . ' data user sample.');
        $this->command->info('Staff users: 5');
        $this->command->info('Regular users: ' . (count($users) - 5));
        $this->command->info('Password untuk semua user: password123');
    }
} 