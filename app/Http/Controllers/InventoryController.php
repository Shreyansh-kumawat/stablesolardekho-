<?php

namespace App\Http\Controllers;

use App\Models\ChannelPartner;
use App\Models\ChannelPartnerRole;
use App\Models\ProductCategory;
use App\Models\ProductInventoryTransaction;
use App\Services\cpInventoryService;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function addNewInventory()
    {
        $categories = ProductCategory::all();
        $suppliers = ChannelPartner::where('cp_role', '1')->get();
        return view('Admin.inventorySetting.addNewInventory')
            ->with('categories', $categories)
            ->with('suppliers', $suppliers);
    }

    public function storeNewInventory(Request $request, InventoryService $inventoryService)
    {
        try {

            $txn_id = $this->getTxnId();
            $inventoryService->addStock(
                $request->product_id,
                $request->quantity,
                $txn_id,
                $request->serial_numbers ?? [],
                $request->all()
            );

            return redirect()->back()->with('success', 'Inventory has been added successfully');
        } catch (\Exception $e) {
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
}
