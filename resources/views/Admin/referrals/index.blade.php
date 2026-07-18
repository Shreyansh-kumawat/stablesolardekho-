@extends('layouts.adminLayout')
@section('page_title', 'Referrals & Cashback')

@section('content')
<div class="p-4">

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-5 text-center">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Leads</p>
            <p class="text-2xl font-bold text-gray-800">{{ $leads->count() }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 text-center">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Active Codes</p>
            <p class="text-2xl font-bold text-gray-800">{{ $codes->where('is_active', true)->count() }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 text-center">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Pending Cashback</p>
            <p class="text-2xl font-bold text-amber-500">{{ $leads->where('status','payment_done')->count() }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 text-center">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Paid</p>
            <p class="text-2xl font-bold text-emerald-600">₹{{ number_format($leads->flatMap(fn($l)=>$l->cashback?collect([$l->cashback]):collect())->where('status','paid')->sum('cashback_amount')) }}</p>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="flex gap-1 mb-4 border-b border-gray-200">
        <button onclick="showTab('leads')" id="tab-leads" class="ref-tab px-4 py-2 text-sm font-semibold border-b-2 border-blue-600 text-blue-600">Referral Leads</button>
        <button onclick="showTab('codes')" id="tab-codes" class="ref-tab px-4 py-2 text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-gray-700">Referral Codes</button>
        <button onclick="showTab('cashback')" id="tab-cashback" class="ref-tab px-4 py-2 text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-gray-700">Cashback</button>
    </div>

    {{-- LEADS TAB --}}
    <div id="panel-leads" class="ref-panel">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="px-4 py-3">#</th><th class="px-4 py-3">Referred Person</th><th class="px-4 py-3">Phone</th><th class="px-4 py-3">City</th><th class="px-4 py-3">System</th><th class="px-4 py-3">Referred By</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Date</th><th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($leads as $lead)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $lead->id }}</td>
                            <td class="px-4 py-3"><span class="font-semibold">{{ $lead->name }}</span><br><span class="text-xs text-gray-400">{{ $lead->email }}</span></td>
                            <td class="px-4 py-3">{{ $lead->phone }}</td>
                            <td class="px-4 py-3">{{ $lead->city ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $lead->system_size ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $lead->referrer->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3">
                                @php $colors = ['pending'=>'bg-gray-100 text-gray-600','contacted'=>'bg-blue-100 text-blue-700','installed'=>'bg-purple-100 text-purple-700','payment_done'=>'bg-amber-100 text-amber-700','cashback_approved'=>'bg-emerald-100 text-emerald-700','rejected'=>'bg-red-100 text-red-700']; @endphp
                                <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold {{ $colors[$lead->status] ?? 'bg-gray-100 text-gray-600' }}">{{ ucwords(str_replace('_',' ',$lead->status)) }}</span>
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-500">{{ $lead->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-3 flex gap-1">
                                <button class="update-status-btn px-2 py-1 text-xs bg-blue-50 text-blue-600 rounded hover:bg-blue-100 font-semibold" data-id="{{ $lead->id }}" data-status="{{ $lead->status }}" data-remarks="{{ $lead->admin_remarks }}"><i class="fas fa-edit"></i></button>
                                @if($lead->status == 'payment_done' && !$lead->cashback)
                                <button class="create-cashback-btn px-2 py-1 text-xs bg-emerald-50 text-emerald-600 rounded hover:bg-emerald-100 font-semibold" data-id="{{ $lead->id }}" data-name="{{ $lead->name }}"><i class="fas fa-coins"></i> Cashback</button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="9" class="px-4 py-8 text-center text-gray-400">No referral leads yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- CODES TAB --}}
    <div id="panel-codes" class="ref-panel hidden">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100">
                <h3 class="font-semibold text-gray-700">Referral Codes</h3>
                <button onclick="document.getElementById('genCodeModal').classList.remove('hidden')" class="px-3 py-1.5 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700"><i class="fas fa-plus mr-1"></i>Generate Code</button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="px-4 py-3">User</th><th class="px-4 py-3">Code</th><th class="px-4 py-3">Referral Link</th><th class="px-4 py-3">Leads</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Created</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($codes as $rc)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $rc->user->name ?? 'Deleted' }}</td>
                            <td class="px-4 py-3"><code class="text-sm font-bold text-orange-600 bg-orange-50 px-2 py-0.5 rounded">{{ $rc->code }}</code></td>
                            <td class="px-4 py-3">
                                <div class="flex gap-1 items-center">
                                    <input type="text" readonly value="{{ url('/refer/'.$rc->code) }}" class="text-xs border border-gray-200 rounded px-2 py-1 w-56 bg-gray-50" id="link-{{ $rc->id }}">
                                    <button class="copy-link-btn px-2 py-1 text-xs bg-gray-100 rounded hover:bg-gray-200" data-target="link-{{ $rc->id }}"><i class="fas fa-copy"></i></button>
                                </div>
                            </td>
                            <td class="px-4 py-3">{{ \App\Models\ReferralLead::where('referrer_id', $rc->user_id)->count() }}</td>
                            <td class="px-4 py-3"><span class="inline-block px-2 py-1 rounded-full text-xs font-semibold {{ $rc->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">{{ $rc->is_active ? 'Active' : 'Inactive' }}</span></td>
                            <td class="px-4 py-3 text-xs text-gray-500">{{ $rc->created_at->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">No referral codes generated yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- CASHBACK TAB --}}
    <div id="panel-cashback" class="ref-panel hidden">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="px-4 py-3">#</th><th class="px-4 py-3">Referrer</th><th class="px-4 py-3">Lead</th><th class="px-4 py-3">Deal Amt</th><th class="px-4 py-3">%</th><th class="px-4 py-3">Cashback</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @php $cashbacks = \App\Models\CashbackTransaction::with(['referrer','lead'])->latest()->get(); @endphp
                        @forelse($cashbacks as $cb)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $cb->id }}</td>
                            <td class="px-4 py-3">{{ $cb->referrer->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ $cb->lead->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3">₹{{ number_format($cb->deal_amount) }}</td>
                            <td class="px-4 py-3">{{ $cb->cashback_percentage }}%</td>
                            <td class="px-4 py-3 font-bold text-emerald-600">₹{{ number_format($cb->cashback_amount) }}</td>
                            <td class="px-4 py-3">
                                @php $cbColors = ['pending'=>'bg-amber-100 text-amber-700','approved'=>'bg-blue-100 text-blue-700','paid'=>'bg-emerald-100 text-emerald-700','rejected'=>'bg-red-100 text-red-700']; @endphp
                                <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold {{ $cbColors[$cb->status] ?? 'bg-gray-100' }}">{{ ucfirst($cb->status) }}</span>
                                @if($cb->paid_at)<br><span class="text-xs text-gray-400">Paid {{ $cb->paid_at->format('d M') }}</span>@endif
                            </td>
                            <td class="px-4 py-3">
                                @if($cb->status == 'pending')
                                <button class="approve-cb-btn px-2 py-1 text-xs bg-emerald-600 text-white rounded hover:bg-emerald-700 font-semibold" data-id="{{ $cb->id }}"><i class="fas fa-check"></i> Approve</button>
                                @elseif($cb->status == 'approved')
                                <button class="mark-paid-btn px-2 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700 font-semibold" data-id="{{ $cb->id }}"><i class="fas fa-money-bill"></i> Mark Paid</button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="px-4 py-8 text-center text-gray-400">No cashback transactions yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODALS (inline styled, no Bootstrap CSS needed) --}}
<style>
.ref-modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:999;display:flex;align-items:center;justify-content:center;}
.ref-modal{background:#fff;border-radius:12px;width:90%;max-width:440px;box-shadow:0 20px 60px rgba(0,0,0,.3);}
.ref-modal-header{padding:16px 20px;border-bottom:1px solid #e5e7eb;display:flex;justify-content:space-between;align-items:center;}
.ref-modal-header h3{font-size:1rem;font-weight:700;margin:0;}
.ref-modal-body{padding:20px;}
.ref-modal-footer{padding:12px 20px;border-top:1px solid #e5e7eb;text-align:right;}
.ref-modal label{display:block;font-size:.8rem;font-weight:600;color:#374151;margin-bottom:4px;}
.ref-modal select,.ref-modal input,.ref-modal textarea{width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:8px;font-size:.9rem;margin-bottom:12px;}
.ref-modal .btn-primary{padding:8px 20px;background:#2563eb;color:#fff;border:none;border-radius:8px;font-weight:600;cursor:pointer;}
.ref-modal .btn-success{padding:8px 20px;background:#059669;color:#fff;border:none;border-radius:8px;font-weight:600;cursor:pointer;}
.ref-modal .btn-close{background:none;border:none;font-size:1.2rem;cursor:pointer;color:#9ca3af;}
</style>

{{-- Update Status Modal --}}
<div id="statusModal" class="ref-modal-overlay hidden">
<div class="ref-modal">
    <div class="ref-modal-header"><h3>Update Lead Status</h3><button class="btn-close" onclick="document.getElementById('statusModal').classList.add('hidden')">&times;</button></div>
    <div class="ref-modal-body">
        <input type="hidden" id="statusLeadId">
        <label>Status</label>
        <select id="statusSelect">
            <option value="pending">Pending</option><option value="contacted">Contacted</option><option value="installed">Installed</option><option value="payment_done">Payment Done</option><option value="cashback_approved">Cashback Approved</option><option value="rejected">Rejected</option>
        </select>
        <label>Admin Remarks</label>
        <textarea id="statusRemarks" rows="2"></textarea>
    </div>
    <div class="ref-modal-footer"><button class="btn-primary" id="saveStatusBtn">Save</button></div>
</div>
</div>

{{-- Create Cashback Modal --}}
<div id="cashbackModal" class="ref-modal-overlay hidden">
<div class="ref-modal">
    <div class="ref-modal-header"><h3>Create Cashback</h3><button class="btn-close" onclick="document.getElementById('cashbackModal').classList.add('hidden')">&times;</button></div>
    <div class="ref-modal-body">
        <input type="hidden" id="cbLeadId">
        <p style="margin-bottom:12px;">Lead: <strong id="cbLeadName"></strong></p>
        <label>Deal Amount (₹)</label>
        <input type="number" id="cbDealAmount" placeholder="Total deal value">
        <label>Cashback Slab (%)</label>
        <select id="cbPercentage">
            <option value="5">5%</option><option value="6">6%</option><option value="7">7%</option><option value="8">8%</option><option value="10">10%</option>
        </select>
        <div id="cbPreview" style="display:none;background:#ecfdf5;border:1px solid #6ee7b7;border-radius:8px;padding:10px 14px;color:#065f46;font-weight:600;">Cashback: <span id="cbPreviewAmt">₹0</span></div>
    </div>
    <div class="ref-modal-footer"><button class="btn-success" id="saveCashbackBtn">Create Cashback</button></div>
</div>
</div>

{{-- Mark Paid Modal --}}
<div id="paidModal" class="ref-modal-overlay hidden">
<div class="ref-modal">
    <div class="ref-modal-header"><h3>Mark as Paid</h3><button class="btn-close" onclick="document.getElementById('paidModal').classList.add('hidden')">&times;</button></div>
    <div class="ref-modal-body">
        <input type="hidden" id="paidCbId">
        <label>Payment Mode</label>
        <select id="paidMode"><option value="bank_transfer">Bank Transfer</option><option value="upi">UPI</option><option value="cash">Cash</option><option value="cheque">Cheque</option></select>
        <label>Transaction Reference</label>
        <input type="text" id="paidRef" placeholder="UTR / Reference number">
        <label>Remarks</label>
        <textarea id="paidRemarks" rows="2"></textarea>
    </div>
    <div class="ref-modal-footer"><button class="btn-primary" id="savePaidBtn">Confirm Payment</button></div>
</div>
</div>

{{-- Generate Code Modal --}}
<div id="genCodeModal" class="ref-modal-overlay hidden">
<div class="ref-modal">
    <div class="ref-modal-header"><h3>Generate Referral Code</h3><button class="btn-close" onclick="document.getElementById('genCodeModal').classList.add('hidden')">&times;</button></div>
    <div class="ref-modal-body">
        <label>Select User</label>
        <select id="genUserId">
            <option value="">Choose a user...</option>
            @foreach(\App\Models\User::whereIn('role_id',[3,4])->whereDoesntHave('referralCode')->orderBy('name')->get() as $u)
            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
            @endforeach
        </select>
        <div id="genResult" style="display:none;background:#ecfdf5;border:1px solid #6ee7b7;border-radius:8px;padding:10px 14px;color:#065f46;"></div>
    </div>
    <div class="ref-modal-footer"><button class="btn-primary" id="genCodeBtn">Generate</button></div>
</div>
</div>
@endsection

@section('js')
<script>
function showTab(name){
    document.querySelectorAll('.ref-panel').forEach(p=>p.classList.add('hidden'));
    document.querySelectorAll('.ref-tab').forEach(t=>{t.classList.remove('border-blue-600','text-blue-600');t.classList.add('border-transparent','text-gray-500');});
    document.getElementById('panel-'+name).classList.remove('hidden');
    var tab=document.getElementById('tab-'+name);tab.classList.add('border-blue-600','text-blue-600');tab.classList.remove('border-transparent','text-gray-500');
}
var csrf='{{ csrf_token() }}';

$('.update-status-btn').click(function(){
    $('#statusLeadId').val($(this).data('id'));$('#statusSelect').val($(this).data('status'));$('#statusRemarks').val($(this).data('remarks'));
    document.getElementById('statusModal').classList.remove('hidden');
});
$('#saveStatusBtn').click(function(){
    $.post('/admin/referrals/leads/'+$('#statusLeadId').val()+'/status',{_token:csrf,status:$('#statusSelect').val(),admin_remarks:$('#statusRemarks').val()},function(){location.reload();});
});

$('.create-cashback-btn').click(function(){
    $('#cbLeadId').val($(this).data('id'));$('#cbLeadName').text($(this).data('name'));$('#cbDealAmount').val('');$('#cbPreview').hide();
    document.getElementById('cashbackModal').classList.remove('hidden');
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
    $('#paidCbId').val($(this).data('id'));document.getElementById('paidModal').classList.remove('hidden');
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
