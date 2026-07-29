<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request)
    {
        $products = Product::with('category')

            // Search by product name
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })

            // Filter by category
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->where('category_id', $request->category);
            })

            // Sorting
            ->when($request->filled('sort'), function ($query) use ($request) {

                switch ($request->sort) {

                    case 'price_asc':
                        $query->orderBy('price');
                        break;

                    case 'price_desc':
                        $query->orderByDesc('price');
                        break;

                    case 'oldest':
                        $query->oldest();
                        break;

                    default:
                        $query->latest();
                        break;
                }

            }, function ($query) {
                $query->latest();
            })

            ->paginate($request->input('per_page', 12));

        return ProductResource::collection($products);
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        return new ProductResource(
            $product->load('category')
        );
    }
}