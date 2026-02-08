@extends('layouts.public')

@section('css')
<style>
    .cp-container {
        max-width: 820px;
        margin: 32px auto 64px;
        padding: 0 16px;
    }
    .cp-card {
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 14px;
        padding: 18px;
        box-shadow: 0 10px 22px rgba(2,6,23,.25);
        backdrop-filter: blur(10px);
    }
    .cp-title {
        font-size: 1.25rem;
        font-weight: 800;
        margin-bottom: 6px;
        color: #e2e8f0;
    }
    .cp-sub {
        color: #94a3b8;
        font-size: .9rem;
        margin-bottom: 14px;
    }
    .cp-grid {
        display: grid;
        gap: 12px;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    }
    .cp-label {
        font-size: .8rem;
        color: #94a3b8;
        margin-bottom: 6px;
        display: block;
    }
    .cp-input {
        width: 100%;
        padding: 9px 12px;
        border-radius: 10px;
        border: 1px solid rgba(255,255,255,0.12);
        background: rgba(255,255,255,0.04);
        color: #e2e8f0;
        font-size: .9rem;
    }
    .cp-btn {
        width: 100%;
        padding: 10px 14px;
        border-radius: 12px;
        border: none;
        font-weight: 800;
        font-size: .95rem;
        color: #0b1220;
        background: linear-gradient(135deg, #60a5fa, #a78bfa);
        box-shadow: 0 8px 18px rgba(96,165,250,.35);
        cursor: pointer;
    }
</style>
@endsection

@section('content')
<div class="cp-container">
    <div class="cp-card">
        <div class="cp-title">Installation Partner Enrollment</div>
        <div class="cp-sub">Share your details and our team will reach out.</div>

        <form method="POST" action="#">
            @csrf
            <div class="cp-grid">
                <div>
                    <label class="cp-label" for="companyName">Company Name</label>
                    <input class="cp-input" id="companyName" name="companyName" type="text" required placeholder="Company name">
                </div>
                <div>
                    <label class="cp-label" for="contactPerson">Contact Person</label>
                    <input class="cp-input" id="contactPerson" name="contactPerson" type="text" required placeholder="Full name">
                </div>
                <div>
                    <label class="cp-label" for="email">Email</label>
                    <input class="cp-input" id="email" name="email" type="email" required placeholder="name@email.com">
                </div>
                <div>
                    <label class="cp-label" for="mobile">Mobile</label>
                    <input class="cp-input" id="mobile" name="mobile" type="text" pattern="[6-9]\d{9}" maxlength="10" required placeholder="10-digit number">
                </div>
                <div>
                    <label class="cp-label" for="state">State</label>
                    <select class="cp-input" id="state" name="state" required>
                        <option value="" disabled selected>Select state</option>
                        @foreach($states as $s)
                            <option value="{{ $s }}">{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="cp-label" for="city">City</label>
                    <select class="cp-input" id="city" name="city" required>
                        <option value="" disabled selected>Select city</option>
                        @foreach($cities as $c)
                            <option value="{{ $c }}">{{ $c }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="margin-top:12px;">
                <button class="cp-btn" type="submit">Submit</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
@endsection