@extends('layouts.adminLayout')
@section('title', 'CP Entries')
@section('page_title', 'CP Entries')

@section('css')
<style>
    .ce-wrap { padding: 1.25rem; }
    .ce-header { display: flex; align-items: center; gap: 12px; margin-bottom: 1.25rem; }
    .ce-icon { width: 40px; height: 40px; background: #7c3aed; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .ce-header h1 { font-size: 1.15rem; font-weight: 700; color: #1e293b; margin: 0; }
    .ce-header p { font-size: 0.78rem; color: #64748b; margin: 2px 0 0; }

    .ce-filters { margin-bottom: 16px; display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
    .ce-filters input { border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.82rem; width: 100%; max-width: 280px; }
    .ce-filters input:focus { outline: none; border-color: #7c3aed; box-shadow: 0 0 0 2px rgba(124,58,237,0.1); }
    .ce-filters select { border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.82rem; min-width: 180px; background: #fff; }
    .ce-filters select:focus { outline: none; border-color: #7c3aed; box-shadow: 0 0 0 2px rgba(124,58,237,0.1); }

    .ce-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 14px; }

    .ce-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; transition: box-shadow 0.2s; }
    .ce-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.06); }
    .ce-card-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 8px; margin-bottom: 10px; }
    .ce-cp-name { font-size: 0.88rem; font-weight: 700; color: #1e293b; }
    .ce-date { font-size: 0.7rem; color: #94a3b8; white-space: nowrap; }
    .ce-product { font-size: 0.82rem; font-weight: 600; color: #475569; margin-bottom: 6px; }
    .ce-qty { display: inline-block; background: #fee2e2; color: #dc2626; font-size: 0.75rem; font-weight: 700; padding: 2px 10px; border-radius: 20px; margin-bottom: 8px; }
    .ce-message { font-size: 0.8rem; color: #64748b; line-height: 1.4; background: #f8fafc; border-radius: 8px; padding: 8px 12px; border-left: 3px solid #7c3aed; }

    .ce-empty { text-align: center; padding: 3rem 1rem; color: #94a3b8; }
    .ce-empty svg { margin: 0 auto 12px; display: block; }

    @media(max-width:640px) {
        .ce-grid { grid-template-columns: 1fr; }
        .ce-wrap { padding: 12px; }
    }
</style>
@endsection

@section('content')
<div class="ce-wrap">
    <div class="ce-header">
        <div class="ce-icon">
            <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
        </div>
        <div>
            <h1>CP Entries</h1>
            <p>Stock usage entries logged by channel partners</p>
        </div>
    </div>

    <div class="ce-filters">
        <svg width="14" height="14" fill="none" stroke="#94a3b8" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
        <input type="text" id="ceSearch" placeholder="Search by product or entry...">
        <select id="ceCpFilter">
            <option value="">All Channel Partners</option>
            @foreach($entries->pluck('channelPartner')->unique('id')->sortBy('cp_name') as $cp)
                @if($cp)
                <option value="{{ strtolower($cp->cp_name) }}">{{ $cp->cp_name }}</option>
                @endif
            @endforeach
        </select>
    </div>

    @if($entries->count() > 0)
    <div class="ce-grid" id="ceGrid">
        @foreach($entries as $entry)
        <div class="ce-card" data-cp="{{ strtolower($entry->channelPartner->cp_name ?? '') }}" data-search="{{ strtolower(($entry->channelPartner->cp_name ?? '') . ' ' . ($entry->product->item_name ?? '') . ' ' . $entry->remarks) }}">
            <div class="ce-card-top">
                <span class="ce-cp-name">{{ $entry->channelPartner->cp_name ?? 'N/A' }}</span>
                <span class="ce-date">{{ $entry->created_at->format('d M Y, h:i A') }}</span>
            </div>
            <div class="ce-product">{{ $entry->product->item_name ?? 'N/A' }}</div>
            <span class="ce-qty">- {{ $entry->quantity }} {{ $entry->product->uom ?? '' }}</span>
            @if($entry->remarks)
            <div class="ce-message">{{ $entry->remarks }}</div>
            @endif
        </div>
        @endforeach
    </div>
    @else
    <div class="ce-empty">
        <svg width="48" height="48" fill="none" stroke="#cbd5e1" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
        <p style="font-weight:600;font-size:0.9rem;margin:0;">No entries yet</p>
        <p style="font-size:0.78rem;margin:4px 0 0;">CP stock usage entries will appear here</p>
    </div>
    @endif
</div>
@endsection

@section('js')
<script>
function filterEntries() {
    var q = document.getElementById('ceSearch').value.toLowerCase();
    var cp = document.getElementById('ceCpFilter').value;
    document.querySelectorAll('#ceGrid .ce-card').forEach(function(card) {
        var matchSearch = !q || card.getAttribute('data-search').indexOf(q) !== -1;
        var matchCp = !cp || card.getAttribute('data-cp') === cp;
        card.style.display = (matchSearch && matchCp) ? '' : 'none';
    });
}
document.getElementById('ceSearch').addEventListener('input', filterEntries);
document.getElementById('ceCpFilter').addEventListener('change', filterEntries);
</script>
@endsection
