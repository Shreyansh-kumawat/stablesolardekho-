<?php

namespace App\Http\Controllers;

use App\Models\ChannelPartner;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductCategory;
use App\Models\ProductSubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function categoriesPage()
    {
        $categories = ProductCategory::withCount(['products' => fn($q) => $q->where('is_active', true)->inStock()])->orderBy('id', 'desc')->get();
        return view('shop.categories', compact('categories'));
    }

    public function shopPage(Request $request, $slug = null)
    {
        $categories = ProductCategory::withCount(['products' => fn($q) => $q->where('is_active', true)->inStock()])->orderBy('id', 'desc')->get();
        $query = Product::with('category')->where('is_active', true)->inStock();
        if (Schema::hasColumn('products', 'is_kit')) $query->where('is_kit', false);
        $activeCategory = null;

        if ($slug) {
            $activeCategory = ProductCategory::where('slug', $slug)->firstOrFail();
            $query->where('category_id', $activeCategory->id);
        } elseif ($request->filled('category')) {
            $activeCategory = ProductCategory::find($request->category);
            if ($activeCategory) {
                $query->where('category_id', $activeCategory->id);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                  ->orWhere('item_code', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('sort')) {
            match ($request->sort) {
                'price_low'  => $query->orderBy('current_sale_price', 'asc'),
                'price_high' => $query->orderBy('current_sale_price', 'desc'),
                'name_asc'   => $query->orderBy('item_name', 'asc'),
                'name_desc'  => $query->orderBy('item_name', 'desc'),
                'oldest'     => $query->orderBy('id', 'asc'),
                default      => $query->latest(),
            };
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->appends($request->query());

        $kits = Schema::hasColumn('products', 'is_kit')
            ? Product::where('is_kit', true)->where('is_active', true)->with('kitSlabPrices')->latest()->get()
            : collect();

        return view('shop.index', compact('products', 'categories', 'activeCategory', 'kits'));
    }

    public function shopSearch(Request $request)
    {
        $query = Product::with('category')->where('is_active', true)->inStock();
        if (Schema::hasColumn('products', 'is_kit')) $query->where('is_kit', false);

        if ($request->filled('category_slug')) {
            $cat = ProductCategory::where('slug', $request->category_slug)->first();
            if ($cat) $query->where('category_id', $cat->id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                  ->orWhere('item_code', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('sort')) {
            match ($request->sort) {
                'price_low'  => $query->orderBy('current_sale_price', 'asc'),
                'price_high' => $query->orderBy('current_sale_price', 'desc'),
                'name_asc'   => $query->orderBy('item_name', 'asc'),
                'name_desc'  => $query->orderBy('item_name', 'desc'),
                'oldest'     => $query->orderBy('id', 'asc'),
                default      => $query->latest(),
            };
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->appends($request->query());

        $html = '';
        foreach ($products as $product) {
            $url = route('product.show', $product->slug);
            $imgHtml = $product->image
                ? '<img src="' . asset('storage/' . $product->image) . '" alt="' . e($product->item_name) . '">'
                : '<div class="prod-img-empty"><span style="color:rgba(249,115,22,0.2);font-size:2rem;font-weight:900;">' . strtoupper(substr($product->item_name, 0, 2)) . '</span></div>';
            $featBadge = $product->is_featured ? '<span class="prod-featured-badge">FEATURED</span>' : '';
            $catName = $product->category->category_name ?? '';
            $priceHtml = $product->current_sale_price
                ? '₹' . $product->current_sale_price
                : '<span style="color:var(--muted);font-size:0.8rem;font-weight:500;">Price on request</span>';

            $specsHtml = '';
            if ($product->brand || $product->manufacturer_warranty || $product->mnre_approved) {
                $specsHtml = '<div style="font-size:0.72rem;color:var(--muted);line-height:1.55;margin-top:2px;">';
                if ($product->brand) $specsHtml .= '<div>Brand: <span style="color:var(--text);">' . e($product->brand) . '</span></div>';
                if ($product->manufacturer_warranty) $specsHtml .= '<div>Warranty: <span style="color:var(--text);">' . e($product->manufacturer_warranty) . '</span></div>';
                if ($product->mnre_approved) $specsHtml .= '<div>MNRE: <span style="color:var(--text);">' . e($product->mnre_approved) . '</span></div>';
                $specsHtml .= '</div>';
            }

            $html .= '<a href="' . $url . '" class="prod-card">'
                . '<div class="prod-img">' . $imgHtml . '<div class="prod-img-overlay"><div class="prod-overlay-btn">View Product</div></div>' . $featBadge . '</div>'
                . '<div class="prod-body"><div class="prod-cat">' . e($catName) . '</div><div class="prod-name">' . e($product->item_name) . '</div>' . $specsHtml
                . '<div class="prod-footer"><div class="prod-price">' . $priceHtml . '</div><div class="prod-arrow">&#8594;</div></div></div></a>';
        }

        $pagHtml = '';
        if ($products->hasPages()) {
            $current = $products->currentPage();
            $last = $products->lastPage();
            $start = max(1, $current - 2);
            $end = min($last, $current + 2);
            if ($end - $start < 4) { $start = max(1, $end - 4); $end = min($last, $start + 4); }

            $pagHtml = '<div class="shop-pagination">';
            $pagHtml .= '<a class="pg ' . ($products->onFirstPage() ? 'off' : '') . '" href="' . $products->previousPageUrl() . '">&laquo; Prev</a>';
            if ($start > 1) {
                $pagHtml .= '<a class="pg" href="' . $products->url(1) . '">1</a>';
                if ($start > 2) $pagHtml .= '<span class="pg off" style="pointer-events:none;border:none;opacity:0.5;">...</span>';
            }
            foreach ($products->getUrlRange($start, $end) as $page => $url) {
                $pagHtml .= '<a class="pg ' . ($page == $current ? 'active' : '') . '" href="' . $url . '">' . $page . '</a>';
            }
            if ($end < $last) {
                if ($end < $last - 1) $pagHtml .= '<span class="pg off" style="pointer-events:none;border:none;opacity:0.5;">...</span>';
                $pagHtml .= '<a class="pg" href="' . $products->url($last) . '">' . $last . '</a>';
            }
            $pagHtml .= '<a class="pg ' . (!$products->hasMorePages() ? 'off' : '') . '" href="' . $products->nextPageUrl() . '">Next &raquo;</a>';
            $pagHtml .= '</div>';
        }

        return response()->json([
            'html' => $html,
            'pagination' => $pagHtml,
            'total' => $products->total(),
        ]);
    }

    public function featuredPage(Request $request)
    {
        $categories = ProductCategory::all();
        $query = Product::with('category')->where('is_active', true)->where('is_featured', true)->inStock();
        if (Schema::hasColumn('products', 'is_kit')) $query->where('is_kit', false);

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $products = $query->latest()->paginate(12)->withQueryString();

        return view('shop.featured', compact('products', 'categories'));
    }

    public function showProduct($slug)
    {
        $product = Product::with(['category', 'inventory'])->where('slug', $slug)->firstOrFail();
        return view('shop.product', compact('product'));
    }

    public function manageCategory()
    {
        abort_unless(auth()->user()->hasAdminPermission('categories'), 403);
        try {
            $category_list = ProductCategory::withCount(['products', 'subCategories'])->orderBy('id', 'desc')->get();

            return view('Admin.productSetting.manageCategory')
                ->with('category_list', $category_list);
        } catch (\Exception $e) {
        }
    }
    public function saveNewCategory(Request $request)
    {
        abort_unless(auth()->user()->hasAdminPermission('categories.add'), 403);
        try {
            $name = trim($request->categoryName);
            if ($name === '') {
                return redirect()->back()->withInput()->with('error', 'Category name is required.');
            }
            $exists = ProductCategory::whereRaw('LOWER(category_name) = ?', [strtolower($name)])->exists();
            if ($exists) {
                return redirect()->back()->withInput()->with('error', 'Category "' . $name . '" already exists. Please use a different name.');
            }
            $category = new ProductCategory();
            $category->category_name = $name;
            $category->category_description = $request->categoryDiscription;
            $category->active_status = '1';
            if ($request->hasFile('image')) {
                $category->image = $request->file('image')->store('categories', 'public');
            }
            $category->save();
            return redirect()->back()->with('success', 'Category has been added successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function updateCategory(Request $request)
    {
        abort_unless(auth()->user()->hasAdminPermission('categories.edit'), 403);
        try {
            $category = ProductCategory::find($request->category_id);
            $name = trim($request->categoryName);
            if ($name === '') {
                return redirect()->back()->withInput()->with('error', 'Category name is required.');
            }
            $exists = ProductCategory::whereRaw('LOWER(category_name) = ?', [strtolower($name)])
                ->where('id', '!=', $category->id)->exists();
            if ($exists) {
                return redirect()->back()->withInput()->with('error', 'Another category with name "' . $name . '" already exists. Please use a different name.');
            }
            $category->category_name = $name;
            $category->category_description = $request->categoryDiscription;
            $category->active_status = $request->activeStatus ?? '1';
            if ($request->hasFile('image')) {
                if ($category->image) Storage::disk('public')->delete($category->image);
                $category->image = $request->file('image')->store('categories', 'public');
            }
            $category->update();
            return redirect()->back()->with('success', 'Category has been updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function categoryDetail($id)
    {
        abort_unless(auth()->user()->hasAdminPermission('categories'), 403);
        $category = ProductCategory::withCount('products')->findOrFail($id);
        $subCategories = ProductSubCategory::where('category_id', $id)->withCount('products')->get();
        $prodQuery = Product::where('category_id', $id)->where('is_active', true);
        if (Schema::hasColumn('products', 'is_kit')) $prodQuery->where('is_kit', false);
        $products = $prodQuery->latest()->get();
        return view('Admin.productSetting.categoryDetail', compact('category', 'subCategories', 'products'));
    }

    public function manageSubCategory()
    {
        try {
            $categories = ProductCategory::where('active_status', '1')->get();
            $sub_category_list = ProductSubCategory::with('category')->get();
            return view('Admin.productSetting.manageSubCategory')->with('categories', $categories)->with('sub_category_list', $sub_category_list);
        } catch (\Exception $e) {
        }
    }

    public function saveNewSubCategory(Request $request)
    {
        try {
            $name = trim($request->sub_category_name);
            if ($name === '' || !$request->category_id) {
                return redirect()->back()->withInput()->with('error', 'Category and Sub-Category name are required.');
            }
            $exists = ProductSubCategory::where('category_id', $request->category_id)
                ->whereRaw('LOWER(sub_category_name) = ?', [strtolower($name)])->exists();
            if ($exists) {
                return redirect()->back()->withInput()->with('error', 'Sub-Category "' . $name . '" already exists in this category. Please use a different name.');
            }
            $sub_category = new ProductSubCategory();
            $sub_category->category_id = $request->category_id;
            $sub_category->sub_category_name = $name;
            $sub_category->remarks = $request->sub_category_description;
            $sub_category->save();
            return redirect()->back()->with('success', 'Sub-Category has been added successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function manageProducts()
    {
        abort_unless(auth()->user()->hasAdminPermission('products'), 403);
        $product_list = Product::with(['category', 'subCategory', 'inventory'])
            ->withSum('warehouseInventories as warehouseInventoriesSum', 'available_qty');
        if (Schema::hasColumn('products', 'is_kit')) $product_list->where('is_kit', false);
        $product_list = $product_list->latest()->get();
        $categories = ProductCategory::all();

        // Attach last purchase price per product
        $productIds = $product_list->pluck('id');
        $lastPurchase = \App\Models\ProductInventoryTransaction::whereIn('product_id', $productIds)
            ->where('transaction_type', 'IN')
            ->whereNotNull('unit_price')
            ->select('product_id', 'unit_price', 'gst_percent', 'total_with_gst', 'created_at')
            ->get()
            ->groupBy('product_id')
            ->map(function ($rows) { return $rows->sortByDesc('created_at')->first(); });
        foreach ($product_list as $p) {
            $lp = $lastPurchase->get($p->id);
            $p->last_purchase_price = $lp->unit_price ?? null;
            $p->last_gst_percent = $lp->gst_percent ?? null;
            $p->last_total_with_gst = $lp->total_with_gst ?? null;
        }

        return view('Admin.productSetting.manageProducts')
            ->with('product_list', $product_list)
            ->with('categories', $categories);
    }

    public function updateSubCategory(Request $request)
    {
        try {
            $sub_category = ProductSubCategory::find($request->sub_category_id);
            $name = trim($request->sub_category_name);
            if ($name === '' || !$request->category_id) {
                return redirect()->back()->withInput()->with('error', 'Category and Sub-Category name are required.');
            }
            $exists = ProductSubCategory::where('category_id', $request->category_id)
                ->whereRaw('LOWER(sub_category_name) = ?', [strtolower($name)])
                ->where('id', '!=', $sub_category->id)->exists();
            if ($exists) {
                return redirect()->back()->withInput()->with('error', 'Another Sub-Category with name "' . $name . '" already exists in this category. Please use a different name.');
            }
            $sub_category->category_id = $request->category_id;
            $sub_category->sub_category_name = $name;
            $sub_category->remarks = $request->sub_category_description;
            $sub_category->update();
            return redirect()->back()->with('success', 'Sub-Category has been updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function getSubCategories(Request $request)
    {
        $id = $request->input('category_id');
        $sub_categories = ProductSubCategory::where('category_id', $id)->get();
        return response()->json($sub_categories);
    }

    public function getProducts(Request $request)
    {
        if ($request->input('category_id')) {
            $query = Product::where('category_id', $request->input('category_id'));
            if ($request->input('sub_category_id')) {
                $query->where('sub_category_id', $request->input('sub_category_id'));
            }
            return response()->json($query->get());
        }

        $id = $request->input('sub_category_id');
        $products = Product::where('sub_category_id', $id)->get();
        return response()->json($products);
    }

    public function saveNewProduct(Request $request)
    {
        abort_unless(auth()->user()->hasAdminPermission('products.add'), 403);
        try {
            $product = new Product();
            $product->category_id = $request->category_id;
            $product->sub_category_id = $request->sub_category_id ?? null;
            $product->item_name = $request->product_name;
            $product->item_code = $request->item_code;
            $product->uom = $request->uom;
            $product->current_sale_price = $request->current_sale_price ?? null;
            $product->quantity = $request->quantity ?? 0;
            $product->description = $request->description ?? null;
            $product->is_featured = $request->is_featured ? 1 : 0;
            $product->is_active = 1;
            $product->type = $request->type;
            $product->brand = $request->brand;
            $product->model = $request->product_model;
            $product->operating_voltage = $request->operating_voltage;
            $product->solar_panel_type = $request->solar_panel_type;
            $product->mnre_approved = $request->mnre_approved;
            $product->certifications = $request->certifications;
            $product->manufacturer_warranty = $request->manufacturer_warranty;
            $product->number_of_cells = $request->number_of_cells;
            $product->encapsulate = $request->encapsulate;
            $product->country_of_origin = $request->country_of_origin;
            $product->input_voltage = $request->input_voltage;
            $product->max_supported_panel_power = $request->max_supported_panel_power;
            $product->is_serialNumber_required = $request->has('is_serialNumber_required') ? 1 : 0;
            if ($request->hasFile('image')) {
                $product->image = $request->file('image')->store('products', 'public');
            }
            $product->save();

            // Save gallery images (max 8)
            if ($request->hasFile('product_images')) {
                $existing = $product->images()->count();
                foreach ($request->file('product_images') as $i => $img) {
                    if ($existing + $i >= 8) break;
                    $product->images()->create([
                        'image' => $img->store('product-gallery', 'public'),
                        'sort_order' => $existing + $i,
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Product has been added successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function updateProduct(Request $request)
    {
        abort_unless(auth()->user()->hasAdminPermission('products.edit'), 403);
        try {
            $product = Product::find($request->product_id);
            $product->category_id = $request->category_id;
            $product->sub_category_id = $request->sub_category_id ?? null;
            $product->item_name = $request->product_name;
            $product->item_code = $request->item_code;
            $product->uom = $request->uom;
            $product->current_sale_price = $request->current_sale_price ?? null;
            $product->quantity = $request->quantity ?? 0;
            $product->description = $request->description ?? null;
            $product->is_featured = $request->is_featured ? 1 : 0;
            $product->type = $request->type;
            $product->brand = $request->brand;
            $product->model = $request->product_model;
            $product->operating_voltage = $request->operating_voltage;
            $product->solar_panel_type = $request->solar_panel_type;
            $product->mnre_approved = $request->mnre_approved;
            $product->certifications = $request->certifications;
            $product->manufacturer_warranty = $request->manufacturer_warranty;
            $product->number_of_cells = $request->number_of_cells;
            $product->encapsulate = $request->encapsulate;
            $product->country_of_origin = $request->country_of_origin;
            $product->input_voltage = $request->input_voltage;
            $product->max_supported_panel_power = $request->max_supported_panel_power;
            $product->is_serialNumber_required = $request->has('is_serialNumber_required') ? 1 : 0;
            if ($request->hasFile('image')) {
                if ($product->image) Storage::disk('public')->delete($product->image);
                $product->image = $request->file('image')->store('products', 'public');
            }
            $product->update();

            // Add new gallery images (respect max 8 total)
            if ($request->hasFile('product_images')) {
                $existing = $product->images()->count();
                foreach ($request->file('product_images') as $i => $img) {
                    if ($existing + $i >= 8) break;
                    $product->images()->create([
                        'image' => $img->store('product-gallery', 'public'),
                        'sort_order' => $existing + $i,
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Product has been updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function updateProductPrice(Request $request)
    {
        try {
            $product = Product::find($request->product_id);
            $product->current_sale_price = $request->new_price;
            $product->update();
            return redirect()->back()->with('success', 'Product price has been updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function toggleProductActive(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->is_active = !$product->is_active;
        $product->save();
        return response()->json(['success' => true, 'is_active' => $product->is_active]);
    }

    /**
     * Update the LATEST purchase entry for a product from the Products page.
     * Also propagates change to linked serials + warehouse transactions.
     */
    public function updateProductPurchasePrice(Request $request, $id)
    {
        abort_unless(auth()->user()->hasAdminPermission('products.edit'), 403);
        $request->validate([
            'unit_price' => 'nullable|numeric|min:0',
            'gst_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        $latest = \App\Models\ProductInventoryTransaction::where('product_id', $id)
            ->where('transaction_type', 'IN')
            ->orderByDesc('created_at')
            ->first();

        if (!$latest) {
            return response()->json(['success' => false, 'message' => 'No purchase entry found. Add stock first from Inventory > Add Stock.'], 422);
        }

        $price = $request->filled('unit_price') ? (float) $request->unit_price : null;
        $gstPct = $request->filled('gst_percent') ? (float) $request->gst_percent : null;
        $gstAmt = ($price !== null && $gstPct !== null) ? round($price * $gstPct / 100, 2) : null;
        $totWithGst = ($price !== null) ? round($price + ($gstAmt ?? 0), 2) : null;

        $latest->unit_price = $price;
        $latest->gst_percent = $gstPct;
        $latest->gst_amount = $gstAmt;
        $latest->total_with_gst = $totWithGst;
        $latest->save();

        // Propagate to linked serials and warehouse transactions
        if ($latest->txn_id) {
            \App\Models\ProductSerial::where('batch_txn_id', $latest->txn_id)->update([
                'purchase_price' => $price,
                'gst_percent' => $gstPct,
            ]);
            \App\Models\WarehouseInventoryTransaction::where('txn_id', $latest->txn_id)
                ->where('transaction_type', 'IN')
                ->update([
                    'unit_price' => $price,
                    'gst_percent' => $gstPct,
                    'gst_amount' => $gstAmt,
                    'total_with_gst' => $totWithGst,
                ]);
        }

        return response()->json([
            'success' => true,
            'unit_price' => $price,
            'gst_percent' => $gstPct,
            'total_with_gst' => $totWithGst,
            'txn_id' => $latest->txn_id,
        ]);
    }

    public function toggleProductFeatured(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->is_featured = !$product->is_featured;
        $product->save();
        return response()->json(['success' => true, 'is_featured' => $product->is_featured]);
    }

    public function getProductImages($id)
    {
        $images = ProductImage::where('product_id', $id)->orderBy('sort_order')->get()->map(fn($img) => [
            'id'  => $img->id,
            'url' => Storage::url($img->image),
        ]);
        return response()->json($images);
    }

    public function deleteProductImage(Request $request, $id)
    {
        $img = ProductImage::findOrFail($id);
        Storage::disk('public')->delete($img->image);
        $img->delete();
        return response()->json(['success' => true]);
    }

    public function deleteMainImage($id)
    {
        abort_unless(auth()->user()->hasAdminPermission('products.edit'), 403);
        $product = Product::findOrFail($id);
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
            $product->image = null;
            $product->save();
        }
        return response()->json(['success' => true]);
    }

    public function deleteCategoryImage($id)
    {
        abort_unless(auth()->user()->hasAdminPermission('categories.edit'), 403);
        $category = ProductCategory::findOrFail($id);
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
            $category->image = null;
            $category->save();
        }
        return response()->json(['success' => true]);
    }

    public function deleteCategory($id)
    {
        abort_unless(auth()->user()->hasAdminPermission('categories.delete'), 403);
        $category = ProductCategory::withCount('products')->findOrFail($id);
        if ($category->products_count > 0) {
            return redirect()->back()->with('error', 'Cannot delete category with ' . $category->products_count . ' product(s). Remove or reassign products first.');
        }
        ProductSubCategory::where('category_id', $id)->delete();
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        $category->delete();
        return redirect()->route('manageCategory')->with('success', 'Category deleted successfully.');
    }

    public function deleteSubCategory($id)
    {
        abort_unless(auth()->user()->hasAdminPermission('categories.delete'), 403);
        $sub = ProductSubCategory::withCount('products')->findOrFail($id);
        if ($sub->products_count > 0) {
            return redirect()->back()->with('error', 'Cannot delete subcategory with ' . $sub->products_count . ' product(s). Remove or reassign products first.');
        }
        $sub->delete();
        return redirect()->back()->with('success', 'Subcategory deleted successfully.');
    }

    public function deleteProduct($id)
    {
        abort_unless(auth()->user()->hasAdminPermission('products.delete'), 403);
        $product = Product::findOrFail($id);

        $activeOrders = \DB::table('customer_order_items')
            ->join('customer_orders', 'customer_orders.id', '=', 'customer_order_items.order_id')
            ->where('customer_order_items.product_id', $id)
            ->whereNotIn('customer_orders.status', ['delivered', 'cancelled'])
            ->exists();
        if ($activeOrders) {
            return redirect()->back()->with('error', 'Cannot delete product "' . $product->item_name . '" — it has active (non-delivered) orders.');
        }

        \DB::table('customer_order_items')->where('product_id', $id)->delete();

        \DB::table('cp_product_inventory_transactions')->where('product_id', $id)->delete();
        \DB::table('cp_product_inventories')->where('product_id', $id)->delete();
        \DB::table('product_inventory_transactions')->where('product_id', $id)->delete();
        \DB::table('product_serials')->where('product_id', $id)->delete();
        \DB::table('product_inventories')->where('product_id', $id)->delete();

        foreach ($product->images as $img) {
            Storage::disk('public')->delete($img->image);
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();
        return redirect()->back()->with('success', 'Product deleted successfully.');
    }

    public function getChannelPartnerByRole()
    {
        $cp_role_id = request()->input('role_id');
        $channel_partners = ChannelPartner::where('cp_role', $cp_role_id)->get();
        if(Auth::user()->role_id != 1){
            $channel_partners = $channel_partners->where('id', '!=', Auth::user()->cp_id);
        }
        return response()->json($channel_partners);
    }
}
