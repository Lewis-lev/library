<?php

namespace App\Models;
use App\Models\Genres;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title',
        'author',
        'publisher',
        'quantity',
        'code',
        'image',
    ];

    public function genres()
{
    return $this->belongsToMany(Genre::class);
}

}
