@extends('layouts.adminLayout')

@section('css')
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --primary-blue: #4A90E2; --primary-light: #f5f7fa; --text-primary: #2d3436; --text-secondary: #636e72; --border-color: #e1e8ed; --card-bg: #ffffff; }
        body { background: var(--primary-light); color: var(--text-primary); }
        .page-header { background: #ffffff; padding: 1.5rem 0; margin-bottom: 1.5rem; border-radius: 8px; border: 1px solid var(--border-color); box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
        .page-header h1 { color: var(--text-primary); font-weight: 600; margin: 0; font-size: 1.25rem; }
        .page-header p { color: var(--text-secondary); margin: 0.35rem 0 0 0; font-size: 0.9rem; }
        .card { border: 1px solid var(--border-color); border-radius: 8px; background: var(--card-bg); box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
        .card-body { padding: 1.5rem; }
        .btn-primary { background: var(--primary-blue); border: 1px solid var(--primary-blue); color: #fff; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; font-size: 0.85rem; }
        .btn-primary:hover { background: #3b7dc4; border-color: #3b7dc4; color: #fff; }
        .btn-secondary { padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; font-size: 0.85rem; border: 1px solid var(--border-color); background: #fff; color: var(--text-primary); }
        .form-control { border-radius: 6px; border: 1px solid var(--border-color); padding: 0.55rem 0.75rem; font-size: 0.9rem; }
        .form-control:focus { box-shadow: 0 0 0 0.2rem rgba(74,144,226,0.15); border-color: var(--primary-blue); }
        .form-label { font-weight: 600; color: var(--text-primary); margin-bottom: 0.5rem; font-size: 0.85rem; }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <div class="container-fluid">
            <h1><i class="fas fa-warehouse me-2"></i>{{ isset($warehouse) ? 'Edit Warehouse' : 'Add New Warehouse' }}</h1>
            <p>{{ isset($warehouse) ? 'Update warehouse details' : 'Create a new warehouse location' }}</p>
        </div>
    </div>

    <div class="container-fluid">
        @if($errors->any())
            <div class="alert alert-danger" style="font-size:0.88rem;">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ isset($warehouse) ? route('admin.warehouses.update', $warehouse->id) : route('admin.warehouses.store') }}" method="POST">
                    @csrf
                    @if(isset($warehouse)) @method('PUT') @endif

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Warehouse Name *</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Jaipur Central Warehouse" value="{{ old('name', $warehouse->name ?? '') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-control" placeholder="Full address" value="{{ old('address', $warehouse->address ?? '') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">City</label>
                            <input type="text" name="city" class="form-control" placeholder="City" value="{{ old('city', $warehouse->city ?? '') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">State</label>
                            <input type="text" name="state" class="form-control" placeholder="State" value="{{ old('state', $warehouse->state ?? '') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">PIN Code</label>
                            <input type="text" name="pin_code" class="form-control" placeholder="PIN Code" maxlength="10" value="{{ old('pin_code', $warehouse->pin_code ?? '') }}">
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            {{ isset($warehouse) ? 'Update Warehouse' : 'Create Warehouse' }}
                        </button>
                        <a href="{{ route('admin.warehouses.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
