<?php

use App\Mail\BookCreatedMail;
use Illuminate\Support\Facades\Mail;
use Database\Factories\BookFactory;

test('Test mail', function () {

    Mail::fake();
    $book = BookFactory::new()->create();
    Mail::to('test@example.com')->send(new BookCreatedMail($book));
    Mail::assertSent(BookCreatedMail::class);
});
