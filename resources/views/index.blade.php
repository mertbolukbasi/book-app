@extends('layouts.app')


@section('content')

    <div class="container">
        <div class="row">
            @foreach($books as $book)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        @if($book->image)
                            <img src="{{ asset('storage/' . $book->image) }}" class="card-img-top"
                                style="height: 250px; object-fit: contain" alt="{{ $book->book_name }}">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $book->book_name }}</h5>
                            <p class="card-text">Author: {{ $book->author->name }}</p>

                            <p class="card-text">Mağazalar: {{ $book->bookstores->pluck('name')->implode(', ') }}</p>

                            <p class="card-text"><small class="text-muted">ISBN: {{ $book->isbn }}</small></p>
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $book->id }}">
                                    Delete
                                </button>
                                <a href="{{ route('books.edit', $book->id) }}" class="btn btn-primary">Update</a>
                            </div>

                            <div class="modal fade" id="deleteModal{{ $book->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Delete Book</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete <strong>{{ $book->book_name }}</strong>?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <form action="{{ route('books.destroy', $book->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection