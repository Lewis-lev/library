

<nav class="navbar navbar-expand-lg navbar-dark site-navbar py-2">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="/">
            <span class="site-brand">
                <span class="site-brand-icon"><i class="fa-solid fa-book-open"></i></span>
                The Reading Room
            </span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto align-items-lg-center">
                @auth
                @if (auth()->user()->role === 'borrower')
                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="{{ route('dashboard') }}">
                        <i class="fa fa-list-alt me-1"></i>Dashboard
                    </a>
                </li>
                @endif
                @endauth
                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="{{ route('books.index') }}">
                        <i class="fa fa-book me-1"></i>Books
                    </a>
                </li>
                @auth
                @if (auth()->user()->role === 'admin')
                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="{{ route('borrow.index') }}">
                        <i class="fa fa-list-alt me-1"></i>Borrow Log
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="{{ route('auth.user-list') }}">
                        <i class="fa fa-user-alt me-1"></i>User  List
                    </a>
                </li>
                @endif
                @endauth
            </ul>

            <ul class="navbar-nav align-items-lg-center mb-2 mb-lg-0">
                @guest
                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="{{ route('login') }}">
                        <i class="fa fa-sign-in-alt me-1"></i>Login
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="{{ route('register') }}">
                        <i class="fa fa-user-plus me-1"></i>Register
                    </a>
                </li>
                @endguest

                @auth
                @if(auth()->user()->role === 'admin')
                <li class="nav-item d-flex align-items-center gap-2 ms-lg-3">
                    <span class="nav-role-label"><i class="fa-solid fa-shield-halved me-1"></i>Admin</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-nav-logout">
                            <i class="fa fa-sign-out-alt me-1"></i>Logout
                        </button>
                    </form>
                </li>
                @elseif(auth()->user()->role === 'borrower')
                <li class="nav-item dropdown ms-lg-3">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="me-2">{{ auth()->user()->name }}</span>
                        <img src="{{ auth()->user()->profile_picture ? asset(path: 'https://pub-94f23dc765bc4b62a5ef536b35ffa982.r2.dev/img/profile_pict/' . auth()->user()->profile_picture) : asset(path: 'https://pub-94f23dc765bc4b62a5ef536b35ffa982.r2.dev/img/profile_pict/default-profile.webp') }}"
                            alt="Profile" width="38" height="38" class="rounded-circle border border-2 shadow-sm profile-nav-avatar">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow mt-2" aria-labelledby="profileDropdown">
                        <li>
                            <a class="dropdown-item w-100 text-start" href="{{ route('profile.edit') }}">
                                <i class="fa fa-user me-2"></i>Profile Detail
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item w-100 text-start" href="{{ route('books.history') }}">
                                <i class="fa fa-history me-2"></i>Books History
                            </a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="dropdown-item w-100 text-start" type="submit">
                                    <i class="fa fa-sign-out-alt me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
                @endif
                @endauth
            </ul>
        </div>
    </div>
</nav>
