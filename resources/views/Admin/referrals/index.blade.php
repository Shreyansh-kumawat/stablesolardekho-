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
    </div>

    {{-- LEADS TAB --}}
    <div id="panel-leads" class="ref-panel">
        <div style="background:#fff;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.08);overflow:hidden;">
            <div style="overflow-x:auto;">
                <table style="width:100%;font-size:.85rem;border-collapse:collapse;">
                    <thead>
                        <tr style="background:#f8fafc;text-align:left;">
                            <th style="padding:12px 16px;font-size:.7rem;font-weight:700;color:#6b7280;text-transform:uppercase;">#</th>
                            <th style="padding:12px 16px;font-size:.7rem;font-weight:700;color:#6b7280;text-transform:uppercase;">Referred Person</th>
                            <th style="padding:12px 16px;font-size:.7rem;font-weight:700;color:#6b7280;text-transform:uppercase;">Phone</th>
                            <th style="padding:12px 16px;font-size:.7rem;font-weight:700;color:#6b7280;text-transform:uppercase;">City</th>
                            <th style="padding:12px 16px;font-size:.7rem;font-weight:700;color:#6b7280;text-transform:uppercase;">System</th>
                            <th style="padding:12px 16px;font-size:.7rem;font-weight:700;color:#6b7280;text-transform:uppercase;">Referred By</th>
                            <th style="padding:12px 16px;font-size:.7rem;font-weight:700;color:#6b7280;text-transform:uppercase;">Status</th>
                            <th style="padding:12px 16px;font-size:.7rem;font-weight:700;color:#6b7280;text-transform:uppercase;">Date</th>
                            <th style="padding:12px 16px;font-size:.7rem;font-weight:700;color:#6b7280;text-transform:uppercase;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leads as $lead)
                        @php $statusColors = ['pending'=>['#f3f4f6','#4b5563'],'contacted'=>['#dbeafe','#1d4ed8'],'installed'=>['#ede9fe','#6d28d9'],'payment_done'=>['#fef3c7','#b45309'],'cashback_approved'=>['#d1fae5','#047857'],'rejected'=>['#fee2e2','#b91c1c']]; $sc=$statusColors[$lead->status]??['#f3f4f6','#4b5563']; @endphp
                        <tr style="border-top:1px solid #f1f5f9;">
                            <td style="padding:12px 16px;">{{ $lead->id }}</td>
                            <td style="padding:12px 16px;"><strong>{{ $lead->name }}</strong><br><span style="color:#9ca3af;font-size:.75rem;">{{ $lead->email }}</span></td>
                            <td style="padding:12px 16px;">{{ $lead->phone }}</td>
                            <td style="padding:12px 16px;">{{ $lead->city ?? '-' }}</td>
                            <td style="padding:12px 16px;">{{ $lead->system_size ?? '-' }}</td>
                            <td style="padding:12px 16px;">{{ $lead->referrer->name ?? 'N/A' }}</td>
                            <td style="padding:12px 16px;"><span style="display:inline-block;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:600;background:{{ $sc[0] }};color:{{ $sc[1] }};">{{ ucwords(str_replace('_',' ',$lead->status)) }}</span></td>
                            <td style="padding:12px 16px;font-size:.78rem;color:#6b7280;">{{ $lead->created_at->format('d M Y') }}</td>
                            <td style="padding:12px 16px;">
                                <button class="update-status-btn" data-id="{{ $lead->id }}" data-status="{{ $lead->status }}" data-remarks="{{ $lead->admin_remarks }}" style="padding:4px 10px;font-size:.75rem;background:#eff6ff;color:#2563eb;border:none;border-radius:4px;cursor:pointer;font-weight:600;"><i class="fas fa-edit"></i></button>
                                @if($lead->status == 'payment_done' && !$lead->cashback)
                                <button class="create-cashback-btn" data-id="{{ $lead->id }}" data-name="{{ $lead->name }}" style="padding:4px 10px;font-size:.75rem;background:#ecfdf5;color:#059669;border:none;border-radius:4px;cursor:pointer;font-weight:600;"><i class="fas fa-coins"></i> Cashback</button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="9" style="padding:32px;text-align:center;color:#9ca3af;">No referral leads yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- CODES TAB --}}
    <div id="panel-codes" class="ref-panel" style="display:none;">
        <div style="background:#fff;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.08);overflow:hidden;">
            <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-bottom:1px solid #f1f5f9;">
                <h3 style="font-weight:700;color:#374151;margin:0;font-size:.95rem;">Referral Codes</h3>
                <button onclick="document.getElementById('genCodeModal').classList.add('active')" style="padding:6px 14px;background:#2563eb;color:#fff;font-size:.78rem;font-weight:600;border:none;border-radius:8px;cursor:pointer;"><i class="fas fa-plus" style="margin-right:4px;"></i>Generate Code</button>
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
                            <td style="padding:12px 16px;">{{ $rc->user->name ?? 'Deleted' }}</td>
                            <td style="padding:12px 16px;"><code style="font-size:.9rem;font-weight:700;color:#ea580c;background:#fff7ed;padding:2px 8px;border-radius:4px;">{{ $rc->code }}</code></td>
                            <td style="padding:12px 16px;">
                                <div style="display:flex;gap:6px;align-items:center;">
                                    <input type="text" readonly value="{{ url('/refer/'.$rc->code) }}" id="link-{{ $rc->id }}" style="font-size:.75rem;border:1px solid #e5e7eb;border-radius:4px;padding:4px 8px;width:220px;background:#f9fafb;">
                                    <button class="copy-link-btn" data-target="link-{{ $rc->id }}" style="padding:4px 8px;font-size:.75rem;background:#f3f4f6;border:1px solid #e5e7eb;border-radius:4px;cursor:pointer;"><i class="fas fa-copy"></i></button>
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
                            <td style="padding:12px 16px;">{{ $cb->referrer->name ?? 'N/A' }}</td>
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
                                <button class="approve-cb-btn" data-id="{{ $cb->id }}" style="padding:4px 12px;font-size:.75rem;background:#059669;color:#fff;border:none;border-radius:4px;cursor:pointer;font-weight:600;"><i class="fas fa-check"></i> Approve</button>
                                @elseif($cb->status == 'approved')
                                <button class="mark-paid-btn" data-id="{{ $cb->id }}" style="padding:4px 12px;font-size:.75rem;background:#2563eb;color:#fff;border:none;border-radius:4px;cursor:pointer;font-weight:600;"><i class="fas fa-money-bill"></i> Mark Paid</button>
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
        <label>Deal Amount (₹)</label>
        <input type="number" id="cbDealAmount" placeholder="Total deal value">
        <label>Cashback Slab (%)</label>
        <select id="cbPercentage"><option value="5">5%</option><option value="6">6%</option><option value="7">7%</option><option value="8">8%</option><option value="10">10%</option></select>
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

$('.update-status-btn').click(function(){
    $('#statusLeadId').val($(this).data('id'));$('#statusSelect').val($(this).data('status'));$('#statusRemarks').val($(this).data('remarks'));
    document.getElementById('statusModal').classList.add('active');
});
$('#saveStatusBtn').click(function(){
    $.post('/admin/referrals/leads/'+$('#statusLeadId').val()+'/status',{_token:csrf,status:$('#statusSelect').val(),admin_remarks:$('#statusRemarks').val()},function(){location.reload();});
});

$('.create-cashback-btn').click(function(){
    $('#cbLeadId').val($(this).data('id'));$('#cbLeadName').text($(this).data('name'));$('#cbDealAmount').val('');$('#cbPreview').hide();
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
    $(this).html('<i class="fas fa-check"></i>');var b=$(this);setTimeout(function(){b.html('<i class="fas fa-copy"></i>');},1500);
});
</script>
@endsection
