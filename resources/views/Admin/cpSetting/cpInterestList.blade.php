@extends('layouts.adminLayout')

@section('title', 'CP Interest Requests')

@section('css')
<link href="/assets/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="/assets/css/buttons.bootstrap5.min.css" rel="stylesheet">
<link href="/assets/css/fa-all.min.css" rel="stylesheet">
<link href="/assets/css/bootstrap.min.css" rel="stylesheet">
<style>
    .cp-int-wrap { max-width: 1200px; margin: 0 auto; padding: 1.5rem 1rem; }
    .cp-int-card { background: #fff; border-radius: 10px; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0,0,0,.04); overflow: hidden; }
    .cp-int-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: .75rem; }
    .cp-int-title { font-size: 1.15rem; font-weight: 700; color: #1f2937; margin: 0; }
    .cp-int-subtitle { font-size: .85rem; color: #6b7280; margin: .15rem 0 0; }
    .cp-int-badges { display: flex; gap: .5rem; flex-wrap: wrap; }
    .cp-int-badge { padding: 4px 12px; border-radius: 20px; font-size: .7rem; font-weight: 700; }
    .cp-int-badge-pending { background: #fef3c7; color: #92400e; }
    .cp-int-badge-approved { background: #d1fae5; color: #065f46; }

    .cp-int-body { padding: 1.25rem 1.5rem; }

    .cp-int-success { padding: .75rem 1rem; background: #d1fae5; border: 1px solid #6ee7b7; border-radius: 8px; color: #065f46; font-size: .85rem; margin-bottom: 1rem; }

    /* Responsive table wrapper */
    .cp-int-table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }

    #interestTable { width: 100% !important; border-collapse: collapse; font-size: .85rem; }
    #interestTable thead th {
        background: #f9fafb;
        color: #374151;
        font-weight: 700;
        font-size: .75rem;
        text-transform: uppercase;
        letter-spacing: .3px;
        padding: .65rem .75rem;
        border-bottom: 2px solid #e5e7eb;
        white-space: nowrap;
    }
    #interestTable tbody td {
        padding: .6rem .75rem;
        border-bottom: 1px solid #f3f4f6;
        color: #374151;
        vertical-align: middle;
    }
    #interestTable tbody tr:hover { background: #f9fafb; }

    .status-badge {
        padding: 3px 10px;
        border-radius: 20px;
        font-size: .65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        white-space: nowrap;
    }
    .status-pending { background: #fef3c7; color: #92400e; }
    .status-approved { background: #d1fae5; color: #065f46; }
    .status-rejected { background: #fee2e2; color: #991b1b; }

    .action-btn {
        padding: 4px 10px;
        border: none;
        border-radius: 6px;
        font-size: .7rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s;
        white-space: nowrap;
    }
    .btn-approve { background: #10b981; color: #fff; }
    .btn-approve:hover { background: #059669; }
    .btn-reject { background: #ef4444; color: #fff; }
    .btn-reject:hover { background: #dc2626; }

    /* DataTables overrides */
    .dataTables_wrapper .dataTables_filter { margin-bottom: .75rem; }
    .dataTables_wrapper .dataTables_filter input { border: 1px solid #d1d5db; border-radius: 6px; padding: .4rem .75rem; font-size: .85rem; }
    .dataTables_wrapper .dataTables_length select { border: 1px solid #d1d5db; border-radius: 6px; padding: .3rem .5rem; font-size: .85rem; }
    .dataTables_wrapper .dataTables_info { font-size: .8rem; color: #6b7280; padding-top: .75rem; }
    .dataTables_wrapper .dataTables_paginate { padding-top: .75rem; }
    .dataTables_wrapper .dataTables_paginate .paginate_button { padding: .35rem .65rem !important; border-radius: 4px !important; font-size: .8rem !important; }

    @media(max-width: 768px) {
        .cp-int-header { padding: 1rem; }
        .cp-int-body { padding: 1rem; }
        .cp-int-title { font-size: 1rem; }
    }
</style>
@endsection

@section('content')
<div class="cp-int-wrap">
    <div class="cp-int-card">
        <div class="cp-int-header">
            <div>
                <h1 class="cp-int-title">CP Interest Requests</h1>
                <p class="cp-int-subtitle">Review and approve channel partner applications</p>
            </div>
            <div class="cp-int-badges">
                <span class="cp-int-badge cp-int-badge-pending">{{ $interests->where('status', 'pending')->count() }} Pending</span>
                <span class="cp-int-badge cp-int-badge-approved">{{ $interests->where('status', 'approved')->count() }} Approved</span>
            </div>
        </div>

        <div class="cp-int-body">
            @if(session('success'))
                <div class="cp-int-success">{{ session('success') }}</div>
            @endif

            <div class="cp-int-table-wrap">
                <table id="interestTable" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Company</th>
                            <th>Contact</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>City</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($interests as $i)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td style="font-weight:600;">{{ $i->company_name }}</td>
                            <td>{{ $i->contact_person }}</td>
                            <td style="font-size:.8rem;">{{ $i->email }}</td>
                            <td>{{ $i->mobile }}</td>
                            <td>{{ $i->city }}, {{ $i->state }}</td>
                            <td style="white-space:nowrap;">{{ $i->created_at->format('d M Y') }}</td>
                            <td>
                                @if($i->status === 'approved')
                                    <span class="status-badge status-approved">Approved</span>
                                @elseif($i->status === 'rejected')
                                    <span class="status-badge status-rejected">Rejected</span>
                                @else
                                    <span class="status-badge status-pending">Pending</span>
                                @endif
                            </td>
                            <td>
                                @if($i->status === 'pending')
                                    <div style="display:flex;gap:4px;flex-wrap:nowrap;">
                                        <form action="{{ route('approveCpInterest', $i->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="action-btn btn-approve" onclick="return confirm('Approve {{ addslashes($i->company_name) }} as Channel Partner?')">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('rejectCpInterest', $i->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="action-btn btn-reject" onclick="return confirm('Reject this application?')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span style="color:#9ca3af;font-size:.8rem;">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
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
$(document).ready(function() {
    $('#interestTable').DataTable({
        order: [[6, 'desc']],
        pageLength: 25,
        responsive: true,
        scrollX: true,
        language: { search: "", searchPlaceholder: "Search..." }
    });
});
</script>
@endsection
