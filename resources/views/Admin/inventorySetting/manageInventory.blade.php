@extends('layouts.adminLayout')

@section('css')
<link href="/assets/css/bootstrap.min.css" rel="stylesheet">
<link href="/assets/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="/assets/css/buttons.bootstrap5.min.css" rel="stylesheet">
<style>
    :root { --blue: #4A90E2; --light: #f5f7fa; --txt: #2d3436; --txt2: #636e72; --bdr: #e1e8ed; --green: #27ae60; --red: #e74c3c; }
    body { background: var(--light); color: var(--txt); }
    .page-header { background: #fff; padding: 1.25rem 1.5rem; margin-bottom: 1.25rem; border-radius: 8px; border: 1px solid var(--bdr); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: .5rem; }
    .page-header h1 { font-weight: 700; margin: 0; font-size: 1.2rem; }
    .page-header p { color: var(--txt2); margin: .2rem 0 0; font-size: .85rem; }
    .card { border: 1px solid var(--bdr); border-radius: 8px; background: #fff; }
    .card-body { padding: 1.25rem; }
    .table thead th { background: #f8f9fa; font-weight: 600; border-bottom: 1px solid var(--bdr); padding: .75rem .6rem; font-size: .75rem; text-transform: uppercase; color: var(--txt); }
    .table tbody td { padding: .6rem; vertical-align: middle; border-color: var(--bdr); font-size: .88rem; }
    .table tbody tr:hover { background: #f8f9fb; }
    .table-responsive { border-radius: 8px; border: 1px solid var(--bdr); overflow: hidden; }
    .btn-blue { background: var(--blue); border: none; color: #fff; padding: .45rem 1rem; border-radius: 6px; font-weight: 600; font-size: .82rem; text-decoration: none; display: inline-flex; align-items: center; gap: .35rem; }
    .btn-blue:hover { background: #3b7dc4; color: #fff; }
    .btn-out { background: #fff; border: 1px solid var(--bdr); color: var(--txt); padding: .45rem 1rem; border-radius: 6px; font-weight: 600; font-size: .82rem; text-decoration: none; }
    .btn-out:hover { background: var(--light); }
    .stock-ctrl { display: flex; align-items: center; gap: 4px; }
    .stock-btn { width: 28px; height: 28px; border-radius: 6px; border: 1px solid var(--bdr); background: #fff; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1rem; font-weight: 700; color: var(--txt); transition: all .12s; }
    .stock-btn:hover { border-color: var(--blue); color: var(--blue); background: #f0f6ff; }
    .stock-btn.minus:hover { border-color: var(--red); color: var(--red); background: #fff5f5; }
    .stock-input { width: 56px; height: 28px; text-align: center; border: 1px solid var(--bdr); border-radius: 6px; font-size: .85rem; font-weight: 600; outline: none; }
    .stock-input:focus { border-color: var(--blue); }
    .stock-save { height: 28px; padding: 0 10px; border-radius: 6px; border: none; background: var(--green); color: #fff; font-size: .72rem; font-weight: 700; cursor: pointer; display: none; transition: all .12s; }
    .stock-save:hover { background: #219a52; }
    .stock-save.show { display: inline-flex; align-items: center; }
    .stock-msg { font-size: .72rem; font-weight: 600; margin-left: 6px; display: none; }
    .badge-stock { padding: .2rem .5rem; border-radius: 10px; font-size: .72rem; font-weight: 700; }
    .badge-stock.in { background: #e8f8ef; color: var(--green); }
    .badge-stock.out { background: #fdf0ef; color: var(--red); }
    .dt-button { background: var(--blue) !important; border: 1px solid var(--blue) !important; border-radius: 6px !important; padding: .4rem .75rem !important; font-weight: 600 !important; color: #fff !important; font-size: .78rem !important; }
    .dt-button:hover { background: #3b7dc4 !important; }
    @media(max-width:768px) { .card-body { padding: .75rem; } .stock-ctrl { flex-wrap: wrap; } }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div>
            <h1>Inventory List</h1>
            <p>View and manage current inventory stock</p>
        </div>
        <div style="display:flex;gap:.5rem;">
            <a href="{{ route('inventoryAddProduct') }}" class="btn-blue">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Add Product
            </a>
            <a href="{{ route('addNewInventory') }}" class="btn-out">Add Stock</a>
            <a href="{{ route('transferInventory') }}" class="btn-out">Transfer</a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius:8px;font-size:.85rem;">
        {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="inventoryTable" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Item Name</th>
                            <th>Code</th>
                            <th>Category</th>
                            <th>UOM</th>
                            <th>Total In</th>
                            <th>Total Out</th>
                            <th>Current Stock</th>
                            <th style="min-width:200px;">Update Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(($inventory_list ?? []) as $index => $item)
                        <tr id="row-{{ $item->id }}">
                            <td>{{ $index + 1 }}</td>
                            <td style="font-weight:600;">{{ $item->item_name }}</td>
                            <td><code style="font-size:.78rem;">{{ $item->item_code }}</code></td>
                            <td>{{ $item->category_name ?? '-' }}</td>
                            <td>{{ $item->uom }}</td>
                            <td><span class="badge-stock in">{{ $item->total_in ?? 0 }}</span></td>
                            <td><span class="badge-stock out">{{ $item->total_out ?? 0 }}</span></td>
                            <td style="font-weight:700;" id="stockDisplay-{{ $item->id }}">{{ $item->current_stock ?? 0 }}</td>
                            <td>
                                <div class="stock-ctrl">
                                    <button type="button" class="stock-btn minus" onclick="stockChange({{ $item->id }}, -1)">−</button>
                                    <input type="number" min="0" class="stock-input" id="stockInput-{{ $item->id }}" value="{{ $item->current_stock ?? 0 }}" data-original="{{ $item->current_stock ?? 0 }}">
                                    <button type="button" class="stock-btn" onclick="stockChange({{ $item->id }}, 1)">+</button>
                                    <button type="button" class="stock-save" id="stockSave-{{ $item->id }}" onclick="stockSave({{ $item->id }})">Done</button>
                                    <span class="stock-msg" id="stockMsg-{{ $item->id }}"></span>
                                </div>
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
<script src="/assets/js/dataTables.buttons.min.js"></script>
<script src="/assets/js/buttons.bootstrap5.min.js"></script>
<script src="/assets/js/jszip.min.js"></script>
<script src="/assets/js/buttons.html5.min.js"></script>
<script src="/assets/js/buttons.print.min.js"></script>
<script>
$(function() {
    $('#inventoryTable').DataTable({
        dom: 'Bfrtip',
        buttons: ['excel','csv','print'],
        pageLength: 25,
        order: [[1,'asc']]
    });

    document.querySelectorAll('.stock-input').forEach(input => {
        input.addEventListener('input', function() {
            const id = this.id.replace('stockInput-','');
            const orig = parseInt(this.dataset.original);
            const val = parseInt(this.value) || 0;
            if (val < 0) this.value = 0;
            const btn = document.getElementById('stockSave-' + id);
            if (val !== orig) {
                btn.classList.add('show');
            } else {
                btn.classList.remove('show');
            }
        });
    });
});

function stockChange(id, delta) {
    const input = document.getElementById('stockInput-' + id);
    let val = parseInt(input.value) || 0;
    val += delta;
    if (val < 0) val = 0;
    input.value = val;
    input.dispatchEvent(new Event('input'));
}

function stockSave(id) {
    const input = document.getElementById('stockInput-' + id);
    const btn = document.getElementById('stockSave-' + id);
    const msg = document.getElementById('stockMsg-' + id);
    const display = document.getElementById('stockDisplay-' + id);
    const newQty = parseInt(input.value) || 0;

    btn.textContent = '...';
    btn.disabled = true;

    fetch('{{ route("inventoryQuickStockUpdate") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ product_id: id, new_qty: newQty })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            input.dataset.original = data.stock;
            display.textContent = data.stock;
            btn.classList.remove('show');
            msg.textContent = 'Saved!';
            msg.style.color = '#27ae60';
            msg.style.display = 'inline';
            setTimeout(() => { msg.style.display = 'none'; }, 2000);
        } else {
            msg.textContent = data.message || 'Error';
            msg.style.color = '#e74c3c';
            msg.style.display = 'inline';
        }
    })
    .catch(() => {
        msg.textContent = 'Failed';
        msg.style.color = '#e74c3c';
        msg.style.display = 'inline';
    })
    .finally(() => {
        btn.textContent = 'Done';
        btn.disabled = false;
    });
}
</script>
@endsection
