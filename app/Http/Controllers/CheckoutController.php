<?php

namespace App\Http\Controllers;

use App\Models\CustomerOrder;
use App\Models\CustomerOrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CheckoutController extends Controller
{
    public function show(Request $request)
    {
        $items = [];

        if ($request->filled('product_id')) {
            $product = Product::findOrFail($request->product_id);
            $qty = (int) ($request->qty ?: 1);
            $items[] = ['product' => $product, 'quantity' => $qty];
            $mode = 'buy_now';
        } else {
            $cart = session('cart', []);
            if (empty($cart)) {
                return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
            }
            foreach ($cart as $id => $qty) {
                $product = Product::find($id);
                if ($product) {
                    $items[] = ['product' => $product, 'quantity' => $qty];
                }
            }
            if (empty($items)) {
                return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
            }
            $mode = 'cart';
        }

        return view('user.checkout', compact('items', 'mode'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'required|string|max:15',
            'address' => 'required|string',
            'city'    => 'required|string|max:100',
            'district'=> 'nullable|string|max:100',
            'state'   => 'required|string|max:100',
            'pincode' => 'required|string|max:10',
            'mode'    => 'required|in:cart,buy_now',
        ]);

        $items = [];

        if ($request->mode === 'buy_now') {
            $request->validate(['product_id' => 'required|exists:products,id', 'qty' => 'required|integer|min:1']);
            $product = Product::findOrFail($request->product_id);
            $items[] = ['product' => $product, 'quantity' => (int) $request->qty];
        } else {
            $cart = session('cart', []);
            if (empty($cart)) {
                return back()->with('error', 'Your cart is empty.');
            }
            foreach ($cart as $id => $qty) {
                $product = Product::find($id);
                if ($product) {
                    $items[] = ['product' => $product, 'quantity' => $qty];
                }
            }
        }

        if (empty($items)) {
            return back()->with('error', 'No products to order.');
        }

        $total = 0;
        foreach ($items as $item) {
            $total += ($item['product']->current_sale_price ?? 0) * $item['quantity'];
        }

        DB::beginTransaction();
        try {
            $order = CustomerOrder::create([
                'order_number'   => CustomerOrder::generateOrderNumber(),
                'user_id'        => Auth::id(),
                'total_amount'   => $total,
                'payment_method' => 'online',
                'payment_status' => 'pending',
                'status'         => 'pending',
                'name'           => $request->name,
                'phone'          => $request->phone,
                'address'        => $request->address,
                'city'           => $request->city,
                'district'       => $request->district,
                'state'          => $request->state,
                'pincode'        => $request->pincode,
                'notes'          => $request->notes,
            ]);

            foreach ($items as $item) {
                $price = $item['product']->current_sale_price ?? 0;
                CustomerOrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $item['product']->id,
                    'product_name' => $item['product']->item_name,
                    'price'        => $price,
                    'quantity'     => $item['quantity'],
                    'subtotal'     => $price * $item['quantity'],
                ]);
            }

            DB::commit();

            Auth::user()->update([
                'address'  => $request->address,
                'state'    => $request->state,
                'district' => $request->district,
                'city'     => $request->city,
                'pincode'  => $request->pincode,
            ]);

            if ($request->mode === 'cart') {
                session()->forget('cart');
            }

            return redirect()->route('user.order.payment', $order->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function paymentPage($id)
    {
        $order = CustomerOrder::where('id', $id)->where('user_id', Auth::id())
            ->with('items.product')->firstOrFail();
        $paymentConfig = config('services.payment');
        return view('user.payment', compact('order', 'paymentConfig'));
    }

    public function uploadPaymentProof(Request $request, $id)
    {
        $request->validate([
            'payment_screenshot' => 'required|image|max:5120',
            'payment_reference'  => 'nullable|string|max:100',
            'bank_account_holder' => 'nullable|string|max:255',
            'bank_name'          => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_ifsc'          => 'nullable|string|max:20',
        ]);

        $order = CustomerOrder::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($order->payment_screenshot) {
            Storage::disk('public')->delete($order->payment_screenshot);
        }

        $path = $request->file('payment_screenshot')->store('payment-screenshots', 'public');

        $order->update([
            'payment_screenshot' => $path,
            'payment_reference'  => $request->payment_reference,
            'payment_status'     => 'verification_pending',
        ]);

        $bankData = array_filter([
            'bank_account_holder' => $request->bank_account_holder,
            'bank_name'          => $request->bank_name,
            'bank_account_number' => $request->bank_account_number,
            'bank_ifsc'          => $request->bank_ifsc ? strtoupper($request->bank_ifsc) : null,
        ]);
        if (!empty($bankData)) {
            Auth::user()->update($bankData);
        }

        return redirect()->route('user.order.success', $order->id);
    }

    public function orderSuccess($id)
    {
        $order = CustomerOrder::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        return view('user.order-success', compact('order'));
    }

    public function myOrders()
    {
        $orders = CustomerOrder::where('user_id', Auth::id())->with('items')->latest()->get();
        return view('user.orders', compact('orders'));
    }

    public function orderDetail($id)
    {
        $order = CustomerOrder::where('id', $id)->where('user_id', Auth::id())->with('items.product')->firstOrFail();
        return view('user.order-detail', compact('order'));
    }
}
