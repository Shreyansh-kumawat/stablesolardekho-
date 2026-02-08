<?php

namespace App\Http\Controllers;

use App\Models\ChannelPartner;
use App\Models\CpWalletTransaction;
use App\Services\cpWalletService;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function transferFundToCp()
    {
        $cp_list = ChannelPartner::whereIn('cp_role', ['2', '4'])
            ->get();

        return view('Admin.fundSetting.newFundTransfer', compact('cp_list'));
    }

    public function storeFundTransfer(Request $request, cpWalletService $cpWalletService)
    {

        $validatedData = $request->validate([
            'cp_id' => 'required|exists:channel_partners,id',
            'amount' => 'required|numeric|min:0.01|max:1000000',
        ]);
        try {
            $cpWalletService->addFundsToCp($validatedData['cp_id'], $validatedData['amount'], $request->all());
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while transferring funds: ' . $e->getMessage());
        }
        return redirect()->back()->with('success', 'Funds transferred successfully.');
    }

    public function deductFundFromCp()
    {
        $cp_list = ChannelPartner::with('wallet')
            ->whereIn('cp_role', ['2', '4'])
            ->get();
        return view('Admin.fundSetting.deductFund', compact('cp_list'));
    }

    public function storeFundDeduction(Request $request, cpWalletService $cpWalletService)
    {
        $validatedData = $request->validate([
            'cp_id' => 'required|exists:channel_partners,id',
            'amount' => 'required|numeric|min:0.01|max:1000000',
        ]);
        $validatedData = $request->validate([
            'cp_id' => 'required|exists:channel_partners,id',
            'amount' => 'required|numeric|min:0.01|max:1000000',
        ]);
        try {
            $cpWalletService->deductFundsFromCp($validatedData['cp_id'], $validatedData['amount'], $request->all());
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deducting funds: ' . $e->getMessage());
        }
        return redirect()->back()->with('success', 'Funds deducted successfully.');
    }

    public function fundTransactionList(Request $request)
    {
        $cp_list = ChannelPartner::whereIn('cp_role', ['2', '4'])->get();
        $query = CpWalletTransaction::with('channelPartner')
            ->orderBy('created_at', 'desc');

        if ($request->has('cp_id') && !empty($request->cp_id)) {
            
            $query->where('cp_id', $request->cp_id);
        }
        if($request->has('from_date') && $request->has('to_date') && !empty($request->from_date) && !empty($request->to_date)){
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }
        
            $transactions = $query->get();
            return view('Admin.fundSetting.fundTransactionList', compact('transactions', 'cp_list'));
        }
    
}
