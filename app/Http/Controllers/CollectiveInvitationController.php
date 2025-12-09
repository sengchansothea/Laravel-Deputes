<?php

namespace App\Http\Controllers;


use App\Models\CaseInvitation;
use App\Models\CaseLog620;
use App\Models\Cases;
use App\Models\Disputant;
use App\Models\InvitationNextTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CollectiveInvitationController extends Controller
{
    private int $totalRecord = 0;
    private int $perPage = 20;



    function deleteInvitationNext($id){
        $tmp = explode("_", $id);
        $caseYear = $tmp[0];
        $next_id = $tmp[1];

        $invNext = InvitationNextTime::find($next_id);
        if(!empty($invNext)){
            // Delete  record
            $invNext->delete();

            // Delete file
            deleteFile($invNext->letter, pathToUploadFile("collectives_invitation/next/".$caseYear."/"));//delete invitation_file
        }
//        return redirect("invitation/".$invitation_id."/edit")->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
        return back()->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
    }
    /**
     * Display a listing of the resource.
     */
    public function index($invitation_type = 0)
    {
        $data['opt_search']= request('opt_search')? request('opt_search'): "quick";
        $data['letters']= $this->getOrSearchEloquent();
        $data['pagetitle']= "បញ្ជីលិខិតអញ្ជើញ";
        $data['totalRecord'] = $this->totalRecord;
        $view="case.invitation.list";

        if(request("json_opt") == 1){ //if request from app
            return response()->json(['status'=>200,'result'=> $data]);
        }

        return view($view, [ "adata" => $data ]);
    }
    function getOrSearchEloquent(){
        $letters = CaseInvitation::query();
        if(request("search")){
            $search = request("search");
            $letters = $letters->whereRelation("company", function ($query) use ($search) {
                //$query->whereRaw("long_distance = 1");
                $query->where(DB::raw("CONCAT('x',company_id,'x', company_name_khmer,'', COALESCE(company_name_latin, 'NULL'), COALESCE(company_register_number, 'NULL'), COALESCE(company_tin, 'NULL') )"), "LIKE", "%".$search."%");
            });
//                ->orWhereRelation("disputant", function ($query) use ($search) {
//                    $query->where(DB::raw("CONCAT('x',id,'x', name,'', COALESCE(name_latin, 'NULL'), id_number )"), "LIKE", "%".$search."%");
//                });
            //dd($cases->ddRawSql());
        }


//        if(request('business_activity') && request('business_activity') > 0){
//            $companys = $companys->where("business_activity", request('business_activity'));
//            $this->pageTitle = "លទ្ធផលស្វែងរករោងចក្រ សហគ្រាស ";
//        }



        //$companys->ddRawSql();
        $this->totalRecord = $letters->count();
        $letters = $letters->orderBy("id", "DESC");
        $letters = $letters->paginate($this->perPage);
        $arraySearchParam =array (
            "json_opt" => request( 'json_opt'),
            "search" => request( 'search'),
//            "business_activity" => request( 'business_activity'),
//            "total_emp" => request( 'total_emp'),
//            "business_province" => request( 'business_province'),
//            "business_district" => request( 'business_district'),
//            "business_commune" => request( 'business_commune'),
        );
        $letters->appends( $arraySearchParam );

        return $letters;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($case_id = 0, $employee_or_company = 1)    {
        $case = Cases::where("id", $case_id)->first();
        if ($employee_or_company == 31 ){
            $title = "បង្កើតលិខិតអញ្ជើញសម្រាប់តំណាងកម្មករនិយោជិត មកផ្ដល់ព័ត៌មាន";
        }elseif ($employee_or_company == 32){
            $title = "បង្កើតលិខិតអញ្ជើញសម្រាប់គ្រឹះស្ថាន សហគ្រាស មកផ្ដល់ព័ត៌មាន";
        }
        $data['pagetitle']= $title;
        $data['case'] = $case;
        $data['case_id'] = $case_id;
        $data['case_type_id'] = $case->case_type_id;
        $data['employee_or_company'] = $employee_or_company;
        $data['invCountPlus1'] = CaseInvitation::where('invitation_type_id','>', 6)->count() + 1;

        $view = "case.invitation.create_collectives";
        if(request("json_opt") == 1){ //if request from app
            return response()->json(['status'=>200,'result'=> $data]);
        }
        return view($view, [ "adata" => $data ]);
    }
    /**
     * Show the form to create both invitation for employee and company (លិខិតផ្សះផ្សា)
     */
    public function createBoth($case_id = 0, $invitation_type_employee = 9, $invitation_type_company = 10)
    {
        $case = Cases::where("id", $case_id)->first();
        $case_type_id = $case->case_type_id;
        $title = "បង្កើតលិខិតអញ្ជើញតំណាងកម្មករនិយោជិត និងតំណាងរោងចក្រ សហគ្រាស (មកផ្សះផ្សា)";
        $data['pagetitle']= $title;
        $data['case'] = $case;
        $data['case_id'] = $case_id;
        $data['case_type_id'] = $case_type_id;
        $data['invitation_type_employee'] = $invitation_type_employee;
        $data['invitation_type_company'] = $invitation_type_company;
        $data['invCountPlus1'] = CaseInvitation::where('invitation_type_id','>', 6)->count() + 1;
        $view = "case.invitation.create_collectives_both";
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

        DB::beginTransaction();
        try{

            /** ================== 1.Insert Case Invitation =================== */
            $dataCaseInvitation = [
                "case_id" => $request->case_id,
//                "disputant_id" => 0,
                "company_id" => $request->company_id,
                "invitation_number" => $request->inv_number,
                "invitation_type_id" => $request->invitation_type_id,
                "meeting_date" => date2DB($request->meeting_date),
                "meeting_time" => $request->meeting_time,
                //"meeting_for" => $request->meeting_for,
                "invitation_required_doc" => $request->invitation_required_doc,
                "letter_date" => date2DB($request->letter_date),
                "contact_phone" => $request->contact_phone,

                "user_created" => Auth::user()->id,
                "date_created" =>  $date_created,
            ];
//            dd($dataCaseInvitation);
            CaseInvitation::create($dataCaseInvitation);

            DB::commit();
            if(request("json_opt") == 1){ //if request from app
                //return response()->json(['status' => 200, 'message' => 'success', 'data'=> $data]);
            }
            return redirect("collective_cases/".$request->case_id)->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return back()->with("message", sweetalert()->addWarning("បរាជ័យ"));
        }
    }
    public function storeBoth(Request $request){
//        dd($request->all());
        //dd($request->input());
        $dateCreated = myDateTime();
        DB::beginTransaction();
        try{
            /** ================== 1.Insert Case Invitation for employee =================== */
            $searchCaseInvEmp = [
                "case_id" => $request->case_id,
                "invitation_number" => $request->inv_num_emp,
                "invitation_type_id" => $request->invitation_type_employee,
            ];

            $adata = [
                "disputant_id" => $request->disputant_id,
                "company_id" => $request->company_id,
                "meeting_date" => date2DB($request->meeting_date),
                "meeting_time" => $request->meeting_time,
                "invitation_required_doc" => $request->invitation_required_doc_employee,
                "letter_date" => date2DB($request->letter_date),
                "contact_phone" => $request->contact_phone,

                "user_created" => Auth::user()->id,
                "date_created" =>  $dateCreated,
            ];
            //dd($adata);
            CaseInvitation::updateOrCreate($searchCaseInvEmp, $adata);

            /** ================== 2.Insert Case Invitation for company =================== */
            $searchCaseInvCom = [
                "case_id" => $request->case_id,
                "invitation_number" => $request->inv_num_com,
                "invitation_type_id" => $request->invitation_type_company,
            ];

            $adata2 = [
                "disputant_id" => $request->disputant_id,
                "company_id" => $request->company_id,
                "meeting_date" => date2DB($request->meeting_date),
                "meeting_time" => $request->meeting_time,
                "invitation_required_doc" => $request->invitation_required_doc_company,
                "letter_date" => date2DB($request->letter_date),
                "contact_phone" => $request->contact_phone,

                "user_created" => Auth::user()->id,
                "date_created" =>  $dateCreated,
            ];
            //dd($adata);
            CaseInvitation::updateOrCreate($searchCaseInvCom, $adata2);

            DB::commit();
            if(request("json_opt") == 1){ //if request from app
                //return response()->json(['status' => 200, 'message' => 'success', 'data'=> $data]);
            }
            return redirect("collective_cases/".$request->case_id)->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
            //return redirect("invitation");
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $letter = CaseInvitation::where("id", $id)->first();
        $case_id = $letter->case_id;
        $tmp = CaseInvitation::select("id")->where("case_id", $case_id)
            ->whereIn("invitation_type_id", [5, 6])->whereNot("id", $id)->first();
        $data['id_pair'] = !empty($tmp)? $tmp->id : 0;
        $title = $letter->type->employee_or_company == 31? "<span class='text-danger'>កែប្រែលិខិតអញ្ជើញ តំណាងកម្មករនិយោជិត</span>" :
            "<span class='text-danger'>កែប្រែលិខិតអញ្ជើញ តំណាងសហគ្រាស គ្រឹះស្ថាន</span>";
        $data['pagetitle']= $title;
        $data['letter'] = $letter;
        //$data['employee_or_company'] = $letter->invitationType->employee_or_company;
        $view = "case.invitation.update_collectives";
        if(request("json_opt") == 1){ //if request from app
            return response()->json(['status' => 200, 'message' => 'success', 'data'=> $data]);
        }
        return view($view, [ "adata" => $data ]);
    }
    public function editBoth(int $case_id, int $id, int $id_pair)
    {
        $case = Cases::where("id", $case_id)->first();
        $letter = CaseInvitation::where("id", $id)->first();
        $letterPair = CaseInvitation::where("id", $id_pair)->first();
        $data['id_pair'] = $id_pair;
        $title ="<span class='text-danger'>កែលិខិតអញ្ជើញតំណាងកម្មករនិយោជិត និងតំណាងរោងចក្រ សហគ្រាស (មកផ្សះផ្សា)</span>";
        $data['pagetitle']= $title;
        $data["case"] = $case;
        $data['case_id'] = $case_id;
        $data['id'] = $id;
        $data['letterPair'] = $letterPair;
        $data['letter'] = $letter;

        //$data['employee_or_company'] = $letter->invitationType->employee_or_company;
        $view = "case.invitation.update_collectives_both";
        if(request("json_opt") == 1){ //if request from app
            return response()->json(['status' => 200, 'message' => 'success', 'data'=> $data]);
        }
        return view($view, [ "adata" => $data ]);
    }

    public function uploadFile(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'file' => 'required|mimes:jpeg,png,jpg,gif,pdf|max:5148', // Max 5MB image size
        ]);
        $file = $request->file('file');
        $fileName = $request->id."_". time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path(pathToUploadFile('invitation')), $fileName); //public_path()

        CaseInvitation::where("id", $request->id)->update([
            "invitation_file" => $fileName
        ]);
        return response()->json(['message' => 'Upload ជោគជ័យ']);
    }
    public function uploadNextFile(Request $request)
    {
//        dd($request->all());
        $request->validate([
            'file' => 'required|mimes:jpeg,png,jpg,gif,pdf|max:5148', // Max 5MB image size
        ]);
        $file = $request->file('file');
        $fileName = $request->id."_". time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path(pathToUploadFile('invitation/next')), $fileName); //public_path()

        InvitationNextTime::where("id", $request->id)->update([
            "doc_file" => $fileName
        ]);

        return response()->json(['message' => 'Upload ជោគជ័យ']);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
//        dd($request->all());
        //dd($request->input());
        $caseYear = $request->case_year;
        $date_created = myDateTime();
        DB::beginTransaction();
        try{

            /** ================== 1.Update Case Invitation =================== */
            $adata = [
                //"case_id" => $request->case_id,
                //"disputant_id" => $request->disputant_id,
                //"company_id" => $request->company_id,
                "invitation_number" => $request->inv_number,
//                "invitation_type_id" => $request->invitation_type_id,
                "meeting_date" => date2DB($request->meeting_date),
                "meeting_time" => $request->meeting_time,
                "invitation_required_doc" => $request->invitation_required_doc,
                "letter_date" => date2DB($request->letter_date),
                "contact_phone" => $request->contact_phone,

//                "receive_date" => $request->receive_date,
//                "receive_time" => $request->receive_time,
//                "receive_disputant_id" => $disputant_id,
//                "receive_disputant_occupation" => $request->receive_disputant_occupation,
//                "receive_disputant_phone_number" => $request->phone_number,
//                //Address
//                "receive_province" => $request->village,
//                "receive_district" => $request->district,
//                "receive_commune" => $request->commune,
//                "receive_village" => $request->village,
//                "receive_street" => $request->addr_street,
//                "receive_house_no" => $request->addr_house_no,

                "user_updated" => Auth::user()->id,
                "date_updated" =>  $date_created,
            ];
            //dd($adata);
            CaseInvitation::where('id', $id)->update($adata);
            //echo count($request->status);
            //dd($request->all());

            /** Defined Upload File Path */
            $pathToUpload = pathToUploadFile("collectives_invitation/next/".$caseYear."/");
//            $pathToUpload = "collectives_invitation/next/".$caseYear."/";

            /** Add New Next Meeting [ONE New Record]  */
            if(!empty($request->reason) && !empty($request->next_date) && !empty($request->next_time) ){
                $adataNext = [
                    "invitation_id" => $id,
                    "status_id" => $request->status,
                    "reason" => $request->reason,
                    "next_date" => date2DB($request->next_date),
                    "next_time" => $request->next_time,

                    "user_created" => Auth::user()->id,
                    "date_created" =>  $date_created,
                ];
                $result = InvitationNextTime::create($adataNext);
                $nextID = $result->id;//get next_id

//                dd($result);

                /** ===============Blog: Upload Next File ======================== */
                $letter = myUploadFileOnly($request, $pathToUpload, "letter", $nextID, "invitation_next");
//                $letter = uploadFileOnly2($request, $path_to_upload, "letter", $next_id, "letter");
                InvitationNextTime::where('id', $nextID)->update([
                    "letter" => $letter,
                ]);

            }

            /** Update Old Next Meeting [One Or Many Records]  */
            if(isset($request->status_old)){
                /** ===============Blog: Upload Next File ======================== */
                $arrayLetter = myUploadMultiFilesOnly($request, $pathToUpload, "letter_old", $request->next_id_old, "invitation_next");
//                dd($arrayLetter);
                for($i = 0; $i < count($request->status_old); $i++){
                    if(!empty($request->reason_old[$i]) && !empty($request->next_date_old[$i]) && !empty($request->next_time_old[$i]) ){
                        $letter = !empty($arrayLetter[$i])? $arrayLetter[$i] : $request->letter_old_old[$i];
                        $adataNext = [
                            "invitation_id" => $id,
                            "status_id" => $request->status_old[$i],
                            "reason" => $request->reason_old[$i],
                            "next_date" => date2DB($request->next_date_old[$i]),
                            "next_time" => $request->next_time_old[$i],
                            "letter" => $letter,

                            //"user_created" => Auth::user()->id,
                            //"date_created" =>  $date_created,
                            "user_updated" => Auth::user()->id,
                            "date_updated" =>  $date_created,
                        ];

                        InvitationNextTime::where("id", $request->next_id_old[$i])->update($adataNext);

                    }
                }
            }

            DB::commit();
            if(request("json_opt") == 1){ //if request from app
                //return response()->json(['status' => 200, 'message' => 'success', 'data'=> $data]);
            }

            return saveInvitationRedirect($request->input("btnSubmit"), $request->input("case_id"));
//            if($request->input("btnSubmit") == "save"){
//                return back()->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
//            }
//            else{
//                return redirect("cases/".$request->input("case_id"))->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
//            }


        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return back()->with("message", sweetalert()->addWarning("បរាជ័យ"));
        }
    }
    /** កែលិខិតអញ្ជើញកម្មករ និងក្រុមហ៊ុន ទាំងពិរតែម្តង ចុចលើមួយណាក៍មកទីនេះដែរ */
    public function updateBoth(Request $request)
    {
        //dd($request->all());
        //dd($request->input());
        $dateCreated = myDateTime();
        $caseYear = $request->case_year;
        $id = $request->id;
        $idPair = $request->id_pair;
        $invTypeID = $request->invitation_type_id;
        $doc = $invTypeID == 5? $request->invitation_required_doc_employee : $request->invitation_required_doc_company;
        $docPair = $invTypeID == 5? $request->invitation_required_doc_company : $request->invitation_required_doc_employee;

        DB::beginTransaction();
        try{
            /** ================== 2.Update Case Invitation =================== */
            $adata = [
                "invitation_number" => $request->inv_num_emp,
                "meeting_date" => date2DB($request->meeting_date),
                "meeting_time" => $request->meeting_time,
                "invitation_required_doc" => $doc,
                "letter_date" => date2DB($request->letter_date),
                "contact_phone" => $request->contact_phone,

                "user_updated" => Auth::user()->id,
                "date_updated" =>  $dateCreated,
            ];
            //dd($adata);
            CaseInvitation::where("id", $id)->update($adata);

            $adata = [
                "invitation_number" => $request->inv_num_com,
                "meeting_date" => date2DB($request->meeting_date),
                "meeting_time" => $request->meeting_time,
                "invitation_required_doc" => $docPair,
                "letter_date" => date2DB($request->letter_date),
                "contact_phone" => $request->contact_phone,

                "user_updated" => Auth::user()->id,
                "date_updated" =>  $dateCreated,
            ];
            //dd($adata);
            CaseInvitation::where("id", $idPair)->update($adata);
            $pathToUpload = pathToUploadFile('collectives_invitation/next/'.$caseYear."/");
            /** New Next Record Only One  */
            if(!empty($request->reason) && !empty($request->next_date) && !empty($request->next_time) ){
                $adataNext = [
                    "invitation_id" => $id,
                    "status_id" => $request->status,
                    "reason" => $request->reason,
                    "next_date" => date2DB($request->next_date),
                    "next_time" => $request->next_time,

                    "user_created" => Auth::user()->id,
                    "date_created" =>  $dateCreated,
                ];
                $result = InvitationNextTime::create($adataNext);
                $nextID = $result->id;//get next_id
                /** ===============Blog: Upload Next File ======================== */
//                $letter = uploadFileOnly2($request, $pathToUpload, "letter", $next_id, "invitation_next");
                $letter = myUploadFileOnly($request, $pathToUpload, "letter", $nextID, "invitation_next");
                //dd($letter);
                InvitationNextTime::where("id", $nextID)->update([
                    "letter" => $letter,
                ]);
            }
            /** New Next Record Many Record  */
            if(isset($request->status_old)){
                /** ===============Blog: Upload Next File ======================== */
//                $arrayLetter = uploadFileOnlyMulti($request, $pathToUpload, "letter_old", $request->next_id_old, "invitation_next");
                $arrayLetter = myUploadMultiFilesOnly($request, $pathToUpload, "letter_old", $request->next_id_old, "invitation_next");
                //dd($arrayLetter);
                for($i = 0; $i < count($request->status_old); $i++){
                    if(!empty($request->reason_old[$i]) && !empty($request->next_date_old[$i]) && !empty($request->next_time_old[$i]) ){
                        $letter = !empty($arrayLetter[$i])? $arrayLetter[$i] : $request->letter_old_old[$i];
                        $adataNext = [
                            "invitation_id" => $id,
                            "status_id" => $request->status_old[$i],
                            "reason" => $request->reason_old[$i],
                            "next_date" => date2DB($request->next_date_old[$i]),
                            "next_time" => $request->next_time_old[$i],
                            "letter" => $letter,

                            //"user_created" => Auth::user()->id,
                            //"date_created" =>  $date_created,
                            "user_updated" => Auth::user()->id,
                            "date_updated" =>  $dateCreated,
                        ];

                        InvitationNextTime::where("id", $request->next_id_old[$i])->update($adataNext);

                    }
                }
            }

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
       // dd($id);
        DB::beginTransaction();
        try{
            $tmp = explode("_", $id);
            //dd(count($tmp));
            if(count($tmp) > 1){
                $i = 1;
                foreach($tmp as $key => $val){
                    if($i == 1){
                        $case_id = CaseInvitation::select("case_id")->where("id", $val)->first()->case_id;
                    }
                    CaseInvitation::where("id", $val)->delete();
                }

            }
            else{
                $case_id = CaseInvitation::select("case_id")->where("id", $id)->first()->case_id;
                CaseInvitation::where("id", $id)->delete();
            }

            DB::commit();
//            if(request("json_opt") == 1){ //if request from app
//                //return response()->json(['status' => 200, 'message' => 'success', 'data'=> $data]);
//            }
            return redirect("collective_cases/".$case_id)->with("message", sweetalert()->addSuccess("ជោគជ័យ"));

        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return back()->with("message", sweetalert()->addWarning("បរាជ័យ"));
        }
    }
}
