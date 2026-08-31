<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductInventory;
use App\Models\ProductInventoryTransaction;
use App\Models\ProductSerial;
use App\Models\Warehouse;
use App\Models\WarehouseInventory;
use App\Models\WarehouseInventoryTransaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WarehouseManagerController extends Controller
{
    private function warehouseId(Request $request)
    {
        return $request->attributes->get('managed_warehouse_id');
    }

    public function dashboard(Request $request)
    {
        $whId = $this->warehouseId($request);
        $warehouse = Warehouse::findOrFail($whId);

        $totalProducts = WarehouseInventory::where('warehouse_id', $whId)
            ->where('available_qty', '>', 0)->count();
        $totalUnits = WarehouseInventory::where('warehouse_id', $whId)->sum('available_qty');
        $totalIn = WarehouseInventoryTransaction::where('warehouse_id', $whId)
            ->where('transaction_type', 'IN')->sum('quantity');
        $totalOut = WarehouseInventoryTransaction::where('warehouse_id', $whId)
            ->where('transaction_type', 'OUT')->sum('quantity');

        $recentTxns = WarehouseInventoryTransaction::with('product')
            ->where('warehouse_id', $whId)
            ->orderBy('created_at', 'desc')
            ->take(10)->get();

        $lowStock = WarehouseInventory::with('product')
            ->where('warehouse_id', $whId)
            ->where('available_qty', '<=', 5)
            ->orderBy('available_qty', 'asc')
            ->take(10)->get();

        return view('warehouseManager.dashboard', compact(
            'warehouse', 'totalProducts', 'totalUnits', 'totalIn', 'totalOut', 'recentTxns', 'lowStock'
        ));
    }

    public function inventory(Request $request)
    {
        $whId = $this->warehouseId($request);
        $warehouse = Warehouse::findOrFail($whId);

        $items = WarehouseInventory::with(['product.category', 'product.subCategory'])
            ->where('warehouse_id', $whId)
            ->get();

        return view('warehouseManager.inventory', compact('warehouse', 'items'));
    }

    public function transactions(Request $request)
    {
        $whId = $this->warehouseId($request);
        $warehouse = Warehouse::findOrFail($whId);

        $txns = WarehouseInventoryTransaction::with(['product', 'performer'])
            ->where('warehouse_id', $whId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('warehouseManager.transactions', compact('warehouse', 'txns'));
    }

    public function transferForm(Request $request)
    {
        $whId = $this->warehouseId($request);
        $warehouse = Warehouse::findOrFail($whId);
        $categories = ProductCategory::orderBy('category_name')->get();
        $otherWarehouses = Warehouse::where('id', '!=', $whId)
            ->where('is_active', true)
            ->orderBy('name')->get();

        return view('warehouseManager.transfer', compact('warehouse', 'categories', 'otherWarehouses'));
    }

    public function storeTransfer(Request $request)
    {
        $request->validate([
            'to_warehouse_id' => 'required',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'nullable|numeric|min:0',
            'remarks' => 'nullable|string|max:500',
            'serial_numbers' => 'nullable|array',
        ]);

        $fromWhId = $this->warehouseId($request);
        $dest = $request->to_warehouse_id;
        $toMain = ($dest === 'main');

        if (!$toMain && !Warehouse::where('id', $dest)->exists()) {
            return redirect()->back()->with('error', 'Invalid destination warehouse.');
        }

        if (!$toMain && $fromWhId == $dest) {
            return redirect()->back()->with('error', 'Source and destination warehouse cannot be the same.');
        }

        DB::beginTransaction();
        try {
            $productId = $request->product_id;
            $qty = (int) $request->quantity;
            $fromWarehouseName = Warehouse::where('id', $fromWhId)->value('name');
            $product = Product::findOrFail($productId);
            $serialsRequested = array_values(array_filter($request->serial_numbers ?? []));

            if ($product->is_serialNumber_required && count($serialsRequested) !== $qty) {
                throw new Exception('Serial count (' . count($serialsRequested) . ') must match quantity (' . $qty . ').');
            }

            $fromInv = WarehouseInventory::where('warehouse_id', $fromWhId)
                ->where('product_id', $productId)
                ->lockForUpdate()->first();

            if (!$fromInv || $fromInv->available_qty < $qty) {
                throw new Exception('Insufficient stock in your warehouse.');
            }

            $txnId = ($toMain ? 'W2M-' : 'W2W-') . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
            $userRemarks = $request->remarks;

            if ($product->is_serialNumber_required && !empty($serialsRequested)) {
                foreach ($serialsRequested as $sn) {
                    $serial = ProductSerial::where('product_id', $productId)
                        ->where('serial_number', $sn)
                        ->where('warehouse_id', $fromWhId)
                        ->where('status', 'in_stock')
                        ->first();
                    if (!$serial) {
                        throw new Exception("Serial '{$sn}' not available in your warehouse.");
                    }
                    if ($toMain) {
                        $serial->update([
                            'warehouse_id' => null,
                            'current_location' => 'main',
                        ]);
                    } else {
                        $serial->update([
                            'warehouse_id' => $dest,
                            'current_location' => 'warehouse',
                        ]);
                    }
                    WarehouseInventoryTransaction::create([
                        'warehouse_id' => $fromWhId,
                        'product_id' => $productId,
                        'serial_id' => $serial->id,
                        'transaction_type' => 'OUT',
                        'quantity' => 1,
                        'transfer_type' => $toMain ? 'to_main' : 'to_warehouse',
                        'transfer_to' => $toMain ? null : $dest,
                        'unit_price' => $request->unit_price,
                        'performed_by' => Auth::id(),
                        'txn_id' => $txnId,
                        'remarks' => $userRemarks ?: ($toMain ? 'Transferred to Main Inventory' : 'Transferred to another warehouse'),
                    ]);
                    if ($toMain) {
                        ProductInventoryTransaction::create([
                            'product_id' => $productId,
                            'serial_id' => $serial->id,
                            'transaction_type' => 'IN',
                            'quantity' => 1,
                            'unit_price' => $request->unit_price,
                            'performed_by' => Auth::id(),
                            'txn_id' => $txnId,
                            'remarks' => $userRemarks ?: ('Received from warehouse: ' . $fromWarehouseName),
                        ]);
                    } else {
                        WarehouseInventoryTransaction::create([
                            'warehouse_id' => $dest,
                            'product_id' => $productId,
                            'serial_id' => $serial->id,
                            'transaction_type' => 'IN',
                            'quantity' => 1,
                            'transfer_type' => 'from_warehouse',
                            'transfer_to' => $fromWhId,
                            'unit_price' => $request->unit_price,
                            'performed_by' => Auth::id(),
                            'txn_id' => $txnId,
                            'remarks' => $userRemarks ?: ('Received from ' . $fromWarehouseName),
                        ]);
                    }
                }
            } else {
                WarehouseInventoryTransaction::create([
                    'warehouse_id' => $fromWhId,
                    'product_id' => $productId,
                    'transaction_type' => 'OUT',
                    'quantity' => $qty,
                    'transfer_type' => $toMain ? 'to_main' : 'to_warehouse',
                    'transfer_to' => $toMain ? null : $dest,
                    'unit_price' => $request->unit_price,
                    'performed_by' => Auth::id(),
                    'txn_id' => $txnId,
                    'remarks' => $userRemarks ?: ($toMain ? 'Transferred to Main Inventory' : 'Transferred to another warehouse'),
                ]);
            }

            $fromInv->decrement('available_qty', $qty);

            if ($toMain) {
                $mainInv = ProductInventory::firstOrCreate(
                    ['product_id' => $productId],
                    ['available_qty' => 0]
                );
                $mainInv->increment('available_qty', $qty);
                Product::where('id', $productId)->update(['quantity' => $mainInv->fresh()->available_qty]);

                if (!$product->is_serialNumber_required || empty($serialsRequested)) {
                    ProductInventoryTransaction::create([
                        'product_id' => $productId,
                        'transaction_type' => 'IN',
                        'quantity' => $qty,
                        'unit_price' => $request->unit_price,
                        'performed_by' => Auth::id(),
                        'txn_id' => $txnId,
                        'remarks' => $userRemarks ?: ('Received from warehouse: ' . $fromWarehouseName),
                    ]);
                }
            } else {
                $toInv = WarehouseInventory::firstOrCreate(
                    ['warehouse_id' => $dest, 'product_id' => $productId],
                    ['available_qty' => 0]
                );
                $toInv->increment('available_qty', $qty);

                if (!$product->is_serialNumber_required || empty($serialsRequested)) {
                    WarehouseInventoryTransaction::create([
                        'warehouse_id' => $dest,
                        'product_id' => $productId,
                        'transaction_type' => 'IN',
                        'quantity' => $qty,
                        'transfer_type' => 'from_warehouse',
                        'transfer_to' => $fromWhId,
                        'unit_price' => $request->unit_price,
                        'performed_by' => Auth::id(),
                        'txn_id' => $txnId,
                        'remarks' => $userRemarks ?: ('Received from ' . $fromWarehouseName),
                    ]);
                }
            }

            DB::commit();
            $destName = $toMain ? 'Main Inventory' : (Warehouse::where('id', $dest)->value('name'));
            return redirect()->back()->with('success', "Transferred {$qty} units to {$destName} successfully.");
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function getWarehouseQty(Request $request)
    {
        $whId = $this->warehouseId($request);
        $inv = WarehouseInventory::where('warehouse_id', $whId)
            ->where('product_id', $request->product_id)->first();
        return response()->json(['available_qty' => $inv ? $inv->available_qty : 0]);
    }

    public function getAvailableSerials(Request $request)
    {
        $whId = $this->warehouseId($request);
        $product = Product::find($request->product_id);
        if (!$product || !$product->is_serialNumber_required) {
            return response()->json(['is_serial_tracked' => false, 'serials' => []]);
        }
        $serials = ProductSerial::where('product_id', $request->product_id)
            ->where('status', 'in_stock')
            ->where('current_location', 'warehouse')
            ->where('warehouse_id', $whId)
            ->orderBy('created_at')
            ->pluck('serial_number');
        return response()->json(['is_serial_tracked' => true, 'serials' => $serials]);
    }

    public function getMyWarehouseProducts(Request $request)
    {
        $whId = $this->warehouseId($request);
        $rows = DB::table('warehouse_inventories as wi')
            ->join('products as p', 'p.id', '=', 'wi.product_id')
            ->leftJoin('product_categories as pc', 'pc.id', '=', 'p.category_id')
            ->leftJoin('product_sub_categories as psc', 'psc.id', '=', 'p.sub_category_id')
            ->where('wi.warehouse_id', $whId)
            ->where('wi.available_qty', '>', 0)
            ->select(
                'p.id as product_id',
                'p.item_name',
                'p.item_code',
                'p.category_id',
                'p.sub_category_id',
                'p.current_sale_price',
                'p.uom',
                'pc.category_name',
                'psc.sub_category_name',
                'wi.available_qty'
            )
            ->orderBy('p.item_name')
            ->get();

        return response()->json($rows);
    }
}
