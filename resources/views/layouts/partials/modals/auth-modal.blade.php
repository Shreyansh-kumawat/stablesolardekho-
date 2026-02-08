<!DOCTYPE html>
<div id="auth-modal" class="modal-overlay hidden" onclick="closeModalOnOutsideClick(event)">
    <div class="modal-container" onclick="event.stopPropagation()">
        <div class="modal-header">
            <button onclick="closeAuthModal()" class="modal-close">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <h2 class="modal-title" id="modal-title">Welcome to Solar Energy</h2>
        </div>

        <div class="modal-body">
            <!-- Login Form -->
            <form id="login-form" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" required class="modal-input" placeholder="your.email@example.com">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                        <input type="password" name="password" required class="modal-input" placeholder="Enter your password">
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="rounded border-gray-300 text-orange-500 focus:ring-orange-500">
                            <span class="ml-2 text-sm text-gray-600">Remember me</span>
                        </label>
                        <a href="#" class="text-sm text-orange-500 hover:text-orange-600">Forgot password?</a>
                    </div>
                    <button type="submit" class="modal-submit-btn">Login</button>
                </div>
            </form>

            <!-- Register Form -->
            <form id="register-form" method="POST" action="{{ route('register') }}" class="hidden">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                        <input type="text" name="name" required class="modal-input" placeholder="John Doe">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" required class="modal-input" placeholder="your.email@example.com">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                        <input type="password" name="password" required class="modal-input" placeholder="Min. 8 characters">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
                        <input type="password" name="password_confirmation" required class="modal-input" placeholder="Confirm password">
                    </div>
                    <button type="submit" class="modal-submit-btn">Register</button>
                </div>
            </form>

            <div class="modal-switch">
                <span id="switch-text">Don't have an account?</span>
                <a href="#" onclick="switchAuthMode(event)">Register here</a>
            </div>
        </div>
    </div>
</div>