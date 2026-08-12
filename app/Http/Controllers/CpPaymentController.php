<?php

namespace App\Http\Controllers;

use App\Models\ChannelPartner;
use App\Models\CpOrder;
use App\Models\CpPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CpPaymentController extends Controller
{
    public function adminIndex(Request $request)
    {
        $cps = ChannelPartner::where('is_active', 1)->orderBy('cp_name')->get();

        $query = CpPayment::with(['channelPartner', 'cpOrder', 'verifiedByUser', 'recordedByUser']);

        if ($request->filled('cp_id')) {
            $query->where('cp_id', $request->cp_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('from_date')) {
            $query->where('payment_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->where('payment_date', '<=', $request->to_date);
        }

        $payments = $query->orderByDesc('payment_date')->orderByDesc('id')->get();

        $stats = [
            'total' => $payments->sum('amount'),
            'verified' => $payments->where('status', 'verified')->sum('amount'),
            'pending' => $payments->where('status', 'pending')->sum('amount'),
            'rejected' => $payments->where('status', 'rejected')->sum('amount'),
        ];

        return view('Admin.payments.index', compact('payments', 'cps', 'stats'));
    }

    public function adminStore(Request $request)
    {
        $request->validate([
            'cp_id' => 'required|exists:channel_partners,id',
            'order_id_ref' => 'nullable|string|max:100',
            'amount' => 'required|numeric|min:0.01',
            'payment_mode' => 'required|string|max:50',
            'reference_number' => 'nullable|string|max:100',
            'screenshot' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
            'payment_date' => 'required|date',
            'remarks' => 'nullable|string|max:500',
        ]);

        $data = $request->only(['cp_id', 'amount', 'payment_mode', 'reference_number', 'payment_date', 'remarks']);
        $data['recorded_by'] = Auth::id();
        $data['status'] = 'pending';
        if ($request->filled('order_id_ref')) {
            $data['order_id_ref'] = $request->order_id_ref;
        }

        if ($request->hasFile('screenshot')) {
            $data['screenshot'] = $request->file('screenshot')->store('cp-payments', 'public');
        }

        CpPayment::create($data);

        return redirect()->back()->with('success', 'Payment recorded successfully.');
    }

    public function adminVerify(Request $request, $id)
    {
        $payment = CpPayment::findOrFail($id);
        $payment->update([
            'status' => $request->action === 'reject' ? 'rejected' : 'verified',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'admin_notes' => $request->admin_notes,
        ]);

        return redirect()->back()->with('success', 'Payment ' . ($request->action === 'reject' ? 'rejected' : 'verified') . '.');
    }

    public function adminDelete($id)
    {
        $payment = CpPayment::findOrFail($id);
        if ($payment->screenshot) {
            Storage::disk('public')->delete($payment->screenshot);
        }
        $payment->delete();

        return redirect()->back()->with('success', 'Payment deleted.');
    }

    public function getOrdersByCp($cpId)
    {
        $orders = CpOrder::where('cp_id', $cpId)
            ->select('id', 'order_id', 'grand_total', 'status')
            ->orderByDesc('id')
            ->get();

        return response()->json($orders);
    }

    public function cpIndex(Request $request)
    {
        $cpId = Auth::user()->cp_id;

        $query = CpPayment::with(['cpOrder', 'verifiedByUser'])->where('cp_id', $cpId);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('from_date')) {
            $query->where('payment_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->where('payment_date', '<=', $request->to_date);
        }

        $payments = $query->orderByDesc('payment_date')->orderByDesc('id')->get();

        $stats = [
            'total' => $payments->sum('amount'),
            'verified' => $payments->where('status', 'verified')->sum('amount'),
            'pending' => $payments->where('status', 'pending')->sum('amount'),
        ];

        return view('channelPartner.payments.index', compact('payments', 'stats'));
    }
}
