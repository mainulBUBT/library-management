<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign In - Library Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-600">
    <div class="auth-container">
        <div class="auth-card">
            {{-- Header --}}
            <div class="auth-header">
                <div class="auth-logo">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253m0-13C6.832 5.477 5.754 18 4.5 1.253v13"/>
                    </svg>
                </div>
                <h1 class="auth-title">Library Management</h1>
                <p class="auth-subtitle">Sign in to your account to continue</p>
            </div>

            {{-- Form --}}
            <div class="auth-body">
                @if (session('error'))
                    <div class="auth-alert auth-alert-error">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('auth.login') }}">
                    @csrf

                    {{-- Email --}}
                    <div class="auth-form-group mb-5">
                        <label for="email" class="auth-label">Email address</label>
                        <input type="email" id="email" name="email"
                            class="auth-input {{ $errors->has('email') ? 'auth-input-error' : '' }}"
                            placeholder="you@example.com"
                            value="{{ old('email') }}"
                            required
                            autofocus>
                        @error('email')
                            <p class="auth-error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="auth-form-group mb-5">
                        <label for="password" class="auth-label">Password</label>
                        <input type="password" id="password" name="password"
                            class="auth-input {{ $errors->has('password') ? 'auth-input-error' : '' }}"
                            placeholder="••••••••"
                            required>
                        @error('password')
                            <p class="auth-error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember Me & Forgot Password --}}
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <input type="checkbox"
                                class="auth-checkbox"
                                id="remember"
                                name="remember">
                            <label class="auth-checkbox-label" for="remember">Remember me</label>
                        </div>
                        <a href="#" class="auth-link text-sm">Forgot password?</a>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="auth-btn">
                        Sign in
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Toast Container --}}
    <div id="toast-container" class="toast-container"></div>

    {{-- Toast functionality --}}
    <script src="{{ asset('js/admin.js') }}"></script>
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('{{ session('success') }}', 'success', 'Welcome back!', 4000);
            });
        </script>
    @endif
</body>
</html>
