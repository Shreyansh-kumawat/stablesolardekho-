{{-- resources/views/auth/reset-password.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
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

        .reset-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            color: #fff;
        }

        .reset-card h3 {
            font-weight: 600;
            margin-bottom: 10px;
        }

        .reset-card p {
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

        .invalid-feedback {
            color: #ffdede;
        }

        .password-hint {
            font-size: 12px;
            opacity: 0.8;
            margin-top: 5px;
        }
    </style>
</head>
<body>

<div class="reset-card text-center">

    <div class="logo-icon">
        <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"/></svg>
    </div>

    <h3>Reset Your Password</h3>
    <p>Create a new secure password below.</p>

    <form method="POST" action="{{ route('password.store') }}" class="mt-4">
        @csrf
        <!-- Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email -->
        <div class="mb-3 text-start">
            <label class="form-label text-white">Email Address</label>
            <input type="email"
                   name="email"
                   value="{{ old('email', $request->email) }}"
                   class="form-control @error('email') is-invalid @enderror"
                   required autofocus>

            @error('email')
                <div class="invalid-feedback d-block">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3 text-start">
            <label class="form-label text-white">New Password</label>
            <input type="password"
                   name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   required>

            <div class="password-hint">
                Minimum 8 characters recommended.
            </div>

            @error('password')
                <div class="invalid-feedback d-block">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-3 text-start">
            <label class="form-label text-white">Confirm Password</label>
            <input type="password"
                   name="password_confirmation"
                   class="form-control"
                   required>
        </div>

        <div class="d-grid mt-3">
            <button type="submit" class="btn btn-custom">
                Reset Password
            </button>
        </div>

    </form>

</div>

</body>
</html>
