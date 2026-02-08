<!DOCTYPE html>
<footer class="bg-green-800 text-white py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col items-center">
            <div class="text-center">
                <h3 class="text-lg font-semibold">Join the Solar Revolution</h3>
                <p class="mt-2">Harness the power of the sun for a sustainable future.</p>
            </div>
            <div class="mt-4">
                <a href="{{ route('contactUs') }}" class="text-white hover:text-yellow-400">Contact Us</a>
                <span class="mx-2">|</span>
                <a href="{{ route('ourTeam') }}" class="text-white hover:text-yellow-400">Our Team</a>
                <span class="mx-2">|</span>
                <a href="{{ route('installationPhotos') }}" class="text-white hover:text-yellow-400">Installation Photos</a>
            </div>
            <div class="mt-4">
                <p>&copy; {{ date('Y') }} Solar Dashboard. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>