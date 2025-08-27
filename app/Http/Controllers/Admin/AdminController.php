<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Contact;
use App\Models\News;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Coupon;
use App\Models\Promotion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Thống kê tổng quan
        $totalUsers = User::count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        // Tính doanh thu: chỉ đơn hàng đã giao (bao gồm cả COD và online đã thanh toán)
        $totalRevenue = Order::where('status', 'delivered')
                            ->where(function($query) {
                                $query->where('payment_status', 'paid')
                                      ->orWhere('payment_method', 'cod');
                            })
                            ->sum('final_total');

        // Thống kê đơn hàng theo trạng thái
        $orderStats = [
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
            'returned' => Order::where('status', 'returned')->count(),
        ];

        // Thống kê chi tiết đơn hàng theo trạng thái với doanh thu
        $orderStatusDetails = [
            'pending' => [
                'count' => Order::where('status', 'pending')->count(),
                'revenue' => Order::where('status', 'pending')->sum('final_total'),
                'label' => 'Chờ xử lý',
                'color' => '#ffc107'
            ],
            'processing' => [
                'count' => Order::where('status', 'processing')->count(),
                'revenue' => Order::where('status', 'processing')->sum('final_total'),
                'label' => 'Đang xử lý',
                'color' => '#17a2b8'
            ],
            'shipped' => [
                'count' => Order::where('status', 'shipped')->count(),
                'revenue' => Order::where('status', 'shipped')->sum('final_total'),
                'label' => 'Đang giao',
                'color' => '#007bff'
            ],
            'delivered' => [
                'count' => Order::where('status', 'delivered')->count(),
                'revenue' => Order::where('status', 'delivered')->sum('final_total'),
                'label' => 'Đã giao',
                'color' => '#28a745'
            ],
            'cancelled' => [
                'count' => Order::where('status', 'cancelled')->count(),
                'revenue' => Order::where('status', 'cancelled')->sum('final_total'),
                'label' => 'Đã hủy',
                'color' => '#dc3545'
            ],
            'returned' => [
                'count' => Order::where('status', 'returned')->count(),
                'revenue' => Order::where('status', 'returned')->sum('final_total'),
                'label' => 'Đã trả hàng',
                'color' => '#6c757d'
            ],
        ];

        // Doanh thu 7 ngày gần đây (chỉ đã giao)
        $revenueLastWeek = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $revenue = Order::whereDate('created_at', $date)
                           ->where('status', 'delivered')
                           ->where(function($query) {
                               $query->where('payment_status', 'paid')
                                     ->orWhere('payment_method', 'cod');
                           })
                           ->sum('final_total');
            
            $revenueLastWeek[] = [
                'date' => $date->format('d/m'),
                'revenue' => $revenue
            ];
        }

        // Đơn hàng 7 ngày gần đây (chỉ đã giao)
        $ordersLastWeek = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $count = Order::whereDate('created_at', $date)
                         ->where('status', 'delivered')
                         ->where(function($query) {
                             $query->where('payment_status', 'paid')
                                   ->orWhere('payment_method', 'cod');
                         })
                         ->count();
            $ordersLastWeek[] = [
                'date' => $date->format('d/m'),
                'count' => $count
            ];
        }

        // Top 5 sản phẩm bán chậm nhất (chỉ đã giao, trừ đi đã hủy)
        $slowMovingProducts = Product::leftJoin('product_variants', 'products.id', '=', 'product_variants.product_id')
            ->leftJoin('order_items', 'product_variants.id', '=', 'order_items.variant_id')
            ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
            ->select('products.id', 'products.name', 
                    DB::raw('COALESCE(SUM(product_variants.stock), 0) as total_stock'),
                    DB::raw('COALESCE(SUM(CASE WHEN orders.status = "delivered" THEN order_items.quantity ELSE 0 END), 0) - COALESCE(SUM(CASE WHEN orders.status = "cancelled" THEN order_items.quantity ELSE 0 END), 0) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->havingRaw('total_sold > 0') // Chỉ hiển thị sản phẩm có số lượng bán > 0
            ->orderBy('total_sold', 'asc')
            ->limit(5)
            ->get();

        // Top 5 sản phẩm bán chạy nhất (chỉ đã giao, trừ đi đã hủy)
        $topProducts = Product::leftJoin('product_variants', 'products.id', '=', 'product_variants.product_id')
            ->leftJoin('order_items', 'product_variants.id', '=', 'order_items.variant_id')
            ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
            ->select('products.id', 'products.name', 
                    DB::raw('COALESCE(SUM(CASE WHEN orders.status = "delivered" THEN order_items.quantity ELSE 0 END), 0) - COALESCE(SUM(CASE WHEN orders.status = "cancelled" THEN order_items.quantity ELSE 0 END), 0) as total_sold'),
                    DB::raw('COALESCE(SUM(CASE WHEN orders.status = "delivered" THEN order_items.total_price ELSE 0 END), 0) - COALESCE(SUM(CASE WHEN orders.status = "cancelled" THEN order_items.total_price ELSE 0 END), 0) as total_revenue'))
            ->groupBy('products.id', 'products.name')
            ->havingRaw('total_sold > 0') // Chỉ hiển thị sản phẩm có số lượng bán > 0
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        // Đơn hàng gần đây
        $recentOrders = Order::with('user:id,name')
            ->select('id', 'user_id', 'recipient_name', 'guest_name', 'final_total', 'status', 'created_at')
            ->latest()
            ->limit(10)
            ->get()
            ->map(function($order) {
                // Sử dụng guest_name nếu có, nếu không thì dùng recipient_name, cuối cùng mới dùng user name
                $customerName = $order->guest_name ?: $order->recipient_name;
                if (!$customerName && $order->user) {
                    $customerName = $order->user->name;
                }
                
                return [
                    'id' => $order->id,
                    'customer_name' => $customerName ?: 'Khách vãng lai',
                    'final_total' => $order->final_total,
                    'status' => $order->status,
                    'created_at' => $order->created_at->format('d/m/Y H:i'),
                ];
            });

        // Thống kê khác
        $stats = [
            'categories' => Category::count(),
            'brands' => Brand::count(),
            'news' => News::count(),
            'contacts' => Contact::count(),
            'promotions' => Promotion::where('status', 1)->count(),
            'active_coupons' => Coupon::where('status', true)
                                    ->where('start_date', '<=', now())
                                    ->where('end_date', '>=', now())
                                    ->count(),
            'low_stock_products' => Product::leftJoin('product_variants', 'products.id', '=', 'product_variants.product_id')
                                             ->where('product_variants.stock', '<', 10)
                                             ->distinct('products.id')
                                             ->count(),
        ];

        // Chương trình đang hoạt động
        $activePromotions = Promotion::where('status', 1)
            ->select('id', 'name', 'discount_type', 'discount_value', 'start_date', 'end_date')
            ->latest()
            ->limit(5)
            ->get();

        // Tỷ lệ thanh toán
        $paymentStats = [
            'cod' => Order::where('payment_method', 'cod')->count(),
            'bank_transfer' => Order::where('payment_method', 'bank_transfer')->count(),
            'credit_card' => Order::where('payment_method', 'credit_card')->count(),
            'vietqr' => Order::where('payment_method', 'vietqr')->count(),
        ];

        return view('admin.dashboard', compact(
            'totalUsers', 'totalProducts', 'totalOrders', 'totalRevenue',
            'orderStats', 'orderStatusDetails', 'revenueLastWeek', 'ordersLastWeek', 
            'slowMovingProducts', 'topProducts', 'recentOrders', 
            'stats', 'paymentStats', 'activePromotions'
        ));
    }

    // Thống kê doanh thu chi tiết
    public function revenueStatistics(Request $request)
    {
        $period = $request->get('period', 'month'); // day, week, month, year
        $year = $request->get('year', Carbon::now()->year);
        $month = $request->get('month', Carbon::now()->month);

        $data = [];
        $labels = [];
        $totalRevenue = 0;
        $totalOrders = 0;

        switch ($period) {
            case 'day':
                // Doanh thu theo ngày trong tháng
                $daysInMonth = Carbon::create($year, $month)->daysInMonth;
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $date = Carbon::create($year, $month, $day);
                    $revenue = $this->getRevenueForDate($date);
                    $orders = $this->getOrdersForDate($date);
                    
                    $data[] = $revenue;
                    $labels[] = $day;
                    $totalRevenue += $revenue;
                    $totalOrders += $orders;
                }
                $periodLabel = "Tháng " . $month . "/" . $year;
                break;

            case 'week':
                // Doanh thu theo tuần trong năm
                $weeks = Carbon::create($year)->weeksInYear;
                for ($week = 1; $week <= $weeks; $week++) {
                    $startOfWeek = Carbon::create($year)->startOfYear()->addWeeks($week - 1);
                    $endOfWeek = $startOfWeek->copy()->endOfWeek();
                    
                    $revenue = $this->getRevenueForDateRange($startOfWeek, $endOfWeek);
                    $orders = $this->getOrdersForDateRange($startOfWeek, $endOfWeek);
                    
                    $data[] = $revenue;
                    $labels[] = "Tuần " . $week;
                    $totalRevenue += $revenue;
                    $totalOrders += $orders;
                }
                $periodLabel = "Năm " . $year;
                break;

            case 'month':
                // Doanh thu theo tháng trong năm
                for ($monthNum = 1; $monthNum <= 12; $monthNum++) {
                    $startOfMonth = Carbon::create($year, $monthNum)->startOfMonth();
                    $endOfMonth = $startOfMonth->copy()->endOfMonth();
                    
                    $revenue = $this->getRevenueForDateRange($startOfMonth, $endOfMonth);
                    $orders = $this->getOrdersForDateRange($startOfMonth, $endOfMonth);
                    
                    $data[] = $revenue;
                    $labels[] = "Tháng " . $monthNum;
                    $totalRevenue += $revenue;
                    $totalOrders += $orders;
                }
                $periodLabel = "Năm " . $year;
                break;

            case 'year':
                // Doanh thu theo năm (5 năm gần đây)
                for ($yearNum = $year - 4; $yearNum <= $year; $yearNum++) {
                    $startOfYear = Carbon::create($yearNum)->startOfYear();
                    $endOfYear = $startOfYear->copy()->endOfYear();
                    
                    $revenue = $this->getRevenueForDateRange($startOfYear, $endOfYear);
                    $orders = $this->getOrdersForDateRange($startOfYear, $endOfYear);
                    
                    $data[] = $revenue;
                    $labels[] = $yearNum;
                    $totalRevenue += $revenue;
                    $totalOrders += $orders;
                }
                $periodLabel = "5 năm gần đây";
                break;
        }

        // Thống kê bổ sung
        $additionalStats = [
            'avgOrderValue' => $totalOrders > 0 ? $totalRevenue / $totalOrders : 0,
            'totalOrders' => $totalOrders,
            'maxRevenue' => max($data),
            'minRevenue' => min($data),
            'avgRevenue' => count($data) > 0 ? array_sum($data) / count($data) : 0,
        ];

        return view('admin.revenue-statistics', compact(
            'data', 'labels', 'totalRevenue', 'period', 'year', 'month', 
            'periodLabel', 'additionalStats'
        ));
    }

    // Lấy doanh thu cho một ngày cụ thể (chỉ đã giao)
    private function getRevenueForDate($date)
    {
        return Order::whereDate('created_at', $date)
                   ->where('status', 'delivered')
                   ->where(function($query) {
                       $query->where('payment_status', 'paid')
                             ->orWhere('payment_method', 'cod');
                   })
                   ->sum('final_total');
    }

    // Lấy số đơn hàng cho một ngày cụ thể (chỉ đã giao)
    private function getOrdersForDate($date)
    {
        return Order::whereDate('created_at', $date)
                   ->where('status', 'delivered')
                   ->where(function($query) {
                       $query->where('payment_status', 'paid')
                             ->orWhere('payment_method', 'cod');
                   })
                   ->count();
    }

    // Lấy doanh thu cho khoảng thời gian (chỉ đã giao)
    private function getRevenueForDateRange($startDate, $endDate)
    {
        return Order::whereBetween('created_at', [$startDate, $endDate])
                   ->where('status', 'delivered')
                   ->where(function($query) {
                       $query->where('payment_status', 'paid')
                             ->orWhere('payment_method', 'cod');
                   })
                   ->sum('final_total');
    }

    // Lấy số đơn hàng cho khoảng thời gian (chỉ đã giao)
    private function getOrdersForDateRange($startDate, $endDate)
    {
        return Order::whereBetween('created_at', [$startDate, $endDate])
                   ->where('status', 'delivered')
                   ->where(function($query) {
                       $query->where('payment_status', 'paid')
                             ->orWhere('payment_method', 'cod');
                   })
                   ->count();
    }

    // API endpoint để lấy dữ liệu doanh thu (cho AJAX)
    public function getRevenueData(Request $request)
    {
        $period = $request->get('period', '7days');
        $year = $request->get('year', Carbon::now()->year);
        $month = $request->get('month', Carbon::now()->month);
        $quarter = $request->get('quarter', 1);

        $data = [];
        $labels = [];
        $totalRevenue = 0;
        $totalOrders = 0;

        switch ($period) {
            case '7days':
                // 7 ngày gần đây
                for ($i = 6; $i >= 0; $i--) {
                    $date = Carbon::now()->subDays($i);
                    $revenue = $this->getRevenueForDate($date);
                    $orders = $this->getOrdersForDate($date);
                    
                    $data[] = $revenue;
                    $labels[] = $date->format('d/m');
                    $totalRevenue += $revenue;
                    $totalOrders += $orders;
                }
                break;

            case '30days':
                // 30 ngày gần đây
                for ($i = 29; $i >= 0; $i--) {
                    $date = Carbon::now()->subDays($i);
                    $revenue = $this->getRevenueForDate($date);
                    $orders = $this->getOrdersForDate($date);
                    
                    $data[] = $revenue;
                    $labels[] = $date->format('d/m');
                    $totalRevenue += $revenue;
                    $totalOrders += $orders;
                }
                break;

            case 'month':
                // Theo tháng trong năm
                for ($monthNum = 1; $monthNum <= 12; $monthNum++) {
                    $startOfMonth = Carbon::create($year, $monthNum)->startOfMonth();
                    $endOfMonth = $startOfMonth->copy()->endOfMonth();
                    $revenue = $this->getRevenueForDateRange($startOfMonth, $endOfMonth);
                    $orders = $this->getOrdersForDateRange($startOfMonth, $endOfMonth);
                    
                    $data[] = $revenue;
                    $labels[] = "Tháng " . $monthNum;
                    $totalRevenue += $revenue;
                    $totalOrders += $orders;
                }
                break;

            case 'quarter':
                // Theo quý - hiển thị 3 tháng trong quý
                $quarterStartMonth = ($quarter - 1) * 3 + 1;
                $quarterEndMonth = $quarter * 3;
                
                for ($monthNum = $quarterStartMonth; $monthNum <= $quarterEndMonth; $monthNum++) {
                    $startOfMonth = Carbon::create($year, $monthNum)->startOfMonth();
                    $endOfMonth = $startOfMonth->copy()->endOfMonth();
                    $revenue = $this->getRevenueForDateRange($startOfMonth, $endOfMonth);
                    $orders = $this->getOrdersForDateRange($startOfMonth, $endOfMonth);
                    
                    $data[] = $revenue;
                    $labels[] = "Tháng " . $monthNum;
                    $totalRevenue += $revenue;
                    $totalOrders += $orders;
                }
                break;

            case 'year':
                // Theo năm - chỉ hiển thị từ năm 2025
                $currentYear = Carbon::now()->year;
                $startYear = max(2025, $currentYear - 4); // Chỉ hiển thị từ 2025
                
                for ($yearNum = $startYear; $yearNum <= $currentYear; $yearNum++) {
                    $startOfYear = Carbon::create($yearNum)->startOfYear();
                    $endOfYear = $startOfYear->copy()->endOfYear();
                    $revenue = $this->getRevenueForDateRange($startOfYear, $endOfYear);
                    $orders = $this->getOrdersForDateRange($startOfYear, $endOfYear);
                    
                    $data[] = $revenue;
                    $labels[] = $yearNum;
                    $totalRevenue += $revenue;
                    $totalOrders += $orders;
                }
                break;
        }

        // Tính toán thống kê
        $maxRevenue = count($data) > 0 ? max($data) : 0;
        $minRevenue = count($data) > 0 ? min($data) : 0;
        $avgRevenue = count($data) > 0 ? array_sum($data) / count($data) : 0;
        
        // Tính tăng trưởng (so với kỳ trước)
        $growth = 0;
        if (count($data) >= 2) {
            $currentPeriod = end($data);
            $previousPeriod = prev($data);
            if ($previousPeriod > 0) {
                $growth = (($currentPeriod - $previousPeriod) / $previousPeriod) * 100;
            }
        }

        return response()->json([
            'data' => $data,
            'labels' => $labels,
            'totalRevenue' => $totalRevenue,
            'maxRevenue' => $maxRevenue,
            'minRevenue' => $minRevenue,
            'avgRevenue' => $avgRevenue,
            'growth' => round($growth, 2),
            'totalOrders' => $totalOrders
        ]);
    }
}
