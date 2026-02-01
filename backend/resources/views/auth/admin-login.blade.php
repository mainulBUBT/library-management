<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - Library Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            @apply bg-gray-100 text-gray-600 font-normal antialiased;
        }

        .admin-login-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1rem;
        }

        .admin-login-card {
            width: 100%;
            max-width: 28rem;
            background: white;
            border-radius: 0.375rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }

        .admin-header {
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
            border-radius: 0.375rem 0.375rem 0 0;
            padding: 1.5rem 2rem;
            text-align: center;
            color: white;
        }

        .form-input {
            @apply border border-gray-300 text-gray-900 rounded focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2 px-3 disabled:opacity-50 disabled:pointer-events-none;
        }

        .form-label {
            @apply inline-block mb-2 text-sm font-medium text-gray-700;
        }

        .btn-primary {
            @apply bg-indigo-600 text-white border-indigo-600 hover:bg-indigo-700 hover:border-indigo-700 active:bg-indigo-800 active:border-indigo-800 focus:outline-none focus:ring-4 focus:ring-indigo-300 rounded-md font-semibold py-2 px-4 text-center transition w-full cursor-pointer;
        }

        .link {
            @apply text-indigo-600 hover:text-indigo-700 text-sm font-medium;
        }

        .alert {
            @apply p-3 rounded-md mb-4 text-sm;
        }

        .alert-error {
            @apply bg-red-50 text-red-800 border border-red-200;
        }

        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            font-size: 0.625rem;
            font-weight: 600;
            border-radius: 0.25rem;
            background: rgba(255,255,255,0.2);
            color: white;
        }
    </style>
</head>
<body>
    <div class="admin-login-container">
        <div class="admin-login-card">
            {{-- Header --}}
            <div class="admin-header">
                <div class="flex items-center justify-center mb-2">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253m0-13C6.832 5.477 5.754 18 4.5 1.253v13"/>
                    </svg>
                </div>
                <h1 class="text-xl font-bold">Library Admin</h1>
                <p class="text-white/80 text-xs mt-1">Secure access for administrators</p>
                <span class="badge mt-2">Admin Portal</span>
            </div>

            <div class="p-6 sm:p-8">
                @if (session('error'))
                    <div class="alert alert-error">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login.submit') }}">
                    @csrf

                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" id="email" name="email"
                            class="form-input {{ $errors->has('email') ? 'border-red-500' : '' }}"
                            placeholder="Email address"
                            value="{{ old('email') }}"
                            required
                            autofocus>
                        @error('email')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password"
                            class="form-input {{ $errors->has('password') ? 'border-red-500' : '' }}"
                            placeholder="Password"
                            required>
                        @error('password')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember Me --}}
                    <div class="flex items-center mb-4">
                        <input type="checkbox" name="remember"
                            class="w-4 h-4 text-indigo-600 bg-white border-gray-300 rounded focus:ring-indigo-600 focus:outline-none focus:ring-2">
                        <label class="inline-block ml-2 text-sm text-gray-600">Remember me</label>
                    </div>

                    {{-- Submit Button --}}
                    <div>
                        <button type="submit" class="btn-primary">
                            <span class="flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4 4m-4-4v8m-4 4h8"/>
                                </svg>
                                Sign In
                            </span>
                        </button>
                    </div>
                </form>

                {{-- Demo Credentials --}}
                <div class="mt-4 p-3 bg-gray-50 rounded-lg text-sm text-gray-600">
                    <p class="font-medium text-gray-700 mb-2">Demo Credentials:</p>
                    <p>Email: <code class="bg-white px-2 py-0.5 rounded text-xs">admin@library.com</code></p>
                    <p>Password: <code class="bg-white px-2 py-0.5 rounded text-xs">password</code></p>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-6 pb-6 text-center border-t border-gray-100">
                <p class="text-gray-400 text-xs">
                    <a href="{{ route('auth.login') }}" class="text-gray-400 hover:text-gray-600">← Back to Member Login</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
