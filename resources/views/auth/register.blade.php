<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Account — {{ config('app.name', 'Solar Panel') }}</title>
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
        .brand-logo { display:flex;align-items:center;gap:14px; }
        .brand-logo img { height:52px;width:auto;object-fit:contain; }
        .brand-logo-name { font-size:1.25rem;font-weight:800;color:#fff;letter-spacing:-0.02em; }
        .brand-tagline { font-size:0.78rem;color:#94a3b8;margin-top:2px; }
        .brand-headline { margin-top:40px; }
        .brand-headline h1 { font-size:2rem;font-weight:900;color:#fff;line-height:1.15;letter-spacing:-0.03em; }
        .brand-headline h1 span { color:#f97316; }
        .brand-headline p { margin-top:12px;font-size:0.9rem;color:#94a3b8;line-height:1.6;max-width:300px; }
        .brand-features { margin-top:32px;display:flex;flex-direction:column;gap:10px; }
        .brand-feat { display:flex;align-items:center;gap:10px;font-size:0.84rem;color:#cbd5e1; }
        .feat-dot { width:7px;height:7px;border-radius:50%;background:#f97316;flex-shrink:0; }
        .brand-bottom { margin-top:40px;font-size:0.76rem;color:#475569; }
        .auth-form-panel {
            background:#0f172a;
            padding:36px;
            display:flex;
            flex-direction:column;
            justify-content:center;
            overflow-y:auto;
            max-height:100vh;
        }
        .form-head { margin-bottom:20px; }
        .form-head h2 { font-size:1.4rem;font-weight:800;color:#f1f5f9;letter-spacing:-0.02em; }
        .form-head p { margin-top:5px;font-size:0.83rem;color:#64748b; }
        .f-group { margin-bottom:14px; }
        .f-label { display:block;font-size:0.78rem;font-weight:600;color:#94a3b8;margin-bottom:5px;letter-spacing:0.02em;text-transform:uppercase; }
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
        .f-input:focus { border-color:#f97316;box-shadow:0 0 0 3px rgba(249,115,22,0.15); }
        .f-error { font-size:0.78rem;color:#f87171;margin-top:5px; }
        .pass-wrap { position:relative; }
        .pass-toggle { position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:#64748b;cursor:pointer;font-size:0.78rem;font-weight:600; }
        .btn-register {
            width:100%;
            background:linear-gradient(135deg,#f97316,#ea580c);
            color:#fff;
            font-weight:800;
            font-size:0.92rem;
            padding:12px;
            border:none;
            border-radius:8px;
            cursor:pointer;
            box-shadow:0 8px 24px rgba(249,115,22,0.3);
            transition:transform 0.15s,box-shadow 0.15s;
            margin-top:4px;
        }
        .btn-register:hover { transform:translateY(-2px);box-shadow:0 12px 32px rgba(249,115,22,0.4); }
        .f-divider { height:1px;background:rgba(255,255,255,0.07);margin:18px 0; }
        .f-login-row { text-align:center;font-size:0.83rem;color:#64748b; }
        .f-login-row a { color:#f97316;font-weight:700;text-decoration:none;margin-left:5px; }
        .f-login-row a:hover { text-decoration:underline; }
        @media(max-width:720px){
            .auth-shell { grid-template-columns:1fr; }
            .auth-brand { padding:28px 24px; }
            .brand-headline h1 { font-size:1.5rem; }
            .auth-form-panel { padding:24px; max-height:none; }
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
                <h1>Join the <span>Solar</span><br>Revolution</h1>
                <p>Create your free account to track installations, manage orders, and monitor your solar performance.</p>
            </div>

            <div class="brand-features">
                <div class="brand-feat"><span class="feat-dot"></span>Free account — no credit card needed</div>
                <div class="brand-feat"><span class="feat-dot"></span>Track your installation live</div>
                <div class="brand-feat"><span class="feat-dot"></span>Exclusive deals &amp; early access</div>
                <div class="brand-feat"><span class="feat-dot"></span>24/7 customer support</div>
            </div>

            <div class="brand-bottom">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</div>
        </div>

        <!-- Form panel -->
        <div class="auth-form-panel">
            <div class="form-head">
                <h2>Create your account</h2>
                <p>Fill in your details to get started</p>
            </div>

            <form method="POST" action="{{ route('register') }}" novalidate>
                @csrf

                <div class="f-group">
                    <label class="f-label" for="name">Full Name</label>
                    <input class="f-input" id="name" type="text" name="name"
                        value="{{ old('name') }}" required autofocus placeholder="John Doe">
                    @error('name') <div class="f-error">{{ $message }}</div> @enderror
                </div>

                <div class="f-group">
                    <label class="f-label" for="email">Email Address</label>
                    <input class="f-input" id="email" type="email" name="email"
                        value="{{ old('email') }}" required placeholder="you@example.com">
                    @error('email') <div class="f-error">{{ $message }}</div> @enderror
                </div>

                <div class="f-group">
                    <label class="f-label" for="mobile_number">Mobile Number</label>
                    <input class="f-input" id="mobile_number" type="tel" name="mobile_number"
                        value="{{ old('mobile_number') }}" placeholder="+91 98765 43210">
                    @error('mobile_number') <div class="f-error">{{ $message }}</div> @enderror
                </div>

                <div class="f-group">
                    <label class="f-label" for="password">Password</label>
                    <div class="pass-wrap">
                        <input class="f-input" id="password" type="password" name="password"
                            required placeholder="Min. 8 characters">
                        <button type="button" class="pass-toggle" id="togglePwd">Show</button>
                    </div>
                    @error('password') <div class="f-error">{{ $message }}</div> @enderror
                </div>

                <div class="f-group">
                    <label class="f-label" for="password_confirmation">Confirm Password</label>
                    <input class="f-input" id="password_confirmation" type="password"
                        name="password_confirmation" required placeholder="Repeat your password">
                </div>

                <button type="submit" class="btn-register">Create Account</button>

                <div class="f-divider"></div>

                <div class="f-login-row">
                    Already have an account?
                    <a href="{{ route('login') }}">Sign in</a>
                </div>
            </form>
        </div>
    </div>

    <script src="/assets/js/toastr.min.js"></script>
    <script>
        document.getElementById('togglePwd').addEventListener('click', function(){
            var p = document.getElementById('password');
            if(p.type==='password'){p.type='text';this.textContent='Hide';}
            else{p.type='password';this.textContent='Show';}
        });
        toastr.options={closeButton:true,progressBar:true,positionClass:'toast-top-right',timeOut:5000};
        @if(Session::has('success')) toastr.success({!! json_encode(Session::get('success')) !!},'Success'); @endif
        @if(Session::has('error')) toastr.error({!! json_encode(Session::get('error')) !!},'Error'); @endif
        @if($errors->any()) @foreach($errors->all() as $error) toastr.error({!! json_encode($error) !!},'Error'); @endforeach @endif
    </script>
</body>
</html>
