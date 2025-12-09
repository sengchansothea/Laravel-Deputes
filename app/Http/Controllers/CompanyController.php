<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest;
use App\Models\Company;
use App\Models\CompanyApi;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    public $perPage = 20;
    public $totalRecord = 0;
    public $pageTitle = "បញ្ជីាឈ្មោះរោងចក្រ សហគ្រាស";
    public $pathFile="public/assets/images/";//public/assets/img/user
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $proID = 0)
    {
        //
        //dd($request);
        $data['opt_search'] = request('opt_search')? request('opt_search'): "quick";
        $data['companies'] = $this->getOrSearchEloquent();
        $data['province_id'] = $proID;
        $data['pagetitle'] = "បញ្ជីរោងចក្រ សហគ្រាស";
        $data['total'] = $data['companies']->total();

        $view = "company.list";
        if(request("json_opt") == 1){ //if request from app
            return response()->json(['status'=>200,'result'=> $data]);
        }
        return view($view, [ "adata" => $data ]);
    }

    public function getOrSearchEloquent()
    {
        $chkUserIdentity = chkUserIdentity();
        $domainMapping = [
            31 => 1, // ការិយាល័យទី១
            32 => 2, // ការិយាល័យទី២
            33 => 3, // ការិយាល័យទី៣
            34 => 4, // ការិយាល័យទី៤
        ];

        $companies = Company::with([
            'cases',
            'province',
            'district',
            'commune',
            'village',
            'companyType',
            'businessActivity',
            'cSIC1',
            'cSIC2',
            'cSIC3',
            'cSIC4',
            'cSIC5',
        ]);

        // Request parameters
        $provinceID = request('province_id');
        $districtID = request('district_id');
        $communeID  = request('commune_id');
        $busActivity = request('business_activity');
        $cSIC       = collect([
                request('csic1'),
                request('csic2'),
                request('csic3'),
                request('csic4'),
                request('csic5')
            ])->filter();

        // Domain-level filtering
        if ($chkUserIdentity > 3 && isset($domainMapping[$chkUserIdentity])) {
            $domainId = $domainMapping[$chkUserIdentity];
            $companies->whereHas('cases.casesCompany', function ($q) use ($domainId) {
                $q->where('domain_id', $domainId);
            })->distinct();
        }

        // Search filter
        if ($search = request('search')) {
            $companies->where(DB::raw("
            CONCAT(
                'x', id, 'x',
                company_name_khmer,
                COALESCE(company_name_latin, ''),
                COALESCE(company_register_number, ''),
                COALESCE(company_tin, ''),
                COALESCE(nssf_number, '')
            )
        "), 'LIKE', "%{$search}%");
        }

        // Business activity filters
        $companies->when($busActivity, fn($q) => $q->where('business_activity', $busActivity));

        // CSICs filters
        $companies->when($cSIC->isNotEmpty(), function ($q) use ($cSIC) {
               foreach ($cSIC->values() as $index => $value) {
                $q->where("csic_" . ($index + 1), $value);
            }
    });

        // Province & District & Commune filters
        $companies->when($provinceID, function ($q) use ($provinceID, $districtID, $communeID) {
            $q->where('province_id', $provinceID);
            if ($districtID) $q->where('district_id', $districtID);
            if ($communeID) $q->where('commune_id', $communeID);
        });


        $companies = $companies->orderBy('company_name_latin', 'ASC')->paginate($this->perPage);
        $companies->appends([
            'json_opt' => request('json_opt'),
            'search'   => request('search'),
            'business_activity'   => request('business_activity'),
            'company_type_id'   => request('company_type_id'),
            'csic1'   => request('csic1'),
            'csic2'   => request('csic2'),
            'csic3'   => request('csic3'),
            'csic4'   => request('csic4'),
            'csic5'   => request('csic5'),
        ]);

        return $companies;
    }



    public function list_json()
    {
        //testing json
        $data['pagetitle']="បញ្ជីាឈ្មោះក្រុមហ៊ុន JSON";
        $data['opt_search']= request('opt_search')? request('opt_search'): "quick";
        $companys=$this->getCompanyOrQuickSearch();
        $data['companys']= $companys;
        $view="company.list_json";
        if(request("json_opt") == 1){
            return response()->json(['status'=>200,'result'=> $data]);

        }
        //dd("web".request("json_opt"));
        return view($view, [ "adata" => $data ]);
    }

    function getOrSearchEloquent1($province_id){
        $companys = CompanyApi::query();
        $companys = $companys->when(request("search"), function ($query, $search) {
            $query
                ->where(DB::raw("CONCAT('x',company_id,'x', company_name_khmer,'', COALESCE(company_name_latin, 'NULL'), COALESCE(company_register_number, 'NULL'), COALESCE(company_tin, 'NULL') )"), "like", "%".$search."%");
        });

        if($province_id > 0){
            $companys = $companys->where("business_province", $province_id);
        }

        if(request('business_activity') && request('business_activity') > 0){
            $companys = $companys->where("business_activity", request('business_activity'));
            $this->pageTitle = "លទ្ធផលស្វែងរករោងចក្រ សហគ្រាស ";
        }
        if(request('total_emp') && request('total_emp') != 0){
            $arr_total_emp= explode("-", request('total_emp'));
            $companys = $companys->whereBetween('latest_total_emp', [ $arr_total_emp[0], $arr_total_emp[1] ]);
            $this->pageTitle = "លទ្ធផលស្វែងរករោងចក្រ សហគ្រាស ";
        }
        if(request('province_id') && request('province_id') > 0){
            $companys = $companys->where("business_province", request('province_id'));
            $this->pageTitle = "លទ្ធផលស្វែងរករោងចក្រ សហគ្រាស ";
        }
        if(request('district_id') && request('district_id') > 0){
            $companys = $companys->where("business_district", request('district_id'));
            $this->pageTitle = "លទ្ធផលស្វែងរករោងចក្រ សហគ្រាស ";
        }
        if(request('commune_id') && request('commune_id') > 0){
            $companys = $companys->where("business_commune", request('commune_id'));
            $this->pageTitle = "លទ្ធផលស្វែងរករោងចក្រ សហគ្រាស ";
        }


        if(request('insp_status') && request('insp_status') > 0){

            if(request('insp_status') <= 3 ){
                //dd(request('insp_status'));

                $companys = $companys->whereRelation("inspection","insp_type", request('insp_status'));

            }
            elseif(request('insp_status') == 4){//used to (ever) inspection
                $companys = $companys->whereRelation("inspection", "insp_type", ">", 0);
                //$companys->ddRawSql();
                //$sql.= " AND i.insp_type > 0 ";
            }
            elseif(request('insp_status') == 5){//never inspection

                //$companys = $companys->whereRelation("inspection", "insp_company_id", NULL);
                $companys = $companys->doesntHave("inspection"); // opposite has("inspection"), doesntExist(), exists()
                //$sql.=" AND i.insp_company_id IS NULL";
            }
            elseif(request('insp_status') == 6){//used to (ever) do self inspection
                $companys = $companys->whereRelation("inspection", "insp_type", "=", 0);
                //$companys->ddRawSql();
                //$sql.= " AND i.insp_type=0 "; //OR i.self_inspection=1

            }
            elseif(request('insp_status') == 7){//never do self inspection
                $str = 0;
                $companys = $companys->whereDoesntHave("inspection", function ($query) use ($str){
                        $query->where('insp_type', $str); //use ($str, $str2)
                    }
                );
                //dd($companys->toRawSql());
                //$sql=$sql_main. $where. " AND c.id NOT IN (SELECT insp_company_id FROM tbl_inspection WHERE self_inspection=1)";
            }
            $this->pageTitle = "លទ្ធផលស្វែងរករោងចក្រ សហគ្រាស ";
        }

        //$companys->ddRawSql();
        $this->totalRecord = $companys->count();
        $companys = $companys->orderBy("company_id", "DESC");
        $companys = $companys->paginate($this->perPage);
        $arraySearchParam =array (
            "json_opt" => request( 'json_opt'),
            "search" => request( 'search'),
            "business_activity" => request( 'business_activity'),
            "total_emp" => request( 'total_emp'),
            "business_province" => request( 'business_province'),
            "business_district" => request( 'business_district'),
            "business_commune" => request( 'business_commune'),
        );
        $companys->appends( $arraySearchParam );
        return $companys;
    }
    function advanceSearch(Request $request)
    {
//        dd($request->all());
        //dd( asset('public/storage/assets/css/font-khmer/KhmerOSbattambang.eot'));
        $field2="c.company_id, c.google_map_link, c.company_name_khmer, c.company_name_latin, c.business_activity, c.total_emp, c.latest_total_emp, c.latest_total_emp_female, c.latest_total_emp_date, c.latest_service, c.latest_total_for, c.latest_total_for_female, c.total_emp_date, c.company_register_number, c.company_tin, c.owner_khmer_name, c.business_province, c.business_district"
            .", b.bus_khmer_name, b.level AS business_activity_level, b.group_id, p.pro_khname AS province_name, d.dis_khname AS district_name ";//
        $field1=" DISTINCT(c.company_id), $field2, i.*";
        if($request->insp_status > 0){
            $companys = DB::table("tbl_company_api AS c")
                ->leftJoin("tbl_inspection AS i", "i.insp_company_id", "=", "c.company_id")
                ->leftJoin("tbl_business_activity AS b", "b.id", "=", "c.business_activity")
                ->leftJoin('camdx_province AS p', 'p.pro_id', '=', 'c.business_province')
                ->leftJoin('camdx_district AS d', 'd.dis_id', '=', 'c.business_district')
                ->select(DB::raw($field1));
            /** insp_status (tbl_inspection.insp_type)
             * 1: inspection normal
             * 2: inspection track
             * 3: inspection special
             * 0: inspection self
             *
             */
            if($request->insp_status <= 3){ // if insp_status = 1 or 2 or 3
                $companys= $companys->where("i.insp_type", "=", $request->insp_status);
            }
            elseif($request->insp_status == 4){ //if insp_status = 4
                $companys= $companys->where("i.insp_type", ">", 0);
            }
            elseif($request->insp_status == 5){//never inspection
                $companys= $companys->whereNull("i.insp_company_id");// where i.insp_company_id IS NULL
            }
            elseif($request->insp_status <= 6){
                $companys= $companys->where("i.insp_type", "=", "0");
            }
            elseif($request->insp_status == 7){ //never do self inspection
                $companys = DB::table("tbl_company_api AS c")
                    ->leftJoin("tbl_business_activity AS b", "b.id", "=", "c.business_activity")
                    ->leftJoin('camdx_province AS p', 'p.pro_id', '=', 'c.business_province')
                    ->leftJoin('camdx_district AS d', 'd.dis_id', '=', 'c.business_district')
                    ->select(DB::raw($field2));
                $companys= $companys->whereRaw("c.company_id NOT IN (SELECT insp_company_id FROM tbl_inspection i WHERE i.insp_type =0) "); //or self_inspection=1
            }
        }
        else{
            $companys = DB::table("tbl_company_api AS c")
                ->leftJoin("tbl_business_activity AS b", "b.id", "=", "c.business_activity")
                ->leftJoin('camdx_province AS p', 'p.pro_id', '=', 'c.business_province')
                ->leftJoin('camdx_district AS d', 'd.dis_id', '=', 'c.business_district')
                ->select(DB::raw($field2));

        }
        $companys= $companys->when(request("search"), function ($query, $search) {
            $query
                ->where(DB::raw("CONCAT('x',c.company_id,'x', c.company_name_khmer,'', COALESCE(c.company_name_latin, 'NULL'), c.company_tin, c.company_register_number )"), "like", "%".$search."%");
        });
        if($request->business_activity > 0)
        {
            $companys= $companys->where("c.business_activity", "=", $request->business_activity);
        }
        if($request->total_emp > 0)
        {
            $companys= $companys->where("c.total_emp", "=", $request->total_emp);
        }
        if($request->province_id > 0)
        {
            $companys= $companys->where("c.business_province", "=", $request->province_id);
        }
        if($request->district_id > 0)
        {
            $companys= $companys->where("c.business_district", "=", $request->district_id);
        }
        if($request->commune_id > 0)
        {
            $companys= $companys->where("c.business_commune", "=", $request->commune_id);
        }

        if ( $request->has("btnExport") )
        {

            $students= $students->get();
            return Excel::download(new StudentsExport($students), "studentlist.xlsx");
        }
        elseif ( $request->has("btnExportView") )
        {
            //dd("Export Excel From View");
            $students= $students->get();
            return Excel::download(new StudentViewExport($students), "studentlist.xlsx");
        }
        elseif ( $request->has("btnExportPDF") )
        {
            //dd("Export PDF2");
            $students= $students->get();
//            return view("pps.student.pdf.test", [
//            "pagetitle" => "Advance Search Student",
//            "i" => 1,
//            "students" => $students
//        ]);
            $pdf= PDF::loadView("pps.student.pdf.test", [
                "pagetitle" => "Advance Search Student",
                "i" => 1,
                "students" => $students
            ]);
            $path = public_path().'/pdf/';
            $fileName= "doc_".time().".pdf";
            //$pdf->save(public_path().'/pdf/document.pdf');//save, stream, download, output
            return  $pdf->stream($fileName);


        }
        else{
            $arraySearchParam =array (
                'search' => request( 'search'),
                'business_activity' => request( 'business_activity'),
                'total_emp' => request( 'total_emp'),
                'insp_status' => request( 'insp_status'),
                'province_id' => request( 'province_id'),
                'district_id' => request( 'district_id'),
                'commune_id' => request( 'commune_id'),
            );
            $total=$companys->count();
            $companys= $companys->paginate($this->perPage);
            $companys->appends( $arraySearchParam );
            $data['pagetitle']="ស្វែងរករោងចក្រ សហគ្រាស";
            $data['opt_search']= $request->opt_search;
            $data['view']="inspection.company.list";
            $data['pageNumber']=request("page");
            $data['companys']= $companys;
            $data['total']= $total;
            return view("main", [ "adata" => $data ]);
        }
    }
    function getCompanyOrQuickSearchxxx($province_id)
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
                ->where(DB::raw("CONCAT('x',c.company_id,'x', c.company_name_khmer,'', COALESCE(c.company_name_latin, 'NULL'), COALESCE(c.company_register_number, 'NULL'), , COALESCE(c.company_tin, 'NULL') )"), "like", "%".$search."%");
        });
        dd($companys);
//        if($company_id > 0)
//        {
//            $companys= $companys->where("", "=", $request->k_category);
//        }
//        $userNotDisplay=[auth()->user()->id];//user login
//        $companys= $companys->whereNotIn("u.id", $userNotDisplay);
        if($province_id > 0){
            $companys =$companys->where("business_province", $province_id);
        }
        $companys=$companys->orderBy("c.company_id", "DESC");
        $companys= $companys->paginate($this->perPage);
        $arraySearchParam =array (
            "json_opt" => request( 'json_opt'),
            'search' => request( 'search'),
        );
        $companys->appends( $arraySearchParam );
        //dd($users);
        return $companys;
    }

    function frm_insert_google_map(Request $request, $company_id=0, $opt=0){
        if($opt==1){//Save google map and photo
            //dd($request->all());
            $google_map_link= $request->input("google_map_link");
//            $google_map_link = str_replace(" ", "", $google_map_link);
//            $map_prefix="https://maps.google.com/maps?q=";
//            $find="maps.google.com";
//            if($google_map_link !=""){
//                if(strpos($google_map_link, $find)  == false){
//                    $google_map_link = $map_prefix.$google_map_link;
//                }
//            }
            $path_to_upload="storage/assets/images/";
            $company_photo= uploadFile($request, $path_to_upload, "company_photo", "company_photo_current", $request->input("company_photo_current"));
            //dd($company_photo);
            $result = DB::table("tbl_company_api")
                ->where("company_id", $company_id)
                ->update([
                    "google_map_link" => $google_map_link,
                    "company_photo" => $company_photo,
                    "date_updated"=> MyDateTime()
                ]);
            //sweetalert()->addSuccess('Your file may not have been uploaded.');
            //app('flasher')->addSuccess("ជោគជ័យ");
            return back()->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
        }
//        else{// get google map and photo to display
//            $query=$this->main->select_one_record("tbl_company_api", "google_map_link, company_photo", $cond);
//            //$google_map_link=$this->main->select_one_data("tbl_company_api", "google_map_link", $cond);
//            //echo "aa";
//            foreach($query->result() as $row)
//                $google_map_link= $row->google_map_link;
//            $company_photo= $row->company_photo; //echo $company_photo;
//        }


        $data['pagetitle']="Add Google Map";
        $data['opt_search']= request('opt_search')? request('opt_search'): "quick";;
        $company= CompanyApi::select("company_id", "google_map_link", "company_photo")
                            ->where("company_id", $company_id)->first();
        //dd($company->google_map_link);
        $data['company']= $company;
        $data['company_id'] =$company_id;
        $view="company.frm_insert_google_map";
        return view($view, [ "adata" => $data ]);
    }
    function refreshCompanyInfoFromLacms($company_id = 0)
    {
        OnlyDeveloperAccess();
        showButtonRefreshCompanyInfoFromLacms($company_id, 1);
        //$data=$this->loading_all(); //loading from MY_Controller
    }//end function
    function getCompanyFromLacms($page = 0)
    {
        OnlyDeveloperAccess();
        $perPage = 2000;
        $data['pagetitle'] = "Get Company From LACMS By Page";
        $data['perPage'] = $perPage;
        $data['page'] = $page;

        $view = "company.get_company_from_lacms";
        if(request("json_opt") == 1){ //if request from app
            return response()->json(['status'=>200,'result'=> $data]);
        }
        return view($view, [ "adata" => $data ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['pagetitle']="Company List";
        $data['opt_search']="quick";
        $view="company.list";
        return view($view, [ "adata" => $data ]);
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
        $data['company'] = Company::where('id', $id)->first();
//        dd($data['company']->cases);
        $data['pagetitle'] = "កំណត់ត្រាគូវិវាទ";
        $view = "company.company_history_list";

        if(request("json_opt") == 1){ //if request from app
            return response()->json(['status'=>200,'result'=> $data]);
        }
        return view($view, [ "adata" => $data ]);


    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $company = Company::where('company_id', $id)->first();
        $data['company'] = $company;
        $data['companyAPI'] = null;
        if(!empty($company->company_name_latin)){
            $data['companyAPI'] = CompanyApi::where('company_name_latin', $company->company_name_latin)->first();
        }
//        dd($data['companyAPI']);
        $data['pagetitle'] = "កែប្រែពត៌មានរោងចក្រ សហគ្រាស";
        $view = "company.company_edit";

        if(request("json_opt") == 1){ //if request from app
            return response()->json(['status'=>200,'result'=> $data]);
        }
        return view($view, [ "adata" => $data ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyRequest $request, string $id)
    {
//        dd($request->all());


        DB::beginTransaction();
        try{
            // Update Company All Data

            $arrCompanyCond = ['id' => $id ];
            $companyData = [
                'company_id_lacms' => $request->company_id_lacms,
                'company_option' => $request->company_option,
                'company_name_khmer' => $request->company_name_khmer,
                'company_name_latin' => $request->company_name_latin,
                'company_type_id' => $request->company_type_id,
                'article_of_company' => $request->article_of_company,
                'business_activity' => $request->business_activity,
                'csic_1' => $request->csic_1,
                'csic_2' => $request->csic_2,
                'csic_3' => $request->csic_3,
                'csic_4' => $request->csic_4,
                'csic_5' => $request->csic_5,
                'open_date' => date2DB($request->open_date),
                'company_register_number' => $request->company_register_number,
                'registration_date' => date2DB($request->registration_date),
                'company_tin' => $request->company_tin,
                'first_business_act' => $request->first_business_act,
                'nssf_number' => $request->nssf_number,
                'building_no' => $request->building_no,
                'street_no' => $request->street_no,
                'village_id' => $request->village_id,
                'commune_id' => $request->commune_id,
                'district_id' => $request->district_id,
                'province_id' => $request->province_id,
                'company_phone_number' => $request->company_phone_number,
                'user_updated' => Auth::user()->id,
                'date_updated' => myDate(),

            ];
//        dd($arrCompanyCond);

            Company::where($arrCompanyCond)->update($companyData);

            DB::commit();
            return back()->with("message", sweetalert()->addSuccess("ពត៌មានរោងចក្រ សហគ្រាស បានកែប្រែដោយជោគជ័យ"));
//            if($companyStatus > 0){
//
//            }else{
//                return back()->with("message", sweetalert()->addWarning("មិនមានអ្វីកែប្រែឡើយ!"));
//            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return back()->with("message", sweetalert()->addWarning("បរាជ័យ"));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
