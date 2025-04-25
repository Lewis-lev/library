<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    protected $primaryKey = 'borrow_id';

    protected $fillable = [
        'user_id',
        'book_id',
        'status',
    ];

}
