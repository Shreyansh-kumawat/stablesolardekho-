<?php

namespace App\Http\Controllers;

use App\Models\ChannelPartner;
use App\Models\CpMaterialLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CpMaterialLedgerController extends Controller
{
    public function adminIndex(Request $request)
    {
        $cps = ChannelPartner::where('is_active', 1)->orderBy('cp_name')->get();

        $query = CpMaterialLedger::with(['channelPartner', 'addedByUser']);

        if ($request->filled('cp_id')) {
            $query->where('cp_id', $request->cp_id);
        }
        if ($request->filled('from_date')) {
            $query->where('entry_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->where('entry_date', '<=', $request->to_date);
        }

        $entries = $query->orderByDesc('entry_date')->orderByDesc('id')->get();

        return view('Admin.materialLedger.index', compact('entries', 'cps'));
    }

    public function adminStore(Request $request)
    {
        $request->validate([
            'cp_id' => 'required|exists:channel_partners,id',
            'material_name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0.01',
            'unit' => 'required|string|max:50',
            'rate' => 'required|numeric|min:0',
            'entry_date' => 'required|date',
            'invoice_number' => 'nullable|string|max:100',
            'invoice_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:5120',
            'remarks' => 'nullable|string|max:500',
        ]);

        $data = $request->only(['cp_id', 'material_name', 'quantity', 'unit', 'rate', 'entry_date', 'invoice_number', 'remarks']);
        $data['total_amount'] = $request->quantity * $request->rate;
        $data['added_by'] = Auth::id();

        if ($request->hasFile('invoice_file')) {
            $data['invoice_file'] = $request->file('invoice_file')->store('material-invoices', 'public');
        }

        CpMaterialLedger::create($data);

        return redirect()->back()->with('success', 'Material entry added successfully.');
    }

    public function adminUpdate(Request $request, $id)
    {
        $request->validate([
            'material_name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0.01',
            'unit' => 'required|string|max:50',
            'rate' => 'required|numeric|min:0',
            'entry_date' => 'required|date',
            'invoice_number' => 'nullable|string|max:100',
            'invoice_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:5120',
            'remarks' => 'nullable|string|max:500',
        ]);

        $entry = CpMaterialLedger::findOrFail($id);
        $entry->fill($request->only(['material_name', 'quantity', 'unit', 'rate', 'entry_date', 'invoice_number', 'remarks']));
        $entry->total_amount = $request->quantity * $request->rate;

        if ($request->hasFile('invoice_file')) {
            if ($entry->invoice_file) {
                Storage::disk('public')->delete($entry->invoice_file);
            }
            $entry->invoice_file = $request->file('invoice_file')->store('material-invoices', 'public');
        }

        $entry->save();

        return redirect()->back()->with('success', 'Entry updated successfully.');
    }

    public function adminDelete($id)
    {
        $entry = CpMaterialLedger::findOrFail($id);
        if ($entry->invoice_file) {
            Storage::disk('public')->delete($entry->invoice_file);
        }
        $entry->delete();

        return redirect()->back()->with('success', 'Entry deleted successfully.');
    }

    public function cpIndex(Request $request)
    {
        $cpId = Auth::user()->cp_id;

        $query = CpMaterialLedger::with('addedByUser')->where('cp_id', $cpId);

        if ($request->filled('from_date')) {
            $query->where('entry_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->where('entry_date', '<=', $request->to_date);
        }

        $entries = $query->orderByDesc('entry_date')->orderByDesc('id')->get();

        return view('channelPartner.materialLedger.index', compact('entries'));
    }
}
