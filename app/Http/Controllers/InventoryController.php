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
use App\Services\SerialExcelParser;
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
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        $editProduct = null;
        if ($id) {
            $editProduct = Product::with(['customSpecs', 'inventory'])->findOrFail($id);
        }
        return view('Admin.inventorySetting.addProduct', compact('categories', 'suppliers', 'products', 'warehouses', 'editProduct'));
    }

    public function getProductJson($id)
    {
        $product = Product::with(['customSpecs', 'inventory'])->findOrFail($id);
        $mainQty = (int) ($product->inventory->available_qty ?? 0);
        $whQty = (int) WarehouseInventory::where('product_id', $product->id)->sum('available_qty');
        $data = $product->toArray();
        $data['main_stock'] = $mainQty;
        $data['warehouse_stock'] = $whQty;
        $data['total_stock'] = $mainQty + $whQty;
        return response()->json($data);
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
            $warehouseId = $request->destination_warehouse_id;
            $inventory = ProductInventory::firstOrCreate(
                ['product_id' => $product->id],
                ['available_qty' => 0]
            );
            $oldQty = $inventory->available_qty;

            // Serial handling removed from this flow — use "Bulk Serial Upload" tab for serial-tracked products
            $serialsInput = [];

            if ($warehouseId) {
                $addQty = $qty - $oldQty;
                if ($addQty > 0) {
                    $txn_id = $this->getTxnId();
                    [$gstAmt, $totWithGst] = $this->gstFields($request->unit_price, $request->gst_percent);

                    ProductInventoryTransaction::create([
                        'product_id' => $product->id,
                        'transaction_type' => 'IN',
                        'quantity' => $addQty,
                        'supplier_name' => $request->supplier_name ?: null,
                        'unit_price' => $request->unit_price ?: null,
                        'gst_percent' => $request->gst_percent ?: null,
                        'gst_amount' => $gstAmt,
                        'total_with_gst' => $totWithGst,
                        'invoice_number' => $request->invoice_number ?: null,
                        'invoice_date' => $request->invoice_date ?: null,
                        'performed_by' => Auth::id(),
                        'txn_id' => $txn_id,
                        'remarks' => 'Stock received',
                    ]);

                    $warehouseName = Warehouse::where('id', $warehouseId)->value('name');
                    ProductInventoryTransaction::create([
                        'product_id' => $product->id,
                        'transaction_type' => 'OUT',
                        'quantity' => $addQty,
                        'performed_by' => Auth::id(),
                        'txn_id' => $txn_id,
                        'remarks' => 'Transferred to warehouse: ' . $warehouseName,
                    ]);

                    $warehouseInv = WarehouseInventory::firstOrCreate(
                        ['warehouse_id' => $warehouseId, 'product_id' => $product->id],
                        ['available_qty' => 0]
                    );
                    $warehouseInv->increment('available_qty', $addQty);

                    WarehouseInventoryTransaction::create([
                        'warehouse_id' => $warehouseId,
                        'product_id' => $product->id,
                        'transaction_type' => 'IN',
                        'quantity' => $addQty,
                        'transfer_type' => 'direct_purchase',
                        'unit_price' => $request->unit_price ?: null,
                        'gst_percent' => $request->gst_percent ?: null,
                        'gst_amount' => $gstAmt,
                        'total_with_gst' => $totWithGst,
                        'invoice_number' => $request->invoice_number ?: null,
                        'invoice_date' => $request->invoice_date ?: null,
                        'performed_by' => Auth::id(),
                        'txn_id' => $txn_id,
                        'remarks' => 'Stock received directly at warehouse',
                    ]);

                    if ($product->is_serialNumber_required && !empty($serialsInput)) {
                        foreach ($serialsInput as $sn) {
                            ProductSerial::create([
                                'product_id' => $product->id,
                                'serial_number' => $sn,
                                'status' => 'in_stock',
                                'current_location' => 'warehouse',
                                'warehouse_id' => $warehouseId,
                                'batch_txn_id' => $txn_id,
                                'purchase_price' => $request->unit_price ?: null,
                                'invoice_number' => $request->invoice_number ?: null,
                                'invoice_date' => $request->invoice_date ?: null,
                                'supplier_name' => $request->supplier_name ?: null,
                            ]);
                        }
                    }
                }
            } else if ($qty !== $oldQty) {
                $diff = $qty - $oldQty;
                $inventory->update(['available_qty' => $qty]);
                $product->update(['quantity' => $qty]);
                $txnIdMain = $this->getTxnId();
                [$gstAmtMain, $totWithGstMain] = $this->gstFields($request->unit_price, $request->gst_percent);

                ProductInventoryTransaction::create([
                    'product_id' => $product->id,
                    'transaction_type' => $diff > 0 ? 'IN' : 'OUT',
                    'quantity' => abs($diff),
                    'supplier_name' => $request->supplier_name ?: null,
                    'unit_price' => $request->unit_price ?: null,
                    'gst_percent' => $request->gst_percent ?: null,
                    'gst_amount' => $gstAmtMain,
                    'total_with_gst' => $totWithGstMain,
                    'invoice_number' => $request->invoice_number ?: null,
                    'invoice_date' => $request->invoice_date ?: null,
                    'performed_by' => Auth::id(),
                    'txn_id' => $txnIdMain,
                    'remarks' => 'Product updated: stock ' . $oldQty . ' > ' . $qty,
                ]);

                if ($product->is_serialNumber_required && $diff > 0 && !empty($serialsInput)) {
                    foreach ($serialsInput as $sn) {
                        ProductSerial::create([
                            'product_id' => $product->id,
                            'serial_number' => $sn,
                            'status' => 'in_stock',
                            'current_location' => 'main',
                            'warehouse_id' => null,
                            'batch_txn_id' => $txnIdMain,
                            'purchase_price' => $request->unit_price ?: null,
                            'gst_percent' => $request->gst_percent ?: null,
                            'invoice_number' => $request->invoice_number ?: null,
                            'invoice_date' => $request->invoice_date ?: null,
                            'supplier_name' => $request->supplier_name ?: null,
                        ]);
                    }
                }
            }

            return redirect()->route('inventoryAddProduct')->with('success', $product->item_name . ' has been updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function storeProduct(Request $request, InventoryService $inventoryService)
    {
        try {
            DB::beginTransaction();

            if ($request->filled('item_code')) {
                $existing = Product::where('item_code', $request->item_code)->first();
                if ($existing) {
                    DB::rollBack();
                    return redirect()->back()->withInput()->with('error',
                        'Product Code "' . $request->item_code . '" already exists (assigned to "' . $existing->item_name . '"). '
                        . 'To add more stock, please use the "Existing Product" tab and select this product. '
                        . 'To add a different product, please choose a unique Product Code.'
                    );
                }
            }

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
            $product->is_serialNumber_required = 0;
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
            $warehouseId = $request->destination_warehouse_id;

            // Serial handling removed from New/Existing tabs — use Bulk Serial Upload for serial-tracked products
            $serialsInput = [];

            if ($qty > 0) {
                $txn_id = $this->getTxnId();

                if ($warehouseId) {
                    ProductInventory::firstOrCreate(
                        ['product_id' => $product->id],
                        ['available_qty' => 0]
                    );

                    [$gstAmt, $totWithGst] = $this->gstFields($request->unit_price, $request->gst_percent);
                    ProductInventoryTransaction::create([
                        'product_id' => $product->id,
                        'transaction_type' => 'IN',
                        'quantity' => $qty,
                        'supplier_name' => $request->supplier_name ?: null,
                        'unit_price' => $request->unit_price ?: null,
                        'gst_percent' => $request->gst_percent ?: null,
                        'gst_amount' => $gstAmt,
                        'total_with_gst' => $totWithGst,
                        'invoice_number' => $request->invoice_number ?: null,
                        'invoice_date' => $request->invoice_date ?: null,
                        'performed_by' => Auth::id(),
                        'txn_id' => $txn_id,
                        'remarks' => 'Stock received',
                    ]);

                    $warehouseName = Warehouse::where('id', $warehouseId)->value('name');
                    ProductInventoryTransaction::create([
                        'product_id' => $product->id,
                        'transaction_type' => 'OUT',
                        'quantity' => $qty,
                        'performed_by' => Auth::id(),
                        'txn_id' => $txn_id,
                        'remarks' => 'Transferred to warehouse: ' . $warehouseName,
                    ]);

                    $warehouseInv = WarehouseInventory::firstOrCreate(
                        ['warehouse_id' => $warehouseId, 'product_id' => $product->id],
                        ['available_qty' => 0]
                    );
                    $warehouseInv->increment('available_qty', $qty);

                    WarehouseInventoryTransaction::create([
                        'warehouse_id' => $warehouseId,
                        'product_id' => $product->id,
                        'transaction_type' => 'IN',
                        'quantity' => $qty,
                        'transfer_type' => 'direct_purchase',
                        'unit_price' => $request->unit_price ?: null,
                        'gst_percent' => $request->gst_percent ?: null,
                        'gst_amount' => $gstAmt,
                        'total_with_gst' => $totWithGst,
                        'invoice_number' => $request->invoice_number ?: null,
                        'invoice_date' => $request->invoice_date ?: null,
                        'performed_by' => Auth::id(),
                        'txn_id' => $txn_id,
                        'remarks' => 'Stock received directly at warehouse',
                    ]);
                } else {
                    [$gstAmt, $totWithGst] = $this->gstFields($request->unit_price, $request->gst_percent);
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
                        'gst_percent' => $request->gst_percent ?: null,
                        'gst_amount' => $gstAmt,
                        'total_with_gst' => $totWithGst,
                        'invoice_number' => $request->invoice_number ?: null,
                        'invoice_date' => $request->invoice_date ?: null,
                        'performed_by' => Auth::id(),
                        'txn_id' => $txn_id,
                        'remarks' => 'Product added to inventory',
                    ]);
                }

                if ($product->is_serialNumber_required && !empty($serialsInput)) {
                    foreach ($serialsInput as $sn) {
                        ProductSerial::create([
                            'product_id' => $product->id,
                            'serial_number' => $sn,
                            'status' => 'in_stock',
                            'current_location' => $warehouseId ? 'warehouse' : 'main',
                            'warehouse_id' => $warehouseId ?: null,
                            'batch_txn_id' => $txn_id,
                            'purchase_price' => $request->unit_price ?: null,
                            'gst_percent' => $request->gst_percent ?: null,
                            'invoice_number' => $request->invoice_number ?: null,
                            'invoice_date' => $request->invoice_date ?: null,
                            'supplier_name' => $request->supplier_name ?: null,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('inventoryEntries')->with('success', $product->item_name . ' has been added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /** Parse serials from a pasted textarea (split on ANY whitespace: space, tab, newline, comma, semicolon). */
    private function parseSerialsInput($input): array
    {
        if (!$input) return [];
        // Split on any whitespace, comma, semicolon — gap = new serial
        $parts = preg_split('/[\s,;]+/', (string) $input, -1, PREG_SPLIT_NO_EMPTY);
        $out = [];
        foreach ($parts as $p) {
            $t = trim($p);
            if ($t !== '') $out[] = $t;
        }
        return array_values(array_unique($out));
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

        // Attach batch serials (serials created in same txn_id as this entry)
        $batchTxns = $entries->pluck('txn_id')->filter()->unique()->values()->toArray();
        $batchSerials = ProductSerial::whereIn('batch_txn_id', $batchTxns)
            ->get()
            ->groupBy(function ($s) { return $s->batch_txn_id . '_' . $s->product_id; });

        foreach ($entries as $entry) {
            $key = ($entry->txn_id ?? '') . '_' . ($entry->product_id ?? '');
            $entry->batch_serials = $batchSerials->get($key, collect())->values();
        }

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

    /** Compute GST amount + total_with_gst from unit_price & gst_percent */
    private function gstFields($unitPrice, $gstPercent): array
    {
        $up = $unitPrice !== null && $unitPrice !== '' ? (float) $unitPrice : null;
        $gp = $gstPercent !== null && $gstPercent !== '' ? (float) $gstPercent : null;
        if ($up === null) return [null, null];
        $amt = $gp !== null ? round($up * $gp / 100, 2) : null;
        $tot = $gp !== null ? round($up + $amt, 2) : round($up, 2);
        return [$amt, $tot];
    }

    public function getLastPurchase($productId)
    {
        $entry = ProductInventoryTransaction::where('product_id', $productId)
            ->where('transaction_type', 'IN')
            ->whereNotNull('unit_price')
            ->orderByDesc('created_at')
            ->first(['unit_price', 'gst_percent', 'gst_amount', 'total_with_gst', 'supplier_name', 'created_at']);
        if (!$entry) return response()->json(null);
        return response()->json([
            'unit_price' => $entry->unit_price,
            'gst_percent' => $entry->gst_percent,
            'gst_amount' => $entry->gst_amount,
            'total_with_gst' => $entry->total_with_gst,
            'supplier_name' => $entry->supplier_name,
            'when' => $entry->created_at ? $entry->created_at->format('d M Y') : null,
        ]);
    }

    public function updateEntryPrice(Request $request, $id)
    {
        $request->validate([
            'unit_price' => 'nullable|numeric|min:0',
            'gst_percent' => 'nullable|numeric|min:0|max:100',
        ]);
        $entry = ProductInventoryTransaction::findOrFail($id);
        $entry->unit_price = $request->unit_price ?: null;
        $entry->gst_percent = $request->gst_percent ?: null;
        if ($request->filled('unit_price')) {
            $price = (float) $request->unit_price;
            $gstPct = (float) ($request->gst_percent ?? 0);
            $entry->gst_amount = round($price * $gstPct / 100, 2);
            $entry->total_with_gst = round($price + $entry->gst_amount, 2);
        } else {
            $entry->gst_amount = null;
            $entry->total_with_gst = null;
        }
        $entry->save();

        // Also update linked serials and warehouse transactions in same batch
        if ($entry->txn_id) {
            ProductSerial::where('batch_txn_id', $entry->txn_id)
                ->update([
                    'purchase_price' => $request->unit_price ?: null,
                    'gst_percent' => $request->gst_percent ?: null,
                ]);
            WarehouseInventoryTransaction::where('txn_id', $entry->txn_id)
                ->where('transaction_type', 'IN')
                ->update([
                    'unit_price' => $request->unit_price ?: null,
                    'gst_percent' => $request->gst_percent ?: null,
                    'gst_amount' => $entry->gst_amount,
                    'total_with_gst' => $entry->total_with_gst,
                ]);
        }
        return response()->json([
            'success' => true,
            'gst_amount' => $entry->gst_amount,
            'total_with_gst' => $entry->total_with_gst,
        ]);
    }

    public function supplierReport(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $q = ProductInventoryTransaction::where('transaction_type', 'IN')
            ->whereNotNull('supplier_name')
            ->where('supplier_name', '!=', '');
        if ($from) $q->whereDate('created_at', '>=', $from);
        if ($to) $q->whereDate('created_at', '<=', $to);

        $summary = (clone $q)
            ->selectRaw("supplier_name, COUNT(DISTINCT product_id) as product_count, SUM(quantity) as total_qty, SUM(COALESCE(total_with_gst, unit_price * quantity, 0)) as total_value")
            ->groupBy('supplier_name')
            ->orderBy('supplier_name')
            ->get();

        $selectedSupplier = $request->input('supplier');
        $details = collect();
        if ($selectedSupplier) {
            $details = ProductInventoryTransaction::with(['product:id,item_name,item_code'])
                ->where('transaction_type', 'IN')
                ->where('supplier_name', $selectedSupplier)
                ->when($from, fn($q) => $q->whereDate('created_at', '>=', $from))
                ->when($to, fn($q) => $q->whereDate('created_at', '<=', $to))
                ->orderByDesc('created_at')
                ->get();
        }

        return view('Admin.inventorySetting.supplierReport', compact('summary', 'details', 'selectedSupplier', 'from', 'to'));
    }

    public function supplierReportExport(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $supplier = $request->input('supplier');
        $q = ProductInventoryTransaction::with('product')->where('transaction_type', 'IN')
            ->whereNotNull('supplier_name')
            ->where('supplier_name', '!=', '');
        if ($from) $q->whereDate('created_at', '>=', $from);
        if ($to) $q->whereDate('created_at', '<=', $to);
        if ($supplier) $q->where('supplier_name', $supplier);
        $rows = $q->orderBy('supplier_name')->orderByDesc('created_at')->get();

        $filename = 'supplier_purchase_report_' . date('Y-m-d') . '.csv';

        return new \Symfony\Component\HttpFoundation\StreamedResponse(function () use ($rows) {
            $h = fopen('php://output', 'w');
            fputcsv($h, ['Date', 'Supplier', 'Product', 'Product Code', 'Qty', 'Unit Price', 'GST %', 'GST Amount', 'Total with GST', 'Invoice #', 'Invoice Date', 'Txn ID', 'Remarks']);
            foreach ($rows as $r) {
                fputcsv($h, [
                    $r->created_at ? $r->created_at->format('d-m-Y H:i') : '',
                    $r->supplier_name,
                    $r->product->item_name ?? '-',
                    $r->product->item_code ?? '-',
                    $r->quantity,
                    $r->unit_price ?? '-',
                    $r->gst_percent ?? '-',
                    $r->gst_amount ?? '-',
                    $r->total_with_gst ?? '-',
                    $r->invoice_number ?? '-',
                    $r->invoice_date ?? '-',
                    $r->txn_id ?? '-',
                    $r->remarks ?? '-',
                ]);
            }
            fclose($h);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function getProductsByCategory($categoryId)
    {
        $products = Product::where('category_id', $categoryId)
            ->orderBy('item_name')
            ->get(['id', 'item_name', 'item_code', 'sub_category_id', 'uom', 'current_sale_price', 'is_serialNumber_required']);
        return response()->json($products);
    }

    /* ═══════════════════════════════════════════════════════════
       SERIAL NUMBER TRACKING
       ═══════════════════════════════════════════════════════════ */

    public function parseSerialExcel(Request $request, SerialExcelParser $parser)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,xls,csv,txt|max:10240']);
        try {
            $data = $parser->parse($request->file('file')->getRealPath());
            $matches = $parser->suggestProductMatches($data);
            $allSerials = collect($data)->flatMap(fn($p) => $p['serials'])->unique()->values()->toArray();
            $dbDupes = $parser->checkDbDuplicates($allSerials);
            foreach ($data as &$block) {
                $block['db_duplicates'] = array_values(array_intersect($block['serials'], $dbDupes));
                if (!empty($block['db_duplicates'])) {
                    $block['warnings'][] = 'These serials already exist in database: ' . implode(', ', array_slice($block['db_duplicates'], 0, 5)) . (count($block['db_duplicates']) > 5 ? ' (+more)' : '');
                }
                $block['suggested_product'] = $matches[$block['product_name']] ?? null;
            }
            return response()->json(['success' => true, 'products' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to parse Excel: ' . $e->getMessage()], 422);
        }
    }

    public function findProductByName(Request $request)
    {
        $name = trim((string) $request->input('name', ''));
        if ($name === '') return response()->json(['exists' => false]);
        $product = Product::whereRaw('LOWER(item_name) = ?', [strtolower($name)])->first();
        if (!$product) {
            // Fuzzy
            $product = Product::where('item_name', 'like', '%' . $name . '%')->first();
        }
        if (!$product) return response()->json(['exists' => false]);
        return response()->json([
            'exists' => true,
            'id' => $product->id,
            'item_name' => $product->item_name,
            'item_code' => $product->item_code,
            'category_id' => $product->category_id,
            'sub_category_id' => $product->sub_category_id,
        ]);
    }

    public function checkSerialDuplicates(Request $request)
    {
        $serials = $request->input('serials', []);
        if (!is_array($serials)) $serials = [];
        $serials = array_values(array_filter(array_map('trim', $serials)));
        $existing = ProductSerial::whereIn('serial_number', $serials)->pluck('serial_number')->toArray();
        return response()->json(['duplicates' => $existing]);
    }

    public function bulkStoreFromExcel(Request $request)
    {
        $request->validate([
            'destination_type' => 'required|in:main,warehouse',
            'destination_warehouse_id' => 'nullable|exists:warehouses,id',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'nullable|exists:products,id',
            'products.*.item_name' => 'required|string|max:500',
            'products.*.category_id' => 'nullable|exists:product_categories,id',
            'products.*.sub_category_id' => 'nullable|exists:product_sub_categories,id',
            'products.*.qty' => 'required|integer|min:1',
            'products.*.unit_price' => 'nullable|numeric|min:0',
            'products.*.gst_percent' => 'nullable|numeric|min:0|max:100',
            'products.*.serials' => 'required|array|min:1',
            'products.*.serials.*' => 'required|string|max:100',
            'invoice_number' => 'nullable|string|max:100',
            'invoice_date' => 'nullable|date',
            'supplier_name' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $destType = $request->destination_type;
            $whId = $destType === 'warehouse' ? $request->destination_warehouse_id : null;
            if ($destType === 'warehouse' && !$whId) {
                throw new \Exception('Warehouse must be selected for warehouse destination.');
            }

            // Pre-fetch all existing serials from DB to auto-skip
            $allSerialsInput = collect($request->products)->flatMap(fn($p) => $p['serials'])->map('trim')->filter()->values()->toArray();
            $existingInDb = ProductSerial::whereIn('serial_number', $allSerialsInput)->pluck('serial_number')->toArray();
            $existingSet = array_flip($existingInDb);

            $results = [];
            $skippedSummary = [];
            $seenInThisRun = [];
            foreach ($request->products as $item) {
                $rawSerials = array_values(array_filter(array_map('trim', $item['serials'])));

                // Filter: skip serials already in DB OR seen earlier in this run
                $validSerials = [];
                $skippedSerials = [];
                foreach ($rawSerials as $sn) {
                    $key = strtoupper($sn);
                    if (isset($existingSet[$sn]) || isset($seenInThisRun[$key])) {
                        $skippedSerials[] = $sn;
                    } else {
                        $validSerials[] = $sn;
                        $seenInThisRun[$key] = true;
                    }
                }

                if (empty($validSerials)) {
                    $skippedSummary[] = [
                        'product' => $item['item_name'],
                        'skipped_count' => count($skippedSerials),
                        'skipped' => array_slice($skippedSerials, 0, 20),
                        'saved_count' => 0,
                    ];
                    continue; // Skip this product entirely - no valid serials
                }

                // ── Auto-match on product name (fuzzy first-word / exact name) ──
                $product = null;
                if (!empty($item['product_id'])) {
                    $product = Product::find($item['product_id']);
                }
                if (!$product) {
                    $product = Product::whereRaw('LOWER(item_name) = ?', [strtolower(trim($item['item_name']))])->first();
                }
                if (!$product) {
                    // Create new
                    if (empty($item['category_id'])) {
                        throw new \Exception('Product "' . $item['item_name'] . '" is new: please assign a category.');
                    }
                    $product = new Product();
                    $product->category_id = $item['category_id'];
                    $product->sub_category_id = $item['sub_category_id'] ?? null;
                    $product->item_name = $item['item_name'];
                    $product->item_code = $item['item_name'];
                    $product->uom = $item['uom'] ?? 'Piece';
                    $product->is_active = 1;
                    $product->is_serialNumber_required = 1;
                    $product->quantity = 0;
                    $product->save();
                }

                if (!$product->is_serialNumber_required) {
                    $product->update(['is_serialNumber_required' => 1]);
                }

                // Use only validSerials from here onwards
                $serials = $validSerials;
                if (!empty($skippedSerials)) {
                    $skippedSummary[] = [
                        'product' => $item['item_name'],
                        'skipped_count' => count($skippedSerials),
                        'skipped' => array_slice($skippedSerials, 0, 20),
                        'saved_count' => count($serials),
                    ];
                }

                // Qty always = actual saved serials count
                $qty = count($serials);
                $unitPrice = $item['unit_price'] !== null && $item['unit_price'] !== '' ? (float) $item['unit_price'] : null;
                $gstPercent = isset($item['gst_percent']) && $item['gst_percent'] !== '' ? (float) $item['gst_percent'] : null;
                [$gstAmt, $totWithGst] = $this->gstFields($unitPrice, $gstPercent);
                $txnId = 'BULK-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

                $inventory = ProductInventory::firstOrCreate(
                    ['product_id' => $product->id],
                    ['available_qty' => 0]
                );

                ProductInventoryTransaction::create([
                    'product_id' => $product->id,
                    'transaction_type' => 'IN',
                    'quantity' => $qty,
                    'supplier_name' => $request->supplier_name ?: null,
                    'unit_price' => $unitPrice,
                    'gst_percent' => $gstPercent,
                    'gst_amount' => $gstAmt,
                    'total_with_gst' => $totWithGst,
                    'invoice_number' => $request->invoice_number ?: null,
                    'invoice_date' => $request->invoice_date ?: null,
                    'performed_by' => Auth::id(),
                    'txn_id' => $txnId,
                    'remarks' => 'Bulk upload with ' . $qty . ' serials',
                ]);

                if ($destType === 'warehouse') {
                    ProductInventoryTransaction::create([
                        'product_id' => $product->id,
                        'transaction_type' => 'OUT',
                        'quantity' => $qty,
                        'performed_by' => Auth::id(),
                        'txn_id' => $txnId,
                        'remarks' => 'Transferred to warehouse (bulk upload)',
                    ]);
                    $whInv = WarehouseInventory::firstOrCreate(
                        ['warehouse_id' => $whId, 'product_id' => $product->id],
                        ['available_qty' => 0]
                    );
                    $whInv->increment('available_qty', $qty);

                    WarehouseInventoryTransaction::create([
                        'warehouse_id' => $whId,
                        'product_id' => $product->id,
                        'transaction_type' => 'IN',
                        'quantity' => $qty,
                        'transfer_type' => 'direct_purchase',
                        'unit_price' => $unitPrice,
                        'gst_percent' => $gstPercent,
                        'gst_amount' => $gstAmt,
                        'total_with_gst' => $totWithGst,
                        'invoice_number' => $request->invoice_number ?: null,
                        'invoice_date' => $request->invoice_date ?: null,
                        'performed_by' => Auth::id(),
                        'txn_id' => $txnId,
                        'remarks' => 'Bulk upload with serials',
                    ]);
                } else {
                    $inventory->increment('available_qty', $qty);
                    Product::where('id', $product->id)->update(['quantity' => $inventory->fresh()->available_qty]);
                }

                foreach ($serials as $sn) {
                    ProductSerial::create([
                        'product_id' => $product->id,
                        'serial_number' => $sn,
                        'status' => 'in_stock',
                        'current_location' => $destType === 'warehouse' ? 'warehouse' : 'main',
                        'warehouse_id' => $whId,
                        'batch_txn_id' => $txnId,
                        'purchase_price' => $unitPrice,
                        'gst_percent' => $gstPercent,
                        'invoice_number' => $request->invoice_number ?: null,
                        'invoice_date' => $request->invoice_date ?: null,
                        'supplier_name' => $request->supplier_name ?: null,
                    ]);
                }

                $results[] = ['product_id' => $product->id, 'item_name' => $product->item_name, 'qty' => $qty, 'txn_id' => $txnId];
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Bulk stock uploaded successfully.',
                'results' => $results,
                'skipped_summary' => $skippedSummary,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function downloadSerialTemplate()
    {
        $filename = 'serial_upload_template.csv';
        $content = "# Serial Number Upload Template\n"
                 . "# Format: Product Name in one row, then serial numbers below it (one per row).\n"
                 . "# Everything after 'SR.NO' (or 'SERIAL') label is treated as serial numbers.\n"
                 . "# Blank rows separate product blocks. Any other extra text is ignored.\n"
                 . "\n"
                 . "Product Name,Serial Number\n"
                 . "SAMPLE PRODUCT NAME - 3600W INVERTER,\n"
                 . ",SERIAL-001-ABC\n"
                 . ",SERIAL-002-ABC\n"
                 . ",SERIAL-003-ABC\n"
                 . "\n"
                 . "ANOTHER PRODUCT - 5000W INVERTER,\n"
                 . ",SERIAL-101-XYZ\n"
                 . ",SERIAL-102-XYZ\n";
        return response($content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function serialSearchPage()
    {
        $products = Product::where('is_serialNumber_required', 1)
            ->orderBy('item_name')
            ->get(['id', 'item_name', 'item_code']);
        return view('Admin.inventorySetting.serialSearch', compact('products'));
    }

    public function getSerialsForProduct($productId)
    {
        $product = Product::findOrFail($productId);
        $serials = ProductSerial::where('product_id', $productId)
            ->with('warehouse:id,name')
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($s) {
                return [
                    'id' => $s->id,
                    'serial_number' => $s->serial_number,
                    'status' => $s->status,
                    'current_location' => $s->current_location,
                    'warehouse_name' => $s->warehouse->name ?? null,
                    'customer_order_id' => $s->customer_order_id,
                    'batch_txn_id' => $s->batch_txn_id,
                    'purchase_price' => $s->purchase_price,
                    'invoice_number' => $s->invoice_number,
                    'invoice_date' => $s->invoice_date ? $s->invoice_date->format('d-m-Y') : null,
                    'supplier_name' => $s->supplier_name,
                    'created_at' => $s->created_at->format('d-m-Y H:i'),
                ];
            });
        return response()->json(['product' => $product, 'serials' => $serials]);
    }

    public function getSerialHistory($serialId)
    {
        $serial = ProductSerial::with(['product', 'warehouse'])->findOrFail($serialId);
        $txns = ProductInventoryTransaction::where('serial_id', $serialId)
            ->orWhere('txn_id', $serial->batch_txn_id)
            ->orderBy('created_at')
            ->get(['id','transaction_type','quantity','txn_id','remarks','supplier_name','unit_price','invoice_number','created_at']);
        $whTxns = WarehouseInventoryTransaction::where('serial_id', $serialId)
            ->orWhere('txn_id', $serial->batch_txn_id)
            ->with('warehouse:id,name')
            ->orderBy('created_at')
            ->get();
        $timeline = collect();
        foreach ($txns as $t) {
            $timeline->push([
                'date' => $t->created_at->format('d M Y, h:i A'),
                'ts' => $t->created_at->timestamp,
                'title' => 'Main Inventory: ' . $t->transaction_type,
                'detail' => $t->remarks ?: '-',
                'meta' => 'TXN: ' . $t->txn_id,
            ]);
        }
        foreach ($whTxns as $t) {
            $timeline->push([
                'date' => $t->created_at->format('d M Y, h:i A'),
                'ts' => $t->created_at->timestamp,
                'title' => ($t->warehouse->name ?? 'Warehouse') . ': ' . $t->transaction_type,
                'detail' => $t->remarks ?: '-',
                'meta' => 'TXN: ' . $t->txn_id,
            ]);
        }
        $timeline = $timeline->sortBy('ts')->values();

        return response()->json([
            'serial' => [
                'id' => $serial->id,
                'serial_number' => $serial->serial_number,
                'product' => $serial->product->item_name ?? '',
                'status' => $serial->status,
                'current_location' => $serial->current_location,
                'warehouse' => $serial->warehouse->name ?? null,
                'customer_order_id' => $serial->customer_order_id,
                'purchase_price' => $serial->purchase_price,
                'supplier_name' => $serial->supplier_name,
                'invoice_number' => $serial->invoice_number,
                'invoice_date' => $serial->invoice_date ? $serial->invoice_date->format('d-m-Y') : null,
            ],
            'timeline' => $timeline,
        ]);
    }
}
