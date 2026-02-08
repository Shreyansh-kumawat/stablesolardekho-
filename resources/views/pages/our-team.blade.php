<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Our Team - Solar Panel Installation</title>
    <link href="{{ asset('css/themes/solar-energy.css') }}" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-light-bg">
    <div class="min-h-screen">
        @include('layouts.partials.navigation')

        <main class="py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold text-dark-text mb-6">Meet Our Team</h1>
                <p class="text-lg text-light-text mb-8">We are a dedicated team of professionals committed to providing the best solar panel installation services. Our expertise and passion for renewable energy drive us to deliver exceptional results for our clients.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-white shadow-lg rounded-lg p-6">
                        <img src="{{ asset('images/team/member1.jpg') }}" alt="Team Member 1" class="rounded-full h-32 w-32 mx-auto mb-4">
                        <h2 class="text-xl font-semibold text-dark-text">John Doe</h2>
                        <p class="text-gray-600">Project Manager</p>
                    </div>
                    <div class="bg-white shadow-lg rounded-lg p-6">
                        <img src="{{ asset('images/team/member2.jpg') }}" alt="Team Member 2" class="rounded-full h-32 w-32 mx-auto mb-4">
                        <h2 class="text-xl font-semibold text-dark-text">Jane Smith</h2>
                        <p class="text-gray-600">Lead Installer</p>
                    </div>
                    <div class="bg-white shadow-lg rounded-lg p-6">
                        <img src="{{ asset('images/team/member3.jpg') }}" alt="Team Member 3" class="rounded-full h-32 w-32 mx-auto mb-4">
                        <h2 class="text-xl font-semibold text-dark-text">Emily Johnson</h2>
                        <p class="text-gray-600">Sales Consultant</p>
                    </div>
                    <!-- Add more team members as needed -->
                </div>
            </div>
        </main>

        @include('layouts.partials.footer')
    </div>

    <script src="{{ asset('js/components/navigation.js') }}"></script>
</body>

</html>