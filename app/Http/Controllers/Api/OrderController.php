<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request)
    {
        $validated = $request->validated();

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

            $order->load('items');

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully.',
                'data' => new OrderResource($order),
            ], 201);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create order.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}