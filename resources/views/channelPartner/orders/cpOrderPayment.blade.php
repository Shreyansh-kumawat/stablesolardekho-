@extends('layouts.adminLayout')

@section('title', 'Upload Payment - ' . $order->order_id)

@section('css')
<link href="/assets/css/fa-all.min.css" rel="stylesheet">
<style>
    .pay-wrap { max-width: 640px; margin: 2rem auto; padding: 0 1rem; }

    .pay-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,.06); }

    .pay-header { text-align: center; margin-bottom: 1.5rem; }
    .pay-header h1 { font-size: 1.25rem; font-weight: 800; color: #1f2937; margin: 0 0 .3rem; }
    .pay-header p { font-size: .85rem; color: #6b7280; margin: 0; }

    .pay-order-info { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem; display: grid; grid-template-columns: 1fr 1fr; gap: .5rem; }
    .pay-order-info .info-item label { display: block; font-size: .7rem; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: .4px; }
    .pay-order-info .info-item span { font-size: .85rem; color: #374151; font-weight: 600; }

    .pay-status { text-align: center; padding: 1.5rem; border-radius: 10px; margin-bottom: 1.5rem; }
    .pay-status.verification { background: #fef3c7; border: 1px solid #fbbf24; }
    .pay-status.verification h3 { color: #92400e; font-size: 1rem; font-weight: 700; margin: 0 0 .3rem; }
    .pay-status.verification p { color: #a16207; font-size: .82rem; margin: 0; }
    .pay-status.paid { background: #d1fae5; border: 1px solid #6ee7b7; }
    .pay-status.paid h3 { color: #065f46; font-size: 1rem; font-weight: 700; margin: 0 0 .3rem; }
    .pay-status.paid p { color: #047857; font-size: .82rem; margin: 0; }
    .pay-status.failed { background: #fee2e2; border: 1px solid #fca5a5; }
    .pay-status.failed h3 { color: #991b1b; font-size: 1rem; font-weight: 700; margin: 0 0 .3rem; }
    .pay-status.failed p { color: #b91c1c; font-size: .82rem; margin: 0; }

    .pay-form label { display: block; font-size: .8rem; font-weight: 600; color: #374151; margin-bottom: .3rem; }
    .pay-form .form-group { margin-bottom: 1rem; }
    .pay-form input[type="text"], .pay-form input[type="file"] {
        width: 100%; padding: .6rem .75rem; border: 1.5px solid #e5e7eb; border-radius: 8px; font-size: .85rem; color: #1f2937; box-sizing: border-box;
    }
    .pay-form input:focus { outline: none; border-color: #4A90E2; box-shadow: 0 0 0 3px rgba(74,144,226,.12); }

    .pay-preview { margin-top: .5rem; }
    .pay-preview img { max-width: 100%; max-height: 200px; border-radius: 8px; border: 1px solid #e5e7eb; }

    .pay-btn { width: 100%; padding: .75rem; background: linear-gradient(135deg, #4A90E2, #357abd); color: #fff; border: none; border-radius: 10px; font-size: .9rem; font-weight: 700; cursor: pointer; transition: transform .15s, box-shadow .15s; }
    .pay-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(74,144,226,.3); }

    .pay-back { display: inline-flex; align-items: center; gap: .4rem; font-size: .82rem; color: #6b7280; text-decoration: none; margin-bottom: 1rem; }
    .pay-back:hover { color: #4A90E2; }

    .error-text { color: #ef4444; font-size: .78rem; margin-top: .3rem; }
</style>
@endsection

@section('content')
<div class="pay-wrap">
    <a href="{{ route('orderReportCp') }}" class="pay-back"><i class="fas fa-arrow-left"></i> Back to Orders</a>

    <div class="pay-card">
        <div class="pay-header">
            <h1>Upload Payment Receipt</h1>
            <p>Upload your payment proof for order {{ $order->order_id }}</p>
        </div>

        <div class="pay-order-info">
            <div class="info-item">
                <label>Order ID</label>
                <span>{{ $order->order_id }}</span>
            </div>
            <div class="info-item">
                <label>Order Date</label>
                <span>{{ $order->order_date ? $order->order_date->format('d M Y') : 'N/A' }}</span>
            </div>
            <div class="info-item">
                <label>Status</label>
                <span>{{ ucfirst($order->status) }}</span>
            </div>
            <div class="info-item">
                <label>Payment</label>
                <span>{{ ucfirst(str_replace('_', ' ', $order->payment_status ?? 'pending')) }}</span>
            </div>
        </div>

        @if($order->payment_status === 'verification_pending')
            <div class="pay-status verification">
                <h3><i class="fas fa-clock"></i> Verification in Progress</h3>
                <p>Your payment receipt has been uploaded. Our team will verify it shortly.</p>
            </div>
            @if($order->payment_screenshot)
            <div style="margin-bottom:1rem;">
                <label style="font-size:.8rem;font-weight:600;color:#374151;">Uploaded Receipt:</label>
                <div class="pay-preview">
                    <img src="{{ asset('storage/' . $order->payment_screenshot) }}" alt="Payment Receipt">
                </div>
            </div>
            @endif
        @elseif($order->payment_status === 'paid')
            <div class="pay-status paid">
                <h3><i class="fas fa-check-circle"></i> Payment Approved</h3>
                <p>Your payment has been verified and approved.</p>
            </div>
        @elseif($order->payment_status === 'failed')
            <div class="pay-status failed">
                <h3><i class="fas fa-times-circle"></i> Payment Rejected</h3>
                <p>Your payment was rejected. Please upload a new receipt.</p>
            </div>
        @endif

        @if(!in_array($order->payment_status, ['paid', 'verification_pending']))
        <form method="POST" action="{{ route('cpOrderPaymentUpload', $order->id) }}" enctype="multipart/form-data" class="pay-form">
            @csrf
            <div class="form-group">
                <label for="payment_screenshot">Payment Screenshot *</label>
                <input type="file" id="payment_screenshot" name="payment_screenshot" accept="image/*" required onchange="previewImage(this)">
                @error('payment_screenshot') <div class="error-text">{{ $message }}</div> @enderror
                <div class="pay-preview" id="imagePreview" style="display:none;">
                    <img id="previewImg" src="" alt="Preview">
                </div>
            </div>
            <div class="form-group">
                <label for="payment_reference">Payment Reference / Transaction ID (Optional)</label>
                <input type="text" id="payment_reference" name="payment_reference" placeholder="e.g. UTR number, transaction ID" value="{{ old('payment_reference') }}">
            </div>
            <button type="submit" class="pay-btn"><i class="fas fa-upload"></i> Upload Receipt</button>
        </form>
        @endif
    </div>
</div>
@endsection

@section('js')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
