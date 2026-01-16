<!DOCTYPE html>
<html>
<head>
    <title>New Book Added!</title>
</head>
<body>
    <h1>Your book has been added</h1>
    <p><strong>Book name:</strong> {{ $book->book_name }}</p>
    <p><strong>ISBN:</strong> {{ $book->isbn }}</p>
    <p>Successfully saved.</p>
</body>
</html>
