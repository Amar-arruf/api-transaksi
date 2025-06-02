<?php

namespace App\Repository;

use App\Models\SalesOrder;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;


class SalesRepository
{
    public function getLastThreeYears(Carbon $fromDate, ?string $customerName = null, ?string $salesName = null): Collection
    {
        $threeYearsAgo = $fromDate->subYears(3);

        return SalesOrder::query()
            ->join('sales_order_items', 'sales_orders.id', '=', 'sales_order_items.order_id')
            ->when($customerName, function ($query) use ($customerName) {
                return $query
                    ->join('customers', 'sales_orders.customer_id', '=', 'customers.id')
                    ->where('customers.name', $customerName);
            })
            ->when($salesName, function ($query) use ($salesName) {
                return $query
                    ->join('sales', 'sales_orders.sales_id', '=', 'sales.id')
                    ->join('users', 'sales.user_id', '=', 'users.id')
                    ->where('users.name', $salesName);
            })
            ->where('sales_orders.created_at', '>=', $threeYearsAgo)
            ->select(
                DB::raw('YEAR(sales_orders.created_at) as year'),
                DB::raw('MONTH(sales_orders.created_at) as month'),
                DB::raw('SUM(sales_order_items.quantity * sales_order_items.selling_price) as total_amount')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'asc')
            ->get();

    }

    public function getMonthlyTargetAndTransactions(?string $salesName = null): Collection
    {
        $currentYear = Carbon::now()->year;

        return SalesOrder::query()
            ->join('sales_order_items', 'sales_orders.id', '=', 'sales_order_items.order_id')
            ->join('sales', 'sales_orders.sales_id', '=', 'sales.id')
            ->join('sales_targets', 'sales.id', '=', 'sales_targets.sales_id')
            ->when($salesName, function ($query) use ($salesName) {
                return $query
                    ->join('users', 'sales.user_id', '=', 'users.id')
                    ->where('users.name', $salesName);
            })
            ->whereYear('sales_orders.created_at', $currentYear)
            ->select(
                DB::raw('MONTH(sales_orders.created_at) as month'),
                DB::raw('SUM(sales_targets.amount) as target'),
                DB::raw('SUM(sales_order_items.quantity * sales_order_items.selling_price) as revenue'),
                DB::raw('SUM(sales_order_items.quantity * (sales_order_items.selling_price - sales_order_items.production_price)) as income'),
                DB::raw('COUNT(DISTINCT sales_orders.id) as total_transactions')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

}