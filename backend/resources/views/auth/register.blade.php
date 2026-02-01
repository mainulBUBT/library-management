<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign Up - Library Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            @apply bg-gray-100 text-gray-600 font-normal antialiased;
        }

        .signup-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1rem;
        }

        .signup-card {
            width: 100%;
            max-width: 28rem;
            background: white;
            border-radius: 0.375rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }

        .form-input, .form-select {
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
    </style>
</head>
<body>
    <div class="signup-container">
        <div class="signup-card">
            <div class="p-6 w-full sm:p-8">
                {{-- Logo & Header --}}
                <div class="mb-6">
                    <div class="flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253m0-13C6.832 5.477 5.754 18 4.5 1.253v13"/>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800 text-center">Create Account</h1>
                    <p class="text-gray-500 text-sm text-center mt-2">Join our library today</p>
                </div>

                <form method="POST" action="{{ route('auth.register') }}">
                    @csrf

                    {{-- Name --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" id="name" name="name"
                            class="form-input {{ $errors->has('name') ? 'border-red-500' : '' }}"
                            placeholder="Your full name"
                            value="{{ old('name') }}"
                            required
                            autofocus>
                        @error('name')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" id="email" name="email"
                            class="form-input {{ $errors->has('email') ? 'border-red-500' : '' }}"
                            placeholder="Email address here"
                            value="{{ old('email') }}"
                            required>
                        @error('email')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone (optional)</label>
                        <input type="tel" id="phone" name="phone"
                            class="form-input"
                            placeholder="Phone number">
                    </div>

                    {{-- Address --}}
                    <div class="mb-3">
                        <label for="address" class="form-label">Address (optional)</label>
                        <input type="text" id="address" name="address"
                            class="form-input"
                            placeholder="Your address">
                    </div>

                    {{-- Member Type --}}
                    <div class="mb-3">
                        <label for="member_type" class="form-label">I am a</label>
                        <select id="member_type" name="member_type"
                            class="form-select {{ $errors->has('member_type') ? 'border-red-500' : '' }}"
                            required>
                            <option value="">Select member type</option>
                            <option value="student">Student</option>
                            <option value="teacher">Teacher</option>
                            <option value="staff">Staff</option>
                            <option value="public">Public Member</option>
                        </select>
                        @error('member_type')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password"
                            class="form-input {{ $errors->has('password') ? 'border-red-500' : '' }}"
                            placeholder="Minimum 8 characters"
                            required>
                        @error('password')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="form-input {{ $errors->has('password_confirmation') ? 'border-red-500' : '' }}"
                            placeholder="Confirm your password"
                            required>
                        @error('password_confirmation')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Terms --}}
                    <div class="flex items-start gap-2 mb-4">
                        <input type="checkbox" name="terms" id="terms" required
                            class="w-4 h-4 text-indigo-600 bg-white border-gray-300 rounded focus:ring-indigo-600 focus:outline-none focus:ring-2 mt-0.5">
                        <label for="terms" class="text-sm text-gray-600">
                            I agree to the <a href="#" class="link">Terms of Service</a> and <a href="#" class="link">Privacy Policy</a>
                        </label>
                    </div>

                    {{-- Submit Button --}}
                    <div>
                        <button type="submit" class="btn-primary">
                            Create Account
                        </button>

                        <div class="text-center mt-4">
                            <span class="text-sm text-gray-600">Already have an account?</span>
                            <a href="{{ route('auth.login') }}" class="link">Sign in</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Admin Login Link --}}
        <p class="text-gray-400 text-xs mt-4">
            <a href="{{ route('admin.login') }}" class="text-gray-400 hover:text-gray-600">Admin Login</a>
        </p>
    </div>
</body>
</html>
