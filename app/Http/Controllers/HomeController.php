<?php

namespace App\Http\Controllers;

use App\Models\Cases;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $opt = 1)
    {
        //dd(getDateTimeAsWord("2024-01-01", "10:43"));
//    $str=Carbon::now();
	$str = Carbon::createFromDate(2022,11,2,null);
    $khmerDate = toLunarDate($str)->toString();
//    dd($khmerDate);
        if(request("month")){
            $thisMonth = $request->month;
            $thisMonthFull =  $request->year."-".$request->month;
        }
        else{
            $thisMonth = myDate('m');
            $thisMonthFull = myDate('Y-m');
        }+

//        $data['totalCases'] = getCasesCountByType();
//        $data['totalCases'] = Cases::where('case_type_id', 1)->count();

        $data['thisMonth'] = $thisMonth;
        $data['thisMonthFull'] = $thisMonthFull;
        $data['pagetitle']="ផ្ទាំងពត៌មានសំណុំរឿង";
        $data['opt_search']="quick";
        $data['label'] = ["មេសា 2024", "មីនា 2024", "កុម្ភៈ 2024", "មករា 2024", "ធ្នូ 2023", "វិច្ឆិកា 2023", "តុលា 2023", "កញ្ញា 2023", "សីហា 2023", "កក្កដា 2023", "មិថុនា 2023", "ឧសភា 2023"];
        $data['total'] = "100, 120, 99, 140, 123, 140, 116, 105, 132, 145, 98, 88";
        $data['total_success'] = "90, 110, 99, 130, 103, 100, 110, 96, 102, 125, 88, 78";

        $view="dashboard.dashboard";
        //$view="layouts.app";
        //dd("Hello");
        return view($view, [ "adata" => $data ]);
    }

    function testingSSO (Request $request){
//        dd("Caching Routing");
        dd(session()->all());

        $loginCookie = session('mlvt_sso_cookie')->get('LOGIN_UNIQUE_COOKIE_UAT');

        if (!$loginCookie) {
            return response()->json(['error' => 'SSO cookie not found'], 400);
        }

        if ($loginCookie) {
            // Use the SSO cookie for authentication or API call

            $response = Http::withHeaders([
                'CLIENT-ID' => "65e18962086749f2ee477386417ef28741cf79d2c5644ddb6f96cc28",
                'CLIENT-SECRET-KEY' => "ff2c71c6956065a4f2766e73c2294082b2e52891243a2cb939a4bcd4e874fd6d",
                'Content-Type' => 'application/json',
            ])->post('https://uat-accounts.mlvt.gov.kh/api/v1/api-request/account/account-login-by-cookie', [
                'login_cookie_code' => $loginCookie,
            ]);

//            dd([
//                'status' => $response->status(),
//                'body' => $response->body(),
//                'headers' => $response->headers(),
//                'json' => $response->json(),
//                'content_type' => $response->header('Content-Type'),
//                'json_error' => json_last_error_msg(),
//            ]);

            if ($response->ok() && $response->json('code') === 200) {
                $data = $response->json('data.result');
                $loggedEmail = $data['user_login_info']['logged_email'] ?? 'not found';
                return $response->json('data.result');
            }
            return null;

        } else {
            return response()->json(['error' => 'SSO cookie not found'], 400);
        }
    }



    function homeDispute(){
        dd("Home Dispute");
    }
    function homeDosh(){
        dd("Home DOSH");
    }
    function homeEmployment(){
        dd("Home Employment");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
