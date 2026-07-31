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
                'user',
                'items.product'
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
                'user',
                'items.product'
            ])
        );
    }



    /**
     * Update order status
     */
    public function update(
        Request $request,
        Order $order
    ) {

        $validated = $request->validate([

            'status' =>
                'required|in:Pending,Processing,Delivered,Cancelled',

        ]);



        $order->update($validated);



        return response()->json([

            'message' =>
                'Order status updated successfully',

            'order' =>
                $order

        ]);

    }


}