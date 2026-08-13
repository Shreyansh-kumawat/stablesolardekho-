<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\KitItem;
use App\Models\KitSlabPrice;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
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
            ->with(['kitItems', 'kitSlabPrices', 'category'])
            ->orderByDesc('id')
            ->get();

        $categories = ProductCategory::orderBy('category_name')->get();

        return view('Admin.productSetting.manageKits', compact('kits', 'categories'));
    }

    public function store(Request $request)
    {
        abort_unless($this->tablesReady(), 500, 'Please run migrations first.');

        $request->validate([
            'kit_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'base_price' => 'required|string|max:50',
            'image' => 'nullable|image|max:5120',
        ]);

        $product = new Product();
        $product->item_name = $request->kit_name;
        $product->item_code = 'KIT-' . strtoupper(\Illuminate\Support\Str::random(6));
        $product->uom = 'Kit';
        $product->category_id = $request->category_id ?? ProductCategory::first()?->id ?? 1;
        $product->current_sale_price = $request->base_price;
        $product->quantity = $request->quantity;
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

        return redirect()->back()->with('success', 'Kit added successfully.');
    }

    public function update(Request $request, $id)
    {
        abort_unless($this->tablesReady(), 500, 'Please run migrations first.');
        $product = Product::where('is_kit', true)->findOrFail($id);

        $request->validate([
            'kit_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'base_price' => 'required|string|max:50',
            'image' => 'nullable|image|max:5120',
        ]);

        $product->item_name = $request->kit_name;
        $product->category_id = $request->category_id ?? $product->category_id;
        $product->current_sale_price = $request->base_price;
        $product->quantity = $request->quantity;
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
            ->with(['kitItems', 'kitSlabPrices', 'category'])
            ->firstOrFail();

        $relatedKits = Product::where('is_kit', true)
            ->where('is_active', true)
            ->where('id', '!=', $kit->id)
            ->limit(4)
            ->get();

        return view('shop.kitDetail', compact('kit', 'relatedKits'));
    }

    public function getKitData($id)
    {
        abort_unless($this->tablesReady(), 500, 'Please run migrations first.');
        $kit = Product::where('is_kit', true)
            ->with(['kitItems', 'kitSlabPrices'])
            ->findOrFail($id);

        return response()->json($kit);
    }

    private function saveKitItems(int $productId, array $items): void
    {
        foreach ($items as $i => $item) {
            if (empty($item['item_name'])) continue;
            KitItem::create([
                'product_id' => $productId,
                'category_label' => $item['category_label'] ?? '',
                'item_name' => $item['item_name'],
                'quantity_label' => $item['quantity_label'] ?? '',
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
