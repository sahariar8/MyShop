<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function CreateCart(Request $request)
    {
        $user_id = $request->header('id');
        $product_id = $request->product_id;
        $color = $request->color;
        $size = $request->size;
        $qty = $request->quantity;
        $UnitPrice = 0;

        $productDetail = Product::where('id', $product_id)->first();

        // ❌ Product not found
        if (!$productDetail) {
            return response()->json(['status' => 'error', 'message' => 'Product not found'], 404);
        }

        // ✅ Check stock before proceeding
        if ($productDetail->stock < $qty) {
            return response()->json([
                'status' => 'error',
                'message' => 'Insufficient stock. Available: ' . $productDetail->stock,
            ], 400);
        }

        // Set price
        $UnitPrice = $productDetail->discount == 1
            ? $productDetail->discount_price
            : $productDetail->price;

        $totalPrice = $UnitPrice * $qty;

        // Update or create cart item
        $data = ProductCart::updateOrCreate(
            [
                'user_id' => $user_id,
                'product_id' => $product_id,
            ],
            [
                'user_id' => $user_id,
                'product_id' => $product_id,
                'color' => $color,
                'size' => $size,
                'qty' => $qty,
                'price' => $totalPrice
            ]
        );

        // Reduce stock
        $productDetail->stock = $productDetail->stock - $qty;
        $productDetail->save();

        return response()->json(['status' => 'success', 'data' => $data], 200);
    }

    public function GetCart(Request $request)
    {
        $user_id = $request->header('id');
        $data = ProductCart::where('user_id', $user_id)->with('product')->get();
        return response()->json(['status' => 'success', 'data' => $data], 200);
    }

    public function DeleteCart(Request $request)
    {
        $user_id = $request->header('id');
        $product_id = $request->product_id;

        $data = ProductCart::where([
            'user_id' => $user_id,
            'product_id' => $product_id
        ])->delete();

        return response()->json(['status' => 'success', 'data' => $data], 200);
    }
}
