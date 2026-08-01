<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    /**
     * Display all categories
     */
    public function index(Request $request)
    {
        return response()->json(
            Category::all()
        );
    }



    /**
     * Display single category
     */
    public function show(Category $category)
    {
        return response()->json(
            $category
        );
    }

}