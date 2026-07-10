<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verify Email — {{ config('app.name', 'Solar Panel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
        .otp-card {
            width:100%;max-width:440px;
            background:#0f172a;
            border-radius:12px;
            border:1px solid rgba(255,255,255,0.08);
            box-shadow:0 24px 60px rgba(0,0,0,0.55);
            padding:40px 36px;
            text-align:center;
        }
        .otp-icon {
            width:64px;height:64px;margin:0 auto 20px;
            background:linear-gradient(135deg,#f97316,#ea580c);
            border-radius:50%;
            display:flex;align-items:center;justify-content:center;
        }
        .otp-icon svg { width:32px;height:32px;color:#fff; }
        .otp-title { font-size:1.5rem;font-weight:800;color:#f1f5f9;margin-bottom:8px; }
        .otp-desc { font-size:0.88rem;color:#64748b;margin-bottom:6px;line-height:1.5; }
        .otp-email { color:#f97316;font-weight:700; }
        .otp-inputs { display:flex;gap:8px;justify-content:center;margin:28px 0; }
        .otp-inputs input {
            width:48px;height:56px;
            text-align:center;
            font-size:1.5rem;font-weight:800;
            color:#f1f5f9;
            background:rgba(255,255,255,0.05);
            border:2px solid rgba(255,255,255,0.12);
            border-radius:10px;
            outline:none;
            transition:border-color 0.2s,box-shadow 0.2s;
        }
        .otp-inputs input:focus {
            border-color:#f97316;
            box-shadow:0 0 0 3px rgba(249,115,22,0.2);
        }
        .otp-inputs input::placeholder { color:#334155; }
        input[name="otp"] { position:absolute;opacity:0;pointer-events:none; }
        .btn-verify {
            width:100%;
            background:linear-gradient(135deg,#f97316,#ea580c);
            color:#fff;font-weight:800;font-size:0.92rem;
            padding:13px;border:none;border-radius:8px;cursor:pointer;
            box-shadow:0 8px 24px rgba(249,115,22,0.3);
            transition:transform 0.15s,box-shadow 0.15s;
        }
        .btn-verify:hover { transform:translateY(-2px);box-shadow:0 12px 32px rgba(249,115,22,0.4); }
        .btn-verify:disabled { opacity:0.6;cursor:not-allowed;transform:none; }
        .resend-row { margin-top:20px;font-size:0.83rem;color:#64748b; }
        .resend-row button {
            background:none;border:none;color:#f97316;font-weight:700;cursor:pointer;
            text-decoration:underline;font-size:0.83rem;
        }
        .resend-row button:disabled { color:#475569;cursor:not-allowed;text-decoration:none; }
        .back-link { display:inline-block;margin-top:16px;font-size:0.82rem;color:#64748b;text-decoration:none; }
        .back-link:hover { color:#94a3b8; }
        .timer { color:#f97316;font-weight:700;font-variant-numeric:tabular-nums; }
        @media(max-width:480px){
            .otp-card { padding:28px 20px; }
            .otp-inputs input { width:40px;height:48px;font-size:1.2rem; }
        }
    </style>
</head>
<body>
    <div class="otp-card">
        <div class="otp-icon">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
            </svg>
        </div>
        <h1 class="otp-title">Verify Your Email</h1>
        <p class="otp-desc">We've sent a 6-digit code to</p>
        <p class="otp-email">{{ session('reg_data.email', '') }}</p>

        <form method="POST" action="{{ route('otp.verify') }}" id="otpForm">
            @csrf
            <input type="hidden" name="otp" id="otpHidden">
            <div class="otp-inputs" id="otpInputs">
                <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" autofocus>
                <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]">
                <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]">
                <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]">
                <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]">
                <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]">
            </div>
            <button type="submit" class="btn-verify" id="verifyBtn" disabled>Verify & Create Account</button>
        </form>

        <div class="resend-row">
            <span>Didn't receive the code?</span>
            <form method="POST" action="{{ route('otp.resend') }}" style="display:inline;">
                @csrf
                <button type="submit" id="resendBtn" disabled>Resend OTP (<span class="timer" id="countdown">60</span>s)</button>
            </form>
        </div>

        <a href="{{ route('register') }}" class="back-link">&larr; Back to Registration</a>
    </div>

    <script src="/assets/js/toastr.min.js"></script>
    <script>
        toastr.options={closeButton:true,progressBar:true,positionClass:'toast-top-right',timeOut:5000};
        @if(Session::has('success')) toastr.success({!! json_encode(Session::get('success')) !!},'Success'); @endif
        @if(Session::has('error')) toastr.error({!! json_encode(Session::get('error')) !!},'Error'); @endif

        (function(){
            var inputs = document.querySelectorAll('#otpInputs input');
            var hidden = document.getElementById('otpHidden');
            var verifyBtn = document.getElementById('verifyBtn');

            function updateHidden(){
                var val = '';
                inputs.forEach(function(i){ val += i.value; });
                hidden.value = val;
                verifyBtn.disabled = val.length < 6;
            }

            inputs.forEach(function(input, idx){
                input.addEventListener('input', function(e){
                    this.value = this.value.replace(/[^0-9]/g, '');
                    if(this.value && idx < inputs.length - 1){
                        inputs[idx + 1].focus();
                    }
                    updateHidden();
                });
                input.addEventListener('keydown', function(e){
                    if(e.key === 'Backspace' && !this.value && idx > 0){
                        inputs[idx - 1].focus();
                        inputs[idx - 1].value = '';
                        updateHidden();
                    }
                });
                input.addEventListener('paste', function(e){
                    e.preventDefault();
                    var paste = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '').substr(0, 6);
                    for(var i = 0; i < paste.length && i < inputs.length; i++){
                        inputs[i].value = paste[i];
                    }
                    if(paste.length > 0) inputs[Math.min(paste.length, inputs.length) - 1].focus();
                    updateHidden();
                });
            });

            // Countdown timer for resend
            var seconds = 60;
            var countdownEl = document.getElementById('countdown');
            var resendBtn = document.getElementById('resendBtn');
            var timer = setInterval(function(){
                seconds--;
                countdownEl.textContent = seconds;
                if(seconds <= 0){
                    clearInterval(timer);
                    resendBtn.disabled = false;
                    resendBtn.textContent = 'Resend OTP';
                }
            }, 1000);
        })();
    </script>
</body>
</html>
