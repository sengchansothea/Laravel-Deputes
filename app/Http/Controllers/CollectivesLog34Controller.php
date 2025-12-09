<?php

namespace App\Http\Controllers;

use App\Models\CaseDisputant;
use App\Models\CaseInvitation;
use App\Models\CaseLog;
use App\Models\CaseLog34;
use App\Models\CaseLogAttendant;
use App\Models\Cases;
use App\Models\CollectivesLog34Issues;
use App\Models\Disputant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CollectivesLog34Controller extends Controller
{

    /** Delete Collectives Log34 Issue */
    function deleteCollectivesLog34Issue($id){
        $tmp = explode("_", $id);
        $issueID = $tmp[0];
        $cLog34ID = $tmp[1];
//        dd($tmp);
        CollectivesLog34Issues::where('id', $issueID)->delete();
        return redirect("collectives_log34/".$cLog34ID."/edit")->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
    }

    function deleteCollectivesLog34Attendant($id){
        $tmp = explode("_", $id);
        $caseID = $tmp[0];
        $cLogID = $tmp[1];
        $disputantID = $tmp[2];
        $attendantTypeID = $tmp[3];
        $log34ID = $tmp[4];
//        dd($tmp);

        DB::beginTransaction();
        try{
            $arrCaseLogAttendant = [
                'case_id' => $caseID,
                'log_id' => $cLogID,
                'attendant_id' => $disputantID,
                'attendant_type_id' => $attendantTypeID,
            ];
            CaseLogAttendant::where($arrCaseLogAttendant)->delete();

            DB::commit();
            if(request("json_opt") == 1){ //if request from app
                //return response()->json(['status' => 200, 'message' => 'success', 'data'=> $data]);
            }
            return redirect("collectives_log34/".$log34ID."/edit")->with("message", sweetalert()->addSuccess("ជោគជ័យ"));

        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return back()->with("message", sweetalert()->addWarning("បរាជ័យ"));
        }
    }



    function deleteCollectivesRepresentative($id){
        $tmp = explode("_", $id);
        $caseID = $tmp[0];
        $cLogID = $tmp[1];
        $disputantID = $tmp[2];
        $attendantTypeID = $tmp[3];
        $log34ID = $tmp[4];
//        dd($tmp);

        // Delete Collective Representative From tbl_case_disputant
        $arrCaseDisputant = [
            'case_id' => $caseID,
            'disputant_id' => $disputantID,
            'attendant_type_id' => $attendantTypeID,
        ];

        $arrCaseLogAttendant = [
            'case_id' => $caseID,
            'log_id' => $cLogID,
            'attendant_id' => $disputantID,
            'attendant_type_id' => $attendantTypeID,
        ];
        CaseDisputant::where($arrCaseDisputant)->delete();
        CaseLogAttendant::where($arrCaseLogAttendant)->delete();

        return redirect("collectives_log34/".$log34ID."/edit")->with("message", sweetalert()->addSuccess("ជោគជ័យ"));

    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    public function uploadFile(Request $request)
    {
//        dd($request->all());
        $request->validate([
            'file' => 'required|mimes:jpeg,png,jpg,gif,pdf|max:5148', // Max 5MB image size
        ]);
        $file = $request->file('file');
        $fileName = $request->id."_". time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path(pathToUploadFile('case_doc/log34')), $fileName); //public_path()
        CaseLog34::where("id", $request->id)->update([
            "log_file" => $fileName
        ]);
//        /dd($fileName);
        return response()->json(['message' => 'Upload ជោគជ័យ']);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create($caseID = 0, $invID = 0)
    {
        $case = Cases::where('id', $caseID)->first();
        $title = "បង្កើតកំណត់ហេតុសាកសួរព័ត៌មានកម្មករ";
        $data['pagetitle']= $title;
        $data['case'] = $case;
        $data['case_id'] = $caseID;
        $data['case_type_id'] = $case->case_type_id;
        $data['invitation_id'] = $invID;
        $view = "case.log.log34.create_collectives_log34";
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
        $caseID = $request->case_id;

        /** ================== 1.Insert Case Log in tbl_case_log ======== */
        $result = CaseLog::create([
            "case_id" => $caseID,
            "log_type_id" => 3
        ]);
        $logID = !empty($result) ? $result->id : 0;

        /** ================== 2.Insert/Update collectives disputant in tbl_disputant, insert in tbl_case_disputant, and insert/update in tbl_case_log_attendant for collectives disputant (employee) ===== */
        if(!empty($request->collectives_emp_name) && !empty($request->collectives_emp_gender) && !empty($request->collectives_emp_nationality) && !empty($request->collectives_phone_number)){
            /** ================== 2a.Insert/Update collectives disputant in tbl_disputant ======== */
            if(!empty($request->collectives_id_number)){
                $searchCollectivesDisputant = ["id_number" => $request->collectives_id_number];
                $adataCollectivesDisputant = [
                    "name" => $request->collectives_emp_name,
                    "gender" => $request->collectives_emp_gender,
                    "dob" => date2DB($request->collectives_emp_dob),
                    "nationality" => $request->collectives_emp_nationality,
//                    "id_number" => $request->id_number,
                    "phone_number" => $request->collectives_phone_number,
                    "phone_number2" => $request->collectives_phone2_number,
                    "occupation" => $request->collectives_emp_occupation,
                    "house_no" => $request->collectives_emp_house_no,
                    "street" => $request->collectives_emp_street_no,
                    "village" => $request->collectives_emp_vil_id,
                    "commune" => $request->collectives_emp_com_id,
                    "district" => $request->collectives_emp_dis_id,
                    "province" => $request->collectives_emp_pro_id,

                    "pob_commune_id" => $request->collectives_emp_nationality == 33 ? $request->collectives_pob_commune_id : 0,
                    "pob_district_id" => $request->collectives_emp_nationality == 33 ? $request->collectives_emp_pob_dis_id : 0,
                    "pob_province_id" => $request->collectives_emp_nationality == 33 ? $request->collectives_emp_pob_pro_id : 0,
//                    "pob_country_id" => $request->collectives_emp_nationality == 33 ? 0 : $request->pob_country_id,


                    "user_created" => Auth::user()->id,
                    "user_updated" => Auth::user()->id,
                    "date_created" =>  $date_created,
                    "date_updated" =>  $date_created,
                ];
            }else{
                $searchCollectivesDisputant = [
                    "name" => $request->collectives_emp_name,
                    "dob" => date2DB($request->collectives_emp_dob),
                    "phone_number" => $request->collectives_phone_number,
                ];
                $adataCollectivesDisputant = [
//                    "name" => $request->name,
                    "gender" => $request->collectives_emp_gender,
//                    "dob" => date2DB($request->dob),
                    "nationality" => $request->collectives_emp_nationality,
//                    "id_number" => $request->id_number,
                    "phone_number2" => $request->collectives_phone2_number,
                    "occupation" => $request->collectives_emp_occupation,
                    "house_no" => $request->collectives_emp_house_no,
                    "street" => $request->collectives_emp_street_no,
//                    "group_name" => $request->group_name,
                    "village" => $request->collectives_emp_vil_id,
                    "commune" => $request->collectives_emp_com_id,
                    "district" => $request->collectives_emp_dis_id,
                    "province" => $request->collectives_emp_pro_id,

                    "pob_commune_id" => $request->collectives_emp_nationality == 33 ? $request->pob_commune_id : 0,
                    "pob_district_id" => $request->collectives_emp_nationality == 33 ? $request->pob_district_id : 0,
                    "pob_province_id" => $request->collectives_emp_nationality == 33 ? $request->pob_province_id : 0,
//                    "pob_country_id" => $request->nationality == 33 ? 0 : $request->pob_country_id,

                    "user_created" => Auth::user()->id,
                    "user_updated" => Auth::user()->id,
                    "date_created" =>  $date_created,
                    "date_updated" =>  $date_created,
                ];
            }
//            dd($searchDisputant);
            $result = Disputant::updateOrCreate($searchCollectivesDisputant, $adataCollectivesDisputant);
//            dd($result);
            $collectivesDisputantID = !empty($result)? $result->id : 0;//get collectives disputant ID

            /** ================== 2b.Insert/Update Case Disputant in tbl_case_disputant ============ */
            $searchCaseDisputant = [
                "case_id" => $caseID,
                "disputant_id" => $collectivesDisputantID,
                "attendant_type_id" => 1,
            ];
            $adataCaseDisputant = [
                "house_no" => $request->collectives_emp_house_no,
                "street" => $request->collectives_emp_street_no,
                "village" => $request->collectives_emp_vil_id,
                "commune" => $request->collectives_emp_com_id,
                "district" => $request->collectives_emp_dis_id,
                "province" => $request->collectives_emp_pro_id,
                "phone_number" => $request->collectives_phone_number,
                "phone_number2" => $request->collectives_phone2_number,
                "occupation" => $request->collectives_emp_occupation,

                "user_created" => Auth::user()->id,
                "date_created" =>  $date_created,
            ];
            //dd($adataCaseDisputant);
            CaseDisputant::updateOrCreate($searchCaseDisputant, $adataCaseDisputant);

            /** ================== 2c.Insert/Update in tbl_case_log_attendant for main Disputant ======== */
            insertUpdateCaseLogAttendant($caseID, $logID, $collectivesDisputantID, 1); // ដើមបណ្តឹង
        }


        /** ================== 3.Insert/Update in tbl_case_log_attendant for officers (Head Meeting & Noter) ======== */
        insertUpdateCaseLogAttendant($caseID, $logID, $request->head_meeting, 6); // Head Meeting
        insertUpdateCaseLogAttendant($caseID, $logID, $request->noter, 8); // Noter

        /** ================== 4.Insert/Update collectives sub representative in tbl_disputant, insert in tbl_case_disputant, and insert/update in tbl_case_log_attendant for collectives sub representative (employee) ===== */
        if(!empty($request->name) && !empty($request->gender) && !empty($request->nationality) && !empty($request->phone_number)){
            /** ================== 4a.Insert/Update Disputant in tbl_disputant ============ */
            if(!empty($request->id_number)){
                $searchCollectivesSubDisputant = ["id_number" => $request->id_number];
                $adataCollectivesSubDisputant = [
                    "name" => $request->name,
                    "gender" => $request->gender,
                    "dob" => date2DB($request->dob),
                    "nationality" => $request->nationality,
//                    "id_number" => $request->id_number,
                    "phone_number" => $request->phone_number,
                    "phone_number2" => $request->phone2_number,
                    "occupation" => $request->occupation,
                    "house_no" => $request->addr_house_no,
                    "street" => $request->addr_street,
                    "village" => $request->village,
                    "commune" => $request->commune,
                    "district" => $request->district,
                    "province" => $request->province,

                    "pob_commune_id" => $request->nationality == 33 ? $request->pob_commune_id : 0,
                    "pob_district_id" => $request->nationality == 33 ? $request->pob_district_id : 0,
                    "pob_province_id" => $request->nationality == 33 ? $request->pob_province_id : 0,
//                    "pob_country_id" => $request->collectives_emp_nationality == 33 ? 0 : $request->pob_country_id,


                    "user_created" => Auth::user()->id,
                    "user_updated" => Auth::user()->id,
                    "date_created" =>  $date_created,
                    "date_updated" =>  $date_created,
                ];
            }else{
                $searchCollectivesSubDisputant = [
                    "name" => $request->name,
                    "dob" => date2DB($request->dob),
                    "phone_number" => $request->phone_number,
                ];
                $adataCollectivesSubDisputant = [
                    "gender" => $request->gender,
                    "nationality" => $request->nationality,
                    "phone_number" => $request->phone2_number,
                    "occupation" => $request->occupation,
                    "house_no" => $request->addr_house_no,
                    "street" => $request->addr_street,
                    "village" => $request->village,
                    "commune" => $request->commune,
                    "district" => $request->district,
                    "province" => $request->province,

                    "pob_commune_id" => $request->nationality == 33 ? $request->pob_commune_id : 0,
                    "pob_district_id" => $request->nationality == 33 ? $request->pob_district_id : 0,
                    "pob_province_id" => $request->nationality == 33 ? $request->pob_province_id : 0,
//                    "pob_country_id" => $request->collectives_emp_nationality == 33 ? 0 : $request->pob_country_id,

                    "user_created" => Auth::user()->id,
                    "user_updated" => Auth::user()->id,
                    "date_created" =>  $date_created,
                    "date_updated" =>  $date_created,
                ];

            }
//            dd($searchDisputant);
            $result = Disputant::updateOrCreate($searchCollectivesSubDisputant, $adataCollectivesSubDisputant);
//            dd($result);
            $collectivesSubDisputantID = !empty($result)? $result->id : 0;//get collectives disputant ID

            /** ================== 4b.Insert/Update Case Disputant in tbl_case_disputant ============ */
            /** ចំពោះអ្នកអមតំណាងកម្មករនិយោជិត អត់ចាំបាច់ Add ចូល Case Disputant ឡើយ */
//                $searchCaseSubDisputant = [
//                    "case_id" => $caseID,
//                    "disputant_id" => $collectivesSubDisputantID,
//                    "attendant_type_id" => 2, //អមដើមបណ្តឹង
//                ];
//                $adataCaseSubDisputant = [
//                    "house_no" => $request->addr_house_no,
//                    "street" => $request->addr_street,
//                    "village" => $request->village,
//                    "commune" => $request->commune,
//                    "district" => $request->district,
//                    "province" => $request->province,
//                    "phone_number" => $request->phone_number,
//                    "occupation" => $request->occupation,
//
//                    "user_created" => Auth::user()->id,
//                    "date_created" =>  $date_created,
//                ];
//                //dd($adataCaseDisputant);
//                CaseDisputant::updateOrCreate($searchCaseSubDisputant ,$adataCaseSubDisputant);

            /** ================== 4c.Insert/Update in tbl_case_log_attendant for main Disputant ======== */
            insertUpdateCaseLogAttendant($caseID, $logID, $collectivesSubDisputantID, 2); // អមដើមបណ្តឹង
        }


        /** ================== 5.Insert Data in Log34 (tbl_case_log34) =================== */
        $adata = [
            "case_id" => $caseID,
            "log_id" => $logID,
            "meeting_date" => date2DB($request->meeting_date),
            "meeting_stime" => $request->meeting_stime,
            "meeting_etime" => $request->meeting_etime,
            "invitation_id" => $request->invitation_id,
            "disputant_give_info" => $request->disputant_give_info,
            "collectives_head_meeting_comment" => $request->collectives_head_meeting_comment,
            "collectives_representatives_comment" => $request->collectives_representatives_comment,

            "user_created" => Auth::user()->id,
            "date_created" =>  $date_created,
        ];
        //dd($adata);
        CaseLog34::create($adata);
//            dd($result);

        /** ================== 6.Insert All Collectives Issues In Log34 (tbl_collectives_log34_representatives) =================== */
        $this->updateOrCreateCollectivesIssues($request->issueID, $caseID, $logID, $request->issues);

        DB::beginTransaction();
        try{


            DB::commit();
            if(request("json_opt") == 1){ //if request from app
                //return response()->json(['status' => 200, 'message' => 'success', 'data'=> $data]);
            }
            return redirect("collective_cases/".$caseID)->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
            //return redirect("log34/".$id."/edit")->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return back()->with("message", sweetalert()->addWarning("បរាជ័យ"));
        }
    }

    private function updateOrCreateCollectivesIssues($issueID, $caseID, $logID , $issue){
//        dd($repreName);
        if(!empty($issue)){
            $cUserID = Auth::id();
            $cDateTime = myDateTime();
            foreach($issue as $key => $val){
                if (!empty($issue[$key])) {
                    $search = [
                        'id' => $issueID[$key],
                        'case_id' => $caseID,
                        'log_id' => $logID
                    ];
                    $adata = [
                        "issue" => $issue[$key],
                        "user_updated" => $cUserID,
                        "date_updated" => $cDateTime,
                    ];
//                    dd($search);
                    $result = CollectivesLog34Issues::updateOrCreate($search, $adata);
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
        $data['pagetitle']= "<span class='text-danger'>កែប្រែកំណត់ហេតុសាកសួរព័ត៌មានកម្មករ</span>";
        $log34 = CaseLog34::where("id", $id)->first();
        $data['log34'] = $log34;
        $data['case'] = $log34->case;
        $data['case_id'] = $log34->case->id;
        $data['head_meeting'] = $log34->headMeeting;
        $data['log34_noter'] = $log34->noter;
        $caseTypeID = $log34->case->case_type_id;
        $data['sub_disputant'] = CaseLogAttendant::where("case_id", $log34->case->id)
                            ->where("log_id", $log34->log_id)
                            ->where("attendant_type_id", 2) // 2: អ្នកអមដើមបណ្តឹង
                            ->get();

        $view = "case.log.log34.update_collectives_log34";
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
        //dd($request->input());
        $date_created = myDateTime();
        $caseID =$request->case_id;
        $logID = $request->log_id;



        DB::beginTransaction();
        try{

            /** ================== 1.Update Data in Log34 (tbl_case_log34) =================== */
            $adata = [
                //"case_id" => $case_id,
                //"log_id" => $log_id,
                "meeting_date" => date2DB($request->meeting_date),
                "meeting_stime" => $request->meeting_stime,
                "meeting_etime" => $request->meeting_etime,
                //"invitation_id" => $request->invitation_id,
                "disputant_give_info" => $request->disputant_give_info,
                "collectives_head_meeting_comment" => $request->collectives_head_meeting_comment,
                "collectives_representatives_comment" => $request->collectives_representatives_comment,
                "user_updated" => Auth::user()->id,
                "date_updated" =>  $date_created,
            ];
//            dd($adata);
            CaseLog34::where('id', $id)->update($adata); // return 1 if update success

            /** ================== 2.Update in tbl_case_log_attendant for officers (Head Meeting & Noter) ======== */
            insertUpdateCaseOfficer($caseID, $logID, $request->head_meeting, 6); // Head Meeting
            insertUpdateCaseOfficer($caseID, $logID, $request->noter, 8); // Noter

            /** ================== 3.Insert/Update collectives representative in tbl_disputant, insert in tbl_case_disputant, and insert/update in tbl_case_log_attendant for collectives disputant (employee) ===== */
            if(!empty($request->collectives_emp_name) && !empty($request->collectives_emp_gender) && !empty($request->collectives_emp_nationality) && !empty($request->collectives_phone_number)){
                if(!empty($request->collectives_id_number)){
                    $searchCollectivesDisputant = ["id_number" => $request->collectives_id_number];
                    $adataCollectivesDisputant = [
                        "name" => $request->collectives_emp_name,
                        "gender" => $request->collectives_emp_gender,
                        "dob" => date2DB($request->collectives_emp_dob),
                        "nationality" => $request->collectives_emp_nationality,
//                    "id_number" => $request->id_number,
                        "phone_number" => $request->collectives_phone_number,
                        "phone_number2" => $request->collectives_phone2_number,
                        "occupation" => $request->collectives_emp_occupation,
                        "house_no" => $request->collectives_emp_house_no,
                        "street" => $request->collectives_emp_street_no,
                        "village" => $request->collectives_emp_vil_id,
                        "commune" => $request->collectives_emp_com_id,
                        "district" => $request->collectives_emp_dis_id,
                        "province" => $request->collectives_emp_pro_id,

                        "pob_commune_id" => $request->collectives_emp_nationality == 33 ? $request->collectives_pob_commune_id : 0,
                        "pob_district_id" => $request->collectives_emp_nationality == 33 ? $request->collectives_emp_pob_dis_id : 0,
                        "pob_province_id" => $request->collectives_emp_nationality == 33 ? $request->collectives_emp_pob_pro_id : 0,
//                    "pob_country_id" => $request->collectives_emp_nationality == 33 ? 0 : $request->pob_country_id,

                        "user_created" => Auth::user()->id,
                        "user_updated" => Auth::user()->id,
                        "date_created" =>  $date_created,
                        "date_updated" =>  $date_created,
                    ];
                }else{
                    $searchCollectivesDisputant = [
                        "name" => $request->collectives_emp_name,
                        "dob" => date2DB($request->collectives_emp_dob),
                        "phone_number" => $request->collectives_phone_number,
                    ];
                    $adataCollectivesDisputant = [
//                    "name" => $request->name,
                        "gender" => $request->collectives_emp_gender,
//                    "dob" => date2DB($request->dob),
                        "nationality" => $request->collectives_emp_nationality,
//                    "id_number" => $request->id_number,
                        "phone_number2" => $request->collectives_phone2_number,
                        "occupation" => $request->collectives_emp_occupation,
                        "house_no" => $request->collectives_emp_house_no,
                        "street" => $request->collectives_emp_street_no,
//                    "group_name" => $request->group_name,
                        "village" => $request->collectives_emp_vil_id,
                        "commune" => $request->collectives_emp_com_id,
                        "district" => $request->collectives_emp_dis_id,
                        "province" => $request->collectives_emp_pro_id,

                        "pob_commune_id" => $request->collectives_emp_nationality == 33 ? $request->pob_commune_id : 0,
                        "pob_district_id" => $request->collectives_emp_nationality == 33 ? $request->pob_district_id : 0,
                        "pob_province_id" => $request->collectives_emp_nationality == 33 ? $request->pob_province_id : 0,
//                    "pob_country_id" => $request->nationality == 33 ? 0 : $request->pob_country_id,

                        "user_created" => Auth::user()->id,
                        "user_updated" => Auth::user()->id,
                        "date_created" =>  $date_created,
                        "date_updated" =>  $date_created,
                    ];

                }
//            dd($searchDisputant);
                $result = Disputant::updateOrCreate($searchCollectivesDisputant, $adataCollectivesDisputant);
//            dd($result);
                $collectivesDisputantID = !empty($result)? $result->id : 0;//get collectives disputant ID

                /** ================== 3a.Insert Case Disputant in tbl_case_disputant ============ */
                $searchCaseDisputant = [
                    "case_id" => $caseID,
                    "disputant_id" => $collectivesDisputantID,
                    "attendant_type_id" => 1,
                ];
                $adataCaseDisputant = [
                    "house_no" => $request->collectives_emp_house_no,
                    "street" => $request->collectives_emp_street_no,
                    "village" => $request->collectives_emp_vil_id,
                    "commune" => $request->collectives_emp_com_id,
                    "district" => $request->collectives_emp_dis_id,
                    "province" => $request->collectives_emp_pro_id,
                    "phone_number" => $request->collectives_phone_number,
                    "phone_number2" => $request->collectives_phone2_number,
                    "occupation" => $request->collectives_emp_occupation,

                    "user_created" => Auth::user()->id,
                    "date_created" =>  $date_created,
                ];
                //dd($adataCaseDisputant);
                CaseDisputant::updateOrCreate($searchCaseDisputant, $adataCaseDisputant);

                /** ================== 3b.Insert/Update in tbl_case_log_attendant for Collectives Representatives Disputant ======== */
                insertUpdateCaseLogAttendant($caseID, $logID, $collectivesDisputantID, 1); //ដើមបណ្តឹង
            }


            /** ================== 4.Insert/Update collectives sub representative in tbl_disputant, insert in tbl_case_disputant, and insert/update in tbl_case_log_attendant for collectives sub representative (employee) ===== */
            if(!empty($request->name) && !empty($request->gender) && !empty($request->nationality) && !empty($request->phone_number)){
                /** ================== 4a.Insert/Update Disputant in tbl_disputant ============ */
                if(!empty($request->id_number)){
                    $searchCollectivesSubDisputant = ["id_number" => $request->id_number];
                    $adataCollectivesSubDisputant = [
                        "name" => $request->name,
                        "gender" => $request->gender,
                        "dob" => date2DB($request->dob),
                        "nationality" => $request->nationality,
//                    "id_number" => $request->id_number,
                        "phone_number" => $request->phone_number,
                        "phone_number2" => $request->phone2_number,
                        "occupation" => $request->occupation,
                        "house_no" => $request->addr_house_no,
                        "street" => $request->addr_street,
                        "village" => $request->village,
                        "commune" => $request->commune,
                        "district" => $request->district,
                        "province" => $request->province,

                        "pob_commune_id" => $request->nationality == 33 ? $request->pob_commune_id : 0,
                        "pob_district_id" => $request->nationality == 33 ? $request->pob_district_id : 0,
                        "pob_province_id" => $request->nationality == 33 ? $request->pob_province_id : 0,
//                    "pob_country_id" => $request->collectives_emp_nationality == 33 ? 0 : $request->pob_country_id,


                        "user_created" => Auth::user()->id,
                        "user_updated" => Auth::user()->id,
                        "date_created" =>  $date_created,
                        "date_updated" =>  $date_created,
                    ];
                }else{
                    $searchCollectivesSubDisputant = [
                        "name" => $request->name,
                        "dob" => date2DB($request->dob),
                        "phone_number" => $request->phone_number,
                    ];
                    $adataCollectivesSubDisputant = [
                        "gender" => $request->gender,
                        "nationality" => $request->nationality,
                        "phone_number2" => $request->phone2_number,
                        "occupation" => $request->occupation,
                        "house_no" => $request->addr_house_no,
                        "street" => $request->addr_street,
                        "village" => $request->village,
                        "commune" => $request->commune,
                        "district" => $request->district,
                        "province" => $request->province,

                        "pob_commune_id" => $request->nationality == 33 ? $request->pob_commune_id : 0,
                        "pob_district_id" => $request->nationality == 33 ? $request->pob_district_id : 0,
                        "pob_province_id" => $request->nationality == 33 ? $request->pob_province_id : 0,
//                    "pob_country_id" => $request->collectives_emp_nationality == 33 ? 0 : $request->pob_country_id,

                        "user_created" => Auth::user()->id,
                        "user_updated" => Auth::user()->id,
                        "date_created" =>  $date_created,
                        "date_updated" =>  $date_created,
                    ];

                }
//            dd($searchDisputant);
                $result = Disputant::updateOrCreate($searchCollectivesSubDisputant, $adataCollectivesSubDisputant);
//            dd($result);
                $collectivesSubDisputantID = !empty($result)? $result->id : 0;//get collectives disputant ID

                /** ================== 4b.Insert/Update Case Disputant in tbl_case_disputant ============ */
                /** ចំពោះអ្នកអមតំណាងកម្មករនិយោជិត អត់ចាំបាច់ Add ចូល Case Disputant ឡើយ */
//                $searchCaseSubDisputant = [
//                    "case_id" => $caseID,
//                    "disputant_id" => $collectivesSubDisputantID,
//                    "attendant_type_id" => 2, //អមដើមបណ្តឹង
//                ];
//                $adataCaseSubDisputant = [
//                    "house_no" => $request->addr_house_no,
//                    "street" => $request->addr_street,
//                    "village" => $request->village,
//                    "commune" => $request->commune,
//                    "district" => $request->district,
//                    "province" => $request->province,
//                    "phone_number" => $request->phone_number,
//                    "occupation" => $request->occupation,
//
//                    "user_created" => Auth::user()->id,
//                    "date_created" =>  $date_created,
//                ];
//                //dd($adataCaseDisputant);
//                CaseDisputant::updateOrCreate($searchCaseSubDisputant ,$adataCaseSubDisputant);

                /** ================== 4c.Insert/Update in tbl_case_log_attendant for main Disputant ======== */
                insertUpdateCaseLogAttendant($caseID, $logID, $collectivesSubDisputantID, 2); // អមដើមបណ្តឹង
            }

            /** ================== 5.Insert/Update collectives other representative in tbl_disputant, insert in tbl_case_disputant, and insert/update in tbl_case_log_attendant for collectives other representative (employee) ===== */
            if(!empty($request->name_other) && !empty($request->gender_other) && !empty($request->nationality_other) && !empty($request->phone_number_other)){
                /** ================== 5a.Insert/Update Disputant in tbl_disputant ============ */
                if(!empty($request->id_number_other)){
                    $searchCollectivesOtherDisputant = ["id_number" => $request->id_number_other];
                    $adataCollectivesOtherDisputant = [
                        "name" => $request->name_other,
                        "gender" => $request->gender_other,
                        "dob" => date2DB($request->dob_other),
                        "nationality" => $request->nationality_other,
//                    "id_number" => $request->id_number,
                        "phone_number" => $request->phone_number_other,
                        "phone_number2" => $request->phone2_number_other,
                        "occupation" => $request->occupation_other,
                        "house_no" => $request->addr_house_no_other,
                        "street" => $request->addr_street_other,
                        "village" => $request->village_other,
                        "commune" => $request->commune_other,
                        "district" => $request->district_other,
                        "province" => $request->province_other,

                        "pob_commune_id" => $request->nationality_other == 33 ? $request->pob_commune_id_other : 0,
                        "pob_district_id" => $request->nationality_other == 33 ? $request->pob_district_id_other : 0,
                        "pob_province_id" => $request->nationality_other == 33 ? $request->pob_province_id_other : 0,
//                    "pob_country_id" => $request->collectives_emp_nationality == 33 ? 0 : $request->pob_country_id,


                        "user_created" => Auth::user()->id,
                        "user_updated" => Auth::user()->id,
                        "date_created" =>  $date_created,
                        "date_updated" =>  $date_created,
                    ];
                }else{
                    $searchCollectivesOtherDisputant = [
                        "name" => $request->name_other,
                        "dob" => date2DB($request->dob_other),
                        "phone_number" => $request->phone_number_other,
                    ];
                    $adataCollectivesOtherDisputant = [
                        "gender" => $request->gender_other,
                        "nationality" => $request->nationality_other,
                        "phone_number2" => $request->phone2_number_other,
                        "occupation" => $request->occupation_other,
                        "house_no" => $request->addr_house_no_other,
                        "street" => $request->addr_street_other,
                        "village" => $request->village_other,
                        "commune" => $request->commune_other,
                        "district" => $request->district_other,
                        "province" => $request->province_other,

                        "pob_commune_id" => $request->nationality_other == 33 ? $request->pob_commune_id_other : 0,
                        "pob_district_id" => $request->nationality_other == 33 ? $request->pob_district_id_other : 0,
                        "pob_province_id" => $request->nationality_other == 33 ? $request->pob_province_id_other : 0,
//                    "pob_country_id" => $request->collectives_emp_nationality == 33 ? 0 : $request->pob_country_id,

                        "user_created" => Auth::user()->id,
                        "user_updated" => Auth::user()->id,
                        "date_created" =>  $date_created,
                        "date_updated" =>  $date_created,
                    ];

                }
//            dd($searchDisputant);
                $result = Disputant::updateOrCreate($searchCollectivesOtherDisputant, $adataCollectivesOtherDisputant);
//            dd($result);
                $collectivesOtherDisputantID = !empty($result)? $result->id : 0;//get collectives disputant ID

                /** ================== 5b.Insert/Update Case Disputant in tbl_case_disputant ============ */
                /** គ្រាន់តែមានវត្តមាននៅក្នុង Log34 តែអត់ត្រូវ Add ចូល CaseDisputant ទេ */
//                $searchCaseOtherDisputant = [
//                    "case_id" => $caseID,
//                    "disputant_id" => $collectivesOtherDisputantID,
//                    "attendant_type_id" => 2, //អមដើមបណ្តឹង
//                ];
//                $adataCaseOtherDisputant = [
//                    "house_no" => $request->addr_house_no_other,
//                    "street" => $request->addr_street_other,
//                    "village" => $request->village_other,
//                    "commune" => $request->commune_other,
//                    "district" => $request->district_other,
//                    "province" => $request->province_other,
//                    "phone_number" => $request->phone_number_other,
//                    "occupation" => $request->occupation_other,
//
//                    "user_created" => Auth::user()->id,
//                    "date_created" =>  $date_created,
//                ];
//                //dd($adataCaseDisputant);
//                CaseDisputant::updateOrCreate($searchCaseOtherDisputant ,$adataCaseOtherDisputant);

                /** ================== 5c.Insert/Update in tbl_case_log_attendant for main Disputant ======== */
                insertUpdateCaseLogAttendant($caseID, $logID, $collectivesOtherDisputantID, 1); // ដើមបណ្តឹង
            }

            /** ================== 6.Insert/Update All Collectives Issues In Log34 (tbl_collectives_log34_representatives) =================== */
            $this->updateOrCreateCollectivesIssues($request->issueID, $caseID, $logID, $request->issues);


            DB::commit();
            if(request("json_opt") == 1){ //if request from app
                //return response()->json(['status' => 200, 'message' => 'success', 'data'=> $data]);
            }

            return saveCollectiveShowCaseRedirect($request->input("btnSubmit"), $request->input("case_id"));
            //return back()->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
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
        //dd($id);
        $caseLog34 = CaseLog34::where('id', $id)->select('id', 'case_id', 'log_id')->first();
        $caseID = $caseLog34->case_id;
        $case = Cases::find($caseID);
        $logID = $caseLog34->log_id;
        $caseYear = !empty($case->case_date) ? date2Display($case->case_date, "Y") : myDate('Y');

//        dd($caseLog34);

        DB::beginTransaction();
        try{
            /** 1.Delete Case Log in tbl_case_log ===================== */
            CaseLog::where("id", $logID)->where("case_id", $caseID)->delete();
            /** 2.Delete Case Disputant in tbl_case_disputant ===================== */
            CaseDisputant::where("case_id", $caseID)->delete();
            /** 3.Delete Case Log Attendant in tbl_case_log_attendant ======== */
            CaseLogAttendant::where('log_id', $logID)->where("case_id", $caseID)->delete();
            /** 4.Delete Case Log34 in tbl_case_log34 ======== */
            if(!empty($caseLog34)){
                //Delete Log34 record
                $caseLog34->delete();

                //Delete Log34 file
                deleteFile($caseLog34->log_file, pathToUploadFile("case_doc/collectives/log34/".$caseYear."/"));//delete invitation_file
            }
            DB::commit();
            if(request("json_opt") == 1){ //if request from app
                //$data = getDataForAllMenu($inspection_id, $this->menu);
                //return response()->json(['status' => 200, 'message' => 'success', 'data'=> $data]);
            }
            return redirect("collective_cases/".$caseID)->with("message", sweetalert()->addSuccess("ជោគជ័យ"));

        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return back()->with("message", sweetalert()->addWarning("បរាជ័យ"));
        }
    }
}
