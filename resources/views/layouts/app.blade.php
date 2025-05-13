<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Nova Blog') }} - @yield('title', 'Home')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Scripts and styles-->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="header">
            <div class="header-container">
                <div class="header-brand-container">
                    <a href="{{ url('/') }}" class="header-brand">Nova Blog</a>
                    <button class="header-toggler" id="headerToggler" aria-label="Toggle navigation">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                </div>
                
                <div class="header-menu" id="headerMenu">
                    <ul class="header-nav">
                        <li class="nav-item">
                            <a href="{{ route('home') }}" class="nav-link">Home</a>
                        </li>
                        @auth
                        <li class="nav-item">
                            <a href="{{ route('posts.create') }}" class="nav-link">New Post</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('posts.my-posts') }}" class="nav-link">My Posts</a>
                        </li>
                        @endauth
                        <li class="nav-item dropdown">
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
                        @else
                            <div class="dropdown-toggle nav-link" id="userDropdown">
                                <span>{{ Auth::user()->name }}</span>
                            </div>
                            <div class="dropdown-menu" id="userDropdownMenu">
                                <a href="{{ route('users.show', Auth::user()->id) }}" class="dropdown-item">My Profile</a>
                                <a href="{{ route('users.edit', Auth::user()->id) }}" class="dropdown-item">Edit Profile</a>
                                <a href="{{ route('logout') }}" class="dropdown-item" 
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                    </ul>
                    
                    <form class="search-form" action="{{ route('users.search') }}" method="GET">
                        <input type="search" name="query" aria-label="Search" class="search-input" placeholder="Search users...">
                        <button type="submit" class="search-button" type='Submit'>Search</button>
                    </form>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
@yield('scripts')  {{-- This must be here --}}
</body>
</html>