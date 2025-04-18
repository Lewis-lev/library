<?php

namespace App\Models;
use App\Models\Book;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    //
    public function books()
{
    return $this->belongsToMany(Book::class);
}


}
