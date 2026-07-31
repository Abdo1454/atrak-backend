<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class CategoryController extends Controller
{

    /**
     * Display all categories
     */
    public function index()
    {
        $categories = Category::latest()->get();


        return response()->json([
            'categories' => $categories
        ]);
    }



    /**
     * Store category
     */
    public function store(Request $request)
    {

        $validated = $request->validate([

            'name' =>
                'required|string|max:255',

        ]);



        $category = Category::create([

            'name' =>
                $validated['name'],

            'slug' =>
                Str::slug(
                    $validated['name']
                ),

        ]);



        return response()->json([

            'message' =>
                'Category created successfully',

            'category' =>
                $category

        ],201);

    }




    /**
     * Show category
     */
    public function show(Category $category)
    {

        return response()->json(
            $category
        );

    }




    /**
     * Update category
     */
    public function update(
        Request $request,
        Category $category
    )
    {

        $validated = $request->validate([

            'name' =>
                'required|string|max:255',

        ]);



        $category->update([

            'name' =>
                $validated['name'],

            'slug' =>
                Str::slug(
                    $validated['name']
                ),

        ]);



        return response()->json([

            'message' =>
                'Category updated successfully',

            'category' =>
                $category

        ]);

    }




    /**
     * Delete category
     */
    public function destroy(Category $category)
    {

        $category->delete();



        return response()->json([

            'message' =>
                'Category deleted successfully'

        ]);

    }

}