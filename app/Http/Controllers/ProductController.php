<?php

namespace App\Http\Controllers;

use App\Models\ChannelPartner;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductSubCategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function manageCategory()
    {
        try {
            $category_list = ProductCategory::get();

            return view('Admin.productSetting.manageCategory')
                ->with('category_list', $category_list);
        } catch (\Exception $e) {
        }
    }
    public function saveNewCategory(Request $request)
    {
        try {
            $category = new ProductCategory();
            $category->category_name = $request->categoryName;
            $category->category_description = $request->categoryDiscription;
            $category->active_status = '1';
            $category->save();
            return redirect()->back()->with('success', 'Category has been added successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function updateCategory(Request $request)
    {
        try {
            $category = ProductCategory::find($request->category_id);
            $category->category_name = $request->categoryName;
            $category->category_description = $request->categoryDiscription;
            $category->active_status = $request->activeStatus ?? '1';
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
        try {

            $product = new Product();
            $product->category_id = $request->category_id;
            $product->sub_category_id = $request->sub_category_id;
            $product->item_name = $request->product_name;
            $product->is_serialNumber_required = $request->is_serialNumber_required ?? 0;
            $product->uom = $request->uom;
            $product->item_code  = $request->item_code;
            $product->save();
            return redirect()->back()->with('success', 'Product has been added successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function updateProduct(Request $request)
    {
        try {
            $product = Product::find($request->product_id);
            $product->category_id = $request->category_id;
            $product->sub_category_id = $request->sub_category_id;
            $product->item_name = $request->product_name;
            $product->is_serialNumber_required = $request->is_serial_required ?? 0;
            $product->uom = $request->uom;
            $product->item_code = $request->item_code;
            $product->update();
            return redirect()->back()->with('success', 'Product has been updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function getChannelPartnerByRole()
    {
        $cp_role_id = request()->input('role_id');
        $channel_partners = ChannelPartner::where('cp_role', $cp_role_id)->get();
        return response()->json($channel_partners);
    }
}
