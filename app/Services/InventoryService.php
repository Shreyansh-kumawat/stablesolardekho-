<?php

namespace App\Services;

use App\Models\ChannelPartner;
use App\Models\ProductInventory;
use App\Models\ProductInventoryTransaction;
use App\Models\ProductSerial;
use Exception;
use Illuminate\Support\Facades\Auth;

class InventoryService
{
    public function addStock(
        int $productId,
        int $qty,
        $unique_txn_id = null,
        array $serialNumbers = [],
        array $requestdata = []
    ) {



        $inventory = ProductInventory::firstOrCreate(
            ['product_id' => $productId],
            ['available_qty' => 0]
        );


        if ($requestdata['is_serialNumber_required'] == '1') {
            if (count($serialNumbers) !== $qty) {
                throw new Exception('Serial count must match quantity');
            }
            foreach ($serialNumbers as $sn) {
                $sn = (is_string($sn) && strtoupper(trim($sn)) === 'NA') ? $this->randomSerialNumber() : $sn;
                $serial = ProductSerial::create([
                    'product_id' => $productId,
                    'serial_number' => $sn,
                    'status' => 'in_stock',
                    'created_at' => now(),
                ]);

                ProductInventoryTransaction::create([
                    'product_id' => $productId,
                    'serial_id' => $serial->id,
                    'transaction_type' => 'IN',
                    'quantity' => 1,
                    'txn_done_from' => $requestdata['supplier_id'] ?? null,
                    'unit_price' => $requestdata['unit_price'] ?? null,
                    'invoice_number' => $requestdata['invoice_number'] ?? null,
                    'invoice_date' => $requestdata['invoice_date'] ?? null,
                    'performed_by' => Auth::user()->id,
                    'txn_id' => $unique_txn_id,
                    'remarks' => $requestdata['remarks'] ?? null,
                    'created_at' => now(),
                ]);
            }
            $inventory->increment('available_qty', $qty);
        } else {
            $inventory->increment('available_qty', $qty);
            ProductInventoryTransaction::create([
                'product_id' => $productId,
                'transaction_type' => 'IN',
                'quantity' => $qty,
                'txn_done_from' => $requestdata['supplier_id'] ?? null,
                'unit_price' => $requestdata['unit_price'] ?? null,
                'invoice_number' => $requestdata['invoice_number'] ?? null,
                'invoice_date' => $requestdata['invoice_date'] ?? null,
                'performed_by' => Auth::user()->id,
                'txn_id' => $unique_txn_id,
                'remarks' => $requestdata['remarks'] ?? null,
                'created_at' => now(),
            ]);
        }
        return true;
    }

    public function transferStock(
        int $productId,
        int $qty,
        $unique_txn_id = null,
        array $serialNumbers = [],
        array $requestdata = []
    ) {


        try {
            $transfer_status = 'issue_to_cp';
            $current_location = 'cp';
            $cp_type = ChannelPartner::findOrFail($requestdata['sold_to'])->cp_role;
            if ($cp_type == 3) {
                $transfer_status = 'issue_to_warehouse';
                $current_location = 'warehouse';
            }
            if ($cp_type == 4) {
                $transfer_status = 'sold_to_user';
                $current_location = 'user';
            }
            if ($cp_type == 5) {
                $transfer_status = 'installed_to_customer';
                $current_location = 'customer';
            }

            $inventory = ProductInventory::where('product_id', $productId)->lockForUpdate()->firstOrFail();

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
                        ->where('status', 'in_stock')
                        ->whereNull('issue_to')
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

                    ProductInventoryTransaction::create([
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
            } else {
                $inventory->decrement('available_qty', $qty);
                ProductInventoryTransaction::create([
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
            
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function randomSerialNumber()
    {
        $serialNumber = 'TEMPSN' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
        while (ProductSerial::where('serial_number', $serialNumber)->exists()) {
            $serialNumber = 'TEMPSN' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
        }
        return $serialNumber;
    }
}
