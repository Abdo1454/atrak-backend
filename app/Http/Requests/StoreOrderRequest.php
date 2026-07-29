<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_name'  => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email'],
            'customer_phone' => ['required', 'string', 'max:20'],

            'address'        => ['required', 'string'],
            'city'           => ['required', 'string'],
            'country'        => ['required', 'string'],

            'payment_method' => ['required', 'in:cash,card'],

            'subtotal'       => ['required', 'numeric', 'min:0'],
            'shipping'       => ['required', 'numeric', 'min:0'],
            'total_price'    => ['required', 'numeric', 'min:0'],

            'items'              => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity'   => ['required', 'integer', 'min:1'],
            'items.*.price'      => ['required', 'numeric', 'min:0'],
        ];
    }
}