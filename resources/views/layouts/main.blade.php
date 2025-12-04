<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Lyline') }} - Digital Library</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Poppins:400,500,600,700&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('styles')


</head>
<body>
    <div id="app">
        @auth
            @if (auth()->user()->role === App\Enums\UserRole::Admin)
                <!-- Admin Layout with Sidebar and Horizontal Breadcrumb Bar -->
                <div class="d-flex" style="min-height: 100vh;">
                    <!-- Sidebar -->
                    <nav class="bg-dark text-white p-3 d-flex flex-column position-fixed" style="width: 250px; height: 100vh; overflow-y: auto;">
                        <div>
                            <h5 class="text-center mb-4">{{ __('Lyline Admin Panel') }}</h5>
                            <ul class="nav flex-column">
                                <li class="nav-item mb-2">
                                    <a class="nav-link text-white" href="{{ route('home') }}"><i class="bi bi-speedometer2 me-2"></i>{{ __('Dashboard') }}</a>
                                </li>
                                <li class="nav-item mb-2">
                                    <a class="nav-link text-white" href="{{ route('users.index') }}"><i class="bi bi-people-fill me-2"></i>{{ __('Manage Users') }}</a>
                                </li>
                                <!-- Books Management with Submenu -->
                                <li class="nav-item mb-2">
                                    <a class="nav-link text-white" href="#booksSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="booksSubmenu">
                                        <i class="bi bi-book-half me-2"></i>{{ __('Book Management') }}
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
                                    <div class="collapse {{ (Request::is('borrows*') || Request::is('databorrows*') || Request::is('bookings*')) ? 'show' : '' }}" id="borrowsSubmenu">
                                        <ul class="nav flex-column ms-3">
                                            <li class="nav-item"><a class="nav-link {{ Request::is('borrows*') ? 'text-white' : 'text-white-50' }}" href="{{ route('borrows.index') }}">Loaned Books</a></li>
                                            {{-- <li class="nav-item"><a class="nav-link {{ Request::is('databorrows*') ? 'text-white' : 'text-white-50' }}" href="{{ route('databorrows.index') }}">Borrow Records</a></li> --}}
                                            <li class="nav-item"><a class="nav-link {{ Request::is('bookings*') ? 'text-white' : 'text-white-50' }}" href="{{ route('bookings.index') }}">Bookings</a></li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="mt-auto">
                            <div class="nav-item dropup">
                                <a id="sidebarUserDropdown" class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="bi bi-person-circle fs-4 me-2"></i>
                                    {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-dark" aria-labelledby="sidebarUserDropdown">
                                    <a class="dropdown-item text-white" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-2"></i>{{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                                </div>
                            </div>
                        </div>
                    </nav>
                    <!-- Main Content with Horizontal Breadcrumb Bar -->
                    <div class="flex-grow-1 d-flex flex-column" style="padding-left: 250px;">
                        <!-- Horizontal Breadcrumb Bar -->
                        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom" style="height: 60px;">
                            <div class="container-fluid d-flex justify-content-between align-items-center">
                                <!-- Breadcrumb (Left) -->
                                <div>
                                    @php
                                        $routeName = Route::currentRouteName();
                                        $segments = explode('.', $routeName);
                                        $breadcrumb = [];
                                        $links = [];
                                        if (isset($segments[0])) {
                                            if ($segments[0] == 'books') {
                                                $breadcrumb[] = 'Books';
                                                $links[] = route('books.index');
                                                if (isset($segments[1])) {
                                                    if ($segments[1] == 'create') $breadcrumb[] = 'Create';
                                                    elseif ($segments[1] == 'edit') $breadcrumb[] = 'Edit';
                                                    elseif ($segments[1] == 'show') $breadcrumb[] = 'Show';
                                                }
                                            } elseif ($segments[0] == 'categories') {
                                                $breadcrumb[] = 'Books';
                                                $links[] = route('books.index');
                                                $breadcrumb[] = 'Categories';
                                                $links[] = route('categories.index');
                                                if (isset($segments[1])) {
                                                    if ($segments[1] == 'create') $breadcrumb[] = 'Create';
                                                    elseif ($segments[1] == 'edit') $breadcrumb[] = 'Edit';
                                                    elseif ($segments[1] == 'show') $breadcrumb[] = 'Show';
                                                }
                                            } elseif ($segments[0] == 'borrows') {
                                                $breadcrumb[] = 'Borrows';
                                                $links[] = route('borrows.index');
                                                if (isset($segments[1])) {
                                                    if ($segments[1] == 'create') $breadcrumb[] = 'Create';
                                                    elseif ($segments[1] == 'edit') $breadcrumb[] = 'Edit';
                                                    elseif ($segments[1] == 'show') $breadcrumb[] = 'Show';
                                                }
                                            } elseif ($segments[0] == 'databorrows') {
                                                $breadcrumb[] = 'Borrow Records';
                                                $links[] = route('databorrows.index');
                                                if (isset($segments[1])) {
                                                    if ($segments[1] == 'create') $breadcrumb[] = 'Create';
                                                    elseif ($segments[1] == 'edit') $breadcrumb[] = 'Edit';
                                                    elseif ($segments[1] == 'show') $breadcrumb[] = 'Show';
                                                }
                                            } elseif ($segments[0] == 'users') {
                                                $breadcrumb[] = 'Users';
                                                $links[] = route('users.index');
                                                if (isset($segments[1])) {
                                                    if ($segments[1] == 'create') $breadcrumb[] = 'Create';
                                                    elseif ($segments[1] == 'edit') $breadcrumb[] = 'Edit';
                                                    elseif ($segments[1] == 'show') $breadcrumb[] = 'Show';
                                                }
                                            } elseif ($routeName == 'home') {
                                                $breadcrumb[] = 'Dashboard';
                                            } else {
                                                $breadcrumb[] = 'Dashboard';
                                            }
                                        }
                                    @endphp
                                    @if(count($breadcrumb) > 0)
                                        <nav aria-label="breadcrumb">
                                            <ol class="breadcrumb mb-0">
                                                @foreach($breadcrumb as $index => $crumb)
                                                    @if($index < count($breadcrumb) - 1 && isset($links[$index]))
                                                        <li class="breadcrumb-item"><a href="{{ $links[$index] }}">{{ $crumb }}</a></li>
                                                    @else
                                                        <li class="breadcrumb-item active" aria-current="page">{{ $crumb }}</li>
                                                    @endif
                                                @endforeach
                                            </ol>
                                        </nav>
                                    @endif
                                </div>

                        </nav>
                        <!-- Main Content Area -->
                        <main class="flex-grow-1 py-4">
                            @yield('content')
                        </main>
                    </div>
                </div>
            @elseif (auth()->user()->role === App\Enums\UserRole::Staff)
                <!-- Staff Layout with Sidebar and Horizontal Breadcrumb Bar -->
                <div class="d-flex" style="min-height: 100vh;">
                    <!-- Sidebar -->
                    <nav class="bg-dark text-white p-3 d-flex flex-column position-fixed" style="width: 250px; height: 100vh; overflow-y: auto;">
                        <div>
                            <h5 class="text-center mb-4">{{ __('Lyline Staff Panel') }}</h5>
                            <ul class="nav flex-column">
                                <li class="nav-item mb-2">
                                    <a class="nav-link text-white" href="{{ route('home') }}"><i class="bi bi-speedometer2 me-2"></i>{{ __('Dashboard') }}</a>
                                </li>
                                <li class="nav-item mb-2">
                                    <a class="nav-link text-white" href="{{ route('users.index') }}"><i class="bi bi-people-fill me-2"></i>{{ __('Manage Users') }}</a>
                                </li>
                                <!-- Books Management with Submenu -->
                                <li class="nav-item mb-2">
                                    <a class="nav-link text-white" href="#booksSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="booksSubmenu">
                                        <i class="bi bi-book-half me-2"></i>{{ __('Book Management') }}
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
                                            {{-- <li class="nav-item"><a class="nav-link {{ Request::is('databorrows*') ? 'text-white' : 'text-white-50' }}" href="{{ route('databorrows.index') }}">Borrow Records</a></li> --}}
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="mt-auto">
                            <div class="nav-item dropup">
                                <a id="sidebarUserDropdown" class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="bi bi-person-circle fs-4 me-2"></i>
                                    {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-dark" aria-labelledby="sidebarUserDropdown">
                                    <a class="dropdown-item text-white" href="{{ route('profile.edit') }}"><i class="bi bi-person-gear me-2"></i>{{ __('Profile') }}</a>
                                    <a class="dropdown-item text-white" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-2"></i>{{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                                </div>
                            </div>
                        </div>
                    </nav>
                    <!-- Main Content with Horizontal Breadcrumb Bar -->
                    <div class="flex-grow-1 d-flex flex-column" style="padding-left: 250px;">
                        <!-- Horizontal Breadcrumb Bar -->
                        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom" style="height: 60px;">
                            <div class="container-fluid d-flex justify-content-between align-items-center">
                                <!-- Breadcrumb (Left) -->
                                <div>
                                    @php
                                        $routeName = Route::currentRouteName();
                                        $segments = explode('.', $routeName);
                                        $breadcrumb = [];
                                        $links = [];
                                        if (isset($segments[0])) {
                                            if ($segments[0] == 'books') {
                                                $breadcrumb[] = 'Books';
                                                $links[] = route('books.index');
                                                if (isset($segments[1])) {
                                                    if ($segments[1] == 'create') $breadcrumb[] = 'Create';
                                                    elseif ($segments[1] == 'edit') $breadcrumb[] = 'Edit';
                                                    elseif ($segments[1] == 'show') $breadcrumb[] = 'Show';
                                                }
                                            } elseif ($segments[0] == 'categories') {
                                                $breadcrumb[] = 'Books';
                                                $links[] = route('books.index');
                                                $breadcrumb[] = 'Categories';
                                                $links[] = route('categories.index');
                                                if (isset($segments[1])) {
                                                    if ($segments[1] == 'create') $breadcrumb[] = 'Create';
                                                    elseif ($segments[1] == 'edit') $breadcrumb[] = 'Edit';
                                                    elseif ($segments[1] == 'show') $breadcrumb[] = 'Show';
                                                }
                                            } elseif ($segments[0] == 'borrows') {
                                                $breadcrumb[] = 'Borrows';
                                                $links[] = route('borrows.index');
                                                if (isset($segments[1])) {
                                                    if ($segments[1] == 'create') $breadcrumb[] = 'Create';
                                                    elseif ($segments[1] == 'edit') $breadcrumb[] = 'Edit';
                                                    elseif ($segments[1] == 'show') $breadcrumb[] = 'Show';
                                                }
                                            } elseif ($segments[0] == 'databorrows') {
                                                $breadcrumb[] = 'Borrow Records';
                                                $links[] = route('databorrows.index');
                                                if (isset($segments[1])) {
                                                    if ($segments[1] == 'create') $breadcrumb[] = 'Create';
                                                    elseif ($segments[1] == 'edit') $breadcrumb[] = 'Edit';
                                                    elseif ($segments[1] == 'show') $breadcrumb[] = 'Show';
                                                }
                                            } elseif ($segments[0] == 'users') {
                                                $breadcrumb[] = 'Users';
                                                $links[] = route('users.index');
                                                if (isset($segments[1])) {
                                                    if ($segments[1] == 'create') $breadcrumb[] = 'Create';
                                                    elseif ($segments[1] == 'edit') $breadcrumb[] = 'Edit';
                                                    elseif ($segments[1] == 'show') $breadcrumb[] = 'Show';
                                                }
                                            } elseif ($routeName == 'home') {
                                                $breadcrumb[] = 'Dashboard';
                                            } else {
                                                $breadcrumb[] = 'Dashboard';
                                            }
                                        }
                                    @endphp
                                    @if(count($breadcrumb) > 0)
                                        <nav aria-label="breadcrumb">
                                            <ol class="breadcrumb mb-0">
                                                @foreach($breadcrumb as $index => $crumb)
                                                    @if($index < count($breadcrumb) - 1 && isset($links[$index]))
                                                        <li class="breadcrumb-item"><a href="{{ $links[$index] }}">{{ $crumb }}</a></li>
                                                    @else
                                                        <li class="breadcrumb-item active" aria-current="page">{{ $crumb }}</li>
                                                    @endif
                                                @endforeach
                                            </ol>
                                        </nav>
                                    @endif
                                </div>
                        </nav>
                        <!-- Main Content Area -->
                        <main class="flex-grow-1 py-4">
                            @yield('content')
                        </main>
                    </div>
                </div>
            @else
                <!-- User Layout with Sidebar and Horizontal Breadcrumb Bar -->
                <div class="d-flex" style="min-height: 100vh;">
                    <!-- Sidebar -->
                    <nav class="bg-dark text-white p-3 d-flex flex-column position-fixed" style="width: 250px; height: 100vh; overflow-y: auto;">
                        <div>
                            <h5 class="text-center mb-4">{{ __('Lyline User Panel') }}</h5>
                            <ul class="nav flex-column">
                                <li class="nav-item mb-2">
                                    <a class="nav-link text-white" href="{{ route('home') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                                </li>
                                <!-- Bookings Management -->
                                <li class="nav-item mb-2">
                                    <a class="nav-link text-white" href="{{ route('bookings.index') }}"><i class="bi bi-calendar-check me-2"></i>My Bookings</a>
                                </li>
                            </ul>
                        </div>
                        <div class="mt-auto">
                            <div class="nav-item dropup">
                                <a id="sidebarUserDropdown" class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="bi bi-person-circle fs-4 me-2"></i>
                                    {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-dark" aria-labelledby="sidebarUserDropdown">

                                    <a class="dropdown-item text-white" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-2"></i>{{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>
            @endif

    </div>

