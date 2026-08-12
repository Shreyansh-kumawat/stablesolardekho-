<?php

namespace App\Http\Controllers;

use App\Models\ChannelPartner;
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
        'other' => 'Other',
    ];

    private const COMPULSORY_TYPES = ['bill', 'dcr', 'stamp_paper', 'warranty_card'];

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
        $compulsoryTypes = self::COMPULSORY_TYPES;

        return view('Admin.documents.index', compact('documents', 'cps', 'docTypes', 'compulsoryTypes'));
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
        $compulsoryTypes = self::COMPULSORY_TYPES;
        $clients = CpDocument::where('cp_id', $cpId)
            ->whereNotNull('batch_id')
            ->select('batch_id', 'client_name', 'client_phone', 'client_address')
            ->groupBy('batch_id', 'client_name', 'client_phone', 'client_address')
            ->latest()
            ->get();

        return view('channelPartner.documents.index', compact('documents', 'docTypes', 'compulsoryTypes', 'clients'));
    }

    public function cpStore(Request $request)
    {
        $request->validate([
            'client_name' => 'required|string|max:255',
            'client_phone' => 'nullable|string|max:20',
            'client_address' => 'nullable|string|max:500',
            'remarks' => 'nullable|string|max:500',
        ]);

        $batchId = Str::uuid()->toString();
        $cpId = Auth::user()->cp_id;
        $uploadedBy = Auth::id();
        $count = 0;

        foreach (self::DOC_TYPES as $key => $label) {
            if ($request->hasFile('doc_' . $key)) {
                $file = $request->file('doc_' . $key);
                CpDocument::create([
                    'cp_id' => $cpId,
                    'client_name' => $request->client_name,
                    'client_phone' => $request->client_phone,
                    'client_address' => $request->client_address,
                    'batch_id' => $batchId,
                    'title' => $label . ' - ' . $request->client_name,
                    'document_type' => $key,
                    'file_path' => $file->store('cp-documents', 'public'),
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'uploaded_by' => $uploadedBy,
                    'remarks' => $request->remarks,
                ]);
                $count++;
            }
        }

        if ($count === 0) {
            return redirect()->back()->with('error', 'Please select at least one file to upload.');
        }

        return redirect()->back()->with('success', $count . ' document(s) uploaded for ' . $request->client_name . '.');
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

        return redirect()->back()->with('success', 'All documents for this client deleted.');
    }
}
