<?php

namespace App\Http\Controllers;

use App\Models\SolarLead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function userQuoteQuery(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mob_no' => 'required|string|max:20',
            'bill' => 'required|string|max:50',
            'pin' => 'required|string|max:10',
            'city' => 'nullable|string|max:100',
        ]);

        SolarLead::create([
            'lead_id' => 'LEAD' . time() . rand(100, 999),
            'customer_name' => $request->name,
            'mobile_number' => $request->mob_no,
            'monthly_bill' => $request->bill,
            'connection_type' => 'residential',
            'pin_code' => $request->pin,
            'city' => $request->city,
            'lead_status' => 0,
        ]);

        return redirect()->back()->with('success', 'Your quote request has been submitted successfully.');
    }

    public function formLeads()
    {
        $leads = SolarLead::latest()->get();
        return view('Admin.leads.formLeads', compact('leads'));
    }

    public function deleteFormLead($id)
    {
        SolarLead::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Lead deleted.');
    }
}
