@extends('layouts.adminLayout')

@section('css')
<style>
    :root {
        --primary-blue: #4A90E2;
        --border-color: #e1e8ed;
        --text-primary: #2d3436;
        --text-secondary: #636e72;
        --card-bg: #ffffff;
        --bg-soft: #f5f7fa;
    }
    body { background: var(--bg-soft); color: var(--text-primary); }
    .ss-header { background:#fff; padding:20px 24px; border-radius:8px; border:1px solid var(--border-color); margin-bottom:20px; }
    .ss-header h1 { font-size:1.3rem; font-weight:600; margin:0; color:var(--text-primary); }
    .ss-header p { color:var(--text-secondary); font-size:.9rem; margin:6px 0 0; }

    .ss-card { background:#fff; border:1px solid var(--border-color); border-radius:10px; padding:22px; margin-bottom:16px; }
    .ss-card h5 { font-size:.95rem; font-weight:600; margin:0 0 14px; padding-bottom:10px; border-bottom:1px solid #f1f3f5; }

    .prod-picker { position:relative; }
    .prod-picker input { width:100%; padding:10px 14px; border:1px solid var(--border-color); border-radius:8px; font-size:.9rem; outline:none; }
    .prod-picker input:focus { border-color: var(--primary-blue); }
    .prod-list { position:absolute; top:100%; left:0; right:0; background:#fff; border:1px solid var(--border-color); border-radius:8px; margin-top:4px; max-height:280px; overflow-y:auto; z-index:100; box-shadow:0 8px 20px rgba(0,0,0,.08); display:none; }
    .prod-list.show { display:block; }
    .prod-item { padding:10px 14px; cursor:pointer; font-size:.88rem; border-bottom:1px solid #f8f9fa; }
    .prod-item:hover { background: #f1f5fb; }
    .prod-item .code { color: var(--text-secondary); font-size:.78rem; }

    .serial-grid { display:grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap:10px; }
    .serial-item {
        background: #fff; border:1px solid var(--border-color); border-radius:8px;
        padding:12px; cursor:pointer; transition:all .15s;
    }
    .serial-item:hover { border-color: var(--primary-blue); box-shadow: 0 4px 12px rgba(74,144,226,.15); }
    .serial-item .sn { font-family:monospace; font-weight:600; font-size:.85rem; color: var(--text-primary); word-break:break-all; }
    .serial-item .meta { display:flex; justify-content:space-between; margin-top:8px; font-size:.72rem; color: var(--text-secondary); }
    .status-badge { padding:2px 8px; border-radius:12px; font-size:.7rem; font-weight:600; }
    .status-in_stock { background:#d1fae5; color:#065f46; }
    .status-sold { background:#fee2e2; color:#991b1b; }
    .status-issue_to_warehouse { background:#dbeafe; color:#1e40af; }
    .status-issued { background:#fef3c7; color:#92400e; }

    .search-filter { margin-bottom:12px; display:flex; gap:10px; }
    .search-filter input, .search-filter select { padding:8px 12px; border:1px solid var(--border-color); border-radius:7px; font-size:.86rem; outline:none; }
    .search-filter input { flex:1; }
    .search-filter input:focus, .search-filter select:focus { border-color: var(--primary-blue); }

    .modal-overlay { position:fixed; inset:0; background: rgba(0,0,0,.55); z-index:1050; display:none; align-items:center; justify-content:center; padding:20px; }
    .modal-overlay.show { display:flex; }
    .modal-body { background:#fff; border-radius:12px; max-width:640px; width:100%; max-height:85vh; overflow-y:auto; }
    .modal-head { padding:18px 22px; border-bottom:1px solid var(--border-color); display:flex; justify-content:space-between; align-items:center; }
    .modal-head h4 { margin:0; font-size:1rem; font-weight:600; }
    .modal-close { background:none; border:none; font-size:1.4rem; cursor:pointer; color:var(--text-secondary); line-height:1; }
    .modal-content { padding:20px 22px; }

    .info-grid { display:grid; grid-template-columns:1fr 1fr; gap:10px 20px; margin-bottom:20px; }
    .info-grid > div > label { display:block; font-size:.72rem; font-weight:700; color:var(--text-secondary); text-transform:uppercase; letter-spacing:.03em; margin-bottom:3px; }
    .info-grid > div > span { font-size:.88rem; color:var(--text-primary); font-weight:500; }

    .timeline-list { border-left: 2px solid #e1e8ed; padding-left: 16px; }
    .timeline-list .tl-item { position:relative; padding-bottom:14px; }
    .timeline-list .tl-item::before { content:''; position:absolute; left:-22px; top:5px; width:10px; height:10px; border-radius:50%; background:var(--primary-blue); border:2px solid #fff; box-shadow: 0 0 0 2px var(--primary-blue); }
    .tl-item .tl-date { font-size:.72rem; color:var(--text-secondary); font-weight:600; }
    .tl-item .tl-title { font-size:.86rem; font-weight:600; margin-top:2px; }
    .tl-item .tl-detail { font-size:.8rem; color:var(--text-secondary); margin-top:2px; }
    .tl-item .tl-meta { font-size:.7rem; color:#94a3b8; margin-top:2px; }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="ss-header">
        <h1><i class="fas fa-barcode me-2"></i> Serial Number Search</h1>
        <p>Pick a serial-tracked product to view all its serial numbers, or click a serial to see its full history.</p>
    </div>

    <div class="ss-card">
        <h5><i class="fas fa-box me-1"></i> Select Product</h5>
        <div class="prod-picker">
            <input type="text" id="prodSearch" placeholder="Search serial-tracked products..." autocomplete="off">
            <div class="prod-list" id="prodList">
                @foreach($products as $p)
                <div class="prod-item" data-name="{{ strtolower($p->item_name . ' ' . $p->item_code) }}" onclick="selectProduct({{ $p->id }}, {{ json_encode($p->item_name) }})">
                    <div>{{ $p->item_name }}</div>
                    <div class="code">Code: {{ $p->item_code ?? 'N/A' }}</div>
                </div>
                @endforeach
                @if($products->isEmpty())
                <div class="prod-item" style="color:#94a3b8; cursor:default;">No serial-tracked products yet. Enable "Serial Tracked" on a product first.</div>
                @endif
            </div>
        </div>
    </div>

    <div class="ss-card" id="serialCard" style="display:none;">
        <h5 id="serialHead"></h5>
        <div class="search-filter">
            <input type="text" id="serialFilter" placeholder="Filter serials by number..." oninput="filterSerials()">
            <select id="statusFilter" onchange="filterSerials()">
                <option value="">All Status</option>
                <option value="in_stock">In Stock</option>
                <option value="issue_to_warehouse">In Warehouse</option>
                <option value="sold">Sold</option>
                <option value="issued">Issued</option>
                <option value="damaged">Damaged</option>
                <option value="returned">Returned</option>
            </select>
        </div>
        <div id="serialLoading" style="text-align:center; padding:40px 0; color:#94a3b8;">Loading serials...</div>
        <div id="serialGrid" class="serial-grid"></div>
        <div id="serialEmpty" style="text-align:center; padding:40px 0; color:#94a3b8; display:none;">No serials for this product yet.</div>
    </div>
</div>

<div class="modal-overlay" id="historyModal" onclick="if(event.target===this)closeHistoryModal()">
    <div class="modal-body">
        <div class="modal-head">
            <h4>Serial History</h4>
            <button type="button" class="modal-close" onclick="closeHistoryModal()">&times;</button>
        </div>
        <div class="modal-content" id="historyContent">
            <div style="text-align:center;padding:20px;color:#94a3b8;">Loading...</div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="/assets/js/jquery-3.6.0.min.js"></script>
<script>
var currentSerials = [];

document.getElementById('prodSearch').addEventListener('input', function() {
    var q = this.value.toLowerCase();
    document.getElementById('prodList').classList.add('show');
    document.querySelectorAll('.prod-item').forEach(function(el) {
        var name = el.getAttribute('data-name') || '';
        el.style.display = (!q || name.indexOf(q) !== -1) ? '' : 'none';
    });
});
document.getElementById('prodSearch').addEventListener('focus', function() {
    document.getElementById('prodList').classList.add('show');
});
document.addEventListener('click', function(e) {
    if (!e.target.closest('.prod-picker')) {
        document.getElementById('prodList').classList.remove('show');
    }
});

function selectProduct(id, name) {
    document.getElementById('prodSearch').value = name;
    document.getElementById('prodList').classList.remove('show');
    document.getElementById('serialCard').style.display = '';
    document.getElementById('serialHead').innerHTML = '<i class="fas fa-list me-1"></i> Serials of "' + name + '"';
    document.getElementById('serialGrid').innerHTML = '';
    document.getElementById('serialLoading').style.display = '';
    document.getElementById('serialEmpty').style.display = 'none';

    fetch("/admin/inventory/serials/for-product/" + id)
        .then(function(r){ return r.json(); })
        .then(function(data) {
            currentSerials = data.serials || [];
            document.getElementById('serialLoading').style.display = 'none';
            renderSerials();
        })
        .catch(function(){
            document.getElementById('serialLoading').style.display = 'none';
            document.getElementById('serialEmpty').style.display = '';
        });
}

function renderSerials() {
    var q = (document.getElementById('serialFilter').value || '').toLowerCase();
    var status = document.getElementById('statusFilter').value;
    var filtered = currentSerials.filter(function(s) {
        if (q && (s.serial_number || '').toLowerCase().indexOf(q) === -1) return false;
        if (status && s.status !== status) return false;
        return true;
    });

    var grid = document.getElementById('serialGrid');
    if (filtered.length === 0) {
        grid.innerHTML = '';
        document.getElementById('serialEmpty').style.display = '';
        return;
    }
    document.getElementById('serialEmpty').style.display = 'none';

    grid.innerHTML = filtered.map(function(s) {
        var statusClass = 'status-' + (s.status || 'in_stock');
        var loc = s.warehouse_name ? s.warehouse_name : (s.current_location || '-');
        return '<div class="serial-item" onclick="showHistory(' + s.id + ')">'
             + '<div class="sn">' + escapeHtml(s.serial_number) + '</div>'
             + '<div class="meta"><span class="status-badge ' + statusClass + '">' + (s.status || '').replace(/_/g,' ') + '</span><span>' + escapeHtml(loc) + '</span></div>'
             + '</div>';
    }).join('');
}

function filterSerials() { renderSerials(); }

function showHistory(serialId) {
    var modal = document.getElementById('historyModal');
    var content = document.getElementById('historyContent');
    modal.classList.add('show');
    content.innerHTML = '<div style="text-align:center;padding:20px;color:#94a3b8;">Loading...</div>';

    fetch("/admin/inventory/serials/" + serialId + "/history")
        .then(function(r){ return r.json(); })
        .then(function(data) {
            var s = data.serial;
            var tl = data.timeline || [];
            var html = '<div class="info-grid">'
                     + '<div><label>Serial Number</label><span style="font-family:monospace;">' + escapeHtml(s.serial_number) + '</span></div>'
                     + '<div><label>Product</label><span>' + escapeHtml(s.product || '-') + '</span></div>'
                     + '<div><label>Status</label><span class="status-badge status-' + (s.status || 'in_stock') + '">' + (s.status || '').replace(/_/g,' ') + '</span></div>'
                     + '<div><label>Current Location</label><span>' + escapeHtml(s.warehouse || s.current_location || '-') + '</span></div>'
                     + '<div><label>Supplier</label><span>' + escapeHtml(s.supplier_name || '-') + '</span></div>'
                     + '<div><label>Purchase Price</label><span>' + (s.purchase_price ? '₹' + Number(s.purchase_price).toLocaleString('en-IN') : '-') + '</span></div>'
                     + '<div><label>Invoice #</label><span>' + escapeHtml(s.invoice_number || '-') + '</span></div>'
                     + '<div><label>Invoice Date</label><span>' + escapeHtml(s.invoice_date || '-') + '</span></div>'
                     + '</div>';
            html += '<h6 style="font-weight:600; margin:0 0 10px; font-size:.9rem;">Timeline</h6>';
            if (tl.length === 0) {
                html += '<div style="color:#94a3b8; font-size:.86rem;">No transactions found.</div>';
            } else {
                html += '<div class="timeline-list">';
                tl.forEach(function(t) {
                    html += '<div class="tl-item">'
                         + '<div class="tl-date">' + escapeHtml(t.date) + '</div>'
                         + '<div class="tl-title">' + escapeHtml(t.title) + '</div>'
                         + '<div class="tl-detail">' + escapeHtml(t.detail) + '</div>'
                         + '<div class="tl-meta">' + escapeHtml(t.meta) + '</div>'
                         + '</div>';
                });
                html += '</div>';
            }
            content.innerHTML = html;
        })
        .catch(function(){
            content.innerHTML = '<div style="color:#dc2626;">Failed to load history.</div>';
        });
}

function closeHistoryModal() {
    document.getElementById('historyModal').classList.remove('show');
}

function escapeHtml(s) {
    return String(s == null ? '' : s).replace(/[&<>"']/g, function(c) {
        return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c];
    });
}
</script>
@endsection
