<?php

namespace App\Http\Controllers;

use App\Models\ChannelPartner;
use App\Models\CpDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

        if ($request->filled('document_type')) {
            $query->where('document_type', $request->document_type);
        }

        $documents = $query->orderByDesc('created_at')->get();
        $docTypes = self::DOC_TYPES;
        $compulsoryTypes = self::COMPULSORY_TYPES;

        return view('channelPartner.documents.index', compact('documents', 'docTypes', 'compulsoryTypes'));
    }

    public function cpStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'document_type' => 'required|string|max:50',
            'file' => 'required|file|max:20480',
            'remarks' => 'nullable|string|max:500',
        ]);

        $file = $request->file('file');

        CpDocument::create([
            'cp_id' => Auth::user()->cp_id,
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

    public function cpDelete($id)
    {
        $doc = CpDocument::where('cp_id', Auth::user()->cp_id)->findOrFail($id);
        Storage::disk('public')->delete($doc->file_path);
        $doc->delete();

        return redirect()->back()->with('success', 'Document deleted successfully.');
    }
}
