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
        .wa-btn { display: inline-flex; align-items: center; gap: 6px; padding: 0.5rem 1rem; border-radius: 6px; background: #25d366; color: #fff; font-size: 0.85rem; font-weight: 600; text-decoration: none; border: none; cursor:pointer; }
        .wa-btn:hover { background: #1ebe5a; color: #fff; }
        .save-img-btn { display: inline-flex; align-items: center; gap: 6px; padding: 0.5rem 1rem; border-radius: 6px; background: #6366f1; color: #fff; font-size: 0.85rem; font-weight: 600; text-decoration: none; border: none; cursor:pointer; }
        .save-img-btn:hover { background: #4f46e5; color: #fff; }

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
                <button type="button" onclick="sendWhatsApp()" class="wa-btn">
                    <i class="fab fa-whatsapp"></i> WhatsApp
                </button>
                <button type="button" onclick="saveQuoteImage()" class="save-img-btn">
                    <i class="fas fa-download me-1"></i> Save Image
                </button>
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
                            <span class="info-value" style="font-weight:600; white-space:pre-line;">{{ $rfq->item_description }}</span>
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

        function getMatchData() {
            var items = [];
            document.querySelectorAll('.match-card').forEach(function(card) {
                var idx = card.dataset.idx;
                var userItem = card.querySelector('input[name="matches['+idx+'][user_item]"]').value;
                var userQty = card.querySelector('input[name="matches['+idx+'][user_qty]"]').value || '0';
                var prodSelect = card.querySelector('.prod-select');
                var prodName = prodSelect.selectedIndex > 0 ? prodSelect.options[prodSelect.selectedIndex].text : '';
                var matchedQty = card.querySelector('.qty-input').value || '0';
                var unitPrice = card.querySelector('.unit-price-hidden').value || '0';
                items.push({
                    userItem: userItem,
                    userQty: parseInt(userQty),
                    prodName: prodName,
                    matchedQty: parseInt(matchedQty),
                    unitPrice: parseFloat(unitPrice)
                });
            });
            return items;
        }

        function sendWhatsApp() {
            var phone = '91{{ preg_replace("/[^0-9]/", "", $rfq->phone) }}';
            var name = @json($rfq->name);
            var items = getMatchData();
            var quotedPrice = document.getElementById('quotedPrice').value || '';
            var discount = document.getElementById('discountPercent').value || '';
            var finalPrice = document.getElementById('finalPrice').value || '';

            var msg = 'Hi ' + name + ',\n\nThank you for your enquiry with *Stable Energy*.\n\n';
            msg += '*Your Requested Items:*\n';
            items.forEach(function(it, i) {
                msg += (i+1) + '. ' + it.userItem + ' - Qty: ' + it.userQty + '\n';
            });

            var hasMatch = items.some(function(it) { return it.prodName; });
            if (hasMatch) {
                msg += '\n*Our Offer:*\n';
                items.forEach(function(it, i) {
                    if (!it.prodName) return;
                    var lineTotal = it.matchedQty * it.unitPrice;
                    msg += (i+1) + '. ' + it.userItem + ' -> ' + it.prodName + '\n';
                    msg += '   Qty: ' + it.matchedQty + ' | Rs.' + it.unitPrice.toLocaleString('en-IN') + ' each | Total: Rs.' + lineTotal.toLocaleString('en-IN') + '\n';
                });
            }

            if (quotedPrice) {
                msg += '\n*Price Summary:*\n';
                msg += 'Subtotal: Rs.' + parseFloat(quotedPrice).toLocaleString('en-IN') + '\n';
                if (discount) msg += 'Discount: ' + discount + '%\n';
                if (finalPrice) msg += '*Final Price: Rs.' + parseFloat(finalPrice).toLocaleString('en-IN') + '*\n';
            }

            var remarks = document.querySelector('textarea[name="admin_remarks"]').value;
            if (remarks) msg += '\nNote: ' + remarks + '\n';

            msg += '\nThank you for choosing Stable Energy!\nhttps://stablesolardekho.com\ninfo@stablesolardekho.com';
            window.open('https://wa.me/' + phone + '?text=' + encodeURIComponent(msg), '_blank');
        }

        function saveQuoteImage() {
            var items = getMatchData();
            var name = @json($rfq->name);
            var phone = @json($rfq->phone);
            var city = @json($rfq->city ?? '');
            var rfqId = @json($rfq->id);
            var rfqDate = @json($rfq->created_at->format('d M Y'));
            var quotedPrice = document.getElementById('quotedPrice').value || '';
            var discount = document.getElementById('discountPercent').value || '';
            var finalPrice = document.getElementById('finalPrice').value || '';
            var remarks = document.querySelector('textarea[name="admin_remarks"]').value || '';

            var hasMatch = items.some(function(it) { return it.prodName; });
            var rowCount = items.length;
            var baseH = 520;
            var rowH = hasMatch ? 56 : 32;
            var canvasH = baseH + (rowCount * rowH) + (quotedPrice ? 90 : 0) + (remarks ? 40 : 0);

            var canvas = document.createElement('canvas');
            canvas.width = 800;
            canvas.height = canvasH;
            var ctx = canvas.getContext('2d');

            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            var headerH = 90;
            var grd = ctx.createLinearGradient(0, 0, canvas.width, 0);
            grd.addColorStop(0, '#1e3a5f');
            grd.addColorStop(1, '#2d5f8a');
            ctx.fillStyle = grd;
            ctx.fillRect(0, 0, canvas.width, headerH);

            var logoImg = new Image();
            logoImg.crossOrigin = 'anonymous';
            logoImg.onload = function() {
                ctx.drawImage(logoImg, 24, 15, 60, 60);
                drawContent();
            };
            logoImg.onerror = function() {
                drawContent();
            };
            logoImg.src = '{{ asset("stable/images/logo1.png") }}';

            function drawContent() {
                ctx.fillStyle = '#ffffff';
                ctx.font = 'bold 26px Arial, sans-serif';
                ctx.fillText('Stable Energy', 96, 42);
                ctx.font = '12px Arial, sans-serif';
                ctx.fillStyle = '#a0c4e8';
                ctx.fillText('Solar Solutions Provider', 96, 62);

                ctx.font = 'bold 14px Arial, sans-serif';
                ctx.fillStyle = '#ffffff';
                ctx.textAlign = 'right';
                ctx.fillText('QUOTATION', canvas.width - 30, 38);
                ctx.font = '12px Arial, sans-serif';
                ctx.fillStyle = '#a0c4e8';
                ctx.fillText('#RFQ-' + rfqId + '  |  ' + rfqDate, canvas.width - 30, 58);
                ctx.textAlign = 'left';

                var y = headerH + 30;
                ctx.fillStyle = '#f8fafc';
                ctx.fillRect(24, y - 6, canvas.width - 48, 60);
                ctx.strokeStyle = '#e2e8f0';
                ctx.lineWidth = 1;
                ctx.strokeRect(24, y - 6, canvas.width - 48, 60);

                ctx.font = '12px Arial, sans-serif';
                ctx.fillStyle = '#64748b';
                ctx.fillText('Customer:', 40, y + 14);
                ctx.fillText('Phone:', 40, y + 36);
                ctx.font = 'bold 13px Arial, sans-serif';
                ctx.fillStyle = '#1e293b';
                ctx.fillText(name, 110, y + 14);
                ctx.fillText(phone, 110, y + 36);
                if (city) {
                    ctx.font = '12px Arial, sans-serif';
                    ctx.fillStyle = '#64748b';
                    ctx.fillText('City:', 400, y + 14);
                    ctx.font = 'bold 13px Arial, sans-serif';
                    ctx.fillStyle = '#1e293b';
                    ctx.fillText(city, 440, y + 14);
                }

                y += 80;
                ctx.font = 'bold 15px Arial, sans-serif';
                ctx.fillStyle = '#1e3a5f';
                ctx.fillText('Requested Items', 30, y);

                if (hasMatch) {
                    ctx.fillText('Our Offer', 420, y);
                }

                y += 20;
                ctx.fillStyle = '#1e3a5f';
                ctx.fillRect(24, y, canvas.width - 48, 30);
                ctx.font = 'bold 11px Arial, sans-serif';
                ctx.fillStyle = '#ffffff';
                ctx.fillText('#', 36, y + 19);
                ctx.fillText('Item Requested', 56, y + 19);
                ctx.fillText('Qty', 260, y + 19);
                if (hasMatch) {
                    ctx.fillText('Matched Product', 320, y + 19);
                    ctx.fillText('Our Qty', 560, y + 19);
                    ctx.fillText('Price', 630, y + 19);
                    ctx.fillText('Total', 710, y + 19);
                }
                y += 30;

                var grandTotal = 0;
                items.forEach(function(it, i) {
                    var bg = i % 2 === 0 ? '#ffffff' : '#f8fafc';
                    ctx.fillStyle = bg;
                    ctx.fillRect(24, y, canvas.width - 48, rowH);

                    ctx.strokeStyle = '#e2e8f0';
                    ctx.lineWidth = 0.5;
                    ctx.beginPath();
                    ctx.moveTo(24, y + rowH);
                    ctx.lineTo(canvas.width - 24, y + rowH);
                    ctx.stroke();

                    var textY = y + (rowH / 2) + 4;
                    ctx.font = 'bold 12px Arial, sans-serif';
                    ctx.fillStyle = '#475569';
                    ctx.fillText((i + 1) + '.', 36, textY);

                    ctx.font = '12px Arial, sans-serif';
                    ctx.fillStyle = '#1e293b';
                    ctx.fillText(truncText(ctx, it.userItem, 190), 56, textY);
                    ctx.fillStyle = '#e67700';
                    ctx.font = 'bold 12px Arial, sans-serif';
                    ctx.fillText(it.userQty.toString(), 268, textY);

                    if (hasMatch && it.prodName) {
                        ctx.font = '12px Arial, sans-serif';
                        ctx.fillStyle = '#2b8a3e';
                        ctx.fillText(truncText(ctx, it.prodName, 220), 320, textY);
                        ctx.fillStyle = '#1e293b';
                        ctx.fillText(it.matchedQty.toString(), 568, textY);
                        ctx.fillText('Rs.' + it.unitPrice.toLocaleString('en-IN'), 630, textY);
                        var lt = it.matchedQty * it.unitPrice;
                        grandTotal += lt;
                        ctx.font = 'bold 12px Arial, sans-serif';
                        ctx.fillText('Rs.' + lt.toLocaleString('en-IN'), 710, textY);
                    }
                    y += rowH;
                });

                y += 16;

                if (quotedPrice) {
                    ctx.fillStyle = '#f0fdf4';
                    ctx.fillRect(420, y, canvas.width - 444, 80);
                    ctx.strokeStyle = '#bbf7d0';
                    ctx.lineWidth = 1;
                    ctx.strokeRect(420, y, canvas.width - 444, 80);

                    ctx.font = '12px Arial, sans-serif';
                    ctx.fillStyle = '#64748b';
                    ctx.textAlign = 'left';
                    ctx.fillText('Subtotal:', 435, y + 22);
                    ctx.textAlign = 'right';
                    ctx.fillStyle = '#1e293b';
                    ctx.fillText('Rs.' + parseFloat(quotedPrice).toLocaleString('en-IN'), canvas.width - 36, y + 22);

                    if (discount) {
                        ctx.textAlign = 'left';
                        ctx.fillStyle = '#64748b';
                        ctx.fillText('Discount:', 435, y + 42);
                        ctx.textAlign = 'right';
                        ctx.fillStyle = '#dc2626';
                        ctx.fillText(discount + '%', canvas.width - 36, y + 42);
                    }

                    if (finalPrice) {
                        ctx.beginPath();
                        ctx.moveTo(435, y + 52);
                        ctx.lineTo(canvas.width - 36, y + 52);
                        ctx.strokeStyle = '#86efac';
                        ctx.stroke();

                        ctx.textAlign = 'left';
                        ctx.font = 'bold 14px Arial, sans-serif';
                        ctx.fillStyle = '#1e3a5f';
                        ctx.fillText('Final Price:', 435, y + 72);
                        ctx.textAlign = 'right';
                        ctx.fillStyle = '#2b8a3e';
                        ctx.font = 'bold 16px Arial, sans-serif';
                        ctx.fillText('Rs.' + parseFloat(finalPrice).toLocaleString('en-IN'), canvas.width - 36, y + 72);
                    }
                    ctx.textAlign = 'left';
                    y += 90;
                }

                if (remarks) {
                    y += 10;
                    ctx.font = '11px Arial, sans-serif';
                    ctx.fillStyle = '#64748b';
                    ctx.fillText('Note: ' + remarks, 30, y);
                    y += 30;
                }

                y += 10;
                var footGrd = ctx.createLinearGradient(0, y, canvas.width, y);
                footGrd.addColorStop(0, '#1e3a5f');
                footGrd.addColorStop(1, '#2d5f8a');
                ctx.fillStyle = footGrd;
                ctx.fillRect(0, y, canvas.width, 40);
                ctx.font = '11px Arial, sans-serif';
                ctx.fillStyle = '#a0c4e8';
                ctx.textAlign = 'center';
                ctx.fillText('Stable Energy | WhatsApp: +91 70149 20144 | Thank you for your enquiry!', canvas.width / 2, y + 25);
                ctx.textAlign = 'left';

                var actualH = y + 40;
                var finalCanvas = document.createElement('canvas');
                finalCanvas.width = 800;
                finalCanvas.height = actualH;
                var fctx = finalCanvas.getContext('2d');
                fctx.drawImage(canvas, 0, 0, 800, actualH, 0, 0, 800, actualH);

                var link = document.createElement('a');
                link.download = 'Quote_RFQ' + rfqId + '_' + name.replace(/\s+/g, '_') + '.png';
                link.href = finalCanvas.toDataURL('image/png');
                link.click();
            }
        }

        function truncText(ctx, text, maxW) {
            if (ctx.measureText(text).width <= maxW) return text;
            while (text.length > 0 && ctx.measureText(text + '...').width > maxW) {
                text = text.slice(0, -1);
            }
            return text + '...';
        }
    </script>
@endsection
