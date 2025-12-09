<?php

use App\Models\ArticleOfCompany;
use App\Models\CaseDisputant;
use App\Models\CaseInvitation;
use App\Models\CaseLog5Union1;
use App\Models\CaseLogAttendant;
use App\Models\CaseOfficer;
use App\Models\Cases;
use App\Models\CaseSteps;
use App\Models\CaseType;
use App\Models\Department;
use App\Models\Disputant;
use App\Models\InvitationType;
use App\Models\Log624;
use App\Models\Log625;
use App\Models\Log6Status;
use App\Models\NeaIsic;
use App\Models\ObjectiveCase;
use App\Models\Officer;
use App\Models\Unit;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


function reportDashboard($month){

}
/** date: 26-03-2024 */
function saveRedirect($btn = "save", $case_id = 0){
    $result="";
    if($btn == "save"){
        $result = back()->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
    }
    else{
        $result = redirect("cases/".$case_id)->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
    }
    return $result;
}

function saveCollectiveShowCaseRedirect($btn = "save", $case_id = 0){
    $result="";
    if($btn == "save"){
        $result = back()->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
    }
    else{
        $result = redirect("collective_cases/".$case_id)->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
    }
    return $result;
}

function saveInvitationRedirect($btn = "save", $case_id = 0){
    $result="";
    if($btn == "save"){
        $result = back()->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
    }
    else{
        $result = redirect("collective_cases/".$case_id)->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
    }
    return $result;
}

function arrayCaseSteps($showDefault = 0, $defValue = "0", $defLabel = "សូមជ្រើសរើស"){
    $data= CaseSteps::orderby("id", "ASC")
        ->select(
            DB::raw("step AS name, id AS id")
        )
        //->limit(1000)
        ->pluck("name", "id")->toArray();
    if($showDefault > 0){
        $result= array($defValue => $defLabel);
        $result += $data;
        $data=$result;
    }
    return $data;
}

function arrayLog624($showDefault=0, $defValue="0", $defLabel="សូមជ្រើសរើស"){
    $data= Log624::orderby("id", "ASC")
        ->select(
            DB::raw("cause_name AS name, id AS id")
        )
        //->limit(1000)
        ->pluck("name", "id")->toArray();
    if($showDefault > 0){
        $result= array($defValue => $defLabel);
        $result += $data;
        $data=$result;
    }
    return $data;
}
function arrayLog625($showDefault=0, $defValue="0", $defLabel="សូមជ្រើសរើស"){
    $data= Log625::orderby("id", "ASC")
        ->select(
            DB::raw("solution_name AS name, id AS id")
        )
        //->limit(1000)
        ->pluck("name", "id")->toArray();
    if($showDefault > 0){
        $result= array($defValue => $defLabel);
        $result += $data;
        $data=$result;
    }
    return $data;
}



function arrayInvitationType($case_type_id = 1, $employee_or_company = 1, $type_group_id = 0, $showDefault=0, $defValue="0", $defLabel="សូមជ្រើសរើស"){
    $data= InvitationType::orderby("id", "ASC")
        ->select(
            DB::raw("invitation_type_name AS name, id AS id")
        )->where("case_type_id", $case_type_id)
        ->where("employee_or_company", $employee_or_company);
        //->limit(1000)
    if($type_group_id > 0){
        $data = $data->where("type_group_id", $type_group_id);
    }
     $data = $data->pluck("name", "id")->toArray();
    if($showDefault > 0){
        $result= array($defValue => $defLabel);
        $result += $data;
        $data = $result;
    }
    return $data;
}
function arrayInvitationTypeOLD($case_type_id = 1, $employee_or_company = 1, $showDefault=0, $defValue="0", $defLabel="សូមជ្រើសរើស"){
    $data= InvitationType::orderby("id", "ASC")
        ->select(
            DB::raw("invitation_type_name AS name, id AS id")
        )->where("case_type_id", $case_type_id)
        ->where("employee_or_company", $employee_or_company)
        //->limit(1000)
        ->pluck("name", "id")->toArray();
    if($showDefault > 0){
        $result= array($defValue => $defLabel);
        $result += $data;
        $data=$result;
    }
    return $data;
}
function arrayArticleOfCompany($showDefault=0, $defValue="0", $defLabel="សូមជ្រើសរើស"){
    $data= ArticleOfCompany::orderby("id", "ASC")
        ->select(
            DB::raw("article_name AS name, id AS id")
        )
        //->limit(1000)
        ->pluck("name", "id")->toArray();
    if($showDefault > 0){
        $result= array($defValue => $defLabel);
        $result += $data;
        $data=$result;
    }
    return $data;
}
function arrayDepartment($department_id = 0, $showDefault=0, $defValue="0", $defLabel="សូមជ្រើសរើស"){
    $data= Department::orderby("id", "ASC")
        ->select(
            DB::raw("department_name AS name, id AS id")
        );
        //->limit(1000)
    if($department_id > 0){
        $data = $data->where("id", $department_id);
    }
    $data = $data->pluck("name", "id")->toArray();
    if($showDefault > 0){
        $result= array($defValue => $defLabel);
        $result += $data;
        $data=$result;
    }
    return $data;
}
function arrayLog6Status($status_id = 0, $showDefault=0, $defValue="0", $defLabel="សូមជ្រើសរើស"){
    $data= Log6Status::orderby("id", "ASC")
        ->select(
            DB::raw("status_name AS name, id AS id")
        );
    //->limit(1000)
    if($status_id > 0){
        $data = $data->where("id", $status_id);
    }
    $data = $data->pluck("name", "id")->toArray();
    if($showDefault > 0){
        $result= array($defValue => $defLabel);
        $result += $data;
        $data=$result;
    }
    return $data;
}
function arrayLog6StatusExclude($arrayExclude = [], $showDefault=0, $defValue="0", $defLabel="សូមជ្រើសរើស"){
    //dd($arrayExclude);
    $data= Log6Status::orderby("id", "ASC")
        ->select(
            DB::raw("status_name AS name, id AS id")
        );
    if(!empty($arrayExclude)){
        $data->whereNotIn('id', $arrayExclude);
    }

    //->limit(1000)
//    if($status_id > 0){
//        $data = $data->where("id", $status_id);
//    }
    $data = $data->pluck("name", "id")->toArray();
    if($showDefault > 0){
        $result= array($defValue => $defLabel);
        $result += $data;
        $data=$result;
    }
    return $data;
}
function getArrayExcludeStatus($status_id = 1){
    if($status_id == 2){
        return [3];
    }
}



function arrayCaseType($showDefault = 0, $defValue="0", $defLabel="សូមជ្រើសរើស"){
    $data= CaseType::orderby("id", "ASC")
        ->select(
            DB::raw("case_type_name AS name, id AS id")
        )
        //->limit(1000)
        ->pluck("name", "id")->toArray();
    if($showDefault > 0){
        $result= array($defValue => $defLabel);
        $result += $data;
        $data=$result;
    }
    return $data;
}
function arrayObjectiveCase($showDefault = 0, $defValue = "0", $defLabel = "សូមជ្រើសរើស")
{
    static $cache = null;

    if ($cache === null) {
        $cache = ObjectiveCase::orderBy("id", "ASC")
            ->pluck("objective_name", "id")
            ->toArray();
    }

    $data = $cache;

    if ($showDefault > 0) {
        $data = [$defValue => $defLabel] + $data;
    }

    return $data;
}

function arrayObjectiveCaseX($showDefault = 0, $defValue = "0", $defLabel = "សូមជ្រើសរើស"){
    $data= ObjectiveCase::orderby("id", "ASC")
        ->select(
            DB::raw("objective_name AS name, id AS id")
        )
        //->limit(1000)
        ->pluck("name", "id")->toArray();
    if($showDefault > 0){
        $result = array($defValue => $defLabel);
        $result += $data;
        $data = $result;
    }
    return $data;
}
/** get all button to create invitation */
function showHideButton2CreateInvitationLetter($case_id){
    $case = Cases::where("id", $case_id)->first();
    $case_file = $case->case_file;
    if($case_file != ""){
        /** 1.Show Button to Create invitation for Employee
         */
        $invitation_type = 0;
        if($case->case_type_id == 1){
            $invitation_type = 1;
        }
        elseif($case->case_type_id == 2){
            $invitation_type = 4;
        }
        //dd($case->inviationDisputant);
        $str = "<a href='".url('invitation/create/'.$case_id.'/'.$invitation_type)."' class='btn btn-success' target='_blank' >"
            ."បង្កើតលិខិតអញ្ជើញកម្មករ</a>";

        /** 2.Show Button to Create invitation for Company
         */
        if($case->case_type_id == 1){
            $invitation_type = 2;
        }
        elseif($case->case_type_id == 2){
            $invitation_type = 3;
        }
        $str.= "<br><a href='".url('invitation/create/'.$case_id.'/'.$invitation_type)."' class='btn btn-info' target='_blank' >"
            ."បង្កើតលិខិតអញ្ជើញក្រុមហ៊ុន</a><br>";
        /** 3.Show Button to Create invitation for both employee and company (ផ្សះផ្សា)
         */
        //dd($case->log345);
        if($case->log345->count() > 1){ // if have log34 and log5, it will show button invitation ផ្សះផ្សា
            if($case->case_type_id == 1){
                $invitation_type_employee = 5;
                $invitation_type_company = 6;
            }
            elseif($case->case_type_id == 2){
                $invitation_type_employee = 5;
                $invitation_type_company = 6;
            }

            $str.= "<a href='".url('invitation/create_both/'.$case_id.'/'.$invitation_type_employee.'/'.$invitation_type_company)."' class='btn btn-warning' target='_blank' >"
                ."បង្កើតលិខិតអញ្ជើញមកផ្សះផ្សា</a><br>";
        }
        return $str;

        //echo "show button to create invitation letter".$case_file;
    }
    else{ // show button to update invitation letter
        //echo "no case file";
    }


}

/** Button to create collective case invitation for employee */
function showCollectivesInviationEMP($case){
    $caseID = $case->id;
    $invType = 31; //លិខិតអញ្ជើញតំណាងកម្មករនិយោជិត ផ្ដល់ព័ត៌មាន វិវាទការងាររួម [ដើមបណ្តឹង]
    if(!empty($case->invitationCollectivesDisputants)){
        $invID = $case->invitationCollectivesDisputants->id;
        $invFile = $case->invitationCollectivesDisputants->invitation_file;
        //dd($case->invitationDisputant);
        $meetingHour = !empty($case->invitationCollectivesDisputants->meeting_time) ? "<span class='blue'> ម៉ោង </span>".substr($case->invitationCollectivesDisputants->meeting_time, 0, -3) : "";
        $info = "<div class='form-group col-sm-8'><a class='fw-bold' href='".url('collectives_invitations/'.$invID.'/edit')."' title='Edit'>"
            ."<span class='text-danger'>".date2Display($case->invitationCollectivesDisputants->meeting_date)."</span>"
            ."<span class='text-danger'>".$meetingHour."</span>"
            ."</a></div>";
//        $export = '<div class="form-group col-sm-4">
//                            <a class="btn btn-info form-control fw-bold mb-1" href="'.url('export/word/invitation/'.$invID).'" title="ទាញយកលិខិតសាកសួរ" target="_blank">ទាញយកលិខិតសាកសួរ</a>
//                            <a class="btn btn-info form-control fw-bold" href="'.url('export/word/invitation/'.$invFile."/2").'" title="ទាញយកលិខិតដោះស្រាយ" target="_blank">ទាញយកលិខិតដោះស្រាយ</a>
//                       </div>';
            $export = '';
        return ["invitation_id" => $invID, "invitation_file" => $invFile, "info" => $info, "export" => $export];
    }
    else{
        $info = '<div class="form-group col-sm-4"><a class="btn btn-success form-control fw-bold" href="'
            .url('collectives_invitation/create/'.$caseID.'/'.$invType).'" target="_blank">បង្កើតលិខិតអញ្ជើញ</a></div>';
        return ["invitation_id" => 0, "invitation_file" => "", "info" => $info, "export" => ""];
    }
}

/** Button to create collective case invitation for company */
function showCollectivesInvitationCompany($case){
    $caseID = $case->id;
    $invType = 32; //លិខិតអញ្ជើញសហគ្រាស គ្រឹះស្ថាន ផ្ដល់ព័ត៌មាន វិវាទការងាររួម [ចុងបណ្តឹង]

    if(!empty($case->invitationCollectivesCompany)){ // have invitation
        $invID = $case->invitationCollectivesCompany->id;
        $invFile = $case->invitationCollectivesCompany->invitation_file;
        $meetingHour = !empty($case->invitationCollectivesCompany->meeting_time) ? "<span class='blue'> ម៉ោង </span>".substr($case->invitationCollectivesCompany->meeting_time, 0, -3) : "";
        $info = "<div class='form-group col-sm-8'><a class='fw-bold' href='".url('collectives_invitations/'.$invID.'/edit')."' title='Edit'>"
            ."<span class='text-danger'>".date2Display($case->invitationCollectivesCompany->meeting_date)."</span>"
            ."<span class='text-danger'>".$meetingHour."</span>"
            ."</a></div>";
//        $export = '<div class="form-group col-sm-4">
//                        <a class="btn btn-info form-control mb-1 fw-bold" href="'.url('export/word/invitation/'.$invID).'" title="Download" target="_blank">ទាញយកលិខិតសាកសួរ</a>
//                        <a class="btn btn-info form-control fw-bold" href="'.url('export/word/invitation/'.$invID."/2").'" title="Download" target="_blank">ទាញយកលិខិតដោះស្រាយ</a>
//                        </div>';
            $export = '';
        return ["invitation_id" => $invID, "invitation_file"=> $invFile, "info" => $info, "export" => $export];
    }
    else{
        $info = '<div class="form-group col-sm-4"><a class="btn btn-success form-control fw-bold" href="'
            .url('collectives_invitation/create/'.$caseID.'/'.$invType).'" target="_blank">បង្កើតលិខិតអញ្ជើញ</a></div>';
        return ["invitation_id" => 0,  "invitation_file"=> "", "info" => $info, "export" => ""];
    }
}

/** Button to create collective case for both invitation*/
function showCollectivesInvitationBoth($case){
//    $case = Cases::where("id", $case_id)->first();
    $str = "";
    $caseID = $case->id;
    /** 3.Show Button to Create invitation for both employee and company (ផ្សះផ្សា)
     */
    //dd($case->log345);
    if($case->collectivesInvitationForConcilation->count() > 1){ //have collectives invitation for Conciliation
        $invID = [];
        $info = [];
        $export = [];
        //$labelNext = ["កម្មករនិយោជិត", "សហគ្រាស គ្រឹះស្ថាន"];
        $labelNext = ["ដើមចោទ", "ចុងចោទ"];
        $label = ["ទាញយកលិខិត", "ទាញយកលិខិត"];
        $i = 0;
//         dd($case->invitationForConcilation);
        foreach($case->collectivesInvitationForConcilation as $row){
//             dd($row);
            $idPair = getCollectivesInvitationIdPair($caseID, $row->id);
            // dd($id_pair);

            $invID [$i] = $row->id;
            $invFile[$i] = $row->invitation_file;
            $strLabelNext = " <span class='pink'>[".$labelNext[$i]."] </span>";
            if($i == 1){
                $strLabelNext = " <span class='pink'>[".$labelNext[$i]."] </span>";
            }
            $info[$i] = "<div class='form-group col-sm-8'><a class='fw-bold blue' href='".url('collectives_invitation/edit_both/'.$caseID.'/'. $row->id.'/'.$idPair)."' title='Edit'>"
                . $strLabelNext
                ."<span class='text-danger'>".date2Display($row->meeting_date)."</span>"
                ." ម៉ោង "
                ."<span class='text-danger'>".substr($row->meeting_time, 0, -3)."</span>"
                ."</a></div>";

//            $export[$i] = '<div class="form-group col-sm-4"><a class="btn btn-info custom form-control fw-bold" href="'
//                .url('export/word/invitation/'.$row->id)
//                .'" title="Download">'.$label[$i].'</a></div>';

            $i++;
        }

        return [
            "invitation_id1" => $invID[0], "invitation_file1" => $invFile[0], "info1" => $info[0], "export1" => "",
            "invitation_id2" => $invID[1], "invitation_file2" => $invFile[1], "info2" => $info[1], "export2" => ""
//            "invitation_id1" => $invID[0], "invitation_file1" => $invFile[0], "info1" => $info[0], "export1" => $export[0],
//            "invitation_id2" => $invID[1], "invitation_file2" => $invFile[1], "info2" => $info[1], "export2" => $export[1]
        ];
    }
    else{ //do not have invitation
//        dd($case->log345->count());
        if($case->log345->count() > 1){ // if have log34 and log5, it will show button invitation ផ្សះផ្សា
//            dd($case->case_type_id);
            $invTypeEmp = 9;
            $invTypeCom = 10;

            $str.= "<div class='form-group col-sm-4'><a href='".url('collectives_invitation/create_both/'.$caseID.'/'.$invTypeEmp.'/'.$invTypeCom)."' class='btn btn-success fw-bold' target='_blank' >"
                ."បង្កើតលិខិតអញ្ជើញមកផ្សះផ្សា</a></div><br>";
        }
    }
    return [
        "invitation_id1" => 0, "invitation_file1" => "", "info1" => $str, "export1" => "",
        "invitation_id2" => 0, "invitation_file2" => "", "info2" => $str, "export2" => ""
    ];

    //echo "show button to create invitation letter".$case_file;



}

/** Generate Invitation For Employee */
function showInvitationEmployee($case): array
{
    $caseID = $case->id;
    $caseTypeID = $case->case_type_id;
    $invType = 0;

    if ($caseTypeID == 1) {
        $invType = 1;
    } elseif ($caseTypeID == 2) {
        $invType = 4;
    }
    $hasInvitation = !empty($case->invitationDisputant);
    if ($hasInvitation) {
        $inv = $case->invitationDisputant;
        $meetingHour = $inv->meeting_time ? " ម៉ោង <span class='text-danger'>" . substr($inv->meeting_time, 0, -3) . "</span>" : "";

        $info = "<div class='form-group col-sm-8'>
                    <a class='fw-bold text-info' href='" . url('invitations/' . $inv->id . '/edit') . "' title='Edit'>
                        <span class='text-danger'>" . date2Display($inv->meeting_date) . "</span>" . $meetingHour . "
                    </a>
                </div>";

        $export = "<div class='form-group col-sm-4'>
                    <a class='btn btn-info form-control mb-1 fw-bold' href='" . url('export/word/invitation/' . $inv->id) . "' target='_blank'>ទាញយកលិខិតអញ្ជើញ</a>
                    <a class='btn btn-info form-control fw-bold' href='" . url('export/word/invitation/' . $inv->id . "/2") . "' target='_blank'>ទាញយកលិខិតដោះស្រាយ</a>
                </div>";

        return [
            "invitation_id" => $inv->id,
            "invitation_file" => $inv->invitation_file,
            "info" => $info,
            "export" => $export
        ];
    } else {
        $info = "<div class='form-group col-sm-4'>
                    <a class='btn btn-success form-control fw-bold' href='" . url("invitation/create/{$caseID}/{$invType}") . "'>
                        បង្កើតលិខិតអញ្ជើញ
                    </a>
                </div>";

        return [
            "invitation_id" => 0,
            "invitation_file" => "",
            "info" => $info,
            "export" => ""
        ];
    }
}

/** Old Version */
function showInvitationEmployeeX($case){
    //$case = Cases::where("id", $case_id)->first();
    $case_id = $case->id;
    $case_file = $case->case_file;
    $invitation_type = 0;

    if($case->case_type_id == 1){
        $invitation_type = 1;
    }
    elseif($case->case_type_id == 2){
        $invitation_type = 4;
    }
    if(!empty($case->invitationDisputant)){ // have invitation
        $invatation_id = $case->invitationDisputant->id;
        $invitation_file = $case->invitationDisputant->invitation_file;
        //dd($case->invitationDisputant);
        $meetingHour = !empty($case->invitationDisputant->meeting_time) ? " ម៉ោង "."<span class='text-danger'>".substr($case->invitationDisputant->meeting_time, 0, -3)."</span>" : "";
        $info = "<div class='form-group col-sm-8'><a class='fw-bold text-info' href='".url('invitations/'.$invatation_id.'/edit')."' title='Edit'>"
            ."<span class='text-danger'>".date2Display($case->invitationDisputant->meeting_date)."</span>"
            .$meetingHour
            ."</a></div>";


        $export = '<div class="form-group col-sm-4">
                            <a class="btn btn-info form-control mb-1 fw-bold" href="'.url('export/word/invitation/'.$invatation_id).'" title="ទាញយកលិខិតសាកសួរ" target="_blank">ទាញយកលិខិតសាកសួរ</a>
                            <a class="btn btn-info form-control fw-bold" href="'.url('export/word/invitation/'.$invatation_id."/2").'" title="ទាញយកលិខិតដោះស្រាយ" target="_blank">ទាញយកលិខិតដោះស្រាយ</a>
                       </div>';
        return ["invitation_id" => $invatation_id, "invitation_file" => $invitation_file, "info" => $info, "export" => $export];
    }
    else{
        $info = '<div class="form-group col-sm-4"><a class="btn btn-success form-control fw-bold" href="'
            .url('invitation/create/'.$case_id.'/'.$invitation_type).'" target="">បង្កើតលិខិតអញ្ជើញ</a></div>';

        return ["invitation_id" => 0, "invitation_file" => "", "info" => $info, "export" => ""];
    }
}

/** Generate Invitation For Company */

function showInvitationCompany(
    $case, 
    $step1_completed = false,
    $step2_completed = false, 
    $step4_completed = false, 
    $hasStep4File = false
): array {
    $caseID = $case->id;
    $invType = $case->case_type_id == 1 ? 2 : ($case->case_type_id == 2 ? 3 : 0);

    $info = '';
    $export = '';
    $invID = 0;
    $invFile = '';

    // Check if invitation already exists
    $invitation = $case->invitationCompany;

    if (!empty($invitation)) {
        $invID = $invitation->id;
        $invFile = $invitation->invitation_file;

        $meetingHour = $invitation->meeting_time 
            ? " ម៉ោង <span class='text-danger'>" . substr($invitation->meeting_time, 0, 5) . "</span>" 
            : '';

        // Display invitation info link
        $info = "<div class='form-group col-sm-8'>
            <a class='fw-bold text-info' href='" . url("invitations/$invID/edit") . "'>
                <span class='text-danger'>" . date2Display($invitation->meeting_date) . "</span>$meetingHour
            </a>
        </div>";

        // Export buttons
        $export = "<div class='form-group col-sm-4'>
            <a class='btn btn-info form-control mb-1 fw-bold' href='" . url("export/word/invitation/$invID") . "' target='_blank'>
                ទាញយកលិខិតអញ្ជើញ
            </a>
            <a class='btn btn-info form-control fw-bold' href='" . url("export/word/invitation/$invID/2") . "' target='_blank'>
                ទាញយកលិខិតដោះស្រាយ
            </a>
        </div>";

    }

    // Show "Create Invitation" button if Step 1 & Step 2 are completed
    else if ($step1_completed && $step2_completed) {
        // Enable button if Step 4 file exists, otherwise still show but can style as disabled if you want
        $createDisabled = !($step4_completed && $hasStep4File)? "disabled style='pointer-events:none; opacity:0.5'" : ""; // You can change to !$hasStep4File if you want to disable without Step 4
         

        $info .= "<div class='form-group col-sm-4'>
            <a $createDisabled class='btn btn-success form-control fw-bold' href='" . url("invitation/create/$caseID/$invType") . "'>
                បង្កើតលិខិតអញ្ជើញ
            </a>
        </div>";
    }
    return [
        "invitation_id" => $invID,
        "invitation_file" => $invFile,
        "info" => $info,
        "export" => $export,
    ];
}


// function showInvitationCompany($case): array
// {
//     $caseID = $case->id;
//     $invType = $case->case_type_id == 1 ? 2 : ($case->case_type_id == 2 ? 3 : 0);
//     $caseFile = $case->case_file ?? '';
//     $info = '';
//     $export = '';
//     $invID = 0;
//     $invFile = '';

//     if ($caseFile !== "null") {
//         $invitation = $case->invitationCompany;

//         if (!empty($invitation)) {
//             $invID = $invitation->id;
//             $invFile = $invitation->invitation_file;
//             $meetingHour = $invitation->meeting_time
//                 ? " ម៉ោង <span class='text-danger'>" . substr($invitation->meeting_time, 0, -3) . "</span>"
//                 : '';

//             $info = "<div class='form-group col-sm-8'>
//                         <a class='fw-bold text-info' href='" . url("invitations/$invID/edit") . "' title='Edit'>
//                             <span class='text-danger'>" . date2Display($invitation->meeting_date) . "</span>$meetingHour
//                         </a>
//                      </div>";

//             $export = '<div class="form-group col-sm-4">
//                         <a class="btn btn-info form-control mb-1 fw-bold" href="' . url("export/word/invitation/$invID") . '" title="Download" target="_blank">ទាញយកលិខិតអញ្ជើញ</a>
//                         <a class="btn btn-info form-control fw-bold" href="' . url("export/word/invitation/$invID/2") . '" title="Download" target="_blank">ទាញយកលិខិតដោះស្រាយ</a>
//                        </div>';
//         } else {
//             $info = '<div class="form-group col-sm-4">
//                         <a class="btn btn-success form-control fw-bold" href="' . url("invitation/create/$caseID/$invType") . '">បង្កើតលិខិតអញ្ជើញ</a>
//                      </div>';
//         }
//     } else {
        // $info = '<div class="form-group col-sm-4">
        //             <a class="btn btn-success form-control" href="' . url("invitation/create/$caseID/$invType") . '" target="_blank">បង្កើតលិខិតអញ្ជើញ</a>
        //          </div>';
//     }

//     return [
//         "invitation_id" => $invID,
//         "invitation_file" => $invFile,
//         "info" => $info,
//         "export" => $export,
//     ];
// }

function showInvitationCompanyX($case){
//$case = Cases::where("id", $case_id)->first();
    $case_id = $case->id;
    $case_file = $case->case_file;
    $invitation_type = 0;
    if($case->case_type_id == 1){
        $invitation_type = 2;
    }
    elseif($case->case_type_id == 2){
        $invitation_type = 3;
    }
    if($case_file != "null"){
        //dd($case->invitationCompany);
        if(!empty($case->invitationCompany)){ // have invitation
            $invitation_id = $case->invitationCompany->id;
//            dd($case->invitationCompany);
            $invitation_file = $case->invitationCompany->invitation_file;
            $meetingHour = !empty($case->invitationCompany->meeting_time) ? " ម៉ោង "."<span class='text-danger'>".substr($case->invitationCompany->meeting_time, 0, -3)."</span>" : "";
            $info = "<div class='form-group col-sm-8'><a class='fw-bold text-info' href='".url('invitations/'.$invitation_id.'/edit')."' title='Edit'>"
                ."<span class='text-danger'>".date2Display($case->invitationCompany->meeting_date)."</span>"
                .$meetingHour
                ."</a></div>";

            $export = '<div class="form-group col-sm-4">
                        <a class="btn btn-info form-control mb-1 fw-bold" href="'.url('export/word/invitation/'.$invitation_id).'" title="Download" target="_blank">ទាញយកលិខិតសាកសួរ</a>
                        <a class="btn btn-info form-control fw-bold" href="'.url('export/word/invitation/'.$invitation_id."/2").'" title="Download" target="_blank">ទាញយកលិខិតដោះស្រាយ</a>
                        </div>';
            return ["invitation_id" => $invitation_id, "invitation_file"=> $invitation_file, "info" => $info, "export" => $export];
        }
        else{
            $info = '<div class="form-group col-sm-4"><a class="btn btn-success form-control fw-bold" href="'
                .url('invitation/create/'.$case_id.'/'.$invitation_type).'">បង្កើតលិខិតអញ្ជើញ</a></div>';

            return ["invitation_id" => 0,  "invitation_file"=> "", "info" => $info, "export" => ""];
        }
    }
    else{
        $info = '<div class="form-group col-sm-4"><a class="btn btn-success form-control" href="'
            .url('invitation/create/'.$case_id.'/'.$invitation_type).'" target="_blank">បង្កើតលិខិតអញ្ជើញ</a></div>';
        return ["invitation_id" => 0, "invitation_file"=> "", "info" => $info, "export" => ""];
    }
}

/** Generate Button Invitation For Reconcilation  */
function showInvitationBoth($case): array
{
    $caseID = $case->id;
    $invitationForConcilation = $case->invitationForConcilation ?? collect();
    $log345Count = $case->log345->count() ?? 0;

    // Default return structure
    $result = [
        "invitation_id1" => 0, "invitation_file1" => "", "info1" => "", "export1" => "",
        "invitation_id2" => 0, "invitation_file2" => "", "info2" => "", "export2" => "",
    ];

    // === CASE 1: If has more than 1 invitation for conciliation ===
    if ($invitationForConcilation->count() > 1) {
        $labelNext = ["ដើមចោទ", "ចុងចោទ"];
        $colors = ["blue", "pink"];

        foreach ($invitationForConcilation->take(2) as $i => $invitation) {
            $invID = $invitation->id;
            $invFile = $invitation->invitation_file;
            $idPair = getInvitationIdPair($caseID, $invID);
            $color = $colors[$i] ?? 'blue';

            $info = "<div class='form-group col-sm-8'>
                        <a class='fw-bold text-info' href='" . url("invitation/edit_both/{$caseID}/{$invID}/{$idPair}") . "' title='Edit'>
                            <span class='{$color}'>[{$labelNext[$i]}]</span>
                            <span class='text-danger'>" . date2Display($invitation->meeting_date) . "</span>
                            ម៉ោង <span class='text-danger'>" . substr($invitation->meeting_time, 0, -3) . "</span>
                        </a>
                    </div>";

            $export = "<div class='form-group col-sm-4'>
                        <a class='btn btn-info custom form-control fw-bold' href='" . url("export/word/invitation/{$invID}") . "' target='_blank'>
                            ទាញយកលិខិត
                        </a>
                    </div>";

            $result["invitation_id" . ($i + 1)] = $invID;
            $result["invitation_file" . ($i + 1)] = $invFile;
            $result["info" . ($i + 1)] = $info;
            $result["export" . ($i + 1)] = $export;
        }

        return $result;
    }

    // === CASE 2: No conciliation invitation, but has log34 and log5 (បង្ហាញ Button អញ្ជើញមកផ្សះផ្សារ) ===
    if ($log345Count > 1) {
        if ($case->case_type_id == 1) {
            $invTypeEmployee = 5;
            $invTypeCompany = 6;
        } elseif ($case->case_type_id == 2) {
            $invTypeEmployee = 6;
            $invTypeCompany = 5;
        }

        $createBtn = "<div class='form-group col-sm-4'>
                        <a href='" . url("invitation/create_both/{$caseID}/{$invTypeEmployee}/{$invTypeCompany}") . "'
                           class='btn btn-success fw-bold' target='_blank'>
                           បង្កើតលិខិតអញ្ជើញមកផ្សះផ្សា
                        </a>
                    </div><br>";

        $result["info1"] = $createBtn;
        $result["info2"] = $createBtn;
    }

    return $result;
}

function showInvitationBothX($case){
//    $case = Cases::where("id", $case_id)->first();
    $str = "";
    $export = "";
    $case_id = $case->id;
    $case_file = $case->case_file;
        /** 3.Show Button to Create invitation for both employee and company (ផ្សះផ្សា)
         */
        //dd($case->log345);
    if($case->invitationForConcilation->count() > 1){ //have invitation
        $invitation_id = [];
        $info = [];
        $export = [];
        $labelNext = ["ដើមចោទ", "ចុងចោទ"];
        $label = ["ទាញយកលិខិត", "ទាញយកលិខិត"];
//        $labelNext = ["ដើមចោទ", "ចុងចោទ","ដើមចោទ", "ចុងចោទ","ដើមចោទ", "ចុងចោទ","ដើមចោទ", "ចុងចោទ"];
//        $label = ["ទាញយកលិខិត", "ទាញយកលិខិត","ទាញយកលិខិត", "ទាញយកលិខិត","ទាញយកលិខិត", "ទាញយកលិខិត","ទាញយកលិខិត", "ទាញយកលិខិត"];
        $i = 0;
//         dd($case->invitationForConcilation);
        foreach($case->invitationForConcilation as $row){

//             dd($row);
            $id_pair = getInvitationIdPair($case_id, $row->id);
            // dd($id_pair);

            $invitation_id [$i] = $row->id;
            $invitation_file[$i] = $row->invitation_file;
            $strLabelNext = " <span class='blue'>[".$labelNext[$i]."] </span>";
            if($i == 1){
                $strLabelNext = " <span class='pink'>[".$labelNext[$i]."] </span>";
            }
            $info[$i] = "<div class='form-group col-sm-8'><a class='fw-bold text-info' href='".url('invitation/edit_both/'.$case_id.'/'. $row->id.'/'.$id_pair)."' title='Edit'>"
                . $strLabelNext
                ."<span class='text-danger'>".date2Display($row->meeting_date)."</span>"
                ." ម៉ោង "
                ."<span class='text-danger'>".substr($row->meeting_time, 0, -3)."</span>"
                ."</a></div>";

            $export[$i] = '<div class="form-group col-sm-4"><a class="btn btn-info custom form-control fw-bold" href="'
                .url('export/word/invitation/'.$row->id)
                .'" title="Download">'.$label[$i].'</a></div>';
            $i++;
        }

        return [
            "invitation_id1" => $invitation_id[0], "invitation_file1" => $invitation_file[0], "info1" => $info[0], "export1" => $export[0],
            "invitation_id2" => $invitation_id[1], "invitation_file2" => $invitation_file[1], "info2" => $info[1], "export2" => $export[1]
        ];
    }
    else{ //do not have invitation
//        dd($case->log345->count());
        if($case->log345->count() > 1){ // if have log34 and log5, it will show button invitation ផ្សះផ្សា
//            dd($case->case_type_id);
            if($case->case_type_id == 1){
                $invitation_type_employee = 5;
                $invitation_type_company = 6;
            }
            elseif($case->case_type_id == 2){
                $invitation_type_employee = 6;
                $invitation_type_company = 5;
            }
            $str.= "<div class='form-group col-sm-4'><a href='".url('invitation/create_both/'.$case_id.'/'.$invitation_type_employee.'/'.$invitation_type_company)."' class='btn btn-success fw-bold' target='_blank' >"
                ."បង្កើតលិខិតអញ្ជើញមកផ្សះផ្សា</a></div><br>";

        }
    }
        return [
            "invitation_id1" => 0, "invitation_file1" => "", "info1" => $str, "export1" => "",
            "invitation_id2" => 0, "invitation_file2" => "", "info2" => $str, "export2" => ""
        ];

        //echo "show button to create invitation letter".$case_file;
}

function getCollectivesInvitationIdPair($case_id, $invitation_id){
    $invitation = CaseInvitation::select("id")->where("case_id", $case_id)
        ->whereIn("invitation_type_id", [9, 10])->whereNot("id", $invitation_id)->first();
    return $invitation->id;
}
function getInvitationIdPair($case_id, $invitation_id){
    $invitation = CaseInvitation::select("id")->where("case_id", $case_id)
        ->whereIn("invitation_type_id", [5, 6])->whereNot("id", $invitation_id)->first();
    return $invitation->id;
}
function getCaseLog($case_id = 0){

}
function showHideButton2CreateCaseLog($case_id = 0, $invitation_id = 0){
    $str = "";
    $case = Cases::where("id", $case_id)->first();
    //dd($case->lastOfficer()->officer_id);
    //dd($case->log34->count());
    /** =======================A. Generate Button For Log34 (Employee) =================*/
    if($case->log34->count() == 0){ // not yet have log34
        if(!empty($case->lastOfficer())){ //check if assign officer, if not yet assign officer cannot create log34
            /** =======================1. Get Case Invitation for Employee Only =================*/
            $letterEmployee = CaseInvitation::where("case_id", $case_id)
                ->whereRelation("invitationType", function ($query)  {
                    $query->where("employee_or_company", 1);
                })->get();
            $label = "បង្កើតកំណត់ហេតុសាកសួរកម្មករ";

            if($letterEmployee->count() > 0){
                foreach($letterEmployee as $row){
                    $str.= "<a href='".url('log34/create/'.$case_id.'/'.$row->id)."' class='btn btn-success' target='_blank' title='Create'>".$label ."</a><br>";
                }
            }
            else{
                $str.= "<a href='".url('log34/create/'.$case_id.'/0')."' class='btn btn-success' target='_blank' title='Create'>".$label ."</a><br>";
            }
        }

    }
    else{ //Exists Log34
        $i = 1;
        foreach($case->log34 as $row){
            $id = isset($row->detail34->id)? $row->detail34->id : 0;
            $label = "កែប្រែកំណត់ហេតុសាកសួរកម្មករ";
            $str.= "<a href='".url('log34/'.$id.'/edit')."' class='' target='_blank' title='Update'>".$label ."</a><br>";
            $i++;
        }

    }

    /** =======================B. Generate Button For Log5 (Company) =================*/
    if($case->log5->count() == 0) { // not yet have log5
        /** =======================2. Get Case Invitation for Company Only =================*/
        $label = "បង្កើតកំណត់ហេតុសាកសួរក្រុមហ៊ុន";
        $letterCompany = CaseInvitation::where("case_id", $case_id)
            ->whereRelation("invitationType", function ($query)  {
                $query->where("employee_or_company", 2);
            })->get();
        if($letterCompany->count() > 0){
            foreach($letterCompany as $row){
                $str.= "<a href='".url('log5/create/'.$case_id.'/'.$row->id)."' class='btn btn-info' target='_blank' title='Create'>".$label ."</a><br>";
            }
        }
    }
    else{ // have log5
        $i = 1;
        //dd($case->log5);
        foreach($case->log5 as $row){
            //dd($row->detail5);
            $label = "កែប្រែកំណត់ហេតុសាកសួរក្រុមហ៊ុន";
            $id = isset($row->detail5->id)? $row->detail5->id : 0;
            $str.= "<a href='".url('log5/'.$id.'/edit')."' class='' target='_blank' title='Update'>".$label ."</a><br>";
            $i++;
        }
    }

    /** =======================C. Generate Button For Log6 =================*/

    if($case->log6->count() == 0) { // not yet have log5
        /** =======================2. Get Case Invitation for Company Only =================*/
        $label = "បង្កើតកំណត់ហេតុ";
        $letterEmployee = CaseInvitation::where("case_id", $case_id)
            ->whereRelation("invitationType", function ($query)  {
                $query->where("type_group_id", 2);
                $query->where("employee_or_company", 1);
            })->orderBy("id", "DESC")->first();
        //dd($letterEmployee->invitationType);
        $letterCompany = CaseInvitation::where("case_id", $case_id)
            ->whereRelation("invitationType", function ($query)  {
                $query->where("type_group_id", 2);
                $query->where("employee_or_company", 2);
            })->orderBy("id", "DESC")->first();
        //dd($letterCompany->invitationType);
        //dd($letterEmployee);
        if(!empty($letterEmployee) && !empty($letterCompany)){
            //dd($letterEmployee->meeting_date);
            if($letterEmployee->meeting_date == $letterCompany->meeting_date && $letterEmployee->meeting_time == $letterCompany->meeting_time){
                $str.= "<a href='"
                    .url('log6/create/'.$case_id.'/'.$letterEmployee->id.'/'.$letterCompany->id)
                    ."' class='btn btn-info' target='_blank' title='Create'>".$label ."</a><br>";
            }
        }
    }
    else{ // have log6
        //dd($case->log6);
        $i = 1;
        //dd($case->log5);
        foreach($case->log6 as $row){
            //dd($row->detail5);
            //dd($row->detail6);
            $label = "កែប្រែកំណត់ហេតុសាកសួរក្រុមហ៊ុន";
            $id = isset($row->detail6->id)? $row->detail6->id : 0;
            $str.= "<a href='".url('log6/'.$id.'/edit')."' class='' target='_blank' title='Update'>".$label ."</a><br>";
            $i++;
        }
    }

    return $str;
}
/** Collectives Log5 */
function showCollectivesLog5($case){
    $str = "";
    $caseID = $case->id;
    $export= "";
    $logFIle = "";
    $log5ID = 0;
    /** =======================A. Generate Button For Collectives Log34 (Employee) =================*/

    if($case->log5->count() == 0){ // not yet exist collectives log5
        if(!empty($case->lastOfficer())){ //check if officer was assigned, if not yet assigned => cannot create log5
            /** =======================1. Get Case Invitation for Employee Only =================*/
            $letterEmployee = CaseInvitation::where("case_id", $caseID)
                    ->whereRelation("invitationType", function ($query)  {
                        $query->where("employee_or_company", 32); //លិខិតអញ្ជើញតំណាងគ្រឹះស្ថាន សហគ្រាស មកផ្ដល់ព័ត៌មាន (ចុងបណ្ដឹង)
                     })->get();
            $label = "បង្កើតកំណត់ហេតុ";
            $startDiv = "";
//            dd($letterEmployee);
            if($letterEmployee->count() > 0){
                foreach($letterEmployee as $row){
                    if($row->invitation_type_id == 8){ //លិខិតអញ្ជើញសហគ្រាស គ្រឹះស្ថាន ផ្ដល់ព័ត៌មាន (ចុងបណ្ដឹង)
                        $startDiv = "<div class='form-group col-sm-4'>";
                        $str.= "<a href='".url('collectives/log5/create/'.$caseID.'/'.$row->id)."' class='btn btn-success form-control fw-bold' title='Create'>".$label ."</a><br>";
                    }
                }
                $str = $startDiv.$str."</div>";

            }
            else{
                $str.= "<div class='form-group col-sm-4'><a href='".url('collectives/log5/create/'.$caseID.'/0')."' class='btn btn-success' title='Create'>".$label ."</a></div><br>";
            }
        }
    }
    else{ //Exists Log5
//        dd($case->log5);
        foreach($case->log5 as $row){
//            dd($row->detail5);
            $log5ID = isset($row->detail5->id)? $row->detail5->id : 0;
            $logFIle = isset($row->detail5->log_file)? $row->detail5->log_file : "";
            //dd($row->detail5->meeting_date);
            $label = "<span class='text-danger'>".date2Display($row->detail5->meeting_date)."</span>"
                ." ម៉ោង "."<span class='text-danger'>".substr($row->detail5->meeting_stime, 0, -3)."</span>"
                ." ដល់ "."<span class='text-danger'>".substr($row->detail5->meeting_etime, 0, -3)."</span>";
            $str.= "<div class='form-group col-sm-8'><a href='".url('collectives_log5/'.$log5ID.'/edit')."' class='fw-bold' title='Update'>".$label ."</a></div>";
//            $export = '<div class="form-bg- col-sm-4"><a class="btn btn-info custom form-control fw-bold" href="'
//                . url('export/word/case/log5/'.$log5ID)
//                .'" title="Download" target="_blank">ទាញយកកំណត់ហេតុ</a></div>';

        }

    }
    return ["log5_id" => $log5ID, "log_file" => $logFIle, "info" => $str, "export" => $export];
}

/** Collectives Log34 */
function showCollectivesLog34($case){
    $str = "";
    $caseID = $case->id;
    /** =======================A. Generate Button For Log34 (Employee) =================*/
    if($case->log34->count() == 0){ // not yet have collectives log34
        $export = "";
        $log34ID = 0;
        $logFIle = "";
        if(!empty($case->lastOfficer())){ //check if assign officer, if not yet assign officer cannot create log34
            /** =======================1. Get Case Invitation for Employee Only =================*/
            $letterEmployee = CaseInvitation::where("case_id", $caseID)
                ->select('id', 'invitation_type_id')
                ->whereRelation("invitationType", function ($query)  {
                    $query->where("employee_or_company", 31); //លិខិតអញ្ជើញតំណាងកម្មករនិយោជិត ផ្ដល់ព័ត៌មាន (ដើមបណ្ដឹង)
                })->get();
            $label = "បង្កើតកំណត់ហេតុ";
//            dd($letterEmployee);
            if($letterEmployee->count() > 0){
                $startDiv = "<div class='form-group col-sm-4'>";
                foreach($letterEmployee as $row){
                    if($row->invitation_type_id == 7){ //លិខិតអញ្ជើញតំណាងកម្មករនិយោជិត ផ្ដល់ព័ត៌មាន (ដើមបណ្ដឹង)
                        $str.= "<a href='".url('collectives/log34/create/'.$caseID.'/'.$row->id)."' class='btn btn-success form-control fw-bold' title='Create'>".$label ."</a><br>";
                    }
                }
                $str = $startDiv.$str."</div>";
            }
            else{
                $str.= "<div class='form-group col-sm-4'><a href='".url('collectives/log34/create/'.$caseID.'/0')."' class='btn btn-success form-control fw-bold' title='Create'>".$label ."</a></div><br>";
            }
        }
    }
    else{ //Exists Log34
        foreach($case->log34 as $row){
//            dd($row);
//            dd($row->detail34);
            $log34ID = !empty($row->detail34->id) ? $row->detail34->id : 0;
            $logFIle = !empty($row->detail34->log_file) ? $row->detail34->log_file : "";
            $label = "<span class='text-danger'>".date2Display($row->detail34->meeting_date)."</span>"
                ." ម៉ោង "."<span class='text-danger'>".substr($row->detail34->meeting_stime, 0, -3)."</span>"
                ." ដល់ម៉ោង "."<span class='text-danger'>".substr($row->detail34->meeting_etime, 0, -3)."</span>";
            $str.= "<div class='form-group col-sm-8'><a href='".url('collectives_log34/'.$log34ID.'/edit')."' class='fw-bold blue' title='Update'>".$label ."</a></div>";
//            $export = '<div class="form-group col-sm-4"><a class="btn btn-info custom form-control fw-bold" href="'
//                . url('export/word/case/log34/'.$log34ID)
//                .'" title="Download" target="_blank">ទាញយកកំណត់ហេតុ</a></div>';
            $export = '';

        }

    }
    return ["log34_id" => $log34ID, "log_file" => $logFIle, "info" => $str, "export" => $export];
}

/** Collectives Log6 */
function showCollectivesLog6($case){
    $str = "";
    $case_id = $case->id;
    $export = "";

    if($case->log6->count() == 0) { // dont have log6
        //dd($case);
        $label = "បង្កើតកំណត់ហេតុផ្សះផ្សា";
        $letterEmployee = CaseInvitation::where("case_id", $case_id)
            ->whereRelation("invitationType", function ($query)  {
                $query->where("type_group_id", 2);
                $query->where("employee_or_company", 31);
            })->orderBy("id", "DESC")->first();
//        dd($letterEmployee);
        $letterCompany = CaseInvitation::where("case_id", $case_id)
            ->whereRelation("invitationType", function ($query)  {
                $query->where("type_group_id", 2);
                $query->where("employee_or_company", 32);
            })->orderBy("id", "DESC")->first();

        //dd($letterEmployee);
        //dd($letterCompany);

        if(!empty($letterEmployee) && !empty($letterCompany)){
            //dd($letterEmployee->meeting_date);
            $result = ["num_log6" => 0];
            if($letterEmployee->meeting_date == $letterCompany->meeting_date && $letterEmployee->meeting_time == $letterCompany->meeting_time){
                $str.= "<div class='form-group col-sm-4'><a href='"
                    .url('collectives/log6/create/'.$case_id.'/'.$letterEmployee->id.'/'.$letterCompany->id)
                    ."' class='btn btn-success form-control fw-bold' title='Create'>".$label ."</a></div><br>";
                $result["log6_data"] = ["num_log6"=>$case->log6->count(),  "log6_id" => 0, "info" => $str, "export" => ""];
                return $result;
            }
        }
        else{
            $result = ["num_log6" => 0];
            $result["log6_data"] = ["num_log6"=> 0,  "log6_id" => 0, "info" => "", "export" => ""];
            return $result;
        }

    }
    else{ // have log6
        //$i = 1;
        //dd($case->log6);
        //dd($case->log6->count());
        $result = ["num_log6" => $case->log6->count()];
        foreach($case->log6 as $row){
            //dd($row->detail6);
            $label = "<span class='text-danger'>".date2Display($row->detail6->log6_date)."</span>"
                ." ម៉ោង "."<span class='text-danger'>".substr($row->detail6->log6_stime, 0, -3)."</span>"
                ." ដល់ម៉ោង "."<span class='text-danger'>".substr($row->detail6->log6_etime, 0, -3)."</span>";
            //$label = "កែប្រែកំណត់ហេតុសាកសួរក្រុមហ៊ុន";
            $log6_id = isset($row->detail6->id)? $row->detail6->id : 0;
            $log_file = isset($row->detail6->log_file) ? $row->detail6->log_file : "";
            $info = "<a href='".url('collectives_log6/'.$log6_id.'/edit')."' class='fw-bold blue' title='Edit'>".$label ."</a><br>";
//            $export = '<a class="btn btn-info custom form-control fw-bold" href="'
//                . url('export/word/case/log6/'.$log6_id)
//                .'" title="Download" target="_blank">ទាញយកកំណត់ហេតុ</a>';

            $result["log6_data"][] = ["log6_id" => $log6_id, "detail" => $row->detail6, "log_file" => $log_file, "info" => $info, "export" => $export, "log6" => $row->detail6];
            //dd($result['log6_data']['info']);
        }
        return $result;
        //return ["log6_id" => $log6_id, "info" => $info, "export" => $export];
    }

    if($case->log345->count() > 1){ // if have log34 and log5, it will show button invitation ផ្សះផ្សា
        if($case->case_type_id == 1){
            $invitation_type_employee = 5;
            $invitation_type_company = 6;
        }
        elseif($case->case_type_id == 2){
            $invitation_type_employee = 5;
            $invitation_type_company = 6;
        }
        $label = "";
        $str.= "<a href='".url('collectives_invitation/create_both/'.$case_id.'/'.$invitation_type_employee.'/'.$invitation_type_company)."' class='btn btn-warning fw-bold' target='_blank' >"
            ."បង្កើតលិខិតអញ្ជើញមកផ្សះផ្សា</a><br>";
    }
    else{ // dont have log6

    }

    /** =======================A. Generate Button For Log6 (Employee) =================*/
    if($case->log6->count() == 0){ // not yet have log6
        $log6_id = 0;
        if(!empty($case->lastOfficer())){ //check if assign officer, if not yet assign officer cannot create log34
            /** =======================1. Get Case Invitation for Employee Only =================*/
            $letterEmployee = CaseInvitation::where("case_id", $case_id)
                ->whereRelation("invitationType", function ($query)  {
                    $query->where("employee_or_company", 2);
                })->get();
            $label = "បង្កើតកំណត់ហេតុសាកសួរសហគ្រាស គ្រឹះស្ថាន";
            if($letterEmployee->count() > 0){
                foreach($letterEmployee as $row){
                    $str.= "<a href='".url('collectives/log6/create/'.$case_id.'/'.$row->id)."' class='btn btn-success fw-bold' target='_blank' title='Create'>".$label ."</a><br>";
                }
            }
            else{
                $str.= "<a href='".url('collectives/log6/create/'.$case_id.'/0')."' class='btn btn-success fw-bold' target='_blank' title='Create'>".$label ."</a><br>";
            }
        }
    }
    else{ //Exists Log6
        foreach($case->log6 as $row){
            //dd($row->detail6);
            $log6_id = isset($row->detail6->id)? $row->detail6->id : 0;
            $label = "កាលបរិច្ឆេទ ".date2Display($row->detail6->meeting_date)
                ." ម៉ោង "."<label class='form-label text-danger'>".substr($row->detail6->meeting_stime, 0, -3)."</label>"
                ." ដល់ "."<label class='form-label text-danger'>".substr($row->detail6->meeting_etime, 0, -3)."</label>";
            $str.= "<a href='".url('collectives/log6/'.$log6_id.'/edit')."' class='' target='_blank' title='Update'>".$label ."</a>";
//            $export = '<a class="btn btn-info custom form-control fw-bold" href="'
//                . url('export/word/case/log6/'.$log6_id)
//                .'" title="Download" target="_blank">ទាញយកកំណត់ហេតុ</a>';
        }

    }
    return ["num_log6" =>$case->log6->count(), "log6_id" => $log6_id, "info" => $str, "export" => $export];
}

/** Generate Log34 Data */
function showCaseLog34($case): array{
    $caseID = $case->id;
    $str = '';
    $log34ID = 0;
    $logFile = '';
    $export = '';
    if ($case->log34->isEmpty()) {
        if (!empty($case->lastOfficer())) {
            $letterEmployee = $case->invitationAll()
                ->whereHas('invitationType', fn($q) => $q->where('employee_or_company', 1))
                ->get();
            $label = "បង្កើតកំណត់ហេតុ";
            if ($letterEmployee->count() > 0) {
                $str .= "<div class='form-group col-sm-4'>";
                foreach ($letterEmployee as $row) {
                    if ($row->invitation_type_id == 1) {
                        $str .= "<a href='".url('log34/create/'.$caseID.'/'.$row->id)."' class='btn btn-success form-control fw-bold' title='Create'>".$label ."</a><br>";
                    }
                }
                $str .= "</div>";
            } else {
                $str .= "<div class='form-group col-sm-4'><a href='".url('log34/create/'.$caseID.'/0')."' class='btn btn-success form-control fw-bold' title='Create'>".$label ."</a></div><br>";
            }
        }
    } else {
        foreach ($case->log34 as $log) {
            $detail = $log->detail34;
            if ($detail) {
                $log34ID = $detail->id;
                $logFile = $detail->log_file;
                $label = "<span class='text-danger'>" . date2Display($detail->meeting_date) . "</span>"
                    . " ម៉ោង <span class='text-danger'>" . substr($detail->meeting_stime, 0, -3) . "</span>"
                    . " ដល់ម៉ោង <span class='text-danger'>" . substr($detail->meeting_etime, 0, -3) . "</span>";
                $str .= "<div class='form-group col-sm-8'><a href='".url('log34/'.$log34ID.'/edit')."' class='fw-bold text-info' title='Update'>".$label ."</a></div>";
                $export = '<div class="form-group col-sm-4"><a class="btn btn-info custom form-control fw-bold" href="' . url('export/word/case/log34/'.$log34ID) . '" title="Download" target="_blank">ទាញយកកំណត់ហេតុ</a></div>';
            }
        }
    }

    return [
        'log34_id' => $log34ID,
        'log_file' => $logFile,
        'info' => $str,
        'export' => $export,
    ];
}

function showCaseLog34X($case){
    $str = "";
    //$case = Cases::where("id", $case_id)->first();
    $case_id =$case->id;
    /** =======================A. Generate Button For Log34 (Employee) =================*/

    if($case->log34->count() == 0){ // not yet have log34
        $export = "";
        $log34_id = 0;
        $log_file ="";
        if(!empty($case->lastOfficer())){ //check if assign officer, if not yet assign officer cannot create log34
            /** =======================1. Get Case Invitation for Employee Only =================*/
            $letterEmployee = CaseInvitation::where("case_id", $case_id)
                ->whereRelation("invitationType", function ($query)  {
                    $query->where("employee_or_company", 1);
                })->get();
            $label = "បង្កើតកំណត់ហេតុ";
//            dd($letterEmployee);
            if($letterEmployee->count() > 0){
                $startDiv = "<div class='form-group col-sm-4'>";
                foreach($letterEmployee as $row){
                    if($row->invitation_type_id == 1){
                        $str.= "<a href='".url('log34/create/'.$case_id.'/'.$row->id)."' class='btn btn-success form-control fw-bold' title='Create'>".$label ."</a><br>";
                    }
                }
                $str = $startDiv.$str."</div>";
            }
            else{
                $str.= "<div class='form-group col-sm-4'><a href='".url('log34/create/'.$case_id.'/0')."' class='btn btn-success form-control fw-bold' title='Create'>".$label ."</a></div><br>";

            }
        }
    }
    else{ //Exists Log34
        $i = 1;
        //dd($case->log34);
        foreach($case->log34 as $row){
//            dd($row);
            //dd($row->detail34);
            $log34_id = isset($row->detail34->id)? $row->detail34->id : 0;
            $log_file = isset($row->detail34->log_file)? $row->detail34->log_file : "";
            $label = "<span class='text-danger'>".date2Display($row->detail34->meeting_date)."</span>"
                ." ម៉ោង "."<span class='text-danger'>".substr($row->detail34->meeting_stime, 0, -3)."</span>"
                ." ដល់ម៉ោង "."<span class='text-danger'>".substr($row->detail34->meeting_etime, 0, -3)."</span>";
            $str.= "<div class='form-group col-sm-8'><a href='".url('log34/'.$log34_id.'/edit')."' class='fw-bold text-info' title='Update'>".$label ."</a></div>";
            $export = '<div class="form-group col-sm-4"><a class="btn btn-info custom form-control fw-bold" href="'
                . url('export/word/case/log34/'.$log34_id)
                .'" title="Download" target="_blank">ទាញយកកំណត់ហេតុ</a></div>';
            $i++;
        }

    }
    return ["log34_id" => $log34_id, "log_file" => $log_file, "info" => $str, "export" => $export];
}
/** Generate Log5 Data */
function showCaseLog5($case): array
{
    $caseID = $case->id;
    $log5ID = 0;
    $logFile = "";
    $str = "";
    $export = "";
    if ($case->log5->isEmpty()) {
        if (!empty($case->lastOfficer())) {
            $letterCompany = $case->invitationAll()
                ->whereHas('invitationType', fn($q) => $q->where('employee_or_company', 2))
                ->get();
            $label = "បង្កើតកំណត់ហេតុ";
            if ($letterCompany->count() > 0) {
                $btns = "";
                foreach ($letterCompany as $row) {
                    if ($row->invitation_type_id == 2) {
                        $btns .= "<a href='" . url("log5/create/$caseID/{$row->id}") . "' class='btn btn-success form-control fw-bold' title='Create'>$label</a><br>";
                    }
                }
                $str = "<div class='form-group col-sm-4'>$btns</div>";
            } else {
                $str = "<div class='form-group col-sm-4'><a href='" . url("log5/create/$caseID/0") . "' class='btn btn-success fw-bold' title='Create'>$label</a></div><br>";
            }
        }
    } else {
        foreach ($case->log5 as $row) {
            $detail = $row->detail5;
            if (!$detail) continue;

            $log5ID = $detail->id ?? 0;
            $logFile = $detail->log_file ?? "";
            $meetingDate = date2Display($detail->meeting_date);
            $sTime = substr($detail->meeting_stime, 0, -3);
            $eTime = substr($detail->meeting_etime, 0, -3);

            $label = "<span class='text-danger'>$meetingDate</span> ម៉ោង <span class='text-danger'>$sTime</span> ដល់ម៉ោង <span class='text-danger'>$eTime</span>";
            $str .= "<div class='form-group col-sm-8'><a href='" . url("log5/$log5ID/edit") . "' class='fw-bold text-info' title='Update'>$label</a></div>";

            $export = '<div class="form-group col-sm-4">
                        <a class="btn btn-info custom form-control fw-bold" href="' . url("export/word/case/log5/$log5ID") . '" title="Download" target="_blank">ទាញយកកំណត់ហេតុ</a>
                       </div>';
        }
    }

    return [
        "log5_id" => $log5ID,
        "log_file" => $logFile,
        "info" => $str,
        "export" => $export,
    ];
}

function showCaseLog5X($case){
    $str = "";
    //$case = Cases::where("id", $case_id)->first();
    $case_id =$case->id;
    $export= "";
    $log_file = "";
    /** =======================A. Generate Button For Log5 (Company) =================*/

    if($case->log5->count() == 0){ // not yet have log5
        $log5_id = 0;
        if(!empty($case->lastOfficer())){ //check if assign officer, if not yet assign officer cannot create log34
            /** =======================1. Get Case Invitation for Employee Only =================*/
            $letterEmployee = CaseInvitation::where("case_id", $case_id)
                ->whereRelation("invitationType", function ($query)  {
                    $query->where("employee_or_company", 2);
                })->get();
            $label = "បង្កើតកំណត់ហេតុ";
            $startDiv = "";
//            dd($letterEmployee);
            if($letterEmployee->count() > 0){
                foreach($letterEmployee as $row){
                    if($row->invitation_type_id == 2){
                        $startDiv = "<div class='form-group col-sm-4'>";
                        $str.= "<a href='".url('log5/create/'.$case_id.'/'.$row->id)."' class='btn btn-success form-control fw-bold' title='Create'>".$label ."</a><br>";

                    }
                }
                $str = $startDiv.$str."</div>";

            }
            else{
                $str.= "<div class='form-group col-sm-4'><a href='".url('log5/create/'.$case_id.'/0')."' class='btn btn-success fw-bold' title='Create'>".$label ."</a></div><br>";

            }
        }
    }
    else{ //Exists Log5
        $i = 1;
        //dd($case->log5);
        foreach($case->log5 as $row){
            //dd($row->detail5);
            $log5_id = isset($row->detail5->id)? $row->detail5->id : 0;
            $log_file = isset($row->detail5->log_file)? $row->detail5->log_file : "";
            //dd($row->detail5->meeting_date);
            $label = "<span class='text-danger'>".date2Display($row->detail5->meeting_date)."</span>"
                ." ម៉ោង "."<span class='text-danger'>".substr($row->detail5->meeting_stime, 0, -3)."</span>"
                ." ដល់ម៉ោង "."<span class='text-danger'>".substr($row->detail5->meeting_etime, 0, -3)."</span>";
            $str.= "<div class='form-group col-sm-8'><a href='".url('log5/'.$log5_id.'/edit')."' class='fw-bold text-info' title='Update'>".$label ."</a></div>";
            $export = '<div class="form-bg- col-sm-4"><a class="btn btn-info custom form-control fw-bold" href="'
                . url('export/word/case/log5/'.$log5_id)
                .'" title="Download" target="_blank">ទាញយកកំណត់ហេតុ</a></div>';
            $i++;
        }

    }
    return ["log5_id" => $log5_id, "log_file" => $log_file, "info" => $str, "export" => $export];
}
/** Generate Log6 Data */
function showCaseLog6($case)
{
    $caseID = $case->id;
    $log6Count = $case->log6->count();
    $result = ['num_log6' => $log6Count];

    // ✅ Case A: Already has Log6
    if ($log6Count > 0) {
        foreach ($case->log6 as $log) {
            $detail = $log->detail6;
            $log6ID = $detail->id ?? 0;
            $logFile = $detail->log_file ?? "";

            $label = "<span class='text-danger'>" . date2Display($detail->log6_date) . "</span>"
                . " ម៉ោង <span class='text-danger'>" . substr($detail->log6_stime, 0, -3) . "</span>"
                . " ដល់ម៉ោង <span class='text-danger'>" . substr($detail->log6_etime, 0, -3) . "</span>";

            $info = "<a href='" . url("log6/{$log6ID}/edit") . "' class='fw-bold text-info' title='Edit'>{$label}</a><br>";

            $export = "<a class='btn btn-info custom form-control fw-bold' href='"
                . url("export/word/case/log6/{$log6ID}")
                . "' title='Download' target='_blank'>ទាញយកកំណត់ហេតុ</a>";

            $result['log6_data'][] = [
                'log6_id' => $log6ID,
                'log_file' => $logFile,
                'info' => $info,
                'export' => $export,
                'log6' => $detail,
                'detail' => $detail
            ];
        }
        return $result;
    }

    // ✅ Case B: No log6 yet — try to create if invitations match
    $employeeInvitation = CaseInvitation::where('case_id', $caseID)
        ->whereRelation('invitationType', function ($q) {
            $q->where('type_group_id', 2)->where('employee_or_company', 1);
        })->latest('id')->first();

    $companyInvitation = CaseInvitation::where('case_id', $caseID)
        ->whereRelation('invitationType', function ($q) {
            $q->where('type_group_id', 2)->where('employee_or_company', 2);
        })->latest('id')->first();

    if ($employeeInvitation && $companyInvitation) {
        if (
            $employeeInvitation->meeting_date === $companyInvitation->meeting_date &&
            $employeeInvitation->meeting_time === $companyInvitation->meeting_time
        ) {
            $label = "បង្កើតកំណត់ហេតុផ្សះផ្សា";
            $info = "<div class='form-group col-sm-4'><a href='"
                . url("log6/create/{$caseID}/{$employeeInvitation->id}/{$companyInvitation->id}")
                . "' class='btn btn-success form-control fw-bold' title='Create'>{$label}</a></div><br>";

            $result['log6_data'] = [
                'num_log6' => 0,
                'log6_id' => 0,
                'info' => $info,
                'export' => ''
            ];
            return $result;
        }
    }

    // ❌ Else: No matching invitations or officer, return default empty
    $result['log6_data'] = [
        'num_log6' => 0,
        'log6_id' => 0,
        'info' => '',
        'export' => ''
    ];

    return $result;
}

function showCaseLog6X($case){
    $str = "";
    //$case = Cases::where("id", $case_id)->first();
    $case_id =$case->id;
    $export= "";
    $log_file = "";

    if($case->log6->count() == 0) { // dont have log6
        //dd($case);
        $label = "បង្កើតកំណត់ហេតុផ្សះផ្សា";
        $letterEmployee = CaseInvitation::where("case_id", $case_id)
            ->whereRelation("invitationType", function ($query)  {
                $query->where("type_group_id", 2);
                $query->where("employee_or_company", 1);
            })->orderBy("id", "DESC")->first();
        //dd($letterEmployee->invitationType);
        $letterCompany = CaseInvitation::where("case_id", $case_id)
            ->whereRelation("invitationType", function ($query)  {
                $query->where("type_group_id", 2);
                $query->where("employee_or_company", 2);
            })->orderBy("id", "DESC")->first();
        //dd($letterCompany->invitationType);
        //dd($letterEmployee);

        if(!empty($letterEmployee) && !empty($letterCompany)){
            //dd($letterEmployee->meeting_date);
            $result = ["num_log6" => 0];
            if($letterEmployee->meeting_date == $letterCompany->meeting_date && $letterEmployee->meeting_time == $letterCompany->meeting_time){
                $str.= "<div class='form-group col-sm-4'><a href='"
                    .url('log6/create/'.$case_id.'/'.$letterEmployee->id.'/'.$letterCompany->id)
                    ."' class='btn btn-success form-control fw-bold' title='Create'>".$label ."</a></div><br>";
                $result["log6_data"] = ["num_log6"=>$case->log6->count(),  "log6_id" => 0, "info" => $str, "export" => ""];
                return $result;
            }
        }
        else{
            $result = ["num_log6" => 0];
            $result["log6_data"] = ["num_log6"=> 0,  "log6_id" => 0, "info" => "", "export" => ""];
            return $result;
        }

    }
    else{ // have log6
        //$i = 1;
        //dd($case->log6);
        //dd($case->log6->count());
        $result = ["num_log6" => $case->log6->count()];
        foreach($case->log6 as $row){
            //dd($row->detail6);
            $label = "<span class='text-danger'>".date2Display($row->detail6->log6_date)."</span>"
                ." ម៉ោង "."<span class='text-danger'>".substr($row->detail6->log6_stime, 0, -3)."</span>"
                ." ដល់ម៉ោង "."<span class='text-danger'>".substr($row->detail6->log6_etime, 0, -3)."</span>";
            //$label = "កែប្រែកំណត់ហេតុសាកសួរក្រុមហ៊ុន";
            $log6_id = isset($row->detail6->id)? $row->detail6->id : 0;
            $log_file = isset($row->detail6->log_file)? $row->detail6->log_file : "";
            $info = "<a href='".url('log6/'.$log6_id.'/edit')."' class='fw-bold text-info' title='Edit'>".$label ."</a><br>";
            $export = '<a class="btn btn-info custom form-control fw-bold" href="'
                . url('export/word/case/log6/'.$log6_id)
                .'" title="Download" target="_blank">ទាញយកកំណត់ហេតុ</a>';
            $result["log6_data"][] = ["log6_id" => $log6_id, "detail" => $row->detail6, "log_file" => $log_file, "info" => $info, "export" => $export, "log6" => $row->detail6];
            //dd($result['log6_data']['info']);
        }
        return $result;
        //return ["log6_id" => $log6_id, "info" => $info, "export" => $export];
    }

    if($case->log345->count() > 1){ // if have log34 and log5, it will show button invitation ផ្សះផ្សា
        if($case->case_type_id == 1){
            $invitation_type_employee = 5;
            $invitation_type_company = 6;
        }
        elseif($case->case_type_id == 2){
            $invitation_type_employee = 5;
            $invitation_type_company = 6;
        }
        $label = "";
        $str.= "<a href='".url('invitation/create_both/'.$case_id.'/'.$invitation_type_employee.'/'.$invitation_type_company)."' class='btn btn-warning fw-bold' target='_blank' >"
            ."បង្កើតលិខិតអញ្ជើញមកផ្សះផ្សា</a><br>";
    }
    else{ // dont have log6

    }

    /** =======================A. Generate Button For Log6 (Employee) =================*/
    if($case->log6->count() == 0){ // not yet have log6
        $log6_id = 0;
        if(!empty($case->lastOfficer())){ //check if assign officer, if not yet assign officer cannot create log34
            /** =======================1. Get Case Invitation for Employee Only =================*/
            $letterEmployee = CaseInvitation::where("case_id", $case_id)
                ->whereRelation("invitationType", function ($query)  {
                    $query->where("employee_or_company", 2);
                })->get();
            $label = "បង្កើតកំណត់ហេតុសាកសួរសហគ្រាស គ្រឹះស្ថាន";
            if($letterEmployee->count() > 0){
                foreach($letterEmployee as $row){
                    $str.= "<a href='".url('log6/create/'.$case_id.'/'.$row->id)."' class='btn btn-success fw-bold' target='_blank' title='Create'>".$label ."</a><br>";
                }
            }
            else{
                $str.= "<a href='".url('log6/create/'.$case_id.'/0')."' class='btn btn-success fw-bold' target='_blank' title='Create'>".$label ."</a><br>";

            }
        }
    }
    else{ //Exists Log6
        foreach($case->log6 as $row){
            //dd($row->detail6);
            $log6_id = isset($row->detail6->id)? $row->detail6->id : 0;
            $label = "កាលបរិច្ឆេទ ".date2Display($row->detail6->meeting_date)
                ." ម៉ោង "."<label class='form-label text-danger'>".substr($row->detail5->meeting_stime, 0, -3)."</label>"
                ." ដល់ "."<label class='form-label text-danger'>".substr($row->detail5->meeting_etime, 0, -3)."</label>";
            $str.= "<a href='".url('log5/'.$log6_id.'/edit')."' class='' target='_blank' title='Update'>".$label ."</a>";
            $export = '<a class="btn btn-info custom form-control fw-bold" href="'
                . url('export/word/case/log5/'.$log6_id)
                .'" title="Download" target="_blank">ទាញយកកំណត់ហេតុ</a>';
        }
    }
    return ["num_log6" =>$case->log6->count(), "log6_id" => $log6_id, "info" => $str, "export" => $export];
}
function arrayUnit($unit_id = 0, $showDefault = 0, $defValue = 0, $defLabel = "សូមជ្រើសរើស"){
    $data= Unit::orderby("id", "ASC")
        ->select(
            DB::raw("unit_name AS name, id AS id")
        );
    if($unit_id > 0){
        $data = $data->where("id", $unit_id);
    }
    $data = $data->pluck("name", "id")->toArray();
    if($showDefault > 0){
        $result = array($defValue => $defLabel);
        $result += $data;
        $data = $result;
    }
    return $data;
}

function arrayOfficer($officer_id = 0, $showDefault=0, $defValue="0", $defLabel="សូមជ្រើសរើស"){
    $data= Officer::orderby("id", "ASC")
        ->select(
            DB::raw("officer_name_khmer AS name, id AS id")
        );
    if($officer_id > 0){
        $data = $data->where("id", $officer_id);
    }
    $data = $data->pluck("name", "id")->toArray();
    if($showDefault > 0){
        $result= array($defValue => $defLabel);
        $result += $data;
        $data=$result;
    }
    return $data;
}
function arrayOfficerExcept($officer_id = 0, $showDefault=0, $defValue="0", $defLabel="សូមជ្រើសរើស"){
    $data= Officer::orderby("id", "ASC")
        ->select(
            DB::raw("officer_name_khmer AS name, id AS id")
        );
    if($officer_id > 0){
        $data = $data->where("id", "!=", $officer_id);
    }
    $data = $data->pluck("name", "id")->toArray();
    if($showDefault > 0){
        $result = array($defValue => $defLabel);
        $result += $data;
        $data = $result;
    }
    return $data;
}
function arrayDisputant($disputan_id = 0, $showDefault=0, $defValue="0", $defLabel="សូមជ្រើសរើស"){
    $data= Disputant::orderby("id", "ASC")
        ->select(
            DB::raw("name AS name, id AS id")
        );
    if($disputan_id > 0){
        $data = $data->where("id", $disputan_id);
    }
    $data = $data->pluck("name", "id")->toArray();
    if($showDefault > 0){
        $result = array($defValue => $defLabel);
        $result += $data;
        $data = $result;
    }
    return $data;
}
function arrayRepresentCompany($represent_company = 0, $showDefault=0, $defValue="0", $defLabel="សូមជ្រើសរើស"){
    $data= Disputant::orderby("id", "ASC")
        ->select(
            DB::raw("name AS name, id AS id")
        )->where("id", $represent_company);
    if($data->count() > 0){
        $data = $data->pluck("name", "id")->toArray();
    }
    else{
        $data = ["" => "មិនទាន់មានឈ្មោះតំណាងក្រុមហ៊ុន"];
    }
    if($showDefault > 0){
        $result= array($defValue => $defLabel);
        $result += $data;
        $data=$result;
    }
    return $data;
}

function getCaseOfficer22($caseID = 0, $getLastID = 0, $attendTypeID = 6, $preloaded = [])
{
    $key = "{$caseID}_{$attendTypeID}";
    $result = $preloaded[$key] ?? collect();
    if ($getLastID == 0) {
        if ($result->isEmpty()) {
            return "- គ្មាន<br>";
        }

        $output = '';
        $total = $result->count();

        foreach ($result as $index => $row) {
            $output .= "- " . ($row->officer->officer_name_khmer ?? 'គ្មាន');
            if ($attendTypeID == 6 && $index == 0 && $total > 1) {
                $output .= " [ចុងក្រោយ]";
            }
            $output .= "<br>";
        }
        return $output;
    }

    return $result->first()->officer_id ?? 0;
}

function getCaseOfficer($caseID = 0, $getLastID = 0, $attendTypeID = 6)
{
    if ($getLastID == 0) {
        $result = CaseOfficer::with('officer')
            ->where("case_id", $caseID)
            ->where("attendant_type_id", $attendTypeID)
            ->orderBy("id", "DESC")
            ->get();

        if ($result->isEmpty()) {
            return "- គ្មាន<br>";
        }
        $output = '';
        $total = $result->count();
        foreach ($result as $index => $row) {
            $output .= "- " . ($row->officer->officer_name_khmer ?? 'គ្មាន');
            if ($attendTypeID == 6 && $index == 0 && $total > 1) {
                $output .= " [ចុងក្រោយ]";
            }
            $output .= "<br>";
        }
        return $output;
    } else {
        $caseOfficer = CaseOfficer::select("officer_id")
            ->where("case_id", $caseID)
            ->where("attendant_type_id", $attendTypeID)
            ->orderBy("id", "DESC")
            ->first();
        return $caseOfficer->officer_id ?? 0;
    }
}

function getCaseOfficerX($caseID = 0, $getLastID = 0, $attendTypeID = 6){
    if($getLastID == 0){
        $result = CaseOfficer::with('officer') // ✅ eager-load the officer
            ->where("case_id", $caseID)
            ->where("attendant_type_id", $attendTypeID)
            ->orderBy("id", "DESC")
            ->get();

//        $officerCount = $result->count();
        if ($result->isEmpty()) {
            return "- គ្មាន"."<br>";
        }

        foreach ($result as $index => $row) {
            echo "- " . $row->officer->officer_name_khmer;
            if ($attendTypeID == 6 && $index == 0 && $result->count() > 1) {
                echo " [ចុងក្រោយ]";
            }
            echo "<br>";
        }
    }
    else{
        $caseOfficer = CaseOfficer::select("officer_id")
            ->where("case_id", $caseID)
            ->where("attendant_type_id", $attendTypeID)
            ->orderBy("id", "DESC")->first();
        if(!empty($caseOfficer)){
            return $caseOfficer->officer_id;
        }else{
            return 0;
        }
    }
}
function getLastOfficerID($case_id, $attendant_type_id = 6){
    $result = CaseOfficer::select("officer_id")
        ->where("case_id", $case_id)
        ->where("attendant_type_id", $attendant_type_id)
        ->orderBy("id", "DESC")->first();
    return isset($result->officer_id)? $result->officer_id : 0;
}

function getLastOfficer($caseID, $attendantTypeID = 6)
{
    if (!$caseID || !$attendantTypeID) {
        return null;
    }
    return CaseOfficer::with('officer')
        ->where('case_id', $caseID)
        ->where('attendant_type_id', $attendantTypeID)
        ->orderByDesc('id')
        ->first();
}

function getLastAttendants($caseID, $attendantTypes = [6, 8])
{
    return CaseOfficer::with('officer')
        ->where('case_id', $caseID)
        ->whereIn('attendant_type_id', $attendantTypes)
        ->orderByDesc('id')
        ->get()
        ->keyBy('attendant_type_id'); // [6 => Officer, 8 => Noter]
}

function getCaseInvitation($case_id){
    $str = "";
    /** =======================1. Get Case Invitation for Employee Only =================*/
    $letterEmployee = CaseInvitation::where("case_id", $case_id)
       ->whereRelation("invitationType", function ($query)  {
            $query->where("employee_or_company", 1);
        })->get();
    if($letterEmployee->count() > 0){
        foreach($letterEmployee as $row){
            $str.= "<a href='".url('invitation/'.$row->id.'/edit')."' title='Edit'>"
                .$row->invitationType->type_short."</a><br>";

        }
    }
    /** =======================2. Get Case Invitation for Company Only =================*/
    $letterCompany = CaseInvitation::where("case_id", $case_id)
        ->whereRelation("invitationType", function ($query)  {
            $query->where("employee_or_company", 2);
        })->get();
    if($letterCompany->count() > 0){
        foreach($letterCompany as $row){
            $str.= "<a href='".url('invitation/'.$row->id.'/edit')."' title='Edit'>"
                .$row->invitationType->type_short."</a><br>";
        }
    }
    return $str;

}

function arrayBusinessActivity1($showDefault = 0, $defValue = "0", $defLabel = "សូមជ្រើសរើស"){
    $data= NeaIsic::orderby("isic_code", "ASC")
        ->select(
            DB::raw("name_khmer AS name, isic_code AS id")
        )->where("level", 1)
        //->limit(1000)
        ->pluck("name", "id")->toArray();
    if($showDefault > 0){
        $result= array($defValue => $defLabel);
        $result += $data;
        $data=$result;
    }
    return $data;
}
function arrayBusinessActivity2($business_activity_id1 = 0, $showDefault=0, $defValue="0", $defLabel="សូមជ្រើសរើស"){
    $data= NeaIsic::orderby("isic_code", "ASC")
        ->select(
            DB::raw("name_khmer AS name, isic_code AS id")
        )->where("level", 2)
        ->where('section', $business_activity_id1)
        //->limit(1000)
        ->pluck("name", "id")->toArray();
    if($showDefault > 0){
        $result = array($defValue => $defLabel);
        $result += $data;
        $data = $result;
    }
    return $data;
}
function arrayBusinessActivity3($business_activity_id1 = 0, $business_activity_id2 = 0, $showDefault=0, $defValue="0", $defLabel="សូមជ្រើសរើស"){
    $data= NeaIsic::orderby("isic_code", "ASC")
        ->select(
            DB::raw("name_khmer AS name, isic_code AS id")
        )->where("level", 3)
        ->where('section', $business_activity_id1)
        ->where("sec_class", "LIKE", $business_activity_id1.$business_activity_id2."%")
        //->limit(1000)
        ->pluck("name", "id")->toArray();
    if($showDefault > 0){
        $result= array($defValue => $defLabel);
        $result += $data;
        $data=$result;
    }
    return $data;
}
function arrayBusinessActivity4($business_activity_id1 = 0, $business_activity_id2 = 0, $business_activity_id3 = 0, $showDefault=0, $defValue="0", $defLabel="សូមជ្រើសរើស"){
    $data= NeaIsic::orderby("isic_code", "ASC")
        ->select(
            DB::raw("name_khmer AS name, isic_code AS id")
        )->where("level", 4)
        ->where('section', $business_activity_id1)
        ->where("sec_class", "LIKE", $business_activity_id1.$business_activity_id3."%")
        //->limit(1000)
        ->pluck("name", "id")->toArray();
    if($showDefault > 0){
        $result= array($defValue => $defLabel);
        $result += $data;
        $data=$result;
    }
    return $data;
}

function insertUpdateHeadMeeting($case_id, $log_id, $head_meeting_id, $attendant_type_id){
    $date_created = myDateTime();
    $arrWhere = [
        "case_id" => $case_id,
        "log_id" => $log_id,
        "attendant_type_id" => $attendant_type_id,
    ];

    $adata = [
        "attendant_id" => $head_meeting_id,
        "user_updated" => Auth::user()->id,
        "date_updated" =>  $date_created,
    ];
    CaseLogAttendant::where($arrWhere)->update($adata);
}
function insertUpdateCaseOfficer($caseID, $logID, $officerID, $attendantTypeId){
    $date_created = myDateTime();
    $arrWhere = [
        "case_id" => $caseID,
        "log_id" => $logID,
        "attendant_type_id" => $attendantTypeId,
    ];
    $adata = [
        "attendant_id" => $officerID,
        "user_updated" => Auth::user()->id,
        "date_updated" =>  $date_created,
    ];

    CaseLogAttendant::updateOrCreate($arrWhere, $adata);
}

function insertUpdateCaseLogAttendant($case_id, $log_id, $attendant_id, $attendant_type_id){
    $date_created = myDateTime();
    if($attendant_id > 0){
        $existsHeadMeeting = 0;
        if($attendant_type_id == 7){
            $adataSearch = [
                "case_id" => $case_id,
                "log_id" => $log_id,
                "attendant_id" => $attendant_id,
                "attendant_type_id" => 6,
            ];
            $existsHeadMeeting = CaseLogAttendant::select("id")->where($adataSearch)->get()->count();
        }
        if($existsHeadMeeting == 0){
            $adataSearch = [
                "case_id" => $case_id,
                "log_id" => $log_id,
                "attendant_id" => $attendant_id,
                "attendant_type_id" => $attendant_type_id,
            ];
            $adata = [
                "user_updated" => Auth::user()->id,
                "date_updated" =>  $date_created,
            ];
//            dd($adata);
            $result = CaseLogAttendant::updateOrCreate($adataSearch, $adata);
            $id = !empty($result)? $result->id : 0; //insert or update it return the same result
            if ($result->wasRecentlyCreated) {
                $arrayCreate = [
                    "user_created" => Auth::user()->id,
                    "date_created" =>  $date_created
                ];
                CaseLogAttendant::where('id', $id)->update($arrayCreate);
            }
        }
    }

}
function insertUpdateDisputant($request, $log_id, $sub_attendant_type_id){
    $date_created = myDateTime();
    $arrayDisputant = $request->id_number;
    //dd($arrayDisputant);
    foreach($arrayDisputant as $key => $val){

        if(!empty($request->id_number[$key]) && !empty($request->name[$key]) && !empty($request->dob[$key]) && !empty($request->nationality[$key]) && !empty($request->phone_number[$key])){ //  && !empty($request->phone_number[$key])

            $searchSubDisputant = ["id_number" => $request->id_number[$key] ];
            /** ================== 1.Insert/Update Disputant in tbl_disputant ============ */
            $adataSubDisputant = [
                "name" => !empty($request->name) ? $request->name[$key] : "",
                "gender" => !empty($request->gender) ? $request->gender[$key] : "",
                "dob" => !empty($request->dob) ? date2DB($request->dob[$key]) : "",
                "nationality" => !empty($request->nationality) ? $request->nationality[$key] : 0,
                //"id_number" => $request->id_number,
                "phone_number" => !empty($request->phone_number) ? $request->phone_number[$key] :"",
                "phone_number2" => !empty($request->phone2_number) ? $request->phone2_number[$key] :"",
                "occupation" => !empty($request->occupation) ? $request->occupation[$key] :"",

                "house_no" => !empty($request->addr_house_no) ? $request->addr_house_no[$key] : "",
                "street" => !empty($request->addr_street) ? $request->addr_street[$key] : "",
                //"group_name" => $request->group_name[$key],
                "village" => !empty($request->village) ? $request->village[$key] : 0,
                "commune" => !empty($request->commune) ? $request->commune[$key] : 0,
                "district" => !empty($request->district) ?  $request->district[$key] : 0,
                "province" => !empty($request->province) ? $request->province[$key] : 0,

                "pob_commune_id" => !empty($request->pob_commune_id) ?  $request->pob_commune_id[$key] : 0,
                "pob_district_id" => !empty($request->pob_district_id) ?  $request->pob_district_id[$key] : 0,
                "pob_province_id" => !empty($request->pob_province_id) ?  $request->pob_province_id[$key] : 0,

                "user_created" => Auth::user()->id,
                "user_updated" => Auth::user()->id,
                "date_created" =>  $date_created,
                "date_updated" =>  $date_created,
            ];
            //dd($adataSubDisputant);
            $result = Disputant::updateOrCreate($searchSubDisputant, $adataSubDisputant);
            $sub_disputant_id = !empty($result)? $result->id : 0;//insert or update it return the same result
            /** ================== 2.Insert/Update in tbl_case_log_attendant for sub disputant ========= */
            insertUpdateCaseLogAttendant($request->case_id, $log_id, $sub_disputant_id, $sub_attendant_type_id);
            /** ================== 3.Insert Case Disputant in tbl_case_disputant ============ */
            $adataCaseDisputant = [
                "case_id" => $request->case_id,
                "disputant_id" => $sub_disputant_id,
                "attendant_type_id" => 2,
                "house_no" =>  !empty($request->addr_house_no) ? $request->addr_house_no[$key] : "",
                "street" =>  !empty($request->addr_street) ? $request->addr_street[$key] : "",
                "village" =>  !empty($request->village) ? $request->village[$key] : 0,
                "commune" =>  !empty($request->commune) ? $request->commune[$key] : 0,
                "district" =>  !empty($request->district) ? $request->district[$key] : 0,
                "province" =>  !empty($request->province) ? $request->province[$key] : 0,

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
function insertUpdateDisputantRepresentCompany($request, $log_id){ //បញ្ចូលឫកែប្រែ អ្នកតំណាងនិយោជក ឫអ្នកអមនិយោជក
//    dd($request->all());
    $date_created = myDateTime();
    $case_id = $request->case_id;
    if($request->case_type_id == 1 || $request->case_type_id == 3){
        $attendant_type_id = 3;
    }
    elseif($request->case_type_id == 2){
        $attendant_type_id = 1;
    }


    $representCompany = CaseLogAttendant::where("case_id", $case_id)
        ->where("log_id", $log_id)->where("attendant_type_id", $attendant_type_id)->first();

//    dd($representCompany);

    if(!empty($representCompany)){ // ករណីមានអ្នកតំណាងនិយោជករួចហើយ
        if($request->case_type_id == 1 || $request->case_type_id == 3){
            $attendant_type_id = 4; //អមចុងបណ្តឹង
        }
        elseif($request->case_type_id == 2){
            $attendant_type_id = 2; //អមដើមបណ្តឹង
        }
    }
    $arrayDisputant = $request->id_number;
//     dd($arrayDisputant);

    foreach($arrayDisputant as $key => $val){
//        dd(!empty($request->phone_number[$key]));
        if(!empty($request->name[$key]) && !empty($request->dob[$key]) && !empty($request->nationality[$key]) && !empty($request->phone_number[$key])){ //  && !empty($request->phone_number[$key])
            /** ================== 1.Insert/Update Disputant in tbl_disputant ============ */
//            dd($request->id_number[$key]);
            if(!empty($request->id_number[$key])){
                $searchSubDisputant = ["id_number" => $request->id_number[$key]];
//                dd($request->pob_commune_id[$key] ?? 0);
                $adataSubDisputant = [
                    "name" => $request->name[$key],
                    "gender" => $request->gender[$key],
                    "dob" => date2DB($request->dob[$key]),
                    "nationality" => $request->nationality[$key],
                    //"id_number" => $request->id_number,
                    "phone_number" => $request->phone_number[$key],
                    "phone_number2" => $request->phone2_number[$key],
                    "occupation" => $request->occupation[$key],

                    "house_no" => $request->addr_house_no[$key],
                    "street" => $request->addr_street[$key],
                    "village" => isset($request->village[$key]) ? $request->village[$key] : 0,
                    "commune" => isset($request->commune[$key]) ? $request->commune[$key] : 0,
                    "district" => isset($request->district[$key]) ? $request->district[$key] : 0,
                    "province" => isset($request->province[$key]) ? $request->province[$key] : 0,

                    "pob_commune_id" => $request->nationality[$key] == 33 ? $request->pob_commune_id[$key] ?? 0 : 0,
                    "pob_district_id" => $request->nationality[$key] == 33 ? $request->pob_district_id[$key] ?? 0 : 0,
                    "pob_province_id" => $request->nationality[$key] == 33 ? $request->pob_province_id[$key] ?? 0 : 0,
                    "pob_country_id" => $request->nationality[$key] != 33 ? $request->pob_country_id[$key] : 0,

                    "user_created" => Auth::user()->id,
                    "user_updated" => Auth::user()->id,
                    "date_created" =>  $date_created,
                    "date_updated" =>  $date_created,
                ];
//                dd($adataSubDisputant);
            }else{
                $searchSubDisputant = [
                    "name" => $request->name[$key],
                    "dob" => date2DB($request->dob[$key]),
                    "phone_number" => $request->phone_number[$key],
                ];
                $adataSubDisputant = [
                    "phone_number2" => $request->phone2_number[$key],
                    "occupation" => $request->occupation[$key],
                    "gender" => $request->gender[$key],
                    "nationality" => $request->nationality[$key],
                    "house_no" => $request->addr_house_no[$key],
                    "street" => $request->addr_street[$key],
                    //"group_name" => $request->group_name[$key],
                    "village" => isset($request->village[$key]) ? $request->village[$key] : 0,
                    "commune" => isset($request->commune[$key]) ? $request->commune[$key] : 0,
                    "district" => isset($request->district[$key]) ? $request->district[$key] : 0,
                    "province" => isset($request->province[$key]) ? $request->province[$key] : 0,

                    "pob_commune_id" => $request->nationality[$key] == 33 ? $request->pob_commune_id[$key] ?? 0 : 0,
                    "pob_district_id" => $request->nationality[$key] == 33 ? $request->pob_district_id[$key] ?? 0 : 0,
                    "pob_province_id" => $request->nationality[$key] == 33 ? $request->pob_province_id[$key] ?? 0 : 0,
                    "pob_country_id" => $request->nationality[$key] != 33 ? $request->pob_country_id[$key] : 0,

                    "user_created" => Auth::user()->id,
                    "user_updated" => Auth::user()->id,
                    "date_created" =>  $date_created,
                    "date_updated" =>  $date_created,
                ];
            }
//             dd($adataSubDisputant);
            $result = Disputant::updateOrCreate($searchSubDisputant, $adataSubDisputant);
            $subDisputantId = !empty($result)? $result->id : 0;//insert or update it return the same result

            /** ================== 2.Insert/Update in tbl_case_log_attendant for sub disputant ========= */
            insertUpdateCaseLogAttendant($case_id, $log_id, $subDisputantId, $attendant_type_id);
            /** ================== 3.Insert Case Disputant in tbl_case_disputant ============ */
            $searchCaseDisputant = [
                "case_id" => $case_id,
                "disputant_id" => $subDisputantId,
                "attendant_type_id" => $attendant_type_id,
            ];
            $adataCaseDisputant = [
                "house_no" => $request->addr_house_no[$key],
                "street" => $request->addr_street[$key],

                "village" => isset($request->village[$key]) ? $request->village[$key] : 0,
                "commune" => isset($request->commune[$key]) ? $request->commune[$key] : 0,
                "district" => isset($request->district[$key]) ? $request->district[$key] : 0,
                "province" => isset($request->province[$key]) ? $request->province[$key] : 0,

                "phone_number" => $request->phone_number[$key],
                "phone_number2" => $request->phone2_number[$key],
                "occupation" => $request->occupation[$key],

                "user_created" => Auth::user()->id,
                "date_created" =>  $date_created,
            ];
            //dd($adataCaseDisputant);
            $result = CaseDisputant::updateOrCreate($searchCaseDisputant, $adataCaseDisputant);
        }
    }

}
function insertUpdateDisputantAll($name = "", $gender = "", $dob = "", $nationality ="", $id_number="", $phone_number ="", $phone_number2 ="" , $occupation = "", $house_no ="", $street = "", $village = 0, $commune =0, $district = 0, $province = 0, $pob_commune_id = 0, $pob_district_id = 0, $pob_province_id = 0){
    $date_created = myDateTime();
    if(!empty($name) && !empty($dob) && !empty($nationality) && !empty($phone_number)){
        if(!empty($id_number)){
            $search = ["id_number" => $id_number ];
            $adata = [
                "name" => $name,
                "gender" => $gender,
                "dob" => date2DB($dob),
                "phone_number" => $phone_number,
                "phone_number2" => $phone_number2,
                "nationality" => $nationality,
                "occupation" => $occupation,
                "house_no" => $house_no,
                "street" => $street,
                "village" => $village,
                "commune" => $commune,
                "district" => $district,
                "province" => $province,
                "pob_commune_id" => $pob_commune_id,
                "pob_district_id" => $pob_district_id,
                "pob_province_id" => $pob_province_id,

                "user_updated" => Auth::user()->id,
                "date_updated" =>  $date_created,
            ];

        }else{
            $search = [
                "name" => $name,
                "dob" => date2DB($dob),
                "phone_number" => $phone_number
            ];
            $adata = [
//            "name" => $name,
                "gender" => $gender,
//            "dob" => date2DB($dob),
                "nationality" => $nationality,
                "occupation" => $occupation,
                "house_no" => $house_no,
//            "phone_number" => $phone_number
                "phone_number2" => $phone_number2,
                "street" => $street,
                "village" => $village,
                "commune" => $commune,
                "district" => $district,
                "province" => $province,
                "pob_commune_id" => $pob_commune_id,
                "pob_district_id" => $pob_district_id,
                "pob_province_id" => $pob_province_id,

                "user_updated" => Auth::user()->id,
                "date_updated" =>  $date_created,
            ];
        }

        /** ================== 1.Insert/Update Disputant in tbl_disputant ============ */

//        dd($adata);
        $result = Disputant::updateOrCreate($search, $adata);
        $disputant_id = !empty($result)? $result->id : 0;//insert or update it return the same result
        if ($result->wasRecentlyCreated) {
            $arrayCreate = [
                "user_created" => Auth::user()->id,
                "date_created" =>  $date_created
            ];
            Disputant::where($search)->update($arrayCreate);
        }

        return $disputant_id;
    }
}
function insertUpdateCaseDisputant($case_id, $disputant_id, $attendant_type_id, $house_no = "", $street = "", $village = 0,$commune = 0, $district = 0, $province = 0, $phone_number = "", $phone_number2 = "", $occupation = ""){
    $date_created = myDateTime();
    if(!empty($occupation) && !empty($phone_number)){
        $search = [
            "case_id" => $case_id,
            "disputant_id" => $disputant_id,
            "attendant_type_id" => $attendant_type_id,
        ];
        $adata = [
            "case_id" => $case_id,
            "disputant_id" => $disputant_id,
            "attendant_type_id" => $attendant_type_id,

            "house_no" => $house_no,
            "street" => $street,
            "village" => $village,
            "commune" => $commune,
            "district" => $district,
            "province" => $province,

            "phone_number" => $phone_number,
            "phone_number2" => $phone_number2,
            "occupation" => $occupation,

            "user_updated" => Auth::user()->id,
            "date_updated" =>  $date_created,
        ];
//        dd($adata);
        $result = CaseDisputant::updateOrCreate($search, $adata);
        $id = !empty($result)? $result->id : 0; //insert or update it return the same result
//        dd($id);
        if ($result->wasRecentlyCreated) {
            $arrayCreate = [
                "user_created" => Auth::user()->id,
                "date_created" =>  $date_created
            ];
            CaseDisputant::where("id", $id)->update($arrayCreate);
        }
    }
}
function getLog5Union($case_id, $log_id){
    return CaseLog5Union1::where("case_id", $case_id)->where("log_id", $log_id)->get();
}

function getArrayAttendantType($case_type_id){
    $arrayResult= array();
    if($case_type_id == 1){
        $arrayResult['employee_main'] = 1;
        $arrayResult['employee_sub'] = 2;
        $arrayResult['company_main'] = 3;
        $arrayResult['company_sub'] = 4;
    }
    elseif($case_type_id == 2){
        $arrayResult['employee_main'] = 3;
        $arrayResult['employee_sub'] = 4;
        $arrayResult['company_main'] = 1;
        $arrayResult['company_sub'] = 2;
    }
    elseif($case_type_id == 3){
        $arrayResult['employee_main'] = 1;
        $arrayResult['employee_sub'] = 2;
        $arrayResult['company_main'] = 3;
        $arrayResult['company_sub'] = 4;
    }
    return $arrayResult;
}
/** date: 17-02-2025 */
function generateCollectivesCaseStatus($case){
//    dd($case);
    $status = [];
    $labelStatus = "កំពុងដំណើរការដល់";
    if($case->case_closed == 1){
//        if($case->id == 519){
//            dd($case->case_closed_date);
//        }
        $status = [
            "status" => 2,
            "status_label" => "បានបិទបញ្ចប់",
            "name" => $case->caseClosedStep->step,
            "create_date" => date2Display($case->case_closed_date),
            "meeting_date" => date2DB($case->case_closed_date),
        ];

    }else{
        if($case->invitationAll->count() == 0){
            $status = [
                "status" => 0,
                "status_label" => "ថ្មី",
                "name" => "បណ្ដឹងថ្មី",
                "create_date" => date2Display($case->case_date),
                "meeting_date" => "",
            ];
//            $i++;
        }
        if(!empty($case->invitationCollectivesDisputants)){
            $status = [
                "status" => 1,
                "status_label" => $labelStatus,
                "name" => "លិខិតអញ្ជើញកម្មករនិយោជិត",
                "create_date" => $case->invitationCollectivesDisputants->letter_date,
                "meeting_date" => $case->invitationCollectivesDisputants->meeting_date,
            ];
//            $i++;
        }
        if(!empty($case->invitationCollectivesCompany)){
            $status = [
                "status" => 1,
                "status_label" => $labelStatus,
                "name" => "លិខិតអញ្ជើញសហគ្រាស គ្រឹះស្ថាន",
                "create_date" => $case->invitationCollectivesCompany->letter_date,
                "meeting_date" => $case->invitationCollectivesCompany->meeting_date,
            ];
//            $i++;
        }
        if(!empty($case->log34Detail)){
            $status = [
                "status" => 1,
                "status_label" => $labelStatus,
                "name" => "កំណត់ហេតុសាកសួរកម្មករនិយោជិត",
                "create_date" => $case->log34Detail->date_created,
                "meeting_date" => date2DB($case->log34Detail->meeting_date),
            ];
//            $i++;
        }
        if(!empty($case->log5Detail)){
            $status = [
                "status" => 1,
                "status_label" => $labelStatus,
                "name" => "កំណត់ហេតុសាកសួរសហគ្រាស គ្រឹះស្ថាន",
                "create_date" => $case->log5Detail->date_created,
                "meeting_date" => date2DB($case->log5Detail->meeting_date),
            ];
//            $i++;
        }
        if($case->collectivesInvitationForConcilation->count() > 0){ // two record
            foreach($case->collectivesInvitationForConcilation as $row){
                $status = [
                    "status" => 1,
                    "status_label" => $labelStatus,
                    "name" => "លិខិតអញ្ជើញមកផ្សះផ្សា",
                    "create_date" => $row->letter_date,
                    "meeting_date" => date2DB($row->meeting_date),
                ];
                break;
            }
//            $i++;
        }
        if(!empty($case->latestLog6Detail)){
            if($case->latestLog6Detail->status_id == 3){
                //$labelStatus = $labelStatus." - ".$case->log6Detail->status->status_name;
                //$labelStatus = $case->log6Detail->status->status_name;
                //$labelStatus = $case->log6Detail->status->status_name;
                $meeting_date = date2DB($case->latestLog6Detail->status_date);
            }
            else{

                $labelStatus = $case->latestLog6Detail->status->status_name;
                $meeting_date = !empty($case->latestLog6Detail->log6_date) ? $case->latestLog6Detail->log6_date :  "";
            }
            //dd($labelStatus);
            //dd($case->log6Detail->status);
            $status = [
                "status" => $case->latestLog6Detail->status_id,
                "status_label" => $labelStatus,
                "status_label2" => $case->latestLog6Detail->status->status_name,
                "name" => "កំណត់ហតុនៃការផ្សះផ្សា",
                "create_date" => $case->latestLog6Detail->date_created,
                "meeting_date" => $meeting_date,
            ];
//            $i++;
        }
    }
    return $status;

//    $status = collect($status); // array to collect that can use with below sortBy
//    $status = $status->sortByDesc('create_date');
//    //dd($status);
//    $lastItem = Arr::first($status);
////    dd($status);
//    return $lastItem;

}

/** date: 28-07-2024 */
function generateCaseStatus($case)
{
    $labelStatus = "កំពុងដំណើរការដល់";
    $status = [];

    // ✅ Case Closed
    if ($case->case_closed == 1) {
        return [
            "status" => 2,
            "status_label" => "បានបិទបញ្ចប់",
            "name" => $case->caseClosedStep->step ?? 'បានបិទ',
            "create_date" => date2Display($case->case_closed_date),
            "meeting_date" => date2DB($case->case_closed_date),
        ];
    }

    // ✅ New Case (no invitations)
    if ($case->invitationAll->count() == 0) {
        $status = [
            "status" => 0,
            "status_label" => "ថ្មី",
            "name" => "បណ្ដឹងថ្មី",
            "create_date" => date2Display($case->case_date),
            "meeting_date" => "",
        ];
    }

    // ✅ Invitation to worker
    if (!empty($case->invitationDisputant)) {
        $status = [
            "status" => 1,
            "status_label" => $labelStatus,
            "name" => "លិខិតអញ្ជើញកម្មករនិយោជិត",
            "create_date" => $case->invitationDisputant->letter_date,
            "meeting_date" => $case->invitationDisputant->meeting_date,
        ];
    }

    // ✅ Invitation to company
    if (!empty($case->invitationCompany)) {
        $status = [
            "status" => 1,
            "status_label" => $labelStatus,
            "name" => "លិខិតអញ្ជើញសហគ្រាស គ្រឹះស្ថាន",
            "create_date" => $case->invitationCompany->letter_date,
            "meeting_date" => $case->invitationCompany->meeting_date,
        ];
    }

    // ✅ Log 34 (Worker Log)
    if (!empty($case->log34Detail)) {
        $status = [
            "status" => 1,
            "status_label" => $labelStatus,
            "name" => "កំណត់ហេតុសាកសួរកម្មករនិយោជិត",
            "create_date" => $case->log34Detail->date_created,
            "meeting_date" => date2DB($case->log34Detail->meeting_date),
        ];
    }

    // ✅ Log 5 (Company Log)
    if (!empty($case->log5Detail)) {
        $status = [
            "status" => 1,
            "status_label" => $labelStatus,
            "name" => "កំណត់ហេតុសាកសួរសហគ្រាស គ្រឹះស្ថាន",
            "create_date" => $case->log5Detail->date_created,
            "meeting_date" => date2DB($case->log5Detail->meeting_date),
        ];
    }

    // ✅ Invitation to conciliation
    if ($case->invitationForConcilation->count() > 0) {
        $row = $case->invitationForConcilation->first(); // only first one matters
        $status = [
            "status" => 1,
            "status_label" => $labelStatus,
            "name" => "លិខិតអញ្ជើញមកផ្សះផ្សា",
            "create_date" => $row->letter_date,
            "meeting_date" => date2DB($row->meeting_date),
        ];
    }

    // ✅ Log 6 (conciliation Log)
    if (!empty($case->latestLog6Detail)) {
        $log6 = $case->latestLog6Detail;

        if ($log6->status_id == 3) {
            $meetingDate = date2DB($log6->status_date);
        } else {
            $labelStatus = $log6->status->status_name ?? $labelStatus;
            $meetingDate = $log6->log6_date ?? "";
        }

        $status = [
            "status" => $log6->status_id,
            "status_label" => $labelStatus,
            "status_label2" => $log6->status->status_name ?? '',
            "name" => "កំណត់ហតុនៃការផ្សះផ្សា",
            "create_date" => $log6->date_created,
            "meeting_date" => $meetingDate,
        ];
    }

    return $status;
}

function generateCaseStatusZZZ($case){
//    dd($case);
    $status = [];
    $labelStatus = "កំពុងដំណើរការដល់";
    if($case->case_closed == 1){
//        if($case->id == 519){
//            dd($case->case_closed_date);
//        }
        $status = [
            "status" => 2,
            "status_label" => "បានបិទបញ្ចប់",
            "name" => $case->caseClosedStep->step,
            "create_date" => date2Display($case->case_closed_date),
            "meeting_date" => date2DB($case->case_closed_date),
        ];
    }else{
        if($case->invitationAll->count() == 0){
            $status = [
                "status" => 0,
                "status_label" => "ថ្មី",
                "name" => "បណ្ដឹងថ្មី",
                "create_date" => date2Display($case->case_date),
                "meeting_date" => "",
            ];
            //$i++;
        }
        if(!empty($case->invitationDisputant)){
            $status = [
                "status" => 1,
                "status_label" => $labelStatus,
                "name" => "លិខិតអញ្ជើញកម្មករនិយោជិត",
                "create_date" => $case->invitationDisputant->letter_date,
                "meeting_date" => $case->invitationDisputant->meeting_date,
            ];
            //$i++;
        }
        if(!empty($case->invitationCompany)){
            $status = [
                "status" => 1,
                "status_label" => $labelStatus,
                "name" => "លិខិតអញ្ជើញសហគ្រាស គ្រឹះស្ថាន",
                "create_date" => $case->invitationCompany->letter_date,
                "meeting_date" => $case->invitationCompany->meeting_date,
            ];
            //$i++;
        }
        if(!empty($case->log34Detail)){
            $status = [
                "status" => 1,
                "status_label" => $labelStatus,
                "name" => "កំណត់ហេតុសាកសួរកម្មករនិយោជិត",
                "create_date" => $case->log34Detail->date_created,
                "meeting_date" => date2DB($case->log34Detail->meeting_date),
            ];
            //$i++;
        }
        if(!empty($case->log5Detail)){
            $status = [
                "status" => 1,
                "status_label" => $labelStatus,
                "name" => "កំណត់ហេតុសាកសួរសហគ្រាស គ្រឹះស្ថាន",
                "create_date" => $case->log5Detail->date_created,
                "meeting_date" => date2DB($case->log5Detail->meeting_date),
            ];
            //$i++;
        }
        if($case->invitationForConcilation->count() > 0){ // two record
            //dd($case->invitationForConcilation);
            foreach($case->invitationForConcilation as $row){
                $status = [
                    "status" => 1,
                    "status_label" => $labelStatus,
                    "name" => "លិខិតអញ្ជើញមកផ្សះផ្សា",
                    "create_date" => $row->letter_date,
                    "meeting_date" => date2DB($row->meeting_date),
                ];
                break;
            }
            //$i++;
        }
        if(!empty($case->latestLog6Detail)){
            if($case->latestLog6Detail->status_id == 3){
                //$labelStatus = $labelStatus." - ".$case->log6Detail->status->status_name;
                //$labelStatus = $case->log6Detail->status->status_name;
                //$labelStatus = $case->log6Detail->status->status_name;
                $meeting_date = date2DB($case->latestLog6Detail->status_date);
            }
            else{

                $labelStatus = $case->latestLog6Detail->status->status_name;
                $meeting_date = !empty($case->latestLog6Detail->log6_date) ? $case->latestLog6Detail->log6_date :  "";
            }
            //dd($labelStatus);
            //dd($case->log6Detail->status);
            $status = [
                "status" => $case->latestLog6Detail->status_id,
                "status_label" => $labelStatus,
                "status_label2" => $case->latestLog6Detail->status->status_name,
                "name" => "កំណត់ហតុនៃការផ្សះផ្សា",
                "create_date" => $case->latestLog6Detail->date_created,
                "meeting_date" => $meeting_date,
            ];

//            $i++;
        }
//        if(!empty($case->log6Detail)){
//            if($case->log6Detail->status_id == 3){
//                //$labelStatus = $labelStatus." - ".$case->log6Detail->status->status_name;
//                //$labelStatus = $case->log6Detail->status->status_name;
//                //$labelStatus = $case->log6Detail->status->status_name;
//                $meeting_date = date2DB($case->log6Detail->status_date);
//            }
//            else{
//                $labelStatus = $case->log6Detail->status->status_name;
////                dd($case->log6Detail->status->status_name);
//
//                $meeting_date ="";
//            }
//            //dd($labelStatus);
//            //dd($case->log6Detail->status);
//            $status[$i] = [
//                "status" => $case->log6Detail->status_id,
//                "status_label" => $labelStatus,
//                "status_label2" => $case->log6Detail->status->status_name,
//                "name" => "កំណត់ហតុនៃការផ្សះផ្សា",
//                "create_date" => $case->log6Detail->date_created,
//                "meeting_date" => $meeting_date,
//            ];
//
//            $i++;
//        }
    }

    return $status;

//    $status = collect($status); // array to collect that can use with below sortBy
//
//    $status = $status->sortByDesc('create_date');
////    dd($status);
//    $lastItem = Arr::first($status);
////    dd($status);
//    return $lastItem;

}

/** date: 19-02-2024 */
function displayCaseStatus($caseStatus){
    $str = "";
    if (!empty($caseStatus)) {
        $statusClasses = [
            1 => "btn btn-info-gradien fw-bold",     // កំពុងដំណើរការ
            2 => "btn btn-danger fw-bold",           // បញ្ចប់
            3 => "btn btn-warning-gradien fw-bold"   // លើកពេលផ្សះផ្សា
        ];

        $class = $statusClasses[$caseStatus['status']] ?? "btn btn-danger-gradien fw-bold";

        // Add inline style to remove finger cursor
        $str = "<span class='mb-1 $class' style='cursor: default;'>".$caseStatus['status_label']."</span>";

        if ($caseStatus['status'] == 3) {
            $str .= "<br><label class='form-label fw-bold'>".$caseStatus['status_label2']."ទៅថ្ងៃ</label><br/>
                     <label class='form-label fw-bold text-danger'>[".date2Display($caseStatus['meeting_date'])."]</label>";
            return $str;
        }

        if ($caseStatus['status'] == 0 && !empty($caseStatus['create_date'])) {
            $str .= "<br><label class='form-label fw-bold'>កាលបរិច្ឆេទប្តឹងទៅអធិការ</label><br>
                     <label class='form-label fw-bold text-danger'>[".date2Display($caseStatus['create_date'])."]</label>";
        }

        if ($caseStatus['status'] > 0) {
            $str .= "<br><label class='form-label fw-bold'>".$caseStatus['name']."</label><br>
                     <label class='form-label fw-bold text-danger'>[".date2Display($caseStatus['meeting_date'])."]</label>";
        }
    }

    return $str;
}
/** 20-06-2024  */
function arrayOfficerCaseInHandByDomain($domainID = 0, $showDefault = 0, $defValue = 0, $defLabel = "សូមជ្រើសរើស") {
    $officerList = [];

    // Eager load officerRole and nested case data
    /** @var \Illuminate\Database\Eloquent\Collection<Officer> $officers */
    $officers = Officer::with([
        'officerRole',
        'casesOfficers.case.log6Latest.detail6',
    ])
        ->get();

    /** @var Officer $officer */
    foreach ($officers as $officer) {
        $caseCount = 0;
        foreach ($officer->casesOfficers as $rowCase) {
            // Safely check nested relationships
            if ($rowCase->attendant_type_id == 6 && $rowCase->case && $rowCase->case->case_closed == 0) {
                $log6 = $rowCase->case->log6Latest;
                if ($log6 && $log6->detail6) {
                    // Safely access status_id with null-safe operator
                    if ($log6->detail6->status_id != 2) {
                        $caseCount++;
                    }
                } elseif (!$log6) {
                    $caseCount++;
                }
            }
        }
        $officerList[] = [
            'id' => $officer->id,
            'name' => $officer->officer_name_khmer,
            'case_count' => $caseCount,
            'domain_match' => $officer->officerRole && $officer->officerRole->domain_id == $domainID
        ];
    }

    // Sort: domain_match desc, then case_count desc
    $sorted = collect($officerList)->sortBy([
        ['domain_match', 'desc'],
        ['case_count', 'desc']
    ]);

    $data = [];
    foreach ($sorted as $officer) {
        $label = $officer['case_count'] == 0
            ? $officer['name'] . " (គ្មានបណ្តឹងក្នុងដៃ)"
            : $officer['name'] . " (កំពុងកាន់ " . Num2Unicode($officer['case_count']) . " បណ្តឹង)";
        $data[$officer['id']] = $label;
    }

    if ($showDefault > 0) {
        $data = [$defValue => $defLabel] + $data;
    }

    return $data;
}

function arrayOfficerCaseInHandByDomainX($domainID = 0, $showDefault = 0, $defValue = 0, $defLabel = "សូមជ្រើសរើស") {
    $data = [];

    // Eager load relationships
    $officers = Officer::with(['casesOfficers.case.log6Latest', 'officerRole'])
        ->orderBy("id", "ASC")
        ->get();

    // Prioritize officers whose role domain_id == $domainID
    $officers = $officers->sortBy(function ($officer) use ($domainID) {
        return $officer->officerRole && $officer->officerRole->domain_id == $domainID ? 0 : 1;
    })->values(); // Reindex the collection

    foreach ($officers as $officer) {
        $counter = 0;
        foreach ($officer->casesOfficers as $rowCase) {
            if ($rowCase->attendant_type_id == 6) { // Only conciliators
                $case = $rowCase->case;
                if (!empty($case) && $case->case_closed == 0) {
                    $log6 = $case->log6Latest;
                    if ($log6) {
                        if ($log6->detail6->status_id != 2) {
                            $counter++;
                        }
                    } else {
                        $counter++;
                    }
                }
            }
        }

        $label = $counter == 0
            ? $officer->officer_name_khmer . " (គ្មានបណ្តឹងក្នុងដៃ)"
            : $officer->officer_name_khmer . " (កំពុងកាន់ " . Num2Unicode($counter) . " បណ្តឹង)";
        $data[$officer->id] = $label;
    }

    if ($showDefault > 0) {
        $data = [$defValue => $defLabel] + $data;
    }

    return $data;
}


/** date: 11-03-2024 Soklay */
function arrayOfficerCaseInHand($showDefault = 0, $defValue = 0 , $defLabel = "សូមជ្រើសរើស") {
    $data = [];
    // Eager load relationships to avoid N+1 query issues
    $officers = Officer::with(['casesOfficers.case.log6Latest'])
        ->orderBy("id", "ASC")
        ->get();
    foreach ($officers as $officer) {
        $counter = 0;
        $countCaseOfficer = count($officer->casesOfficers);
        if($countCaseOfficer > 0){
            foreach($officer->casesOfficers as $rowCase){
                if($rowCase->attendant_type_id == 6){ // រើសយកតែអ្នកផ្សះផ្សារប៉ុណ្ណោះ
                    if(!empty($rowCase->case)){
                        if($rowCase->case->case_closed == 0){ //case_closed == 1 បានបិទបញ្ចប់
                            if(!empty($rowCase->case->log6Latest)){
                                if($rowCase->case->log6Latest->detail6->status_id <> 2){ // status_id: 1=>ដំណើរការ, 2=>បញ្ចប់ , 3=>លើកពេល
                                    $counter ++;
                                }
                            }else{
                                $counter ++;
                            }
                        }
                    }
//                    elseif(!empty($rowCase->case->log6Latest)){
//                        if($rowCase->case->log6Latest->detail6->status_id <> 2){ // status_id: 1=>ដំណើរការ, 2=>បញ្ចប់ , 3=>លើកពេល
//                            $counter ++;
//                        }
//                    }
//                    else{
//                        $counter ++;
//                    }
                }
            }
        }

        // Assign officer name with case count
        $data[$officer->id] = $counter == 0
            ? $officer->officer_name_khmer . " (គ្មានបណ្តឹងក្នុងដៃ)"
            : $officer->officer_name_khmer . " (កំពុងកាន់ " . Num2Unicode($counter) . " បណ្តឹង)";
    }
    if($showDefault > 0){
        $result = array($defValue => $defLabel);
        $result += $data;
        $data = $result;
    }
    return $data;
}

function arrayOfficerCaseInHandXX ($officer_id = 0, $showDefault=0, $defValue="0", $defLabel="សូមជ្រើសរើស"){
    $data = [];
    $officers = Officer::orderby("id", "ASC")->get();


    foreach ($officers as $officer){
        $counter = 0;

        if(count($officer->casesOfficers) > 0){
            foreach($officer->casesOfficers as $rowCase){
                //dd($rowCase->case->log6Latest->detail6->status_id);
                if(!empty($rowCase->case->log6Latest) ){
                    if($rowCase->case->log6Latest->detail6->status_id <> 2){
                        $counter ++;
                    }
                }
                else{
                    $counter ++;
                }
            }
        }
//        dd(count($officer->casesOfficers));
        if($counter == 0){
            $data[$officer->id] = $officer->officer_name_khmer." ( គ្មានបណ្តឹងក្នុងដៃ )";
        }else{
            $data[$officer->id] = $officer->officer_name_khmer." (កំពុងកាន់ ".Num2Unicode($counter)." បណ្តឹង )";
        }
    }
//    dd($counter);
    dd($data);
    return $data;
}



