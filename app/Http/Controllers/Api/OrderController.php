<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;


class OrderController extends Controller
{

    /**
     * Display all orders
     */
    public function index()
    {

        $orders = Order::with([
                'items',
            ])
            ->latest()
            ->paginate(10);



        return response()->json($orders);

    }



    /**
     * Display single order
     */
    public function show(Order $order)
    {

        return response()->json(

            $order->load([
                'items'
            ])

        );

    }



    /**
     * Update order status
     */
    public function update(
        Request $request,
        Order $order
    )
    {

        $validated = $request->validate([

            'status' => [
                'required',
                'in:pending,processing,shipped,delivered,cancelled'
            ]

        ]);



        $order->update([

            'status' =>
                $validated['status']

        ]);



        return response()->json([

            'success' => true,

            'message' =>
                'Order status updated successfully',

            'order' =>
                $order

        ]);

    }

}