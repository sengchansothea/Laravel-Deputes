<?php

namespace App\Http\Controllers;

use App\Models\CaseCompany;
use App\Models\CaseLog;
use App\Models\CaseLog34;
use App\Models\CaseLog5;
use App\Models\CaseLog5Union1;
use App\Models\CaseLogAttendant;
use App\Models\Cases;
use App\Models\Company;
use App\Services\TelegramService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Log5Controller extends Controller
{

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
        $file->move(public_path(pathToUploadFile('case_doc/log5')), $fileName); //public_path()

        CaseLog5::where("id", $request->id)->update([
            "log_file" => $fileName
        ]);

        return response()->json(['message' => 'Upload ជោគជ័យ']);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create($case_id = 0, $invitation_id = 0)
    {
        if(!allowUserAccess($case_id)){
            abort(403, 'You do not have permission to access this page.');
        }
        $case = Cases::where("id", $case_id)->first();
        $case_type_id = $case->case_type_id;
        $title = "បង្កើតកំណត់ហេតុសាកសួរព័ត៌សហគ្រាស គ្រឹះស្ថាន";
        $data['pagetitle']= $title;
        $data['case'] = $case;
        $data['case_id'] = $case_id;
        $data['case_type_id'] = $case_type_id;
        $data['invitation_id'] = $invitation_id;

        $view = "case.log.log5.create_log5";
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
        //       dd($request->all());
        //dd($request->input());
        $date_created = myDateTime();
        $case_id = $request->case_id;

        DB::beginTransaction();
        try{
            /** ================== 1.Insert Case Log in tbl_case_log ======== */
            $result = CaseLog::create([
                "case_id" => $case_id,
                "log_type_id" => 5
            ]);
            $log_id = !empty($result)? $result->id : 0;

            /** ================== 2.Insert/Update Officer Attendant in tbl_case_log_attendant ====== */
            $attendant_type_id = 6; //Head of Meeting
            insertUpdateCaseLogAttendant($case_id, $log_id, $request->head_meeting, $attendant_type_id);
            $attendant_type_id = 8; //Noter
            insertUpdateCaseLogAttendant($case_id, $log_id, $request->noter, $attendant_type_id);

            /** ================== 3.Insert/Update Disputant Represent Company in tbl_disputant, insert in tbl_case_disputant, and insert/update in tbl_case_log_attendant for Represent Company ===== */
            insertUpdateDisputantRepresentCompany($request, $log_id);

            /** ================== 4.Update Company Info in tbl_company =================== */
            $adataCompany = [
                "company_name_khmer" => $request->company_name_khmer,
                "company_name_latin" => $request->company_name_latin,
                "first_business_act" => $request->log5_first_business_act,
                "article_of_company" => $request->log5_article_of_company,
                "company_type_id" => $request->log5_company_type_id,

                "sector_id" => $request->log5_sector_id,
                "business_activity" => $request->log5_business_activity,
                "business_activity1" => $request->log5_business_activity1,
                "business_activity2" => $request->log5_business_activity2,
                "business_activity3" => $request->log5_business_activity3,
                "business_activity4" => $request->log5_business_activity4,

                "open_date" => date2DB($request->log5_open_date),
                "company_register_number" => $request->company_register_number,
                "registration_date" => date2DB($request->registration_date),
                "company_tin" => $request->company_tin,
                "nssf_number" => $request->nssf_number,

                "building_no" => $request->log5_building_no,
                "street_no" => $request->log5_street_no,
                "village_id" => $request->log5_village_id,
                "commune_id" => $request->log5_commune_id,
                "district_id" => $request->log5_district_id,
                "province_id" => $request->log5_province_id,
                "company_phone_number" => $request->log5_company_phone_number,

                "total_employee" => $request->log5_total_employee,
                "total_employee_female" => $request->log5_total_employee_female,
                "union1_number" => $request->log5_union1_number,

                "user_updated" => Auth::user()->id,
                "date_updated" =>  $date_created,

            ];
        //            dd($adataCompany);
            Company::where("company_id", $request->company_id)->update($adataCompany);

            /** ================== 5.Insert/Update Case Company in tbl_case_company =================== */
            $adataCaseCompany = [
                //"case_id" => $case_id,
                "log_id" => $log_id,
                "company_id" => $request->company_id,
                "log5_open_date" => date2DB($request->log5_open_date),
                "log5_head_phone" => $request->log5_head_phone,
                "log5_head_building_no" => $request->log5_head_building_no,
                "log5_head_street_no" => $request->log5_head_street_no,
                "log5_head_village_id" => $request->log5_head_village_id,
                "log5_head_commune_id" => $request->log5_head_commune_id,
                "log5_head_district_id" => $request->log5_head_district_id,
                "log5_head_province_id" => $request->log5_head_province_id,

                "log5_owner_name_khmer" => $request->log5_owner_name_khmer,
                "log5_owner_nationality_id" => $request->log5_owner_nationality_id,
                "log5_director_name_khmer" => $request->log5_director_name_khmer,
                "log5_director_nationality_id" => $request->log5_director_nationality_id,

                "log5_first_business_act" => $request->log5_first_business_act,
                "log5_article_of_company" => $request->log5_article_of_company,
                "log5_company_type_id" => $request->log5_company_type_id,

                "log5_sector_id" => $request->log5_sector_id,
                "log5_business_activity" => $request->log5_business_activity,
                "log5_business_activity1" => $request->log5_business_activity1,
                "log5_business_activity2" => $request->log5_business_activity2,
                "log5_business_activity3" => $request->log5_business_activity3,
                "log5_business_activity4" => $request->log5_business_activity4,

                "log5_company_phone_number" => $request->log5_company_phone_number,
                "log5_building_no" => $request->log5_building_no,
                "log5_street_no" => $request->log5_street_no,
                "log5_village_id" => $request->log5_village_id,
                "log5_commune_id" => $request->log5_commune_id,
                "log5_district_id" => $request->log5_district_id,
                "log5_province_id" => $request->log5_province_id,
                "log5_total_employee" => $request->log5_total_employee,
                "log5_total_employee_female" => $request->log5_total_employee_female,
                "log5_union1_number" => $request->log5_union1_number,

                "user_created" => Auth::user()->id,
                "date_created" =>  $date_created,
            ];
            //dd($adataCaseCompany);
            $searchCaseCompany = ["case_id" => $case_id];
            CaseCompany::updateOrCreate($searchCaseCompany, $adataCaseCompany);

            /** ================== 6.Insert/Update Union1 in tbl_case_log5_union1 =================== */
            $this->insertUpdateUnion1($case_id, $log_id, $request->log5_union1_number, $request->union1_name);

            /** ================== 7.Insert Log5 Data in tbl_case_log5 =================== */
            $adataLog5 = [
                "case_id" => $case_id,
                "log_id" => $log_id,
                "meeting_date" => date2DB($request->meeting_date),
                "meeting_stime" => $request->meeting_stime,
                "meeting_etime" => $request->meeting_etime,
                "invitation_id" => $request->invitation_id,
                "meeting_place_id" => $request->meeting_place_id,
                "meeting_place_other" => $request->meeting_place_other,
                "meeting_about" => $request->meeting_about,
                "head_officer_comment" => $request->head_officer_comment,

                "contract_type_with_employee" => $request->contract_type_with_employee,
                "dispute_cause" => $request->dispute_cause,
                "dispute_more_info" => $request->dispute_more_info,

                "user_created" => Auth::user()->id,
                "date_created" =>  $date_created,
            ];

            CaseLog5::create($adataLog5);

            $msgTitle = "បានបង្កើតកំណត់ហេតុ សាកសួរព័ត៌សហគ្រាស គ្រឹះស្ថាន";
            $currentCase = Cases::find($request->case_id);
            caseStatusTelegramNotification($currentCase, $msgTitle);

            DB::commit();
            if(request("json_opt") == 1){ //if request from app
                //return response()->json(['status' => 200, 'message' => 'success', 'data'=> $data]);
            }
            return redirect("cases/".$case_id)->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
            //return redirect("log5/".$id."/edit")->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return back()->with("message", sweetalert()->addWarning("បរាជ័យ"));
        }
    }
    private function insertUpdateUnion1($case_id, $log_id, $union1_number = 0, $union1)
    {
        if($union1_number > 0){
            $date_created = myDateTime();
            foreach($union1 as $key => $val){
                if($union1[$key] != ""){
                    $search = [
                        "case_id" => $case_id,
                        "log_id" => $log_id,
                        "union1_name" => $union1[$key]
                    ];
                    $adata = [
                        "user_updated" => Auth::user()->id,
                        "date_updated" =>  $date_created,
                    ];
                    //dd($adataSubDisputant);
                    $result = CaseLog5Union1::updateOrCreate($search, $adata);
                    if ($result->wasRecentlyCreated) {
                        $arrayCreate = [
                            "user_created" => Auth::user()->id,
                            "date_created" =>  $date_created
                        ];
                        CaseLog5Union1::where($search)->update($arrayCreate);
                        // The record was just created
                        //echo 'Record was created';
                    }
                    //                else {
                    //                    // The record already existed and was updated
                    //                    echo 'Record was updated';
                    //                }
                }
            }
            //dd("Result");

        }
    }
    private function updateUnion1($union1_id, $union1)
    {
        if(!empty($union1)){
            $date_created = myDateTime();
            foreach($union1 as $key => $val) {
                $id = $union1_id[$key];
                if($union1[$key] != ""){
                    $adata = [
                        "union1_name" => $union1[$key],
                        "user_updated" => Auth::user()->id,
                        "date_updated" => $date_created,
                    ];
                    CaseLog5Union1::where("id", $id)->update($adata);
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
        $data['pagetitle']= "<span class='text-danger'>កែប្រែកំណត់ហេតុសាកសួរព័ត៌មានសហគ្រាស គ្រឹះស្ថាន</span>";
        $log5 = CaseLog5::where("id", $id)->first();
        $data['log5'] = $log5;
        $data['case'] = $log5->case;
        $data['case_id'] = $log5->case->id;
        $data['head_meeting'] = $log5->headMeeting;
        $data['noter'] = $log5->noter;
        //        $data['noter'] = CaseLogAttendant::where("case_id", $log5->case->id)
        //            ->where("log_id", $log5->log_id)->where("attendant_type_id", 8)
        //            ->first();
        $case_type_id = $log5->case->case_type_id;
        if($case_type_id == 1){
            $attendant_type_id = 3;//represent company
            $sub_attendant_type_id = 4;//sub represent company
        }
        elseif($case_type_id == 2){
            $attendant_type_id = 1; //represent company
            $sub_attendant_type_id = 2;//sub represent company
        }
        //dd($sub_attendant_type_id);
        //dd($log5->case->id);
        $data['represent_company'] = CaseLogAttendant::where("case_id", $log5->case->id)
            ->where("log_id", $log5->log_id)->where("attendant_type_id", $attendant_type_id)
            ->first();
        //dd($data['represent_company']);
        $data['sub_represent_company'] = CaseLogAttendant::where("case_id", $log5->case->id)
            ->where("log_id", $log5->log_id)->where("attendant_type_id", $sub_attendant_type_id)
            ->get();

        $view = "case.log.log5.update_log5";
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
        //         dd($request->all());
        $date_created = myDateTime();
        $log_id = $request->log_id;
        $case_id = $request->case_id;

        /** ======= 1.Update Log5 data in tbl_case_log5 ======== */
        $adataLog5 = [
            "case_id" => $case_id,
            "log_id" => $log_id,
            "meeting_date" => date2DB($request->meeting_date),
            "meeting_stime" => $request->meeting_stime,
            "meeting_etime" => $request->meeting_etime,
            "invitation_id" => $request->invitation_id,
            "meeting_place_id" => $request->meeting_place_id,
            "meeting_place_other" => $request->meeting_place_other,
            "meeting_about" => $request->meeting_about,
            "head_officer_comment" => $request->head_officer_comment,

            "contract_type_with_employee" => $request->contract_type_with_employee,
            "dispute_cause" => $request->dispute_cause,
            "dispute_more_info" => $request->dispute_more_info,

            "user_updated" => Auth::user()->id,
            "date_updated" =>  $date_created,
        ];
        //dd($adata);
        CaseLog5::where("id", $id)->update($adataLog5);
        /** ======= 2.Update Exists Union1, Insert/Update New Union1 in tbl_case_log5_union1 ========== */
        $this->updateUnion1($request->union1_id, $request->union1_name_update);
        $this->insertUpdateUnion1($case_id, $log_id, $request->log5_union1_number, $request->union1_name);

        /** ======= 3.Update Head-Meeting && Officer-Noter Attendant in tbl_case_log_attendant ====== */
        $attendant_type_id = 6;
        insertUpdateHeadMeeting($case_id, $log_id, $request->head_meeting, $attendant_type_id);

        $attendant_type_id = 8; //Noter
        insertUpdateCaseOfficer($case_id, $log_id, $request->noter, $attendant_type_id);
        //            $adataNoter = [
        //                "attendant_id" => $request->noter,
        //                "user_updated" => Auth::user()->id,
        //                "date_updated" =>  $date_created,
        //            ];
        //            CaseLogAttendant::where("id", $request->noterid)->update($adataNoter);

                /** ======== 4.Insert/Update Disputant Represent Company in tbl_disputant, insert in tbl_case_disputant, and insert/update in tbl_case_log_attendant for Represent Company ===== */
        insertUpdateDisputantRepresentCompany($request, $log_id);

        /** ======= 5.Update Company Info in tbl_company =================== */
        $adataCompany = [
            "company_name_khmer" => $request->company_name_khmer,
            "company_name_latin" => $request->company_name_latin,

            "first_business_act" => $request->log5_first_business_act,
            "article_of_company" => $request->log5_article_of_company,
            "company_type_id" => $request->log5_company_type_id,

            "sector_id" => $request->log5_sector_id,

            "open_date" => date2DB($request->log5_open_date),
            "company_register_number" => $request->company_register_number,
            "registration_date" => date2DB($request->registration_date),
            "company_tin" => $request->company_tin,
            "nssf_number" => $request->nssf_number,

            "building_no" => $request->log5_building_no,
            "street_no" => $request->log5_street_no,
            "village_id" => $request->log5_village_id,
            "commune_id" => $request->log5_commune_id,
            "district_id" => $request->log5_district_id,
            "province_id" => $request->log5_province_id,
            "company_phone_number" => $request->log5_company_phone_number,

            "total_employee" => $request->log5_total_employee,
            "total_employee_female" => $request->log5_total_employee_female,
            "union1_number" => $request->log5_union1_number,

            "user_updated" => Auth::user()->id,
            "date_updated" =>  $date_created,

        ];
        //dd($adataCompany);
        Company::where("company_id", $request->company_id)->update($adataCompany);

        /** ====== 6.Update Case Company in tbl_case_company =================== */
        $adataCaseCompany = [
            //"case_id" => $case_id,
            "log_id" => $log_id,
            "company_id" => $request->company_id,
            "log5_open_date" => date2DB($request->log5_open_date),
            "log5_head_phone" => $request->log5_head_phone,
            "log5_head_building_no" => $request->log5_head_building_no,
            "log5_head_street_no" => $request->log5_head_street_no,
            "log5_head_village_id" => $request->log5_head_village_id,
            "log5_head_commune_id" => $request->log5_head_commune_id,
            "log5_head_district_id" => $request->log5_head_district_id,
            "log5_head_province_id" => $request->log5_head_province_id,

            "log5_owner_name_khmer" => $request->log5_owner_name_khmer,
            "log5_owner_nationality_id" => $request->log5_owner_nationality_id,
            "log5_director_name_khmer" => $request->log5_director_name_khmer,
            "log5_director_nationality_id" => $request->log5_director_nationality_id,

            "log5_first_business_act" => $request->log5_first_business_act,
            "log5_article_of_company" => $request->log5_article_of_company,
            "log5_company_type_id" => $request->log5_company_type_id,

            "log5_sector_id" => $request->log5_sector_id,

            "log5_company_phone_number" => $request->log5_company_phone_number,
            "log5_building_no" => $request->log5_building_no,
            "log5_street_no" => $request->log5_street_no,
            "log5_village_id" => $request->log5_village_id,
            "log5_commune_id" => $request->log5_commune_id,
            "log5_district_id" => $request->log5_district_id,
            "log5_province_id" => $request->log5_province_id,
            "log5_total_employee" => $request->log5_total_employee,
            "log5_total_employee_female" => $request->log5_total_employee_female,
            "log5_union1_number" => $request->log5_union1_number,

            "user_updated" => Auth::user()->id,
            "date_updated" =>  $date_created,
        ];
        //dd($adataCaseCompany);
        CaseCompany::where("case_id", $case_id)->update($adataCaseCompany);

        $msgTitle = "បានកែប្រែកំណត់ហេតុ សាកសួរព័ត៌សហគ្រាស គ្រឹះស្ថាន";
        $currentCase = Cases::find($request->case_id);
        caseStatusTelegramNotification($currentCase, $msgTitle);

        DB::beginTransaction();
        try{

            DB::commit();
            if(request("json_opt") == 1){ //if request from app
                //return response()->json(['status' => 200, 'message' => 'success', 'data'=> $data]);
            }
            return saveRedirect($request->input("btnSubmit"), $request->input("case_id"));
            //return redirect("log5/".$id."/edit")->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
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
    public function destroy(string $id, TelegramService $telegramService)
    {
        //        dd($id);
        if(!allowUserAccess()){
            abort(403, 'You do not have permission to access this page.');
        }
        $caseLog5 = CaseLog5::where('id', $id)->first();
        $caseID = $caseLog5->case_id;
        $case = Cases::find($caseID);
        $logID = $caseLog5->log_id;
        $caseYear = !empty($case->case_date) ? date2Display($case->case_date, "Y") : myDate('Y');

        DB::beginTransaction();
        try{
            /** 1.Delete Case Log in tbl_case_log ===================== */
            CaseLog::where("id", $logID)->where("case_id", $caseID)->delete();
            /** 2.Delete Case Log Attendant in tbl_case_log_attendant ======== */
            CaseLogAttendant::where("log_id", $logID)->where("case_id", $caseID)->delete();
            /** 3.Delete Union1 in tbl_case_log5_union1 ======== */
            CaseLog5Union1::where("log_id", $logID)->where("case_id", $caseID)->delete();

            /** 4.Delete Case Log5 in tbl_case_log ======== */
            if(!empty($caseLog5)){
                /**=================== Telegram Bot ============================  */
                $todayCases = DB::table('tbl_case')
                    ->whereDate('date_created', Carbon::today())
                    ->count();
                $totalCases = DB::table('tbl_case')->where('case_type_id', 1)->count();

                $msg2Telegram = "<b>"."📢 លុបកំណត់ហេតុ សាកសួរព័ត៌មានសហគ្រាស គ្រឹះស្ថាន !!!"."</b>"."\n"
                    . "===========================". "\n"
                    . "📌 សំណុំរឿងលេខ៖ " . "<b>" . ($case->case_num_str ?? 'N/A') . "</b>\n\n"
                    . "👤 អ្នកបញ្ចូលកំណត់ហេតុ៖ " ."<b>". ($caseLog5->entryUpdatedUser->k_fullname ?? 'N/A') ."</b>". "\n\n"
                    . "👤 អ្នកលុបកំណត់ហេតុ៖ <b>" . (Auth::user()->k_fullname ?? 'N/A') ."</b>". "\n\n"
                    . "👤 ដើមបណ្តឹង៖ " ."<b>". ($case->disputant->name ?? 'N/A') ."</b>". "\n\n"
                    . "👤 ចុងបណ្តឹង៖ " ."<b>". ($case->company->company_name_khmer ?? 'N/A') ."</b>". "\n\n"
                    . "📆 កាលបរិច្ឆេទជួបប្រជុំ៖ "."<b>". (date2Display($caseLog5->meeting_date) ?? 'N/A')."</b>". "\n\n"
                    . "===========================". "\n"
                    . "#️⃣ សំណុំរឿងដែលបានបញ្ចូលថ្ងៃនេះចំនួន៖ "."<b>".number2KhmerNumber($todayCases)."</b>". " បណ្តឹង\n\n"
                    . "#️⃣ សំណុំរឿងសរុបទាំងអស់ចំនួន៖ "."<b>".number2KhmerNumber($totalCases)."</b>". " បណ្តឹង\n\n";


                // Push notification to telegram
                $telegramService->sendMessage($msg2Telegram);
                //Delete Log5 record
                $caseLog5->delete();

                //Delete Log5 file
                deleteFile($caseLog5->log_file, pathToUploadFile("case_doc/log5/".$caseYear."/"));//delete invitation_file
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
    function deleteRepresentCompany($id)
    {
        if(!allowUserAccess()){
            abort(403, 'You do not have permission to access this page.');
        }
        //dd($id);
        $tmp = explode("_", $id);
        $id = $tmp[0];
        $logID = $tmp[1];
        CaseLogAttendant::where("id", $id)->delete();
        return redirect("log5/".$logID."/edit")->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
    }
    function deleteUnion1($id)
    {
        if(!allowUserAccess()){
            abort(403, 'You do not have permission to access this page.');
        }
        $tmp = explode("_", $id);
        $id = $tmp[0];
        $logID = $tmp[1];
        CaseLog5Union1::where("id", $id)->delete();
        return redirect("log5/".$logID."/edit")->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
    }


}
