<?php

namespace App\Http\Controllers;

use App\Models\CaseInvitation;
use App\Models\CaseLog34;
use App\Models\CaseLog5;
use App\Models\CaseLog6;
use App\Models\Cases;
use App\Models\Inspection;
use App\Models\Letter;
use Exception;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;

//use PhpOffice\PhpWord\TemplateProcessor;

class ExportWordController extends Controller
{
    /** date: 01-02-2024 soklay code */
    public function exportCaseReport ($caseID){
        $case = Cases::where('id', $caseID)->first();
        $emp = $case->disputant;
        $empCase = $case->caseDisputant;
        $company = $case->company;
        $com = $case->caseCompany;
        //dd($emp);
        if(is_null($case)){
            return abort(404);
        }
//        $file = storage_path("doc_template/1_case_report.docx");
        $file = pathToUploadFile("doc_template/1_case_report.docx");
        $phpWord = new TemplateProcessor($file);
        /** ===================1. Header and Body Data ======================== */
        //ខ្ញុំបាទ/នាងខ្ញុំ
        $arrEmpDOB = getDateAsKhmer($emp->dob);
        $pre_name ="ខ្ញុំបាទ";
        $empGender = "ប្រុស";
        if($emp->gender == 2){
            $pre_name ="នាងខ្ញុំ";
            $empGender = "ស្រី";
        }
        $empNationality = $emp->disNationality->nationality_kh;
        $empPOB = "";
        $empPOBCom = "";
        $empPOBDis = "";
        $empPOBPro = "";

        if(!empty($emp->pob_country_id) && $emp->pob_country_id != 33){
            $empPOB = "កើតនៅប្រទេស ".$emp->pobAbroad->nationality_kh;

        }else{
            $empPOBCom = !empty($emp->pobCommune)? "ឃុំ/សង្កាត់ ".$emp->pobCommune->com_khname : "";
            $empPOBDis = !empty($emp->pobDistrict)? "ស្រុក/ខណ្ឌ ".$emp->pobDistrict->dis_khname : "";
            $empPOBPro = !empty($emp->pobProvince)? "ខេត្ត/ក្រុង ".$emp->pobProvince->pro_khname : "";
        }

        if(!empty($empPOBCom) || !empty($empPOBDis) || !empty($empPOBPro)){
            $empPOB = "កើតនៅ ".$empPOBCom." ". $empPOBDis." ". $empPOBPro;
        }

        $empNo = !empty($empCase->house_no)? "ផ្ទះលេខ ".$empCase->house_no : "";
        $empStreet = !empty($empCase->street)? "ផ្លូវ ".$empCase->street : "";
        $empVil = !empty($empCase->addressVillage) ? "ក្រុម/ភូមិ ".$empCase->addressVillage->vil_khname : "";
        $empCom = !empty($empCase->addressCommune) ? "ឃុំ/សង្កាត់ ".$empCase->addressCommune->com_khname : "";
        $empDis = !empty($empCase->addressDistrict) ?  "ស្រុក/ខណ្ឌ ".$empCase->addressDistrict->dis_khname : "";
        $empPro = !empty($empCase->addressProvince) ? "ខេត្ត/រាជធានី ".$empCase->addressProvince->pro_khname : "";
        $empPhone = !empty($empCase->phone_number) ? "ទូរស័ព្ទទំនាក់ទំនង ".$empCase->phone_number : "";
        $empOccupation = !empty($empCase->occupation) ? "មុខងារ ".$empCase->occupation : "";
        $companyType = !empty($company->companyType) ? $company->companyType->company_type_name : "";
        $comName = $companyType." ".$company->company_name_khmer;
        $comBuilding = !empty($com->log5_building_no) ? "អគារលេខ ". $com->log5_building_no : "";
        $comStreet = !empty($com->log5_street_no) ? "ផ្លូវ ".$com->log5_street_no : "";
        $comVil = !empty($com->village) ? "ក្រុម/ភូមិ ". $com->village->vil_khname : "";
        $comCom = !empty($com->commune) ? "ឃុំ/សង្កាត់ ".$com->commune->com_khname : "";
        $comDis = !empty($com->district) ? "ស្រុក/ខណ្ឌ ".$com->district->dis_khname : "";
        $comPro = !empty($com->province) ? "ខេត្ដ/រាជធានី ".$com->province->pro_khname : "";
        $comPhone = !empty($com->log5_company_phone_number) ? "ទូរស័ព្ទទំនាក់ទំនង ". $com->log5_company_phone_number : "";
        $comBusinessType = !empty($case->caseSector) ? "មានអាជីវកម្មផ្នែក ".$case->caseSector->sector_name : "";
        $caseObjective = $case->case_ojective_other;
        //$arrCaseDate = getDateAsKhmer($case->case_date);
        $arrTerminatedDate = getDateAsKhmer($case->terminated_contract_date);
        $empTerminatedTime = getTimeAsKhmer($case->terminated_contract_time,'H:i');
//        $empTerminatedTime = getDateTimeAsWord("", $case->terminated_contract_time);
        $caseReason = $case->case_objective_des;
        $arrEmpStartDate = getDateAsKhmer($case->disputant_sdate_work);
        $empContractType = $case->disputant_contract_type == 1 ? "កំណត់" : "មិនកំណត់";
        $empWorkHour = $case->disputant_work_hour_day;
        $empWorkWeek = $case->disputant_work_hour_week;
        $empWage = $case->disputant_salary;

        if($case->disputant_night_work == 1){
            $empNightShift = "ធ្លាប់ធ្វើ";
        }elseif($case->disputant_night_work == 2){
            $empNightShift = "ម្តងម្កាល";
        }elseif($case->disputant_night_work == 3){
            $empNightShift = "មិនធ្លាប់ធ្វើ";
        }

        if($case->disputant_holiday_week == 1){
            $empHolidayWeek = "ឈប់តាមប្រកាស";
        }elseif($case->disputant_holiday_week == 2){
            $empHolidayWeek = "ម្តងម្កាល";
        }elseif($case->disputant_holiday_week == 3){
            $empHolidayWeek = "មិនធ្លាប់បានឈប់";
        }

        if($case->disputant_holiday_year == 1){
            $empHolidayYear = "ឈប់តាមប្រកាស";
        }elseif($case->disputant_holiday_year == 2){
            $empHolidayYear = "ធ្លាប់បានឈប់ម្តងម្កាល";
        }elseif($case->disputant_holiday_year == 3){
            $empHolidayYear = "មិនធ្លាប់បានឈប់";
        }

        $caseFirstReason = !empty($case->case_first_reason) ? $case->case_first_reason : "";
        $empRequest = $case->disputant_request;
        $arrCaseKhmerEntryDate = getDateAsKhmer($case->case_date_entry);

        $phpWord->setValue('pre_name', xmlEntities($pre_name));
        $phpWord->setValue('emp_name', xmlEntities($emp->name));
        $phpWord->setValue('emp_gender', xmlEntities($empGender));
        $phpWord->setValue('emp_nationality', xmlEntities($empNationality));
        $phpWord->setValue('dob_day', Num2Unicode($arrEmpDOB["day"]));
        $phpWord->setValue('dob_month', $arrEmpDOB["month"]);
        $phpWord->setValue('dob_year', Num2Unicode($arrEmpDOB["year"]));
        $phpWord->setValue('emp_pob', xmlEntities($empPOB));
        $phpWord->setValue('emp_no', Num2Unicode($empNo));
        $phpWord->setValue('emp_street', Num2Unicode($empStreet));
        $phpWord->setValue('emp_vil', xmlEntities($empVil));
        $phpWord->setValue('emp_com', xmlEntities($empCom));
        $phpWord->setValue('emp_dis', xmlEntities($empDis));
        $phpWord->setValue('emp_pro', xmlEntities($empPro));
        $phpWord->setValue('emp_phone', Num2Unicode($empPhone));
        $phpWord->setValue('emp_occupation', xmlEntities($empOccupation));
        $phpWord->setValue('dob_pro', xmlEntities($empPOBPro));
        $phpWord->setValue('com_name', xmlEntities($comName));
        $phpWord->setValue('com_building', xmlEntities(Num2Unicode($comBuilding)));
        $phpWord->setValue('com_street', xmlEntities(Num2Unicode($comStreet)));
        $phpWord->setValue('com_vil', xmlEntities($comVil));
        $phpWord->setValue('com_com', xmlEntities($comCom));
        $phpWord->setValue('com_dis', xmlEntities($comDis));
        $phpWord->setValue('com_pro', xmlEntities($comPro));
        $phpWord->setValue('com_phone', xmlEntities(Num2Unicode($comPhone)));
        $phpWord->setValue('com_bus_type', xmlEntities($comBusinessType));
        $phpWord->setValue('case_objective', xmlEntities($caseObjective));

        $tday = !empty($arrTerminatedDate['day'])? $arrTerminatedDate['day'] : ".....";
        $tmonth = !empty($arrTerminatedDate['month'])? $arrTerminatedDate['month'] : "........";
        $tyear = !empty($arrTerminatedDate['year'])? $arrTerminatedDate['year'] : ".......";
        $phpWord->setValue('terminated_day', xmlEntities(Num2Unicode($tday)));
        $phpWord->setValue('terminated_month', xmlEntities($tmonth));
        $phpWord->setValue('terminated_year', xmlEntities(Num2Unicode($tyear)));

        $ttime = !empty($empTerminatedTime)? $empTerminatedTime : ".............";
        $phpWord->setValue('terminated_time', xmlEntities($ttime));
        $phpWord->setValue('case_reason', xmlEntities($caseReason));
        $phpWord->setValue('contract_type', xmlEntities($empContractType));
        $phpWord->setValue('working_hour', xmlEntities(Num2Unicode($empWorkHour)));
        $phpWord->setValue('working_week', xmlEntities(Num2Unicode($empWorkWeek)));
        $phpWord->setValue('wage', xmlEntities(Num2Unicode($empWage))."$");
        $phpWord->setValue('night_shift', xmlEntities($empNightShift));
        $phpWord->setValue('weekly_holiday', xmlEntities($empHolidayWeek));
        $phpWord->setValue('yearly_holiday', xmlEntities($empHolidayYear));
        $phpWord->setValue('case_first_reason', xmlEntities($caseFirstReason));
        $phpWord->setValue('emp_request', xmlEntities($empRequest));

        $sday = !empty($arrEmpStartDate['day'])? $arrEmpStartDate['day'] : ".....";
        $smonth = !empty($arrEmpStartDate['month'])? $arrEmpStartDate['month'] : "........";
        $syear = !empty($arrEmpStartDate['year'])? $arrEmpStartDate['year'] : ".......";
        $phpWord->setValue('emp_start_day', xmlEntities($sday));
        $phpWord->setValue('emp_start_month', xmlEntities($smonth));
        $phpWord->setValue('emp_start_year', xmlEntities($syear));


        $phpWord->setValue('case_khmer_entry_date', xmlEntities(khmerDate($case->case_date_entry)));
        $phpWord->setValue('day', $arrCaseKhmerEntryDate['day']);
        $phpWord->setValue('month', $arrCaseKhmerEntryDate['month']);
        $phpWord->setValue('year', $arrCaseKhmerEntryDate['year']);

        /** ===================3. Download File ======================== */
        $fileToSave = "ពាក្យបណ្តឹង"."_".myDate("m_d_Y").".docx";//file name to

        try{

            $phpWord->saveAs(public_path($fileToSave)); //Create new file and save it to public folder
            // $phpWord->saveAs(storage_path($fileToSave));
        }catch (Exception $e){
            //handle exception
        }
        // dd(public_path($fileToSave));
        // dd(storage_path($fileToSave));
        return response()->download(public_path($fileToSave))->deleteFileAfterSend(true);
        // return response()->download(storage_path($fileToSave))->deleteFileAfterSend(true);
    }
    /** date: 01-02-2024 soklay code */
    public function exportLog34($log_id){
        $log34 = CaseLog34::where("id", $log_id)->first();
        $case = $log34->case;
        $com = $case->company;
        $emp = $case->disputant;
        $empCase = $case->caseDisputant;
        //$caseOfficer = getLastOfficer($case->id, 6); //LastOfficer
        $caseOfficer = $log34->headMeeting;
        $attendantsAll = $case->log34Detail->attendant;

        if(is_null($log34)){
            return abort(404);
        }
//        $file = storage_path("doc_template/3_log34_employee_info.docx");
        $file = pathToUploadFile("doc_template/3_log34_employee_info.docx");
        $phpWord = new TemplateProcessor($file);
        /** ===================1. Header and Body Data ======================== */
        $arrLog34Date = getDateTimeAsWord($log34->meeting_date, $log34->meeting_stime);
        $log34_etime = getDateTimeAsWord("", $log34->meeting_etime);
        $arrCaseDate = getDateAsKhmer($case->case_date_entry);


//        $pre_name ="ខ្ញុំបាទ";
//        $empGender = "ប្រុស";
//        if($emp->gender == 2){
//            $pre_name ="នាងខ្ញុំ";
//            $empGender = "ស្រី";
//        }

        $empName = $emp->gender == 1 ? "លោក ".$emp->name : "លោកស្រី ".$emp->name;
        $arrEmpDOB = getDateAsKhmer($emp->dob);
        $empHouse = !empty($empCase->house_no) ? "ផ្ទះលេខ ". $empCase->house_no : "";
        $empStreet = !empty($empCase->street) ? "ផ្លុវលេខ ".$empCase->street : "";
        $empVil = !empty($empCase->addressVillage->vil_khname) ? "ក្រុម/ភូមិ ".$empCase->addressVillage->vil_khname : "";
        $empCom = !empty($empCase->addressCommune->com_khname)? $empCase->addressCommune->com_khname:"...............";
        $empDis = !empty($empCase->addressDistrict->dis_khname)? $empCase->addressDistrict->dis_khname:".............";
        $empPro = !empty($empCase->addressProvince->pro_khname)? $empCase->addressProvince->pro_khname:".............";
        $empOccupation = $empCase->occupation;
        $comName = $com->company_name_khmer;
        $empGiveInfo = $log34->disputant_give_info;

        $officerName = $caseOfficer->officer->officer_name_khmer;
        $log34_1 = $log34->log34_1;
        $log34_2 = $log34->log34_2;
        $log34_3 = $log34->log34_3;
        $log34_4 = $log34->log34_4;
        $log34_5 = $log34->log34_5;
        $log34_6 = $log34->log34_6;
        $log34_7 = $log34->log34_7;
        $log34_8 = $log34->log34_8;
        $log34_9 = $log34->log34_9;
        $log34_10 = $log34->log34_10;
        $log34_11 = $log34->log34_11;

        //dd($attendantsAll);


        $noter = "";
        foreach ($attendantsAll as $row){
            //dd($row);
            if($row->attendant_type_id > 6){
//                if($row->attendant_type_id == 6){
//                    $attend_type = "ប្រធានអង្គប្រជុំ";
//                }
//                elseif($row->attendant_type_id == 7){
//                    $attend_type = "អមអ្នកផ្សះផ្សា";
//                    //$noter = $row->officer->officer_name_khmer;
//                }

                if($row->attendant_type_id == 8){
                    $noter = $row->officer->officer_name_khmer;
                }
                if($row->officer->sex == 1){
                    $officerGender = "លោក  ";
                }elseif($row->officer->sex == 2){
                    $officerGender = "លោកស្រី ";
                }
                $attendants[]= array(
                    // "no" => $j,
                    "attend_name"=> $officerGender.$row->officer->officer_name_khmer,
                    // "attend_type" => $row->type->attendant_type_name,
                    "role" => $row->officer->officerRole->officer_role,
                );

            }elseif($row->attendant_type_id < 6){
                if($row->disputant->gender == 1){
                    $disGender = "លោក  ";
                }elseif($row->disputant->gender == 2){
                    $disGender = "លោកស្រី ";
                }
                $attendants[]= array(
                    // "no" => $j,
                    "attend_name"=> $disGender.$row->disputant->name,
                    // "attend_type" => "ដើមបណ្ដឹង",
                    "role" => $row->caseDisputant->occupation,
                );
            }
        }

        $phpWord->cloneRowAndSetValues('attend_name', $attendants);

        $phpWord->setValue('emp_name', xmlEntities($empName));
        $phpWord->setValue('log34_kh_date', $arrLog34Date["txt_date"]);
        $phpWord->setValue('log34_kh_hour', $arrLog34Date["txt_time"]);
//         dd($log34->invitation);
        if(!empty($log34->invitation)){//have invitation
            $invDate = getDateAsKhmer($log34->invitation->letter_date);
            $invNum = !empty($log34->invitation->invitation_number) ? Num2Unicode($log34->invitation->invitation_number)." ក.ប/អ.ក/វ.ក" : "............ ក.ប/អ.ក/វ.ក";
            $refer_to = "តបតាម លិខិតអញ្ជើញលេខ ".$invNum." ចុះថ្ងៃទី" .$invDate["day"]
                ." ខែ".$invDate["month"]." ឆ្នាំ".$invDate["year"]
                ." របស់នាយកដ្ឋានវិវាទការងារ ".$empName;
        }
        else{ // no invitation
            $refer_to = "តាមរយៈពាក្យបណ្តឹងចុះថ្ងៃទី" .$arrCaseDate["day"]
                ." ខែ".$arrCaseDate["month"]." ឆ្នាំ".$arrCaseDate["year"]
                ." ".$empName;
//                ." របស់នាយកដ្ឋានវិវាទការងារ។".$empName;
        }
        $phpWord->setValue('refer_to', $refer_to);
//            $phpWord->setValue('case_day', $arrCaseDate["day"]);
//            $phpWord->setValue('case_month', $arrCaseDate["month"]);
//            $phpWord->setValue('case_year', $arrCaseDate["year"]);



        $phpWord->setValue('dob_day', $arrEmpDOB["day"]);
        $phpWord->setValue('dob_month', $arrEmpDOB["month"]);
        $phpWord->setValue('dob_year', $arrEmpDOB["year"]);

        $phpWord->setValue('emp_house', xmlEntities($empHouse));
        $phpWord->setValue('emp_street', xmlEntities($empStreet));
        $phpWord->setValue('emp_vil', xmlEntities($empVil));
        $phpWord->setValue('emp_com', xmlEntities($empCom));
        $phpWord->setValue('emp_dis', xmlEntities($empDis));
        $phpWord->setValue('emp_pro', xmlEntities($empPro));
        $phpWord->setValue('emp_occupation', xmlEntities($empOccupation));

        $phpWord->setValue('pre_com_name', $log34->case->company->companyType->company_type_name);
        $phpWord->setValue('com_name', xmlEntities($comName));
        $phpWord->setValue('emp_give_info', xmlEntities($empGiveInfo));
        $phpWord->setValue('officer_name', xmlEntities($officerName));
        $phpWord->setValue('log34_1', xmlEntities($log34_1));
        $phpWord->setValue('log34_2', xmlEntities($log34_2));
        $phpWord->setValue('log34_3', xmlEntities($log34_3));
        $phpWord->setValue('log34_4', xmlEntities($log34_4));
        $phpWord->setValue('log34_5', xmlEntities($log34_5));
        $phpWord->setValue('log34_6', xmlEntities($log34_6));
        $phpWord->setValue('log34_7', xmlEntities($log34_7));
        $phpWord->setValue('log34_8', xmlEntities($log34_8));
        $phpWord->setValue('log34_9', xmlEntities($log34_9));
        $phpWord->setValue('log34_10', xmlEntities($log34_10));
        $phpWord->setValue('log34_11', xmlEntities($log34_11));
        $phpWord->setValue('meeting_etime', xmlEntities($log34_etime['txt_time']));
        $phpWord->setValue('noter', xmlEntities($noter));
        /** ===================3. Download File ======================== */
        $fileToSave = "កំណត់ហេតុសាកសួរពត៌មានកម្មករ"."_".myDate("m_d_Y").".docx";//file name to

        try{
//            $phpWord->saveAs(storage_path($fileToSave));
            $phpWord->saveAs(public_path($fileToSave)); //Create new file and save it to public folder

        }catch (Exception $e){
            //handle exception
        }
//        return response()->download(storage_path($fileToSave))->deleteFileAfterSend(true);
        return response()->download(public_path($fileToSave))->deleteFileAfterSend(true);

    }
    /** date: 01-02-2024 soklay code */
    public function exportLog5($log_id){
        $log5 = CaseLog5::where('id', $log_id)->first();
        $case = $log5->case;
        $com = $case->company;
        $comCase = $case->caseCompany;
        //dd($comCase);
        // $caseOfficer = getLastOfficer($case->id, 6); //LastOfficer
        $caseOfficer = $log5->headMeeting;

        $attendantsAll = $case->log5Detail->attendant;
        $log5Union1 = $log5->union1;

        if(is_null($log5)){
            return abort(404);
        }
//        $file = storage_path("doc_template/5_log5_company_info.docx");
        $file = pathToUploadFile("doc_template/5_log5_company_info.docx");
        $phpWord = new TemplateProcessor($file);
        /** ===================1. Header and Body Data ======================== */
        $comName = xmlEntities($com->company_name_khmer);
        $arrMeetingDate = getDateTimeAsWord($log5->meeting_date, $log5->meeting_stime);
        $meetingETime = getDateTimeAsWord("", $log5->meeting_etime);
        $meetingPlace = $log5->meeting_place_id == 1 ? "នាយកដ្ឋានវិវាទការងារ" : xmlEntities($log5->meeting_place_other);
        $arrComOpenDate = getDateAsKhmer($comCase->log5_open_date);
        $officerName = $caseOfficer->officer->officer_name_khmer;

        //dd($comCase);
        $head_vil = !empty($comCase->village)? $comCase->village->vil_khname:".............";
        $head_com = !empty($comCase->commune)? $comCase->commune->com_khname:".............";
        $head_dis = !empty($comCase->district)? $comCase->district->dis_khname:".............";
        $head_pro = !empty($comCase->province)? $comCase->province->pro_khname:".............";
        $phpWord->setValue('pre_company_name', $log5->case->company->companyType->company_type_name);
        $phpWord->setValue('company_name', $comName);
        $phpWord->setValue('meeting_date', $arrMeetingDate['txt_date']);
        $phpWord->setValue('meeting_time', $arrMeetingDate['txt_time']);
        $phpWord->setValue('meeting_place', $meetingPlace);
        $phpWord->setValue('meeting_about', xmlEntities($log5->meeting_about));
        $phpWord->setValue('officer_name', xmlEntities($officerName));
        $phpWord->setValue('officer_comment', xmlEntities($log5->head_officer_comment));
        $phpWord->setValue('com_day', $arrComOpenDate['day']);
        $phpWord->setValue('com_month', $arrComOpenDate['month']);
        $phpWord->setValue('com_year', $arrComOpenDate['year']);
        $phpWord->setValue('head_phone', Num2Unicode($comCase->log5_head_phone));
        $phpWord->setValue('head_building', xmlEntities(Num2Unicode($comCase->log5_head_building_no)));
        $phpWord->setValue('head_street', xmlEntities(Num2Unicode($comCase->log5_head_street_no)));
        $phpWord->setValue('head_vil', xmlEntities($head_vil));
        $phpWord->setValue('head_com', xmlEntities($head_com));
        $phpWord->setValue('head_dis', xmlEntities($head_dis));
        $phpWord->setValue('head_pro', xmlEntities($head_pro));
        $phpWord->setValue('director_name', !empty($comCase->log5_director_name_khmer) ? xmlEntities($comCase->log5_director_name_khmer) : "..........................");


        $dirNation = "....................";
        if(!empty($comCase->nationalityDirector)){
            $dirNation = xmlEntities($comCase->nationalityDirector->nationality_kh);
        }
        $phpWord->setValue('director_nationality', $dirNation);
        $phpWord->setValue('owner_name', !empty($comCase->log5_owner_name_khmer) ? xmlEntities($comCase->log5_owner_name_khmer) : "..........................");
        $ownNation = "....................";
        if(!empty($comCase->nationalityOwner)){
            $ownNation = xmlEntities($comCase->nationalityOwner->nationality_kh);
        }
        $com_vil = !empty($comCase->village)? $comCase->village->vil_khname:".............";
        $com_com = !empty($comCase->commune)? $comCase->commune->com_khname:".............";
        $com_dis = !empty($comCase->district)? $comCase->district->dis_khname:".............";
        $com_pro = !empty($comCase->province)? $comCase->province->pro_khname:".............";
        $comArticle = "..........................";
        if(!empty($comCase->companyArticle)){
            $comArticle = $comCase->companyArticle->article_name;
        }elseif(!empty($com->companyArticle)){
            $comArticle = $com->companyArticle->article_name;
        }
        $phpWord->setValue('owner_nationality', $ownNation);
        $phpWord->setValue('company_article', xmlEntities($comArticle));
        $phpWord->setValue('company_type', xmlEntities($comCase->companyType->company_type_name));
        $phpWord->setValue('com_phone', Num2Unicode($comCase->log5_company_phone_number));
        $phpWord->setValue('com_building', xmlEntities(Num2Unicode($comCase->log5_building_no)));
        $phpWord->setValue('com_street', xmlEntities(Num2Unicode($comCase->log5_street_no)));
        $phpWord->setValue('com_vil', xmlEntities($com_vil));
        $phpWord->setValue('com_com', xmlEntities($com_com));
        $phpWord->setValue('com_dis', xmlEntities($com_dis));
        $phpWord->setValue('com_pro', xmlEntities($com_pro));
        $phpWord->setValue('emp_total', Num2Unicode($comCase->log5_total_employee));
        $phpWord->setValue('emp_female', Num2Unicode($comCase->log5_total_employee_female));
        $phpWord->setValue('emp_male', Num2Unicode($comCase->log5_total_employee-$comCase->log5_total_employee_female));
        $phpWord->setValue('union1_num', Num2Unicode($comCase->log5_union1_number));
        $phpWord->setValue('contract_type_emp', xmlEntities($log5->contract_type_with_employee));
        $phpWord->setValue('dispute_cause', xmlEntities($log5->dispute_cause));
        $phpWord->setValue('dispute_more_info', xmlEntities($log5->dispute_more_info));
        $phpWord->setValue('meeting_etime', Num2Unicode($meetingETime['txt_time']));

//        dd($attendantsAll);
        // Attendants Lists
        $noter = "";
        foreach ($attendantsAll as $row){
            if($row->attendant_type_id > 6){
                if($row->attendant_type_id == 8){
                    $noter = $row->officer->officer_name_khmer;
                }

                if($row->officer->sex == 1){
                    $officerGender = "លោក  ";
                }elseif($row->officer->sex == 2){
                    $officerGender = "លោកស្រី ";
                }
                $attendants[]= array(
                    "attend_name"=> $officerGender.$row->officer->officer_name_khmer,
                    "role" => $row->officer->officerRole->officer_role,
                );

            }elseif($row->attendant_type_id < 6){
                if($row->disputant->gender == 1){
                    $disGender = "លោក  ";
                }elseif($row->disputant->gender == 2){
                    $disGender = "លោកស្រី ";
                }
                $attendants[]= array(
                    "attend_name"=> $disGender.$row->disputant->name,
                    // "role" => $row->disputant->occupation,
                    "role" => $row->caseDisputant->occupation,
                );
            }
        }
        $phpWord->setValue('noter', xmlEntities($noter));
        $phpWord->cloneRowAndSetValues('attend_name', $attendants);

        // Union1 List
        $j = 0;
        if(count($log5Union1) > 0){
            foreach ($log5Union1 as $union){
                $union1_name[$j]= array(
                    "union1_name"=> $union->union1_name,
                );
                $j++;
            }
//        dd($union1_name);
            $phpWord->cloneRowAndSetValues('union1_name', $union1_name);
        }
        else{
            $phpWord->setValue('union1_name', "");
        }



        /** ===================3. Download File ======================== */
        $fileToSave = "កំណត់ហេតុសាកសួរពត៌មានរោងចក្រសហគ្រាស"."_".myDate("m_d_Y").".docx";//file name to

        try{
//            $phpWord->saveAs(storage_path($fileToSave));
            $phpWord->saveAs(public_path($fileToSave)); //Create new file and save it to public folder

        }catch (Exception $e){
            //handle exception
        }
//        return response()->download(storage_path($fileToSave))->deleteFileAfterSend(true);
        return response()->download(public_path($fileToSave))->deleteFileAfterSend(true);

    }

    /** date: 08-02-2024 soklay code */
    public function exportLog6($id){
        $log6 = CaseLog6::where('id', $id)->first();
        $case = $log6->case;
        $com = $case->company;
        $comCase = $case->caseCompany;
        $emp = $case->disputant;
        $empCase = $case->caseDisputant;
//        $caseOfficer = getLastOfficer($case->id, 6); //LastOfficer
        $attendantsAll = $case->log6Detail->attendant;

        if(is_null($log6)){
            return abort(404);
        }
//        $file = storage_path("doc_template/6_log6.docx");
        $file = pathToUploadFile("doc_template/6_log6.docx");
        $phpWord = new TemplateProcessor($file);
        /** ===================1. Header and Body Data ======================== */
        $comName = xmlEntities($com->company_name_khmer);
        $arrCaseDate = getDateAsKhmer($case->case_date);
        $arrCaseDateEntry = getDateAsKhmer($case->case_date_entry);
        $arrLog6Date = getDateAsKhmer($log6->log6_date);
        $log6STime = getTimeAsKhmer($log6->log6_stime);
        $log6ETime = getTimeAsKhmer($log6->log6_etime);
        $log6_meeting = $log6->log6_meeting_place_id == 1 ? "នៅនាយកដ្ឋានវិវាទការងារ (ភ្នំពេញ)" : xmlEntities($log6->log6_meeting_other);
        $log6_meeting_about = $log6->log6_meeting_about;
        $log6EmpStatus = $log6->log6_employee_status == 1 ? "ដើមបណ្តឹង" : "ចុងបណ្តឹង";
        $log6ComStatus = $log6->log6_company_status == 1 ? "ដើមបណ្តឹង" : "ចុងបណ្តឹង";
        $phpWord->setValue('company_name', $comName);
        $phpWord->setValue('case_date', "ថ្ងៃទី".$arrCaseDate['day']." ខែ".$arrCaseDate['month']." ឆ្នាំ".$arrCaseDate['year']);
        $phpWord->setValue('case_entry_date', "ថ្ងៃទី".$arrCaseDateEntry['day']." ខែ".$arrCaseDateEntry['month']." ឆ្នាំ".$arrCaseDateEntry['year']);
        $phpWord->setValue('log6_date', "ថ្ងៃទី".$arrLog6Date['day']." ខែ".$arrLog6Date['month']." ឆ្នាំ".$arrLog6Date['year']);
        $phpWord->setValue('log6_stime', $log6STime);
        $phpWord->setValue('log6_etime', $log6ETime);
        $phpWord->setValue('log6_meeting_place', $log6_meeting);
        $phpWord->setValue('log6_meeting_about', $log6_meeting_about);
        $phpWord->setValue('log6_emp_status', $log6EmpStatus);
        $phpWord->setValue('log6_com_status', $log6ComStatus);

        //ដើមបណ្តឹង (Employee Info)
        if($emp->gender == 1){
            $empPrename = "លោក ";
            $empGender = "ប្រុស";
        }elseif($emp->gender == 2){
            $empPrename = "លោកស្រី ";
            $empGender = "ស្រី";
        }

        $empPOB = "";
        $empPOBCom = "";
        $empPOBDis = "";
        $empPOBPro = "";
        if(!empty($emp->pob_country_id) && $emp->pob_country_id != 33){
            $empPOB = "ប្រទេស ".$emp->pobAbroad->nationality_kh;

        }else{
            $empPOBCom = !empty($emp->pobCommune)? "ឃុំ/សង្កាត់ ".$emp->pobCommune->com_khname : "";
            $empPOBDis = !empty($emp->pobDistrict)? "ស្រុក/ខណ្ឌ ".$emp->pobDistrict->dis_khname : "";
            $empPOBPro = !empty($emp->pobProvince)? "ខេត្ត/ក្រុង ".$emp->pobProvince->pro_khname : "";
        }
        if(!empty($empPOBCom) || !empty($empPOBDis) || !empty($empPOBPro)){
            $empPOB = $empPOBCom." ". $empPOBDis." ". $empPOBPro;
        }

        $empName = $emp->name;
        $empDOB = !empty($emp->dob) ? xmlEntities($emp->dob) : "";
        $empRole = !empty($empCase->occupation) ? xmlEntities($empCase->occupation) : "";
        $empPhone = !empty($empCase->phone_number) ? xmlEntities($empCase->phone_number) : "";
        $empPOBCom = !empty($emp->pobCommune->com_khname) ? "ឃុំ/សង្កាត់ ".xmlEntities($emp->pobCommune->com_khname) : "";
        $empPOBDis = !empty($emp->pobDistrict->dis_khname) ? "ស្រុក/ខណ្ឌ ".xmlEntities($emp->pobDistrict->dis_khname) : "";
        $empPOBPro = !empty($emp->pobProvince->pro_khname) ? "ខេត្ត/ក្រុង ".xmlEntities($emp->pobProvince->pro_khname) : "";
        $empHouse = !empty($empCase->house_no) ? "ផ្ទះលេខ ".xmlEntities(Num2Unicode($empCase->house_no)) : "";
        $empStreet = !empty($empCase->street) ? "ផ្លូវ ".xmlEntities(Num2Unicode($empCase->street)) : "";
        $empVil = !empty($empCase->addressVillage) ? "ក្រុម/ភូមិ ".xmlEntities($empCase->addressVillage->vil_khname)  : "";
        $empCom = !empty($empCase->addressCommune) ? "ឃុំ/សង្កាត់ ".xmlEntities($empCase->addressCommune->com_khname)  : "";
        $empDis = !empty($empCase->addressDistrict) ? "ស្រុក/ខណ្ឌ ".xmlEntities($empCase->addressDistrict->dis_khname) : "";
        $empPro = !empty($empCase->addressProvince) ? "ខេត្ត/រាជធានី ".xmlEntities($empCase->addressProvince->pro_khname) : "";

        $phpWord->setValue('emp_name', xmlEntities($empPrename . $empName));
        $phpWord->setValue('emp_pob', xmlEntities($empPOB));
        $phpWord->setValue('emp_gender', xmlEntities($empGender));
        $phpWord->setValue('emp_dob', Num2Unicode($empDOB));
        $phpWord->setValue('emp_role', xmlEntities($empRole));
        $phpWord->setValue('emp_phone', Num2Unicode($empPhone));
        $phpWord->setValue('emp_pob_com', $empPOBCom);
        $phpWord->setValue('emp_pob_dis', $empPOBDis);
        $phpWord->setValue('emp_pob_pro', $empPOBPro);
        $phpWord->setValue('emp_house', $empHouse);
        $phpWord->setValue('emp_street', $empStreet);
        $phpWord->setValue('emp_vil', $empVil);
        $phpWord->setValue('emp_com', $empCom);
        $phpWord->setValue('emp_dis', $empDis);
        $phpWord->setValue('emp_pro', $empPro);


        //ចុងបណ្តឹង (Company Info)
        $comBuilding = !empty($comCase->log5_building_no) ? "អគារលេខ ".xmlEntities(Num2Unicode($comCase->log5_building_no)) : "";
        $comStreet = !empty($comCase->log5_street_no) ? "ផ្លូវ ".xmlEntities(Num2Unicode($comCase->log5_street_no)) : "";
        $comVil = !empty($comCase->village) ? "ក្រុម/ភូមិ ".xmlEntities($comCase->village->vil_khname) : "";
        $comCom = !empty($comCase->commune) ? "ឃុំ/សង្កាត់ ".xmlEntities($comCase->commune->com_khname) : "";
        $comDis = !empty($comCase->district) ? "ស្រុក/ខណ្ឌ ".xmlEntities($comCase->district->dis_khname) : "";
        $comPro = !empty($comCase->province) ? "ខេត្ដ/រាជធានី ".xmlEntities($comCase->province->pro_khname) : "";
        $comPhone = !empty($comCase->log5_company_phone_number) ? xmlEntities(Num2Unicode($comCase->log5_company_phone_number)) : "..............";
        $comTotalEmp = xmlEntities(Num2Unicode($comCase->log5_total_employee));
        $comFirstBusAct = xmlEntities($comCase->log5_first_business_act);
        $phpWord->setValue('com_building', $comBuilding);
        $phpWord->setValue('com_street', $comStreet);
        $phpWord->setValue('com_vil', $comVil);

        $phpWord->setValue('com_com', $comCom);
        $phpWord->setValue('com_dis', $comDis);
        $phpWord->setValue('com_pro', $comPro);
        $phpWord->setValue('com_phone', $comPhone);
        $phpWord->setValue('com_total_emp', $comTotalEmp." នាក់");
        $phpWord->setValue('first_bus_activity', $comFirstBusAct);

        $sub_officer_index = 0;
        $sub_emp_index = 0;
        $sub_com_index = 0;
        $subOfficers = array();
        $subEmps = array();
        $subComs = array();
        $translatorName = "";

        $repreName = "                           ";
        $repreGender = "";
        $repreDOB = "";
        $repreRole = "";
        $reprePhone = "";
        $reprePOBCom = "";
        $reprePOBDis = "";
        $reprePOBPro = "";
        $repreHouse = "";
        $repreStreet = "";
        $repreVil = "";
        $repreCom = "";
        $repreDis = "";
        $reprePro = "";
        foreach ($attendantsAll as $row){
            if($row->attendant_type_id == 1){ //ដើមបណ្តឹង (កម្មករនិយោជិត)
            }
            if($row->attendant_type_id == 2){ //អមដើមបណ្តឹង (អមកម្មករនិយោជិត)
                $subEmp = $row->disputant;
                $subEmpCase = $row->caseDisputant;
                $subEmpPro = "";
                $subEmpDis = "";
                $subEmpCom = "";
                $subEmpVil = "";

                if(!empty($subEmpCase->addressProvince)){
                    $subEmpPro = $subEmpCase->addressProvince->pro_khname;
                }elseif (!empty($subEmp->nowProvince)){
                    $subEmpPro = $subEmp->nowProvince->pro_khname;
                }
                if(!empty($subEmpCase->addressDistrict)){
                    $subEmpDis = $subEmpCase->addressDistrict->dis_khname;
                }elseif(!empty($subEmp->nowDistrict)){
                    $subEmpDis = $subEmp->nowDistrict->dis_khname;
                }
                if(!empty($subEmpCase->addressCommune)){
                    $subEmpCom = $subEmpCase->addressCommune->com_khname;
                }elseif(!empty($subEmp->nowCommune)){
                    $subEmpCom = $subEmp->nowCommune->com_khname;
                }
                if(!empty($subEmpCase->addressVillage)){
                    $subEmpVil = $subEmpCase->addressVillage->vil_khname;
                }elseif(!empty($subEmp->nowVillage)){
                    $subEmpVil = $subEmp->nowVillage->vil_khname;
                }
                $subEmps[$sub_emp_index]= array(
                    "sub_emp_name" => xmlEntities($subEmp->name),
                    "sub_emp_gender" => $subEmp->gender == 1 ? "ប្រុស" : "ស្រី",
                    "sub_emp_dob" => xmlEntities(Num2Unicode($subEmp->dob)),
                    "sub_emp_role" => xmlEntities($subEmp->occupation),
                    "sub_emp_phone" => xmlEntities(Num2Unicode($subEmp->phone_number)),
                    "sub_emp_pob_com" => !empty($subEmp->pobCommune) ? "ឃុំ/សង្កាត់ ".xmlEntities($subEmp->pobCommune->com_khname) : "",
                    "sub_emp_pob_dis" => !empty($subEmp->pobDistrict) ? "ស្រុក/ខណ្ឌ ".xmlEntities($subEmp->pobDistrict->dis_khname) : "",
                    "sub_emp_pob_pro" => !empty($subEmp->pobProvince) ? "ខេត្ត/រាជធានី ".xmlEntities($subEmp->pobProvince->pro_khname) : "",
                    "sub_emp_house" => !empty($subEmp->house_no) ? "ផ្ទះលេខ ".xmlEntities(Num2Unicode($subEmp->house_no)) : "",
                    "sub_emp_street" => !empty($subEmp->street) ? "ផ្លូវ ".xmlEntities(Num2Unicode($subEmp->street)) : "",
                    "sub_emp_vil" =>  "ក្រុម/ភូមិ ".xmlEntities($subEmpVil),
                    "sub_emp_com" => "ឃុំ/សង្កាត់ ".xmlEntities($subEmpCom),
                    "sub_emp_dis" => "ឃុំ/សង្កាត់ ".xmlEntities($subEmpDis),
                    "sub_emp_pro" => "ខេត្ត/រាជធានី ".xmlEntities($subEmpPro),
                );
                $sub_emp_index ++;
            }
            if($row->attendant_type_id == 3){ //ចុងបណ្តឹង (តំណាងនិយោជក)
                $attendant = $row->disputant;
                $attendantCase = $row->caseDisputant;
//                dd($attendant);
                $repreName = !empty($attendant->name) ? xmlEntities($attendant->name) : "";
                if(!empty($attendant->gender) && $attendant->gender == 1){
                    $repreGender = "ប្រុស";
                }elseif(!empty($attendant->gender) && $attendant->gender == 2){
                    $repreGender = "ស្រី";
                }
                $repreDOB = !empty($attendant->dob) ? xmlEntities(Num2Unicode($attendant->dob)) : "";
                $repreRole = !empty($attendant->occupation) ? xmlEntities($attendant->occupation) : "";
                $reprePhone = !empty($attendant->phone_number) ? xmlEntities(Num2Unicode($attendant->phone_number)) : "";
                $reprePOBCom = !empty($attendant->pobCommune) ? "ឃុំ/សង្កាត់ ".xmlEntities($attendant->pobCommune->com_khname) : "";
                $reprePOBDis = !empty($attendant->pobDistrict) ? "ស្រុក/ខណ្ឌ ".xmlEntities($attendant->pobDistrict->dis_khname) : "";
                $reprePOBPro = !empty($attendant->pobProvince) ? "ខេត្ត/រាជធានី ".xmlEntities($attendant->pobProvince->pro_khname) : "";

                if(!empty($attendantCase->house_no)){
                    $repreHouse = "ផ្ទះលេខ ".Num2Unicode($attendantCase->house_no);
                }elseif(!empty($attendant->house_no)){
                    $repreHouse = "ផ្ទះលេខ ".Num2Unicode($attendant->house_no);
                }
                if(!empty($attendantCase->street)){
                    $repreStreet = "ផ្លូវ ".Num2Unicode($attendantCase->street);
                }elseif(!empty($attendant->street)){
                    $repreStreet = "ផ្លូវ ".number2KhmerNumber($attendant->street);
                }
                if(!empty($attendantCase->addressVillage)){
                    $repreVil = "ក្រុម/ភូមិ ".$attendantCase->addressVillage->vil_khname;
                }elseif(!empty($attendant->nowVillage)){
                    $repreVil = "ក្រុម/ភូមិ ".$attendant->nowVillage->vil_khname;
                }
                if(!empty($attendantCase->addressCommune)){
                    $repreCom = "ឃុំ/សង្កាត់ ".$attendantCase->addressCommune->com_khname;
                }elseif(!empty($attendant->nowCommune)){
                    $repreCom = "ឃុំ/សង្កាត់ ".$attendant->nowCommune->com_khname;
                }
                if(!empty($attendantCase->addressDistrict)){
                    $repreDis = "ស្រុក/ខណ្ឌ ".$attendantCase->addressDistrict->dis_khname;
                }elseif(!empty($attendant->nowDistrict)){
                    $repreDis = "ស្រុក/ខណ្ឌ ".$attendant->nowDistrict->dis_khname;
                }
                if(!empty($attendantCase->addressProvince)){
                    $reprePro = "ខេត្ត/រាជធានី ".$attendantCase->addressProvince->pro_khname;
                }elseif(!empty($attendant->nowProvince)){
                    $reprePro = "ខេត្ត/រាជធានី ".$attendant->nowProvince->pro_khname;
                }

            }
            if($row->attendant_type_id == 4){ //អមចុងបណ្តឹង (អមនិយោជក)
                $subCom = $row->disputant;
                if(!empty($subCom->gender) && $subCom->gender == 1){
                    $subComGender = "ប្រុស";
                }elseif(!empty($subCom->gender) && $subCom->gender == 2){
                    $subComGender = "ស្រី";
                }
                $subComs[$sub_com_index]= array(
                    "sub_com_name" => !empty($subCom->name) ? xmlEntities($subCom->name) : "..............",
                    "sub_com_gender" => !empty($subCom->gender) ? xmlEntities($subComGender) : "..............",
                    "sub_com_phone" => !empty($subCom->phone_number) ? xmlEntities(Num2Unicode($subCom->phone_number)) : "..............",
                    "sub_com_role" => !empty($subCom->occupation) ? xmlEntities($subCom->occupation) : "..............",
                    "sub_com_dob" => !empty($subCom->dob) ? xmlEntities(Num2Unicode($subCom->dob)) : "..............",
                    "sub_com_pob_com" => !empty($subCom->pobCommune) ? "ឃុំ/សង្កាត់ ".xmlEntities($subCom->pobCommune->com_khname) : "",
                    "sub_com_pob_dis" => !empty($subCom->pobDistrict) ? "ស្រុក/ខណ្ឌ ".xmlEntities($subCom->pobDistrict->dis_khname) : "",
                    "sub_com_pob_pro" => !empty($subCom->pobProvince) ? "ខេត្ត/រាជធានី ".xmlEntities($subCom->pobProvince->pro_khname) : "",
                    "sub_com_house" => !empty($subCom->house_no) ? "ផ្ទះលេខ ".xmlEntities(Num2Unicode($subCom->house_no)) : "",
                    "sub_com_street" => !empty($subCom->street) ? "ផ្លូវ ".xmlEntities(Num2Unicode($subCom->street)) : "",
                    "sub_com_vil" => !empty($subCom->nowVillage) ? "ក្រុម/ភូមិ ".xmlEntities($subCom->nowVillage->vil_khname) : "",
                    "sub_com_com" => !empty($subCom->nowCommune) ? "ឃុំ/សង្កាត់ ".xmlEntities($subCom->nowCommune->com_khname) : "",
                    "sub_com_dis" => !empty($subCom->nowDistrict) ? "ស្រុក/ខណ្ឌ ".xmlEntities($subCom->nowDistrict->dis_khname) : "",
                    "sub_com_pro" => !empty($subCom->nowProvince) ? "ខេត្ត/រាជធានី ".xmlEntities($subCom->nowProvince->pro_khname) : "",
                );
                $sub_com_index ++;

            }

            if($row->attendant_type_id == 5){ //អ្នកបកប្រែ
                $translatorName = $row->officer->officerRole->officer_role;
            }
            if($row->attendant_type_id == 6){ //អ្នកផ្សះផ្សារ
                if($row->officer->sex == 1){
                    $officerGender = "លោក  ";
                }elseif($row->officer->sex == 2){
                    $officerGender = "លោកស្រី ";
                }
                $officerName = $officerGender.$row->officer->officer_name_khmer;
                $officerRole = $row->officer->officerRole->officer_role;

                $phpWord->setValue('officer_name', $officerName);
                $phpWord->setValue('officer_role', $officerRole);


            }

            if($row->attendant_type_id == 7){ //អមអ្នកផ្សះផ្សារ
                if($row->officer->sex == 1){
                    $officerGender = "លោក ";
                }elseif($row->officer->sex == 2){
                    $officerGender = "លោកស្រី ";
                }

                $subOfficers[]= array(
//                    "sub_officer_index" => Num2Unicode($sub_officer_index + 1),
                    "sub_officer_name" => $officerGender.$row->officer->officer_name_khmer,
                    "sub_officer_role" => $row->officer->officerRole->officer_role,
                );
                $sub_officer_index ++;
            }
            if($row->attendant_type_id == 8){ //អ្នកកត់ត្រា
                if($row->officer->sex == 1){
                    $officerGender = "លោក  ";
                }elseif($row->officer->sex == 2){
                    $officerGender = "លោកស្រី ";
                }
                $phpWord->setValue('noter_name', $officerGender.$row->officer->officer_name_khmer);
                $phpWord->setValue('noter_role', $row->officer->officerRole->officer_role);
            }

        }


        $phpWord->setValue('repre_name', xmlEntities($repreName));
        $phpWord->setValue('repre_gender', xmlEntities($repreGender));
        $phpWord->setValue('repre_dob', xmlEntities($repreDOB));
        $phpWord->setValue('repre_role', xmlEntities($repreRole));
        $phpWord->setValue('repre_phone', xmlEntities($reprePhone));
        $phpWord->setValue('repre_pob_com', xmlEntities($reprePOBCom));
        $phpWord->setValue('repre_pob_dis', xmlEntities($reprePOBDis));
        $phpWord->setValue('repre_pob_pro', xmlEntities($reprePOBPro));
        $phpWord->setValue('repre_house', xmlEntities($repreHouse));
        $phpWord->setValue('repre_street', xmlEntities($repreStreet));
        $phpWord->setValue('repre_vil', xmlEntities($repreVil));
        $phpWord->setValue('repre_com', xmlEntities($repreCom));
        $phpWord->setValue('repre_dis', xmlEntities($repreDis));
        $phpWord->setValue('repre_pro', xmlEntities($reprePro));

        $phpWord->setValue('log6_17', xmlEntities($log6->log6_17));
        $phpWord->setValue('log6_181', xmlEntities($log6->log6_181));
        $phpWord->setValue('log6_182', xmlEntities($log6->log6_182));
        $phpWord->setValue('log6_19', xmlEntities($log6->log6_19));
        $phpWord->setValue('log6_19a', !empty($log6->log6_19a) ? xmlEntities($log6->log6_19a) : "គ្មាន");
        $phpWord->setValue('log6_22', xmlEntities($log6->log6_22));
        $phpWord->setValue('log6_day', Num2Unicode($arrLog6Date['day']));
        $phpWord->setValue('log6_month', Num2Unicode($arrLog6Date['month']));
        $phpWord->setValue('log6_year', Num2Unicode($arrLog6Date['year']));
        $phpWord->setValue('translator_name', !empty($translatorName) ? xmlEntities($translatorName) : "..............");
        $phpWord->cloneRowAndSetValues('sub_emp_name', $subEmps);
        $phpWord->cloneRowAndSetValues('sub_com_name', $subComs);
        $phpWord->cloneRowAndSetValues('sub_officer_name', $subOfficers);

        $agree_points_index = 0;
        $agreement = array();
//        $agree_points = array();
        foreach ($log6->log620 as $log620){
            $agreement [$agree_points_index] = array(
                "agreement" => "- ".$log620->agree_point." ".$log620->solution
            );
//            $agree_points[$agree_points_index]= array(
//                "agree_index" => "-ចំណុចទី".Num2Unicode($agree_points_index + 1),
//                "agree_points"=> !empty($log620->agree_point) ? "លោកស្នើសុំឱ្យក្រុមហ៊ុនទូទាត់ ".$log620->agree_point : "",
//                "agree_solutions" => !empty($log620->solution) ? "តំណាងនិយោជកបញ្ជាក់ថា ".$log620->solution : ""
//            );
            $agree_points_index ++;
        }
//        dd($agreement);
        if (count($agreement) === 0) {
            $agreement = array(
                array(
                    "agreement" => "- គ្មាន",
                )
            );
        }
//        dd($agreement);
        $phpWord->cloneRowAndSetValues('agreement', $agreement);
//        $phpWord->cloneRowAndSetValues('agree_points', $agree_points);

        $disagree_points_index = 0;
//        $disagree_points = array();
        $disagreement = array();
        foreach ($log6->log621 as $log621){
            $disagreement[$disagree_points_index] = array(
                "disagreement" => "- ".$log621->disagree_point." ".$log621->solution
            );
//            $disagree_points[$disagree_points_index]= array(
//                "disagree_index" => "-ចំណុចទី".Num2Unicode($disagree_points_index + 1),
//                "disagree_points"=> !empty($log621->disagree_point) ? "លោកស្នើសុំឱ្យក្រុមហ៊ុនទូទាត់ ".$log621->disagree_point : "គ្មាន",
//                "disagree_solutions" => !empty($log621->solution) ? "តំណាងនិយោជកបញ្ជាក់ថា ".$log621->solution : "គ្មាន"
//            );
            $disagree_points_index ++;
        }

        // Check if $disagreement array is empty
        if (count($disagreement) === 0) {
            $disagreement = array(
                array(
                    "disagreement" => "- គ្មាន",
                )
            );
        }
        $phpWord->cloneRowAndSetValues('disagreement', $disagreement);


//        $log6_cause = "";
//        if(!empty($log6->log624)){
//            $log6_cause = $log6->log624->id <> 11 ? $log6->log624->cause_name : $log6->log624_cause_other;
//        }
//        $log6_solution = "";
//        if(!empty($log6->log625)){
//            $log6_solution = $log6->log625->solution_name;
//        }


//        $phpWord->setValue('log6_24', xmlEntities($log6_cause));
//        $phpWord->setValue('log6_25', xmlEntities($log6_solution));
//        $phpWord->setValue('log6_comment', !empty($log6->log6_comment) ? xmlEntities($log6->log6_comment) : "..............");



        /** ===================3. Download File ======================== */
        $fileToSave = "កំណត់ហេតុនៃការផ្សះផ្សារវិវាទបុគ្គល"."_".myDate("m_d_Y").".docx";//file name to

        try{
//            $phpWord->saveAs(storage_path($fileToSave));
            $phpWord->saveAs(public_path($fileToSave)); //Create new file and save it to public folder

        }catch (Exception $e){
            //handle exception
        }
//        return response()->download(storage_path($fileToSave))->deleteFileAfterSend(true);
        return response()->download(public_path($fileToSave))->deleteFileAfterSend(true);



    }


    public function exportInvitation($invitation_id = 0, $type = 1) //type = 1 អញ្ញើញផ្តល់ពត៌មាន type=2 អញ្ញើញផ្តល់ពត៌មាន + ផ្សះផ្សារ
    {
        $num = 1;
        $str = "";
        $row = CaseInvitation::where('id', $invitation_id)->first();
        //dd($row->invitationType->employee_or_company); // =1 employee, =2 company
        if(!empty($row->nextTimeLatest)){
            $num = $row->nextTime->count() + 1;
            $str = "លើកទី".Num2Unicode($num);
            $arrayMeetingDate = getDateAsKhmer($row->nextTimeLatest->next_date);
            $meeting_time = $row->nextTimeLatest->next_time;
        }
        else{
            $arrayMeetingDate = getDateAsKhmer($row->meeting_date);
            $meeting_time = $row->meeting_time;
        }
        //dd($arrayMeetingDate);
        if(is_null($row)){
            return abort(404);
        }
//        $file = storage_path("doc_template/invitation_letter.docx");
        $file = pathToUploadFile("doc_template/invitation_letter.docx");
        $type_group_short = $row->invitationType->group->type_group_short;
        if($type == 2){//លិខិតអញ្ញើញ ផ្តល់ពត៌មាន + ផ្សះផ្សារ
//            $file = storage_path("doc_template/invitation_reconcilation.docx");
            $file = pathToUploadFile("doc_template/invitation_reconcilation.docx");
            $type_group_short = "";
        }
        $phpWord = new \PhpOffice\PhpWord\TemplateProcessor($file);
        /** ===================1. Header and Body Data ======================== */
        //dd($row->invitationType->group->type_group_short);
        $invitation_lebel = "";
        $invitation_name = "";
        $for = "";
        if($row->invitationType->employee_or_company == 1){ //invite employee
            $invitation_lebel = $row->disputant->name;
            $gender = $row->disputant->gender == 1? "លោក ":"លោកស្រី ";
            $invitation_name.= $gender;
            $invitation_name.= $row->disputant->name;
            $arrayCaseDate = getDateAsKhmer($row->case->case_date);
            $caseDate = "ចុះថ្ងៃទី ".$arrayCaseDate['day']." ខែ".$arrayCaseDate['month']." ឆ្នាំ ".$arrayCaseDate['year'];
            $for.= $type_group_short ." ពាក់ព័ន្ធនឹងបណ្ដឹងរបស់".$gender.$caseDate;
            $label1 = $gender;
            $label2 = $gender;
        }
        else{ //invite company
            $invitation_lebel = xmlEntities($row->company->company_name_khmer);
            $companyType = !empty($row->company->companyType) ? $row->company->companyType->company_type_name : "";
            $invitation_name.= "លោក/លោកស្រី នាយក ".$companyType." ".$row->company->company_name_khmer;
            $gender = $row->disputant->gender == 1? "លោក ":"លោកស្រី ";
            $arrayCaseDate = getDateAsKhmer($row->case->case_date);
            $caseDate = "ចុះថ្ងៃទី ".$arrayCaseDate['day']." ខែ".$arrayCaseDate['month']." ឆ្នាំ ".$arrayCaseDate['year'];
            $for.= $type_group_short ." ពាក់ព័ន្ធនឹងបណ្ដឹងរបស់".$gender.$row->disputant->name." ".$caseDate;

            $label1 = "លោក/លោកស្រី នាយក";
            $label2 = "លោក/លោកស្រី នាយក";
        }
        $invNumber = !empty($row->invitation_number) ? Num2Unicode($row->invitation_number) : "...............";
        $phpWord->setValue('invitation_title', $str);
        $phpWord->setValue('invitation_name', xmlEntities($invitation_name));
        $phpWord->setValue('invitation_number', xmlEntities($invNumber));
        //$arrayMeetingDate = getDateAsKhmer($row->meeting_date);
        $phpWord->setValue('day', $arrayMeetingDate["day"]);
        $phpWord->setValue('month', $arrayMeetingDate["month"]);
        $phpWord->setValue('year', $arrayMeetingDate["year"]);

        $phpWord->setValue('time', getTimeAsKhmer($meeting_time)."នាទី");
        $phpWord->setValue('for', $for);
        $phpWord->setValue('invitation_required_doc', xmlEntities($row->invitation_required_doc));
        $phpWord->setValue('label1', $label1);
        $phpWord->setValue('label2', $label2);
        /** ===================2. Bottom Date ======================== */
        $phpWord->setValue('contact_phone', $row->contact_phone);
        $phpWord->setValue('letter_khmer_date', khmerDate($row->letter_date));
        $arrayLettergDate = getDateAsKhmer($row->letter_date);
        $phpWord->setValue('lday', $arrayLettergDate["day"]);
        $phpWord->setValue('lmonth', $arrayLettergDate["month"]);
        $phpWord->setValue('lyear', $arrayLettergDate["year"]);

        /** ===================3. Download File ======================== */
        $fileToSave = "លិខិតអញ្ជើញ".$type_group_short."_".$invitation_lebel."_".myDate("d_m_Y").".docx";//file name to
        try{
//            $phpWord->saveAs(storage_path($fileToSave));
            $phpWord->saveAs(public_path($fileToSave)); //Create new file and save it to public folder

        }catch (Exception $e){
            //handle exception
        }
//        return response()->download(storage_path($fileToSave))->deleteFileAfterSend(true);
        return response()->download(public_path($fileToSave))->deleteFileAfterSend(true);
    }
    public function exportInvitationEmployee($invitation_id = 0)
    {
        $row = CaseInvitation::where('id', $invitation_id)->first();

        if(is_null($row)){
            return abort(404);
        }
//        $file = storage_path("doc_template/invitation_letter.docx");
        $file = pathToUploadFile("doc_template/invitation_letter.docx");
        $phpWord = new \PhpOffice\PhpWord\TemplateProcessor($file);
        /** ===================1. Header and Body Data ======================== */
        //dd($row->invitationType->group->type_group_short);
        $invitation_lebel = "";
        $invitation_name = "";
        $for="";
        $type_group_short = $row->invitationType->group->type_group_short;
        if($row->invitationType->employee_or_company == 1){ //invite employee
            $invitation_lebel = $row->disputant->name;
            $gender = $row->disputant->gender == 1? "លោក ":"លោកស្រី ";
            $invitation_name.= $gender;
            $invitation_name.= $row->disputant->name;
            $arrayCaseDate = getDateAsKhmer($row->case->case_date);
            $caseDate = "ចុះថ្ងៃទី ".$arrayCaseDate['day']." ខែ".$arrayCaseDate['month']." ឆ្នាំ ".$arrayCaseDate['year'];
            $for.= $type_group_short ." ពាក់ព័ន្ធនឹងបណ្ដឹងរបស់".$gender.$caseDate;
            $label1 = $gender;
            $label2 = $gender;
        }
        else{ //invite company
            $invitation_lebel = xmlEntities($row->company->company_name_khmer);
            $invitation_name.= "លោក/លោកស្រី ជាតំណាង ".$row->company->company_name_khmer;
            $gender = $row->disputant->gender == 1? "លោក ":"លោកស្រី ";
            $arrayCaseDate = getDateAsKhmer($row->case->case_date);
            $caseDate = "ចុះថ្ងៃទី ".$arrayCaseDate['day']." ខែ".$arrayCaseDate['month']." ឆ្នាំ ".$arrayCaseDate['year'];
            $for.= $type_group_short ." ពាក់ព័ន្ធនឹងបណ្ដឹងរបស់".$gender.$row->disputant->name." ".$caseDate;

            $label1 = "លោក/លោកស្រី";
            $label2 = "លោក/លោកស្រី";
        }
        $phpWord->setValue('invitation_name', xmlEntities($invitation_name));
        $arrayMeetingDate = getDateAsKhmer($row->meeting_date);
        $phpWord->setValue('day', $arrayMeetingDate["day"]);
        $phpWord->setValue('month', $arrayMeetingDate["month"]);
        $phpWord->setValue('year', $arrayMeetingDate["year"]);

        $phpWord->setValue('time', getTimeAsKhmer($row->meeting_time)."នាទី");
        $phpWord->setValue('for', $for);
        $phpWord->setValue('invitation_required_doc', xmlEntities($row->invitation_required_doc));
        $phpWord->setValue('label1', $label1);
        $phpWord->setValue('label2', $label2);
        /** ===================2. Bottom Date ======================== */
        $phpWord->setValue('contact_phone', $row->contact_phone);
        $phpWord->setValue('letter_khmer_date', khmerDate($row->letter_date));
        $arrayLettergDate = getDateAsKhmer($row->letter_date);
        $phpWord->setValue('lday', $arrayLettergDate["day"]);
        $phpWord->setValue('lmonth', $arrayLettergDate["month"]);
        $phpWord->setValue('lyear', $arrayLettergDate["year"]);

        /** ===================3. Download File ======================== */
        $fileToSave = "លិខិតអញ្ជើញ".$type_group_short."_".$invitation_lebel."_".myDate("m_d_Y").".docx";//file name to
        try{
//            $phpWord->saveAs(storage_path($fileToSave));
            $phpWord->saveAs(public_path($fileToSave)); //Create new file and save it to public folder

        }catch (Exception $e){
            //handle exception
        }
//        return response()->download(storage_path($fileToSave))->deleteFileAfterSend(true);
        return response()->download(public_path($fileToSave))->deleteFileAfterSend(true);
    }
    public function exportInvitationCompany($invitation_id = 0)
    {
        $row = CaseInvitation::where('id', $invitation_id)->first();

        if(is_null($row)){
            return abort(404);
        }
//        $file = storage_path("doc_template/invitation_letter.docx");
        $file = pathToUploadFile("doc_template/invitation_letter.docx");
        $phpWord = new \PhpOffice\PhpWord\TemplateProcessor($file);
        /** ===================1. Header and Body Data ======================== */
        //dd($row->invitationType->group->type_group_short);
        $invitation_lebel = "";
        $invitation_name = "";
        $for="";
        $type_group_short = $row->invitationType->group->type_group_short;
        if($row->invitationType->employee_or_company == 1){ //invite employee
            $invitation_lebel = $row->disputant->name;
            $gender = $row->disputant->gender == 1? "លោក ":"លោកស្រី ";
            $invitation_name.= $gender;
            $invitation_name.= $row->disputant->name;
            $arrayCaseDate = getDateAsKhmer($row->case->case_date);
            $caseDate = "ចុះថ្ងៃទី ".$arrayCaseDate['day']." ខែ".$arrayCaseDate['month']." ឆ្នាំ ".$arrayCaseDate['year'];
            $for.= $type_group_short ." ពាក់ព័ន្ធនឹងបណ្ដឹងរបស់".$gender.$caseDate;
        }
        else{ //invite company
            $invitation_lebel = $row->company->company_name_khmer;
        }
        $phpWord->setValue('invitation_name', xmlEntities($invitation_name));
        $arrayMeetingDate = getDateAsKhmer($row->meeting_date);
        $phpWord->setValue('day', $arrayMeetingDate["day"]);
        $phpWord->setValue('month', $arrayMeetingDate["month"]);
        $phpWord->setValue('year', $arrayMeetingDate["year"]);

        $phpWord->setValue('time', getTimeAsKhmer($row->meeting_time)."នាទី");
        $phpWord->setValue('for', $for);
        $phpWord->setValue('invitation_required_doc', xmlEntities($row->invitation_required_doc));
        $phpWord->setValue('label1', $gender);
        $phpWord->setValue('label2', $gender);
        /** ===================2. Bottom Date ======================== */
        $arrayLettergDate = getDateAsKhmer($row->letter_date);
        $phpWord->setValue('lday', $arrayLettergDate["day"]);
        $phpWord->setValue('lmonth', $arrayLettergDate["month"]);
        $phpWord->setValue('lyear', $arrayLettergDate["year"]);

        /** ===================3. Download File ======================== */
        $fileToSave = "លិខិតអញ្ជើញ".$type_group_short."_".$invitation_lebel."_".myDate("m_d_Y").".docx";//file name to
        try{
//            $phpWord->saveAs(storage_path($fileToSave));
            $phpWord->saveAs(public_path($fileToSave)); //Create new file and save it to public folder

        }catch (Exception $e){
            //handle exception
        }
//        return response()->download(storage_path($fileToSave))->deleteFileAfterSend(true);
        return response()->download(public_path($fileToSave))->deleteFileAfterSend(true);
    }



    /** date: 02-10-2023
     *Export Check list (Letter7) for inspection type=1 for Garment Category
     */
    public function exportCheckListNormalInspectionGarmentCategory($inspection_id)
    {
        ini_set('memory_limit', '-1');
        $row = Inspection::where("id", $inspection_id)->first();
        if(is_null($row)){
            return abort(404);
        }

        $file=storage_path('letter_templete/checklist_result_new.docx');
        //$file=storage_path('letter_templete/checklist_result_old_version.docx');
        //$file=storage_path('letter_templete/checklist_result_self_decleration.docx');
        //$file=storage_path('letter_templete/other_category/checklist_result_other_category.docx');

        $phpWord = new \PhpOffice\PhpWord\TemplateProcessor($file); //file template to open
        /** ==================1. Initiallize Data ========================= */
        //dd("Hello");
        /** ==============1.1 Main Info for using ===========*/

        for($i=4; $i<=10; $i++)
        {
            $phpWord->setValue('block_menu'.$i, "");
            $phpWord->setValue('/block_menu'.$i, "");
        }
        /** ===============Menu 0: Officer======== **/
        exportMenu0($row, $phpWord);
        exportMenu1($row, $phpWord);
        exportMenu2($row, $phpWord);
        exportMenu3($row, $phpWord);
        exportMenu4($row, $phpWord);
        exportMenu5($row, $phpWord);
        exportMenu6($row, $phpWord);
        exportMenu7($row, $phpWord);
        exportMenu8($row, $phpWord);
        exportMenu9($row, $phpWord);
        exportMenu10($row, $phpWord);
        exportMenu11($row, $phpWord);
        exportMenu12($row, $phpWord);
        exportMenu13($row, $phpWord);
        exportMenu14($row, $phpWord);

//        $phpWord->setValue('menu14_d1', xmlEntities("ឃ. លោក លោកស្រីជាម្ចាស់ ឬនាយករោងចក្រ សហគ្រាស ត្រូវអនុវត្តលក្ខខណ្ឌនៃបេឡាជាតិសន្តិសុខសង្គមឱ្យត្រឹមត្រូវ តាមច្បាប់ស្ដីពីរបបសន្តិសុខសង្គម ចាប់ពីថ្ងៃចុះធ្វើអធិការកិច្ចការងារនេះតទៅ គឺ៖"));
//        $phpWord->setValue('menu14_d2', xmlEntities("ប្រសិនបើ លោក លោកស្រីជាម្ចាស់ ឬនាយករោងចក្រ សហគ្រាស មិនព្រមអនុវត្តឱ្យបានត្រឹមត្រូវតាមការដាក់កំហិតខាងលើទេនោះ នឹងត្រូវទទួលការផាកពិន័យ ឬផ្ដន្ទាទោសតាមច្បាប់ស្ដីពីការងារ និងបទប្បញ្ញត្តិផ្សេងទៀតជាធរមាន។"));
//        $phpWord->setValue('menu14_e1', xmlEntities("ង. លោកលោក លោស្រីជាម្ចាស់ ឬនាយករោងចក្រ សហគ្រាសត្រូវលក្ខខណ្ឌនៃទំនាក់ទំនងវិជ្ជាជីវៈឱ្យត្រឹមត្រូវ តាមច្បាប់ស្ដីពីការងារ និងច្បាប់ស្ដីពីសហជីព ចាប់ពីថ្ងៃចុះធ្វើអធិការកិច្ចការងារនេះតទៅ គឺ៖"));
//        $phpWord->setValue('menu14_e2', xmlEntities("ប្រសិនបើ លោក លោកស្រីជាម្ចាស់ ឬនាយករោងចក្រ សហគ្រាស មិនព្រមអនុវត្តឱ្យបានត្រឹមត្រូវតាមការដាក់កំហិតខាងលើទេនោះ នឹងត្រូវទទួលការផាកពិន័យ ឬផ្ដន្ទាទោសតាមច្បាប់ស្ដីពីការងារ និងបទប្បញ្ញត្តិផ្សេងទៀតជាធរមាន។"));
        //$phpWord->setValue('menu14_last', xmlEntities("ប្រសិនបើ លោក លោកស្រីជាម្ចាស់ ឬនាយករោងចក្រ សហគ្រាស មិនព្រមអនុវត្តឱ្យបានត្រឹមត្រូវតាមការដាក់កំហិតខាងលើទេនោះ នឹងត្រូវទទួលការផាកពិន័យ ឬផ្ដន្ទាទោសតាមច្បាប់ស្ដីពីការងារ និងបទប្បញ្ញត្តិផ្សេងទៀតជាធរមាន។"));
        /** ===================1.3 Bottom Date ======================== */
        $arr_end=$this->get_datetime_word("", $row->insp_end_time);
        $phpWord->setValue('insp_end_time', $arr_end["txt_time"]);


        $phpWord->setValue('khmer_date', khmerDate());
        $provinceName = $row->company->province->pro_khname;
        if($row->company->business_province == 12)
            $provinceName = __("general.capital_city");

        $arr_bottom= getDateProvinceAsKhmer($row->insp_date);
        $phpWord->setValue('pro_name', $provinceName);
        $phpWord->setValue('b_day', $arr_bottom["day"]);
        $phpWord->setValue('b_month', __("general.month_".$arr_bottom["month"]));
        $phpWord->setValue('b_year', $arr_bottom["year"]);

        //dd($row);
        /** ===================3. Download File ======================== */
        $fileToSave = "របាយការណ៍ស្ដីពីអធិការកិច្ចការងារ7_garment_".date("d_m_Y")."_".time().".docx";//file name to
        try{
            //$phpWord->saveAs(storage_path($fileToSave));
            $phpWord->saveAs(public_path($fileToSave)); //Create new file and save it to public folder

        }catch (Exception $e){
            //handle exception
        }
//        return response()->download(storage_path($fileToSave))->deleteFileAfterSend(true);
        return response()->download(public_path($fileToSave))->deleteFileAfterSend(true);

    }
    /** date: 10-10-2023
     *Export Check list (Letter7) for inspection type=2 for Other Category
     */
    public function exportCheckListNormalInspectionOtherCategory($inspection_id)
    {
        ini_set('memory_limit', '-1');
        $row = Inspection::where("id", $inspection_id)->first();
        if(is_null($row)){
            return abort(404);
        }

        //$file=rurl('letter_templete/other_category/checklist_result_other_category.docx');
        $file=storage_path('letter_templete/other_category/checklist_result_other_category.docx');
        //$phpWord = new \PhpOffice\PhpWord\TemplateProcessor($file);//file template to open
        $phpWord = new \PhpOffice\PhpWord\TemplateProcessor($file);

//        $file=rurl('letter_template/checklist_result.docx');//file template to open
//        $phpWord = new \PhpOffice\PhpWord\TemplateProcessor($file);
        /** ==================1. Initiallize Data ========================= */
        $str="";
        $str2="";
        $str3="";
        $str4="";
        $str5="";
        $checked = '<w:sym w:font="Wingdings" w:char="F0FE"/>';
        $unchecked = '<w:sym w:font="Wingdings" w:char="F0A8"/>';
        $arr_a=array();
        for($i=0;$i<=25;$i++){
            $arr_a[$i]=$unchecked;
        }
        /** ==============1.1 Main Info for using ===========*/
        $company_id= $row->insp_company_id;
        $province_id= $row->company->business_province;
        $insp_date= $row->insp_date;
        $insp_start_time=$row->insp_start_time;
        $insp_end_time=$row->insp_end_time;
        $arr_datetime= getDateTimeAsWord($insp_date, $insp_start_time);
        $insp_type=$row->insp_type;


        for($i=4; $i<=10; $i++)
        {
            $phpWord->setValue('block_menu'.$i, "");
            $phpWord->setValue('/block_menu'.$i, "");
        }

        /** ===============Menu 0: Officer======== **/
        exportMenu0($row, $phpWord);
        exportOtherMenu1($row, $phpWord);
        exportOtherMenu2($row, $phpWord);
        exportOtherMenu3($row, $phpWord);
        exportOtherMenu4($row, $phpWord);
        exportOtherMenu5($row, $phpWord);
        exportOtherMenu6($row, $phpWord);
        exportOtherMenu7($row, $phpWord);
        exportOtherMenu8($row, $phpWord);
        exportOtherMenu9($row, $phpWord);
        exportOtherMenu10($row, $phpWord);
        exportOtherMenu11($row, $phpWord);
        exportOtherMenu12($row, $phpWord);
        exportOtherMenu13($row, $phpWord);
        exportOtherMenu14($row, $phpWord);

        /** ===================1.3 Bottom Date ======================== */
        $arr_end=$this->get_datetime_word("", $insp_end_time);
        $phpWord->setValue('insp_end_time', $arr_end["txt_time"]);

        $phpWord->setValue('khmer_date', khmerDate());
        $provinceName = $row->company->province->pro_khname;
        if($row->company->business_province == 12)
            $provinceName = __("general.capital_city");

        $arr_bottom= getDateProvinceAsKhmer($insp_date);
        $phpWord->setValue('pro_name', $provinceName);
        $phpWord->setValue('b_day', $arr_bottom["day"]);
        $phpWord->setValue('b_month', __("general.month_".$arr_bottom["month"]));
        $phpWord->setValue('b_year', $arr_bottom["year"]);

        /** ===================3. Download File ======================== */
        $fileToSave = "របាយការណ៍ស្ដីពីអធិការកិច្ចការងារ7_other_".date("d_m_Y")."_".time().".docx";//file name to
        try{
//            $phpWord->saveAs(storage_path($fileToSave));
            $phpWord->saveAs(public_path($fileToSave)); //Create new file and save it to public folder
        }catch (Exception $e){
            //handle exception
        }
//        return response()->download(storage_path($fileToSave))->deleteFileAfterSend(true);
        return response()->download(public_path($fileToSave))->deleteFileAfterSend(true);

    }
    /** date: 13-12-2023
     *Export Check list (Letter7) for self inspection garment category
     */
    public function exportSelfDeclarationGarmentCategory($inspection_id, $json_opt = 0)
    {
        ini_set('memory_limit', '-1');
        $row = Inspection::where("id", $inspection_id)->first();
        if(is_null($row)){
            return abort(404);
        }
        $file=storage_path('letter_templete/checklist_result_self_decleration.docx');
        $phpWord = new \PhpOffice\PhpWord\TemplateProcessor($file);

        /** ==================1. Initiallize Data ========================= */
        $checked = '<w:sym w:font="Wingdings" w:char="F0FE"/>';
        $unchecked = '<w:sym w:font="Wingdings" w:char="F0A8"/>';
        $arr_a=array();
        for($i=0;$i<=25;$i++){
            $arr_a[$i]=$unchecked;
        }
        /** ==============1.1 Main Info for using ===========*/
        $company_id= $row->insp_company_id;
        $province_id= $row->company->business_province;
        $insp_date= $row->insp_date;
        $insp_start_time=$row->insp_start_time;
        $insp_end_time=$row->insp_end_time;
        $arr_datetime= getDateTimeAsWord($insp_date, $insp_start_time);
        $insp_type=$row->insp_type;


//        for($i=4; $i<=10; $i++)
//        {
//            $phpWord->setValue('block_menu'.$i, "");
//            $phpWord->setValue('/block_menu'.$i, "");
//        }

        /** ===============Menu 0: Officer======== **/
        exportMenu1($row, $phpWord);
        exportMenu2($row, $phpWord);
        exportMenu3($row, $phpWord);
        exportMenu4($row, $phpWord);
        exportMenu5($row, $phpWord);
        exportMenu6($row, $phpWord);
        exportMenu7($row, $phpWord);
        exportMenu8($row, $phpWord);
        exportMenu9($row, $phpWord);
        exportMenu10($row, $phpWord);

        /** ===================1.3 Bottom Date ======================== */
        $arr_end=$this->get_datetime_word("", $insp_end_time);
        $phpWord->setValue('insp_end_time', $arr_end["txt_time"]);

        $phpWord->setValue('khmer_date', khmerDate());
        $provinceName = $row->company->province->pro_khname;
        if($row->company->business_province == 12)
            $provinceName = __("general.capital_city");

        $arr_bottom= getDateProvinceAsKhmer($insp_date);
        $phpWord->setValue('pro_name', $provinceName);
        $phpWord->setValue('b_day', $arr_bottom["day"]);
        $phpWord->setValue('b_month', __("general.month_".$arr_bottom["month"]));
        $phpWord->setValue('b_year', $arr_bottom["year"]);

        /** ===================3. Download File ======================== */
        $fileToSave = "របាយការណ៍ស្វ័យប្រកាសអធិការកិច្ចការងារ_garment_".date("d_m_Y")."_".time().".docx";//file name to
        try{
//            $phpWord->saveAs(storage_path($fileToSave));
            $phpWord->saveAs(public_path($fileToSave)); //Create new file and save it to public folder
        }catch (Exception $e){
            //handle exception
        }
//        return response()->download(storage_path($fileToSave))->deleteFileAfterSend(true);
        return response()->download(public_path($fileToSave))->deleteFileAfterSend(true);

    }
    /** date: 10-10-2023
     *Export Check list (Letter7) for self inspection Other Category
     */
    public function exportSelfDeclarationOtherCategory($inspection_id, $json_opt = 0)
    {
        ini_set('memory_limit', '-1');
        $row = Inspection::where("id", $inspection_id)->first();
        if(is_null($row)){
            return abort(404);
        }
        $file=storage_path('letter_templete/other_category/checklist_result_self_declaration_other_category.docx');
        $phpWord = new \PhpOffice\PhpWord\TemplateProcessor($file);

        /** ==================1. Initiallize Data ========================= */
        $checked = '<w:sym w:font="Wingdings" w:char="F0FE"/>';
        $unchecked = '<w:sym w:font="Wingdings" w:char="F0A8"/>';
        $arr_a=array();
        for($i=0;$i<=25;$i++){
            $arr_a[$i]=$unchecked;
        }
        /** ==============1.1 Main Info for using ===========*/
        $company_id= $row->insp_company_id;
        $province_id= $row->company->business_province;
        $insp_date= $row->insp_date;
        $insp_start_time=$row->insp_start_time;
        $insp_end_time=$row->insp_end_time;
        $arr_datetime= getDateTimeAsWord($insp_date, $insp_start_time);
        $insp_type=$row->insp_type;


//        for($i=4; $i<=10; $i++)
//        {
//            $phpWord->setValue('block_menu'.$i, "");
//            $phpWord->setValue('/block_menu'.$i, "");
//        }

        /** ===============Menu 0: Officer======== **/
        exportOtherMenu1($row, $phpWord);
        exportOtherMenu2($row, $phpWord);
        exportOtherMenu3($row, $phpWord);
        exportOtherMenu4($row, $phpWord);
        exportOtherMenu5($row, $phpWord);
        exportOtherMenu6($row, $phpWord);
        exportOtherMenu7($row, $phpWord);
        exportOtherMenu8($row, $phpWord);
        exportOtherMenu9($row, $phpWord);
        exportOtherMenu10($row, $phpWord);

        /** ===================1.3 Bottom Date ======================== */
        $arr_end=$this->get_datetime_word("", $insp_end_time);
        $phpWord->setValue('insp_end_time', $arr_end["txt_time"]);

        $phpWord->setValue('khmer_date', khmerDate());
        $provinceName = $row->company->province->pro_khname;
        if($row->company->business_province == 12)
            $provinceName = __("general.capital_city");

        $arr_bottom= getDateProvinceAsKhmer($insp_date);
        $phpWord->setValue('pro_name', $provinceName);
        $phpWord->setValue('b_day', $arr_bottom["day"]);
        $phpWord->setValue('b_month', __("general.month_".$arr_bottom["month"]));
        $phpWord->setValue('b_year', $arr_bottom["year"]);

        /** ===================3. Download File ======================== */
        $fileToSave = "របាយការណ៍ស្វ័យប្រកាសអធិការកិច្ចការងារ_other_".date("d_m_Y")."_".time().".docx";//file name to
        try{
//            $phpWord->saveAs(storage_path($fileToSave));
            $phpWord->saveAs(public_path($fileToSave)); //Create new file and save it to public folder
        }catch (Exception $e){
            //handle exception
        }
//        return response()->download(storage_path($fileToSave))->deleteFileAfterSend(true);
        return response()->download(public_path($fileToSave))->deleteFileAfterSend(true);
    }

    /** date: 02-11-2023
     * លិខិតជូនដំណឹងអធិការកិច្ចការងារ
     */
    public function exportLetter1($letter_id = 0)
    {
//        dd("Letter ID: ".$letter_id);

        $row = Letter::where('id', $letter_id)->first();
        $company = $row->company;
        $province = $company->province->pro_khname;
        $district = $company->district->dis_khname;
        $commune = $company->commune->com_khname;
        $village = "";
        if(!empty($company->village)){ $village = $company->village->vil_khname; }
//        dd(khmerDate());
        if(is_null($row)){
            return abort(404);
        }
        $file = storage_path("letter_templete/letter_1.docx");
        $phpWord = new \PhpOffice\PhpWord\TemplateProcessor($file);
        /** ===================1. Header and Body Data ======================== */
        $phpWord->setValue('owner_name', xmlEntities($company->owner_khmer_name));
        $phpWord->setValue('company_name', xmlEntities($company->company_name_khmer));
        $phpWord->setValue('add_village', $village);
        $phpWord->setValue('add_commune', $commune);
        $phpWord->setValue('add_district', $district);

        /** ===================1.1 Inspection Date ======================== */
        $insp_date = $row->will_inspection_date;
        $arr_insp_date = getDateProvinceAsKhmer($insp_date);
//        dd($arr_insp_date);
        $phpWord->setValue('insp_day', $arr_insp_date["day"]);
        $phpWord->setValue('insp_month', __("general.month_".$arr_insp_date["month"]));
        $phpWord->setValue('insp_year', $arr_insp_date["year"]);
        /** =============Date of Latest Self Inspection */
        $company_id = $row->company_id;
        $self_day = ".....";
        $self_month = ".....";
        $self_year = ".....";
        $latest_inspection_id = getLatestSelfInspectionID($company_id, 0);
//        dd("Latest Inspection ID: ".$latest_inspection_id);
        if($latest_inspection_id > 0){
            $self_insp_date = Inspection::select('insp_date')->where('id', $latest_inspection_id)->first();
            $arr_self_insp_date = getDateProvinceAsKhmer($self_insp_date);
            $self_day = $arr_self_insp_date["day"];
            $self_month = __("general.month_".$arr_self_insp_date["month"]) ;
            $self_year = $arr_self_insp_date["year"];

        }
        $phpWord->setValue('self_insp_day', $self_day);
        $phpWord->setValue('self_insp_month', $self_month);
        $phpWord->setValue('self_insp_year', $self_year);

        /** ===================1.2 Bottom Date ======================== */
        $phpWord->setValue('khmer_date', khmerDate());
        if($company->business_province == 12)
            $province = __("general.capital_city");
        $arr_bottom = getDateProvinceAsKhmer($insp_date);
        $phpWord->setValue('pro_name', $province);
        $phpWord->setValue('b_day', $arr_bottom["day"]);
        $phpWord->setValue('b_month', __("general.month_".$arr_bottom["month"]));
        $phpWord->setValue('b_year', $arr_bottom["year"]);

        /** ===================3. Download File ======================== */
        $fileToSave = "លិខិតជូនដំណឹង1_".date("m_d_Y").".docx";//file name to
        try{
//            $phpWord->saveAs(storage_path($fileToSave));
            $phpWord->saveAs(public_path($fileToSave)); //Create new file and save it to public folder
        }catch (Exception $e){
            //handle exception
        }
//        return response()->download(storage_path($fileToSave))->deleteFileAfterSend(true);
        return response()->download(public_path($fileToSave))->deleteFileAfterSend(true);
    }
    /** date: 28-09-2023
     * លិខិតដាក់កំហិត សម្រាប់វិស័យកាត់ដេរ និង វិស័យក្រៅកាត់ដេរ
     */
    public function exportLetter2($inspection_id)
    {
        $row = Inspection::where("id", $inspection_id)->first();
        $group_id = $row->inspectionBusinessActivity->group_id;

        if(is_null($row)){
            return abort(404);
        }
        //$file=rurl('letter_templete/letter_2.docx');
        $file=storage_path('letter_templete/letter_2.docx');
        //$phpWord = new \PhpOffice\PhpWord\TemplateProcessor($file);//file template to open
        $phpWord = new \PhpOffice\PhpWord\TemplateProcessor($file);
        /** ===================1. Header and Body Data ======================== */

        $insp_date= $row->insp_date;
        $officer=getOfficer($inspection_id, 1);
        $phpWord->setValue('insp_group', Num2Unicode($row->inspectionGroup->group_name));
        $phpWord->setValue('officer_name', $officer->officer_name);
        $phpWord->setValue('officer_name2', $officer->officer_name);
        $phpWord->setValue('officer_id', Num2Unicode($officer->officer_id));
        $phpWord->setValue('company_name', xmlEntities($row->company->company_name_khmer));

        $insp3="";
        if($row->insp_type == 3)
            $insp3="ពិសេស";
        $phpWord->setValue('insp3', $insp3);
        /** ===================1.1 Inspection Date ======================== */
        $arr_insp_date= getDateProvinceAsKhmer($insp_date);
        $phpWord->setValue('insp_day', $arr_insp_date["day"]);
        $phpWord->setValue('insp_month', __("general.month_".$arr_insp_date["month"]));
        $phpWord->setValue('insp_year', $arr_insp_date["year"]);

        /** ===================1.2 Expire Date ======================== */
        $exp_date=date("Y-m-d", strtotime('+45 day', strtotime($insp_date)));
        $arr_exp_date= getDateProvinceAsKhmer($exp_date);
        $phpWord->setValue('exp_day', $arr_exp_date["day"]);
        $phpWord->setValue('exp_month', __("general.month_".$arr_exp_date["month"]));
        $phpWord->setValue('exp_year', $arr_exp_date["year"]);
        /** ===================1.3 Bottom Date ======================== */


            $phpWord->setValue('khmer_date', khmerDate());
        $provinceName= $row->company->province->pro_khname;
        if($row->company->business_province == 12)
            $provinceName = __("general.k_capital");
        $arr_bottom= getDateProvinceAsKhmer($insp_date);
        $phpWord->setValue('pro_name', $provinceName);
        $phpWord->setValue('b_day', $arr_bottom["day"]);
        $phpWord->setValue('b_month', __("general.month_".$arr_bottom["month"]));
        $phpWord->setValue('b_year', $arr_bottom["year"]);
        /** ===================2. Start A B C D E F======================== */

        $arr_a=getData14_A($row);
        $arr_b=getData14_B($row);
        $arr_c=getData14_C($row);
        $arr_d=getData14_D($row);
        $arr_e=getData14_E($row);
        $arr_f=getData14_F($row);//dd($row);
        $text_a="     ";
        $text_b="     ";
        $text_c="     ";
        $text_d="     ";
        $text_e="     ";
        $text_f="     ";

        if(count($arr_a) > 0){
            $i=1;
            foreach($arr_a as $item){
                if($item != ""){
                    $text_a.=Num2Unicode($i).". ".$item."<w:br/>";
                    $i++;
                }
            }
        }
        if(count($arr_b) > 0){
            $i=1;
            foreach($arr_b as $item){
                if($item != ""){
                    $text_b.=Num2Unicode($i).". ".$item."<w:br/>";
                    $i++;
                }
            }
        }
        if(count($arr_c) > 0){
            $i=1;
            foreach($arr_c as $item){
                if($item != ""){
                    $text_c.=Num2Unicode($i).". ".$item."<w:br/>";
                    $i++;
                }
            }
        }
        if(count($arr_d) > 0){
            $i=1;
            foreach($arr_d as $item){
                if($item != ""){
                    $text_d.=Num2Unicode($i).". ".$item."<w:br/>";
                    $i++;
                }
            }
        }
        if(count($arr_e) > 0){
            $i=1;
            foreach($arr_e as $item){
                if($item != ""){
                    $text_e.=Num2Unicode($i).". ".$item."<w:br/>";
                    $i++;
                }
            }
        }
        if(count($arr_f) > 0){
            $i=1;
            foreach($arr_f as $item){
                if($item != ""){
                    $text_f.=Num2Unicode($i).". ".$item."<w:br/>";
                    $i++;
                }
            }
        }
        $phpWord->setValue('cond_a', $text_a);
        $phpWord->setValue('cond_b', $text_b);
        $phpWord->setValue('cond_c', $text_c);
        $phpWord->setValue('cond_d', $text_d);
        $phpWord->setValue('cond_e', $text_e);
        $phpWord->setValue('cond_f', $text_f);
        /** ===================3. Download File ======================== */
        $fileToSave = "លិខិតដាក់កំហិត2_".date("m_d_Y").".docx";//file name to
        try{
//            $phpWord->saveAs(storage_path($fileToSave));
            $phpWord->saveAs(public_path($fileToSave)); //Create new file and save it to public folder
        }catch (Exception $e){
            //handle exception
        }
//        return response()->download(storage_path($fileToSave))->deleteFileAfterSend(true);
        return response()->download(public_path($fileToSave))->deleteFileAfterSend(true);
    }

    /** date: 29-09-2023
     * លិខិតផាកពិន័យ សម្រាប់វិស័យកាត់ដេរ និង វិស័យក្រៅកាត់ដេរ
     */
    public function exportLetter3($inspection_id)
    {

        //require_once asset("vendor/autoload.php");
        //require_once 'vendor/autoload.php';
//        $dateTime = KhmerDateTime::parse('2022-05-07');
//        $tmp= $dateTime->format("LLLL");
//        dd($tmp);
        $row = Inspection::where("id", $inspection_id)->first();
        $group_id = $row->inspectionBusinessActivity->group_id;
        if(is_null($row)){
            return abort(404);
        }
        $file=rurl('letter_templete/letter_3.docx');
        $file= storage_path("letter_templete/letter_3.docx");

        //$phpWord = new \PhpOffice\PhpWord\TemplateProcessor($file);//file template to open
        $phpWord = new \PhpOffice\PhpWord\TemplateProcessor($file);
        /** ===================1. Header and Body Data ======================== */

        $total_price="............................";
        $insp_date= $row->insp_date;
        $officer=getOfficer($inspection_id, 1);
        $phpWord->setValue('insp_group', Num2Unicode($row->inspectionGroup->group_name));
        $phpWord->setValue('officer_name', $officer->officer_name);
        $phpWord->setValue('officer_name2', $officer->officer_name);
        $phpWord->setValue('officer_id', Num2Unicode($officer->officer_id));
        $phpWord->setValue('company_name', xmlEntities($row->company->company_name_khmer));
        $phpWord->setValue('total_price', Num2Unicode($total_price));

        $insp3="";
        if($row->insp_type == 3)
            $insp3="ពិសេស";
        $phpWord->setValue('insp3', $insp3);
        /** ===================1.1 Inspection Date ======================== */
        $arr_insp_date= getDateProvinceAsKhmer($insp_date);
        $phpWord->setValue('insp_day', $arr_insp_date["day"]);
        $phpWord->setValue('insp_month', __("general.month_".$arr_insp_date["month"]));
        $phpWord->setValue('insp_year', $arr_insp_date["year"]);
        /** ===================1.2 Bottom Date ======================== */
        $provinceName=$row->company->province->pro_khname;
        if($row->company->business_province == 12)
            $provinceName = __("general.capital_city");
        $arr_bottom= getDateProvinceAsKhmer($insp_date);
        $phpWord->setValue('pro_name', $provinceName);
        $phpWord->setValue('b_day', $arr_bottom["day"]);
        $phpWord->setValue('b_month', __("general.month_".$arr_bottom["month"]));
        $phpWord->setValue('b_year', $arr_bottom["year"]);
        /** ===================2. Display All Fine ======================== */
//        $text_a="     ";
//        $arr_a = $inspection->insp_type == 2? getData14($inspection): getData15($inspection);
//        if(count($arr_a) > 0){
//            $i=1;
//            foreach($arr_a as $item){
//                if($item != ""){
//                    $text_a.=Num2Unicode($i).". ".$item."<w:br/>";
//                    $i++;
//                }
//            }
//        }
//        $phpWord->setValue('cond_a', $text_a);//set data to word


        /** ===================2. Start A B C D E F======================== */
        $arr_a = $row->insp_type == 2? getData14_A($row): getData15A($row);
        $arr_b= $row->insp_type == 2? getData14_B($row): getData15B($row);
        $arr_c= $row->insp_type == 2? getData14_C($row): getData15C($row);
        $arr_d=$row->insp_type == 2? getData14_D($row): getData15D($row);
        $arr_e= $row->insp_type == 2? getData14_E($row): getData15E($row);
        $arr_f= $row->insp_type == 2? getData14_F($row): getData15F($row);
        $text_a="     ";
        $text_b="     ";
        $text_c="     ";
        $text_d="     ";
        $text_e="     ";
        $text_f="     ";
        if(count($arr_a) > 0){
            $i=1;
            foreach($arr_a as $item){
                if($item != ""){
                    $text_a.=Num2Unicode($i).". ".$item."<w:br/>";
                    $i++;
                }
            }
        }
        if(count($arr_b) > 0){
            $i=1;
            foreach($arr_b as $item){
                if($item != ""){
                    $text_b.=Num2Unicode($i).". ".$item."<w:br/>";
                    $i++;
                }
            }
        }
        if(count($arr_c) > 0){
            $i=1;
            foreach($arr_c as $item){
                if($item != ""){
                    $text_c.=Num2Unicode($i).". ".$item."<w:br/>";
                    $i++;
                }
            }
        }
        if(count($arr_d) > 0){
            $i=1;
            foreach($arr_d as $item){
                if($item != ""){
                    $text_d.=Num2Unicode($i).". ".$item."<w:br/>";
                    $i++;
                }
            }
        }
        if(count($arr_e) > 0){
            $i=1;
            foreach($arr_e as $item){
                if($item != ""){
                    $text_e.=Num2Unicode($i).". ".$item."<w:br/>";
                    $i++;
                }
            }
        }
        if(count($arr_f) > 0){
            $i=1;
            foreach($arr_f as $item){
                if($item != ""){
                    $text_f.=Num2Unicode($i).". ".$item."<w:br/>";
                    $i++;
                }
            }
        }
        $phpWord->setValue('cond_a', $text_a);
        $phpWord->setValue('cond_b', $text_b);
        $phpWord->setValue('cond_c', $text_c);
        $phpWord->setValue('cond_d', $text_d);
        $phpWord->setValue('cond_e', $text_e);
        $phpWord->setValue('cond_f', $text_f);

        /** ===================3. Download File ======================== */
        $fileToSave = "លិខិតផាកពិន័យ3_".date("m_d_Y").".docx";//file name to
        try{
//            $phpWord->saveAs(storage_path($fileToSave));
            $phpWord->saveAs(public_path($fileToSave)); //Create new file and save it to public folder
        }catch (Exception $e){
            //handle exception
        }
//        return response()->download(storage_path($fileToSave))->deleteFileAfterSend(true);
        return response()->download(public_path($fileToSave))->deleteFileAfterSend(true);
    }
    public function exportLetter3x2($inspection_id)
    {


        $file=asset('public/template.docx');
        $file= storage_path("template.docx");
        //dd($file);
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($file);
        //$templateProcessor = new TemplateProcessor("template.docx");

        $templateProcessor->setValue('firstname', 'Sohail');
        $templateProcessor->setValue('lastname', 'Saleem');
        $templateProcessor->saveAs(storage_path('Result.docx'));
    }
    public function exportLetter3xx(Request $request)
    {
        $file=rurl('letter_templete/letter_3.docx');
//        $file= public_path("storage/letter_template/letter_3.docx");
//        $file= asset("storage/app/public/letter_template/letter_3.doc");
        //dd($file);

        $phpWord =   new \PhpOffice\PhpWord\PhpWord();
        $phpWord2 = new TemplateProcessor("template.docx");
//        dd("Hello");
        $section = $phpWord->addSection();
        $text = $section->addText("Totti");
        $text = $section->addText("totti@gmail.com");
        $text = $section->addText("testing",array('name'=>'Arial','size' => 20,'bold' => true));
        //$section->addImage("./images/prashant.jpg");
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save(storage_path('CodeSolutionStuff.docx'));
        $objWriter->save(public_path('CodeSolutionStuff.docx'));
        return response()->download(public_path('CodeSolutionStuff.docx'))->deleteFileAfterSend(true);
        //return response()->download(public_path('CodeSolutionStuff.docx'));
    }

    function get_datetime_word($date="", $time=""){
        $txt_date="";
        $txt_time="";
        if($date!=""){
            $sdate = explode('-', $date);
            $txt_date = __('g1.1_year');
            $txt_time = __('g1.1_start_time');

            //year 2020
            for($i=2020; $i<=2040; $i++){
                if($sdate[0] == $i){
                    $txt_date=__('general.word_'.$i);
                    break;
                }
            }

            //month
            $txt_date.=" ".__("g1.1_month").__("general.month_".$sdate[1]);
            $txt_date.=__("g1.1_day");
            //day
            $split_day= str_split($sdate[2]);
            for($i=0; $i<sizeof($split_day); $i++){
                if($i==0){
                    if($split_day[$i]>0)
                        $txt_date.=__('g1.txt_len_2_'.$split_day[$i]);
                }
                else{
                    $txt_date.=__('g1.txt_'.$split_day[$i]);
                }
            }
        }//end if date

        if($time!= ""){
            //time
            $txt_time="";//lang("1_start_time");
            $stime = explode(':', $time);
            //$txt_date.=$stime[0];

            //hour
            if($stime[0] == 10 ){
                $txt_time.=__('g1.txt_10');
            }
            else{
                $split_time= str_split($stime[0]);
                for($i=0; $i<sizeof($split_time); $i++){
                    if($i==0){
                        if($split_time[$i]>0)
                            $txt_time.=__('g1.txt_len_2_'.$split_time[$i]);
                    }
                    else{
                        $txt_time.=__('g1.txt_'.$split_time[$i]);
                    }

                }
            }

            //minute
            $txt_time.= __("g1.1_and");
            if($stime[1] == 10){
                $txt_time.=__('g1.txt_len_2_1');
            }
            elseif($stime[1] == 20){
                $txt_time.= __('g1.txt_len_2_2');
            }
            elseif($stime[1] == 30){
                $txt_time.=__('g1.txt_len_2_3');
            }
            elseif($stime[1] == 40){
                $txt_time.=__('g1.txt_len_2_4');
            }
            elseif($stime[1] == 50){
                $txt_time.=__('g1.txt_len_2_5');
            }
            else{
                $split_time= str_split($stime[1]);
                for($i=0; $i<sizeof($split_time); $i++){
                    if($i==0){
                        if($split_time[$i]>0)
                            $txt_time.=__('g1.txt_len_2_'.$split_time[$i]);
                    }
                    else{
                        $txt_time.=__('g1.txt_'.$split_time[$i]);
                        //if($split_time[$i] == 0)
                        //   $txt_time.=lang('txt_0');
                    }

                }
            }

            $txt_time.=__("g1.1_minute");
        }// end if time
        $arr_result= array();
        $arr_result["txt_date"]=$txt_date;
        $arr_result["txt_time"]=$txt_time;
        return $arr_result;
    }






    public function test1(Request $request, $inspection_id){
        $row = Inspection::where("id", $inspection_id);
        if(is_null($row)){
            return abort(404);
        }
        //$file = public_path('storage/letter_template/test.docx');
//        $file=storage_path("");
//        dd($file);

        $file=rurl('letter_template/letter_2.docx');
        //$file=rurl('letter_templete/checklist_result.docx');
       //$file=rurl('letter_template/checklist_result.docx');//file template to open
        $phpWord = new \PhpOffice\PhpWord\TemplateProcessor($file);
//        $phpWord->setValue('name', $row->name);
//        $phpWord->setValue('username', $row->username);
//        $phpWord->setValue('email', $row->email);
        /** ===================3. Download File ======================== */
        $fileToSave = "checklist_result".date("m_d_Y").".docx";//file name to
        try{
            $phpWord->saveAs(storage_path($fileToSave));
        }catch (Exception $e){
            //handle exception
        }
        return response()->download(storage_path($fileToSave))->deleteFileAfterSend(true);
    }
    public function test2()
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $description = "Lorem ipsum dolor sit amet";
        //$section->addImage("https://www.itsolutionstuff.com/frontTheme/images/logo.png");
        $section->addText($description);
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        try {
            $objWriter->save(storage_path('helloWorld.docx'));
        } catch (Exception $e) {
        }
        return response()->download(storage_path('helloWorld.docx'))->deleteFileAfterSend(true);
    }
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
