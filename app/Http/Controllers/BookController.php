<?php

namespace App\Http\Controllers;

use App\Mail\BookCreatedMail;
use App\Http\Requests\StoreAuthorRequest;
use App\Mail\BookDeletedMail;
use App\Models\Author;
use App\Models\Book;
use App\Models\Bookstore;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Storage;

// php artisan make:controller BookController --resource --model=Book
class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::with(['author', 'bookstores'])->get();
        return view('index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bookstores = Bookstore::all();
        $authors = Author::all();
        return view('create', compact('bookstores', 'authors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAuthorRequest $request)
    {
        $request->validate([
            'book_name' => ['required', 'unique:books', 'max:255'],
            'bookstores' => 'array',
            'image' => 'image|mimes:jpg,png,jpeg|max:10240', // max 10gb, default jpg, jpeg, png, bmp, gif, or webp
            'isbn' => ['required', 'unique:books', 'regex:/^[0-9-]{10,17}$/'],
        ]);

        $author = Author::firstOrCreate(
            ['name' => $request->author_name],
        );

        $book = new Book();
        $book->book_name = $request->book_name;
        $book->isbn = $request->isbn;
        $book->author_id = $author->id;

        if ($request->hasfile('image')) {
            $image_path = $request->file('image')->store('images', 'public');
            $book->image = $image_path;
        }

        $book->save();

        $book->bookstores()->attach($request->bookstores);

        $emails = $book->bookstores()->pluck('email')->toArray();

        if (!empty($emails)) {
            Mail::to($emails)->send(new BookCreatedMail($book));
        }

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
        $bookstores = Bookstore::all();
        $authors = Author::all();
        return view('edit', compact('book', 'bookstores', 'authors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreAuthorRequest $request, Book $book)
    {
        $request->validate([
            'book_name' => ['required', Rule::unique('books', 'book_name')->ignore($book->id)],
            'bookstores' => 'array',
            'image' => 'image|mimes:jpg,png,jpeg|max:1024', // max 1gb, default jpg, jpeg, png, bmp, gif, or webp
            'isbn' => ['required', Rule::unique('books', 'isbn')->ignore($book->id), 'regex:/^[0-9-]{10,17}$/'],
        ]);

        $author = Author::firstOrCreate(
            ['name' => $request->author_name],
        );

        $book->book_name = $request->book_name;
        $book->isbn = $request->isbn;
        $book->author_id = $author->id;

        if ($request->hasfile('image')) {
            if ($book->image) {
                Storage::disk('public')->delete($book->image);
            }
            $book->image = $request->file('image')->store('images', 'public');
        }

        $book->save();

        $current_stores = $book->bookstores;
        $new_stores = collect($request->bookstores)->pluck('name');
        $removed_stores = $current_stores->whereNotIn('name', $new_stores);
        $added_stores = $new_stores->diff($current_stores);

        if ($removed_stores->isNotEmpty()) {
            foreach ($removed_stores as $store) {
                Mail::to($store->email)->send(new BookDeletedMail($book));
            }
        }

        if ($added_stores->isNotEmpty()) {
            $added_stores_models = Bookstore::whereIn('name', $added_stores)->get();

            foreach ($added_stores_models as $added_stores_model) {
                Mail::to($added_stores_model->email)->send(new BookCreatedMail($book));
            }
        }

        $book->bookstores()->sync($request->bookstores);

        return redirect()->route('list');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        if ($book->image) {
            Storage::disk('public')->delete($book->image);
        }
        $book->delete();
        return redirect()->route('list');
    }
}
