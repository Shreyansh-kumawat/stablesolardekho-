<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $products = [];

        foreach ($cart as $id => $qty) {
            $product = Product::find($id);
            if ($product) {
                $products[] = ['product' => $product, 'quantity' => $qty];
            }
        }

        return view('user.cart', compact('products'));
    }

    public function add(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id', 'quantity' => 'required|integer|min:1']);

        $cart = session('cart', []);
        $id = $request->product_id;
        $cart[$id] = ($cart[$id] ?? 0) + $request->quantity;
        session(['cart' => $cart]);

        if ($request->filled('redirect_to')) {
            return redirect($request->redirect_to);
        }

        $product = Product::find($id);
        return back()->with('success', 'Item "' . $product->item_name . '" added to cart!');
    }

    public function update(Request $request)
    {
        $request->validate(['product_id' => 'required', 'quantity' => 'required|integer|min:1']);
        $cart = session('cart', []);
        $cart[$request->product_id] = $request->quantity;
        session(['cart' => $cart]);
        return back()->with('success', 'Cart updated.');
    }

    public function remove(Request $request)
    {
        $request->validate(['product_id' => 'required']);
        $cart = session('cart', []);
        unset($cart[$request->product_id]);
        session(['cart' => $cart]);
        return back()->with('success', 'Item removed.');
    }
}
