<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function userQuoteQuery(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'customer_name' => 'required|string|max:255',
            'mobile_number' => 'nullable|string|max:20',
            'email_id' => 'nullable|email|max:255',
            'monthly_bill' => 'nullable|string|max:50',
            'connection_type' => 'required|string|max:100',
            'pin_code' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'complete_address' => 'nullable|string|max:500',
            'state_name' => 'nullable|string|max:100',
            'proposed_capacity' => 'nullable|string|max:50',
            'remarks' => 'nullable|string',
        ]);

        // Here you can handle the logic to store the lead in the database
        // For example:
        // SolarLead::create($validatedData);

        // Return a response, e.g., redirect back with a success message
        return redirect()->back()->with('success', 'Your quote request has been submitted successfully.');
    }
}
