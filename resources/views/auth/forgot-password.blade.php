{{-- resources/views/auth/forgot-password.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5.3 -->
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea, #764ba2);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .forgot-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            color: #fff;
        }

        .forgot-card h3 {
            font-weight: 600;
            margin-bottom: 10px;
        }

        .forgot-card p {
            font-size: 14px;
            opacity: 0.9;
        }

        .form-control {
            border-radius: 10px;
            border: none;
            padding: 12px;
        }

        .form-control:focus {
            box-shadow: 0 0 0 2px rgba(255,255,255,0.4);
        }

        .btn-custom {
            background: #fff;
            color: #764ba2;
            font-weight: 600;
            border-radius: 10px;
            padding: 12px;
            transition: 0.3s ease;
        }

        .btn-custom:hover {
            background: #f1f1f1;
            transform: translateY(-2px);
        }

        .logo-icon {
            font-size: 40px;
            margin-bottom: 15px;
        }

        .back-login {
            text-align: center;
            margin-top: 15px;
        }

        .back-login a {
            color: #fff;
            text-decoration: none;
            font-size: 14px;
        }

        .back-login a:hover {
            text-decoration: underline;
        }

        .alert-success {
            background-color: rgba(40,167,69,0.2);
            border: none;
            color: #fff;
        }

        .invalid-feedback {
            color: #ffdede;
        }
    </style>
</head>
<body>

<div class="forgot-card text-center">

    <div class="logo-icon">
        <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
    </div>

    <h3>Forgot Password?</h3>
    <p>Enter your registered email to receive password reset link.</p>

    {{-- Success Message --}}
    @if (session('status'))
        <div class="alert alert-success mt-3">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="mt-4">
        @csrf

        <div class="mb-3 text-start">
            <label class="form-label text-white">Email Address</label>
            <input type="email"
                   name="email"
                   value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror"
                   placeholder="Enter your email"
                   required autofocus>

            @error('email')
                <div class="invalid-feedback d-block">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="d-grid mt-3">
            <button type="submit" class="btn btn-custom">
                Send Reset Link
            </button>
        </div>

        <div class="back-login">
            <a href="{{ route('login') }}">← Back to Login</a>
        </div>

    </form>

</div>

</body>
</html>
