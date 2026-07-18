@extends('layouts.adminLayout')
@section('page_title', 'Referrals & Cashback')

@section('content')
<div class="container-fluid">

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h5 class="text-muted mb-1" style="font-size:.75rem;text-transform:uppercase;">Total Leads</h5>
                    <h3 class="fw-bold mb-0">{{ $leads->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h5 class="text-muted mb-1" style="font-size:.75rem;text-transform:uppercase;">Active Codes</h5>
                    <h3 class="fw-bold mb-0">{{ $codes->where('is_active', true)->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h5 class="text-muted mb-1" style="font-size:.75rem;text-transform:uppercase;">Pending Cashback</h5>
                    <h3 class="fw-bold mb-0 text-warning">{{ $leads->where('status','payment_done')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h5 class="text-muted mb-1" style="font-size:.75rem;text-transform:uppercase;">Total Paid</h5>
                    <h3 class="fw-bold mb-0 text-success">₹{{ number_format($leads->flatMap(fn($l)=>$l->cashback?collect([$l->cashback]):collect())->where('status','paid')->sum('cashback_amount')) }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <ul class="nav nav-tabs mb-3" id="refTabs">
        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#leadsTab">Referral Leads</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#codesTab">Referral Codes</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#cashbackTab">Cashback</a></li>
    </ul>

    <div class="tab-content">

        {{-- LEADS TAB --}}
        <div class="tab-pane fade show active" id="leadsTab">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size:.85rem;">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th><th>Referred Person</th><th>Phone</th><th>City</th><th>System</th><th>Referred By</th><th>Status</th><th>Date</th><th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($leads as $lead)
                                <tr>
                                    <td>{{ $lead->id }}</td>
                                    <td><strong>{{ $lead->name }}</strong><br><small class="text-muted">{{ $lead->email }}</small></td>
                                    <td>{{ $lead->phone }}</td>
                                    <td>{{ $lead->city ?? '-' }}</td>
                                    <td>{{ $lead->system_size ?? '-' }}</td>
                                    <td>{{ $lead->referrer->name ?? 'N/A' }}</td>
                                    <td>
                                        @php $colors = ['pending'=>'secondary','contacted'=>'info','installed'=>'primary','payment_done'=>'warning','cashback_approved'=>'success','rejected'=>'danger']; @endphp
                                        <span class="badge bg-{{ $colors[$lead->status] ?? 'secondary' }}">{{ ucwords(str_replace('_',' ',$lead->status)) }}</span>
                                    </td>
                                    <td>{{ $lead->created_at->format('d M Y') }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary update-status-btn" data-id="{{ $lead->id }}" data-status="{{ $lead->status }}" data-remarks="{{ $lead->admin_remarks }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @if($lead->status == 'payment_done' && !$lead->cashback)
                                        <button class="btn btn-sm btn-outline-success create-cashback-btn" data-id="{{ $lead->id }}" data-name="{{ $lead->name }}">
                                            <i class="fas fa-coins"></i> Cashback
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="9" class="text-center text-muted py-4">No referral leads yet</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- CODES TAB --}}
        <div class="tab-pane fade" id="codesTab">
            <div class="card border-0 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Referral Codes</h6>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#genCodeModal"><i class="fas fa-plus me-1"></i>Generate Code</button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size:.85rem;">
                            <thead class="table-light">
                                <tr><th>User</th><th>Code</th><th>Referral Link</th><th>Leads</th><th>Status</th><th>Created</th></tr>
                            </thead>
                            <tbody>
                                @forelse($codes as $rc)
                                <tr>
                                    <td>{{ $rc->user->name ?? 'Deleted' }}</td>
                                    <td><code style="font-size:.9rem;font-weight:700;">{{ $rc->code }}</code></td>
                                    <td>
                                        <input type="text" readonly value="{{ url('/refer/'.$rc->code) }}" style="border:1px solid #dee2e6;border-radius:4px;padding:2px 8px;font-size:.78rem;width:250px;" id="link-{{ $rc->id }}">
                                        <button class="btn btn-sm btn-outline-secondary copy-link-btn" data-target="link-{{ $rc->id }}" title="Copy"><i class="fas fa-copy"></i></button>
                                    </td>
                                    <td>{{ \App\Models\ReferralLead::where('referrer_id', $rc->user_id)->count() }}</td>
                                    <td><span class="badge bg-{{ $rc->is_active ? 'success' : 'secondary' }}">{{ $rc->is_active ? 'Active' : 'Inactive' }}</span></td>
                                    <td>{{ $rc->created_at->format('d M Y') }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="text-center text-muted py-4">No referral codes generated yet</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- CASHBACK TAB --}}
        <div class="tab-pane fade" id="cashbackTab">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size:.85rem;">
                            <thead class="table-light">
                                <tr><th>#</th><th>Referrer</th><th>Lead</th><th>Deal Amt</th><th>%</th><th>Cashback</th><th>Status</th><th>Actions</th></tr>
                            </thead>
                            <tbody>
                                @php $cashbacks = \App\Models\CashbackTransaction::with(['referrer','lead'])->latest()->get(); @endphp
                                @forelse($cashbacks as $cb)
                                <tr>
                                    <td>{{ $cb->id }}</td>
                                    <td>{{ $cb->referrer->name ?? 'N/A' }}</td>
                                    <td>{{ $cb->lead->name ?? 'N/A' }}</td>
                                    <td>₹{{ number_format($cb->deal_amount) }}</td>
                                    <td>{{ $cb->cashback_percentage }}%</td>
                                    <td class="fw-bold text-success">₹{{ number_format($cb->cashback_amount) }}</td>
                                    <td>
                                        @php $cbColors = ['pending'=>'warning','approved'=>'info','paid'=>'success','rejected'=>'danger']; @endphp
                                        <span class="badge bg-{{ $cbColors[$cb->status] ?? 'secondary' }}">{{ ucfirst($cb->status) }}</span>
                                        @if($cb->paid_at)<br><small class="text-muted">Paid {{ $cb->paid_at->format('d M') }}</small>@endif
                                    </td>
                                    <td>
                                        @if($cb->status == 'pending')
                                        <button class="btn btn-sm btn-success approve-cb-btn" data-id="{{ $cb->id }}"><i class="fas fa-check"></i> Approve</button>
                                        @elseif($cb->status == 'approved')
                                        <button class="btn btn-sm btn-primary mark-paid-btn" data-id="{{ $cb->id }}"><i class="fas fa-money-bill"></i> Mark Paid</button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="8" class="text-center text-muted py-4">No cashback transactions yet</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Update Status Modal --}}
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Update Lead Status</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <input type="hidden" id="statusLeadId">
            <div class="mb-3">
                <label class="form-label fw-semibold">Status</label>
                <select class="form-select" id="statusSelect">
                    <option value="pending">Pending</option>
                    <option value="contacted">Contacted</option>
                    <option value="installed">Installed</option>
                    <option value="payment_done">Payment Done</option>
                    <option value="cashback_approved">Cashback Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Admin Remarks</label>
                <textarea class="form-control" id="statusRemarks" rows="2"></textarea>
            </div>
        </div>
        <div class="modal-footer"><button class="btn btn-primary" id="saveStatusBtn">Save</button></div>
    </div></div>
</div>

{{-- Create Cashback Modal --}}
<div class="modal fade" id="cashbackModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Create Cashback</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <input type="hidden" id="cbLeadId">
            <p class="mb-3">Lead: <strong id="cbLeadName"></strong></p>
            <div class="mb-3">
                <label class="form-label fw-semibold">Deal Amount (₹)</label>
                <input type="number" class="form-control" id="cbDealAmount" placeholder="Total deal value">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Cashback Slab (%)</label>
                <select class="form-select" id="cbPercentage">
                    <option value="5">5%</option>
                    <option value="6">6%</option>
                    <option value="7">7%</option>
                    <option value="8">8%</option>
                    <option value="10">10%</option>
                </select>
            </div>
            <div class="alert alert-info py-2" id="cbPreview" style="display:none;">
                Cashback: <strong id="cbPreviewAmt">₹0</strong>
            </div>
        </div>
        <div class="modal-footer"><button class="btn btn-success" id="saveCashbackBtn">Create Cashback</button></div>
    </div></div>
</div>

{{-- Mark Paid Modal --}}
<div class="modal fade" id="paidModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Mark as Paid</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <input type="hidden" id="paidCbId">
            <div class="mb-3">
                <label class="form-label fw-semibold">Payment Mode</label>
                <select class="form-select" id="paidMode">
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="upi">UPI</option>
                    <option value="cash">Cash</option>
                    <option value="cheque">Cheque</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Transaction Reference</label>
                <input type="text" class="form-control" id="paidRef" placeholder="UTR / Reference number">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Remarks</label>
                <textarea class="form-control" id="paidRemarks" rows="2"></textarea>
            </div>
        </div>
        <div class="modal-footer"><button class="btn btn-primary" id="savePaidBtn">Confirm Payment</button></div>
    </div></div>
</div>

{{-- Generate Code Modal --}}
<div class="modal fade" id="genCodeModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Generate Referral Code</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="mb-3">
                <label class="form-label fw-semibold">Select User</label>
                <select class="form-select" id="genUserId">
                    <option value="">Choose a user...</option>
                    @foreach(\App\Models\User::whereIn('role_id',[3,4])->whereDoesntHave('referralCode')->orderBy('name')->get() as $u)
                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                    @endforeach
                </select>
            </div>
            <div id="genResult" style="display:none;" class="alert alert-success"></div>
        </div>
        <div class="modal-footer"><button class="btn btn-primary" id="genCodeBtn">Generate</button></div>
    </div></div>
</div>
@endsection

@section('js')
<script>
var csrf = '{{ csrf_token() }}';

// Update Status
$('.update-status-btn').click(function(){
    $('#statusLeadId').val($(this).data('id'));
    $('#statusSelect').val($(this).data('status'));
    $('#statusRemarks').val($(this).data('remarks'));
    new bootstrap.Modal('#statusModal').show();
});
$('#saveStatusBtn').click(function(){
    var id = $('#statusLeadId').val();
    $.post('/admin/referrals/leads/'+id+'/status', {_token:csrf, status:$('#statusSelect').val(), admin_remarks:$('#statusRemarks').val()}, function(){ location.reload(); });
});

// Create Cashback
$('.create-cashback-btn').click(function(){
    $('#cbLeadId').val($(this).data('id'));
    $('#cbLeadName').text($(this).data('name'));
    $('#cbDealAmount').val('');
    $('#cbPreview').hide();
    new bootstrap.Modal('#cashbackModal').show();
});
$('#cbDealAmount, #cbPercentage').on('input change', function(){
    var amt = parseFloat($('#cbDealAmount').val()) || 0;
    var pct = parseFloat($('#cbPercentage').val()) || 0;
    if(amt > 0){ $('#cbPreview').show(); $('#cbPreviewAmt').text('₹' + Math.round(amt*pct/100)); }
});
$('#saveCashbackBtn').click(function(){
    var id = $('#cbLeadId').val();
    $.post('/admin/referrals/leads/'+id+'/cashback', {_token:csrf, deal_amount:$('#cbDealAmount').val(), cashback_percentage:$('#cbPercentage').val()}, function(){ location.reload(); });
});

// Approve Cashback
$('.approve-cb-btn').click(function(){
    var id = $(this).data('id');
    Swal.fire({title:'Approve Cashback?',icon:'question',showCancelButton:true,confirmButtonText:'Yes, Approve'}).then(function(r){
        if(r.isConfirmed) $.post('/admin/referrals/cashback/'+id+'/approve', {_token:csrf}, function(){ location.reload(); });
    });
});

// Mark Paid
$('.mark-paid-btn').click(function(){
    $('#paidCbId').val($(this).data('id'));
    new bootstrap.Modal('#paidModal').show();
});
$('#savePaidBtn').click(function(){
    var id = $('#paidCbId').val();
    $.post('/admin/referrals/cashback/'+id+'/paid', {_token:csrf, payment_mode:$('#paidMode').val(), transaction_reference:$('#paidRef').val(), admin_remarks:$('#paidRemarks').val()}, function(){ location.reload(); });
});

// Generate Code
$('#genCodeBtn').click(function(){
    var uid = $('#genUserId').val();
    if(!uid){ alert('Select a user'); return; }
    $.post('/admin/referrals/generate-code/'+uid, {_token:csrf}, function(res){
        $('#genResult').show().html('Code generated: <strong>'+res.code+'</strong><br>Link: '+location.origin+'/refer/'+res.code);
        $('#genCodeBtn').prop('disabled',true);
    }).fail(function(xhr){ alert(xhr.responseJSON?.error || 'Error'); });
});

// Copy link
$('.copy-link-btn').click(function(){
    var input = document.getElementById($(this).data('target'));
    input.select();
    document.execCommand('copy');
    $(this).html('<i class="fas fa-check"></i>');
    var btn = $(this);
    setTimeout(function(){ btn.html('<i class="fas fa-copy"></i>'); }, 1500);
});
</script>
@endsection
