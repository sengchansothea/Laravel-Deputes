<?php

namespace App\Http\Controllers;

use App\Models\CaseCompany;
use App\Models\Cases;
use App\Models\CollectivesIssues;
use App\Models\CollectivesRepresentatives;
use App\Models\Company;
use App\Models\JointCases;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class CollectiveCaseController extends Controller
{
    private int $totalRecord = 0;
    private int $perPage = 20;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        //deleteFile("qrcode1.jpg", pathToUploadFile("case_doc/form1/"));//delete invitation_file
        $data['opt_search'] = request('opt_search')? request('opt_search'): "quick";
        $data['collectives'] = $this->getOrSearchEloquent();
        $data['pagetitle'] = "បញ្ជីវិវាទការងាររួម";
        $data['totalRecord'] = $data['collectives']->total();

        $view="case.collectives.list_collective_case";

        if(request("json_opt") == 1){ //if request from app
            return response()->json(['status' => 200, 'message' => 'success', 'data'=> $data]);
        }

        return view($view, [ "adata" => $data ]);
    }

    function getOrSearchEloquent()
    {
        $cases = Cases::with('caseType')
            ->where('case_type_id', 3);
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
//        $this->totalRecord = $cases->count();
        $cases = $cases->orderBy("id", "DESC");
        $cases = $cases->paginate($this->perPage);
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
        $data['pagetitle']= "បង្កើតបណ្តឹងវិវាទការងាររួមថ្មី";
        $collectivesCountPlus1 = Cases::where('case_type_id', 3)->count() + 1;
//        $data['collectivesCountPlus1'] = $collectivesCountPlus1;
        $data['caseNumber'] = sprintf('%03d', $collectivesCountPlus1);
        $view = "case.collectives.create_collective_case";

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
        $date_created = myDateTime();
//        $countPlus1 = $request->collectivesCountPlus1;
//        dd($countPlus1);
        $company_name_khmer = $request->company_name_khmer;
        $company_option = $request->company_option;
        $caseYear = !empty($request->case_date) ? date2Display($request->case_date, "Y") : myDate('Y');
        $caseNumStr = Num2Unicode($request->case_number)."/". Num2Unicode(date2Display($request->case_date_entry,'y')) . "/វរ";

        DB::beginTransaction();
        try{
            /**
             * 1. Insert/Update Company Info
             * 2. Insert Collectives Case
             * 3. Upload All Reference Files
             * 4. Insert CaseCompany
             * 5. Update Or Create Collectives Representatives & Issues
             * 6. Assign Officer
             */

            /** ===============1. Create Or Update Company (tbl_company) =================== */
            $searchCompany = ["company_name_khmer" => $company_name_khmer];
            $adataComInfo = [
                "company_id_lacms" => $request->company_id,
                "company_option" => $company_option,
                "company_name_latin" => $request->company_name_latin,
                "first_business_act" => $request->first_business_act,
                "article_of_company" => $request->article_of_company,
                "company_type_id" => $request->company_type_id,
                "sector_id" => $request->sector_id,
                "company_phone_number" => $request->company_phone_number,
                "company_phone_number2" => $request->company_phone_number2,
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
                "village_id" => $request->village_id,
                "commune_id" => $request->commune_id,
                "district_id" => $request->district_id,
                "province_id" => $request->province_id,

                "user_created" => Auth::user()->id,
                "user_updated" => Auth::user()->id,
                "date_created" =>  $date_created,
                "date_updated" =>  $date_created,
            ];

//            dd($adataComInfo);
            $insertedCompany = Company::updateOrCreate($searchCompany, $adataComInfo);
            $comID = !empty($insertedCompany) ? $insertedCompany->id : 0;
            Company::where("id", $comID)->update(["company_id" => $comID]);

//            dd($comID);

            /** ===============2. Create Collectives Case (tbl_case) ======================== */
            $adataCollectivesCase = [
                "case_number" => $request->case_number,
                "case_num_str" => $caseNumStr,
                "case_type_id" => $request->case_type_id,
//                "disputant_id" => 0, //
                "company_id" => $comID,
                //"company_option" => $request->case_type_id,
                "company_type_id" => $request->company_type_id,
                "sector_id" => $request->sector_id,
                "business_activity" => $request->business_activity,
//                "case_objective_id" => $request->case_objective_id,
//                "case_ojective_other" => $request->case_ojective_other,
//                "terminated_contract_date" => date2DB($request->terminated_contract_date),
//                "terminated_contract_time" => $request->terminated_contract_time,
//                "case_objective_des" => $request->case_objective_des,
//                "disputant_sdate_work" => date2DB($request->disputant_sdate_work),
//                "disputant_contract_type" => $request->disputant_contract_type,
//                "disputant_work_hour_day" => $request->disputant_work_hour_day,
//                "disputant_work_hour_week" => $request->disputant_work_hour_week,
//                "disputant_salary" => $request->disputant_salary,
//                "disputant_night_work" => $request->disputant_night_work,
//                "disputant_holiday_week" => $request->disputant_holiday_week,
//                "disputant_holiday_year" => $request->disputant_holiday_year,
//                "case_first_reason" => $request->case_first_reason,
//                "disputant_terminated_contract" => $request->disputant_terminated_contract,
//                "disputant_request" => $request->disputant_request,
                "case_date" => date2DB($request->case_date),
                "case_date_entry" => date2DB($request->case_date_entry),
                "collectives_cause_id" => $request->collectives_cause_id,
                "collectives_officer_rank" => $request->collectives_officer_rank,
                "collectives_order_letter_num" => $request->collectives_order_letter_num,
                "collectives_assigned_officer_date" => date2DB($request->collectives_assigned_officer_date),

                "user_created" => Auth::user()->id,
                "date_created" =>  $date_created,
            ];
//            dd($adataCollectivesCase);
            $insertedCollectivesCase = Cases::create($adataCollectivesCase);
            $cCaseID = $insertedCollectivesCase->id; //get Collectives Case ID
//            dd($insertedCollectivesCase);
//            dd($cCaseID);

            /** ===============3. Upload All Reference File */
            $path_to_upload = pathToUploadFile("case_doc/collectives/".$caseYear."/");
            $orderLetterFile = myUploadFileOnly($request, $path_to_upload, "collectives_order_letter_file", $cCaseID, "collectives_order_letter");
            $collectivesFile = myUploadFileOnly($request, $path_to_upload, "collectives_case_file", $cCaseID, "collectives_case");
            $otherFile = myUploadFileOnly($request, $path_to_upload, "collectives_other_file", $cCaseID, "collectives_other");

//            dd($orderLetterFile);

            //Update Collectives Case table for All Reference Files
            Cases::where("id", $cCaseID)->update([
                "collectives_order_letter_file" => $orderLetterFile,
                "collectives_case_file" => $collectivesFile,
                "collectives_other_file" => $otherFile,
            ]);

            /** ===============4. Insert new Record to tbl_case_company ======================== */
            $adataCaseCompany = [
                "case_id" => $cCaseID,
                "company_id" => $comID,
                "log5_article_of_company" => $request->article_of_company,
                "log5_company_type_id" => $request->company_type_id,
                "log5_sector_id" => $request->sector_id,
                "log5_business_activity" => $request->business_activity,
                "log5_business_activity1" => $request->business_activity1,
                "log5_business_activity2" => $request->business_activity2,
                "log5_business_activity3" => $request->business_activity3,
                "log5_business_activity4" => $request->business_activity4,
                "log5_company_phone_number" => $request->company_phone_number,
                "log5_company_phone_number2" => $request->company_phone_number2,
                "log5_building_no" => $request->building_no,
                "log5_street_no" => $request->street_no,
                "log5_village_id" => $request->village_id,
                "log5_commune_id" => $request->commune_id,
                "log5_district_id" => $request->district_id,
                "log5_province_id" => $request->province_id,

                "user_created" => Auth::user()->id,
                "date_created" =>  $date_created,
            ];
//            dd($adataCaseCompany);
            //print_r($adata);
            $result = CaseCompany::create($adataCaseCompany);
//            dd($result);

            /**================5. Update Or Create Collectives Representatives & Issues ===========*/
            $this->updateOrCreateCollectivesRepresentatives($cCaseID, $request->repID, $request->repName);
            $this->updateOrCreateCollectivesIssues($cCaseID, $request->issueID, $request->issues);

            /** ===============6. Assigning Officer ======================== */
            updateOrCreateOfficer($cCaseID, $request->officer_id, 6);
//            $this->updateOrCreateOfficer($case_id, $request->officer_id8, 8);


            DB::commit();
            if(request("json_opt") == 1){ //if request from app
                //$data = getDataForAllMenu($inspection_id, $this->menu);
                //return response()->json(['status' => 200, 'message' => 'success', 'data'=> $data]);
            }
            return redirect("collective_cases");

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
        $data['pagetitle']= "ដំណើរការបណ្ដឹងវិាទការងាររួម";
        $data['cases'] = Cases::where("id", $id)->first();
        $caseDomain = getCaseDomainControl($id);
        $data['lastOfficer'] = getLastOfficer($id, 6);
        $data['officerInDomain'] = arrayOfficerCaseInHandByDomainCtrl($caseDomain);
        $view = "case.collectives.show_collective_case";
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
        $data['case'] = Cases::where("id", $id)->first();
        $view = "case.collectives.update_collective_case";
        if(request("json_opt") == 1){ //if request from app
            return response()->json(['status' => 200, 'message' => 'success', 'data'=> $data]);
        }
        return view($view, [ "adata" => $data ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TelegramService $telegramService, string $id)
    {
//        dd($request->all());


        $date_updated = myDateTime();
        $cCaseID = $id;
        $caseYear = $request->case_year;
        $caseNumStr = Num2Unicode($request->case_number)."/". Num2Unicode(date2Display($request->case_date_entry,'y')) . "/វរ";

        DB::beginTransaction();
        try{
            /**
             * 1. Upload All Reference Files
             * 2. Update Collectives Case
             * 3. Update CaseCompany
             * 4. Update Or Create Collectives Representatives & Issues
             * 5. Assign Officer
             */

            /** ===============1. Upload All Reference File */
            $path_to_upload = pathToUploadFile("case_doc/collectives/".$caseYear."/");

            //Order Letter File
            $orderLetterFileNew = myUploadFileOnly($request, $path_to_upload, "collectives_order_letter_file", $cCaseID, "collectives_order_letter");
            $orderLetterFile = !empty($orderLetterFileNew)? $orderLetterFileNew : $request->order_letter_file_old;

            //Collectives File
            $collectivesFileNew = myUploadFileOnly($request, $path_to_upload, "collectives_case_file", $cCaseID, "collectives_case");
            $collectivesFile = !empty($collectivesFileNew)? $collectivesFileNew : $request->collectives_file_old;

            //Collectives Other Files
            $otherFileNew = myUploadFileOnly($request, $path_to_upload, "collectives_other_file", $cCaseID, "collectives_other");
            $otherFile = !empty($otherFileNew)? $otherFileNew : $request->other_file_old;




            /** ===============2. Update Collectives Case (tbl_case) ======================== */
            $adataCollectivesCase = [
                "case_number" => $request->case_number,
                "case_num_str" => $caseNumStr,
                "case_type_id" => $request->case_type_id,
//                "disputant_id" => 0, //
//                "company_id" => $request->company_id,
                //"company_option" => $request->case_type_id,
                "company_type_id" => $request->company_type_id,
                "sector_id" => $request->sector_id,
                "business_activity" => $request->business_activity,
                "case_date" => date2DB($request->case_date),
                "case_date_entry" => date2DB($request->case_date_entry),
                "collectives_cause_id" => $request->collectives_cause_id,
                "collectives_officer_rank" => $request->collectives_officer_rank,
                "collectives_order_letter_num" => $request->collectives_order_letter_num,
                "collectives_assigned_officer_date" => date2DB($request->collectives_assigned_officer_date),

                "collectives_order_letter_file" => $orderLetterFile,
                "collectives_case_file" => $collectivesFile,
                "collectives_other_file" => $otherFile,

                "user_updated" => Auth::user()->id,
                "date_updated" =>  $date_updated,
            ];
//            dd($adataCollectivesCase);
            Cases::where("id", $cCaseID)->update($adataCollectivesCase); // return 1 if update success

            /** ===============3. Insert new Record to tbl_case_company ======================== */
            $adataCaseCompany = [
                "log5_article_of_company" => $request->article_of_company,
                "log5_company_type_id" => $request->company_type_id,
                "log5_sector_id" => $request->sector_id,
                "log5_business_activity" => $request->business_activity,
                "log5_business_activity1" => $request->business_activity1,
                "log5_business_activity2" => $request->business_activity2,
                "log5_business_activity3" => $request->business_activity3,
                "log5_business_activity4" => $request->business_activity4,
                "log5_company_phone_number" => $request->company_phone_number,
                "log5_company_phone_number2" => $request->company_phone_number2,
                "log5_building_no" => $request->building_no,
                "log5_street_no" => $request->street_no,
                "log5_village_id" => $request->village_id,
                "log5_commune_id" => $request->commune_id,
                "log5_district_id" => $request->district_id,
                "log5_province_id" => $request->province_id,

                "user_updated" => Auth::user()->id,
                "date_updated" =>  $date_updated,
            ];
//            dd($adataCaseCompany);
            CaseCompany::where("case_id", $cCaseID)->update($adataCaseCompany);
//            dd("I'm Here");
//            dd($request->issues);
            /**================4. Update Or Create Collectives Representatives & Issues ===========*/
            $this->updateOrCreateCollectivesRepresentatives($cCaseID, $request->repID, $request->repName);
            $this->updateOrCreateCollectivesIssues($cCaseID, $request->issueID, $request->issues);


            /** ===============5. Assigning Officer ======================== */
            updateOrCreateOfficer($cCaseID, $request->officer_id, 6);

            DB::commit();
            if(request("json_opt") == 1){ //if request from app
                //$data = getDataForAllMenu($inspection_id, $this->menu);
                //return response()->json(['status' => 200, 'message' => 'success', 'data'=> $data]);
            }
            $message2Telegram = "Hello Kru \n". myDateTime(). " Success";
            $telegramService->sendMessage($message2Telegram);
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

        $cCase = Cases::where("id", $id)->first();
        $caseYear = !empty($cCase->case_date) ? date2Display($cCase->case_date, "Y") : myDate('Y');
        $pathToDelete = pathToUploadFile("case_doc/collectives/".$caseYear."/");

        $orderLetter = $cCase->collectives_order_letter_file;
        $collectivesCaseFile = $cCase->collectives_case_file;
        $collectivesOtherFile = $cCase->collectives_other_file;

        DB::beginTransaction();
        try{
            /**  Delete All References Files */
            deleteFile($orderLetter, $pathToDelete);
            deleteFile($collectivesCaseFile, $pathToDelete);
            deleteFile($collectivesOtherFile, $pathToDelete);

            /** Delete Joint Case: tbl_joint_disute : 1 record */
            Cases::where("id", $id)->delete();


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

    /** Delete Collectives Representatives */
    function deleteCollectivesRepresentative($id){
        $tmp = explode("_", $id);
        $repreID = $tmp[0];
        $cCaseID = $tmp[1];
        CollectivesRepresentatives::where('id', $repreID)->delete();
        return redirect("collective_cases/".$cCaseID."/edit")->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
    }

    /** Delete Collectives Issue */
    function deleteCollectivesIssue($id){
        $tmp = explode("_", $id);
        $issueID = $tmp[0];
        $cCaseID = $tmp[1];
        CollectivesIssues::where('id', $issueID)->delete();
        return redirect("collective_cases/".$cCaseID."/edit")->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
    }

    /**
     * Update Or Create Collective Representatives
     * @param $caseID
     * @param $repreID
     * @param $repreName
     * @return void
     */
    private function updateOrCreateCollectivesRepresentatives($caseID, $repreID, $repreName){
//        dd($repreName);
        if(!empty($repreName)){
            $cUserID = Auth::id();
            $cDateTime = myDateTime();
            foreach($repreName as $key => $val){
                if (!empty($repreName[$key])) {
                    $search = [
                        "id" => $repreID[$key],
                        "case_id" => $caseID
                    ];
                    $adata = [
                        "fullname" => $repreName[$key],
                        "user_updated" => $cUserID,
                        "date_updated" => $cDateTime,
                    ];
//                    dd($search);
                    $result = CollectivesRepresentatives::updateOrCreate($search, $adata);
                    if ($result->wasRecentlyCreated) {
                        $result->update([
                            "user_created" => $cUserID,
                            "date_created" => $cDateTime,
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Update Or Create Collectives Issues
     * @param $caseID
     * @param $issueID
     * @param $issue
     * @return void
     */
    private function updateOrCreateCollectivesIssues($caseID, $issueID, $issue){
//        dd($repreName);
        if(!empty($issue)){
            $cUserID = Auth::id();
            $cDateTime = myDateTime();
            foreach($issue as $key => $val){
                if (!empty($issue[$key])) {
                    $search = [
                        "id" => $issueID[$key],
                        "case_id" => $caseID
                    ];
                    $adata = [
                        "issue" => $issue[$key],
                        "user_updated" => $cUserID,
                        "date_updated" => $cDateTime,
                    ];
//                    dd($search);
                    $result = CollectivesIssues::updateOrCreate($search, $adata);
                    if ($result->wasRecentlyCreated) {
                        $result->update([
                            "user_created" => $cUserID,
                            "date_created" => $cDateTime,
                        ]);
                    }
                }
            }
        }
    }
}
