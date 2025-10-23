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

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .sidebar-icon {
            transition: transform 0.2s ease;
        }

        .sidebar-icon:hover {
            transform: scale(1.1);
        }

        .navbar-brand {
            font-weight: 700;
            color: #667eea !important;
        }

        .breadcrumb {
            background: transparent;
            margin-bottom: 0;
        }

        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        }

        .text-primary {
            color: #667eea !important;
        }

        .bg-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }

        .dropdown-menu-dark {
            background-color: #343a40;
            border: 1px solid #495057;
        }

        .dropdown-item:hover {
            background-color: #495057;
        }

        /* notifikasi badge & dropdown list styling */
        .notif-btn { position: relative; }
        .notif-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            font-size: .65rem;
        }
        .dropdown-menu .list-group {
            max-height: 320px;
            overflow-y: auto;
        }
    </style>

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
                            <h5 class="text-center mb-4">Lyline Admin Panel</h5>
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
                                    <div class="collapse {{ (Request::is('borrows*') || Request::is('databorrows*') || Request::is('bookings*')) ? 'show' : '' }}" id="borrowsSubmenu">
                                        <ul class="nav flex-column ms-3">
                                            <li class="nav-item"><a class="nav-link {{ Request::is('borrows*') ? 'text-white' : 'text-white-50' }}" href="{{ route('borrows.index') }}">Loaned Books</a></li>
                                            <li class="nav-item"><a class="nav-link {{ Request::is('databorrows*') ? 'text-white' : 'text-white-50' }}" href="{{ route('databorrows.index') }}">Borrow Records</a></li>
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

                                <!-- Search Bar (Center) -->
                                <div class="flex-grow-1 d-flex justify-content-center">
                                    <div class="input-group" style="max-width: 400px;">
                                        <input type="text" id="globalSearch" class="form-control" placeholder="Search...">
                                        <button class="btn btn-outline-secondary" type="button" id="globalSearchBtn">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Page-specific actions (Right) -->
                                <div class="d-flex align-items-center">
                                    <div class="dropdown me-3">
                                        <button id="notif-btn" class="btn btn-light notif-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifikasi">
                                            <i class="bi bi-bell fs-4"></i>
                                            <span id="notif-count" class="badge bg-danger rounded-pill notif-badge">3</span>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end p-2" style="min-width: 320px;">
                                            <div class="list-group">
                                              <a href="#" class="list-group-item list-group-item-action active" aria-current="true">
                                                <div class="d-flex w-100 justify-content-between">
                                                  <h5 class="mb-1">List group item heading</h5>
                                                  <small>3 days ago</small>
                                                </div>
                                                <p class="mb-1">Some placeholder content in a paragraph.</p>
                                                <small>And some small print.</small>
                                              </a>
                                              <a href="#" class="list-group-item list-group-item-action">
                                                <div class="d-flex w-100 justify-content-between">
                                                  <h5 class="mb-1">List group item heading</h5>
                                                  <small class="text-body-secondary">3 days ago</small>
                                                </div>
                                                <p class="mb-1">Some placeholder content in a paragraph.</p>
                                                <small class="text-body-secondary">And some muted small print.</small>
                                              </a>
                                              <a href="#" class="list-group-item list-group-item-action">
                                                <div class="d-flex w-100 justify-content-between">
                                                  <h5 class="mb-1">List group item heading</h5>
                                                  <small class="text-body-secondary">3 days ago</small>
                                                </div>
                                                <p class="mb-1">Some placeholder content in a paragraph.</p>
                                                <small class="text-body-secondary">And some muted small print.</small>
                                              </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Page-specific actions can be added here if needed -->
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
                            <h5 class="text-center mb-4">Lyline Staff Panel</h5>
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
                                            <li class="nav-item"><a class="nav-link {{ Request::is('databorrows*') ? 'text-white' : 'text-white-50' }}" href="{{ route('databorrows.index') }}">Borrow Records</a></li>
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

                                <!-- Search Bar (Center) -->
                                <div class="flex-grow-1 d-flex justify-content-center">
                                    <div class="input-group" style="max-width: 400px;">
                                        <input type="text" id="globalSearch" class="form-control" placeholder="Search...">
                                        <button class="btn btn-outline-secondary" type="button" id="globalSearchBtn">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Page-specific actions (Right) -->
                                <div class="d-flex align-items-center">
                                    <div class="dropdown me-3">
                                        <button id="notif-btn" class="btn btn-light notif-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifikasi">
                                            <i class="bi bi-bell fs-4"></i>
                                            <span id="notif-count" class="badge bg-danger rounded-pill notif-badge">3</span>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end p-2" style="min-width: 320px;">
                                            <div class="list-group">
                                              <a href="#" class="list-group-item list-group-item-action active" aria-current="true">
                                                <div class="d-flex w-100 justify-content-between">
                                                  <h5 class="mb-1">List group item heading</h5>
                                                  <small>3 days ago</small>
                                                </div>
                                                <p class="mb-1">Some placeholder content in a paragraph.</p>
                                                <small>And some small print.</small>
                                              </a>
                                              <a href="#" class="list-group-item list-group-item-action">
                                                <div class="d-flex w-100 justify-content-between">
                                                  <h5 class="mb-1">List group item heading</h5>
                                                  <small class="text-body-secondary">3 days ago</small>
                                                </div>
                                                <p class="mb-1">Some placeholder content in a paragraph.</p>
                                                <small class="text-body-secondary">And some muted small print.</small>
                                              </a>
                                              <a href="#" class="list-group-item list-group-item-action">
                                                <div class="d-flex w-100 justify-content-between">
                                                  <h5 class="mb-1">List group item heading</h5>
                                                  <small class="text-body-secondary">3 days ago</small>
                                                </div>
                                                <p class="mb-1">Some placeholder content in a paragraph.</p>
                                                <small class="text-body-secondary">And some muted small print.</small>
                                              </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Page-specific actions can be added here if needed -->
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
                            <h5 class="text-center mb-4">Lyline User Panel</h5>
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
                                            if ($segments[0] == 'bookings') {
                                                $breadcrumb[] = 'My Bookings';
                                                $links[] = route('bookings.index');
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

                                <!-- Search Bar (Center) -->
                                <div class="flex-grow-1 d-flex justify-content-center">
                                    <div class="input-group" style="max-width: 400px;">
                                        <input type="text" id="globalSearch" class="form-control" placeholder="Search...">
                                        <button class="btn btn-outline-secondary" type="button" id="globalSearchBtn">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Page-specific actions (Right) -->
                                <div class="d-flex align-items-center">
                                    <div class="dropdown me-3">
                                        <button id="notif-btn" class="btn btn-light notif-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifikasi">
                                            <i class="bi bi-bell fs-4"></i>
                                            <span id="notif-count" class="badge bg-danger rounded-pill notif-badge">3</span>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end p-2" style="min-width: 320px;">
                                            <div class="list-group">
                                              <a href="#" class="list-group-item list-group-item-action active" aria-current="true">
                                                <div class="d-flex w-100 justify-content-between">
                                                  <h5 class="mb-1">List group item heading</h5>
                                                  <small>3 days ago</small>
                                                </div>
                                                <p class="mb-1">Some placeholder content in a paragraph.</p>
                                                <small>And some small print.</small>
                                              </a>
                                              <a href="#" class="list-group-item list-group-item-action">
                                                <div class="d-flex w-100 justify-content-between">
                                                  <h5 class="mb-1">List group item heading</h5>
                                                  <small class="text-body-secondary">3 days ago</small>
                                                </div>
                                                <p class="mb-1">Some placeholder content in a paragraph.</p>
                                                <small class="text-body-secondary">And some muted small print.</small>
                                              </a>
                                              <a href="#" class="list-group-item list-group-item-action">
                                                <div class="d-flex w-100 justify-content-between">
                                                  <h5 class="mb-1">List group item heading</h5>
                                                  <small class="text-body-secondary">3 days ago</small>
                                                </div>
                                                <p class="mb-1">Some placeholder content in a paragraph.</p>
                                                <small class="text-body-secondary">And some muted small print.</small>
                                              </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Page-specific actions can be added here if needed -->
                            </div>
                        </nav>
                        <!-- Main Content Area -->
                        <main class="flex-grow-1 py-4">
                            @yield('content')
                        </main>
                    </div>
                </div>
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

    <!-- Notifikasi script: kurangi counter dan tutup dropdown saat klik item -->
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const btn = document.getElementById('notif-btn');
            if (!btn) return;
            const countEl = document.getElementById('notif-count');
            const dropdownMenu = btn.nextElementSibling;
            const dropdownInstance = bootstrap.Dropdown.getOrCreateInstance(btn);

            function updateBadge(n) {
                if (!countEl) return;
                if (n <= 0) {
                    countEl.style.display = 'none';
                } else {
                    countEl.style.display = '';
                    countEl.textContent = String(n);
                }
            }

            updateBadge(parseInt(countEl.textContent, 10) || 0);

            dropdownMenu.querySelectorAll('.list-group-item').forEach(item => {
                item.addEventListener('click', function(e){
                    e.preventDefault();
                    this.classList.remove('active');
                    let current = parseInt(countEl.textContent, 10) || 0;
                    if (current > 0) current = current - 1;
                    updateBadge(current);
                    dropdownInstance.hide();
                });
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
