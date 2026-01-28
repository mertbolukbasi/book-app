<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Book App') }}
        </a>

        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Sign in</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Sign up</a>
                    </li>
                @endguest

                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('books.create') }}">Add Book</a>
                    </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('import.store') }}">Import Author</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('import.books.index') }}">Import Book</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('import.history') }}">Import History</a>
                        </li>

                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link" style="text-decoration: none;">
                                Logout
                            </button>
                        </form>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
