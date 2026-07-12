@extends('layouts.adminLayout')

@section('title', 'Channel Partners')

@section('css')
<link href="/assets/css/fa-all.min.css" rel="stylesheet">
<style>
    .cp-wrap { max-width: 1200px; margin: 0 auto; padding: 1.5rem 1rem; }

    .cp-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: .75rem; margin-bottom: 1.25rem; }
    .cp-header-left h1 { font-size: 1.2rem; font-weight: 800; color: #1f2937; margin: 0; }
    .cp-header-left p { font-size: .8rem; color: #6b7280; margin: .2rem 0 0; }
    .cp-header-right { display: flex; gap: .5rem; align-items: center; }
    .cp-add-btn { display: inline-flex; align-items: center; gap: .4rem; padding: .5rem 1rem; background: #4A90E2; color: #fff; border-radius: 8px; font-size: .85rem; font-weight: 700; text-decoration: none; transition: background .15s; border: none; cursor: pointer; }
    .cp-add-btn:hover { background: #3b7dc4; color: #fff; }

    .cp-stats { display: flex; gap: .5rem; margin-bottom: 1.25rem; flex-wrap: wrap; }
    .cp-stat { padding: 5px 14px; border-radius: 20px; font-size: .7rem; font-weight: 700; }
    .cp-stat-total { background: #e3f2fd; color: #1565c0; }
    .cp-stat-active { background: #d1fae5; color: #065f46; }
    .cp-stat-inactive { background: #fee2e2; color: #991b1b; }

    .cp-success { padding: .75rem 1rem; background: #d1fae5; border: 1px solid #6ee7b7; border-radius: 8px; color: #065f46; font-size: .85rem; margin-bottom: 1rem; }
    .cp-error { padding: .75rem 1rem; background: #fee2e2; border: 1px solid #fca5a5; border-radius: 8px; color: #991b1b; font-size: .85rem; margin-bottom: 1rem; }

    .cp-filters { display: flex; gap: .75rem; margin-bottom: 1.25rem; flex-wrap: wrap; }
    .cp-search { flex: 1; min-width: 200px; padding: .5rem .75rem; border: 1.5px solid #e5e7eb; border-radius: 8px; font-size: .85rem; background: #fff; color: #1f2937; }
    .cp-search:focus { outline: none; border-color: #4A90E2; box-shadow: 0 0 0 3px rgba(74,144,226,.12); }
    .cp-filter-btn { padding: .5rem 1rem; border: 1.5px solid #e5e7eb; border-radius: 8px; font-size: .8rem; font-weight: 600; cursor: pointer; background: #fff; color: #6b7280; transition: all .15s; }
    .cp-filter-btn.active { background: #1f2937; color: #fff; border-color: #1f2937; }
    .cp-filter-btn:hover:not(.active) { border-color: #9ca3af; }

    .cp-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 1rem; }
    @media(max-width: 420px) { .cp-grid { grid-template-columns: 1fr; } }

    .cp-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,.04); transition: box-shadow .15s, border-color .15s; position: relative; }
    .cp-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,.08); border-color: #d1d5db; }

    .cp-card-top { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: .75rem; }
    .cp-card-company { font-size: 1rem; font-weight: 700; color: #1f2937; margin: 0; }
    .cp-card-date { font-size: .7rem; color: #9ca3af; margin-top: 2px; }
    .cp-card-status { flex-shrink: 0; }

    .cp-card-info { display: grid; grid-template-columns: 1fr 1fr; gap: .5rem .75rem; margin-bottom: .85rem; }
    .cp-card-field label { display: block; font-size: .65rem; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: .4px; margin-bottom: 1px; }
    .cp-card-field span { font-size: .82rem; color: #374151; word-break: break-word; }
    .cp-card-field.full { grid-column: 1 / -1; }

    .cp-card-meta { display: flex; gap: .5rem; flex-wrap: wrap; margin-bottom: .85rem; }
    .cp-meta-tag { padding: 3px 10px; border-radius: 6px; font-size: .72rem; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; }
    .cp-meta-role { background: #e3f2fd; color: #1565c0; }
    .cp-meta-users { background: #f3e5f5; color: #6a1b9a; }
    .cp-meta-balance { background: #fff3e0; color: #e65100; }

    .cp-card-actions { display: flex; gap: .4rem; padding-top: .75rem; border-top: 1px solid #f3f4f6; flex-wrap: wrap; }
    .cp-act-btn { padding: .4rem .7rem; border: none; border-radius: 6px; font-size: .75rem; font-weight: 700; cursor: pointer; transition: all .15s; display: inline-flex; align-items: center; gap: .3rem; text-decoration: none; }
    .cp-act-view { background: #e3f2fd; color: #1565c0; }
    .cp-act-view:hover { background: #bbdefb; color: #1565c0; }
    .cp-act-edit { background: #fff3e0; color: #e65100; }
    .cp-act-edit:hover { background: #ffe0b2; color: #e65100; }
    .cp-act-delete { background: #fee2e2; color: #991b1b; }
    .cp-act-delete:hover { background: #fecaca; color: #991b1b; }
    .cp-act-status { border: none; padding: 4px 10px; border-radius: 12px; font-size: .7rem; font-weight: 700; cursor: pointer; }
    .cp-act-active { background: #d1fae5; color: #065f46; }
    .cp-act-inactive { background: #fee2e2; color: #991b1b; }

    .status-badge { padding: 3px 10px; border-radius: 20px; font-size: .65rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; }
    .status-active { background: #d1fae5; color: #065f46; }
    .status-inactive { background: #fee2e2; color: #991b1b; }

    .cp-empty { text-align: center; padding: 3rem 1rem; color: #9ca3af; font-size: .9rem; }
</style>
@endsection

@section('content')
<div class="cp-wrap">
    <div class="cp-header">
        <div class="cp-header-left">
            <h1>Channel Partners</h1>
            <p>Manage all channel partners and their information</p>
        </div>
        <div class="cp-header-right">
            <a href="{{ route('addNewCp') }}" class="cp-add-btn">
                <i class="fas fa-plus"></i> Add New Partner
            </a>
        </div>
    </div>

    <div class="cp-stats">
        <span class="cp-stat cp-stat-total">{{ $cp_list->count() }} Total</span>
        <span class="cp-stat cp-stat-active">{{ $cp_list->where('is_active', 1)->count() }} Active</span>
        @if($cp_list->where('is_active', 0)->count() > 0)
            <span class="cp-stat cp-stat-inactive">{{ $cp_list->where('is_active', 0)->count() }} Inactive</span>
        @endif
    </div>

    @if(session('success'))
        <div class="cp-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="cp-error">{{ session('error') }}</div>
    @endif

    <div class="cp-filters">
        <input type="text" class="cp-search" id="cpSearch" placeholder="Search by name, email, city...">
        <button class="cp-filter-btn active" data-filter="all">All</button>
        <button class="cp-filter-btn" data-filter="active">Active</button>
        <button class="cp-filter-btn" data-filter="inactive">Inactive</button>
    </div>

    @if($cp_list->count())
    <div class="cp-grid" id="cpGrid">
        @foreach($cp_list as $cp)
        <div class="cp-card" data-status="{{ $cp->is_active ? 'active' : 'inactive' }}" data-search="{{ strtolower($cp->cp_name . ' ' . $cp->contact_person . ' ' . $cp->email . ' ' . $cp->phone_number . ' ' . $cp->city . ' ' . $cp->state) }}">
            <div class="cp-card-top">
                <div>
                    <h3 class="cp-card-company">{{ $cp->cp_name }}</h3>
                    <div class="cp-card-date">{{ $cp->created_at ? $cp->created_at->format('d M Y, h:i A') : 'N/A' }}</div>
                </div>
                <div class="cp-card-status">
                    @if($cp->is_active)
                        <span class="status-badge status-active">Active</span>
                    @else
                        <span class="status-badge status-inactive">Inactive</span>
                    @endif
                </div>
            </div>

            <div class="cp-card-info">
                <div class="cp-card-field">
                    <label>Contact Person</label>
                    <span>{{ $cp->contact_person }}</span>
                </div>
                <div class="cp-card-field">
                    <label>Mobile</label>
                    <span>{{ $cp->phone_number }}</span>
                </div>
                <div class="cp-card-field full">
                    <label>Email</label>
                    <span>{{ $cp->email }}</span>
                </div>
                <div class="cp-card-field">
                    <label>City</label>
                    <span>{{ $cp->city ?? 'N/A' }}</span>
                </div>
                <div class="cp-card-field">
                    <label>State</label>
                    <span>{{ $cp->state ?? 'N/A' }}</span>
                </div>
            </div>

            <div class="cp-card-meta">
                <span class="cp-meta-tag cp-meta-role">
                    <i class="fas fa-tag"></i> {{ $cp->role ? $cp->role->role_name : 'N/A' }}
                </span>
                <span class="cp-meta-tag cp-meta-users">
                    <i class="fas fa-users"></i> {{ $cp->associateUsers ? $cp->associateUsers->count() : 0 }} Users
                </span>
                <span class="cp-meta-tag cp-meta-balance">
                    <i class="fas fa-wallet"></i> {{ $cp->wallet ? number_format($cp->wallet->balance, 2) : '0.00' }}
                </span>
            </div>

            <div class="cp-card-actions">
                <a href="{{ route('cpDetail', $cp->id) }}" class="cp-act-btn cp-act-view">
                    <i class="fas fa-eye"></i> View
                </a>
                <a href="{{ route('edit_cp', $cp->id) }}" class="cp-act-btn cp-act-edit">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <form action="{{ route('toggleCpStatus', $cp->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @if($cp->is_active)
                        <button type="submit" class="cp-act-btn cp-act-status cp-act-active" title="Click to deactivate">
                            <i class="fas fa-toggle-on"></i> Active
                        </button>
                    @else
                        <button type="submit" class="cp-act-btn cp-act-status cp-act-inactive" title="Click to activate">
                            <i class="fas fa-toggle-off"></i> Inactive
                        </button>
                    @endif
                </form>
                <button type="button" class="cp-act-btn cp-act-delete" onclick="deleteCp({{ $cp->id }})">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        </div>
        @endforeach
    </div>
    @else
        <div class="cp-empty">No channel partners yet. <a href="{{ route('addNewCp') }}">Add your first partner</a></div>
    @endif
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var cards = document.querySelectorAll('.cp-card');
    var filterBtns = document.querySelectorAll('.cp-filter-btn');
    var searchInput = document.getElementById('cpSearch');
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

function deleteCp(id) {
    Swal.fire({
        title: 'Delete Channel Partner?',
        text: 'Associated users will be reverted to normal users.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel'
    }).then(function(result) {
        if (result.isConfirmed) {
            fetch('/admin/cp/' + id + '/delete', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success) {
                    Swal.fire('Deleted!', 'Channel Partner deleted successfully.', 'success')
                        .then(function() { location.reload(); });
                } else {
                    Swal.fire('Error', data.message || 'Failed to delete.', 'error');
                }
            })
            .catch(function() {
                Swal.fire('Error', 'Something went wrong.', 'error');
            });
        }
    });
}
</script>
@endsection
