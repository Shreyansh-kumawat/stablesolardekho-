@extends('layouts.public')

@section('css')
<style>
.os-wrap{display:flex;align-items:center;justify-content:center;min-height:70vh;padding:40px 16px;}
.os-card{text-align:center;max-width:440px;width:100%;}
.os-check-ring{width:100px;height:100px;margin:0 auto 28px;position:relative;}
.os-check-circle{fill:none;stroke:#22c55e;stroke-width:3;stroke-linecap:round;stroke-dasharray:283;stroke-dashoffset:283;animation:os-draw 0.6s 0.2s ease forwards;}
.os-check-tick{fill:none;stroke:#22c55e;stroke-width:3.5;stroke-linecap:round;stroke-linejoin:round;stroke-dasharray:40;stroke-dashoffset:40;animation:os-draw 0.35s 0.7s ease forwards;}
@keyframes os-draw{to{stroke-dashoffset:0;}}
.os-title{color:#fff;font-size:1.6rem;font-weight:900;margin:0 0 8px;opacity:0;animation:os-fade 0.4s 0.5s ease forwards;}
.os-sub{color:#94a3b8;font-size:0.92rem;margin:0 0 6px;opacity:0;animation:os-fade 0.4s 0.65s ease forwards;}
.os-order-num{color:#f97316;font-weight:800;font-size:1rem;margin:0 0 10px;opacity:0;animation:os-fade 0.4s 0.8s ease forwards;}
.os-status{opacity:0;animation:os-fade 0.4s 0.9s ease forwards;margin-bottom:28px;}
.os-status span{background:rgba(249,115,22,0.12);border:1px solid rgba(249,115,22,0.3);color:#f97316;padding:6px 16px;border-radius:20px;font-size:0.8rem;font-weight:700;}
.os-bar-wrap{width:200px;height:4px;background:rgba(255,255,255,0.08);border-radius:4px;margin:0 auto;overflow:hidden;opacity:0;animation:os-fade 0.3s 1s ease forwards;}
.os-bar{height:100%;background:linear-gradient(90deg,#f97316,#22c55e);border-radius:4px;width:0;animation:os-fill 3s 1s linear forwards;}
@keyframes os-fade{to{opacity:1;}}
@keyframes os-fill{to{width:100%;}}
.os-redirect{color:#64748b;font-size:0.75rem;margin-top:10px;opacity:0;animation:os-fade 0.3s 1s ease forwards;}
</style>
@endsection

@section('content')
<div class="os-wrap">
    <div class="os-card">
        <div class="os-check-ring">
            <svg viewBox="0 0 100 100" width="100" height="100">
                <circle class="os-check-circle" cx="50" cy="50" r="45"/>
                <path class="os-check-tick" d="M30 52 L44 66 L70 38"/>
            </svg>
        </div>
        <p class="os-title">Payment Submitted!</p>
        <p class="os-sub">Your payment proof has been received</p>
        <p class="os-order-num">{{ $order->order_number }}</p>
        <div class="os-status">
            <span>Confirmation Pending</span>
        </div>
        <div class="os-bar-wrap"><div class="os-bar"></div></div>
        <p class="os-redirect">Redirecting to order details...</p>
    </div>
</div>
@endsection

@section('js')
<script>
setTimeout(function(){ window.location.href = "{{ route('user.order.detail', $order->id) }}"; }, 3200);
</script>
@endsection
