@extends('layouts.adminLayout')

@section('title', 'Add New Channel Partner')

@section('css')
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
            display: block; width: 100%;
            font-weight: 600; color: var(--text-primary);
            margin-bottom: 0.5rem; font-size: 0.85rem;
        }

        .form-control {
            width: 100%;
            border-radius: 6px !important;
            border: 1px solid var(--border-color) !important;
            padding: 0.55rem 0.75rem !important;
            font-size: 0.9rem !important;
            min-height: 42px;
            background: #fff;
            color: var(--text-primary);
        }
        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(74,144,226,0.15);
            border-color: var(--primary-blue);
            outline: none;
        }

        .btn-primary-theme {
            background: var(--primary-blue); border: 1px solid var(--primary-blue);
            color: #fff; padding: 0.5rem 1rem; border-radius: 6px;
            font-weight: 600; font-size: 0.85rem; cursor: pointer;
        }
        .btn-primary-theme:hover { background: #3b7dc4; border-color: #3b7dc4; color: #fff; }

        .btn-secondary-theme {
            padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600;
            font-size: 0.85rem; border: 1px solid var(--border-color);
            background: #fff; color: var(--text-primary); text-decoration: none;
        }
        .btn-secondary-theme:hover { background: var(--hover-bg); }

        /* Custom Dropdown */
        .custom-select-wrap { position: relative; }

        .custom-select-trigger {
            width: 100%;
            min-height: 42px;
            padding: 0.55rem 2.2rem 0.55rem 0.75rem;
            border-radius: 6px;
            border: 1px solid var(--border-color);
            background: #fff;
            color: var(--text-primary);
            font-size: 0.9rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            user-select: none;
            position: relative;
        }
        .custom-select-trigger::after {
            content: '';
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 0; height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 5px solid #636e72;
            pointer-events: none;
        }
        .custom-select-trigger.open { border-color: var(--primary-blue); box-shadow: 0 0 0 0.2rem rgba(74,144,226,0.15); }
        .custom-select-trigger.open::after { border-top: none; border-bottom: 5px solid var(--primary-blue); }
        .custom-select-trigger .placeholder { color: #999; }

        .custom-select-dropdown {
            display: none;
            position: absolute;
            top: calc(100% + 4px);
            left: 0; right: 0;
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
            z-index: 1000;
            max-height: 260px;
            overflow: hidden;
            flex-direction: column;
        }
        .custom-select-dropdown.show { display: flex; }

        .custom-select-search {
            padding: 10px 12px;
            border-bottom: 1px solid var(--border-color);
            flex-shrink: 0;
        }
        .custom-select-search input {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 0.85rem;
            outline: none;
            color: var(--text-primary);
            background: var(--primary-light);
        }
        .custom-select-search input:focus { border-color: var(--primary-blue); }

        .custom-select-options {
            overflow-y: auto;
            flex: 1;
        }
        .custom-select-option {
            padding: 9px 14px;
            cursor: pointer;
            font-size: 0.88rem;
            color: var(--text-primary);
            transition: background 0.1s;
        }
        .custom-select-option:hover { background: var(--hover-bg); }
        .custom-select-option.selected { background: rgba(74,144,226,0.1); color: var(--primary-blue); font-weight: 600; }
        .custom-select-option.hidden { display: none; }
        .custom-select-empty {
            padding: 14px;
            text-align: center;
            color: #999;
            font-size: 0.85rem;
            display: none;
        }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <div class="container-fluid">
            <h1><i class="fas fa-user-plus me-2"></i>Add New Channel Partner</h1>
            <p>Create a new channel Partner with role assignment</p>
        </div>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('storeNewCp') }}" id="cpForm">
            @csrf

            <div style="display: flex; flex-wrap: wrap; gap: 1rem;">
                <div style="flex: 1; min-width: 280px;">
                    <label for="cp_name" class="form-label">Company Name</label>
                    <input id="cp_name" name="cp_name" type="text" required value="{{ old('cp_name') }}"
                        class="form-control" placeholder="Company name" />
                    @error('cp_name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div style="flex: 1; min-width: 280px;">
                    <label for="contact_person" class="form-label">Contact Person</label>
                    <input id="contact_person" name="contact_person" type="text" required
                        value="{{ old('contact_person') }}" class="form-control" placeholder="Full name" />
                    @error('contact_person') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div style="flex: 1; min-width: 280px;">
                    <label for="cp_email" class="form-label">Email</label>
                    <input id="cp_email" name="cp_email" type="email" required value="{{ old('cp_email') }}"
                        class="form-control" placeholder="email@example.com" />
                    @error('cp_email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div style="flex: 1; min-width: 280px;">
                    <label for="mobile" class="form-label">Mobile Number</label>
                    <input id="mobile" name="mobile" type="text" value="{{ old('mobile') }}" class="form-control"
                        placeholder="98765 43210" />
                    @error('mobile') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div style="flex: 1; min-width: 280px;">
                    <label class="form-label">Channel Partner Role</label>
                    <div class="custom-select-wrap" data-name="role_id" data-required="true" data-placeholder="Select CP Type">
                        <input type="hidden" name="role_id" value="{{ old('role_id') }}">
                        @foreach($cp_roles as $cp)
                            <span class="custom-select-data" data-value="{{ $cp->id }}" data-label="{{ $cp->role_name }}"></span>
                        @endforeach
                    </div>
                    @error('role_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div style="flex: 1; min-width: 280px;">
                    <label class="form-label">State</label>
                    <div class="custom-select-wrap" data-name="state" data-required="true" data-placeholder="Select State">
                        <input type="hidden" name="state" value="{{ old('state') }}">
                        @foreach($states as $state)
                            <span class="custom-select-data" data-value="{{ $state }}" data-label="{{ $state }}"></span>
                        @endforeach
                    </div>
                    @error('state') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div style="flex: 1; min-width: 280px;">
                    <label class="form-label">City</label>
                    <div class="custom-select-wrap" data-name="city" data-required="true" data-placeholder="Select City">
                        <input type="hidden" name="city" value="{{ old('city') }}">
                        @foreach($cities as $city)
                            <span class="custom-select-data" data-value="{{ $city }}" data-label="{{ $city }}"></span>
                        @endforeach
                    </div>
                    @error('city') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div style="flex: 2; min-width: 300px;">
                    <label for="full_address" class="form-label">Full Address</label>
                    <input id="full_address" name="full_address" type="text" required value="{{ old('full_address') }}"
                        class="form-control" placeholder="Street address" />
                    @error('full_address') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div style="flex: 1; min-width: 200px;">
                    <label for="pin_code" class="form-label">Pin Code</label>
                    <input id="pin_code" name="pin_code" type="text" required value="{{ old('pin_code') }}"
                        class="form-control" placeholder="123456" pattern="[0-9]{6}" />
                    @error('pin_code') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn-primary-theme"><i class="fa fa-user-plus me-2"></i>Enroll Partner</button>
                <a href="{{ route('cpList') }}" class="btn-secondary-theme"><i class="fa fa-times me-2"></i>Cancel</a>
            </div>
        </form>
    </div>
@endsection

@section('js')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.custom-select-wrap').forEach(function(wrap) {
            var placeholder = wrap.dataset.placeholder || 'Select...';
            var hiddenInput = wrap.querySelector('input[type="hidden"]');
            var items = [];
            wrap.querySelectorAll('.custom-select-data').forEach(function(el) {
                items.push({ value: el.dataset.value, label: el.dataset.label });
                el.remove();
            });

            var trigger = document.createElement('div');
            trigger.className = 'custom-select-trigger';
            trigger.innerHTML = '<span class="placeholder">' + placeholder + '</span>';

            var dropdown = document.createElement('div');
            dropdown.className = 'custom-select-dropdown';

            var searchBox = document.createElement('div');
            searchBox.className = 'custom-select-search';
            var searchInput = document.createElement('input');
            searchInput.type = 'text';
            searchInput.placeholder = 'Search...';
            searchBox.appendChild(searchInput);

            var optionsDiv = document.createElement('div');
            optionsDiv.className = 'custom-select-options';

            var emptyMsg = document.createElement('div');
            emptyMsg.className = 'custom-select-empty';
            emptyMsg.textContent = 'No results found';

            items.forEach(function(item) {
                var opt = document.createElement('div');
                opt.className = 'custom-select-option';
                opt.dataset.value = item.value;
                opt.textContent = item.label;

                if (hiddenInput.value === item.value) {
                    opt.classList.add('selected');
                    trigger.innerHTML = item.label;
                }

                opt.addEventListener('click', function() {
                    hiddenInput.value = item.value;
                    trigger.innerHTML = item.label;
                    optionsDiv.querySelectorAll('.custom-select-option').forEach(function(o) { o.classList.remove('selected'); });
                    opt.classList.add('selected');
                    dropdown.classList.remove('show');
                    trigger.classList.remove('open');
                });

                optionsDiv.appendChild(opt);
            });

            dropdown.appendChild(searchBox);
            dropdown.appendChild(optionsDiv);
            dropdown.appendChild(emptyMsg);
            wrap.appendChild(trigger);
            wrap.appendChild(dropdown);

            trigger.addEventListener('click', function(e) {
                e.stopPropagation();
                document.querySelectorAll('.custom-select-dropdown.show').forEach(function(d) {
                    if (d !== dropdown) {
                        d.classList.remove('show');
                        d.previousElementSibling.classList.remove('open');
                    }
                });
                dropdown.classList.toggle('show');
                trigger.classList.toggle('open');
                if (dropdown.classList.contains('show')) {
                    searchInput.value = '';
                    searchInput.focus();
                    optionsDiv.querySelectorAll('.custom-select-option').forEach(function(o) { o.classList.remove('hidden'); });
                    emptyMsg.style.display = 'none';
                }
            });

            searchInput.addEventListener('input', function() {
                var q = this.value.toLowerCase();
                var visibleCount = 0;
                optionsDiv.querySelectorAll('.custom-select-option').forEach(function(o) {
                    var match = o.textContent.toLowerCase().indexOf(q) !== -1;
                    o.classList.toggle('hidden', !match);
                    if (match) visibleCount++;
                });
                emptyMsg.style.display = visibleCount === 0 ? 'block' : 'none';
            });

            searchInput.addEventListener('click', function(e) { e.stopPropagation(); });
            dropdown.addEventListener('click', function(e) { e.stopPropagation(); });
        });

        document.addEventListener('click', function() {
            document.querySelectorAll('.custom-select-dropdown.show').forEach(function(d) {
                d.classList.remove('show');
                d.previousElementSibling.classList.remove('open');
            });
        });

        var form = document.getElementById('cpForm');
        form.addEventListener('submit', function(e) {
            var valid = true;
            form.querySelectorAll('.custom-select-wrap[data-required="true"]').forEach(function(wrap) {
                var input = wrap.querySelector('input[type="hidden"]');
                if (!input.value) {
                    valid = false;
                    wrap.querySelector('.custom-select-trigger').style.borderColor = '#e74c3c';
                } else {
                    wrap.querySelector('.custom-select-trigger').style.borderColor = '';
                }
            });
            if (!valid || !form.checkValidity()) {
                e.preventDefault();
                form.reportValidity();
            }
        });
    });
    </script>
@endsection
