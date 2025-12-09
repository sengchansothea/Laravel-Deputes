<?php


use App\Models\ArticleOfCompany;
use App\Models\BusinessActivity;
use App\Models\Commune;
use App\Models\CompanyApi;
use App\Models\CompanyType;
use App\Models\Department;
use App\Models\AllowCompanySelfInsp;

use App\Models\District;
use App\Models\Nationality;
use App\Models\Province;
use App\Models\Role;
use App\Models\RolekParents;
use App\Models\Setting;
use App\Models\Village;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\File\File;

function loadRunUnlimitedTime(){
    ini_set('memory_limit', -1);
    ini_set('MAX_EXECUTION_TIME', -1);
    set_time_limit(0);
}
function nbs($repeat=2){
    return str_repeat("&nbsp;", $repeat);
}

function Num2Unicode($num=2017){
    $num_unicode="";
    $arr_num= array(
        0=>"០",
        1=>"១",
        2=>"២",
        3=>"៣",
        4=>"៤",
        5=>"៥",
        6=>"៦",
        7=>"៧",
        8=>"៨",
        9=>"៩"
    );
    $arr_split= str_split($num);
    for($i=0; $i<sizeof($arr_split); $i++){
        if(is_numeric($arr_split[$i]))
            $num_unicode.=$arr_num[$arr_split[$i]];
        else
            $num_unicode.=$arr_split[$i];
    }
    return $num_unicode;
}
function number2KhmerNumber($string=2017){
    return strtr($string, array('0'=>'០', '1'=>'១', '2'=>'២', '3'=>'៣', '4'=>'៤', '5'=>'៥', '6'=>'៦', '7'=>'៧', '8'=>'៨', '9'=>'៩'
    ));
}
function khmerNumber2English($string=2017){
    return strtr($string, array('០'=>'0', '១'=>'1', '២'=>'2', '៣'=>'3', '៤'=>'4', '៥'=>'5', '៦'=>'6', '៧'=>'7', '៨'=>'8', '៩'=>'9'
    ));
}
function cleanNumber($str){
    $phone= str_replace("​", "", trim($str));// remove zero space
    $phone= str_replace(" ", "", $phone); //remove space
    $phone= khmerNumber2English($phone);
    return $phone;
}
function cleanPhone($phone){
    $phone= cleanNumber($phone);
    $first = substr($phone,0, 1);
    if($first > 0){
        $phone= "0".$phone;
    }
    return $phone;
}

function convertToUserDate($date, $userTimeZone = 'Asia/Phnom_Penh', $format = 'd-m-Y H:i:s', $serverTimeZone = 'UTC'){
    if($userTimeZone == "") $userTimeZone="Asia/Phnom_Penh";
    if($format == '') $date_format= 'd-m-Y H:i:s';
    try {
        $serverTimeZone=date_default_timezone_get();
        $dateTime = new DateTime ($date, new DateTimeZone($serverTimeZone));
        $dateTime->setTimezone(new DateTimeZone($userTimeZone));
        return $dateTime->format($format);
    }
    catch (Exception $e) {
        return '';
    }
}
function myDateTime($format ="Y-m-d H:i:s" ,$userTimeZone="Asia/Phnom_Penh"){
    return convertToUserDate(date("Y-m-d H:i:s"), $userTimeZone, $format);
}
function myDate($format ="Y-m-d", $userTimeZone="Asia/Phnom_Penh"){
    return convertToUserDate(date("Y-m-d H:i:s"), $userTimeZone, $format);
}
function myTime($format ="H:i" ,$userTimeZone="Asia/Phnom_Penh"){
    return convertToUserDate(date("H:i"), $userTimeZone, $format);
}
function date2DB($date="", $formate="Y-m-d"){
//return date_format(date_create($date), "Y-m-d"); // H:i:s
    $result= null;
    if(strtotime($date)){
        $result= date_format(date_create($date), $formate);
    }
//    if( !($date =="" || $date == null || $date == "00-00-0000" || $date == "0000-00-00" || $date=="0000-00-00 00::00:00") ){
//        $result= date_format(date_create($date), $formate);
//    }

    return $result;
}
function date2Display($date='', $formate="d-m-Y"){
    $result="";
    if(strtotime($date)){
        $result = date_format(date_create($date), $formate);
    }
//    if(!($date =='' || $date =="0000-00-00"))
//        $result= date_format(date_create($date), $formate);
    return $result;
}
function date2DisplaySlash($date=''){
    $result="";
    if(!($date =='' || $date == "0000-00-00"))
        $result= date_format(date_create($date), "d/m/Y");
    return $result;
}

function khmerDate($mydate="")
{
    $tz="Asia/Phnom_Penh";
    $d=date("Y-m-d");
//        $validatedData = $request->validate([
//            'mydate' => 'date'
//        ]);
//        if($validatedData->fails()){
//            $tmp=explode("-", date("Y-m-d"));
//            $str= Carbon::createFromDate($tmp[0],$tmp[1],$tmp[2], $tz); //$str=Carbon::now();
//            $khmerDate=toLunarDate($str)->toString();
//            $success['date']=$khmerDate;
//            return $this->sendResponse($success, 'successfully.');
//        }
//        else{
//            $tmp=explode("-", date2DB($validatedData['mydate']));
//            $str= Carbon::createFromDate($tmp[0],$tmp[1],$tmp[2], $tz); //$str=Carbon::now();
//            $khmerDate=toLunarDate($str)->toString();
//            $success['date']=$khmerDate;
//            return $this->sendResponse($success, 'successfully.');
//        }


    if($mydate != ""){
        $d= date2DB($mydate);
    }
    //return $this->sendResponse($mydate, 'successfully.');
    //$d=request("mydate");
    //$d=$mydate;
    //$d=$request->mydate;
    $tmp=explode("-", $d);
    $str= Carbon::createFromDate($tmp[0],$tmp[1],$tmp[2], $tz); //$str=Carbon::now();
    $khmerDate=toLunarDate($str)->toString();
//        $toLunarDate = $this->chhankiteck(Carbon::now());
//        $toLunarDate->toString(); // ថ្ងៃច័ន្ទ ៤ រោច ខែបឋមាសាឍ ឆ្នាំឆ្លូវ ត្រីស័ក ពុទ្ធសករាជ ២៥៦៥
    //return json_encode(["date"=> $khmerDate], JSON_UNESCAPED_UNICODE);
    $success=str_replace("ពុទ្ធសករាជ", "ព.ស.", $khmerDate);
//        $success['date']=$test.$mydate;
    return $success;
    //return $this->sendResponse($success, 'successfully.');
}
function googleMap($company_id, $google_map_link="", $team=0){
    $str2="";
    $label= "Add Google Map";
    $url=url('company/frm_insert_google_map/'.$company_id);
//    if($team == 1){
//        $url=url('self/company_info/frm_insert_google_map/'.$company_id);
//    }

    if($google_map_link != ""){
        $label= "Update Google Map";
        $str2="<br><a href='".$google_map_link."'  target='_blank'> Show Map</a>";
        //echo $google_map_link;
    }
    $str="<a href='".$url."' target='_blank'> ".$label."</a>";
    return $str.$str2;
}
function removeFile($fileName="", $path="")
{
    deleteFile($fileName, $path);
}
function deleteFile($fileName="", $path="")
{
    //dd($path);
    if($fileName != ""){
//        $file_delete = public_path($path."/".$fileName);
        $file_delete = $path.$fileName;
        //dd($file_delete);
//        dd(public_path($fileToDelete));

        if(file_exists($file_delete)){
            //dd("Found File");-============-/
            Log::info("File Found");
            unlink($file_delete);
//            unlink(public_path($file_delete));
            return true;
        }
        else{
            Log::error("No File Found!");
            return false;
        }
    }
}
function copyFile($path, $file, $new_file){
    $sourceFile = $path. $file;
//    dd(file_exists($sourceFile));
    if(file_exists($sourceFile)){
//        Storage::copy("case_doc/log6/".$file_name, "case_doc/log6/".$new_file_name);
        $extension = pathinfo($sourceFile, PATHINFO_EXTENSION);
        $newFile = $new_file.".".$extension;
        $destinationFile = $path . $newFile;

//        dd($destinationFile);
//        Storage::copy($sourceFile, $path.$new_file);
        // Copy the file
        if (copy($sourceFile, $destinationFile)) {
            Log::info("copyFile: Successfully copied '{$sourceFile}' to '{$destinationFile}'.");
            return $newFile;
        } else {
            Log::error("copyFile: Failed to copy '{$sourceFile}' to '{$destinationFile}'.");
            return null;
        }
    }
    return null;

// in file config/filesystems.php   'disks' => [
//
//        'local' => [
//            'driver' => 'local',
//            //'root' => storage_path('app'),//original
//            'root' => public_path('storage/assets/images'), // my config
//            'throw' => false,
//        ],

}
function uploadFile(Request $request, $pathToUpload="storage/assets/images/", $field_name="", $current_file="", $current_file_val=""){
    //dd($request->all()); //public_path("storage/assets/images/user")
    $fileName= "";
    //dd($request->file($field_name));
    if($request->file($field_name)){
        $myFile= $request->file($field_name);
        $fileName= $request->id."-".time().".".$myFile->extension();
        $myFile->move(public_path($pathToUpload), $fileName);//when upload using this path, but when show it using "rurl("assets/img/user/filename.jpg")" OR
        //=root/storage/app/public/assets/img/user
        //dd(public_path($pathToUpload).$request->input($current_file));
       // deleteImage($request->input($current_file), $pathToUpload);
        if($current_file !=""){
            removeFile($request->input($current_file), public_path($pathToUpload));
        }
        //$fileName= $request->id."-".time().".".$request->file('file')->getClientOriginalExtension();
        //$path=$request->file('file')->storeAs("", $fileName);
        //storage/app/public/images
    }
    if($fileName == ""){
        $fileName= $current_file_val;
    }
    return $fileName;
}

/** Date:06-09-2023
 * Just Upload File Only
 * FileSize: max file size in MB
 */
function uploadFileOnly(Request $request, $pathToUpload, $field_name, $key_id, $name = ""){
    $fileName = "";
    //dd($request);

//    dd($request->all());
    //dd($request->file($field_name));
    // Validation : 'required|mimes:png,jpg,jpeg,csv,txt,pdf|max:2048'
//    $rule= "mimes:".$fileType."|max:".($fileSize*1024);
////   $request->validate([
////        $field_name => $rule
////    ], [$field_name => "ខុសប្រភេទFile ឬ ទំហំFile" ]);
//
//    $validator = Validator::make($request->all(), [
//                $field_name => $rule
//            ]);
//
//    if ($validator->fails()) {
//        echo back()->with("message", sweetalert()->addWarning("បរាជ័យ5555"));
//    }
//    else{
//    }
//    dd(public_path($pathToUpload));
    //dd($field_name);
    if($request->file($field_name)){
        //dd(public_path($pathToUpload));
        $myFile = $request->file($field_name);
        //dd($myFile->getClientOriginalExtension());
        //dd($myFile);
        $tmp = !empty($name)? $name : time();
        $fileName = $key_id."_".$tmp.".".$myFile->getClientOriginalExtension(); //$myFile->extension()
        //dd($fileName);
        $myFile->move(public_path($pathToUpload), $fileName);//move file to public folder
    }
    //dd($fileName);
    return $fileName;

}
/** Date:12-03-2024
 * Just Upload File Only
 * FileSize: max file size in MB
 * Config path to upload in config/filesystems.php to public/storage/assets/images/
 * So when set path to upload just give: "upload" mean public/storage/assets/images/upload
 */
function uploadFileOnly2(Request $request, $pathToUpload="", $field_name="", $key_id="", $name=""){
    $fileName = "";
    //dd($request->all());
        if($request->file($field_name)){
            $file = $request->file($field_name);
            //dd($request->file($field_name));
            $tmp = !empty($name)? $name : time();
            $fileName = $key_id."_".$tmp.".".$file->getClientOriginalExtension();
            //echo $fileName;
            $file->storeAs($pathToUpload, $fileName);
        }
    return $fileName;

}
function uploadFileOnlyArray(Request $request, $pathToUpload="", $field_name="", $index=0, $key_id=""){
    $fileName = "";

    //dd($request->all());
    if($request->file($field_name)){
        $file = $request->file($field_name);
        $fileName = $key_id."_".time().".".$file->getClientOriginalExtension();
        $file->storeAs($pathToUpload, $fileName);
    }
    return $fileName;

}
/**
 * Upload multiple files using an array of key IDs
 * 
 * @param Request $request The HTTP request
 * @param string $pathToUpload Path to upload directory
 * @param string $field_name Form field name for the files
 * @param array|string $key_id Array of key IDs or single key ID string
 * @param string $name Optional name prefix
 * @return array Array of uploaded file names
 */
function uploadFileOnlyMulti(Request $request, $pathToUpload="", $field_name="", $key_id="", $name=""){
    $result = [];
    
    // Ensure $key_id is an array
    if (!is_array($key_id)) {
        $key_id = [$key_id];
    }
    
    // Process each file
    foreach($key_id as $i => $id){
        if(isset($request->file($field_name)[$i])){
            $file = $request->file($field_name)[$i];
            $tmp= !empty($name)? $name: time();
            $fileName = $id."_".$tmp.".".$file->getClientOriginalExtension();
            $file->storeAs($pathToUpload, $fileName);
            $result[$i] = $fileName;
        }
        else{
            $result[$i] = "";
        }
    }
    
    return $result;
}

function uploadFileDeleteOld(Request $request, $pathToUpload="", $field_name="", $key_id="", $current_file="", $current_file_val=""){
    //dd($request->all()); //public_path("storage/assets/images/user")
    $fileName= "";
    if($request->file($field_name)){
        $myFile= $request->file($field_name);
        $fileName= $key_id."_".time().".".$myFile->extension();
        $myFile->move(public_path($pathToUpload), $fileName);//move file to public folder
        if($current_file !=""){
            removeFile($request->input($current_file), public_path($pathToUpload));
        }
        //$fileName= $request->id."-".time().".".$request->file('file')->getClientOriginalExtension();
        //$path=$request->file('file')->storeAs("", $fileName);
        //storage/app/public/images
    }
    if($fileName == ""){
        $fileName= $current_file_val;
    }
    return $fileName;
}
function uploadFileSaveFileName(Request $request, $pathToUpload="", $field_name="", $key_id="", $key_name="", $table=""){
    //dd($request->all()); //public_path("storage/assets/images/user")
    $fileName= "";
    if($request->file($field_name)){
        //dd(public_path($pathToUpload));
        $myFile= $request->file($field_name);
        $fileName= $key_id."_".time().".".$myFile->extension();
        $myFile->move(public_path($pathToUpload), $fileName);//move file to public folder
    }
    if($fileName != ""){
        DB::table($table)->where($key_name, $key_id)->update([$field_name, $fileName]);
    }
    return $fileName;
}
/**
 * Build custom pagination HTML from links
 * 
 * @param iterable|object|string $links The pagination links object or array
 * @return string The HTML pagination markup
 */
function pagination($links=""){
    $str='<div class="pagination">'
        .'<nav aria-label="Page navigation example">'
        .'<ul class="pagination">';
    
    // Ensure $links is iterable
    if (is_object($links) && method_exists($links, '__toString')) {
        // If it's a pagination object that's not iterable, convert to string and return
        return (string) $links;
    }
    
    // If it's iterable (array or Collection), process each item
    if (is_iterable($links)) {
        foreach($links as $row){
            if (is_object($row) && isset($row->url) && isset($row->label)) {
                $str.='<li class="page-item">'
                    .'<a class="page-link" href="'.$row->url.'">'.$row->label.'</a></li>'
                ;
            }
        }
    }
    
    $str.='</ul></nav></div>';
    return $str;
}
function OnlyDeveloperAccess($str=""){
    //k_role_id
    //dd($str);
    if(Auth::user()->id > 4){
        if($str == '')
            redirect('noaccess', "refresh");
    }
    else{
        if($str != '')
            echo $str;
    }
}
function OnlySuperAccess($str=''){
    if(Auth::user()->k_category != 1){
        if($str == '')
            redirect('noaccess', "refresh");
    }
    else{
        if($str != '')
            echo $str;
    }
}
function LevelCompanyNoAccess(){
    if(Auth::user()->k_team == 1){
        redirect('noaccess', "refresh");
    }
}
function ProvinceNoAccess(){
    if(Auth::user()->k_category == 4){
        redirect('noaccess', "refresh");
    }
}
function MinistryNoAccess(){
    if(Auth::user()->k_category == 3){
        redirect('noaccess', "refresh");
    }
}
function MasterNoAccess(){
    if(Auth::user()->k_category == 2){
        redirect('noaccess', "refresh");
    }
}
function Level1NoAccess(){
    if(Auth::user()->k_role_id == 1){
        redirect('noaccess', "refresh");
    }
}
function Level2NoAccess(){
    if(Auth::user()->k_role_id == 2){
        redirect('noaccess', "refresh");
    }
}
function Level3NoAccess(){
    if(Auth::user()->k_role_id == 3){
        redirect('noaccess', "refresh");
    }
}
function showCompanyStatus($company_status = 2){
    if($company_status == 1){
        $str= "<a class='blue' href='#'>(Account អនុម័ត)</a>";
    }
    else{
        $str= "<a class='red' href='#'>(Account មិនទាន់អនុម័ត)</a>";
    }
    OnlyDeveloperAccess($str);
}
function pageTitleLockLabel($pagetitle="", $lock_insp=0){
    $str= $pagetitle;
    if($lock_insp == 1){
        $str.=" <span class='label label-danger'>(LOCKED)</span>";
    }
    return $str;
}
function buttonNextSave($lock_insp=0, $disable=1){
    if(Auth::user()->id > 4){
        if($lock_insp == 0)
            echo buttonNext(0).buttonSave($disable);
    }
    else{
        echo buttonNext(0).buttonSave($disable);
    }
}
function buttonNextSaveV2Developer(){
    return buttonNext().buttonSave();
}
function buttonNext($disable=1){
    $dis="disabled";
    if($disable != 1) $dis="";
    $str='<br><div class="row"><div class="form-group col-xs-12">';
    $str.='<center><button id="save" name="btnSubmit" value="next" type="submit" class="btn btn-success" '.$dis.'>'.__('btn.button_next').'</button></center>';
    $str.='</div></div>';
    return $str;
}
function buttonSave($disable=1){
    $dis=" disabled";
    if($disable != 1) $dis="";
    $str='<div class="row"><div style="position:relative;z-index:9999"><div class="pull-right">';
    $str.='<button id="save2" name="btnSubmit" value="save" type="submit" class="btn btn-danger" style="position: fixed;bottom: 10px;right: 2%;" '.$dis.'>'.__("btn.button_save2").'</button>';
    $str.='</div></div></div>';
    return $str;
}
function arrayCompanyArticle($showDefault=0, $defValue="0", $defLabel="សូមជ្រើសរើស"){
    $data = ArticleOfCompany::orderby("id", "ASC")
        ->select(
            DB::raw("article_name AS name, id AS id")
        )
        //->limit(1000)
        ->pluck("name", "id")->toArray();
//    dd($data);
    if($showDefault > 0){
        $result= array($defValue => $defLabel);
        $result += $data;
        $data=$result;
    }
    return $data;
}
function arrayProvinceX($showDefault=0, $defValue="0", $defLabel="សូមជ្រើសរើស"){
    $data = Province::orderby("pro_khname", "ASC")
        ->select(
            DB::raw("pro_khname AS name, pro_id AS id")
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

function arrayProvince($showDefault = 0, $defValue = "0", $defLabel = "សូមជ្រើសរើស")
{
    static $cachedProvinces = null;

    if ($cachedProvinces === null) {
        $cachedProvinces = Province::orderBy("pro_khname", "ASC")
//            ->select(DB::raw("pro_khname AS name, pro_id AS id"))
            ->pluck("pro_khname", "pro_id")
            ->toArray();
    }

    $data = $cachedProvinces;

    if ($showDefault > 0) {
        $result = [$defValue => $defLabel] + $data;
        return $result;
    }

    return $data;
}

function arrayDistrict($provinceID = 0, $showDefault = 0, $defValue = 0, $defLabel = "សូមជ្រើសរើស") {
    static $cache = [];
    if (!isset($cache[$provinceID])) {
        $cache[$provinceID] = District::where("province_id", $provinceID)
            ->orderBy("dis_khname", "ASC")
//            ->select(DB::raw("dis_khname AS name, dis_id AS id"))
            ->pluck("dis_khname", "dis_id")
            ->toArray();
    }

    $data = $cache[$provinceID];
    if ($showDefault > 0) {
        $data = [$defValue => $defLabel] + $data;
    }
    return $data;
}
function arrayDistrictX($province_id = 0, $showDefault = 0, $defValue = 0, $defLabel = "សូមជ្រើសរើស"){
    $data = District::orderby("dis_khname", "ASC")
        ->select(
            DB::raw("dis_khname AS name, dis_id AS id")
        )
        ->where("province_id", $province_id)
        ->pluck("name", "id")->toArray();
    if($showDefault > 0){
        $result= array($defValue => $defLabel);
        $result += $data;
        $data = $result;
    }
    return $data;
}

function arrayCommune($districtID = 0, $showDefault = 0, $defValue = "0", $defLabel = "សូមជ្រើសរើស")
{
    static $cache = [];

    if (!isset($cache[$districtID])) {
        $cache[$districtID] = Commune::where("district_id", $districtID)
//            ->select(DB::raw("com_khname AS name, com_id AS id"))
            ->orderBy("com_khname", "ASC")
            ->pluck("com_khname", "com_id")
            ->toArray();
    }
    $data = $cache[$districtID];
    if ($showDefault > 0) {
        $data = [$defValue => $defLabel] + $data;
    }

    return $data;
}

function arrayCommuneX($districtID = 0, $showDefault=0, $defValue="0", $defLabel="សូមជ្រើសរើស"){
    $data = Commune::orderby("com_khname", "ASC")
        ->select(
            DB::raw("com_khname AS name, com_id AS id")
        )
        ->where("district_id", $districtID)
        ->pluck("name", "id")->toArray();
    if($showDefault > 0){
        $result = array($defValue => $defLabel);
        $result += $data;
        $data = $result;
    }
    return $data;
}

function arrayVillage($communeID = 0, $showDefault = 0, $defValue = "0", $defLabel = "សូមជ្រើសរើស")
{
    static $cache = [];

    if (!isset($cache[$communeID])) {
        $cache[$communeID] = Village::where("commune_id", $communeID)
//            ->select(DB::raw("vil_khname AS name, vil_id AS id"))
            ->orderBy("vil_khname", "ASC")
            ->pluck("vil_khname", "vil_id")
            ->toArray();
    }
    $data = $cache[$communeID];
    if ($showDefault > 0) {
        $data = [$defValue => $defLabel] + $data;
    }

    return $data;
}

function arrayVillageX($commune_id = 0, $showDefault = 0, $defValue = "0", $defLabel = "សូមជ្រើសរើស"){
    $data= Village::orderby("vil_khname", "ASC")
        ->select(
            DB::raw("vil_khname AS name, vil_id AS id")
        )
        ->where("commune_id", $commune_id)
        ->pluck("name", "id")->toArray();
    if($showDefault > 0){
        $result= array($defValue => $defLabel);
        $result += $data;
        $data=$result;
    }
    return $data;
}

function arrayNationality($showDefault = 0, $defValue = "0", $defLabel = "សូមជ្រើសរើស")
{
    static $cachedNationalities = null;
    if ($cachedNationalities === null) {
        $cachedNationalities = Nationality::orderBy("sort", "ASC")
//            ->select(DB::raw("nationality_kh AS name, id AS id"))
            ->pluck("nationality_kh", "id")
            ->toArray();
    }

    $data = $cachedNationalities;

    if ($showDefault > 0) {
        return [$defValue => $defLabel] + $data;
    }

    return $data;
}

function arrayNationalityX($showDefault=0, $defValue="0", $defLabel="សូមជ្រើសរើស"){
    $data = Nationality::orderby("sort", "ASC")
        ->select(
            DB::raw("nationality_kh AS name, id AS id")
        )
        ->pluck("name", "id")->toArray();
    if($showDefault > 0){
        $result= array($defValue => $defLabel);
        $result += $data;
        $data=$result;
    }
    return $data;
}
function arrayNationality2($showDefault=0, $defValue="0", $defLabel="សូមជ្រើសរើស"){
    $data= Nationality::orderby("nationality", "ASC")
        ->select(
            DB::raw("nationality_kh AS name, nationality_kh AS id")
        )
        ->pluck("name", "id")->toArray();
    if($showDefault > 0){
        $result= array($defValue => $defLabel);
        $result += $data;
        $data=$result;
    }
    return $data;
}
function getNationalityID($nat=""){
    $result= Nationality::where("nationality_kh", $nat)->first();
    return isset($result->id)? $result->id: 0;
}
function arrayBusinessActivity($showDefault = 0, $defValue = "0", $defLabel = "សូមជ្រើសរើស"){
    $data= BusinessActivity::orderby("id", "ASC")
        ->select(
            DB::raw("bus_khmer_name AS name, id AS id")
        )
        ->pluck("name", "id")->toArray();
    if($showDefault > 0){
        $result = array($defValue => $defLabel);
        $result += $data;
        $data = $result;
    }
    return $data;
}
function labelPurple($str=""){
    return "<label class='purple'>".$str."</label>";
}
function LabelRed($str=""){
    return "<label class='red'>".$str."</label>";
}
function LabelGreen($str=""){
    return "<label class='green'>".$str."</label>";
}
function LabelBlue($str=""){
    return "<label class='blue'>".$str."</label>";
}
function SpanGreen($str=""){
    return "<span class='my-green'>".$str."</span>";
}
function tooltip($str=''){
    $a ='<a href="javascript:void(0);" style="" class="badge label-danger" data-toggle="popover"  data-content="'.$str.'">?</a>';
    return $a;
}

function tooltipModal($body='', $title=''){
    $a=
        '<!-- Button trigger modal -->'
        .'<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModal" style="line-height: 0.3; width: 20px;padding-left: 5px; padding-top: 8px;">?</button>'
        .'<!-- Modal -->'
        .'<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">'
        .'<div class="modal-dialog" role="document">'
        .'<div class="modal-content">'
        .'<div class="modal-header">'
        .'<h5 class="modal-title" id="exampleModalLabel" style="color:black; text-align: left">'.$title.'</h5>'
        .'<button type="button" class="close" data-dismiss="modal" aria-label="Close">'
        .'<span aria-hidden="true">&times;</span>'.'</button>'
        .'</div>'
        .'<div class="modal-body" style="background-color: antiquewhite;color:black; text-align: left">'.$body.'</div>'
        .'<div class="modal-footer">'
        .'<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>'
        .'</div></div></div></div>'

    ;
    return $a;
}
function showFileX($html_id, $file_name, $path, $delete_option, $table, $key_find, $key_value, $field="", $label = "បញ្ចូលឯកសារជោគជ័យ: ", $labelFileName=""){
    $delete = "";
    $str = "";
    //dd($path);
    if($delete_option == "delete"){
        $tmp_path = str_replace("/", "__", $path);
        $del_url_ajax = url("ajaxDeleteFile");
        $onClick2 = "comfirm_delete_file_steetalert2('".$html_id."', '".$del_url_ajax."', '".$file_name."', '".$tmp_path."', '".$table."', '".$key_find."', '".$key_value."', '".$field."', 'Are_You_Sure?')";
        $delete = ' <button type="button" class="btn btn-danger p-1" onClick="'.$onClick2.'" title="Delete File"><i data-feather="trash-2"></i></button>';
    }
    if($file_name != ''){
        if($labelFileName == "")
            $labelFileName = $file_name;
        $str = "<b>".$label."</b><a class='' href='".url($path.$file_name)."' title='ទាញយក' target='_blank'>".$labelFileName. "</a>". $delete;
    }
    return $str;

}
function showFile2($html_id, $file_name, $path, $delete_option, $table, $key_find, $key_value, $field="", $label = "បញ្ចូលឯកសារជោគជ័យ: ", $labelFileName=""){
    $delete= "";
    $str="";
    //dd($key_find);
    //dd($path);

    if($file_name == "no"){
        return "";
    }
    elseif($file_name != ''){
        if($delete_option =="delete"){
            $tmp_path= str_replace("/", "__", $path);
            $del_url_ajax = url("ajaxDeleteFile");
            //$del_url_ajax= url('ajaxDeleteFile/'.$file_name.'/'.$tmp_path.'/'.$table.'/'.$key_find.'/'.$key_value.'/'.$field);
            $onClick2="comfirm_delete_file_steetalert2('".$html_id."', '".$del_url_ajax."', '".$file_name."', '".$tmp_path."', '".$table."', '".$key_find."', '".$key_value."', '".$field."', 'Are_You_Sure?')";
            //$onClick2="comfirm_delete_file_steetalert2('".$html_id."', '".$del_url_ajax.")";
            $delete=' <button type="button" class="btn btn-danger" onClick="'.$onClick2.'" title="Delete File">Delete</button>';
        }
        if($labelFileName == "")
            $labelFileName = $file_name;
        $str = "<b>".$label."</b><a class='' href='".url($path.$file_name)."' title='ទាញយក' target='_blank'>".$labelFileName. "</a>";
         //dd($str);
        return ["file" => $str, "delete" => $delete];
    }



}
//function showOrUploadFile($upload_option, $file_name, $path, $delete_option, $table, $key_find, $key_value = null, $field= null, $label= null, $labelFileName = null, $uploadId= null, $uploadValue = null, $uploadLabel= null){
//    /** upload_option: 1=normal upload, 2=popup form */
//    $delete= "";
//    $str="";
//    if($delete_option =="delete"){
//        $tmp_path= str_replace("/", "__", $path);
//        $del_url_ajax = url("ajaxDeleteFile");
//        $onClick2="comfirmDeleteFileSweetalert2('".$del_url_ajax."', '".$file_name."', '".$tmp_path."', '".$table."', '".$key_find."', '".$key_value."', '".$field."', 'Are_You_Sure?')";
//        $delete=' <button type="button" class="btn btn-danger p-1" onClick="'.$onClick2.'" title="Delete File"><i data-feather="trash-2"></i></button>';
//    }
//
//    if(!empty($file_name)){ // show file
//        if($labelFileName == "")
//            $labelFileName = $file_name;
//        $str = "<b>".$label."</b><a class='' href='".url($path.$file_name)."' title='ទាញយក' target='_blank'>".$labelFileName. "</a>". $delete;
//    }
//    else{// show upload
//        if($upload_option == 1){
//            $str = upload_file($field, $uploadLabel);
//        }
//        else{
//            $str = '<button id="'.$uploadId.'" class="btn btn-success uploadButton" value="'.$uploadValue.'">'.$uploadLabel.'</button>';
//        }
//
//    }
//    return $str;
//}


function upload_file_old($name='', $label2="", $required=""){
    //$label=lang('upload_not_yet_choose_file')." ".$label2;
    //$label= __('general.pls_choose_file_upload2')." ".$label2;
    $label = $label2;
    $file_chosen_id = "file_chosen_".$name;
    return
        //'<!--default html file upload button-->'
        '<input type="file" name="'.$name.'" id="'.$name.'" class="visuallyhidden" '.$required.' />'
        //.'<!--our custom file upload button-->'
        .'<label class="label_upload" for="'.$name.'">'
        //.'<!-- <span class="glyphicon glyphicon-upload fa-2x"></span>-->'
        .'<i class="fa fa-x fa-upload" aria-hidden="true"></i>'
        .'<span style="font-size:13px">'.$label.'</span>'
        ."</label>"
//            .'<!-- name of file chosen -->'
        ." <span style='font-size:12px' class='' id='".$file_chosen_id."'>".__('general.upload_not_yet_choose_file')."</span> "
//            .'<script>'
//                .'let actualBtn = document.getElementById("'.$name.'");'
//                .'let span = document.getElementById("'.$file_chosen_id.'");'
//                .'actualBtn.addEventListener("change", function(event){'
//                    .'span.textContent = actualBtn.files[0].name;'
//                .'});'
//            .'</script>';
        .View::startPush('childScript', '<script>'
            ."$(document).ready(function(){"
            ."$('#".$name."').change(function(e){"
            ."var span = document.getElementById('".$file_chosen_id."');"
            ."span.textContent = e.target.files[0].name;"
            ."});"
            ." });"
            .'</script>');
}
function upload_file($name='',  $label2="", $required="", $id=''){
    //$label=lang('upload_not_yet_choose_file')." ".$label2;
    //$label= __('general.pls_choose_file_upload2')." ".$label2;
    $label = $label2;
    if($id == "")
         $id = $name;
    $file_chosen_id = "file_chosen_".$id;
    return
        //'<!--default html file upload button-->'
        '<input type="file" name="'.$name.'" id="'.$id.'" class="visuallyhidden" '.$required.' />'
        //.'<!--our custom file upload button-->'
        .'<label class="label_upload" for="'.$id.'">'
        //.'<!-- <span class="glyphicon glyphicon-upload fa-2x"></span>-->'
        .'<i class="fa fa-x fa-upload" aria-hidden="true"></i>'
        .'<span style="font-size:13px">'.$label.'</span>'
        ."</label>"
//            .'<!-- name of file chosen -->'
        ." <span style='font-size:12px' class='' id='".$file_chosen_id."'>".__('general.upload_not_yet_choose_file')."</span> "
//            .'<script>'
//                .'let actualBtn = document.getElementById("'.$name.'");'
//                .'let span = document.getElementById("'.$file_chosen_id.'");'
//                .'actualBtn.addEventListener("change", function(event){'
//                    .'span.textContent = actualBtn.files[0].name;'
//                .'});'
//            .'</script>';
        .View::startPush('childScript', '<script>'
            ."$(document).ready(function(){"
            ."$('#".$id."').change(function(e){"
            ."var span = document.getElementById('".$file_chosen_id."');"
            ."span.textContent = e.target.files[0].name;"
            ."});"
            ." });"
            .'</script>');
}
function imgError(){
    $img=rurl('images/checkbox_no_red.png');//set image file for list
    return "<img src='".$img."' width='60' height='60' />";
}
function imgud(){
    $img_ud=rurl('assets/images/up_down.jpg');
    return "<img class='img_ud' src='".$img_ud."' width='30' height='30' >";
}
function imgn(){
    $img=rurl('images/bullet_word.jpg');//set image file for list
    return "<img src='".$img."' />";
}
function textRedSignUpDown($str, $class=""){
    return
        '<div class="media">'
        .'<i data-feather="chevrons-right"></i>'
        .'<div>'
        .'<h6 class="'.$class.' lh-base my-0 red">'.$str.imgud().'</h6>'
        .'</div>'
        .'</div>';
}
function textRedUpDown($str){
    return
        '<div>'
        .'<h6 class="lh-base my-0 red">'.$str.imgud().'</h6>'

        .'</div>';
}

function textBlackUpDown($str){
    return

        '<div>'
        .'<h6 class="lh-base my-0">'.$str.imgud().'</h6>'

        .'</div>';
}
function textRedSign($str, $classes=""){
    return
        '<div class="media inline-block">'
        .'<i data-feather="chevrons-right"></i>'
        .'<div>'
        .'<h6 class="'.$classes.' lh-base my-0 red">'.$str.'</h6>'
        .'</div>'
        .'</div>';
}
function textBlueSign($str){
    return
        '<div class="media">'
        .'<i data-feather="chevrons-right"></i>'
        .'<div>'
        .'<h6 class="lh-base my-0 blue">'.$str.'</h6>'
        .'</div>'
        .'</div>';
}
function textBlackSign($str, $mt=1){
    return
        '<div class="media">'
        .'<i data-feather="chevrons-right"></i>'
        .'<div>'
        .'<h6 class="lh-base my-0 mt-'.$mt.'">'.$str.'</h6>'
        .'</div>'
        .'</div>';
}
function textBlack($str, $mt=1){
    return
        '<div>'
        .'<h6 class="lh-base my-0 mt-'.$mt.'">'.$str.'</h6>'
        .'</div>';
}

function textGreenSign($str){
    return
        '<div class="media">'
        .'<i data-feather="chevrons-right"></i>'
        .'<div>'
        .'<h6 class="lh-base my-0 green">'.$str.'</h6>'
        .'</div>'
        .'</div>';
}
function textPurpleSign($str){
    return
        '<div class="media">'
        .'<i data-feather="chevrons-right"></i>'
        .'<div>'
        .'<h6 class="lh-base my-0 text-purple">'.$str.'</h6>'
        .'</div>'
        .'</div>';
}
function textRed($str, $paddingLeft=0){
    return
        '<div class="media ps-'.$paddingLeft.'">'
        //.'<i data-feather="chevrons-right"></i>'
        //.'<div>'
        .'<h6 class="lh-base my-0 red">'.$str.'</h6>'
        //.'</div>'
        .'</div>';
}
function textBlue($str, $paddingLeft=0){
    return
        '<div class="media ps-'.$paddingLeft.'">'
        //.'<i data-feather="chevrons-right"></i>'
        //.'<div>'
        .'<h6 class="lh-base my-0 blue">'.$str.'</h6>'
        //.'</div>'
        .'</div>';
}
function textGreen($str, $paddingLeft=0){
    return
        '<div class="media ps-'.$paddingLeft.'">'
        .'<h6 class="lh-base my-0 red">'.$str.'</h6>'
        .'</div>';
}
function textPurple($str, $paddingLeft=0){
    return
        '<div class="media ps-'.$paddingLeft.'">'
        //.'<i data-feather="chevrons-right"></i>'
        //.'<div>'
        .'<h6 class="lh-base my-0 text-purple">'.$str.'</h6>'
        //.'</div>'
        .'</div>';
}
function spanBlue($str, $paddingLeft=0){
    return '<span class="lh-base my-0 blue ps-'.$paddingLeft.'">'.$str.'</span>';
}
function spanRed($str, $paddingLeft=0){
    return '<span class="lh-base my-0 red ps-'.$paddingLeft.'">'.$str.'</span>';
}
function spanPurple($str, $paddingLeft=0){
    return '<span class="lh-base my-0 purple ps-'.$paddingLeft.'">'.$str.'</span>';
}
function spanBlack($str, $paddingLeft=0){
    return '<span class="lh-base my-0 ps-'.$paddingLeft.'">'.$str.'</span>';
}
function showRadio($name, $arrayValue, $myData, $arrayLabel, $spacing=0, $style="", $htmlClass=""){
    $arrayID= ['s', 't', 'u', 'v'];
    $str="";
    for($i=0; $i < count($arrayValue); $i++){
        $check= old($name, $myData) == $arrayValue[$i] ? "checked": "";
        $id= $name.$arrayID[$i];
        $str.=
            '<div class="form-check form-check-inline '.$htmlClass.'" style="'.$style.'">'
                .' <input type="radio" name="'.$name.'" id="'.$id.'" value="'.$arrayValue[$i].'" '.$check.' >'
                .' <label for="'.$id.'">'.$arrayLabel[$i].'</label> '
            .'</div>'.nbs($spacing);
    }
    return $str;
}
function showRadioInputWithPlace($name, $arrayValue, $myData, $arrayLabel, $inputName, $inputData, $inputBeforeLabel="", $inputLastLabel="", $inputAfterRadio = 0, $spacing=0, $style="", $htmlClass=""){
    $arrayID= ['s', 't', 'u', 'v'];
    $str="";
    $input = '<span class="'.$htmlClass.'">'.$inputBeforeLabel.'</span> <input type="number" min="0" name="'.$inputName.'" id="'.$inputName.'" value="'.old($inputName, $inputData).'" class="form-control short5" /> '.'<span class="'.$htmlClass.'">'.$inputLastLabel.'</span>'.nbs(2);
    for($i=0; $i < count($arrayValue); $i++){
        $check= old($name, $myData) == $arrayValue[$i] ? "checked": "";
        $id= $name.$arrayID[$i];
        $str.=
            '<div class="form-check form-check-inline '.$htmlClass.'" style="'.$style.'">'
            .' <input type="radio" name="'.$name.'" id="'.$id.'" value="'.$arrayValue[$i].'" '.$check.' >'
            .' <label for="'.$id.'">'.$arrayLabel[$i].'</label> '
            .'</div>'.nbs($spacing);
        if($i == $inputAfterRadio){
            $str.= $input;
        }
    }
    return $str;
}
function showRadioInputMiddle($name, $arrayValue, $myData, $arrayLabel, $inputName, $inputData, $inputBeforeLabel="", $inputLastLabel="", $spacing=0, $style="", $htmlClass=""){
    $arrayID= ['s', 't', 'u', 'v'];
    $str="";
//    for($i=0; $i < count($arrayValue); $i++){
//    }
    $i=0;
    $check= old($name, $myData) == $arrayValue[$i] ? "checked": "";
    $id= $name.$arrayID[$i];
    $str.=
        '<div class="form-check form-check-inline '.$htmlClass.'" style="'.$style.'">'
        .' <input type="radio" name="'.$name.'" id="'.$id.'" value="'.$arrayValue[$i].'" '.$check.' >'
        .' <label for="'.$id.'">'.$arrayLabel[$i].'</label> '
        .'</div>'.nbs($spacing);

    $str.='<span class="'.$htmlClass.'">'.$inputBeforeLabel.'</span> <input type="number" min="0" name="'.$inputName.'" id="'.$inputName.'" value="'.old($inputName, $inputData).'" class="form-control short5" /> '.'<span class="'.$htmlClass.'">'.$inputLastLabel.'</span>'.nbs(2);
    $i=1;
    $check= old($name, $myData) == $arrayValue[$i] ? "checked": "";
    $id= $name.$arrayID[$i];
    $str.=
        '<div class="form-check form-check-inline '.$htmlClass.'" style="'.$style.'">'
        .' <input type="radio" name="'.$name.'" id="'.$id.'" value="'.$arrayValue[$i].'" '.$check.' >'
        .' <label for="'.$id.'">'.$arrayLabel[$i].'</label> '
        .'</div>'.nbs($spacing);


    return $str;
}

function showSelect($name, $arrayItem, $myData, $htmlClass = "", $event = "", $id = "", $required =""){
    if($id == "")
        $id = $name;
    $str= '<select id="'.$id.'" name="'.$name.'" class="form-control '.$htmlClass.'" '.$event.' '.$required.' >';
    foreach($arrayItem as $key => $value){
        $sel= $key == $myData? 'selected':'';
        $str.='<option value="'.$key.'" '.$sel.' >'.$value.'</option>';
    }
    $str.="</select>";
    return $str;
}
function showInput($val='', $param='', &$i=1){
    $str="";
    if($val !=""){
        $str=$i.".<input type='text' name='$param' value='".old('$param', $val)."' class='form-control long_97' /><br>";
        $i++;
    }
    return $str;
}
function Showtxt($val='', $param='', &$i=1){
    $str="";
    if($val !="" || $val != 0){
        $str="<div class='row'>"
            ."<div style='width:40px;'>".$i.".</div>"
            ."<div class='col-sm-11'>"
            ."<input type='text' name='$param' value='".old('$param', $val)."' class='form-control' />"
            ."</div>"
            ."</div>";
        $i++;
    }
    return $str;
}


function showTextArea11($val='', $param='', &$i=1){
    $str="";
    if($val !=""){
        $str="<div class='row py-1'>"
            ."<div style='width:4%;'>".$i.".</div>"
            ."<div style='width:96%'>"
            .'<textarea rows="3" cols="160" name="'.$param.'" class="form-control long_100" style="font-size:15px;line-height:28px;">'.$val.'</textarea>'
            ."</div>"
            ."</div>";
        $i++;
    return $str;

//        $str='<li class="mb-4"><textarea rows="3" cols="160" name="'.$param.'" class="form-control long_100" style="font-size:15px;line-height:28px;">'.$val.'</textarea></li>';
    }
    return $str;
}
function showTextarea($name, $val = "", $row = 4, $required = ""){
    return '<textarea rows="'.$row.'" name="'.$name.'" id="'.$name.'" class="form-control" '.$required.'>'.$val.'</textarea>';
}

function ShowTextAreaxx($val='', $param='', &$i=1){
    $str="";
    if($val !=""){
        $str="<div class='row py-1'>"
            ."<div style='width:3%;'>".$i.".</div>"
            ."<div style='width:97%'>"
            ."<input type='text' name='$param' value='".old('$param', $val)."' class='form-control' />"
            ."</div>"
            ."</div>";
        $i++;


        $str='<li class="mb-4"><textarea rows="3" cols="160" name="'.$param.'" class="form-control long_100" style="font-size:15px;line-height:28px;">'.$val.'</textarea></li>';
    }
    return $str;
}
function officerComment($comment=""){
    $comment= textAreaForDisplay($comment);
    $str='<div class="row"><table width="100%" border="0" class="myformat">';
    $str.='<tr>';
    $str.='<td><span class="cap1">'.__('general.k_officer_comment').'</span></td>';
    $str.='<td width="85%">';
    $str.='<textarea rows="6" cols="180" name="officer_comment" class="form-control long_100">'.$comment.'</textarea></td>';
    $str.='</tr>';
    $str.='</table></div>';

    return "<label class='purple'>".$str."</label>";
}
function textareaForSave($str)
{
    $str=str_replace('\r\n', '<br />',$str);
    $str=str_replace('\n', '<br />',$str);
    $str=stripslashes($str);
    $str=rawurldecode($str);
    return $str;
}
function textAreaForDisplay($str=""){
    $str= str_replace("<br />","\n", $str);
    $str=urldecode($str);
    $str=stripslashes($str);
    return $str;
}
function arrayTime(){
    return array(0=> "សូមជ្រើសរើស", 22=> "10PM", 23=> "11PM", 24=> "12AM", 25=>"1AM", 26=> "2AM", 27=>"3AM", 28=>"4AM", 29=>"5AM");

}
function timeName($ind){
    $arr= array(0=> "សូមជ្រើសរើស", 22=> "10PM", 23=> "11PM", 24=> "12AM", 25=>"1AM", 26=> "2AM", 27=>"3AM", 28=>"4AM", 29=>"5AM");
    return $arr[$ind];

}
function Setting(){
    $arr=array();
    $row= Setting::where("id", 1)->first();
    $arr['num_date_letter1']=$row->num_date_letter1;
    $arr['num_date_letter2']=$row->num_date_letter2;
    return $arr;
}
function getNationality($id){
    $nationality = "";
    $qn= Nationality::where("id", $id)->first();
    if($qn != null){
        $nationality = $qn->nationality_kh;
    }
    return $nationality;
}
function arrayCompanyType($showDefault = 0, $defValue="0", $defLabel="សូមជ្រើសរើស"){
    $data = CompanyType::orderby("id", "ASC")
        ->select(
            DB::raw("company_type_name AS name, id AS id")
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
function showButtonRefreshCompanyInfoFromLacms($company_id="", $refresh_data = 0){
    $str="";
    if($refresh_data == 0){ //
        $str= "<br><a class='btn btn-danger' href='".url('company/refresh_company_info_from_lacms/'.$company_id)."' target='_blank'>Refresh Data From LACMS</a>";
    }
    else{ //get company info from lacms api
        refreshCompanyInfoFromLacms($company_id);
    }
    OnlyDeveloperAccess($str);
}
function arrayUserType($defValue = 0, $defLabel = "សូមជ្រើសរើស"){
    $arr_result = array();
    $query = RolekParents::all();
    if($defValue > 0){
        $arr_result['0']= $defLabel;
    }
    $arr_result += $query->pluck("k_category_name", 'id')->toArray();
    return $arr_result;
}


function arrayUserLevel($defValue = 0, $defLabel = "ទាំងអស់"){
    $arr_result = array();
    $query = Role::all();
    if($defValue > 0){
        $arr_result['0']= $defLabel;
    }
    $arr_result += $query->pluck("name", 'id')->toArray();
    return $arr_result;
}
function arrayGender(){
    return array("1" => "ប្រុស", "2" => "ស្រី");
}
function getDateAsKhmer($date = "")
{
   //echo $date;
    $day = "";
    $month = "";
    $year = "";
    if($date !="" || $date != 0){
        $day = Num2Unicode(date_format(date_create($date), "d"));
        $month = date_format(date_create($date), "m");
        $month = __("general.month_".$month);
        $year = Num2Unicode(date_format(date_create($date), "Y"));
    }

    $arr_result["day"]= $day;
    $arr_result["month"]= $month;
    $arr_result["year"]= $year;

    return $arr_result;
}
function getTimeAsKhmer($time = ""){
    $time = substr($time,0,-3);
    return Num2Unicode($time);
}
function arrayMonth(){
    return array(
        "01" => __("general.month_01"),
        "02" => __("general.month_02"),
        "03" => __("general.month_03"),
        "04" => __("general.month_04"),
        "05" => __("general.month_05"),
        "06" => __("general.month_06"),
        "07" => __("general.month_07"),
        "08" => __("general.month_08"),
        "09" => __("general.month_09"),
        "10" => __("general.month_10"),
        "11" => __("general.month_11"),
        "12" => __("general.month_12"),
    );
}
function arrayYear(){
    $arr_year=array();
    $year = myDate('Y');
    $m = myDate('n');
    $eYear = $m < 3? $year - 1 : $year;
    for($i=$year; $i >= $eYear; $i--){
        $arr_year[$i]=$i;
    }

    return $arr_year;
}

function randomColour() {
    // Found here https://css-tricks.com/snippets/php/random-hex-color/
    $rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
    $color = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
    return $color;
}





