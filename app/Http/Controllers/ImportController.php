<?php

namespace App\Http\Controllers;

use App\Jobs\AuthorImport;
use App\Models\Import;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('import');
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
            'file' => ['required', 'mimes:csv,xlsx,txt', 'max:1048000000'],
        ]);

        $fileName = $request->file('file')->getClientOriginalName();

        $path = $request->file('file')->storeAs('imports', $fileName);

        $history = Import::create([
            'path' => $path,
            'status' => 'pending',
        ]);

        AuthorImport::dispatch($history->id);
        return back();
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
