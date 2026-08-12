<?php

namespace App\Http\Controllers;

use App\Models\ChannelPartner;
use App\Models\CpMaterialLedger;
use App\Models\CpOrder;
use App\Models\CpPayment;
use App\Models\CustomerOrder;
use App\Models\CpWalletTransaction;
use Illuminate\Http\Request;
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
        $amtCol = $this->cpOrderAmountCol();

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
            'total' => (clone $cpOrders)->sum($amtCol),
            'count' => (clone $cpOrders)->count(),
            'pending' => (clone $cpOrders)->where('status', 'pending')->sum($amtCol),
            'completed' => (clone $cpOrders)->whereIn('status', ['completed', 'confirmed', 'delivered'])->sum($amtCol),
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

        $topCps = ChannelPartner::select('channel_partners.*')
            ->selectRaw("COALESCE((SELECT SUM({$amtCol}) FROM cp_orders WHERE cp_orders.cp_id = channel_partners.id), 0) as total_orders")
            ->selectRaw('COALESCE((SELECT SUM(amount) FROM cp_payments WHERE cp_payments.cp_id = channel_partners.id AND cp_payments.status = "verified"), 0) as total_paid')
            ->where('is_active', 1)
            ->orderByDesc('total_orders')
            ->limit(10)
            ->get();

        $driver = config('database.default');
        $monthExpr = $driver === 'sqlite'
            ? 'strftime("%Y-%m", order_date)'
            : 'DATE_FORMAT(order_date, "%Y-%m")';

        $monthlyRevenue = CpOrder::selectRaw("$monthExpr as month, SUM({$amtCol}) as total")
            ->whereNotNull($amtCol)
            ->groupBy('month')
            ->orderBy('month')
            ->limit(12)
            ->get();

        return view('Admin.financial.dashboard', compact(
            'cpOrderStats', 'custOrderStats', 'paymentStats', 'materialStats',
            'topCps', 'monthlyRevenue', 'period'
        ));
    }

    public function profitLoss(Request $request)
    {
        $period = $request->get('period', 'all');
        $dateFilter = $this->getDateRange($period, $request);
        $amtCol = $this->cpOrderAmountCol();

        $cpRevenue = CpOrder::query();
        $custRevenue = CustomerOrder::query();
        $paymentsReceived = CpPayment::where('status', 'verified');
        $materialExpenses = CpMaterialLedger::query();
        $walletCredits = CpWalletTransaction::where('transaction_type', 'credit');
        $walletDebits = CpWalletTransaction::where('transaction_type', 'debit');

        if ($dateFilter) {
            $cpRevenue->whereBetween('order_date', $dateFilter);
            $custRevenue->whereBetween('created_at', [$dateFilter[0], $dateFilter[1] . ' 23:59:59']);
            $paymentsReceived->whereBetween('payment_date', $dateFilter);
            $materialExpenses->whereBetween('entry_date', $dateFilter);
            $walletCredits->whereBetween('created_at', [$dateFilter[0], $dateFilter[1] . ' 23:59:59']);
            $walletDebits->whereBetween('created_at', [$dateFilter[0], $dateFilter[1] . ' 23:59:59']);
        }

        $revenue = [
            'cp_orders' => (clone $cpRevenue)->whereIn('status', ['completed', 'confirmed', 'delivered'])->sum($amtCol),
            'customer_orders' => (clone $custRevenue)->where('payment_status', 'paid')->sum('total_amount'),
        ];
        $revenue['total'] = $revenue['cp_orders'] + $revenue['customer_orders'];

        $expenses = [
            'material_cost' => $materialExpenses->sum('total_amount'),
            'wallet_transfers' => $walletCredits->sum('amount'),
        ];
        $expenses['total'] = $expenses['material_cost'] + $expenses['wallet_transfers'];

        $collections = [
            'payments_received' => $paymentsReceived->sum('amount'),
            'wallet_deductions' => $walletDebits->sum('amount'),
        ];
        $collections['total'] = $collections['payments_received'] + $collections['wallet_deductions'];

        $profitLoss = $revenue['total'] - $expenses['total'];

        $outstanding = [
            'cp_pending' => CpOrder::where('status', 'pending')->sum($amtCol),
            'customer_pending' => CustomerOrder::where('payment_status', 'pending')->sum('total_amount'),
        ];
        $outstanding['total'] = $outstanding['cp_pending'] + $outstanding['customer_pending'];

        return view('Admin.financial.profitLoss', compact(
            'revenue', 'expenses', 'collections', 'profitLoss', 'outstanding', 'period'
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
