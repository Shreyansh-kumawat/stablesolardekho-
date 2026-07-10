<?php

namespace App\Http\Controllers;

use App\Models\ManualInstallation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


/*


*/
class ManualInstallationController extends Controller
{
    public function newEntry()
    {
        return view('channelPartner.manualInstallationReport.addNewManualData');
    }

    public function storeManualInstallation(Request $request)
    {

        try {
            $manualInstallation = new ManualInstallation();
            $manualInstallation->channel_partner_id = Auth::user()->cp_id;
            $manualInstallation->customer_name = $request->customer_name;
            $manualInstallation->customer_number = $request->customer_number;
            $manualInstallation->dcr_panel_count = $request->dcr_panel_count;
            $manualInstallation->inverter_count = $request->inverter_count;
            $manualInstallation->meter_count = $request->meter_count;
            $manualInstallation->structure_count = $request->structure_count;
            $manualInstallation->acdb_dcdb_count = $request->acdb_dcdb_count;
            $manualInstallation->wire_count = $request->wire_count;
            $manualInstallation->nut_count = $request->nut_count;
            $manualInstallation->other_1_name = $request->other_1_name;
            $manualInstallation->other_1_count = $request->other_1_count;
            $manualInstallation->other_2_name = $request->other_2_name;
            $manualInstallation->other_2_count = $request->other_2_count;
            $manualInstallation->bill_number = $request->bill_number;
            $manualInstallation->bill_amount = $request->bill_amount;
            $manualInstallation->gst_amount = $request->gst_amount;
            $manualInstallation->total_amount = $request->total_amount;
            $manualInstallation->installation_status = $request->installation_status;
            $manualInstallation->net_metering_status = $request->net_metering_status;
            $manualInstallation->payment_1 = $request->payment_1;
            $manualInstallation->payment_2 = $request->payment_2;
            $manualInstallation->payment_3 = $request->payment_3;
            $manualInstallation->stable_deposit = $request->stable_deposit;
            $manualInstallation->other_deposit = $request->other_deposit;
            $manualInstallation->total_cost = $request->total_cost;
            $manualInstallation->total_deposit = $request->total_deposit;
            $manualInstallation->pending_amount = $request->pending_amount;
            $manualInstallation->added_by = Auth::user()->id;
            $manualInstallation->save();
        } catch (\Exception $e) {
            return redirect()->route('newManualEntry')->with('error', 'An error occurred while saving the manual installation report: ' . $e->getMessage());
        }
        return redirect()->route('newManualEntry')->with('success', 'Manual installation report saved successfully.');
    }

    public function myManualEntries()
    {
        $manualInstallations = ManualInstallation::where('channel_partner_id', Auth::user()->cp_id)->get();
        return view('channelPartner.manualInstallationReport.manualDataReport', compact('manualInstallations'));
    }
}
