<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Category;
use App\Models\Publisher;
use App\Models\Resource;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::pluck('id', 'name');
        $publishers = Publisher::pluck('id', 'name');

        $books = [
            [
                'title' => 'To Kill a Mockingbird',
                'author' => 'Harper Lee',
                'category' => 'Fiction',
                'publisher' => 'HarperCollins',
                'isbn' => '9780061120084',
                'publication_year' => 1960,
                'pages' => 324,
                'description' => 'The unforgettable novel of a childhood in a sleepy Southern town and the crisis of conscience that rocked it.',
            ],
            [
                'title' => '1984',
                'author' => 'George Orwell',
                'category' => 'Fiction',
                'publisher' => 'Penguin Random House',
                'isbn' => '9780451524935',
                'publication_year' => 1949,
                'pages' => 328,
                'description' => 'Among the seminal texts of the 20th century, Nineteen Eighty-Four is a rare work that grows more haunting as its futuristic purgatory becomes more real.',
            ],
            [
                'title' => 'Pride and Prejudice',
                'author' => 'Jane Austen',
                'category' => 'Fiction',
                'publisher' => 'Penguin Random House',
                'isbn' => '9780141439518',
                'publication_year' => 1813,
                'pages' => 432,
                'description' => 'The romantic clash of two opinionated young people provides the sustaining theme of Pride and Prejudice.',
            ],
            [
                'title' => 'The Great Gatsby',
                'author' => 'F. Scott Fitzgerald',
                'category' => 'Fiction',
                'publisher' => 'Scribner',
                'isbn' => '9780743273565',
                'publication_year' => 1925,
                'pages' => 180,
                'description' => 'The story of the fabulously wealthy Jay Gatsby and his love for the beautiful Daisy Buchanan.',
            ],
            [
                'title' => 'The Catcher in the Rye',
                'author' => 'J.D. Salinger',
                'category' => 'Fiction',
                'publisher' => 'Little, Brown and Company',
                'isbn' => '9780316769488',
                'publication_year' => 1951,
                'pages' => 277,
                'description' => 'The hero-narrator of The Catcher in the Rye is an ancient child of sixteen, a native New Yorker named Holden Caulfield.',
            ],
            [
                'title' => 'Harry Potter and the Sorcerer\'s Stone',
                'author' => 'J.K. Rowling',
                'category' => 'Children',
                'publisher' => 'Bloomsbury Publishing',
                'isbn' => '9780590353427',
                'publication_year' => 1997,
                'pages' => 309,
                'description' => 'Harry Potter has never been the star of a Quidditch team, scoring points while riding a broom. He knows no spells, has never helped to hatch a dragon, and has never worn a cloak of invisibility.',
            ],
            [
                'title' => 'The Hobbit',
                'author' => 'J.R.R. Tolkien',
                'category' => 'Fiction',
                'publisher' => 'HarperCollins',
                'isbn' => '9780547928227',
                'publication_year' => 1937,
                'pages' => 300,
                'description' => 'Bilbo Baggins is a hobbit who enjoys a comfortable, unambitious life, rarely traveling further than the pantry of his hobbit-hole in Bag End.',
            ],
            [
                'title' => 'The Lord of the Rings',
                'author' => 'J.R.R. Tolkien',
                'category' => 'Fiction',
                'publisher' => 'HarperCollins',
                'isbn' => '9780544003415',
                'publication_year' => 1954,
                'pages' => 1178,
                'description' => 'One Ring to rule them all, One Ring to find them, One Ring to bring them all and in the darkness bind them.',
            ],
            [
                'title' => 'Brave New World',
                'author' => 'Aldous Huxley',
                'category' => 'Fiction',
                'publisher' => 'Harper Perennial',
                'isbn' => '9780060929879',
                'publication_year' => 1932,
                'pages' => 288,
                'description' => 'A fantasy of the future that sheds a blazing critical light on the present.',
            ],
            [
                'title' => 'The Chronicles of Narnia',
                'author' => 'C.S. Lewis',
                'category' => 'Children',
                'publisher' => 'HarperCollins',
                'isbn' => '9780066238500',
                'publication_year' => 1950,
                'pages' => 767,
                'description' => 'This collection includes all seven novels in the series.',
            ],
            [
                'title' => 'Clean Code',
                'author' => 'Robert C. Martin',
                'category' => 'Technology',
                'publisher' => 'Pearson Education',
                'isbn' => '9780132350884',
                'publication_year' => 2008,
                'pages' => 464,
                'description' => 'A handbook of agile software craftsmanship that shows how to write clean code.',
            ],
            [
                'title' => 'Introduction to Algorithms',
                'author' => 'Thomas H. Cormen',
                'category' => 'Technology',
                'publisher' => 'MIT Press',
                'isbn' => '9780262033848',
                'publication_year' => 2009,
                'pages' => 1312,
                'description' => 'The book covers a broad range of algorithms in depth.',
            ],
            [
                'title' => 'A Brief History of Time',
                'author' => 'Stephen Hawking',
                'category' => 'Science',
                'publisher' => 'Bantam',
                'isbn' => '9780553380163',
                'publication_year' => 1988,
                'pages' => 212,
                'description' => 'A landmark volume in science writing by one of the great minds of our time.',
            ],
            [
                'title' => 'The Art of War',
                'author' => 'Sun Tzu',
                'category' => 'Philosophy',
                'publisher' => 'Penguin Random House',
                'isbn' => '9780143037555',
                'publication_year' => -500,
                'pages' => 272,
                'description' => 'An ancient Chinese military treatise dating from the Late Spring and Autumn Period.',
            ],
            [
                'title' => 'Sapiens: A Brief History of Humankind',
                'author' => 'Yuval Noah Harari',
                'category' => 'History',
                'publisher' => 'HarperCollins',
                'isbn' => '9780062316097',
                'publication_year' => 2011,
                'pages' => 443,
                'description' => 'From a renowned historian comes a groundbreaking narrative of humanity\'s creation and evolution.',
            ],
            [
                'title' => 'The Diary of a Young Girl',
                'author' => 'Anne Frank',
                'category' => 'Biography',
                'publisher' => 'Penguin Random House',
                'isbn' => '9780307594009',
                'publication_year' => 1947,
                'pages' => 336,
                'description' => 'The diary of a young Jewish girl hiding from the Nazis during World War II.',
            ],
            [
                'title' => 'The Alchemist',
                'author' => 'Paulo Coelho',
                'category' => 'Fiction',
                'publisher' => 'HarperCollins',
                'isbn' => '9780062315007',
                'publication_year' => 1988,
                'pages' => 208,
                'description' => 'A magical story about Santiago, an Andalusian shepherd boy who yearns to travel in search of a worldly treasure.',
            ],
            [
                'title' => 'Thinking, Fast and Slow',
                'author' => 'Daniel Kahneman',
                'category' => 'Psychology',
                'publisher' => 'Farrar, Straus and Giroux',
                'isbn' => '9780374533557',
                'publication_year' => 2011,
                'pages' => 499,
                'description' => 'The major New York Times bestseller that explains the two systems that drive the way we think.',
            ],
            [
                'title' => 'The Lean Startup',
                'author' => 'Eric Ries',
                'category' => 'Business',
                'publisher' => 'Currency',
                'isbn' => '9780307887894',
                'publication_year' => 2011,
                'pages' => 336,
                'description' => 'How today\'s entrepreneurs use continuous innovation to create radically successful businesses.',
            ],
            [
                'title' => 'Dune',
                'author' => 'Frank Herbert',
                'category' => 'Fiction',
                'publisher' => 'Ace',
                'isbn' => '9780441172719',
                'publication_year' => 1965,
                'pages' => 688,
                'description' => 'Set in the distant future amidst a feudal interstellar society, Dune tells the story of young Paul Atreides.',
            ],
        ];

        foreach ($books as $bookData) {
            $author = Author::where('name', $bookData['author'])->first();
            $categoryId = $categories[$bookData['category']] ?? null;
            $publisherId = $publishers[$bookData['publisher']] ?? null;

            $slug = Str::slug($bookData['title']);

            $resource = Resource::firstOrCreate(
                ['slug' => $slug],
                [
                    'title' => $bookData['title'],
                    'isbn' => $bookData['isbn'],
                    'resource_type' => 'book',
                    'description' => $bookData['description'],
                    'category_id' => $categoryId,
                    'publisher_id' => $publisherId,
                    'publication_year' => $bookData['publication_year'],
                    'pages' => $bookData['pages'],
                    'language' => 'en',
                    'status' => 'available',
                ]
            );

            // Attach author to resource
            if ($author && $resource) {
                $resource->authors()->syncWithoutDetaching([$author->id]);
            }

            // Download cover image if not exists
            if ($resource && !$resource->cover_image) {
                $this->downloadCoverImage($resource);
            }
        }
    }

    /**
     * Download book cover image from free source.
     */
    private function downloadCoverImage(Resource $resource): void
    {
        try {
            // Use Google Books API for cover images
            $isbn = $resource->isbn;

            if ($isbn) {
                $response = Http::timeout(30)->get("https://www.googleapis.com/books/v1/volumes", [
                    'q' => "isbn:{$isbn}"
                ]);

                $data = $response->json();
                if ($response->successful() && isset($data['items'][0]['volumeInfo']['imageLinks']['thumbnail'])) {
                    $imageUrl = str_replace('&zoom=1', '', $data['items'][0]['volumeInfo']['imageLinks']['thumbnail']);

                    $imageResponse = Http::timeout(30)->get($imageUrl);

                    if ($imageResponse->successful()) {
                        $filename = "covers/{$resource->id}_" . strtolower(str_replace([' ', ':'], ['_', ''], $resource->title)) . '.jpg';
                        $path = public_path($filename);

                        $dir = dirname($path);
                        if (!is_dir($dir)) {
                            mkdir($dir, 0755, true);
                        }

                        file_put_contents($path, $imageResponse->body());
                        $resource->update(['cover_image' => $filename]);
                        return;
                    }
                }
            }

            // Fallback to picsum.photos if no cover found
            $width = 300;
            $height = 450;
            $seed = $resource->id;

            $response = Http::timeout(30)->get("https://picsum.photos/seed/{$seed}/{$width}/{$height}.jpg");

            if ($response->successful()) {
                $filename = "covers/{$resource->id}_" . strtolower(str_replace([' ', ':'], ['_', ''], $resource->title)) . '.jpg';
                $path = public_path($filename);

                $dir = dirname($path);
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }

                file_put_contents($path, $response->body());
                $resource->update(['cover_image' => $filename]);
            }
        } catch (\Exception $e) {
            logger()->error("Failed to download cover for resource {$resource->title}: " . $e->getMessage());
        }
    }
}
