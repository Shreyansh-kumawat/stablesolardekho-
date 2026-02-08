<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Installation Photos - {{ config('app.name', 'Solar Dashboard') }}</title>
    <link href="{{ asset('css/themes/solar-energy.css') }}" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-light-bg">
    <div class="min-h-screen">
        <!-- Navigation -->
        @include('layouts.partials.navigation')

        <!-- Main Content -->
        <main class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold text-primary-blue mb-6">Installation Photos</h1>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Example Photo Item -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <img src="{{ asset('images/photo1.jpg') }}" alt="Installation Photo 1" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h2 class="text-lg font-semibold">Installation Title 1</h2>
                            <p class="text-gray-600">Description of the installation.</p>
                        </div>
                    </div>
                    <!-- Repeat for more photos -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <img src="{{ asset('images/photo2.jpg') }}" alt="Installation Photo 2" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h2 class="text-lg font-semibold">Installation Title 2</h2>
                            <p class="text-gray-600">Description of the installation.</p>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <img src="{{ asset('images/photo3.jpg') }}" alt="Installation Photo 3" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h2 class="text-lg font-semibold">Installation Title 3</h2>
                            <p class="text-gray-600">Description of the installation.</p>
                        </div>
                    </div>
                    <!-- Add more photo items as needed -->
                </div>
            </div>
        </main>

        <!-- Footer -->
        @include('layouts.partials.footer')
    </div>

    <script src="{{ asset('js/components/modal.js') }}"></script>
</body>

</html>