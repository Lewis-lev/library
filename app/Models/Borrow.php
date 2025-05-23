<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    protected $primaryKey = 'borrow_id';

    protected $fillable = [
        'user_id',
        'book_id',
        'borrow_code',
        'borrow_duration',
        'return_date',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id', 'book_id');
    }

}
