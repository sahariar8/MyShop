<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Helper\SSLCommerz;
use App\Models\CustomerProfile;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\ProductCart;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function CreateInvoice(Request $request)
    {
        DB::beginTransaction();
        try {
            $user_id = $request->header('id');
            $user_email = $request->header('email');

            $tran_id = uniqid();
            $delivery_status = 'Pending';
            $payment_status = 'Pending';
            
            $profile = CustomerProfile::where('user_id', $user_id)->first();
            $cus_details = "Name:{$profile->cus_name},Address:{$profile->cus_add},City:{$profile->cus_city},Country:{$profile->cus_country},Phone:{$profile->cus_phone}";
            $ship_details = "Name:{$profile->ship_name},Address:{$profile->ship_add},City:{$profile->ship_city},Country:{$profile->ship_country},Phone:{$profile->ship_phone}";


            //payable Calculation
            $total = 0;
            $cartList = ProductCart::where('user_id', $user_id)->get();
            foreach ($cartList as $cart) {
                $total += $cart->price;
            }

            $vat = ceil(($total * 5) / 100);
            $payable = $total + $vat;

            $invoice = Invoice::create([
                'user_id' => $user_id,
                'cus_details' => $cus_details,
                'ship_details' => $ship_details,
                'total' => $total,
                'vat' => $vat,
                'payable' => $payable,
                'tran_id' => $tran_id,
                'delivery_status' => $delivery_status,
                'payment_status' => $payment_status,
                'discount' => 100,
                'shipping_method' => 'Courier',
            ]);


            $invoice_id = $invoice->id;

            foreach ($cartList as $cart) {
                InvoiceProduct::create([
                    'invoice_id' => $invoice_id,
                    'product_id' => $cart->product_id,
                    'qty' => $cart->qty,
                    'sale_price' => $cart->price,
                    'user_id' => $user_id,
                ]);
            }

            $paymentMethod = SSLCommerz::initiatePayment($tran_id, $payable, $profile, $user_email);
           
            DB::commit();
            return ResponseHelper::Out('success',array(['paymentMethod' => $paymentMethod,'payable' => $payable,'invoice_id' => $invoice_id,'tran_id' => $tran_id,'cus_details' => $cus_details,'ship_details' => $ship_details,'total' => $total,'vat' => $vat]),200);
        
        } catch (Exception $e) {
           DB::rollBack();
            return ResponseHelper::Out('error',array($e->getMessage()),500);
        }
    }

    public function GetInvoiceList(Request $request)
    {
        $user_id = $request->header('id');
        $invoiceList = Invoice::where('user_id', $user_id)->get();
        return ResponseHelper::Out('success',array($invoiceList),200);
    }

    public function InvoiceProductList(Request $request)
    {
        $user_id = $request->header('id');
        $invoice_id = $request->invoice_id;
        $invoiceDetails = InvoiceProduct::where('invoice_id', $invoice_id)->where('user_id',$user_id)->with('product')->get();
        return ResponseHelper::Out('success',array($invoiceDetails),200);
    }

}
