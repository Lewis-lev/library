<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;

class BookBorrowedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $user;
    public $book;

    /**
     * Create a new notification instance.
     */
    public function __construct($user, $book)
    {
        $this->user = $user;
        $this->book = $book;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['database']; // or ['mail', 'database'] if you want to send emails too
    }

    /**
     * Get the array representation of the notification for storage in the database.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->user->name . ' requested to borrow "' . $this->book->title . '".',
            'user_id' => $this->user->user_id,
            'book_id' => $this->book->book_id,
            'book_title' => $this->book->title,
        ];
    }
}
