@extends('layouts.adminLayout')
@section('page_title', 'Referrals & Cashback')

@section('content')
<div style="padding:20px;">

    {{-- Stats --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;">
        <div style="background:#fff;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.08);padding:20px;text-align:center;">
            <p style="font-size:.7rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:1px;margin:0 0 4px;">Total Leads</p>
            <p style="font-size:1.75rem;font-weight:800;color:#1e293b;margin:0;">{{ $leads->count() }}</p>
        </div>
        <div style="background:#fff;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.08);padding:20px;text-align:center;">
            <p style="font-size:.7rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:1px;margin:0 0 4px;">Active Codes</p>
            <p style="font-size:1.75rem;font-weight:800;color:#1e293b;margin:0;">{{ $codes->where('is_active', true)->count() }}</p>
        </div>
        <div style="background:#fff;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.08);padding:20px;text-align:center;">
            <p style="font-size:.7rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:1px;margin:0 0 4px;">Pending Cashback</p>
            <p style="font-size:1.75rem;font-weight:800;color:#d97706;margin:0;">{{ $leads->where('status','payment_done')->count() }}</p>
        </div>
        <div style="background:#fff;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.08);padding:20px;text-align:center;">
            <p style="font-size:.7rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:1px;margin:0 0 4px;">Total Paid</p>
            <p style="font-size:1.75rem;font-weight:800;color:#059669;margin:0;">₹{{ number_format($leads->flatMap(fn($l)=>$l->cashback?collect([$l->cashback]):collect())->where('status','paid')->sum('cashback_amount')) }}</p>
        </div>
    </div>

    {{-- Tabs --}}
    <script>
    function showTab(name){
        document.querySelectorAll('.ref-panel').forEach(function(p){p.style.display='none';});
        document.querySelectorAll('.ref-tab').forEach(function(t){t.style.borderBottomColor='transparent';t.style.color='#6b7280';});
        document.getElementById('panel-'+name).style.display='block';
        var tab=document.getElementById('tab-'+name);tab.style.borderBottomColor='#2563eb';tab.style.color='#2563eb';
    }
    </script>
    <div style="display:flex;gap:4px;border-bottom:2px solid #e5e7eb;margin-bottom:20px;">
        <button onclick="showTab('leads')" id="tab-leads" class="ref-tab" style="padding:10px 20px;font-size:.85rem;font-weight:600;border:none;background:none;cursor:pointer;border-bottom:2px solid #2563eb;color:#2563eb;margin-bottom:-2px;">Referral Leads</button>
        <button onclick="showTab('codes')" id="tab-codes" class="ref-tab" style="padding:10px 20px;font-size:.85rem;font-weight:600;border:none;background:none;cursor:pointer;border-bottom:2px solid transparent;color:#6b7280;margin-bottom:-2px;">Referral Codes</button>
        <button onclick="showTab('cashback')" id="tab-cashback" class="ref-tab" style="padding:10px 20px;font-size:.85rem;font-weight:600;border:none;background:none;cursor:pointer;border-bottom:2px solid transparent;color:#6b7280;margin-bottom:-2px;">Cashback</button>
        <button onclick="showTab('slabs')" id="tab-slabs" class="ref-tab" style="padding:10px 20px;font-size:.85rem;font-weight:600;border:none;background:none;cursor:pointer;border-bottom:2px solid transparent;color:#6b7280;margin-bottom:-2px;">Slab Settings</button>
    </div>

    {{-- LEADS TAB --}}
    <div id="panel-leads" class="ref-panel">
        @forelse($leads as $lead)
        @php $statusColors = ['pending'=>['#f3f4f6','#4b5563'],'contacted'=>['#dbeafe','#1d4ed8'],'installed'=>['#ede9fe','#6d28d9'],'payment_done'=>['#fef3c7','#b45309'],'cashback_approved'=>['#d1fae5','#047857'],'rejected'=>['#fee2e2','#b91c1c']]; $sc=$statusColors[$lead->status]??['#f3f4f6','#4b5563']; @endphp
        <div style="background:#fff;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.08);margin-bottom:14px;border:1px solid #f1f5f9;overflow:hidden;">
            {{-- Top row: status badge + date + actions --}}
            <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 18px;background:#f8fafc;border-bottom:1px solid #f1f5f9;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <span style="display:inline-block;padding:3px 12px;border-radius:20px;font-size:.72rem;font-weight:700;background:{{ $sc[0] }};color:{{ $sc[1] }};">{{ ucwords(str_replace('_',' ',$lead->status)) }}</span>
                    <span style="font-size:.78rem;color:#9ca3af;">{{ $lead->created_at->format('d M Y') }}</span>
                </div>
                <div style="display:flex;gap:6px;">
                    <button class="update-status-btn" data-id="{{ $lead->id }}" data-status="{{ $lead->status }}" data-remarks="{{ $lead->admin_remarks }}" style="padding:5px 12px;font-size:.75rem;background:#eff6ff;color:#2563eb;border:none;border-radius:6px;cursor:pointer;font-weight:600;"><svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;margin-right:3px;"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg> Update</button>
                    @if($lead->status == 'payment_done' && !$lead->cashback)
                    <button class="create-cashback-btn" data-id="{{ $lead->id }}" data-name="{{ $lead->name }}" style="padding:5px 12px;font-size:.75rem;background:#ecfdf5;color:#059669;border:none;border-radius:6px;cursor:pointer;font-weight:600;"><svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;margin-right:3px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Cashback</button>
                    @endif
                </div>
            </div>
            {{-- Body: two columns --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0;">
                {{-- Left: Referred Person --}}
                <div style="padding:16px 18px;border-right:1px solid #f1f5f9;">
                    <p style="font-size:.68rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.5px;margin:0 0 8px;">Referred Person</p>
                    <p style="font-weight:700;color:#1e293b;font-size:.92rem;margin:0 0 4px;">{{ $lead->name }}</p>
                    <p style="color:#6b7280;font-size:.8rem;margin:0 0 2px;">{{ $lead->email }}</p>
                    <p style="color:#6b7280;font-size:.8rem;margin:0 0 2px;">{{ $lead->phone }}</p>
                    <div style="display:flex;gap:16px;margin-top:8px;">
                        @if($lead->city)<span style="font-size:.78rem;color:#4b5563;"><svg width="10" height="10" fill="#9ca3af" viewBox="0 0 384 512" style="display:inline;vertical-align:middle;margin-right:3px;"><path d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 110 128 64 64 0 010-128z"/></svg>{{ $lead->city }}@if($lead->state), {{ $lead->state }}@endif</span>@endif
                        @if($lead->system_size)<span style="font-size:.78rem;color:#4b5563;"><svg width="10" height="10" fill="#9ca3af" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;margin-right:3px;"><path d="M4 4h16v16H4V4zm2 2v5h5V6H6zm7 0v5h5V6h-5zM6 13v5h5v-5H6zm7 0v5h5v-5h-5z"/></svg>{{ $lead->system_size }}</span>@endif
                    </div>
                </div>
                {{-- Right: Referred By + Bank Details --}}
                <div style="padding:16px 18px;">
                    <p style="font-size:.68rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.5px;margin:0 0 8px;">Referred By</p>
                    @if($lead->referrer)
                    <p style="font-weight:700;color:#1e293b;font-size:.92rem;margin:0 0 4px;">{{ $lead->referrer->name }}@if($lead->referrer->role_id == 4) <span style="display:inline-block;padding:1px 6px;border-radius:10px;font-size:.65rem;font-weight:700;background:#ede9fe;color:#6d28d9;vertical-align:middle;margin-left:4px;">CP</span>@endif</p>
                    <p style="color:#6b7280;font-size:.8rem;margin:0 0 2px;">{{ $lead->referrer->email }}</p>
                    @if($lead->referrer->mobile_number)<p style="color:#6b7280;font-size:.8rem;margin:0 0 2px;">{{ $lead->referrer->mobile_number }}</p>@endif
                    {{-- Bank Details --}}
                    @if($lead->referrer->bank_account_number && $lead->referrer->bank_ifsc)
                    <div style="margin-top:10px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:10px 12px;">
                        <p style="font-size:.68rem;font-weight:700;color:#15803d;text-transform:uppercase;letter-spacing:.5px;margin:0 0 6px;"><svg width="11" height="11" fill="#15803d" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;margin-right:4px;"><path d="M12 2L2 7v2h20V7L12 2zM4 11v6h3v-6H4zm5 0v6h3v-6H9zm5 0v6h3v-6h-3zm5 0v6h3v-6h-3zM2 19v2h20v-2H2z"/></svg>Bank Details</p>
                        @if($lead->referrer->bank_account_holder)<p style="font-size:.8rem;color:#166534;margin:0 0 3px;display:flex;align-items:center;gap:6px;"><strong>Holder:</strong> <span>{{ $lead->referrer->bank_account_holder }}</span><button onclick="copyText(this,'{{ $lead->referrer->bank_account_holder }}')" style="background:none;border:none;cursor:pointer;color:#15803d;padding:0;line-height:1;" title="Copy"><svg width="11" height="11" fill="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;"><path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/></svg></button></p>@endif
                        <p style="font-size:.8rem;color:#166534;margin:0 0 3px;display:flex;align-items:center;gap:6px;"><strong>A/C:</strong> <span>{{ $lead->referrer->bank_account_number }}</span><button onclick="copyText(this,'{{ $lead->referrer->bank_account_number }}')" style="background:none;border:none;cursor:pointer;color:#15803d;padding:0;line-height:1;" title="Copy"><svg width="11" height="11" fill="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;"><path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/></svg></button></p>
                        <p style="font-size:.8rem;color:#166534;margin:0 0 3px;display:flex;align-items:center;gap:6px;"><strong>IFSC:</strong> <span>{{ $lead->referrer->bank_ifsc }}</span><button onclick="copyText(this,'{{ $lead->referrer->bank_ifsc }}')" style="background:none;border:none;cursor:pointer;color:#15803d;padding:0;line-height:1;" title="Copy"><svg width="11" height="11" fill="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;"><path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/></svg></button></p>
                        @if($lead->referrer->bank_name)<p style="font-size:.8rem;color:#166534;margin:0;display:flex;align-items:center;gap:6px;"><strong>Bank:</strong> <span>{{ $lead->referrer->bank_name }}</span><button onclick="copyText(this,'{{ $lead->referrer->bank_name }}')" style="background:none;border:none;cursor:pointer;color:#15803d;padding:0;line-height:1;" title="Copy"><svg width="11" height="11" fill="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;"><path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/></svg></button></p>@endif
                    </div>
                    @else
                    <div style="margin-top:10px;background:#fef3c7;border:1px solid #fcd34d;border-radius:8px;padding:8px 12px;">
                        <p style="font-size:.78rem;color:#92400e;margin:0;"><svg width="12" height="12" fill="#92400e" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;margin-right:4px;"><path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/></svg>Bank details not provided</p>
                    </div>
                    @endif
                    @else
                    <p style="color:#9ca3af;font-size:.85rem;margin:0;">N/A</p>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div style="background:#fff;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.08);padding:48px;text-align:center;">
            <p style="color:#9ca3af;font-size:.9rem;margin:0;">No referral leads yet</p>
        </div>
        @endforelse
    </div>

    {{-- CODES TAB --}}
    <div id="panel-codes" class="ref-panel" style="display:none;">
        <div style="background:#fff;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.08);overflow:hidden;">
            <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-bottom:1px solid #f1f5f9;">
                <h3 style="font-weight:700;color:#374151;margin:0;font-size:.95rem;">Referral Codes</h3>
                <button onclick="document.getElementById('genCodeModal').classList.add('active')" style="padding:6px 14px;background:#2563eb;color:#fff;font-size:.78rem;font-weight:600;border:none;border-radius:8px;cursor:pointer;">+ Generate Code</button>
            </div>
            <div style="overflow-x:auto;">
                <table style="width:100%;font-size:.85rem;border-collapse:collapse;">
                    <thead>
                        <tr style="background:#f8fafc;text-align:left;">
                            <th style="padding:12px 16px;font-size:.7rem;font-weight:700;color:#6b7280;text-transform:uppercase;">User</th>
                            <th style="padding:12px 16px;font-size:.7rem;font-weight:700;color:#6b7280;text-transform:uppercase;">Code</th>
                            <th style="padding:12px 16px;font-size:.7rem;font-weight:700;color:#6b7280;text-transform:uppercase;">Referral Link</th>
                            <th style="padding:12px 16px;font-size:.7rem;font-weight:700;color:#6b7280;text-transform:uppercase;">Leads</th>
                            <th style="padding:12px 16px;font-size:.7rem;font-weight:700;color:#6b7280;text-transform:uppercase;">Status</th>
                            <th style="padding:12px 16px;font-size:.7rem;font-weight:700;color:#6b7280;text-transform:uppercase;">Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($codes as $rc)
                        <tr style="border-top:1px solid #f1f5f9;">
                            <td style="padding:12px 16px;">{{ $rc->user->name ?? 'Deleted' }}@if($rc->user && $rc->user->role_id == 4) <span style="display:inline-block;padding:1px 6px;border-radius:10px;font-size:.65rem;font-weight:700;background:#ede9fe;color:#6d28d9;vertical-align:middle;margin-left:4px;">CP</span>@endif</td>
                            <td style="padding:12px 16px;"><code style="font-size:.9rem;font-weight:700;color:#ea580c;background:#fff7ed;padding:2px 8px;border-radius:4px;">{{ $rc->code }}</code></td>
                            <td style="padding:12px 16px;">
                                <div style="display:flex;gap:6px;align-items:center;">
                                    <input type="text" readonly value="{{ url('/refer/'.$rc->code) }}" id="link-{{ $rc->id }}" style="font-size:.75rem;border:1px solid #e5e7eb;border-radius:4px;padding:4px 8px;width:220px;background:#f9fafb;">
                                    <button class="copy-link-btn" data-target="link-{{ $rc->id }}" style="padding:4px 8px;font-size:.75rem;background:#f3f4f6;border:1px solid #e5e7eb;border-radius:4px;cursor:pointer;"><svg width="12" height="12" fill="#6b7280" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;"><path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/></svg></button>
                                </div>
                            </td>
                            <td style="padding:12px 16px;">{{ \App\Models\ReferralLead::where('referrer_id', $rc->user_id)->count() }}</td>
                            <td style="padding:12px 16px;"><span style="display:inline-block;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:600;background:{{ $rc->is_active?'#d1fae5':'#f3f4f6' }};color:{{ $rc->is_active?'#047857':'#6b7280' }};">{{ $rc->is_active ? 'Active' : 'Inactive' }}</span></td>
                            <td style="padding:12px 16px;font-size:.78rem;color:#6b7280;">{{ $rc->created_at->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" style="padding:32px;text-align:center;color:#9ca3af;">No referral codes generated yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- CASHBACK TAB --}}
    <div id="panel-cashback" class="ref-panel" style="display:none;">
        <div style="background:#fff;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.08);overflow:hidden;">
            <div style="overflow-x:auto;">
                <table style="width:100%;font-size:.85rem;border-collapse:collapse;">
                    <thead>
                        <tr style="background:#f8fafc;text-align:left;">
                            <th style="padding:12px 16px;font-size:.7rem;font-weight:700;color:#6b7280;text-transform:uppercase;">#</th>
                            <th style="padding:12px 16px;font-size:.7rem;font-weight:700;color:#6b7280;text-transform:uppercase;">Referrer</th>
                            <th style="padding:12px 16px;font-size:.7rem;font-weight:700;color:#6b7280;text-transform:uppercase;">Lead</th>
                            <th style="padding:12px 16px;font-size:.7rem;font-weight:700;color:#6b7280;text-transform:uppercase;">Deal Amt</th>
                            <th style="padding:12px 16px;font-size:.7rem;font-weight:700;color:#6b7280;text-transform:uppercase;">%</th>
                            <th style="padding:12px 16px;font-size:.7rem;font-weight:700;color:#6b7280;text-transform:uppercase;">Cashback</th>
                            <th style="padding:12px 16px;font-size:.7rem;font-weight:700;color:#6b7280;text-transform:uppercase;">Status</th>
                            <th style="padding:12px 16px;font-size:.7rem;font-weight:700;color:#6b7280;text-transform:uppercase;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $cashbacks = \App\Models\CashbackTransaction::with(['referrer','lead'])->latest()->get(); @endphp
                        @forelse($cashbacks as $cb)
                        @php $cbColors=['pending'=>['#fef3c7','#b45309'],'approved'=>['#dbeafe','#1d4ed8'],'paid'=>['#d1fae5','#047857'],'rejected'=>['#fee2e2','#b91c1c']]; $cc=$cbColors[$cb->status]??['#f3f4f6','#4b5563']; @endphp
                        <tr style="border-top:1px solid #f1f5f9;">
                            <td style="padding:12px 16px;">{{ $cb->id }}</td>
                            <td style="padding:12px 16px;">{{ $cb->referrer->name ?? 'N/A' }}@if($cb->referrer && $cb->referrer->role_id == 4) <span style="display:inline-block;padding:1px 6px;border-radius:10px;font-size:.65rem;font-weight:700;background:#ede9fe;color:#6d28d9;vertical-align:middle;margin-left:4px;">CP</span>@endif</td>
                            <td style="padding:12px 16px;">{{ $cb->lead->name ?? 'N/A' }}</td>
                            <td style="padding:12px 16px;">₹{{ number_format($cb->deal_amount) }}</td>
                            <td style="padding:12px 16px;">{{ $cb->cashback_percentage }}%</td>
                            <td style="padding:12px 16px;font-weight:700;color:#059669;">₹{{ number_format($cb->cashback_amount) }}</td>
                            <td style="padding:12px 16px;">
                                <span style="display:inline-block;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:600;background:{{ $cc[0] }};color:{{ $cc[1] }};">{{ ucfirst($cb->status) }}</span>
                                @if($cb->paid_at)<br><span style="font-size:.72rem;color:#9ca3af;">Paid {{ $cb->paid_at->format('d M') }}</span>@endif
                            </td>
                            <td style="padding:12px 16px;">
                                @if($cb->status == 'pending')
                                <button class="approve-cb-btn" data-id="{{ $cb->id }}" style="padding:4px 12px;font-size:.75rem;background:#059669;color:#fff;border:none;border-radius:4px;cursor:pointer;font-weight:600;">&#10003; Approve</button>
                                @elseif($cb->status == 'approved')
                                <button class="mark-paid-btn" data-id="{{ $cb->id }}" style="padding:4px 12px;font-size:.75rem;background:#2563eb;color:#fff;border:none;border-radius:4px;cursor:pointer;font-weight:600;">&#8377; Mark Paid</button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" style="padding:32px;text-align:center;color:#9ca3af;">No cashback transactions yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

    {{-- SLAB SETTINGS TAB --}}
    <div id="panel-slabs" class="ref-panel" style="display:none;">
        {{-- CP Slabs --}}
        <div style="background:#fff;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.08);padding:24px;margin-bottom:20px;">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                <span style="display:inline-block;padding:2px 8px;border-radius:10px;font-size:.7rem;font-weight:700;background:#ede9fe;color:#6d28d9;">CP</span>
                <h3 style="font-weight:700;color:#374151;margin:0;font-size:.95rem;">Channel Partner Cashback Slabs</h3>
            </div>
            <p style="color:#6b7280;font-size:.8rem;margin:0 0 16px;">Cashback slabs for Channel Partners. These are separate from user slabs and typically higher.</p>
            <div id="cpSlabRows">
                @foreach($cpSlabs as $i => $slab)
                <div class="cp-slab-row" style="display:flex;gap:12px;align-items:center;margin-bottom:10px;">
                    <div style="flex:1;"><label style="font-size:.72rem;font-weight:600;color:#6b7280;display:block;margin-bottom:2px;">From (referrals)</label><input type="number" name="cp_min[]" value="{{ $slab['min'] }}" style="width:100%;padding:8px 10px;border:1px solid #d1d5db;border-radius:8px;font-size:.85rem;box-sizing:border-box;"></div>
                    <div style="flex:1;"><label style="font-size:.72rem;font-weight:600;color:#6b7280;display:block;margin-bottom:2px;">To (referrals)</label><input type="number" name="cp_max[]" value="{{ $slab['max'] }}" style="width:100%;padding:8px 10px;border:1px solid #d1d5db;border-radius:8px;font-size:.85rem;box-sizing:border-box;"></div>
                    <div style="flex:1;"><label style="font-size:.72rem;font-weight:600;color:#6b7280;display:block;margin-bottom:2px;">Cashback %</label><input type="number" step="0.5" name="cp_percentage[]" value="{{ $slab['percentage'] }}" style="width:100%;padding:8px 10px;border:1px solid #d1d5db;border-radius:8px;font-size:.85rem;box-sizing:border-box;"></div>
                    <button onclick="this.closest('.cp-slab-row').remove()" style="margin-top:16px;background:#fee2e2;color:#b91c1c;border:none;border-radius:6px;padding:6px 10px;cursor:pointer;font-size:.8rem;"><svg width="11" height="11" fill="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg></button>
                </div>
                @endforeach
            </div>
            <div style="display:flex;gap:10px;margin-top:16px;">
                <button onclick="addCpSlabRow()" style="padding:8px 16px;background:#f5f3ff;color:#6d28d9;border:none;border-radius:8px;font-weight:600;cursor:pointer;font-size:.82rem;">+ Add Slab</button>
                <button onclick="saveCpSlabs()" style="padding:8px 16px;background:#7c3aed;color:#fff;border:none;border-radius:8px;font-weight:600;cursor:pointer;font-size:.82rem;"><svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;margin-right:4px;"><path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/></svg> Save CP Slabs</button>
            </div>
            <div id="cpSlabMsg" style="display:none;margin-top:12px;padding:10px 14px;border-radius:8px;font-size:.82rem;font-weight:600;"></div>
        </div>

        {{-- User Slabs --}}
        <div style="background:#fff;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.08);padding:24px;">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                <span style="display:inline-block;padding:2px 8px;border-radius:10px;font-size:.7rem;font-weight:700;background:#dbeafe;color:#1d4ed8;">User</span>
                <h3 style="font-weight:700;color:#374151;margin:0;font-size:.95rem;">User Cashback Slabs</h3>
            </div>
            <p style="color:#6b7280;font-size:.8rem;margin:0 0 16px;">Cashback slabs for regular users. System auto-suggests slab % when creating cashback. Admin can still override.</p>
            <div id="slabRows">
                @foreach($slabs as $i => $slab)
                <div class="slab-row" style="display:flex;gap:12px;align-items:center;margin-bottom:10px;">
                    <div style="flex:1;"><label style="font-size:.72rem;font-weight:600;color:#6b7280;display:block;margin-bottom:2px;">From (referrals)</label><input type="number" name="min[]" value="{{ $slab['min'] }}" style="width:100%;padding:8px 10px;border:1px solid #d1d5db;border-radius:8px;font-size:.85rem;box-sizing:border-box;"></div>
                    <div style="flex:1;"><label style="font-size:.72rem;font-weight:600;color:#6b7280;display:block;margin-bottom:2px;">To (referrals)</label><input type="number" name="max[]" value="{{ $slab['max'] }}" style="width:100%;padding:8px 10px;border:1px solid #d1d5db;border-radius:8px;font-size:.85rem;box-sizing:border-box;"></div>
                    <div style="flex:1;"><label style="font-size:.72rem;font-weight:600;color:#6b7280;display:block;margin-bottom:2px;">Cashback %</label><input type="number" step="0.5" name="percentage[]" value="{{ $slab['percentage'] }}" style="width:100%;padding:8px 10px;border:1px solid #d1d5db;border-radius:8px;font-size:.85rem;box-sizing:border-box;"></div>
                    <button onclick="this.closest('.slab-row').remove()" style="margin-top:16px;background:#fee2e2;color:#b91c1c;border:none;border-radius:6px;padding:6px 10px;cursor:pointer;font-size:.8rem;"><svg width="11" height="11" fill="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg></button>
                </div>
                @endforeach
            </div>
            <div style="display:flex;gap:10px;margin-top:16px;">
                <button onclick="addSlabRow()" style="padding:8px 16px;background:#eff6ff;color:#2563eb;border:none;border-radius:8px;font-weight:600;cursor:pointer;font-size:.82rem;">+ Add Slab</button>
                <button onclick="saveSlabs()" style="padding:8px 16px;background:#059669;color:#fff;border:none;border-radius:8px;font-weight:600;cursor:pointer;font-size:.82rem;"><svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;margin-right:4px;"><path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/></svg> Save User Slabs</button>
            </div>
            <div id="slabMsg" style="display:none;margin-top:12px;padding:10px 14px;border-radius:8px;font-size:.82rem;font-weight:600;"></div>
        </div>
    </div>

{{-- MODALS --}}
<style>
.ref-modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:999;display:none;align-items:center;justify-content:center;}
.ref-modal-overlay.active{display:flex;}
.ref-modal{background:#fff;border-radius:12px;width:90%;max-width:440px;box-shadow:0 20px 60px rgba(0,0,0,.3);}
.ref-modal-header{padding:16px 20px;border-bottom:1px solid #e5e7eb;display:flex;justify-content:space-between;align-items:center;}
.ref-modal-header h3{font-size:1rem;font-weight:700;margin:0;}
.ref-modal-body{padding:20px;}
.ref-modal-footer{padding:12px 20px;border-top:1px solid #e5e7eb;text-align:right;}
.ref-modal label{display:block;font-size:.8rem;font-weight:600;color:#374151;margin-bottom:4px;}
.ref-modal select,.ref-modal input,.ref-modal textarea{width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:8px;font-size:.9rem;margin-bottom:12px;box-sizing:border-box;}
.ref-btn-primary{padding:8px 20px;background:#2563eb;color:#fff;border:none;border-radius:8px;font-weight:600;cursor:pointer;}
.ref-btn-success{padding:8px 20px;background:#059669;color:#fff;border:none;border-radius:8px;font-weight:600;cursor:pointer;}
.ref-btn-close{background:none;border:none;font-size:1.4rem;cursor:pointer;color:#9ca3af;line-height:1;}
</style>

<div id="statusModal" class="ref-modal-overlay"><div class="ref-modal">
    <div class="ref-modal-header"><h3>Update Lead Status</h3><button class="ref-btn-close" onclick="document.getElementById('statusModal').classList.remove('active')">&times;</button></div>
    <div class="ref-modal-body">
        <input type="hidden" id="statusLeadId">
        <label>Status</label>
        <select id="statusSelect"><option value="pending">Pending</option><option value="contacted">Contacted</option><option value="installed">Installed</option><option value="payment_done">Payment Done</option><option value="cashback_approved">Cashback Approved</option><option value="rejected">Rejected</option></select>
        <label>Admin Remarks</label>
        <textarea id="statusRemarks" rows="2"></textarea>
    </div>
    <div class="ref-modal-footer"><button class="ref-btn-primary" id="saveStatusBtn">Save</button></div>
</div></div>

<div id="cashbackModal" class="ref-modal-overlay"><div class="ref-modal">
    <div class="ref-modal-header"><h3>Create Cashback</h3><button class="ref-btn-close" onclick="document.getElementById('cashbackModal').classList.remove('active')">&times;</button></div>
    <div class="ref-modal-body">
        <input type="hidden" id="cbLeadId">
        <p style="margin-bottom:12px;">Lead: <strong id="cbLeadName"></strong></p>
        <div id="cbSlabInfo" style="background:#eff6ff;border:1px solid #93c5fd;border-radius:8px;padding:10px 14px;margin-bottom:12px;font-size:.82rem;color:#1e40af;display:none;">
            <svg width="12" height="12" fill="#1e40af" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;margin-right:4px;"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
            Referrer has <strong id="cbRefCount">0</strong> successful referrals. Auto slab: <strong id="cbAutoSlab">5</strong>%
        </div>
        <label>Deal Amount (₹)</label>
        <input type="number" id="cbDealAmount" placeholder="Total deal value">
        <label>Cashback % <span style="font-weight:400;color:#9ca3af;">(auto-filled from slab, you can override)</span></label>
        <input type="number" step="0.5" id="cbPercentage" placeholder="Cashback %" value="5">
        <div id="cbPreview" style="display:none;background:#ecfdf5;border:1px solid #6ee7b7;border-radius:8px;padding:10px 14px;color:#065f46;font-weight:600;">Cashback: <span id="cbPreviewAmt">₹0</span></div>
    </div>
    <div class="ref-modal-footer"><button class="ref-btn-success" id="saveCashbackBtn">Create Cashback</button></div>
</div></div>

<div id="paidModal" class="ref-modal-overlay"><div class="ref-modal">
    <div class="ref-modal-header"><h3>Mark as Paid</h3><button class="ref-btn-close" onclick="document.getElementById('paidModal').classList.remove('active')">&times;</button></div>
    <div class="ref-modal-body">
        <input type="hidden" id="paidCbId">
        <label>Payment Mode</label>
        <select id="paidMode"><option value="bank_transfer">Bank Transfer</option><option value="upi">UPI</option><option value="cash">Cash</option><option value="cheque">Cheque</option></select>
        <label>Transaction Reference</label>
        <input type="text" id="paidRef" placeholder="UTR / Reference number">
        <label>Remarks</label>
        <textarea id="paidRemarks" rows="2"></textarea>
    </div>
    <div class="ref-modal-footer"><button class="ref-btn-primary" id="savePaidBtn">Confirm Payment</button></div>
</div></div>

<div id="genCodeModal" class="ref-modal-overlay"><div class="ref-modal">
    <div class="ref-modal-header"><h3>Generate Referral Code</h3><button class="ref-btn-close" onclick="document.getElementById('genCodeModal').classList.remove('active')">&times;</button></div>
    <div class="ref-modal-body">
        <label>Select User</label>
        <select id="genUserId">
            <option value="">Choose a user...</option>
            @foreach(\App\Models\User::whereIn('role_id',[3,4])->whereDoesntHave('referralCode')->orderBy('name')->get() as $u)
            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
            @endforeach
        </select>
        <div id="genResult" style="display:none;background:#ecfdf5;border:1px solid #6ee7b7;border-radius:8px;padding:10px 14px;color:#065f46;margin-top:8px;"></div>
    </div>
    <div class="ref-modal-footer"><button class="ref-btn-primary" id="genCodeBtn">Generate</button></div>
</div></div>
@endsection

@section('js')
<script>
var csrf='{{ csrf_token() }}';

function copyText(btn,text){
    navigator.clipboard.writeText(text).then(function(){
        var orig=btn.innerHTML;btn.innerHTML='&#10003;';btn.style.color='#047857';
        setTimeout(function(){btn.innerHTML=orig;btn.style.color='#15803d';},1500);
    });
}

$('.update-status-btn').click(function(){
    $('#statusLeadId').val($(this).data('id'));$('#statusSelect').val($(this).data('status'));$('#statusRemarks').val($(this).data('remarks'));
    document.getElementById('statusModal').classList.add('active');
});
$('#saveStatusBtn').click(function(){
    $.post('/admin/referrals/leads/'+$('#statusLeadId').val()+'/status',{_token:csrf,status:$('#statusSelect').val(),admin_remarks:$('#statusRemarks').val()},function(){location.reload();});
});

$('.create-cashback-btn').click(function(){
    var lid=$(this).data('id');
    $('#cbLeadId').val(lid);$('#cbLeadName').text($(this).data('name'));$('#cbDealAmount').val('');$('#cbPreview').hide();$('#cbSlabInfo').hide();
    $.get('/admin/referrals/leads/'+lid+'/slab-info',function(res){
        $('#cbRefCount').text(res.successful_referrals);$('#cbAutoSlab').text(res.percentage);
        $('#cbPercentage').val(res.percentage);$('#cbSlabInfo').show();
    });
    document.getElementById('cashbackModal').classList.add('active');
});
$('#cbDealAmount,#cbPercentage').on('input change',function(){
    var a=parseFloat($('#cbDealAmount').val())||0,p=parseFloat($('#cbPercentage').val())||0;
    if(a>0){$('#cbPreview').show();$('#cbPreviewAmt').text('₹'+Math.round(a*p/100));}
});
$('#saveCashbackBtn').click(function(){
    $.post('/admin/referrals/leads/'+$('#cbLeadId').val()+'/cashback',{_token:csrf,deal_amount:$('#cbDealAmount').val(),cashback_percentage:$('#cbPercentage').val()},function(){location.reload();});
});

$('.approve-cb-btn').click(function(){
    var id=$(this).data('id');
    if(confirm('Approve this cashback?'))$.post('/admin/referrals/cashback/'+id+'/approve',{_token:csrf},function(){location.reload();});
});

$('.mark-paid-btn').click(function(){
    $('#paidCbId').val($(this).data('id'));document.getElementById('paidModal').classList.add('active');
});
$('#savePaidBtn').click(function(){
    $.post('/admin/referrals/cashback/'+$('#paidCbId').val()+'/paid',{_token:csrf,payment_mode:$('#paidMode').val(),transaction_reference:$('#paidRef').val(),admin_remarks:$('#paidRemarks').val()},function(){location.reload();});
});

$('#genCodeBtn').click(function(){
    var uid=$('#genUserId').val();if(!uid){alert('Select a user');return;}
    $.post('/admin/referrals/generate-code/'+uid,{_token:csrf},function(res){
        $('#genResult').show().html('Code: <strong>'+res.code+'</strong><br>Link: '+location.origin+'/refer/'+res.code);$('#genCodeBtn').prop('disabled',true);
    }).fail(function(xhr){alert(xhr.responseJSON?.error||'Error');});
});

$('.copy-link-btn').click(function(){
    var i=document.getElementById($(this).data('target'));i.select();document.execCommand('copy');
    var b=$(this),orig=b.html();b.html('&#10003;');setTimeout(function(){b.html(orig);},1500);
});

function addCpSlabRow(){
    var html='<div class="cp-slab-row" style="display:flex;gap:12px;align-items:center;margin-bottom:10px;">';
    html+='<div style="flex:1;"><label style="font-size:.72rem;font-weight:600;color:#6b7280;display:block;margin-bottom:2px;">From</label><input type="number" name="cp_min[]" style="width:100%;padding:8px 10px;border:1px solid #d1d5db;border-radius:8px;font-size:.85rem;box-sizing:border-box;"></div>';
    html+='<div style="flex:1;"><label style="font-size:.72rem;font-weight:600;color:#6b7280;display:block;margin-bottom:2px;">To</label><input type="number" name="cp_max[]" style="width:100%;padding:8px 10px;border:1px solid #d1d5db;border-radius:8px;font-size:.85rem;box-sizing:border-box;"></div>';
    html+='<div style="flex:1;"><label style="font-size:.72rem;font-weight:600;color:#6b7280;display:block;margin-bottom:2px;">%</label><input type="number" step="0.5" name="cp_percentage[]" style="width:100%;padding:8px 10px;border:1px solid #d1d5db;border-radius:8px;font-size:.85rem;box-sizing:border-box;"></div>';
    html+='<button onclick="this.closest(\'.cp-slab-row\').remove()" style="margin-top:16px;background:#fee2e2;color:#b91c1c;border:none;border-radius:6px;padding:6px 10px;cursor:pointer;font-size:.8rem;"><svg width="11" height="11" fill="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg></button>';
    html+='</div>';
    document.getElementById('cpSlabRows').insertAdjacentHTML('beforeend',html);
}
function saveCpSlabs(){
    var mins=[],maxs=[],pcts=[];
    document.querySelectorAll('#cpSlabRows input[name="cp_min[]"]').forEach(function(e){mins.push(e.value);});
    document.querySelectorAll('#cpSlabRows input[name="cp_max[]"]').forEach(function(e){maxs.push(e.value);});
    document.querySelectorAll('#cpSlabRows input[name="cp_percentage[]"]').forEach(function(e){pcts.push(e.value);});
    $.post('/admin/referrals/cp-slabs',{_token:csrf,min:mins,max:maxs,percentage:pcts},function(){
        var m=document.getElementById('cpSlabMsg');m.style.display='block';m.style.background='#f5f3ff';m.style.color='#6d28d9';m.textContent='CP slabs saved successfully!';
        setTimeout(function(){m.style.display='none';},3000);
    }).fail(function(xhr){
        var m=document.getElementById('cpSlabMsg');m.style.display='block';m.style.background='#fee2e2';m.style.color='#b91c1c';m.textContent=xhr.responseJSON?.error||'Error saving CP slabs';
    });
}
function addSlabRow(){
    var html='<div class="slab-row" style="display:flex;gap:12px;align-items:center;margin-bottom:10px;">';
    html+='<div style="flex:1;"><label style="font-size:.72rem;font-weight:600;color:#6b7280;display:block;margin-bottom:2px;">From</label><input type="number" name="min[]" style="width:100%;padding:8px 10px;border:1px solid #d1d5db;border-radius:8px;font-size:.85rem;box-sizing:border-box;"></div>';
    html+='<div style="flex:1;"><label style="font-size:.72rem;font-weight:600;color:#6b7280;display:block;margin-bottom:2px;">To</label><input type="number" name="max[]" style="width:100%;padding:8px 10px;border:1px solid #d1d5db;border-radius:8px;font-size:.85rem;box-sizing:border-box;"></div>';
    html+='<div style="flex:1;"><label style="font-size:.72rem;font-weight:600;color:#6b7280;display:block;margin-bottom:2px;">%</label><input type="number" step="0.5" name="percentage[]" style="width:100%;padding:8px 10px;border:1px solid #d1d5db;border-radius:8px;font-size:.85rem;box-sizing:border-box;"></div>';
    html+='<button onclick="this.closest(\'.slab-row\').remove()" style="margin-top:16px;background:#fee2e2;color:#b91c1c;border:none;border-radius:6px;padding:6px 10px;cursor:pointer;font-size:.8rem;"><svg width="11" height="11" fill="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg></button>';
    html+='</div>';
    document.getElementById('slabRows').insertAdjacentHTML('beforeend',html);
}
function saveSlabs(){
    var mins=[],maxs=[],pcts=[];
    document.querySelectorAll('#slabRows input[name="min[]"]').forEach(function(e){mins.push(e.value);});
    document.querySelectorAll('#slabRows input[name="max[]"]').forEach(function(e){maxs.push(e.value);});
    document.querySelectorAll('#slabRows input[name="percentage[]"]').forEach(function(e){pcts.push(e.value);});
    $.post('/admin/referrals/slabs',{_token:csrf,min:mins,max:maxs,percentage:pcts},function(){
        var m=document.getElementById('slabMsg');m.style.display='block';m.style.background='#ecfdf5';m.style.color='#065f46';m.textContent='Slabs saved successfully!';
        setTimeout(function(){m.style.display='none';},3000);
    }).fail(function(xhr){
        var m=document.getElementById('slabMsg');m.style.display='block';m.style.background='#fee2e2';m.style.color='#b91c1c';m.textContent=xhr.responseJSON?.error||'Error saving slabs';
    });
}
</script>
@endsection
