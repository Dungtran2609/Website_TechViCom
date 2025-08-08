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
        $totalRevenue = Order::where('status', 'delivered')
                            ->where('payment_status', 'paid')
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

        // Doanh thu 7 ngày gần đây
        $revenueLastWeek = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $revenue = Order::whereDate('created_at', $date)
                           ->where('status', 'delivered')
                           ->where('payment_status', 'paid')
                           ->sum('final_total');
            $revenueLastWeek[] = [
                'date' => $date->format('d/m'),
                'revenue' => $revenue
            ];
        }

        // Đơn hàng 7 ngày gần đây
        $ordersLastWeek = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $count = Order::whereDate('created_at', $date)->count();
            $ordersLastWeek[] = [
                'date' => $date->format('d/m'),
                'count' => $count
            ];
        }

        // Top 5 sản phẩm bán chậm nhất
        $slowMovingProducts = Product::leftJoin('product_variants', 'products.id', '=', 'product_variants.product_id')
            ->leftJoin('order_items', 'product_variants.id', '=', 'order_items.variant_id')
            ->select('products.id', 'products.name', 
                    DB::raw('COALESCE(SUM(product_variants.stock), 0) as total_stock'),
                    DB::raw('COALESCE(SUM(order_items.quantity), 0) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'asc')
            ->limit(5)
            ->get();

        // Top 5 sản phẩm bán chạy nhất
        $topProducts = Product::leftJoin('product_variants', 'products.id', '=', 'product_variants.product_id')
            ->leftJoin('order_items', 'product_variants.id', '=', 'order_items.variant_id')
            ->select('products.id', 'products.name', 
                    DB::raw('COALESCE(SUM(order_items.quantity), 0) as total_sold'),
                    DB::raw('COALESCE(SUM(order_items.total_price), 0) as total_revenue'))
            ->groupBy('products.id', 'products.name')
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
            'active_coupons' => Coupon::where('status', true)
                                    ->where('start_date', '<=', now())
                                    ->where('end_date', '>=', now())
                                    ->count(),
            'low_stock_products' => Product::leftJoin('product_variants', 'products.id', '=', 'product_variants.product_id')
                                             ->where('product_variants.stock', '<', 10)
                                             ->distinct('products.id')
                                             ->count(),
        ];

        // Tỷ lệ thanh toán
        $paymentStats = [
            'cod' => Order::where('payment_method', 'cod')->count(),
            'bank_transfer' => Order::where('payment_method', 'bank_transfer')->count(),
            'credit_card' => Order::where('payment_method', 'credit_card')->count(),
            'vietqr' => Order::where('payment_method', 'vietqr')->count(),
        ];

        return view('admin.dashboard', compact(
            'totalUsers', 'totalProducts', 'totalOrders', 'totalRevenue',
            'orderStats', 'revenueLastWeek', 'ordersLastWeek', 
            'slowMovingProducts', 'topProducts', 'recentOrders', 
            'stats', 'paymentStats'
        ));
    }
}
