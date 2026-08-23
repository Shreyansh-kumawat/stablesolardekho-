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

                        <form action="{{ route('admin.rfq.process', $rfq->id) }}" method="POST">
                            @csrf

                            <div class="row g-3">
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

                                <div class="col-md-6">
                                    <label class="form-label">Match Category</label>
                                    <select id="categorySelect" class="form-select">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Sub Category</label>
                                    <select id="subCategorySelect" class="form-select">
                                        <option value="">Select Sub Category</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Match Product</label>
                                    <select name="product_id" id="productSelect" class="form-select">
                                        <option value="">Select Product</option>
                                        @if($rfq->product)
                                        <option value="{{ $rfq->product_id }}" selected>{{ $rfq->product->item_name }}</option>
                                        @endif
                                    </select>
                                </div>

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
    <script src="/assets/js/select2.min.js"></script>
    <script>
        $(function () {
            $('#categorySelect').on('change', function () {
                var catId = $(this).val();
                $('#subCategorySelect').html('<option value="">Loading...</option>');
                $('#productSelect').html('<option value="">Select Product</option>');
                if (!catId) return;

                $.get('/getSubCategory', { category_id: catId }, function (data) {
                    var opts = '<option value="">Select Sub Category</option>';
                    data.forEach(function (sc) {
                        opts += '<option value="' + sc.id + '">' + sc.sub_category_name + '</option>';
                    });
                    $('#subCategorySelect').html(opts);
                });
            });

            $('#subCategorySelect').on('change', function () {
                var subCatId = $(this).val();
                $('#productSelect').html('<option value="">Loading...</option>');
                if (!subCatId) return;

                $.get('/getProducts', { sub_category_id: subCatId }, function (data) {
                    var opts = '<option value="">Select Product</option>';
                    data.forEach(function (p) {
                        opts += '<option value="' + p.id + '" data-price="' + (p.current_sale_price || 0) + '">' + p.item_name + ' (' + p.item_code + ')</option>';
                    });
                    $('#productSelect').html(opts);
                });
            });

            $('#productSelect').on('change', function () {
                var price = $(this).find(':selected').data('price');
                if (price && price > 0) {
                    $('#quotedPrice').val(price);
                    calcFinal();
                }
            });

            function calcFinal() {
                var price = parseFloat($('#quotedPrice').val()) || 0;
                var disc = parseFloat($('#discountPercent').val()) || 0;
                var final_p = price - (price * disc / 100);
                $('#finalPrice').val(final_p.toFixed(2));
            }

            $('#quotedPrice, #discountPercent').on('input', calcFinal);
        });
    </script>
@endsection
