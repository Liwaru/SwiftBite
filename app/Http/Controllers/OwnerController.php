<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\IngredientPurchase;
use App\Models\IngredientUsage;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OwnerController extends Controller
{
    public function dashboard(): View
    {
        [
            'stats' => $stats,
            'topMenusToday' => $topMenusToday,
            'paymentSummary' => $paymentSummary,
            'weeklyRevenueDays' => $weeklyRevenueDays,
        ] = $this->businessReportData();

        return view('owner.dashboard', compact('stats', 'topMenusToday', 'paymentSummary', 'weeklyRevenueDays'));
    }

    public function sales(Request $request): View|Response
    {
        $report = $this->salesReportData($request);

        if ($request->query('export') === 'excel') {
            return $this->exportSalesExcel($report);
        }

        if ($request->query('export') === 'pdf') {
            return Pdf::loadView('owner.exports.sales_pdf', $report)->download('laporan-penjualan-swiftbite.pdf');
        }

        return view('owner.report_sales', $report);
    }

    public function finance(Request $request): View|Response
    {
        $report = $this->financeReportData($request);

        if ($request->query('export') === 'excel') {
            return $this->exportFinanceExcel($report);
        }

        if ($request->query('export') === 'pdf') {
            return Pdf::loadView('owner.exports.finance_pdf', $report)->download('laporan-keuangan-swiftbite.pdf');
        }

        return view('owner.report_finance', $report);
    }

    public function products(Request $request): View|Response
    {
        $report = $this->productReportData($request);

        if ($request->query('export') === 'excel') {
            return $this->exportProductsExcel($report);
        }

        if ($request->query('export') === 'pdf') {
            return Pdf::loadView('owner.exports.products_pdf', $report)->download('laporan-produk-swiftbite.pdf');
        }

        return view('owner.report_products', $report);
    }

    public function ingredients(Request $request): View|Response
    {
        $report = $this->ingredientReportData($request);

        if ($request->query('export') === 'excel') {
            return $this->exportIngredientsExcel($report);
        }

        if ($request->query('export') === 'pdf') {
            return Pdf::loadView('owner.exports.ingredients_pdf', $report)->download('laporan-bahan-swiftbite.pdf');
        }

        return view('owner.report_ingredients', $report);
    }

    private function businessReportData(): array
    {
        $todayOrders = Order::whereDate('created_at', today());
        $todayCompletedOrders = (clone $todayOrders)->where('status', 'selesai');
        $todayRevenue = (clone $todayCompletedOrders)->sum('total_harga');
        $completedTodayCount = (clone $todayCompletedOrders)->count();

        $topMenusToday = DB::table('order_details')
            ->join('orders', 'order_details.id_order', '=', 'orders.id_order')
            ->join('menus', 'order_details.id_menu', '=', 'menus.id_menu')
            ->whereDate('orders.created_at', today())
            ->where('orders.status', 'selesai')
            ->select('menus.nama_menu', DB::raw('SUM(order_details.qty) as total_qty'))
            ->groupBy('menus.id_menu', 'menus.nama_menu')
            ->orderByDesc('total_qty')
            ->limit(3)
            ->get();

        $paymentMethods = (clone $todayOrders)
            ->select('metode_pembayaran', DB::raw('COUNT(*) as total'))
            ->groupBy('metode_pembayaran')
            ->pluck('total', 'metode_pembayaran');

        $weekStart = today()->subDays(today()->isoWeekday() - 1);
        $weekEnd = $weekStart->copy()->addDays(6);

        $weeklyRevenueDays = collect(range(0, 6))
            ->map(function (int $dayOffset) use ($weekStart) {
                $date = $weekStart->copy()->addDays($dayOffset);
                $dayNames = [
                    1 => 'Senin',
                    2 => 'Selasa',
                    3 => 'Rabu',
                    4 => 'Kamis',
                    5 => 'Jumat',
                    6 => 'Sabtu',
                    7 => 'Minggu',
                ];

                return [
                    'date' => $date,
                    'label' => $dayNames[$date->isoWeekday()],
                    'revenue' => 0,
                ];
            });

        $weeklyRevenue = Order::query()
            ->where('status', 'selesai')
            ->whereDate('created_at', '>=', $weekStart)
            ->whereDate('created_at', '<=', $weekEnd)
            ->selectRaw('DATE(created_at) as order_date, SUM(total_harga) as revenue')
            ->groupBy('order_date')
            ->pluck('revenue', 'order_date');

        $weeklyRevenueDays = $weeklyRevenueDays
            ->map(function (array $day) use ($weeklyRevenue) {
                $day['revenue'] = (float) ($weeklyRevenue[$day['date']->toDateString()] ?? 0);

                return $day;
            });

        $stats = [
            'today_revenue' => $todayRevenue,
            'today_orders' => (clone $todayOrders)->count(),
            'average_transaction' => $completedTodayCount > 0 ? $todayRevenue / $completedTodayCount : 0,
            'best_seller' => $topMenusToday->first()?->nama_menu ?? '-',
            'orders_today' => [
                'menunggu' => (clone $todayOrders)->where('status', 'menunggu')->count(),
                'diproses' => (clone $todayOrders)->where('status', 'diproses')->count(),
                'selesai' => (clone $todayOrders)->where('status', 'selesai')->count(),
                'dibatalkan' => (clone $todayOrders)->where('status', 'dibatalkan')->count(),
            ],
        ];

        $paymentSummary = [
            'Tunai' => (int) ($paymentMethods['cash'] ?? 0),
            'QRIS' => (int) ($paymentMethods['qris'] ?? 0),
            'E-Wallet' => (int) ($paymentMethods['ewallet'] ?? 0),
        ];

        return compact('stats', 'topMenusToday', 'paymentSummary', 'weeklyRevenueDays');
    }

    private function salesReportData(Request $request): array
    {
        $chartPeriod = $this->validChartPeriod($request->query('chart_period', 'weekly'));
        [$startDate, $endDate, $period] = $this->periodRangeFromChartPeriod($chartPeriod);

        $ordersQuery = Order::query()
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        $completedOrdersQuery = (clone $ordersQuery)->where('status', 'selesai');

        $totalOrders = (clone $ordersQuery)->count();
        $completedOrders = (clone $completedOrdersQuery)->count();
        $totalRevenue = (clone $completedOrdersQuery)->sum('total_harga');
        $totalProductsSold = DB::table('order_details')
            ->join('orders', 'order_details.id_order', '=', 'orders.id_order')
            ->whereDate('orders.created_at', '>=', $startDate)
            ->whereDate('orders.created_at', '<=', $endDate)
            ->where('orders.status', 'selesai')
            ->sum('order_details.qty');

        $statusSummary = [
            'Menunggu' => (clone $ordersQuery)->where('status', 'menunggu')->count(),
            'Diproses' => (clone $ordersQuery)->where('status', 'diproses')->count(),
            'Selesai' => $completedOrders,
            'Dibatalkan' => (clone $ordersQuery)->where('status', 'dibatalkan')->count(),
        ];

        $topMenus = DB::table('order_details')
            ->join('orders', 'order_details.id_order', '=', 'orders.id_order')
            ->join('menus', 'order_details.id_menu', '=', 'menus.id_menu')
            ->whereDate('orders.created_at', '>=', $startDate)
            ->whereDate('orders.created_at', '<=', $endDate)
            ->where('orders.status', 'selesai')
            ->select('menus.nama_menu', DB::raw('SUM(order_details.qty) as total_qty'))
            ->groupBy('menus.id_menu', 'menus.nama_menu')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        $paymentRows = (clone $ordersQuery)
            ->select('metode_pembayaran', DB::raw('COUNT(*) as total'))
            ->groupBy('metode_pembayaran')
            ->pluck('total', 'metode_pembayaran');

        $paymentSummary = [
            'Tunai' => (int) ($paymentRows['cash'] ?? 0),
            'QRIS' => (int) ($paymentRows['qris'] ?? 0),
            'Dana' => (int) ($paymentRows['dana'] ?? 0),
            'OVO' => (int) ($paymentRows['ovo'] ?? 0),
            'GoPay' => (int) ($paymentRows['gopay'] ?? 0),
        ];

        if (($paymentRows['ewallet'] ?? 0) > 0) {
            $paymentSummary['E-Wallet'] = (int) $paymentRows['ewallet'];
        }

        $transactions = Order::with('diningTable')
            ->withSum('items as total_items', 'qty')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $transactionRows = Order::with('diningTable')
            ->withSum('items as total_items', 'qty')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->latest()
            ->get();

        $summary = [
            'total_orders' => $totalOrders,
            'total_products_sold' => $totalProductsSold,
            'total_revenue' => $totalRevenue,
            'average_transaction' => $completedOrders > 0 ? $totalRevenue / $completedOrders : 0,
        ];

        $chartPeriodOptions = $this->chartPeriodOptions();
        $salesChart = $this->salesTrendData($chartPeriod);

        $periodOptions = [
            'daily' => 'Harian',
            'weekly' => 'Mingguan',
            'monthly' => 'Bulanan',
            'yearly' => 'Tahunan',
            'custom' => 'Custom Tanggal',
        ];

        return compact(
            'period',
            'periodOptions',
            'startDate',
            'endDate',
            'summary',
            'statusSummary',
            'topMenus',
            'paymentSummary',
            'transactions',
            'transactionRows',
            'chartPeriod',
            'chartPeriodOptions',
            'salesChart',
        );
    }

    private function salesPeriodRange(Request $request): array
    {
        $period = (string) $request->query('period', 'daily');

        if (! in_array($period, ['daily', 'weekly', 'monthly', 'yearly', 'custom'], true)) {
            $period = 'daily';
        }

        $today = today();

        if ($period === 'weekly') {
            return [$today->copy()->startOfWeek(), $today->copy()->endOfWeek(), $period];
        }

        if ($period === 'monthly') {
            return [$today->copy()->startOfMonth(), $today->copy()->endOfMonth(), $period];
        }

        if ($period === 'yearly') {
            return [$today->copy()->startOfYear(), $today->copy()->endOfYear(), $period];
        }

        if ($period === 'custom') {
            $startDate = rescue(fn () => \Illuminate\Support\Carbon::parse($request->query('start_date'))->startOfDay(), $today->copy());
            $endDate = rescue(fn () => \Illuminate\Support\Carbon::parse($request->query('end_date'))->endOfDay(), $today->copy());

            if ($endDate->lt($startDate)) {
                [$startDate, $endDate] = [$endDate, $startDate];
            }

            return [$startDate, $endDate, $period];
        }

        return [$today->copy()->startOfDay(), $today->copy()->endOfDay(), $period];
    }

    private function periodRangeFromChartPeriod(string $period): array
    {
        $today = today();

        if ($period === 'daily') {
            return [$today->copy()->startOfDay(), $today->copy()->endOfDay(), $period];
        }

        if ($period === 'monthly') {
            return [$today->copy()->startOfMonth(), $today->copy()->endOfMonth(), $period];
        }

        if ($period === 'yearly') {
            return [$today->copy()->startOfYear(), $today->copy()->endOfYear(), $period];
        }

        return [$today->copy()->startOfWeek(), $today->copy()->endOfWeek(), 'weekly'];
    }

    private function financeReportData(Request $request): array
    {
        $chartPeriod = $this->validChartPeriod($request->query('chart_period', 'yearly'));
        [$startDate, $endDate, $period] = $this->periodRangeFromChartPeriod($chartPeriod);

        $incomeQuery = Order::query()
            ->where('status', 'selesai')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        $expenseQuery = IngredientPurchase::with('ingredient')
            ->whereDate('purchased_at', '>=', $startDate)
            ->whereDate('purchased_at', '<=', $endDate);

        $totalIncome = (clone $incomeQuery)->sum('total_harga');
        $totalExpense = (clone $expenseQuery)->sum('harga_total');
        $transactionCount = (clone $incomeQuery)->count() + (clone $expenseQuery)->count();

        $summary = [
            'total_income' => $totalIncome,
            'total_expense' => $totalExpense,
            'net_profit' => $totalIncome - $totalExpense,
            'transactions' => $transactionCount,
        ];

        $comparison = [
            'Pemasukan' => $totalIncome,
            'Pengeluaran' => $totalExpense,
            'Laba Bersih' => $totalIncome - $totalExpense,
        ];

        $topExpenses = IngredientPurchase::query()
            ->join('ingredients', 'ingredient_purchases.id_bahan', '=', 'ingredients.id_bahan')
            ->whereDate('ingredient_purchases.purchased_at', '>=', $startDate)
            ->whereDate('ingredient_purchases.purchased_at', '<=', $endDate)
            ->select('ingredients.nama_bahan', DB::raw('SUM(ingredient_purchases.harga_total) as total_expense'))
            ->groupBy('ingredients.id_bahan', 'ingredients.nama_bahan')
            ->orderByDesc('total_expense')
            ->limit(5)
            ->get();

        $paymentRows = Order::query()
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->select('metode_pembayaran', DB::raw('COUNT(*) as total'))
            ->groupBy('metode_pembayaran')
            ->pluck('total', 'metode_pembayaran');

        $paymentSummary = [
            'Tunai' => (int) ($paymentRows['cash'] ?? 0),
            'QRIS' => (int) ($paymentRows['qris'] ?? 0),
            'Dana' => (int) ($paymentRows['dana'] ?? 0),
            'OVO' => (int) ($paymentRows['ovo'] ?? 0),
            'GoPay' => (int) ($paymentRows['gopay'] ?? 0),
        ];

        if (($paymentRows['ewallet'] ?? 0) > 0) {
            $paymentSummary['E-Wallet'] = (int) $paymentRows['ewallet'];
        }

        $incomeRows = (clone $incomeQuery)
            ->latest()
            ->get()
            ->map(fn (Order $order) => [
                'date' => $order->created_at,
                'type' => 'Pemasukan',
                'description' => 'Pesanan ' . $order->kode_pesanan,
                'amount' => (float) $order->total_harga,
            ]);

        $expenseRows = (clone $expenseQuery)
            ->orderByDesc('purchased_at')
            ->get()
            ->map(fn (IngredientPurchase $purchase) => [
                'date' => $purchase->purchased_at ?? $purchase->created_at,
                'type' => 'Pengeluaran',
                'description' => $purchase->note ?: 'Pembelian ' . ($purchase->ingredient?->nama_bahan ?? 'Bahan'),
                'amount' => (float) $purchase->harga_total,
            ]);

        $financialRows = $incomeRows
            ->concat($expenseRows)
            ->sortByDesc(fn (array $row) => $row['date']?->timestamp ?? 0)
            ->values();

        $chartPeriodOptions = $this->chartPeriodOptions();
        $financeChart = $this->financeTrendData($chartPeriod);

        $periodOptions = [
            'daily' => 'Harian',
            'weekly' => 'Mingguan',
            'monthly' => 'Bulanan',
            'yearly' => 'Tahunan',
            'custom' => 'Custom Tanggal',
        ];

        return compact(
            'period',
            'periodOptions',
            'startDate',
            'endDate',
            'summary',
            'comparison',
            'topExpenses',
            'paymentSummary',
            'financialRows',
            'chartPeriod',
            'chartPeriodOptions',
            'financeChart',
        );
    }

    private function chartPeriodOptions(): array
    {
        return [
            'daily' => 'Harian',
            'weekly' => 'Mingguan',
            'monthly' => 'Bulanan',
            'yearly' => 'Tahunan',
        ];
    }

    private function validChartPeriod(mixed $period): string
    {
        $period = (string) $period;

        return in_array($period, ['daily', 'weekly', 'monthly', 'yearly'], true) ? $period : 'weekly';
    }

    private function salesTrendData(string $period): array
    {
        if ($period === 'daily') {
            $hours = [8, 10, 12, 14, 16, 18, 20];
            $rows = Order::query()
                ->whereDate('created_at', today())
                ->selectRaw('HOUR(created_at) as bucket, COUNT(*) as total')
                ->groupBy('bucket')
                ->pluck('total', 'bucket');

            return [
                'title' => 'Grafik Penjualan Harian',
                'subtitle' => 'Tren jumlah pesanan dalam satu hari.',
                'labels' => collect($hours)->map(fn (int $hour) => sprintf('%02d:00', $hour))->all(),
                'values' => collect($hours)->map(fn (int $hour) => (int) ($rows[$hour] ?? 0))->all(),
            ];
        }

        if ($period === 'monthly') {
            $start = today()->copy()->startOfMonth();
            $days = range(1, $start->daysInMonth);
            $rows = Order::query()
                ->whereDate('created_at', '>=', $start)
                ->whereDate('created_at', '<=', $start->copy()->endOfMonth())
                ->selectRaw('DAY(created_at) as bucket, COUNT(*) as total')
                ->groupBy('bucket')
                ->pluck('total', 'bucket');

            return [
                'title' => 'Grafik Penjualan Bulanan',
                'subtitle' => 'Tren jumlah pesanan per tanggal bulan ini.',
                'labels' => collect($days)->map(fn (int $day) => (string) $day)->all(),
                'values' => collect($days)->map(fn (int $day) => (int) ($rows[$day] ?? 0))->all(),
            ];
        }

        if ($period === 'yearly') {
            $months = [
                1 => 'Jan',
                2 => 'Feb',
                3 => 'Mar',
                4 => 'Apr',
                5 => 'Mei',
                6 => 'Jun',
                7 => 'Jul',
                8 => 'Agu',
                9 => 'Sep',
                10 => 'Okt',
                11 => 'Nov',
                12 => 'Des',
            ];
            $rows = Order::query()
                ->whereYear('created_at', today()->year)
                ->selectRaw('MONTH(created_at) as bucket, COUNT(*) as total')
                ->groupBy('bucket')
                ->pluck('total', 'bucket');

            return [
                'title' => 'Grafik Penjualan Tahunan',
                'subtitle' => 'Tren jumlah pesanan per bulan tahun ini.',
                'labels' => array_values($months),
                'values' => collect(array_keys($months))->map(fn (int $month) => (int) ($rows[$month] ?? 0))->all(),
            ];
        }

        $weekStart = today()->copy()->startOfWeek();
        $dayNames = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
        $dates = collect(range(0, 6))->map(fn (int $offset) => $weekStart->copy()->addDays($offset));
        $rows = Order::query()
            ->whereDate('created_at', '>=', $weekStart)
            ->whereDate('created_at', '<=', $weekStart->copy()->endOfWeek())
            ->selectRaw('DATE(created_at) as bucket, COUNT(*) as total')
            ->groupBy('bucket')
            ->pluck('total', 'bucket');

        return [
            'title' => 'Grafik Penjualan Mingguan',
            'subtitle' => 'Tren jumlah pesanan per hari minggu ini.',
            'labels' => $dayNames,
            'values' => $dates->map(fn ($date) => (int) ($rows[$date->toDateString()] ?? 0))->all(),
        ];
    }

    private function financeTrendData(string $period): array
    {
        if ($period === 'daily') {
            $hours = [8, 10, 12, 14, 16, 18, 20];
            $incomeRows = Order::query()
                ->where('status', 'selesai')
                ->whereDate('created_at', today())
                ->selectRaw('HOUR(created_at) as bucket, SUM(total_harga) as total')
                ->groupBy('bucket')
                ->pluck('total', 'bucket');
            $expenseRows = IngredientPurchase::query()
                ->whereDate('purchased_at', today())
                ->selectRaw('HOUR(purchased_at) as bucket, SUM(harga_total) as total')
                ->groupBy('bucket')
                ->pluck('total', 'bucket');

            return [
                'title' => 'Tren Keuangan Harian',
                'subtitle' => 'Pemasukan dan pengeluaran berdasarkan jam.',
                'labels' => collect($hours)->map(fn (int $hour) => sprintf('%02d:00', $hour))->all(),
                'income' => collect($hours)->map(fn (int $hour) => (float) ($incomeRows[$hour] ?? 0))->all(),
                'expense' => collect($hours)->map(fn (int $hour) => (float) ($expenseRows[$hour] ?? 0))->all(),
            ];
        }

        if ($period === 'monthly') {
            $start = today()->copy()->startOfMonth();
            $days = range(1, $start->daysInMonth);
            $incomeRows = Order::query()
                ->where('status', 'selesai')
                ->whereDate('created_at', '>=', $start)
                ->whereDate('created_at', '<=', $start->copy()->endOfMonth())
                ->selectRaw('DAY(created_at) as bucket, SUM(total_harga) as total')
                ->groupBy('bucket')
                ->pluck('total', 'bucket');
            $expenseRows = IngredientPurchase::query()
                ->whereDate('purchased_at', '>=', $start)
                ->whereDate('purchased_at', '<=', $start->copy()->endOfMonth())
                ->selectRaw('DAY(purchased_at) as bucket, SUM(harga_total) as total')
                ->groupBy('bucket')
                ->pluck('total', 'bucket');

            return [
                'title' => 'Tren Keuangan Bulanan',
                'subtitle' => 'Pemasukan dan pengeluaran per tanggal bulan ini.',
                'labels' => collect($days)->map(fn (int $day) => (string) $day)->all(),
                'income' => collect($days)->map(fn (int $day) => (float) ($incomeRows[$day] ?? 0))->all(),
                'expense' => collect($days)->map(fn (int $day) => (float) ($expenseRows[$day] ?? 0))->all(),
            ];
        }

        if ($period === 'yearly') {
            $months = [
                1 => 'Jan',
                2 => 'Feb',
                3 => 'Mar',
                4 => 'Apr',
                5 => 'Mei',
                6 => 'Jun',
                7 => 'Jul',
                8 => 'Agu',
                9 => 'Sep',
                10 => 'Okt',
                11 => 'Nov',
                12 => 'Des',
            ];
            $incomeRows = Order::query()
                ->where('status', 'selesai')
                ->whereYear('created_at', today()->year)
                ->selectRaw('MONTH(created_at) as bucket, SUM(total_harga) as total')
                ->groupBy('bucket')
                ->pluck('total', 'bucket');
            $expenseRows = IngredientPurchase::query()
                ->whereYear('purchased_at', today()->year)
                ->selectRaw('MONTH(purchased_at) as bucket, SUM(harga_total) as total')
                ->groupBy('bucket')
                ->pluck('total', 'bucket');

            return [
                'title' => 'Tren Keuangan Tahunan',
                'subtitle' => 'Pemasukan dan pengeluaran per bulan tahun ini.',
                'labels' => array_values($months),
                'income' => collect(array_keys($months))->map(fn (int $month) => (float) ($incomeRows[$month] ?? 0))->all(),
                'expense' => collect(array_keys($months))->map(fn (int $month) => (float) ($expenseRows[$month] ?? 0))->all(),
            ];
        }

        $weekStart = today()->copy()->startOfWeek();
        $dayNames = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
        $dates = collect(range(0, 6))->map(fn (int $offset) => $weekStart->copy()->addDays($offset));
        $incomeRows = Order::query()
            ->where('status', 'selesai')
            ->whereDate('created_at', '>=', $weekStart)
            ->whereDate('created_at', '<=', $weekStart->copy()->endOfWeek())
            ->selectRaw('DATE(created_at) as bucket, SUM(total_harga) as total')
            ->groupBy('bucket')
            ->pluck('total', 'bucket');
        $expenseRows = IngredientPurchase::query()
            ->whereDate('purchased_at', '>=', $weekStart)
            ->whereDate('purchased_at', '<=', $weekStart->copy()->endOfWeek())
            ->selectRaw('DATE(purchased_at) as bucket, SUM(harga_total) as total')
            ->groupBy('bucket')
            ->pluck('total', 'bucket');

        return [
            'title' => 'Tren Keuangan Mingguan',
            'subtitle' => 'Pemasukan dan pengeluaran per hari minggu ini.',
            'labels' => $dayNames,
            'income' => $dates->map(fn ($date) => (float) ($incomeRows[$date->toDateString()] ?? 0))->all(),
            'expense' => $dates->map(fn ($date) => (float) ($expenseRows[$date->toDateString()] ?? 0))->all(),
        ];
    }

    private function productReportData(Request $request): array
    {
        [$startDate, $endDate, $period] = $this->salesPeriodRange($request);

        $salesSubquery = DB::table('order_details')
            ->join('orders', 'order_details.id_order', '=', 'orders.id_order')
            ->whereDate('orders.created_at', '>=', $startDate)
            ->whereDate('orders.created_at', '<=', $endDate)
            ->where('orders.status', 'selesai')
            ->select(
                'order_details.id_menu',
                DB::raw('SUM(order_details.qty) as total_sold'),
                DB::raw('SUM(order_details.subtotal) as total_revenue'),
            )
            ->groupBy('order_details.id_menu');

        $productRows = DB::table('menus')
            ->leftJoin('categories', 'menus.id_kategori', '=', 'categories.id_kategori')
            ->leftJoinSub($salesSubquery, 'sales', fn ($join) => $join->on('menus.id_menu', '=', 'sales.id_menu'))
            ->select(
                'menus.id_menu',
                'menus.nama_menu',
                'menus.harga',
                'menus.status',
                DB::raw("COALESCE(categories.nama_kategori, 'Tanpa kategori') as nama_kategori"),
                DB::raw('COALESCE(sales.total_sold, 0) as total_sold'),
                DB::raw('COALESCE(sales.total_revenue, 0) as total_revenue'),
            )
            ->orderBy('menus.nama_menu')
            ->get();

        $topProducts = $productRows
            ->filter(fn ($product) => (int) $product->total_sold > 0)
            ->sortByDesc(fn ($product) => (int) $product->total_sold)
            ->take(5)
            ->values();

        $lowProducts = $productRows
            ->sortBy(fn ($product) => (int) $product->total_sold)
            ->take(5)
            ->values();

        $revenueByProduct = $productRows
            ->filter(fn ($product) => (float) $product->total_revenue > 0)
            ->sortByDesc(fn ($product) => (float) $product->total_revenue)
            ->take(5)
            ->values();

        $totalProductsSold = (int) $productRows->sum('total_sold');
        $bestProduct = $topProducts->first()?->nama_menu ?? '-';
        $lowestProduct = $lowProducts->first()?->nama_menu ?? '-';

        $categoryDistribution = $productRows
            ->groupBy('nama_kategori')
            ->map(fn ($items, $category) => [
                'category' => $category,
                'total_sold' => (int) $items->sum('total_sold'),
                'percentage' => $totalProductsSold > 0 ? ($items->sum('total_sold') / $totalProductsSold) * 100 : 0,
            ])
            ->values();

        $summary = [
            'total_products_sold' => $totalProductsSold,
            'total_active_menu' => DB::table('menus')->where('status', 'tersedia')->count(),
            'best_product' => $bestProduct,
            'lowest_product' => $lowestProduct,
        ];

        $periodOptions = [
            'daily' => 'Harian',
            'weekly' => 'Mingguan',
            'monthly' => 'Bulanan',
            'yearly' => 'Tahunan',
            'custom' => 'Custom Tanggal',
        ];

        return compact(
            'period',
            'periodOptions',
            'startDate',
            'endDate',
            'summary',
            'topProducts',
            'lowProducts',
            'revenueByProduct',
            'categoryDistribution',
            'productRows',
        );
    }

    private function ingredientReportData(Request $request): array
    {
        [$startDate, $endDate, $period] = $this->salesPeriodRange($request);

        $ingredients = Ingredient::orderBy('nama_bahan')->get();

        $usedByIngredient = IngredientUsage::query()
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->select('id_bahan', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('id_bahan')
            ->pluck('total_qty', 'id_bahan');

        $incomingByIngredient = IngredientPurchase::query()
            ->whereDate('purchased_at', '>=', $startDate)
            ->whereDate('purchased_at', '<=', $endDate)
            ->select('id_bahan', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('id_bahan')
            ->pluck('total_qty', 'id_bahan');

        $usageSummary = $ingredients
            ->map(function (Ingredient $ingredient) use ($usedByIngredient, $incomingByIngredient) {
                $used = (float) ($usedByIngredient[$ingredient->id_bahan] ?? 0);
                $incoming = (float) ($incomingByIngredient[$ingredient->id_bahan] ?? 0);
                $remaining = (float) $ingredient->stok;
                $initial = $remaining - $incoming + $used;

                return [
                    'id_bahan' => $ingredient->id_bahan,
                    'nama_bahan' => $ingredient->nama_bahan,
                    'satuan' => $ingredient->satuan,
                    'stok_awal' => max(0, $initial),
                    'masuk' => $incoming,
                    'digunakan' => $used,
                    'sisa' => $remaining,
                    'status_label' => $ingredient->status_label,
                    'status_type' => $ingredient->status_type,
                ];
            });

        $topUsedIngredients = $usageSummary
            ->filter(fn (array $row) => $row['digunakan'] > 0)
            ->sortByDesc('digunakan')
            ->take(5)
            ->values();

        $lowIngredients = $ingredients
            ->filter(fn (Ingredient $ingredient) => in_array($ingredient->status_label, ['Menipis', 'Habis'], true))
            ->sortBy('stok')
            ->values();

        $incomingRows = IngredientPurchase::with('ingredient')
            ->whereDate('purchased_at', '>=', $startDate)
            ->whereDate('purchased_at', '<=', $endDate)
            ->get()
            ->map(fn (IngredientPurchase $purchase) => [
                'date' => $purchase->purchased_at ?? $purchase->created_at,
                'ingredient' => $purchase->ingredient?->nama_bahan ?? 'Bahan',
                'type' => 'Masuk',
                'qty' => (float) $purchase->qty,
                'unit' => $purchase->satuan,
                'note' => $purchase->note ?: 'Restock bahan',
            ]);

        $outgoingRows = IngredientUsage::with('ingredient')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->get()
            ->map(fn (IngredientUsage $usage) => [
                'date' => $usage->created_at,
                'ingredient' => $usage->ingredient?->nama_bahan ?? 'Bahan',
                'type' => 'Keluar',
                'qty' => (float) $usage->qty,
                'unit' => $usage->ingredient?->satuan ?? '',
                'note' => $usage->note ?: 'Produksi',
            ]);

        $movementRows = $incomingRows
            ->concat($outgoingRows)
            ->sortByDesc(fn (array $row) => $row['date']?->timestamp ?? 0)
            ->values();

        $summary = [
            'total' => $ingredients->count(),
            'aman' => $ingredients->filter(fn (Ingredient $ingredient) => $ingredient->status_label === 'Aman')->count(),
            'menipis' => $ingredients->filter(fn (Ingredient $ingredient) => $ingredient->status_label === 'Menipis')->count(),
            'habis' => $ingredients->filter(fn (Ingredient $ingredient) => $ingredient->status_label === 'Habis')->count(),
        ];

        $periodOptions = [
            'daily' => 'Harian',
            'weekly' => 'Mingguan',
            'monthly' => 'Bulanan',
            'yearly' => 'Tahunan',
            'custom' => 'Custom Tanggal',
        ];

        return compact(
            'period',
            'periodOptions',
            'startDate',
            'endDate',
            'summary',
            'topUsedIngredients',
            'lowIngredients',
            'usageSummary',
            'movementRows',
        );
    }

    private function exportSalesExcel(array $report): Response
    {
        $lines = [
            ['Tanggal', 'Invoice', 'Meja', 'Jumlah Item', 'Metode Bayar', 'Total', 'Status'],
        ];

        foreach ($report['transactionRows'] as $transaction) {
            $lines[] = [
                $transaction->created_at?->format('d/m/Y') ?? '-',
                $transaction->kode_pesanan,
                $transaction->diningTable?->nama_meja ?? '-',
                (int) ($transaction->total_items ?? 0),
                $this->paymentMethodLabel($transaction->metode_pembayaran),
                (int) $transaction->total_harga,
                ucfirst($transaction->status),
            ];
        }

        $csv = collect($lines)
            ->map(fn (array $line) => collect($line)->map(fn ($value) => '"' . str_replace('"', '""', (string) $value) . '"')->implode(','))
            ->implode("\n");

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="laporan-penjualan-swiftbite.csv"',
        ]);
    }

    private function exportProductsExcel(array $report): Response
    {
        $lines = [
            ['Nama Produk', 'Kategori', 'Harga', 'Jumlah Terjual', 'Pendapatan', 'Status'],
        ];

        foreach ($report['productRows'] as $product) {
            $lines[] = [
                $product->nama_menu,
                $product->nama_kategori,
                (int) $product->harga,
                (int) $product->total_sold,
                (int) $product->total_revenue,
                $product->status === 'tersedia' ? 'Aktif' : 'Habis',
            ];
        }

        $csv = collect($lines)
            ->map(fn (array $line) => collect($line)->map(fn ($value) => '"' . str_replace('"', '""', (string) $value) . '"')->implode(','))
            ->implode("\n");

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="laporan-produk-swiftbite.csv"',
        ]);
    }

    private function exportFinanceExcel(array $report): Response
    {
        $lines = [
            ['Tanggal', 'Jenis', 'Keterangan', 'Nominal'],
        ];

        foreach ($report['financialRows'] as $row) {
            $lines[] = [
                $row['date']?->format('d/m/Y') ?? '-',
                $row['type'],
                $row['description'],
                (int) $row['amount'],
            ];
        }

        $csv = collect($lines)
            ->map(fn (array $line) => collect($line)->map(fn ($value) => '"' . str_replace('"', '""', (string) $value) . '"')->implode(','))
            ->implode("\n");

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="laporan-keuangan-swiftbite.csv"',
        ]);
    }

    private function exportIngredientsExcel(array $report): Response
    {
        $lines = [
            ['Bahan', 'Stok Awal', 'Masuk', 'Digunakan', 'Sisa', 'Satuan', 'Status'],
        ];

        foreach ($report['usageSummary'] as $row) {
            $lines[] = [
                $row['nama_bahan'],
                $row['stok_awal'],
                $row['masuk'],
                $row['digunakan'],
                $row['sisa'],
                $row['satuan'],
                $row['status_label'],
            ];
        }

        $lines[] = [];
        $lines[] = ['Tanggal', 'Bahan', 'Jenis', 'Jumlah', 'Satuan', 'Keterangan'];

        foreach ($report['movementRows'] as $row) {
            $lines[] = [
                $row['date']?->format('d/m/Y') ?? '-',
                $row['ingredient'],
                $row['type'],
                $row['qty'],
                $row['unit'],
                $row['note'],
            ];
        }

        $csv = collect($lines)
            ->map(fn (array $line) => collect($line)->map(fn ($value) => '"' . str_replace('"', '""', (string) $value) . '"')->implode(','))
            ->implode("\n");

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="laporan-bahan-swiftbite.csv"',
        ]);
    }

    private function paymentMethodLabel(?string $method): string
    {
        return match ($method) {
            'cash' => 'Tunai',
            'qris' => 'QRIS',
            'dana' => 'Dana',
            'ovo' => 'OVO',
            'gopay' => 'GoPay',
            'ewallet' => 'E-Wallet',
            default => $method ? ucfirst($method) : '-',
        };
    }
}
