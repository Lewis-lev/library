<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/">Laravel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                @auth
                <li class="nav-item">
                    <a class="nav-link active" href="#">Dashboard</a>
                </li>
                @endauth
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('books.index') }}">Books</a>
                </li>
            </ul>

            <ul class="navbar-nav">
                @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                </li>
                @endguest

                @auth
                @if(auth()->user()->role === 'admin')
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link btn btn-link" style="color: white; text-decoration: none;">
                            Logout
                        </button>
                    </form>
                </li>
                @endif
                @endauth

                @auth
                @if(auth()->user()->role === 'borrower')
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ auth()->user()->profile_picture ? asset('storage/profile_pict/' . auth()->user()->profile_picture) : asset('storage/profile_pict/default-profile.jpg') }}"
                            alt="Profile" width="34" height="34" class="rounded-circle border" style="object-fit:cover;">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end mt-2" aria-labelledby="profileDropdown">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fa fa-user me-2"></i>Profile Detail</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="dropdown-item" type="submit"><i class="fa fa-sign-out-alt me-2"></i>Logout</button>
                            </form>
                        </li>
                    </ul>
                </li>
                @else
                {{-- original logout/profile display for other roles --}}
                @endif
                @endauth
            </ul>
        </div>
    </div>
</nav>
