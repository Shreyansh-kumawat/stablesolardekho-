<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Installation Partner - {{ config('app.name', 'Solar Dashboard') }}</title>
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="{{ asset('css/themes/solar-energy.css') }}" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-light-bg">
    <div class="min-h-screen">
        <!-- Navigation Bar -->
        @include('layouts.partials.navigation')

        <!-- Main Content -->
        <main class="py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold text-dark-text">Installation Partner</h1>
                <p class="mt-4 text-lg text-light-text">
                    Welcome to our Installation Partner page. Here you can find all the information you need to become a partner in our solar panel installation program. We are committed to providing high-quality solar solutions and support to our partners.
                </p>
                <div class="mt-6">
                    <h2 class="text-2xl font-semibold text-dark-text">Why Partner with Us?</h2>
                    <ul class="list-disc list-inside mt-2 text-light-text">
                        <li>Access to exclusive training and resources.</li>
                        <li>Competitive pricing on solar products.</li>
                        <li>Dedicated support from our team.</li>
                        <li>Marketing materials to help you succeed.</li>
                    </ul>
                </div>
                <div class="mt-6">
                    <h2 class="text-2xl font-semibold text-dark-text">Get Started</h2>
                    <p class="mt-2 text-light-text">
                        If you're interested in becoming an installation partner, please fill out the form below, and our team will get back to you shortly.
                    </p>
                    <!-- Partner Registration Form -->
                    <form action="{{ route('partner.register') }}" method="POST" class="mt-4">
                        @csrf
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-700">Full Name</label>
                                <input type="text" name="name" id="name" required class="modal-input" placeholder="Your Name">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-700">Email</label>
                                <input type="email" name="email" id="email" required class="modal-input" placeholder="your.email@example.com">
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-semibold text-gray-700">Phone Number</label>
                                <input type="text" name="phone" id="phone" required class="modal-input" placeholder="Your Phone Number">
                            </div>
                            <button type="submit" class="modal-submit-btn">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>

        <!-- Footer -->
        @include('layouts.partials.footer')
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>