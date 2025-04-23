<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    protected $primaryKey = 'genre_id';


    protected $fillable = ['book_id', 'name'];

    public function books()
    {
        return $this->belongsToMany(Book::class , 'book_genre', 'genre_id', 'book_id');
    }
}
