<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Models\User;
use App\Models\Book;

class BookBorrowed implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $user;
    public $book;

    public function __construct(User $user, Book $book)
    {
        $this->user = $user;
        $this->book = $book;
    }

    public function broadcastOn()
    {
        return new Channel('admin-channel');
    }

    public function broadcastAs()
    {
        return 'BookBorrowed';
    }
}
