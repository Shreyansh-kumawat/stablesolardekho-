@extends('layouts.public')

@section('css')
<style>
.pay-wrap{max-width:680px;margin:0 auto;padding:40px 16px 64px;}
.pay-header{text-align:center;margin-bottom:2rem;}
.pay-header h1{color:#fff;font-size:1.5rem;font-weight:900;margin:0 0 6px;}
.pay-header p{color:#94a3b8;font-size:0.88rem;margin:0;}
.pay-amount{color:#f97316;font-weight:900;font-size:2rem;margin:12px 0 0;}
.pay-card{background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.10);border-radius:16px;padding:1.5rem;margin-bottom:1.2rem;}
.pay-card h3{color:#e2e8f0;font-weight:700;font-size:0.95rem;margin:0 0 1rem;display:flex;align-items:center;gap:8px;}
.pay-row{display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid rgba(255,255,255,0.06);}
.pay-row:last-child{border-bottom:none;}
.pay-label{color:#94a3b8;font-size:0.82rem;}
.pay-val{color:#e2e8f0;font-weight:600;font-size:0.85rem;display:flex;align-items:center;gap:6px;}
.pay-copy{background:rgba(249,115,22,0.12);border:1px solid rgba(249,115,22,0.3);color:#f97316;border-radius:6px;padding:2px 8px;font-size:0.7rem;cursor:pointer;font-weight:700;}
.pay-copy:hover{background:rgba(249,115,22,0.25);}
.pay-divider{display:flex;align-items:center;gap:12px;margin:1.5rem 0;}
.pay-divider span{color:#475569;font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;}
.pay-divider::before,.pay-divider::after{content:'';flex:1;height:1px;background:rgba(255,255,255,0.08);}
.pay-note{background:rgba(249,115,22,0.06);border:1px solid rgba(249,115,22,0.15);border-radius:10px;padding:12px 16px;margin-bottom:1.2rem;}
.pay-note p{color:#f97316;font-size:0.8rem;margin:0;line-height:1.5;}
.pay-upload-area{border:2px dashed rgba(255,255,255,0.15);border-radius:12px;padding:2rem;text-align:center;cursor:pointer;transition:border-color 0.2s;}
.pay-upload-area:hover{border-color:rgba(249,115,22,0.4);}
.pay-upload-area.has-file{border-color:#22c55e;background:rgba(34,197,94,0.05);}
.pay-preview{max-width:200px;max-height:200px;border-radius:8px;margin:10px auto 0;display:none;}
.pay-btn{width:100%;background:linear-gradient(135deg,#f97316,#ea580c);color:#fff;font-weight:800;padding:14px;border-radius:14px;border:none;cursor:pointer;font-size:1rem;display:flex;align-items:center;justify-content:center;gap:8px;transition:opacity 0.2s;}
.pay-btn:disabled{opacity:0.5;cursor:not-allowed;}
@media(max-width:480px){
.pay-wrap{padding:28px 14px 48px;}
.pay-header h1{font-size:1.25rem;}
.pay-amount{font-size:1.6rem;}
.pay-row{flex-direction:column;align-items:flex-start;gap:4px;}
.pay-val{font-size:0.8rem;word-break:break-all;}
.pay-upload-area{padding:1.2rem;}
}
</style>
@endsection

@section('content')
<div class="pay-wrap">
    <div class="pay-header">
        <h1>Complete Payment</h1>
        <p>Order #{{ $order->order_number }}</p>
        <div class="pay-amount">₹{{ number_format($order->total_amount, 0) }}</div>
    </div>

    {{-- Bank Transfer Section --}}
    <div class="pay-card">
        <h3>
            <svg width="20" height="20" fill="none" stroke="#f97316" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z"/></svg>
            Pay via Bank Transfer (NEFT / RTGS / IMPS)
        </h3>
        <div class="pay-row">
            <span class="pay-label">Account Name</span>
            <span class="pay-val">{{ $paymentConfig['account_name'] }}</span>
        </div>
        <div class="pay-row">
            <span class="pay-label">Account No.</span>
            <span class="pay-val">
                <span id="accNo">{{ $paymentConfig['account_no'] }}</span>
                <button class="pay-copy" onclick="copyText('accNo')">Copy</button>
            </span>
        </div>
        <div class="pay-row">
            <span class="pay-label">IFSC Code</span>
            <span class="pay-val">
                <span id="ifscCode">{{ $paymentConfig['ifsc'] }}</span>
                <button class="pay-copy" onclick="copyText('ifscCode')">Copy</button>
            </span>
        </div>
        <div class="pay-row">
            <span class="pay-label">Bank</span>
            <span class="pay-val">{{ $paymentConfig['bank_name'] }}</span>
        </div>
    </div>

    {{-- Instructions --}}
    <div class="pay-note">
        <p>
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
            Transfer ₹{{ number_format($order->total_amount, 0) }} to the above bank account via NEFT, RTGS, or IMPS, then upload your payment screenshot below.
        </p>
    </div>

    {{-- Upload Screenshot --}}
    <div class="pay-card">
        <h3>
            <svg width="20" height="20" fill="none" stroke="#f97316" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
            Upload Payment Screenshot
        </h3>
        <form action="{{ route('user.order.payment.upload', $order->id) }}" method="POST" enctype="multipart/form-data" id="payForm">
            @csrf
            <div class="pay-upload-area" id="uploadArea" onclick="document.getElementById('ssInput').click();">
                <svg width="40" height="40" fill="none" stroke="#475569" stroke-width="1.2" viewBox="0 0 24 24" style="margin:0 auto 8px;display:block;"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                <p style="color:#94a3b8;font-size:0.85rem;margin:0 0 4px;" id="uploadText">Click to upload screenshot</p>
                <p style="color:#475569;font-size:0.72rem;margin:0;">JPG, PNG — Max 5MB</p>
                <img id="ssPreview" class="pay-preview" alt="Preview">
            </div>
            <input type="file" id="ssInput" name="payment_screenshot" accept="image/*" style="display:none;">

            <div style="margin-top:1rem;">
                <label style="color:#94a3b8;font-size:0.78rem;display:block;margin-bottom:4px;">Transaction / Reference ID (optional)</label>
                <input type="text" name="payment_reference" placeholder="e.g. UPI Ref No. or UTR Number"
                    style="width:100%;background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.15);border-radius:10px;padding:9px 12px;color:#e2e8f0;font-size:0.85rem;box-sizing:border-box;outline:none;">
            </div>

            <button type="submit" class="pay-btn" id="submitBtn" disabled style="margin-top:1.2rem;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                Submit Payment Proof
            </button>
        </form>
    </div>

    @if($errors->any())
    <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);border-radius:10px;padding:12px;margin-top:1rem;">
        @foreach($errors->all() as $error)
        <p style="color:#f87171;font-size:0.82rem;margin:0;">{{ $error }}</p>
        @endforeach
    </div>
    @endif
</div>
@endsection

@section('js')
<script>
function copyText(id) {
    var text = document.getElementById(id).textContent;
    navigator.clipboard.writeText(text).then(function(){
        var btn = document.getElementById(id).nextElementSibling;
        btn.textContent = 'Copied!';
        setTimeout(function(){ btn.textContent = 'Copy'; }, 1500);
    });
}

var ssInput = document.getElementById('ssInput');
var uploadArea = document.getElementById('uploadArea');
var ssPreview = document.getElementById('ssPreview');
var uploadText = document.getElementById('uploadText');
var submitBtn = document.getElementById('submitBtn');

ssInput.addEventListener('change', function(){
    if (this.files && this.files[0]) {
        var file = this.files[0];
        if (file.size > 5 * 1024 * 1024) {
            alert('File too large. Maximum 5MB allowed.');
            this.value = '';
            return;
        }
        var reader = new FileReader();
        reader.onload = function(e){
            ssPreview.src = e.target.result;
            ssPreview.style.display = 'block';
            uploadArea.classList.add('has-file');
            uploadText.textContent = file.name;
            submitBtn.disabled = false;
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection
