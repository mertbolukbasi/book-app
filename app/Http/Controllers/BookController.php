<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;


// php artisan make:controller BookController --resource --model=Book
class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::all();
        return view('index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'book_name' => ['required', 'unique:books'],
            'author' => 'required',
            'image' => 'image|mimes:jpg,png,jpeg|max:10240', // max 10gb, default jpg, jpeg, png, bmp, gif, or webp
            'isbn' => 'required|unique:books',
        ]);

        $book = new Book();
        $book->book_name = $request->book_name;
        $book->author = $request->author;
        $book->isbn = $request->isbn;

        if($request->hasfile('image')) {
            $image_path = $request->file('image')->store('images', 'public');
            $book->image = $image_path;
        }

        $book->save();
        // TODO: Add route to book list menu.
        return redirect()->route('list');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        return view('show', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        return view('edit', compact('book'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $request->validate([
            'book_name' => ['required', 'unique:books'],
            'author' => 'required',
            'image' => 'image|mimes:jpg,png,jpeg|max:10240', // max 10gb, default jpg, jpeg, png, bmp, gif, or webp
            'isbn' => 'required|unique:books',
        ]);

        $update_book = $request->all();

        if($request->hasfile('image')) {
            $image_path = $request->file('image')->store('images', 'public');
        }

        $book->update($update_book);
        // TODO: Add route to book list menu.
        return redirect()->route('list');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        // TODO: To delete add delete button to book card.
        $book->delete();
    }
}
