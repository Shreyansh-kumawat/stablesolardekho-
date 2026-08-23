@extends('layouts.adminLayout')

@section('css')
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="/assets/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <style>
        :root { --primary-blue: #4A90E2; --primary-light: #f5f7fa; --text-primary: #2d3436; --text-secondary: #636e72; --border-color: #e1e8ed; --hover-bg: #f1f3f5; --card-bg: #ffffff; }
        body { background: var(--primary-light); color: var(--text-primary); }
        .page-header { background: #ffffff; padding: 1.5rem 0; margin-bottom: 1.5rem; border-radius: 8px; border: 1px solid var(--border-color); box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
        .page-header h1 { color: var(--text-primary); font-weight: 600; margin: 0; font-size: 1.25rem; }
        .page-header p { color: var(--text-secondary); margin: 0.35rem 0 0 0; font-size: 0.9rem; }
        .card { border: 1px solid var(--border-color); border-radius: 8px; background: var(--card-bg); box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
        .card-body { padding: 1.5rem; }
        .btn-primary { background: var(--primary-blue); border: 1px solid var(--primary-blue); color: #fff; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; font-size: 0.85rem; }
        .table thead th { background: #f8f9fa; color: var(--text-primary); font-weight: 600; border-bottom: 1px solid var(--border-color); padding: 0.85rem; font-size: 0.8rem; text-transform: uppercase; }
        .table tbody td { padding: 0.8rem 0.85rem; vertical-align: middle; border-color: var(--border-color); font-size: 0.85rem; }
        .table tbody tr:hover { background-color: var(--hover-bg); }
        .dt-button { background: var(--primary-blue) !important; border: 1px solid var(--primary-blue) !important; border-radius: 6px !important; padding: 0.45rem 0.8rem !important; font-weight: 600 !important; color: #fff !important; font-size: 0.8rem !important; }
        .stat-pills { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 1rem; }
        .stat-pill { padding: 6px 14px; border-radius: 20px; font-size: 0.78rem; font-weight: 600; border: 1px solid var(--border-color); text-decoration: none; color: var(--text-secondary); transition: all 0.15s; }
        .stat-pill:hover { background: var(--primary-blue); color: #fff; border-color: var(--primary-blue); }
        .stat-pill.active { background: var(--primary-blue); color: #fff; border-color: var(--primary-blue); }
        .stat-pill .count { background: rgba(0,0,0,0.08); padding: 1px 6px; border-radius: 10px; margin-left: 4px; font-size: 0.72rem; }
        .stat-pill.active .count { background: rgba(255,255,255,0.25); }
        .badge-status { padding: 0.25rem 0.6rem; border-radius: 12px; font-weight: 600; font-size: 0.73rem; text-transform: capitalize; }
        .badge-pending { background: #fff8e1; color: #f59e0b; }
        .badge-processing { background: #e8f4fd; color: #3b82f6; }
        .badge-quoted { background: #f3e8ff; color: #8b5cf6; }
        .badge-accepted { background: #d3f9d8; color: #2b8a3e; }
        .badge-rejected { background: #fff5f5; color: #c92a2a; }
        .badge-closed { background: #f1f3f5; color: #636e72; }
        .filter-bar { background: #fff; border: 1px solid var(--border-color); border-radius: 8px; padding: 0.75rem 1rem; margin-bottom: 1rem; }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div>
                <h1><i class="fas fa-file-invoice me-2"></i>Customer RFQ Requests</h1>
                <p>Manage product quote requests from customers</p>
            </div>
            <a href="{{ route('admin.rfq.export', request()->only('status')) }}" class="btn btn-primary">
                <i class="fas fa-download me-1"></i> Export CSV
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

        <div class="stat-pills">
            <a href="{{ route('admin.rfq.index') }}" class="stat-pill {{ !request('status') ? 'active' : '' }}">
                All <span class="count">{{ $statusCounts->sum() }}</span>
            </a>
            @foreach(['pending', 'processing', 'quoted', 'accepted', 'rejected', 'closed'] as $s)
            <a href="{{ route('admin.rfq.index', ['status' => $s]) }}" class="stat-pill {{ request('status') == $s ? 'active' : '' }}">
                {{ ucfirst($s) }} <span class="count">{{ $statusCounts[$s] ?? 0 }}</span>
            </a>
            @endforeach
        </div>

        <div class="filter-bar">
            <form method="GET" action="{{ route('admin.rfq.index') }}" class="d-flex gap-2 align-items-center">
                @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <input type="text" name="search" class="form-control" style="max-width:300px;font-size:0.85rem;" placeholder="Search by name, phone, item..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Search</button>
                @if(request('search'))
                <a href="{{ route('admin.rfq.index', request()->only('status')) }}" class="btn btn-outline-secondary btn-sm">Clear</a>
                @endif
            </form>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="rfqTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Phone</th>
                                <th>Item Requested</th>
                                <th>Qty</th>
                                <th>Status</th>
                                <th>Final Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rfqs as $i => $rfq)
                            <tr>
                                <td>{{ $rfq->id }}</td>
                                <td style="white-space:nowrap;">{{ $rfq->created_at->format('d M Y') }}</td>
                                <td style="font-weight:600;">
                                    {{ $rfq->name }}
                                    @if($rfq->user_id)
                                    <br><small style="color:var(--text-secondary);">Registered User</small>
                                    @endif
                                </td>
                                <td>{{ $rfq->phone }}</td>
                                <td style="max-width:200px;">
                                    <span style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">{{ $rfq->item_description }}</span>
                                </td>
                                <td>{{ $rfq->quantity }}</td>
                                <td><span class="badge-status badge-{{ $rfq->status }}">{{ $rfq->status }}</span></td>
                                <td>{{ $rfq->final_price ? 'Rs ' . number_format($rfq->final_price, 2) : '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.rfq.show', $rfq->id) }}" class="btn btn-sm btn-outline-primary" title="View & Process">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="https://wa.me/91{{ preg_replace('/[^0-9]/', '', $rfq->phone) }}?text={{ urlencode('Hi ' . $rfq->name . ', regarding your request for ' . $rfq->item_description . ' - ') }}"
                                       target="_blank" class="btn btn-sm btn-outline-success" title="WhatsApp">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">No RFQ requests found.</td>
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
    <script src="/assets/js/dataTables.buttons.min.js"></script>
    <script src="/assets/js/buttons.bootstrap5.min.js"></script>
    <script src="/assets/js/jszip.min.js"></script>
    <script src="/assets/js/buttons.html5.min.js"></script>
    <script>
        $(function () {
            $('#rfqTable').DataTable({
                pageLength: 25,
                order: [[0, 'desc']],
                dom: 'Bfrtip',
                buttons: ['copy', 'excel', 'csv']
            });
        });
    </script>
@endsection
