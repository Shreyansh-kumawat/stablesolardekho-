@extends('layouts.adminLayout')

@section('title', 'Create User')

@section('css')
    <!-- Select2 CSS (only for this page) -->
    <link href="/assets/css/select2.min.css" rel="stylesheet" />
    <style>
        :root {
            --primary-blue: #4A90E2;
            --primary-light: #f5f7fa;
            --text-primary: #2d3436;
            --text-secondary: #636e72;
            --border-color: #e1e8ed;
            --hover-bg: #f1f3f5;
            --card-bg: #ffffff;
        }

        body { background: var(--primary-light); color: var(--text-primary); }

        .page-header {
            background: #ffffff;
            padding: 1.5rem 0;
            margin-bottom: 1.5rem;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .page-header h1 { color: var(--text-primary); font-weight: 600; margin: 0; font-size: 1.25rem; }
        .page-header p { color: var(--text-secondary); margin: 0.35rem 0 0 0; font-size: 0.9rem; }

        .card {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: var(--card-bg);
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            padding: 1.5rem;
        }

        .form-label {
            display: block;
            width: 100%;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
        }
        .form-control {
            width: 100%;
            border-radius: 6px !important;
            border: 1px solid var(--border-color) !important;
            padding: 0.55rem 0.75rem !important;
            font-size: 0.9rem !important;
            min-height: 42px;
        }
        .form-control:focus { box-shadow: 0 0 0 0.2rem rgba(74,144,226,0.15); border-color: var(--primary-blue); }

        .btn-primary-theme {
            background: var(--primary-blue);
            border: 1px solid var(--primary-blue);
            color: #fff;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.85rem;
        }
        .btn-primary-theme:hover { background: #3b7dc4; border-color: #3b7dc4; color: #fff; }
        .btn-secondary-theme {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.85rem;
            border: 1px solid var(--border-color);
            background: #fff;
            color: var(--text-primary);
        }
        .btn-secondary-theme:hover { background: var(--hover-bg); }

        .select2-container {
            width: 100% !important;
        }
        .select2-container .select2-selection--single {
            height: 42px !important;
            display: flex !important;
            align-items: center !important;
        }
        .select2-container .select2-selection__rendered {
            line-height: 42px !important;
            padding-left: 0.75rem !important;
        }
        .select2-container .select2-selection__arrow {
            height: 42px !important;
            right: 10px !important;
        }

        .help-text { font-size: 0.85rem; color: var(--text-secondary); }
    </style>
@endsection

@section('content')
<div class="page-header">
    <div class="container-fluid">
        <h1><i class="fas fa-user-plus me-2"></i>Add New User</h1>
        <p>Create a new user account with role assignment</p>
    </div>
</div>

<div class="card">
    <form method="POST" action="{{ route('storeNewUser') }}">
        @csrf

        <div class="row g-3">
            <div class="col-md-6">
                <label for="name" class="form-label">Full Name</label>
                <input id="name" name="name" type="text" required value="{{ old('name') }}"
                    class="form-control" placeholder="John Doe" />
                @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label for="email" class="form-label">Email</label>
                <input id="email" name="email" type="email" required value="{{ old('email') }}"
                    class="form-control" placeholder="your.email@example.com" />
                @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label for="mobile" class="form-label">Mobile Number</label>
                <input id="mobile" name="mobile" type="text" value="{{ old('mobile') }}"
                    class="form-control" placeholder="+91 98765 43210" />
                @error('mobile') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label for="cp_id" class="form-label">Channel Partner</label>
                <select id="cp_id" name="cp_id" class="select2-element form-control" required>
                    <option value="">Select CP</option>
                    @foreach($cp_list as $cp)
                        <option value="{{ $cp->id }}" {{ old('cp_id') == $cp->id ? 'selected' : '' }}>
                            {{ $cp->cp_name }}
                        </option>
                    @endforeach
                </select>
                @error('cp_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label for="role" class="form-label">Role</label>
                <select id="role" name="role_id" class="select2-element form-control" required>
                    <option value="">Select role</option>
                    @if(isset($roles) && $roles->count())
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    @else
                        <option value="1" {{ old('role_id') == 1 ? 'selected' : '' }}>Admin</option>
                        <option value="2" {{ old('role_id') == 2 ? 'selected' : '' }}>Manager</option>
                        <option value="3" {{ old('role_id') == 3 ? 'selected' : '' }}>Installer</option>
                    @endif
                </select>
                @error('role_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label for="password" class="form-label">Password</label>
                <input id="password" name="password" type="password" required
                    class="form-control" placeholder="Choose a strong password" />
                @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                    class="form-control" placeholder="Confirm password" />
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn-primary-theme"><i class="fa fa-user-plus me-2"></i>Create User</button>
            <a href="#" class="btn-secondary-theme"><i class="fa fa-times me-2"></i>Cancel</a>
        </div>
    </form>
</div>
@endsection

@section('js')
    <!-- Select2 JS (init only on this page) -->
    <script src="/assets/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // initialize Select2 with dropdownParent to avoid z-index/overflow issues
            $('.select2-element').select2({
                width: 'resolve',
                dropdownParent: $(document.body),
                minimumResultsForSearch: 6
            });

            // client-side validation: prevent submit when invalid and show browser messages
            const form = document.querySelector('form');
            form.addEventListener('submit', function (e) {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                    // show browser validation UI
                    form.reportValidity();
                    // focus first invalid field
                    const invalid = form.querySelector(':invalid');
                    invalid?.focus();
                    return false;
                }
                // allow submit (server will validate too)
            });

             // Simple client-side enhancement: focus first invalid field if server validation failed
             @if ($errors->any())
                 const firstError = document.querySelector('.is-invalid, .text-red-600');
                 if (firstError) {
                     const input = firstError.closest('div').querySelector('input, select, textarea');
                     input?.focus();
                 }
             @endif
         });
     </script>
@endsection