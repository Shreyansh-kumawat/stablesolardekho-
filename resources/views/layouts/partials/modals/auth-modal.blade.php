<div id="auth-modal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.6);align-items:center;justify-content:center;padding:16px;" aria-hidden="true">
    <div style="background:#fff;border-radius:16px;width:100%;max-width:420px;max-height:90vh;overflow-y:auto;box-shadow:0 25px 60px rgba(0,0,0,0.3);" onclick="event.stopPropagation()">

        {{-- Header --}}
        <div style="display:flex;align-items:center;justify-content:space-between;padding:20px 24px 0;">
            <h2 id="auth-modal-title" style="font-size:1.2rem;font-weight:700;color:#1f2937;margin:0;">Welcome Back</h2>
            <button id="auth-modal-close" onclick="closeAuthModal()" style="background:none;border:none;cursor:pointer;color:#6b7280;padding:4px;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div style="padding:20px 24px 24px;">

            {{-- Login Form --}}
            <form id="login-form" method="POST" action="{{ route('login') }}">
                @csrf
                <div style="display:flex;flex-direction:column;gap:14px;">
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:600;color:#374151;margin-bottom:5px;">Email</label>
                        <input id="auth-email" type="email" name="email" required
                               style="width:100%;padding:10px 12px;border:1px solid #d1d5db;border-radius:8px;font-size:0.88rem;color:#1f2937;box-sizing:border-box;outline:none;"
                               placeholder="your.email@example.com">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:600;color:#374151;margin-bottom:5px;">Password</label>
                        <input type="password" name="password" required
                               style="width:100%;padding:10px 12px;border:1px solid #d1d5db;border-radius:8px;font-size:0.88rem;color:#1f2937;box-sizing:border-box;outline:none;"
                               placeholder="Enter your password">
                    </div>
                    <div style="display:flex;align-items:center;justify-content:space-between;">
                        <label style="display:flex;align-items:center;gap:6px;font-size:0.82rem;color:#6b7280;cursor:pointer;">
                            <input type="checkbox" name="remember"> Remember me
                        </label>
                        <a href="{{ route('password.request') }}" style="font-size:0.82rem;color:#f97316;text-decoration:none;">Forgot password?</a>
                    </div>
                    <button type="submit"
                            style="width:100%;padding:11px;background:linear-gradient(135deg,#f97316,#ef4444);color:#fff;font-weight:700;font-size:0.9rem;border:none;border-radius:10px;cursor:pointer;">
                        Login
                    </button>
                </div>
            </form>

            {{-- Register Form --}}
            <form id="register-form" method="POST" action="{{ route('register') }}" style="display:none;">
                @csrf
                <div style="display:flex;flex-direction:column;gap:14px;">
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:600;color:#374151;margin-bottom:5px;">Full Name</label>
                        <input type="text" name="name" required
                               style="width:100%;padding:10px 12px;border:1px solid #d1d5db;border-radius:8px;font-size:0.88rem;color:#1f2937;box-sizing:border-box;outline:none;"
                               placeholder="John Doe">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:600;color:#374151;margin-bottom:5px;">Email</label>
                        <input type="email" name="email" required
                               style="width:100%;padding:10px 12px;border:1px solid #d1d5db;border-radius:8px;font-size:0.88rem;color:#1f2937;box-sizing:border-box;outline:none;"
                               placeholder="your.email@example.com">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:600;color:#374151;margin-bottom:5px;">Password</label>
                        <input type="password" name="password" required
                               style="width:100%;padding:10px 12px;border:1px solid #d1d5db;border-radius:8px;font-size:0.88rem;color:#1f2937;box-sizing:border-box;outline:none;"
                               placeholder="Min. 8 characters">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.82rem;font-weight:600;color:#374151;margin-bottom:5px;">Confirm Password</label>
                        <input type="password" name="password_confirmation" required
                               style="width:100%;padding:10px 12px;border:1px solid #d1d5db;border-radius:8px;font-size:0.88rem;color:#1f2937;box-sizing:border-box;outline:none;"
                               placeholder="Confirm password">
                    </div>
                    <button type="submit"
                            style="width:100%;padding:11px;background:linear-gradient(135deg,#f97316,#ef4444);color:#fff;font-weight:700;font-size:0.9rem;border:none;border-radius:10px;cursor:pointer;">
                        Create Account
                    </button>
                </div>
            </form>

            {{-- Divider --}}
            <div style="display:flex;align-items:center;gap:10px;margin:16px 0;">
                <div style="flex:1;height:1px;background:#e5e7eb;"></div>
                <span style="color:#9ca3af;font-size:0.75rem;white-space:nowrap;">or continue with</span>
                <div style="flex:1;height:1px;background:#e5e7eb;"></div>
            </div>

            {{-- Google Login --}}
            <a href="{{ route('auth.google') }}"
               style="display:flex;align-items:center;justify-content:center;gap:10px;width:100%;padding:10px;border:1px solid #e5e7eb;border-radius:10px;background:#fff;color:#374151;font-weight:600;font-size:0.88rem;text-decoration:none;box-sizing:border-box;"
               onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='#fff'">
                <svg width="18" height="18" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Continue with Google
            </a>

            {{-- Switch Login/Register --}}
            <div style="text-align:center;margin-top:16px;font-size:0.82rem;color:#6b7280;">
                <span id="switch-text">Don't have an account?</span>
                <a href="#" onclick="switchAuthMode(event)" style="color:#f97316;font-weight:600;text-decoration:none;margin-left:4px;">Register here</a>
            </div>

        </div>
    </div>
</div>

<script>
function openAuthModal(mode) {
    const modal = document.getElementById('auth-modal');
    const titleEl = document.getElementById('auth-modal-title');
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    const switchText = document.getElementById('switch-text');
    const switchLink = switchText ? switchText.nextElementSibling : null;

    if (!modal) return;
    modal.style.display = 'flex';
    modal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';

    if (mode === 'register') {
        if (titleEl) titleEl.textContent = 'Create Account';
        if (loginForm) loginForm.style.display = 'none';
        if (registerForm) registerForm.style.display = 'block';
        if (switchText) switchText.textContent = 'Already have an account?';
        if (switchLink) switchLink.textContent = 'Login here';
        switchLink && switchLink.setAttribute('data-mode', 'login');
    } else {
        if (titleEl) titleEl.textContent = 'Welcome Back';
        if (loginForm) loginForm.style.display = 'block';
        if (registerForm) registerForm.style.display = 'none';
        if (switchText) switchText.textContent = "Don't have an account?";
        if (switchLink) switchLink.textContent = 'Register here';
        switchLink && switchLink.setAttribute('data-mode', 'register');
    }
}

function closeAuthModal() {
    const modal = document.getElementById('auth-modal');
    if (!modal) return;
    modal.style.display = 'none';
    modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
}

function switchAuthMode(e) {
    e.preventDefault();
    const link = e.target;
    const mode = link.getAttribute('data-mode') || 'register';
    openAuthModal(mode);
}

document.getElementById('auth-modal').addEventListener('click', function(e) {
    if (e.target === this) closeAuthModal();
});
</script>
