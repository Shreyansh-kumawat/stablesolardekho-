<?php

namespace App\Http\Controllers;

use App\Models\ChannelPartner;
use App\Models\ChannelPartnerRole;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductCustomSpec;
use App\Models\ProductInventory;
use App\Models\ProductInventoryTransaction;
use App\Models\ProductSerial;
use App\Models\Warehouse;
use App\Models\WarehouseInventory;
use App\Models\WarehouseInventoryTransaction;
use App\Services\cpInventoryService;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InventoryController extends Controller
{
    public function addNewInventory()
    {
        $categories = ProductCategory::all();
        $suppliers = ChannelPartner::where('cp_role', '1')->get();
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        return view('Admin.inventorySetting.addNewInventory')
            ->with('categories', $categories)
            ->with('suppliers', $suppliers)
            ->with('warehouses', $warehouses);
    }

    public function storeNewInventory(Request $request, InventoryService $inventoryService)
    {
        try {
            $txn_id = $this->getTxnId();
            $warehouseId = $request->warehouse_id;

            if ($warehouseId) {
                DB::beginTransaction();
                $productId = (int) $request->product_id;
                $qty = (int) $request->quantity;
                $serialRequired = $request->is_serialNumber_required == '1';
                $serialNumbers = $request->serial_numbers ?? [];

                $warehouseInv = WarehouseInventory::firstOrCreate(
                    ['warehouse_id' => $warehouseId, 'product_id' => $productId],
                    ['available_qty' => 0]
                );

                if ($serialRequired) {
                    if (count($serialNumbers) !== $qty) {
                        throw new \Exception('Serial count must match quantity');
                    }
                    foreach ($serialNumbers as $sn) {
                        $sn = (is_string($sn) && strtoupper(trim($sn)) === 'NA') ? $inventoryService->randomSerialNumber() : $sn;
                        $serial = ProductSerial::create([
                            'product_id' => $productId,
                            'serial_number' => $sn,
                            'status' => 'issue_to_warehouse',
                            'current_location' => 'warehouse',
                            'issue_to' => $warehouseId,
                        ]);

                        WarehouseInventoryTransaction::create([
                            'warehouse_id' => $warehouseId,
                            'product_id' => $productId,
                            'serial_id' => $serial->id,
                            'transaction_type' => 'IN',
                            'quantity' => 1,
                            'transfer_type' => 'direct_purchase',
                            'unit_price' => $request->unit_price,
                            'invoice_number' => $request->invoice_number,
                            'invoice_date' => $request->invoice_date,
                            'performed_by' => Auth::id(),
                            'txn_id' => $txn_id,
                            'remarks' => 'Direct purchase to warehouse',
                        ]);
                    }
                } else {
                    WarehouseInventoryTransaction::create([
                        'warehouse_id' => $warehouseId,
                        'product_id' => $productId,
                        'transaction_type' => 'IN',
                        'quantity' => $qty,
                        'transfer_type' => 'direct_purchase',
                        'unit_price' => $request->unit_price,
                        'invoice_number' => $request->invoice_number,
                        'invoice_date' => $request->invoice_date,
                        'performed_by' => Auth::id(),
                        'txn_id' => $txn_id,
                        'remarks' => 'Direct purchase to warehouse',
                    ]);
                }

                $warehouseInv->increment('available_qty', $qty);
                DB::commit();

                return redirect()->back()->with('success', 'Inventory added directly to warehouse successfully');
            }

            $inventoryService->addStock(
                $request->product_id,
                $request->quantity,
                $txn_id,
                $request->serial_numbers ?? [],
                $request->all()
            );

            return redirect()->back()->with('success', 'Inventory has been added successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error',  $e->getMessage());
        }
    }

    public function manageInventory()
    {
        $report = DB::table('products as p')
            ->leftJoin('product_categories as pc', 'pc.id', '=', 'p.category_id')
            ->leftJoin('product_sub_categories as psc', 'psc.id', '=', 'p.sub_category_id')
            ->leftJoin('product_inventory_transactions as t', 't.product_id', '=', 'p.id')
            ->leftJoin('product_inventories as pi', 'pi.product_id', '=', 'p.id')
            ->select(
                'p.id',
                'p.item_name',
                'p.item_code',
                'p.uom',
                'pc.category_name',
                'psc.sub_category_name',
                DB::raw("SUM(CASE WHEN t.transaction_type = 'IN' THEN t.quantity ELSE 0 END) as total_in"),
                DB::raw("SUM(CASE WHEN t.transaction_type = 'OUT' THEN t.quantity ELSE 0 END) as total_out"),
                DB::raw("COALESCE(pi.available_qty, 0) as current_stock")
            )
            ->groupBy('p.id', 'p.item_name', 'p.item_code', 'p.uom', 'pc.category_name', 'psc.sub_category_name', 'pi.available_qty')
            ->orderBy('p.item_name')
            ->get();

        return view('Admin.inventorySetting.manageInventory')->with('inventory_list', $report);
    }

    public function transferInventory()
    {
        $categories = ProductCategory::all();
        $cp_roles = ChannelPartnerRole::all();
        return view('Admin.inventorySetting.transferInventory')
            ->with('categories', $categories)
            ->with('cp_roles', $cp_roles);
    }

    public function storeTransferInventory(Request $request, InventoryService $inventoryService, CpInventoryService $cpInventoryService)
    {

        try {
            $txn_id = $this->getTxnId();
            $inventoryService->transferStock(
                $request->product_id,
                $request->quantity,
                $txn_id,
                $request->serial_numbers ?? [],
                $request->all()
            );

            $cpInventoryService->addCpStock(
                $request->sold_to,
                $request->product_id,
                $request->quantity,
                $txn_id,
                $request->serial_numbers ?? [],
                $request->all()
            );
        } catch (\Exception $e) {
            return redirect()->back()->with('error',  $e->getMessage());
        }

        return redirect()->back()->with('success', 'Inventory has been transferred successfully');
    }

    public function getProductAvailableQty(Request $request)
    {
        try{
        $productId = $request->input('product_id');
        if(Auth::user()->role_id != 1){
            $availableQty = DB::table('cp_product_inventories')
            ->where('product_id', $productId)
            ->where('cp_id', Auth::user()->cp_id)
            ->value('available_qty');
        }
        else{
             $availableQty = DB::table('product_inventories')
            ->where('product_id', $productId)
            ->value('available_qty');
        }
        
        return response()->json(['available_qty' => $availableQty ?? 0]);
        }
        catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getAvailableSerial(Request $request)
    {
        $productId = $request->input('product_id');

        if(Auth::user()->role_id != 1){
           $availableSerials = DB::table('product_serials')
            ->where('product_id', $productId)
            ->where('status', '!=', 'in_stock')
            ->where('issue_to', Auth::user()->cp_id)
            ->where('serial_number', '!=', null)
            ->pluck('serial_number');
        }
        else{
        $availableSerials = DB::table('product_serials')
            ->where('product_id', $productId)
            ->where('status', 'in_stock')
            ->where('issue_to', null)
            ->where('serial_number', '!=', null)
            ->pluck('serial_number');
        }
        return response()->json(['available_serials' => $availableSerials]);
    }

    public function getTxnId()
    {
        $prefix = 'INV';
        $datePart = date('Ymd');
        $randomPart = strtoupper(substr(uniqid(), -4));
        return $prefix . $datePart . $randomPart;
    }

    public function invTxnsAdmin()
    {
        try {
            $txn_list = ProductInventoryTransaction::with([
                'product',
                'channelPartner',
                'serialNumbers'
            ])
                ->orderByDesc('created_at')
                ->get();

            return view('Admin.inventorySetting.invTxnsAdmin')->with('txn_list', $txn_list);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->with('error',  $e->getMessage());
        }
    }

    public function addProductForm($id = null)
    {
        $categories = ProductCategory::with('subCategories')->get();
        $suppliers = ChannelPartner::where('cp_role', '1')->get();
        $products = Product::with('category')->orderBy('item_name')->get();
        $editProduct = null;
        if ($id) {
            $editProduct = Product::with(['customSpecs', 'inventory'])->findOrFail($id);
        }
        return view('Admin.inventorySetting.addProduct', compact('categories', 'suppliers', 'products', 'editProduct'));
    }

    public function getProductJson($id)
    {
        $product = Product::with(['customSpecs', 'inventory'])->findOrFail($id);
        return response()->json($product);
    }

    public function updateProduct(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);

            $product->current_sale_price = $request->current_sale_price ?: $product->current_sale_price;
            $product->is_featured = $request->is_featured ? 1 : 0;
            $product->save();

            if (!$request->has('existing_stock_only')) {
                $product->category_id = $request->category_id;
                $product->sub_category_id = $request->sub_category_id ?: null;
                $product->item_name = $request->product_name;
                $product->item_code = $request->item_code;
                $product->uom = $request->uom;
                $product->current_sale_price = $request->current_sale_price ?: null;
                $product->description = $request->description ?: null;
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
                $product->save();

                if ($request->hasFile('product_images')) {
                    foreach ($request->file('product_images') as $i => $img) {
                        if ($i >= 8) break;
                        $product->images()->create([
                            'image' => $img->store('product-gallery', 'public'),
                            'sort_order' => $i,
                        ]);
                    }
                }

                $product->customSpecs()->delete();
                if ($request->filled('custom_spec_names')) {
                    foreach ($request->custom_spec_names as $i => $name) {
                        if (empty(trim($name))) continue;
                        ProductCustomSpec::create([
                            'product_id' => $product->id,
                            'spec_name' => trim($name),
                            'spec_value' => trim($request->custom_spec_values[$i] ?? ''),
                            'sort_order' => $i,
                        ]);
                    }
                }
            }

            $qty = (int) ($request->quantity ?? 0);
            $inventory = ProductInventory::firstOrCreate(
                ['product_id' => $product->id],
                ['available_qty' => 0]
            );
            $oldQty = $inventory->available_qty;

            if ($qty !== $oldQty) {
                $diff = $qty - $oldQty;
                $inventory->update(['available_qty' => $qty]);
                $product->update(['quantity' => $qty]);

                ProductInventoryTransaction::create([
                    'product_id' => $product->id,
                    'transaction_type' => $diff > 0 ? 'IN' : 'OUT',
                    'quantity' => abs($diff),
                    'supplier_name' => $request->supplier_name ?: null,
                    'unit_price' => $request->unit_price ?: null,
                    'invoice_number' => $request->invoice_number ?: null,
                    'invoice_date' => $request->invoice_date ?: null,
                    'performed_by' => Auth::id(),
                    'txn_id' => $this->getTxnId(),
                    'remarks' => 'Product updated: stock ' . $oldQty . ' → ' . $qty,
                ]);
            }

            return redirect()->route('inventoryAddProduct')->with('success', $product->item_name . ' has been updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function storeProduct(Request $request, InventoryService $inventoryService)
    {
        try {
            $product = new Product();
            $product->category_id = $request->category_id;
            $product->sub_category_id = $request->sub_category_id ?: null;
            $product->item_name = $request->product_name;
            $product->item_code = $request->item_code;
            $product->uom = $request->uom;
            $product->current_sale_price = $request->current_sale_price ?: null;
            $product->quantity = $request->quantity ?? 0;
            $product->description = $request->description ?: null;
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

            if ($request->hasFile('product_images')) {
                foreach ($request->file('product_images') as $i => $img) {
                    if ($i >= 8) break;
                    $product->images()->create([
                        'image' => $img->store('product-gallery', 'public'),
                        'sort_order' => $i,
                    ]);
                }
            }

            if ($request->filled('custom_spec_names')) {
                foreach ($request->custom_spec_names as $i => $name) {
                    if (empty(trim($name))) continue;
                    ProductCustomSpec::create([
                        'product_id' => $product->id,
                        'spec_name' => trim($name),
                        'spec_value' => trim($request->custom_spec_values[$i] ?? ''),
                        'sort_order' => $i,
                    ]);
                }
            }

            $qty = (int) ($request->quantity ?? 0);
            if ($qty > 0) {
                $txn_id = $this->getTxnId();
                ProductInventory::create([
                    'product_id' => $product->id,
                    'available_qty' => $qty,
                ]);
                ProductInventoryTransaction::create([
                    'product_id' => $product->id,
                    'transaction_type' => 'IN',
                    'quantity' => $qty,
                    'supplier_name' => $request->supplier_name ?: null,
                    'unit_price' => $request->unit_price ?: null,
                    'invoice_number' => $request->invoice_number ?: null,
                    'invoice_date' => $request->invoice_date ?: null,
                    'performed_by' => Auth::id(),
                    'txn_id' => $txn_id,
                    'remarks' => 'Product added to inventory',
                ]);
            }

            return redirect()->route('inventoryEntries')->with('success', $product->item_name . ' has been added successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function quickStockUpdate(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'new_qty' => 'required|integer|min:0',
        ]);

        $productId = $request->product_id;
        $newQty = (int) $request->new_qty;

        $inventory = ProductInventory::firstOrCreate(
            ['product_id' => $productId],
            ['available_qty' => 0]
        );

        $oldQty = $inventory->available_qty;
        $diff = $newQty - $oldQty;

        if ($diff === 0) {
            return response()->json(['success' => true, 'message' => 'No change', 'stock' => $newQty]);
        }

        $inventory->update(['available_qty' => $newQty]);

        $txnType = $diff > 0 ? 'IN' : 'OUT';
        $product = Product::find($productId);
        ProductInventoryTransaction::create([
            'product_id' => $productId,
            'transaction_type' => $txnType,
            'quantity' => abs($diff),
            'unit_price' => $product->current_sale_price ?? 0,
            'performed_by' => Auth::id(),
            'txn_id' => $this->getTxnId(),
            'remarks' => 'Quick stock update: ' . $oldQty . ' → ' . $newQty,
        ]);

        if ($product) {
            $product->update(['quantity' => $newQty]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Stock updated: ' . $oldQty . ' → ' . $newQty,
            'stock' => $newQty,
        ]);
    }

    public function inventoryEntries()
    {
        $entries = ProductInventoryTransaction::with([
            'product.category',
            'product.customSpecs',
            'channelPartner',
            'serialNumbers',
        ])
            ->join('users', 'users.id', '=', 'product_inventory_transactions.performed_by')
            ->select('product_inventory_transactions.*', 'users.name as performer_name')
            ->orderByDesc('product_inventory_transactions.created_at')
            ->get();

        return view('Admin.inventorySetting.inventoryEntries', compact('entries'));
    }

    public function updateEntryRemarks(Request $request, $id)
    {
        $request->validate(['remarks' => 'nullable|string|max:1000']);
        $entry = ProductInventoryTransaction::findOrFail($id);
        $entry->remarks = $request->remarks;
        $entry->save();
        return response()->json(['success' => true]);
    }
}
