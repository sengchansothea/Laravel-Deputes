<?php

namespace App\Http\Controllers;


use App\Models\CaseInvitation;
use App\Models\CaseLog34;
use App\Models\Cases;
use App\Models\Disputant;
use App\Models\InvitationNextTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvitationController extends Controller
{
    private int $totalRecord = 0;
    private int $perPage = 20;

    function deleteInvitationNext($id)
    {
        //        dd($id);
        $tmp = explode("_", $id);
        $caseYear = $tmp[0];
        $next_id = $tmp[1];
        $invNext = InvitationNextTime::find($next_id);
        if(!empty($invNext)){
            // Delete  record
            $invNext->delete();

            // Delete file
            deleteFile($invNext->letter, pathToUploadFile("invitation/next/".$caseYear."/"));//delete invitation_file
        }
        //        return redirect("invitation/".$invitation_id."/edit")->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
        return back()->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
    }
    /**
     * Display a listing of the resource.
     */
    public function index($invitation_type = 0)
    {
        if(!allowUserAccess()){
            abort(403, 'You do not have permission to access this page.');
        }
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
    function getOrSearchEloquent() //searchCampany
    {
        $letters = CaseInvitation::with([
            'case.caseType',
            'case.caseDisputant',
            'invitationType',
            'disputant',
            'company',
            'caseCompany',
        ]);

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

        $letters = $letters->orderBy("id", "DESC");
        $letters = $letters->paginate($this->perPage);
        $this->totalRecord = $letters->total(); // ✅ Get total after pagination (no extra query)
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
    public function create($case_id = 0, $employee_or_company = 1)
    {
        if(!allowUserAccess($case_id)){
            abort(403, 'You do not have permission to access this page.');
        }

        $case = Cases::where("id", $case_id)->first();
        if($employee_or_company == 1){
            $title = "បង្កើតលិខិតអញ្ជើញសម្រាប់កម្មករនិយោជិត មកផ្ដល់ព័ត៌មាន";
        }elseif($employee_or_company == 2){
            $title = "បង្កើតលិខិតអញ្ជើញសម្រាប់រោងចក្រ សហគ្រាស មកផ្ដល់ព័ត៌មាន";
        }elseif ($employee_or_company == 31 ){
            $title = "បង្កើតលិខិតអញ្ជើញសម្រាប់តំណាងកម្មករនិយោជិត មកផ្ដល់ព័ត៌មាន";
        }elseif ($employee_or_company == 32){
            $title = "បង្កើតលិខិតអញ្ជើញសម្រាប់រោងចក្រ សហគ្រាស មកផ្ដល់ព័ត៌មាន";
        }

        $data['pagetitle']= $title;
        $data['case'] = $case;
        $data['case_id'] = $case_id;
        $data['case_type_id'] = $case->case_type_id;
        $data['employee_or_company'] = $employee_or_company;
        if($case->case_type_id == 3){ //លិខិតអញ្ញើញវិវាទរួម
            $data['invCountPlus1'] = CaseInvitation::where('invitation_type_id','>', 6)->count() + 1;
        }else{ // លិខិតអញ្ញើញវិវាទបុគ្គល
            $data['invCountPlus1'] = CaseInvitation::query()->count() + 1;
        }


        $view = "case.invitation.create";
        if(request("json_opt") == 1){ //if request from app
            return response()->json(['status'=>200,'result'=> $data]);
        }
        return view($view, [ "adata" => $data ]);
    }
    /**
     * Show the form to create both invitation for employee and company (លិខិតផ្សះផ្សា)
     */
    public function createBoth($case_id = 0, $invitation_type_employee = 1, $invitation_type_company = 2)
    {
        if(!allowUserAccess($case_id)){
            abort(403, 'You do not have permission to access this page.');
        }
        $case = Cases::with([
            'disputant',
            'caseDisputant',
            'company',
            'caseCompany'
        ])->where("id", $case_id)->first();
        $case_type_id = $case->case_type_id;
        $title = "បង្កើតលិខិតអញ្ជើញកម្មករនិយោជិត និងរោងចក្រ សហគ្រាស (មកផ្សះផ្សា)";
        $data['pagetitle']= $title;
        $data['case'] = $case;
        $data['case_id'] = $case_id;
        $data['case_type_id'] = $case_type_id;
        $data['invitation_type_employee'] = $invitation_type_employee;
        $data['invitation_type_company'] = $invitation_type_company;
        $data['invCountPlus1'] = CaseInvitation::query()->count() + 1;
        $view = "case.invitation.create_both";
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
        if(!allowUserAccess($request->case_id)){
            abort(403, 'You do not have permission to access this page.');
        }
        $date_created = myDateTime();
        DB::beginTransaction();
        try{
            $invTypeID = $request->invitation_type_id;
            if($invTypeID == 1 || $invTypeID == 4){
                $msg = 'លិខិតអញ្ជើញកម្មករនិយោជិត ផ្ដល់ព័ត៌មាន';
            }elseif($invTypeID == 2 || $invTypeID == 5){
                $msg = 'លិខិតអញ្ជើញសហគ្រាស គ្រឹះស្ថានផ្ដល់ព័ត៌មាន';
            }
            $msgTitle = "បានចេញ". $msg;
            /** ================== 1.Insert/Update Disputant (Invitation Letter) =================== */
            $disputant_id = 0;
            if(!empty($request->id_number) && !empty($request->name) && !empty($request->dob) && !empty($request->nationality) && !empty($request->phone_number)){
                $searchDisputant = ["id_number" => $request->id_number];
                $adataDisputant = [
                    "name" => $request->name,
                    "gender" => $request->gender,
                    "dob" => date2DB($request->dob),
                    "nationality" => $request->nationality,
                    //"id_number" => $request->id_number,
                    "phone_number" => $request->phone_number,
                    "phone_number2" => $request->phone_number2,

                    "house_no" => $request->addr_house_no,
                    "street" => $request->addr_street,
                    "group_name" => $request->group_name,
                    "village" => $request->village,
                    "commune" => $request->commune,
                    "district" => $request->district,
                    "province" => $request->province,

                    "pob_commune_id" => $request->pob_commune_id,
                    "pob_district_id" => $request->pob_district_id,
                    "pob_province_id" => $request->pob_province_id,

                    "user_created" => Auth::user()->id,
                    "user_updated" => Auth::user()->id,
                    "date_created" =>  $date_created,
                    "date_updated" =>  $date_created,
                ];

                //                dd($adataDisputant);
                $result = Disputant::updateOrCreate($searchDisputant, $adataDisputant);

                dd($result);
                $disputant_id = !empty($result)? $result->id : 0;//get disputant_id
            }
            /** ================== 2.Insert Case Invitation =================== */
            $adata = [
                "case_id" => $request->case_id,
                "disputant_id" => $request->disputant_id,
                "company_id" => $request->company_id,
                "invitation_number" => $request->inv_number,
                "invitation_type_id" => $request->invitation_type_id,
                "meeting_date" => date2DB($request->meeting_date),
                "meeting_time" => $request->meeting_time,
                //"meeting_for" => $request->meeting_for,
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

                "user_created" => Auth::user()->id,
                "date_created" =>  $date_created,
            ];
            CaseInvitation::create($adata);
            $currentCase = Cases::find($request->case_id);
            caseStatusTelegramNotification($currentCase, $msgTitle);

            DB::commit();
            if(request("json_opt") == 1){ //if request from app
                //return response()->json(['status' => 200, 'message' => 'success', 'data'=> $data]);
            }
            return redirect("cases/".$request->case_id)->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return back()->with("message", sweetalert()->addWarning("បរាជ័យ"));
        }
    }
    public function storeBoth(Request $request)
    {
        if(!allowUserAccess($request->case_id)){
            abort(403, 'You do not have permission to access this page.');
        }
        //        dd($request->all());

        $date_created = myDateTime();
        DB::beginTransaction();
        try{
                        /** ================== .Insert/Update Disputant (Receive Letter) =================== */
            //            $disputant_id = 0;
            //            if(!empty($request->id_number) && !empty($request->name) && !empty($request->dob) && !empty($request->nationality) && !empty($request->phone_number)){
            //                $searchDisputant = ["id_number" => $request->id_number];
            //                $adataDisputant = [
            //                    "name" => $request->name,
            //                    "gender" => $request->gender,
            //                    "dob" => date2DB($request->dob),
            //                    "nationality" => $request->nationality,
            //                    //"id_number" => $request->id_number,
            //                    "phone_number" => $request->phone_number,
            //
            //                    "house_no" => $request->addr_house_no,
            //                    "street" => $request->addr_street,
            //                    "group_name" => $request->group_name,
            //                    "village" => $request->village,
            //                    "commune" => $request->commune,
            //                    "district" => $request->district,
            //                    "province" => $request->province,
            //
            //                    "pob_commune_id" => $request->pob_commune_id,
            //                    "pob_district_id" => $request->pob_district_id,
            //                    "pob_province_id" => $request->pob_province_id,
            //
            //                    "user_created" => Auth::user()->id,
            //                    "user_updated" => Auth::user()->id,
            //                    "date_created" =>  $date_created,
            //                    "date_updated" =>  $date_created,
            //                ];
            //                $result = Disputant::updateOrCreate($searchDisputant, $adataDisputant);
            //                $disputant_id = !empty($result)? $result->id : 0;//get disputant_id
            //            }
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
                "date_created" =>  $date_created,
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
                "date_created" =>  $date_created,
            ];
            //dd($adata);
            CaseInvitation::updateOrCreate($searchCaseInvCom, $adata2);
            $msgTitle = 'បានបង្កើតលិខិតអញ្ជើញ កម្មករនិយោជិត និងរោងចក្រ សហគ្រាស មកផ្សះផ្សា';
            $currentCase = Cases::find($request->case_id);
            caseStatusTelegramNotification($currentCase, $msgTitle);

            DB::commit();
            if(request("json_opt") == 1){ //if request from app
                //return response()->json(['status' => 200, 'message' => 'success', 'data'=> $data]);
            }
            return redirect("cases/".$request->case_id)->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
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
        $invitation = CaseInvitation::with('type')->findOrFail($id);
        $pairedInvitation = CaseInvitation::select('id')
            ->where('case_id', $invitation->case_id)
            ->whereIn('invitation_type_id', [5, 6])
            ->where('id', '!=', $id)
            ->first();
        $isEmployee = $invitation->type->employee_or_company == 1;

        $data = [
            'pagetitle' => $isEmployee
                ? "<span class='text-danger'>កែប្រែលិខិតអញ្ជើញកម្មករនិយោជិត</span>"
                : "<span class='text-danger'>កែប្រែលិខិតអញ្ជើញសហគ្រាស គ្រឹះស្ថាន</span>",
            'letter' => $invitation,
            'id_pair' => $pairedInvitation?->id ?? 0,
        ];


        $view = "case.invitation.update";
        if(request("json_opt") == 1){ //if request from app
            return response()->json(['status' => 200, 'message' => 'success', 'data'=> $data]);
        }
        return view($view, [ "adata" => $data ]);
    }
    public function editBoth(int $case_id, int $id, int $id_pair)
    {
        $case = Cases::with([
            'invitationForConcilationEmployee', // eager-load employee invitation
            'invitationForConcilationCompany',  // eager-load company invitation
        ])->findOrFail($case_id);

        $letter = CaseInvitation::with(['nextTime'])->findOrFail($id);
        $letterPair = CaseInvitation::findOrFail($id_pair);
        $user = auth()->user();
        $data = [
            'pagetitle' => "<span class='text-danger'>កែលិខិតអញ្ជើញកម្មករនិយោជិត និងរោងចក្រ សហគ្រាស (មកផ្សះផ្សា)</span>",
            'case' => $case,
            'case_id' => $case_id,
            'id' => $id,
            'id_pair' => $id_pair,
            'letter' => $letter,
            'letterPair' => $letterPair,
            'user' => $user,
            'chkAllowAccess' => allowAccessFromHeadOffice(),
            'arrOfficerIDs' => getCaseOfficerIDs($case->id),
        ];

        //$data['employee_or_company'] = $letter->invitationType->employee_or_company;
        $view = "case.invitation.update_both";
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
            "letter" => $fileName
        ]);

        return response()->json(['message' => 'Upload ជោគជ័យ']);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if(!allowUserAccess($request->case_id)){
            abort(403, 'You do not have permission to access this page.');
        }

        //        dd($request->all());
        //dd($request->input());
        $caseYear = $request->case_year;
        $date_created = myDateTime();

        DB::beginTransaction();
        try{

            $invTypeID = $request->invitation_type_id;
            if($invTypeID == 1 || $invTypeID == 4){
                $msg = 'លិខិតអញ្ជើញកម្មករនិយោជិត ផ្ដល់ព័ត៌មាន';
            }elseif($invTypeID == 2 || $invTypeID == 5){
                $msg = 'លិខិតអញ្ជើញសហគ្រាស គ្រឹះស្ថានផ្ដល់ព័ត៌មាន';
            }
            $msgTitle = "បានកែប្រែ". $msg;

            /** ================== 1.Insert/Update Disputant (Receive Letter) =================== */
            $disputant_id = 0;
            if(!empty($request->id_number) && !empty($request->name) && !empty($request->dob) && !empty($request->nationality) && !empty($request->phone_number)){
                $searchDisputant = ["id_number" => $request->id_number];
                $adataDisputant = [
                    "name" => $request->name,
                    "gender" => $request->gender,
                    "dob" => date2DB($request->dob),
                    "nationality" => $request->nationality,
                    //"id_number" => $request->id_number,
                    "phone_number" => $request->phone_number,

                    "house_no" => $request->addr_house_no,
                    "street" => $request->addr_street,
                    "group_name" => $request->group_name,
                    "village" => $request->village,
                    "commune" => $request->commune,
                    "district" => $request->district,
                    "province" => $request->province,

                    "pob_commune_id" => $request->pob_commune_id,
                    "pob_district_id" => $request->pob_district_id,
                    "pob_province_id" => $request->pob_province_id,

                    "user_created" => Auth::user()->id,
                    "user_updated" => Auth::user()->id,
                    "date_created" =>  $date_created,
                    "date_updated" =>  $date_created,
                ];
                $result = Disputant::updateOrCreate($searchDisputant, $adataDisputant);
                $disputant_id = !empty($result)? $result->id : 0;//get disputant_id
            }
            /** ================== 2.Update Case Invitation =================== */
            $adata = [
                //"case_id" => $request->case_id,
                //"disputant_id" => $request->disputant_id,
                //"company_id" => $request->company_id,
                "invitation_number" => $request->inv_number,
                "invitation_type_id" => $request->invitation_type_id,
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
            CaseInvitation::where("id", $id)->update($adata);

            /** New Next Record Only One  */
            $path_to_upload = pathToUploadFile("invitation/next/".$caseYear."/");
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
                $next_id = $result->id;//get next_id
                /** ===============Blog: Upload Next File ======================== */
                //                $letter = uploadFileOnly2($request, $path_to_upload, "letter", $next_id, "letter");
                $letter = myUploadFileOnly($request, $path_to_upload, "letter", $next_id, "invitation_next");
                //dd($letter);
                InvitationNextTime::where("id", $next_id)->update([
                    "letter" => $letter,
                ]);

            }

            /** New Next Record Many Record  */
            if(isset($request->status_old)){
                /** ===============Blog: Upload Next File ======================== */
                //                $arrayLetter = uploadFileOnlyMulti($request, "invitation/next/".$caseYear."/", "letter_old", $request->next_id_old, "letter");
                $arrayLetter = myUploadMultiFilesOnly($request, $path_to_upload, "letter_old", $request->next_id_old, "invitation_next");
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
                            "date_updated" =>  $date_created,
                        ];

                        InvitationNextTime::where("id", $request->next_id_old[$i])->update($adataNext);
                    }
                }
            }

            $currentCase = Cases::find($request->case_id);
            caseStatusTelegramNotification($currentCase, $msgTitle);

            DB::commit();
            if(request("json_opt") == 1){ //if request from app
                //return response()->json(['status' => 200, 'message' => 'success', 'data'=> $data]);
            }

            return saveRedirect($request->input("btnSubmit"), $request->input("case_id"));
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
        if(!allowUserAccess($request->case_id)){
            abort(403, 'You do not have permission to access this page.');
        }
        //dd($request->all());
        //dd($request->input());
        $date_created = myDateTime();
        $caseYear = $request->case_year;
        $id = $request->id;
        $id_pair = $request->id_pair;
        $invitation_type_id = $request->invitation_type_id;
        $doc = $invitation_type_id == 5? $request->invitation_required_doc_employee : $request->invitation_required_doc_company;
        $docPair = $invitation_type_id == 5? $request->invitation_required_doc_company : $request->invitation_required_doc_employee;

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
                "date_updated" =>  $date_created,
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
                "date_updated" =>  $date_created,
            ];
            //dd($adata);
            CaseInvitation::where("id", $id_pair)->update($adata);
            $path_to_upload = pathToUploadFile("invitation/next/".$caseYear."/");

            /** New Next Record Only One  */
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
                $next_id = $result->id;//get next_id
                /** ===============Blog: Upload Next File ======================== */
                //                $letter = uploadFileOnly2($request, $path_to_upload, "letter", $next_id, "letter");
                $letter = myUploadFileOnly($request, $path_to_upload, "letter", $next_id, "invitation_next");
                //dd($letter);
                InvitationNextTime::where("id", $next_id)->update([
                    "letter" => $letter,
                ]);

            }

            /** New Next Record Many Record  */
            if(isset($request->status_old)){
                /** ===============Blog: Upload Next File ======================== */
                //                $arrayLetter = uploadFileOnlyMulti($request, "invitation/next", "letter_old", $request->next_id_old, "letter");
                $arrayLetter = myUploadMultiFilesOnly($request, $path_to_upload, "letter_old", $request->next_id_old, "invitation_next");
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
                            "date_updated" =>  $date_created,
                        ];

                        InvitationNextTime::where("id", $request->next_id_old[$i])->update($adataNext);

                    }
                }
            }

            $msgTitle = 'បានកែប្រែលិខិតអញ្ជើញ កម្មករនិយោជិត និងរោងចក្រ សហគ្រាស មកផ្សះផ្សា';
            $currentCase = Cases::find($request->case_id);
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
    public function destroy(string $id)
    {
        if(!allowUserAccess()){
            abort(403, 'You do not have permission to access this page.');
        }
       // dd($id);
        DB::beginTransaction();
        try{
            $tmp = explode("_", $id);
        //            dd(count($tmp));
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
                $currentCaseInv = CaseInvitation::find($case_id);
                $invTypeID = $currentCaseInv->invitation_type_id;
                if($invTypeID == 1 || $invTypeID == 4){
                    $msg = 'លិខិតអញ្ជើញកម្មករនិយោជិត ផ្ដល់ព័ត៌មាន';
                }elseif($invTypeID == 2 || $invTypeID == 5){
                    $msg = 'លិខិតអញ្ជើញសហគ្រាស គ្រឹះស្ថានផ្ដល់ព័ត៌មាន';
                }
                $msgTitle = "បានលុប". $msg;

                CaseInvitation::where("id", $id)->delete();
            }
            $currentCase = Cases::find($case_id);
            caseStatusTelegramNotification($currentCase, $msgTitle);

            DB::commit();
        //            if(request("json_opt") == 1){ //if request from app
        //                //return response()->json(['status' => 200, 'message' => 'success', 'data'=> $data]);
        //            }
            return redirect("cases/".$case_id)->with("message", sweetalert()->addSuccess("ជោគជ័យ"));

        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return back()->with("message", sweetalert()->addWarning("បរាជ័យ"));
        }
    }
}
