<?php

namespace App\Http\Controllers;

use App\Models\CashbackTransaction;
use App\Models\ReferralCode;
use App\Models\ReferralLead;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReferralController extends Controller
{
    private function getSlabConfig()
    {
        $path = storage_path('app/referral_slabs.json');
        if (file_exists($path)) {
            $data = json_decode(file_get_contents($path), true);
            if (is_array($data) && !empty($data)) return $data;
        }
        return [
            ['min' => 1, 'max' => 3, 'percentage' => 5],
            ['min' => 4, 'max' => 7, 'percentage' => 6],
            ['min' => 8, 'max' => 12, 'percentage' => 7],
            ['min' => 13, 'max' => 999, 'percentage' => 8],
        ];
    }

    private function saveSlabConfig(array $slabs)
    {
        file_put_contents(storage_path('app/referral_slabs.json'), json_encode($slabs));
    }

    private function getSlabPercentage($referrerId)
    {
        $successCount = CashbackTransaction::where('referrer_id', $referrerId)
            ->whereIn('status', ['pending', 'approved', 'paid'])->count();
        $nextCount = $successCount + 1;
        $slabs = $this->getSlabConfig();
        foreach ($slabs as $slab) {
            if ($nextCount >= $slab['min'] && $nextCount <= $slab['max']) {
                return $slab['percentage'];
            }
        }
        return $slabs[count($slabs) - 1]['percentage'] ?? 5;
    }

    // ─── ADMIN ───

    public function adminIndex()
    {
        abort_unless(auth()->user()->hasAdminPermission('referrals'), 403);
        $leads = ReferralLead::with(['referrer', 'cashback'])->latest()->get();
        $codes = ReferralCode::with('user')->latest()->get();
        $slabs = $this->getSlabConfig();
        return view('Admin.referrals.index', compact('leads', 'codes', 'slabs'));
    }

    public function saveSlabs(Request $request)
    {
        abort_unless(auth()->user()->hasAdminPermission('referrals'), 403);
        $slabs = [];
        $mins = $request->input('min', []);
        $maxs = $request->input('max', []);
        $pcts = $request->input('percentage', []);
        for ($i = 0; $i < count($mins); $i++) {
            if (isset($mins[$i], $maxs[$i], $pcts[$i]) && $mins[$i] !== '' && $maxs[$i] !== '' && $pcts[$i] !== '') {
                $slabs[] = ['min' => (int)$mins[$i], 'max' => (int)$maxs[$i], 'percentage' => (float)$pcts[$i]];
            }
        }
        if (empty($slabs)) return response()->json(['error' => 'At least one slab is required'], 422);
        $this->saveSlabConfig($slabs);
        return response()->json(['success' => true]);
    }

    public function getLeadSlabInfo($leadId)
    {
        abort_unless(auth()->user()->hasAdminPermission('referrals'), 403);
        $lead = ReferralLead::findOrFail($leadId);
        $percentage = $this->getSlabPercentage($lead->referrer_id);
        $successCount = CashbackTransaction::where('referrer_id', $lead->referrer_id)
            ->whereIn('status', ['pending', 'approved', 'paid'])->count();
        return response()->json(['percentage' => $percentage, 'successful_referrals' => $successCount]);
    }

    public function updateLeadStatus(Request $request, $id)
    {
        abort_unless(auth()->user()->hasAdminPermission('referrals'), 403);
        $lead = ReferralLead::findOrFail($id);
        $lead->status = $request->status;
        $lead->admin_remarks = $request->admin_remarks;
        $lead->save();
        return response()->json(['success' => true]);
    }

    public function createCashback(Request $request, $leadId)
    {
        abort_unless(auth()->user()->hasAdminPermission('referrals'), 403);
        $lead = ReferralLead::findOrFail($leadId);

        $existing = CashbackTransaction::where('referral_lead_id', $leadId)->first();
        if ($existing) {
            return response()->json(['error' => 'Cashback already exists for this lead'], 422);
        }

        $dealAmount = $request->deal_amount;
        $percentage = $request->cashback_percentage;
        $cashbackAmount = round(($dealAmount * $percentage) / 100, 2);

        CashbackTransaction::create([
            'referrer_id' => $lead->referrer_id,
            'referral_lead_id' => $lead->id,
            'deal_amount' => $dealAmount,
            'cashback_percentage' => $percentage,
            'cashback_amount' => $cashbackAmount,
            'status' => 'pending',
        ]);

        $lead->update(['status' => 'payment_done']);
        return response()->json(['success' => true, 'cashback_amount' => $cashbackAmount]);
    }

    public function approveCashback($id)
    {
        abort_unless(auth()->user()->hasAdminPermission('referrals'), 403);
        $txn = CashbackTransaction::findOrFail($id);
        $txn->update(['status' => 'approved', 'approved_at' => now()]);
        $txn->lead->update(['status' => 'cashback_approved']);
        return response()->json(['success' => true]);
    }

    public function markCashbackPaid(Request $request, $id)
    {
        abort_unless(auth()->user()->hasAdminPermission('referrals'), 403);
        $txn = CashbackTransaction::findOrFail($id);
        $txn->update([
            'status' => 'paid',
            'paid_at' => now(),
            'payment_mode' => $request->payment_mode,
            'transaction_reference' => $request->transaction_reference,
            'admin_remarks' => $request->admin_remarks,
        ]);
        return response()->json(['success' => true]);
    }

    public function generateCode($userId)
    {
        abort_unless(auth()->user()->hasAdminPermission('referrals'), 403);
        $user = User::findOrFail($userId);

        $existing = ReferralCode::where('user_id', $userId)->first();
        if ($existing) {
            return response()->json(['error' => 'User already has a referral code', 'code' => $existing->code], 422);
        }

        $code = strtoupper(Str::substr(preg_replace('/[^a-zA-Z]/', '', $user->name), 0, 4)) . rand(1000, 9999);
        while (ReferralCode::where('code', $code)->exists()) {
            $code = strtoupper(Str::substr(preg_replace('/[^a-zA-Z]/', '', $user->name), 0, 4)) . rand(1000, 9999);
        }

        ReferralCode::create([
            'user_id' => $userId,
            'code' => $code,
        ]);

        return response()->json(['success' => true, 'code' => $code]);
    }

    // ─── PUBLIC REFERRAL FORM ───

    public function showReferralForm($code)
    {
        $referralCode = ReferralCode::where('code', $code)->where('is_active', true)->firstOrFail();
        $referrer = $referralCode->user;
        return view('publicPages.referralForm', compact('referralCode', 'referrer'));
    }

    public function submitReferralForm(Request $request, $code)
    {
        $referralCode = ReferralCode::where('code', $code)->where('is_active', true)->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pin_code' => 'nullable|string|max:10',
            'system_size' => 'nullable|string|max:50',
            'monthly_bill' => 'nullable|string|max:50',
        ]);

        $validated['referrer_id'] = $referralCode->user_id;

        ReferralLead::create($validated);

        return redirect()->back()->with('success', 'Your details have been submitted successfully! Our team will contact you soon.');
    }

    // ─── CUSTOMER DASHBOARD (their own referral data) ───

    public function myReferrals()
    {
        $user = auth()->user();
        $referralCode = ReferralCode::where('user_id', $user->id)->first();
        $leads = ReferralLead::where('referrer_id', $user->id)->latest()->get();
        $cashbacks = CashbackTransaction::where('referrer_id', $user->id)->latest()->get();
        $totalEarned = CashbackTransaction::where('referrer_id', $user->id)->where('status', 'paid')->sum('cashback_amount');
        $pendingAmount = CashbackTransaction::where('referrer_id', $user->id)->whereIn('status', ['pending', 'approved'])->sum('cashback_amount');
        return view('user.referrals', compact('referralCode', 'leads', 'cashbacks', 'totalEarned', 'pendingAmount'));
    }
}
