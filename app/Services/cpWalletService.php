<?php

namespace App\Services;

use App\Models\CpWallet;
use App\Models\CpWalletTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CpWalletService
{

public function addFundsToCp($cpId, $amount, $requestData)
{
    return DB::transaction(function () use ($cpId, $amount, $requestData) {

        // 🔒 Lock wallet row to avoid race condition
        $wallet = CpWallet::lockForUpdate()->firstOrCreate(
            ['cp_id' => $cpId],
            ['balance' => 0]
        );

        $opening = $wallet->balance;
        $closing = $opening + $amount;

        // 🧾 Wallet transaction (ledger entry)
        CpWalletTransaction::create([
            'cp_id' => $cpId,
            'amount' => $amount,
            'transaction_type' => 'CREDIT',
            'opening_balance' => $opening,
            'closing_balance' => $closing,
            'txn_id' => $this->getTxnId(),
            'source' => 'Admin Fund Transfer',
            'remarks' => $requestData['remark'] ?? 'Fund transfer to CP',
            'status' => 'SUCCESS',
            'txn_done_by'=>Auth::user()->id
        ]);

        // 💰 Update wallet balance
        $wallet->update(['balance' => $closing]);

        return true;
    });
}


    public function deductFundsFromCp($cpId, $amount, $requestData)
    {
         return DB::transaction(function () use ($cpId, $amount, $requestData) {

        // 🔒 Lock wallet row to avoid race condition
        $wallet = CpWallet::lockForUpdate()
        ->where('cp_id', $cpId)
        ->firstOrFail();

        if($wallet->balance < $amount){
            throw new \Exception('Insufficient funds in CP wallet.');
        }

        $opening = $wallet->balance;
        $closing = $opening - $amount;

        // 🧾 Wallet transaction (ledger entry)
        CpWalletTransaction::create([
            'cp_id' => $cpId,
            'amount' => $amount,
            'transaction_type' => 'DEBIT',
            'opening_balance' => $opening,
            'closing_balance' => $closing,
            'txn_id' => $this->getTxnId(),
            'source' => 'Admin Fund Deduction',
            'remarks' => $requestData['remark'] ?? 'Fund deduction from CP',
            'status' => 'SUCCESS',
            'txn_done_by'=>Auth::user()->id
        ]);

        // 💰 Update wallet balance
        $wallet->update(['balance' => $closing]);

        return true;
    });
    }

    public function getTxnId()
    {       
        return 'PAYTXN' . time() . rand(1000, 9999);
    }
}
