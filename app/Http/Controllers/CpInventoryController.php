<?php

namespace App\Http\Controllers;

use App\Models\ChannelPartnerRole;
use App\Models\CpProductInventoryTransaction;
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
        $report = DB::table('products as p')
            ->leftJoin('product_categories as pc', 'pc.id', '=', 'p.category_id')
            ->leftJoin('product_sub_categories as psc', 'psc.id', '=', 'p.sub_category_id')
            ->leftJoin('cp_product_inventory_transactions as t', 't.product_id', '=', 'p.id')
            ->leftJoin('cp_product_inventories as pi', 'pi.product_id', '=', 'p.id')
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
            ->where('pi.cp_id', Auth::user()->cp_id) // Filter by current CP
            ->where('t.cp_id', Auth::user()->cp_id) // Filter by current CP
            ->groupBy('p.id', 'p.item_name', 'p.item_code', 'p.uom', 'pc.category_name', 'psc.sub_category_name', 'pi.available_qty')
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
            ])->where('cp_id', Auth::user()->cp_id) // Filter by current CP
                ->orderByDesc('created_at')
                ->get();

            return view('channelPartner.inventory.inventoryTxnsCp')->with('txn_list', $txn_list);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->with('error',  $e->getMessage());
        }
    }
}
