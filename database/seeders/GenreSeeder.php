<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Genre;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $genres = [
            ['name' => 'Romance'],
            ['name' => 'Fantasy'],
            ['name' => 'Horror'],
            ['name' => 'Science Fiction'],
            ['name' => 'Comedy'],
            ['name' => 'Psychology'],
            ['name' => 'Thriller'],
            ['name' => 'History'],
            ['name' => 'History Fiction'],
            ['name' => 'Mystery'],
            ['name' => 'Biography'],
            ['name' => 'Adventure'],
            ['name' => 'Fiction'],
            ['name' => 'Children'],
            ['name' => 'Adult'],
            ['name' => 'Non-Fiction'],
        ];

        DB::table('genres')->insert($genres);
    }
}
