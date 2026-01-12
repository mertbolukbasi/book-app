@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5"> <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-body p-5"> <h3 class="text-center fw-bold mb-4">Sign in</h3>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <form action="{{ route('register') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label text-muted">Username</label>
                                <input type="text" name="name" class="form-control form-control-lg" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Email</label>
                                <input type="email" name="email" class="form-control form-control-lg" required autofocus>
                            </div>
                            <div class="mb-4">
                                <label class="form-label text-muted">Password</label>
                                <input type="password" name="password" class="form-control form-control-lg" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm">Sign in</button>
                        </form>

                        <div class="mt-4 text-center">
                            <span class="text-muted">Already have an account?</span>
                            <a href="{{ route('login') }}" class="text-decoration-none fw-bold">Sign in</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
