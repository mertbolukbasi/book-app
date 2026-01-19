<?php

namespace App\Listeners;

use App\Events\BookCreated;
use App\Mail\BookCreatedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendCreatedNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BookCreated $event): void
    {
        $book = $event->book;
        $emails = $book->bookstores->pluck('email')->toArray();
        foreach ($emails as $email) {
            echo $email;
        }
        if (!empty($emails)) {
            foreach ($emails as $email) {
                Mail::to($email)->send(new BookCreatedMail($book));
            }
        }
    }
}
