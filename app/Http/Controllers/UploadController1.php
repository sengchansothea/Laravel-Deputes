<?php

namespace App\Http\Controllers;

use App\Models\CaseInvitation;
use App\Models\CaseLog34;
use App\Models\CaseLog5;
use App\Models\CaseLog6;
use App\Models\Cases;
use App\Models\InvitationNextTime;
use Illuminate\Http\Request;

class UploadController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    public function formUploadFileAll($url_opt, $case_id, $id)
    {
//        dd($url_opt);
        $url = "";
        $pageTitle = "Upload លិខិត";
        $file_name = "";
        $view = "case.form_upload_file_all";

        if($url_opt == 1){
            $pageTitle = "Upload លិខិត";
            $file_name = "";
        }
        elseif($url_opt == 2){
            $pageTitle = "Upload លិខិត";
            $file_name = "";
        }
        elseif($url_opt == 3){
            $pageTitle = "Upload លិខិត";
            $file_name = "";
        }
        elseif($url_opt == 33){
            $pageTitle = "Upload លិខិតលើកពេល ដែលមានចុះហត្ថលេខាទទួល";
            $file_name = "";
        }
        elseif($url_opt == 4){
            $pageTitle = "Upload កំណត់ហេតុដែលបានចុះហត្ថលេខា ផ្ដិតមេដៃ និងវាយត្រាឈ្មោះ";
            $file_name = "";
        }
        elseif($url_opt == 5){
            $pageTitle = "Upload លិខិត";
            $file_name = "";
        }
        elseif($url_opt == 55){
            $pageTitle = "Upload លិខិតលើកពេល ដែលមានចុះហត្ថលេខាទទួល";
            $file_name = "";
        }
        elseif($url_opt == 6){
            $pageTitle = "Upload កំណត់ហេតុដែលបានចុះហត្ថលេខា ផ្ដិតមេដៃ និងវាយត្រាឈ្មោះ";
            $file_name = "";
        }
        elseif($url_opt == 7){
            $pageTitle = "Upload លិខិតអញ្ជើញដែលមានចុះហត្ថលេខាទទួល";
            $file_name = "";
        }
        elseif($url_opt == 77){
            $pageTitle = "Upload លិខិតអញ្ជើញដែលមានចុះហត្ថលេខាទទួល";
            $file_name = "";
        }
        elseif($url_opt == 8){
            $pageTitle = "Upload កំណត់ហេតុដែលបានចុះហត្ថលេខា ផ្ដិតមេដៃ និងវាយត្រាឈ្មោះ";
            $file_name = "";
        }
        elseif($url_opt == 81){
            $pageTitle = "Upload កំណត់ហេតុដែលបានចុះហត្ថលេខា ផ្ដិតមេដៃ និងវាយត្រាឈ្មោះ";
            $file_name = "status_letter";
        }
        elseif($url_opt == 82){ // ReOpen Case
            $pageTitle = "ការសុំផ្សះផ្សាឡើងវិញ";
            $file_name = "status_letter";
            $data['log6'] = CaseLog6::where("id", $id)->first();
            $view = "case.log.log6.reopen_log6";
        }
        elseif($url_opt == 83){ // កែប្រែព័ត៌មានលើកពេលផ្សះផ្សា
            $pageTitle = "កែប្រែព័ត៌មានសុំលើកពេលផ្សះផ្សា";
            $file_name = "status_letter";
            $data['log6'] = CaseLog6::where("id", $id)->first();
            $view = "case.log.log6.reopen3_log6";
        }
        elseif($url_opt == 84){ // កែប្រែព័ត៌មានសុំផ្សះផ្សាឡើងវិញ
            $pageTitle = "កែប្រែព័ត៌មានសុំផ្សះផ្សាឡើងវិញ";
            $file_name = "status_letter";
            $data['log6'] = CaseLog6::where("id", $id)->first();
            $view = "case.log.log6.reopen3_log6";
        }

        $url= url("uploads/".$case_id);
        $caseData = Cases::where('id', $case_id)->first();

        $data['pagetitle']= $pageTitle;
        $data['case_id'] = $case_id;
        $data['case_type'] = $caseData->case_type_id;
        $data['case_year'] = !empty($caseData->case_date) ? date2Display($caseData->case_date,'Y') : myDate('Y');
        $data['id'] = $id;
        $data['url'] = $url;
        $data['url_opt'] = $url_opt;
        $data['file_name'] = $file_name;


//        if(request("json_opt") == 1){ //if request from app
//            return response()->json(['status' => 200, 'message' => 'success', 'data'=> $data]);
//        }
        return view($view, [ "adata" => $data ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
//        dd($request->all());
        $caseYear = $request->case_year;
        $caseType = $request->case_type;

        //dd($request->input());
        if( !(request("url_opt") == 83 || request("url_opt") == 84) ){
            $request->validate([
//                'file' => 'required|mimes:jpeg,png,jpg,gif,pdf|max:5148', // Max 5MB image size
                'file' => 'required|mimes:pdf,PDF|max:15360', // Max 5MB image size
            ]);
            $file = $request->file('file');
            $name = !empty(request('file_name'))? request('file_name') : time();
            $fileName = $request->id."_". $name . '.' . $file->getClientOriginalExtension();
        }


        if(request("url_opt") == 1){
//            $file->move(public_path(pathToUploadFile('case_doc/form1/'.$caseYear."/")), $fileName); //public_path()
            $name = "case_file";
            $fileName = $request->id."_". $name . '.' . $file->getClientOriginalExtension();
            if($caseType == 3){
                $pathToUpload = pathToUploadFile('case_doc/collectives/'.$caseYear."/");
                $file->move(public_path($pathToUpload), $fileName);
                Cases::where("id", $request->id)->update(["collectives_case_file" => $fileName]);
            }else{
                $pathToUpload = pathToUploadFile('case_doc/form1/'.$caseYear."/");
                /** If Directory is not Found, Let's create the new ONE */
                if (!is_dir($pathToUpload)) {

                    mkdir($pathToUpload, 0777, true);  // Creates the folder with appropriate permissions
                }
                $file->move(public_path($pathToUpload), $fileName);
                Cases::where("id", $request->id)->update(["case_file" => $fileName]);
            }
//            dd($pathToUpload);

        }
        elseif(request("url_opt") == 3){
//            $file->move(public_path(pathToUploadFile('invitation')), $fileName); //public_path()
            $name = "employee_inv";
            $fileName = $request->id."_". $name . '.' . $file->getClientOriginalExtension();
            if($caseType == 3){
                $pathToUpload = pathToUploadFile('collectives_invitation/'.$caseYear."/");
            }else{
                $pathToUpload = pathToUploadFile('invitation/'.$caseYear."/");
            }
            /** If Directory is not Found, Let's create the new ONE */
            if (!is_dir($pathToUpload)) {
                mkdir($pathToUpload, 0777, true);  // Creates the folder with appropriate permissions
            }
            $file->move(public_path($pathToUpload), $fileName);
            CaseInvitation::where("id", $request->id)->update(["invitation_file" => $fileName]);
        }
        elseif(request("url_opt") == 33){
//            $file->move(public_path(pathToUploadFile('invitation/next')), $fileName); //public_path()
            $name = "invitation_next";
            $fileName = $request->id."_". $name . '.' . $file->getClientOriginalExtension();
            if($caseType == 3){
                $pathToUpload = pathToUploadFile('collectives_invitation/next/'.$caseYear."/");
            }else{
                $pathToUpload = pathToUploadFile('invitation/next/'.$caseYear."/");
            }
            /** If Directory is not Found, Let's create the new ONE */
            if (!is_dir($pathToUpload)) {
                mkdir($pathToUpload, 0777, true);  // Creates the folder with appropriate permissions
            }
            $file->move(public_path($pathToUpload), $fileName);
            InvitationNextTime::where("id", $request->id)->update(["letter" => $fileName]);
        }
        elseif(request("url_opt") == 4){
            $name = "employee_log";
            $fileName = $request->id."_". $name . '.' . $file->getClientOriginalExtension();
//            $file->move(public_path(pathToUploadFile('case_doc/log34')), $fileName); //public_path()
            if($caseType == 3){
                $pathToUpload = pathToUploadFile('case_doc/collectives/log34/'.$caseYear."/");
            }else{
                $pathToUpload = pathToUploadFile('case_doc/log34/'.$caseYear."/");
            }
            /** If Directory is not Found, Let's create the new ONE */
            if (!is_dir($pathToUpload)) {
                mkdir($pathToUpload, 0777, true);  // Creates the folder with appropriate permissions
            }
            $file->move(public_path($pathToUpload), $fileName);
            CaseLog34::where("id", $request->id)->update(["log_file" => $fileName]);
        }
        elseif(request("url_opt") == 5){
//            $file->move(public_path(pathToUploadFile('invitation')), $fileName); //public_path()
            $name = "company_inv";
            $fileName = $request->id."_". $name . '.' . $file->getClientOriginalExtension();
            if($caseType == 3){
                $pathToUpload = pathToUploadFile('collectives_invitation/'.$caseYear."/");
            }else{
                $pathToUpload = pathToUploadFile('invitation/'.$caseYear."/");
            }
            /** If Directory is not Found, Let's create the new ONE */
            if (!is_dir($pathToUpload)) {
                mkdir($pathToUpload, 0777, true);  // Creates the folder with appropriate permissions
            }
            $file->move(public_path($pathToUpload), $fileName);
            CaseInvitation::where("id", $request->id)->update(["invitation_file" => $fileName]);
        }
        elseif(request("url_opt") == 55){
//            $file->move(public_path(pathToUploadFile('invitation/next')), $fileName); //public_path()
            $name = "invitation_next";
            $fileName = $request->id."_". $name . '.' . $file->getClientOriginalExtension();
            if($caseType == 3){
                $pathToUpload = pathToUploadFile('collectives_invitation/next/'.$caseYear."/");
            }else{
                $pathToUpload = pathToUploadFile('invitation/next/'.$caseYear."/");
            }
            /** If Directory is not Found, Let's create the new ONE */
            if (!is_dir($pathToUpload)) {
                mkdir($pathToUpload, 0777, true);  // Creates the folder with appropriate permissions
            }
            $file->move(public_path($pathToUpload), $fileName);
            InvitationNextTime::where("id", $request->id)->update(["letter" => $fileName]);
        }
        elseif(request("url_opt") == 6){
//            $file->move(public_path(pathToUploadFile('case_doc/log5')), $fileName); //public_path()
            $name = "company_log";
            $fileName = $request->id."_". $name . '.' . $file->getClientOriginalExtension();
            if($caseType == 3){
                $pathToUpload = pathToUploadFile('case_doc/collectives/log5/'.$caseYear."/");
            }else{
                $pathToUpload = pathToUploadFile('case_doc/log5/'.$caseYear."/");
            }
            /** If Directory is not Found, Let's create the new ONE */
            if (!is_dir($pathToUpload)) {
                mkdir($pathToUpload, 0777, true);  // Creates the folder with appropriate permissions
            }
            $file->move(public_path($pathToUpload), $fileName);
            CaseLog5::where("id", $request->id)->update(["log_file" => $fileName]);
        }
        elseif(request("url_opt") == 7){
//            $file->move(public_path(pathToUploadFile('invitation')), $fileName); //public_path()
            $name = "invitation_both";
            $fileName = $request->id."_". $name . '.' . $file->getClientOriginalExtension();
            if($caseType == 3){
                $pathToUpload = pathToUploadFile('collectives_invitation/'.$caseYear."/");
            }else{
                $pathToUpload = pathToUploadFile('invitation/'.$caseYear."/");
            }
            /** If Directory is not Found, Let's create the new ONE */
            if (!is_dir($pathToUpload)) {
                mkdir($pathToUpload, 0777, true);  // Creates the folder with appropriate permissions
            }
            $file->move(public_path($pathToUpload), $fileName);
            CaseInvitation::where("id", $request->id)->update(["invitation_file" => $fileName]);
        }
        elseif(request("url_opt") == 77){
//            $file->move(public_path(pathToUploadFile('invitation/next')), $fileName); //public_path()
            $name = "invitation_next";
            $fileName = $request->id."_". $name . '.' . $file->getClientOriginalExtension();
            if($caseType == 3){
                $pathToUpload = pathToUploadFile('collectives_invitation/next/'.$caseYear."/");
            }else{
                $pathToUpload = pathToUploadFile('invitation/next/'.$caseYear."/");
            }
            /** If Directory is not Found, Let's create the new ONE */
            if (!is_dir($pathToUpload)) {
                mkdir($pathToUpload, 0777, true);  // Creates the folder with appropriate permissions
            }
            $file->move(public_path($pathToUpload), $fileName);
            InvitationNextTime::where("id", $request->id)->update(["letter" => $fileName]);
        }
        elseif(request("url_opt") == 8){
//            $file->move(public_path(pathToUploadFile('case_doc/log6')), $fileName); //public_path()
            $name = "conflict_log";
            $fileName = $request->id."_". $name . '.' . $file->getClientOriginalExtension();
            if($caseType == 3){
                $pathToUpload = pathToUploadFile('case_doc/collectives/log6/'.$caseYear."/");
            }else{
                $pathToUpload = pathToUploadFile('case_doc/log6/'.$caseYear."/");
            }
            /** If Directory is not Found, Let's create the new ONE */
            if (!is_dir($pathToUpload)) {
                mkdir($pathToUpload, 0777, true);  // Creates the folder with appropriate permissions
            }
            $file->move(public_path($pathToUpload), $fileName);
            CaseLog6::where("id", $request->id)->update(["log_file" => $fileName]);
        }
        elseif(request("url_opt") == 81){
//            $file->move(public_path(pathToUploadFile('case_doc/log6/status_letter')), $fileName); //public_path()
            $name = "status_letter";
            $fileName = $request->id."_". $name . '.' . $file->getClientOriginalExtension();
            if($caseType == 3){
                $pathToUpload = pathToUploadFile('case_doc/collectives/log6/status_letter/'.$caseYear."/");
            }else{
                $pathToUpload = pathToUploadFile('case_doc/log6/status_letter/'.$caseYear."/");
            }
            /** If Directory is not Found, Let's create the new ONE */
            if (!is_dir($pathToUpload)) {
                mkdir($pathToUpload, 0777, true);  // Creates the folder with appropriate permissions
            }
            $file->move(public_path($pathToUpload), $fileName);
            CaseLog6::where("id", $request->id)->update([
                "status_letter" => $fileName
            ]);
        }
        elseif(request("url_opt") == 82){ // reopen case
//            $file->move(public_path(pathToUploadFile('case_doc/log6/status_letter')), $fileName); //public_path()
            $name = "reopen_letter";
            $fileName = $request->id."_". $name . '.' . $file->getClientOriginalExtension();
            if($caseType == 3){
                $pathToUpload = pathToUploadFile('case_doc/collectives/log6/status_letter/'.$caseYear."/");
            }else{
                $pathToUpload = pathToUploadFile('case_doc/log6/status_letter/'.$caseYear."/");
            }
            /** If Directory is not Found, Let's create the new ONE */
            if (!is_dir($pathToUpload)) {
                mkdir($pathToUpload, 0777, true);  // Creates the folder with appropriate permissions
            }
            $file->move(public_path($pathToUpload), $fileName);

            CaseLog6::where("id", $request->id)->update([
                "reopen_status" => 1,
                "status_date" => date2DB($request->status_date),

                "status_time" => $request->status_time,
                "status_letter" => $fileName
            ]);
        }
        elseif(request("url_opt") == 83){ // កែប្រែព័ត៌មានសុំលើកពេលផ្សះផ្សា
            CaseLog6::where("id", $request->id)->update([
                "reopen_status" => 0,
                "status_date" => date2DB($request->status_date),
                "status_time" => $request->status_time,
            ]);
        }
        elseif(request("url_opt") == 84){ // កែប្រែព័ត៌មានសុំផ្សះផ្សាឡើងវិញ
            CaseLog6::where("id", $request->id)->update([
                "reopen_status" => 1,
                "status_date" => date2DB($request->status_date),
                "status_time" => $request->status_time,
            ]);
        }

//        dd($caseType);

        if(request('form_upload') == "normal"){ // upload from normal form
            if($caseType == 3){
                return redirect("collective_cases/".request('case_id'))->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
            }else{
                return redirect("cases/".request('case_id'))->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
            }

        }
        else{ // upload from popup form
            return response()->json(['message' => 'Upload ជោគជ័យ']);
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
