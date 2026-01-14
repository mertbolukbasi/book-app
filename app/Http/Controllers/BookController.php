<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


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
            'isbn' => ['required', 'unique:books', 'regex:/^[0-9-]{10,17}$/'],
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
            'book_name' => ['required', Rule::unique('books', 'book_name')->ignore($book->id)],
            'author' => 'required',
            'image' => 'image|mimes:jpg,png,jpeg|max:1024', // max 1gb, default jpg, jpeg, png, bmp, gif, or webp
            'isbn' => ['required', Rule::unique('books', 'isbn')->ignore($book->id), 'regex:/^[0-9-]{10,17}$/'],
        ]);

        $update_book = $request->except(['image']);

        if($request->hasfile('image')) {
            $update_book['image'] = $request->file('image')->store('images', 'public');
        } else {
            $update_book['image'] = null;
        }

        $book->update($update_book);
        return redirect()->route('list');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('list');
    }
}
