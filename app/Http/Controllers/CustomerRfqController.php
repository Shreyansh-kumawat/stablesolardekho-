<?php

namespace App\Http\Controllers;

use App\Models\CustomerRfq;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CustomerRfqController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'nullable|email|max:255',
            'city' => 'nullable|string|max:100',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string|max:255',
            'items.*.qty' => 'required|integer|min:1|max:99999',
            'preferred_brand' => 'nullable|string|max:255',
            'additional_notes' => 'nullable|string|max:1000',
        ]);

        $items = array_values(array_filter($request->items, function ($it) {
            return !empty($it['name']) && !empty($it['qty']);
        }));

        if (empty($items)) {
            return redirect()->back()->withInput()->withErrors(['items' => 'Please add at least one product with quantity.']);
        }

        $lines = [];
        $totalQty = 0;
        foreach ($items as $i => $it) {
            $qty = (int) $it['qty'];
            $lines[] = ($i + 1) . '. ' . trim($it['name']) . ' - Qty: ' . $qty;
            $totalQty += $qty;
        }
        $itemDescription = implode("\n", $lines);

        CustomerRfq::create([
            'user_id' => Auth::check() ? Auth::id() : null,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'city' => $request->city,
            'item_description' => $itemDescription,
            'quantity' => $totalQty,
            'preferred_brand' => $request->preferred_brand,
            'additional_notes' => $request->additional_notes,
        ]);

        return redirect()->back()->with('rfq_success', 'Your request has been submitted successfully! We will contact you soon with a quote.');
    }

    public function myRequests()
    {
        $rfqs = CustomerRfq::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        return view('user.rfq-requests', compact('rfqs'));
    }

    public function adminIndex(Request $request)
    {
        abort_unless(Auth::user()->hasAdminPermission('rfq.view'), 403);
        $query = CustomerRfq::with(['user', 'product', 'processor']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('item_description', 'like', "%{$search}%");
            });
        }

        $rfqs = $query->orderByDesc('created_at')->get();
        $statusCounts = CustomerRfq::selectRaw('status, COUNT(*) as count')->groupBy('status')->pluck('count', 'status');

        return view('Admin.rfq.index', compact('rfqs', 'statusCounts'));
    }

    public function adminShow($id)
    {
        abort_unless(Auth::user()->hasAdminPermission('rfq.view'), 403);
        $rfq = CustomerRfq::with(['user', 'product', 'processor'])->findOrFail($id);
        $categories = ProductCategory::where('active_status', 1)->orderBy('category_name')->get();

        return view('Admin.rfq.show', compact('rfq', 'categories'));
    }

    public function adminProcess(Request $request, $id)
    {
        abort_unless(Auth::user()->hasAdminPermission('rfq.manage'), 403);
        $request->validate([
            'status' => 'required|in:processing,quoted,accepted,rejected,closed',
            'product_id' => 'nullable|exists:products,id',
            'quoted_price' => 'nullable|numeric|min:0',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'final_price' => 'nullable|numeric|min:0',
            'admin_remarks' => 'nullable|string|max:1000',
        ]);

        $rfq = CustomerRfq::findOrFail($id);

        $data = [
            'status' => $request->status,
            'product_id' => $request->product_id,
            'quoted_price' => $request->quoted_price,
            'discount_percent' => $request->discount_percent,
            'final_price' => $request->final_price,
            'admin_remarks' => $request->admin_remarks,
            'processed_by' => Auth::id(),
        ];

        if ($request->status === 'quoted' && !$rfq->quoted_at) {
            $data['quoted_at'] = now();
        }

        $rfq->update($data);

        return redirect()->route('admin.rfq.show', $rfq->id)->with('success', 'RFQ updated successfully.');
    }

    public function adminExport(Request $request): StreamedResponse
    {
        abort_unless(Auth::user()->hasAdminPermission('rfq.view'), 403);
        $query = CustomerRfq::with(['product', 'processor']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rfqs = $query->orderByDesc('created_at')->get();

        $filename = 'rfq_export_' . date('Y-m-d') . '.csv';

        return new StreamedResponse(function () use ($rfqs) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Date', 'Name', 'Phone', 'Email', 'City', 'Item', 'Qty', 'Brand', 'Status', 'Matched Product', 'Quoted Price', 'Discount %', 'Final Price', 'Processed By', 'Remarks']);
            foreach ($rfqs as $rfq) {
                fputcsv($handle, [
                    $rfq->id,
                    $rfq->created_at->format('d-m-Y H:i'),
                    $rfq->name,
                    $rfq->phone,
                    $rfq->email ?? '-',
                    $rfq->city ?? '-',
                    $rfq->item_description,
                    $rfq->quantity,
                    $rfq->preferred_brand ?? '-',
                    $rfq->status,
                    $rfq->product->item_name ?? '-',
                    $rfq->quoted_price ?? '-',
                    $rfq->discount_percent ?? '-',
                    $rfq->final_price ?? '-',
                    $rfq->processor->name ?? '-',
                    $rfq->admin_remarks ?? '-',
                ]);
            }
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
