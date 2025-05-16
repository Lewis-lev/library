<?php

namespace App\Models;
use App\Models\Genres;
use Illuminate\Database\Eloquent\Model;


class Book extends Model
{
    protected $primaryKey = 'book_id';

    protected $fillable = [
        'title',
        'author',
        'description',
        'publisher',
        'quantity',
        'code',
        'image',
    ];

    public function genres()
{
    return $this->belongsToMany(Genre::class , 'book_genre', 'book_id', 'genre_id');
}

}
