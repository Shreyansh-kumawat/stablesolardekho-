<?php

namespace App\Services;

use App\Models\ChannelPartner;
use App\Models\CpProductInventory;
use App\Models\CpProductInventoryTransaction;
use App\Models\ProductInventory;
use App\Models\ProductInventoryTransaction;
use App\Models\ProductSerial;
use Exception;
use Illuminate\Support\Facades\Auth;

class wareHouseInventoryService
{
    
    public function transferStock(
        int $productId,
        int $qty,
        $unique_txn_id = null,
        array $serialNumbers = [],
        array $requestdata = []
    ) {


        try{

            $transfer_status = 'transferred_to_cp_from_warehouse';
            $current_location = 'cp';
            $cp_type = ChannelPartner::findOrFail($requestdata['sold_to'])->cp_role;
          
            if ($cp_type == 4) {
                $transfer_status = 'sold_to_user_from_warehouse';
                $current_location = 'user';
            }
            if ($cp_type == 5) {
                $transfer_status = 'installed_to_customer_from_warehouse';
                $current_location = 'customer';
            }

        $inventory = CpProductInventory::where('product_id', $productId)
        ->where('cp_id', Auth::user()->cp_id)
        ->lockForUpdate()->firstOrFail();

        if ($inventory->available_qty < $qty) {
            throw new Exception('Insufficient stock');
        }       
        if ($requestdata['is_serialNumber_required'] == '1') {
            if (count($serialNumbers) !== $qty) {
                throw new Exception('Serial count must match quantity');
            }

           
            foreach ($serialNumbers as $sn) {
            
            $serial = ProductSerial::where('product_id', $productId)
                ->where('serial_number', $sn)
                // ->where('status', 'issue_to_warehouse')
                ->where('issue_to', Auth::user()->cp_id)
                ->first();

            if (!$serial) {
                throw new Exception("Serial number {$sn} is not available in stock");
            }
            
            $serial->update([
                'status' => $transfer_status,
                'current_location' => $current_location,
                'issue_to' => $requestdata['sold_to'] ?? 'unknown',
                'updated_at' => now(),
            ]);

            CpProductInventoryTransaction::create([
                'cp_id' => Auth::user()->cp_id,
                'product_id' => $productId,
                'serial_id' => $serial->id,
                'transaction_type' => 'OUT',
                'quantity' => 1,
                'txn_done_from' => $requestdata['sold_to'] ?? null,
                'unit_price' => $requestdata['unit_price'] ?? null,
                'invoice_number' => $requestdata['invoice_number'] ?? null,
                'invoice_date' => $requestdata['invoice_date'] ?? null,
                'performed_by' => Auth::user()->id,
                'txn_id' => $unique_txn_id,
                'remarks' => $requestdata['remarks'] ?? null,
                'created_at' => now(),
            ]);
        } 
        }else {
            $inventory->decrement('available_qty', $qty);
            CpProductInventoryTransaction::create([
                'product_id' => $productId,
                'transaction_type' => 'OUT',
                'quantity' => $qty,
                'txn_done_from' => $requestdata['sold_to'] ?? null,
                'performed_by' => Auth::user()->id,
                'txn_id' => $unique_txn_id,
                'remarks' => $requestdata['remarks'] ?? null,
                'created_at' => now(),
            ]);
        }
        $inventory->decrement('available_qty', $qty);
        return true;
        } catch(Exception $e){
            throw $e;
        }
    }

    // public function randomSerialNumber()
    // {
    //    $serialNumber = 'TEMPSN' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
    //    while (ProductSerial::where('serial_number', $serialNumber)->exists()) {
    //        $serialNumber = 'TEMPSN' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
    //    }
    //    return $serialNumber;
    // }
    
}
