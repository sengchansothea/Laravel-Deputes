<?php

namespace App\Http\Controllers;

use App\Models\CaseCompany;
use App\Models\CaseLog;
use App\Models\CaseLog5;
use App\Models\CaseLog6;
use App\Models\CaseLog620;
use App\Models\CaseLog621;
use App\Models\CaseLogAttendant;
use App\Models\Cases;
use App\Models\Company;
use App\Models\Disputant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Log6Controller extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function reopenInsert(Request $request)
    {
        dd($request->all());
        $request->validate([
            'file' => 'mimes:jpeg,png,jpg,gif,pdf|max:5148', // Max 5MB image size
        ]);
        $file = $request->file('file');
        $fileName = $request->id."_status_letter".'.' . $file->getClientOriginalExtension();

        $file->move(public_path(pathToUploadFile('case_doc/log6/status_letter')), $fileName); //public_path()
        CaseLog6::where("id", $request->id)->update([
            "reopen_status" => 1,
            "status_date" => date2DB($request->status_date),
            "status_time" => $request->status_time,
            "status_letter" => $fileName
        ]);
        return response()->json(['message' => 'Upload ជោគជ័យ']);
    }
    public function reopenUpdate(Request $request)
    {
        //dd($request->all());
        CaseLog6::where("id", $request->id)->update([
            "reopen_status" => 1,
            "status_date" => date2DB($request->status_date),
            "status_time" => $request->status_time,
        ]);
        return response()->json(['message' => 'Upload ជោគជ័យ']);
    }
    public function reopenUploadFile(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'file' => 'mimes:jpeg,png,jpg,gif,pdf|max:5148', // Max 5MB image size
        ]);
        $file = $request->file('file');
        $fileName = $request->id."_status_letter". '.' . $file->getClientOriginalExtension();
        $file->move(public_path(pathToUploadFile('case_doc/log6/status_letter')), $fileName); //public_path()
        CaseLog6::where("id", $request->id)->update([
            "status_letter" => $fileName
        ]);

        return response()->json(['message' => 'Upload ជោគជ័យ']);
    }
    public function reopenRequestCancel($caseID, $log6ID)
    {
        DB::beginTransaction();
        try{
            $log6 =  CaseLog6::where("id", $log6ID)->first();
            $caseYear = !empty($log6->case->case_date) ? date2Display($log6->case->case_date,'Y') : myDate('Y');
            $status_letter = $log6->status_letter;
            if(!empty($status_letter)){
                //dd($status_letter);
                deleteFile($status_letter, pathToDeleteFile("case_doc/log6/status_letter/".$caseYear."/"));//delete file
            }
            $log6->status_date = null;
            $log6->reopen_status = null;
            $log6->status_time = null;
            $log6->status_letter = null;
            $log6->save();

            //        CaseLog6::where("id", $log6_id)->update([
            //            "reopen_status" => 0,
            //            "status_date" => null,
            //            "status_time" => null,
            //            "status_letter" => null
            //        ]);

            DB::commit();
            if(request("json_opt") == 1){ //if request from app
                //$data = getDataForAllMenu($inspection_id, $this->menu);
                //return response()->json(['status' => 200, 'message' => 'success', 'data'=> $data]);
            }
            return redirect("cases/".$caseID)->with("message", sweetalert()->addSuccess("ជោគជ័យ"));

        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return back()->with("message", sweetalert()->addWarning("បរាជ័យ"));
        }



    }
    public function uploadFile(Request $request)
    {
        //dd($request->all());

        $status_letter =$request->status_letter_old;
        if ($request->hasFile('file')) {
            $request->validate([
                'file' => 'mimes:jpeg,png,jpg,gif,pdf|max:5148', // Max 5MB image size
            ]);
            $file = $request->file('file');
            $fileName = $request->id."_". time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path(pathToUploadFile('case_doc/log6')), $fileName); //public_path()
            $status_letter = $fileName;
        }
        CaseLog6::where("id", $request->id)->update([
        //            "reopen_status" => 1,
        //            "status_date" => date2DB($request->status_date),
        //            "status_time" => $request->status_time,
            "log_file" => $status_letter
        ]);

        return response()->json(['message' => 'Upload ជោគជ័យ']);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create($case_id = 0, $invitation_id_employee = 0, $invitaion_id_company = 0)
    {

        $case = Cases::where("id", $case_id)->first();
        $case_type_id = $case->case_type_id;
        $title = "បង្កើតកំណត់ហេតុផ្សះផ្សា";
        $data['pagetitle']= $title;
        $data['case'] = $case;
        $data['case_id'] = $case_id;
        $data['case_type_id'] = $case_type_id;
        $data['invitation_id_employee'] = $invitation_id_employee;
        $data['invitation_id_company'] = $invitaion_id_company;
        $view = "case.log.log6.create_log6";
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
        //dd($request->input());
        $date_created = myDateTime();
        $case_id = $request->case_id;
        $arrayAttendantType = getArrayAttendantType($request->case_type_id);

        DB::beginTransaction();
        try{

            /** ================== 1.Insert Case Log6 in tbl_case_log ======== */
            $result = CaseLog::create([
                "case_id" => $case_id,
                "log_type_id" => 6
            ]);
            $log_id = !empty($result)? $result->id : 0;

            /** =============== Upload File: translator_letter ======================== */
            $path_to_upload = pathToUploadFile("case_doc/log6");
            //        $translator_letter = uploadFileOnly($request, $path_to_upload, "translator_letter", $log_id);
            $translator_letter = myUploadFileOnly($request, $path_to_upload, "translator_letter", $log_id, "translator_letter");
            /** ================== 2.Insert Log6 Data in tbl_case_log6 =================== */
            $log6_employee_status = 1;
            $log6_company_status = 2;
            if($result->case_type_id == 1){
                $log6_employee_status = 1;
                $log6_company_status = 2;
            }
            elseif($request->case_type_id == 2){
                $log6_employee_status = 2;
                $log6_company_status = 1;
            }
            $adataLog6 = [
                "type_id" => 1,
                "status_id" => $request->status_id,
                "case_id" => $case_id,
                "log_id" => $log_id,
                "log6_date" => date2DB($request->log6_date),
                "log6_stime" => $request->log6_stime,
                "log6_etime" => $request->log6_etime,
                "invitation_id_employee" => $request->invitation_id_employee,
                "invitation_id_company" => $request->invitation_id_company,

                "log6_meeting_place_id" => $request->log6_meeting_place_id,
                "log6_meeting_other" => $request->log6_meeting_other,
                "log6_meeting_about" => $request->log6_meeting_about,
                "log6_employee_status" => $log6_employee_status,
                "log6_company_status" => $log6_company_status,

                "log6_17" => $request->log6_17,
                "log6_181"  => $request->log6_181,
                "log6_182"  => $request->log6_182,
                "log6_19"  => $request->log6_19,
                "log6_19a"  => $request->log6_19a,
                //20, 21 in sub table
                "log6_22"  => $request->log6_22,
                "translator"  => $request->translator,
                "translator_letter" => $translator_letter,
                "log624_cause_id"  => $request->log624_cause_id,
                "log624_cause_other"  => $request->log624_cause_id == 11 ? $request->log624_cause_other : "",
                "log625_solution_id"  => $request->log625_solution_id,

                "log6_comment"  => $request->log6_comment,
                "log6_contact"  => $request->log6_contact,

                "user_created" => Auth::user()->id,
                "date_created" =>  $date_created,
            ];
            //dd($adataLog6);
            $result = CaseLog6::create($adataLog6);
            $id = !empty($result)? $result->id : 0;

            /** ================== 3.Insert Log6 Sub: log6_20 in tbl_case_log6_20agree ========== */
            $this->insertOrUpdateLog620($case_id, $log_id, $request->log620_id, $request->log620_agree_point, $request->log620_solution);

            /** ================== 4.Insert Log6 Sub: log6_21 in tbl_case_log6_21disagree ========== */
            $this->insertOrUpdateLog621($case_id, $log_id, $request->log621_id, $request->log621_disagree_point, $request->log621_solution);

            /** ================== 5.Insert Main Employee in tbl_case_log_attendant ====== */
            insertUpdateCaseLogAttendant($case_id, $log_id, $request->disputant_id, $arrayAttendantType["employee_main"]);//3
            /** ================== 6.Insert/Update Employee Sub in tbl_disputant, tbl_case_disputant ====== */
            //dd($request->sub_employee_id_number);
            foreach($request->sub_employee_id_number as $key => $val){
                $disputant_id = insertUpdateDisputantAll(
                    $request->sub_employee_name[$key],
                    $request->sub_employee_gender[$key],
                    $request->sub_employee_dob[$key],
                    $request->sub_employee_nationality[$key],
                    $request->sub_employee_id_number[$key],
                    $request->sub_employee_phone_number[$key],
                    $request->sub_employee_phone2_number[$key],
                    $request->sub_employee_occupation[$key],

                    $request->sub_employee_addr_house_no[$key],
                    $request->sub_employee_addr_street[$key],

                    isset($request->sub_employee_village[$key])? $request->sub_employee_village[$key]:0,
                    isset($request->sub_employee_commune[$key])? $request->sub_employee_commune[$key]:0,
                    isset($request->sub_employee_district[$key])? $request->sub_employee_district[$key]:0,
                    $request->sub_employee_province[$key],

                    isset($request->sub_employee_pob_commune_id[$key])? $request->sub_employee_pob_commune_id[$key]:0,
                    isset($request->sub_employee_pob_district_id[$key])? $request->sub_employee_pob_district_id[$key]:0,
                    $request->sub_employee_pob_province_id[$key],
                );//1
                insertUpdateCaseDisputant(
                    $request->case_id,
                    $disputant_id,
                    $arrayAttendantType['employee_sub'],

                    $request->sub_employee_addr_house_no[$key],
                    $request->sub_employee_addr_street[$key],
                    isset($request->sub_employee_village[$key])? $request->sub_employee_village[$key]:0,
                    isset($request->sub_employee_commune[$key])? $request->sub_employee_commune[$key]:0,
                    isset($request->sub_employee_district[$key])? $request->sub_employee_district[$key]:0,
                    $request->sub_employee_province[$key],
                    $request->sub_employee_phone_number[$key],
                    $request->sub_employee_phone2_number[$key],
                    $request->sub_employee_occupation[$key],
                );//2
                insertUpdateCaseLogAttendant($case_id, $log_id, $disputant_id, $arrayAttendantType["employee_sub"]);//3
            }

            /** ================== 6.Insert/Update Represent Company in tbl_disputant, tbl_case_disputant ====== */
            $disputant_id = insertUpdateDisputantAll(
                $request->represent_company_name,
                $request->represent_company_gender,
                $request->represent_company_dob,
                $request->represent_company_nationality,
                $request->represent_company_id_number,
                $request->represent_company_phone_number,
                $request->represent_company_phone2_number,
                $request->represent_company_occupation,

                $request->represent_company_addr_house_no,
                $request->represent_company_addr_street,
                isset($request->represent_company_village)? $request->represent_company_village:0,
                isset($request->represent_company_commune)? $request->represent_company_commune:0,
                isset($request->represent_company_district)? $request->represent_company_district:0,
                $request->represent_company_province,

                isset($request->represent_company_pob_commune_id[$key])? $request->represent_company_pob_commune_id[$key]:0,
                isset($request->represent_company_pob_district_id[$key])? $request->represent_company_pob_district_id[$key]:0,
                $request->represent_company_pob_province_id,
            );//1
            insertUpdateCaseDisputant(
                $request->case_id,
                $disputant_id,
                $arrayAttendantType['company_main'],

                $request->represent_company_addr_house_no,
                $request->represent_company_addr_street,
                isset($request->represent_company_village)? $request->represent_company_village:0,
                isset($request->represent_company_commune)? $request->represent_company_commune:0,
                isset($request->represent_company_district)? $request->represent_company_district:0,
                $request->represent_company_province,
                $request->represent_company_phone_number,
                $request->represent_company_phone2_number,
                $request->represent_company_occupation,
            );//2
            insertUpdateCaseLogAttendant($case_id, $log_id, $disputant_id, $arrayAttendantType["company_main"]);//3
            //dd($disputant_id);
            /** ================== 7.Insert/Update Sub Represent Company in tbl_disputant, tbl_case_disputant ====== */
            //dd( $request->sub_company_gender);
            foreach($request->sub_company_id_number as $key => $val){
                //dd( $request->sub_company_gender[$key]);
                $disputant_id = insertUpdateDisputantAll(
                    $request->sub_company_name[$key],
                    $request->sub_company_gender[$key],
                    $request->sub_company_dob[$key],
                    $request->sub_company_nationality[$key],
                    $request->sub_company_id_number[$key],
                    $request->sub_company_phone_number[$key],
                    $request->sub_company_phone2_number[$key],
                    $request->sub_company_occupation[$key],

                    $request->sub_company_addr_house_no[$key],
                    $request->sub_company_addr_street[$key],

                    isset($request->sub_company_village[$key])? $request->sub_company_village[$key]:0,
                    isset($request->sub_company_commune[$key])? $request->sub_company_commune[$key]:0,
                    isset($request->sub_company_district[$key])? $request->sub_company_district[$key]:0,
                    $request->sub_company_province[$key],

                    isset($request->sub_company_pob_commune_id[$key])? $request->sub_company_pob_commune_id[$key]:0,
                    isset($request->sub_company_pob_district_id[$key])? $request->sub_company_pob_district_id[$key]:0,
                    $request->sub_company_pob_province_id[$key],
                );//1
                insertUpdateCaseDisputant(
                    $request->case_id,
                    $disputant_id,
                    $arrayAttendantType['company_sub'],

                    $request->sub_company_addr_house_no[$key],
                    $request->sub_company_addr_street[$key],
                    isset($request->sub_company_village[$key])? $request->sub_company_village[$key]:0,
                    isset($request->sub_company_commune[$key])? $request->sub_company_commune[$key]:0,
                    isset($request->sub_company_district[$key])? $request->sub_company_district[$key]:0,
                    $request->sub_company_province[$key],
                    $request->sub_company_phone_number[$key],
                    $request->sub_company_phone2_number[$key],
                    $request->sub_company_occupation[$key],
                );//2
                insertUpdateCaseLogAttendant($case_id, $log_id, $disputant_id, $arrayAttendantType["company_sub"]);//3
            }

            /** ================== 8.Insert/Update Officer Attendant in tbl_case_log_attendant ====== */
            $attendant_type_id = 6;//Officer: Head of Meeting
            insertUpdateCaseLogAttendant($case_id, $log_id, $request->head_meeting, $attendant_type_id);
            $attendant_type_id = 8;//Officer: Noter
            insertUpdateCaseLogAttendant($case_id, $log_id, $request->noter, $attendant_type_id);

            /** ================== 9.Insert/Update Sub Officer Attendant in tbl_case_log_attendant ====== */
            $attendant_type_id = 7;//Sub Officer
            foreach($request->sub_officer as $key => $val){
                insertUpdateCaseLogAttendant($case_id, $log_id, $request->sub_officer[$key], $attendant_type_id);
            }

            $msgTitle = 'បានបង្កើតកំណត់ហេតុផ្សះផ្សា';
            $currentCase = Cases::find($request->case_id);
            caseStatusTelegramNotification($currentCase, $msgTitle);

            DB::commit();
            if(request("json_opt") == 1){ //if request from app
                //return response()->json(['status' => 200, 'message' => 'success', 'data'=> $data]);
            }
            return redirect("cases/".$request->case_id)->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
            //return redirect("log6/".$id."/edit")->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return back()->with("message", sweetalert()->addWarning("បរាជ័យ"));
        }
    }

    /** Insert Or Update Log620 */
    private function insertOrUpdateLog620($caseID, $logID, $log620ID, $log620AgreePoint, $log620Solution)
    {
        $dateCreated = myDateTime();
        if(!empty($log620AgreePoint)){
            $cUserID = Auth::id();
            $cDateTime = myDateTime();
            foreach($log620AgreePoint as $key => $val){
                if (!empty($log620AgreePoint[$key]) && !empty($log620Solution[$key])) {
                    $log620Search = [
                        'id' => $log620ID[$key]
                    ];
                    $log620Data = [
                        "case_id" => $caseID,
                        "log_id" => $logID,
                        "agree_point" => $log620AgreePoint[$key],
                        "solution" => $log620Solution[$key],
                        "user_created" => Auth::user()->id,
                        "date_created" =>  $dateCreated,
                    ];

        //                    dd($search);
                    $result = CaseLog620::updateOrCreate($log620Search, $log620Data);
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


    /** Insert Or Update Log621 */
    private function insertOrUpdateLog621($caseID, $logID, $log621ID , $log621DisagreePoint, $log621Solution)
    {
        $dateCreated = myDateTime();
        if(!empty($log621DisagreePoint)){
            $cUserID = Auth::id();
            $cDateTime = myDateTime();
            foreach($log621DisagreePoint as $key => $val){
                if (!empty($log621DisagreePoint[$key]) && !empty($log621Solution[$key])) {
                    $log621Search = [
                        'id' => $log621ID[$key]
                    ];
                    $log621Data = [
                        "case_id" => $caseID,
                        "log_id" => $logID,
                        "disagree_point" => $log621DisagreePoint[$key],
                        "solution" => $log621Solution[$key],
                        "user_created" => Auth::user()->id,
                        "date_created" =>  $dateCreated,
                    ];

            //                    dd($search);
                    $result = CaseLog621::updateOrCreate($log621Search, $log621Data);
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
    private function insertLog620($case_id, $log_id, $log620_agree_point, $log20_solution)
    {
        $date_created = myDateTime();
        foreach($log620_agree_point as $key => $val){
            if($log620_agree_point[$key] != ""){
                $adata = [
                    "case_id" => $case_id,
                    "log_id" => $log_id,
                    "agree_point" => $log620_agree_point[$key],
                    "solution" => $log20_solution[$key],
                    "user_created" => Auth::user()->id,
                    "date_created" =>  $date_created,
                ];
                //dd($adataSubDisputant);
                CaseLog620::create($adata);
            }
        }
    }
    private function insertLog621($case_id, $log_id, $log621_disagree_point, $log621_solution)
    {
        $date_created = myDateTime();
        foreach($log621_disagree_point as $key => $val){
            if($log621_disagree_point[$key] != ""){
                $adata = [
                    "case_id" => $case_id,
                    "log_id" => $log_id,
                    "disagree_point" => $log621_disagree_point[$key],
                    "solution" => $log621_solution[$key],
                    "user_created" => Auth::user()->id,
                    "date_created" =>  $date_created,
                ];
                //dd($adataSubDisputant);
                CaseLog621::create($adata);
            }
        }
    }

    private function updateLog620($log620_id, $log620_agree_point, $log620_solution)
    {
        $date_created = myDateTime();
        if(!empty($log620_agree_point)){
            foreach($log620_agree_point as $key => $val){
                if($log620_agree_point[$key] != ""){
                    $adata = [
                        "agree_point" => $log620_agree_point[$key],
                        "solution" => $log620_solution[$key],
                        "user_updated" => Auth::user()->id,
                        "date_updated" =>  $date_created,
                    ];
                    //dd($adataSubDisputant);
                    CaseLog620::where("id", $log620_id[$key])->update($adata);
                }
            }
        }

    }
    private function updateLog621($log621_id, $log621_disagree_point, $log621_solution)
    {
        $date_created = myDateTime();
        if(!empty($log621_disagree_point)){
            foreach($log621_disagree_point as $key => $val){
                if($log621_disagree_point[$key] != ""){
                    $adata = [
                        "disagree_point" => $log621_disagree_point[$key],
                        "solution" => $log621_solution[$key],
                        "user_updated" => Auth::user()->id,
                        "date_updated" =>  $date_created,
                    ];
                    //dd($adataSubDisputant);
                    CaseLog621::where("id", $log621_id[$key])->update($adata);
                }
            }
        }

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
        $data['pagetitle']= "<span class='text-danger'>កែប្រែកំណត់ហេតុផ្សះផ្សា</span>";
        $log6 = CaseLog6::where("id", $id)->first();
        $caseTypeID = $log6->case->case_type_id;
        $arrayAttendantType = getArrayAttendantType($caseTypeID);
        $data['conflict_officer'] = $log6->conflictOfficer;
        $data['conflict_noter'] = $log6->conflictNoter;
        $data['log6'] = $log6;
        $data['case'] = $log6->case;
        $data['case_id'] = $log6->case->id;

        $data['employee_sub'] = CaseLogAttendant::where("case_id", $log6->case->id)
            ->where("log_id", $log6->log_id)->where("attendant_type_id", $arrayAttendantType['employee_sub'] )
            ->get();
        $data['company_main'] = CaseLogAttendant::where("case_id", $log6->case->id)
            ->where("log_id", $log6->log_id)->where("attendant_type_id", $arrayAttendantType['company_main'] )
            ->get();
        $data['company_sub'] = CaseLogAttendant::where("case_id", $log6->case->id)
            ->where("log_id", $log6->log_id)->where("attendant_type_id", $arrayAttendantType['company_sub'] )
            ->get();

        $data['sub_officer'] = CaseLogAttendant::where("case_id", $log6->case->id)
            ->where("log_id", $log6->log_id)->where("attendant_type_id", 7)
            ->get();

        $view = "case.log.log6.update_log6";
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

        $date_created = myDateTime();
        $case_id = $request->case_id;
        $log_id = $request->log_id;
        $log6_id = $id;
        $caseYear = $request->case_year;
        $arrayAttendantType = getArrayAttendantType($request->case_type_id);


        DB::beginTransaction();
        try{

            /** ================== 1.Update Log6 Data in tbl_case_log6 and Upload File =================== */

            /** =============== Upload File: translator_letter ======================== */
            $path_to_upload = pathToUploadFile("case_doc/log6");
            //        $translator_letter_new = uploadFileOnly($request, $path_to_upload, "translator_letter", $log_id);
            $translator_letter_new = myUploadFileOnly($request, $path_to_upload, "translator_letter", $log_id, "translator");
            //dd($translator_letter_new);
            $translator_letter = !empty($translator_letter_new)? $translator_letter_new : $request->translator_letter_old;
            $log6_employee_status = 1;
            $log6_company_status = 2;
            //        if($result->case_type_id == 1){
            //            $log6_employee_status = 1;
            //            $log6_company_status = 2;
            //        }
            //        elseif($request->case_type_id == 2){
            //            $log6_employee_status = 2;
            //            $log6_company_status = 1;
            //        }
            if($request->radio_status_id == 1 || $request->radio_status_id == 2){
                //dd($request->radio_status_id);
                $reopen_status = 0;
                $status_date = null;
                $status_time = null;
                $status_letter = null;
                deleteFile($request->status_letter_old, pathToDeleteFile("case_doc/log6/status_letter/".$caseYear."/"));//delete file
            }
            //        elseif($request->radio_status_id == 2){
            //            //dd($request->radio_status_id);
            //            $reopen_status = !empty($request->reopen_status)? $request->reopen_status : 0;
            //            $status_date = null;
            //            $status_time = null;
            //            $status_letter = null;
            //            if($reopen_status == 1){
            //                $status_date = date2DB($request->status_date);
            //                $path_to_upload = pathToUploadFile("case_doc/log6/status_letter/");
            //                $status_letter_new = uploadFileOnly($request, $path_to_upload, "status_letter", $log6_id, "status_letter");
            //                $status_letter = !empty($status_letter_new)? $status_letter_new : $request->status_letter_old;
            //            }
            //            //dd($status_letter);
            //        }
            elseif($request->radio_status_id == 3){
                //dd($request->radio_status_id);
                $path_to_upload = pathToUploadFile("case_doc/log6/status_letter/".$caseYear."/");
                $reopen_status = 0;
                $status_date = date2DB($request->status_date);
                $status_time = $request->status_time;
            //            $status_letter_new = uploadFileOnly($request, $path_to_upload, "status_letter", $log6_id, "status_letter");
                $status_letter_new = myUploadFileOnly($request, $path_to_upload, "status_letter", $log6_id, "status_letter");
                $status_letter = !empty($status_letter_new)? $status_letter_new : $request->status_letter_old;
                //dd($status_letter);
            }

            $adataLog6 = [
                //"case_id" => $case_id,
                //"log_id" => $log_id,
                "reopen_status" => $reopen_status,
                "status_id" => $request->radio_status_id,
                "status_date" => $status_date,
                "status_time" => $status_time,
                "status_letter" => $status_letter,

                "log6_date" => date2DB($request->log6_date),
                "log6_stime" => $request->log6_stime,
                "log6_etime" => $request->log6_etime,
                "invitation_id_employee" => $request->invitation_id_employee,
                "invitation_id_company" => $request->invitation_id_company,

                "log6_meeting_place_id" => $request->log6_meeting_place_id,
                "log6_meeting_other" => $request->log6_meeting_other,
                "log6_meeting_about" => $request->log6_meeting_about,
                //"log6_employee_status" => $log6_employee_status,
                //"log6_company_status" => $log6_company_status,

                "log6_17" => $request->log6_17,
                "log6_181"  => $request->log6_181,
                "log6_182"  => $request->log6_182,
                "log6_19"  => $request->log6_19,
                "log6_19a"  => $request->log6_19a,
                //20, 21 in sub table
                "log6_22"  => $request->log6_22,
                "translator"  => $request->translator,
                "translator_letter" => $translator_letter,
                "log624_cause_id"  => $request->log624_cause_id,
                "log624_cause_other"  => $request->log624_cause_id == 11 ? $request->log624_cause_other: null,
                "log625_solution_id"  => $request->log625_solution_id,

                "log6_comment"  => $request->log6_comment,
                "log6_contact"  => $request->log6_contact,

                "user_updated" => Auth::user()->id,
                "date_updated" =>  $date_created,
            ];
            //dd($adataLog6);
            CaseLog6::where("id", $id)->update($adataLog6);

            /** ================== 2.Insert/Update Log6 Sub: log6_20 in tbl_case_log6_20agree ========== */
            $this->insertOrUpdateLog620($case_id, $log_id, $request->log620_id, $request->log620_agree_point, $request->log620_solution);
            /** ================== 4.Insert/Update Log6 Sub: log6_21 in tbl_case_log6_21disagree ========== */
            $this->insertOrUpdateLog621($case_id, $log_id, $request->log621_id, $request->log621_disagree_point, $request->log621_solution);

            /** ================== 6.Insert/Update Employee Sub in tbl_disputant, tbl_case_disputant ====== */

            foreach($request->sub_employee_id_number as $key => $val){
                $disputant_id = insertUpdateDisputantAll(
                    $request->sub_employee_name[$key],
                    $request->sub_employee_gender[$key],
                    $request->sub_employee_dob[$key],
                    $request->sub_employee_nationality[$key],
                    $request->sub_employee_id_number[$key],
                    $request->sub_employee_phone_number[$key],
                    $request->sub_employee_phone2_number[$key],
                    $request->sub_employee_occupation[$key],

                    $request->sub_employee_addr_house_no[$key],
                    $request->sub_employee_addr_street[$key],

                    isset($request->sub_employee_village[$key])? $request->sub_employee_village[$key]:0,
                    isset($request->sub_employee_commune[$key])? $request->sub_employee_commune[$key]:0,
                    isset($request->sub_employee_district[$key])? $request->sub_employee_district[$key]:0,
                    $request->sub_employee_province[$key],

                    isset($request->sub_employee_pob_commune_id[$key])? $request->sub_employee_pob_commune_id[$key]:0,
                    isset($request->sub_employee_pob_district_id[$key])? $request->sub_employee_pob_district_id[$key]:0,
                    $request->sub_employee_pob_province_id[$key],
                );//1
                insertUpdateCaseDisputant(
                    $request->case_id,
                    $disputant_id,
                    $arrayAttendantType['employee_sub'],

                    $request->sub_employee_addr_house_no[$key],
                    $request->sub_employee_addr_street[$key],

                    isset($request->sub_employee_village[$key])? $request->sub_employee_village[$key]:0,
                    isset($request->sub_employee_commune[$key])? $request->sub_employee_commune[$key]:0,
                    isset($request->sub_employee_district[$key])? $request->sub_employee_district[$key]:0,
                    $request->sub_employee_province[$key],

                    $request->sub_employee_phone_number[$key],
                    $request->sub_employee_phone2_number[$key],
                    $request->sub_employee_occupation[$key],
                );//2
                insertUpdateCaseLogAttendant($case_id, $log_id, $disputant_id, $arrayAttendantType["employee_sub"]);//3
            }

            /** ================== 7.Insert/Update Represent Company in tbl_disputant, tbl_case_disputant ====== */
            $disputant_id = insertUpdateDisputantAll(
                $request->represent_company_name,
                $request->represent_company_gender,
                $request->represent_company_dob,
                $request->represent_company_nationality,
                $request->represent_company_id_number,
                $request->represent_company_phone_number,
                $request->represent_company_phone2_number,
                $request->represent_company_occupation,

                $request->represent_company_addr_house_no,
                $request->represent_company_addr_street,
                isset($request->represent_company_village)? $request->represent_company_village:0,
                isset($request->represent_company_commune)? $request->represent_company_commune:0,
                isset($request->represent_company_district)? $request->represent_company_district:0,
                $request->represent_company_province,

                isset($request->represent_company_pob_commune_id)? $request->represent_company_pob_commune_id:0,
                isset($request->represent_company_pob_district_id)? $request->represent_company_pob_district_id:0,
                $request->represent_company_pob_province_id,
            );//1
            insertUpdateCaseDisputant(
                $request->case_id,
                $disputant_id,
                $arrayAttendantType['company_main'],

                $request->represent_company_addr_house_no,
                $request->represent_company_addr_street,
                isset($request->represent_company_village)? $request->represent_company_village:0,
                isset($request->represent_company_commune)? $request->represent_company_commune:0,
                isset($request->represent_company_district)? $request->represent_company_district:0,
                $request->represent_company_province,
                $request->represent_company_phone_number,
                $request->represent_company_phone2_number,
                $request->represent_company_occupation,
            );//2
            insertUpdateCaseLogAttendant($case_id, $log_id, $disputant_id, $arrayAttendantType["company_main"]);//3
            //dd($disputant_id);
            /** ================== 8.Insert/Update Sub Represent Company in tbl_disputant, tbl_case_disputant ====== */
            foreach($request->sub_company_id_number as $key => $val){
                //dd( $request->sub_company_gender[$key]);
                $disputant_id = insertUpdateDisputantAll(
                    $request->sub_company_name[$key],
                    $request->sub_company_gender[$key],
                    $request->sub_company_dob[$key],
                    $request->sub_company_nationality[$key],
                    $request->sub_company_id_number[$key],
                    $request->sub_company_phone_number[$key],
                    $request->sub_company_phone2_number[$key],
                    $request->sub_company_occupation[$key],

                    $request->sub_company_addr_house_no[$key],
                    $request->sub_company_addr_street[$key],

                    isset($request->sub_company_village[$key])? $request->sub_company_village[$key]:0,
                    isset($request->sub_company_commune[$key])? $request->sub_company_commune[$key]:0,
                    isset($request->sub_company_district[$key])? $request->sub_company_district[$key]:0,
                    $request->sub_company_province[$key],

                    isset($request->sub_company_pob_commune_id[$key])? $request->sub_company_pob_commune_id[$key]:0,
                    isset($request->sub_company_pob_district_id[$key])? $request->sub_company_pob_district_id[$key]:0,
                    $request->sub_company_pob_province_id[$key],
                );//1
                insertUpdateCaseDisputant(
                    $request->case_id,
                    $disputant_id,
                    $arrayAttendantType['company_sub'],

                    $request->sub_company_addr_house_no[$key],
                    $request->sub_company_addr_street[$key],

                    isset($request->sub_company_village[$key])? $request->sub_company_village[$key]:0,
                    isset($request->sub_company_commune[$key])? $request->sub_company_commune[$key]:0,
                    isset($request->sub_company_district[$key])? $request->sub_company_district[$key]:0,
                    $request->sub_company_province[$key],

                    $request->sub_company_phone_number[$key],
                    $request->sub_company_phone2_number[$key],
                    $request->sub_company_occupation[$key],
                );//2
                insertUpdateCaseLogAttendant($case_id, $log_id, $disputant_id, $arrayAttendantType["company_sub"]);//3
            }

            /** ================== 8.Update Officer Attendant in tbl_case_log_attendant ====== */
            $attendantTypeID = 8; //Officer: Noter
            insertUpdateCaseOfficer($case_id, $log_id, $request->noter, $attendantTypeID);

            /** ================== 9.Insert/Update Sub Officer Attendant in tbl_case_log_attendant ====== */
            $attendant_type_id = 7;//Sub Officer
            foreach($request->sub_officer as $key => $val){
                insertUpdateCaseLogAttendant($case_id, $log_id, $request->sub_officer[$key], $attendant_type_id);
            }

            $msgTitle = 'បានកែប្រែកំណត់ហេតុផ្សះផ្សា';
            $currentCase = Cases::find($request->case_id);
            caseStatusTelegramNotification($currentCase, $msgTitle);

            DB::commit();
            if(request("json_opt") == 1){ //if request from app
                //return response()->json(['status' => 200, 'message' => 'success', 'data'=> $data]);
            }
            return saveRedirect($request->input("btnSubmit"), $request->input("case_id"));
            //return redirect("log6/".$id."/edit")->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
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
        $caseLog6 = CaseLog6::where("id", $id)->first();
        $caseID = $caseLog6->case_id;
        $case = Cases::find($caseID);
        $logID = $caseLog6->log_id;
        $caseYear = !empty($case->case_date) ? date2Display($case->case_date, "Y") : myDate('Y');

        DB::beginTransaction();
        try{
            /** 1.Delete Case Log in tbl_case_log ===================== */
            CaseLog::where("id", $logID)->where("case_id", $caseID)->delete();
            /** 2.Delete Case Log Attendant in tbl_case_log_attendant ======== */
            CaseLogAttendant::where("log_id", $logID)->where("case_id", $caseID)->delete();
            /** 3.Delete Log620 in tbl_case_log6_20agree ======== */
            CaseLog620::where("log_id", $logID)->where("case_id", $caseID)->delete();
            /** 4.Delete Log621 in tbl_case_log6_21disagree ======== */
            CaseLog621::where("log_id", $logID)->where("case_id", $caseID)->delete();
            /** 5.Delete Case Log6 in tbl_case_log6 ======== */

            if(!empty($caseLog6)){
                //Delete Log6 record
                $caseLog6->delete();

                //Delete Log6 file
                deleteFile($caseLog6->log_file, pathToUploadFile("case_doc/log6/".$caseYear."/"));//delete invitation_file
            }


            DB::commit();
            if(request("json_opt") == 1){ //if request from app
                //$data = getDataForAllMenu($inspection_id, $this->menu);
                //return response()->json(['status' => 200, 'message' => 'success', 'data'=> $data]);
            }
            return redirect("cases/".$caseID)->with("message", sweetalert()->addSuccess("ជោគជ័យ"));

        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return back()->with("message", sweetalert()->addWarning("បរាជ័យ"));
        }
    }
    function deleteLogAttendant($id){
        //dd($id);
        $tmp = explode("_", $id);
        $id = $tmp[0];
        $logID = $tmp[1];
        CaseLogAttendant::where("id", $id)->delete();
        return redirect("log6/".$logID."/edit")->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
    }
    function deleteLog620($id){
        $tmp = explode("_", $id);
        $id = $tmp[0];
        $logID = $tmp[1];
        CaseLog620::where("id", $id)->delete();
        return redirect("log6/".$logID."/edit")->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
    }
    function deleteLog621($id){
        $tmp = explode("_", $id);
        $id = $tmp[0];
        $logID = $tmp[1];
        CaseLog621::where("id", $id)->delete();
        return redirect("log6/".$logID."/edit")->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
    }
    function generateNewLog($log6ID, $log6StatusID)
    {
        $id = $log6ID;
        if($log6StatusID == 2 || $log6StatusID == 3){
            $oldLog6 = CaseLog6::where("id", $log6ID)->first();
            if($log6StatusID == 2){
                $log6TypeID = 3;
            }
            elseif($log6StatusID == 3){
                $log6TypeID = 2;
            }

            $dateCreated = myDateTime();
            $caseID = $oldLog6->case_id;
            $case = Cases::find($caseID);
            $caseYear = !empty($case->case_date) ? date2Display($case->case_date, "Y") : myDate('Y');
            $caseTypeID = $oldLog6->case->case_type_id;
            $translatorLetter = $oldLog6->translator_letter;
            $arrayAttendantType = getArrayAttendantType($caseTypeID);
            //dd($arrayAttendantType["employee_main"]);

            //            dd($arrayAttendantType);
            /** ================== 1.Insert Case Log6 in tbl_case_log ======== */
            $result = CaseLog::create([
                "case_id" => $caseID,
                "log_type_id" => 6
            ]);
            $logID = !empty($result)? $result->id : 0;
            /** ================= 2.Copy translator_letter file if exists ======================== */
            $newTranslatorLetter = "";
            if(!empty($translator_letter)){
                $pathToUpload = pathToUploadFile("case_doc/log6/".$caseYear);
                $newTranslatorLetter = copyFile($pathToUpload, $translatorLetter, $logID."_".time());
            }
            /** ================= 3.Insert Log6 Data in tbl_case_log6 =================== */
            $newLog6 = $oldLog6->replicate();
            $newLog6->type_id = $log6TypeID;
            $newLog6->status_id = 1;
            $newLog6->log_id = $logID;
            $newLog6->reopen_status = 0;
            $newLog6->status_date = null;
            $newLog6->status_time = null;
            $newLog6->status_letter = null;
            $newLog6->log6_date = myDate();
            $newLog6->log6_stime = myDateTime("H:i");
            $newLog6->log6_etime = null;
            $newLog6->translator_letter = $newTranslatorLetter;
            $newLog6->log_file = null;
            $newLog6->user_created =  Auth::user()->id;
            $newLog6->date_created =  $dateCreated;
            $newLog6->user_updated =  Auth::user()->id;
            $newLog6->date_updated =  $dateCreated;
            //dd($newLog6);
            $newLog6->save();
            $id = !empty($newLog6)? $newLog6->id : 0;
            //dd($newLog6);
            /** ================== 4.Insert Log6 Sub: log6_20 in tbl_case_log6_20agree ========== */
            $log620 = $oldLog6->log620;
            if($log620->count() > 0){
                foreach($log620 as $row){
                    $adataLog620 = [
                        "case_id" => $row->case_id,
                        "log_id" => $logID,
                        "agree_point" => $row->agree_point,
                        "solution" => $row->solution,
                        "user_created" => Auth::user()->id,
                        "date_created" =>  $dateCreated,
                    ];
                    //dd($adataLog620);
                    CaseLog620::create($adataLog620);
                }
            }
            /** ================== 5.Insert Log6 Sub: log6_21 in tbl_case_log6_21disagree ========== */
            $log621 = $oldLog6->log621;
            if($log621->count() > 0){
                foreach($log621 as $row){
                    $adataLog621 = [
                        "case_id" => $row->case_id,
                        "log_id" => $logID,
                        "disagree_point" => $row->disagree_point,
                        "solution" => $row->solution,
                        "user_created" => Auth::user()->id,
                        "date_created" =>  $dateCreated,
                    ];
                    //dd($adataLog621);
                    CaseLog621::create($adataLog621);
                }
            }
            /** ================== 6.Insert All Case Log6 Attendant in tbl_case_log_attendant ====== */
            $allCaseLogAttendant = $oldLog6->attendant;
            //dd($allCaseLogAttendant);
            if($allCaseLogAttendant->count() > 0){
                foreach($allCaseLogAttendant as $row){
                    $adataLogAttendant = [
                        "case_id" => $row->case_id,
                        "log_id" => $logID,
                        "attendant_id" => $row->attendant_id,
                        "attendant_type_id" => $row->attendant_type_id,
                        "user_created" => Auth::user()->id,
                        "date_created" =>  $dateCreated,
                    ];
                    //dd($adataLogAttendant);
                    CaseLogAttendant::create($adataLogAttendant);
                }
            }
        }//end

        DB::beginTransaction();
        try{

            DB::commit();
            if(request("json_opt") == 1){ //if request from app
                //return response()->json(['status' => 200, 'message' => 'success', 'data'=> $data]);
            }
            return redirect("log6/".$id."/edit")->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return back()->with("message", sweetalert()->addWarning("បរាជ័យ"));
        }
    }
}
