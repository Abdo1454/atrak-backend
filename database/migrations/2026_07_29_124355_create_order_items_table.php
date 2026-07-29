<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Store a newly created order.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string|max:20',

            'address' => 'required|string',
            'city' => 'required|string',
            'country' => 'required|string',

            'payment_method' => 'required|in:cash,visa',

            'subtotal' => 'required|numeric',
            'shipping' => 'required|numeric',
            'total_price' => 'required|numeric',

            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {

            $order = Order::create([
                'customer_name' => $validated['customer_name'],
                'email' => $validated['customer_email'],
                'phone' => $validated['customer_phone'],

                'address' => $validated['address'],
                'city' => $validated['city'],
                'country' => $validated['country'],

                'payment_method' => $validated['payment_method'],

                'subtotal' => $validated['subtotal'],
                'shipping' => $validated['shipping'],
                'total' => $validated['total_price'],

                'status' => 'pending',
            ]);

            foreach ($validated['items'] as $item) {

                $product = Product::findOrFail($item['product_id']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'custom_perfume' => null,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully.',
                'order' => $order->load('items'),
            ], 201);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}