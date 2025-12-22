<?php

namespace App\Http\Controllers;

use App\Models\CaseDisputant;
use App\Models\CaseInvitation;
use App\Models\CaseLog;
use App\Models\CaseLog34;
use App\Models\CaseLogAttendant;
use App\Models\Cases;
use App\Models\Disputant;
use App\Services\TelegramService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Log34Controller extends Controller
{

    function deleteSubDisputant($id){
        if(!allowUserAccess()){
            abort(403, 'You do not have permission to access this page.');
        }
        $tmp = explode("_", $id);
        $id = $tmp[0];
        $logID = $tmp[1];
        CaseLogAttendant::where("id", $id)->delete();
        return redirect("log34/".$logID."/edit")->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
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
        if(!allowUserAccess($caseID)){
            abort(403, 'You do not have permission to access this page.');
        }
        $case = Cases::with([])->findOrFail($caseID);
        $caseTypeID = $case->case_type_id;
        $title = "បង្កើតកំណត់ហេតុសាកសួរព័ត៌មានកម្មករ";
        $data['pagetitle']= $title;
        $data['case'] = $case;
        $data['case_id'] = $caseID;
        $data['case_type_id'] = $caseTypeID;
        $data['invitation_id'] = $invID;
        $view = "case.log.log34.create_log34";
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
        //dd($request->all());
        //dd($request->input());
        $dateCreated = myDateTime();
        $caseID = $request->case_id;

        DB::beginTransaction();
        try{
            /** ================== 1.Insert Case Log in tbl_case_log ======== */
            $result = CaseLog::create([
                "case_id" => $caseID,
                "log_type_id" => 3
            ]);
            $logID = !empty($result)? $result->id : 0;
            /** ================== 2.Insert/Update in tbl_case_log_attendant for officer ======== */

            $attendant_type_id = 6;
            insertUpdateCaseLogAttendant($caseID, $logID, $request->head_meeting, $attendant_type_id);
            $attendant_type_id = 8;
            insertUpdateCaseLogAttendant($caseID, $logID, $request->noter, $attendant_type_id);
            /** ================== 3.Insert/Update in tbl_case_log_attendant for main Disputant ======== */
            if($request->case_type_id == 1){
                $attendantTypeID = 1;
                $subAttendantTypeID = 2;
            }
            elseif($request->case_type_id == 2){
                $attendantTypeID = 3;
                $subAttendantTypeID = 4;
            }
            insertUpdateCaseLogAttendant($caseID, $logID, $request->disputant_id, $attendantTypeID);
            /** ================== 4.Insert/Update Sub Disputant in tbl_disputant, insert in tbl_case_disputant, and insert/update in tbl_case_log_attendant for Sub disputant (employee) ===== */
            $sub_disputant_id = 0;
            insertUpdateDisputant($request, $logID, $subAttendantTypeID);
                //        if(!empty($request->id_number) && !empty($request->name) && !empty($request->dob) && !empty($request->nationality) && !empty($request->phone_number)){
                //            $searchSubDisputant = ["id_number" => $request->id_number];
                //            $adataSubDisputant = [
                //                "name" => $request->name,
                //                "gender" => $request->gender,
                //                "dob" => date2DB($request->dob),
                //                "nationality" => $request->nationality,
                //                //"id_number" => $request->id_number,
                //                "phone_number" => $request->phone_number,
                //
                //                "house_no" => $request->addr_house_no,
                //                "street" => $request->addr_street,
                //                "group_name" => $request->group_name,
                //                "village" => $request->village,
                //                "commune" => $request->commune,
                //                "district" => $request->district,
                //                "province" => $request->province,
                //
                //                "pob_commune_id" => $request->pob_commune_id,
                //                "pob_district_id" => $request->pob_district_id,
                //                "pob_province_id" => $request->pob_province_id,
                //
                //                "user_created" => Auth::user()->id,
                //                "user_updated" => Auth::user()->id,
                //                "date_created" =>  $date_created,
                //                "date_updated" =>  $date_created,
                //            ];
                //            //dd($adataSubDisputant);
                //            $result = Disputant::updateOrCreate($searchSubDisputant, $adataSubDisputant);
                //            $sub_disputant_id = !empty($result)? $result->id : 0;//insert or update it return the same result
                //            /** ================== 3.Insert Case Disputant in tbl_case_disputant ============ */
                //            $adataCaseDisputant = [
                //                "case_id" => $request->case_id,
                //                "disputant_id" => $sub_disputant_id,
                //                "attendant_type_id" => 2,
                //                "house_no" => $request->addr_house_no,
                //                "street" => $request->addr_street,
                //                "village" => $request->village,
                //                "commune" => $request->commune,
                //                "district" => $request->district,
                //                "province" => $request->province,
                //
                //                "phone_number" => $request->phone_number,
                //                "occupation" => $request->occupation,
                //
                //                "user_created" => Auth::user()->id,
                //                "date_created" =>  $date_created,
                //            ];
                //            //dd($adataCaseDisputant);
                //            $result = CaseDisputant::create($adataCaseDisputant);
                //            dd($result);
                //
                //        }
            /** ================== 5.Insert Data in Log34 (tbl_case_log34) =================== */
            $adata = [
                "case_id" => $caseID,
                "log_id" => $logID,
                "meeting_date" => date2DB($request->meeting_date),
                "meeting_stime" => $request->meeting_stime,
                "meeting_etime" => $request->meeting_etime,
                "invitation_id" => $request->invitation_id,
                "disputant_give_info" => $request->disputant_give_info,

                "log34_1" => $request->log34_1,
                "log34_2" => $request->log34_2,
                "log34_3" => $request->log34_3,
                "log34_4" => $request->log34_4,
                "log34_5" => $request->log34_5,
                "log34_6" => $request->log34_6,
                "log34_7" => $request->log34_7,
                "log34_8" => $request->log34_8,
                "log34_9" => $request->log34_9,
                "log34_10" => $request->log34_10,
                "log34_11" => $request->log34_11,

                "user_created" => Auth::user()->id,
                "date_created" =>  $dateCreated,
            ];
            //dd($adata);
            CaseLog34::create($adata);

        //            $id = !empty($result)? $result->id : 0;
            $msgTitle = "បានបង្កើតកំណត់ហេតុ សាកសួរព័ត៌មានកម្មករ";

            $currentCase = Cases::find($caseID);
            caseStatusTelegramNotification($currentCase, $msgTitle);

            DB::commit();
            if(request("json_opt") == 1){ //if request from app
                //return response()->json(['status' => 200, 'message' => 'success', 'data'=> $data]);
            }
            return redirect("cases/".$caseID)->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
            //return redirect("log34/".$id."/edit")->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return back()->with("message", sweetalert()->addWarning("បរាជ័យ"));
        }
    }
    function insertUpdateDisputantxx($request, $log_id, $sub_attendant_type_id)
    {
        $date_created = myDateTime();
        $arrayDisputant = $request->id_number;
        //dd($arrayDisputant);
        foreach($arrayDisputant as $key => $val){

            if(!empty($request->id_number[$key]) && !empty($request->name[$key]) && !empty($request->dob[$key]) && !empty($request->nationality[$key]) && !empty($request->phone_number[$key])){ //  && !empty($request->phone_number[$key])
                $searchSubDisputant = ["id_number" => $request->id_number[$key] ];
                /** ================== 1.Insert/Update Disputant in tbl_disputant ============ */
                $adataSubDisputant = [
                    "name" => $request->name[$key],
                    "gender" => $request->gender[$key],
                    "dob" => date2DB($request->dob[$key]),
                    "nationality" => $request->nationality[$key],
                    //"id_number" => $request->id_number,
                    "phone_number" => $request->phone_number[$key],

                    "house_no" => $request->addr_house_no[$key],
                    "street" => $request->addr_street[$key],
                    //"group_name" => $request->group_name[$key],
                    "village" => $request->village[$key],
                    "commune" => $request->commune[$key],
                    "district" => $request->district[$key],
                    "province" => $request->province[$key],

                    "pob_commune_id" => $request->pob_commune_id[$key],
                    "pob_district_id" => $request->pob_district_id[$key],
                    "pob_province_id" => $request->pob_province_id[$key],

                    "user_created" => Auth::user()->id,
                    "user_updated" => Auth::user()->id,
                    "date_created" =>  $date_created,
                    "date_updated" =>  $date_created,
                ];
                //dd($adataSubDisputant);
                $result = Disputant::updateOrCreate($searchSubDisputant, $adataSubDisputant);
                $sub_disputant_id = !empty($result)? $result->id : 0;//insert or update it return the same result
                /** ================== 2.Insert/Update in tbl_case_log_attendant for sub disputant ========= */
                $this->insertUpdateCaseLogAttendant($request->case_id, $log_id, $sub_disputant_id, $sub_attendant_type_id);
                /** ================== 3.Insert Case Disputant in tbl_case_disputant ============ */
                $adataCaseDisputant = [
                    "case_id" => $request->case_id,
                    "disputant_id" => $sub_disputant_id,
                    "attendant_type_id" => 2,
                    "house_no" => $request->addr_house_no[$key],
                    "street" => $request->addr_street[$key],
                    "village" => $request->village[$key],
                    "commune" => $request->commune[$key],
                    "district" => $request->district[$key],
                    "province" => $request->province[$key],

                    "phone_number" => $request->phone_number[$key],
                    "occupation" => $request->occupation[$key],

                    "user_created" => Auth::user()->id,
                    "date_created" =>  $date_created,
                ];
                //dd($adataCaseDisputant);
                $result = CaseDisputant::create($adataCaseDisputant);
            }
        }

    }
    function insertUpdateCaseLogAttendantxx($case_id, $log_id, $attendant_id, $attendant_type_id){
        $date_created = myDateTime();
        if($attendant_id > 0){
            $adataSearch = [
                "case_id" => $case_id,
                "log_id" => $log_id,
                "attendant_id" => $attendant_id,
                "attendant_type_id" => $attendant_type_id,
            ];
            $adata = [
                "user_created" => Auth::user()->id,
                "user_updated" => Auth::user()->id,
                "date_created" =>  $date_created,
                "date_updated" =>  $date_created,
            ];
            //dd($adataSubDisputant);
            CaseLogAttendant::updateOrCreate($adataSearch, $adata);
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
//        $data['noter'] = CaseLogAttendant::where("case_id", $log34->case->id)
//            ->where("log_id", $log34->log_id)->where("attendant_type_id", 8)
//            ->first();
        $caseTypeID = $log34->case->case_type_id;
        if($caseTypeID == 1){
            $attendantTypeID = 1;
            $subAttendantTypeID = 2;
        }
        elseif($caseTypeID == 2){
            $attendantTypeID = 3;
            $subAttendantTypeID = 4;
        }
        $data['sub_disputant'] = CaseLogAttendant::where("case_id", $log34->case->id)
            ->where("log_id", $log34->log_id)->where("attendant_type_id", $subAttendantTypeID)
            ->get();

        $view = "case.log.log34.update_log34";
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
        //dd($request->all());
        //dd($request->input());
        $dateCreated = myDateTime();
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

                "log34_1" => $request->log34_1,
                "log34_2" => $request->log34_2,
                "log34_3" => $request->log34_3,
                "log34_4" => $request->log34_4,
                "log34_5" => $request->log34_5,
                "log34_6" => $request->log34_6,
                "log34_7" => $request->log34_7,
                "log34_8" => $request->log34_8,
                "log34_9" => $request->log34_9,
                "log34_10" => $request->log34_10,
                "log34_11" => $request->log34_11,

                "user_updated" => Auth::user()->id,
                "date_updated" =>  $dateCreated,
            ];
            //dd($adata);
            $result = CaseLog34::where("id", $request->id)->update($adata);// return 1 if update success

            /** ================== 2.Update Noter in tbl_case_log_attendant =================== */
//        $result = CaseLogAttendant::where("id", $request->noter_id)
//            ->update([
//                "attendant_id" => $request->noter,
//                "user_updated" => Auth::user()->id,
//                "date_updated" =>  $date_created,
//            ]);
            /** ================== 3.Insert/Update Disputant in tbl_disputant, insert in tbl_case_disputant, and insert/update in tbl_case_log_attendant for disputant (employee) ===== */
            if($request->case_type_id == 1){
                //$attendant_type_id = 1;
                $subAttendantTypeID = 2;
            }
            elseif($request->case_type_id == 2){
                //$attendant_type_id = 3;
                $subAttendantTypeID = 4;
            }
            insertUpdateDisputant($request, $logID, $subAttendantTypeID);

            /** ================== 4.Insert/Update in tbl_case_log_attendant for officer & Noter ======== */
            $attendant_type_id = 6; //Head Meeting
            insertUpdateHeadMeeting($caseID, $logID, $request->head_meeting, $attendant_type_id);

            $attendant_type_id = 8; //Noter
            insertUpdateCaseOfficer($caseID, $logID, $request->noter, $attendant_type_id);

            $msgTitle = "បានកែប្រែកំណត់ហេតុ សាកសួរព័ត៌មានកម្មករ";

            $currentCase = Cases::find($caseID);
            caseStatusTelegramNotification($currentCase, $msgTitle);

            DB::commit();
            if(request("json_opt") == 1){ //if request from app
                //return response()->json(['status' => 200, 'message' => 'success', 'data'=> $data]);
            }

            return saveRedirect($request->input("btnSubmit"), $request->input("case_id"));
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
    public function destroy(string $id, TelegramService $telegramService)
    {
        if(!allowUserAccess()){
            abort(403, 'You do not have permission to access this page.');
        }
        $caseLog34 = CaseLog34::find($id);
        $caseID = $caseLog34->case_id;
        $case = Cases::find($caseID);
        $logID = $caseLog34->log_id;
        $caseYear = !empty($case->case_date) ? date2Display($case->case_date, "Y") : myDate('Y');
//        dd($caseYear);

//        dd(pathToUploadFile("case_doc/log34/".$caseYear."/"));

        DB::beginTransaction();
        try{
            /** 1.Delete Case Log in tbl_case_log ===================== */
            CaseLog::where("id", $logID)->where("case_id", $caseID)->delete();
            /** 2.Delete Case Log Attendant in tbl_case_log_attendant ======== */
            CaseLogAttendant::where("log_id", $logID)->where("case_id", $caseID)->delete();
            /** 3.Delete Case Log34 in tbl_case_log34 ======== */

            if(!empty($caseLog34)){
                /**=================== Telegram Bot ============================  */
                $todayCases = DB::table('tbl_case')
                    ->whereDate('date_created', Carbon::today())
                    ->count();
                $totalCases = DB::table('tbl_case')->where('case_type_id', 1)->count();

                $msg2Telegram = "<b>"."📢 លុបកំណត់ហេតុសាកសួរ ព័ត៌មានកម្មករ !!!"."</b>"."\n"
                    . "===========================". "\n"
                    . "📌 សំណុំរឿងលេខ៖ " . "<b>" . ($case->case_num_str ?? 'N/A') . "</b>\n\n"
                    . "👤 អ្នកបញ្ចូលកំណត់ហេតុ៖ " ."<b>". ($caseLog34->entryUpdatedUser->k_fullname ?? 'N/A') ."</b>". "\n\n"
                    . "👤 អ្នកលុបកំណត់ហេតុ៖ <b>" . (Auth::user()->k_fullname ?? 'N/A') ."</b>". "\n\n"
                    . "👤 ដើមបណ្តឹង៖ " ."<b>". ($case->disputant->name ?? 'N/A') ."</b>". "\n\n"
                    . "👤 ចុងបណ្តឹង៖ " ."<b>". ($case->company->company_name_khmer ?? 'N/A') ."</b>". "\n\n"
                    . "📆 កាលបរិច្ឆេទជួបប្រជុំ៖ "."<b>". (date2Display($caseLog34->meeting_date) ?? 'N/A')."</b>". "\n\n"
                    . "===========================". "\n"
                    . "#️⃣ សំណុំរឿងដែលបានបញ្ចូលថ្ងៃនេះចំនួន៖ "."<b>".number2KhmerNumber($todayCases)."</b>". " បណ្តឹង\n\n"
                    . "#️⃣ សំណុំរឿងសរុបទាំងអស់ចំនួន៖ "."<b>".number2KhmerNumber($totalCases)."</b>". " បណ្តឹង\n\n";


                // Push notification to telegram
                $telegramService->sendMessage($msg2Telegram);

                //Delete Log34 record
                $caseLog34->delete();

                //Delete Log34 file
                deleteFile($caseLog34->log_file, pathToUploadFile("case_doc/log34/".$caseYear."/"));//delete invitation_file
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
}
