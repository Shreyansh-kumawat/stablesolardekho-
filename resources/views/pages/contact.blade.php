<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Contact Us - Solar Panel Installation</title>
    <link href="{{ asset('css/themes/solar-energy.css') }}" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-light-bg">
    <div class="min-h-screen">
        @include('layouts.partials.navigation')

        <main class="py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold text-dark-text mb-6">Contact Us</h1>
                <p class="text-lg text-light-text mb-4">We'd love to hear from you! Please fill out the form below to get in touch.</p>

                <form action="{{ route('contact.submit') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Name</label>
                        <input type="text" id="name" name="name" required class="modal-input" placeholder="Your Name">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email</label>
                        <input type="email" id="email" name="email" required class="modal-input" placeholder="your.email@example.com">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="message">Message</label>
                        <textarea id="message" name="message" required class="modal-input" rows="4" placeholder="Your message..."></textarea>
                    </div>
                    <div class="flex items-center justify-between">
                        <button type="submit" class="modal-submit-btn">Send Message</button>
                    </div>
                </form>
            </div>
        </main>

        @include('layouts.partials.footer')
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        // Toastr Configuration
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-top-right',
            timeOut: 5000
        };

        // Session messages
        @if (Session::has('success'))
            toastr.success("{{ Session::get('success') }}", 'Success');
        @endif

        @if (Session::has('error'))
            toastr.error("{{ Session::get('error') }}", 'Error');
        @endif
    </script>
</body>

</html>