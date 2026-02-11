<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Fiction', 'description' => 'Fictional literature and novels', 'slug' => 'fiction'],
            ['name' => 'Non-Fiction', 'description' => 'Non-fictional and factual books', 'slug' => 'non-fiction'],
            ['name' => 'Science', 'description' => 'Scientific books and research materials', 'slug' => 'science'],
            ['name' => 'Technology', 'description' => 'Technology and computing books', 'slug' => 'technology'],
            ['name' => 'History', 'description' => 'Historical books and documents', 'slug' => 'history'],
            ['name' => 'Biography', 'description' => 'Biographies and autobiographies', 'slug' => 'biography'],
            ['name' => 'Philosophy', 'description' => 'Philosophy and ethics', 'slug' => 'philosophy'],
            ['name' => 'Psychology', 'description' => 'Psychology and mental health', 'slug' => 'psychology'],
            ['name' => 'Business', 'description' => 'Business and economics', 'slug' => 'business'],
            ['name' => 'Education', 'description' => 'Educational materials', 'slug' => 'education'],
            ['name' => 'Literature', 'description' => 'Classic and modern literature', 'slug' => 'literature'],
            ['name' => 'Children', 'description' => 'Children\'s books', 'slug' => 'children'],
            ['name' => 'Reference', 'description' => 'Reference and dictionaries', 'slug' => 'reference'],
            ['name' => 'Arts', 'description' => 'Arts, music and design', 'slug' => 'arts'],
            ['name' => 'Health', 'description' => 'Health and medical books', 'slug' => 'health'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
