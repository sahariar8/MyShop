<?php

namespace App\Http\Controllers;

use App\Helper\SSLCommerz;
use Illuminate\Http\Request;

class SslcommerzAccountController extends Controller
{
      public function PaymentSuccess(Request $request)
    {

        return SSLCommerz::initiateSuccess($request->query('tran_id'));
    }

    public function PaymentFail(Request $request)
    {
 
        return SSLCommerz::initiateFail($request->query('tran_id'));
    }

    public function PaymentCancel(Request $request)
    {

        return SSLCommerz::initiateCancel($request->query('tran_id'));
    }
    
    public function PaymentIpn(Request $request)
    {
        return SSLCommerz::initiateIpn($request->input('tran_id'),$request->input('status'),$request->input('val_id'));
    }
}
