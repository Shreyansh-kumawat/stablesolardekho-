<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
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
            'to_warehouse_id' => 'required|exists:warehouses,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'nullable|numeric|min:0',
            'remarks' => 'nullable|string|max:500',
        ]);

        $fromWhId = $this->warehouseId($request);
        $toWhId = $request->to_warehouse_id;

        if ($fromWhId == $toWhId) {
            return redirect()->back()->with('error', 'Source and destination warehouse cannot be the same.');
        }

        DB::beginTransaction();
        try {
            $productId = $request->product_id;
            $qty = (int) $request->quantity;

            $fromInv = WarehouseInventory::where('warehouse_id', $fromWhId)
                ->where('product_id', $productId)
                ->lockForUpdate()->first();

            if (!$fromInv || $fromInv->available_qty < $qty) {
                throw new Exception('Insufficient stock in your warehouse.');
            }

            $toInv = WarehouseInventory::firstOrCreate(
                ['warehouse_id' => $toWhId, 'product_id' => $productId],
                ['available_qty' => 0]
            );

            $txnId = 'W2W-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

            WarehouseInventoryTransaction::create([
                'warehouse_id' => $fromWhId,
                'product_id' => $productId,
                'transaction_type' => 'OUT',
                'quantity' => $qty,
                'transfer_type' => 'to_warehouse',
                'transfer_to' => $toWhId,
                'unit_price' => $request->unit_price,
                'performed_by' => Auth::id(),
                'txn_id' => $txnId,
                'remarks' => $request->remarks ?? 'Transferred to another warehouse',
            ]);

            WarehouseInventoryTransaction::create([
                'warehouse_id' => $toWhId,
                'product_id' => $productId,
                'transaction_type' => 'IN',
                'quantity' => $qty,
                'transfer_type' => 'from_warehouse',
                'transfer_to' => $fromWhId,
                'unit_price' => $request->unit_price,
                'performed_by' => Auth::id(),
                'txn_id' => $txnId,
                'remarks' => $request->remarks ?? 'Received from another warehouse',
            ]);

            $fromInv->decrement('available_qty', $qty);
            $toInv->increment('available_qty', $qty);

            DB::commit();
            return redirect()->back()->with('success', "Transferred {$qty} units successfully.");
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
}
