<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    /**
     * Store new order
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'user_id' => [
                'nullable',
                'integer',
                'exists:users,id'
            ],

            'items' => [
                'required',
                'array'
            ],

            'items.*.product_id' => [
                'required',
                'integer',
                'exists:products,id'
            ],

            'items.*.quantity' => [
                'required',
                'integer',
                'min:1'
            ],

            'total' => [
                'required',
                'numeric',
                'min:0'
            ],

            'address' => [
                'required',
                'string'
            ],

            'payment_method' => [
                'nullable',
                'string'
            ],

        ]);



        $order = DB::transaction(function () use ($validated) {


            $order = Order::create([

                'user_id' => $validated['user_id'] ?? null,

                'total' => $validated['total'],

                'address' => $validated['address'],

                'payment_method' =>
                    $validated['payment_method'] ?? null,

            ]);



            foreach ($validated['items'] as $item) {

                OrderItem::create([

                    'order_id' => $order->id,

                    'product_id' =>
                        $item['product_id'],

                    'quantity' =>
                        $item['quantity'],

                ]);

            }



            return $order->load('items');

        });



        return response()->json([

            'message' => 'Order created successfully',

            'order' => $order

        ], 201);
    }

}