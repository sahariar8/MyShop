<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Helper\ResponseHelper;
use App\Mail\OTPMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function UserLogin (Request $request)
    {
         try {
            $email = $request->userEmail;
            $otp = rand(100000, 999999);
            $details = ['code'=> $otp];
            Mail::to($email)->send(new OTPMail($details));
            User::updateOrCreate(['email' => $email], ['otp' => $otp]);
            return response()->json(['success' => 'A 6 digit OTP has been sent to your email address.','otp'=>$otp]);

         } catch (\Exception $e) {

            $msg = $e->getMessage();
            return response()->json(['error' => $msg]);
         }
    }

    public function VerifyLogin(Request $request)
    {
        try {
            $email = $request->userEmail;
            $otp = $request->otp;
            $user = User::where('email', $email)->first();
            if ($user->otp == $otp) {
                User::where('email', $email)->update(['otp' => null]);
                $token = JWTToken::CreateToken($email, $user->id);          
                return Response('Success',200)->cookie('jwt', $token, 60);
            } else {
                return response()->json(['error' => 'Invalid OTP'], 401);
            }
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            return response()->json(['error' => $msg]);
        }
    }

    public function UserLogout()
    {
        // return redirect('/user-login')->cookie('jwt', '', -1);
        return ResponseHelper::out('Success','Logout Successfully',200)->cookie('jwt', '', -1);
    }
}
