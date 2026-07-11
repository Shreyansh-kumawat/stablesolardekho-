@extends('layouts.adminLayout')

@section('title', 'CP Interest Requests')

@section('css')
<link href="/assets/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="/assets/css/buttons.bootstrap5.min.css" rel="stylesheet">
<link href="/assets/css/fa-all.min.css" rel="stylesheet">
<link href="/assets/css/bootstrap.min.css" rel="stylesheet">
<style>
    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .status-pending { background: #fef3c7; color: #92400e; }
    .status-approved { background: #d1fae5; color: #065f46; }
    .status-rejected { background: #fee2e2; color: #991b1b; }
    .action-btn {
        padding: 5px 12px;
        border: none;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s;
    }
    .btn-approve { background: #10b981; color: #fff; }
    .btn-approve:hover { background: #059669; }
    .btn-reject { background: #ef4444; color: #fff; }
    .btn-reject:hover { background: #dc2626; }
    .detail-modal { max-width: 500px; }
    .detail-row { display: flex; padding: 8px 0; border-bottom: 1px solid #f3f4f6; }
    .detail-label { width: 120px; font-weight: 600; color: #6b7280; font-size: 13px; }
    .detail-value { flex: 1; color: #1f2937; font-size: 13px; }
</style>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h1 class="text-lg font-bold text-gray-800">CP Interest Requests</h1>
                <p class="text-sm text-gray-500 mt-1">Review and approve channel partner applications</p>
            </div>
            <div class="flex gap-2">
                <span class="px-3 py-1 bg-amber-50 text-amber-700 rounded-full text-xs font-semibold">
                    {{ $interests->where('status', 'pending')->count() }} Pending
                </span>
                <span class="px-3 py-1 bg-green-50 text-green-700 rounded-full text-xs font-semibold">
                    {{ $interests->where('status', 'approved')->count() }} Approved
                </span>
            </div>
        </div>

        @if(session('success'))
            <div class="mx-6 mt-4 p-3 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="p-6">
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
                        <td class="fw-semibold">{{ $i->company_name }}</td>
                        <td>{{ $i->contact_person }}</td>
                        <td>{{ $i->email }}</td>
                        <td>{{ $i->mobile }}</td>
                        <td>{{ $i->city }}, {{ $i->state }}</td>
                        <td>{{ $i->created_at->format('d M Y') }}</td>
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
                                <div class="d-flex gap-1">
                                    <form action="{{ route('approveCpInterest', $i->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="action-btn btn-approve" onclick="return confirm('Approve {{ $i->company_name }} as Channel Partner?')">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('rejectCpInterest', $i->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="action-btn btn-reject" onclick="return confirm('Reject this application?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </div>
                            @else
                                <span class="text-gray-400 text-xs">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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
        language: { search: "", searchPlaceholder: "Search..." }
    });
});
</script>
@endsection
