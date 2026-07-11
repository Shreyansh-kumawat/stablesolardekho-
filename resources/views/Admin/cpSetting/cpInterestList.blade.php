@extends('layouts.adminLayout')

@section('title', 'CP Interest Requests')

@section('css')
<link href="/assets/css/fa-all.min.css" rel="stylesheet">
<style>
    .cpi-wrap { max-width: 1200px; margin: 0 auto; padding: 1.5rem 1rem; }

    .cpi-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: .75rem; margin-bottom: 1.25rem; }
    .cpi-header-left h1 { font-size: 1.2rem; font-weight: 800; color: #1f2937; margin: 0; }
    .cpi-header-left p { font-size: .8rem; color: #6b7280; margin: .2rem 0 0; }
    .cpi-badges { display: flex; gap: .5rem; }
    .cpi-badge { padding: 5px 14px; border-radius: 20px; font-size: .7rem; font-weight: 700; }
    .cpi-badge-pending { background: #fef3c7; color: #92400e; }
    .cpi-badge-approved { background: #d1fae5; color: #065f46; }
    .cpi-badge-rejected { background: #fee2e2; color: #991b1b; }

    .cpi-success { padding: .75rem 1rem; background: #d1fae5; border: 1px solid #6ee7b7; border-radius: 8px; color: #065f46; font-size: .85rem; margin-bottom: 1rem; }

    /* Filter/Search */
    .cpi-filters { display: flex; gap: .75rem; margin-bottom: 1.25rem; flex-wrap: wrap; }
    .cpi-search { flex: 1; min-width: 200px; padding: .5rem .75rem; border: 1.5px solid #e5e7eb; border-radius: 8px; font-size: .85rem; background: #fff; color: #1f2937; }
    .cpi-search:focus { outline: none; border-color: #4A90E2; box-shadow: 0 0 0 3px rgba(74,144,226,.12); }
    .cpi-filter-btn { padding: .5rem 1rem; border: 1.5px solid #e5e7eb; border-radius: 8px; font-size: .8rem; font-weight: 600; cursor: pointer; background: #fff; color: #6b7280; transition: all .15s; }
    .cpi-filter-btn.active { background: #1f2937; color: #fff; border-color: #1f2937; }
    .cpi-filter-btn:hover:not(.active) { border-color: #9ca3af; }

    /* Cards Grid */
    .cpi-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 1rem; }
    @media(max-width: 420px) { .cpi-grid { grid-template-columns: 1fr; } }

    .cpi-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,.04); transition: box-shadow .15s, border-color .15s; position: relative; }
    .cpi-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,.08); border-color: #d1d5db; }

    .cpi-card-top { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: .75rem; }
    .cpi-card-company { font-size: 1rem; font-weight: 700; color: #1f2937; margin: 0; }
    .cpi-card-date { font-size: .7rem; color: #9ca3af; margin-top: 2px; }

    .cpi-card-status { flex-shrink: 0; }

    .cpi-card-info { display: grid; grid-template-columns: 1fr 1fr; gap: .5rem .75rem; margin-bottom: .85rem; }
    .cpi-card-field label { display: block; font-size: .65rem; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: .4px; margin-bottom: 1px; }
    .cpi-card-field span { font-size: .82rem; color: #374151; word-break: break-word; }
    .cpi-card-field.full { grid-column: 1 / -1; }

    .cpi-card-message { background: #f9fafb; border-radius: 6px; padding: .5rem .65rem; margin-bottom: .85rem; }
    .cpi-card-message label { display: block; font-size: .65rem; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: .4px; margin-bottom: 2px; }
    .cpi-card-message p { margin: 0; font-size: .8rem; color: #4b5563; line-height: 1.4; }

    .cpi-card-actions { display: flex; gap: .5rem; padding-top: .75rem; border-top: 1px solid #f3f4f6; }
    .cpi-card-actions form { flex: 1; }
    .cpi-act-btn { width: 100%; padding: .5rem; border: none; border-radius: 8px; font-size: .8rem; font-weight: 700; cursor: pointer; transition: all .15s; display: flex; align-items: center; justify-content: center; gap: .35rem; }
    .cpi-act-approve { background: #10b981; color: #fff; }
    .cpi-act-approve:hover { background: #059669; }
    .cpi-act-reject { background: #fee2e2; color: #991b1b; }
    .cpi-act-reject:hover { background: #fecaca; }

    .cpi-card-done { font-size: .8rem; color: #9ca3af; text-align: center; padding-top: .75rem; border-top: 1px solid #f3f4f6; }

    .cpi-empty { text-align: center; padding: 3rem 1rem; color: #9ca3af; font-size: .9rem; }

    .status-badge { padding: 3px 10px; border-radius: 20px; font-size: .65rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; }
    .status-pending { background: #fef3c7; color: #92400e; }
    .status-approved { background: #d1fae5; color: #065f46; }
    .status-rejected { background: #fee2e2; color: #991b1b; }
</style>
@endsection

@section('content')
<div class="cpi-wrap">
    <div class="cpi-header">
        <div class="cpi-header-left">
            <h1>CP Interest Requests</h1>
            <p>Review and approve channel partner applications</p>
        </div>
        <div class="cpi-badges">
            <span class="cpi-badge cpi-badge-pending">{{ $interests->where('status', 'pending')->count() }} Pending</span>
            <span class="cpi-badge cpi-badge-approved">{{ $interests->where('status', 'approved')->count() }} Approved</span>
            @if($interests->where('status', 'rejected')->count() > 0)
                <span class="cpi-badge cpi-badge-rejected">{{ $interests->where('status', 'rejected')->count() }} Rejected</span>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="cpi-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div style="padding:.75rem 1rem;background:#fee2e2;border:1px solid #fca5a5;border-radius:8px;color:#991b1b;font-size:.85rem;margin-bottom:1rem;">{{ session('error') }}</div>
    @endif

    <div class="cpi-filters">
        <input type="text" class="cpi-search" id="cpiSearch" placeholder="Search by name, email, city...">
        <button class="cpi-filter-btn active" data-filter="all">All</button>
        <button class="cpi-filter-btn" data-filter="pending">Pending</button>
        <button class="cpi-filter-btn" data-filter="approved">Approved</button>
        <button class="cpi-filter-btn" data-filter="rejected">Rejected</button>
    </div>

    @if($interests->count())
    <div class="cpi-grid" id="cpiGrid">
        @foreach($interests->sortByDesc('created_at') as $i)
        <div class="cpi-card" data-status="{{ $i->status ?? 'pending' }}" data-search="{{ strtolower($i->company_name . ' ' . $i->contact_person . ' ' . $i->email . ' ' . $i->mobile . ' ' . $i->city . ' ' . $i->state) }}">
            <div class="cpi-card-top">
                <div>
                    <h3 class="cpi-card-company">{{ $i->company_name }}</h3>
                    <div class="cpi-card-date">{{ $i->created_at->format('d M Y, h:i A') }}</div>
                </div>
                <div class="cpi-card-status">
                    @if($i->status === 'approved')
                        <span class="status-badge status-approved">Approved</span>
                    @elseif($i->status === 'rejected')
                        <span class="status-badge status-rejected">Rejected</span>
                    @else
                        <span class="status-badge status-pending">Pending</span>
                    @endif
                </div>
            </div>

            <div class="cpi-card-info">
                <div class="cpi-card-field">
                    <label>Contact Person</label>
                    <span>{{ $i->contact_person }}</span>
                </div>
                <div class="cpi-card-field">
                    <label>Mobile</label>
                    <span>{{ $i->mobile }}</span>
                </div>
                <div class="cpi-card-field full">
                    <label>Email</label>
                    <span>{{ $i->email }}</span>
                </div>
                <div class="cpi-card-field">
                    <label>City</label>
                    <span>{{ $i->city }}</span>
                </div>
                <div class="cpi-card-field">
                    <label>State</label>
                    <span>{{ $i->state }}</span>
                </div>
                @if($i->pin_code)
                <div class="cpi-card-field">
                    <label>Pin Code</label>
                    <span>{{ $i->pin_code }}</span>
                </div>
                @endif
            </div>

            @if($i->message)
            <div class="cpi-card-message">
                <label>Message</label>
                <p>{{ $i->message }}</p>
            </div>
            @endif

            @if($i->status === 'pending')
                <div class="cpi-card-actions">
                    <form action="{{ route('approveCpInterest', $i->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="cpi-act-btn cpi-act-approve" onclick="return confirm('Approve {{ addslashes($i->company_name) }} as Channel Partner?')">
                            <i class="fas fa-check"></i> Approve
                        </button>
                    </form>
                    <form action="{{ route('rejectCpInterest', $i->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="cpi-act-btn cpi-act-reject" onclick="return confirm('Reject this application?')">
                            <i class="fas fa-times"></i> Reject
                        </button>
                    </form>
                </div>
            @else
                <div class="cpi-card-done">
                    {{ $i->status === 'approved' ? 'Approved as Channel Partner' : 'Application rejected' }}
                </div>
            @endif
        </div>
        @endforeach
    </div>
    @else
        <div class="cpi-empty">No interest requests yet.</div>
    @endif
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var cards = document.querySelectorAll('.cpi-card');
    var filterBtns = document.querySelectorAll('.cpi-filter-btn');
    var searchInput = document.getElementById('cpiSearch');
    var currentFilter = 'all';

    function applyFilters() {
        var query = searchInput.value.toLowerCase().trim();
        cards.forEach(function(card) {
            var matchFilter = currentFilter === 'all' || card.dataset.status === currentFilter;
            var matchSearch = !query || card.dataset.search.indexOf(query) !== -1;
            card.style.display = matchFilter && matchSearch ? '' : 'none';
        });
    }

    filterBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            filterBtns.forEach(function(b) { b.classList.remove('active'); });
            btn.classList.add('active');
            currentFilter = btn.dataset.filter;
            applyFilters();
        });
    });

    searchInput.addEventListener('input', applyFilters);
});
</script>
@endsection
