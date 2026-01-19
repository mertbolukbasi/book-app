<?php

namespace App\Providers;

use App\Events\BookCreated;
use App\Events\BookDeleted;
use App\Events\BookUpdated;
use App\Listeners\SendCreatedNotification;
use App\Listeners\SendDeletedNotification;
use App\Listeners\SendUpdatedNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(
            BookCreated::class,
            SendCreatedNotification::class
        );

        Event::listen(
            BookUpdated::class,
            SendUpdatedNotification::class
        );

        Event::listen(
            BookDeleted::class,
            SendDeletedNotification::class
        );

    }
}
