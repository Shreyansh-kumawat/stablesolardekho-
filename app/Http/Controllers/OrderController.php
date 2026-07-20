<?php

namespace App\Http\Controllers;

use App\Models\CpOrder;
use App\Models\CustomerOrder;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function newOrderCp()
    {
        $categories = ProductCategory::all();
        return view('channelPartner.orders.newOrderCp', compact('categories'));
    }
    public function storeNewOrderRequest(Request $request)
    {
        try {
            $data = [
                'cp_id' => Auth::user()->cp_id,
                'order_id' => $this->orderTxnId(),
                'products' => $request->input('products'),
                'order_notes' => $request->input('remarks'),
                'status' => 'pending',
                'order_date' => now()->format('Y-m-d'),
                'payment_status' => 'verification_pending',
            ];

            if ($request->hasFile('payment_screenshot')) {
                $data['payment_screenshot'] = $request->file('payment_screenshot')->store('cp-payment-screenshots', 'public');
            }

            if ($request->input('payment_reference')) {
                $data['payment_reference'] = $request->input('payment_reference');
            }

            CpOrder::create($data);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to submit order: ' . $e->getMessage()], 500);
        }
        return response()->json(['message' => 'Order submitted successfully']);
    }

    public function orderTxnId()
    {
        // Generate a unique transaction ID for the order
        $txnId = 'ORDER' . time() . rand(1000, 9999);
        while (CpOrder::where('order_id', $txnId)->exists()) {
            $txnId = 'ORDER' . time() . rand(1000, 9999);
        }
        return $txnId;
    }

    public function orderReportCp()
    {
        $orders = CpOrder::where('cp_id', Auth::user()->cp_id)->orderBy('created_at', 'desc')->get();
        return view('channelPartner.orders.orderReportCp', compact('orders'));
    }

    public function cpOrderPaymentPage($id)
    {
        $order = CpOrder::where('cp_id', Auth::user()->cp_id)->findOrFail($id);
        return view('channelPartner.orders.cpOrderPayment', compact('order'));
    }

    public function uploadCpOrderPayment(Request $request, $id)
    {
        $request->validate([
            'payment_screenshot' => 'required|image|max:5120',
            'payment_reference' => 'nullable|string|max:255',
        ]);

        $order = CpOrder::where('cp_id', Auth::user()->cp_id)->findOrFail($id);

        if ($order->payment_screenshot) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($order->payment_screenshot);
        }

        $path = $request->file('payment_screenshot')->store('cp-payment-screenshots', 'public');
        $order->payment_screenshot = $path;
        $order->payment_reference = $request->payment_reference;
        $order->payment_status = 'verification_pending';
        $order->viewed_by_admin = 0;
        $order->save();

        return redirect()->route('orderReportCp')->with('success', 'Payment receipt uploaded successfully. Our team will verify it shortly.');
    }

     public function viewSingleOrderCp($id)
    {
        $order = CpOrder::with('channelPartner')->findOrFail($id);
        return view('channelPartner.orders.viewSingleOrderCp', compact('order'));
    }

    public function pendingOrders()
    {
        $orders = CpOrder::with(relations: 'channelPartner')->where('status', 'pending')->orderBy('created_at', 'desc')->get();
        CpOrder::where('viewed_by_admin', 0)->update(['viewed_by_admin' => 1]);
        try { \App\Models\AdminLastSeen::markSeen(auth()->id(), 'cp_orders'); } catch (\Exception $e) {}
        return view('Admin.orders.pendingOrders', compact('orders'));
    }
    public function manageOrdersAdmin()
    {
        $orders = CpOrder::with(relations: 'channelPartner')->orderBy('created_at', 'desc')->get();
        CpOrder::where('viewed_by_admin', 0)->update(['viewed_by_admin' => 1]);
        try { \App\Models\AdminLastSeen::markSeen(auth()->id(), 'cp_orders'); } catch (\Exception $e) {}
        return view('Admin.orders.manageOrderAdmin', compact('orders'));
    }

    public function viewSingleOrder($id)
    {
        $order = CpOrder::with('channelPartner')->findOrFail($id);
        return view('Admin.orders.viewSIngleOrderAdmin', compact('order'));
    }

    public function saveOrderPricing(Request $request)
    {
        try {
            $orderId = $request->input('order_id');
            $action = $request->input('action', 'approve');
            $productsJson = $request->input('products_json');
            $find_order = CpOrder::findOrFail($orderId);;
            $find_order->products = $productsJson;
            $find_order->status = $action == 'approve' ? 'completed' : 'rejected';
            $find_order->inQuoteSent = '1';
            $find_order->quote_amount = $request->input('grand_total');
            $find_order->quote_validity_date = $request->input('quote_validity_date');
            $find_order->quote_date = now()->format('Y-m-d');
            $find_order->quote_generated_by  = Auth::user()->id;
            $find_order->save();

            return redirect()->route('pendingOrders')->with('success', 'Order pricing saved successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error saving order pricing: ' . $e->getMessage());
        }
    }

    public function approveInventoryRequest(Request $request, $id)
    {
        $order = CpOrder::findOrFail($id);
        $order->status = 'completed';
        $order->admin_remarks = $request->input('admin_remarks');
        $order->save();

        return redirect()->route('pendingOrders')->with('success', 'Inventory request approved successfully.');
    }

    public function cancelInventoryRequest(Request $request, $id)
    {
        $order = CpOrder::findOrFail($id);
        $order->status = 'cancelled';
        $order->admin_remarks = $request->input('admin_remarks');
        $order->save();

        return redirect()->route('pendingOrders')->with('success', 'Inventory request cancelled.');
    }

    public function productPricing()
    {
        try {
            $products = Product::with(['category', 'subCategory'])->where('current_sale_price', '>', 0)->get();
        } catch (\Exception $e) {
            try {
                $products = Product::with(['category'])->where('current_sale_price', '>', 0)->get();
            } catch (\Exception $e2) {
                $products = collect();
            }
        }
        return view('channelPartner.products.productPricingCp', compact('products'));
    }

    public function approveCpPayment($id)
    {
        $order = CpOrder::findOrFail($id);
        $order->payment_status = 'paid';
        $order->status = 'completed';
        $order->save();
        return redirect()->back()->with('success', 'CP payment approved and order confirmed.');
    }

    public function rejectCpPayment($id)
    {
        $order = CpOrder::findOrFail($id);
        $order->payment_status = 'failed';
        $order->save();
        return redirect()->back()->with('success', 'CP payment rejected.');
    }

    public function customerOrderList()
    {
        abort_unless(auth()->user()->hasAdminPermission('orders'), 403);
        $orders = CustomerOrder::with('user')->latest()->get();
        CustomerOrder::where('viewed_by_admin', 0)->update(['viewed_by_admin' => 1]);
        try { \App\Models\AdminLastSeen::markSeen(auth()->id(), 'orders'); } catch (\Exception $e) {}
        return view('Admin.orders.customerOrdersList', compact('orders'));
    }

    public function viewCustomerOrder($id)
    {
        abort_unless(auth()->user()->hasAdminPermission('orders.manage'), 403);
        $order = CustomerOrder::with(['user', 'items.product'])->findOrFail($id);
        return view('Admin.orders.viewCustomerOrder', compact('order'));
    }

    public function updateCustomerOrderStatus(Request $request, $id)
    {
        abort_unless(auth()->user()->hasAdminPermission('orders.manage'), 403);
        $order = CustomerOrder::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        if ($request->status === 'delivered' && $order->user_id) {
            $existing = \App\Models\ReferralCode::where('user_id', $order->user_id)->first();
            if (!$existing) {
                $user = \App\Models\User::find($order->user_id);
                if ($user) {
                    $namePart = strtoupper(\Illuminate\Support\Str::substr(preg_replace('/[^a-zA-Z]/', '', $user->name), 0, 4));
                    $code = $namePart . rand(1000, 9999);
                    while (\App\Models\ReferralCode::where('code', $code)->exists()) {
                        $code = $namePart . rand(1000, 9999);
                    }
                    \App\Models\ReferralCode::create(['user_id' => $order->user_id, 'code' => $code]);
                }
            }
        }

        return redirect()->back()->with('success', 'Order status updated successfully');
    }

    public function approvePayment($id)
    {
        $order = CustomerOrder::with('items')->findOrFail($id);
        $order->update([
            'payment_status' => 'paid',
            'status' => 'confirmed',
        ]);

        foreach ($order->items as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                $product->quantity = max(0, $product->quantity - $item->quantity);
                $product->save();
            }
        }

        return redirect()->back()->with('success', 'Payment approved and order confirmed.');
    }

    public function rejectPayment($id)
    {
        $order = CustomerOrder::findOrFail($id);
        $order->update([
            'payment_status' => 'failed',
        ]);
        return redirect()->back()->with('success', 'Payment rejected.');
    }

    public function deleteCustomerOrder($id)
    {
        abort_unless(auth()->user()->hasAdminPermission('orders.manage'), 403);
        $order = CustomerOrder::findOrFail($id);
        $order->items()->delete();
        if ($order->payment_screenshot) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($order->payment_screenshot);
        }
        $order->delete();
        return redirect()->route('customerOrders')->with('success', 'Order #' . $order->order_number . ' deleted successfully.');
    }

    public function razorpayTransactions()
    {
        $transactions = CustomerOrder::where('payment_method', 'online')
            ->whereNotNull('razorpay_payment_id')
            ->with('user')
            ->latest()
            ->get();

        $paidTotal   = $transactions->where('payment_status', 'paid')->sum('total_amount');
        $paidCount   = $transactions->where('payment_status', 'paid')->count();
        $failedCount = $transactions->where('payment_status', 'failed')->count();

        return view('Admin.orders.razorpayTransactions', compact('transactions', 'paidTotal', 'paidCount', 'failedCount'));
    }
}
