<!DOCTYPE html>
<html>
<head>
    <title>{{ $book->name }} is on sale!</title>
</head>
<body>
    <h1>Your book is on sale.</h1>
    <p><strong>Book name:</strong> {{ $book->name }}</p>
    <p><strong>ISBN:</strong> {{ $book->isbn }}</p>
</body>
</html>
