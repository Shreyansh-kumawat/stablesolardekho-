<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Login — {{ config('app.name', 'Solar Panel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="{{ asset('css/themes/solar-energy.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="/assets/css/toastr.min.css">
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
        body {
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            background:
                radial-gradient(900px 500px at 0% 0%, rgba(249,115,22,0.12), transparent 55%),
                radial-gradient(700px 400px at 100% 100%, rgba(249,115,22,0.07), transparent 50%),
                linear-gradient(180deg,#0b1117,#0f172a);
            font-family:Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",Roboto,sans-serif;
            padding:1rem;
        }
        .auth-shell {
            width:100%;
            max-width:960px;
            display:grid;
            grid-template-columns:1fr 420px;
            border-radius:12px;
            overflow:hidden;
            box-shadow:0 24px 60px rgba(0,0,0,0.55);
            border:1px solid rgba(255,255,255,0.08);
        }
        /* Left brand panel */
        .auth-brand {
            background:linear-gradient(160deg,#111827,#0b1117);
            padding:48px 40px;
            display:flex;
            flex-direction:column;
            justify-content:space-between;
            border-right:1px solid rgba(255,255,255,0.07);
            position:relative;
            overflow:hidden;
        }
        .auth-brand::before {
            content:'';
            position:absolute;
            top:-80px;right:-80px;
            width:280px;height:280px;
            border-radius:50%;
            background:radial-gradient(circle,rgba(249,115,22,0.18),transparent 70%);
            pointer-events:none;
        }
        .brand-logo {
            display:flex;
            align-items:center;
            gap:14px;
        }
        .brand-logo img {
            height:52px;
            width:auto;
            object-fit:contain;
        }
        .brand-logo-name {
            font-size:1.25rem;
            font-weight:800;
            color:#fff;
            letter-spacing:-0.02em;
        }
        .brand-tagline {
            font-size:0.78rem;
            color:#94a3b8;
            margin-top:2px;
        }
        .brand-headline {
            margin-top:40px;
        }
        .brand-headline h1 {
            font-size:2rem;
            font-weight:900;
            color:#fff;
            line-height:1.15;
            letter-spacing:-0.03em;
        }
        .brand-headline h1 span { color:#f97316; }
        .brand-headline p {
            margin-top:12px;
            font-size:0.9rem;
            color:#94a3b8;
            line-height:1.6;
            max-width:300px;
        }
        .brand-features {
            margin-top:32px;
            display:flex;
            flex-direction:column;
            gap:10px;
        }
        .brand-feat {
            display:flex;
            align-items:center;
            gap:10px;
            font-size:0.84rem;
            color:#cbd5e1;
        }
        .feat-dot {
            width:7px;height:7px;border-radius:50%;
            background:#f97316;
            flex-shrink:0;
        }
        .brand-bottom {
            margin-top:40px;
            font-size:0.76rem;
            color:#475569;
        }
        /* Right form panel */
        .auth-form-panel {
            background:#0f172a;
            padding:48px 36px;
            display:flex;
            flex-direction:column;
            justify-content:center;
        }
        .form-head { margin-bottom:28px; }
        .form-head h2 {
            font-size:1.4rem;
            font-weight:800;
            color:#f1f5f9;
            letter-spacing:-0.02em;
        }
        .form-head p {
            margin-top:5px;
            font-size:0.83rem;
            color:#64748b;
        }
        .f-group { margin-bottom:16px; }
        .f-label {
            display:block;
            font-size:0.78rem;
            font-weight:600;
            color:#94a3b8;
            margin-bottom:6px;
            letter-spacing:0.02em;
            text-transform:uppercase;
        }
        .f-input {
            width:100%;
            background:rgba(255,255,255,0.05);
            border:1px solid rgba(255,255,255,0.1);
            border-radius:8px;
            padding:10px 14px;
            color:#e2e8f0;
            font-size:0.88rem;
            outline:none;
            transition:border-color 0.2s,box-shadow 0.2s;
        }
        .f-input::placeholder { color:#475569; }
        .f-input:focus {
            border-color:#f97316;
            box-shadow:0 0 0 3px rgba(249,115,22,0.15);
        }
        .f-error { font-size:0.78rem; color:#f87171; margin-top:5px; }
        .f-row {
            display:flex;
            align-items:center;
            justify-content:space-between;
            margin-bottom:18px;
        }
        .f-check {
            display:flex;
            align-items:center;
            gap:7px;
            font-size:0.82rem;
            color:#64748b;
            cursor:pointer;
        }
        .f-check input { accent-color:#f97316; width:15px;height:15px; }
        .f-forgot {
            font-size:0.82rem;
            color:#f97316;
            text-decoration:none;
            font-weight:600;
        }
        .f-forgot:hover { text-decoration:underline; }
        .btn-login {
            width:100%;
            background:linear-gradient(135deg,#f97316,#ea580c);
            color:#fff;
            font-weight:800;
            font-size:0.92rem;
            padding:12px;
            border:none;
            border-radius:8px;
            cursor:pointer;
            letter-spacing:0.01em;
            box-shadow:0 8px 24px rgba(249,115,22,0.3);
            transition:transform 0.15s,box-shadow 0.15s;
        }
        .btn-login:hover {
            transform:translateY(-2px);
            box-shadow:0 12px 32px rgba(249,115,22,0.4);
        }
        .f-divider {
            height:1px;
            background:rgba(255,255,255,0.07);
            margin:20px 0;
        }
        .f-signup-row {
            text-align:center;
            font-size:0.83rem;
            color:#64748b;
        }
        .f-signup-row a {
            color:#f97316;
            font-weight:700;
            text-decoration:none;
            margin-left:5px;
        }
        .f-signup-row a:hover { text-decoration:underline; }
        .pass-wrap { position:relative; }
        .pass-toggle {
            position:absolute;right:12px;top:50%;transform:translateY(-50%);
            background:none;border:none;color:#64748b;cursor:pointer;
            font-size:0.78rem;font-weight:600;
        }
        @media(max-width:720px){
            .auth-shell { grid-template-columns:1fr; }
            .auth-brand { padding:28px 24px; }
            .brand-headline h1 { font-size:1.5rem; }
            .auth-form-panel { padding:28px 24px; }
        }
    </style>
</head>
<body>
    <div class="auth-shell">
        <!-- Brand panel -->
        <div class="auth-brand">
            <div class="brand-logo">
                <img src="{{ asset('stable/images/logo1.png') }}" alt="{{ config('app.name') }}">
                <div>
                    <div class="brand-logo-name">{{ config('app.name','Solar Panel') }}</div>
                    <div class="brand-tagline">Solar Energy Solutions</div>
                </div>
            </div>

            <div class="brand-headline">
                <h1>Power Your Home<br>with <span>Clean Energy</span></h1>
                <p>Manage your solar installations, track performance, and monitor your savings — all in one place.</p>
            </div>

            <div class="brand-features">
                <div class="brand-feat"><span class="feat-dot"></span>Free site visit &amp; consultation</div>
                <div class="brand-feat"><span class="feat-dot"></span>10-year workmanship warranty</div>
                <div class="brand-feat"><span class="feat-dot"></span>Easy financing options available</div>
                <div class="brand-feat"><span class="feat-dot"></span>Trusted by 1000+ homeowners</div>
            </div>

            <div class="brand-bottom">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</div>
        </div>

        <!-- Form panel -->
        <div class="auth-form-panel">
            <div class="form-head">
                <h2>Welcome back</h2>
                <p>Sign in to access your dashboard</p>
            </div>

            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                <div class="f-group">
                    <label class="f-label" for="email">Email Address</label>
                    <input class="f-input" id="email" type="email" name="email"
                        value="{{ old('email') }}" required autofocus placeholder="you@example.com">
                    @error('email') <div class="f-error">{{ $message }}</div> @enderror
                </div>

                <div class="f-group">
                    <label class="f-label" for="password">Password</label>
                    <div class="pass-wrap">
                        <input class="f-input" id="password" type="password" name="password"
                            required placeholder="Enter your password">
                        <button type="button" class="pass-toggle" id="togglePwd">Show</button>
                    </div>
                    @error('password') <div class="f-error">{{ $message }}</div> @enderror
                </div>

                <div class="f-row">
                    <label class="f-check">
                        <input type="checkbox" name="remember"> Remember me
                    </label>
                    <a href="{{ route('password.request') }}" class="f-forgot">Forgot password?</a>
                </div>

                <button type="submit" class="btn-login">Sign In</button>

                <div style="display:flex;align-items:center;gap:10px;margin:20px 0;">
                    <div style="flex:1;height:1px;background:rgba(255,255,255,0.07);"></div>
                    <span style="color:#64748b;font-size:0.75rem;white-space:nowrap;">or continue with</span>
                    <div style="flex:1;height:1px;background:rgba(255,255,255,0.07);"></div>
                </div>

                <a href="{{ route('auth.google') }}"
                   style="display:flex;align-items:center;justify-content:center;gap:10px;width:100%;padding:11px;border:1px solid rgba(255,255,255,0.12);border-radius:8px;background:rgba(255,255,255,0.05);color:#e2e8f0;font-weight:600;font-size:0.88rem;text-decoration:none;transition:background 0.15s,border-color 0.15s;"
                   onmouseover="this.style.background='rgba(255,255,255,0.1)';this.style.borderColor='rgba(255,255,255,0.2)';" onmouseout="this.style.background='rgba(255,255,255,0.05)';this.style.borderColor='rgba(255,255,255,0.12)';">
                    <svg width="18" height="18" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Continue with Google
                </a>

                <div class="f-divider"></div>

                <div class="f-signup-row">
                    Don't have an account?
                    <a href="{{ route('register') }}">Create one free</a>
                </div>
            </form>
        </div>
    </div>

    <script src="/assets/js/toastr.min.js"></script>
    <script>
        document.getElementById('togglePwd').addEventListener('click', function(){
            var p = document.getElementById('password');
            if(p.type === 'password'){ p.type='text'; this.textContent='Hide'; }
            else { p.type='password'; this.textContent='Show'; }
        });
        toastr.options = { closeButton:true, progressBar:true, positionClass:'toast-top-right', timeOut:5000 };
        @if(Session::has('success')) toastr.success({!! json_encode(Session::get('success')) !!},'Success'); @endif
        @if(Session::has('error')) toastr.error({!! json_encode(Session::get('error')) !!},'Error'); @endif
        @if($errors->any()) @foreach($errors->all() as $error) toastr.error({!! json_encode($error) !!},'Error'); @endforeach @endif
    </script>
</body>
</html>
