<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        @auth
            @if (auth()->user()->role === App\Enums\UserRole::Admin)
                <!-- Admin Layout with Sidebar -->
                <div class="d-flex">
                    <!-- Sidebar -->
                    <nav class="bg-dark text-white p-3" style="width: 250px; min-height: 100vh;">
                        <h4 class="text-center mb-4">Lyline Admin Panel</h4>
                        <ul class="nav flex-column">
                            <li class="nav-item mb-2">
                                <a class="nav-link text-white" href="{{ route('home') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                            </li>
                            <li class="nav-item mb-2">
                                <a class="nav-link text-white" href="{{ route('users.index') }}"><i class="bi bi-people-fill me-2"></i>Manage Users</a>
                            </li>
                            <!-- Books Management with Submenu -->
                            <li class="nav-item mb-2">
                                <a class="nav-link text-white" href="#booksSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="booksSubmenu">
                                    <i class="bi bi-book-half me-2"></i>Book Management
                    <nav class="bg-dark text-white p-3 d-flex flex-column" style="width: 250px; min-height: 100vh;">
                        <div>
                            <h4 class="text-center mb-4">Lyline Admin Panel</h4>
                            <ul class="nav flex-column">
                                <li class="nav-item mb-2">
                                    <a class="nav-link text-white" href="{{ route('home') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                                </li>
                                <li class="nav-item mb-2">
                                    <a class="nav-link text-white" href="{{ route('users.index') }}"><i class="bi bi-people-fill me-2"></i>Manage Users</a>
                                </li>
                                <!-- Books Management with Submenu -->
                                <li class="nav-item mb-2">
                                    <a class="nav-link text-white" href="#booksSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="booksSubmenu">
                                        <i class="bi bi-book-half me-2"></i>Book Management
                                    </a>
                                    <div class="collapse {{ (Request::is('books*') || Request::is('categories*')) ? 'show' : '' }}" id="booksSubmenu">
                                        <ul class="nav flex-column ms-3">
                                            <li class="nav-item">
                                                <a class="nav-link {{ Request::is('books*') ? 'text-white' : 'text-white-50' }}" href="{{ route('books.index') }}"><i class="bi bi-journals me-2"></i>Book List</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link {{ Request::is('categories*') ? 'text-white' : 'text-white-50' }}" href="{{ route('categories.index') }}"><i class="bi bi-tags-fill me-2"></i>Categories</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <!-- Borrows Management with Submenu -->
                                <li class="nav-item mb-2">
                                    <a class="nav-link text-white" href="#borrowsSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="borrowsSubmenu">
                                        <i class="bi bi-arrow-down-up me-2"></i>Borrow Management
                                    </a>
                                    <div class="collapse {{ (Request::is('borrows*') || Request::is('databorrows*')) ? 'show' : '' }}" id="borrowsSubmenu">
                                        <ul class="nav flex-column ms-3">
                                            <li class="nav-item"><a class="nav-link {{ Request::is('borrows*') ? 'text-white' : 'text-white-50' }}" href="{{ route('borrows.index') }}">Loaned Books</a></li>
                                            <li class="nav-item"><a class="nav-link {{ Request::is('databorrows*') ? 'text-white' : 'text-white-50' }}" href="{{ route('databorrows.index') }}">Borrower Data</a></li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="mt-auto">
                            <div class="nav-item dropup">
                                <a id="sidebarUserDropdown" class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    @if(Auth::user()->image)
                                        <img src="{{ asset('storage/images/' . Auth::user()->image) }}" alt="Profile" class="rounded-circle me-2" style="width: 30px; height: 30px; object-fit: cover;">
                                    @else
                                        <i class="bi bi-person-circle fs-4 me-2"></i>
                                    @endif
                                    {{ Auth::user()->name }}
                                </a>
                                <div class="collapse {{ (Request::is('books*') || Request::is('categories*')) ? 'show' : '' }}" id="booksSubmenu">
                                    <ul class="nav flex-column ms-3">
                                        <li class="nav-item">
                                            <a class="nav-link {{ Request::is('books*') ? 'text-white' : 'text-white-50' }}" href="{{ route('books.index') }}"><i class="bi bi-journals me-2"></i>Book List</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link {{ Request::is('categories*') ? 'text-white' : 'text-white-50' }}" href="{{ route('categories.index') }}"><i class="bi bi-tags-fill me-2"></i>Categories</a>
                                        </li>
                                    </ul>
                                <div class="dropdown-menu dropdown-menu-dark" aria-labelledby="sidebarUserDropdown">
                                    <a class="dropdown-item text-white" href="{{ route('profile.edit') }}"><i class="bi bi-person-gear me-2"></i>{{ __('Profile') }}</a>
                                    <a class="dropdown-item text-white" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-2"></i>{{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                                </div>
                            </li>
                            <!-- Borrows Management with Submenu -->
                            <li class="nav-item mb-2">
                                <a class="nav-link text-white" href="#borrowsSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="borrowsSubmenu">
                                    <i class="bi bi-arrow-down-up me-2"></i>Borrow Management
                                </a>
                                <div class="collapse {{ (Request::is('borrows*') || Request::is('databorrows*')) ? 'show' : '' }}" id="borrowsSubmenu">
                                    <ul class="nav flex-column ms-3">
                                        <li class="nav-item"><a class="nav-link {{ Request::is('borrows*') ? 'text-white' : 'text-white-50' }}" href="{{ route('borrows.index') }}">Loaned Books</a></li>
                                        <li class="nav-item"><a class="nav-link {{ Request::is('databorrows*') ? 'text-white' : 'text-white-50' }}" href="{{ route('databorrows.index') }}">Borrower Data</a></li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                            </div>
                        </div>
                    </nav>
                    <!-- Main Content -->
                    <div class="flex-grow-1">
                        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
                            <div class="container-fluid">
                                <span class="navbar-brand mb-0 h1">Welcome, {{ Auth::user()->name }} (Admin)</span>
                                <div class="navbar-nav ms-auto align-items-center">
                                    <div class="nav-item dropup">
                                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                            @if(Auth::user()->image)
                                                <img src="{{ asset('storage/images/' . Auth::user()->image) }}" alt="Profile" class="rounded-circle me-2" style="width: 30px; height: 30px; object-fit: cover;">
                                            @else
                                                <i class="bi bi-person-circle fs-4 me-2"></i>
                                            @endif
                                            {{ Auth::user()->name }}
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                                <i class="bi bi-person-gear me-2"></i>{{ __('Profile') }}
                                            </a>
                                            <a class="dropdown-item" href="{{ route('logout') }}"
                                               onclick="event.preventDefault();
                                                             document.getElementById('logout-form').submit();">
                                                {{ __('Logout') }}
                                            </a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                @csrf
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </nav>
                        <main class="py-4">
                            @yield('content')
                        </main>
                    </div>
                </div>
            @elseif (auth()->user()->role === App\Enums\UserRole::Staff)
                <!-- Staff Layout with Top Nav -->
                <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
                    <div class="container">
                        <a class="navbar-brand" href="{{ url('/') }}">
                            {{ config('app.name', 'Laravel') }}
                        </a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <!-- Left Side Of Navbar -->
                            <ul class="navbar-nav me-auto">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('home') }}"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('books.index') }}"><i class="bi bi-journals me-1"></i>Books</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('borrows.index') }}"><i class="bi bi-book-half me-1"></i>Loaned Books</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('categories.index') }}"><i class="bi bi-tags-fill me-1"></i>Categories</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('databorrows.index') }}"><i class="bi bi-person-lines-fill me-1"></i>Data Borrows</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('users.create') }}"><i class="bi bi-person-plus-fill me-1"></i>Add User</a>
                                </li>
                            </ul>

                            <!-- Right Side Of Navbar -->
                            <ul class="navbar-nav ms-auto align-items-center">
                                <li class="nav-item dropup">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        @if(Auth::user()->image)
                                            <img src="{{ asset('storage/images/' . Auth::user()->image) }}" alt="Profile" class="rounded-circle me-2" style="width: 30px; height: 30px; object-fit: cover;">
                                        @else
                                            <i class="bi bi-person-circle fs-4 me-2"></i>
                                        @endif
                                        {{ Auth::user()->name }} (Staff)
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('profile.edit') }}">{{ __('Profile') }}</a>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>

                <main class="py-4">
                    @yield('content')
                </main>
            @else
                <!-- User Layout -->
                <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
                    <div class="container">
                        <a class="navbar-brand" href="{{ url('/') }}">
                            {{ config('app.name', 'Laravel') }}
                        </a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <!-- Left Side Of Navbar -->
                            <ul class="navbar-nav me-auto">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('home') }}"><i class="bi bi-house-door-fill me-1"></i>Dashboard</a>
                                </li>
                            </ul>

                            <!-- Right Side Of Navbar -->
                            <ul class="navbar-nav ms-auto align-items-center">
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        @if(Auth::user()->image)
                                            <img src="{{ asset('storage/images/' . Auth::user()->image) }}" alt="Profile" class="rounded-circle me-2" style="width: 30px; height: 30px; object-fit: cover;">
                                        @else
                                            <i class="bi bi-person-circle fs-4 me-2"></i>
                                        @endif
                                        {{ Auth::user()->name }} (User)
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('profile.edit') }}">{{ __('Profile') }}</a>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>

                <main class="py-4">
                    @yield('content')
                </main>
            @endif
        @else
            <!-- Guest Layout -->
            <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
                <div class="container">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav me-auto">

                        </ul>

                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ms-auto">
                            <!-- Authentication Links -->
                            @guest
                                @if (Route::has('login'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                    </li>
                                @endif

                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                    </li>
                                @endif
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>

            <main class="py-4">
                @yield('content')
            </main>
        @endauth
    </div>
</body>
</html>
