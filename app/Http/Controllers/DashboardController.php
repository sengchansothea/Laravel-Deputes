<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyApi;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\PersionalAccessToken;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;


class DashboardController extends Controller
{
    //use HasApiTokens;




    public function index(){

//        $str=Carbon::now();
////        OR From exist date
//	    $str= Carbon::createFromDate(2022,11,2,null);
//        $khmerDate=toLunarDate($str)->toString();
//        echo $khmerDate;
        //flash()->addSuccess('Your account has been re-verified.');

         //print_r(Auth()->user());
         //$userToken = PersionalAccessToken::where("tokenable_id", 365)->first();
//        $userToken = PersionalAccessToken::find(1);
//         dd($userToken);
//        $tokenable_id=365;
//        //$user= PersionalAccessToken::find($tokenable_id);
//        $token = PersionalAccessToken::where("tokenable_id", $tokenable_id)->first();
//        //dd($token);
//        $token->token = hash('sha256', $plainTextToken = Str::random(40));
//        $token->updated_at = date("Y-m-d G:i:s");
//        $token->save();
//        dd($plainTextToken);

//        $token = $user->updateToken('auth_token')->plainTextToken;

//        return response()->json([
//            'status' => true,
//            'token' => $token,
//            'token_type' => 'Bearer',
//        ]);

        $data['pagetitle']="Dashboard";
        $data['opt_search']="quick";
        //$data['view']="dashboard";
        //$data['users']= User::all();

        //return view("welcome");
        //return view("mainboard");
        $view="dashboard.dashboard";
        //$view="layouts.app";
        //dd("Hello");
        return view($view, [ "adata" => $data ]);
    }

}
