<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class ProductController extends Controller
{

    /**
     * Display products
     */
    public function index()
    {
        $products = Product::with('category')
            ->latest()
            ->paginate(10);


        return response()->json($products);
    }



    /**
     * Store product
     */
    public function store(Request $request)
    {

        $validated = $request->validate([

            'name' => 'required|string|max:255',

            'category_id' =>
                'required|exists:categories,id',

            'price' =>
                'required|numeric',

            'stock' =>
                'required|integer',

            'description' =>
                'nullable|string',

            'image' =>
                'nullable|image|max:2048',

        ]);



        if ($request->hasFile('image')) {

            $validated['image'] =
                $request
                    ->file('image')
                    ->store('products','public');

        }



        $validated['slug'] =
            Str::slug($validated['name']);



        $product =
            Product::create($validated);



        return response()->json([

            'message' =>
                'Product created successfully',

            'product' =>
                $product

        ],201);

    }




    /**
     * Show single product
     */
    public function show(Product $product)
    {

        return response()->json(

            $product->load('category')

        );

    }




    /**
     * Update product
     */
    public function update(
        Request $request,
        Product $product
    )
    {

        $validated = $request->validate([

            'name' =>
                'sometimes|string|max:255',

            'category_id' =>
                'sometimes|exists:categories,id',

            'price' =>
                'sometimes|numeric',

            'stock' =>
                'sometimes|integer',

            'description' =>
                'nullable|string',

            'image' =>
                'nullable|image|max:2048',

        ]);



        if ($request->hasFile('image')) {


            if ($product->image) {

                Storage::disk('public')
                    ->delete($product->image);

            }


            $validated['image'] =
                $request
                    ->file('image')
                    ->store('products','public');

        }



        if (isset($validated['name'])) {

            $validated['slug'] =
                Str::slug(
                    $validated['name']
                );

        }



        $product->update($validated);



        return response()->json([

            'message' =>
                'Product updated successfully',

            'product' =>
                $product

        ]);

    }




    /**
     * Delete product
     */
    public function destroy(Product $product)
    {


        if ($product->image) {

            Storage::disk('public')
                ->delete($product->image);

        }


        $product->delete();



        return response()->json([

            'message' =>
                'Product deleted successfully'

        ]);

    }


}