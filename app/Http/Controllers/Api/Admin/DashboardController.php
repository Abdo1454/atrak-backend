<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\Order;
use App\Models\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;


class DashboardController extends Controller
{
    /**
     * Admin Dashboard Statistics
     */
    public function __invoke(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Statistics Cards
        |--------------------------------------------------------------------------
        */

        $productsCount = Product::count();

        $ordersCount = Order::count();

        $customersCount = User::where('role', 'customer')
            ->count();


        $revenue = Order::where('status', 'Delivered')
            ->sum('total');



        /*
        |--------------------------------------------------------------------------
        | Latest Orders
        |--------------------------------------------------------------------------
        */

        $latestOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($order) {

                return [
                    'id' => $order->id,

                    'customer' =>
                        $order->user?->name ?? 'Guest',

                    'status' =>
                        $order->status,

                    'total' =>
                        $order->total,

                    'date' =>
                        $order->created_at
                            ->format('Y-m-d'),
                ];

            });



        /*
        |--------------------------------------------------------------------------
        | Recent Customers
        |--------------------------------------------------------------------------
        */

        $recentCustomers = User::where('role', 'customer')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($user) {

                return [
                    'id' =>
                        $user->id,

                    'name' =>
                        $user->name,

                    'email' =>
                        $user->email,

                    'joined' =>
                        $user->created_at
                            ->format('Y-m-d'),
                ];

            });



        /*
        |--------------------------------------------------------------------------
        | Sales Chart - Last 7 Days
        |--------------------------------------------------------------------------
        */

        $salesChart = [];

        for ($i = 6; $i >= 0; $i--) {

            $date = Carbon::now()
                ->subDays($i)
                ->format('Y-m-d');


            $sales = Order::whereDate(
                    'created_at',
                    $date
                )
                ->where('status', 'Delivered')
                ->sum('total');


            $salesChart[] = [
                'date' =>
                    $date,

                'sales' =>
                    $sales,
            ];
        }



        /*
        |--------------------------------------------------------------------------
        | Orders Per Month
        |--------------------------------------------------------------------------
        */

        $ordersChart = Order::select(
                DB::raw(
                    "MONTH(created_at) as month"
                ),
                DB::raw(
                    "COUNT(*) as orders"
                )
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();



        /*
        |--------------------------------------------------------------------------
        | Best Selling Products
        |--------------------------------------------------------------------------
        */

        $bestProducts = DB::table('order_items')
            ->select(
                'product_id',
                DB::raw(
                    'SUM(quantity) as total_sold'
                )
            )
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();



        return response()->json([

            'statistics' => [

                'products' =>
                    $productsCount,

                'orders' =>
                    $ordersCount,

                'customers' =>
                    $customersCount,

                'revenue' =>
                    $revenue,

            ],


            'latest_orders' =>
                $latestOrders,


            'recent_customers' =>
                $recentCustomers,


            'charts' => [

                'sales_last_7_days' =>
                    $salesChart,


                'orders_by_month' =>
                    $ordersChart,


                'best_products' =>
                    $bestProducts,

            ]

        ]);
    }
}