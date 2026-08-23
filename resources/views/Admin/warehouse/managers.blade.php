@extends('layouts.adminLayout')

@section('css')
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/select2.min.css" rel="stylesheet" />
    <style>
        :root { --primary-blue: #4A90E2; --primary-light: #f5f7fa; --text-primary: #2d3436; --text-secondary: #636e72; --border-color: #e1e8ed; --hover-bg: #f1f3f5; --card-bg: #ffffff; }
        body { background: var(--primary-light); color: var(--text-primary); }
        .page-header { background: #ffffff; padding: 1.5rem 0; margin-bottom: 1.5rem; border-radius: 8px; border: 1px solid var(--border-color); box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
        .page-header h1 { color: var(--text-primary); font-weight: 600; margin: 0; font-size: 1.25rem; }
        .page-header p { color: var(--text-secondary); margin: 0.35rem 0 0 0; font-size: 0.9rem; }
        .card { border: 1px solid var(--border-color); border-radius: 8px; background: var(--card-bg); box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
        .card-body { padding: 1.5rem; }
        .btn-primary { background: var(--primary-blue); border: 1px solid var(--primary-blue); color: #fff; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; font-size: 0.85rem; }
        .btn-primary:hover { background: #3b7dc4; border-color: #3b7dc4; color: #fff; }
        .table thead th { background: #f8f9fa; color: var(--text-primary); font-weight: 600; border-bottom: 1px solid var(--border-color); padding: 0.9rem; font-size: 0.8rem; text-transform: uppercase; }
        .table tbody td { padding: 0.85rem; vertical-align: middle; border-color: var(--border-color); }
        .table tbody tr:hover { background-color: var(--hover-bg); }
        .select2-container { width: 100% !important; }
        .select2-container--default .select2-selection--single { height: 38px !important; border: 1px solid var(--border-color) !important; border-radius: 6px !important; padding: 0.35rem 0.75rem !important; display: flex !important; align-items: center !important; }
        .form-label { font-weight: 600; color: var(--text-primary); margin-bottom: 0.5rem; font-size: 0.85rem; }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <div class="container-fluid">
            <h1><i class="fas fa-users me-2"></i>Warehouse Managers - {{ $warehouse->name }}</h1>
            <p>Assign or remove managers for this warehouse</p>
        </div>
    </div>

    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size:0.88rem;">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="font-size:0.88rem;">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-body">
                <h6 style="font-weight:700;margin-bottom:1rem;">Add Manager</h6>
                <form action="{{ route('admin.warehouses.addManager', $warehouse->id) }}" method="POST" class="d-flex gap-2 align-items-end">
                    @csrf
                    <div style="flex:1;">
                        <label class="form-label">Select User</label>
                        <select name="user_id" class="form-select select2" required>
                            <option value="">Search user...</option>
                            @foreach($users as $user)
                                @if(!$warehouse->managers->contains($user->id))
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" style="height:38px;">Add Manager</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h6 style="font-weight:700;margin-bottom:1rem;">Current Managers ({{ $warehouse->managers->count() }})</h6>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($warehouse->managers as $i => $manager)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td style="font-weight:600;">{{ $manager->name }}</td>
                                <td>{{ $manager->email }}</td>
                                <td>
                                    @if($manager->role_id == 1) Master Admin
                                    @elseif($manager->role_id == 2) Secondary Admin
                                    @else User
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.warehouses.removeManager', [$warehouse->id, $manager->id]) }}" method="POST" onsubmit="return confirm('Remove this manager?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-times"></i> Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">No managers assigned yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('admin.warehouses.index') }}" class="btn btn-secondary" style="padding:0.5rem 1rem;border-radius:6px;font-weight:600;font-size:0.85rem;border:1px solid #e1e8ed;background:#fff;color:#2d3436;">
                <i class="fas fa-arrow-left me-1"></i> Back to Warehouses
            </a>
        </div>
    </div>
@endsection

@section('js')
    <script src="/assets/js/select2.min.js"></script>
    <script>$(function(){ $('.select2').select2({ width: '100%' }); });</script>
@endsection
