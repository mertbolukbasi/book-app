<?php

namespace App\Listeners;

use App\Events\BookDeleted;
use App\Mail\BookDeletedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendDeletedNotification
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
    public function handle(BookDeleted $event): void
    {
        $book = $event->book;
        $emails = $event->emails;

        if (!empty($emails)) {
            foreach ($emails as $email) {
                Mail::to($email)->send(new BookDeletedMail($book));
            }
        }
    }
}
