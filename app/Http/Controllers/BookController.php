<?php

namespace App\Http\Controllers;

use App\Events\BookCreated;
use App\Events\BookDeleted;
use App\Events\BookUpdated;
use App\Http\Requests\StoreAuthorRequest;
use App\Models\Author;
use App\Models\Book;
use App\Models\Bookstore;
use App\Rules\IsbnRule;
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
            'name' => ['required', 'unique:books', 'max:255'],
            'bookstores' => 'array',
            'image' => 'image|mimes:jpg,png,jpeg|max:10240', // max 10gb, default jpg, jpeg, png, bmp, gif, or webp
            'isbn' => ['required', 'unique:books', new IsbnRule()],
        ]);

        $author = Author::firstOrCreate(
            ['name' => $request->authorName],
        );

        $book = new Book();
        $book->name = $request->name;
        $book->isbn = $request->isbn;
        $book->author_id = $author->id;

        if ($request->hasfile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $book->image = $imagePath;
        }

        $book->save();

        $book->bookstores()->attach($request->bookstores);
        event(new BookCreated($book));

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
            'name' => ['required', Rule::unique('books', 'name')->ignore($book->id)],
            'bookstores' => 'array',
            'image' => 'image|mimes:jpg,png,jpeg|max:1024', // max 1gb, default jpg, jpeg, png, bmp, gif, or webp
            'isbn' => ['required', Rule::unique('books', 'isbn')->ignore($book->id), new IsbnRule()],
        ]);

        $author = Author::firstOrCreate(
            ['name' => $request->authorName],
        );

        $book->name = $request->name;
        $book->isbn = $request->isbn;
        $book->author_id = $author->id;

        if ($request->hasfile('image')) {
            if ($book->image) {
                Storage::disk('public')->delete($book->image);
            }
            $book->image = $request->file('image')->store('images', 'public');
        }

        $oldStoreIds = $book->bookstores()->pluck('bookstores.id')->toArray();
        $newStoreIds = $request->bookstores;

        $book->save();
        $book->bookstores()->sync($request->bookstores);
        event(new BookUpdated($book, $oldStoreIds, $newStoreIds));

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

        $emails = $book->bookstores()->pluck('email')->toArray();
        event(new BookDeleted($book, $emails));

        $book->delete();

        return redirect()->route('list');
    }
}
