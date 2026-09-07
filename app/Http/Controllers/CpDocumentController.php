<?php

namespace App\Http\Controllers;

use App\Models\ChannelPartner;
use App\Models\CpClientPayment;
use App\Models\CpDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CpDocumentController extends Controller
{
    private const DOC_TYPES = [
        'bill' => 'Bill',
        'dcr' => 'DCR',
        'stamp_paper' => 'Stamp Paper',
        'warranty_card' => 'Warranty Card',
        'bank_invoice' => 'Bank Invoice',
    ];

    public function adminIndex(Request $request)
    {
        $cps = ChannelPartner::where('is_active', 1)->orderBy('cp_name')->get();

        $query = CpDocument::with(['channelPartner', 'uploadedByUser']);

        if ($request->filled('cp_id')) {
            $query->where('cp_id', $request->cp_id);
        }
        if ($request->filled('document_type')) {
            $query->where('document_type', $request->document_type);
        }
        if ($request->filled('client_name')) {
            $query->where('client_name', 'like', '%' . $request->client_name . '%');
        }

        $documents = $query->orderByDesc('created_at')->get();
        $docTypes = self::DOC_TYPES;

        $batchIds = $documents->whereNotNull('batch_id')->pluck('batch_id')->unique();
        $payments = CpClientPayment::whereIn('batch_id', $batchIds)->orderBy('payment_date')->get()->groupBy('batch_id');

        try { \App\Models\AdminLastSeen::markSeen(auth()->id(), 'cp_documents'); } catch (\Exception $e) {}
        return view('Admin.documents.index', compact('documents', 'cps', 'docTypes', 'payments'));
    }

    public function adminStore(Request $request)
    {
        $request->validate([
            'cp_id' => 'required|exists:channel_partners,id',
            'title' => 'required|string|max:255',
            'document_type' => 'required|string|max:50',
            'file' => 'required|file|max:20480',
            'remarks' => 'nullable|string|max:500',
        ]);

        $file = $request->file('file');

        CpDocument::create([
            'cp_id' => $request->cp_id,
            'client_name' => $request->client_name,
            'client_phone' => $request->client_phone,
            'client_address' => $request->client_address,
            'title' => $request->title,
            'document_type' => $request->document_type,
            'file_path' => $file->store('cp-documents', 'public'),
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'uploaded_by' => Auth::id(),
            'remarks' => $request->remarks,
        ]);

        return redirect()->back()->with('success', 'Document uploaded successfully.');
    }

    public function adminDelete($id)
    {
        $doc = CpDocument::findOrFail($id);
        Storage::disk('public')->delete($doc->file_path);
        $doc->delete();

        return redirect()->back()->with('success', 'Document deleted successfully.');
    }

    public function cpIndex(Request $request)
    {
        $cpId = Auth::user()->cp_id;

        $query = CpDocument::where('cp_id', $cpId)->with('uploadedByUser');

        if ($request->filled('client_name')) {
            $query->where('client_name', 'like', '%' . $request->client_name . '%');
        }

        $documents = $query->orderByDesc('created_at')->get();
        $docTypes = self::DOC_TYPES;
        $clients = CpDocument::where('cp_id', $cpId)
            ->whereNotNull('batch_id')
            ->select('batch_id', 'client_name', 'client_phone', 'client_address')
            ->groupBy('batch_id', 'client_name', 'client_phone', 'client_address')
            ->latest()
            ->get();

        $batchIds = $documents->whereNotNull('batch_id')->pluck('batch_id')->unique();
        $payments = CpClientPayment::whereIn('batch_id', $batchIds)->orderBy('payment_date')->get()->groupBy('batch_id');

        return view('channelPartner.documents.index', compact('documents', 'docTypes', 'clients', 'payments'));
    }

    public function cpStore(Request $request)
    {
        $request->validate([
            'client_name' => 'nullable|string|max:255',
            'client_phone' => 'nullable|string|max:20',
            'client_address' => 'nullable|string|max:500',
            'remarks' => 'nullable|string|max:500',
            'total_receivable' => 'nullable|numeric|min:0',
            'instalment_amount' => 'nullable|numeric|min:0',
            'instalment_date' => 'nullable|date',
        ]);

        $batchId = $request->input('batch_id') ?: Str::uuid()->toString();
        $cpId = Auth::user()->cp_id;
        $uploadedBy = Auth::id();
        $count = 0;
        $isUpdate = (bool) $request->input('batch_id');

        foreach (self::DOC_TYPES as $key => $label) {
            if ($request->hasFile('doc_' . $key)) {
                $file = $request->file('doc_' . $key);
                CpDocument::create([
                    'cp_id' => $cpId,
                    'client_name' => $request->client_name,
                    'client_phone' => $request->client_phone,
                    'client_address' => $request->client_address,
                    'batch_id' => $batchId,
                    'title' => $label . ($request->client_name ? ' - ' . $request->client_name : ''),
                    'document_type' => $key,
                    'file_path' => $file->store('cp-documents', 'public'),
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'uploaded_by' => $uploadedBy,
                    'remarks' => $request->remarks,
                    'total_receivable' => $request->total_receivable,
                ]);
                $count++;
            }
        }

        $otherNames = $request->input('other_names', []);
        $otherFiles = $request->file('other_files', []);
        foreach ($otherFiles as $i => $file) {
            if ($file && $file->isValid()) {
                $customName = trim($otherNames[$i] ?? '');
                if (!$customName) $customName = 'Document ' . ($i + 1);
                CpDocument::create([
                    'cp_id' => $cpId,
                    'client_name' => $request->client_name,
                    'client_phone' => $request->client_phone,
                    'client_address' => $request->client_address,
                    'batch_id' => $batchId,
                    'title' => $customName . ($request->client_name ? ' - ' . $request->client_name : ''),
                    'document_type' => 'other',
                    'file_path' => $file->store('cp-documents', 'public'),
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'uploaded_by' => $uploadedBy,
                    'remarks' => $request->remarks,
                    'total_receivable' => $request->total_receivable,
                ]);
                $count++;
            }
        }

        if ($request->filled('total_receivable') && $isUpdate) {
            CpDocument::where('batch_id', $batchId)->update(['total_receivable' => $request->total_receivable]);
        }

        if ($request->filled('instalment_amount') && $request->filled('instalment_date')) {
            CpClientPayment::create([
                'batch_id' => $batchId,
                'cp_id' => $cpId,
                'amount' => $request->instalment_amount,
                'payment_date' => $request->instalment_date,
                'remarks' => $request->input('instalment_remarks'),
                'added_by' => $uploadedBy,
            ]);
        }

        if ($count === 0 && !$isUpdate) {
            $hasPayment = $request->filled('instalment_amount') && $request->filled('instalment_date');
            $hasClientInfo = $request->filled('client_name') || $request->filled('total_receivable');

            if (!$hasPayment && !$hasClientInfo) {
                return redirect()->back()->with('error', 'Please select at least one file to upload or fill in client details.');
            }

            CpDocument::create([
                'cp_id' => $cpId,
                'client_name' => $request->client_name,
                'client_phone' => $request->client_phone,
                'client_address' => $request->client_address,
                'batch_id' => $batchId,
                'title' => $request->client_name ?: 'Client Record',
                'document_type' => 'record',
                'uploaded_by' => $uploadedBy,
                'remarks' => $request->remarks,
                'total_receivable' => $request->total_receivable,
            ]);
        }

        $msg = $count > 0 ? $count . ' document(s) uploaded' : 'Client record created';
        if ($request->filled('instalment_amount')) $msg .= ' with payment instalment';
        if ($request->client_name) $msg .= ' for ' . $request->client_name;
        return redirect()->back()->with('success', $msg . '.');
    }

    public function cpAddPayment(Request $request)
    {
        $request->validate([
            'batch_id' => 'required|string',
            'instalment_amount' => 'required|numeric|min:0.01',
            'instalment_date' => 'required|date',
            'instalment_remarks' => 'nullable|string|max:500',
            'total_receivable' => 'nullable|numeric|min:0',
        ]);

        $cpId = Auth::user()->cp_id;
        $batch = CpDocument::where('cp_id', $cpId)->where('batch_id', $request->batch_id)->first();
        if (!$batch) abort(404);

        if ($request->filled('total_receivable')) {
            CpDocument::where('batch_id', $request->batch_id)->update(['total_receivable' => $request->total_receivable]);
        }

        CpClientPayment::create([
            'batch_id' => $request->batch_id,
            'cp_id' => $cpId,
            'amount' => $request->instalment_amount,
            'payment_date' => $request->instalment_date,
            'remarks' => $request->instalment_remarks,
            'added_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Payment instalment added.');
    }

    public function cpDeletePayment($id)
    {
        $payment = CpClientPayment::where('cp_id', Auth::user()->cp_id)->findOrFail($id);
        $payment->delete();

        return redirect()->back()->with('success', 'Payment entry deleted.');
    }

    public function cpDelete($id)
    {
        $doc = CpDocument::where('cp_id', Auth::user()->cp_id)->findOrFail($id);
        Storage::disk('public')->delete($doc->file_path);
        $doc->delete();

        return redirect()->back()->with('success', 'Document deleted successfully.');
    }

    public function cpDeleteBatch($batchId)
    {
        $cpId = Auth::user()->cp_id;
        $docs = CpDocument::where('cp_id', $cpId)->where('batch_id', $batchId)->get();

        if ($docs->isEmpty()) abort(404);

        foreach ($docs as $doc) {
            Storage::disk('public')->delete($doc->file_path);
            $doc->delete();
        }

        CpClientPayment::where('batch_id', $batchId)->where('cp_id', $cpId)->delete();

        return redirect()->back()->with('success', 'All documents for this client deleted.');
    }
}
