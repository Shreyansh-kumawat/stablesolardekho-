<?php

namespace App\Http\Controllers;

use App\Models\ChannelPartner;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductCategory;
use App\Models\ProductSubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function shopPage(Request $request, $slug = null)
    {
        $categories = ProductCategory::withCount(['products' => fn($q) => $q->where('is_active', true)])->orderBy('id', 'desc')->get();
        $query = Product::with('category')->where('is_active', true);
        $activeCategory = null;

        if ($slug) {
            $activeCategory = ProductCategory::where('slug', $slug)->firstOrFail();
            $query->where('category_id', $activeCategory->id);
        }

        $products = $query->latest()->paginate(12);

        return view('shop.index', compact('products', 'categories', 'activeCategory'));
    }

    public function featuredPage(Request $request)
    {
        $categories = ProductCategory::all();
        $query = Product::with('category')->where('is_active', true)->where('is_featured', true);

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $products = $query->latest()->paginate(12)->withQueryString();

        return view('shop.featured', compact('products', 'categories'));
    }

    public function showProduct($slug)
    {
        $product = Product::with('category')->where('slug', $slug)->firstOrFail();
        return view('shop.product', compact('product'));
    }

    public function manageCategory()
    {
        abort_unless(auth()->user()->hasAdminPermission('categories'), 403);
        try {
            $category_list = ProductCategory::withCount('products')->orderBy('id', 'desc')->get();

            return view('Admin.productSetting.manageCategory')
                ->with('category_list', $category_list);
        } catch (\Exception $e) {
        }
    }
    public function saveNewCategory(Request $request)
    {
        abort_unless(auth()->user()->hasAdminPermission('categories.add'), 403);
        try {
            $category = new ProductCategory();
            $category->category_name = $request->categoryName;
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
            $category->category_name = $request->categoryName;
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
            $sub_category = new ProductSubCategory();
            $sub_category->category_id = $request->category_id;
            $sub_category->sub_category_name = $request->sub_category_name;
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
        $product_list = Product::with(['category', 'subCategory'])->get();
        $categories = ProductCategory::all();
        return view('Admin.productSetting.manageProducts')
            ->with('product_list', $product_list)
            ->with('categories', $categories);
    }

    public function updateSubCategory(Request $request)
    {
        try {
            $sub_category = ProductSubCategory::find($request->sub_category_id);
            $sub_category->category_id = $request->category_id;
            $sub_category->sub_category_name = $request->sub_category_name;
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
        return redirect()->back()->with('success', 'Category deleted successfully.');
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
