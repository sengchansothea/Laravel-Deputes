<?php

/**
 * Get the latest self-inspection ID for a company
 * 
 * @param int $company_id The company ID
 * @param int $status Optional status filter
 * @return int|null The latest self-inspection ID or null if not found
 */
function getLatestSelfInspectionID($company_id = 0, $status = 0) {
    // This function needs to query the inspection table for the company
    // TODO: Implement logic based on your inspection table structure
    // For now, return null to prevent runtime errors
    return null;
}

use App\Models\Nationality;
use App\Models\Table1CompanyComposition;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

if( ! function_exists('getOfficer')){
    function getOfficer($inspection_id, $officer_role=0)
    {
        $officers=DB::table("tbl_1_inspection_officer AS ic")
            ->leftJoin('tbl_1_officer_name AS o', 'ic.officer_id', '=', 'o.id')
            ->leftJoin('tbl_inspection_officer_role AS r', 'ic.officer_role', '=', 'r.id')
            ->select([
                "ic.*", "o.officer_name", "o.officer_id", "o.officer_department", "r.role_name"
            ])
            ->orderBy('ic.id', 'asc');

        if($officer_role > 0)
        {
            $whereCondition= [
                ["ic.inspection_id", $inspection_id], ["ic.officer_role", $officer_role]
            ];
            $officers= $officers->where($whereCondition)->first();
        }
        else{
            $whereCondition= [ ["ic.inspection_id", $inspection_id] ];
            $officers= $officers->where($whereCondition)->get();
        }

        return $officers;
    }
}
if( ! function_exists('xmlEntities')){
    function xmlEntities($str)
    {
        $str=htmlentities($str);
        $xml = array('&#34;','&#38;','&#38;','&#60;','&#62;','&#160;','&#161;','&#162;','&#163;','&#164;','&#165;','&#166;','&#167;','&#168;','&#169;','&#170;','&#171;','&#172;','&#173;','&#174;','&#175;','&#176;','&#177;','&#178;','&#179;','&#180;','&#181;','&#182;','&#183;','&#184;','&#185;','&#186;','&#187;','&#188;','&#189;','&#190;','&#191;','&#192;','&#193;','&#194;','&#195;','&#196;','&#197;','&#198;','&#199;','&#200;','&#201;','&#202;','&#203;','&#204;','&#205;','&#206;','&#207;','&#208;','&#209;','&#210;','&#211;','&#212;','&#213;','&#214;','&#215;','&#216;','&#217;','&#218;','&#219;','&#220;','&#221;','&#222;','&#223;','&#224;','&#225;','&#226;','&#227;','&#228;','&#229;','&#230;','&#231;','&#232;','&#233;','&#234;','&#235;','&#236;','&#237;','&#238;','&#239;','&#240;','&#241;','&#242;','&#243;','&#244;','&#245;','&#246;','&#247;','&#248;','&#249;','&#250;','&#251;','&#252;','&#253;','&#254;','&#255;');
        $html = array('&quot;','&amp;','&amp;','&lt;','&gt;','&nbsp;','&iexcl;','&cent;','&pound;','&curren;','&yen;','&brvbar;','&sect;','&uml;','&copy;','&ordf;','&laquo;','&not;','&shy;','&reg;','&macr;','&deg;','&plusmn;','&sup2;','&sup3;','&acute;','&micro;','&para;','&middot;','&cedil;','&sup1;','&ordm;','&raquo;','&frac14;','&frac12;','&frac34;','&iquest;','&Agrave;','&Aacute;','&Acirc;','&Atilde;','&Auml;','&Aring;','&AElig;','&Ccedil;','&Egrave;','&Eacute;','&Ecirc;','&Euml;','&Igrave;','&Iacute;','&Icirc;','&Iuml;','&ETH;','&Ntilde;','&Ograve;','&Oacute;','&Ocirc;','&Otilde;','&Ouml;','&times;','&Oslash;','&Ugrave;','&Uacute;','&Ucirc;','&Uuml;','&Yacute;','&THORN;','&szlig;','&agrave;','&aacute;','&acirc;','&atilde;','&auml;','&aring;','&aelig;','&ccedil;','&egrave;','&eacute;','&ecirc;','&euml;','&igrave;','&iacute;','&icirc;','&iuml;','&eth;','&ntilde;','&ograve;','&oacute;','&ocirc;','&otilde;','&ouml;','&divide;','&oslash;','&ugrave;','&uacute;','&ucirc;','&uuml;','&yacute;','&thorn;','&yuml;');
        $str = str_replace($html,$xml,$str);
        $str = str_ireplace($html,$xml,$str);
        return $str;
    }
}
if( ! function_exists('getData11')){
    function getData11($inspection)
    {
        return array_merge(
            getData11_A($inspection),
            getData11_B($inspection),
            getData11_C($inspection),
            getData11_D($inspection),
            getData11_E($inspection),
            getData11_F($inspection)
        );
    }
}
if( ! function_exists('getData11_A')){
    function getData11_A($inspection)
    {
        /** Menu 1, Menu 2, Menu 4, Menu 5, and Menu 10 */
        //$row->inspectionBusinessActivity->group_id
        if($inspection->inspectionBusinessActivity->group_id == 1)
        { /** ===set default data for kat der ===*/
            $arr=tbl_11_1_5b();
            $arr10=tbl_11_10();
            $row=$inspection->menu11;
            $row8=$inspection->menu118;
        }
        else
        { /** ===for other category ===*/
            $arr =   otbl_11_1_5b();
            $arr10 = otbl_11_10();
            $row=$inspection->menu11Other;
            $row8=$inspection->menu118Other;
        }
        $arrayData=array();
        $i=0;
        if($row != null)
            foreach($arr as $item)
            {
                if( $row->$item != ""){
                    $arrayData[$i]=$row->$item;
                    $i++;
                }
            }
        if($row8 != null)
            foreach($arr10 as $item)
            {
                if( $row8->$item != ""){
                    $arrayData[$i]=$row8->$item;
                    $i++;
                }
            }
        return $arrayData;
    }
}
if( ! function_exists('getData11_B')){
    function getData11_B($inspection)
    {
        /** Menu 6 + Menu 4 (f11_4_2_2a ONLY)  */
        if($inspection->inspectionBusinessActivity->group_id ==1)
        { /** ===set default data for kat der ===*/
            $arr=tbl_11_1_5b();
            $arr6=getTableColumns("tbl_11_note_6", 3, 2);// tbl_14_6();
            $row=$inspection->menu11;
            $row6=$inspection->menu116;
        }
        else
        { /** ===for other category ===*/
            $arr =   otbl_11_1_5b();
            $arr6 = getTableColumns("tbl_011_6", 3, 2);// otbl_11_6();
            $row=$inspection->menu11Other;
            $row6=$inspection->menu116Other;
        }
        $arrayData=array();
        $i=0;
        if($row != null)
            if( $row->f11_4_2_2a != ""){
                $arrayData[$i]=$row->f11_4_2_2a;
                $i++;
            }
        if($row6 != null)
            foreach($arr6 as $item)
            {
                if( $row6->$item != ""){
                    $arrayData[$i]=$row6->$item;
                    $i++;
                }
            }
        return $arrayData;
    }
}
if( ! function_exists('getData11_C')){
    function getData11_C($inspection)
    {
        /** Menu 7 Only */
        if($inspection->inspectionBusinessActivity->group_id ==1)
        { /** ===set default data for kat der ===*/
            $arr7= getTableColumns("tbl_11_note_7", 3, 2);//tbl_11_7();
            $row7=$inspection->menu117;
        }
        else
        { /** ===for other category ===*/
            $arr7 = getTableColumns("tbl_011_7", 3, 2);//otbl_11_7();
            $row7=$inspection->menu117Other;
        }
        $arrayData=array();
        $i=0;
        if($row7 != null)
            foreach($arr7 as $item)
            {
                if( $row7->$item != ""){
                    $arrayData[$i]=$row7->$item;
                    $i++;
                }
            }
        return $arrayData;
    }
}
if( ! function_exists('getData11_D')){
    function getData11_D($inspection)
    {
        /** Menu 8 Only */
        if($inspection->inspectionBusinessActivity->group_id == 1)
        { /** ===set default data for kat der ===*/
            $arr8=tbl_11_8();
            $row8=$inspection->menu118;
        }
        else
        { /** ===for other category ===*/
            $arr8 = otbl_11_8();
            $row8=$inspection->menu118Other;
        }
        $arrayData=array();
        $i=0;
        if($row8 != null)
            foreach($arr8 as $item)
            {
                if( $row8->$item != ""){
                    $arrayData[$i]=$row8->$item;
                    $i++;
                }
            }
        return $arrayData;
    }
}
if( ! function_exists('getData11_E')){
    function getData11_E($inspection)
    {
        /** Menu 9 Only */
        if($inspection->inspectionBusinessActivity->group_id ==1)
        { /** ===set default data for kat der ===*/
            $arr9=tbl_11_9();
            $row8=$inspection->menu118;
        }
        else
        { /** ===for other category ===*/
            $arr9 = otbl_11_9();
            $row8=$inspection->menu118Other;
        }
        $arrayData=array();
        $i=0;
        if($row8 != null)
            foreach($arr9 as $item)
            {
                if( $row8->$item != ""){
                    $arrayData[$i]=$row8->$item;
                    $i++;
                }
            }
        return $arrayData;
    }
}
if( ! function_exists('getData11_F')){
    function getData11_F($inspection)
    {
        /** Menu 0 Only */
        if($inspection->inspectionBusinessActivity->group_id ==1)
        { /** ===set default data for kat der ===*/

            $arr = tbl_11_1_5a();
            $row=$inspection->menu11;
        }
        else
        { /** ===for other category ===*/
            $arr =   otbl_11_1_5a();
            $row=$inspection->menu11Other;
        }
        $arrayData=array();
        $i=0;
        if($row != null)
            foreach($arr as $item)
            {
                if( $row->$item != ""){
                    $arrayData[$i]=$row->$item;
                    $i++;
                }
            }
        return $arrayData;
    }
}

if( ! function_exists('getData12_A')){
    function getData12_A($inspection)
    {

        return "Testing";
    }
}
if( ! function_exists('getData12_B')){
    function getData12_B($inspection)
    {
        /** Menu 1, Menu 2, Menu 4, Menu 5, and Menu 10 */
        if($inspection->inspectionBusinessActivity->group_id ==1)
        { /** ===set default data for kat der ===*/
            $arr= tbl_12_1_5();
            $row= $inspection->menu12;
        }
        else
        { /** ===for other category ===*/
            $arr = otbl_12_1_5();
            $row=$inspection->menu12Other;
        }
        $arrayData=array();
        $i=0;
        if($row != null)
            foreach($arr as $item)
            {
                if( $row->$item != ""){
                    $arrayData[$i]=$row->$item;
                    $i++;
                }
            }
        return $arrayData;

    }
}
if( ! function_exists('getData12_C')){
    function getData12_C($inspection)
    {
        /** Menu 1, Menu 2, Menu 4, Menu 5, and Menu 10 */
        if($inspection->inspectionBusinessActivity->group_id ==1)
        { /** ===set default data for kat der ===*/
            $arr= tbl_12_7();
            $row=$inspection->menu12;
        }
        else
        { /** ===for other category ===*/
            $arr = otbl_12_7();
            $row=$inspection->menu12Other;
        }
        $arrayData=array();
        $i=0;
        if($row != null)
            foreach($arr as $item)
            {
                if( $row->$item != ""){
                    $arrayData[$i]=$row->$item;
                    $i++;
                }
            }
        return $arrayData;
    }
}

if( ! function_exists('getData14')){
    function getData14($inspection, $category_group=1)
    {
        return array_merge(
            getData14_A($inspection),
            getData14_B($inspection),
            getData14_C($inspection),
            getData14_D($inspection),
            getData14_E($inspection),
            getData14_F($inspection)
        );
    }
}
if( ! function_exists('getData14_A')){
    function getData14_A($inspection)
    {

        /** Menu 1, Menu 2, Menu 4, Menu 5, and Menu 10 */
        if($inspection->inspectionBusinessActivity->group_id == 1)
        { /** ===set default data for kat der ===*/
            $arr=tbl_14_1_5b();
            $arr10=tbl_14_10();
            $row=$inspection->menu1415;
            $row8=$inspection->menu148;

        }
        else
        { /** ===for other category ===*/
            $arr = otbl_14_1_5b();
            $arr10 = otbl_14_10();
            $row=$inspection->otherMenu1415;
            $row8=$inspection->otherMenu148;
        }
        $arrayData=array();
        $i=0;

        if($row != null)
            foreach($arr as $item)
            {
                if( $row->$item != ""){
                    $arrayData[$i]=$row->$item;
                    $i++;
                }
            }
        if($row8 != null)
            foreach($arr10 as $item)
            {
                if( $row8->$item != ""){
                    $arrayData[$i]=$row8->$item;
                    $i++;
                }
            }

        return $arrayData;
    }
}
if( ! function_exists('getData14_B')){
    function getData14_B($inspection)
    {

        /** Menu 6 + Menu 4 (f14_4_2_2a ONLY)  */
        if($inspection->inspectionBusinessActivity->group_id == 1)
        { /** ===set default data for kat der ===*/
            $arr=tbl_14_1_5b();
            $arr6=getTableColumns("tbl_14_restricted_6", 3, 2);// tbl_14_6();
            $row=$inspection->menu1415;
            $row6=$inspection->menu146;
//            $menu147=$inspection->menu147;
        }
        else
        { /** ===for other category ===*/
            $arr =   otbl_14_1_5b();
            $arr6 = getTableColumns("tbl_014_6", 3, 2);// otbl_14_6();
            $row=$inspection->otherMenu1415;
            $row6=$inspection->otherMenu146;
//            $menu147=$inspection->menu147Other;
        }
        $arrayData=array();
        $i=0;
        if($row != null)
            if( $row->f14_4_2_2a != ""){
                $arrayData[$i]=$row->f14_4_2_2a;
                $i++;
            }
        if($row6 != null)
            foreach($arr6 as $item)
            {
                if( $row6->$item != ""){
                    $arrayData[$i]=$row6->$item;
                    $i++;
                }
            }
        return $arrayData;
    }
}
if( ! function_exists('getData14_C')){
    function getData14_C($inspection)
    {
        /** Menu 7 Only */
        if($inspection->inspectionBusinessActivity->group_id ==1)
        { /** ===set default data for kat der ===*/
            $arr7= getTableColumns("tbl_14_restricted_7", 3, 2);//tbl_14_7();
            $row7=$inspection->menu147;
        }
        else
        { /** ===for other category ===*/
            $arr7 = getTableColumns("tbl_014_7", 3, 2);//otbl_14_7();
            $row7=$inspection->otherMenu147;
        }
        $arrayData=array();
        $i=0;
        if($row7 != null)
            foreach($arr7 as $item)
            {
                if( $row7->$item != ""){
                    $arrayData[$i]=$row7->$item;
                    $i++;
                }
            }
        return $arrayData;
    }
}
if( ! function_exists('getData14_D')){
    function getData14_D($inspection)
    {
        /** Menu 8 Only */
        if($inspection->inspectionBusinessActivity->group_id ==1)
        { /** ===set default data for kat der ===*/
            $arr8=tbl_14_8();
            $row8=$inspection->menu148;
        }
        else
        { /** ===for other category ===*/
            $arr8 = otbl_14_8();
            $row8=$inspection->otherMenu148;
        }
        $arrayData=array();
        $i=0;
        if($row8 != null)
            foreach($arr8 as $item)
            {
                if( $row8->$item != ""){
                    $arrayData[$i]=$row8->$item;
                    $i++;
                }
            }
        return $arrayData;
    }
}
if( ! function_exists('getData14_E')){
    function getData14_E($inspection)
    {
        /** Menu 9 Only */
        if($inspection->inspectionBusinessActivity->group_id ==1)
        { /** ===set default data for kat der ===*/
            $arr9=tbl_14_9();
            $row8=$inspection->menu14810;
        }
        else
        { /** ===for other category ===*/
            $arr9 = otbl_14_9();
            $row8=$inspection->otherMenu14810;
        }
        $arrayData=array();
        $i=0;
        if($row8 != null)
            foreach($arr9 as $item)
            {
                if( $row8->$item != ""){
                    $arrayData[$i]=$row8->$item;
                    $i++;
                }
            }
        return $arrayData;
    }
}
if( ! function_exists('getData14_F')){
    function getData14_F($inspection)
    {
        /** Menu 0 Only */
        if($inspection->inspectionBusinessActivity->group_id ==1)
        { /** ===set default data for kat der ===*/
            $arr= tbl_14_1_5a();
            $row=$inspection->menu1415;
        }
        else
        { /** ===for other category ===*/
            $arr = otbl_14_1_5a();
            $row=$inspection->otherMenu1415;
        }
        $arrayData=array();
        $i=0;
        if($row != null)
            foreach($arr as $item)
            {
                if( $row->$item != ""){
                    $arrayData[$i]=$row->$item;
                    $i++;
                }
            }
        return $arrayData;
    }
}
if( ! function_exists('getData15')){
    function getData15($inspection)
    {
        /** Menu 0 Only */
        if($inspection->inspectionBusinessActivity->group_id == 1)
        { /** ===set default data for kat der ===*/
            $arr = tbl_15();
            $row = $inspection->menu15;
        }
        else
        { /** ===for other category ===*/
            $arr =   otbl_15();
            $row=$inspection->menu15Other;
        }
        $arrayData = array();
        $i = 0;
        if($row != null)
            foreach($arr as $item)
            {
                if( $row->$item != ""){
                    $arrayData[$i]=$row->$item;
                    $i++;
                }
            }
        return $arrayData;
    }
}
if( ! function_exists('getData15A')){
    function getData15A($inspection)
    {
        /** Menu 0 Only */
        if($inspection->inspectionBusinessActivity->group_id ==1)
        { /** ===set default data for kat der ===*/
            $arr=tbl_15A();
            $row=$inspection->menu15;
        }
        else
        { /** ===for other category ===*/

            $arr = otbl_15();
            $row = $inspection->otherMenu15;
        }
        $arrayData=array();
        $i=0;
        if($row != null)
            foreach($arr as $item)
            {
                if( $row->$item != ""){
                    $arrayData[$i]=$row->$item;
                    $i++;
                }
            }
        return $arrayData;
    }
}
if( ! function_exists('getData15B')){
    function getData15B($inspection)
    {
        /** Menu 0 Only */
        if($inspection->inspectionBusinessActivity->group_id ==1)
        { /** ===set default data for kat der ===*/
            $arr=tbl_15B();
            $row=$inspection->menu15;
        }
        else
        { /** ===for other category ===*/
            $arr =   otbl_15();
            $row=$inspection->otherMenu15;
        }
        $arrayData=array();
        $i=0;
        if($row != null)
            foreach($arr as $item)
            {
                if( $row->$item != ""){
                    $arrayData[$i]=$row->$item;
                    $i++;
                }
            }
        return $arrayData;
    }
}
if( ! function_exists('getData15C')){
    function getData15C($inspection)
    {
        /** Menu 0 Only */
        if($inspection->inspectionBusinessActivity->group_id ==1)
        { /** ===set default data for kat der ===*/
            $arr=tbl_15C();
            $row=$inspection->menu15;
        }
        else
        { /** ===for other category ===*/
            $arr =   otbl_15();
            $row=$inspection->otherMenu15;
        }
        $arrayData=array();
        $i=0;
        if($row != null)
            foreach($arr as $item)
            {
                if( $row->$item != ""){
                    $arrayData[$i]=$row->$item;
                    $i++;
                }
            }
        return $arrayData;
    }
}
if( ! function_exists('getData15D')){
    function getData15D($inspection)
    {
        /** Menu 0 Only */
        if($inspection->inspectionBusinessActivity->group_id ==1)
        { /** ===set default data for kat der ===*/
            $arr=tbl_15D();
            $row=$inspection->menu15;
        }
        else
        { /** ===for other category ===*/
            $arr =   otbl_15();
            $row=$inspection->otherMenu15;
        }
        $arrayData=array();
        $i=0;
        if($row != null)
            foreach($arr as $item)
            {
                if( $row->$item != ""){
                    $arrayData[$i]=$row->$item;
                    $i++;
                }
            }
        return $arrayData;
    }
}
if( ! function_exists('getData15E')){
    function getData15E($inspection)
    {
        /** Menu 0 Only */
        if($inspection->inspectionBusinessActivity->group_id ==1)
        { /** ===set default data for kat der ===*/
            $arr=tbl_15E();
            $row=$inspection->menu15;
        }
        else
        { /** ===for other category ===*/
            $arr =   otbl_15();
            $row=$inspection->otherMenu15;
        }
        $arrayData=array();
        $i=0;
        if($row != null)
            foreach($arr as $item)
            {
                if( $row->$item != ""){
                    $arrayData[$i]=$row->$item;
                    $i++;
                }
            }
        return $arrayData;
    }
}


if( ! function_exists('getData15F')){
    function getData15F($inspection)
    {
        /** Menu 0 Only */
        if($inspection->inspectionBusinessActivity->group_id ==1)
        { /** ===set default data for kat der ===*/
            $arr=tbl_15F();
            $row=$inspection->menu15F;
        }
        else
        { /** ===for other category ===*/
            $arr =   otbl_15F();
            $row=$inspection->otherMenu15F;
        }
        $arrayData=array();
        $i=0;
        if($row != null)
            foreach($arr as $item)
            {
                if( $row->$item != ""){
                    $arrayData[$i]=$row->$item;
                    $i++;
                }
            }
        return $arrayData;
    }
}


if( ! function_exists('getDateProvinceAsKhmer')){
    function getDateProvinceAsKhmer($date_bottom="", $company_province_id=0)
    {
        $company_province_name="";
//        if($company_province_id != 0){
//            $company_province_name=ProvinceName($company_province_id);
//            if($company_province_id == 12 ) $company_province_name=__("general.rest_capital");//if PP
//        }

        $day="";
        $month="";
        $year="";
        //=============Bottom Date
        if($date_bottom !="" || $date_bottom != 0){
            $day= Num2Unicode(date_format(date_create($date_bottom), "d"));
            $month= date_format(date_create($date_bottom), "m");
            $year= Num2Unicode(date_format(date_create($date_bottom), "Y"));
        }

        $arr_result["company_province_name"]= $company_province_name;
        $arr_result["day"]= $day;
        $arr_result["month"]= $month;
        $arr_result["year"]= $year;

        return $arr_result;
    }
}

if( ! function_exists('getDateTimeAsWord')){
    function getDateTimeAsWord($date="", $time="")
    {
        $txt_date="";
        $txt_time="";
        if($date!=""){
            $sdate = explode('-', $date);
            $txt_date = __('general.year');
            $txt_time = __('general.start_time');

            //year 2010
            for($i=2010; $i<=2040; $i++){
                if($sdate[0] == $i){
                    $txt_date=__('general.word_'.$i);
                    break;
                }
            }

            //month
            $txt_date.=" ".__("general.month").__("general.month_".$sdate[1]);
            $txt_date.=" ".__("general.day");
            //day
            $split_day= str_split($sdate[2]);
            for($i=0; $i<sizeof($split_day); $i++){
                if($i==0){
                    if($split_day[$i]>0)
                        $txt_date.=__('general.txt_len_2_'.$split_day[$i]);
                }
                else{
                    $txt_date.=__('general.txt_'.$split_day[$i]);
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
                $txt_time.=__('general.txt_10');
            }
            else{
                $split_time= str_split($stime[0]);
                for($i=0; $i<sizeof($split_time); $i++){
                    if($i==0){
                        if($split_time[$i]>0)
                            $txt_time.=__('general.txt_len_2_'.$split_time[$i]);
                    }
                    else{
                        $txt_time.=__('general.txt_'.$split_time[$i]);
                    }

                }
            }

            //minute
            $txt_time.=__("general.and");
            if($stime[1] == 10){
                $txt_time.=__('general.txt_len_2_1');
            }
            elseif($stime[1] == 20){
                $txt_time.=__('general.txt_len_2_2');
            }
            elseif($stime[1] == 30){
                $txt_time.=__('general.txt_len_2_3');
            }
            elseif($stime[1] == 40){
                $txt_time.=__('general.txt_len_2_4');
            }
            elseif($stime[1] == 50){
                $txt_time.=__('general.txt_len_2_5');
            }
            else{
                $split_time= str_split($stime[1]);
                for($i=0; $i<sizeof($split_time); $i++){
                    if($i==0){
                        if($split_time[$i]>0)
                            $txt_time.=__('general.txt_len_2_'.$split_time[$i]);
                    }
                    else{
                        $txt_time.=__('general.txt_'.$split_time[$i]);
                        //if($split_time[$i] == 0)
                        //   $txt_time.=lang('txt_0');
                    }

                }
            }

            $txt_time.=__("general.minute");

        }// end if time

        $arr_result= array();
        $arr_result["txt_date"]=$txt_date;
        $arr_result["txt_time"]=$txt_time;
        return $arr_result;
    }
}
if( ! function_exists('exportMenu0')){
    function exportMenu0($inspection, $phpWord)
    {
        //dd($inspection->officer);
        //$officers = $inspection->officer;
        $officers = getOfficer($inspection->id);

        //dd($officers);
        //======1.Setting
        $checked = '<w:sym w:font="Wingdings" w:char="F0FE"/>';
        $unchecked = '<w:sym w:font="Wingdings" w:char="F0A8"/>';
        $arr_a=array();
        for($i=0;$i<=25;$i++){
            $arr_a[$i]=$unchecked;
        }

        $i=1;
        $j=0;
        $officername="";
        $offname= array();
        $offname_last= array();
        $offname2= array();
        foreach($officers as $rowo){
            $role=__('general.menu_1_member');
            if($rowo->officer_role == 1) $role=__('general.menu_1_leader');
            elseif($rowo->officer_role == 2) $role=__('general.menu_1_leader_sub');

            $officername=xmlEntities($rowo->officer_name);
            $offname[$j]= array(
                "offname"=> Num2Unicode($i). "-". __('general.officer_role_label'). $officername,
                "offid" => __('general.menu_1_officer_id')." ". Num2Unicode($rowo->officer_id),
                "offdepartment"=> __('general.menu_1_officer_department')." ". $rowo->officer_department ,
                "offrole"=> $role
            );
            $offname_last[$j]= array("offname_last"=> Num2Unicode($i)."-". $officername, "role_last"=> "...................");
            //$phpWord->setValue('officer'.$i, $officername);
            $i++;
            $j++;
        }
        //dd($phpWord);
        //dd($inspection->officer);
        //$phpWord->setValue('officername', $officername);
        $phpWord->cloneRowAndSetValues('offname', $offname);
        $phpWord->cloneRowAndSetValues('offname_last', $offname_last);
        //$phpWord->cloneRowAndSetValues('offtest', $offname);
        //======For insp_type=3
        $special="";
        $ref1= __('general.k_ref2');
        $ref2=__('general.k_ref4');

        if($inspection->insp_type == 3){
            $special="".__('general.k_special');
            $ref1= "";
            $ref2= "";
        }

        $phpWord->setValue('special', $special);
        $phpWord->setValue('ref1', $ref1);
        $phpWord->setValue('ref2', $ref2);
    }
}
function exportMenu1($inspection, $phpWord)
{
    $checked = '<w:sym w:font="Wingdings" w:char="F0FE"/>';
    $unchecked = '<w:sym w:font="Wingdings" w:char="F0A8"/>';
    $arr_a=array();
    for($i=0;$i<=25;$i++){
        $arr_a[$i]=$unchecked;
    }
    //=============Inspection Date, Time
    //$group_id = $row->inspectionBusinessActivity->group_id;
    $row = $inspection->menu1;

//        dd($row->businessProvince);
    $company_id=$row->company_id;
    $insp_date=$row->insp_date;
    $insp_start_time=$row->insp_start_time;
    $insp_end_time=$row->insp_end_time;
    $province_id=$row->business_province;

    $arr_datetime=getDateTimeAsWord($row->insp_date, $row->insp_start_time);
    $phpWord->setValue('insp_date', $arr_datetime["txt_date"]);
    $phpWord->setValue('insp_stime', $arr_datetime["txt_time"]);
    $phpWord->setValue('insp_group', Num2Unicode($row->group_name));
    $phpWord->setValue('com_name_kh', xmlEntities($row->company_name_khmer));
    $phpWord->setValue('com_tin', Num2Unicode($row->company_tin));

    $phpWord->setValue('com_license_no', Num2Unicode($row->company_register_number));
    $arr_date=getDateProvinceAsKhmer($row->registration_date);
    $regMonth= $arr_date["month"] != null? __("general.month_".$arr_date["month"]) : "";
    $phpWord->setValue('com_license_day', $arr_date["day"]);
    $phpWord->setValue('com_license_month', $regMonth);
    $phpWord->setValue('com_license_year', $arr_date["year"]);

    $bus_activity="....................................................................";
    $phpWord->setValue('bus_activity', $bus_activity);
    $phpWord->setValue('business_activity', $inspection->inspectionBusinessActivity->bus_khmer_name);
//        businessActivityName($row->bus_id)
    //$phpWord->setValue('com_license_by',$row->com_license_by);

    $com_com_statute_have=5;
    for($i=1;$i<=2;$i++){
        if($com_com_statute_have == $i)
            $phpWord->setValue('com_com_statute'.$i, $checked);
        else
            $phpWord->setValue('com_com_statute'.$i, $unchecked);
    }
    //dd($row->commune->com_khname);
    $phpWord->setValue('com_1_1_addr_house_no', xmlEntities(Num2Unicode($row->business_house_no)));
    $phpWord->setValue('com_1_1_addr_street', xmlEntities(Num2Unicode($row->business_street)));
    $phpWord->setValue('com_1_1_addr_group', xmlEntities(Num2Unicode($row->business_group)));
    $phpWord->setValue('com_1_1_addr_village', $row->village->vil_khname);
    $phpWord->setValue('com_1_1_addr_commune', $row->commune->com_khname);
    $phpWord->setValue('com_1_1_addr_district', $row->district->dis_khname);
    $phpWord->setValue('com_1_1_addr_province', $row->province->pro_khname);
    $phpWord->setValue('com_1_1_phone', Num2Unicode($row->company_phone_number));
    $phpWord->setValue('com_1_1_email', $row->company_email);

    $phpWord->setValue('com_1_1_owner_name', xmlEntities($row->owner_khmer_name));
    $phpWord->setValue('com_1_1_owner_nationality', $row->ownerNationality->nationality_kh);

    $phpWord->setValue('com_1_1_ceo_name', xmlEntities($row->director_khmer_name));
    $phpWord->setValue('com_1_1_ceo_nationality', $row->directorNationality->nationality_kh);


    $enterprise_name= $row->enterprise_name != ""? xmlEntities($row->enterprise_name): ".....................................................................";
    $phpWord->setValue('com_1_1_enterprise_name', $enterprise_name);
    //dd($row->villageEnterprise);
    $enterprise_address=__('g1.enterprise_addr2')." ";
    if($row->enterprise_addr == 1){//local
        $str="...........";
        if($row->enterprise_house_no !="")
            $str= Num2Unicode($row->enterprise_house_no);
        $enterprise_address.= __('g1.enterprise_house_no')." ".$str;
        $str="...........";
        if($row->enterprise_street !="")
            $str= Num2Unicode($row->enterprise_street);
        $enterprise_address.= __('g1.enterprise_street')." ".$str;
        $str="...........";
        if($row->enterprise_krom !="")
            $str= Num2Unicode($row->enterprise_krom);
        $enterprise_address.= __('g1.enterprise_group')." ".$str;

        if(!empty($row->villageEnterprise)){
            $enterprise_address.= " ".__('g1.enterprise_village')." ".$row->villageEnterprise->vil_khname;
        }
        if(!empty($row->communeEnterprise)){
            $enterprise_address.= " ".__('g1.enterprise_commune')." ".$row->communeEnterprise->com_khname;
        }
        if(!empty($row->districtEnterprise)){
            $enterprise_address.= " ".__('g1.enterprise_district')." ".$row->districtEnterprise->dis_khname;
        }
        if(!empty($row->provinceEnterprise)){
            $enterprise_address.= " ".__('g1.enterprise_province')." ".$row->provinceEnterprise->pro_khname;
        }
    }
    else{//abroad
        $enterprise_address.= $row->enterprise_abroad;
    }
    $phpWord->setValue('enterprise_address', xmlEntities($enterprise_address));


    /** ==============Karey============== */
    for($i=1;$i<=2;$i++){
        if($row->com_1_1_karey_reg == $i)
            $phpWord->setValue('karey'.$i, $checked);
        else
            $phpWord->setValue('karey'.$i, $unchecked);
    }

    $karey_name="";
//        $qk=$this->main->select_many_record("tbl_1_karey", "karey_name, karey_contract", array('inspection_id' => $inspection_id));
//        if($inspection->karey != null)
//            dd("HEllo");
    //dd($inspection->karey);
    $br = "</w:t><w:br/><w:t>";
    $karey_name = "";
    if($row->karey != null){
        $i=1;
        foreach($row->karey as $rowk){
            $karey_contract= $unchecked." ".__('general.k_have')."  ".$unchecked." ".__('general.k_none');
            if($rowk->karey_contract == 1){
                $karey_contract= $checked." ".__('general.k_have')."  ".$unchecked." ".__('general.k_none');
            }
            elseif($rowk->karey_contract == 2){
                $karey_contract= $unchecked." ".__('general.k_have')."  ".$checked." ".__('general.k_none');
            }
            $karey_name.=Num2Unicode($i).".".__("general.k_name")." ".xmlEntities($rowk->karey_name);
            $karey_name.="     ".__('general.karey_contract')."   ".$karey_contract;
            $karey_name.="<w:br/>";
            $i++;
        }
    }

    if($karey_name != null){
        $karey_name = $br.substr($karey_name, 0, -7);
    }
    $phpWord->setValue('karey_name', $karey_name);


    for($i=1;$i<=2;$i++){
        if($row->karey_com == $i)
            $phpWord->setValue('karey_com'.$i, $checked);
        else
            $phpWord->setValue('karey_com'.$i, $unchecked);
    }
    $str=".............................................";
    if($row->karey_com_name != "")
        $str=xmlEntities($row->karey_com_name);
    $phpWord->setValue('karey_com_name', $str);
    $str=".............................................";
    if($row->karey_com_brand != "")
        $str=xmlEntities($row->karey_com_brand);
    $phpWord->setValue('karey_com_brand', $str);
    $str="......................";
    if($row->main_product != "")
        $str= xmlEntities($row->main_product);
    $phpWord->setValue('main_product', $str);

    //$str="................................";
    //dd(xmlEntities(companyBrand($inspection->id, $row->brand_product)));
    $str= xmlEntities(companyBrand($inspection->id, $row->brand_product));
    $phpWord->setValue('brand_product', $str);

    $str="................................";
    if($row->country_product_out != "")
        $str= xmlEntities($row->country_product_out);
    $phpWord->setValue('country_product_out', $str);
    /** Member of Associate 23-11-2021 */
    for($i=1;$i<=2;$i++){
        if($row->memberof_associate == $i)
            $phpWord->setValue('memberof_associate'.$i, $checked);
        else
            $phpWord->setValue('memberof_associate'.$i, $unchecked);
    }
    if($row->memberof_camfeba == 1)
        $phpWord->setValue('memberof_camfeba', $checked);
    else
        $phpWord->setValue('memberof_camfeba', $unchecked);

    if($row->memberof_gmac == 1)
        $phpWord->setValue('memberof_gmac', $checked);
    else
        $phpWord->setValue('memberof_gmac', $unchecked);

    if($row->memberof_cfa == 1)
        $phpWord->setValue('memberof_cfa', $checked);
    else
        $phpWord->setValue('memberof_cfa', $unchecked);

    if($row->memberof_other == 1)
        $phpWord->setValue('memberof_other', $checked);
    else
        $phpWord->setValue('memberof_other', $unchecked);
    $phpWord->setValue('memberof_other_text', $row->memberof_other_text);

    /** ========================= */
//        dd($inspection->company->article->article_name);
//        $article_of_company=articleOfCompany($row->article_of_company);
    $phpWord->setValue('article_of_company', $row->articleOfCompany->article_name);

    for($i=1;$i<=11;$i++){
        if($row->com_1_1_weekly_leave_id == $i){
            $phpWord->setValue('weekly_leave_'.$i, $checked);
        }
        else $phpWord->setValue('weekly_leave_'.$i, $unchecked);
    }

    for($i=1;$i<=3;$i++){
        if($row->last_update_info == $i)
            $phpWord->setValue('last_update'.$i, $checked);
        else
            $phpWord->setValue('last_update'.$i, $unchecked);
    }
    /** =======Menu 1.2======================== */

    $arr121=array();
    $arr121[0]=array("1_2_1_name"=>"", "1_2_1_nat"=>"", "1_2_1_card"=>"", "1_2_1_add"=> "", "1_2_1_share"=> "");
//        $query_compos=$this->main->select_many_record("tbl_1_2_company_composition", "*", array('inspection_id'=>$inspection_id, 'comc_typeof_composition'=>1));
    $query_compos= Table1CompanyComposition::where(["inspection_id" => $inspection->id, "comc_typeof_composition" => 1])->get();
//        dd(count($query_compos));
//        $num_compos= count($query_compos);
    if(count($query_compos) > 0){
        $j=0;
        $i=1;
        foreach($query_compos as $rowc){
//                $qn= Nationality::where("id", $rowc->comc_com_nationality)->first();
//                $nationality = count($qn) > 0 ? $qn->nationality_kh : "";
            $arr121[$j] = array(
                "1_2_1_name" => Num2Unicode($i).". ". xmlEntities($rowc->comc_com_fullname),
                "1_2_1_nat" => getNationality($rowc->comc_com_nationality),
                "1_2_1_card" => Num2Unicode($rowc->comc_com_id_card_no),
                "1_2_1_add" => xmlEntities($rowc->comc_com_address),
                "1_2_1_share" => Num2Unicode($rowc->comc_com_numof_shares));
            $j++;
            $i++;
        }
    }
    $phpWord->cloneRowAndSetValues('1_2_1_name', $arr121);

    /** =======Menu 1.2======================== */

    $m122=array();
    $m122[0]=array("m122_name"=>"", "m122_nat"=>"", "m122_card"=>"", "m122_position"=> "", "m122_add"=> "");
    $query_compos2=Table1CompanyComposition::where(["inspection_id" => $inspection->id, "comc_typeof_composition" => 2])->get();
//        $num_compos2=$query_compos2->num_rows();
    if(count($query_compos2) > 0){
        $i=1;
        $j=0;
        foreach($query_compos2 as $rowc2){

            $m122[$j]=array(
                "m122_name"=>Num2Unicode($i).". ". xmlEntities($rowc2->comc_com_fullname),
                "m122_nat"=> getNationality($rowc2->comc_com_nationality),
                "m122_card"=>Num2Unicode($rowc2->comc_com_id_card_no),
                "m122_position"=>$rowc2->comc_com_role,
                "m122_add"=>xmlEntities(Num2Unicode($rowc2->comc_com_address))
            );
            $i++;
            $j++;
        }
    }
    $phpWord->cloneRowAndSetValues('m122_name', $m122);

}
function exportMenu2($inspection, $phpWord)
{
    $checked = '<w:sym w:font="Wingdings" w:char="F0FE"/>';
    $unchecked = '<w:sym w:font="Wingdings" w:char="F0A8"/>';
    $arr_a=array();
    for($i=0;$i<=25;$i++){
        $arr_a[$i]=$unchecked;
    }
    /** ==============Menu 2===========================*/
    $str="";
    $row2=$inspection->menu2;
    if($row2->admin_2_1_date_open !="0000-00-00"){
        $arr_date=getDateProvinceAsKhmer($row2->admin_2_1_date_open);
        $str=__('general.k_date3').$arr_date['day']."  ";
        $str.=__('general.k_month').__("general.month_".$arr_date["month"])."  ";
        $str.=__('general.k_year').$arr_date['year'];
    }
    $phpWord->setValue('m2_2_1', $str);
    $str=$unchecked." ".__('general.k_have')."  ";
    $str.=$unchecked." ".__('general.k_none')."  ";

    for($i=1;$i<=2;$i++){
        if($row2->admin_2_2_1 == $i)
            $phpWord->setValue('m2_2_1'.$i, $checked);
        else
            $phpWord->setValue('m2_2_1'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row2->admin_2_2_2 == $i)
            $phpWord->setValue('m2_2_2'.$i, $checked);
        else
            $phpWord->setValue('m2_2_2'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row2->admin_2_2_3 == $i)
            $phpWord->setValue('m2_2_3'.$i, $checked);
        else
            $phpWord->setValue('m2_2_3'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row2->admin_2_2_3a == $i)
            $phpWord->setValue('m2_2_3a'.$i, $checked);
        else
            $phpWord->setValue('m2_2_3a'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row2->admin_2_2_4 == $i)
            $phpWord->setValue('m2_2_4'.$i, $checked);
        else
            $phpWord->setValue('m2_2_4'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row2->admin_2_2_5 == $i)
            $phpWord->setValue('m2_2_5'.$i, $checked);
        else
            $phpWord->setValue('m2_2_5'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row2->admin_2_2_6 == $i)
            $phpWord->setValue('m2_2_6'.$i, $checked);
        else
            $phpWord->setValue('m2_2_6'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row2->admin_2_2_7 == $i)
            $phpWord->setValue('m2_2_7'.$i, $checked);
        else
            $phpWord->setValue('m2_2_7'.$i, $unchecked);
    }

    if($inspection->insp_type > 0)
    {
        for($i=1;$i<=4;$i++){
            if($row2->admin_2_conclusion == $i){
                $phpWord->setValue('m2_con'.$i, $checked);
            }
            else $phpWord->setValue('m2_con'.$i, $unchecked);
        }

        $str=__('g2.2_ocomment_sample')." ".__('g2.2_ocomment_sample2');
        if($row2->admin_officer_comment != ""){
            $str= textAreaForDisplay($row2->officer_comment);
        }
        $phpWord->setValue('m2_comment', $str);
    }

}
function exportMenu3($inspection, $phpWord)
{
    $checked = '<w:sym w:font="Wingdings" w:char="F0FE"/>';
    $unchecked = '<w:sym w:font="Wingdings" w:char="F0A8"/>';
    $arr_a=array();
    for($i=0;$i<=25;$i++){
        $arr_a[$i]=$unchecked;
    }
    /** ==============Menu 3===========================*/
    $total_emp=0;
    $totalEmployeeKhmer=0;
    $totalEmployeeFor=0;
    $row=$inspection->menu3;
    $totalEmployeeKhmer=$row->emp_3_1_kh_total;
    $totalEmployeeFor=$row->emp_3_1_for_total;
    $total_emp=$totalEmployeeKhmer + $totalEmployeeFor;

    $phpWord->setValue('m3_total_kh', Num2Unicode($row->emp_3_1_kh_total));
    $phpWord->setValue('m3_total_kh_f', Num2Unicode($row->emp_3_1_kh_female));
    $phpWord->setValue('m3_total_for', Num2Unicode($row->emp_3_1_for_total));
    $phpWord->setValue('m3_total_for_f', Num2Unicode($row->emp_3_1_for_female));
    $phpWord->setValue('m3_total_all', Num2Unicode($total_emp));
    $phpWord->setValue('m3_total_all_f', Num2Unicode($row->emp_3_1_kh_female+$row->emp_3_1_for_female));

    $phpWord->setValue('m3_total_12t', Num2Unicode($row->emp_3_2_12_15y_total));
    $phpWord->setValue('m3_total_12t_f', Num2Unicode($row->emp_3_2_12_15y_female));
    $phpWord->setValue('m3_total_15t', Num2Unicode($row->emp_3_2_15_18y_total));
    $phpWord->setValue('m3_total_15t_f', Num2Unicode($row->emp_3_2_15_18y_female));
    $phpWord->setValue('m3_total_18t', Num2Unicode($row->emp_3_2_from_18y_total));
    $phpWord->setValue('m3_total_18t_f', Num2Unicode($row->emp_3_2_from_18y_female));

    $phpWord->setValue('m3_leader_total', Num2Unicode($row->emp_3_3_leader_total));
    $phpWord->setValue('m3_leader_f', Num2Unicode($row->emp_3_3_leader_female));
    $phpWord->setValue('m3_leader_dis', Num2Unicode($row->emp_3_3_leader_disabled));
    $phpWord->setValue('m3_leader_for', Num2Unicode($row->emp_3_3_leader_for));
    $phpWord->setValue('m3_leader_for_f', Num2Unicode($row->emp_3_3_leader_for_female));

    $phpWord->setValue('m3_supervior_total', Num2Unicode($row->emp_3_3_supervior_total));
    $phpWord->setValue('m3_supervior_f', Num2Unicode($row->emp_3_3_supervior_female));
    $phpWord->setValue('m3_supervior_dis', Num2Unicode($row->emp_3_3_supervior_disabled));
    $phpWord->setValue('m3_supervior_for', Num2Unicode($row->emp_3_3_supervior_for));
    $phpWord->setValue('m3_supervior_for_f', Num2Unicode($row->emp_3_3_supervior_for_female));

    $phpWord->setValue('m3_office_total', Num2Unicode($row->emp_3_3_office_total));
    $phpWord->setValue('m3_office_f', Num2Unicode($row->emp_3_3_office_female));
    $phpWord->setValue('m3_office_dis', Num2Unicode($row->emp_3_3_office_disabled));
    $phpWord->setValue('m3_office_for', Num2Unicode($row->emp_3_3_office_for));
    $phpWord->setValue('m3_office_for_f', Num2Unicode($row->emp_3_3_office_for_female));

    $phpWord->setValue('m3_expert_total', Num2Unicode($row->emp_3_3_expert_total));
    $phpWord->setValue('m3_expert_f', Num2Unicode($row->emp_3_3_expert_female));
    $phpWord->setValue('m3_expert_dis', Num2Unicode($row->emp_3_3_expert_disabled));
    $phpWord->setValue('m3_expert_for', Num2Unicode($row->emp_3_3_expert_for));
    $phpWord->setValue('m3_expert_for_f', Num2Unicode($row->emp_3_3_expert_for_female));

    $phpWord->setValue('m3_worker_total', Num2Unicode($row->emp_3_3_worker_total));
    $phpWord->setValue('m3_worker_f', Num2Unicode($row->emp_3_3_worker_female));
    $phpWord->setValue('m3_worker_dis', Num2Unicode($row->emp_3_3_worker_disabled));
    $phpWord->setValue('m3_worker_for', Num2Unicode($row->emp_3_3_worker_for));
    $phpWord->setValue('m3_worker_for_f', Num2Unicode($row->emp_3_3_worker_for_female));

    for($i=1;$i<=2;$i++){
        if($row->emp_3_4_1_a == $i)
            $phpWord->setValue('m3_4_1_a'.$i, $checked);
        else
            $phpWord->setValue('m3_4_1_a'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->emp_3_4_1_b == $i)
            $phpWord->setValue('m3_4_1_b'.$i, $checked);
        else
            $phpWord->setValue('m3_4_1_b'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->emp_3_4_1_c == $i)
            $phpWord->setValue('m3_4_1_c'.$i, $checked);
        else
            $phpWord->setValue('m3_4_1_c'.$i, $unchecked);
    }

    $str="";//lang('dot_sample1')." ".lang('dot_sample2');
    if($row->emp_3_4_1_d_text != ""){
        $str=$row->emp_3_4_1_d_text;
    }
    $phpWord->setValue('m3_4_1d', $str);

    for($i=1;$i<=2;$i++){
        if($row->emp_3_4_2 == $i)
            $phpWord->setValue('m3_4_2'.$i, $checked);
        else
            $phpWord->setValue('m3_4_2'.$i, $unchecked);
    }

    for($i=1;$i<=9;$i++){
        $var="emp_3_4_2_".$i;
        if($row->$var == 1){
            $phpWord->setValue('m3_4_2_'.$i, $checked);
        }
        else $phpWord->setValue('m3_4_2_'.$i, $unchecked);
    }
    $str="";//lang('dot_sample4')." ".lang('dot_sample2');
    if($row->emp_3_4_2_8 ==1)
        if($row->emp_3_4_2_8_text != "")
            $str=$row->emp_3_4_2_8_text;
    $phpWord->setValue('m3_4_2_8text', $str);

    for($i=1;$i<=2;$i++){
        if($row->emp_3_6 == $i)
            $phpWord->setValue('m3_6'.$i, $checked);
        else
            $phpWord->setValue('m3_6'.$i, $unchecked);
    }
    $arr_m3_6=array();
    for($j=1;$j<=6;$j++)
        for($i=1;$i<=4;$i++){
            $arr_m3_6[$j][$i]="";
        }
    if($row->emp_3_6 == 1){//have
        $arr_m3_6[1][1]=$row->emp_3_6_1_kh_total;
        $arr_m3_6[1][2]=$row->emp_3_6_1_kh_female;
        $arr_m3_6[1][3]=$row->emp_3_6_1_for_total;
        $arr_m3_6[1][4]=$row->emp_3_6_1_for_female;

        $arr_m3_6[2][1]=$row->emp_3_6_2_kh_total;
        $arr_m3_6[2][2]=$row->emp_3_6_2_kh_female;
        $arr_m3_6[2][3]=$row->emp_3_6_2_for_total;
        $arr_m3_6[2][4]=$row->emp_3_6_2_for_female;

        $arr_m3_6[3][1]=$row->emp_3_6_3_kh_total;
        $arr_m3_6[3][2]=$row->emp_3_6_3_kh_female;
        $arr_m3_6[3][3]=$row->emp_3_6_3_for_total;
        $arr_m3_6[3][4]=$row->emp_3_6_3_for_female;

        $arr_m3_6[4][1]=$row->emp_3_6_4_kh_total;
        $arr_m3_6[4][2]=$row->emp_3_6_4_kh_female;
        $arr_m3_6[4][3]=$row->emp_3_6_4_for_total;
        $arr_m3_6[4][4]=$row->emp_3_6_4_for_female;

        $arr_m3_6[5][1]=$row->emp_3_6_5_kh_total;
        $arr_m3_6[5][2]=$row->emp_3_6_5_kh_female;
        $arr_m3_6[5][3]=$row->emp_3_6_5_for_total;
        $arr_m3_6[5][4]=$row->emp_3_6_5_for_female;

        $arr_m3_6[6][1]=$row->emp_3_6_1_kh_total+$row->emp_3_6_2_kh_total+$row->emp_3_6_3_kh_total+$row->emp_3_6_4_kh_total+$row->emp_3_6_5_kh_total;
        $arr_m3_6[6][2]=$row->emp_3_6_1_kh_female+$row->emp_3_6_2_kh_female+$row->emp_3_6_3_kh_female+$row->emp_3_6_4_kh_female+$row->emp_3_6_5_kh_female;
        $arr_m3_6[6][3]=$row->emp_3_6_1_for_total+$row->emp_3_6_2_for_total+$row->emp_3_6_3_for_total+$row->emp_3_6_4_for_total+$row->emp_3_6_5_for_total;
        $arr_m3_6[6][4]=$row->emp_3_6_1_for_female+$row->emp_3_6_2_for_female+$row->emp_3_6_3_for_female+$row->emp_3_6_4_for_female+$row->emp_3_6_5_for_female;
    }

    for($j=1;$j<=6;$j++)
        for($i=1;$i<=4;$i++){
            $phpWord->setValue('m6_'.$j.'_'.$i, Num2Unicode($arr_m3_6[$j][$i]));
        }

}
function exportMenu4($inspection, $phpWord)
{
    $checked = '<w:sym w:font="Wingdings" w:char="F0FE"/>';
    $unchecked = '<w:sym w:font="Wingdings" w:char="F0A8"/>';
    $arr_a=array();
    for($i=0;$i<=25;$i++){
        $arr_a[$i]=$unchecked;
    }
    $menu3=$inspection->menu3;
    $total_emp=$menu3->emp_3_1_kh_total + $menu3->emp_3_1_for_total;
    /** ==============Menu 4===========================*/
    $row=$inspection->menu4;
    $phpWord->setValue('m4_1', $row->iplaw_4_1_1n);
    for($i=1;$i<=2;$i++){
        if($row->iplaw_4_1_1 == $i)
            $phpWord->setValue('m4_1_1'.$i, $checked);
        else
            $phpWord->setValue('m4_1_1'.$i, $unchecked);
    }

    $str2=".................";
    $str3=".................";
    $str=$unchecked;
    $str=$checked;
    $iplaw_4_2_1_num_per=0;
    if($row->iplaw_4_2_1_num > 0){
        $str2=$row->iplaw_4_2_1_num;
        if($total_emp == 0) $str3 = 0;
        else{
            $iplaw_4_2_1_num_per=number_format(($row->iplaw_4_2_1_num * 100)/$total_emp, 2);
            $str3= $iplaw_4_2_1_num_per;

        }

    }
    //$phpWord->setValue('m4_2_1', $str);
    $phpWord->setValue('m4_2_1n', Num2Unicode($str2));
    $phpWord->setValue('m4_2_1p', Num2Unicode($str3));

    $str4=$str3;
    $str2=".................";
    $str3=".................";
    $str=$unchecked;
    //if($row->iplaw_4_2_2 ==1){
    //$total_emp
    $str=$checked;
    $iplaw_4_2_2_num =  $total_emp - $row->iplaw_4_2_1_num;
    if($iplaw_4_2_2_num > 0){
        $str2=$iplaw_4_2_2_num;
        if(is_numeric($str4))
            $str3= 100-$str4;
        else {
            if($total_emp == 0) $str3 = 0;
            else{
                $iplaw_4_2_2_num_per= 100- $iplaw_4_2_1_num_per;
                $str3= number_format($iplaw_4_2_2_num_per, 2);
            }

        }
    }
    //}
    $phpWord->setValue('m4_2_2', $str);
    $phpWord->setValue('m4_2_2n', Num2Unicode($str2));
    $phpWord->setValue('m4_2_2p', Num2Unicode($str3));

    for($i=1;$i<=2;$i++){
        if($row->iplaw_4_2_2a == $i)
            $phpWord->setValue('m4_2_2a'.$i, $checked);
        else
            $phpWord->setValue('m4_2_2a'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->iplaw_4_2_3 == $i)
            $phpWord->setValue('m4_2_3'.$i, $checked);
        else
            $phpWord->setValue('m4_2_3'.$i, $unchecked);
    }

    $str="......................................";
    if($row->iplaw_4_2_3_if_have !="")
        $str=$row->iplaw_4_2_3_if_have;
    $phpWord->setValue('m4_2_3t', $str);




    for($i=1;$i<=2;$i++){
        if($row->iplaw_4_2_4 == $i)
            $phpWord->setValue('m4_2_4'.$i, $checked);
        else
            $phpWord->setValue('m4_2_4'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->iplaw_4_2_4_1 == $i)
            $phpWord->setValue('m4_2_4_1'.$i, $checked);
        else
            $phpWord->setValue('m4_2_4_1'.$i, $unchecked);
    }


    $str="........";
    if($row->iplaw_4_2_4_1totale > 0)
        $str=$row->iplaw_4_2_4_1totale;
    $phpWord->setValue('m4_2_4_1totale', Num2Unicode($str));
    $str="........";
    if($row->iplaw_4_2_4_1totalf > 0)
        $str=$row->iplaw_4_2_4_1totalf;
    $phpWord->setValue('m4_2_4_1totalf', Num2Unicode($str));
    $str="........";
    if($row->iplaw_4_2_4_1sdate != "0000-00-00")
        $str= date2Display($row->iplaw_4_2_4_1sdate);
    $phpWord->setValue('m4_2_4_1sdate', $str);
    $str="........";
    if($row->iplaw_4_2_4_1edate != "0000-00-00")
        $str= date2Display($row->iplaw_4_2_4_1edate);
    $phpWord->setValue('m4_2_4_1edate', $str);
    $str="........";
    if($row->iplaw_4_2_4_1sdate != "0000-00-00" && $row->iplaw_4_2_4_1edate != "0000-00-00"){
        $str= strtotime($row->iplaw_4_2_4_1edate) - strtotime($row->iplaw_4_2_4_1sdate);
        $str=round($str / 86400);
        $str++;
    }
    $phpWord->setValue('m4_total_date', Num2Unicode($str));

    for($i=1;$i<=2;$i++){
        if($row->iplaw_4_2_4_1help == $i)
            $phpWord->setValue('m4_2_4_1help'.$i, $checked);
        else
            $phpWord->setValue('m4_2_4_1help'.$i, $unchecked);
    }
    $str="........";
    if($row->iplaw_4_2_4_1help_money > 0)
        $str=$row->iplaw_4_2_4_1help_money;
    $phpWord->setValue('m4_2_4_1help_money', Num2Unicode($str));





    if($inspection->insp_type > 0)
    {
        for($i=1;$i<=4;$i++){//m4 conclusion
            if($row->iplaw_4_conclusion == $i){
                $phpWord->setValue('m4_con'.$i, $checked);
            }
            else $phpWord->setValue('m4_con'.$i, $unchecked);
        }
        $str=__('g2.2_ocomment_sample')." ".__('g2.2_ocomment_sample2');
        if($row->iplaw_officer_comment != ""){
            $str=$row->iplaw_officer_comment;
        }
        $phpWord->setValue('m4_comment', $str);
    }


}
function exportMenu5($inspection, $phpWord)
{
    $checked = '<w:sym w:font="Wingdings" w:char="F0FE"/>';
    $unchecked = '<w:sym w:font="Wingdings" w:char="F0A8"/>';
    $arr_a=array();
    for($i=0;$i<=25;$i++){
        $arr_a[$i]=$unchecked;
    }
    $row=$inspection->menu5;
    $str="..............";
    if($row->train_5y > 0)
        $str=$row->train_5y;
    $phpWord->setValue('m5y', Num2Unicode($str));
    $phpWord->setValue('m5y2', Num2Unicode($str));

    for($i=1;$i<=2;$i++){
        if($row->train_5 == $i)
            $phpWord->setValue('m5'.$i, $checked);
        else
            $phpWord->setValue('m5'.$i, $unchecked);
    }
    if($row->train_5_1status == 1)
        $phpWord->setValue('m5_1status', $checked);
    else
        $phpWord->setValue('m5_1status', $unchecked);

    for($i=1;$i<=2;$i++){
        if($row->train_5_1 == $i)
            $phpWord->setValue('m5_1'.$i, $checked);
        else
            $phpWord->setValue('m5_1'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->train_5_1_1 == $i)
            $phpWord->setValue('m5_1_1'.$i, $checked);
        else
            $phpWord->setValue('m5_1_1'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->train_5_1_2 == $i)
            $phpWord->setValue('m5_1_2'.$i, $checked);
        else
            $phpWord->setValue('m5_1_2'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->train_5_1_3 == $i)
            $phpWord->setValue('m5_1_3'.$i, $checked);
        else
            $phpWord->setValue('m5_1_3'.$i, $unchecked);
    }

    if($row->train_5_2status == 1)
        $phpWord->setValue('m5_2status', $checked);
    else
        $phpWord->setValue('m5_2status', $unchecked);
    for($i=1;$i<=4;$i++){
        if($row->train_5_2 == $i)
            $phpWord->setValue('m5_2'.$i, $checked);
        else
            $phpWord->setValue('m5_2'.$i, $unchecked);
    }

    if($inspection->insp_type > 0)
    {
        for($i=1;$i<=4;$i++){//m5 conclusion
            if($row->train_conclusion == $i){
                $phpWord->setValue('m5_con'.$i, $checked);
            }
            else $phpWord->setValue('m5_con'.$i, $unchecked);
        }

        $str=__('g2.2_ocomment_sample')." ".__('g2.2_ocomment_sample2');
        if($row->officer_comment != ""){
            $str=$row->officer_comment;
        }
        $phpWord->setValue('m5_comment', $str);
    }
}
function exportMenu6($inspection, $phpWord)
{
    $checked = '<w:sym w:font="Wingdings" w:char="F0FE"/>';
    $unchecked = '<w:sym w:font="Wingdings" w:char="F0A8"/>';
    $arr_a=array();
    for($i=0;$i<=25;$i++){
        $arr_a[$i]=$unchecked;
    }
    /** ==============Menu 6===========================*/
    $row=$inspection->menu6;
    $str="..............".__('menu6.k_reil_dollar');
    if($row->gen_6_1_1_lowest > 0)
        $str=$row->gen_6_1_1_lowest.$row->gen_6_1_1_lowest_cur;
    $phpWord->setValue('m6_1_1L', Num2Unicode($str));

    $str="..............".__('menu6.k_reil_dollar');
    if($row->gen_6_1_1_average > 0)
        $str=$row->gen_6_1_1_average.$row->gen_6_1_1_average_cur;
    $phpWord->setValue('m6_1_1a', Num2Unicode($str));

    $str="..............".__('menu6.k_reil_dollar');
    if($row->gen_6_1_1_highest > 0)
        $str=$row->gen_6_1_1_highest.$row->gen_6_1_1_highest_cur;
    $phpWord->setValue('m6_1_1h', Num2Unicode($str));

    $str="....................................................................................";
    if($row->gen_6_1_2 != "")
        $str=$row->gen_6_1_2;
    $phpWord->setValue('m6_1_2a', $str);


    $str=$unchecked;
    if($row->gen_6_1_3_1 == 1)
        $str=$checked;
    $phpWord->setValue('m6_1_3_1', $str);
    $str=".......";
    if($row->gen_6_1_3_1_num >0)
        $str=$row->gen_6_1_3_1_num;
    $phpWord->setValue('m6_1_3_1n', Num2Unicode($str));

    $str=".......";
    if($row->gen_6_1_3_1month >0)
        $str=$row->gen_6_1_3_1month;
    $phpWord->setValue('m6_1_3_1m', Num2Unicode($str));
    $str=".......";
    if($row->gen_6_1_3_1day >0)
        $str=$row->gen_6_1_3_1day;
    $phpWord->setValue('m6_1_3_1d', Num2Unicode($str));
    $str=".......";
    if($row->gen_6_1_3_1hour >0)
        $str=$row->gen_6_1_3_1hour;
    $phpWord->setValue('m6_1_3_1h', Num2Unicode($str));

    $str=$unchecked;
    if($row->gen_6_1_3_2 == 1)
        $str=$checked;
    $phpWord->setValue('m6_1_3_2', $str);
    $str=".......";
    if($row->gen_6_1_3_2_num >0)
        $str=$row->gen_6_1_3_2_num;
    $phpWord->setValue('m6_1_3_2n', Num2Unicode($str));

//        $str=$unchecked;
//        if($row->gen_6_1_3_1 == 1)
//            $str=$checked;
//        $phpWord->setValue('m6_1_3_1', $str);
//        $str=".......";
//        if($row->gen_6_1_3_1_num >0)
//            $str=$row->gen_6_1_3_1_num;
//        $phpWord->setValue('m6_1_3_1n', Num2Unicode($str));
//
//        $str=$unchecked;
//        if($row->gen_6_1_3_2 == 1)
//            $str=$checked;
//        $phpWord->setValue('m6_1_3_2', $str);
//        $str=".......";
//        if($row->gen_6_1_3_2_num >0)
//            $str=$row->gen_6_1_3_2_num;
//        $phpWord->setValue('m6_1_3_2n', Num2Unicode($str));

    for($i=1;$i<=2;$i++){
        if($row->gen_6_1_4 == $i)
            $phpWord->setValue('m6_1_4'.$i, $checked);
        else
            $phpWord->setValue('m6_1_4'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_1_5 == $i)
            $phpWord->setValue('m6_1_5'.$i, $checked);
        else
            $phpWord->setValue('m6_1_5'.$i, $unchecked);
    }

    /*for($i=1;$i<=2;$i++){
        if($row->gen_6_1_6 == $i)
            $phpWord->setValue('m6_1_6'.$i, $checked);
        else
            $phpWord->setValue('m6_1_6'.$i, $unchecked);
    }*/

    for($i=1;$i<=3;$i++){
        if($row->gen_6_1_6_1 == $i)
            $phpWord->setValue('m6_1_6_1'.$i, $checked);
        else
            $phpWord->setValue('m6_1_6_1'.$i, $unchecked);
    }
    for($i=1;$i<=3;$i++){
        if($row->gen_6_1_6_2 == $i)
            $phpWord->setValue('m6_1_6_2'.$i, $checked);
        else
            $phpWord->setValue('m6_1_6_2'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_1_7_1 == $i)
            $phpWord->setValue('m6_1_7_1'.$i, $checked);
        else
            $phpWord->setValue('m6_1_7_1'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_1_7_2 == $i)
            $phpWord->setValue('m6_1_7_2'.$i, $checked);
        else
            $phpWord->setValue('m6_1_7_2'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_1_8 == $i)
            $phpWord->setValue('m6_1_8'.$i, $checked);
        else
            $phpWord->setValue('m6_1_8'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->gen_6_1_8_1 == $i)
            $phpWord->setValue('m6_1_8_1'.$i, $checked);
        else
            $phpWord->setValue('m6_1_8_1'.$i, $unchecked);
    }
    $str=".......";
    if($row->gen_6_1_8_1a >0)
        $str=$row->gen_6_1_8_1a;
    $phpWord->setValue('m6_1_8_1a', Num2Unicode($str));
    if($row->gen_6_1_8_2a >0)
        $str=$row->gen_6_1_8_2a;
    $phpWord->setValue('m6_1_8_2a', Num2Unicode($str));
    for($i=1;$i<=2;$i++){
        if($row->gen_6_1_8_2 == $i)
            $phpWord->setValue('m6_1_8_2'.$i, $checked);
        else
            $phpWord->setValue('m6_1_8_2'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_1_9 == $i)
            $phpWord->setValue('m6_1_9'.$i, $checked);
        else
            $phpWord->setValue('m6_1_9'.$i, $unchecked);
    }

    for($i=1;$i<=3;$i++){
        if($row->gen_6_1_10 == $i)
            $phpWord->setValue('m6_1_10'.$i, $checked);
        else
            $phpWord->setValue('m6_1_10'.$i, $unchecked);
    }


    for($j=2;$j<=5;$j++)
        for($i=1;$i<=4;$i++){
            $v="gen_6_1_11_".$j;
            if($row->$v == $i)
                $phpWord->setValue('m6_1_11_'.$j.$i, $checked);
            else
                $phpWord->setValue('m6_1_11_'.$j.$i, $unchecked);
        }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_1_12_1 == $i)
            $phpWord->setValue('m6_1_12_1'.$i, $checked);
        else
            $phpWord->setValue('m6_1_12_1'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_1_12_2 == $i)
            $phpWord->setValue('m6_1_12_2'.$i, $checked);
        else
            $phpWord->setValue('m6_1_12_2'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_1_12_3 == $i)
            $phpWord->setValue('m6_1_12_3'.$i, $checked);
        else
            $phpWord->setValue('m6_1_12_3'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_1_12_4 == $i)
            $phpWord->setValue('m6_1_12_4'.$i, $checked);
        else
            $phpWord->setValue('m6_1_12_4'.$i, $unchecked);
    }

    $str="...........";
    if($row->gen_6_1_13bank_id >0)
        $str= $row->bank->bank_name;
    $phpWord->setValue('m6_1_13bank', $str);

    for($i=1;$i<=2;$i++){
        if($row->gen_6_1_12_4 == $i)
            $phpWord->setValue('m6_1_12_4'.$i, $checked);
        else
            $phpWord->setValue('m6_1_12_4'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_1_13 == $i)
            $phpWord->setValue('m6_1_13'.$i, $checked);
        else
            $phpWord->setValue('m6_1_13'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_1_13_1 == $i)
            $phpWord->setValue('m6_1_13_1'.$i, $checked);
        else
            $phpWord->setValue('m6_1_13_1'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_1_13_2 == $i)
            $phpWord->setValue('m6_1_13_2'.$i, $checked);
        else
            $phpWord->setValue('m6_1_13_2'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_2 == $i)
            $phpWord->setValue('m6_2'.$i, $checked);
        else
            $phpWord->setValue('m6_2'.$i, $unchecked);
    }
    $str=".......";
    if($row->gen_6_2e > 0)
        $str=$row->gen_6_2e;
    $phpWord->setValue('m6_2e', Num2Unicode($str));
    $str=".......";
    if($row->gen_6_2ef > 0)
        $str=$row->gen_6_2ef;
    $phpWord->setValue('m6_2ef', Num2Unicode($str));

    for($i=1;$i<=2;$i++){
        if($row->gen_6_2_1 == $i)
            $phpWord->setValue('m6_2_1'.$i, $checked);
        else
            $phpWord->setValue('m6_2_1'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_2_2 == $i)
            $phpWord->setValue('m6_2_2'.$i, $checked);
        else
            $phpWord->setValue('m6_2_2'.$i, $unchecked);
    }
    $str=".......";
    if($row->gen_6_2_3a > 0)
        $str=timeName($row->gen_6_2_3a);
    $phpWord->setValue('m6_2_3a', Num2Unicode($str));
    $str=".......";
    if($row->gen_6_2_3b > 0)
        $str=timeName($row->gen_6_2_3b);
    $phpWord->setValue('m6_2_3b', Num2Unicode($str));

    $str=".......";
    if($row->gen_6_2_3a > 0 && $row->gen_6_2_3b > 0){
        $str= $row->gen_6_2_3b -  $row->gen_6_2_3a;
    }
    $phpWord->setValue('m6_2_3c', Num2Unicode($str));

    for($i=1;$i<=2;$i++){
        if($row->gen_6_3 == $i)
            $phpWord->setValue('m6_3'.$i, $checked);
        else
            $phpWord->setValue('m6_3'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_3_1 == $i)
            $phpWord->setValue('m6_3_1'.$i, $checked);
        else
            $phpWord->setValue('m6_3_1'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_3_2 == $i)
            $phpWord->setValue('m6_3_2'.$i, $checked);
        else
            $phpWord->setValue('m6_3_2'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_4_1 == $i)
            $phpWord->setValue('m6_4_1'.$i, $checked);
        else
            $phpWord->setValue('m6_4_1'.$i, $unchecked);
    }

    for($j=1;$j<=2;$j++)
        for($i=1;$i<=3;$i++){
            $v="gen_6_4_1_".$j;
            if($row->$v == $i)
                $phpWord->setValue('m6_4_1_'.$j.$i, $checked);
            else
                $phpWord->setValue('m6_4_1_'.$j.$i, $unchecked);
        }
    $str="..........";
    if($row->gen_6_4_1_3 > 0)
        $str=$row->gen_6_4_1_3;
    $phpWord->setValue('m6_4_1_3', $str);

    /** =================== 6_4_2 */
    for($i=1;$i<=2;$i++){
        if($row->gen_6_4_2 == $i)
            $phpWord->setValue('m6_4_2'.$i, $checked);
        else
            $phpWord->setValue('m6_4_2'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->gen_6_4_2_1 == $i)
            $phpWord->setValue('m6_4_2_1'.$i, $checked);
        else
            $phpWord->setValue('m6_4_2_1'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_4_2_2 == $i)
            $phpWord->setValue('m6_4_2_2'.$i, $checked);
        else
            $phpWord->setValue('m6_4_2_2'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_4_2a == $i)
            $phpWord->setValue('m6_4_2a'.$i, $checked);
        else
            $phpWord->setValue('m6_4_2a'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->gen_6_4_2a_1 == $i)
            $phpWord->setValue('m6_4_2a_1'.$i, $checked);
        else
            $phpWord->setValue('m6_4_2a_1'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->gen_6_4_2a_2 == $i)
            $phpWord->setValue('m6_4_2a_2'.$i, $checked);
        else
            $phpWord->setValue('m6_4_2a_2'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->gen_6_4_2a_3 == $i)
            $phpWord->setValue('m6_4_2a_3'.$i, $checked);
        else
            $phpWord->setValue('m6_4_2a_3'.$i, $unchecked);
    }


    for($i=1;$i<=2;$i++){
        if($row->gen_6_4_2b == $i)
            $phpWord->setValue('m6_4_2b'.$i, $checked);
        else
            $phpWord->setValue('m6_4_2b'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_4_2b_1 == $i)
            $phpWord->setValue('m6_4_2b_1'.$i, $checked);
        else
            $phpWord->setValue('m6_4_2b_1'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_4_2b_2 == $i)
            $phpWord->setValue('m6_4_2b_2'.$i, $checked);
        else
            $phpWord->setValue('m6_4_2b_2'.$i, $unchecked);
    }

    /** =================== 6_4_3 */
    for($i=1;$i<=2;$i++){
        if($row->gen_6_4_3 == $i)
            $phpWord->setValue('m6_4_3'.$i, $checked);
        else
            $phpWord->setValue('m6_4_3'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_4_3_1 == $i)
            $phpWord->setValue('m6_4_3_1'.$i, $checked);
        else
            $phpWord->setValue('m6_4_3_1'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_4_3_2 == $i)
            $phpWord->setValue('m6_4_3_2'.$i, $checked);
        else
            $phpWord->setValue('m6_4_3_2'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->gen_6_4_3_3 == $i)
            $phpWord->setValue('m6_4_3_3'.$i, $checked);
        else
            $phpWord->setValue('m6_4_3_3'.$i, $unchecked);
    }
    /** =================== 6_4_4 */
    for($i=1;$i<=2;$i++){
        if($row->gen_6_4_4_1 == $i)
            $phpWord->setValue('m6_4_4_1'.$i, $checked);
        else
            $phpWord->setValue('m6_4_4_1'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->gen_6_4_4_2 == $i)
            $phpWord->setValue('m6_4_4_2'.$i, $checked);
        else
            $phpWord->setValue('m6_4_4_2'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->gen_6_4_4_3 == $i)
            $phpWord->setValue('m6_4_4_3'.$i, $checked);
        else
            $phpWord->setValue('m6_4_4_3'.$i, $unchecked);
    }


    /** =================== 6_4_5 */
    for($i=1;$i<=2;$i++){
        if($row->gen_6_4_5_1 == $i)
            $phpWord->setValue('m6_4_5_1'.$i, $checked);
        else
            $phpWord->setValue('m6_4_5_1'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->gen_6_4_5_2 == $i)
            $phpWord->setValue('m6_4_5_2'.$i, $checked);
        else
            $phpWord->setValue('m6_4_5_2'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->gen_6_4_5_3 == $i)
            $phpWord->setValue('m6_4_5_3'.$i, $checked);
        else
            $phpWord->setValue('m6_4_5_3'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_4_6 == $i)
            $phpWord->setValue('m6_4_6'.$i, $checked);
        else
            $phpWord->setValue('m6_4_6'.$i, $unchecked);
    }

    /** =================== 6_5_1 */
    for($i=1;$i<=2;$i++){
        if($row->gen_6_5_1_1 == $i)
            $phpWord->setValue('m6_5_1_1'.$i, $checked);
        else
            $phpWord->setValue('m6_5_1_1'.$i, $unchecked);
    }

    $str=".......";
    if($row->gen_6_5_1_1a > 0)
        $str= Num2Unicode($row->gen_6_5_1_1a);
    $phpWord->setValue('m6_5_1_1a', $str);
    $str=".......";
    if($row->gen_6_5_1_1b > 0)
        $str= Num2Unicode($row->gen_6_5_1_1b);
    $phpWord->setValue('m6_5_1_1b', $str);
    $str=".......";
    if($row->gen_6_5_1_1c > 0)
        $str= Num2Unicode($row->gen_6_5_1_1c);
    $phpWord->setValue('m6_5_1_1c', $str);
    $str=".......";
    if($row->gen_6_5_1_1d > 0)
        $str= Num2Unicode($row->gen_6_5_1_1d);
    $phpWord->setValue('m6_5_1_1d', $str);
    $str=".......";
    if($row->gen_6_5_1_1e > 0)
        $str= Num2Unicode($row->gen_6_5_1_1e);
    $phpWord->setValue('m6_5_1_1e', $str);
    $str=".......";
    if($row->gen_6_5_1_1f > 0)
        $str= Num2Unicode($row->gen_6_5_1_1f);
    $phpWord->setValue('m6_5_1_1f', $str);
    $str=".......";
    if($row->gen_6_5_1_1g > 0)
        $str= Num2Unicode($row->gen_6_5_1_1g);
    $phpWord->setValue('m6_5_1_1g', $str);
    $str=".......";
    if($row->gen_6_5_1_1h > 0)
        $str= Num2Unicode($row->gen_6_5_1_1h);
    $phpWord->setValue('m6_5_1_1h', $str);


    for($i=1;$i<=2;$i++){
        if($row->gen_6_5_1_2 == $i)
            $phpWord->setValue('m6_5_1_2'.$i, $checked);
        else
            $phpWord->setValue('m6_5_1_2'.$i, $unchecked);
    }
    $str=".......";
    if($row->gen_6_5_1_2num > 0)
        $str= Num2Unicode($row->gen_6_5_1_2num);
    $phpWord->setValue('m6_5_1_2num', $str);

    for($i=1;$i<=2;$i++){
        if($row->gen_6_5_2_1 == $i)
            $phpWord->setValue('m6_5_2_1'.$i, $checked);
        else
            $phpWord->setValue('m6_5_2_1'.$i, $unchecked);
    }

    /** =================== 6_5_2 */
    $str=".......";
    if($row->gen_6_5_2_1num > 0)
        $str= Num2Unicode($row->gen_6_5_2_1num);
    $phpWord->setValue('m6_5_2_1num', $str);

    for($i=1;$i<=2;$i++){
        if($row->gen_6_5_2_2 == $i)
            $phpWord->setValue('m6_5_2_2'.$i, $checked);
        else
            $phpWord->setValue('m6_5_2_2'.$i, $unchecked);
    }
    $str=".......";
    if($row->gen_6_5_2_2num > 0)
        $str= Num2Unicode($row->gen_6_5_2_2num);
    $phpWord->setValue('m6_5_2_2num', $str);
    /** =================== 6_6 */
    for($i=2;$i>=1;$i--){
        if($row->gen_6_6_1 == $i)
            $phpWord->setValue('m6_6_1'.$i, $checked);
        else
            $phpWord->setValue('m6_6_1'.$i, $unchecked);
    }
    $str=".......";
    if($row->gen_6_6_1num > 0)
        $str= Num2Unicode($row->gen_6_6_1num);
    $phpWord->setValue('m6_6_1num', $str);

    /** =================== 6_7 */
    for($i=1;$i<=2;$i++){
        if($row->gen_6_7_2 == $i)
            $phpWord->setValue('m6_7_2'.$i, $checked);
        else
            $phpWord->setValue('m6_7_2'.$i, $unchecked);
    }

    $str="..........";
    if($row->gen_6_7_1 > 0)
        $str=$row->gen_6_7_1;
    $phpWord->setValue('m6_7_1', Num2Unicode($str));

    $str="..........";
    if($row->gen_6_7_2_1 > 0)
        $str=$row->gen_6_7_2_1;
    $phpWord->setValue('m6_7_2_1', Num2Unicode($str));

    $str="..........";
    if($row->gen_6_7_2_2 > 0)
        $str=$row->gen_6_7_2_2;
    $phpWord->setValue('m6_7_2_2', Num2Unicode($str));

    for($i=1;$i<=2;$i++){
        if($row->gen_6_7_2_3 == $i)
            $phpWord->setValue('m6_7_2_3'.$i, $checked);
        else
            $phpWord->setValue('m6_7_2_3'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_7_2_4 == $i)
            $phpWord->setValue('m6_7_2_4'.$i, $checked);
        else
            $phpWord->setValue('m6_7_2_4'.$i, $unchecked);
    }
    $str=".......";
    if($row->gen_6_7_2_4num > 0)
        $str= Num2Unicode($row->gen_6_7_2_4num);
    $phpWord->setValue('m6_7_2_4num', $str);

    for($i=1;$i<=2;$i++){
        if($row->gen_6_7_3 == $i)
            $phpWord->setValue('m6_7_3'.$i, $checked);
        else
            $phpWord->setValue('m6_7_3'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_7_4 == $i)
            $phpWord->setValue('m6_7_4'.$i, $checked);
        else
            $phpWord->setValue('m6_7_4'.$i, $unchecked);
    }
    $str=".......";
    if($row->gen_6_7_4num > 0)
        $str= Num2Unicode($row->gen_6_7_4num);
    $phpWord->setValue('m6_7_4num', $str);

    /** ==========6_9_1 in DB but display as 6_8_1=========  **/
    for($i=1;$i<=2;$i++){
        if($row->gen_6_9_1c == $i)
            $phpWord->setValue('m6_9_1c'.$i, $checked);
        else
            $phpWord->setValue('m6_9_1c'.$i, $unchecked);
    }
    $str=".......";
    if($row->gen_6_9_1cnum > 0)
        $str= Num2Unicode($row->gen_6_9_1cnum);
    $phpWord->setValue('m6_9_1cnum', $str);

    for($i=1;$i<=2;$i++){
        if($row->gen_6_9_1 == $i)
            $phpWord->setValue('m6_9_1'.$i, $checked);
        else
            $phpWord->setValue('m6_9_1'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_9_1_1 == $i)
            $phpWord->setValue('m6_9_1_1'.$i, $checked);
        else
            $phpWord->setValue('m6_9_1_1'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_9_1_2 == $i)
            $phpWord->setValue('m6_9_1_2'.$i, $checked);
        else
            $phpWord->setValue('m6_9_1_2'.$i, $unchecked);
    }

    for($i=1;$i<=3;$i++){
        if($row->gen_6_9_1_3 == $i)
            $phpWord->setValue('m6_9_1_3'.$i, $checked);
        else
            $phpWord->setValue('m6_9_1_3'.$i, $unchecked);
    }

    for($i=1;$i<=3;$i++){
        if($row->gen_6_9_1_4 == $i)
            $phpWord->setValue('m6_9_1_4'.$i, $checked);
        else
            $phpWord->setValue('m6_9_1_4'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_9_1_5 == $i)
            $phpWord->setValue('m6_9_1_5'.$i, $checked);
        else
            $phpWord->setValue('m6_9_1_5'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_9_1_6 == $i)
            $phpWord->setValue('m6_9_1_6'.$i, $checked);
        else
            $phpWord->setValue('m6_9_1_6'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_9_1_7 == $i)
            $phpWord->setValue('m6_9_1_7'.$i, $checked);
        else
            $phpWord->setValue('m6_9_1_7'.$i, $unchecked);
    }

    /** =================Menu 6: conclusion and comment of officer=======*/
    if($inspection->insp_type > 0)
    {
        for($i=1;$i<=4;$i++){//m6 conclusion
            if($row->gen_6_conclusion == $i){
                $phpWord->setValue('m6_con'.$i, $checked);
            }
            else $phpWord->setValue('m6_con'.$i, $unchecked);
        }
        $str=__('g2.2_ocomment_sample')." ".__('g2.2_ocomment_sample2');
        if($row->officer_comment != ""){
            $str=$row->officer_comment;
        }
        $phpWord->setValue('m6_comment', $str);
    }

}
function exportMenu7($inspection, $phpWord)
{
    $checked = '<w:sym w:font="Wingdings" w:char="F0FE"/>';
    $unchecked = '<w:sym w:font="Wingdings" w:char="F0A8"/>';
    $arr_a=array();
    for($i=0;$i<=25;$i++){
        $arr_a[$i]=$unchecked;
    }

    /** ==============Menu 7===========================*/
    $row = $inspection->menu7;
    for($i=1;$i<=2;$i++){
        if($row->sec_7_1_1 == $i)
            $phpWord->setValue('m7_1_1'.$i, $checked);
        else
            $phpWord->setValue('m7_1_1'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->sec_7_1_1 == $i)
            $phpWord->setValue('m7_1_1'.$i, $checked);
        else
            $phpWord->setValue('m7_1_1'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->sec_7_1_2 == $i)
            $phpWord->setValue('m7_1_2'.$i, $checked);
        else
            $phpWord->setValue('m7_1_2'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->sec_7_1_3 == $i)
            $phpWord->setValue('m7_1_3'.$i, $checked);
        else
            $phpWord->setValue('m7_1_3'.$i, $unchecked);
    }

    $str=".........";
    if($row->sec_7_1_3_if_have > 0)
        $str=$row->sec_7_1_3_if_have;
    $phpWord->setValue('m7_1_3h', Num2Unicode($str));

    for($i=1;$i<=2;$i++){
        if($row->sec_7_2 == $i)
            $phpWord->setValue('m7_2'.$i, $checked);
        else
            $phpWord->setValue('m7_2'.$i, $unchecked);
    }


    /** ==============Menu 7_3_1===========================*/
    for($i=1;$i<=3;$i++){
        if($row->sec_7_3_1_1 == $i)
            $phpWord->setValue('m7_3_1_1'.$i, $checked);
        else
            $phpWord->setValue('m7_3_1_1'.$i, $unchecked);
    }
    for($i=1;$i<=3;$i++){
        if($row->sec_7_3_1_2 == $i)
            $phpWord->setValue('m7_3_1_2'.$i, $checked);
        else
            $phpWord->setValue('m7_3_1_2'.$i, $unchecked);
    }
    for($i=1;$i<=3;$i++){
        if($row->sec_7_3_1_3 == $i)
            $phpWord->setValue('m7_3_1_3'.$i, $checked);
        else
            $phpWord->setValue('m7_3_1_3'.$i, $unchecked);
    }

//        $total_dose=CompanyWorkerCovidVaccine($company_id);
//        $total_female_dose=CompanyWorkerFemaleCovidVaccine($company_id);
//        $total_emp=$total_dose[0] + $total_dose[1]+ $total_dose[2]+$total_dose[3]+$total_dose[4];
//        $total_emp_female= $total_female_dose[0] + $total_female_dose[1]+ $total_female_dose[2]+   $total_female_dose[3]+   $total_female_dose[4];
//        $phpWord->setValue('m7_total', $total_emp);
//        $phpWord->setValue('m7_total_female', $total_emp_female);
//        $phpWord->setValue('m7_dose0', $total_dose[0]);
//        $phpWord->setValue('m7_dose0_female', $total_female_dose[0]);
//        $phpWord->setValue('m7_dose12', $total_dose[2]);
//        $phpWord->setValue('m7_dose12_female', $total_female_dose[2]);
//        $phpWord->setValue('m7_dose3', $total_dose[3]);
//        $phpWord->setValue('m7_dose3_female', $total_female_dose[3]);
//        $phpWord->setValue('m7_dose4', $total_dose[4]);
//        $phpWord->setValue('m7_dose4_female', $total_female_dose[4]);
//        dd($inspection->company->toArray());
//        $arrComInfo=CompanyInfobyInspection($inspection_id);
    $arrComInfo = $inspection->company->toArray();
    $company_id=$arrComInfo['company_id'];
    $covidData= getCovid19LACMS($company_id);
    $phpWord->setValue('m7_total', Num2Unicode($covidData['total_worker']));
    $phpWord->setValue('m7_total_female', Num2Unicode($covidData['total_worker_female']));
    $phpWord->setValue('m7_dose3', Num2Unicode($covidData['dose_3']));
    $phpWord->setValue('m7_dose3_female', 0);
    $phpWord->setValue('m7_dose4', Num2Unicode($covidData['dose_4']));
    $phpWord->setValue('m7_dose4_female', 0);
    $phpWord->setValue('m7_dose5', Num2Unicode($covidData['dose_5']));
    $phpWord->setValue('m7_dose5_female', 0);
    $phpWord->setValue('m7_dose0', Num2Unicode($covidData['not_vaccinated']));
    $phpWord->setValue('m7_dose0_female', 0);

    /** ==============Menu 7_3_2===========================*/
    for($i=1;$i<=2;$i++){
        if($row->sec_7_3_2_1 == $i)
            $phpWord->setValue('m7_3_2_1'.$i, $checked);
        else
            $phpWord->setValue('m7_3_2_1'.$i, $unchecked);
    }


    for($i=1;$i<=2;$i++){
        if($row->sec_7_3_2_2 == $i)
            $phpWord->setValue('m7_3_2_2'.$i, $checked);
        else
            $phpWord->setValue('m7_3_2_2'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->sec_7_3_2_3 == $i)
            $phpWord->setValue('m7_3_2_3'.$i, $checked);
        else
            $phpWord->setValue('m7_3_2_3'.$i, $unchecked);
    }

    for($i=1;$i<=4;$i++){
        if($row->sec_7_3_2_4 == $i)
            $phpWord->setValue('m7_3_2_4'.$i, $checked);
        else
            $phpWord->setValue('m7_3_2_4'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->sec_7_3_3 == $i)
            $phpWord->setValue('m7_3_3'.$i, $checked);
        else
            $phpWord->setValue('m7_3_3'.$i, $unchecked);
    }

    for($i=1;$i<=3;$i++){
        if($row->sec_7_3_3_1 == $i)
            $phpWord->setValue('m7_3_3_1'.$i, $checked);
        else
            $phpWord->setValue('m7_3_3_1'.$i, $unchecked);
    }
    for($i=1;$i<=3;$i++){
        if($row->sec_7_3_3_2 == $i)
            $phpWord->setValue('m7_3_3_2'.$i, $checked);
        else
            $phpWord->setValue('m7_3_3_2'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->sec_7_3_4 == $i)
            $phpWord->setValue('m7_3_4'.$i, $checked);
        else
            $phpWord->setValue('m7_3_4'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->sec_7_3_5_1 == $i)
            $phpWord->setValue('m7_3_5_1'.$i, $checked);
        else
            $phpWord->setValue('m7_3_5_1'.$i, $unchecked);
    }

    for($i=1;$i<=3;$i++){
        if($row->sec_7_3_5_2 == $i)
            $phpWord->setValue('m7_3_5_2'.$i, $checked);
        else
            $phpWord->setValue('m7_3_5_2'.$i, $unchecked);
    }


    for($i=1;$i<=2;$i++){
        if($row->sec_7_3_6 == $i)
            $phpWord->setValue('m7_3_6'.$i, $checked);
        else
            $phpWord->setValue('m7_3_6'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->sec_7_3_6_1 == $i)
            $phpWord->setValue('m7_3_6_1'.$i, $checked);
        else
            $phpWord->setValue('m7_3_6_1'.$i, $unchecked);
    }
    for($i=1;$i<=3;$i++){
        if($row->sec_7_3_6_2 == $i)
            $phpWord->setValue('m7_3_6_2'.$i, $checked);
        else
            $phpWord->setValue('m7_3_6_2'.$i, $unchecked);
    }

    for($i=1;$i<=3;$i++){
        if($row->sec_7_3_7 == $i)
            $phpWord->setValue('m7_3_7'.$i, $checked);
        else
            $phpWord->setValue('m7_3_7'.$i, $unchecked);
    }

    for($i=1;$i<=3;$i++){
        if($row->sec_7_4_1 == $i)
            $phpWord->setValue('m7_4_1'.$i, $checked);
        else
            $phpWord->setValue('m7_4_1'.$i, $unchecked);
    }

    for($i=1;$i<=3;$i++){
        if($row->sec_7_4_2 == $i)
            $phpWord->setValue('m7_4_2'.$i, $checked);
        else
            $phpWord->setValue('m7_4_2'.$i, $unchecked);
    }
    /**  =============7_4_3 ================== */
    for($i=1;$i<=2;$i++){
        if($row->sec_7_4_3require == $i)
            $phpWord->setValue('m7_4_3require'.$i, $checked);
        else
            $phpWord->setValue('m7_4_3require'.$i, $unchecked);
    }
    if($row->sec_7_4_3a == 1)
        $phpWord->setValue('m7_4_3a', $checked);
    else
        $phpWord->setValue('m7_4_3a', $unchecked);

    for($i=1;$i<=2;$i++){
        if($row->sec_7_4_3a1 == $i)
            $phpWord->setValue('m7_4_3a1'.$i, $checked);
        else
            $phpWord->setValue('m7_4_3a1'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->sec_7_4_3 == $i)
            $phpWord->setValue('m7_4_3'.$i, $checked);
        else
            $phpWord->setValue('m7_4_3'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->sec_7_4_3_1 == $i)
            $phpWord->setValue('m7_4_3_1'.$i, $checked);
        else
            $phpWord->setValue('m7_4_3_1'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->sec_7_4_3_3 == $i)
            $phpWord->setValue('m7_4_3_3'.$i, $checked);
        else
            $phpWord->setValue('m7_4_3_3'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->sec_7_4_3_3_1 == $i)
            $phpWord->setValue('m7_4_3_3_1'.$i, $checked);
        else
            $phpWord->setValue('m7_4_3_3_1'.$i, $unchecked);
    }

    $str=".........";
    if($row->sec_7_4_3_3_2 > 0)
        $str=$row->sec_7_4_3_3_2;
    $phpWord->setValue('m7_4_3_3_2', Num2Unicode($str));

    $str=".........";
    if($row->sec_7_4_3_3_3 > 0)
        $str=$row->sec_7_4_3_3_3;
    $phpWord->setValue('m7_4_3_3_3', Num2Unicode($str));

    for($i=1;$i<=2;$i++){
        if($row->sec_7_4_3_4 == $i)
            $phpWord->setValue('m7_4_3_4'.$i, $checked);
        else
            $phpWord->setValue('m7_4_3_4'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->sec_7_4_3_4_1 == $i)
            $phpWord->setValue('m7_4_3_4_1'.$i, $checked);
        else
            $phpWord->setValue('m7_4_3_4_1'.$i, $unchecked);
    }

    $str=".........";
    if($row->sec_7_4_3_4_2 > 0)
        $str=$row->sec_7_4_3_4_2;
    $phpWord->setValue('m7_4_3_4_2', Num2Unicode($str));

    $str=".........";
    if($row->sec_7_4_3_4_3 > 0)
        $str=$row->sec_7_4_3_4_3;
    $phpWord->setValue('m7_4_3_4_3', Num2Unicode($str));

    for($i=1;$i<=2;$i++){
        if($row->sec_7_4_3_5 == $i)
            $phpWord->setValue('m7_4_3_5'.$i, $checked);
        else
            $phpWord->setValue('m7_4_3_5'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->sec_7_4_3_5_1 == $i)
            $phpWord->setValue('m7_4_3_5_1'.$i, $checked);
        else
            $phpWord->setValue('m7_4_3_5_1'.$i, $unchecked);
    }

    $str=".........";
    if($row->sec_7_4_3_5_2 > 0)
        $str=$row->sec_7_4_3_5_2;
    $phpWord->setValue('m7_4_3_5_2', Num2Unicode($str));

    $str=".........";
    if($row->sec_7_4_3_5_3 > 0)
        $str=$row->sec_7_4_3_5_3;
    $phpWord->setValue('m7_4_3_5_3', Num2Unicode($str));


    for($i=1;$i<=2;$i++){
        if($row->sec_7_4_3_6 == $i)
            $phpWord->setValue('m7_4_3_6'.$i, $checked);
        else
            $phpWord->setValue('m7_4_3_6'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->sec_7_4_3_7 == $i)
            $phpWord->setValue('m7_4_3_7'.$i, $checked);
        else
            $phpWord->setValue('m7_4_3_7'.$i, $unchecked);
    }
    if($row->sec_7_4_3b == 1)
        $phpWord->setValue('m7_4_3b', $checked);
    else
        $phpWord->setValue('m7_4_3b', $unchecked);

    $phpWord->setValue('m7_4_3ba', $row->sec_7_4_3ba);
    $phpWord->setValue('m7_4_3bb', $row->sec_7_4_3bb);
    for($i=1;$i<=2;$i++){
        if($row->sec_7_4_3b1 == $i)
            $phpWord->setValue('m7_4_3b1'.$i, $checked);
        else
            $phpWord->setValue('m7_4_3b1'.$i, $unchecked);
    }


    for($i=1;$i<=2;$i++){
        if($row->sec_7_4_4 == $i)
            $phpWord->setValue('m7_4_4'.$i, $checked);
        else
            $phpWord->setValue('m7_4_4'.$i, $unchecked);
    }

    $str=".........";
    if($row->sec_7_4_4_if_have > 0)
        $str=$row->sec_7_4_4_if_have;
    $phpWord->setValue('m7_4_4n', Num2Unicode($str));

    for($i=1;$i<=2;$i++){
        if($row->sec_7_4_5 == $i)
            $phpWord->setValue('m7_4_5'.$i, $checked);
        else
            $phpWord->setValue('m7_4_5'.$i, $unchecked);
    }

    $str=".........";
    if($row->sec_7_4_5_if_have > 0)
        $str=$row->sec_7_4_5_if_have;
    $phpWord->setValue('m7_4_5n', Num2Unicode($str));

    $str=".........../.........../...........";
    $str2=".....................................................................................................";


    for($i=1;$i<=3;$i++){
        if($row->sec_7_5_1 == $i)
            $phpWord->setValue('m7_5_1'.$i, $checked);
        else
            $phpWord->setValue('m7_5_1'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->sec_7_5_2 == $i)
            $phpWord->setValue('m7_5_2'.$i, $checked);
        else
            $phpWord->setValue('m7_5_2'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->sec_7_5_3 == $i)
            $phpWord->setValue('m7_5_3'.$i, $checked);
        else
            $phpWord->setValue('m7_5_3'.$i, $unchecked);
    }

    for($i=1;$i<=3;$i++){
        if($row->sec_7_5_4 == $i)
            $phpWord->setValue('m7_5_4'.$i, $checked);
        else
            $phpWord->setValue('m7_5_4'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->sec_7_5_5 == $i)
            $phpWord->setValue('m7_5_5'.$i, $checked);
        else
            $phpWord->setValue('m7_5_5'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->sec_7_5_5_1 == $i)
            $phpWord->setValue('m7_5_5_1'.$i, $checked);
        else
            $phpWord->setValue('m7_5_5_1'.$i, $unchecked);
    }

    for($i=1;$i<=3;$i++){
        if($row->sec_7_5_5_2 == $i)
            $phpWord->setValue('m7_5_5_2'.$i, $checked);
        else
            $phpWord->setValue('m7_5_5_2'.$i, $unchecked);
    }

    for($i=1;$i<=3;$i++){
        if($row->sec_7_5_6 == $i)
            $phpWord->setValue('m7_5_6'.$i, $checked);
        else
            $phpWord->setValue('m7_5_6'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->sec_7_5_7 == $i)
            $phpWord->setValue('m7_5_7'.$i, $checked);
        else
            $phpWord->setValue('m7_5_7'.$i, $unchecked);
    }

    for($i=1;$i<=3;$i++){
        if($row->sec_7_5_8 == $i)
            $phpWord->setValue('m7_5_8'.$i, $checked);
        else
            $phpWord->setValue('m7_5_8'.$i, $unchecked);
    }
    for($i=1;$i<=3;$i++){
        if($row->sec_7_5_8_1 == $i)
            $phpWord->setValue('m7_5_8_1'.$i, $checked);
        else
            $phpWord->setValue('m7_5_8_1'.$i, $unchecked);
    }
    for($i=1;$i<=3;$i++){
        if($row->sec_7_5_8_2 == $i)
            $phpWord->setValue('m7_5_8_2'.$i, $checked);
        else
            $phpWord->setValue('m7_5_8_2'.$i, $unchecked);
    }

    /*for($i=1;$i<=2;$i++){
        if($row->sec_7_5_9 == $i)
            $phpWord->setValue('m7_5_9'.$i, $checked);
        else
            $phpWord->setValue('m7_5_9'.$i, $unchecked);
    }*/
    for($i=1;$i<=3;$i++){
        if($row->sec_7_5_9_1 == $i)
            $phpWord->setValue('m7_5_9_1'.$i, $checked);
        else
            $phpWord->setValue('m7_5_9_1'.$i, $unchecked);
    }

    $str=".........";
    if($row->sec_7_5_9_2_1 > 0)
        $str=$row->sec_7_5_9_2_1;
    $phpWord->setValue('m7_5_9_2_1', Num2Unicode($str));

    $str=".........";
    if($row->sec_7_5_9_2_2 > 0)
        $str=$row->sec_7_5_9_2_2;
    $phpWord->setValue('m7_5_9_2_2', Num2Unicode($str));

    $str=".........";
    if($row->sec_7_5_9_2_3 > 0)
        $str=$row->sec_7_5_9_2_3;
    $phpWord->setValue('m7_5_9_2_3', Num2Unicode($str));

    for($i=1;$i<=2;$i++){
        if($row->sec_7_5_9_3 == $i)
            $phpWord->setValue('m7_5_9_3'.$i, $checked);
        else
            $phpWord->setValue('m7_5_9_3'.$i, $unchecked);
    }

    for($i=1;$i<=3;$i++){
        if($row->sec_7_5_10 == $i)
            $phpWord->setValue('m7_5_10'.$i, $checked);
        else
            $phpWord->setValue('m7_5_10'.$i, $unchecked);
    }

    for($i=1;$i<=3;$i++){
        if($row->sec_7_5_11 == $i)
            $phpWord->setValue('m7_5_11'.$i, $checked);
        else
            $phpWord->setValue('m7_5_11'.$i, $unchecked);
    }

    for($i=1;$i<=3;$i++){
        if($row->sec_7_5_12 == $i)
            $phpWord->setValue('m7_5_12'.$i, $checked);
        else
            $phpWord->setValue('m7_5_12'.$i, $unchecked);
    }

    for($i=1;$i<=3;$i++){
        if($row->sec_7_5_13 == $i)
            $phpWord->setValue('m7_5_13'.$i, $checked);
        else
            $phpWord->setValue('m7_5_13'.$i, $unchecked);
    }

    for($i=1;$i<=3;$i++){
        if($row->sec_7_5_14 == $i)
            $phpWord->setValue('m7_5_14'.$i, $checked);
        else
            $phpWord->setValue('m7_5_14'.$i, $unchecked);
    }
    for($j=1;$j<=4;$j++)
        for($i=1;$i<=2;$i++){
            $v='sec_7_5_14_'.$j;
            if($row->$v == $i)
                $phpWord->setValue('m7_5_14_'.$j.$i, $checked);
            else
                $phpWord->setValue('m7_5_14_'.$j.$i, $unchecked);
        }

    for($i=1;$i<=2;$i++){
        if($row->sec_7_5_15 == $i)
            $phpWord->setValue('m7_5_15'.$i, $checked);
        else
            $phpWord->setValue('m7_5_15'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->sec_7_5_16 == $i)
            $phpWord->setValue('m7_5_16'.$i, $checked);
        else
            $phpWord->setValue('m7_5_16'.$i, $unchecked);
    }

    /** =============Menu 7: conlcusion and comment of officer ==== */
    if($inspection->insp_type > 0)
    {
        for($i=1;$i<=4;$i++){//m7 conclusion
            if($row->sec_7_conclusion == $i){
                $phpWord->setValue('m7_con'.$i, $checked);
            }
            else $phpWord->setValue('m7_con'.$i, $unchecked);
        }
        $str=__('g2.2_ocomment_sample')." ".__('g2.2_ocomment_sample2');
        if($row->officer_comment != ""){
            $str=$row->officer_comment;
        }
        $phpWord->setValue('m7_comment', $str);
    }

}
function exportMenu8($inspection, $phpWord)
{
    $checked = '<w:sym w:font="Wingdings" w:char="F0FE"/>';
    $unchecked = '<w:sym w:font="Wingdings" w:char="F0A8"/>';
    $arr_a=array();
    for($i=0;$i<=25;$i++){
        $arr_a[$i]=$unchecked;
    }

    /** ==============Menu 8===========================*/
    $row = $inspection->menu8;
    for($j=1;$j<=3;$j++)
        for($i=1;$i<=2;$i++){
            $v="nssf_8_".$j;
            if($row->$v == $i)
                $phpWord->setValue('m8_'.$j.$i, $checked);
            else
                $phpWord->setValue('m8_'.$j.$i, $unchecked);
        }

    $str=$unchecked;
    $str2=".......";
    $str3=".......";
    if($row->nssf_8_2_if_have ==1){
        $str=$checked;
        $str2=$row->nssf_8_2_if_have_num;
        $str3=$row->nssf_8_2_if_have_numf;
    }
    $phpWord->setValue('m8_2h', $str);
    $phpWord->setValue('m8_2hn', Num2Unicode($str2));
    $phpWord->setValue('m8_2hnf', Num2Unicode($str3));

    for($i=1;$i<=5;$i++){
        $v="nssf_8_3_".$i;
        if($row->$v == 1)
            $phpWord->setValue('m8_3_'.$i, $checked);
        else
            $phpWord->setValue('m8_3_'.$i, $unchecked);
    }

    for($i=1;$i<=3;$i++){
        $str=$unchecked;
        $v="nssf_8_4_".$i;
        if($row->$v)
            $str=$checked;
        $phpWord->setValue('m8_4_'.$i, $str);

        $str=".......";
        $v="nssf_8_4_".$i."_num";
        if($row->$v > 0)
            $str=$row->$v;
        $phpWord->setValue('m8_4_'.$i.'n', Num2Unicode($str));
    }
//        dd(count($row->driver));
//        $query_driver=$this->main->select_many_record("tbl_8_4_3_driver", "*", $con);
    $str="";
    if(count($row->driver) > 0){
        $i=1;
        foreach($row->driver as $rowd){
            $str2=".......................................................";
            if($rowd->name !="") $str2=$rowd->name;
            $str3=".................................";
            if($rowd->phone !="") $str3=$rowd->phone;
            $str4=".................................";
            if($rowd->car_no !="") $str4=$rowd->car_no;
            $str.=Num2Unicode($i).".ឈ្មោះអ្នកបើកបរ"." ".$str2."  ";
            $str.= "លេខទូរសព្ទ"." ".Num2Unicode($str3)."  ";
            $str.= "ស្លាកលេខ"." ".Num2Unicode($str4)."<w:br/>";
            $i++;
        }
    }
    $phpWord->setValue('m8_4_3list', $str);
    /** =============Menu 8: conlcusion and comment of officer */
    if($inspection->insp_type > 0)
    {
        for($i=1;$i<=4;$i++){//m8 conclusion
            if($row->nssf_8_conclusion == $i){
                $phpWord->setValue('m8_con'.$i, $checked);
            }
            else $phpWord->setValue('m8_con'.$i, $unchecked);
        }

        $str=__('g2.2_ocomment_sample')." ".__('g2.2_ocomment_sample2');
        if($row->officer_comment != ""){
            $str=$row->officer_comment;
        }
        $phpWord->setValue('m8_comment', $str);
    }

}
function exportMenu9($inspection, $phpWord)
{
    $checked = '<w:sym w:font="Wingdings" w:char="F0FE"/>';
    $unchecked = '<w:sym w:font="Wingdings" w:char="F0A8"/>';
    $arr_a=array();
    for($i=0;$i<=25;$i++){
        $arr_a[$i]=$unchecked;
    }

    /** ==============Menu 9===========================*/
    $row = $inspection->menu9;
    /** ==============Menu 9_1===========================*/
    for($i=1;$i<=2;$i++){
        if($row->prof_9_1option== $i)
            $phpWord->setValue('m9_1option'.$i, $checked);
        else
            $phpWord->setValue('m9_1option'.$i, $unchecked);
    }

    $str="...........";
    if($row->prof_9_1a_num1 > 0)
        $str=$row->prof_9_1a_num1;
    $phpWord->setValue('m9_1a_num1', Num2Unicode($str));
    $str="...........";
    if($row->prof_9_1a_num2 > 0)
        $str=$row->prof_9_1a_num2;
    $phpWord->setValue('m9_1a_num2', Num2Unicode($str));
    $str="...........";
    if($row->prof_9_1a_num3 > 0)
        $str=$row->prof_9_1a_num3;
    $phpWord->setValue('m9_1a_num3', Num2Unicode($str));
    $str="...........";
    if($row->prof_9_1a_num4 > 0)
        $str=$row->prof_9_1a_num4;
    $phpWord->setValue('m9_1a_num4', Num2Unicode($str));

    for($i=1;$i<=2;$i++){
        if($row->prof_9_1== $i)
            $phpWord->setValue('m9_1'.$i, $checked);
        else
            $phpWord->setValue('m9_1'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->prof_9_1_1== $i)
            $phpWord->setValue('m9_1_1'.$i, $checked);
        else
            $phpWord->setValue('m9_1_1'.$i, $unchecked);
    }

    /** ==============Menu 9_2===========================*/
    for($j=1;$j<=2;$j++)
        for($i=1;$i<=2;$i++){
            $v="prof_9_2_".$j;
            if($row->$v == $i)
                $phpWord->setValue('m9_2_'.$j.$i, $checked);
            else
                $phpWord->setValue('m9_2_'.$j.$i, $unchecked);
        }

    for($i=1;$i<=2;$i++){
        if($row->prof_9_3== $i)
            $phpWord->setValue('m9_3'.$i, $checked);
        else
            $phpWord->setValue('m9_3'.$i, $unchecked);
    }

    $str=".........";
    if($row->prof_9_3_1all > 0)
        $str=$row->prof_9_3_1all;
    $phpWord->setValue('m9_3_1all', Num2Unicode($str));

    $str=".........";
    if($row->prof_9_3_1 > 0)
        $str=$row->prof_9_3_1;
    $phpWord->setValue('m9_3_1', Num2Unicode($str));

    $m931_data="";
    //$qk=$this->main->select_many_record("tbl_931", "*", array('inspection_id' => $inspection_id));
    if(count($row->union) > 0){
        //dd("have union");
        $i=1;
        foreach($row->union as $rowk){
            $m931_data.= Num2Unicode($i).".ឈ្មោះសហជីព: ".xmlEntities($rowk->union_name);
            $m931_data.= "  ចំនួនសមាជិក ".Num2Unicode($rowk->total_member)."នាក់ ";
            $m931_data.= "  ជាសមាជិកសហព័ន្ធសហជីព ".$rowk->member_of;
            $m931_data.= "<w:br/>";
            $i++;
        }
    }
    $phpWord->setValue('m931_data', $m931_data);

    $str=".........";
    if($row->prof_9_3_2 > 0)
        $str=$row->prof_9_3_2;
    $phpWord->setValue('m9_3_2', $str);

    $str=".........";
    if($row->prof_9_3_2_per > 0)
        $str=$row->prof_9_3_2_per;
    $phpWord->setValue('m9_3_2p', $str);

    for($j=1;$j<=2;$j++)
        for($i=1;$i<=2;$i++){
            $v="prof_9_3_3_".$j;
            if($row->$v == $i)
                $phpWord->setValue('m9_3_3_'.$j.$i, $checked);
            else
                $phpWord->setValue('m9_3_3_'.$j.$i, $unchecked);
        }

    for($j=4;$j<=8;$j++)
        for($i=1;$i<=2;$i++){
            $v="prof_9_3_".$j;
            if($row->$v == $i)
                $phpWord->setValue('m9_3_'.$j.$i, $checked);
            else
                $phpWord->setValue('m9_3_'.$j.$i, $unchecked);
        }
    $str="....";
    if($row->prof_9_3_8num > 0)
        $str=$row->prof_9_3_8num;
    $phpWord->setValue('m9_3_8num', Num2Unicode($str));

    for($i=1;$i<=3;$i++){
        if($row->prof_9_3_9== $i)
            $phpWord->setValue('m9_3_9'.$i, $checked);
        else
            $phpWord->setValue('m9_3_9'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->prof_9_3_6_1== $i)
            $phpWord->setValue('m9_3_6_1'.$i, $checked);
        else
            $phpWord->setValue('m9_3_6_1'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->prof_9_4== $i)
            $phpWord->setValue('m9_4'.$i, $checked);
        else
            $phpWord->setValue('m9_4'.$i, $unchecked);
    }

    $str="....................................................................................";
    if($row->prof_9_4_name !="")
        $str=$row->prof_9_4_name;
    $phpWord->setValue('m9_4n', $str);

    for($i=1;$i<=2;$i++){
        if($row->prof_9_4_1== $i)
            $phpWord->setValue('m9_4_1'.$i, $checked);
        else
            $phpWord->setValue('m9_4_1'.$i, $unchecked);
    }

    $str="........";
//        if($row->prof_9_4_1_date != null);
//            dd($row->prof_9_4_1_date);

    if($row->prof_9_4_1_date != null){
        $arr_date= getDateProvinceAsKhmer($row->prof_9_4_1_date);
//                $this->get_date_province_as_khmer($row->prof_9_4_1_date);
        $str=__('general.k_date3').$arr_date['day']."  ";
        $str.=__('general.k_month').__("general.month_".$arr_date["month"])."  ";
        $str.=__('general.k_year').$arr_date['year'];
    }
    $phpWord->setValue('m9_4_1date', $str);

    $str="..............................................";
    if($row->prof_9_4_2_name !="")
        $str=$row->prof_9_4_2_name;
    $phpWord->setValue('m9_4_2n', $str);

    for($i=1;$i<=2;$i++){
        if($row->prof_9_4_2 == $i)
            $phpWord->setValue('m9_4_2'.$i, $checked);
        else
            $phpWord->setValue('m9_4_2'.$i, $unchecked);
    }

    $str="........";
    if($row->prof_9_4_2_date != null){
        $arr_date= getDateProvinceAsKhmer($row->prof_9_4_2_date);
//                $this->get_date_province_as_khmer($row->prof_9_4_2_date);
        $str=__('general.k_date3').$arr_date['day']."  ";
        $str.=__('general.k_month').__("general.month_".$arr_date["month"])."  ";
        $str.=__('general.k_year').$arr_date['year'];
    }
    $phpWord->setValue('m9_4_2date', $str);


    for($i=1;$i<=2;$i++){
        if($row->prof_9_5 == $i)
            $phpWord->setValue('m9_5'.$i, $checked);
        else
            $phpWord->setValue('m9_5'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->prof_9_5_1 == $i)
            $phpWord->setValue('m9_5_1'.$i, $checked);
        else
            $phpWord->setValue('m9_5_1'.$i, $unchecked);
    }

    $str="...............";
    if($row->prof_9_5_1_year !="")
        $str=$row->prof_9_5_1_year;
    $phpWord->setValue('m9_5_1yy', $str);

    $str="........";
    if($row->prof_9_5_1_date != null){
        $arr_date=getDateProvinceAsKhmer($row->prof_9_5_1_date);
//                $this->get_date_province_as_khmer($row->prof_9_5_1_date);
        $str=__('general.k_date3').$arr_date['day']."  ";
        $str.=__('general.k_month').__("month_".$arr_date["month"])."  ";
        $str.=__('general.k_year').$arr_date['year'];
    }
    $phpWord->setValue('m9_5_1date', $str);

    for($i=1;$i<=3;$i++){
        if($row->prof_9_5_1_reg_with == $i)
            $phpWord->setValue('m9_5_1reg'.$i, $checked);
        else
            $phpWord->setValue('m9_5_1reg'.$i, $unchecked);
    }

    for($j=1;$j<=2;$j++)
        for($i=1;$i<=2;$i++){
            $v="prof_9_6_".$j;
            if($row->$v == $i)
                $phpWord->setValue('m9_6_'.$j.$i, $checked);
            else
                $phpWord->setValue('m9_6_'.$j.$i, $unchecked);
        }

    $str="....................................";
    if($row->prof_9_6_2_if_have !="")
        $str=$row->prof_9_6_2_if_have;
    $phpWord->setValue('m9_6_2b', $str);

    $str=".....................................";
    if($row->prof_9_6_2_if_have_reason !="")
        $str=$row->prof_9_6_2_if_have_reason;
    $phpWord->setValue('m9_6_2r', $str);

    $str=".....................................";
    if($row->prof_9_6_2_if_have_when !="")
        $str=$row->prof_9_6_2_if_have_when;
    $phpWord->setValue('m9_6_2w', $str);

    for($i=1;$i<=2;$i++){
        if($row->prof_9_7 == $i)
            $phpWord->setValue('m9_7'.$i, $checked);
        else
            $phpWord->setValue('m9_7'.$i, $unchecked);
    }
    $arr=array(3=>'a', 4=>'b', 5=>'c', 6=>'d', 7=>'f');
    for($j=3;$j<=7;$j++)
        for($i=1;$i<=2;$i++){
            $v="prof_9_7_".$arr[$j];
            if($row->$v == $i)
                $phpWord->setValue('m9_7_'.$arr[$j].$i, $checked);
            else
                $phpWord->setValue('m9_7_'.$arr[$j].$i, $unchecked);
            if($j<7){
                $str=".............";
                $v="prof_9_7_".$arr[$j]."_num";
                if($row->$v > 0)
                    $str=$row->$v;
                $phpWord->setValue('m9_7_'.$arr[$j].'n', $str);
            }
        }

    if($row->prof_9_7_g == 1)
        $phpWord->setValue('m9_7_g1', $checked);
    else
        $phpWord->setValue('m9_7_g1', $unchecked);

    $str="...............................................................................................................................";
    /*if($row->prof_9_7_e !="")
        $str=$row->prof_9_7_e;
    $phpWord->setValue('m9_7_e', $str);*/
    for($j=1;$j<=11;$j++){
        //for($i=1;$i<=2;$i++){
        $v="prof_9_7_e".$j;
        if($row->$v == 1)
            $phpWord->setValue('m9_7_e'.$j, $checked);
        else
            $phpWord->setValue('m9_7_e'.$j, $unchecked);
    }
    $str="......................................................";
    if($row->prof_9_7_e11t != "")
        $str=$row->prof_9_7_e11t;
    $phpWord->setValue('m9_7_e11t', $str);


    for($i=1;$i<=2;$i++){
        if($row->prof_9_8 == $i)
            $phpWord->setValue('m9_8'.$i, $checked);
        else
            $phpWord->setValue('m9_8'.$i, $unchecked);
    }

    $str=".............";
    if($row->prof_9_8_1_num >0)
        $str=$row->prof_9_8_1_num;
    $phpWord->setValue('m9_8_1n', $str);

    /*for($i=1;$i<=2;$i++){
        if($row->prof_9_8_1 == $i)
            $phpWord->setValue('m9_8_1'.$i, $checked);
        else
            $phpWord->setValue('m9_8_1'.$i, $unchecked);
    }*/

    $arr=array(1=>'a', 2=>'b', 3=>'c', 4=>'d', 5=>'e', 6=>'f', 7=>'g', 8=>'h', 9=>'i', 10=>'j', 11=>'k');
    for($i=1;$i<=11;$i++){
        $v="prof_9_8_1_1".$arr[$i];
        if($row->$v == 1)
            $phpWord->setValue('m9_8_1_1'.$arr[$i], $checked);
        else
            $phpWord->setValue('m9_8_1_1'.$arr[$i], $unchecked);
    }
    $str="..................................................................................";
    if($row->prof_9_8_1_1k_text > 0)
        $str=$row->prof_9_8_1_1k_text;
    $phpWord->setValue('m9_8_1_1k_text', $str);

    $str="...........................................................................................................................................";
    /*if($row->prof_9_8_1_1 >0)
        $str=$row->prof_9_8_1_1;
    $phpWord->setValue('m9_8_1_1', $str);
    */
    for($j=2;$j<=5;$j++)
        for($i=1;$i<=2;$i++){
            $v="prof_9_8_1_".$j;
            if($row->$v == $i)
                $phpWord->setValue('m9_8_1_'.$j.$i, $checked);
            else
                $phpWord->setValue('m9_8_1_'.$j.$i, $unchecked);
        }

    $str="...............";
    if($row->prof_9_8_2 >0)
        $str=$row->prof_9_8_2;
    $phpWord->setValue('m9_8_2', $str);

    $arr=array(1=>'a', 2=>'b', 3=>'c', 4=>'d', 5=>'e', 6=>'f', 7=>'g', 8=>'h', 9=>'i', 10=>'j', 11=>'k');
    for($i=1;$i<=11;$i++){
        $v="prof_9_8_2_1".$arr[$i];
        if($row->$v == 1)
            $phpWord->setValue('m9_8_2_1'.$arr[$i], $checked);
        else
            $phpWord->setValue('m9_8_2_1'.$arr[$i], $unchecked);
    }

    $str="..................................................................................";
    if($row->prof_9_8_2_1k_text > 0)
        $str=$row->prof_9_8_2_1k_text;
    $phpWord->setValue('m9_8_2_1f_text', $str);

    $str=".........................................................................................................................";
    if($row->prof_9_8_2_2 !="")
        $str=$row->prof_9_8_2_2;
    $phpWord->setValue('m9_8_2_2', $str);

    for($i=1;$i<=2;$i++){
        if($row->prof_9_8_2_3 == $i)
            $phpWord->setValue('m9_8_2_3'.$i, $checked);
        else
            $phpWord->setValue('m9_8_2_3'.$i, $unchecked);
    }
    $str="............";
    if($row->prof_9_8_2_3_num >0)
        $str=$row->prof_9_8_2_3_num;
    $phpWord->setValue('m9_8_2_3n', $str);

    $str="............";
    if($row->prof_9_8_2_4 >0)
        $str=$row->prof_9_8_2_4;
    $phpWord->setValue('m9_8_2_4', $str);
    $str="............";
    if($row->prof_9_8_2_4_num >0)
        $str=$row->prof_9_8_2_4_num;
    $phpWord->setValue('m9_8_2_4n', $str);


    for($i=1;$i<=2;$i++){
        if($row->prof_9_8_2_5 == $i)
            $phpWord->setValue('m9_8_2_5'.$i, $checked);
        else
            $phpWord->setValue('m9_8_2_5'.$i, $unchecked);
    }
    $str="............";
    if($row->prof_9_8_2_5_num >0)
        $str=$row->prof_9_8_2_5_num;
    $phpWord->setValue('m9_8_2_5n', $str);



    for($i=1;$i<=2;$i++){
        if($row->prof_9_9 == $i)
            $phpWord->setValue('m9_9'.$i, $checked);
        else
            $phpWord->setValue('m9_9'.$i, $unchecked);
    }

    for($i=1;$i<=5;$i++){
        $v="prof_9_9_1".$i;
        if($row->$v == 1)
            $phpWord->setValue('m9_9_1'.$i, $checked);
        else
            $phpWord->setValue('m9_9_1'.$i, $unchecked);
    }
    $str="..................................................................................";
    if($row->prof_9_9_15t != "")
        $str=$row->prof_9_9_15t;
    $phpWord->setValue('m9_9_15t', $str);

    for($i=1;$i<=2;$i++){
        if($row->prof_9_9_2 == $i)
            $phpWord->setValue('m9_9_2'.$i, $checked);
        else
            $phpWord->setValue('m9_9_2'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->prof_9_9_3 == $i)
            $phpWord->setValue('m9_9_3'.$i, $checked);
        else
            $phpWord->setValue('m9_9_3'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->prof_9_10 == $i)
            $phpWord->setValue('m9_10'.$i, $checked);
        else
            $phpWord->setValue('m9_10'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->prof_9_10_1 == $i)
            $phpWord->setValue('m9_10_1'.$i, $checked);
        else
            $phpWord->setValue('m9_10_1'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->prof_9_10_2 == $i)
            $phpWord->setValue('m9_10_2'.$i, $checked);
        else
            $phpWord->setValue('m9_10_2'.$i, $unchecked);
    }

    if($inspection->insp_type > 0)
    {
        for($i=1;$i<=4;$i++){//m9 conclusion
            if($row->prof_9_conclusion == $i){
                $phpWord->setValue('m9_con'.$i, $checked);
            }
            else $phpWord->setValue('m9_con'.$i, $unchecked);
        }

        $str=__('g2.2_ocomment_sample')." ".__('g2.2_ocomment_sample2');
        if($row->officer_comment != ""){
            $str=$row->officer_comment;
        }
        $phpWord->setValue('m9_comment', $str);
    }

}
function exportMenu10($inspection, $phpWord)
{
    $checked = '<w:sym w:font="Wingdings" w:char="F0FE"/>';
    $unchecked = '<w:sym w:font="Wingdings" w:char="F0A8"/>';
    $arr_a=array();
    for($i=0;$i<=25;$i++){
        $arr_a[$i]=$unchecked;
    }
    /** ==============Menu 10===========================**/
    $row = $inspection->menu10;
    /** 10_1 */
    for($i=1;$i<=2;$i++){
        if($row->worker_10_1 == $i)
            $phpWord->setValue('m10_1'.$i, $checked);
        else
            $phpWord->setValue('m10_1'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->worker_10_1_1 == $i)
            $phpWord->setValue('m10_1_1'.$i, $checked);
        else
            $phpWord->setValue('m10_1_1'.$i, $unchecked);
    }
    $str="............";
    $v="worker_10_1_1_num";
    if($row->$v > 0)
        $str=$row->$v;
    $phpWord->setValue('m10_1_1n', $str);
    $str="............";
    $v="worker_10_1_1_female";
    if($row->$v > 0)
        $str=$row->$v;
    $phpWord->setValue('m10_1_1nf', $str);
    $str=".....................";
    $v="worker_10_1_1_reg_no";
    if($row->$v !="")
        $str=$row->$v;
    $phpWord->setValue('m10_1_1regn', $str);
    $str="........";
    $str2="........";
    $str3="........";
    $v="worker_10_1_1_reg_date";
    if($row->$v !="0000-00-00"){
        $str=date_format(date_create($row->$v), "d");
        $str2=date_format(date_create($row->$v), "m");
        $str3=date_format(date_create($row->$v), "Y");
    }
    $phpWord->setValue('m10_1_1regd', $str);
    $phpWord->setValue('m10_1_1regm', $str2);
    $phpWord->setValue('m10_1_1regy', $str3);
    /** 10_1_2 */
    for($i=1;$i<=2;$i++){
        if($row->worker_10_1_2 == $i)
            $phpWord->setValue('m10_1_2'.$i, $checked);
        else
            $phpWord->setValue('m10_1_2'.$i, $unchecked);
    }
    $str="............";
    $v="worker_10_1_2_num";
    if($row->$v > 0)
        $str=$row->$v;
    $phpWord->setValue('m10_1_2n', $str);
    $str="............";
    $v="worker_10_1_2_female";
    if($row->$v > 0)
        $str=$row->$v;
    $phpWord->setValue('m10_1_2nf', $str);
    $str=".....................";
    $v="worker_10_1_2_reg_no";
    if($row->$v !="")
        $str=$row->$v;
    $phpWord->setValue('m10_1_2regn', $str);
    $str="........";
    $str2="........";
    $str3="........";
    $v="worker_10_1_2_reg_date";
    if($row->$v !="0000-00-00"){
        $str=date_format(date_create($row->$v), "d");
        $str2=date_format(date_create($row->$v), "m");
        $str3=date_format(date_create($row->$v), "Y");
    }
    $phpWord->setValue('m10_1_2regd', $str);
    $phpWord->setValue('m10_1_2regm', $str2);
    $phpWord->setValue('m10_1_2regy', $str3);


    /** 10_2_2 */
    for($i=1;$i<=2;$i++){
        if($row->worker_10_2 == $i)
            $phpWord->setValue('m10_2'.$i, $checked);
        else
            $phpWord->setValue('m10_2'.$i, $unchecked);
    }

    for($j=1;$j<=2;$j++)
        for($i=1;$i<=2;$i++){
            $v="worker_10_2_".$j;
            if($row->$v == $i)
                $phpWord->setValue('m10_2_'.$j.$i, $checked);
            else
                $phpWord->setValue('m10_2_'.$j.$i, $unchecked);
        }

    /** 10_3 */
    /** =======================Way 3 */
    $totalEmployee = $inspection->totalEmployeeGarment();
    $w103_3date="";
    if($row->w103_3){
        if($row->w103_3d !="0000-00-00" || $row->w103_3d != null){
            $arr_date3=getDateProvinceAsKhmer($row->w103_3d);
            if(is_array($arr_date3))
                $w103_3date= __('g10.worker_10_3date').": ".__('general.k_date3').$arr_date3['day']." ".__('general.k_month').$arr_date3['month']." ".__("general.k_year").$arr_date3['year'];
        }
    }

    //dd($inspection->totalEmployeeGarment()['total_for']);
    $totalEmployeeFor= $totalEmployee['total_for'];
    $w103_4 = $totalEmployeeFor - $row->w103_2 - $row->w103_3;
    $w103_4 = $w103_4 < 0? 0: $w103_4;
    $phpWord->setValue('m103_1', Num2Unicode($totalEmployeeFor));
    $phpWord->setValue('m103_2', Num2Unicode($row->w103_2));
    $phpWord->setValue('m103_3', Num2Unicode($row->w103_3));
    $phpWord->setValue('m103_3date', Num2Unicode($w103_3date));
    $phpWord->setValue('m103_4', Num2Unicode($w103_4));

    if($w103_4 > 0){
        $phpWord->setValue('m10_32', $checked);
        $phpWord->setValue('m10_31', $unchecked);
    }
    else{
        $phpWord->setValue('m10_31', $checked);
        $phpWord->setValue('m10_32', $unchecked);
    }
//        for($i=1;$i<=2;$i++){
//            if($row->worker_10_3 == $i)
//                $phpWord->setValue('m10_3'.$i, $checked);
//            else
//                $phpWord->setValue('m10_3'.$i, $unchecked);
//        }
//        $get103Data=get103Data_v2($inspection);
//        $get104Data=get104Data($inspection);
//
//        $w103_3=$row->worker_10_3_have_num_old_style;
//        $w103_5=$get103Data['w103_1'] - $get103Data['w103_2'] -$w103_3 - $get103Data['w103_4'];
//        $w103_4d="";
//        if($get103Data['w103_4'] > 0){
//            $arr_date=getDateProvinceAsKhmer($get103Data['w103_4d']);
////$this->get_date_province_as_khmer($get103Data['w103_4d']);
//            $w103_4d=__('menu10.worker_10_3date').": ".__('general.k_date3').$arr_date['day']." ".__('general.k_month').$arr_date['month']." ".__("general.k_year").$arr_date['year'];
//        }
//
//        $phpWord->setValue('m103_1', $get103Data['w103_1']);
//        $phpWord->setValue('m103_2', $get103Data['w103_2']);
//        //$phpWord->setValue('m103_3', $w103_3);
//        $phpWord->setValue('m103_4', $get103Data['w103_4']);
//        $phpWord->setValue('m103_4date', $w103_4d);
//        $phpWord->setValue('m103_5', $w103_5);

    /** ================= 10_4 */
    for($i=1;$i<=2;$i++){
        if($row->worker_10_4 == $i)
            $phpWord->setValue('m10_4'.$i, $checked);
        else
            $phpWord->setValue('m10_4'.$i, $unchecked);
    }
    $w104_4date ="";
    if($row->w104_4){
        if($row->w104_4d !="0000-00-00" || $row->w104_4d != null){
            $arr_date4= getDateProvinceAsKhmer($row->w104_4d);
            if(is_array($arr_date4))
                $w104_4date= __('g10.worker_10_3date').": ".__('general.k_date3').$arr_date4['day']." ".__('general.k_month').$arr_date4['month']." ".__("general.k_year").$arr_date4['year'];
        }
    }


    $totalEmployeeKhmer= $totalEmployee['total_khmer'];
    $w104_5= $totalEmployeeKhmer - $row->w104_2 - $row->w104_3 - $row->w104_4;
    $w104_5 = $w104_5 < 0? 0: $w104_5;
    $phpWord->setValue('m104_1', Num2Unicode($totalEmployeeKhmer));
    $phpWord->setValue('m104_2', Num2Unicode($row->w104_2));
    $phpWord->setValue('m104_3', Num2Unicode($row->w104_3));
    $phpWord->setValue('m104_4', Num2Unicode($row->w104_4));
    $phpWord->setValue('m104_4date', Num2Unicode($w104_4date));
    $phpWord->setValue('m104_5', Num2Unicode($w104_5));
//        for($i=1;$i<=2;$i++){
//            if($row->worker_10_4 == $i)
//                $phpWord->setValue('m10_4'.$i, $checked);
//            else
//                $phpWord->setValue('m10_4'.$i, $unchecked);
//        }
//
//        $w104_3=$row->worker_10_4_have_num_old_style;
//        $w104_5=$get104Data['w104_1'] - $get104Data['w104_2'] -$w104_3 - $get104Data['w104_4'];
//        $w104_4d="";
//        if($get104Data['w104_4'] > 0){
//            $arr_date= getDateProvinceAsKhmer($get104Data['w104_4d']);
////                $this->get_date_province_as_khmer($get104Data['w104_4d']);
//            $w104_4d=__('menu10.worker_10_3date').": ".__('general.k_date3').$arr_date['day']." ".__('general.k_month').$arr_date['month']." ".__("general.k_year").$arr_date['year'];
//        }
//
//        $phpWord->setValue('m104_1', $get104Data['w104_1']);
//        $phpWord->setValue('m104_2', $get104Data['w104_2']);
//        $phpWord->setValue('m104_3', $w104_3);
//        $phpWord->setValue('m104_4', $get104Data['w104_4']);
//        $phpWord->setValue('m104_4date', $w104_4d);
//        $phpWord->setValue('m104_5', $w104_5);

    if($inspection->insp_type >0)
    {
        for($i=1;$i<=4;$i++){//m10 conclusion
            if($row->worker_10_conclusion == $i){
                $phpWord->setValue('m10_con'.$i, $checked);
            }
            else $phpWord->setValue('m10_con'.$i, $unchecked);
        }

        $str=__('g2.2_ocomment_sample')." ".__('g2.2_ocomment_sample2');
        if($row->officer_comment != ""){
            $str=$row->officer_comment;
        }
        $phpWord->setValue('m10_comment', $str);
    }

}
function exportMenu11($inspection, $phpWord)
{
    /** ==============Menu 11===========================*/
    $arr_a = getData11_A($inspection);
    $arr_b = getData11_B($inspection);
    $arr_c = getData11_C($inspection);
    $arr_d = getData11_D($inspection);
    $arr_e = getData11_E($inspection);
    $arr_f = getData11_F($inspection);

//        $arr_a= array();
//        $arr_b= array();
//        $arr_c= array();
//        $arr_d= array();
//        $arr_e= array();
//        $arr_f= array();
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
    $phpWord->setValue('menu11_a', $text_a);
    $phpWord->setValue('menu11_b', $text_b);
    $phpWord->setValue('menu11_c', $text_c);
    $phpWord->setValue('menu11_d', $text_d);
    $phpWord->setValue('menu11_e', $text_e);
    $phpWord->setValue('menu11_f', $text_f);

}
function exportMenu12($inspection, $phpWord)
{
    /** ==============Menu 12===========================*/
    $arr_b = getData12_B($inspection);
    $arr_c = getData12_C($inspection);
    $text_b = "     ";
    $text_c = "     ";

    if(count($arr_b) > 0){
        $i=1;
        foreach($arr_b as $item){
            if($item != ""){
                $text_b.=Num2Unicode($i).". ".$item."<w:br/>";
                $i++;
            }
        }
    }
    if(sizeof($arr_c) > 0){
        for($i=0; $i< sizeof($arr_c); $i++){
            $j=$i+1;
            $text_c.=Num2Unicode($j).". ".$arr_c[$i]."<w:br/>";
        }
    }
    $phpWord->setValue('menu12_a', "");
    $phpWord->setValue('menu12_b', $text_b);
    $phpWord->setValue('menu12_c', $text_c);
    //$phpWord->setValue('menu12_d', "");
    //$phpWord->setValue('menu12_e', "");
    //$phpWord->setValue('menu12_f', "");

}
function exportMenu13($inspection, $phpWord)
{
    /** ==============Menu 13===========================*/
    $row13= $inspection->menu13;
    $m13_1=str_replace("<br />", "<w:br/>", $row13->sug_employer);
    $m13_2=str_replace("<br />", "<w:br/>", $row13->sug_employee);
    $phpWord->setValue('menu13_1', $m13_1);//echo $row13->sug_employer;
    $phpWord->setValue('menu13_2', $m13_2);//echo $row13->sug_employee;

}
function exportMenu14($inspection, $phpWord)
{
    /** ==============Menu 14: A, B, C, D, E, F===========*/
//        $arr_a = array();
//        $arr_b = array();
//        $arr_c = array();
//        $arr_d = array();
//        $arr_e = array();
//        $arr_f = array();
    $arr_a = getData14_A($inspection);
    $arr_b = getData14_B($inspection);
    $arr_c = getData14_C($inspection);
    $arr_d = getData14_D($inspection);
    $arr_e = getData14_E($inspection);
    $arr_f = getData14_F($inspection);
    $text_a = "     ";
    $text_b = "     ";
    $text_c = "     ";
    $text_d = "     ";
    $text_e = "     ";
    $text_f = "     ";
    $i = 1;
    if ($arr_a != null)
        foreach ($arr_a as $item) {
            if ($item != "") {
                $text_a .= Num2Unicode($i) . ". " . $item . "<w:br/>";
                $i++;
            }
        }
    $i = 1;
    if ($arr_b != null)
        foreach ($arr_b as $item) {
            if ($item != "") {
                $text_b .= Num2Unicode($i) . ". " . $item . "<w:br/>";
                $i++;
            }
        }
    $i = 1;
    if ($arr_c != null)
        foreach ($arr_c as $item) {
            if ($item != "") {
                $text_c .= Num2Unicode($i) . ". " . $item . "<w:br/>";
                $i++;
            }
        }

    $i = 1;
    if ($arr_d != null)
        foreach ($arr_d as $item) {
            if ($item != "") {
                $text_d .= Num2Unicode($i) . ". " . $item . "<w:br/>";
                $i++;
            }
        }
    $i = 1;
    if ($arr_e != null)
        foreach ($arr_e as $item) {
            if ($item != "") {
                $text_e .= Num2Unicode($i) . ". " . $item . "<w:br/>";
                $i++;
            }
        }

    $i = 1;
    if ($arr_f != null)
        foreach ($arr_f as $item) {
            $text_f .= Num2Unicode($i) . ". " . $item . "<w:br/>";
            $i++;
        }
    $phpWord->setValue('menu14_a', $text_a);
    $phpWord->setValue('menu14_b', $text_b);
    $phpWord->setValue('menu14_c', $text_c);
    $phpWord->setValue('menu14_d', $text_d);
    $phpWord->setValue('menu14_e', $text_e);
    $phpWord->setValue('menu14_f', $text_f);
}

function exportOtherMenu1($inspection, $phpWord)
{
    $checked = '<w:sym w:font="Wingdings" w:char="F0FE"/>';
    $unchecked = '<w:sym w:font="Wingdings" w:char="F0A8"/>';
    $arr_a=array();
    for($i=0;$i<=25;$i++){
        $arr_a[$i]=$unchecked;
    }
    //=============Inspection Date, Time
    //$group_id = $row->inspectionBusinessActivity->group_id;
    $row = $inspection->otherMenu1;

    $company_id=$row->company_id;
    $insp_date=$row->insp_date;
    $insp_start_time=$row->insp_start_time;
    $insp_end_time=$row->insp_end_time;
    $province_id=$row->business_province;

    $arr_datetime=getDateTimeAsWord($row->insp_date, $row->insp_start_time);
    $phpWord->setValue('insp_date', $arr_datetime["txt_date"]);
    $phpWord->setValue('insp_stime', $arr_datetime["txt_time"]);
    $phpWord->setValue('insp_group', Num2Unicode($row->group_name));
    $phpWord->setValue('com_name_kh', xmlEntities($row->company_name_khmer));
    $phpWord->setValue('com_tin', Num2Unicode($row->company_tin));

    $phpWord->setValue('com_license_no', Num2Unicode($row->company_register_number));
    $arr_date=getDateProvinceAsKhmer($row->registration_date);

    $regMonth= $arr_date["month"] != null? __("general.month_".$arr_date["month"]) : "";
    $phpWord->setValue('com_license_day', $arr_date["day"]);
    $phpWord->setValue('com_license_month', $regMonth);
    $phpWord->setValue('com_license_year', $arr_date["year"]);

    $bus_activity="....................................................................";
    $phpWord->setValue('bus_activity', $bus_activity);
    $phpWord->setValue('business_activity', $inspection->inspectionBusinessActivity->bus_khmer_name);

    $com_com_statute_have=5;
    for($i=1;$i<=2;$i++){
        if($com_com_statute_have == $i)
            $phpWord->setValue('com_com_statute'.$i, $checked);
        else
            $phpWord->setValue('com_com_statute'.$i, $unchecked);
    }

    $phpWord->setValue('com_1_1_addr_house_no', xmlEntities(Num2Unicode($row->business_house_no)));
    $phpWord->setValue('com_1_1_addr_street', xmlEntities(Num2Unicode($row->business_street)));
    $phpWord->setValue('com_1_1_addr_group', xmlEntities(Num2Unicode($row->business_group)));
    $phpWord->setValue('com_1_1_addr_village', $row->village != null? $row->village->vil_khname:"");
    $phpWord->setValue('com_1_1_addr_commune', $row->commune != null? $row->commune->com_khname:"");
    $phpWord->setValue('com_1_1_addr_district', $row->district != null? $row->district->dis_khname:"");
    $phpWord->setValue('com_1_1_addr_province', $row->province != null? $row->province->pro_khname:"");
    $phpWord->setValue('com_1_1_phone', Num2Unicode($row->company_phone_number));
    $phpWord->setValue('com_1_1_email', $row->company_email);

    $phpWord->setValue('com_1_1_owner_name', xmlEntities($row->owner_khmer_name));
    $phpWord->setValue('com_1_1_owner_nationality', $row->ownerNationality->nationality_kh);

    $phpWord->setValue('com_1_1_ceo_name', xmlEntities($row->director_khmer_name));
    $phpWord->setValue('com_1_1_ceo_nationality', $row->directorNationality->nationality_kh);

    $enterprise_name= $row->enterprise_name != ""? xmlEntities($row->enterprise_name): ".....................................................................";
    $phpWord->setValue('com_1_1_enterprise_name', $enterprise_name);
    //dd($row->villageEnterprise);
    $enterprise_address=__('g1.enterprise_addr2');
    if($row->enterprise_addr == 1){//local
        $str="...........";
        if($row->enterprise_house_no !="")
            $str= Num2Unicode($row->enterprise_house_no);
        $enterprise_address.= __('g1.enterprise_house_no')." ".$str;
        $str="...........";
        if($row->enterprise_street !="")
            $str= Num2Unicode($row->enterprise_street);
        $enterprise_address.= __('g1.enterprise_street')." ".$str;
        $str="...........";
        if($row->enterprise_krom !="")
            $str= Num2Unicode($row->enterprise_krom);
        $enterprise_address.= __('g1.enterprise_group')." ".$str;

        if(!empty($row->villageEnterprise)){
            $enterprise_address.= " ".__('g1.enterprise_village')." ".$row->villageEnterprise->vil_khname;
        }
        if(!empty($row->communeEnterprise)){
            $enterprise_address.= " ".__('g1.enterprise_commune')." ".$row->communeEnterprise->com_khname;
        }
        if(!empty($row->districtEnterprise)){
            $enterprise_address.= " ".__('g1.enterprise_district')." ".$row->districtEnterprise->dis_khname;
        }
        if(!empty($row->provinceEnterprise)){
            $enterprise_address.= " ".__('g1.enterprise_province')." ".$row->provinceEnterprise->pro_khname;
        }
    }
    else{//abroad
        $enterprise_address.= $row->enterprise_abroad;
    }
    $phpWord->setValue('enterprise_address', xmlEntities($enterprise_address));


    /** ==============Karey============== */
    for($i=1;$i<=2;$i++){
        if($row->com_1_1_karey_reg == $i)
            $phpWord->setValue('karey'.$i, $checked);
        else
            $phpWord->setValue('karey'.$i, $unchecked);
    }

    $br = "</w:t><w:br/><w:t>";
    $karey_name = "";
    if($row->karey != null){
        $i=1;
        foreach($row->karey as $rowk){
            $karey_contract= $unchecked." ".__('general.k_have')."  ".$unchecked." ".__('general.k_none');
            if($rowk->karey_contract == 1){
                $karey_contract= $checked." ".__('general.k_have')."  ".$unchecked." ".__('general.k_none');
            }
            elseif($rowk->karey_contract == 2){
                $karey_contract= $unchecked." ".__('general.k_have')."  ".$checked." ".__('general.k_none');
            }
            $karey_name.=Num2Unicode($i).".".__("general.k_name")." ".xmlEntities($rowk->karey_name);
            $karey_name.="     ".__('general.karey_contract')."   ".$karey_contract;
            $karey_name.="<w:br/>";
            $i++;
        }
    }

    if($karey_name != null){
        $karey_name = $br.substr($karey_name, 0, -7);
    }
    $phpWord->setValue('karey_name', $karey_name);

    for($i=1;$i<=2;$i++){
        if($row->karey_com == $i)
            $phpWord->setValue('karey_com'.$i, $checked);
        else
            $phpWord->setValue('karey_com'.$i, $unchecked);
    }
    $str=".............................................";
    if($row->karey_com_name != "")
        $str=xmlEntities($row->karey_com_name);
    $phpWord->setValue('karey_com_name', $str);
    $str=".............................................";
    if($row->karey_com_brand != "")
        $str=xmlEntities($row->karey_com_brand);
    $phpWord->setValue('karey_com_brand', $str);

    $str="................................";
    if($row->main_product != "")
        $str= xmlEntities($row->main_product);
    $phpWord->setValue('main_product', $str);
    $str="................................";
    //dd($inspection->id);
    $str= xmlEntities(companyBrand($inspection->id, $row->brand_product));
    $phpWord->setValue('brand_product', $str);
    $str="................................";
    if($row->country_product_out != "")
        $str= xmlEntities($row->country_product_out);
    $phpWord->setValue('country_product_out', $str);

    /** Member of Associate 23-11-2021 */
    for($i=1;$i<=2;$i++){
        if($row->memberof_associate == $i)
            $phpWord->setValue('memberof_associate'.$i, $checked);
        else
            $phpWord->setValue('memberof_associate'.$i, $unchecked);
    }
    if($row->memberof_camfeba == 1)
        $phpWord->setValue('memberof_camfeba', $checked);
    else
        $phpWord->setValue('memberof_camfeba', $unchecked);

    if($row->memberof_gmac == 1)
        $phpWord->setValue('memberof_gmac', $checked);
    else
        $phpWord->setValue('memberof_gmac', $unchecked);

    if($row->memberof_cfa == 1)
        $phpWord->setValue('memberof_cfa', $checked);
    else
        $phpWord->setValue('memberof_cfa', $unchecked);

    if($row->memberof_other == 1)
        $phpWord->setValue('memberof_other', $checked);
    else
        $phpWord->setValue('memberof_other', $unchecked);
    $phpWord->setValue('memberof_other_text', $row->memberof_other_text);

    /** ========================= */
    $phpWord->setValue('article_of_company', $row->articleOfCompany->article_name);
    for($i=1;$i<=11;$i++){
        if($row->com_1_1_weekly_leave_id == $i){
            $phpWord->setValue('weekly_leave_'.$i, $checked);
        }
        else $phpWord->setValue('weekly_leave_'.$i, $unchecked);
    }

    for($i=1;$i<=3;$i++){
        if($row->last_update_info == $i)
            $phpWord->setValue('last_update'.$i, $checked);
        else
            $phpWord->setValue('last_update'.$i, $unchecked);
    }
    /** =======Menu 1.2======================== */
    $arr121=array();
    $arr121[0]=array("1_2_1_name"=>"", "1_2_1_nat"=>"", "1_2_1_card"=>"", "1_2_1_add"=> "", "1_2_1_share"=> "");
    $query_compos= Table1CompanyComposition::where(["inspection_id" => $inspection->id, "comc_typeof_composition" => 1])->get();
    if(count($query_compos) > 0){
        $j=0;
        $i=1;
        foreach($query_compos as $rowc){
            $arr121[$j] = array(
                "1_2_1_name" => Num2Unicode($i).". ". xmlEntities($rowc->comc_com_fullname),
                "1_2_1_nat" => getNationality($rowc->comc_com_nationality),
                "1_2_1_card" => Num2Unicode($rowc->comc_com_id_card_no),
                "1_2_1_add" => xmlEntities($rowc->comc_com_address),
                "1_2_1_share" => Num2Unicode($rowc->comc_com_numof_shares));
            $j++;
            $i++;
        }
    }
    $phpWord->cloneRowAndSetValues('1_2_1_name', $arr121);
    /** =======Menu 1.2======================== */
    $m122=array();
    $m122[0]=array("m122_name"=>"", "m122_nat"=>"", "m122_card"=>"", "m122_position"=> "", "m122_add"=> "");
    $query_compos2=Table1CompanyComposition::where(["inspection_id" => $inspection->id, "comc_typeof_composition" => 2])->get();
    if(count($query_compos2) > 0){
        $i=1;
        $j=0;
        foreach($query_compos2 as $rowc2){

            $m122[$j]=array(
                "m122_name"=>Num2Unicode($i).". ". xmlEntities($rowc2->comc_com_fullname),
                "m122_nat"=> getNationality($rowc2->comc_com_nationality),
                "m122_card"=>Num2Unicode($rowc2->comc_com_id_card_no),
                "m122_position"=>$rowc2->comc_com_role,
                "m122_add"=>xmlEntities(Num2Unicode($rowc2->comc_com_address))
            );
            $i++;
            $j++;
        }
    }
    $phpWord->cloneRowAndSetValues('m122_name', $m122);

}
function exportOtherMenu2($inspection, $phpWord)
{
    $checked = '<w:sym w:font="Wingdings" w:char="F0FE"/>';
    $unchecked = '<w:sym w:font="Wingdings" w:char="F0A8"/>';
    $arr_a=array();
    for($i=0;$i<=25;$i++){
        $arr_a[$i]=$unchecked;
    }
    /** ==============Menu 2===========================*/
    $str="";
    $row2=$inspection->otherMenu2;
    if($row2->admin_2_1_date_open !="0000-00-00"){
        $arr_date=getDateProvinceAsKhmer($row2->admin_2_1_date_open);
        $str=__('general.k_date3').$arr_date['day']."  ";
        $str.=__('general.k_month').__("general.month_".$arr_date["month"])."  ";
        $str.=__('general.k_year').$arr_date['year'];
    }
    $phpWord->setValue('m2_2_1', $str);
    $str=$unchecked." ".__('general.k_have')."  ";
    $str.=$unchecked." ".__('general.k_none')."  ";

    for($i=1;$i<=2;$i++){
        if($row2->admin_2_2_1 == $i)
            $phpWord->setValue('m2_2_1'.$i, $checked);
        else
            $phpWord->setValue('m2_2_1'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row2->admin_2_2_2 == $i)
            $phpWord->setValue('m2_2_2'.$i, $checked);
        else
            $phpWord->setValue('m2_2_2'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row2->admin_2_2_3 == $i)
            $phpWord->setValue('m2_2_3'.$i, $checked);
        else
            $phpWord->setValue('m2_2_3'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row2->admin_2_2_3a == $i)
            $phpWord->setValue('m2_2_3a'.$i, $checked);
        else
            $phpWord->setValue('m2_2_3a'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row2->admin_2_2_4 == $i)
            $phpWord->setValue('m2_2_4'.$i, $checked);
        else
            $phpWord->setValue('m2_2_4'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row2->admin_2_2_5 == $i)
            $phpWord->setValue('m2_2_5'.$i, $checked);
        else
            $phpWord->setValue('m2_2_5'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row2->admin_2_2_6 == $i)
            $phpWord->setValue('m2_2_6'.$i, $checked);
        else
            $phpWord->setValue('m2_2_6'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row2->admin_2_2_6require == $i)
            $phpWord->setValue('m2_2_6r'.$i, $checked);
        else
            $phpWord->setValue('m2_2_6r'.$i, $unchecked);
    }

    if($inspection->insp_type > 0)
    {
        for($i=1;$i<=4;$i++){
            if($row2->admin_2_conclusion == $i){
                $phpWord->setValue('m2_con'.$i, $checked);
            }
            else $phpWord->setValue('m2_con'.$i, $unchecked);
        }

        $str=__('g2.2_ocomment_sample')." ".__('g2.2_ocomment_sample2');
        if($row2->admin_officer_comment != ""){
            $str= textAreaForDisplay($row2->officer_comment);
        }
        $phpWord->setValue('m2_comment', $str);
    }

}
function exportOtherMenu3($inspection, $phpWord)
{
    $checked = '<w:sym w:font="Wingdings" w:char="F0FE"/>';
    $unchecked = '<w:sym w:font="Wingdings" w:char="F0A8"/>';
    $arr_a=array();
    for($i=0;$i<=25;$i++){
        $arr_a[$i]=$unchecked;
    }
    /** ==============Menu 3===========================*/
    $total_emp=0;
    $totalEmployeeKhmer=0;
    $totalEmployeeFor=0;
    $row=$inspection->otherMenu3;
    $totalEmployeeKhmer=$row->emp_3_1_kh_total;
    $totalEmployeeFor=$row->emp_3_1_for_total;
    $total_emp=$totalEmployeeKhmer + $totalEmployeeFor;

    $phpWord->setValue('m3_total_kh', Num2Unicode($row->emp_3_1_kh_total));
    $phpWord->setValue('m3_total_kh_f', Num2Unicode($row->emp_3_1_kh_female));
    $phpWord->setValue('m3_total_for', Num2Unicode($row->emp_3_1_for_total));
    $phpWord->setValue('m3_total_for_f', Num2Unicode($row->emp_3_1_for_female));
    $phpWord->setValue('m3_total_all', Num2Unicode($total_emp));
    $phpWord->setValue('m3_total_all_f', Num2Unicode($row->emp_3_1_kh_female+$row->emp_3_1_for_female));

}
function exportOtherMenu4($inspection, $phpWord)
{
    $checked = '<w:sym w:font="Wingdings" w:char="F0FE"/>';
    $unchecked = '<w:sym w:font="Wingdings" w:char="F0A8"/>';
    $arr_a=array();
    for($i=0;$i<=25;$i++){
        $arr_a[$i]=$unchecked;
    }
    $menu3=$inspection->otherMenu3;
    $total_emp=$menu3->emp_3_1_kh_total + $menu3->emp_3_1_for_total;
    /** ==============Menu 4===========================*/
    $row=$inspection->otherMenu4;
    $phpWord->setValue('m4_1n', $row->iplaw_4_1n);
    for($i=1;$i<=2;$i++){
        if($row->iplaw_4_1 == $i)
            $phpWord->setValue('m4_1'.$i, $checked);
        else
            $phpWord->setValue('m4_1'.$i, $unchecked);
    }

    $str2=".................";
    $str3=".................";
    $str=$unchecked;
    $str=$checked;
    $iplaw_4_2_1_num_per=0;
    if($row->iplaw_4_2_1n > 0){
        $str2=$row->iplaw_4_2_1n;
        if($total_emp == 0) $str3 = 0;
        else{
            $iplaw_4_2_1_num_per=number_format(($row->iplaw_4_2_1n * 100)/$total_emp, 2);
            $str3= $iplaw_4_2_1_num_per;

        }

    }
    //$phpWord->setValue('m4_2_1', $str);
    $phpWord->setValue('m4_2_1n', Num2Unicode($str2));
    $phpWord->setValue('m4_2_1p', Num2Unicode($str3));

    $str4=$str3;
    $str2=".................";
    $str3=".................";
    $str=$unchecked;
    //if($row->iplaw_4_2_2 ==1){
    //$total_emp
    $str=$checked;
    $iplaw_4_2_2_num =  $total_emp - $row->iplaw_4_2_1n;
    if($iplaw_4_2_2_num > 0){
        $str2=$iplaw_4_2_2_num;
        if(is_numeric($str4))
            $str3= 100-$str4;
        else {
            if($total_emp == 0) $str3 = 0;
            else{
                $iplaw_4_2_2_num_per= 100- $iplaw_4_2_1_num_per;
                $str3= number_format($iplaw_4_2_2_num_per, 2);
            }

        }
    }
    //}
    $phpWord->setValue('m4_2_2', $str);
    $phpWord->setValue('m4_2_2n', Num2Unicode($str2));
    $phpWord->setValue('m4_2_2p', Num2Unicode($str3));

//        for($i=1;$i<=2;$i++){
//            if($row->iplaw_4_2_2a == $i)
//                $phpWord->setValue('m4_2_2a'.$i, $checked);
//            else
//                $phpWord->setValue('m4_2_2a'.$i, $unchecked);
//        }
//
    for($i=1;$i<=2;$i++){
        if($row->iplaw_4_2_3 == $i)
            $phpWord->setValue('m4_2_3'.$i, $checked);
        else
            $phpWord->setValue('m4_2_3'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->iplaw_4_2_3_1 == $i)
            $phpWord->setValue('m4_2_3_1'.$i, $checked);
        else
            $phpWord->setValue('m4_2_3_1'.$i, $unchecked);
    }
//
//        $str="......................................";
//        if($row->iplaw_4_2_3_if_have !="")
//            $str=$row->iplaw_4_2_3_if_have;
//        $phpWord->setValue('m4_2_3t', $str);




//        for($i=1;$i<=2;$i++){
//            if($row->iplaw_4_2_4 == $i)
//                $phpWord->setValue('m4_2_4'.$i, $checked);
//            else
//                $phpWord->setValue('m4_2_4'.$i, $unchecked);
//        }
//        for($i=1;$i<=2;$i++){
//            if($row->iplaw_4_2_4_1 == $i)
//                $phpWord->setValue('m4_2_4_1'.$i, $checked);
//            else
//                $phpWord->setValue('m4_2_4_1'.$i, $unchecked);
//        }


    $str="........";
    if($row->iplaw_4_2_3_1totale > 0)
        $str=$row->iplaw_4_2_3_1totale;
    $phpWord->setValue('m4_2_3_1totale', Num2Unicode($str));
    $str="........";
    if($row->iplaw_4_2_3_1totalf > 0)
        $str=$row->iplaw_4_2_3_1totalf;
    $phpWord->setValue('m4_2_3_1totalf', Num2Unicode($str));
    $str="........";
    if($row->iplaw_4_2_3_1sdate != "0000-00-00")
        $str= date2Display($row->iplaw_4_2_3_1sdate);
    $phpWord->setValue('m4_2_3_1sdate', $str);
    $str="........";
    if($row->iplaw_4_2_3_1edate != "0000-00-00")
        $str= date2Display($row->iplaw_4_2_3_1edate);
    $phpWord->setValue('m4_2_3_1edate', $str);
    $str="........";
    if($row->iplaw_4_2_3_1sdate != "0000-00-00" && $row->iplaw_4_2_3_1edate != "0000-00-00"){
        $str= strtotime($row->iplaw_4_2_3_1edate) - strtotime($row->iplaw_4_2_3_1sdate);
        $str=round($str / 86400);
        $str++;
    }
    $phpWord->setValue('m4_total_date', Num2Unicode($str));
    //dd($row->iplaw_4_2_3_1help);
    for($i=1;$i<=2;$i++){
        if($row->iplaw_4_2_3_1help == $i)
            $phpWord->setValue('m4_2_3_1help'.$i, $checked);
        else
            $phpWord->setValue('m4_2_3_1help'.$i, $unchecked);
    }
    $str="........";
    if($row->iplaw_4_2_3_1help_money > 0)
        $str=$row->iplaw_4_2_3_1help_money;
    $phpWord->setValue('m4_2_3_1help_money', Num2Unicode($str));





    if($inspection->insp_type > 0)
    {
        for($i=1;$i<=4;$i++){//m4 conclusion
            if($row->iplaw_4_conclusion == $i){
                $phpWord->setValue('m4_con'.$i, $checked);
            }
            else $phpWord->setValue('m4_con'.$i, $unchecked);
        }
        $str=__('g2.2_ocomment_sample')." ".__('g2.2_ocomment_sample2');
        if($row->iplaw_officer_comment != ""){
            $str=$row->iplaw_officer_comment;
        }
        $phpWord->setValue('m4_comment', $str);
    }


}
function exportOtherMenu5($inspection, $phpWord)
{
    $checked = '<w:sym w:font="Wingdings" w:char="F0FE"/>';
    $unchecked = '<w:sym w:font="Wingdings" w:char="F0A8"/>';
    $arr_a=array();
    for($i=0;$i<=25;$i++){
        $arr_a[$i]=$unchecked;
    }
    $row=$inspection->otherMenu5;
    $str="..............";
    if($row->train_5y > 0)
        $str=$row->train_5y;
    $phpWord->setValue('m5y', Num2Unicode($str));
    $phpWord->setValue('m5y2', Num2Unicode($str));

    for($i=1;$i<=2;$i++){
        if($row->train_5 == $i)
            $phpWord->setValue('m5'.$i, $checked);
        else
            $phpWord->setValue('m5'.$i, $unchecked);
    }
    if($row->train_5_1status == 1)
        $phpWord->setValue('m5_1status', $checked);
    else
        $phpWord->setValue('m5_1status', $unchecked);

    for($i=1;$i<=2;$i++){
        if($row->train_5_1 == $i)
            $phpWord->setValue('m5_1'.$i, $checked);
        else
            $phpWord->setValue('m5_1'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->train_5_1_1 == $i)
            $phpWord->setValue('m5_1_1'.$i, $checked);
        else
            $phpWord->setValue('m5_1_1'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->train_5_1_2 == $i)
            $phpWord->setValue('m5_1_2'.$i, $checked);
        else
            $phpWord->setValue('m5_1_2'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->train_5_1_3 == $i)
            $phpWord->setValue('m5_1_3'.$i, $checked);
        else
            $phpWord->setValue('m5_1_3'.$i, $unchecked);
    }

    if($row->train_5_2status == 1)
        $phpWord->setValue('m5_2status', $checked);
    else
        $phpWord->setValue('m5_2status', $unchecked);
    for($i=1;$i<=4;$i++){
        if($row->train_5_2 == $i)
            $phpWord->setValue('m5_2'.$i, $checked);
        else
            $phpWord->setValue('m5_2'.$i, $unchecked);
    }

    if($inspection->insp_type > 0)
    {
        for($i=1;$i<=4;$i++){//m5 conclusion
            if($row->train_conclusion == $i){
                $phpWord->setValue('m5_con'.$i, $checked);
            }
            else $phpWord->setValue('m5_con'.$i, $unchecked);
        }

        $str=__('g2.2_ocomment_sample')." ".__('g2.2_ocomment_sample2');
        if($row->officer_comment != ""){
            $str=$row->officer_comment;
        }
        $phpWord->setValue('m5_comment', $str);
    }
}
function exportOtherMenu6($inspection, $phpWord)
{
    $checked = '<w:sym w:font="Wingdings" w:char="F0FE"/>';
    $unchecked = '<w:sym w:font="Wingdings" w:char="F0A8"/>';
    $arr_a=array();
    for($i=0;$i<=25;$i++){
        $arr_a[$i]=$unchecked;
    }

    /** ==============Menu 6===========================*/
    $row=$inspection->otherMenu6;
    $str="..............".__('g6.k_reil_dollar');
    if($row->gen_6_1_1_lowest > 0)
        $str=$row->gen_6_1_1_lowest.$row->gen_6_1_1_lowest_cur;
    $phpWord->setValue('m6_1_1L', Num2Unicode($str));

    $str="..............".__('g6.k_reil_dollar');
    if($row->gen_6_1_1_average > 0)
        $str=$row->gen_6_1_1_average.$row->gen_6_1_1_average_cur;
    $phpWord->setValue('m6_1_1a', Num2Unicode($str));

    $str="..............".__('g6.k_reil_dollar');
    if($row->gen_6_1_1_highest > 0)
        $str=$row->gen_6_1_1_highest.$row->gen_6_1_1_highest_cur;
    $phpWord->setValue('m6_1_1h', Num2Unicode($str));

    $str="....................................................................................";
    if($row->gen_6_1_2 != "")
        $str=$row->gen_6_1_2;
    $phpWord->setValue('m6_1_2a', $str);


    $str=$unchecked;
    if($row->gen_6_1_3_1 == 1)
        $str=$checked;
    $phpWord->setValue('m6_1_3_1', $str);
    $str=".......";
    if($row->gen_6_1_3_1_num >0)
        $str=$row->gen_6_1_3_1_num;
    $phpWord->setValue('m6_1_3_1n', Num2Unicode($str));

    $str=".......";
    if($row->gen_6_1_3_1month >0)
        $str=$row->gen_6_1_3_1month;
    $phpWord->setValue('m6_1_3_1m', Num2Unicode($str));
    $str=".......";
    if($row->gen_6_1_3_1day >0)
        $str=$row->gen_6_1_3_1day;
    $phpWord->setValue('m6_1_3_1d', Num2Unicode($str));
    $str=".......";
    if($row->gen_6_1_3_1hour >0)
        $str=$row->gen_6_1_3_1hour;
    $phpWord->setValue('m6_1_3_1h', Num2Unicode($str));

    $str=$unchecked;
    if($row->gen_6_1_3_2 == 1)
        $str=$checked;
    $phpWord->setValue('m6_1_3_2', $str);
    $str=".......";
    if($row->gen_6_1_3_2_num >0)
        $str=$row->gen_6_1_3_2_num;
    $phpWord->setValue('m6_1_3_2n', Num2Unicode($str));

//        $str=$unchecked;
//        if($row->gen_6_1_3_1 == 1)
//            $str=$checked;
//        $phpWord->setValue('m6_1_3_1', $str);
//        $str=".......";
//        if($row->gen_6_1_3_1_num >0)
//            $str=$row->gen_6_1_3_1_num;
//        $phpWord->setValue('m6_1_3_1n', Num2Unicode($str));
//
//        $str=$unchecked;
//        if($row->gen_6_1_3_2 == 1)
//            $str=$checked;
//        $phpWord->setValue('m6_1_3_2', $str);
//        $str=".......";
//        if($row->gen_6_1_3_2_num >0)
//            $str=$row->gen_6_1_3_2_num;
//        $phpWord->setValue('m6_1_3_2n', Num2Unicode($str));

    for($i=1;$i<=2;$i++){
        if($row->gen_6_1_4 == $i)
            $phpWord->setValue('m6_1_4'.$i, $checked);
        else
            $phpWord->setValue('m6_1_4'.$i, $unchecked);
    }

    for($i=1;$i<=3;$i++){
        if($row->gen_6_1_5 == $i)
            $phpWord->setValue('m6_1_5'.$i, $checked);
        else
            $phpWord->setValue('m6_1_5'.$i, $unchecked);
    }
    for($i=1;$i<=3;$i++){
        if($row->gen_6_1_5_1 == $i)
            $phpWord->setValue('m6_1_5_1'.$i, $checked);
        else
            $phpWord->setValue('m6_1_5_1'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_1_6 == $i)
            $phpWord->setValue('m6_1_6'.$i, $checked);
        else
            $phpWord->setValue('m6_1_6'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->gen_6_1_6_1 == $i)
            $phpWord->setValue('m6_1_6_1'.$i, $checked);
        else
            $phpWord->setValue('m6_1_6_1'.$i, $unchecked);
    }
    $str="......";
    if($row->gen_6_1_6_1a > 0)
        $str=Num2Unicode($row->gen_6_1_6_1a);
    $phpWord->setValue('m6_1_6_1a', $str);

    $str="......";
    if($row->gen_6_1_6_2a > 0)
        $str=Num2Unicode($row->gen_6_1_6_2a);
    $phpWord->setValue('m6_1_6_2a', $str);

    for($i=1;$i<=2;$i++){
        if($row->gen_6_1_6_2 == $i)
            $phpWord->setValue('m6_1_6_2'.$i, $checked);
        else
            $phpWord->setValue('m6_1_6_2'.$i, $unchecked);
    }

    for($i=1;$i<=3;$i++){
        if($row->gen_6_1_7 == $i)
            $phpWord->setValue('m6_1_7'.$i, $checked);
        else
            $phpWord->setValue('m6_1_7'.$i, $unchecked);
    }
    $str="......";
    if($row->gen_6_1_7a > 0)
        $str=Num2Unicode($row->gen_6_1_7a);
    $phpWord->setValue('m6_1_7a', $str);

    for($i=1;$i<=3;$i++){
        if($row->gen_6_1_8 == $i)
            $phpWord->setValue('m6_1_8'.$i, $checked);
        else
            $phpWord->setValue('m6_1_8'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_1_9 == $i)
            $phpWord->setValue('m6_1_9'.$i, $checked);
        else
            $phpWord->setValue('m6_1_9'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_1_10_1 == $i)
            $phpWord->setValue('m6_1_10_1'.$i, $checked);
        else
            $phpWord->setValue('m6_1_10_1'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->gen_6_1_10_2 == $i)
            $phpWord->setValue('m6_1_10_2'.$i, $checked);
        else
            $phpWord->setValue('m6_1_10_2'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->gen_6_1_10_3 == $i)
            $phpWord->setValue('m6_1_10_3'.$i, $checked);
        else
            $phpWord->setValue('m6_1_10_3'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->gen_6_1_10_4 == $i)
            $phpWord->setValue('m6_1_10_4'.$i, $checked);
        else
            $phpWord->setValue('m6_1_10_4'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_1_11 == $i)
            $phpWord->setValue('m6_1_11'.$i, $checked);
        else
            $phpWord->setValue('m6_1_11'.$i, $unchecked);
    }
    $str="...........";
    if($row->gen_6_1_11bank_id >0)
        $str=bankName($row->gen_6_1_11bank_id);
    $phpWord->setValue('m6_1_11bank', $str);
    for($i=1;$i<=2;$i++){
        if($row->gen_6_1_11_1 == $i)
            $phpWord->setValue('m6_1_11_1'.$i, $checked);
        else
            $phpWord->setValue('m6_1_11_1'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->gen_6_1_11_2 == $i)
            $phpWord->setValue('m6_1_11_2'.$i, $checked);
        else
            $phpWord->setValue('m6_1_11_2'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->gen_6_1_11_3 == $i)
            $phpWord->setValue('m6_1_11_3'.$i, $checked);
        else
            $phpWord->setValue('m6_1_11_3'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_2 == $i)
            $phpWord->setValue('m6_2'.$i, $checked);
        else
            $phpWord->setValue('m6_2'.$i, $unchecked);
    }
    $str="......";
    if($row->gen_6_2e > 0)
        $str=Num2Unicode($row->gen_6_2e);
    $phpWord->setValue('m6_2e', $str);
    $str="......";
    if($row->gen_6_2ef > 0)
        $str=Num2Unicode($row->gen_6_2ef);
    $phpWord->setValue('m6_2ef', $str);

    for($i=1;$i<=2;$i++){
        if($row->gen_6_2_1 == $i)
            $phpWord->setValue('m6_2_1'.$i, $checked);
        else
            $phpWord->setValue('m6_2_1'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_2_2 == $i)
            $phpWord->setValue('m6_2_2'.$i, $checked);
        else
            $phpWord->setValue('m6_2_2'.$i, $unchecked);
    }
    $str="......";
    if($row->gen_6_2_3a > 0)
        $str=Num2Unicode(timeName($row->gen_6_2_3a));
    $phpWord->setValue('m6_2_3a', $str);
    $str="......";
    if($row->gen_6_2_3b > 0)
        $str=Num2Unicode(timeName($row->gen_6_2_3b));
    $phpWord->setValue('m6_2_3b', $str);
    $str="......";
    if($row->gen_6_2_3b > 0 && $row->gen_6_2_3a > 0 )
        $str=Num2Unicode($row->gen_6_2_3b - $row->gen_6_2_3a);
    $phpWord->setValue('m6_2_3c', $str);

    for($i=1;$i<=2;$i++){
        if($row->gen_6_3 == $i)
            $phpWord->setValue('m6_3'.$i, $checked);
        else
            $phpWord->setValue('m6_3'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_3_1 == $i)
            $phpWord->setValue('m6_3_1'.$i, $checked);
        else
            $phpWord->setValue('m6_3_1'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_3_2 == $i)
            $phpWord->setValue('m6_3_2'.$i, $checked);
        else
            $phpWord->setValue('m6_3_2'.$i, $unchecked);
    }

    for($i=1;$i<=3;$i++){
        if($row->gen_6_4_1_1 == $i)
            $phpWord->setValue('m6_4_1_1'.$i, $checked);
        else
            $phpWord->setValue('m6_4_1_1'.$i, $unchecked);
    }
    for($i=1;$i<=3;$i++){
        if($row->gen_6_4_1_2 == $i)
            $phpWord->setValue('m6_4_1_2'.$i, $checked);
        else
            $phpWord->setValue('m6_4_1_2'.$i, $unchecked);
    }
    $phpWord->setValue('m6_4_1_3', $row->gen_6_4_1_3 );

    /** =================== 6_4_2 */
    for($i=1;$i<=2;$i++){
        if($row->gen_6_4_2 == $i)
            $phpWord->setValue('m6_4_2'.$i, $checked);
        else
            $phpWord->setValue('m6_4_2'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->gen_6_4_2_1 == $i)
            $phpWord->setValue('m6_4_2_1'.$i, $checked);
        else
            $phpWord->setValue('m6_4_2_1'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_4_2_2 == $i)
            $phpWord->setValue('m6_4_2_2'.$i, $checked);
        else
            $phpWord->setValue('m6_4_2_2'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_4_2a == $i)
            $phpWord->setValue('m6_4_2a'.$i, $checked);
        else
            $phpWord->setValue('m6_4_2a'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->gen_6_4_2a_1 == $i)
            $phpWord->setValue('m6_4_2a_1'.$i, $checked);
        else
            $phpWord->setValue('m6_4_2a_1'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->gen_6_4_2a_2 == $i)
            $phpWord->setValue('m6_4_2a_2'.$i, $checked);
        else
            $phpWord->setValue('m6_4_2a_2'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->gen_6_4_2a_3 == $i)
            $phpWord->setValue('m6_4_2a_3'.$i, $checked);
        else
            $phpWord->setValue('m6_4_2a_3'.$i, $unchecked);
    }


    for($i=1;$i<=2;$i++){
        if($row->gen_6_4_2b == $i)
            $phpWord->setValue('m6_4_2b'.$i, $checked);
        else
            $phpWord->setValue('m6_4_2b'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_4_2b_1 == $i)
            $phpWord->setValue('m6_4_2b_1'.$i, $checked);
        else
            $phpWord->setValue('m6_4_2b_1'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_4_2b_2 == $i)
            $phpWord->setValue('m6_4_2b_2'.$i, $checked);
        else
            $phpWord->setValue('m6_4_2b_2'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_4_3 == $i)
            $phpWord->setValue('m6_4_3'.$i, $checked);
        else
            $phpWord->setValue('m6_4_3'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_4_3_1 == $i)
            $phpWord->setValue('m6_4_3_1'.$i, $checked);
        else
            $phpWord->setValue('m6_4_3_1'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_4_3_2 == $i)
            $phpWord->setValue('m6_4_3_2'.$i, $checked);
        else
            $phpWord->setValue('m6_4_3_2'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->gen_6_4_3_3 == $i)
            $phpWord->setValue('m6_4_3_3'.$i, $checked);
        else
            $phpWord->setValue('m6_4_3_3'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_4_4 == $i)
            $phpWord->setValue('m6_4_4'.$i, $checked);
        else
            $phpWord->setValue('m6_4_4'.$i, $unchecked);
    }


    for($i=1;$i<=2;$i++){
        if($row->gen_6_4_5 == $i)
            $phpWord->setValue('m6_4_5'.$i, $checked);
        else
            $phpWord->setValue('m6_4_5'.$i, $unchecked);
    }


    for($i=1;$i<=2;$i++){
        if($row->gen_6_4_6 == $i)
            $phpWord->setValue('m6_4_6'.$i, $checked);
        else
            $phpWord->setValue('m6_4_6'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_5_1 == $i)
            $phpWord->setValue('m6_5_1'.$i, $checked);
        else
            $phpWord->setValue('m6_5_1'.$i, $unchecked);
    }
    $str="......";
    if($row->gen_6_5_1a > 0)
        $str=Num2Unicode($row->gen_6_5_1a);
    $phpWord->setValue('m6_5_1a', $str);
    $str="......";
    if($row->gen_6_5_1b > 0)
        $str=Num2Unicode($row->gen_6_5_1b);
    $phpWord->setValue('m6_5_1b', $str);
    $str="......";
    if($row->gen_6_5_1c > 0)
        $str=Num2Unicode($row->gen_6_5_1c);
    $phpWord->setValue('m6_5_1c', $str);
    $str="......";
    if($row->gen_6_5_1d > 0)
        $str=Num2Unicode($row->gen_6_5_1d);
    $phpWord->setValue('m6_5_1d', $str);
    $str="......";
    if($row->gen_6_5_1e > 0)
        $str=Num2Unicode($row->gen_6_5_1e);
    $phpWord->setValue('m6_5_1e', $str);
    $str="......";
    if($row->gen_6_5_1f > 0)
        $str=Num2Unicode($row->gen_6_5_1f);
    $phpWord->setValue('m6_5_1f', $str);
    $str="......";
    if($row->gen_6_5_1g > 0)
        $str=Num2Unicode($row->gen_6_5_1g);
    $phpWord->setValue('m6_5_1g', $str);
    $str="......";
    if($row->gen_6_5_1h > 0)
        $str=Num2Unicode($row->gen_6_5_1h);
    $phpWord->setValue('m6_5_1h', $str);


    for($i=1;$i<=2;$i++){
        if($row->gen_6_5_2 == $i)
            $phpWord->setValue('m6_5_2'.$i, $checked);
        else
            $phpWord->setValue('m6_5_2'.$i, $unchecked);
    }
    $str="......";
    if($row->gen_6_5_2num > 0)
        $str=Num2Unicode($row->gen_6_5_2num);
    $phpWord->setValue('m6_5_2num', $str);

    $phpWord->setValue('m6_6_1', $row->gen_6_6_1);
    for($i=1;$i<=2;$i++){
        if($row->gen_6_6_2 == $i)
            $phpWord->setValue('m6_6_2'.$i, $checked);
        else
            $phpWord->setValue('m6_6_2'.$i, $unchecked);
    }
    $phpWord->setValue('m6_6_2_1', $row->gen_6_6_2_1);
    $phpWord->setValue('m6_6_2_2', $row->gen_6_6_2_2);
    for($i=1;$i<=2;$i++){
        if($row->gen_6_6_2_3 == $i)
            $phpWord->setValue('m6_6_2_3'.$i, $checked);
        else
            $phpWord->setValue('m6_6_2_3'.$i, $unchecked);
    }
    $str="......";
    if($row->gen_6_6_2_3num > 0)
        $str=Num2Unicode($row->gen_6_6_2_3num);
    $phpWord->setValue('m6_6_2_3num', $str);

    for($i=1;$i<=2;$i++){
        if($row->gen_6_6_3 == $i)
            $phpWord->setValue('m6_6_3'.$i, $checked);
        else
            $phpWord->setValue('m6_6_3'.$i, $unchecked);
    }
    $str="......";
    if($row->gen_6_6_3num > 0)
        $str=Num2Unicode($row->gen_6_6_3num);
    $phpWord->setValue('m6_6_3num', $str);

    for($i=1;$i<=2;$i++){
        if($row->gen_6_7_1 == $i)
            $phpWord->setValue('m6_7_1'.$i, $checked);
        else
            $phpWord->setValue('m6_7_1'.$i, $unchecked);
    }
    $str="......";
    if($row->gen_6_7_1num > 0)
        $str=Num2Unicode($row->gen_6_7_1num);
    $phpWord->setValue('m6_7_1num', $str);

    for($i=1;$i<=2;$i++){
        if($row->gen_6_7_1_1 == $i)
            $phpWord->setValue('m6_7_1_1'.$i, $checked);
        else
            $phpWord->setValue('m6_7_1_1'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_7_1_2 == $i)
            $phpWord->setValue('m6_7_1_2'.$i, $checked);
        else
            $phpWord->setValue('m6_7_1_2'.$i, $unchecked);
    }

    for($i=1;$i<=3;$i++){
        if($row->gen_6_7_1_3 == $i)
            $phpWord->setValue('m6_7_1_3'.$i, $checked);
        else
            $phpWord->setValue('m6_7_1_3'.$i, $unchecked);
    }

    for($i=1;$i<=3;$i++){
        if($row->gen_6_7_1_4 == $i)
            $phpWord->setValue('m6_7_1_4'.$i, $checked);
        else
            $phpWord->setValue('m6_7_1_4'.$i, $unchecked);
    }

    for($i=1;$i<=3;$i++){
        if($row->gen_6_7_1_5 == $i)
            $phpWord->setValue('m6_7_1_5'.$i, $checked);
        else
            $phpWord->setValue('m6_7_1_5'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->gen_6_7_1_6 == $i)
            $phpWord->setValue('m6_7_1_6'.$i, $checked);
        else
            $phpWord->setValue('m6_7_1_6'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->gen_6_7_1_7 == $i)
            $phpWord->setValue('m6_7_1_7'.$i, $checked);
        else
            $phpWord->setValue('m6_7_1_7'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->gen_6_7_1_8 == $i)
            $phpWord->setValue('m6_7_1_8'.$i, $checked);
        else
            $phpWord->setValue('m6_7_1_8'.$i, $unchecked);
    }

    /** =================Menu 6: conclusion and comment of officer=======*/
    if($inspection->insp_type > 0)
    {
        for($i=1;$i<=4;$i++){//m6 conclusion
            if($row->gen_6_conclusion == $i){
                $phpWord->setValue('m6_con'.$i, $checked);
            }
            else $phpWord->setValue('m6_con'.$i, $unchecked);
        }
        $str=__('g2.2_ocomment_sample')." ".__('g2.2_ocomment_sample2');
        if($row->officer_comment != ""){
            $str=$row->officer_comment;
        }
        $phpWord->setValue('m6_comment', $str);
    }

}
function exportOtherMenu7($inspection, $phpWord)
{
    $checked = '<w:sym w:font="Wingdings" w:char="F0FE"/>';
    $unchecked = '<w:sym w:font="Wingdings" w:char="F0A8"/>';
    $arr_a=array();
    for($i=0;$i<=25;$i++){
        $arr_a[$i]=$unchecked;
    }

    /** ==============Menu 7===========================*/
    $row = $inspection->otherMenu7;

    for($i=1;$i<=2;$i++){
        if($row->sec_7_1_1 == $i)
            $phpWord->setValue('m7_1_1'.$i, $checked);
        else
            $phpWord->setValue('m7_1_1'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->sec_7_1_1 == $i)
            $phpWord->setValue('m7_1_1'.$i, $checked);
        else
            $phpWord->setValue('m7_1_1'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->sec_7_1_2 == $i)
            $phpWord->setValue('m7_1_2'.$i, $checked);
        else
            $phpWord->setValue('m7_1_2'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->sec_7_2opt == $i)
            $phpWord->setValue('m7_2opt'.$i, $checked);
        else
            $phpWord->setValue('m7_2opt'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->sec_7_2 == $i)
            $phpWord->setValue('m7_2'.$i, $checked);
        else
            $phpWord->setValue('m7_2'.$i, $unchecked);
    }

    /** ======================= 7_3_1 ========================== */
    for($i=1;$i<=2;$i++){
        if($row->sec_7_1_3 == $i)
            $phpWord->setValue('m7_1_3'.$i, $checked);
        else
            $phpWord->setValue('m7_1_3'.$i, $unchecked);
    }

    $str=".........";
    if($row->sec_7_1_3_if_have > 0)
        $str=$row->sec_7_1_3_if_have;
    $phpWord->setValue('m7_1_3h', Num2Unicode($str));

    for($i=1;$i<=2;$i++){
        if($row->sec_7_2 == $i)
            $phpWord->setValue('m7_2'.$i, $checked);
        else
            $phpWord->setValue('m7_2'.$i, $unchecked);
    }


    /** ==============Menu 7_3_1===========================*/
    for($i=1;$i<=3;$i++){
        if($row->sec_7_3_1_1 == $i)
            $phpWord->setValue('m7_3_1_1'.$i, $checked);
        else
            $phpWord->setValue('m7_3_1_1'.$i, $unchecked);
    }
    for($i=1;$i<=3;$i++){
        if($row->sec_7_3_1_2 == $i)
            $phpWord->setValue('m7_3_1_2'.$i, $checked);
        else
            $phpWord->setValue('m7_3_1_2'.$i, $unchecked);
    }
    for($i=1;$i<=3;$i++){
        if($row->sec_7_3_1_3 == $i)
            $phpWord->setValue('m7_3_1_3'.$i, $checked);
        else
            $phpWord->setValue('m7_3_1_3'.$i, $unchecked);
    }

    for($i=1;$i<=3;$i++){
        if($row->sec_7_3_1_4 == $i)
            $phpWord->setValue('m7_3_1_4'.$i, $checked);
        else
            $phpWord->setValue('m7_3_1_4'.$i, $unchecked);
    }
    for($i=1;$i<=3;$i++){
        if($row->sec_7_3_1_5 == $i)
            $phpWord->setValue('m7_3_1_5'.$i, $checked);
        else
            $phpWord->setValue('m7_3_1_5'.$i, $unchecked);
    }

//        $total_dose=CompanyWorkerCovidVaccine($company_id);
//        $total_female_dose=CompanyWorkerFemaleCovidVaccine($company_id);
//        $total_emp=$total_dose[0] + $total_dose[1]+ $total_dose[2]+$total_dose[3]+$total_dose[4];
//        $total_emp_female= $total_female_dose[0] + $total_female_dose[1]+ $total_female_dose[2]+   $total_female_dose[3]+   $total_female_dose[4];
//        $phpWord->setValue('m7_total', $total_emp);
//        $phpWord->setValue('m7_total_female', $total_emp_female);
//        $phpWord->setValue('m7_dose0', $total_dose[0]);
//        $phpWord->setValue('m7_dose0_female', $total_female_dose[0]);
//        $phpWord->setValue('m7_dose12', $total_dose[2]);
//        $phpWord->setValue('m7_dose12_female', $total_female_dose[2]);
//        $phpWord->setValue('m7_dose3', $total_dose[3]);
//        $phpWord->setValue('m7_dose3_female', $total_female_dose[3]);
//        $phpWord->setValue('m7_dose4', $total_dose[4]);
//        $phpWord->setValue('m7_dose4_female', $total_female_dose[4]);
//        dd($inspection->company->toArray());
//        $arrComInfo=CompanyInfobyInspection($inspection_id);
    $arrComInfo = $inspection->company->toArray();
    $company_id=$arrComInfo['company_id'];
    $covidData= getCovid19LACMS($company_id);
    $phpWord->setValue('m7_total', Num2Unicode($covidData['total_worker']));
    $phpWord->setValue('m7_total_female', Num2Unicode($covidData['total_worker_female']));
    $phpWord->setValue('m7_dose3', Num2Unicode($covidData['dose_3']));
    $phpWord->setValue('m7_dose3_female', 0);
    $phpWord->setValue('m7_dose4', Num2Unicode($covidData['dose_4']));
    $phpWord->setValue('m7_dose4_female', 0);
    $phpWord->setValue('m7_dose5', Num2Unicode($covidData['dose_5']));
    $phpWord->setValue('m7_dose5_female', 0);
    $phpWord->setValue('m7_dose0', Num2Unicode($covidData['not_vaccinated']));
    $phpWord->setValue('m7_dose0_female', 0);

    /** ==============Menu 7_3_2===========================*/
    for($i=1;$i<=2;$i++){
        if($row->sec_7_3_2_1 == $i)
            $phpWord->setValue('m7_3_2_1'.$i, $checked);
        else
            $phpWord->setValue('m7_3_2_1'.$i, $unchecked);
    }


    for($i=1;$i<=2;$i++){
        if($row->sec_7_3_2_2 == $i)
            $phpWord->setValue('m7_3_2_2'.$i, $checked);
        else
            $phpWord->setValue('m7_3_2_2'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->sec_7_3_2_3 == $i)
            $phpWord->setValue('m7_3_2_3'.$i, $checked);
        else
            $phpWord->setValue('m7_3_2_3'.$i, $unchecked);
    }

    for($i=1;$i<=4;$i++){
        if($row->sec_7_3_2_4 == $i)
            $phpWord->setValue('m7_3_2_4'.$i, $checked);
        else
            $phpWord->setValue('m7_3_2_4'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->sec_7_3_3 == $i)
            $phpWord->setValue('m7_3_3'.$i, $checked);
        else
            $phpWord->setValue('m7_3_3'.$i, $unchecked);
    }

    for($i=1;$i<=3;$i++){
        if($row->sec_7_3_3_1 == $i)
            $phpWord->setValue('m7_3_3_1'.$i, $checked);
        else
            $phpWord->setValue('m7_3_3_1'.$i, $unchecked);
    }
    for($i=1;$i<=3;$i++){
        if($row->sec_7_3_3_2 == $i)
            $phpWord->setValue('m7_3_3_2'.$i, $checked);
        else
            $phpWord->setValue('m7_3_3_2'.$i, $unchecked);
    }


    for($i=1;$i<=2;$i++){
        if($row->sec_7_3_4 == $i)
            $phpWord->setValue('m7_3_4'.$i, $checked);
        else
            $phpWord->setValue('m7_3_4'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->sec_7_3_4_1 == $i)
            $phpWord->setValue('m7_3_4_1'.$i, $checked);
        else
            $phpWord->setValue('m7_3_4_1'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->sec_7_3_4_2 == $i)
            $phpWord->setValue('m7_3_4_2'.$i, $checked);
        else
            $phpWord->setValue('m7_3_4_2'.$i, $unchecked);
    }


    for($i=1;$i<=3;$i++){
        if($row->sec_7_4_1 == $i)
            $phpWord->setValue('m7_4_1'.$i, $checked);
        else
            $phpWord->setValue('m7_4_1'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->sec_7_4_2require == $i)
            $phpWord->setValue('m7_4_2r'.$i, $checked);
        else
            $phpWord->setValue('m7_4_2r'.$i, $unchecked);
    }
    for($i=1;$i<=3;$i++){
        if($row->sec_7_4_2 == $i)
            $phpWord->setValue('m7_4_2'.$i, $checked);
        else
            $phpWord->setValue('m7_4_2'.$i, $unchecked);
    }

//        for($i=1;$i<=2;$i++){
//            if($row->sec_7_4_3require == $i)
//                $phpWord->setValue('m7_4_3require'.$i, $checked);
//            else
//                $phpWord->setValue('m7_4_3require'.$i, $unchecked);
//        }
//        for($i=1;$i<=2;$i++){
//            if($row->sec_7_4_3have == $i)
//                $phpWord->setValue('m7_4_3have'.$i, $checked);
//            else
//                $phpWord->setValue('m7_4_3have'.$i, $unchecked);
//        }
//        for($i=1;$i<=2;$i++){
//            if($row->sec_7_4_3a1 == $i)
//                $phpWord->setValue('m7_4_3a1'.$i, $checked);
//            else
//                $phpWord->setValue('m7_4_3a1'.$i, $unchecked);
//        }
//        for($i=1;$i<=3;$i++){
//            if($row->sec_7_4_3a2 == $i)
//                $phpWord->setValue('m7_4_3a2'.$i, $checked);
//            else
//                $phpWord->setValue('m7_4_3a2'.$i, $unchecked);
//        }
//
//        $str=$unchecked;
//        if($row->sec_7_4_3a ==1)
//            $str=$checked;
//        $phpWord->setValue('m7_4_3a', $str);
//
//
//
//        for($i=1;$i<=2;$i++){
//            if($row->sec_7_4_3_1 == $i)
//                $phpWord->setValue('m7_4_3_1'.$i, $checked);
//            else
//                $phpWord->setValue('m7_4_3_1'.$i, $unchecked);
//        }
//        for($i=1;$i<=2;$i++){
//            if($row->sec_7_4_3_2 == $i)
//                $phpWord->setValue('m7_4_3_2'.$i, $checked);
//            else
//                $phpWord->setValue('m7_4_3_2'.$i, $unchecked);
//        }
//        for($i=1;$i<=2;$i++){
//            if($row->sec_7_4_3_3 == $i)
//                $phpWord->setValue('m7_4_3_3'.$i, $checked);
//            else
//                $phpWord->setValue('m7_4_3_3'.$i, $unchecked);
//        }
//        for($i=1;$i<=2;$i++){
//            if($row->sec_7_4_3_4 == $i)
//                $phpWord->setValue('m7_4_3_4'.$i, $checked);
//            else
//                $phpWord->setValue('m7_4_3_4'.$i, $unchecked);
//        }
//
//        $str=$unchecked;
//        if($row->sec_7_4_3b ==1)
//            $str=$checked;
//        $phpWord->setValue('m7_4_3b', $str);
//        $phpWord->setValue('sec_7_4_3ba', $row->sec_7_4_3ba);
//        $phpWord->setValue('sec_7_4_3bb', $row->sec_7_4_3bb);
//        for($i=1;$i<=2;$i++){
//            if($row->sec_7_4_3b1 == $i)
//                $phpWord->setValue('m7_4_3b1'.$i, $checked);
//            else
//                $phpWord->setValue('m7_4_3b1'.$i, $unchecked);
//        }
//        $str=".........................................";
//        if($row->sec_7_4_3ba !="")
//            $str=$row->sec_7_4_3ba;
//        $phpWord->setValue('m7_4_3ba', $str);
//        $str="...................";
//        if($row->sec_7_4_3bb !="")
//            $str=$row->sec_7_4_3bb;
//        $phpWord->setValue('m7_4_3bb', $str);

    /**  =============7_4_3 ================== */
    for($i=1;$i<=2;$i++){
        if($row->sec_7_4_3require == $i)
            $phpWord->setValue('m7_4_3require'.$i, $checked);
        else
            $phpWord->setValue('m7_4_3require'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->sec_7_4_3have == $i)
            $phpWord->setValue('m7_4_3have'.$i, $checked);
        else
            $phpWord->setValue('m7_4_3have'.$i, $unchecked);
    }

    if($row->sec_7_4_3a == 1)
        $phpWord->setValue('m7_4_3a', $checked);
    else
        $phpWord->setValue('m7_4_3a', $unchecked);

    for($i=1;$i<=2;$i++){
        if($row->sec_7_4_3a1 == $i)
            $phpWord->setValue('m7_4_3a1'.$i, $checked);
        else
            $phpWord->setValue('m7_4_3a1'.$i, $unchecked);
    }

//        for($i=1;$i<=2;$i++){
//            if($row->sec_7_4_3 == $i)
//                $phpWord->setValue('m7_4_3'.$i, $checked);
//            else
//                $phpWord->setValue('m7_4_3'.$i, $unchecked);
//        }

    for($i=1;$i<=2;$i++){
        if($row->sec_7_4_3_1 == $i)
            $phpWord->setValue('m7_4_3_1'.$i, $checked);
        else
            $phpWord->setValue('m7_4_3_1'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->sec_7_4_3_3 == $i)
            $phpWord->setValue('m7_4_3_3'.$i, $checked);
        else
            $phpWord->setValue('m7_4_3_3'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->sec_7_4_3_3_1 == $i)
            $phpWord->setValue('m7_4_3_3_1'.$i, $checked);
        else
            $phpWord->setValue('m7_4_3_3_1'.$i, $unchecked);
    }

    $str=".........";
    if($row->sec_7_4_3_3_2 > 0)
        $str=$row->sec_7_4_3_3_2;
    $phpWord->setValue('m7_4_3_3_2', Num2Unicode($str));

    $str=".........";
    if($row->sec_7_4_3_3_3 > 0)
        $str=$row->sec_7_4_3_3_3;
    $phpWord->setValue('m7_4_3_3_3', Num2Unicode($str));

    for($i=1;$i<=2;$i++){
        if($row->sec_7_4_3_4 == $i)
            $phpWord->setValue('m7_4_3_4'.$i, $checked);
        else
            $phpWord->setValue('m7_4_3_4'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->sec_7_4_3_4_1 == $i)
            $phpWord->setValue('m7_4_3_4_1'.$i, $checked);
        else
            $phpWord->setValue('m7_4_3_4_1'.$i, $unchecked);
    }

    $str=".........";
    if($row->sec_7_4_3_4_2 > 0)
        $str=$row->sec_7_4_3_4_2;
    $phpWord->setValue('m7_4_3_4_2', Num2Unicode($str));

    $str=".........";
    if($row->sec_7_4_3_4_3 > 0)
        $str=$row->sec_7_4_3_4_3;
    $phpWord->setValue('m7_4_3_4_3', Num2Unicode($str));

    for($i=1;$i<=2;$i++){
        if($row->sec_7_4_3_5 == $i)
            $phpWord->setValue('m7_4_3_5'.$i, $checked);
        else
            $phpWord->setValue('m7_4_3_5'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->sec_7_4_3_5_1 == $i)
            $phpWord->setValue('m7_4_3_5_1'.$i, $checked);
        else
            $phpWord->setValue('m7_4_3_5_1'.$i, $unchecked);
    }

    $str=".........";
    if($row->sec_7_4_3_5_2 > 0)
        $str=$row->sec_7_4_3_5_2;
    $phpWord->setValue('m7_4_3_5_2', Num2Unicode($str));

    $str=".........";
    if($row->sec_7_4_3_5_3 > 0)
        $str=$row->sec_7_4_3_5_3;
    $phpWord->setValue('m7_4_3_5_3', Num2Unicode($str));


    for($i=1;$i<=2;$i++){
        if($row->sec_7_4_3_6 == $i)
            $phpWord->setValue('m7_4_3_6'.$i, $checked);
        else
            $phpWord->setValue('m7_4_3_6'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->sec_7_4_3_7 == $i)
            $phpWord->setValue('m7_4_3_7'.$i, $checked);
        else
            $phpWord->setValue('m7_4_3_7'.$i, $unchecked);
    }
    if($row->sec_7_4_3b == 1)
        $phpWord->setValue('m7_4_3b', $checked);
    else
        $phpWord->setValue('m7_4_3b', $unchecked);

    $phpWord->setValue('m7_4_3ba', xmlEntities($row->sec_7_4_3ba));
    $phpWord->setValue('m7_4_3bb', $row->sec_7_4_3bb);
    for($i=1;$i<=2;$i++){
        if($row->sec_7_4_3b1 == $i)
            $phpWord->setValue('m7_4_3b1'.$i, $checked);
        else
            $phpWord->setValue('m7_4_3b1'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->sec_7_4_4 == $i)
            $phpWord->setValue('m7_4_4'.$i, $checked);
        else
            $phpWord->setValue('m7_4_4'.$i, $unchecked);
    }

    $str=".........";
    if($row->sec_7_4_4have > 0)
        $str=$row->sec_7_4_4have;
    $phpWord->setValue('m7_4_4have', $str);

    for($i=1;$i<=2;$i++){
        if($row->sec_7_5_1 == $i)
            $phpWord->setValue('m7_5_1'.$i, $checked);
        else
            $phpWord->setValue('m7_5_1'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->sec_7_5_2 == $i)
            $phpWord->setValue('m7_5_2'.$i, $checked);
        else
            $phpWord->setValue('m7_5_2'.$i, $unchecked);
    }

    for($i=1;$i<=3;$i++){
        if($row->sec_7_5_3 == $i)
            $phpWord->setValue('m7_5_3'.$i, $checked);
        else
            $phpWord->setValue('m7_5_3'.$i, $unchecked);
    }

    for($i=1;$i<=3;$i++){
        if($row->sec_7_5_4 == $i)
            $phpWord->setValue('m7_5_4'.$i, $checked);
        else
            $phpWord->setValue('m7_5_4'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->sec_7_5_5 == $i)
            $phpWord->setValue('m7_5_5'.$i, $checked);
        else
            $phpWord->setValue('m7_5_5'.$i, $unchecked);
    }

    for($i=1;$i<=3;$i++){
        if($row->sec_7_5_6 == $i)
            $phpWord->setValue('m7_5_6'.$i, $checked);
        else
            $phpWord->setValue('m7_5_6'.$i, $unchecked);
    }

    for($i=1;$i<=3;$i++){
        if($row->sec_7_5_7 == $i)
            $phpWord->setValue('m7_5_7'.$i, $checked);
        else
            $phpWord->setValue('m7_5_7'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->sec_7_5_8 == $i)
            $phpWord->setValue('m7_5_8'.$i, $checked);
        else
            $phpWord->setValue('m7_5_8'.$i, $unchecked);
    }

    /** =============Menu 7: conlcusion and comment of officer ==== */
    if($inspection->insp_type > 0)
    {
        for($i=1;$i<=4;$i++){//m7 conclusion
            if($row->sec_7_conclusion == $i){
                $phpWord->setValue('m7_con'.$i, $checked);
            }
            else $phpWord->setValue('m7_con'.$i, $unchecked);
        }
        $str=__('g2.2_ocomment_sample')." ".__('g2.2_ocomment_sample2');
        if($row->officer_comment != ""){
            $str=$row->officer_comment;
        }
        $phpWord->setValue('m7_comment', $str);
    }

}
function exportOtherMenu8($inspection, $phpWord)
{
    $checked = '<w:sym w:font="Wingdings" w:char="F0FE"/>';
    $unchecked = '<w:sym w:font="Wingdings" w:char="F0A8"/>';
    $arr_a=array();
    for($i=0;$i<=25;$i++){
        $arr_a[$i]=$unchecked;
    }

    /** ==============Menu 8===========================*/
    $row = $inspection->otherMenu8;
    for($j=1;$j<=3;$j++)
        for($i=1;$i<=2;$i++){
            $v="nssf_8_".$j;
            if($row->$v == $i)
                $phpWord->setValue('m8_'.$j.$i, $checked);
            else
                $phpWord->setValue('m8_'.$j.$i, $unchecked);
        }

    $str=$unchecked;
    $str2=".......";
    $str3=".......";
    if($row->nssf_8_2_if_have ==1){
        $str=$checked;
        $str2=$row->nssf_8_2_if_have_num;
        $str3=$row->nssf_8_2_if_have_numf;
    }
    $phpWord->setValue('m8_2h', $str);
    $phpWord->setValue('m8_2hn', Num2Unicode($str2));
    $phpWord->setValue('m8_2hnf', Num2Unicode($str3));

    for($i=1;$i<=5;$i++){
        $v="nssf_8_3_".$i;
        if($row->$v == 1)
            $phpWord->setValue('m8_3_'.$i, $checked);
        else
            $phpWord->setValue('m8_3_'.$i, $unchecked);
    }


    /** =============Menu 8: conlcusion and comment of officer */
    if($inspection->insp_type > 0)
    {
        for($i=1;$i<=4;$i++){//m8 conclusion
            if($row->nssf_8_conclusion == $i){
                $phpWord->setValue('m8_con'.$i, $checked);
            }
            else $phpWord->setValue('m8_con'.$i, $unchecked);
        }

        $str=__('g2.2_ocomment_sample')." ".__('g2.2_ocomment_sample2');
        if($row->officer_comment != ""){
            $str=$row->officer_comment;
        }
        $phpWord->setValue('m8_comment', $str);
    }

}
function exportOtherMenu9($inspection, $phpWord)
{
    $checked = '<w:sym w:font="Wingdings" w:char="F0FE"/>';
    $unchecked = '<w:sym w:font="Wingdings" w:char="F0A8"/>';
    $arr_a=array();
    for($i=0;$i<=25;$i++){
        $arr_a[$i]=$unchecked;
    }

    /** ==============Menu 9===========================*/
    $row = $inspection->otherMenu9;
    //dd($row);
    /** ==============Menu 9_1===========================*/
    for($i=1;$i<=2;$i++){
        if($row->prof_9_1option== $i)
            $phpWord->setValue('m9_1option'.$i, $checked);
        else
            $phpWord->setValue('m9_1option'.$i, $unchecked);
    }

    $str="...........";
    if($row->prof_9_1a_num1 > 0)
        $str=$row->prof_9_1a_num1;
    $phpWord->setValue('m9_1a_num1', Num2Unicode($str));
    $str="...........";
    if($row->prof_9_1a_num2 > 0)
        $str=$row->prof_9_1a_num2;
    $phpWord->setValue('m9_1a_num2', Num2Unicode($str));
    $str="...........";
    if($row->prof_9_1a_num3 > 0)
        $str=$row->prof_9_1a_num3;
    $phpWord->setValue('m9_1a_num3', Num2Unicode($str));
    $str="...........";
    if($row->prof_9_1a_num4 > 0)
        $str=$row->prof_9_1a_num4;
    $phpWord->setValue('m9_1a_num4', Num2Unicode($str));

    for($i=1;$i<=2;$i++){
        if($row->prof_9_1== $i)
            $phpWord->setValue('m9_1'.$i, $checked);
        else
            $phpWord->setValue('m9_1'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->prof_9_1_1== $i)
            $phpWord->setValue('m9_1_1'.$i, $checked);
        else
            $phpWord->setValue('m9_1_1'.$i, $unchecked);
    }

    /** ==============Menu 9_2===========================*/
    for($i=1;$i<=2;$i++){
        if($row->prof_9_2== $i)
            $phpWord->setValue('m9_2'.$i, $checked);
        else
            $phpWord->setValue('m9_2'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->prof_9_3== $i)
            $phpWord->setValue('m9_3'.$i, $checked);
        else
            $phpWord->setValue('m9_3'.$i, $unchecked);
    }

    $str=".........";
    if($row->prof_9_3a > 0)
        $str=$row->prof_9_3a;
    $phpWord->setValue('m9_3a', Num2Unicode($str));

    $str=".........";
    if($row->prof_9_3b > 0)
        $str=$row->prof_9_3b;
    $phpWord->setValue('m9_3b', Num2Unicode($str));

    $m931_data="";
    //dd($row->union);
    //$qk=$this->main->select_many_record("tbl_931", "*", array('inspection_id' => $inspection_id));
    if(count($row->union) > 0){
        //dd("have union");
        $i=1;
        foreach($row->union as $rowk){
            $m931_data.= Num2Unicode($i).".ឈ្មោះសហជីព: ".xmlEntities($rowk->union_name);
            $m931_data.= "  ចំនួនសមាជិក ".Num2Unicode($rowk->total_member)."នាក់ ";
            $m931_data.= "  ជាសមាជិកសហព័ន្ធសហជីព ".$rowk->member_of;
            $m931_data.= "<w:br/>";
            $i++;
        }
    }
    $phpWord->setValue('m93_data', $m931_data);

    for($i=1;$i<=2;$i++){
        if($row->prof_9_3_1== $i)
            $phpWord->setValue('m9_3_1'.$i, $checked);
        else
            $phpWord->setValue('m9_3_1'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->prof_9_3_1_1== $i)
            $phpWord->setValue('m9_3_1_1'.$i, $checked);
        else
            $phpWord->setValue('m9_3_1_1'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->prof_9_4== $i)
            $phpWord->setValue('m9_4'.$i, $checked);
        else
            $phpWord->setValue('m9_4'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->prof_9_5 == $i)
            $phpWord->setValue('m9_5'.$i, $checked);
        else
            $phpWord->setValue('m9_5'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->prof_9_6== $i)
            $phpWord->setValue('m9_6'.$i, $checked);
        else
            $phpWord->setValue('m9_6'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->prof_9_6_1== $i)
            $phpWord->setValue('m9_6_1'.$i, $checked);
        else
            $phpWord->setValue('m9_6_1'.$i, $unchecked);
    }

    $str=".............";
    if($row->prof_9_6_1n !="")
        $str=$row->prof_9_6_1n;
    $phpWord->setValue('m9_6_1n', $str);

    for($i=1;$i<=3;$i++){
        if($row->prof_9_7== $i)
            $phpWord->setValue('m9_7'.$i, $checked);
        else
            $phpWord->setValue('m9_7'.$i, $unchecked);
    }


    for($i=1;$i<=2;$i++){
        if($row->prof_9_8 == $i)
            $phpWord->setValue('m9_8'.$i, $checked);
        else
            $phpWord->setValue('m9_8'.$i, $unchecked);
    }
    for($i=1;$i<=2;$i++){
        if($row->prof_9_8_1 == $i)
            $phpWord->setValue('m9_8_1'.$i, $checked);
        else
            $phpWord->setValue('m9_8_1'.$i, $unchecked);
    }

    if($inspection->insp_type > 0)
    {
        for($i=1;$i<=4;$i++){//m9 conclusion
            if($row->prof_9_conclusion == $i){
                $phpWord->setValue('m9_con'.$i, $checked);
            }
            else $phpWord->setValue('m9_con'.$i, $unchecked);
        }

        $str=__('g2.2_ocomment_sample')." ".__('g2.2_ocomment_sample2');
        if($row->officer_comment != ""){
            $str=$row->officer_comment;
        }
        $phpWord->setValue('m9_comment', $str);
    }

}
function exportOtherMenu10($inspection, $phpWord)
{
    $checked = '<w:sym w:font="Wingdings" w:char="F0FE"/>';
    $unchecked = '<w:sym w:font="Wingdings" w:char="F0A8"/>';
    $arr_a=array();
    for($i=0;$i<=25;$i++){
        $arr_a[$i]=$unchecked;
    }
    /** ==============Menu 10===========================**/
    $row= $inspection->otherMenu10;

    /** 10_1 */
    for($i=1;$i<=2;$i++){
        if($row->worker_10_1 == $i)
            $phpWord->setValue('m10_1'.$i, $checked);
        else
            $phpWord->setValue('m10_1'.$i, $unchecked);
    }

    for($i=1;$i<=2;$i++){
        if($row->worker_10_1_1 == $i)
            $phpWord->setValue('m10_1_1'.$i, $checked);
        else
            $phpWord->setValue('m10_1_1'.$i, $unchecked);
    }
    $str="............";
    $v="worker_10_1_1_num";
    if($row->$v > 0)
        $str=$row->$v;
    $phpWord->setValue('m10_1_1n', $str);
    $str="............";
    $v="worker_10_1_1_female";
    if($row->$v > 0)
        $str=$row->$v;
    $phpWord->setValue('m10_1_1nf', $str);
    $str=".....................";
    $v="worker_10_1_1_reg_no";
    if($row->$v !="")
        $str=$row->$v;
    $phpWord->setValue('m10_1_1regn', $str);
    $str="........";
    $str2="........";
    $str3="........";
    $v="worker_10_1_1_reg_date";
    if($row->$v !="0000-00-00"){
        $str=date_format(date_create($row->$v), "d");
        $str2=date_format(date_create($row->$v), "m");
        $str3=date_format(date_create($row->$v), "Y");
    }
    $phpWord->setValue('m10_1_1regd', $str);
    $phpWord->setValue('m10_1_1regm', $str2);
    $phpWord->setValue('m10_1_1regy', $str3);
    /** 10_1_2 */
    for($i=1;$i<=2;$i++){
        if($row->worker_10_1_2 == $i)
            $phpWord->setValue('m10_1_2'.$i, $checked);
        else
            $phpWord->setValue('m10_1_2'.$i, $unchecked);
    }
    $str="............";
    $v="worker_10_1_2_num";
    if($row->$v > 0)
        $str=$row->$v;
    $phpWord->setValue('m10_1_2n', $str);
    $str="............";
    $v="worker_10_1_2_female";
    if($row->$v > 0)
        $str=$row->$v;
    $phpWord->setValue('m10_1_2nf', $str);
    $str=".....................";
    $v="worker_10_1_2_reg_no";
    if($row->$v !="")
        $str=$row->$v;
    $phpWord->setValue('m10_1_2regn', $str);
    $str="........";
    $str2="........";
    $str3="........";
    $v="worker_10_1_2_reg_date";
    if($row->$v !="0000-00-00"){
        $str=date_format(date_create($row->$v), "d");
        $str2=date_format(date_create($row->$v), "m");
        $str3=date_format(date_create($row->$v), "Y");
    }
    $phpWord->setValue('m10_1_2regd', $str);
    $phpWord->setValue('m10_1_2regm', $str2);
    $phpWord->setValue('m10_1_2regy', $str3);


    /** 10_2_2 */
    for($i=1;$i<=2;$i++){
        if($row->worker_10_2 == $i)
            $phpWord->setValue('m10_2'.$i, $checked);
        else
            $phpWord->setValue('m10_2'.$i, $unchecked);
    }

    for($j=1;$j<=2;$j++)
        for($i=1;$i<=2;$i++){
            $v="worker_10_2_".$j;
            if($row->$v == $i)
                $phpWord->setValue('m10_2_'.$j.$i, $checked);
            else
                $phpWord->setValue('m10_2_'.$j.$i, $unchecked);
        }

    /** 10_3 */
    /** =======================Way 3 */
    $totalEmployee = $inspection->totalEmployeeOther();
    $w103_3date="";
    if($row->w103_3){
        if($row->w103_3d !="0000-00-00" || $row->w103_3d != null){
            $arr_date3=getDateProvinceAsKhmer($row->w103_3d);
            if(is_array($arr_date3))
                $w103_3date= __('g10.worker_10_3date').": ".__('general.k_date3').$arr_date3['day']." ".__('general.k_month').$arr_date3['month']." ".__("general.k_year").$arr_date3['year'];
        }
    }

    //dd($inspection->totalEmployeeGarment()['total_for']);
    $totalEmployeeFor= $totalEmployee['total_for'];
    $w103_4 = $totalEmployeeFor - $row->w103_2 - $row->w103_3;
    $w103_4 = $w103_4 < 0? 0: $w103_4;
    $phpWord->setValue('m103_1', Num2Unicode($totalEmployeeFor));
    $phpWord->setValue('m103_2', Num2Unicode($row->w103_2));
    $phpWord->setValue('m103_3', Num2Unicode($row->w103_3));
    $phpWord->setValue('m103_3date', Num2Unicode($w103_3date));
    $phpWord->setValue('m103_4', Num2Unicode($w103_4));

    if($w103_4 > 0){
        $phpWord->setValue('m10_32', $checked);
        $phpWord->setValue('m10_31', $unchecked);
    }
    else{
        $phpWord->setValue('m10_31', $checked);
        $phpWord->setValue('m10_32', $unchecked);
    }
//        for($i=1;$i<=2;$i++){
//            if($row->worker_10_3 == $i)
//                $phpWord->setValue('m10_3'.$i, $checked);
//            else
//                $phpWord->setValue('m10_3'.$i, $unchecked);
//        }
//        $get103Data=get103Data_v2($inspection);
//        $get104Data=get104Data($inspection);
//
//        $w103_3=$row->worker_10_3_have_num_old_style;
//        $w103_5=$get103Data['w103_1'] - $get103Data['w103_2'] -$w103_3 - $get103Data['w103_4'];
//        $w103_4d="";
//        if($get103Data['w103_4'] > 0){
//            $arr_date=getDateProvinceAsKhmer($get103Data['w103_4d']);
////$this->get_date_province_as_khmer($get103Data['w103_4d']);
//            $w103_4d=__('menu10.worker_10_3date').": ".__('general.k_date3').$arr_date['day']." ".__('general.k_month').$arr_date['month']." ".__("general.k_year").$arr_date['year'];
//        }
//
//        $phpWord->setValue('m103_1', $get103Data['w103_1']);
//        $phpWord->setValue('m103_2', $get103Data['w103_2']);
//        //$phpWord->setValue('m103_3', $w103_3);
//        $phpWord->setValue('m103_4', $get103Data['w103_4']);
//        $phpWord->setValue('m103_4date', $w103_4d);
//        $phpWord->setValue('m103_5', $w103_5);

    /** ================= 10_4 */
    for($i=1;$i<=2;$i++){
        if($row->worker_10_4 == $i)
            $phpWord->setValue('m10_4'.$i, $checked);
        else
            $phpWord->setValue('m10_4'.$i, $unchecked);
    }
    $w104_4date ="";
    if($row->w104_4){
        if($row->w104_4d !="0000-00-00" || $row->w104_4d != null){
            $arr_date4= getDateProvinceAsKhmer($row->w104_4d);
            if(is_array($arr_date4))
                $w104_4date= __('g10.worker_10_3date').": ".__('general.k_date3').$arr_date4['day']." ".__('general.k_month').$arr_date4['month']." ".__("general.k_year").$arr_date4['year'];
        }
    }


    $totalEmployeeKhmer= $totalEmployee['total_khmer'];
    $w104_5= $totalEmployeeKhmer - $row->w104_2 - $row->w104_3 - $row->w104_4;
    $w104_5 = $w104_5 < 0? 0: $w104_5;
    $phpWord->setValue('m104_1', Num2Unicode($totalEmployeeKhmer));
    $phpWord->setValue('m104_2', Num2Unicode($row->w104_2));
    $phpWord->setValue('m104_3', Num2Unicode($row->w104_3));
    $phpWord->setValue('m104_4', Num2Unicode($row->w104_4));
    $phpWord->setValue('m104_4date', Num2Unicode($w104_4date));
    $phpWord->setValue('m104_5', Num2Unicode($w104_5));
//        for($i=1;$i<=2;$i++){
//            if($row->worker_10_4 == $i)
//                $phpWord->setValue('m10_4'.$i, $checked);
//            else
//                $phpWord->setValue('m10_4'.$i, $unchecked);
//        }
//
//        $w104_3=$row->worker_10_4_have_num_old_style;
//        $w104_5=$get104Data['w104_1'] - $get104Data['w104_2'] -$w104_3 - $get104Data['w104_4'];
//        $w104_4d="";
//        if($get104Data['w104_4'] > 0){
//            $arr_date= getDateProvinceAsKhmer($get104Data['w104_4d']);
////                $this->get_date_province_as_khmer($get104Data['w104_4d']);
//            $w104_4d=__('menu10.worker_10_3date').": ".__('general.k_date3').$arr_date['day']." ".__('general.k_month').$arr_date['month']." ".__("general.k_year").$arr_date['year'];
//        }
//
//        $phpWord->setValue('m104_1', $get104Data['w104_1']);
//        $phpWord->setValue('m104_2', $get104Data['w104_2']);
//        $phpWord->setValue('m104_3', $w104_3);
//        $phpWord->setValue('m104_4', $get104Data['w104_4']);
//        $phpWord->setValue('m104_4date', $w104_4d);
//        $phpWord->setValue('m104_5', $w104_5);

    if($inspection->insp_type >0)
    {
        for($i=1;$i<=4;$i++){//m10 conclusion
            if($row->worker_10_conclusion == $i){
                $phpWord->setValue('m10_con'.$i, $checked);
            }
            else $phpWord->setValue('m10_con'.$i, $unchecked);
        }

        $str=__('g2.2_ocomment_sample')." ".__('g2.2_ocomment_sample2');
        if($row->officer_comment != ""){
            $str=$row->officer_comment;
        }
        $phpWord->setValue('m10_comment', $str);
    }

}
function exportOtherMenu11($inspection, $phpWord)
{
    /** ==============Menu 11===========================*/
    $arr_a = getData11_A($inspection);
    $arr_b = getData11_B($inspection);
    $arr_c = getData11_C($inspection);
    $arr_d = getData11_D($inspection);
    $arr_e = getData11_E($inspection);
    $arr_f = getData11_F($inspection);

//        $arr_a= array();
//        $arr_b= array();
//        $arr_c= array();
//        $arr_d= array();
//        $arr_e= array();
//        $arr_f= array();
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
    $phpWord->setValue('menu11_a', $text_a);
    $phpWord->setValue('menu11_b', $text_b);
    $phpWord->setValue('menu11_c', $text_c);
    $phpWord->setValue('menu11_d', $text_d);
    $phpWord->setValue('menu11_e', $text_e);
    $phpWord->setValue('menu11_f', $text_f);

}
function exportOtherMenu12($inspection, $phpWord)
{
    /** ==============Menu 12===========================*/
    $arr_b = getData12_B($inspection);
    $arr_c = getData12_C($inspection);
    $text_b = "     ";
    $text_c = "     ";

    if(count($arr_b) > 0){
        $i=1;
        foreach($arr_b as $item){
            if($item != ""){
                $text_b.=Num2Unicode($i).". ".$item."<w:br/>";
                $i++;
            }
        }
    }
    if(sizeof($arr_c) > 0){
        for($i=0; $i< sizeof($arr_c); $i++){
            $j=$i+1;
            $text_c.=Num2Unicode($j).". ".$arr_c[$i]."<w:br/>";
        }
    }
    $phpWord->setValue('menu12_a', "");
    $phpWord->setValue('menu12_b', $text_b);
    $phpWord->setValue('menu12_c', $text_c);
    //$phpWord->setValue('menu12_d', "");
    //$phpWord->setValue('menu12_e', "");
    //$phpWord->setValue('menu12_f', "");

}
function exportOtherMenu13($inspection, $phpWord)
{
    /** ==============Menu 13===========================*/
    $row13= $inspection->otherMenu13;
    $m13_1=str_replace("<br />", "<w:br/>", $row13->sug_employer);
    $m13_2=str_replace("<br />", "<w:br/>", $row13->sug_employee);
    $phpWord->setValue('menu13_1', $m13_1);//echo $row13->sug_employer;
    $phpWord->setValue('menu13_2', $m13_2);//echo $row13->sug_employee;

}
function exportOtherMenu14($inspection, $phpWord)
{
    /** ==============Menu 14: A, B, C, D, E, F===========*/
//        $arr_a = array();
//        $arr_b = array();
//        $arr_c = array();
//        $arr_d = array();
//        $arr_e = array();
//        $arr_f = array();
    $arr_a = getData14_A($inspection);
    $arr_b = getData14_B($inspection);
    $arr_c = getData14_C($inspection);
    $arr_d = getData14_D($inspection);
    $arr_e = getData14_E($inspection);
    $arr_f = getData14_F($inspection);
    $text_a = "     ";
    $text_b = "     ";
    $text_c = "     ";
    $text_d = "     ";
    $text_e = "     ";
    $text_f = "     ";
    $i = 1;
    if ($arr_a != null)
        foreach ($arr_a as $item) {
            if ($item != "") {
                $text_a .= Num2Unicode($i) . ". " . $item . "<w:br/>";
                $i++;
            }
        }
    $i = 1;
    if ($arr_b != null)
        foreach ($arr_b as $item) {
            if ($item != "") {
                $text_b .= Num2Unicode($i) . ". " . $item . "<w:br/>";
                $i++;
            }
        }
    $i = 1;
    if ($arr_c != null)
        foreach ($arr_c as $item) {
            if ($item != "") {
                $text_c .= Num2Unicode($i) . ". " . $item . "<w:br/>";
                $i++;
            }
        }

    $i = 1;
    if ($arr_d != null)
        foreach ($arr_d as $item) {
            if ($item != "") {
                $text_d .= Num2Unicode($i) . ". " . $item . "<w:br/>";
                $i++;
            }
        }
    $i = 1;
    if ($arr_e != null)
        foreach ($arr_e as $item) {
            if ($item != "") {
                $text_e .= Num2Unicode($i) . ". " . $item . "<w:br/>";
                $i++;
            }
        }

    $i = 1;
    if ($arr_f != null)
        foreach ($arr_f as $item) {
            $text_f .= Num2Unicode($i) . ". " . $item . "<w:br/>";
            $i++;
        }
    $phpWord->setValue('menu14_a', $text_a);
    $phpWord->setValue('menu14_b', $text_b);
    $phpWord->setValue('menu14_c', $text_c);
    $phpWord->setValue('menu14_d', $text_d);
    $phpWord->setValue('menu14_e', $text_e);
    $phpWord->setValue('menu14_f', $text_f);
}
function getTableColumns($table, $removeFirstCol=0, $removeLastCol=0)
{

    $arr=DB::getSchemaBuilder()->getColumnListing($table);
    $count= Count($arr);
    $arrRemoveFirst= array();
    $arrRemoveLast= array();
    if($removeFirstCol > 0){
        for($i=0; $i< $removeFirstCol; $i++){
            $arrRemoveFirst[$i]= $i;
        }

    }
    if($removeLastCol > 0){
        for($i= $count-$removeLastCol; $i< $count; $i++){
            $arrRemoveLast[$i]= $i;
        }

    }
    $arr = Arr::except($arr, $arrRemoveLast);
    $arr = Arr::except($arr, $arrRemoveFirst);
    return $arr;
}

// Table column getter functions - Table 11
if( ! function_exists('tbl_11_1_5a')){ function tbl_11_1_5a(){ return getTableColumns("tbl_11_1_5a", 3, 2); } }
if( ! function_exists('tbl_11_1_5b')){ function tbl_11_1_5b(){ return getTableColumns("tbl_11_1_5b", 3, 2); } }
if( ! function_exists('tbl_11_8')){ function tbl_11_8(){ return getTableColumns("tbl_11_note_8", 3, 2); } }
if( ! function_exists('tbl_11_9')){ function tbl_11_9(){ return getTableColumns("tbl_11_note_9", 3, 2); } }
if( ! function_exists('tbl_11_10')){ function tbl_11_10(){ return getTableColumns("tbl_11_note_10", 3, 2); } }

// Table column getter functions - Table 11 Other Category
if( ! function_exists('otbl_11_1_5a')){ function otbl_11_1_5a(){ return getTableColumns("tbl_011_1_5a", 3, 2); } }
if( ! function_exists('otbl_11_1_5b')){ function otbl_11_1_5b(){ return getTableColumns("tbl_011_1_5b", 3, 2); } }
if( ! function_exists('otbl_11_8')){ function otbl_11_8(){ return getTableColumns("tbl_011_note_8", 3, 2); } }
if( ! function_exists('otbl_11_9')){ function otbl_11_9(){ return getTableColumns("tbl_011_note_9", 3, 2); } }
if( ! function_exists('otbl_11_10')){ function otbl_11_10(){ return getTableColumns("tbl_011_note_10", 3, 2); } }

// Table column getter functions - Table 12
if( ! function_exists('tbl_12_1_5')){ function tbl_12_1_5(){ return getTableColumns("tbl_12_1_5", 3, 2); } }
if( ! function_exists('tbl_12_7')){ function tbl_12_7(){ return getTableColumns("tbl_12_note_7", 3, 2); } }

// Table column getter functions - Table 12 Other Category
if( ! function_exists('otbl_12_1_5')){ function otbl_12_1_5(){ return getTableColumns("tbl_012_1_5", 3, 2); } }
if( ! function_exists('otbl_12_7')){ function otbl_12_7(){ return getTableColumns("tbl_012_note_7", 3, 2); } }

// Table column getter functions - Table 14
if( ! function_exists('tbl_14_1_5a')){ function tbl_14_1_5a(){ return getTableColumns("tbl_14_1_5a", 3, 2); } }
if( ! function_exists('tbl_14_1_5b')){ function tbl_14_1_5b(){ return getTableColumns("tbl_14_1_5b", 3, 2); } }
if( ! function_exists('tbl_14_8')){ function tbl_14_8(){ return getTableColumns("tbl_14_restricted_8", 3, 2); } }
if( ! function_exists('tbl_14_9')){ function tbl_14_9(){ return getTableColumns("tbl_14_restricted_9", 3, 2); } }
if( ! function_exists('tbl_14_10')){ function tbl_14_10(){ return getTableColumns("tbl_14_restricted_10", 3, 2); } }

// Table column getter functions - Table 14 Other Category
if( ! function_exists('otbl_14_1_5a')){ function otbl_14_1_5a(){ return getTableColumns("tbl_014_1_5a", 3, 2); } }
if( ! function_exists('otbl_14_1_5b')){ function otbl_14_1_5b(){ return getTableColumns("tbl_014_1_5b", 3, 2); } }
if( ! function_exists('otbl_14_8')){ function otbl_14_8(){ return getTableColumns("tbl_014_restricted_8", 3, 2); } }
if( ! function_exists('otbl_14_9')){ function otbl_14_9(){ return getTableColumns("tbl_014_restricted_9", 3, 2); } }
if( ! function_exists('otbl_14_10')){ function otbl_14_10(){ return getTableColumns("tbl_014_restricted_10", 3, 2); } }

// Table column getter functions - Table 15
if( ! function_exists('tbl_15')){ function tbl_15(){ return getTableColumns("tbl_15", 3, 2); } }
if( ! function_exists('tbl_15A')){ function tbl_15A(){ return getTableColumns("tbl_15A", 3, 2); } }
if( ! function_exists('tbl_15B')){ function tbl_15B(){ return getTableColumns("tbl_15B", 3, 2); } }
if( ! function_exists('tbl_15C')){ function tbl_15C(){ return getTableColumns("tbl_15C", 3, 2); } }
if( ! function_exists('tbl_15D')){ function tbl_15D(){ return getTableColumns("tbl_15D", 3, 2); } }
if( ! function_exists('tbl_15E')){ function tbl_15E(){ return getTableColumns("tbl_15E", 3, 2); } }
if( ! function_exists('tbl_15F')){ function tbl_15F(){ return getTableColumns("tbl_15F", 3, 2); } }

// Table column getter functions - Table 15 Other Category
if( ! function_exists('otbl_15')){ function otbl_15(){ return getTableColumns("tbl_015", 3, 2); } }
if( ! function_exists('otbl_15A')){ function otbl_15A(){ return getTableColumns("tbl_015A", 3, 2); } }
if( ! function_exists('otbl_15B')){ function otbl_15B(){ return getTableColumns("tbl_015B", 3, 2); } }
if( ! function_exists('otbl_15C')){ function otbl_15C(){ return getTableColumns("tbl_015C", 3, 2); } }
if( ! function_exists('otbl_15D')){ function otbl_15D(){ return getTableColumns("tbl_015D", 3, 2); } }
if( ! function_exists('otbl_15E')){ function otbl_15E(){ return getTableColumns("tbl_015E", 3, 2); } }
if( ! function_exists('otbl_15F')){ function otbl_15F(){ return getTableColumns("tbl_015F", 3, 2); } }

// Additional helper functions
if( ! function_exists('companyBrand')){
    function companyBrand($inspection_id, $brand_product_id) {
        // Returns brand name for a product
        // TODO: Implement logic to fetch brand from database
        return '';
    }
}

if( ! function_exists('getCovid19LACMS')){
    function getCovid19LACMS($company_id) {
        // Returns COVID-19 LACMS data for company
        // TODO: Implement logic to fetch COVID data
        return null;
    }
}

if( ! function_exists('bankName')){
    function bankName($bank_id) {
        // Returns bank name by ID
        // TODO: Implement logic to fetch bank name from database
        return '';
    }
}












