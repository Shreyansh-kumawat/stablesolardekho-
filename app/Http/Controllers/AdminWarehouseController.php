<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductInventory;
use App\Models\ProductInventoryTransaction;
use App\Models\ProductSerial;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseInventory;
use App\Models\WarehouseInventoryTransaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminWarehouseController extends Controller
{
    private function checkAccess(string $perm = 'warehouses.view'): void
    {
        abort_unless(Auth::user()->hasAdminPermission($perm), 403);
    }

    public function index()
    {
        $this->checkAccess();
        $warehouses = Warehouse::withCount('managers')
            ->withCount('inventories')
            ->orderByDesc('created_at')
            ->get();

        return view('Admin.warehouse.index', compact('warehouses'));
    }

    public function create()
    {
        $this->checkAccess('warehouses.manage');
        return view('Admin.warehouse.create');
    }

    public function store(Request $request)
    {
        $this->checkAccess('warehouses.manage');
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pin_code' => 'nullable|string|max:10',
        ]);

        Warehouse::create($request->only('name', 'address', 'city', 'state', 'pin_code'));

        return redirect()->route('admin.warehouses.index')->with('success', 'Warehouse created successfully.');
    }

    public function edit($id)
    {
        $this->checkAccess('warehouses.manage');
        $warehouse = Warehouse::findOrFail($id);
        return view('Admin.warehouse.create', compact('warehouse'));
    }

    public function update(Request $request, $id)
    {
        $this->checkAccess('warehouses.manage');
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pin_code' => 'nullable|string|max:10',
        ]);

        $warehouse = Warehouse::findOrFail($id);
        $warehouse->update($request->only('name', 'address', 'city', 'state', 'pin_code'));

        return redirect()->route('admin.warehouses.index')->with('success', 'Warehouse updated successfully.');
    }

    public function toggleActive($id)
    {
        $this->checkAccess('warehouses.manage');
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->update(['is_active' => !$warehouse->is_active]);

        return redirect()->back()->with('success', 'Warehouse status updated.');
    }

    public function destroy($id)
    {
        $this->checkAccess('warehouses.manage');
        $warehouse = Warehouse::findOrFail($id);
        $hasStock = $warehouse->inventories()->where('available_qty', '>', 0)->exists();

        if ($hasStock) {
            return redirect()->back()->with('error', 'Cannot delete warehouse with existing stock. Transfer or clear stock first.');
        }

        $warehouse->delete();
        return redirect()->route('admin.warehouses.index')->with('success', 'Warehouse deleted successfully.');
    }

    public function managers($id)
    {
        $warehouse = Warehouse::with('managers')->findOrFail($id);
        $users = User::where('role_id', '!=', 4)
            ->orderBy('name')
            ->get();

        return view('Admin.warehouse.managers', compact('warehouse', 'users'));
    }

    public function addManager(Request $request, $id)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);

        $warehouse = Warehouse::findOrFail($id);

        if ($warehouse->managers()->where('user_id', $request->user_id)->exists()) {
            return redirect()->back()->with('error', 'This user is already a manager of this warehouse.');
        }

        $warehouse->managers()->attach($request->user_id);

        return redirect()->back()->with('success', 'Manager added successfully.');
    }

    public function removeManager($id, $userId)
    {
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->managers()->detach($userId);

        return redirect()->back()->with('success', 'Manager removed successfully.');
    }

    public function inventory($id)
    {
        $warehouse = Warehouse::findOrFail($id);

        $inventory = DB::table('warehouse_inventories as wi')
            ->join('products as p', 'p.id', '=', 'wi.product_id')
            ->leftJoin('product_categories as pc', 'pc.id', '=', 'p.category_id')
            ->leftJoin('warehouse_inventory_transactions as t', function ($join) use ($id) {
                $join->on('t.product_id', '=', 'p.id')
                     ->where('t.warehouse_id', '=', $id);
            })
            ->where('wi.warehouse_id', $id)
            ->select(
                'p.id as product_id',
                'p.item_name',
                'p.item_code',
                'p.uom',
                'pc.category_name',
                'wi.available_qty',
                DB::raw("SUM(CASE WHEN t.transaction_type = 'IN' THEN t.quantity ELSE 0 END) as total_in"),
                DB::raw("SUM(CASE WHEN t.transaction_type = 'OUT' THEN t.quantity ELSE 0 END) as total_out")
            )
            ->groupBy('p.id', 'p.item_name', 'p.item_code', 'p.uom', 'pc.category_name', 'wi.available_qty')
            ->orderBy('p.item_name')
            ->get();

        return view('Admin.warehouse.inventory', compact('warehouse', 'inventory'));
    }

    public function transferForm()
    {
        $this->checkAccess('warehouses.transfer');
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        $categories = ProductCategory::where('active_status', 1)->orderBy('category_name')->get();

        return view('Admin.warehouse.transfer', compact('warehouses', 'categories'));
    }

    public function storeTransfer(Request $request)
    {
        $this->checkAccess('warehouses.transfer');
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'nullable|numeric|min:0',
            'serial_numbers' => 'nullable|array',
            'remarks' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            $productId = $request->product_id;
            $warehouseId = $request->warehouse_id;
            $qty = (int) $request->quantity;
            $serialNumbers = $request->serial_numbers ?? [];
            $serialRequired = !empty($serialNumbers);

            $mainInventory = ProductInventory::where('product_id', $productId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($mainInventory->available_qty < $qty) {
                throw new Exception('Insufficient stock in main inventory.');
            }

            $warehouseInventory = WarehouseInventory::firstOrCreate(
                ['warehouse_id' => $warehouseId, 'product_id' => $productId],
                ['available_qty' => 0]
            );

            $txnId = 'WH-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

            if ($serialRequired) {
                if (count($serialNumbers) !== $qty) {
                    throw new Exception('Serial count must match quantity.');
                }

                foreach ($serialNumbers as $sn) {
                    $serial = ProductSerial::where('product_id', $productId)
                        ->where('serial_number', $sn)
                        ->where('status', 'in_stock')
                        ->whereNull('issue_to')
                        ->first();

                    if (!$serial) {
                        throw new Exception("Serial number {$sn} is not available in stock.");
                    }

                    $serial->update([
                        'status' => 'issue_to_warehouse',
                        'current_location' => 'warehouse',
                        'issue_to' => $warehouseId,
                    ]);

                    ProductInventoryTransaction::create([
                        'product_id' => $productId,
                        'serial_id' => $serial->id,
                        'transaction_type' => 'OUT',
                        'quantity' => 1,
                        'txn_done_from' => null,
                        'supplier_name' => 'To Warehouse',
                        'unit_price' => $request->unit_price,
                        'invoice_number' => $request->invoice_number,
                        'invoice_date' => $request->invoice_date,
                        'performed_by' => Auth::id(),
                        'txn_id' => $txnId,
                        'remarks' => $request->remarks ?? 'Transferred to warehouse',
                    ]);

                    WarehouseInventoryTransaction::create([
                        'warehouse_id' => $warehouseId,
                        'product_id' => $productId,
                        'serial_id' => $serial->id,
                        'transaction_type' => 'IN',
                        'quantity' => 1,
                        'transfer_type' => 'from_main',
                        'unit_price' => $request->unit_price,
                        'invoice_number' => $request->invoice_number,
                        'invoice_date' => $request->invoice_date,
                        'performed_by' => Auth::id(),
                        'txn_id' => $txnId,
                        'remarks' => $request->remarks ?? 'Received from main inventory',
                    ]);
                }
            } else {
                ProductInventoryTransaction::create([
                    'product_id' => $productId,
                    'transaction_type' => 'OUT',
                    'quantity' => $qty,
                    'txn_done_from' => null,
                    'supplier_name' => 'To Warehouse',
                    'unit_price' => $request->unit_price,
                    'invoice_number' => $request->invoice_number,
                    'invoice_date' => $request->invoice_date,
                    'performed_by' => Auth::id(),
                    'txn_id' => $txnId,
                    'remarks' => $request->remarks ?? 'Transferred to warehouse',
                ]);

                WarehouseInventoryTransaction::create([
                    'warehouse_id' => $warehouseId,
                    'product_id' => $productId,
                    'transaction_type' => 'IN',
                    'quantity' => $qty,
                    'transfer_type' => 'from_main',
                    'unit_price' => $request->unit_price,
                    'invoice_number' => $request->invoice_number,
                    'invoice_date' => $request->invoice_date,
                    'performed_by' => Auth::id(),
                    'txn_id' => $txnId,
                    'remarks' => $request->remarks ?? 'Received from main inventory',
                ]);
            }

            $mainInventory->decrement('available_qty', $qty);
            $warehouseInventory->increment('available_qty', $qty);

            $mainInventory->refresh();
            Product::where('id', $productId)->update(['quantity' => $mainInventory->available_qty]);

            DB::commit();

            return redirect()->back()->with('success', "Successfully transferred {$qty} items to warehouse.");

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function transactions($id, Request $request)
    {
        $warehouse = Warehouse::findOrFail($id);

        $query = WarehouseInventoryTransaction::with(['product', 'serial', 'performer'])
            ->where('warehouse_id', $id);

        if ($request->filled('type') && in_array($request->type, ['IN', 'OUT'])) {
            $query->where('transaction_type', $request->type);
        }
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $transactions = $query->orderByDesc('created_at')->get();

        $products = WarehouseInventoryTransaction::where('warehouse_id', $id)
            ->distinct('product_id')
            ->with('product')
            ->get()
            ->pluck('product')
            ->filter()
            ->unique('id');

        return view('Admin.warehouse.transactions', compact('warehouse', 'transactions', 'products'));
    }

    public function dashboard($id)
    {
        $warehouse = Warehouse::with('managers')->findOrFail($id);

        $totalProducts = WarehouseInventory::where('warehouse_id', $id)->count();
        $totalStock = WarehouseInventory::where('warehouse_id', $id)->sum('available_qty');

        $totalInValue = WarehouseInventoryTransaction::where('warehouse_id', $id)
            ->where('transaction_type', 'IN')
            ->selectRaw('SUM(COALESCE(unit_price, 0) * quantity) as total')
            ->value('total') ?? 0;

        $totalOutValue = WarehouseInventoryTransaction::where('warehouse_id', $id)
            ->where('transaction_type', 'OUT')
            ->selectRaw('SUM(COALESCE(unit_price, 0) * quantity) as total')
            ->value('total') ?? 0;

        $recentTxns = WarehouseInventoryTransaction::with(['product', 'performer'])
            ->where('warehouse_id', $id)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $topProducts = WarehouseInventory::with('product')
            ->where('warehouse_id', $id)
            ->where('available_qty', '>', 0)
            ->orderByDesc('available_qty')
            ->limit(5)
            ->get();

        return view('Admin.warehouse.dashboard', compact(
            'warehouse', 'totalProducts', 'totalStock',
            'totalInValue', 'totalOutValue', 'recentTxns', 'topProducts'
        ));
    }

    public function adjustStock(Request $request, $id)
    {
        $this->checkAccess('warehouses.manage');
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'adjustment' => 'required|integer',
            'remarks' => 'nullable|string|max:500',
        ]);

        $warehouse = Warehouse::findOrFail($id);
        $productId = $request->product_id;
        $adjustment = (int) $request->adjustment;

        if ($adjustment === 0) {
            return response()->json(['success' => true, 'message' => 'No change']);
        }

        DB::beginTransaction();
        try {
            $inv = WarehouseInventory::where('warehouse_id', $id)
                ->where('product_id', $productId)
                ->lockForUpdate()
                ->first();

            if (!$inv) {
                throw new Exception('Product not found in this warehouse.');
            }

            $newQty = $inv->available_qty + $adjustment;
            if ($newQty < 0) {
                throw new Exception('Stock cannot go below 0.');
            }

            $inv->update(['available_qty' => $newQty]);

            $txnType = $adjustment > 0 ? 'IN' : 'OUT';
            WarehouseInventoryTransaction::create([
                'warehouse_id' => $id,
                'product_id' => $productId,
                'transaction_type' => $txnType,
                'quantity' => abs($adjustment),
                'transfer_type' => 'admin_adjustment',
                'performed_by' => Auth::id(),
                'txn_id' => 'ADJ-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4)),
                'remarks' => $request->remarks ?? 'Stock adjustment by admin',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Stock updated',
                'new_qty' => $newQty,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function profitLoss($id, Request $request)
    {
        $warehouse = Warehouse::findOrFail($id);

        $period = $request->get('period', 'all');
        $dateFilter = match ($period) {
            'today' => [today()->toDateString(), today()->toDateString()],
            'week' => [now()->startOfWeek()->toDateString(), now()->endOfWeek()->toDateString()],
            'month' => [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()],
            'year' => [now()->startOfYear()->toDateString(), now()->endOfYear()->toDateString()],
            'custom' => $request->filled('from_date') && $request->filled('to_date')
                ? [$request->from_date, $request->to_date]
                : null,
            default => null,
        };

        $inQuery = WarehouseInventoryTransaction::where('warehouse_id', $id)->where('transaction_type', 'IN');
        $outQuery = WarehouseInventoryTransaction::where('warehouse_id', $id)->where('transaction_type', 'OUT');

        if ($dateFilter) {
            $inQuery->whereBetween('created_at', [$dateFilter[0], $dateFilter[1] . ' 23:59:59']);
            $outQuery->whereBetween('created_at', [$dateFilter[0], $dateFilter[1] . ' 23:59:59']);
        }

        $purchaseCost = (clone $inQuery)->selectRaw('SUM(COALESCE(unit_price, 0) * quantity) as total')->value('total') ?? 0;
        $salesRevenue = (clone $outQuery)->selectRaw('SUM(COALESCE(unit_price, 0) * quantity) as total')->value('total') ?? 0;

        $totalIn = (clone $inQuery)->sum('quantity');
        $totalOut = (clone $outQuery)->sum('quantity');

        $productWise = DB::table('warehouse_inventory_transactions as t')
            ->join('products as p', 'p.id', '=', 't.product_id')
            ->where('t.warehouse_id', $id)
            ->when($dateFilter, function ($q) use ($dateFilter) {
                $q->whereBetween('t.created_at', [$dateFilter[0], $dateFilter[1] . ' 23:59:59']);
            })
            ->select(
                'p.id', 'p.item_name', 'p.item_code',
                DB::raw("SUM(CASE WHEN t.transaction_type = 'IN' THEN COALESCE(t.unit_price, 0) * t.quantity ELSE 0 END) as purchase_value"),
                DB::raw("SUM(CASE WHEN t.transaction_type = 'OUT' THEN COALESCE(t.unit_price, 0) * t.quantity ELSE 0 END) as sales_value"),
                DB::raw("SUM(CASE WHEN t.transaction_type = 'IN' THEN t.quantity ELSE 0 END) as qty_in"),
                DB::raw("SUM(CASE WHEN t.transaction_type = 'OUT' THEN t.quantity ELSE 0 END) as qty_out")
            )
            ->groupBy('p.id', 'p.item_name', 'p.item_code')
            ->orderBy('p.item_name')
            ->get();

        $profitLoss = $salesRevenue - $purchaseCost;

        return view('Admin.warehouse.profitLoss', compact(
            'warehouse', 'purchaseCost', 'salesRevenue', 'profitLoss',
            'totalIn', 'totalOut', 'productWise', 'period'
        ));
    }

    public function exportInventory($id): StreamedResponse
    {
        $warehouse = Warehouse::findOrFail($id);

        $inventory = DB::table('warehouse_inventories as wi')
            ->join('products as p', 'p.id', '=', 'wi.product_id')
            ->leftJoin('product_categories as pc', 'pc.id', '=', 'p.category_id')
            ->where('wi.warehouse_id', $id)
            ->select('p.item_name', 'p.item_code', 'pc.category_name', 'p.uom', 'wi.available_qty')
            ->orderBy('p.item_name')
            ->get();

        $filename = 'warehouse_' . str_replace(' ', '_', $warehouse->name) . '_inventory_' . date('Y-m-d') . '.csv';

        return new StreamedResponse(function () use ($inventory) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Product', 'Code', 'Category', 'UOM', 'Available Qty']);
            foreach ($inventory as $item) {
                fputcsv($handle, [
                    $item->item_name, $item->item_code,
                    $item->category_name ?? '-', $item->uom, $item->available_qty,
                ]);
            }
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function masterDashboard()
    {
        $this->checkAccess();
        $warehouses = Warehouse::where('is_active', true)
            ->withCount('inventories')
            ->get()
            ->map(function ($wh) {
                $wh->total_stock = WarehouseInventory::where('warehouse_id', $wh->id)->sum('available_qty');
                $wh->total_in_value = WarehouseInventoryTransaction::where('warehouse_id', $wh->id)
                    ->where('transaction_type', 'IN')
                    ->selectRaw('SUM(COALESCE(unit_price, 0) * quantity) as total')
                    ->value('total') ?? 0;
                $wh->total_out_value = WarehouseInventoryTransaction::where('warehouse_id', $wh->id)
                    ->where('transaction_type', 'OUT')
                    ->selectRaw('SUM(COALESCE(unit_price, 0) * quantity) as total')
                    ->value('total') ?? 0;
                return $wh;
            });

        $grandTotalStock = $warehouses->sum('total_stock');
        $grandTotalIn = $warehouses->sum('total_in_value');
        $grandTotalOut = $warehouses->sum('total_out_value');
        $totalWarehouses = $warehouses->count();

        $lowStockItems = DB::table('warehouse_inventories as wi')
            ->join('products as p', 'p.id', '=', 'wi.product_id')
            ->join('warehouses as w', 'w.id', '=', 'wi.warehouse_id')
            ->where('w.is_active', true)
            ->where('wi.available_qty', '>', 0)
            ->where('wi.available_qty', '<=', 5)
            ->select('w.name as warehouse_name', 'p.item_name', 'p.item_code', 'wi.available_qty')
            ->orderBy('wi.available_qty')
            ->limit(20)
            ->get();

        $recentTxns = WarehouseInventoryTransaction::with(['warehouse', 'product', 'performer'])
            ->orderByDesc('created_at')
            ->limit(15)
            ->get();

        $since = now()->subDays(30);
        $activityRows = DB::table('warehouse_inventory_transactions')
            ->selectRaw('warehouse_id, COUNT(*) as txn_count, SUM(quantity) as units_moved, MAX(created_at) as last_activity')
            ->where('created_at', '>=', $since)
            ->groupBy('warehouse_id')
            ->get()
            ->keyBy('warehouse_id');

        $comparison = $warehouses->map(function ($wh) use ($activityRows) {
            $row = $activityRows->get($wh->id);
            $pnl = (float) $wh->total_out_value - (float) $wh->total_in_value;
            return (object) [
                'id' => $wh->id,
                'name' => $wh->name,
                'txn_count' => $row ? (int) $row->txn_count : 0,
                'units_moved' => $row ? (int) $row->units_moved : 0,
                'last_activity' => $row ? $row->last_activity : null,
                'stock' => (int) $wh->total_stock,
                'in_value' => (float) $wh->total_in_value,
                'out_value' => (float) $wh->total_out_value,
                'pnl' => $pnl,
            ];
        });

        $grandPnl = $grandTotalOut - $grandTotalIn;
        $maxTxnCount = max(1, $comparison->max('txn_count'));
        $maxStock = max(1, $comparison->max('stock'));

        return view('Admin.warehouse.masterDashboard', compact(
            'warehouses', 'grandTotalStock', 'grandTotalIn', 'grandTotalOut',
            'totalWarehouses', 'lowStockItems', 'recentTxns',
            'comparison', 'grandPnl', 'maxTxnCount', 'maxStock'
        ));
    }

    public function warehouseTransferForm()
    {
        $this->checkAccess('warehouses.transfer');
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        $categories = ProductCategory::where('active_status', 1)->orderBy('category_name')->get();

        return view('Admin.warehouse.warehouseTransfer', compact('warehouses', 'categories'));
    }

    public function storeWarehouseTransfer(Request $request)
    {
        $this->checkAccess('warehouses.transfer');
        $request->validate([
            'from_warehouse_id' => 'required|exists:warehouses,id',
            'to_warehouse_id' => 'required|exists:warehouses,id|different:from_warehouse_id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'nullable|numeric|min:0',
            'remarks' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $fromId = $request->from_warehouse_id;
            $toId = $request->to_warehouse_id;
            $productId = $request->product_id;
            $qty = (int) $request->quantity;

            $fromInv = WarehouseInventory::where('warehouse_id', $fromId)
                ->where('product_id', $productId)
                ->lockForUpdate()
                ->first();

            if (!$fromInv || $fromInv->available_qty < $qty) {
                throw new Exception('Insufficient stock in source warehouse.');
            }

            $toInv = WarehouseInventory::firstOrCreate(
                ['warehouse_id' => $toId, 'product_id' => $productId],
                ['available_qty' => 0]
            );

            $txnId = 'W2W-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

            WarehouseInventoryTransaction::create([
                'warehouse_id' => $fromId,
                'product_id' => $productId,
                'transaction_type' => 'OUT',
                'quantity' => $qty,
                'transfer_type' => 'to_warehouse',
                'transfer_to' => $toId,
                'unit_price' => $request->unit_price,
                'performed_by' => Auth::id(),
                'txn_id' => $txnId,
                'remarks' => $request->remarks ?? 'Transferred to another warehouse',
            ]);

            WarehouseInventoryTransaction::create([
                'warehouse_id' => $toId,
                'product_id' => $productId,
                'transaction_type' => 'IN',
                'quantity' => $qty,
                'transfer_type' => 'from_warehouse',
                'transfer_to' => $fromId,
                'unit_price' => $request->unit_price,
                'performed_by' => Auth::id(),
                'txn_id' => $txnId,
                'remarks' => $request->remarks ?? 'Received from another warehouse',
            ]);

            $fromInv->decrement('available_qty', $qty);
            $toInv->increment('available_qty', $qty);

            DB::commit();

            return redirect()->back()->with('success', "Successfully transferred {$qty} items between warehouses.");
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function getWarehouseProductQty(Request $request)
    {
        $qty = WarehouseInventory::where('warehouse_id', $request->warehouse_id)
            ->where('product_id', $request->product_id)
            ->value('available_qty') ?? 0;

        return response()->json(['available_qty' => $qty]);
    }

    public function getMainInventoryProducts(Request $request)
    {
        $rows = DB::table('product_inventories as pi')
            ->join('products as p', 'p.id', '=', 'pi.product_id')
            ->leftJoin('product_categories as pc', 'pc.id', '=', 'p.category_id')
            ->leftJoin('product_sub_categories as psc', 'psc.id', '=', 'p.sub_category_id')
            ->where('pi.available_qty', '>', 0)
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
                'pi.available_qty'
            )
            ->orderBy('p.item_name')
            ->get();

        return response()->json($rows);
    }

    public function getWarehouseProducts(Request $request)
    {
        $rows = DB::table('warehouse_inventories as wi')
            ->join('products as p', 'p.id', '=', 'wi.product_id')
            ->leftJoin('product_categories as pc', 'pc.id', '=', 'p.category_id')
            ->leftJoin('product_sub_categories as psc', 'psc.id', '=', 'p.sub_category_id')
            ->where('wi.warehouse_id', $request->warehouse_id)
            ->where('wi.available_qty', '>', 0)
            ->select(
                'p.id as product_id',
                'p.item_name',
                'p.item_code',
                'p.category_id',
                'p.sub_category_id',
                'p.current_sale_price',
                'pc.category_name',
                'psc.sub_category_name',
                'wi.available_qty'
            )
            ->orderBy('p.item_name')
            ->get();

        return response()->json($rows);
    }

    public function exportTransactions($id, Request $request): StreamedResponse
    {
        $warehouse = Warehouse::findOrFail($id);

        $query = WarehouseInventoryTransaction::with(['product', 'performer'])
            ->where('warehouse_id', $id);

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $transactions = $query->orderByDesc('created_at')->get();

        $filename = 'warehouse_' . str_replace(' ', '_', $warehouse->name) . '_transactions_' . date('Y-m-d') . '.csv';

        return new StreamedResponse(function () use ($transactions) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Date', 'Product', 'Type', 'Qty', 'Transfer', 'Unit Price', 'Invoice', 'TXN ID', 'By', 'Remarks']);
            foreach ($transactions as $txn) {
                fputcsv($handle, [
                    $txn->created_at->format('d-m-Y H:i'),
                    $txn->product->item_name ?? '-',
                    $txn->transaction_type,
                    $txn->quantity,
                    $txn->transfer_type ?? '-',
                    $txn->unit_price ?? '-',
                    $txn->invoice_number ?? '-',
                    $txn->txn_id ?? '-',
                    $txn->performer->name ?? '-',
                    $txn->remarks ?? '-',
                ]);
            }
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
