<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Book::create([
            'author' => 'author 1',
            'title' => 'Example Book 1',
            'genre' => 'Trending',
            'quantity' => '10',
            'image' => 'hutao13',
            'status' => 'Available'
        ]);

        Book::create([
            'author' => 'author 1',
            'title' => 'Example Book 3',
            'genre' => 'Trending',
            'quantity' => '10',
            'image' => 'hutao14',
            'status' => 'Available'
        ]);

        Book::create([
            'author' => 'author 1',
            'title' => 'Example Book 4',
            'genre' => 'Trending',
            'quantity' => '10',
            'image' => 'hutao12',
            'status' => 'Available'
        ]);

        Book::create([
            'author' => 'author 2',
            'title' => 'Example Book 2',
            'genre' => 'Classic',
            'quantity' => '10',
            'image' => 'hutao11',
            'status' => 'Checked Out'
        ]);
        Book::create([
            'author' => 'author 2',
            'title' => 'Example Book 2',
            'genre' => 'Classic',
            'quantity' => '10',
            'image' => 'hutao11',
            'status' => 'Checked Out'
        ]);
        Book::create([
            'author' => 'author 2',
            'title' => 'Example Book 2',
            'genre' => 'Classic',
            'quantity' => '10',
            'image' => 'hutao19',
            'status' => 'Checked Out'
        ]);
        Book::create([
            'author' => 'author 2',
            'title' => 'Example Book 2',
            'genre' => 'Classic',
            'quantity' => '10',
            'image' => 'hutao18',
            'status' => 'Checked Out'
        ]);
        Book::create([
            'author' => 'author 2',
            'title' => 'Example Book 2',
            'genre' => 'Classic',
            'quantity' => '17',
            'image' => 'hutao16',
            'status' => 'Checked Out'
        ]);
        Book::create([
            'author' => 'author 2',
            'title' => 'Example Book 2',
            'genre' => 'Classic',
            'quantity' => '15',
            'image' => 'hutao15',
            'status' => 'Checked Out'
        ]);
        Book::create([
            'author' => 'author 2',
            'title' => 'Example Book 2',
            'genre' => 'Classic',
            'quantity' => '10',
            'image' => 'hutao17',
            'status' => 'Checked Out'
        ]);
    }
}
