<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Models\CustomerProfile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function ProfilePage(){
        return view('pages.profile-page');
    }
    public function CreateProfile(Request $request)
    {
       $user_id = $request->header('id');
       $request->merge(['user_id' => $user_id]);
       $data = CustomerProfile::updateOrCreate(['user_id' => $user_id],$request->input());
       return ResponseHelper::Out('success',$data,200);
    }

    public function GetProfile(Request $request)
    {
        $user_id = $request->header('id');
        $data = CustomerProfile::where('user_id',$user_id)->with('user')->first();
        return ResponseHelper::Out('success',$data,200);
    }
}
