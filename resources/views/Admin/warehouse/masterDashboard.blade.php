@extends('layouts.adminLayout')

@section('css')
    <style>
        :root { --primary-blue: #4A90E2; --primary-light: #f5f7fa; --text-primary: #2d3436; --text-secondary: #636e72; --border-color: #e1e8ed; --hover-bg: #f1f3f5; --card-bg: #ffffff; }
        body { background: var(--primary-light); color: var(--text-primary); }
        .page-header { background: #ffffff; padding: 1.5rem 0; margin-bottom: 1.5rem; border-radius: 8px; border: 1px solid var(--border-color); box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
        .page-header h1 { color: var(--text-primary); font-weight: 600; margin: 0; font-size: 1.25rem; }
        .page-header p { color: var(--text-secondary); margin: 0.35rem 0 0 0; font-size: 0.9rem; }
        .card { border: 1px solid var(--border-color); border-radius: 8px; background: var(--card-bg); box-shadow: 0 1px 3px rgba(0,0,0,0.04); margin-bottom: 1rem; }
        .card-body { padding: 1.5rem; }
        .card-title { font-weight: 600; font-size: 0.95rem; margin-bottom: 1rem; color: var(--text-primary); }
        .stat-strip { display: flex; gap: 12px; flex-wrap: wrap; }
        .stat-item { flex: 1; min-width: 160px; background: #fff; border: 1px solid var(--border-color); border-radius: 8px; padding: 0.75rem 1rem; display: flex; align-items: center; gap: 8px; }
        .stat-item .stat-num { font-size: 1.1rem; font-weight: 700; white-space: nowrap; }
        .stat-item .stat-label { font-size: 0.78rem; color: var(--text-secondary); }
        .table thead th { background: #f8f9fa; color: var(--text-primary); font-weight: 600; border-bottom: 1px solid var(--border-color); padding: 0.75rem; font-size: 0.8rem; text-transform: uppercase; }
        .table tbody td { padding: 0.7rem 0.75rem; vertical-align: middle; border-color: var(--border-color); font-size: 0.85rem; }
        .table tbody tr:hover { background-color: var(--hover-bg); }
        .badge-in { background: #d3f9d8; color: #2b8a3e; padding: 0.25rem 0.55rem; border-radius: 12px; font-weight: 600; font-size: 0.73rem; }
        .badge-out { background: #fff5f5; color: #c92a2a; padding: 0.25rem 0.55rem; border-radius: 12px; font-weight: 600; font-size: 0.73rem; }
        .wh-card { background: #fff; border: 1px solid var(--border-color); border-radius: 8px; padding: 1rem 1.25rem; transition: border-color 0.15s; text-decoration: none; display: block; color: inherit; }
        .wh-card:hover { border-color: var(--primary-blue); }
        .wh-card .wh-name { font-weight: 700; font-size: 0.95rem; color: var(--text-primary); margin: 0 0 8px; }
        .wh-card .wh-stats { display: flex; gap: 16px; flex-wrap: wrap; }
        .wh-card .wh-stat { text-align: center; }
        .wh-card .wh-stat .num { font-weight: 700; font-size: 1.1rem; color: var(--primary-blue); }
        .wh-card .wh-stat .lbl { font-size: 0.7rem; color: var(--text-secondary); }
        .alert-low { background: #fff8e1; border: 1px solid #ffd54f; border-radius: 8px; padding: 0.6rem 1rem; display: flex; align-items: center; gap: 8px; margin-bottom: 6px; font-size: 0.82rem; }
        .alert-low .qty { font-weight: 700; color: #c92a2a; }
        .quick-link { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; font-size: 0.85rem; border: 1px solid var(--border-color); background: #fff; color: var(--text-primary); text-decoration: none; transition: all 0.15s; }
        .quick-link:hover { background: var(--primary-blue); color: #fff; border-color: var(--primary-blue); }

        .cmp-row { display: grid; grid-template-columns: 22px 1fr auto auto auto; align-items: center; gap: 12px; padding: 10px 0; border-bottom: 1px solid #f1f3f5; }
        .cmp-row:last-child { border-bottom: none; }
        .cmp-rank { width: 22px; height: 22px; border-radius: 50%; background: #eef3ff; color: var(--primary-blue); font-weight: 700; font-size: 0.72rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .cmp-rank.top { background: #fff3cd; color: #b8860b; }
        .cmp-rank.idle { background: #f1f3f5; color: #868e96; }
        .cmp-name { font-weight: 600; font-size: 0.88rem; color: var(--text-primary); min-width: 0; }
        .cmp-name .sub { display: block; font-size: 0.7rem; font-weight: 500; color: var(--text-secondary); margin-top: 1px; }
        .cmp-bar-wrap { width: 140px; height: 8px; background: #f1f3f5; border-radius: 4px; overflow: hidden; }
        .cmp-bar { height: 100%; background: linear-gradient(90deg, #4A90E2, #667eea); border-radius: 4px; }
        .cmp-bar.idle { background: #ced4da; }
        .cmp-metric { font-weight: 700; font-size: 0.85rem; color: var(--text-primary); white-space: nowrap; }
        .cmp-status { padding: 2px 10px; border-radius: 12px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.03em; }
        .cmp-status.active { background: #d3f9d8; color: #2b8a3e; }
        .cmp-status.moderate { background: #fff3cd; color: #b8860b; }
        .cmp-status.idle { background: #f1f3f5; color: #868e96; }

        .pnl-strip { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 14px; }
        .pnl-tile { flex: 1; min-width: 160px; padding: 12px 14px; border-radius: 8px; border: 1px solid var(--border-color); background: #fff; }
        .pnl-tile .l { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.04em; color: var(--text-secondary); font-weight: 600; }
        .pnl-tile .v { font-size: 1.15rem; font-weight: 800; margin-top: 3px; }
        .pnl-tile.profit { background: #f0fdf4; border-color: #bbf7d0; }
        .pnl-tile.loss { background: #fef2f2; border-color: #fecaca; }
        .pnl-row { display: grid; grid-template-columns: 1fr auto auto auto; gap: 12px; padding: 9px 0; border-bottom: 1px solid #f1f3f5; align-items: center; font-size: 0.85rem; }
        .pnl-row:last-child { border-bottom: none; }
        .pnl-row .n { font-weight: 600; color: var(--text-primary); }
        .pnl-row .in { color: #2b8a3e; font-weight: 600; }
        .pnl-row .out { color: #c92a2a; font-weight: 600; }
        .pnl-row .pl { font-weight: 800; padding: 2px 10px; border-radius: 6px; white-space: nowrap; }
        .pnl-row .pl.pos { background: #d3f9d8; color: #2b8a3e; }
        .pnl-row .pl.neg { background: #ffe3e3; color: #c92a2a; }
        .pnl-row .pl.zero { background: #f1f3f5; color: #868e96; }

        .dist-wrap { display: grid; grid-template-columns: 220px 1fr; gap: 24px; align-items: center; }
        .dist-donut { width: 200px; height: 200px; margin: 0 auto; }
        .dist-donut .center-num { font-size: 1.3rem; font-weight: 800; fill: var(--text-primary); }
        .dist-donut .center-lbl { font-size: 0.68rem; fill: var(--text-secondary); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
        .dist-legend { display: flex; flex-direction: column; gap: 8px; }
        .dist-item { display: grid; grid-template-columns: 14px 1fr auto auto; gap: 10px; align-items: center; font-size: 0.85rem; padding: 4px 0; }
        .dist-swatch { width: 12px; height: 12px; border-radius: 3px; }
        .dist-name { font-weight: 600; color: var(--text-primary); }
        .dist-qty { color: var(--text-secondary); font-weight: 600; }
        .dist-pct { font-weight: 800; color: var(--primary-blue); min-width: 46px; text-align: right; }
        @media (max-width: 768px) { .dist-wrap { grid-template-columns: 1fr; } }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div>
                <h1><i class="fas fa-th-large me-2"></i>Warehouse Master Dashboard</h1>
                <p>Cross-warehouse overview and alerts</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.warehouses.w2wTransfer') }}" class="quick-link"><i class="fas fa-exchange-alt"></i> W2W Transfer</a>
                <a href="{{ route('admin.warehouses.transfer') }}" class="quick-link"><i class="fas fa-arrow-right"></i> Main to WH</a>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        @if($lowStockItems->count())
        <div class="card">
            <div class="card-body">
                <h5 class="card-title" style="color:#e67700;"><i class="fas fa-exclamation-triangle me-1"></i> Low Stock Alerts (5 or less)</h5>
                @foreach($lowStockItems as $item)
                <div class="alert-low">
                    <i class="fas fa-exclamation-circle" style="color:#f59e0b;"></i>
                    <span><strong>{{ $item->warehouse_name }}</strong> &rarr; {{ $item->item_name }} ({{ $item->item_code }})</span>
                    <span class="qty">Only {{ $item->available_qty }} left</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="stat-strip mb-3">
            <div class="stat-item"><span class="stat-num" style="color:var(--primary-blue);">{{ $totalWarehouses }}</span> <span class="stat-label">Active Warehouses</span></div>
            <div class="stat-item"><span class="stat-num" style="color:#e67700;">{{ $grandTotalStock }}</span> <span class="stat-label">Total Stock</span></div>
            <div class="stat-item"><span class="stat-num" style="color:#2b8a3e;">Rs {{ number_format($grandTotalIn, 2) }}</span> <span class="stat-label">Total IN Value</span></div>
            <div class="stat-item"><span class="stat-num" style="color:#c92a2a;">Rs {{ number_format($grandTotalOut, 2) }}</span> <span class="stat-label">Total OUT Value</span></div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-warehouse me-1"></i> All Warehouses</h5>
                <div class="row g-3">
                    @foreach($warehouses as $wh)
                    <div class="col-md-4">
                        <a href="{{ route('admin.warehouses.dashboard', $wh->id) }}" class="wh-card">
                            <p class="wh-name"><i class="fas fa-warehouse me-1" style="color:var(--primary-blue);"></i> {{ $wh->name }}</p>
                            <div class="wh-stats">
                                <div class="wh-stat">
                                    <div class="num">{{ $wh->inventories_count }}</div>
                                    <div class="lbl">Products</div>
                                </div>
                                <div class="wh-stat">
                                    <div class="num" style="color:#e67700;">{{ $wh->total_stock }}</div>
                                    <div class="lbl">Stock</div>
                                </div>
                                <div class="wh-stat">
                                    <div class="num" style="color:#2b8a3e;">{{ number_format($wh->total_in_value) }}</div>
                                    <div class="lbl">IN Value</div>
                                </div>
                                <div class="wh-stat">
                                    <div class="num" style="color:#c92a2a;">{{ number_format($wh->total_out_value) }}</div>
                                    <div class="lbl">OUT Value</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-clock me-1"></i> Recent Transactions (All Warehouses)</h5>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Warehouse</th>
                                <th>Product</th>
                                <th>Type</th>
                                <th>Qty</th>
                                <th>Transfer</th>
                                <th>By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTxns as $txn)
                            <tr>
                                <td style="white-space:nowrap;">{{ $txn->created_at->format('d M, h:i A') }}</td>
                                <td style="font-weight:600;">{{ $txn->warehouse->name ?? '-' }}</td>
                                <td>{{ $txn->product->item_name ?? '-' }}</td>
                                <td><span class="{{ $txn->transaction_type === 'IN' ? 'badge-in' : 'badge-out' }}">{{ $txn->transaction_type }}</span></td>
                                <td>{{ $txn->quantity }}</td>
                                <td>{{ $txn->transfer_type ?? '-' }}</td>
                                <td>{{ $txn->performer->name ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center text-muted py-3">No transactions yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Warehouse Comparison --}}
        <div class="row g-3">
            <div class="col-lg-7">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-chart-bar me-1"></i> Warehouse Activity Comparison <small style="color:var(--text-secondary); font-weight:500;">(last 30 days)</small></h5>
                        @php
                            $sortedCmp = $comparison->sortByDesc('txn_count')->values();
                        @endphp
                        @if($sortedCmp->count() === 0)
                            <p class="text-muted mb-0">No warehouses to compare.</p>
                        @else
                            @foreach($sortedCmp as $i => $c)
                                @php
                                    $pct = $maxTxnCount > 0 ? ($c->txn_count / $maxTxnCount) * 100 : 0;
                                    $isTop = ($i === 0 && $c->txn_count > 0);
                                    $isIdle = $c->txn_count === 0;
                                    $status = $isIdle ? 'idle' : ($c->txn_count >= ($maxTxnCount * 0.5) ? 'active' : 'moderate');
                                    $statusLabel = $isIdle ? 'Idle' : ($status === 'active' ? 'Active' : 'Moderate');
                                @endphp
                                <div class="cmp-row">
                                    <div class="cmp-rank {{ $isTop ? 'top' : ($isIdle ? 'idle' : '') }}">{{ $i + 1 }}</div>
                                    <div class="cmp-name">
                                        {{ $c->name }}
                                        <span class="sub">
                                            @if($c->last_activity)
                                                Last activity: {{ \Carbon\Carbon::parse($c->last_activity)->diffForHumans() }}
                                            @else
                                                No activity in 30 days
                                            @endif
                                        </span>
                                    </div>
                                    <div class="cmp-bar-wrap"><div class="cmp-bar {{ $isIdle ? 'idle' : '' }}" style="width: {{ max(3, $pct) }}%;"></div></div>
                                    <div class="cmp-metric">{{ $c->txn_count }} txns</div>
                                    <span class="cmp-status {{ $status }}">{{ $statusLabel }}</span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            {{-- Stock Distribution --}}
            <div class="col-lg-5">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-chart-pie me-1"></i> Stock Distribution</h5>
                        @php
                            $palette = ['#4A90E2','#e67700','#2b8a3e','#c92a2a','#9c36b5','#0ca678','#e8590c','#5f3dc4','#1971c2','#087f5b'];
                            $totalDist = max(1, (int) $grandTotalStock);
                            $cx = 100; $cy = 100; $r = 70; $inner = 42;
                            $cumAngle = -90;
                        @endphp
                        @if($grandTotalStock === 0)
                            <p class="text-muted mb-0">No stock in any warehouse yet.</p>
                        @else
                        <div class="dist-wrap">
                            <svg class="dist-donut" viewBox="0 0 200 200">
                                @foreach($comparison as $idx => $c)
                                    @php
                                        if ($c->stock === 0) continue;
                                        $frac = $c->stock / $totalDist;
                                        $angle = $frac * 360;
                                        $startAngle = $cumAngle;
                                        $endAngle = $cumAngle + $angle;
                                        $cumAngle = $endAngle;
                                        $largeArc = $angle > 180 ? 1 : 0;
                                        $x1 = $cx + $r * cos(deg2rad($startAngle));
                                        $y1 = $cy + $r * sin(deg2rad($startAngle));
                                        $x2 = $cx + $r * cos(deg2rad($endAngle));
                                        $y2 = $cy + $r * sin(deg2rad($endAngle));
                                        $color = $palette[$idx % count($palette)];
                                    @endphp
                                    @if($angle >= 359.9)
                                        <circle cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $r }}" fill="{{ $color }}"/>
                                    @else
                                        <path d="M {{ $cx }} {{ $cy }} L {{ number_format($x1, 3, '.', '') }} {{ number_format($y1, 3, '.', '') }} A {{ $r }} {{ $r }} 0 {{ $largeArc }} 1 {{ number_format($x2, 3, '.', '') }} {{ number_format($y2, 3, '.', '') }} Z" fill="{{ $color }}"/>
                                    @endif
                                @endforeach
                                <circle cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $inner }}" fill="#fff"/>
                                <text x="{{ $cx }}" y="{{ $cy - 4 }}" text-anchor="middle" class="center-num">{{ $grandTotalStock }}</text>
                                <text x="{{ $cx }}" y="{{ $cy + 14 }}" text-anchor="middle" class="center-lbl">Total Units</text>
                            </svg>
                            <div class="dist-legend">
                                @foreach($comparison as $idx => $c)
                                    @php
                                        $color = $palette[$idx % count($palette)];
                                        $pct = ($c->stock / $totalDist) * 100;
                                    @endphp
                                    <div class="dist-item">
                                        <span class="dist-swatch" style="background: {{ $color }};"></span>
                                        <span class="dist-name">{{ $c->name }}</span>
                                        <span class="dist-qty">{{ $c->stock }} units</span>
                                        <span class="dist-pct">{{ number_format($pct, 1) }}%</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Total P&L --}}
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-balance-scale me-1"></i> Profit &amp; Loss Across All Warehouses</h5>
                @php
                    $pnlClass = $grandPnl > 0 ? 'profit' : ($grandPnl < 0 ? 'loss' : '');
                    $pnlColor = $grandPnl > 0 ? '#2b8a3e' : ($grandPnl < 0 ? '#c92a2a' : '#868e96');
                    $pnlSign = $grandPnl > 0 ? '+' : ($grandPnl < 0 ? '' : '');
                @endphp
                <div class="pnl-strip">
                    <div class="pnl-tile"><div class="l">Total IN (Purchase)</div><div class="v" style="color:#2b8a3e;">Rs {{ number_format($grandTotalIn, 2) }}</div></div>
                    <div class="pnl-tile"><div class="l">Total OUT (Sold/Issued)</div><div class="v" style="color:#c92a2a;">Rs {{ number_format($grandTotalOut, 2) }}</div></div>
                    <div class="pnl-tile {{ $pnlClass }}"><div class="l">Net P&amp;L</div><div class="v" style="color:{{ $pnlColor }};">{{ $pnlSign }}Rs {{ number_format(abs($grandPnl), 2) }}</div></div>
                </div>

                <div style="margin-top:8px;">
                    <div class="pnl-row" style="border-bottom:2px solid var(--border-color); font-size:0.72rem; text-transform:uppercase; letter-spacing:0.05em; color:var(--text-secondary); font-weight:700;">
                        <div>Warehouse</div>
                        <div>IN Value</div>
                        <div>OUT Value</div>
                        <div style="text-align:right;">P&amp;L</div>
                    </div>
                    @foreach($comparison as $c)
                        @php
                            $cls = $c->pnl > 0 ? 'pos' : ($c->pnl < 0 ? 'neg' : 'zero');
                            $sign = $c->pnl > 0 ? '+' : '';
                        @endphp
                        <div class="pnl-row">
                            <div class="n">{{ $c->name }}</div>
                            <div class="in">Rs {{ number_format($c->in_value, 2) }}</div>
                            <div class="out">Rs {{ number_format($c->out_value, 2) }}</div>
                            <div style="text-align:right;"><span class="pl {{ $cls }}">{{ $sign }}Rs {{ number_format($c->pnl, 2) }}</span></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
