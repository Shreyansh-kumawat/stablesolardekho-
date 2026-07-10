@extends('layouts.adminLayout')

@section('title', 'Fund Transaction Report')

@section('css')
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
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

        body { background: var(--primary-light); color: var(--text-primary); font-size: 0.85rem; }
        .page-header { padding: 1rem; margin-bottom: 1rem; }
        .page-header h1 { font-size: 1.05rem; margin-bottom: 0.15rem; }
        .page-header p { font-size: 0.8rem; }

        .card { padding: 1rem; }
        .card-body { padding: 0; }

        .form-label { font-size: 0.78rem; margin-bottom: 0.35rem; }
        .form-control, .form-select {
            min-height: 34px;
            font-size: 0.82rem;
            padding: 0.35rem 0.6rem;
        }

        .btn-primary-theme {
            background: var(--primary-blue);
            border: 1px solid var(--primary-blue);
            color: #fff;
            font-weight: 600;
            border-radius: 6px;
            padding: 0.4rem 0.9rem;
            font-size: 0.82rem;
            transition: all 0.2s ease;
        }
        .btn-primary-theme:hover {
            background: #3b7dc4;
            border-color: #3b7dc4;
        }

        .btn-secondary-theme {
            background: #fff;
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            font-weight: 600;
            border-radius: 6px;
            padding: 0.4rem 0.9rem;
            font-size: 0.82rem;
            transition: all 0.2s ease;
        }
        .btn-secondary-theme:hover {
            background: var(--hover-bg);
            border-color: #cfd8dc;
        }

        .row-credit { background: #e8f7ee; }
        .row-debit { background: #fdecec; }
        .text-credit { color: #198754; font-weight: 600; }
        .text-debit { color: #dc3545; font-weight: 600; }

        .filter-row {
            display: flex;
            gap: 0.75rem;
            align-items: end;
            flex-wrap: nowrap;
            overflow-x: auto;
        }
        .filter-row > div { min-width: 170px; }
        .filter-row .actions { min-width: auto; }

        .table thead th { font-size: 0.75rem; padding: 0.6rem; }
        .table tbody td { font-size: 0.82rem; padding: 0.6rem; }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <div class="container-fluid">
            <h1><i class="fas fa-wallet"></i>Manage Wallet Transactions</h1>
            <p>Organize and manage your wallet transactions efficiently</p>
        </div>
    </div>
    <div class="card shadow">
        <div class="card-body">
        <form method="GET" action="{{ route('fundTransactionList') }}">
            <div class="filter-row">
                <div>
                    <label class="form-label">Channel Partner</label>
                    <select name="cp_id" class="form-select">
                        <option value="">All</option>
                        @foreach($cp_list as $cp)
                            <option value="{{ $cp->id }}" {{ request('cp_id') == $cp->id ? 'selected' : '' }}>
                                {{ $cp->cp_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">From Date</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
                </div>
                <div>
                    <label class="form-label">To Date</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
                </div>
                <div style="flex: 0 0 auto; display: flex; gap: 0.5rem;">
                    <button class="btn btn-primary-theme" type="submit">Filter</button>
                    <a class="btn btn-secondary-theme" href="{{ route('fundTransactionList') }}">Reset</a>
                </div>
            </div>
        </form>

        <div class="controls-top">
            <div id="buttons_export"></div>
        </div>

        <div class="table-responsive">
            <table id="fundTable" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>Channel Partner</th>
                        <th>Type</th>
                        <th>Purpose</th>
                        <th>Amount</th>
                        <th>Remarks</th>
                        <th>Balance After</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $txn)
                        @php
                            $type = strtoupper($txn->transaction_type ?? '');
                        @endphp
                        <tr class="{{ $type === 'CREDIT' ? 'row-credit' : ($type === 'DEBIT' ? 'row-debit' : '') }}">
                            <td>{{ $txn->channelPartner->cp_name ?? 'N/A' }}</td>
                            <td>
                                <span class="{{ $type === 'CREDIT' ? 'text-credit' : ($type === 'DEBIT' ? 'text-debit' : '') }}">
                                    {{ $txn->transaction_type ?? 'N/A' }}
                                </span>
                            </td>
                            <td>{{ $txn->source ?? 'N/A' }}</td>
                            <td class="{{ $type === 'CREDIT' ? 'text-credit' : ($type === 'DEBIT' ? 'text-debit' : '') }}">
                                {{ number_format($txn->amount, 2) }}
                            </td>
                            <td>{{ $txn->remarks ?? '-' }}</td>
                            <td style="font-weight: 600;">{{ number_format($txn->closing_balance, 2) }}</td>
                            <td>{{ \Carbon\Carbon::parse($txn->created_at)->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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
    <script src="/assets/js/buttons.print.min.js"></script>

    <script>
        $(function () {
            const table = $('#fundTable').DataTable({
                dom: '<"controls-top"lBf>rtip',
                buttons: [
                    {
                        extend: 'excel',
                        className: 'btn-export',
                        text: '<i class="fas fa-file-excel me-1"></i>Excel'
                    },
                    {
                        extend: 'csv',
                        className: 'btn-export',
                        text: '<i class="fas fa-file-csv me-1"></i>CSV'
                    },
                    {
                        extend: 'print',
                        className: 'btn-export',
                        text: '<i class="fas fa-print me-1"></i>Print'
                    }
                ],
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
                order: [[6, 'desc']]
            });

        });
    </script>
@endsection