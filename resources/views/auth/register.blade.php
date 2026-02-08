<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Solar Panel Installation') }}</title>
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="{{ asset('css/themes/solar-energy.css') }}" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-light-bg">
    <div class="min-h-screen">
        <!-- Navigation -->
        @include('layouts.partials.navigation')

        <!-- Main Content -->
        <main class="flex justify-center items-center h-screen">
            <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-md">
                <h2 class="text-2xl font-bold text-center text-primary-blue">Create Your Account</h2>
                <form method="POST" action="{{ route('register') }}" class="mt-4">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-semibold text-gray-700">Full Name</label>
                        <input type="text" name="name" id="name" required class="modal-input" placeholder="John Doe">
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-semibold text-gray-700">Email</label>
                        <input type="email" name="email" id="email" required class="modal-input" placeholder="your.email@example.com">
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                        <input type="password" name="password" id="password" required class="modal-input" placeholder="Min. 8 characters">
                    </div>
                    <div class="mb-4">
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required class="modal-input" placeholder="Confirm password">
                    </div>
                    <button type="submit" class="modal-submit-btn w-full">Register</button>
                </form>
                <div class="mt-4 text-center">
                    <span class="text-sm text-gray-600">Already have an account?</span>
                    <a href="{{ route('login') }}" class="text-primary-orange font-semibold">Login here</a>
                </div>
            </div>
        </main>

        <!-- Footer -->
        @include('layouts.partials.footer')
    </div>
</body>

</html>