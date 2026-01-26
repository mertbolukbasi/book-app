<?php

use App\Mail\BookCreatedMail;
use Database\Factories\BookFactory;
use Illuminate\Support\Facades\Mail;

test('Test mail', function () {

    Mail::fake();
    $book = BookFactory::new()->create();
    Mail::to('test@example.com')->send(new BookCreatedMail($book));
    Mail::assertSent(BookCreatedMail::class);
});
