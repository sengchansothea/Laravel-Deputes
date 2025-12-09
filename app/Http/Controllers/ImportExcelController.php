<?php

namespace App\Http\Controllers;

use App\Models\AllowCompanySelfInsp;
//use App\Models\AllowCompanySelfInspection;
//use App\Models\Inspection;
//use App\Models\InspectionGroup;
//
//use App\Models\PeacefulCompany;
//use App\Models\WillInspection3;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;


class ImportExcelController extends Controller
{
    private $arrPoint = [];
    private $arrRedPoint = [];

    public function import_company_listXX() {
        $remoteUrl = "http://sicms.mlvt.gov.kh/public/storage/assets/images/sample/allow_company_list.xlsx";
        $tempPath = public_path('storage/assets/images/import/tmp_allow_company_list.xlsx');

        // Step 1: Download the remote file
        $fileContents = file_get_contents($remoteUrl);

        if (!$fileContents) {
            return response()->json(['status' => 500, 'message' => 'Failed to download Excel file.']);
        }



        // Step 2: Save it to local temporary path
        file_put_contents($tempPath, $fileContents);

//        dd($tempPath);

        // Step 3: Load and process the file using PhpSpreadsheet
        $spreadsheet = IOFactory::load($tempPath);
        $sheetData = $spreadsheet->getActiveSheet()->toArray();

//        dd($sheetData);

        // Step 4: Process rows
        $insert = 0;
        $i = 1;
        $start_row = 3;
        $date_created = now();
        $user_id = auth()->id();

        foreach ($sheetData as $value) {
            if ($i >= $start_row) {
                $company_id = trim($value[1]);

                $adata = [
                    "company_id"       => $company_id,
                    "company_name_kh"  => trim($value[2]),
                    "company_name_eng" => trim($value[3]),
                    "sector_id"        => $value[4],
                    "date_created"     => $date_created,
                    "user_created"     => $user_id,
                ];
//                dd($adata);
                if ($company_id > 0) {
                    $search = ["company_id" => $company_id];
                    $result = AllowCompanySelfInsp::updateOrCreate($search, $adata);
                    if ($result) {
                        $insert++;
                    }
                }
            }
            $i++;
        }

//        dd($adata);

        // Optional: delete the temp file after processing
        //@ Operator: ignore error message if file not found
        @unlink($tempPath);

        return response()->json(['status' => 200, 'inserted' => $insert, 'result' => $adata],200);
    }

    public function import_company_list(){
        //http://localhost:8888/laravel10/sicms_v5/import/peaceful_company
        //$inputFileName = './import/2025_peacefule_company.xlsx';//
        //$file_name = "allow_company_list3.xlsx";
        //$inputFileName = storage_path('import/'.$file_name); //allow_company_list3.xlsx
        /** =========== Delete All Record before Import =========== */
//        AllowCompanySelfInspection::truncate();//delete all and Reset auto-increment ID
        //AllowCompanySelfInspection::query()->delete();// delete all and រក្សា auto-increment ID
        /** ====================================================== */

        $file_name = "allow_company_list.xlsx";
//        dd($file_name);
        $inputFileName = public_path('storage/assets/images/import/'.$file_name);

//        $inputFileName = "http://sicms.mlvt.gov.kh/public/storage/assets/images/sample/".$file_name;
//        dd(storage_path());
//        $inputFileName = storage_path('import/'.$file_name); //allow_company_list3.xlsx

//        dd($inputFileName);


//        dd($inputFileName);
        /** Load $inputFileName to a Spreadsheet Object  **/
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        //echo $file_name;
        //dd($sheetData);
        $inserdata = array();
        $insert = 0;
        $i = 1;
        $date_created = MyDateTime();
        $user_id = Auth::user()->id;
        $start_row = 3;
        foreach ($sheetData as $value) {
            if($i >= $start_row){
//            $tmp = explode(lang('khmer_space'), $value[1]);
                $company_id = trim($value[1]);
                $adata = [
                    "company_id"             => $company_id,
                    "company_name_kh"        => trim($value[2]),
                    "company_name_eng"       => trim($value[3]),
                    "sector_id"              => $value[4],

                    "date_created"          => $date_created,
                    "user_created"          => $user_id,
                ];
                dd($adata);
                //echo $j."======>"; print_r($adata); echo "<br><br>";
                if($company_id > 0){
                    $search = array("company_id" => $company_id);
                    $result = AllowCompanySelfInsp::updateOrCreate($search, $adata);
                    if($result){
                        $insert++;
                    }
                }

            }
            $i++;

        }
        return $insert;
        //echo "<br>==========Final Result: Insert Data: ".$insert."=============";
    }//end function
    public function import_peaceful_company(){
        //http://localhost:8888/laravel10/sicms_v5/import/peaceful_company
        //$inputFileName = './import/2025_peacefule_company.xlsx';//
        $inputFileName = storage_path('import/20250403_បញ្ជីសហ_គ_ចូលរួមប្រកួតប្រជែង_V5.xlsx');
        //$inputFileName = public_path('storage/assets/images/self_inspection_allow_list/'.$file_name);
        /** Load $inputFileName to a Spreadsheet Object  **/
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
        //$spreadsheet = $reader->load($_FILES['upload_file']['tmp_name']);
        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        //print_r($sheetData);
        $inserdata= array();
        $insert = 0;
        $i = 1;
        $j = 1;
        $date_created = date("Y-m-d H:i:s");
        $user_id = 1;
        $table = "tbl_peacefule_company";
        $start_row = 6;
        foreach ($sheetData as $value) {
            if($i >= $start_row){
//            $tmp = explode(lang('khmer_space'), $value[1]);
//            $fullname = trim($tmp[0]). lang('khmer_space'). trim($tmp[1]);
                $year = 2025;
                $company_id = $value[2];
                $adata = [
                    "insp_type_id"              => 0,
                    "year"                      => $year,
                    "no1"                       => $value[0],
                    "no2"                       => $value[1],
                    "company_id"                => $company_id,//2

                    "company_name_khmer"        => trim($value[3]),
                    "company_name_latin"        => trim($value[4]),
                    "source"                    => $value[5],
                    "owner_nat"                 => $value[6],
                    "visai"                     => $value[7],
                    "commune"                  => $value[8],
                    "district"                  => $value[9],
                    "province"                  => $value[10],
                    "total_employee"            => $value[11],
                    "total_employee_female"     => $value[12],
                    "phone"                     => $value[13],
                    "self_inspection_status"    => $value[14],

                    "dispute_status"            => $value[24],
                    "nssf_status"               => $value[25],
                    "other"                     => $value[26],
                    "checklist_id"              => $value[27],
                ];
                //dd($adata);
                //echo $j."======>"; print_r($adata); echo "<br><br>";
                $search = array("company_id" => $company_id, "year" => $year);
                $result = PeacefulCompany::updateOrCreate($search, $adata);
                if($result){
                    echo $j."=>".trim($value[2])."<br><br>";
                    $insert++;
                    $j++;

                }

            }
            $i++;

        }
        echo "<br>==========Final Result: Insert Data: ".$insert."=============";
    }//end function
    public function import_sth_file_test(){
        //dd("xx");
        $inputFileName = './import/2025_peacefule_company.xlsx';//
        /** Load $inputFileName to a Spreadsheet Object  **/
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
        //$spreadsheet = $reader->load($_FILES['upload_file']['tmp_name']);
        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        //print_r($sheetData);
        $inserdata= array();
        $insert=0;
        $i=0;
        $date_created = date("Y-m-d H:i:s");
        $user_id = 1;
        $table = "tbl_peacefule_company";
        foreach ($sheetData as $value) {
//            $tmp = explode(lang('khmer_space'), $value[1]);
//            $fullname = trim($tmp[0]). lang('khmer_space'). trim($tmp[1]);
//            $gender = 1;
//            if(trim($value[2]) == lang('female_sort'))
//                $gender=2;
            $year = 2025;
            $company_id = $value[1];
            $adata = [
                "company_id" => $company_id,//1
                "year" => 2025,
                "insp_type_id" => $value[3],
                "company_name_khmer" => trim($value[2]),
                "company_name_latin" => trim($value[4]),
                "house" => $value[5],
                "street" => $value[6],
                "addr_group" => $value[7],
                "village" => $value[8],
                "commune" => $value[9],
                "district" => $value[10],
                "province" => $value[11],
                "total_employee" => $value[12],
                "total_employee_female" => $value[13],
                "for_total" => $value[14],
                "for_female" => $value[15],
                "business_activity" => $value[16],
                "visai" => $value[17],
            ];
            //dd($adata);
            $con = array("company_id" => $company_id, "year" => $year);
            $search = $this->main->count_record("tbl_peaceful_company", $con);
            echo "<br>Search Result: ".$search."<br>";
            print_r($adata);
            if($search == 0 ){
                $this->main->insert("tbl_peaceful_company", $adata);
                $insert++;
            }
            $i++;
        }
        echo "<br>==========Final Result: Insert Data: ".$insert."=============";
    }//end function



    /** date: 20-06-2023: import using upload form for testing */
    public function frm_import_sth(){
        $this->load->view('import/frm_import_sth');
    }
    /** date: 20-06-2023: import using upload form for testing */
    public function import_sth(){
        $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        if(isset($_FILES['upload_file']['name']) && in_array($_FILES['upload_file']['type'], $file_mimes)) {
            $arr_file = explode('.', $_FILES['upload_file']['name']);
            $extension = end($arr_file);
            if('csv' == $extension){
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }
            $spreadsheet = $reader->load($_FILES['upload_file']['tmp_name']);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();
            echo "<pre>";
            //print_r($sheetData);

            $inserdata= array();
            $insert=0;
            $i=0;
            $date_created=date("Y-m-d H:i:s");
            $user_id=1;
            $table="tbl_1_import_sth";
            foreach ($sheetData as $value) {
                $name= trim($value[2]);
                $name= str_replace("​", "", $name);// remove white space
                $name= str_replace("លោកស្រី ", "", $name); //remove
                $name= str_replace("លោកស្រី", "", $name); //remove
                $name= str_replace("លោក ", "", $name);  //remove
                $name= str_replace("លោក", "", $name);  //remove
                $name= str_replace("កញ្ញា ", "", $name);  //remove
                $name= str_replace("កញ្ញា", "", $name);  //remove
                $name= str_replace("    ", " ", $name);// 4 spaces to 1
                $name= str_replace("   ", " ", $name);// 3 spaces to 1
                $name= str_replace("  ", " ", $name); //2 spaces to 1
                echo "<br>".$i.".".$name;
//                $gender=1;
//                if(trim($value[2]) == lang('female_sort'))
//                    $gender=2;
                //$dob=$value[3];
                //$phone=$value[4];
                //echo "<br>".$fullname.",".$gender.",".$value[3].",".$value[4];
                /** for insert batch
                $inserdata[$i]['student_name_kh'] = $fullname;//1
                $inserdata[$i]['gender'] = $gender;//2
                $inserdata[$i]['dob'] = $dob;//3
                $inserdata[$i]['phone'] = $phone;//4
                $inserdata[$i]['date_created'] = $date_created;
                $inserdata[$i]['user_created'] = $user_id;
                 */
                $con=array("c" => $name, "c<>"=> "");
                $search_result=0;
                $is_doublicate=0;
                $search_result= $this->main->select_one_data($table, "id", $con);
                if($search_result > 0){
                    $is_doublicate = 1;
                    $this->main->update($table, array("is_doublicate" => 1), array("id" => $search_result));
                }
                //$search_result=$this->main->count_record($table, $con);
                $adata = array(
                    "a" => $value[0],
                    "b" => $value[1],
                    "c" => $name,
                    "d" => $value[3],
                    "e" => $value[4],
                    "f" => $value[5],
                    "g" => $value[6],
                    "h" => $value[7],
                    "i" => $value[8],
                    "j" => $value[9],
                    "k" => $value[10],
                    "l" => $value[11],
                    "m" => $value[12],
                    "n" => $value[13],
                    "is_doublicate" => $is_doublicate,
                    //"date_created" => $date_created,
                    //"user_created" => $user_id
                );
                $this->main->insert($table, $adata);
                $i++;
            }
            echo "<br>==========Final Result: Insert Data: ".$i."=============";
            //print_r($inserdata);
            //$result = $this->import->insert_batch($inserdata);//change table name in model
            /*if($result){//successfully Insert to DB
                //redirect("import");
                //echo "Imported successfully";
            }else{
                echo "ERROR !";
            } */


        }

    }//end function




}
