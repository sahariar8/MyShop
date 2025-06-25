<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Models\ProductWish;
use Illuminate\Http\Request;

class ProductWishController extends Controller
{
    public function GetWishList(Request $request)
    {
        $user_id = $request->header('id');
        $data = ProductWish::where('user_id', $user_id)->with('product')->get();
        return ResponseHelper::Out('success', $data,200);
    }

    public function CreateWishList(Request $request)
    {
        $user_id = $request->header('id');
        $product_id = $request->product_id;
        $data = ProductWish::firstOrCreate([
            'user_id' => $user_id,
            'product_id' => $product_id,
        ]);
                
        return ResponseHelper::Out('Product added to wishlist', $data, 200);
        
    }

    public function DeleteWishList(Request $request)
    {
        $user_id = $request->header('id');
        $product_id = $request->product_id;
        $data = ProductWish::where('user_id', $user_id)->where('product_id', $product_id)->delete();
        return ResponseHelper::Out('Product removed from wishlist', $data, 200);
    }
}
