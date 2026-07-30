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
        $query = Product::with('category');

        // Search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Sorting
        switch ($request->input('sort')) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;

            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;

            case 'oldest':
                $query->oldest();
                break;

            case 'newest':
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(
            $request->input('per_page', 12)
        );

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