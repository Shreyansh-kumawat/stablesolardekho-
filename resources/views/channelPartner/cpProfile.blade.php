@extends('layouts.adminLayout')
@section('title', 'My Profile')

@section('css')
<style>
    .cpf-wrap { max-width: 700px; margin: 0 auto; padding: 1.5rem 1rem; }
    .cpf-header { margin-bottom: 1.5rem; }
    .cpf-header h1 { font-size: 1.3rem; font-weight: 800; color: #1f2937; margin: 0 0 .3rem; display: flex; align-items: center; gap: .5rem; }
    .cpf-header p { font-size: .82rem; color: #6b7280; margin: 0; }

    .cpf-alert { padding: 10px 14px; border-radius: 8px; font-size: .82rem; margin-bottom: 1rem; }
    .cpf-alert-ok { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
    .cpf-alert-err { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }

    .cpf-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 1.5rem; }
    .cpf-company { font-size: 1rem; font-weight: 700; color: #1f2937; margin: 0 0 1.25rem; padding-bottom: .75rem; border-bottom: 1px solid #f3f4f6; }

    .cpf-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem; }
    .cpf-row-full { margin-bottom: 1rem; }
    @media(max-width:600px) { .cpf-row { grid-template-columns: 1fr; } }

    .cpf-label { display: block; font-size: .75rem; font-weight: 600; color: #374151; margin-bottom: .3rem; text-transform: uppercase; letter-spacing: .03em; }
    .cpf-input {
        width: 100%; padding: 8px 12px; font-size: .85rem;
        border: 1px solid #d1d5db; border-radius: 8px; color: #1f2937;
        background: #fff; outline: none; transition: border-color .15s, box-shadow .15s;
    }
    .cpf-input:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,.1); }
    .cpf-input:read-only { background: #f9fafb; color: #6b7280; cursor: not-allowed; }

    .cpf-actions { display: flex; gap: .75rem; margin-top: 1.25rem; padding-top: 1rem; border-top: 1px solid #f3f4f6; }
    .cpf-btn {
        padding: 8px 20px; font-size: .82rem; font-weight: 600;
        border: none; border-radius: 8px; cursor: pointer; transition: all .15s;
    }
    .cpf-btn-save { background: #3b82f6; color: #fff; }
    .cpf-btn-save:hover { background: #2563eb; }

    .cpf-err { color: #dc2626; font-size: .72rem; margin-top: .2rem; }
</style>
@endsection

@section('content')
<div class="cpf-wrap">
    <div class="cpf-header">
        <h1>
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
            My Profile
        </h1>
        <p>Update your contact details</p>
    </div>

    @if(session('success'))
        <div class="cpf-alert cpf-alert-ok">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="cpf-alert cpf-alert-err">{{ session('error') }}</div>
    @endif

    <div class="cpf-card">
        <p class="cpf-company">{{ $cp->cp_name }}</p>

        <form action="{{ route('cpProfileUpdate') }}" method="POST">
            @csrf

            <div class="cpf-row">
                <div>
                    <label class="cpf-label">Contact Person *</label>
                    <input type="text" name="contact_person" class="cpf-input" value="{{ old('contact_person', $cp->contact_person) }}" required>
                    @error('contact_person') <div class="cpf-err">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="cpf-label">Phone Number *</label>
                    <input type="text" name="phone_number" class="cpf-input" value="{{ old('phone_number', $cp->phone_number) }}" required>
                    @error('phone_number') <div class="cpf-err">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="cpf-row-full">
                <label class="cpf-label">Email *</label>
                <input type="email" name="email" class="cpf-input" value="{{ old('email', $cp->email) }}" required>
                @error('email') <div class="cpf-err">{{ $message }}</div> @enderror
            </div>

            <div class="cpf-row-full">
                <label class="cpf-label">Full Address</label>
                <input type="text" name="full_address" class="cpf-input" value="{{ old('full_address', $cp->full_address) }}">
            </div>

            <div class="cpf-row">
                <div>
                    <label class="cpf-label">City</label>
                    <input type="text" name="city" class="cpf-input" value="{{ old('city', $cp->city) }}">
                </div>
                <div>
                    <label class="cpf-label">State</label>
                    <input type="text" name="state" class="cpf-input" value="{{ old('state', $cp->state) }}">
                </div>
            </div>

            <div class="cpf-row">
                <div>
                    <label class="cpf-label">Zip Code</label>
                    <input type="text" name="zip_code" class="cpf-input" value="{{ old('zip_code', $cp->zip_code) }}">
                </div>
                <div></div>
            </div>

            <div class="cpf-actions">
                <button type="submit" class="cpf-btn cpf-btn-save">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
