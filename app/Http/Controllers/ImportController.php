<?php

namespace App\Http\Controllers;

use App\Jobs\AuthorImport;
use App\Jobs\BookImport;
use App\Models\Import;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ImportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('import');
    }

    public function indexBooks()
    {
        return view('import_books');
    }

    public function history()
    {
        $imports = Import::latest()->get();
        return view('history', compact('imports'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => ['required', 'mimes:csv,xlsx,txt', 'max:1048576'],
        ]);

        $fileName = $request->file('file')->getClientOriginalName();

        $path = $request->file('file')->storeAs('imports', $fileName);

        $history = Import::create([
            'path' => $path,
            'status' => 'pending',
        ]);

        AuthorImport::dispatch($history->id);
        return redirect()->route('import.history');
    }

    public function storeBooks(Request $request)
    {
        $request->validate([
            'file' => ['required', 'mimes:csv,xlsx,txt', 'max:1048576'],
        ]);

        $fileName = Str::uuid() . '_' . $request->file('file')->getClientOriginalName();
        $path = $request->file('file')->storeAs('imports', $fileName);

        $history = Import::create([
            'path' => $path,
            'status' => 'pending',
        ]);

        BookImport::dispatch($history->id);
        return redirect()->route('import.history');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
