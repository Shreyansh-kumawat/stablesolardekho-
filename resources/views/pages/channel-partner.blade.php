<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Channel Partner - Solar Dashboard</title>
    <link href="{{ asset('css/themes/solar-energy.css') }}" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-light-bg">
    <div class="min-h-screen">
        @include('layouts.partials.navigation')

        <main class="py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold text-primary-blue">Channel Partner</h1>
                <p class="mt-4 text-lg text-dark-text">
                    Welcome to the Channel Partner page. Here you can find information about our partnerships and how we work together to promote solar energy solutions.
                </p>
                <div class="mt-6">
                    <h2 class="text-2xl font-semibold text-secondary-blue">Our Partners</h2>
                    <ul class="list-disc list-inside mt-2">
                        <li class="text-dark-text">Partner 1: Leading Solar Solutions</li>
                        <li class="text-dark-text">Partner 2: Eco-Friendly Installations</li>
                        <li class="text-dark-text">Partner 3: Renewable Energy Experts</li>
                    </ul>
                </div>
            </div>
        </main>

        @include('layouts.partials.footer')
    </div>

    <script src="{{ asset('js/components/navigation.js') }}"></script>
</body>

</html>