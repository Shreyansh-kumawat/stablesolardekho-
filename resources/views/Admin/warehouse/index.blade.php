@extends('layouts.adminLayout')

@section('css')
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/dataTables.bootstrap5.min.css" rel="stylesheet">
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
        .btn-sm { padding: 0.35rem 0.65rem; font-size: 0.8rem; }
        .table thead th { background: #f8f9fa; color: var(--text-primary); font-weight: 600; border-bottom: 1px solid var(--border-color); padding: 0.9rem; font-size: 0.8rem; text-transform: uppercase; }
        .table tbody td { padding: 0.85rem; vertical-align: middle; border-color: var(--border-color); }
        .table tbody tr:hover { background-color: var(--hover-bg); }
        .badge-active { background: #d3f9d8; color: #2b8a3e; padding: 0.3rem 0.65rem; border-radius: 12px; font-weight: 600; font-size: 0.75rem; }
        .badge-inactive { background: #fff5f5; color: #c92a2a; padding: 0.3rem 0.65rem; border-radius: 12px; font-weight: 600; font-size: 0.75rem; }
        .dt-button { background: var(--primary-blue) !important; border: 1px solid var(--primary-blue) !important; border-radius: 6px !important; padding: 0.45rem 0.8rem !important; font-weight: 600 !important; color: #fff !important; font-size: 0.8rem !important; }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div>
                <h1><i class="fas fa-warehouse me-2"></i>Warehouses</h1>
                <p>Manage all warehouse locations</p>
            </div>
            <a href="{{ route('admin.warehouses.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Add Warehouse
            </a>
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

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="warehouseTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Managers</th>
                                <th>Products</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($warehouses as $i => $wh)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td style="font-weight:600;">{{ $wh->name }}</td>
                                <td>
                                    {{ $wh->city ?? '' }}{{ $wh->city && $wh->state ? ', ' : '' }}{{ $wh->state ?? '' }}
                                    @if($wh->pin_code) <small class="text-muted">({{ $wh->pin_code }})</small> @endif
                                </td>
                                <td>{{ $wh->managers_count }}</td>
                                <td>{{ $wh->inventories_count }}</td>
                                <td>
                                    <span class="{{ $wh->is_active ? 'badge-active' : 'badge-inactive' }}">
                                        {{ $wh->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1 flex-wrap">
                                        <a href="{{ route('admin.warehouses.dashboard', $wh->id) }}" class="btn btn-sm btn-outline-dark" title="Dashboard">
                                            <i class="fas fa-tachometer-alt"></i>
                                        </a>
                                        <a href="{{ route('admin.warehouses.inventory', $wh->id) }}" class="btn btn-sm btn-outline-primary" title="Inventory">
                                            <i class="fas fa-boxes"></i>
                                        </a>
                                        <a href="{{ route('admin.warehouses.transactions', $wh->id) }}" class="btn btn-sm btn-outline-secondary" title="Transactions">
                                            <i class="fas fa-exchange-alt"></i>
                                        </a>
                                        <a href="{{ route('admin.warehouses.managers', $wh->id) }}" class="btn btn-sm btn-outline-info" title="Managers">
                                            <i class="fas fa-users"></i>
                                        </a>
                                        <a href="{{ route('admin.warehouses.edit', $wh->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        {{-- Toggle disabled: stock becomes ghost if warehouse deactivated --}}
                                        <form action="{{ route('admin.warehouses.destroy', $wh->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this warehouse?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No warehouses found. Click "Add Warehouse" to create one.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/assets/js/jquery.dataTables.min.js"></script>
    <script src="/assets/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(function () {
            @if($warehouses->count() > 5)
            $('#warehouseTable').DataTable({ pageLength: 25, order: [] });
            @endif
        });
    </script>
@endsection
