<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\ProductReview;
use App\Models\ProductSlider;
use Illuminate\Http\Request;

class ProductController extends Controller
{

     public function WishList()
    {
        return view('pages.wish-list-page');
    }


    public function CartListPage()
    {
        return view('pages.cart-list-page');
    }


    public function Details()
    {
        return view('pages.details-page');
    }
    public function ListProductByCategory(Request $request)
    {

        $data = Product::where('category_id',$request->category_id)->with('brand','category')->get();
        return ResponseHelper::Out('success', $data, 200);
    }

    public function ListProductByRemark(Request $request)
    {
        
        $data = Product::where('remark',$request->remark)->with('brand','category')->get();
        return ResponseHelper::Out('success', $data, 200);
    }

    public function ListProductByBrand(Request $request)
    {
        
        $data = Product::where('brand_id',$request->brand_id)->with('brand','category')->get();
        return ResponseHelper::Out('success', $data, 200);
    }

    public function ListProductSlider()
    {
        
        $data = ProductSlider::all();
        return ResponseHelper::Out('success', $data, 200);
    }

    public function ProductDetailsById(Request $request)
    {
        
        $data = ProductDetail::where('id',$request->product_id)->with('product','product.brand','product.category')->first();
        return ResponseHelper::Out('success', $data, 200);
    }

    public function ListProductBySearch(Request $request)
    {
        
        $data = Product::whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($request->search) . '%'])
                ->with('brand', 'category')
                ->get();

        return ResponseHelper::Out('success', $data, 200);
    }

    
}
