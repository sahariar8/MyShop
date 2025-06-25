<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

     public function ByCategoryPage()
    {
        return view('pages.product-by-category');
    }
    public function CategoryList()
    {
        $category = Category::all();
        return ResponseHelper::Out('success', $category,200);
    }
}
