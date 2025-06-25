<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Models\CustomerProfile;
use App\Models\ProductReview;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    public function ListReviewByProduct(Request $request)
    {

        $data = ProductReview::where('product_id', $request->product_id)->with(['profile' => function ($q) {
            $q->select('id', 'cus_name', 'cus_img');
        }])->get();
        return ResponseHelper::Out('success', $data, 200);
    }

    public function CreateReview(Request $request)
    {
        $user_id = $request->header('id');
        $profile = CustomerProfile::where('customer_id', $user_id)->first();
        if (!$profile) {
            return ResponseHelper::Out('error', 'Customer Profile not Exist', 400);
        }
        $data = ProductReview::updateOrCreate(
            [
                'customer_id' => $user_id,
                'product_id' => $request->input('product_id')
            ],
            $request->only(['description', 'rating'])
        );
        return ResponseHelper::Out('success', $data, 200);
    }
}
