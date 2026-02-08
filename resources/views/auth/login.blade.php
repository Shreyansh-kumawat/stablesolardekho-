<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ config('app.name', 'Solar Panel Installation') }} - Login</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="{{ asset('css/themes/solar-energy.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <style>
        /* Minimal attractive login styles */
        body {
            min-height: 100vh;
            display: grid;
            place-items: center;
            background: radial-gradient(1200px 600px at 10% 20%, rgba(255,214,64,0.08), transparent 10%),
                        linear-gradient(135deg,#909aa8 0%, #627589 50%, #42a5f5 100%);
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
        }

        .auth-wrap {
            width: 100%;
            max-width: 980px;
            margin: 2rem;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(2,6,23,0.45);
            display: grid;
            grid-template-columns: 1fr 480px;
            background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.02));
            backdrop-filter: blur(6px);
        }

        .auth-brand {
            padding: 42px;
            color: #fff;
            background: linear-gradient(135deg, rgba(255,255,255,0.04), rgba(0,0,0,0.06));
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 18px;
            position: relative;
            border-radius: 12px;
            overflow: hidden;
        }

        /* subtle dark overlay so white text is always readable */
        .auth-brand::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(0,0,0,0.28), rgba(0,0,0,0.12));
            pointer-events: none;
            z-index: 0;
        }

        /* ensure content sits above overlay */
        .auth-brand > * { position: relative; z-index: 1; }

        .auth-brand .logo {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        /* bigger, more prominent logo */
        .auth-brand .logo img {
            height: 164px;                 /* increased size */
            max-width: 240px;
            width: auto;
            object-fit: contain;
            border-radius: 12px;
            padding: 6px;
            background: rgba(255,255,255,0.06);
            box-shadow: 0 8px 30px rgba(11,37,69,0.25);
        }

        .brand-title {
            font-size: 2.25rem;           /* larger title */
            font-weight: 900;
            letter-spacing: -0.02em;
            color: #fff;
            text-shadow: 0 8px 30px rgba(2,6,23,0.6); /* improved contrast */
        }

        .brand-sub {
            color: #f8fafc;
            opacity: 0.95;
            max-width: 420px;
            line-height: 1.45;
            font-size: 1.02rem;           /* slightly larger */
            text-shadow: 0 4px 14px rgba(2,6,23,0.45);
        }

        /* make the small chips clearer on darker background */
        .auth-brand .small {
            color: #fff;
            opacity: 0.95;
        }

        .auth-hero {
            position: absolute;
            right: -60px;
            bottom: -40px;
            width: 320px;
            height: 320px;
            border-radius: 50%;
            background: radial-gradient(circle at 30% 30%, rgba(255,214,64,0.14), transparent 30%),
                        radial-gradient(circle at 70% 70%, rgba(255,107,53,0.08), transparent 20%);
            filter: blur(30px);
            pointer-events: none;
        }

        .auth-form {
            padding: 36px;
            background: #fff;
        }

        .auth-card {
            max-width: 420px;
            margin: 10px auto;
        }

        .auth-card h2 {
            font-size: 1.45rem;
            font-weight: 800;
            color: #0b2545;
            margin-bottom: 6px;
        }

        .auth-card p.lead {
            margin-bottom: 20px;
            color: #546b83;
            font-size: 0.95rem;
        }

        .form-group { margin-bottom: 14px; }
        .form-group label { display:block; font-weight:600; font-size:0.9rem; color:#213547; margin-bottom:6px; }
        .form-control {
            display:block;
            width:100%;
            padding: 12px 14px;
            border-radius: 10px;
            border: 1px solid #e6eef6;
            background: #fbfdff;
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.6);
            transition: box-shadow .12s, border-color .12s, transform .06s;
        }
        .form-control:focus { outline: none; border-color: #4f9ef8; box-shadow: 0 6px 20px rgba(79,158,248,0.12); transform: translateY(-1px); }

        .form-help { font-size: 0.85rem; color: #67758a; margin-top:6px; }

        .error-text { font-size: 0.85rem; color:#d14343; margin-top:6px; }

        .actions { display:flex; gap:10px; align-items:center; margin-top: 8px; }
        .btn-primary {
            background: linear-gradient(90deg,#ff6b35,#ffb04e);
            color: #fff;
            padding: 12px 18px;
            border-radius: 10px;
            border: none;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 10px 30px rgba(255,107,53,0.18);
            transition: transform .12s, box-shadow .12s;
            width:100%;
        }
        .btn-primary:hover { transform: translateY(-3px); box-shadow: 0 16px 40px rgba(255,107,53,0.22); }

        .secondary-link { color:#4f9ef8; font-weight:600; text-decoration:none; }
        .divider { height:1px; background:#f1f6fb; margin:20px 0; border-radius:4px; }

        .small { font-size:0.85rem; color:#6d7b8a; }

        @media (max-width: 880px) {
            .auth-wrap { grid-template-columns: 1fr; margin: 1rem; }
            .auth-hero { display:none; }
            .auth-brand .logo img { height: 64px; max-width: 180px; } /* responsive */
            .brand-title { font-size: 1.6rem; }
            .brand-sub { font-size: 0.95rem; }
        }
    </style>
</head>

<body>
    <div class="auth-wrap" role="main">
        <div class="auth-brand" aria-hidden="false">
            <div class="logo">
                <a href="{{ route('dashBoardFunction') }}" aria-label="Home">
                    <img src="{{ asset('stable/images/logo1.png') }}" alt="{{ config('app.name') }} logo" />
                </a>
                <div>
                    <div class="brand-title">{{ config('app.name', 'Solar Panel') }}</div>
                    <div class="brand-sub"><b>S</b>olar <b>T</b>ransformation for <b>A</b> Greener <b>B</b>etter <b>L</b>iving <b>E</b>nvironment</div>
                </div>
            </div>

            <div style="margin-top:18px;">
                <div class="small">Trusted by homeowners & businesses across the country.</div>
                <ul style="margin-top:10px;color:rgba(255,255,255,0.92);list-style:none;padding:0;display:flex;gap:10px;flex-wrap:wrap">
                    <li class="small">Free site visit</li>
                    <li class="small" >10-year warranty</li>
                    <li class="small" >Easy financing</li>
                </ul>
            </div>

            <div class="auth-hero" aria-hidden="true"></div>
        </div>

        <div class="auth-form">
            <div class="auth-card" aria-labelledby="login-heading">
                <h2 id="login-heading">Welcome back</h2>
                <p class="lead">Sign in to access your dashboard and manage installations.</p>

                <form method="POST" action="{{ route('login') }}" novalidate>
                    @csrf

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="form-control" placeholder="your.email@example.com" />
                        @error('email') <div class="error-text" role="alert">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div style="position:relative;">
                            <input id="password" type="password" name="password" required class="form-control" placeholder="Enter your password" />
                            <button type="button" id="togglePassword" aria-label="Toggle password visibility" style="position:absolute;right:10px;top:8px;background:none;border:none;color:#67758a;font-weight:700;cursor:pointer;">Show</button>
                        </div>
                        @error('password') <div class="error-text" role="alert">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group actions" style="align-items:center;justify-content:space-between;">
                        <label style="display:flex;align-items:center;gap:8px;font-weight:600;color:#4a6078;">
                            <input type="checkbox" name="remember" style="width:16px;height:16px;" /> <span style="font-weight:600;color:#4a6078;font-size:0.9rem;">Remember me</span>
                        </label>

                        <a href="{{ route('password.request') ?? '#' }}" class="secondary-link">Forgot password?</a>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn-primary">Login to your account</button>
                    </div>

                    <div class="divider"></div>

                    {{-- <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;">
                        <div class="small">Don't have an account?</div>
                        <a href="{{ route('register') }}" class="secondary-link">Create account</a>
                    </div> --}}
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        // Password toggle
        document.getElementById('togglePassword')?.addEventListener('click', function () {
            const pwd = document.getElementById('password');
            if (!pwd) return;
            if (pwd.type === 'password') {
                pwd.type = 'text';
                this.textContent = 'Hide';
            } else {
                pwd.type = 'password';
                this.textContent = 'Show';
            }
        });

        // Toastr Configuration
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-top-right',
            timeOut: 5000
        };

        // Session messages
        @if (Session::has('success'))
            toastr.success({!! json_encode(Session::get('success')) !!}, 'Success');
        @endif

        @if (Session::has('error'))
            toastr.error({!! json_encode(Session::get('error')) !!}, 'Error');
        @endif

        // Validation errors (show as toasts)
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error({!! json_encode($error) !!}, 'Error');
            @endforeach
        @endif
    </script>
</body>

</html>