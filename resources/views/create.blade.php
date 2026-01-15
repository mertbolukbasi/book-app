@extends('layouts.app')

@section('content')
<div class="container">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header mb-3">
                <h5 class="modal-title">Add Book</h5>
            </div>
            <div class="modal-body">
                <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="book_name" class="form-label">Book Name</label>
                        <input type="text" class="form-control @error('book_name') is-invalid @enderror" id="book_name" name="book_name" value="{{ old('book_name') }}" required>
                        @error('book_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="author" class="form-label">Author Name</label>
                        <input type="text" 
                               class="form-control @error('author_name') is-invalid @enderror" 
                               id="author" 
                               name="author_name" 
                               list="author_list" 
                               value="{{ old('author_name') }}" 
                               required 
                               autocomplete="off" 
                               placeholder="Search author">
                        
                        <datalist id="author_list">
                            @foreach($authors as $auth)
                                <option value="{{ $auth->name }}">
                            @endforeach
                        </datalist>

                        @error('author_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="isbn" class="form-label">ISBN</label>
                        <input type="text" class="form-control @error('isbn') is-invalid @enderror" id="isbn" name="isbn" value="{{ old('isbn') }}" required>
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
                                            <input class="form-check-input" type="checkbox" 
                                                   name="bookstores[]" 
                                                   value="{{ $store->id }}" 
                                                   id="store_{{ $store->id }}"
                                                   {{ (is_array(old('bookstores')) && in_array($store->id, old('bookstores'))) ? 'checked' : '' }}
                                            >
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
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="modal-footer gap-2">
                        <a href="{{ route('list') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection