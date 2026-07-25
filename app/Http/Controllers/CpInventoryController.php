<?php

namespace App\Http\Controllers;

use App\Models\ChannelPartner;
use App\Models\ChannelPartnerRole;
use App\Models\CpProductInventory;
use App\Models\CpProductInventoryTransaction;
use App\Models\Product;
use App\Models\ProductCategory;
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

        $report = CpProductInventory::where('cp_product_inventories.cp_id', $cpId)
            ->join('products as p', 'p.id', '=', 'cp_product_inventories.product_id')
            ->leftJoin('product_categories as pc', 'pc.id', '=', 'p.category_id')
            ->select(
                'cp_product_inventories.id as inv_id',
                'p.id',
                'p.item_name',
                'p.item_code',
                'p.uom',
                'pc.category_name',
                'cp_product_inventories.available_qty as current_stock'
            )
            ->orderBy('p.item_name')
            ->get();

        return view('channelPartner.inventory.manageInventoryCp')->with('inventory_list', $report);
    }

     public function transferInventoryCp()
    {
        $categories = ProductCategory::all();
        $cp_roles = ChannelPartnerRole::where('id', '!=', 1)
            ->where('id', '!=', 2)
            ->where('id', '!=', 3)->get(); // Exclude the role with ID 1
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
        } catch (\Exception $e) {
            return redirect()->back()->with('error',  $e->getMessage());
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
                'product',
                'channelPartner',
                'transferToCp',
                'transferByUser'
            ])->where('cp_id', Auth::user()->cp_id)
                ->orderByDesc('created_at')
                ->get();

            return view('channelPartner.inventory.inventoryTxnsCp')->with('txn_list', $txn_list);
        } catch (\Exception $e) {
            return redirect()->back()->with('error',  $e->getMessage());
        }
    }

    public function cpAddStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cpId = Auth::user()->cp_id;

        $inv = CpProductInventory::firstOrCreate(
            ['cp_id' => $cpId, 'product_id' => $request->product_id],
            ['available_qty' => 0]
        );
        $inv->available_qty += $request->quantity;
        $inv->save();

        CpProductInventoryTransaction::create([
            'cp_id' => $cpId,
            'product_id' => $request->product_id,
            'transaction_type' => 'IN',
            'quantity' => $request->quantity,
            'performed_by' => Auth::id(),
            'txn_id' => $this->getTxnId(),
            'remarks' => 'Manual stock entry by CP',
        ]);

        return redirect()->route('cpInventory')->with('success', 'Stock added successfully.');
    }

    public function cpUpdateStock(Request $request, $id)
    {
        $request->validate([
            'available_qty' => 'required|integer|min:0',
        ]);

        $inv = CpProductInventory::where('id', $id)
            ->where('cp_id', Auth::user()->cp_id)
            ->firstOrFail();

        $oldQty = $inv->available_qty;
        $newQty = $request->available_qty;
        $diff = $newQty - $oldQty;

        $inv->available_qty = $newQty;
        $inv->save();

        if ($diff != 0) {
            CpProductInventoryTransaction::create([
                'cp_id' => Auth::user()->cp_id,
                'product_id' => $inv->product_id,
                'transaction_type' => $diff > 0 ? 'IN' : 'OUT',
                'quantity' => abs($diff),
                'performed_by' => Auth::id(),
                'txn_id' => $this->getTxnId(),
                'remarks' => 'Stock adjusted by CP',
            ]);
        }

        return redirect()->route('cpInventory')->with('success', 'Stock updated successfully.');
    }

    public function adminCpInventoryList()
    {
        $cps = ChannelPartner::with('role')->where('is_active', 1)->orderBy('cp_name')->get();
        return view('Admin.inventorySetting.cpInventoryList', compact('cps'));
    }

    public function adminCpInventoryDetail($cpId)
    {
        $cp = ChannelPartner::findOrFail($cpId);
        $inventory = CpProductInventory::where('cp_id', $cpId)
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

        $products = Product::orderBy('item_name')->get();

        return view('Admin.inventorySetting.cpInventoryDetail', compact('cp', 'inventory', 'products'));
    }

    public function adminCpUpdateStock(Request $request, $id)
    {
        $request->validate([
            'available_qty' => 'required|integer|min:0',
        ]);

        $inv = CpProductInventory::findOrFail($id);
        $oldQty = $inv->available_qty;
        $newQty = $request->available_qty;
        $diff = $newQty - $oldQty;

        $inv->available_qty = $newQty;
        $inv->save();

        if ($diff != 0) {
            CpProductInventoryTransaction::create([
                'cp_id' => $inv->cp_id,
                'product_id' => $inv->product_id,
                'transaction_type' => $diff > 0 ? 'IN' : 'OUT',
                'quantity' => abs($diff),
                'performed_by' => Auth::id(),
                'txn_id' => $this->getTxnId(),
                'remarks' => 'Stock adjusted by Admin',
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

        $inv = CpProductInventory::firstOrCreate(
            ['cp_id' => $cpId, 'product_id' => $request->product_id],
            ['available_qty' => 0]
        );
        $inv->available_qty += $request->quantity;
        $inv->save();

        CpProductInventoryTransaction::create([
            'cp_id' => $cpId,
            'product_id' => $request->product_id,
            'transaction_type' => 'IN',
            'quantity' => $request->quantity,
            'performed_by' => Auth::id(),
            'txn_id' => $this->getTxnId(),
            'remarks' => 'Stock added by Admin',
        ]);

        return redirect()->back()->with('success', 'Stock added successfully.');
    }
}
