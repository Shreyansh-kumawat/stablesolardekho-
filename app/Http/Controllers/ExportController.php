<?php

namespace App\Http\Controllers;

use App\Models\ChannelPartner;
use App\Models\CpMaterialLedger;
use App\Models\CpOrder;
use App\Models\CpPayment;
use App\Models\CustomerOrder;
use App\Models\InstallationStory;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductInventory;
use App\Models\ProductInventoryTransaction;
use App\Models\SolarTeam;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function index()
    {
        $customerOrders = CustomerOrder::with('user')->orderByDesc('id')->limit(50)->get();
        $cpOrders = CpOrder::with('channelPartner')->orderByDesc('id')->limit(50)->get();
        $cpPayments = CpPayment::with('channelPartner')->orderByDesc('payment_date')->limit(50)->get();
        $materialLedger = CpMaterialLedger::with('channelPartner')->orderByDesc('entry_date')->limit(50)->get();
        $users = User::where('role_id', 3)->orderByDesc('id')->limit(50)->get();
        $channelPartners = ChannelPartner::orderByDesc('id')->limit(50)->get();
        $products = Product::with('category')->orderByDesc('id')->limit(50)->get();
        $inventoryStock = DB::table('products as p')
            ->leftJoin('product_inventories as pi', 'pi.product_id', '=', 'p.id')
            ->select('p.id', 'p.item_name', 'p.item_code', 'p.uom', DB::raw('COALESCE(pi.available_qty, 0) as available_qty'))
            ->orderBy('p.item_name')->limit(50)->get();
        $invTransactions = ProductInventoryTransaction::with('product')->orderByDesc('created_at')->limit(50)->get();
        $solarTeam = SolarTeam::orderByDesc('id')->limit(50)->get();
        $stories = InstallationStory::orderByDesc('id')->limit(50)->get();
        $categories = ProductCategory::with(['subCategories.products', 'products'])->orderBy('category_name')->get();

        return view('Admin.export.index', compact(
            'customerOrders', 'cpOrders', 'cpPayments', 'materialLedger', 'users', 'channelPartners',
            'products', 'inventoryStock', 'invTransactions', 'solarTeam', 'stories', 'categories'
        ));
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

    public function exportUsers()
    {
        $users = User::where('role_id', 3)->orderByDesc('id')->get();
        $headers = ['Name', 'Email', 'Phone', 'Address', 'City', 'District', 'State', 'Pincode', 'Registered'];

        return $this->streamCsv('users.csv', $headers, $users, function ($u) {
            return [
                $u->name,
                $u->email,
                $u->mobile_number ?? '',
                $u->address ?? '',
                $u->city ?? '',
                $u->district ?? '',
                $u->state ?? '',
                $u->pincode ?? '',
                $u->created_at?->format('Y-m-d'),
            ];
        });
    }

    public function exportProducts()
    {
        $products = Product::with('category')->orderByDesc('id')->get();
        $headers = ['Item Name', 'Item Code', 'Category', 'UOM', 'Price', 'Stock', 'Kit', 'Active', 'Featured', 'Created'];

        return $this->streamCsv('products.csv', $headers, $products, function ($p) {
            return [
                $p->item_name,
                $p->item_code,
                $p->category->category_name ?? '-',
                $p->uom,
                $p->current_sale_price,
                $p->quantity,
                $p->is_kit ? 'Yes' : 'No',
                $p->is_active ? 'Yes' : 'No',
                $p->is_featured ? 'Yes' : 'No',
                $p->created_at?->format('Y-m-d'),
            ];
        });
    }

    public function exportInventoryStock()
    {
        $items = DB::table('products as p')
            ->leftJoin('product_inventories as pi', 'pi.product_id', '=', 'p.id')
            ->leftJoin('product_inventory_transactions as t', 't.product_id', '=', 'p.id')
            ->select(
                'p.item_name', 'p.item_code', 'p.uom',
                DB::raw("COALESCE(pi.available_qty, 0) as available_qty"),
                DB::raw("SUM(CASE WHEN t.transaction_type = 'IN' THEN t.quantity ELSE 0 END) as total_in"),
                DB::raw("SUM(CASE WHEN t.transaction_type = 'OUT' THEN t.quantity ELSE 0 END) as total_out")
            )
            ->groupBy('p.id', 'p.item_name', 'p.item_code', 'p.uom', 'pi.available_qty')
            ->orderBy('p.item_name')->get();

        $headers = ['Item Name', 'Item Code', 'UOM', 'Total IN', 'Total OUT', 'Current Stock'];

        return $this->streamCsv('inventory_stock.csv', $headers, $items, function ($i) {
            return [$i->item_name, $i->item_code, $i->uom, $i->total_in, $i->total_out, $i->available_qty];
        });
    }

    public function exportInvTransactions()
    {
        $txns = ProductInventoryTransaction::with(['product', 'channelPartner'])->orderByDesc('created_at')->get();
        $headers = ['Product', 'Type', 'Quantity', 'Unit Price', 'Invoice No', 'Invoice Date', 'Channel Partner', 'Remarks', 'Date'];

        return $this->streamCsv('inventory_transactions.csv', $headers, $txns, function ($t) {
            return [
                $t->product->item_name ?? '-',
                $t->transaction_type,
                $t->quantity,
                $t->unit_price ?? 0,
                $t->invoice_number ?? '',
                $t->invoice_date ?? '',
                $t->channelPartner->cp_name ?? '-',
                $t->remarks ?? '',
                $t->created_at?->format('Y-m-d H:i'),
            ];
        });
    }

    public function exportSolarTeam()
    {
        $team = SolarTeam::orderByDesc('id')->get();
        $headers = ['Name', 'Position', 'Mobile', 'Address', 'District', 'State', 'Status', 'Created'];

        return $this->streamCsv('solar_team.csv', $headers, $team, function ($m) {
            return [
                $m->name,
                $m->position,
                $m->mobile_number ?? '',
                $m->address ?? '',
                $m->district ?? '',
                $m->state ?? '',
                $m->status ? 'Active' : 'Inactive',
                $m->created_at?->format('Y-m-d'),
            ];
        });
    }

    public function exportStories()
    {
        $stories = InstallationStory::orderByDesc('id')->get();
        $headers = ['Type', 'Location', 'System Size (KW)', 'Installation Date', 'Active', 'Created'];

        return $this->streamCsv('installation_stories.csv', $headers, $stories, function ($s) {
            return [
                $s->installation_type,
                $s->location,
                $s->system_size_kw ?? '',
                $s->installation_date ?? '',
                $s->active_status ? 'Yes' : 'No',
                $s->created_at?->format('Y-m-d'),
            ];
        });
    }

    public function exportCategories()
    {
        $categories = ProductCategory::with(['subCategories.products', 'products'])->orderBy('category_name')->get();

        return response()->streamDownload(function () use ($categories) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($handle, ['Type', 'Category', 'Sub Category', 'Status']);
            foreach ($categories as $cat) {
                fputcsv($handle, ['Category', $cat->category_name, '', $cat->active_status ? 'Active' : 'Inactive']);
                foreach ($cat->subCategories as $sub) {
                    fputcsv($handle, ['Sub Category', $cat->category_name, $sub->sub_category_name, '']);
                }
            }
            fclose($handle);
        }, 'categories.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
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
