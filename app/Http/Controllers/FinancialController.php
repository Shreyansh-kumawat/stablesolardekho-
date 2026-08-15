<?php

namespace App\Http\Controllers;

use App\Models\ChannelPartner;
use App\Models\CpMaterialLedger;
use App\Models\CpOrder;
use App\Models\CpPayment;
use App\Models\CustomerOrder;
use App\Models\CpWalletTransaction;
use App\Models\ProductInventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FinancialController extends Controller
{
    private function cpOrderAmountCol(): string
    {
        return Schema::hasColumn('cp_orders', 'grand_total') ? 'grand_total' : 'quote_amount';
    }

    public function dashboard(Request $request)
    {
        $period = $request->get('period', 'all');
        $dateFilter = $this->getDateRange($period, $request);

        $cpOrders = CpOrder::query();
        $custOrders = CustomerOrder::query();
        $payments = CpPayment::query();
        $materials = CpMaterialLedger::query();

        if ($dateFilter) {
            $cpOrders->whereBetween('order_date', $dateFilter);
            $custOrders->whereBetween('created_at', [$dateFilter[0], $dateFilter[1] . ' 23:59:59']);
            $payments->whereBetween('payment_date', $dateFilter);
            $materials->whereBetween('entry_date', $dateFilter);
        }

        $cpOrderStats = [
            'count' => (clone $cpOrders)->count(),
            'pending_count' => (clone $cpOrders)->where('status', 'pending')->count(),
            'delivered_count' => (clone $cpOrders)->whereIn('status', ['completed', 'confirmed', 'delivered'])->count(),
        ];

        $custOrderStats = [
            'total' => (clone $custOrders)->sum('total_amount'),
            'count' => (clone $custOrders)->count(),
            'paid' => (clone $custOrders)->where('payment_status', 'paid')->sum('total_amount'),
        ];

        $paymentStats = [
            'received' => (clone $payments)->where('status', 'verified')->sum('amount'),
            'pending' => (clone $payments)->where('status', 'pending')->sum('amount'),
            'count' => (clone $payments)->count(),
        ];

        $materialStats = [
            'total_spent' => (clone $materials)->sum('total_amount'),
            'entries' => (clone $materials)->count(),
        ];

        $inventoryCost = ProductInventoryTransaction::where('transaction_type', 'IN')
            ->whereNotNull('unit_price');
        $salesRevenue = ProductInventoryTransaction::where('transaction_type', 'OUT')
            ->whereNotNull('unit_price');
        if ($dateFilter) {
            $inventoryCost->whereBetween('created_at', [$dateFilter[0], $dateFilter[1] . ' 23:59:59']);
            $salesRevenue->whereBetween('created_at', [$dateFilter[0], $dateFilter[1] . ' 23:59:59']);
        }
        $inventoryCostStats = [
            'total' => (clone $inventoryCost)->selectRaw('SUM(unit_price * quantity) as total')->value('total') ?? 0,
            'entries' => (clone $inventoryCost)->count(),
        ];
        $salesRevenueTotal = (clone $salesRevenue)->selectRaw('SUM(unit_price * quantity) as total')->value('total') ?? 0;

        $revenue = [
            'sales' => $salesRevenueTotal,
            'customer_orders' => $custOrderStats['paid'],
            'cp_payments' => $paymentStats['received'],
        ];
        $revenue['total'] = $revenue['sales'] + $revenue['customer_orders'];

        return view('Admin.financial.dashboard', compact(
            'cpOrderStats', 'custOrderStats', 'paymentStats', 'materialStats',
            'inventoryCostStats', 'revenue', 'period'
        ));
    }

    public function profitLoss(Request $request)
    {
        $period = $request->get('period', 'all');
        $dateFilter = $this->getDateRange($period, $request);

        $custRevenue = CustomerOrder::query();
        $materialExpenses = CpMaterialLedger::query();
        $walletCredits = CpWalletTransaction::where('transaction_type', 'credit');

        if ($dateFilter) {
            $custRevenue->whereBetween('created_at', [$dateFilter[0], $dateFilter[1] . ' 23:59:59']);
            $materialExpenses->whereBetween('entry_date', $dateFilter);
            $walletCredits->whereBetween('created_at', [$dateFilter[0], $dateFilter[1] . ' 23:59:59']);
        }

        $inventoryPurchase = ProductInventoryTransaction::where('transaction_type', 'IN')
            ->whereNotNull('unit_price');
        $salesOut = ProductInventoryTransaction::where('transaction_type', 'OUT')
            ->whereNotNull('unit_price');
        if ($dateFilter) {
            $inventoryPurchase->whereBetween('created_at', [$dateFilter[0], $dateFilter[1] . ' 23:59:59']);
            $salesOut->whereBetween('created_at', [$dateFilter[0], $dateFilter[1] . ' 23:59:59']);
        }
        $inventoryPurchaseCost = (clone $inventoryPurchase)->selectRaw('SUM(unit_price * quantity) as total')->value('total') ?? 0;
        $salesRevenueTotal = (clone $salesOut)->selectRaw('SUM(unit_price * quantity) as total')->value('total') ?? 0;

        $revenue = [
            'sales' => $salesRevenueTotal,
            'customer_orders' => (clone $custRevenue)->where('payment_status', 'paid')->sum('total_amount'),
        ];
        $revenue['total'] = $revenue['sales'] + $revenue['customer_orders'];

        $expenses = [
            'inventory_purchase' => $inventoryPurchaseCost,
            'material_cost' => $materialExpenses->sum('total_amount'),
            'wallet_transfers' => $walletCredits->sum('amount'),
        ];
        $expenses['total'] = $expenses['inventory_purchase'] + $expenses['material_cost'] + $expenses['wallet_transfers'];

        $profitLoss = $revenue['total'] - $expenses['total'];

        $outstanding = [
            'cp_pending' => CpOrder::where('status', 'pending')->count(),
            'customer_pending' => CustomerOrder::where('payment_status', 'pending')->sum('total_amount'),
        ];
        $outstanding['total'] = $outstanding['customer_pending'];

        return view('Admin.financial.profitLoss', compact(
            'revenue', 'expenses', 'profitLoss', 'outstanding', 'period'
        ));
    }

    private function getDateRange($period, Request $request): ?array
    {
        return match ($period) {
            'today' => [today()->toDateString(), today()->toDateString()],
            'week' => [now()->startOfWeek()->toDateString(), now()->endOfWeek()->toDateString()],
            'month' => [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()],
            'year' => [now()->startOfYear()->toDateString(), now()->endOfYear()->toDateString()],
            'custom' => $request->filled('from_date') && $request->filled('to_date')
                ? [$request->from_date, $request->to_date]
                : null,
            default => null,
        };
    }
}
