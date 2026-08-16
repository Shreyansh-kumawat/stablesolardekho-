@extends('layouts.adminLayout')

@section('title', 'Kit Order Payment - ' . $kit->item_name)

@section('css')
<link href="/assets/css/fa-all.min.css" rel="stylesheet">
<style>
    .pay-wrap { max-width: 640px; margin: 2rem auto; padding: 0 1rem; }
    .pay-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,.06); }
    .pay-header { text-align: center; margin-bottom: 1.5rem; }
    .pay-header h1 { font-size: 1.25rem; font-weight: 800; color: #1f2937; margin: 0 0 .3rem; }
    .pay-header p { font-size: .85rem; color: #6b7280; margin: 0; }

    .pay-summary { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem; }
    .pay-summary-row { display: flex; justify-content: space-between; padding: .4rem 0; font-size: .85rem; color: #374151; }
    .pay-summary-row.total { border-top: 2px solid #e5e7eb; margin-top: .5rem; padding-top: .7rem; font-weight: 800; font-size: 1rem; color: #1f2937; }
    .pay-summary-label { color: #6b7280; font-weight: 600; }

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
    .alert-danger { background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: .75rem 1rem; border-radius: 8px; margin-bottom: 1rem; font-size: .85rem; }
</style>
@endsection

@section('content')
<div class="pay-wrap">
    <a href="{{ url()->previous() }}" class="pay-back"><i class="fas fa-arrow-left"></i> Back</a>

    <div class="pay-card">
        <div class="pay-header">
            <h1>Complete Kit Order</h1>
            <p>Upload payment receipt to place your order</p>
        </div>

        @if(session('error'))
            <div class="alert-danger">{{ session('error') }}</div>
        @endif

        <div class="pay-summary">
            <div class="pay-summary-row">
                <span class="pay-summary-label">Kit</span>
                <span>{{ $kit->item_name }}</span>
            </div>
            <div class="pay-summary-row">
                <span class="pay-summary-label">Price per Kit</span>
                <span>&#8377;{{ number_format($slabPrice) }}</span>
            </div>
            <div class="pay-summary-row">
                <span class="pay-summary-label">Quantity</span>
                <span>{{ $qty }}</span>
            </div>
            <div class="pay-summary-row total">
                <span>Total Amount</span>
                <span>&#8377;{{ number_format($totalAmount) }}</span>
            </div>
        </div>

        <form method="POST" action="{{ route('kit.payment.submit', $kit->id) }}" enctype="multipart/form-data" class="pay-form">
            @csrf
            <input type="hidden" name="quantity" value="{{ $qty }}">

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
            <button type="submit" class="pay-btn"><i class="fas fa-check-circle"></i> Upload Receipt & Place Order</button>
        </form>
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
