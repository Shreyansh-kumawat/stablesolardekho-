<?php

namespace App\Http\Controllers;

use App\Models\ChannelPartner;
use App\Models\ChannelPartnerRole;
use App\Models\CpMaterialLedger;
use App\Models\CpOrder;
use App\Models\CpProductInventory;
use App\Models\CpProductInventoryTransaction;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductInventoryTransaction;
use App\Services\wareHouseInventoryService;
use App\Services\WarehouseToCpInventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CpInventoryController extends Controller
{
    public function cpInventory()
    {
        $cpId = Auth::user()->cp_id;

        $inventory = CpProductInventory::where('cp_product_inventories.cp_id', $cpId)
            ->join('products as p', 'p.id', '=', 'cp_product_inventories.product_id')
            ->leftJoin('product_categories as pc', 'pc.id', '=', 'p.category_id')
            ->select(
                'cp_product_inventories.id as inv_id',
                'cp_product_inventories.product_id',
                'p.item_name',
                'p.item_code',
                'p.uom',
                'pc.category_name',
                'cp_product_inventories.available_qty as current_stock'
            )
            ->orderBy('p.item_name')
            ->get();

        $products = Product::orderBy('item_name')->get();

        return view('channelPartner.inventory.manageInventoryCp', compact('inventory', 'products'));
    }

    public function cpReduceStock(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'remarks' => 'nullable|string|max:500',
        ]);

        $inv = CpProductInventory::where('id', $id)
            ->where('cp_id', Auth::user()->cp_id)
            ->firstOrFail();

        if ($request->quantity > $inv->available_qty) {
            return redirect()->route('cpInventory')->with('error', 'Cannot reduce more than available stock.');
        }

        $inv->available_qty -= $request->quantity;
        $inv->save();

        $product = Product::find($inv->product_id);
        $sellingPrice = $product->current_sale_price ?? 0;

        CpProductInventoryTransaction::create([
            'cp_id' => Auth::user()->cp_id,
            'product_id' => $inv->product_id,
            'transaction_type' => 'OUT',
            'quantity' => $request->quantity,
            'unit_price' => $sellingPrice,
            'performed_by' => Auth::id(),
            'txn_id' => $this->getTxnId(),
            'remarks' => $request->remarks ?? 'Stock used by CP',
        ]);

        CpMaterialLedger::create([
            'cp_id' => Auth::user()->cp_id,
            'material_name' => $product->item_name ?? 'Unknown Item',
            'quantity' => -$request->quantity,
            'unit' => $product->uom ?? 'Piece',
            'rate' => $sellingPrice,
            'total_amount' => -($request->quantity * $sellingPrice),
            'entry_date' => now()->format('Y-m-d'),
            'added_by' => Auth::id(),
            'remarks' => 'Stock used: ' . ($request->remarks ?? ''),
            'source' => 'stock_used',
        ]);

        return redirect()->route('cpInventory')->with('success', 'Stock reduced successfully.');
    }

    public function cpReorder(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        $product = Product::findOrFail($request->product_id);

        $txnId = 'REORD' . time() . rand(1000, 9999);
        while (CpOrder::where('order_id', $txnId)->exists()) {
            $txnId = 'REORD' . time() . rand(1000, 9999);
        }

        CpOrder::create([
            'cp_id' => $user->cp_id,
            'order_id' => $txnId,
            'products' => [
                [
                    'product_id' => $product->id,
                    'category_id' => $product->category_id,
                    'subcategory_id' => $product->sub_category_id,
                    'uom' => $product->uom,
                    'quantity' => $request->quantity,
                ]
            ],
            'order_notes' => 'Re-order from inventory page',
            'status' => 'pending',
            'order_date' => now()->format('Y-m-d'),
            'payment_status' => 'verification_pending',
        ]);

        return redirect()->route('cpInventory')->with('success', 'Re-order placed! Stock will be added after admin approval and delivery.');
    }

    public function cpDeleteInventory($id)
    {
        $inv = CpProductInventory::where('id', $id)
            ->where('cp_id', Auth::user()->cp_id)
            ->firstOrFail();

        if ($inv->available_qty > 0) {
            return redirect()->route('cpInventory')->with('error', 'Cannot delete item with stock remaining.');
        }

        $inv->delete();
        return redirect()->route('cpInventory')->with('success', 'Item removed from inventory.');
    }

    public function transferInventoryCp()
    {
        $categories = ProductCategory::all();
        $cp_roles = ChannelPartnerRole::where('id', '!=', 1)
            ->where('id', '!=', 2)
            ->where('id', '!=', 3)->get();
        return view('channelPartner.inventory.transferInventoryCp')
            ->with('categories', $categories)
            ->with('cp_roles', $cp_roles);
    }

    public function storeTransferInventoryCp(Request $request, wareHouseInventoryService $inventoryService, WarehouseToCpInventoryService $cpInventoryService)
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

            $product = Product::find($request->product_id);
            if ($product && $request->sold_to) {
                $sellingPrice = $product->current_sale_price ?? 0;
                CpMaterialLedger::create([
                    'cp_id' => $request->sold_to,
                    'material_name' => $product->item_name,
                    'quantity' => $request->quantity,
                    'unit' => $product->uom ?? 'Piece',
                    'rate' => $sellingPrice,
                    'total_amount' => $request->quantity * $sellingPrice,
                    'entry_date' => now()->format('Y-m-d'),
                    'added_by' => Auth::id(),
                    'remarks' => 'Stock transferred from warehouse (auto)',
                    'source' => 'warehouse_transfer',
                ]);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        return redirect()->back()->with('success', 'Inventory has been transferred successfully');
    }

    public function getTxnId()
    {
        $prefix = 'TRAN';
        $datePart = date('Ymd');
        $randomPart = strtoupper(substr(uniqid(), -4));
        return $prefix . $datePart . $randomPart;
    }

    public function invTxnsCp()
    {
        try {
            $txn_list = CpProductInventoryTransaction::with([
                'product', 'channelPartner', 'transferToCp', 'transferByUser'
            ])->where('cp_id', Auth::user()->cp_id)
                ->orderByDesc('created_at')
                ->get();
            return view('channelPartner.inventory.inventoryTxnsCp')->with('txn_list', $txn_list);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // ── Admin ──

    public function adminCpInventoryList()
    {
        $cps = ChannelPartner::with('role')->where('is_active', 1)->orderBy('cp_name')->get();

        $invStats = CpProductInventory::select('cp_id',
                DB::raw('COUNT(*) as items_count'),
                DB::raw('COALESCE(SUM(available_qty), 0) as total_stock')
            )
            ->groupBy('cp_id')
            ->get()
            ->keyBy('cp_id');

        return view('Admin.inventorySetting.cpInventoryList', compact('cps', 'invStats'));
    }

    public function adminCpInventoryDetail($cpId)
    {
        $cp = ChannelPartner::findOrFail($cpId);
        $inventory = CpProductInventory::where('cp_product_inventories.cp_id', $cpId)
            ->join('products', 'products.id', '=', 'cp_product_inventories.product_id')
            ->leftJoin('product_categories', 'product_categories.id', '=', 'products.category_id')
            ->select(
                'cp_product_inventories.*',
                'products.item_name',
                'products.item_code',
                'products.uom',
                'product_categories.category_name'
            )
            ->orderBy('products.item_name')
            ->get();

        $products = Product::with('inventory')->orderBy('item_name')->get();

        return view('Admin.inventorySetting.cpInventoryDetail', compact('cp', 'inventory', 'products'));
    }

    public function adminCpUpdateStock(Request $request, $id)
    {
        $request->validate(['available_qty' => 'required|integer|min:0']);

        $inv = CpProductInventory::findOrFail($id);
        $oldQty = $inv->available_qty;
        $newQty = $request->available_qty;
        $diff = $newQty - $oldQty;

        if ($diff > 0) {
            $warehouseInv = \App\Models\ProductInventory::where('product_id', $inv->product_id)->first();
            $warehouseStock = $warehouseInv ? $warehouseInv->available_qty : 0;
            if ($diff > $warehouseStock) {
                return redirect()->back()->with('error', "Not enough warehouse stock. Available: {$warehouseStock}, Increase requested: {$diff}");
            }
            $warehouseInv->available_qty -= $diff;
            $warehouseInv->save();

            $product = Product::find($inv->product_id);
            $sellingPrice = $product->current_sale_price ?? 0;

            ProductInventoryTransaction::create([
                'product_id' => $inv->product_id,
                'transaction_type' => 'OUT',
                'quantity' => $diff,
                'unit_price' => $sellingPrice,
                'performed_by' => Auth::id(),
                'txn_id' => $this->getTxnId(),
                'txn_done_from' => $inv->cp_id,
                'remarks' => 'Stock adjusted for CP: ' . ChannelPartner::find($inv->cp_id)?->cp_name,
            ]);
        } elseif ($diff < 0) {
            $warehouseInv = \App\Models\ProductInventory::where('product_id', $inv->product_id)->first();
            if ($warehouseInv) {
                $warehouseInv->available_qty += abs($diff);
                $warehouseInv->save();
            }

            $product = Product::find($inv->product_id);
            $sellingPrice = $product->current_sale_price ?? 0;

            ProductInventoryTransaction::create([
                'product_id' => $inv->product_id,
                'transaction_type' => 'IN',
                'quantity' => abs($diff),
                'unit_price' => $sellingPrice,
                'performed_by' => Auth::id(),
                'txn_id' => $this->getTxnId(),
                'txn_done_from' => $inv->cp_id,
                'remarks' => 'Stock returned from CP: ' . ChannelPartner::find($inv->cp_id)?->cp_name,
            ]);
        }

        $inv->available_qty = $newQty;
        $inv->save();

        if ($diff != 0) {
            $product = isset($product) ? $product : Product::find($inv->product_id);
            $unitPrice = $product->current_sale_price ?? 0;

            CpProductInventoryTransaction::create([
                'cp_id' => $inv->cp_id,
                'product_id' => $inv->product_id,
                'transaction_type' => $diff > 0 ? 'IN' : 'OUT',
                'quantity' => abs($diff),
                'unit_price' => $unitPrice,
                'performed_by' => Auth::id(),
                'txn_id' => $this->getTxnId(),
                'remarks' => 'Stock adjusted by Admin',
            ]);

            CpMaterialLedger::create([
                'cp_id' => $inv->cp_id,
                'material_name' => $product->item_name ?? 'Unknown Item',
                'quantity' => $diff,
                'unit' => $product->uom ?? 'Piece',
                'rate' => $unitPrice,
                'total_amount' => $diff * $unitPrice,
                'entry_date' => now()->format('Y-m-d'),
                'added_by' => Auth::id(),
                'remarks' => $diff > 0 ? 'Stock increased by Admin (auto)' : 'Stock decreased by Admin (auto)',
                'source' => 'admin_adjusted',
            ]);
        }

        return redirect()->back()->with('success', 'Stock updated successfully.');
    }

    public function adminCpAddStock(Request $request, $cpId)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $warehouseInv = \App\Models\ProductInventory::where('product_id', $product->id)->first();
        $warehouseStock = $warehouseInv ? $warehouseInv->available_qty : 0;

        if ($request->quantity > $warehouseStock) {
            return redirect()->back()->with('error', "Not enough warehouse stock. Available: {$warehouseStock}, Requested: {$request->quantity}");
        }

        $warehouseInv->available_qty -= $request->quantity;
        $warehouseInv->save();

        $txnId = $this->getTxnId();
        $sellingPrice = $product->current_sale_price ?? 0;

        ProductInventoryTransaction::create([
            'product_id' => $product->id,
            'transaction_type' => 'OUT',
            'quantity' => $request->quantity,
            'unit_price' => $sellingPrice,
            'performed_by' => Auth::id(),
            'txn_id' => $txnId,
            'txn_done_from' => $cpId,
            'remarks' => 'Stock allocated to CP: ' . ChannelPartner::find($cpId)?->cp_name,
        ]);

        $inv = CpProductInventory::firstOrCreate(
            ['cp_id' => $cpId, 'product_id' => $product->id],
            ['available_qty' => 0]
        );
        $inv->available_qty += $request->quantity;
        $inv->save();

        CpProductInventoryTransaction::create([
            'cp_id' => $cpId,
            'product_id' => $product->id,
            'transaction_type' => 'IN',
            'quantity' => $request->quantity,
            'unit_price' => $sellingPrice,
            'performed_by' => Auth::id(),
            'txn_id' => $txnId,
            'remarks' => 'Stock added by Admin',
        ]);

        CpMaterialLedger::create([
            'cp_id' => $cpId,
            'material_name' => $product->item_name,
            'quantity' => $request->quantity,
            'unit' => $product->uom ?? 'Piece',
            'rate' => $sellingPrice,
            'total_amount' => $request->quantity * $sellingPrice,
            'entry_date' => now()->format('Y-m-d'),
            'added_by' => Auth::id(),
            'remarks' => 'Stock allocated to CP (auto)',
            'source' => 'admin_allocated',
        ]);

        return redirect()->back()->with('success', "Stock added successfully. ({$request->quantity} x {$product->item_name} @ ₹{$sellingPrice})");
    }

    public function adminCpEntries()
    {
        $entries = CpProductInventoryTransaction::with(['product', 'channelPartner', 'transferByUser'])
            ->where('transaction_type', 'OUT')
            ->whereNotNull('remarks')
            ->where('remarks', '!=', '')
            ->orderByDesc('created_at')
            ->get();

        return view('Admin.inventorySetting.cpEntries', compact('entries'));
    }
}
