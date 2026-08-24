@extends('layouts.adminLayout')

@section('css')
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/select2.min.css" rel="stylesheet">
    <style>
        :root { --primary-blue: #4A90E2; --primary-light: #f5f7fa; --text-primary: #2d3436; --text-secondary: #636e72; --border-color: #e1e8ed; --hover-bg: #f1f3f5; --card-bg: #ffffff; }
        body { background: var(--primary-light); color: var(--text-primary); }
        .page-header { background: #ffffff; padding: 1.5rem 0; margin-bottom: 1.5rem; border-radius: 8px; border: 1px solid var(--border-color); box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
        .page-header h1 { color: var(--text-primary); font-weight: 600; margin: 0; font-size: 1.25rem; }
        .page-header p { color: var(--text-secondary); margin: 0.35rem 0 0 0; font-size: 0.9rem; }
        .card { border: 1px solid var(--border-color); border-radius: 8px; background: var(--card-bg); box-shadow: 0 1px 3px rgba(0,0,0,0.04); margin-bottom: 1rem; }
        .card-body { padding: 1.5rem; }
        .card-title { font-weight: 600; font-size: 0.95rem; margin-bottom: 1rem; color: var(--text-primary); border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem; }
        .info-row { display: flex; padding: 0.5rem 0; border-bottom: 1px solid #f1f3f5; }
        .info-row:last-child { border-bottom: none; }
        .info-label { width: 140px; flex-shrink: 0; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; }
        .info-value { flex: 1; font-size: 0.88rem; color: var(--text-primary); }
        .btn-primary { background: var(--primary-blue); border: 1px solid var(--primary-blue); color: #fff; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; font-size: 0.85rem; }
        .badge-status { padding: 0.3rem 0.7rem; border-radius: 12px; font-weight: 600; font-size: 0.78rem; text-transform: capitalize; }
        .badge-pending { background: #fff8e1; color: #f59e0b; }
        .badge-processing { background: #e8f4fd; color: #3b82f6; }
        .badge-quoted { background: #f3e8ff; color: #8b5cf6; }
        .badge-accepted { background: #d3f9d8; color: #2b8a3e; }
        .badge-rejected { background: #fff5f5; color: #c92a2a; }
        .badge-closed { background: #f1f3f5; color: #636e72; }
        .form-label { font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; }
        .form-control, .form-select { font-size: 0.88rem; border-color: var(--border-color); }
        .form-control:focus, .form-select:focus { border-color: var(--primary-blue); box-shadow: 0 0 0 2px rgba(74,144,226,0.15); }
        .wa-btn { display: inline-flex; align-items: center; gap: 6px; padding: 0.5rem 1rem; border-radius: 6px; background: #25d366; color: #fff; font-size: 0.85rem; font-weight: 600; text-decoration: none; border: none; }
        .wa-btn:hover { background: #1ebe5a; color: #fff; }

        .match-card { border: 1px solid var(--border-color); border-radius: 10px; padding: 14px 16px; margin-bottom: 14px; background: #fbfcfd; }
        .match-card.matched { background: #f0fdf4; border-color: #bbf7d0; }
        .match-line { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px dashed #d1d5db; }
        .match-line .num { width: 26px; height: 26px; border-radius: 50%; background: var(--primary-blue); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 0.78rem; font-weight: 700; flex-shrink: 0; }
        .match-line .req { flex: 1; }
        .match-line .req .name { font-weight: 700; color: var(--text-primary); font-size: 0.9rem; }
        .match-line .req .qty { font-size: 0.75rem; color: var(--text-secondary); margin-top: 1px; }
        .match-line .req .qty strong { color: #e67700; }
        .match-fields { display: grid; grid-template-columns: 1fr 1fr 1fr 100px; gap: 8px; }
        .match-fields .stock-hint { font-size: 0.72rem; color: var(--text-secondary); margin-top: 3px; }
        .match-fields .stock-hint.ok { color: #2b8a3e; font-weight: 600; }
        .match-fields .stock-hint.low { color: #b8860b; font-weight: 600; }
        .match-fields .stock-hint.zero { color: #c92a2a; font-weight: 600; }
        .match-fields label { font-size: 0.68rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.03em; margin-bottom: 3px; display: block; }
        .match-fields select, .match-fields input { font-size: 0.82rem; padding: 6px 8px; border-radius: 6px; border: 1px solid var(--border-color); width: 100%; }
        .match-fields select:focus, .match-fields input:focus { border-color: var(--primary-blue); outline: none; }
        @media (max-width: 900px) { .match-fields { grid-template-columns: 1fr 1fr; } }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div>
                <h1><i class="fas fa-file-invoice me-2"></i>RFQ #{{ $rfq->id }} - {{ $rfq->name }}</h1>
                <p>Submitted {{ $rfq->created_at->format('d M Y, h:i A') }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="https://wa.me/91{{ preg_replace('/[^0-9]/', '', $rfq->phone) }}?text={{ urlencode('Hi ' . $rfq->name . ', regarding your request for ' . $rfq->item_description . ' - ') }}"
                   target="_blank" class="wa-btn">
                    <i class="fab fa-whatsapp"></i> WhatsApp
                </a>
                <a href="{{ route('admin.rfq.index') }}" style="padding:0.5rem 1rem;border-radius:6px;font-weight:600;font-size:0.85rem;border:1px solid var(--border-color);background:#fff;color:var(--text-primary);text-decoration:none;">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size:0.88rem;">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-user me-1"></i> Customer Details</h5>
                        <div class="info-row">
                            <span class="info-label">Name</span>
                            <span class="info-value" style="font-weight:600;">{{ $rfq->name }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Phone</span>
                            <span class="info-value">{{ $rfq->phone }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Email</span>
                            <span class="info-value">{{ $rfq->email ?? '-' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">City</span>
                            <span class="info-value">{{ $rfq->city ?? '-' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Registered</span>
                            <span class="info-value">{{ $rfq->user ? 'Yes (' . $rfq->user->email . ')' : 'Guest' }}</span>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-box me-1"></i> Request Details</h5>
                        <div class="info-row">
                            <span class="info-label">Status</span>
                            <span class="info-value"><span class="badge-status badge-{{ $rfq->status }}">{{ $rfq->status }}</span></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Item</span>
                            <span class="info-value" style="font-weight:600;">{{ $rfq->item_description }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Quantity</span>
                            <span class="info-value">{{ $rfq->quantity }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Brand</span>
                            <span class="info-value">{{ $rfq->preferred_brand ?? '-' }}</span>
                        </div>
                        @if($rfq->additional_notes)
                        <div class="info-row">
                            <span class="info-label">Notes</span>
                            <span class="info-value">{{ $rfq->additional_notes }}</span>
                        </div>
                        @endif
                        @if($rfq->quoted_at)
                        <div class="info-row">
                            <span class="info-label">Quoted At</span>
                            <span class="info-value">{{ $rfq->quoted_at->format('d M Y, h:i A') }}</span>
                        </div>
                        @endif
                        @if($rfq->processor)
                        <div class="info-row">
                            <span class="info-label">Processed By</span>
                            <span class="info-value">{{ $rfq->processor->name }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-cog me-1"></i> Process This Request</h5>

                        @if(session('error'))
                            <div class="alert alert-danger" style="font-size:.85rem;">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('admin.rfq.process', $rfq->id) }}" method="POST" id="rfqProcessForm">
                            @csrf

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select" required>
                                        <option value="processing" {{ $rfq->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="quoted" {{ $rfq->status == 'quoted' ? 'selected' : '' }}>Quoted</option>
                                        <option value="accepted" {{ $rfq->status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                                        <option value="rejected" {{ $rfq->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        <option value="closed" {{ $rfq->status == 'closed' ? 'selected' : '' }}>Closed</option>
                                    </select>
                                </div>
                            </div>

                            <div style="margin-top:6px;">
                                <label class="form-label" style="display:block; margin-bottom:10px;"><i class="fas fa-link me-1"></i> Match Each Requested Item to a Product</label>

                                @php $savedMatches = is_array($rfq->matches) ? $rfq->matches : []; @endphp

                                @foreach($userItems as $idx => $item)
                                    @php
                                        $saved = $savedMatches[$idx] ?? null;
                                        $isMatched = $saved && !empty($saved['product_id']);
                                    @endphp
                                    <div class="match-card {{ $isMatched ? 'matched' : '' }}" data-idx="{{ $idx }}">
                                        <div class="match-line">
                                            <div class="num">{{ $idx + 1 }}</div>
                                            <div class="req">
                                                <div class="name">{{ $item['name'] }}</div>
                                                @if($item['qty'])
                                                    <div class="qty">User asked for: <strong>{{ $item['qty'] }} units</strong></div>
                                                @endif
                                            </div>
                                        </div>

                                        <input type="hidden" name="matches[{{ $idx }}][user_item]" value="{{ $item['name'] }}">
                                        <input type="hidden" name="matches[{{ $idx }}][user_qty]" value="{{ $item['qty'] ?? '' }}">

                                        <div class="match-fields">
                                            <div>
                                                <label>Category</label>
                                                <select class="cat-select" data-idx="{{ $idx }}">
                                                    <option value="">Select</option>
                                                    @foreach($categories as $cat)
                                                        <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label>Sub Category</label>
                                                <select class="sub-select" data-idx="{{ $idx }}">
                                                    <option value="">Select</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label>Product</label>
                                                <select name="matches[{{ $idx }}][product_id]" class="prod-select" data-idx="{{ $idx }}">
                                                    <option value="">Select</option>
                                                    @if($isMatched)
                                                        @php $sp = \App\Models\Product::find($saved['product_id']); @endphp
                                                        @if($sp)
                                                            <option value="{{ $sp->id }}" data-price="{{ $sp->current_sale_price }}" selected>{{ $sp->item_name }}</option>
                                                        @endif
                                                    @endif
                                                </select>
                                                <div class="stock-hint" id="stockHint{{ $idx }}"></div>
                                            </div>
                                            <div>
                                                <label>Our Qty</label>
                                                <input type="number" name="matches[{{ $idx }}][matched_qty]" min="0" value="{{ $saved['matched_qty'] ?? '' }}" class="qty-input" data-idx="{{ $idx }}" placeholder="0">
                                                <input type="hidden" name="matches[{{ $idx }}][unit_price]" value="{{ $saved['unit_price'] ?? '' }}" class="unit-price-hidden" data-idx="{{ $idx }}">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="row g-3 mt-2">
                                <div class="col-md-4">
                                    <label class="form-label">Quoted Price (Rs)</label>
                                    <input type="number" name="quoted_price" class="form-control" step="0.01" min="0" value="{{ $rfq->quoted_price }}" id="quotedPrice">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Discount %</label>
                                    <input type="number" name="discount_percent" class="form-control" step="0.01" min="0" max="100" value="{{ $rfq->discount_percent }}" id="discountPercent">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Final Price (Rs)</label>
                                    <input type="number" name="final_price" class="form-control" step="0.01" min="0" value="{{ $rfq->final_price }}" id="finalPrice" style="font-weight:700;color:#2b8a3e;">
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Admin Remarks</label>
                                    <textarea name="admin_remarks" class="form-control" rows="3" placeholder="Internal notes or message to customer...">{{ $rfq->admin_remarks }}</textarea>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Update RFQ
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/assets/js/jquery-3.6.0.min.js"></script>
    <script>
        $(function () {
            var subCatUrl = "{{ route('getSubCategory') }}";
            var productsUrl = "{{ route('getProducts') }}";
            var stockUrl = "{{ route('admin.rfq.getProductTotalStock') }}";

            $(document).on('change', '.cat-select', function () {
                var idx = $(this).data('idx');
                var catId = $(this).val();
                var $sub = $('.sub-select[data-idx="' + idx + '"]');
                var $prod = $('.prod-select[data-idx="' + idx + '"]');
                $sub.html('<option value="">Select</option>');
                $prod.html('<option value="">Select</option>');
                clearStock(idx);
                if (!catId) return;

                $.get(subCatUrl, { category_id: catId }, function (data) {
                    var opts = '<option value="">Select</option>';
                    data.forEach(function (sc) {
                        opts += '<option value="' + sc.id + '">' + escapeHtml(sc.sub_category_name) + '</option>';
                    });
                    $sub.html(opts);
                }).fail(function () {
                    $sub.html('<option value="">Failed to load</option>');
                });
            });

            $(document).on('change', '.sub-select', function () {
                var idx = $(this).data('idx');
                var subId = $(this).val();
                var $prod = $('.prod-select[data-idx="' + idx + '"]');
                $prod.html('<option value="">Select</option>');
                clearStock(idx);
                if (!subId) return;

                $.get(productsUrl, { sub_category_id: subId }, function (data) {
                    var opts = '<option value="">Select</option>';
                    data.forEach(function (p) {
                        opts += '<option value="' + p.id + '" data-price="' + (p.current_sale_price || 0) + '">' + escapeHtml(p.item_name) + (p.item_code ? ' (' + escapeHtml(p.item_code) + ')' : '') + '</option>';
                    });
                    $prod.html(opts);
                }).fail(function () {
                    $prod.html('<option value="">Failed to load</option>');
                });
            });

            $(document).on('change', '.prod-select', function () {
                var idx = $(this).data('idx');
                var pid = $(this).val();
                var $hint = $('#stockHint' + idx);
                var $qty = $('.qty-input[data-idx="' + idx + '"]');
                var $unitPrice = $('.unit-price-hidden[data-idx="' + idx + '"]');
                var $card = $('.match-card[data-idx="' + idx + '"]');

                if (!pid) { clearStock(idx); $card.removeClass('matched'); return; }
                $hint.text('Checking stock...').removeClass('ok low zero');

                $.get(stockUrl, { product_id: pid }, function (data) {
                    var total = data.total_stock || 0;
                    var main = data.main_stock || 0;
                    var wh = data.warehouse_stock || 0;
                    var price = data.unit_price || 0;
                    $qty.attr('max', total);
                    $unitPrice.val(price);
                    var cls = total === 0 ? 'zero' : (total <= 5 ? 'low' : 'ok');
                    $hint.attr('class', 'stock-hint ' + cls).text('Available: ' + total + ' (Main: ' + main + ', Warehouses: ' + wh + ')');
                    $card.addClass('matched');
                    if (total > 0 && !$qty.val()) {
                        var userQty = parseInt($('input[name="matches[' + idx + '][user_qty]"]').val()) || 0;
                        $qty.val(Math.min(userQty > 0 ? userQty : total, total));
                    }
                    if (parseInt($qty.val()) > total) $qty.val(total);
                }).fail(function () {
                    $hint.attr('class', 'stock-hint zero').text('Could not load stock');
                });
            });

            $(document).on('input', '.qty-input', function () {
                var max = parseInt($(this).attr('max')) || 0;
                var val = parseInt($(this).val()) || 0;
                if (max > 0 && val > max) {
                    $(this).val(max);
                    $(this).css('border-color', '#c92a2a');
                    setTimeout(function () { $(this).css('border-color', ''); }.bind(this), 800);
                }
            });

            function clearStock(idx) {
                $('#stockHint' + idx).text('').attr('class', 'stock-hint');
                $('.qty-input[data-idx="' + idx + '"]').removeAttr('max');
                $('.unit-price-hidden[data-idx="' + idx + '"]').val('');
            }

            @foreach($userItems as $idx => $item)
                @php $saved = $savedMatches[$idx] ?? null; @endphp
                @if($saved && !empty($saved['product_id']))
                    $('.prod-select[data-idx="{{ $idx }}"]').trigger('change');
                @endif
            @endforeach

            function calcFinal() {
                var price = parseFloat($('#quotedPrice').val()) || 0;
                var disc = parseFloat($('#discountPercent').val()) || 0;
                var final_p = price - (price * disc / 100);
                $('#finalPrice').val(final_p.toFixed(2));
            }
            $('#quotedPrice, #discountPercent').on('input', calcFinal);

            function escapeHtml(s) {
                return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
                    return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
                });
            }
        });
    </script>
@endsection
