<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\DB;


class ApiController extends Controller
{



    function getTestData(){
        return $this->sendResponse("Testing Data", 'successfully.');
    }
    function khmerDate($mydate=""){
        $data= khmerDate($mydate);
        //$data= $data->toJson();

        return json_encode($data, JSON_UNESCAPED_UNICODE);//must return as Json
//        //$success=khmerDate($mydate);
////        $success['date']=$test.$mydate;
//        $success= "success data";
//        return $this->sendResponse($success, 'successfully.');

    }


    function getData2(){
        $data= User::all();
        //$data= $data->toJson();
        return json_encode($data, JSON_UNESCAPED_UNICODE);//must return as Json
        //return $data->json();
        //return $data;
    }
    function getData(){
        $data['pagetitle']="បញ្ជីាឈ្មោះក្រុមហ៊ុន JSON";
        $data['opt_search']= request('opt_search')? request('opt_search'): "quick";
        $companys=$this->getCompanyOrQuickSearch();
        $data['companys']= $companys;
        $view="company.list_json";
        $result = response()->json(['status'=>200,'result'=> $data]);
//        $jsonData = json_decode($result->content());
//        return $result;
        return json_encode($data, JSON_UNESCAPED_UNICODE);//must return as Json

    }
    function getCompanyOrQuickSearch()
    {
        //$students= Student::latest();
//        $users=User::orderBy("id", "DESC")
//            ->paginate($this->userPerPage);
        $companys = DB::table("tbl_company_api AS c")
            ->leftJoin('camdx_province AS p', 'p.pro_id', '=', 'c.business_province')
            ->leftJoin('camdx_district AS d', 'd.dis_id', '=', 'c.business_district')
            ->leftJoin("tbl_business_activity AS b", "b.id", "=", "c.business_activity")
            ->select([
                "c.company_id", "c.google_map_link", "c.encrypt_id", "c.company_name_khmer", "c.company_name_latin", "c.business_activity", "c.total_emp", "c.latest_total_emp","c.latest_total_emp_female", "c.latest_total_emp_date", "c.latest_service", "c.latest_total_for", "c.latest_total_for_female",
                "c.total_emp_date", "c.company_register_number", "c.company_tin", "c.owner_khmer_name",
                "c.business_province", "c.business_district",
                "b.bus_khmer_name", "b.level AS business_activity_level", "b.group_id",
                "p.pro_khname AS province_name", "d.dis_khname AS district_name"
            ]);
        //$companys = $companys->where("c.company_id", 2);
        //dd($companys->get());
        $companys = $companys->when(request("search"), function ($query, $search) {
            $query
                ->where(DB::raw("CONCAT('x',c.company_id,'x', c.company_name_khmer,'', COALESCE(c.company_name_latin, 'NULL') )"), "like", "%".$search."%");
        });
//        if($company_id > 0)
//        {
//            $companys= $companys->where("", "=", $request->k_category);
//        }
//        $userNotDisplay=[auth()->user()->id];//user login
//        $companys= $companys->whereNotIn("u.id", $userNotDisplay);
        $companys=$companys->orderBy("c.company_id", "DESC");
        $companys= $companys->paginate(5);
        $arraySearchParam =array (
            'search' => request( 'search'),
        );
        $companys->appends( $arraySearchParam );
        //dd($users);
        return $companys;
    }

    function test2(){
        $url="http://test1-sicms.kservone.com/api/testdata";
        $token="Bearer 1|1uZoo0q9rbwVtzhtGm7MgaUAK50q2g3zWMwWSnM5";
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'token' =>  $token
        ];
//        $client = new Client(['headers' => $headers ]);
        $client = new Client();
        $response = $client->request(
            'POST',
            $url,
            [
                'headers' => $headers,
                'form_params' => [] //['id' => "nokey"]
            ]
        );
        //getBody()->getContents()
//        $company= json_decode($response->getBody(), true);
//        $result= isset($company['results'])? $company['results'] : "";
        //print_r($result);
        return response($response, 201);
        //return $result;
//        return view("test.lacms-company", [
//            "pagetitle" => "Company List",
//            "opt_search" => "quick", //"quick",
//            "companies" => $result
//        ]);
    }

    function from_lacms(){
        $url="https://lacms.mlvt.gov.kh/api/company/list";
        $token="sdciqwksdsadfjJvVTFytoVCVBsa32897s28asdfk99a1xdzEjOHqNIs3yhdAICSXZ0Y";
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'token' =>  $token
        ];
//        $client = new Client(['headers' => $headers ]);
        $client = new Client();
        $response = $client->request(
            'POST',
            $url,
            [
                'headers' => $headers,
                'form_params' => ['id' => "nokey"]
            ]
        );
        //getBody()->getContents()
        $company= json_decode($response->getBody(), true);
        $result= isset($company['results'])? $company['results'] : "";
        //print_r($result);
        return response($result, 201);
        //return $result;
//        return view("test.lacms-company", [
//            "pagetitle" => "Company List",
//            "opt_search" => "quick", //"quick",
//            "companies" => $result
//        ]);
    }
}
