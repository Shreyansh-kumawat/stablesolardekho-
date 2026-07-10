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
            CpOrder::create([
                'cp_id' => Auth::user()->cp_id, // Assuming you have the channel partner ID in the session
                'order_id' => $this->orderTxnId(),
                'products' => json_encode($request->input('products')), // Store products as JSON
                'order_notes' => $request->input('remarks'),
                'status' => 'pending', // Set initial status to pending
                'order_date' => now()->format('Y-m-d'), // Set the order date to the current date and time
            ]);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return response()->json(['error' => 'Failed to submit order request: ' . $e->getMessage()], 500);
        }
        return response()->json(['message' => 'Order request submitted successfully']);
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
        $orders = CpOrder::where('cp_id', Auth::user()->cp_id)->get();
        return view('channelPartner.orders.orderReportCp', compact('orders'));
    }

     public function viewSingleOrderCp($id)
    {
        $order = CpOrder::with('channelPartner')->findOrFail($id);
        return view('channelPartner.orders.viewSingleOrderCp', compact('order'));
    }

    public function pendingOrders()
    {
        $orders = CpOrder::with(relations: 'channelPartner')->where('status', 'pending')->get();
        return view('Admin.orders.pendingOrders', compact('orders'));
    }
    public function manageOrdersAdmin()
    {
        $orders = CpOrder::with(relations: 'channelPartner')->get();
        return view('Admin.orders.manageOrderAdmin', compact('orders'));
    }

    public function viewSingleOrder($id)
    {
        $order = CpOrder::with('channelPartner')->findOrFail($id);
        return view('admin.orders.viewSIngleOrderAdmin', compact('order'));
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
        $products = Product::with(['category', 'subCategory'])->where('current_sale_price', '>', 0)->get();
        return view('channelPartner.products.productPricingCp', compact('products'));
    }

    public function customerOrderList()
    {
        abort_unless(auth()->user()->hasAdminPermission('orders'), 403);
        $orders = CustomerOrder::with('user')->latest()->get();
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
