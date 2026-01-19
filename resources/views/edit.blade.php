@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header mb-3">
                    <h5 class="modal-title">Update Book</h5>
                </div>
                <div class="modal-body">
                    <form action="{{ route('books.update', $book) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Book Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $book->name) }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="author" class="form-label">Author Name</label>
                            <input type="text" class="form-control @error('authorName') is-invalid @enderror" id="author" name="authorName" list="author_list" value="{{ old('authorName', $book->author->name ?? '') }}" required autocomplete="off" placeholder="Search author">

                            <datalist id="author_list">
                                @foreach($authors as $auth)
                                    <option value="{{ $auth->name }}">
                                @endforeach
                            </datalist>

                            @error('authorName')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="isbn" class="form-label">ISBN</label>
                            <input type="text" class="form-control @error('isbn') is-invalid @enderror" id="isbn" name="isbn" value="{{ old('isbn', $book->isbn) }}" required>
                            @error('isbn')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block">Stores</label>
                            <div class="card p-3 @error('bookstores') border-danger @enderror">
                                @if($bookstores->isEmpty())
                                    <p class="text-muted small mb-0">No bookstores found in database.</p>
                                @else
                                    <div class="d-flex flex-wrap gap-3">
                                        @foreach($bookstores as $store)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="bookstores[]" value="{{ $store->id }}" id="store_{{ $store->id }}"
                                                {{ (is_array(old('bookstores')) && in_array($store->id, old('bookstores'))) || (!old('bookstores') && $book->bookstores->contains($store->id)) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="store_{{ $store->id }}">
                                                    {{ $store->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            @error('bookstores')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            @if($book->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $book->image) }}" alt="Current Image" style="height: 100px; object-fit: cover; border-radius: 5px;">
                                </div>
                            @endif

                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="modal-footer gap-2">
                            <a href="{{ route('list') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
