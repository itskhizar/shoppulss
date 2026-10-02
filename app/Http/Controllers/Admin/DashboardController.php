<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the Admin Dashboard.
     */
    public function index(Request $request): View
    {
        $today = now()->startOfDay();
        $startOfWeek = now()->startOfWeek();
        $startOfMonth = now()->startOfMonth();

        // 1. Core KPIs
        $todaysOrders = Order::where('created_at', '>=', $today)->count();

        // Revenue definition: paid or non-cancelled fulfilling/completed orders
        $revenueCondition = function ($q) {
            $q->where('payment_status', 'paid')
                ->orWhereIn('status', ['paid', 'processing', 'packed', 'shipped', 'delivered']);
        };

        $todaysRevenue = (float) Order::where('created_at', '>=', $today)
            ->where($revenueCondition)
            ->sum('total_amount');

        $thisWeekRevenue = (float) Order::where('created_at', '>=', $startOfWeek)
            ->where($revenueCondition)
            ->sum('total_amount');

        $thisMonthRevenue = (float) Order::where('created_at', '>=', $startOfMonth)
            ->where($revenueCondition)
            ->sum('total_amount');

        $thisMonthOrders = Order::where('created_at', '>=', $startOfMonth)->count();

        $totalSales = (float) Order::where($revenueCondition)->sum('total_amount');
        $totalOrders = Order::count();

        // Average Order Value
        $qualifyingOrdersCount = Order::where($revenueCondition)->count();
        $averageOrderValue = $qualifyingOrdersCount > 0 ? (float) ($totalSales / $qualifyingOrdersCount) : 0.0;

        // Products sold count
        $productsSoldCount = (int) OrderItem::whereHas('order', function ($q) {
            $q->whereNotIn('status', ['cancelled']);
        })->sum('quantity');

        // Review & stock counts
        $pendingReviewCount = Review::where('status', 'pending')->count();
        $pendingReviewsCount = $pendingReviewCount; // alias for backward compat
        $lowStockCount = Product::whereColumn('stock_quantity', '<=', 'low_stock_threshold')
            ->where('stock_quantity', '>', 0)
            ->count();
        $outOfStockCount = Product::where('stock_quantity', '<=', 0)->count();

        // Catalog breakdown
        $totalProducts = Product::count();
        $publishedProducts = Product::where('status', 'published')->count();
        $draftProducts = Product::where('status', 'draft')->count();
        $archivedProducts = Product::where('status', 'archived')->count();
        $totalCategories = Category::count();

        // Customers
        $customerRoles = ['Super Admin', 'Store Admin', 'Catalog Manager', 'Order Manager', 'Support Agent'];
        $totalCustomers = User::whereDoesntHave('roles', function ($q) use ($customerRoles) {
            $q->whereIn('name', $customerRoles);
        })->count();

        $newCustomersThisMonth = User::whereDoesntHave('roles', function ($q) use ($customerRoles) {
            $q->whereIn('name', $customerRoles);
        })->where('created_at', '>=', $startOfMonth)->count();

        // Operational queues
        $pendingPaymentVerificationCount = Payment::where('status', 'pending_verification')->count();
        $ordersAwaitingShipment = Order::whereIn('status', ['paid', 'confirmed', 'processing', 'packed'])
            ->whereDoesntHave('shipments', function ($q) {
                $q->whereIn('shipment_status', ['picked_up', 'in_transit', 'out_for_delivery', 'delivered']);
            })
            ->count();

        // 2. Clickable Order Pipeline
        $pipelineCounts = [
            'pending' => Order::where('status', 'pending')->count(),
            'payment_pending' => Order::where('status', 'payment_pending')->count(),
            'paid' => Order::where('status', 'paid')->count(),
            'processing' => Order::whereIn('status', ['confirmed', 'processing'])->count(),
            'packed' => Order::where('status', 'packed')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        // Individual pipeline count aliases for the dashboard view
        $pendingCount = $pipelineCounts['pending'] + $pipelineCounts['payment_pending'];
        $processingCount = $pipelineCounts['paid'] + $pipelineCounts['processing'] + $pipelineCounts['packed'];
        $shippedCount = $pipelineCounts['shipped'];
        $deliveredCount = $pipelineCounts['delivered'];
        $cancelledCount = $pipelineCounts['cancelled'];

        // 3. Recent Orders with user, items, shipment, and payments
        $recentOrders = Order::with(['user', 'items.product', 'shipment.courier', 'payment'])
            ->orderByRaw("CASE WHEN status IN ('pending', 'payment_pending', 'confirmed') THEN 0 ELSE 1 END")
            ->latest('created_at')
            ->limit(10)
            ->get();

        // 4. Low Stock Alerts
        $lowStockProducts = Product::with(['category', 'images'])
            ->where(function ($q) {
                $q->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
                    ->orWhere('stock_quantity', '<=', 0);
            })
            ->orderBy('stock_quantity')
            ->limit(8)
            ->get();

        // 5. Recent Customers for dashboard sidebar
        $recentCustomers = User::whereDoesntHave('roles', function ($q) use ($customerRoles) {
            $q->whereIn('name', $customerRoles);
        })
            ->withCount('orders')
            ->latest()
            ->limit(6)
            ->get();

        // 6. Chart 1: Sales & Orders Trend (dynamic timeframes)
        $chartPeriod = $request->get('period', '30d');
        $chartData = $this->buildSalesChartData($chartPeriod, $revenueCondition);

        // 7. Chart 2: Order status distribution
        $orderDistribution = [
            'Pending' => $pipelineCounts['pending'] + $pipelineCounts['payment_pending'],
            'Processing' => $pipelineCounts['paid'] + $pipelineCounts['processing'] + $pipelineCounts['packed'],
            'Shipped' => $pipelineCounts['shipped'],
            'Delivered' => $pipelineCounts['delivered'],
            'Cancelled' => $pipelineCounts['cancelled'],
        ];

        // 8. Top Selling Products
        $topProducts = OrderItem::select('product_id', 'product_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(total_price) as total_revenue'))
            ->whereHas('order', function ($q) {
                $q->whereNotIn('status', ['cancelled']);
            })
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'todaysOrders',
            'todaysRevenue',
            'thisWeekRevenue',
            'thisMonthRevenue',
            'thisMonthOrders',
            'totalSales',
            'totalOrders',
            'averageOrderValue',
            'productsSoldCount',
            'pendingReviewCount',
            'pendingReviewsCount',
            'lowStockCount',
            'outOfStockCount',
            'totalProducts',
            'publishedProducts',
            'draftProducts',
            'archivedProducts',
            'totalCategories',
            'totalCustomers',
            'newCustomersThisMonth',
            'pendingPaymentVerificationCount',
            'ordersAwaitingShipment',
            'pipelineCounts',
            'pendingCount',
            'processingCount',
            'shippedCount',
            'deliveredCount',
            'cancelledCount',
            'recentOrders',
            'recentCustomers',
            'lowStockProducts',
            'chartPeriod',
            'chartData',
            'orderDistribution',
            'topProducts'
        ));
    }

    /**
     * Build aggregated sales & orders trend data for charts.
     */
    protected function buildSalesChartData(string $period, \Closure $revenueCondition): array
    {
        $labels = [];
        $revenueData = [];
        $ordersData = [];

        if ($period === 'today') {
            // Group by hour
            for ($h = 0; $h <= 23; $h++) {
                $hourStart = now()->startOfDay()->addHours($h);
                $hourEnd = (clone $hourStart)->addHour();

                $labels[] = $hourStart->format('g A');
                $revenueData[] = (float) Order::whereBetween('created_at', [$hourStart, $hourEnd])
                    ->where($revenueCondition)
                    ->sum('total_amount');
                $ordersData[] = Order::whereBetween('created_at', [$hourStart, $hourEnd])->count();
            }
        } elseif ($period === '7d') {
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i)->startOfDay();
                $dateEnd = (clone $date)->endOfDay();

                $labels[] = $date->format('D, M d');
                $revenueData[] = (float) Order::whereBetween('created_at', [$date, $dateEnd])
                    ->where($revenueCondition)
                    ->sum('total_amount');
                $ordersData[] = Order::whereBetween('created_at', [$date, $dateEnd])->count();
            }
        } elseif ($period === '3m') {
            // 12 weeks
            for ($i = 11; $i >= 0; $i--) {
                $weekStart = now()->subWeeks($i)->startOfWeek();
                $weekEnd = (clone $weekStart)->endOfWeek();

                $labels[] = $weekStart->format('M d');
                $revenueData[] = (float) Order::whereBetween('created_at', [$weekStart, $weekEnd])
                    ->where($revenueCondition)
                    ->sum('total_amount');
                $ordersData[] = Order::whereBetween('created_at', [$weekStart, $weekEnd])->count();
            }
        } elseif ($period === '12m') {
            for ($i = 11; $i >= 0; $i--) {
                $monthStart = now()->subMonths($i)->startOfMonth();
                $monthEnd = (clone $monthStart)->endOfMonth();

                $labels[] = $monthStart->format('M Y');
                $revenueData[] = (float) Order::whereBetween('created_at', [$monthStart, $monthEnd])
                    ->where($revenueCondition)
                    ->sum('total_amount');
                $ordersData[] = Order::whereBetween('created_at', [$monthStart, $monthEnd])->count();
            }
        } else {
            // Default: 30 days
            for ($i = 29; $i >= 0; $i--) {
                $date = now()->subDays($i)->startOfDay();
                $dateEnd = (clone $date)->endOfDay();

                $labels[] = $date->format('M d');
                $revenueData[] = (float) Order::whereBetween('created_at', [$date, $dateEnd])
                    ->where($revenueCondition)
                    ->sum('total_amount');
                $ordersData[] = Order::whereBetween('created_at', [$date, $dateEnd])->count();
            }
        }

        return [
            'labels' => $labels,
            'revenue' => $revenueData,
            'orders' => $ordersData,
        ];
    }
}
