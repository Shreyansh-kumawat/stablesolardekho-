@extends('layouts.public')
@section('content')
<div style="max-width:500px; margin:80px auto; padding:0 16px; text-align:center;">
    <div style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.10); border-radius:20px; padding:2rem;">
        <div style="margin-bottom:1rem; display:flex; justify-content:center;">
            <svg width="52" height="52" fill="none" stroke="#a78bfa" stroke-width="1.3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
        </div>
        <h2 style="color:#fff; font-weight:800; margin-bottom:0.5rem;">Complete Payment</h2>
        <p style="color:#94a3b8; font-size:0.88rem; margin-bottom:1.5rem;">Order #{{ $order->order_number }} — ₹{{ number_format($order->total_amount, 0) }}</p>
        <button id="rzp-button"
            style="background:linear-gradient(135deg,#60a5fa,#a78bfa); color:#0b1220; font-weight:800; padding:14px 32px; border-radius:14px; border:none; cursor:pointer; font-size:1rem; width:100%;">
            Pay ₹{{ number_format($order->total_amount, 0) }}
        </button>
        <p style="color:#94a3b8; font-size:0.75rem; margin-top:1rem;">Secured by Razorpay</p>
    </div>
</div>

<form id="rzp-form" action="{{ route('razorpay.callback') }}" method="POST" style="display:none;">
    @csrf
    <input type="hidden" name="razorpay_payment_id" id="rzp_payment_id">
    <input type="hidden" name="razorpay_order_id" id="rzp_order_id" value="{{ $rzpOrder['id'] }}">
    <input type="hidden" name="razorpay_signature" id="rzp_signature">
</form>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
var options = {
    key: "{{ $keyId }}",
    amount: {{ (int)($order->total_amount * 100) }},
    currency: "INR",
    name: "Stable Solar Energy",
    description: "Order #{{ $order->order_number }}",
    order_id: "{{ $rzpOrder['id'] }}",
    handler: function(response) {
        document.getElementById('rzp_payment_id').value = response.razorpay_payment_id;
        document.getElementById('rzp_order_id').value   = response.razorpay_order_id;
        document.getElementById('rzp_signature').value  = response.razorpay_signature;
        document.getElementById('rzp-form').submit();
    },
    prefill: {
        name:  "{{ $order->name }}",
        email: "{{ Auth::user()->email }}",
        contact: "{{ $order->phone }}"
    },
    theme: { color: "#60a5fa" }
};
var rzp = new Razorpay(options);
document.getElementById('rzp-button').onclick = function(e) { rzp.open(); e.preventDefault(); };
</script>
@endsection
