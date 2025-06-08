<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Fiction', 'slug' => 'fiction', 'description' => 'Buku fiksi dan novel'],
            ['name' => 'Non-Fiction', 'slug' => 'non-fiction', 'description' => 'Buku non-fiksi'],
            ['name' => 'Science & Technology', 'slug' => 'science-technology', 'description' => 'Buku sains dan teknologi'],
            ['name' => 'History', 'slug' => 'history', 'description' => 'Buku sejarah'],
            ['name' => 'Biography', 'slug' => 'biography', 'description' => 'Buku biografi'],
        ];

        foreach ($categories as $category) {
            // Gunakan updateOrCreate untuk menghindari duplikasi
            Category::updateOrCreate(
                ['slug' => $category['slug']], // Kondisi pencarian
                $category // Data yang akan di-update atau create
            );
        }
    }
}
