@extends('layouts.adminLayout')
@section('css')
<link rel="stylesheet" href="/assets/css/bootstrap-icons.min.css">
<style>
    .cb-wrap { max-width: 900px; margin: 0 auto; padding: 1.5rem 1rem; }
    .cb-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: .75rem; margin-bottom: 1.5rem; }
    .cb-header-left { display: flex; align-items: center; gap: .75rem; }
    .cb-header-left h1 { font-size: 1.3rem; font-weight: 800; color: #1f2937; margin: 0; }
    .cb-header-left p { font-size: .8rem; color: #6b7280; margin: .15rem 0 0; }
    .cb-icon-box { width: 40px; height: 40px; background: #2563eb; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.1rem; }
    .cb-badge { padding: 5px 14px; border-radius: 20px; font-size: .78rem; font-weight: 600; background: #eff6ff; color: #1d4ed8; }

    .cb-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; }

    .cb-empty { text-align: center; padding: 60px 20px; color: #9ca3af; }
    .cb-empty i { font-size: 2.5rem; display: block; margin-bottom: 10px; }
    .cb-empty p { font-size: .9rem; }

    .cb-item { display: flex; align-items: center; gap: 14px; padding: 14px 20px; border-bottom: 1px solid #f3f4f6; transition: background .1s; }
    .cb-item:last-child { border-bottom: none; }
    .cb-item:hover { background: #f9fafb; }

    .cb-file-icon { width: 42px; height: 42px; background: #fef2f2; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #dc2626; font-size: 1.2rem; flex-shrink: 0; }
    .cb-file-info { flex: 1; min-width: 0; }
    .cb-file-name { font-weight: 700; color: #1f2937; font-size: .88rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .cb-file-name a { color: #2563eb; text-decoration: none; }
    .cb-file-name a:hover { text-decoration: underline; }
    .cb-file-meta { font-size: .75rem; color: #9ca3af; margin-top: 2px; }
    .cb-file-remarks { font-size: .78rem; color: #6b7280; margin-top: 2px; }

    .cb-order-tag { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 16px; font-size: .72rem; font-weight: 600; background: #eff6ff; color: #1d4ed8; white-space: nowrap; flex-shrink: 0; }

    @media (max-width: 640px) {
        .cb-item { flex-wrap: wrap; gap: 10px; }
        .cb-order-tag { margin-top: 4px; }
    }
</style>
@endsection

@section('content')
<div class="cb-wrap">
    <div class="cb-header">
        <div class="cb-header-left">
            <div class="cb-icon-box"><i class="bi bi-file-earmark-text"></i></div>
            <div>
                <h1>Bills</h1>
                <p>Bills uploaded by admin for your orders</p>
            </div>
        </div>
        <span class="cb-badge">{{ $bills->count() }} {{ Str::plural('bill', $bills->count()) }}</span>
    </div>

    <div class="cb-card">
        @forelse($bills as $bill)
        <div class="cb-item">
            <div class="cb-file-icon"><i class="bi bi-file-earmark-pdf"></i></div>
            <div class="cb-file-info">
                <div class="cb-file-name">
                    <a href="{{ url('serve/' . $bill->file_path) }}" target="_blank">{{ $bill->file_name }}</a>
                </div>
                <div class="cb-file-meta">
                    {{ $bill->created_at->format('d M Y, h:i A') }}
                    @if($bill->file_size)
                        &middot; {{ number_format($bill->file_size / 1024, 0) }} KB
                    @endif
                </div>
                @if($bill->remarks)
                <div class="cb-file-remarks">{{ $bill->remarks }}</div>
                @endif
            </div>
            @if($bill->cpOrder)
            <a href="{{ route('viewSingleOrderCp', $bill->cpOrder->id) }}" class="cb-order-tag" title="View order">
                <i class="bi bi-box-arrow-up-right"></i>
                {{ $bill->cpOrder->order_id }}
            </a>
            @endif
        </div>
        @empty
        <div class="cb-empty">
            <i class="bi bi-file-earmark-x"></i>
            <p>No bills yet</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
