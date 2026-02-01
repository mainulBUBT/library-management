<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' - ' : '' }}Library Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Admin Panel CSS --}}
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-600">
    {{-- App Layout --}}
    <div id="app-layout">
        {{-- Sidebar Overlay --}}
        <div id="sidebar-overlay" class="sidebar-overlay" onclick="closeSidebar()"></div>

        {{-- Vertical Navbar (Sidebar) --}}
        <nav id="navbar-vertical" class="navbar-vertical">
            <div id="sidebar-scrollable">
                {{-- Brand Logo --}}
                <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                    <svg class="text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span class="ml-2 text-xl font-bold text-gray-900">Library</span>
                </a>

                {{-- Navbar Nav --}}
                <ul class="navbar-nav">
                    {{-- Dashboard --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Dashboard
                        </a>
                    </li>

                    {{-- Catalog Section --}}
                    <li class="nav-item">
                        <div class="navbar-heading">Catalog</div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.resources.*') ? 'active' : '' }}" href="{{ route('admin.resources.index') }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            Resources
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            Categories
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.authors.*') ? 'active' : '' }}" href="{{ route('admin.authors.index') }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Authors
                        </a>
                    </li>

                    {{-- Circulation Section --}}
                    <li class="nav-item">
                        <div class="navbar-heading">Circulation</div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.loans.*') ? 'active' : '' }}" href="{{ route('admin.loans.index') }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                            Loans
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.reservations.*') ? 'active' : '' }}" href="{{ route('admin.reservations.index') }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                            </svg>
                            Reservations
                        </a>
                    </li>

                    {{-- Members Section --}}
                    <li class="nav-item">
                        <div class="navbar-heading">Members</div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.members.*') ? 'active' : '' }}" href="{{ route('admin.members.index') }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            Members
                        </a>
                    </li>

                    {{-- Financial Section --}}
                    <li class="nav-item">
                        <div class="navbar-heading">Financial</div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.fines.*') ? 'active' : '' }}" href="{{ route('admin.fines.index') }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Fines
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}" href="{{ route('admin.payments.index') }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Payments
                        </a>
                    </li>

                    {{-- Settings Section --}}
                    <li class="nav-item">
                        <div class="navbar-heading">Settings</div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Settings
                        </a>
                    </li>
                </ul>

                {{-- User Section --}}
                <div class="sidebar-user">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                <span class="text-indigo-600 font-semibold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                            </div>
                        </div>
                        <div class="ml-3 flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ ucfirst(str_replace('_', ' ', auth()->user()->getAttributes()['role'] ?? 'member')) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        {{-- App Layout Content --}}
        <div id="app-layout-content">
            {{-- Top Navbar --}}
            <div class="header w-full">
                <nav class="bg-white px-4 sm:px-6 py-[10px] flex items-center w-full shadow-sm">
                    <div class="flex items-center gap-3 min-w-0 flex-1">
                        {{-- Hamburger Menu --}}
                        <a id="nav-toggle" href="#" class="hamburger-button">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                            </svg>
                        </a>

                        {{-- Search --}}
                        {{-- <div class="flex-1 max-w-xl min-w-[10rem]">
                            <form class="flex items-center">
                                <input type="search"
                                    class="border border-gray-300 text-gray-900 rounded focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2 px-3 disabled:opacity-50 disabled:pointer-events-none"
                                    placeholder="Search..." />
                            </form>
                        </div> --}}
                    </div>

                    {{-- Right Nav Items --}}
                    <ul class="flex items-center gap-2 sm:gap-4 ml-auto">
                        {{-- Notifications --}}
                        <li class="relative">
                            <button type="button" class="text-gray-600 hover:text-gray-800 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                                </svg>
                            </button>
                            <span class="absolute top-0 right-0 h-2 w-2 rounded-full bg-red-500"></span>
                        </li>

                        {{-- User Dropdown --}}
                        <li class="relative">
                            <button type="button" id="user-menu-button" class="rounded-full">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <span class="text-indigo-600 font-semibold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                                </div>
                            </button>
                            <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 border-2 border-gray-300 z-50">
                                <div class="px-4 py-2 border-b border-gray-200">
                                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                                </div>
                                <a href="{{ route('admin.settings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                                <form id="logout-form" method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button id="logout-open" type="button" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sign out</button>
                                </form>
                            </div>
                        </li>
                    </ul>
                </nav>
            </div>

            {{-- Logout Confirmation Modal --}}
            <div id="logout-modal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4">
                <div id="logout-modal-overlay" class="absolute inset-0 bg-black/50"></div>
                <div class="relative z-10 w-full max-w-xs rounded-lg bg-white p-5 shadow-xl border-2 border-gray-300">
                    <div class="flex items-start gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-red-50 text-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A3.75 3.75 0 0012 1.5h0A3.75 3.75 0 008.25 5.25V9m-.75 12h9a2.25 2.25 0 002.25-2.25V11.25A2.25 2.25 0 0016.5 9h-9a2.25 2.25 0 00-2.25 2.25v7.5A2.25 2.25 0 007.5 21z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900">Confirm sign out</h3>
                            <p class="mt-0.5 text-xs text-gray-500">You will be returned to the login page.</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center justify-end gap-2">
                        <button type="button" data-logout-close class="rounded-md border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50">No</button>
                        <button id="logout-confirm" type="button" class="rounded-md bg-red-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-red-700">Yes</button>
                    </div>
                </div>
            </div>

            {{-- Page Content --}}
            <div class="p-4 sm:p-6">
                @yield('content')
            </div>
        </div>
    </div>

    @stack('scripts')
    {{-- Admin Panel JavaScript --}}
    <script src="{{ asset('js/admin.js') }}"></script>
</body>
</html>
