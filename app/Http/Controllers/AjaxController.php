<?php

namespace App\Http\Controllers;

use App\Models\Commune;
use App\Models\Company;
use App\Models\CompanyApi;
use App\Models\Disputant;
use App\Models\AllowCompanySelfInsp;
use App\Models\District;
use App\Models\NeaIsic;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AjaxController extends Controller
{
    protected $main;
    public function findCompanyAutocomplete(Request $request)
    {
        $query = $request->input('query');
        $data = CompanyApi::selectRaw("CONCAT(company_name_khmer, '__', company_name_latin, '__', '1', '__', company_id) AS name ")
            //where('company_name_latin', 'like', "%$query%")
                //->where("ready_add_2case", 0)
            ->where(DB::raw("CONCAT('x',company_id,'x', company_name_khmer,'', COALESCE(company_name_latin, 'NULL'),  COALESCE(company_tin, 'NULL') )"), "like", "%".$query."%")
            ->limit(10)
//            ->orderBy('id', 'DESC')
//            ->where('operation_status', 1)
            ->pluck('name');

        if($data->count() > 0){
            return response()->json($data);
        }
        else{
            $data2 = Company::selectRaw("CONCAT(company_name_khmer, '__', company_name_latin, '__', '2', '__', company_id) AS name ")
                //where('company_name_latin', 'like', "%$query%")
                ->where(DB::raw("CONCAT('x',company_id,'x', company_name_khmer,'', COALESCE(company_name_latin, 'NULL'),  COALESCE(company_tin, 'NULL') )"), "like", "%".$query."%")
                ->limit(10)
                ->pluck('name');

            if($data2->count() > 0){
                return response()->json($data2);
            }
            return response()->json([
                0 => "Result Not Found."
            ]);
        }
        //return response()->json($data);
    }
    public function getDetails(Request $request)
    {
        $tmp = explode("__", $request->input('name'));
        $name = $tmp[0];
        //dd($tmp);
//        $company_option = $tmp[2];

        // Attempting to find the company in CompanyApi
        $details = CompanyApi::selectRaw("
                id, company_id, company_name_khmer, company_name_latin, nssf_number, company_tin,
                company_register_number, registration_date, type_of_company as company_type_id,
                first_business_act, article_of_company, sector_id, csic_1, csic_2, csic_3, csic_4, csic_5,
                business_activity, business_activity1, business_activity2, business_activity3, business_activity4,
                business_province AS province_id, business_district as district_id,
                business_commune as commune_id, business_village as village_id,
                business_group, business_street as street_no, business_house_no as building_no, company_phone_number,
                single_id, operation_status
            ")
            ->where('company_name_khmer', $name)
//            ->where('company_name_khmer', "LIKE", "%".$name."%")
            ->first();

            // Set company_option based on which source the data is found
            $company_option = 1;

        // Fallback to search in Company if not found in CompanyApi
        if(empty($details)) {
            $details = Company::selectRaw("
                id as company_id, company_option, company_name_khmer, company_name_latin, nssf_number, company_tin,
                company_register_number, registration_date, company_type_id, first_business_act,
                article_of_company, sector_id, csic_1, csic_2, csic_3, csic_4, csic_5,
                business_activity, business_activity1, business_activity2, business_activity3, business_activity4,
                province_id, district_id, commune_id, village_id, street_no, building_no,
                company_phone_number, company_phone_number2
            ")
            ->where('company_name_khmer', "LIKE", "%".$name."%")
            ->first();

            // Set company_option to 2 since details were found in the fallback
            $company_option = 2;
        }

        // Add company_option as an attribute to $details
        if ($details) {
            $details->company_option = $company_option;
        } else {
            $details = (object) ['company_option' => $company_option]; // Ensure $details is an object
        }

        return response()->json($details);
//         Adding company_option to the response
//        return response()->json([
//            'details' => $details,
//            'company_option' => $company_option
//        ]);
    }

    /** Improved Searching Quality: Search Emp in LocalDB, If NOT FOUND, let's check in API  */
    public function findEmployeeAutocomplete(Request $request, int $companyID = 0)
    {
        $search = $request->input('query', '');
        if ($companyID > 0 && $companyID < 500000) {
            // Fetch local disputants
            $data = $this->fetchDisputants($search);

            if ($data->isNotEmpty()) {
                return response()->json($data);
            }

            // Fetch employees from the external API
            $employees = ApiAdmin($companyID, "30");

            if (!empty($employees)) {
                // Filter employees based on the search query
                $filteredData = collect($employees)
                    ->filter(fn($employee) => Str::contains($employee->name, $search))
                    ->take(5)
                    ->map(function ($employee) {
                        return sprintf(
                            "1::%s ភេទ %s ថ្ងៃខែឆ្នាំកំណើត %s លេខអត្តសញ្ញាណប័ណ្ណ::%s",
                            $employee->name,
                            $employee->gender,
                            $employee->dob,
                            $employee->id_number
                        );
                    });

                if ($filteredData->isNotEmpty()) {
                    return response()->json($filteredData->values());
                }
            }

            return response()->json(["Result Not Found."]);
        }

        // Fallback for other company IDs
        return $this->findLocalDisputant($search);
    }

    private function fetchDisputants(string $search)
    {
        return Disputant::selectRaw("
                CONCAT(
                    '2::',
                    name,
                    ' ភេទ ',
                    IF(gender = 1, 'ប្រុស', 'ស្រី'),
                    ' ថ្ងៃខែឆ្នាំកំណើត ',
                    dob,
                    ' លេខអត្តសញ្ញាណប័ណ្ណ::',
                    IFNULL(id_number, name)
                ) AS name
            ")
            ->where("name", "LIKE", "%{$search}%")
            ->limit(10)
            ->pluck('name');
    }

    public function findLocalDisputant(string $search = "")
    {
        $data = $this->fetchDisputants($search);

        if ($data->isNotEmpty()) {
            return response()->json($data);
        }

        return response()->json(["Result Not Found."]);
    }


    public function findEmployeeAutocompleteX(Request $request, $companyID = 0)
    {
        $search = $request->input('query');
        if($companyID > 0 && $companyID < 500000) {
            // Get employees from external API
            $employees = ApiAdmin($companyID, "30");

            if(count($employees) > 0) {
                // Filter employees based on the search query
                $filteredData = array_filter($employees, function ($data) use ($search) {
                    return Str::contains($data->name, $search);
                });

                // Limit results to 5
                if(count($filteredData) > 5) {
                    $filteredData = array_slice($filteredData, 0, 5);
                }

                // Prepare the response data
                $data = [];
                foreach($filteredData as $row) {
                    $data[] = "1::". $row->name
                        ." ភេទ ".$row->gender." ថ្ងៃខែឆ្នាំកំណើត ".$row->dob
                        ." លេខអត្តសញ្ញាណប័ណ្ណ::".$row->id_number;
                }

                if(!empty($data)) {
                    return response()->json($data);
                }
            }

            // Fallback to findDisputant if no employees found in ApiAdmin
            return $this->findDisputant($search);
        }
        else {
            // Fallback to findDisputant for company IDs
            return $this->findDisputant($search);
        }
    }
    public function getEmployeeDetail(Request $request, $company_id = 0)
    {
        $input = $request->input('name');
        $tmp = explode("::", $input);

        // Safely retrieve search type and search term
        $searchType = $tmp[0] ?? 2;
        $search = $tmp[2] ?? '';

        if ($searchType == 1) {
            // Search from lacms
            $employees = ApiAdmin($company_id, "30");

            if (!empty($employees)) {
                $filteredData = array_filter($employees, function ($data) use ($search) {
                    return $data->id_number === $search;
                });

                if (!empty($filteredData)) {
                    $employeeDetails = reset($filteredData); // Get the first match
                    $employeeDetails->nationality = getNationalityID($employeeDetails->nationality);
                    return response()->json([$employeeDetails]);
                }
            }
            // Fall back to searching in tbl_disputant if no match found
            return $this->getDisputantDetail($search);
        } else {
            // Directly search in tbl_disputant
            return $this->getDisputantDetail($search);
        }
    }


    public function getEmployeeDetailX(Request $request, $company_id = 0)
    {
        $tmp = explode("::", $request->input('name'));

        // Safely retrieve search type and search term
        $searchType = $tmp[0] ?? 2;
        $search = $tmp[2] ?? '';

        //search from lacms
        if($searchType == 1){
            // Search from lacms
            $employees = ApiAdmin($company_id, "30");
            if(count($employees) > 0){
                // Define your filtering criteria
                $filteredData = array_filter($employees, function ($data) use ($search) {
//                    return Str::contains($data->name, $search);
                    return $data->id_number == $search;
                });
                $data = array();
                //dd(count($filteredData));
                if(count($filteredData) > 0){
                    foreach($filteredData as $row){
                        $tmp = $row->nationality;
                        $tmp = getNationalityID($tmp);
                        $row->nationality = $tmp;
                        $data[0] = $row;
                    }
                    return response()->json($data);
                }
                else{
                    return $this->getDisputantDetail($search);
//                    $data = Disputant::where("id_number", "LIKE", "%".$search."%")
//                        ->limit(10)->get();
//                    if($data->count() > 0){
//                        return response()->json($data);
//                    }
//                    else{
//                        return response()->json([
//                            0 => "Result Not Found."
//                        ]);
//                    }
                }
            }
            else{
                return $this->getDisputantDetail($search);
//                $data = Disputant::where("id_number", "LIKE", "%".$search."%")
//                    ->limit(10)->pluck('name');
//                if($data->count() > 0){
//                    return response()->json($data);
//                }
//                else{
//                    return response()->json([
//                        0 => "Result Not Found."
//                    ]);
//                }
            }
        }
        else{ // search in tbl_disputant
            return $this->getDisputantDetail($search);
        }
    }

    function findDisputant($search = "")
    {
        $data = Disputant::selectRaw("
                CONCAT(
                    '2::',
                    name,
                    ' ភេទ ',
                    IF(gender = 1, 'ប្រុស', 'ស្រី'),
                    ' ថ្ងៃខែឆ្នាំកំណើត ',
                    dob,
                    ' លេខអត្តសញ្ញាណប័ណ្ណ::',
                    IFNULL(id_number, name)
                ) AS name
            ")
            ->where("name", "LIKE", "%".$search."%")
            ->limit(10)
            ->pluck('name');

        if($data->count() > 0) {
            return response()->json($data);
        } else {
            return response()->json([
                0 => "Result Not Found."
            ]);
        }
    }

    function getDisputantDetail($search=""){
        $data = Disputant::where("id_number", $search)
            ->orWhere("name", $search)
//            ->orWhere("name", "LIKE", "%".$search."%")
            ->limit(10)->get();
        if($data->count() > 0){
            return response()->json($data);
        }
        else{
            return response()->json([
                0 => "Result Not Found."
            ]);
        }
    }
    public function getRole($officerID){
        return response()->json(getOfficerRoleName($officerID));
    }
    public function getRoleX($roleID){
        return response()->json(myArrOfficerRole($roleID));
    }

    public function getRolexX(){
        return response()->json(arrayUserLevel());
    }

    public function getProvince(){
//        return response()->json(myArrProvince());
        return response()->json(arrayProvince());
    }
    public function getDistrict($province_id = 0){
        $result['data'] = District::orderby("name","asc")
            ->select('dis_id AS id','dis_khname AS name')
            ->where('province_id', $province_id)
            ->get();
        return response()->json($result);
    }
    public function getCommune($district_id = 0){
        //ddd($district_id);
        $result['data'] = Commune::orderby("name","asc")
            ->select('com_id AS id','com_khname AS name')
            ->where('district_id', $district_id)
            ->get();
        return response()->json($result);
    }
    public function getVillage($commune_id = 0){
        $result['data'] = Village::orderby("name","asc")
            ->select('vil_id AS id','vil_khname AS name')
            ->where('commune_id', $commune_id)
            ->get();
        return response()->json($result);
    }

    function getCSIC2($csic1){
        $data = arrCSIC2($csic1);
        return response()->json(['data' => $data]);
    }

    function getCSIC3($csic1, $csic2){

        $data = arrCSIC3($csic1, $csic2);
        return response()->json(['data' => $data]);
    }

    function getCSIC4($csic1, $csic2, $csic3){
        $data = arrCSIC4($csic1, $csic2, $csic3);
        return response()->json(['data' => $data]);
    }

    function getCSIC5 ($csic1, $csic2, $csic3, $csic4){
        $data = arrCSIC5($csic1, $csic2, $csic3, $csic4);
        return response()->json(['data' => $data]);
    }

    public function getBusinessActivity2($business_activity_id1 = 0){
        $result['data'] = NeaIsic::orderby("isic_code","asc")
            ->select('isic_code AS id','name_khmer AS name')
            ->where("level", 2)
            ->where('section', $business_activity_id1)
            ->get();
        return response()->json($result);
    }
    public function getBusinessActivity3($business_activity_id1 = 0, $business_activity_id2 = 0){
        //dd($business_activity_id2);
        $result['data'] = NeaIsic::orderby("isic_code","asc")
            ->select('isic_code AS id','name_khmer AS name')
            ->where("level", 3)
            ->where('section', $business_activity_id1)
            ->where("sec_class", "LIKE", $business_activity_id1.$business_activity_id2."%")
            ->get();
        return response()->json($result);
    }
    public function getBusinessActivity4($business_activity_id1 = 0, $business_activity_id2 = 0, $business_activity_id3 = 0){
        $result['data'] = NeaIsic::orderby("isic_code","asc")
            ->select('isic_code AS id','name_khmer AS name')
            ->where("level", 4)
            ->where('section', $business_activity_id1)
            ->where("sec_class", "LIKE", $business_activity_id1.$business_activity_id3."%")
            ->get();
        return response()->json($result);
    }

    /** Soklay function 21-09-2023 */

    function delete_file($file_name="", $path="", $table="", $key_find="", $key_value="", $field=""){
        header('Content-Type: application/x-json; charset=utf-8');
        $path= str_replace("__", "/", $path);
        /** ======= Delete File Blog =============*/
        $file_delete="./".$path.$file_name;
        if(file_exists($file_delete)){
            $this->main->update($table, array($field => ""), array($key_find => $key_value) );
            unlink($file_delete);// delete old file
            deleteFile($file_name, $path);
            echo  "1";
        }
        else{
            $this->main->update($table, array($field => ""), array($key_find => $key_value) );
        }
    }
    function deleteFile($file_name="", $path="", $table="", $key_find="", $key_value="", $field=""){ //
//        dd($path);
        $path = str_replace("__", "/", $path);

//        dd($file_name.", Path: ". $path);
        /** Testing File Before Deletion */
        $fileToDelete = $path.$file_name;
//        dd(public_path($fileToDelete));

        /** ======= Delete File Blog =============*/
        $result = deleteFile($file_name, $path);
        if($result){
           //dd($result);
            DB::table($table)->where($key_find, $key_value)->update([$field => ""]);
            return 1;
            //$this->main->update($table, array($field => ""), array($key_find => $key_value) );
        }
    }

    function deleteFileOnly($fileName = "", $pathFile = ""){ //
        $path = str_replace("__", "/", $pathFile);

        /** ======= Delete File Blog =============*/
        $result = deleteFile($fileName, $path);
        if($result){
            return 1;
        }
    }

    function addRowDate($count = 0 ){
//        header('Content-Type: application/x-json; charset=utf-8');
        $cols="<tr>";
        $cols.="<script>$('#festival_date_".$count."').datepicker({format: 'dd-mm-yyyy'});";
        $cols.="</script>";

        $cols .= '<td><input type="text" id="festival_date_'.$count.'" name="festival_date[]" class="datepicker-here form-control digits" data-provide="datepicker" data-masked-input="99-99-9999" data-language="en" placeholder="DD-MM-YYYY" maxlength="10" data-date-viewmode="years" /></td>';
        $cols .= '<td><input type="text" name="festival_name[]" class="form-control" /></td>';
        $cols .= '<td><input type="button" class="ibtnDel_date btn btn-danger"  value="Delete"></td>';
        $cols .="</tr>";
        return $cols;
        //return response()->json($cols);
    }

    /** Soklay function */
//    function delete_931($id){
//        header('Content-Type: application/x-json; charset=utf-8');
//        delete('tbl_931', array('id'=>$id));
//        return response()->json(['success'=>'Driver deleted successfully.']);
//    }



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
