<?php

namespace App\Helper;

use App\Models\Invoice;
use App\Models\ProductCart;
use App\Models\SslcommerzAccount;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SSLCommerz
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    static function initiatePayment($tran_id, $payable, $profile, $email)
    {

        try {
            $ssl = SslcommerzAccount::first();
            $response = Http::asForm()->post('https://sandbox.sslcommerz.com/gwprocess/v4/api.php', [
                "store_id" => $ssl->store_id,
                "store_passwd" => $ssl->store_password,
                "total_amount" => $payable,
                "currency" => "BDT",
                "tran_id" => $tran_id,
                "success_url" => "$ssl->success_url?tran_id=$tran_id",
                "fail_url" => "$ssl->fail_url?tran_id=$tran_id",
                "cancel_url" => "$ssl->cancel_url?tran_id=$tran_id",
                "ipn_url" => "$ssl->ipn_url?tran_id=$tran_id",
                "emi_option" => 0,
                "cus_name" => $profile->cus_name,
                "cus_email" => $email,
                "product_category" => "apple shop",
                "cus_add1" => $profile->cus_add,
                "cus_add2" => $profile->cus_add,
                "cus_city" => $profile->cus_city,
                "cus_postcode" => $profile->cus_postcode,
                "cus_country" => $profile->cus_country,
                "cus_phone" => $profile->cus_phone,
                "shipping_method" => "Yes",
                "num_of_item" => 0,
                "weight_of_items" => 0,
                "logistic_pickup_id" => "SHA-121",
                "logistic_delivery_type" => "COURIER",
                "product_name" => "LAPTOP",
                "product_profile" => "physical-goods",
                "ship_name" => $profile->ship_name,
                "ship_add1" => $profile->ship_add,
                "ship_city" => $profile->ship_city,
                "ship_postcode" => $profile->ship_postcode,
                "ship_country" => $profile->ship_country,
                "ship_phone" => $profile->ship_phone,
            ]);
            return $response->json('desc');
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    static function initiateFail($tran_id)
    {
        try {
            Invoice::where('tran_id', $tran_id)
                ->where('val_id', 0)
                ->update(['payment_status' => 'Fail']);

            return 1;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    static function initiateSuccess($tran_id)
    {
        try {
            // Invoice::where('tran_id', $tran_id)
            //     ->where('val_id', 0)
            //     ->update(['payment_status' => 'success']);

            // return 1;

            $invoice = Invoice::where('tran_id', $tran_id)->first();

            if (!$invoice) {
                return response()->json(['status' => 'error', 'message' => 'Invoice not found'], 404);
            }

            // Mark invoice as paid
            $invoice->payment_status = 'success';
            $invoice->save();

            // Clear user's cart after payment

            ProductCart::where('user_id', $invoice->user_id)->delete();
            return ResponseHelper::Out('success', 'Payment successful completed',200);
            
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    static function initiateCancel($tran_id)
    {
        try {
            Invoice::where('tran_id', $tran_id)
                ->where('val_id', 0)
                ->update(['payment_status' => 'cancel']);

            return 1;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    static function initiateIPN($tran_id, $val_id, $status)
    {
        try {
            Invoice::where(['tran_id', $tran_id, 'val_id' => 0])->update(['payment_status' => $status, 'val_id' => $val_id]);
            return 1;
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $e->getMessage();
        }
    }
}
