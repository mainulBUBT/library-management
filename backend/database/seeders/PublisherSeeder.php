<?php

namespace Database\Seeders;

use App\Models\Publisher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PublisherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $publishers = [
            ['name' => 'Penguin Random House', 'website_url' => 'https://www.penguinrandomhouse.com'],
            ['name' => 'HarperCollins', 'website_url' => 'https://www.harpercollins.com'],
            ['name' => 'Macmillan Publishers', 'website_url' => 'https://www.macmillan.com'],
            ['name' => 'Simon & Schuster', 'website_url' => 'https://www.simonandschuster.com'],
            ['name' => 'Hachette Book Group', 'website_url' => 'https://www.hachettebookgroup.com'],
            ['name' => 'Oxford University Press', 'website_url' => 'https://global.oup.com'],
            ['name' => 'Cambridge University Press', 'website_url' => 'https://www.cambridge.org'],
            ['name' => 'Elsevier', 'website_url' => 'https://www.elsevier.com'],
            ['name' => 'Springer Nature', 'website_url' => 'https://www.springernature.com'],
            ['name' => 'Pearson Education', 'website_url' => 'https://www.pearson.com'],
            ['name' => 'Wiley', 'website_url' => 'https://www.wiley.com'],
            ['name' => 'Bloomsbury Publishing', 'website_url' => 'https://www.bloomsbury.com'],
            ['name' => 'Scribner', 'website_url' => 'https://www.scribnerbooks.com'],
            ['name' => 'Little, Brown and Company', 'website_url' => 'https://www.littlebrown.com'],
            ['name' => 'Harper Perennial', 'website_url' => 'https://www.harpercollins.com/pages/harper-perennial'],
            ['name' => 'MIT Press', 'website_url' => 'https://mitpress.mit.edu'],
            ['name' => 'Bantam', 'website_url' => 'https://www.penguinrandomhouse.com/imprints/3H/bantam'],
            ['name' => 'Farrar, Straus and Giroux', 'website_url' => 'https://us.macmillan.com/farrarstrausgiroux'],
            ['name' => 'Currency', 'website_url' => 'https://www.penguinrandomhouse.com/imprints/C6/currency'],
            ['name' => 'Ace', 'website_url' => 'https://www.penguinrandomhouse.com/imprints/AC/ace'],
        ];

        foreach ($publishers as $publisher) {
            Publisher::firstOrCreate(
                ['slug' => Str::slug($publisher['name'])],
                array_merge($publisher, ['slug' => Str::slug($publisher['name'])])
            );
        }
    }
}
