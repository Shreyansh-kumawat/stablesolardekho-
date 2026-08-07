<?php

namespace App\Http\Controllers;

use App\Models\ChannelPartner;
use App\Models\CpMaterialLedger;
use App\Models\CpOrder;
use App\Models\CpPayment;
use App\Models\CustomerOrder;
use App\Models\ProductInventory;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function index()
    {
        return view('Admin.export.index');
    }

    public function exportCpOrders(Request $request)
    {
        $query = CpOrder::with('channelPartner');
        if ($request->filled('from_date')) $query->where('order_date', '>=', $request->from_date);
        if ($request->filled('to_date')) $query->where('order_date', '<=', $request->to_date);
        $orders = $query->orderByDesc('id')->get();

        $headers = ['Order ID', 'CP Name', 'Order Date', 'Status', 'Quote Amount', 'Grand Total', 'Payment Status'];

        return $this->streamCsv('cp_orders.csv', $headers, $orders, function ($o) {
            return [
                $o->order_id,
                $o->channelPartner->cp_name ?? '-',
                $o->order_date,
                $o->status,
                $o->quote_amount ?? 0,
                $o->grand_total ?? $o->quote_amount ?? 0,
                $o->payment_status ?? 'N/A',
            ];
        });
    }

    public function exportCustomerOrders(Request $request)
    {
        $query = CustomerOrder::with('user');
        if ($request->filled('from_date')) $query->where('created_at', '>=', $request->from_date);
        if ($request->filled('to_date')) $query->where('created_at', '<=', $request->to_date . ' 23:59:59');
        $orders = $query->orderByDesc('id')->get();

        $headers = ['Order No', 'Customer', 'Phone', 'City', 'State', 'Total', 'Payment Method', 'Payment Status', 'Status', 'Date'];

        return $this->streamCsv('customer_orders.csv', $headers, $orders, function ($o) {
            return [
                $o->order_number,
                $o->name,
                $o->phone,
                $o->city,
                $o->state,
                $o->total_amount,
                $o->payment_method,
                $o->payment_status,
                $o->status,
                $o->created_at->format('Y-m-d'),
            ];
        });
    }

    public function exportInventory()
    {
        $items = ProductInventory::with('product')->get();

        $headers = ['Product', 'Available Qty', 'Total In', 'Total Out', 'Updated At'];

        return $this->streamCsv('inventory.csv', $headers, $items, function ($i) {
            return [
                $i->product->name ?? '-',
                $i->available_quantity,
                $i->total_quantity_in ?? 0,
                $i->total_quantity_out ?? 0,
                $i->updated_at?->format('Y-m-d'),
            ];
        });
    }

    public function exportMaterialLedger(Request $request)
    {
        $query = CpMaterialLedger::with('channelPartner');
        if ($request->filled('from_date')) $query->where('entry_date', '>=', $request->from_date);
        if ($request->filled('to_date')) $query->where('entry_date', '<=', $request->to_date);
        $entries = $query->orderByDesc('entry_date')->get();

        $headers = ['CP Name', 'Material', 'Qty', 'Unit', 'Rate', 'Total Amount', 'Date', 'Invoice No', 'Remarks'];

        return $this->streamCsv('material_ledger.csv', $headers, $entries, function ($e) {
            return [
                $e->channelPartner->cp_name ?? '-',
                $e->material_name,
                $e->quantity,
                $e->unit,
                $e->rate,
                $e->total_amount,
                $e->entry_date,
                $e->invoice_number ?? '',
                $e->remarks ?? '',
            ];
        });
    }

    public function exportPayments(Request $request)
    {
        $query = CpPayment::with('channelPartner');
        if ($request->filled('from_date')) $query->where('payment_date', '>=', $request->from_date);
        if ($request->filled('to_date')) $query->where('payment_date', '<=', $request->to_date);
        $payments = $query->orderByDesc('payment_date')->get();

        $headers = ['CP Name', 'Amount', 'Mode', 'Reference', 'Date', 'Status', 'Remarks'];

        return $this->streamCsv('cp_payments.csv', $headers, $payments, function ($p) {
            return [
                $p->channelPartner->cp_name ?? '-',
                $p->amount,
                $p->payment_mode,
                $p->reference_number ?? '',
                $p->payment_date,
                $p->status,
                $p->remarks ?? '',
            ];
        });
    }

    public function exportChannelPartners()
    {
        $cps = ChannelPartner::all();
        $headers = ['Name', 'Contact Person', 'Email', 'Phone', 'City', 'State', 'Active', 'Created'];

        return $this->streamCsv('channel_partners.csv', $headers, $cps, function ($c) {
            return [
                $c->cp_name,
                $c->contact_person,
                $c->email,
                $c->phone_number,
                $c->city,
                $c->state,
                $c->is_active ? 'Yes' : 'No',
                $c->created_at?->format('Y-m-d'),
            ];
        });
    }

    private function streamCsv(string $filename, array $headers, $data, callable $rowMapper): StreamedResponse
    {
        return response()->streamDownload(function () use ($headers, $data, $rowMapper) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($handle, $headers);
            foreach ($data as $item) {
                fputcsv($handle, $rowMapper($item));
            }
            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
