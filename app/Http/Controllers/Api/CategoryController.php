<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::latest()->get();

        return CategoryResource::collection($categories);
    }


    public function show(Category $category)
    {
        return new CategoryResource($category);
    }
}