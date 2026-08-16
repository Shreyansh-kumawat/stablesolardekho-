<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\KitItem;
use App\Models\KitSlabPrice;
use App\Models\ProductCategory;
use App\Models\ProductInventory;
use App\Models\CpOrder;
use App\Models\CustomerOrder;
use App\Models\CustomerOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class KitController extends Controller
{
    private function tablesReady(): bool
    {
        return Schema::hasColumn('products', 'is_kit')
            && Schema::hasTable('kit_items')
            && Schema::hasTable('kit_slab_prices');
    }

    public function index()
    {
        if (!$this->tablesReady()) {
            $kits = collect();
            $categories = ProductCategory::orderBy('category_name')->get();
            return view('Admin.productSetting.manageKits', compact('kits', 'categories'));
        }

        $kits = Product::where('is_kit', true)
            ->with(['kitItems.componentProduct', 'kitSlabPrices', 'category'])
            ->orderByDesc('id')
            ->get();

        foreach ($kits as $kit) {
            $kit->max_kits = $this->calculateMaxKits($kit);
        }

        $categories = ProductCategory::orderBy('category_name')->get();

        return view('Admin.productSetting.manageKits', compact('kits', 'categories'));
    }

    private function calculateMaxKits(Product $kit): int
    {
        $maxKits = PHP_INT_MAX;
        foreach ($kit->kitItems as $item) {
            if (!$item->component_product_id || $item->quantity <= 0) continue;
            $stock = 0;
            $inv = ProductInventory::where('product_id', $item->component_product_id)->first();
            if ($inv) {
                $stock = $inv->available_qty;
            } else {
                $stock = $item->componentProduct->quantity ?? 0;
            }
            $possible = floor($stock / $item->quantity);
            $maxKits = min($maxKits, $possible);
        }
        return $maxKits === PHP_INT_MAX ? 0 : (int) $maxKits;
    }

    public function store(Request $request)
    {
        abort_unless($this->tablesReady(), 500, 'Please run migrations first.');

        $request->validate([
            'kit_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|string|max:50',
            'image' => 'nullable|image|max:5120',
        ]);

        $product = new Product();
        $product->item_name = $request->kit_name;
        $product->item_code = 'KIT-' . strtoupper(\Illuminate\Support\Str::random(6));
        $product->uom = 'Kit';
        $product->category_id = $request->category_id ?? ProductCategory::first()?->id ?? 1;
        $product->current_sale_price = $request->base_price;
        $product->quantity = 0;
        $product->description = $request->description;
        $product->is_kit = true;
        $product->is_active = 1;
        $product->is_featured = $request->is_featured ? 1 : 0;

        if ($request->hasFile('image')) {
            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->save();

        $this->saveKitItems($product->id, $request->input('items', []));
        $this->saveSlabPrices($product->id, $request->input('slabs', []));

        $kit = Product::with('kitItems.componentProduct')->find($product->id);
        $product->quantity = $this->calculateMaxKits($kit);
        $product->save();

        return redirect()->back()->with('success', 'Kit added successfully.');
    }

    public function update(Request $request, $id)
    {
        abort_unless($this->tablesReady(), 500, 'Please run migrations first.');
        $product = Product::where('is_kit', true)->findOrFail($id);

        $request->validate([
            'kit_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|string|max:50',
            'image' => 'nullable|image|max:5120',
        ]);

        $product->item_name = $request->kit_name;
        $product->category_id = $request->category_id ?? $product->category_id;
        $product->current_sale_price = $request->base_price;
        $product->description = $request->description;
        $product->is_featured = $request->is_featured ? 1 : 0;

        if ($request->hasFile('image')) {
            if ($product->image) Storage::disk('public')->delete($product->image);
            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->save();

        $product->kitItems()->delete();
        $product->kitSlabPrices()->delete();
        $this->saveKitItems($product->id, $request->input('items', []));
        $this->saveSlabPrices($product->id, $request->input('slabs', []));

        $kit = Product::with('kitItems.componentProduct')->find($product->id);
        $product->quantity = $this->calculateMaxKits($kit);
        $product->save();

        return redirect()->back()->with('success', 'Kit updated successfully.');
    }

    public function destroy($id)
    {
        $product = Product::where('is_kit', true)->findOrFail($id);
        if ($product->image) Storage::disk('public')->delete($product->image);
        $product->delete();

        return redirect()->back()->with('success', 'Kit deleted successfully.');
    }

    public function toggleActive($id)
    {
        $product = Product::where('is_kit', true)->findOrFail($id);
        $product->is_active = !$product->is_active;
        $product->save();
        return response()->json(['success' => true, 'is_active' => $product->is_active]);
    }

    public function show($slug)
    {
        abort_unless($this->tablesReady(), 404);
        $kit = Product::where('is_kit', true)
            ->where('slug', $slug)
            ->where('is_active', true)
            ->with(['kitItems.componentProduct', 'kitSlabPrices', 'category'])
            ->firstOrFail();

        $kit->max_kits = $this->calculateMaxKits($kit);

        $relatedKits = Product::where('is_kit', true)
            ->where('is_active', true)
            ->inStock()
            ->where('id', '!=', $kit->id)
            ->limit(4)
            ->get();

        return view('shop.kitDetail', compact('kit', 'relatedKits'));
    }

    public function getKitData($id)
    {
        abort_unless($this->tablesReady(), 500, 'Please run migrations first.');
        $kit = Product::where('is_kit', true)
            ->with(['kitItems.componentProduct', 'kitSlabPrices'])
            ->findOrFail($id);

        return response()->json($kit);
    }

    public function orderKit(Request $request)
    {
        $request->validate([
            'kit_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $kit = Product::where('is_kit', true)
            ->with('kitItems.componentProduct')
            ->findOrFail($request->kit_id);

        $qty = (int) $request->quantity;
        $maxKits = $this->calculateMaxKits($kit);

        if ($qty > $maxKits) {
            return response()->json(['error' => 'Only ' . $maxKits . ' kits available'], 422);
        }

        $slabPrice = $kit->current_sale_price;
        foreach ($kit->kitSlabPrices()->orderBy('min_qty')->get() as $slab) {
            if ($qty >= $slab->min_qty && (!$slab->max_qty || $qty <= $slab->max_qty)) {
                $slabPrice = $slab->price;
            }
        }
        $totalAmount = $slabPrice * $qty;

        $user = Auth::user();
        $isCp = $user->role_id == 4 && $user->cp_id;

        $products = [];
        foreach ($kit->kitItems as $item) {
            if (!$item->component_product_id || $item->quantity <= 0) continue;
            $products[] = [
                'product_id' => $item->component_product_id,
                'quantity' => $item->quantity * $qty,
                'price' => $item->componentProduct->current_sale_price ?? 0,
            ];
        }

        if ($isCp) {
            $txnId = 'ORDER' . time() . rand(1000, 9999);
            $data = [
                'cp_id' => $user->cp_id,
                'order_id' => $txnId,
                'products' => json_encode($products),
                'order_notes' => 'Kit Order: ' . $kit->item_name . ' x' . $qty,
                'status' => 'pending',
                'order_date' => now()->format('Y-m-d'),
                'payment_status' => 'verification_pending',
            ];
            if (Schema::hasColumn('cp_orders', 'grand_total')) {
                $data['grand_total'] = $totalAmount;
            }
            CpOrder::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Kit order placed! Redirecting to your orders...',
                'redirect' => route('orderReportCp'),
            ]);
        }

        $order = CustomerOrder::create([
            'order_number' => CustomerOrder::generateOrderNumber(),
            'user_id' => $user->id,
            'total_amount' => $totalAmount,
            'payment_method' => 'pending',
            'payment_status' => 'pending',
            'status' => 'pending',
            'name' => $user->name,
            'phone' => $user->phone ?? '',
            'notes' => 'Kit Order: ' . $kit->item_name . ' x' . $qty,
        ]);

        foreach ($products as $prod) {
            $p = Product::find($prod['product_id']);
            CustomerOrderItem::create([
                'order_id' => $order->id,
                'product_id' => $prod['product_id'],
                'product_name' => $p->item_name ?? 'Product',
                'price' => $prod['price'],
                'quantity' => $prod['quantity'],
                'subtotal' => $prod['price'] * $prod['quantity'],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Kit order placed! Redirecting to payment...',
            'redirect' => route('user.order.payment', $order->id),
        ]);
    }

    private function saveKitItems(int $productId, array $items): void
    {
        foreach ($items as $i => $item) {
            if (empty($item['component_product_id'])) continue;
            $componentProduct = Product::find($item['component_product_id']);
            KitItem::create([
                'product_id' => $productId,
                'category_id' => $item['category_id'] ?? null,
                'component_product_id' => $item['component_product_id'],
                'category_label' => $componentProduct?->category?->category_name ?? '',
                'item_name' => $componentProduct?->item_name ?? '',
                'quantity' => (int) ($item['quantity'] ?? 1),
                'quantity_label' => $item['quantity'] ?? '1',
                'sort_order' => $i,
            ]);
        }
    }

    private function saveSlabPrices(int $productId, array $slabs): void
    {
        foreach ($slabs as $slab) {
            if (empty($slab['price'])) continue;
            KitSlabPrice::create([
                'product_id' => $productId,
                'min_qty' => $slab['min_qty'] ?? 1,
                'max_qty' => !empty($slab['max_qty']) ? $slab['max_qty'] : null,
                'price' => $slab['price'],
            ]);
        }
    }
}
