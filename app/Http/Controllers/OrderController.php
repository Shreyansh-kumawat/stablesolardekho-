<?php

namespace App\Http\Controllers;

use App\Models\CpMaterialLedger;
use App\Models\CpOrder;
use App\Models\CustomerOrder;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductInventory;
use App\Models\ProductInventoryTransaction;
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

            $productsArr = json_decode($request->input('products'), true);
            $grandTotal = 0;
            if (is_array($productsArr)) {
                foreach ($productsArr as $item) {
                    $prod = Product::find($item['product_id'] ?? null);
                    if ($prod) {
                        $grandTotal += ($prod->current_sale_price ?? 0) * (int) ($item['quantity'] ?? 0);
                    }
                }
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('cp_orders', 'grand_total')) {
                $data['grand_total'] = $grandTotal;
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
        $user = Auth::user();

        $cpOrders = CpOrder::where('cp_id', $user->cp_id)->get()->map(function($o) {
            return (object)[
                'id' => $o->id,
                'type' => 'cp_order',
                'order_id' => $o->order_id,
                'date' => $o->order_date,
                'amount' => $o->grand_total ?? $o->quote_amount,
                'status' => $o->status,
                'payment_status' => $o->payment_status,
                'created_at' => $o->created_at,
            ];
        });

        $customerOrders = CustomerOrder::where('user_id', $user->id)->get()->map(function($o) {
            return (object)[
                'id' => $o->id,
                'type' => 'customer_order',
                'order_id' => $o->order_number,
                'date' => $o->created_at,
                'amount' => $o->total_amount,
                'status' => $o->status,
                'payment_status' => $o->payment_status,
                'created_at' => $o->created_at,
            ];
        });

        $orders = $cpOrders->concat($customerOrders)->sortByDesc('created_at')->values();
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
        $mapCpOrder = function($o) {
            return (object)[
                'id' => $o->id,
                'type' => 'cp_order',
                'order_id' => $o->order_id,
                'cp_name' => $o->channelPartner->cp_name ?? 'N/A',
                'date' => $o->order_date,
                'items' => $this->getItemCount($o),
                'amount' => $o->grand_total ?? $o->quote_amount,
                'status' => $o->status,
                'payment_status' => $o->payment_status,
                'payment_screenshot' => $o->payment_screenshot,
                'created_at' => $o->created_at,
            ];
        };

        $pendingCp = CpOrder::with('channelPartner')->whereIn('status', ['pending', 'confirmed'])->orderByDesc('created_at')->get()->map($mapCpOrder);
        $pastCp = CpOrder::with('channelPartner')->whereIn('status', ['delivered', 'rejected', 'completed', 'cancelled'])->orderByDesc('created_at')->limit(50)->get()->map($mapCpOrder);

        $pendingOrders = $pendingCp->sortByDesc('created_at')->values();
        $pastOrders = $pastCp->sortByDesc('created_at')->values();

        CpOrder::where('viewed_by_admin', 0)->update(['viewed_by_admin' => 1]);
        try { \App\Models\AdminLastSeen::markSeen(auth()->id(), 'cp_orders'); } catch (\Exception $e) {}
        return view('Admin.orders.pendingOrders', compact('pendingOrders', 'pastOrders'));
    }

    private function getItemCount($cpOrder)
    {
        $prods = $cpOrder->products;
        if (is_string($prods)) $prods = json_decode($prods, true);
        if (!is_array($prods)) $prods = [];
        return count($prods);
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
            \Illuminate\Support\Facades\DB::table('cp_orders')->where('id', $orderId)->update([
                'products' => $productsJson,
                'status' => $action == 'approve' ? 'completed' : 'rejected',
                'inQuoteSent' => 1,
                'quote_amount' => $request->input('grand_total'),
                'quote_validity_date' => $request->input('quote_validity_date'),
                'quote_date' => now()->format('Y-m-d'),
                'quote_generated_by' => Auth::user()->id,
            ]);

            return redirect()->route('pendingOrders')->with('success', 'Order pricing saved successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error saving order pricing: ' . $e->getMessage());
        }
    }

    public function approveInventoryRequest(Request $request, $id)
    {
        try {
            $order = CpOrder::findOrFail($id);

            try {
                \Illuminate\Support\Facades\DB::table('cp_orders')->where('id', $id)->update([
                    'status' => 'confirmed',
                    'admin_remarks' => $request->input('admin_remarks'),
                ]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\DB::table('cp_orders')->where('id', $id)->update([
                    'status' => 'completed',
                    'admin_remarks' => $request->input('admin_remarks'),
                ]);
            }

            return redirect()->route('pendingOrders')->with('success', 'Order approved. Stock will be deducted when marked as delivered.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function cancelInventoryRequest(Request $request, $id)
    {
        try {
            \Illuminate\Support\Facades\DB::table('cp_orders')->where('id', $id)->update([
                'status' => 'cancelled',
                'admin_remarks' => $request->input('admin_remarks'),
            ]);
            return redirect()->route('pendingOrders')->with('success', 'Inventory request cancelled.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function checkCpOrderStock($id)
    {
        $order = CpOrder::findOrFail($id);
        $products = $order->products;
        if (is_string($products)) $products = json_decode($products, true);

        $warnings = [];
        if (is_array($products)) {
            foreach ($products as $product) {
                $productId = $product['product_id'] ?? null;
                $qty = (int) ($product['quantity'] ?? 0);
                if (!$productId || $qty <= 0) continue;
                $prod = Product::find($productId);
                if ($prod && $prod->quantity < $qty) {
                    $warnings[] = [
                        'name' => $prod->item_name,
                        'available' => $prod->quantity,
                        'requested' => $qty,
                    ];
                }
            }
        }

        return response()->json(['warnings' => $warnings]);
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

        \Illuminate\Support\Facades\DB::table('cp_orders')->where('id', $id)->update([
            'payment_status' => 'paid',
            'status' => 'confirmed',
        ]);

        return redirect()->back()->with('success', 'CP payment approved and order confirmed.');
    }

    public function markCpOrderDelivered($id)
    {
        $order = CpOrder::findOrFail($id);

        \Illuminate\Support\Facades\DB::table('cp_orders')->where('id', $id)->update([
            'status' => 'delivered',
        ]);

        $products = $order->products;
        if (is_string($products)) $products = json_decode($products, true);
        $orderGrandTotal = 0;
        if (is_array($products)) {
            foreach ($products as $product) {
                $productId = $product['product_id'] ?? null;
                $qty = (int) ($product['quantity'] ?? 0);
                if (!$productId || $qty <= 0) continue;

                $inv = \App\Models\CpProductInventory::firstOrCreate(
                    ['cp_id' => $order->cp_id, 'product_id' => $productId],
                    ['available_qty' => 0]
                );
                $inv->available_qty += $qty;
                $inv->save();

                \App\Models\CpProductInventoryTransaction::create([
                    'cp_id' => $order->cp_id,
                    'product_id' => $productId,
                    'transaction_type' => 'IN',
                    'quantity' => $qty,
                    'performed_by' => auth()->id(),
                    'txn_id' => 'DEL' . date('Ymd') . strtoupper(substr(uniqid(), -4)),
                    'remarks' => 'Auto-added from delivered order ' . $order->order_id,
                ]);

                $prod = Product::find($productId);
                if ($prod) {
                    $prod->quantity = max(0, ($prod->quantity ?? 0) - $qty);
                    $prod->save();
                }

                $inventory = ProductInventory::where('product_id', $productId)->first();
                if ($inventory) {
                    $inventory->available_qty = max(0, $inventory->available_qty - $qty);
                    $inventory->save();
                }

                $salePrice = $product['price'] ?? $product['sale_price'] ?? ($prod ? $prod->current_sale_price : 0) ?? 0;
                ProductInventoryTransaction::create([
                    'product_id' => $productId,
                    'transaction_type' => 'OUT',
                    'quantity' => $qty,
                    'unit_price' => $salePrice,
                    'performed_by' => auth()->id(),
                    'txn_id' => 'INV' . date('Ymd') . strtoupper(substr(uniqid(), -4)),
                    'remarks' => 'CP Order #' . $order->order_id . ' delivered',
                ]);

                CpMaterialLedger::create([
                    'cp_id' => $order->cp_id,
                    'material_name' => $prod ? $prod->item_name : 'Product #' . $productId,
                    'quantity' => $qty,
                    'unit' => $prod ? ($prod->uom ?? 'Piece') : 'Piece',
                    'rate' => $salePrice,
                    'total_amount' => $salePrice * $qty,
                    'entry_date' => now()->format('Y-m-d'),
                    'added_by' => auth()->id(),
                    'remarks' => 'CP Order #' . $order->order_id . ' delivered',
                    'source' => 'order_delivery',
                ]);

                $orderGrandTotal += $salePrice * $qty;
            }

            if (\Illuminate\Support\Facades\Schema::hasColumn('cp_orders', 'grand_total')) {
                if (empty($order->grand_total) || $order->grand_total == 0) {
                    \Illuminate\Support\Facades\DB::table('cp_orders')->where('id', $id)->update([
                        'grand_total' => $orderGrandTotal,
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Order marked as delivered and inventory updated.');
    }

    public function rejectCpPayment($id)
    {
        \Illuminate\Support\Facades\DB::table('cp_orders')->where('id', $id)->update([
            'payment_status' => 'failed',
        ]);
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
            if (!$product) continue;

            $product->quantity = max(0, $product->quantity - $item->quantity);
            $product->save();

            $inventory = ProductInventory::where('product_id', $item->product_id)->first();
            if ($inventory) {
                $inventory->available_qty = max(0, $inventory->available_qty - $item->quantity);
                $inventory->save();
            }

            $salePrice = $item->price ?? $product->current_sale_price ?? 0;

            $txnId = 'INV' . date('Ymd') . strtoupper(substr(uniqid(), -4));
            ProductInventoryTransaction::create([
                'product_id' => $item->product_id,
                'transaction_type' => 'OUT',
                'quantity' => $item->quantity,
                'unit_price' => $salePrice,
                'performed_by' => Auth::id(),
                'txn_id' => $txnId,
                'remarks' => 'Customer Order #' . ($order->order_number ?? $order->id) . ' approved',
            ]);
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
