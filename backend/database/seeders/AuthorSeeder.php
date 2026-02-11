<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $authors = [
            ['name' => 'William Shakespeare', 'bio' => 'English playwright and poet', 'birth_date' => '1564-04-23', 'nationality' => 'English'],
            ['name' => 'Jane Austen', 'bio' => 'English novelist known for social commentary', 'birth_date' => '1775-12-16', 'nationality' => 'English'],
            ['name' => 'Charles Dickens', 'bio' => 'English writer and social critic', 'birth_date' => '1812-02-07', 'nationality' => 'English'],
            ['name' => 'Mark Twain', 'bio' => 'American writer and humorist', 'birth_date' => '1835-11-30', 'nationality' => 'American'],
            ['name' => 'Virginia Woolf', 'bio' => 'English modernist writer', 'birth_date' => '1882-01-25', 'nationality' => 'English'],
            ['name' => 'Ernest Hemingway', 'bio' => 'American novelist and journalist', 'birth_date' => '1899-07-21', 'nationality' => 'American'],
            ['name' => 'George Orwell', 'bio' => 'English novelist and essayist', 'birth_date' => '1903-06-25', 'nationality' => 'English'],
            ['name' => 'J.K. Rowling', 'bio' => 'British author, best known for Harry Potter series', 'birth_date' => '1965-07-31', 'nationality' => 'British'],
            ['name' => 'Stephen King', 'bio' => 'American author of horror and supernatural fiction', 'birth_date' => '1947-09-21', 'nationality' => 'American'],
            ['name' => 'Agatha Christie', 'bio' => 'English writer of crime novels', 'birth_date' => '1890-09-15', 'nationality' => 'English'],
            ['name' => 'Leo Tolstoy', 'bio' => 'Russian writer and philosopher', 'birth_date' => '1828-09-09', 'nationality' => 'Russian'],
            ['name' => 'Fyodor Dostoevsky', 'bio' => 'Russian novelist and philosopher', 'birth_date' => '1821-11-11', 'nationality' => 'Russian'],
            ['name' => 'Gabriel García Márquez', 'bio' => 'Colombian novelist and Nobel laureate', 'birth_date' => '1927-03-06', 'nationality' => 'Colombian'],
            ['name' => 'Haruki Murakami', 'bio' => 'Japanese writer and translator', 'birth_date' => '1949-01-12', 'nationality' => 'Japanese'],
            ['name' => 'Toni Morrison', 'bio' => 'American novelist and Nobel laureate', 'birth_date' => '1931-02-18', 'nationality' => 'American'],
            ['name' => 'Maya Angelou', 'bio' => 'American poet and civil rights activist', 'birth_date' => '1928-04-04', 'nationality' => 'American'],
            ['name' => 'Isabel Allende', 'bio' => 'Chilean-American writer', 'birth_date' => '1942-08-02', 'nationality' => 'Chilean-American'],
            ['name' => 'Harper Lee', 'bio' => 'American novelist', 'birth_date' => '1926-04-28', 'nationality' => 'American'],
            ['name' => 'J.R.R. Tolkien', 'bio' => 'English writer and philologist', 'birth_date' => '1892-01-03', 'nationality' => 'English'],
            ['name' => 'C.S. Lewis', 'bio' => 'British writer and theologian', 'birth_date' => '1898-11-29', 'nationality' => 'British'],
        ];

        foreach ($authors as $authorData) {
            Author::firstOrCreate(
                ['slug' => Str::slug($authorData['name'])],
                array_merge($authorData, ['slug' => Str::slug($authorData['name'])])
            );
        }
    }
}
