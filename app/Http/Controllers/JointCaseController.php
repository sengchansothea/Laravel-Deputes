<?php

namespace App\Http\Controllers;

use App\Models\CaseCompany;
use App\Models\CaseDisputant;
use App\Models\CaseInvitation;
use App\Models\CaseLog;
use App\Models\CaseLog34;
use App\Models\CaseLog5;
use App\Models\CaseLog5Union1;
use App\Models\CaseLog6;
use App\Models\CaseLog620;
use App\Models\CaseLog621;
use App\Models\CaseLogAttendant;
use App\Models\CaseOfficer;
use App\Models\Cases;
use App\Models\Company;
use App\Models\CompanyApi;
use App\Models\Disputant;
use App\Models\InvitationNextTime;
use App\Models\JointCases;
use App\Models\Officer;
use Carbon\Traits\Date;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class JointCaseController extends Controller
{
    private int $totalRecord = 0;
    private int $perPage = 20;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        //deleteFile("qrcode1.jpg", pathToUploadFile("case_doc/form1/"));//delete invitation_file
        $data['opt_search']= request('opt_search')? request('opt_search'): "quick";
        $data['jointCases']= $this->getOrSearchEloquent();
        $data['pagetitle']= "តារាងតាមដានវឌ្ឍនភាព នៃសំណុំរឿងវិវាទការងារ";
        $data['totalRecord'] = $this->totalRecord;
        $view="case.joint_case.list_joint_case";

        if(request("json_opt") == 1){ //if request from app
            return response()->json(['status' => 200, 'message' => 'success', 'data'=> $data]);
        }

        return view($view, [ "adata" => $data ]);
    }


    function getOrSearchEloquent(){
        $cases = JointCases::with([
            'unit',
            'company',
            'company.province',
            'company.district'
        ]);

        if(request("search")){
            $search = request("search");
            $cases = $cases->whereRelation("company", function ($query) use ($search) {
                //$query->whereRaw("long_distance = 1");
                $query->where(DB::raw("CONCAT('x',company_id,'x', company_name_khmer,'', COALESCE(company_name_latin, 'NULL'), COALESCE(company_register_number, 'NULL'), COALESCE(company_tin, 'NULL') )"), "LIKE", "%".$search."%");
            })
            ->orWhereRelation("disputant", function ($query) use ($search) {
                $query->where(DB::raw("CONCAT('x',id,'x', name,'', COALESCE(name_latin, 'NULL'), id_number )"), "LIKE", "%".$search."%");
            });
            //dd($cases->ddRawSql());
        }

//        if(request('business_activity') && request('business_activity') > 0){
//            $companys = $companys->where("business_activity", request('business_activity'));
//            $this->pageTitle = "លទ្ធផលស្វែងរករោងចក្រ សហគ្រាស ";
//        }

        //$companys->ddRawSql();

        $cases = $cases->orderBy("id", "DESC");
        $cases = $cases->paginate($this->perPage);
        $this->totalRecord = $cases->total(); // ✅ Get total after pagination (no extra query)

        $arraySearchParam =array (
            "json_opt" => request( 'json_opt'),
            "search" => request( 'search'),
//            "business_activity" => request( 'business_activity'),
//            "total_emp" => request( 'total_emp'),
//            "business_province" => request( 'business_province'),
//            "business_district" => request( 'business_district'),
//            "business_commune" => request( 'business_commune'),
        );
        $cases->appends( $arraySearchParam );
        return $cases;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['pagetitle']= "បង្កើតបណ្តឹងវិវាទរួមថ្មី";
        $view = "case.joint_case.create_joint_case";
        if(request("json_opt") == 1){ //if request from app
            return response()->json(['status'=>200,'result'=> $data]);
        }
        return view($view, [ "adata" => $data ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
//        dd($request->all());
        $date_created = myDateTime();
        $company_name_khmer = $request->company_name_khmer;
        $caseYear = $request->case_year;


        DB::beginTransaction();
        try{

            /** =============== Create Or Update Company (tbl_company) =================== */
            $searchCompany = ["company_name_khmer" => $company_name_khmer];
            $adataComInfo = [
                "company_id_lacms" => $request->company_id,
                "company_option" => 2,
                "company_name_latin" => $request->company_name_latin,
                "first_business_act" => $request->first_business_act,
                "article_of_company" => $request->article_of_company,
                "company_type_id" => $request->company_type_id,
                "sector_id" => $request->sector_id,
                "company_phone_number" => $request->company_phone_number,

                "business_activity" => $request->business_activity,
                "business_activity1" => $request->business_activity1,
                "business_activity2" => $request->business_activity2,
                "business_activity3" => $request->business_activity3,
                "business_activity4" => $request->business_activity4,

                "company_register_number" => $request->company_register_number,
                "registration_date" => $request->registration_date,
                "company_tin" => $request->company_tin,
                "nssf_number" => $request->nssf_number,

                "street_no" => $request->street_no,
                "building_no" => $request->building_no,
                "village_id" => $request->vil_id,
                "commune_id" => $request->com_id,
                "district_id" => !empty($request->dis_id) ? $request->dis_id : $request->district_id,
                "province_id" => !empty($request->pro_id) ? $request->pro_id : $request->province_id,


                "user_created" => Auth::user()->id,
                "user_updated" => Auth::user()->id,
                "date_created" =>  $date_created,
                "date_updated" =>  $date_created,
            ];

//            dd($adataComInfo);
//            dd($searchCompany);


            $insertedCom = Company::updateOrCreate($searchCompany, $adataComInfo);
            $comID = !empty($insertedCom) ? $insertedCom->id : 0;
            Company::where("id", $comID)->update(["company_id" => $comID]);

            /** =============== Create Joint Case (tbl_joint_case) ======================== */
            $adataJCase = [
                "company_id" => $comID,
                "province_id" => $request->province_id,
                "district_id" => $request->district_id,
                "case_year" => $caseYear,
                "total_disputed_emp" => $request->total_disputed_emp,
                "total_emp" => $request->total_emp,
                "disputed_reason" => $request->disputed_reason,
                "union_representative" => $request->union_representative,
                "agree_result" => $request->agree_result,
                "disagree_result" => $request->disagree_result,
                "dispute_resolution" => $request->dispute_resolution,
                "next_measure" => $request->next_measure,
                "unit_id" => $request->unit_id,
                "responsible_person" => $request->responsible_person,
                "user_created" => Auth::user()->id,
                "user_updated" => Auth::user()->id,
                "date_created" =>  $date_created,
                "date_updated" =>  $date_created,
            ];
//            dd($adataJCase);
            $insertedJCase = JointCases::create($adataJCase);
//            dd($insertedJCase);

            $jCaseID = $insertedJCase->id;//get Joint Case ID
            //dd($case_id);

            /** Upload All References Files and Update Joint Case table */
            $path_to_upload = pathToUploadFile("case_doc/joint/".$caseYear."/");

            $resultFile = uploadFileOnly($request, $path_to_upload, "result_file", $jCaseID, "result");
            $disputeResolutionFile = uploadFileOnly($request, $path_to_upload, "dispute_resolution_file", $jCaseID, "resolution");
            $nextMeasureFile = uploadFileOnly($request, $path_to_upload, "next_measure_file", $jCaseID, "measure");
            $jointCaseFile = uploadFileOnly($request, $path_to_upload, "joint_case_file", $jCaseID, "joint");

//            dd($nextMeasureFile);

            //Update Joint Case table
            JointCases::where("id", $jCaseID)->update([
                "result_file" => $resultFile,
                "dispute_resolution_file" => $disputeResolutionFile,
                "next_measure_file" => $nextMeasureFile,
                "joint_case_file" => $jointCaseFile,
            ]);

            DB::commit();
            if(request("json_opt") == 1){ //if request from app
                //$data = getDataForAllMenu($inspection_id, $this->menu);
                //return response()->json(['status' => 200, 'message' => 'success', 'data'=> $data]);
            }
            return redirect("joint_cases");

        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return back()->with("message", sweetalert()->addWarning("បរាជ័យ"));
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //dd("show:".$id);
        $data['pagetitle']= "ដំណើរការបណ្ដឹង";
        $data['cases'] = Cases::where("id", $id)->first();
        $view = "case.show_case";
        if(request("json_opt") == 1){ //if request from app
            return response()->json(['status' => 200, 'message' => 'success', 'data'=> $data]);
        }
        return view($view, [ "adata" => $data ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['pagetitle']= "កែប្រែពាក្យបណ្ដឹង (វិវាទរួម)";
        $data['cases'] = JointCases::where("id", $id)->first();
        $view = "case.joint_case.update_joint_case";
        if(request("json_opt") == 1){ //if request from app
            return response()->json(['status' => 200, 'message' => 'success', 'data'=> $data]);
        }
        return view($view, [ "adata" => $data ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

//        dd($request->all());
        $date_created = myDateTime();
        $jCaseID = $id;
        $caseYear = $request->case_year;
        DB::beginTransaction();
        try{

            /** =============== Upload All Reference Files ====================== */
            $path_to_upload = pathToUploadFile("case_doc/joint/".$caseYear."/");

            //Result File
            $resultFileNew = uploadFileOnly($request, $path_to_upload, "result_file", $jCaseID, "result");
            $resultFile = !empty($resultFileNew)? $resultFileNew : $request->result_file_old;

            //Dispute Resolution File
            $resolutionFileNew = uploadFileOnly($request, $path_to_upload, "dispute_resolution_file", $jCaseID, "resolution");
            $resolutionFile = !empty($resolutionFileNew)? $resolutionFileNew : $request->dispute_resolution_file_old;

            //Next Measure File
            $measureFileNew = uploadFileOnly($request, $path_to_upload, "next_measure_file", $jCaseID, "measure");
            $measureFile = !empty($measureFileNew)? $measureFileNew : $request->next_measure_file_old;



            $jointCaseFileNew = uploadFileOnly($request, $path_to_upload, "joint_case_file", $jCaseID, "joint");
            $jointCaseFile = !empty($jointCaseFileNew)? $jointCaseFileNew : $request->joint_case_file_old;


            /** =============== Update Joint Case (tbl_joint_case) ======================== */
            $adataJCase = [
                "case_year" => $caseYear,
                "total_disputed_emp" => $request->total_disputed_emp,
                "total_emp" => $request->total_emp,
                "disputed_reason" => $request->disputed_reason,
                "union_representative" => $request->union_representative,
                "agree_result" => $request->agree_result,
                "disagree_result" => $request->disagree_result,
                "result_file" => $resultFile,
                "dispute_resolution" => $request->dispute_resolution,
                "dispute_resolution_file" => $resolutionFile,
                "next_measure" => $request->next_measure,
                "next_measure_file" => $measureFile,
                "unit_id" => $request->unit_id,
                "responsible_person" => $request->responsible_person,
                "joint_case_file" => $jointCaseFile,
                "user_created" => Auth::user()->id,
                "user_updated" => Auth::user()->id,
                "date_created" =>  $date_created,
                "date_updated" =>  $date_created,
            ];
//            dd($adataJCase);
            JointCases::where('id', $jCaseID)->update($adataJCase);

            DB::commit();
            if(request("json_opt") == 1){ //if request from app
                //$data = getDataForAllMenu($inspection_id, $this->menu);
                //return response()->json(['status' => 200, 'message' => 'success', 'data'=> $data]);
            }
            return back()->with("message", sweetalert()->addSuccess("ជោគជ័យ"));

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

        $jCase = JointCases::where("id", $id)->first();
        $caseYear = $jCase->case_year;
        $path_to_delete = pathToUploadFile("case_doc/joint/".$caseYear."/");

        $resultFile = $jCase->result_file;
        $resolutionFile = $jCase->dispute_resolution_file;
        $measureFile = $jCase->next_measure_file;
        $jointFile = $jCase->joint_case_file;

        DB::beginTransaction();
        try{
            /**  Delete All References Files */
            deleteFile($resultFile, $path_to_delete);
            deleteFile($resolutionFile, $path_to_delete);
            deleteFile($measureFile, $path_to_delete);
            deleteFile($jointFile, $path_to_delete);

            /** Delete Joint Case: tbl_joint_disute : 1 record */
            JointCases::where("id", $id)->delete();


            DB::commit();
            if(request("json_opt") == 1){ //if request from app
                //$data = getDataForAllMenu($inspection_id, $this->menu);
                //return response()->json(['status' => 200, 'message' => 'success', 'data'=> $data]);
            }
            return back()->with("message", sweetalert()->addSuccess("ជោគជ័យ"));

        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return back()->with("message", sweetalert()->addWarning("បរាជ័យ"));
        }
    }
}
