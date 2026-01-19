<?php

namespace App\Listeners;

use App\Events\BookUpdated;
use App\Mail\BookCreatedMail;
use App\Mail\BookDeletedMail;
use App\Models\Bookstore;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendUpdatedNotification implements ShouldQueue
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
    public function handle(BookUpdated $event): void
    {
        $book = $event->book;

        $removedIds = array_diff($event->oldStoreIds, $event->newStoreIds);
        $addedIds = array_diff($event->newStoreIds, $event->oldStoreIds);

        if (! empty($removedIds)) {
            $removedStores = Bookstore::whereIn('id', $removedIds)->get();

            foreach ($removedStores as $store) {
                Mail::to($store->email)->send(new BookDeletedMail($book));
            }
        }

        if (! empty($addedIds)) {
            $addedStores = Bookstore::whereIn('id', $addedIds)->get();

            foreach ($addedStores as $store) {
                Mail::to($store->email)->send(new BookCreatedMail($book));
            }
        }
    }
}
