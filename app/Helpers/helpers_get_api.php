<?php


use App\Models\Commune;
use App\Models\CompanyApi;
use App\Models\District;
use App\Models\Inspection;
use App\Models\Province;
use App\Models\User;
use App\Models\Village;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

function ApiToken(){
    return 'sdciqwksdsadfjJvVTFytoVCVBsa32897s28asdfk99a1xdzEjOHqNIs3yhdAICSXZ0Y';
}
/** Date: 04-09-2023 */
function getS18Status($status="", $approved_at=""){
    $str=" (".__('general.s18_approved_status2').")";/** ===default data is processing===*/
    if($status == __('general.s18_approved_status1')){/** ===if service is approved ===*/
        $approved_at= substr($approved_at, 0, 10);
        $str= " (".__('general.s18_approved2')." ".date2Display($approved_at).")";
    }
    return $str;
}
/** Date: 04-09-2023 */
function displayS18($sid, $arr_s18, $team=0){

    if($team != 1){//team=1 is self inspection, so do not need to show data
        $str= labelPurple(__('general.s18_'.$sid)).": ";
        if(is_array($arr_s18) || is_object($arr_s18)){
            $str.="";
            $arr_s18 = collect($arr_s18);
            if($sid == "0"){
                //print_r($arr_s18);
                $str.= __('general.s1_total_emp').": ".$arr_s18["total_staff"].__('general.k_neak');
            }
            elseif($sid == "1"){/** service1: table total estimate employee */
                //print_r($arr_s18);
                $total_khmer=$arr_s18["estimate_staff_amount"] - $arr_s18["foreign_staff_amount"];
                $total_khmer_female=$arr_s18["estimate_female_staff_amount"] - $arr_s18["foreign_female_staff_amount"];
                $status=" (".__('general.s18_approved_status2').")";
                if($arr_s18["status"] == __('general.s18_approved_status1')){//approved
                    $approved_at=$arr_s18["approved_at"];
                    $approved_at= substr($approved_at, 0, 10);
                    $status= " (".__('general.s18_approved_status1')." ".$approved_at.")";
                }
                $str="<table class='table table-bordered myformat myformat_1 purple'>";
                $str.="<tr class='my-bold'>";
                $str.="<td>".labelPurple('*'.__('general.s18_'.$sid)).$status."</td>";
                $str.="<td>".__('general.k_total_estimate')."</td>";
                $str.="<td>".__('general.k_female')."</td></tr>";

                $str.="<tr>";
                $str.="<td>".__('menu3.emp_khmer')."</td>";
                $str.="<td>".$total_khmer."</td>";
                $str.="<td>".$total_khmer_female."</td>";
                $str.="</tr>";

                $str.="<tr>";
                $str.="<td>".__('menu3.emp_foreigner')."</td>";
                $str.="<td>".$arr_s18["foreign_staff_amount"]."</td>";
                $str.="<td>".$arr_s18["foreign_female_staff_amount"]."</td>";
                $str.="</tr>";

                $str.="<tr>";
                $str.="<td>".__('menu3.emp_total_all')."</td>";
                $str.="<td>".$arr_s18["estimate_staff_amount"]."</td>";
                $str.="<td>".$arr_s18["estimate_female_staff_amount"]."</td>";
                $str.="</tr>";
                $str.="</table>";

                $result=$str;
                return $result;

            }
            elseif($sid == "1approve"){/** service1: show approve date only */
                $str=labelPurple(__('general.s18_1')).": ";
                $str.=getS18Status($arr_s18["status"], $arr_s18["approved_at"]);
                return $str;
            }
            elseif($sid == "1leave"){/** service1: weekly leave info */
                $leave= isset($arr_s18["working_hours_type"])? $arr_s18["working_hours_type"] : "";
                $label=__('general.general.s18_1').": ".__('general.com_1_1_weekly_leave'). __('general.k_is').__('general.com_1_1_weekly_'.$leave);
                ///return LabelPurple($label);

            }
            elseif($sid == "1head"){/** service1: head office info */
//                    dd($arr_s18);
                $str=__('general.s18_1').": ";
                if(isset($arr_s18["oversea_address"])  &&  $arr_s18["oversea_address"] !="")
                    $str.= $arr_s18['oversea_address'];
                else{
                    //dd($arr_s18);
                    $province_id =isset($arr_s18['head_province'])? $arr_s18['head_province']:0;
                    $district_id =isset($arr_s18['head_district'])? $arr_s18['head_district']:0;
                    $commune_id =isset($arr_s18['head_commune'])? $arr_s18['head_commune']:0;
                    $village_id =isset($arr_s18['head_village'])? $arr_s18['head_village']:0;
                    //dd(Province::select("pro_khname")->where("pro_id", $province_id)->first()->pro_khname);
                    $str.="<b>".__('g1.enterprise_province')."</b>: ".Province::select("pro_khname")->where("pro_id", $province_id)->first()->pro_khname;
                    $str.=" <b>".__('g1.enterprise_district')."</b>: ".District::select("dis_khname")->where("dis_id", $district_id)->first()->dis_khname;
                    $str.=" <b>".__('g1.enterprise_commune')."</b>: ";
                    if($commune_id > 0){
                        $str.= Commune::select("com_khname")->where("com_id", $commune_id)->first()->com_khname;
                    }
                    $str.=" <b>".__('g1.enterprise_village')."</b>: ";
                    if($village_id > 0){
                        $str.= Village::select("vil_khname")->where("vil_id", $village_id)->first()->vil_khname;
                    }


                    $str.=" <b>".__('g1.enterprise_group')."</b>: ";
                    $str.= isset($arr_s18["head_group"])? $arr_s18["head_group"]: "";
                    $str.=" <b>".__('g1.enterprise_street')."</b>: ";
                    $str.= isset($arr_s18["head_street"])? $arr_s18["head_street"]: "";
                    $str.=" <b>".__('g1.enterprise_house_no')."</b>: ";
                    $str.=isset($arr_s18["head_house_no"])? $arr_s18["head_house_no"]: "";
                }
                return labelPurple($str);
            }
            elseif($sid == 2){
                //print_r($arr_s18);
                $str=labelPurple(__('general.s18_'.$sid)).": ";
                $str.=getS18Status($arr_s18["status"], $arr_s18["approved_at"]);
                return $str;
            }
            elseif($sid == 3){
                //print_r($arr_s18);
                $str=labelPurple(__('general.s18_'.$sid)).": ";
                $str.=getS18Status($arr_s18["status"], $arr_s18["approved_at"]);
                return $str;
            }
            elseif($sid == 33){
                //print_r($arr_s18);
                $str=labelPurple(__('general.s18_'.$sid)).": ";
                $str.=getS18Status($arr_s18["status"], $arr_s18["approved_at"]);
                return $str;
            }
            elseif($sid == 333){
                //print_r($arr_s18);
                $str=labelPurple(__('general.s18_'.$sid)).": ";
                $str.=getS18Status($arr_s18["status"], $arr_s18["approved_at"]);
                return $str;
            }
            elseif($sid == 4){
                //print_r($arr_s18['approved_at']);
                $str=labelPurple(__('general.s18_'.$sid)).": ";
                $str.=getS18Status(isset($arr_s18["status"])? $arr_s18["status"]:"", isset($arr_s18['approved_at'])? $arr_s18['approved_at']:"");
                return $str;
            }
            elseif($sid == 5){
                //print_r($arr_s18);
                $str=labelPurple(__('general.s18_'.$sid)).": ";
                $approved_at=$arr_s18["approve_created_date"];
                if($approved_at !=""){
                    $approved_at= substr($approved_at, 0, 10);
                    $approved_at = date2Display($approved_at);
                    //$str.="<span style='color:#000000;'>".lang('s18_13_approved')." ".$approved_at."</span>";

                    $approved=$arr_s18["total_approve_approve"];
                    $pending=$arr_s18["total_pending_request"];
                    $request= $approved + $pending;
                    $str.="<table class='table table-bordered myformat purple' style='color:#000'>";
                    $str.="<tr>";
                    $str.="<td>".__('general.s5_amount_request')."</td>";
                    $str.="<td>".__('general.s5_amount_approved')."</td>";
                    $str.="<td>".__('general.s5_approved_date')."</td>";
                    $str.="<td>".__('general.s5_amount_process')."</td>";
                    $str.="</tr>";

                    $str.="<tr>";
                    $str.="<td>".$request."</td>";
                    $str.="<td>".$approved."</td>";
                    $str.="<td>".$approved_at."</td>";
                    $str.="<td>".$pending."</td>";
                    $str.="</tr>";

                    $str.="</table>";

                }

                return $str;
            }
            elseif($sid == 6){
                //print_r($arr_s18);
                $str=labelPurple(__('general.s18_'.$sid)).": ";
                $approved_at=$arr_s18["approve_created_date"];
                if($approved_at !=""){
                    $approved_at= substr($approved_at, 0, 10);
                    $approved_at = date2Display($approved_at);
                    //$str.="<span style='color:#000000;'>".lang('s18_13_approved')." ".$approved_at."</span>";

                    $approved=$arr_s18["total_approve_request"];
                    $pending=$arr_s18["total_pending_request"];
                    $request= $approved + $pending;
                    $str.="<table class='table table-bordered myformat purple' style='color:#000'>";
                    $str.="<tr>";
                    $str.="<td>".__('general.s6_amount_request')."</td>";
                    $str.="<td>".__('general.s6_amount_approved')."</td>";
                    $str.="<td>".__('general.s6_approved_date')."</td>";
                    $str.="<td>".__('general.s6_amount_process')."</td>";
                    $str.="</tr>";

                    $str.="<tr>";
                    $str.="<td>".$request."</td>";
                    $str.="<td>".$approved."</td>";
                    $str.="<td>".$approved_at."</td>";
                    $str.="<td>".$pending."</td>";
                    $str.="</tr>";

                    $str.="</table>";

                }

                return $str;
            }
            elseif($sid == 7){
                //print_r($arr_s18);
                $total_khmer=$arr_s18['total_staff'] - $arr_s18['total_staff_foreigner'];
                $total_khmer_female=$arr_s18['total_staff_female']-$arr_s18['total_staff_foreigner_female'];
                $status=getS18Status($arr_s18['status'], $arr_s18['approved_at']);

                $str="<table class='table table-bordered myformat myformat_1 purple'>";
                $str.="<tr class='my-bold'>";
                $str.="<td>".labelPurple('*'.__('general.s18_'.$sid)).$status."</td>";
                $str.="<td>".__('general.k_total_employee2')."</td>";
                $str.="<td>".__('general.k_female')."</td></tr>";

                $str.="<tr>";
                $str.="<td>ខ្មែរ</td>";
                $str.="<td>".$total_khmer."</td>";
                $str.="<td>".$total_khmer_female."</td>";
                $str.="</tr>";

                $str.="<tr>";
                $str.="<td>បរទេស</td>";
                $str.="<td>".$arr_s18['total_staff_foreigner']."</td>";
                $str.="<td>".$arr_s18['total_staff_foreigner_female']."</td>";
                $str.="</tr>";

                $str.="<tr>";
                $str.="<td>សរុបទាំងអស់</td>";
                $str.="<td>".$arr_s18['total_staff']."</td>";
                $str.="<td>".$arr_s18['total_staff_female']."</td>";
                $str.="</tr>";
                $str.="</table>";

                return $str;
            }
            elseif($sid == 77){
                //print_r($arr_s18);
                $total_khmer=$arr_s18['total_staff'] - $arr_s18['total_staff_foreigner'];
                $total_khmer_female=$arr_s18['total_staff_female']-$arr_s18['total_staff_foreigner_female'];
                $status=getS18Status($arr_s18['status'], $arr_s18['approved_at']);
                $str = "<div>".labelPurple('*'.__('general.s18_'.$sid)).$status."</div>";
                return $str;
            }
            elseif($sid == 8){
                //print_r($arr_s18);
                $total_khmer=$arr_s18['total_staff'] - $arr_s18['total_staff_foreigner'];
                $total_khmer_female=$arr_s18['total_staff_female']-$arr_s18['total_staff_foreigner_female'];
                $status=getS18Status($arr_s18['status'], $arr_s18['approved_at']);
                $str="<table class='table table-bordered myformat myformat_1 purple'>";
                $str.="<tr class='my-bold'>";
                $str.="<td>".labelPurple('*'.__('general.s18_'.$sid)).$status."</td>";
                $str.="<td>".__('general.k_total_employee2')."</td>";
                $str.="<td>".__('general.k_female')."</td></tr>";

                $str.="<tr>";
                $str.="<td>ខ្មែរ</td>";
                $str.="<td>".$total_khmer."</td>";
                $str.="<td>".$total_khmer_female."</td>";
                $str.="</tr>";

                $str.="<tr>";
                $str.="<td>បរទេស</td>";
                $str.="<td>".$arr_s18['total_staff_foreigner']."</td>";
                $str.="<td>".$arr_s18['total_staff_foreigner_female']."</td>";
                $str.="</tr>";

                $str.="<tr>";
                $str.="<td>សរុបទាំងអស់</td>";
                $str.="<td>".$arr_s18['total_staff']."</td>";
                $str.="<td>".$arr_s18['total_staff_female']."</td>";
                $str.="</tr>";
                $str.="</table>";

                return $str;
            }
            elseif($sid == 88){
                $total_khmer=$arr_s18['total_staff'] - $arr_s18['total_staff_foreigner'];
                $total_khmer_female=$arr_s18['total_staff_female']-$arr_s18['total_staff_foreigner_female'];
                $status=getS18Status($arr_s18['status'], $arr_s18['approved_at']);
                $str = "<div>".labelPurple('*'.__('general.s18_'.$sid)).$status."</div>";
                return $str;
            }
            elseif($sid == 9){
                print_r($arr_s18);

                return $str;
            }

            elseif($sid == 10){
                //print_r($arr_s18);
                $str=labelPurple(__('general.s18_'.$sid)).": ";

                $status= $arr_s18['status'];
                $approved_at=$arr_s18['approved_at'];
                if($approved_at !=""){
                    $approved_at= substr($approved_at, 0, 10);
                    $approved_at = date2Display($approved_at);

                    $status.=" (".$approved_at.")";
                }else{
                    $submitted_at= date2Display($arr_s18['submitted_at']);
                    $status.= " (".__('general.s11_rdate')." ".$submitted_at.")";
                }
                $str.="<table class='table table-bordered myformat purple' style='color:#000'>";
                $str.="<tr>";
                $str.="<td>".__('general.s11_status')."</td>";
                $str.="<td>".__('general.s11_request_vote_type')."</td>";
                $str.="<td>".__('general.s11_term')."</td>";
                //$str.="<td>".__('general.s11_sdate')."</td>";
                //$str.="<td>".__('general.s11_edate')."</td>";
                $str.="<td>".__('general.s11_member')."</td>";
                $str.="</tr>";

                $str.="<tr>";
                $str.="<td>".$status."</td>";
                $str.="<td>".$arr_s18['request_vote_type']."</td>";
                $str.="<td>".$arr_s18['term'].__('general.k_year')." (".$arr_s18['start_date']." ដល់ ".$arr_s18['end_date'].")"."</td>";//.$arr_s18->term
                $str.="<td>".$arr_s18['member'].__('general.k_neak')." (".__('general.k_female')." ".$arr_s18['member_female'].__('general.k_neak').")"."</td>";
                $str.="</tr>";

                $str.="</table>";


                return $str;
            }
            elseif($sid == 11){
                print_r($arr_s18);

                return $str;
            }
            elseif($sid == 12){
                //print_r($arr_s18);
                $str=labelPurple(__('general.s18_'.$sid)).": ";
                $approved_at=$arr_s18['approved_at'];
                if($approved_at !=""){/** approval 8*/
                    $approved_at= substr($approved_at, 0, 10);
                    $str.="<span style='color:#000000;'>".__('general.s18_12_approved')." ".date2Display($approved_at)."</span>";
                }
                else{/** processing */
                    $date_request= $arr_s18['submitted_at'];
                    $str.="<span style='color:#000000;'>".__('general.s18_12_process')." ".date2Display($date_request)."</span>";

                }
                return $str;
            }
            elseif($sid == 13){
                //print_r($arr_s18);
                $str=labelPurple(__('general.s18_'.$sid)).": ";
                $approved_at=$arr_s18['approved_at'];
                if($approved_at !=""){
                    $approved_at= substr($approved_at, 0, 10);
                    $str.="<span style='color:#000000;'>".__('general.s18_13_approved')." ".date2Display($approved_at)."</span>";
                }
                else{// processing
                    $date_request= $arr_s18['submitted_at'];
                    $str.="<span style='color:#000000;'>".__('general.s18_13_process')." ".date2Display($date_request)."</span>";

                }
                return $str;
            }
            elseif($sid == 14){
                //print_r($arr_s18);

                return $str;
            }
            elseif($sid == 15){
                //print_r($arr_s18);
                $str=labelPurple(__('general.s18_'.$sid)).": ";
                $approved_at=$arr_s18['approved_at'];
                if($approved_at !=""){
                    $approved_at= substr($approved_at, 0, 10);
                    $str.="<span style='color:#000000;'>".__('general.s18_15_approved')." ".date2Display($approved_at)."</span>";
                }
                else{// processing
                    $date_request= $arr_s18['submitted_at'];
                    $str.="<span style='color:#000000;'>".__('general.s18_15_process')." ".date2Display($date_request)."</span>";

                }
                return $str;
            }
            elseif($sid == 16){
                //print_r($arr_s18);
            }
            elseif($sid == 17){
                //print_r($arr_s18);
                $status=labelPurple(__('general.s18_'.$sid)).": ";
                $status.=getS18Status($arr_s18['status'], $arr_s18['approved_at']);
                $str="<table class='table table-bordered myformat purple' style='color:#000'>";
                $str.="<tr><td colspan='4'>".$status."</td></tr>";
                $str.="<tr>";
                $str.="<td>".__('general.s17_type')."</td>";
                $str.="<td>".__('general.s17_date')."</td>";
                $str.="<td>".__('general.s17_total_staff')."</td>";
                $str.="<td>".__('general.s17_total_female')."</td>";
                $str.="</tr>";

                $str.="<tr>";
                $str.="<td>".$arr_s18['type']."</td>";
                $str.="<td>".$arr_s18['from_date'].__('k_to').$arr_s18->to_date."</td>";
                $str.="<td>".$arr_s18['total_staff']."</td>";
                $str.="<td>".$arr_s18['total_staff_female']."</td>";
                $str.="</tr>";

                $str.="</table>";
                return $str;
            }
            elseif($sid == 18){
                print_r($arr_s18);
            }

        }else{
            $str.=LabelPurple(__('general.s18_no_reg'));
            return $str;
        }
    }
    //return $str;
}
function lacmsDomain()
{
    $domain = "https://lacms.mlvt.gov.kh";//real domain
    //$domain="https://lacms.fwcms.co";//Testing Domain
    //$domain="https://cems.mlvt.gov.kh";//Testing Domain
    return $domain;
}
/** Date: 04-09-2023 */
function ApiAdmin($company_id="", $service = "0", $team=0){
    if($team != 1){ //team=1 is self inspection, so do nothing

        $company_encrypt = CompanyApi::select("encrypt_id")->where("company_id", $company_id)->first()->encrypt_id;
        if($company_encrypt == "") $company_encrypt='nokey';
        $arr_service= array(
            '0' => lacmsDomain().'/api/company/detail',
            '1' => lacmsDomain().'/api/registration/detail',//service1
            '2' => lacmsDomain().'/api/inspection/detail/s1',//service2
            '3' => lacmsDomain().'/api/inspection/ot/detail/s1',//service3
            '3a' => lacmsDomain().'/api/inspection/detail/s2',//service3
            '3b' => lacmsDomain().'/api/inspection/detail/s3',//service3
            '4' => lacmsDomain().'/api/inspection/ir/detail',
            '5' => lacmsDomain().'/api/physical/detail',
            '6' => lacmsDomain().'/api/bio/detail',//update 23-11-2021

            '7' => lacmsDomain().'/api/staffmovement/detail/s2',
            '8' => lacmsDomain().'/api/staffmovement/detail/s3',
            '9' => lacmsDomain().'/api/bio/detail/reprint',
            '10' => lacmsDomain().'/api/vote/detail',
            '11' => lacmsDomain().'/api/children/detail/s1',
            '12' => lacmsDomain().'/api/children/detail/s2',
            '13' => lacmsDomain().'/api/inspection/ot/detail/s1',
            '14' => lacmsDomain().'/api/inspection/ot/detail/s2',
            '15' => lacmsDomain().'/api/inspection/ot/detail/s3',
            '16' => '',
            '17' => lacmsDomain().'/api/apprenticeship/detail',
            '18' => '',
            '19' => 'http://google.com/test',
            '20' => lacmsDomain().'/api/company/list',

            //18-09-21 latest worker excel and worker work book data
            '29' => lacmsDomain().'api/company/worker/total', // បញ្ជីបច្ចុប្បន្នភាពបូកនឹងការស្នើសុំសៀវភៅការងារ
            '30' => lacmsDomain().'/api/company/worker/list',//បញ្ជីបច្ចុប្បន្នភាព, latest worker excel, can delete and add new worker, not related to book_id, All worker that active in company
            '31' => lacmsDomain().'/api/bio/detail/list',//that do book, list all worker that have book in company
            '32' => lacmsDomain().'/api/company/worker/list/total',//total worker in company 15-11-21 Related to 30
            '33'=> lacmsDomain().'/api/detail/wp',//FWP00187093
            '34' => lacmsDomain().'/api/company/worker/list/trash',//get worker that trash
            //27-07-2022: for covid august 2022
            '35' => lacmsDomain().'/api/company/worker/vaccination',
            '36' => lacmsDomain().'/api/company/detail/upload',
        );

        if($arr_service[$service] !=''){
            $client = new Client(['headers' => ['Content-Type' => 'application/x-www-form-urlencoded', 'token' => ApiToken()]]);
            try {
                $response = $client->request(
                    'POST',
                    $arr_service[$service],
                    ['form_params' => ['id' => $company_encrypt]]
                );
                //print_r($response);
                $com_attachment = json_decode($response->getBody());
                //print_r($com_attachment);
                $result= isset($com_attachment->{'results'})? $com_attachment->{'results'}: "";
                //print_r($com_attachment);
                //echo "Hello";
                //print_r($result);
                return $result;

            }
            catch (GuzzleHttp\Exception\RequestException $e) {
                //catch (GuzzleHttp\Exception\ConnectException $e) {
                //$response = $e->getResponse();
                //$responseBodyAsString = $response->getBody()->getContents();
                //echo "Error1";
                //print_r($response);
                //$r=array();
                //$CI->session->set_flashdata('error_request', $response);
                $r="no_result_found";
                return $r;


            }
            catch (GuzzleHttp\Exception\ConnectException $e) {//error connection
                $r="Could not resolve the server cURL.";
               //$response = $e->getResponse();
                //$responseBodyAsString = $response->getBody()->getContents();
                //echo "bb";
                //echo "Error2";
                //print_r($response);
                //$CI->session->set_flashdata('error_connection', $response);
                return $r;
            }

        }else{
            return array();
        }
    }//else{ return "";}

}


if ( ! function_exists('ApiCompanyDetail')){
    function ApiCompanyDetail($company_id, $is_admin = 0){
//        $CI = get_instance();
//        $CI->load->model('model_main', "main");
        //$session=$CI->session->all_userdata();
        $result=array();
//        if($is_admin == 0)
//            $company_encrypt=$CI->main->select_one_data("tbl_accounts", "company_encrypt", array('company_id'=>$company_id));
//        else
//            $company_encrypt=$CI->main->select_one_data("tbl_company_api", "encrypt_id", array('company_id'=>$company_id));
        $company_encrypt = CompanyApi::select("encrypt_id")->where("company_id", $company_id)->first()->encrypt_id;
        //echo "Encrypt: ".$company_encrypt;
        $client = new Client(['headers' => ['Content-Type' => 'application/x-www-form-urlencoded', 'token' => ApiToken()]]);
        try {
            $response = $client->request(
                'POST',
                lacmsDomain().'/api/company/detail',
                ['form_params' => ['id' => $company_encrypt]]
            );
            $com_info = json_decode($response->getBody());
            //print_r($com_info);
            $result= isset($com_info->{'results'})? $com_info->{'results'} : 0 ;
            //echo "<br>=========>".$arr->id;
            //redirect('', 'location');
            return $result;

        } catch (GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            return 0;
            //echo "<br>No Record Found.";
            //redirect('http://rumdoul.com/sothea/inspection_v3/auth/login/', 'location');
            //var_dump($responseBodyAsString);
        }
    }
}//end function

/** 27-06-23: get all company list full but with page */
if ( ! function_exists('ApiCompanyList')){
    function ApiCompanyList($page = 1, $per_page=2000){
//        $CI = get_instance();
//        $CI->load->model('model_main', "main");
        //$session=$CI->session->all_userdata();
        $result=array();
        //$company_encrypt=$CI->main->select_one_data("tbl_accounts", "company_encrypt", array('company_id'=>$company_id));
        $company_encrypt='nokey';
        $client = new Client(['headers' => ['Content-Type' => 'application/x-www-form-urlencoded', 'token' => ApiToken()]]);
        try {
            $response = $client->request(
                'POST',
                lacmsDomain().'/api/company/list/full', //apiData in index=20
                ['form_params' => ['id' => $company_encrypt, 'page'=>$page, 'per_page'=> $per_page] ]
            );
            $com_attachment = json_decode($response->getBody());
            $result= isset($com_attachment->{'results'})? $com_attachment->{'results'}: "";
            //dd($result);
            return $result;
            //return $com_attachment;

        } catch (GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            return 0;
        }
    }
}//end function
if ( ! function_exists('ApiData')){
    function ApiData($company_id, $service = "0", $is_admin=1){
//        $CI = get_instance();
//        $CI->load->model('model_main', "main");
        //$session=$CI->session->all_userdata();
        $result=array();
        //$company_encrypt=$CI->main->select_one_data("tbl_accounts", "company_encrypt", array('company_id'=>$company_id));
        $company_encrypt = CompanyApi::select("encrypt_id")->where("company_id", $company_id)->first()->encrypt_id;
//        if($is_admin == 0)
//            $company_encrypt=$CI->main->select_one_data("tbl_accounts", "company_encrypt", array('company_id'=>$company_id));
//        else
//            $company_encrypt=$CI->main->select_one_data("tbl_company_api", "encrypt_id", array('company_id'=>$company_id));

        if($company_encrypt == "") $company_encrypt = 'nokey';

        //echo "<br>Key: ".$company_encrypt."ABC:<br>";
        $arr_service= array(
            '0' => lacmsDomain().'/api/company/detail',
            '1' => lacmsDomain().'/api/registration/detail',//service1
            '2' => lacmsDomain().'/api/inspection/detail/s1',//service2
            '3' => lacmsDomain().'/api/inspection/ot/detail/s1',//service3
            '3a' => lacmsDomain().'/api/inspection/detail/s2',//service3
            '3b' => lacmsDomain().'/api/inspection/detail/s3',//service3
            '4' => lacmsDomain().'/api/inspection/ir/detail',
            '5' => lacmsDomain().'/api/physical/detail',
            '6' => lacmsDomain().'/api/bio/detail',
            '7' => lacmsDomain().'/api/staffmovement/detail/s2',
            '8' => lacmsDomain().'/api/staffmovement/detail/s3',
            '9' => lacmsDomain().'/api/bio/detail/reprint',
            '10' => lacmsDomain().'/api/vote/detail',
            '11' => lacmsDomain().'/api/children/detail/s1',
            '12' => lacmsDomain().'/api/children/detail/s2',
            '13' => lacmsDomain().'/api/inspection/ot/detail/s2',
            '14' => lacmsDomain().'/api/inspection/ot/detail/s3',
            '15' => lacmsDomain().'/api/inspection/ot/detail/s3',
            '16' => '',
            '17' => lacmsDomain().'https://lacms.mlvt.gov.kh/api/apprenticeship/detail',
            '18' => '',
            '19' => 'http://google.com/test',
            '20'  => lacmsDomain().'/api/company/list/full',  //get all company from lacms (Full List)
            '201' => lacmsDomain().'/api/company/list', // get last 7 days approval company from lacms

            //18-09-21 latest worker excel and worker work book data
            '30' => lacmsDomain().'/api/company/worker/list',//latest worker excel, can delete and add new worker, not related to book_id
            '31' => lacmsDomain().'/api/bio/detail/list',//
            '32' => lacmsDomain().'/api/company/worker/list/total',//total worker in company 15-11-21 Related to 30
            '33'=> lacmsDomain().'/api/detail/wp',//FWP00187093
            '34' => lacmsDomain().'/api/company/worker/list/trash',//get worker that trash

        );

        if($arr_service[$service] !=''){
            //echo "<br>Hello: ".$arr_service[$service]."<br>";
            $client = new Client(['headers' => ['Content-Type' => 'application/x-www-form-urlencoded', 'token' => ApiToken()]]);
            try {
                $response = $client->request(
                    'POST', $arr_service[$service],
                    ['form_params' => ['id' => $company_encrypt]]
                );
                $com_attachment = json_decode($response->getBody());
                $result= isset($com_attachment->{'results'})? $com_attachment->{'results'}: "";
                //print_r($com_attachment);
                //print_r($result);
                return $result;
            }


            catch (GuzzleHttp\Exception\RequestException $e) {
                //$response = $e->getResponse();
                //$responseBodyAsString = $response->getBody()->getContents();
                return 0;
            }

        }else{
            return array();
        }

    }//end function
}


/** 18-09-21: get worker data */
if ( ! function_exists('ApiWorkerData')){
    function ApiWorkerData($worker_id='0', $service = "0"){
        $CI = get_instance();
        $CI->load->model('model_main', "main");
        //$session=$CI->session->all_userdata();
        $result=array();
        //$company_encrypt=$CI->main->select_one_data("tbl_accounts", "company_encrypt", array('company_id'=>$company_id));
        //if($company_encrypt == "") $company_encrypt='nokey';
        //echo "Key: ".$company_encrypt;
        $arr_service= array(
            '0' => lacmsDomain().'/api/bio/detail/worker',
            //'1' => lacmsDomain().'/api/bio/detail/worker'
        );
        //echo $arr_service[$service];
        if($arr_service[$service] !=''){
            $client = new Client(['headers' => ['Content-Type' => 'application/x-www-form-urlencoded', 'token' => ApiToken()]]);
            try {
                $response = $client->request(
                    'POST',
                    $arr_service[$service],
                    ['form_params' => ['id' => $worker_id]]
                );
                $worker_attachment = json_decode($response->getBody());
                $result= isset($worker_attachment->{'results'})? $worker_attachment->{'results'}: "";
                return $result;
                //return $worker_attachment;
            }

            catch (GuzzleHttp\Exception\RequestException $e) {
                //$response = $e->getResponse();
                //$responseBodyAsString = $response->getBody()->getContents();
                return 0;
            }

        }else{
            return array();
        }

    }//end function
}
/** for cron job only*/
if ( ! function_exists('ApiCronjob')){
    function ApiCronjob($company_id="", $company_encrypt="", $service = "0", $team=0){
        if($team != 1){ //team=1 is self inspection, so do nothing
            $CI = get_instance();
            $CI->load->model('model_main', "main");
            //$session=$CI->session->all_userdata();
            $result=array();
            //$company_encrypt=$CI->main->select_one_data("tbl_company_api", "encrypt_id", array('company_id'=>$company_id));
            //if($company_encrypt == "") $company_encrypt='nokey';

            //echo "Key: ".$company_encrypt;
            $arr_service= array(
                '0' => lacmsDomain().'/api/company/detail',
                '1' => lacmsDomain().'/api/registration/detail',//service1
                '2' => lacmsDomain().'/api/inspection/detail/s1',//service2
                '3' => lacmsDomain().'/api/inspection/ot/detail/s1',//service3
                '3a' => lacmsDomain().'/api/inspection/detail/s2',//service3
                '3b' => lacmsDomain().'/api/inspection/detail/s3',//service3
                '4' => lacmsDomain().'/api/inspection/ir/detail',
                '5' => lacmsDomain().'/api/physical/detail',
                '6' => lacmsDomain().'/api/bio/detail',
                '7' => lacmsDomain().'/api/staffmovement/detail/s2',
                '8' => lacmsDomain().'/api/staffmovement/detail/s3',
                '9' => lacmsDomain().'/api/bio/detail/reprint',
                '10' => lacmsDomain().'/api/vote/detail',
                '11' => lacmsDomain().'/api/children/detail/s1',
                '12' => lacmsDomain().'/api/children/detail/s2',
                '13' => lacmsDomain().'/api/inspection/ot/detail/s2',
                '14' => lacmsDomain().'/api/inspection/ot/detail/s3',
                '15' => lacmsDomain().'/api/inspection/ot/detail/s3',
                '16' => '',
                '17' => lacmsDomain().'/api/apprenticeship/detail',
                '18' => '',
                '19' => 'http://google.com/test',
                '20' => lacmsDomain().'/api/company/list',

                //18-09-21 latest worker excel and worker work book data
                '30' => lacmsDomain().'/api/company/worker/list',//latest worker excel, can delete and add new worker, not related to book_id
                '31' => lacmsDomain().'/api/bio/detail/list',//
                '32' => lacmsDomain().'/api/company/worker/list/total',//total worker in company 15-11-21 Related to 30
                '33'=> lacmsDomain().'/api/detail/wp',//FWP00187093
                '34' => lacmsDomain().'api/company/worker/list/trash',//get worker that trash
            );
            //echo $arr_service[$service];
            if($arr_service[$service] !=''){
                $client = new Client(['headers' => ['Content-Type' => 'application/x-www-form-urlencoded', 'token' => ApiToken()]]);
                try {
                    $response = $client->request(
                        'POST',
                        $arr_service[$service],
                        ['form_params' => ['id' => $company_encrypt]]
                    );
                    $com_attachment = json_decode($response->getBody());
                    $result= isset($com_attachment->{'results'})? $com_attachment->{'results'}: "";
                    //print_r($com_attachment);
                    //echo "Hello";
                    return $result;

                }
                catch (GuzzleHttp\Exception\RequestException $e) {
                    //catch (GuzzleHttp\Exception\ConnectException $e) {
                    //$response = $e->getResponse();
                    //$responseBodyAsString = $response->getBody()->getContents();
                    $r="no_result_found.";
                    return $r;


                }
                catch (GuzzleHttp\Exception\ConnectException $e) {//error connection
                    $r="Could not resolve the server cURL.";
                    //$response = $e->getResponse();
                    //$responseBodyAsString = $response->getBody()->getContents();
                    //echo "bb";
                    //print_r($response);
                    return $r;
                }

            }else{
                return array();
            }
        }//else{ return "";}
    }//end function
}
if ( ! function_exists('APITesting')){
    function APITesting(){
        $CI = get_instance();
        $CI->load->model('model_main', "main");
        //$session=$CI->session->all_userdata();
        $result=array();
        //$company_encrypt=$CI->main->select_one_data("tbl_accounts", "company_encrypt", array('company_id'=>$company_id));

        $client = new Client(['headers' => [
            //'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => 'Bearer 1|xN9rXC9kg0RnOGxaj2lzjcWrwunaQsJ2tuTrZbaT']]);
        try {
            echo "Success";
            $response = $client->request(
                'GET',
                'http://localhost:7777/laravel/ppsfortify/api/getdata',
            //['form_params' => ['id' => $company_encrypt]]
            );
            print_r( json_decode($response->getBody()) );
            //$com_attachment = json_decode($response->getBody());
            //print_r($com_attachment);
            //$result= $com_attachment->{'results'};
            //return $result;

        } catch (GuzzleHttp\Exception\ClientException $e) {
            echo "failed";
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            return 0;
        }
    }
}//end function
/** All API here */
if ( ! function_exists('ApiAdminTest')){
    function ApiAdminTest($company_id="", $service = "0", $team=0){
        if($team != 1){ //team=1 is self inspection, so do nothing
            $CI = get_instance();

            $CI->load->model('model_main', "main");
            $CI->load->library('session');
            //$session=$CI->session->all_userdata();
            $result=array();
            $company_encrypt=$CI->main->select_one_data("tbl_company_api", "encrypt_id", array('company_id'=>$company_id));
            if($company_encrypt == "") $company_encrypt='nokey';

            //echo "<br> Company Encrypt Key: ".$company_encrypt."<br>";
            $arr_service= array(
                '0' => lacmsDomain().'/api/company/detail',
                '1' => lacmsDomain().'/api/registration/detail',//service1
                '2' => lacmsDomain().'/api/inspection/detail/s1',//service2
                '3' => lacmsDomain().'/api/inspection/ot/detail/s1',//service3
                '3a' => lacmsDomain().'/api/inspection/detail/s2',//service3
                '3b' => lacmsDomain().'/api/inspection/detail/s3',//service3
                '4' => lacmsDomain().'/api/inspection/ir/detail',
                '5' => lacmsDomain().'/api/physical/detail',
                '6' => lacmsDomain().'/api/bio/detail',//update 23-11-2021

                '7' => lacmsDomain().'/api/staffmovement/detail/s2',
                '8' => lacmsDomain().'/api/staffmovement/detail/s3',
                '9' => lacmsDomain().'/api/bio/detail/reprint',
                '10' => lacmsDomain().'/api/vote/detail',
                '11' => lacmsDomain().'/api/children/detail/s1',
                '12' => lacmsDomain().'/api/children/detail/s2',
                '13' => lacmsDomain().'/api/inspection/ot/detail/s1',
                '14' => lacmsDomain().'/api/inspection/ot/detail/s2',
                '15' => lacmsDomain().'/api/inspection/ot/detail/s3',
                '16' => '',
                '17' => lacmsDomain().'/api/apprenticeship/detail',
                '18' => '',
                '19' => 'http://google.com/test',
                '20' => lacmsDomain().'/api/company/list',

                //18-09-21 latest worker excel and worker work book data
                '30' => lacmsDomain().'/api/company/worker/list',//latest worker excel, can delete and add new worker, not related to book_id, All worker that active in company
                '31' => lacmsDomain().'/api/bio/detail/list',//that do book
                '32' => lacmsDomain().'/api/company/worker/list/total',//total worker in company 15-11-21 Related to 30
                '33'=> lacmsDomain().'/api/detail/wp',//FWP00187093
                '34' => lacmsDomain().'/api/company/worker/list/trash',//get worker that trash

                //27-07-2022: for covid august 2022
                '35' => lacmsDomain().'/api/company/worker/vaccination',
                '36' => lacmsDomain().'/api/company/detail/upload',
            );
            /**
             * '6'=stdClass Object ( [total_pending_request] => 50 [total_pending_female_request] => 28 [total_approve_request] => 650 [total_approve_female_request] => 439 [pending_created_date] => 2021-11-27 [approve_created_date] => 2021-10-22 )
             *
             *
             */
            //echo $arr_service[$service];
            if($arr_service[$service] !=''){
                $client = new Client(['headers' => ['Content-Type' => 'application/x-www-form-urlencoded', 'token' => ApiToken()]]);
                try {
                    $response = $client->request(
                        'POST',
                        $arr_service[$service],
                        ['form_params' => ['id' => $company_encrypt]]
                    );
                    $com_attachment = json_decode($response->getBody());
                    //print_r($com_attachment);
                    $result= isset($com_attachment->{'results'})? $com_attachment->{'results'}: "";
                    //print_r($com_attachment);
                    //echo "Hello";
                    return $result;

                }
                catch (GuzzleHttp\Exception\RequestException $e) {
                    //catch (GuzzleHttp\Exception\ConnectException $e) {
                    $response = $e->getResponse();
                    $responseBodyAsString = $response->getBody()->getContents();
                    //echo "Error1";
                    print_r($responseBodyAsString);
                    //$r=array();
                    //$CI->session->set_flashdata('error_request', $response);
                    $r="no_result_found";
                    return $r;


                }
                catch (GuzzleHttp\Exception\ConnectException $e) {//error connection
                    $r="Could not resolve the server cURL.";
                    $response = $e->getResponse();
                    //$responseBodyAsString = $response->getBody()->getContents();
                    //echo "bb";
                    //echo "Error2";
                    print_r($response);
                    //$CI->session->set_flashdata('error_connection', $response);
                    return $r;
                }

            }else{
                return array();
            }
        }//else{ return "";}
    }//end function
}
/** Testing not completed function 24-06-2022 */
if ( ! function_exists('ApiGetEmployeeList')){
    function ApiGetEmployeeList($company_id="", $service = "30"){
        $CI = get_instance();
        $CI->load->library('session');
        $CI->load->model('model_main', "main");
        //$session=$CI->session->all_userdata();
        $result=array();
        $company_encrypt=$CI->main->select_one_data("tbl_company_api", "encrypt_id", array('company_id'=>$company_id));
        if($company_encrypt == "") $company_encrypt='nokey';
        //echo "<br> Company Encrypt Key: ".$company_encrypt."<br>";
        $arr_service= array(
            '30' => lacmsDomain().'/api/company/worker/list',//latest worker excel, can delete and add new worker, not related to book_id, All worker that active in company
        );
        $client = new Client(['headers' => ['Content-Type' => 'application/x-www-form-urlencoded', 'token' => ApiToken()]]);
        try {
            $response = $client->request(
                'POST',
                $arr_service[$service],
                ['form_params' => ['id' => $company_encrypt]]
            );
            $com_attachment = json_decode($response->getBody());
            //print_r($com_attachment);
            $result= isset($com_attachment->{'results'})? $com_attachment->{'results'}: "";
            //print_r($com_attachment);
            return $result;

        }
        catch (GuzzleHttp\Exception\RequestException $e) {
            //catch (GuzzleHttp\Exception\ConnectException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            //print_r($response);
            echo "<a href='".site_url('worker_vaccine/list_worker_for_vaccine_inspector_v3/'.$company_id)."' > GO BACK</a>";
            //print_r($responseBodyAsString);
            //$CI->session->set_flashdata('error_request', $response);
            $r="no_result_found";
            return $r;


        }
        catch (GuzzleHttp\Exception\ConnectException $e) {//error connection
            $r="Could not resolve the server cURL.";
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            //print_r($response);
            print_r($responseBodyAsString);
            $CI->session->set_flashdata('error_connection', $response);
            return $r;
        }

    }//end function
}
/** 17-11-2021
 *
API for getting the lastest records of worker list
- Url : {base_url}/api/company/worker/list/latest
- Param : {id : (ID of company), lastest_id : (Lastest ID of worker)}
- Type : Post method
 *
 */
if ( ! function_exists('ApiLatestWorkerList')){
    function ApiLatestWorkerList($company_id, $last_worker_id, $service=0){
        $CI = get_instance();
        $CI->load->model('model_main', "main");
        $result=array();
        //$last_worker_id="1841836";
        $company_encrypt=$CI->main->select_one_data("tbl_company_api", "encrypt_id", array('company_id'=>$company_id));
        if($company_encrypt == "") $company_encrypt='nokey';

        echo $company_id."<br> Company Encrypt Key: ".$company_encrypt."<br>";
        $arr_service= array(
            '0'=> lacmsDomain().'/api/company/worker/list/latest',
        );
        //echo $arr_service[$service];
        if($arr_service[$service] !=''){
            $client = new Client(['headers' => ['Content-Type' => 'application/x-www-form-urlencoded', 'token' => ApiToken()]]);
            try {
                $response = $client->request(
                    'POST',
                    $arr_service[$service],

                    ['form_params' => ['id' => $company_encrypt, 'latest_id' => $last_worker_id ] ]
                );
                $com_attachment = json_decode($response->getBody());
                $result= isset($com_attachment->{'results'})? $com_attachment->{'results'}: "";
                //print_r($com_attachment);
                return $result;

            }
            catch (GuzzleHttp\Exception\RequestException $e) {
                //catch (GuzzleHttp\Exception\ConnectException $e) {
                //$response = $e->getResponse();
                //$responseBodyAsString = $response->getBody()->getContents();
                $r="no_result_found.";
                return $r;
            }
            catch (GuzzleHttp\Exception\ConnectException $e) {//error connection
                $r="Could not resolve the server cURL.";
                //$response = $e->getResponse();
                //$responseBodyAsString = $response->getBody()->getContents();
                //print_r($response);
                return $r;
            }
        }else{
            return array();
        }
    }//end function
}
if ( ! function_exists('ApiTotalEmployeeForeigner')){
    function ApiTotalEmployeeForeigner($register_no, $service=0){
        $CI = get_instance();
        $CI->load->model('model_main', "main");
        $result=array();
        $arr_service= array(
            '0'=> 'https://admin.fwcms.mlvt.gov.kh/api/total/wp',
            //stdClass Object ( [total_approved] => 2 [total_approved_female] => 1 [total_pending] => 0 [total_pending_female] => 0 [last_request_date] => stdClass Object ( [date] => 2021-01-11 21:51:40.000000 [timezone_type] => 3 [timezone] => Asia/Bangkok ) )
        );
        //echo $arr_service[$service];
        if($arr_service[$service] !=''){
            $client = new Client(['headers' => ['Content-Type' => 'application/x-www-form-urlencoded', 'token' => ApiToken()]]);
            try {
                $response = $client->request(
                    'POST',
                    //$arr_service[$service],
                    'https://admin.fwcms.mlvt.gov.kh/api/total/wp',
                    ['form_params' => ['register_no' => $register_no]]
                );
                $com_attachment = json_decode($response->getBody());
                $result= isset($com_attachment->{'results'})? $com_attachment->{'results'}: "";
                //print_r($com_attachment);
                return $result;

            }
            catch (GuzzleHttp\Exception\RequestException $e) {
                //catch (GuzzleHttp\Exception\ConnectException $e) {
                //$response = $e->getResponse();
                //$responseBodyAsString = $response->getBody()->getContents();
                $r="Could not connect to the server.";
                return $r;
            }
            catch (GuzzleHttp\Exception\ConnectException $e) {//error connection
                $r="Could not resolve the server cURL.";
                //$response = $e->getResponse();
                //$responseBodyAsString = $response->getBody()->getContents();
                //print_r($response);
                return $r;
            }
        }else{
            return array();
        }
    }//end function
}
/** for cron job only*/
if ( ! function_exists('ApiWorkPermit')){
    function ApiWorkPermit($workpermit_id="", $service=0){
        $CI = get_instance();
        $CI->load->model('model_main', "main");
        //$session=$CI->session->all_userdata();
        $result=array();
        //$company_encrypt=$CI->main->select_one_data("tbl_company_api", "encrypt_id", array('company_id'=>$company_id));
        //if($company_encrypt == "") $company_encrypt='nokey';

        //echo "Key: ".$company_encrypt;
        $arr_service= array(
            '0'=> 'https://admin.fwcms.mlvt.gov.kh/api/detail/wp',//FWP00187093
        );
        //echo $arr_service[$service];
        if($arr_service[$service] !=''){
            $client = new Client(['headers' => ['Content-Type' => 'application/x-www-form-urlencoded', 'token' => ApiToken()]]);
            try {
                $response = $client->request(
                    'POST',
                    $arr_service[$service],
                    ['form_params' => ['workpermit_id' => $workpermit_id]]
                );
                $com_attachment = json_decode($response->getBody());
                $result= isset($com_attachment->{'results'})? $com_attachment->{'results'}: "";
                //print_r($com_attachment);
                //echo "Hello";
                return $result;

            }
            catch (GuzzleHttp\Exception\RequestException $e) {
                //catch (GuzzleHttp\Exception\ConnectException $e) {
                //$response = $e->getResponse();
                //$responseBodyAsString = $response->getBody()->getContents();
                $r="Could not connect to the server.";
                return $r;


            }
            catch (GuzzleHttp\Exception\ConnectException $e) {//error connection
                $r="Could not resolve the server cURL.";
                //$response = $e->getResponse();
                //$responseBodyAsString = $response->getBody()->getContents();
                //echo "bb";
                //print_r($response);
                return $r;
            }

        }else{
            return array();
        }
    }//end function
}

function getArrayCompanyInfoApi($company_id){

    $com_info = ApiCompanyDetail($company_id);
    //dd($com_info);
    //print_r($com_info);
    return array(
        'company_name_khmer' => $com_info->company_name_khmer,
        'company_name_latin' => $com_info->company_name_latin,
        'business_activity' => $com_info->business_activity,
        'business_activity1' => $com_info->main_business_activity,
        'business_activity2' => $com_info->main_business_activity_2 == NULL? "": $com_info->main_business_activity_2,
        'business_activity3' => $com_info->main_business_activity_3 == null? "": $com_info->main_business_activity_3,
        'business_activity4' => $com_info->main_business_activity_3 == null? "": $com_info->main_business_activity_4,
        'branch_id' => $com_info->branch_id== NULL? "": $com_info->branch_id ,
        'total_emp' => $com_info->total_staff == NULL? 0: $com_info->total_staff,
        'type_of_company' => $com_info->type_of_company == NULL ? 0:$com_info->type_of_company,
        'main_product' => $com_info->main_product == NULL ? "": $com_info->main_product,
        'sme_type' => $com_info->sme_type == NULL ? 0:$com_info->sme_type,
        'article_of_company' => $com_info->article_of_company == NULL ? 0:$com_info->article_of_company,
        'company_register_number' => $com_info->company_register_number,
        'registration_date' => $com_info->registration_date == NULL ? "": date2DB($com_info->registration_date),
        'company_tin' => $com_info->company_tin ,
        'business_province' => $com_info->province == NULL ? 0: $com_info->province,
        'business_district' => $com_info->district == NULL ? 0: $com_info->district,
        'business_commune' => $com_info->commune == NULL ? 0: $com_info->commune,
        'business_village' => $com_info->village == NULL ? 0: $com_info->village,
        'business_group' => $com_info->group == NULL ? "": $com_info->group,
        'business_street' => $com_info->street == NULL ? "": $com_info->street,
        'business_house_no' => $com_info->house_no == NULL ? "": $com_info->house_no,

        'owner_khmer_name' => $com_info->owner_khmername == NULL ? "": $com_info->owner_khmername,
        'owner_latin_name' => $com_info->owner_name == NULL ? "": $com_info->owner_name,
        'owner_gender' => $com_info->owner_gender == NULL ? "": $com_info->owner_gender,
        'owner_phone' => $com_info->owner_phone == NULL ? "": $com_info->owner_phone,
        'owner_email' => $com_info->owner_email == NULL ? "": $com_info->owner_email,
        'owner_nationality' => $com_info->owner_nationality == NULL ? "": $com_info->owner_nationality,
        'owner_id_number' => $com_info->owner_id_number == NULL ? "": $com_info->owner_id_number,

        'director_khmer_name' => $com_info->director_khmername == NULL ? "": $com_info->director_khmername,
        'director_latin_name' => $com_info->director_name == NULL ? "": $com_info->director_name,
        'director_gender' => $com_info->director_gender == NULL ? "": $com_info->director_gender,
        'director_phone' => $com_info->director_phone == NULL ? "": $com_info->director_phone,
        'director_email' => $com_info->director_email == NULL ? "": $com_info->director_email,
        'director_nationality' => $com_info->director_nationality == NULL ? 0: $com_info->director_nationality,
        'director_id_number' => $com_info->director_id_number == NULL ? "": $com_info->director_id_number,
        'telephone' => $com_info->telephone == NULL ? "": $com_info->telephone,
        'company_phone_number' => $com_info->company_phone_number == NULL ? "": $com_info->company_phone_number ,
        'company_email' => $com_info->company_email == NULL ? "": $com_info->company_email ,
        'is_child_company' => $com_info->is_child_company == NULL ? 0: $com_info->is_child_company ,
        'is_branch_company' => $com_info->is_branch_company == NULL ? 0: $com_info->is_branch_company
    );

    //return $adata_com_info;
}


/** 30-10-2023
 * Get company list from lacms to save in tbl_company_api
 * if exists update company info
 * elseif not exists insert new record
 * get company list by page cause so many company
 */
function saveApiCompanyList2DB($page= 1, $perPage=2000){
    loadRunUnlimitedTime();
    $company = ApiCompanyList($page, $perPage);//get company list full with page from api
    if(is_array($company) || is_object($company)){
        $total_company=sizeof($company);
        $insert=0;
        $update=0;
        $j=1;
        //print_r($company);
        echo "<br>";
        for($i=0; $i< $total_company; $i++){
            $company_status= $company[$i]->status == NULL ? "": $company[$i]->status;
            if($company_status == "អនុមត្រ"){
                $company_status = 1;
            }
//            $adata2 =  array(
//                'company_id' => $company[$i]->id,
//                'total_emp' => $company[$i]->total_staff == NULL? 0: khmerNumber2English($company[$i]->total_staff)
//            );
            $adata = array(
                'encrypt_id' => $company[$i]->encrypt_id,
                'company_id' => $company[$i]->id,
                'company_code' => $company[$i]->code ,
                'single_id' => $company[$i]->single_id,
                'nssf_number' => $company[$i]->nssf_number,
                'company_name_khmer' => $company[$i]->company_name_khmer,
                'company_name_latin' => $company[$i]->company_name_latin,
                'business_activity' => $company[$i]->business_activity,
                //'business_activity' =>   $company[$i]->business_activity == NULL? 15: $company[$i]->business_activity,
                'business_activity1' => $company[$i]->main_business_activity,
                'business_activity2' => $company[$i]->main_business_activity_2,
                'business_activity3' => $company[$i]->main_business_activity_3,
                'business_activity4' => $company[$i]->main_business_activity_4,
                'branch_id' => $company[$i]->branch_id,
                //'total_emp' => khmerNumber2English($company[$i]->total_staff),
                'total_emp' => $company[$i]->total_staff == NULL? 0: khmerNumber2English($company[$i]->total_staff),
                'total_emp_date' => $company[$i]->created_at,
                //'latest_total_emp' => $company[$i]->total_staff == NULL? 0: $company[$i]->total_staff,
                //'latest_service' => 0,
                'type_of_company' => $company[$i]->type_of_company == NULL ? 0:$company[$i]->type_of_company ,
                'main_product' => $company[$i]->main_product,
                'sme_type' => $company[$i]->sme_type == NULL ? 0:$company[$i]->sme_type ,
                'article_of_company' => $company[$i]->article_of_company == NULL ? 0:$company[$i]->article_of_company ,
                'company_register_number' => $company[$i]->company_register_number ,
                'registration_date' => date2DB($company[$i]->registration_date),
                'company_tin' => $company[$i]->company_tin ,
                'business_province' => $company[$i]->province == NULL ? 0: $company[$i]->province,
                'business_district' => $company[$i]->district == NULL ? 0: $company[$i]->district,
                'business_commune' => $company[$i]->commune == NULL ? 0: $company[$i]->commune,
                'business_village' => $company[$i]->village == NULL ? 0: $company[$i]->village,
                'business_group' => $company[$i]->group,
                'business_street' => $company[$i]->street,
                'business_house_no' => $company[$i]->house_no,

                'is_use_owner_name' => $company[$i]->is_use_owner_name == NULL ? 0: $company[$i]->is_use_owner_name,
                'is_use_company_name' => $company[$i]->is_use_company_name == NULL ? 0: $company[$i]->is_use_company_name,
                'owner_khmer_name' => $company[$i]->owner_khmername,
                'owner_latin_name' => $company[$i]->owner_name,
                'owner_gender' => $company[$i]->owner_gender,
                'owner_phone' => $company[$i]->owner_phone,
                'owner_email' => $company[$i]->owner_email,
                'owner_nationality' => $company[$i]->owner_nationality,
                'owner_id_number' => $company[$i]->owner_id_number,

                'director_khmer_name' => $company[$i]->director_khmername,
                'director_latin_name' => $company[$i]->director_name,
                'director_gender' => $company[$i]->director_gender,
                'director_phone' => $company[$i]->director_phone,
                'director_email' => $company[$i]->director_email,
                'director_nationality' => $company[$i]->director_nationality == NULL ? 0: $company[$i]->director_nationality  ,
                'director_id_number' => $company[$i]->director_id_number,
                'telephone' => $company[$i]->telephone,
                'company_phone_number' => $company[$i]->company_phone_number,
                'company_email' => $company[$i]->company_email,
                'is_child_company' => $company[$i]->is_child_company == NULL ? 0: $company[$i]->is_child_company ,
                'is_branch_company' => $company[$i]->is_branch_company == NULL ? 0: $company[$i]->is_branch_company,
                'hr_name' => $company[$i]->hr_name == NULL ? "": $company[$i]->hr_name ,
                'hr_phone_1' => $company[$i]->hr_phone_1,
                'hr_phone_2' => $company[$i]->hr_phone_2,
                'hr_email_1' => $company[$i]->hr_email_1,
                'hr_email_2' => $company[$i]->hr_email_2,
                'company_status' => $company_status,
                'date_updated' => MyDateTime()
            );
            //dd($adata);
            print_r($adata); echo "<br>";
            $company_id= $company[$i]->id;
            $searchCondition = ["company_id" => $company_id];
            CompanyApi::updateOrCreate($searchCondition, $adata);
            $update++;
            $insert++;
            /** ================Delay time in for loop ====================*/
            if($j == 200){
                $rand= rand(1, 3);
                $time= MyDateTime();
                echo "<br>================Sleep for ".$rand." Seconds".$time." ============="."<br>";
                sleep($rand);
                $j=1;
            }
            $j++;

        }//end for
        $date= MyDateTime();
        $server_time=date("d-m-Y H:i:s");
        $str= "\n =====> Run Date(Local Time):". $date."======== (Server Time:".$server_time.") <=======";
        $str.= "\n Total Company:".$total_company.",";
        $str.= "\n Update Exists Company:".$update.",";
        $str.= "\n Insert New Company:".$insert.",";
        $str.= "\n ============================================================";
        //echo $str;
        /** ===========Save Result to File ================*/
        $file = storage_path('cronjob/save_api_company_list_2db.txt');
        //$file = 'cronjob/save_api_company_list_2db.txt';
        // Open the file to get existing content
        $current = file_get_contents($file);
        // Append a new person to the file
        $current .= $str;//"John Smith\n";
        // Write the contents back to the file
        file_put_contents($file, $current);
        /** ===============================================*/
    }
    else{
        $r= "Can not get data";
    }
    //return $r;
}
/**
 * Date: 01-11-2023
 * </p>
 * Get one company data by company_id with api service=0 from lacms to update in tbl_company_api
 * </p>
 * To Update latest company data from lacms in tbl_company_api
 */
function refreshCompanyInfoFromLacms($company_id= 0){
    loadRunUnlimitedTime();
    $company = ApiData($company_id, "0");//return as object
    if(is_array($company) || is_object($company)){
        //print_r($company);echo "<br>";
        $company_status= $company->status == NULL ? "": $company->status;
        if($company_status == "អនុមត្រ"){
            $company_status = 1;
        }
        $adata = [
            //'encrypt_id' => $company->encrypt_id ,
            //'company_id' => $company->id,
            //'company_code' => $company->code ,
            'single_id' => $company->single_id,
            'nssf_number' => $company->nssf_number,
            'company_name_khmer' => $company->company_name_khmer,
            'company_name_latin' => $company->company_name_latin,
            'business_activity' => $company->business_activity ,
            //'business_activity' =>   $company->business_activity == NULL? 15: $company->business_activity,
            'business_activity1' => $company->main_business_activity ,
            'business_activity2' => $company->main_business_activity_2,
            'business_activity3' => $company->main_business_activity_3,
            'business_activity4' => $company->main_business_activity_4,
            'branch_id' => $company->branch_id,
            'total_emp' => $company->total_staff == NULL? 0: $company->total_staff,
            'total_emp_date' => $company->created_at,
            //'latest_total_emp' => $company->total_staff == NULL? 0: $company->total_staff,
            //'latest_service' => 0,
            'type_of_company' => $company->type_of_company == NULL ? 0: $company->type_of_company ,
            'main_product' => $company->main_product,
            'sme_type' => $company->sme_type == NULL ? 0:$company->sme_type ,
            'article_of_company' => $company->article_of_company == NULL ? 0: $company->article_of_company ,
            'company_register_number' => $company->company_register_number ,
            'registration_date' => date2DB($company->registration_date),
            'company_tin' => $company->company_tin ,
            'business_province' => $company->province == NULL ? 0: $company->province ,
            'business_district' => $company->district == NULL ? 0: $company->district ,
            'business_commune' => $company->commune == NULL ? 0: $company->commune ,
            'business_village' => $company->village == NULL ? 0: $company->village ,
            'business_group' => $company->group,
            'business_street' => $company->street,
            'business_house_no' => $company->house_no,

            //'is_use_owner_name' => $company->is_use_owner_name == NULL ? 0: $company->is_use_owner_name,
            //'is_use_company_name' => $company->is_use_company_name == NULL ? 0: $company->is_use_company_name,
            'owner_khmer_name' => $company->owner_khmername,
            'owner_latin_name' => $company->owner_name,
            'owner_gender' => $company->owner_gender,
            'owner_phone' => $company->owner_phone,
            'owner_email' => $company->owner_email,
            'owner_nationality' => $company->owner_nationality,
            'owner_id_number' => $company->owner_id_number,

            'director_khmer_name' => $company->director_khmername,
            'director_latin_name' => $company->director_name,
            'director_gender' => $company->director_gender,
            'director_phone' => $company->director_phone,
            'director_email' => $company->director_email,
            'director_nationality' => $company->director_nationality == NULL ? 0: $company->director_nationality  ,
            'director_id_number' => $company->director_id_number,
            'telephone' => $company->telephone,
            'company_phone_number' => $company->company_phone_number,
            'company_email' => $company->company_email,
            'is_child_company' => $company->is_child_company == NULL ? 0: $company->is_child_company ,
            'is_branch_company' => $company->is_branch_company == NULL ? 0: $company->is_branch_company,
            'hr_name' => $company->hr_name ,

            'hr_phone_1' => $company->hr_phone_1,
            'hr_phone_2' => $company->hr_phone_2,
            'hr_email_1' => $company->hr_email_1,
            'hr_email_2' => $company->hr_email_2,

            'company_status' => $company_status,
            'date_updated' => MyDateTime()
        ];
        print_r($adata);
//        $con=array("company_id" => $company_id);
//        $r= $CI->main->update($tbl, $adata, $con);
        $r = CompanyApi::where("company_id", $company_id)->update($adata);
        if($r){
            echo "<br><br><label style='font-size:30px;color:blue'>Company Info was updated from LACMS.</label>";
        }
    }
    else{
        $r= "Can not get data";
    }
    //return $r;
}


/** Login Success can call this function */
function getSoklayApi(){
    $client = new Client(['headers' => [
        //'Content-Type' => 'application/x-www-form-urlencoded',
        'Authorization' => 'Bearer 10|dsELmZvbEWYnHXnkPRG9EQK5XwHd40nxVm32Xw14f10748d6']]);
    try {
        $response = $client->request(
            'GET',
            'https://test4-sicms.kservone.com/api/user',
            //['form_params' => ['id' => $company_encrypt]]
        );
        //print_r( json_decode($response->getBody()) );
        return json_decode($response->getBody());

    } catch (GuzzleHttp\Exception\ClientException $e) {
        $response = $e->getResponse();
        $responseBodyAsString = $response->getBody()->getContents();
        return 0;
    }
}

function loginCompany(Request $request)
{
    $username = $request->username;
    $password = $request->password;
    $client_secret = $request->client_secret;
    //dd($request->all());
    //$client_secret = "xyBo8dET07Wzn4FvDDVkZYFOnAyKuGics4ZV1vwv";
    $scope = "xyBo8dET07Wzn4FvDDVkZYFOnAyKuGics4ZV1vwv";
    //$username = "hrwegc@wegroupltd.com";
    //$password = "wegcssezc22-8";
    $client = new Client(['headers' => [
        //'Content-Type' => 'application/x-www-form-urlencoded',
        //'Authorization' => 'Bearer 10|dsELmZvbEWYnHXnkPRG9EQK5XwHd40nxVm32Xw14f10748d6'
    ]]);
    try { // success
        $response = $client->request(
            'POST',
            'https://lacms.mlvt.gov.kh/oauth/token',
            ['form_params' => [
                'grant_type' => "password",
                'client_id' => 11,
                'client_secret' => $client_secret,
                'username'  => $username,
                'password'  => $password,
                'scope' =>  $scope,
            ]
            ]
        );

        $response = json_decode($response->getBody()); //1.get access_token
        $access_token = $response->access_token;
        $encrypt_id = getCompanyEncrypt($access_token);//2.get company encrypt_id for login
        $company_id = getCompanyIDLacms($encrypt_id); //3.get company_id using encrypt_id
        //$company_id = 12389;
        $company = CompanyApi::where("company_id", $company_id)->first();
        $user = User::where("company_id", $company_id)->first();
        //$password = $request->password ?? "12334455";
        if($user == null){ // no user in table user
            $user_name = "company_".$company_id."_update";
            $email = $username;
            $fullname = "company_".$company_id;
            $user = User::create([
                "username" => $user_name, //$validatedData['username'],
                'password' => Hash::make($password), //Hash::make($validatedData['password']),
                'k_fullname' => $fullname, //$validatedData['fullname'],
                'email' => $email, //$validatedData['email'],
                "company_id" => $company_id,
                "k_role_id" => 0,
                "k_team" => 0,
                "k_province" => 0,
                "k_parents" => 1,
                "banned" => 0,
            ]);
        }
        else{ // exists user in table user, so update
            $user->password = Hash::make($password);
            $user->save();
        }
        $token = $user->createToken('auth_token')->plainTextToken;
        $data = [
            "company" => $company,
            "user" => $user
        ];
        //dd($data);
        return response()->json(
            [
                'status' => 200,
                'token' => $token,
                'token_type' => 'Bearer',
                'message'=> "success",
                'data'=> $data
            ], 200
        );

    } catch (GuzzleHttp\Exception\ClientException $e) {
        //$response = $e->getResponse();
        return response()->json(
            [
                'status' => 400,
                'message'=> "fail to login",
                'data' => null
            ], 400
        );
    }
}

function getCompanyEncrypt($access_token){
    $scope = "xyBo8dET07Wzn4FvDDVkZYFOnAyKuGics4ZV1vwv";
    $client = new Client(['headers' => [
        'Content-Type' => 'application/application/json',
        'Authorization' => 'Bearer '.$access_token
    ]]);
    try {
        $response = $client->request(
            'POST',
            'https://lacms.mlvt.gov.kh/api/sicms/company/code',
            ['form_params' =>
                [
                    'token_scope' =>  $scope,
                ]
            ]
        );

        $response = json_decode($response->getBody()); //success
        //dd($response);
        //$encrypt_id = $response->company_id;
        return $response->company_id;

    } catch (GuzzleHttp\Exception\ClientException $e) {
        $response = $e->getResponse();
        //$responseBodyAsString = $response->getBody()->getContents();
        //return ['status' => 400, 'message'=> "fail to login", 'data' => null];
        return "fail";
    }
}
function getCompanyIDLacms($encrypt_id){

    $client = new Client(['headers' => ['Content-Type' => 'application/x-www-form-urlencoded', 'token' => ApiToken()]]);
    try {
        $response = $client->request(
            'POST',
            'https://lacms.mlvt.gov.kh/api/company/detail',//company detail
            ['form_params' => ['id' => $encrypt_id]]
        );
        $company = json_decode($response->getBody(), true);
        $result= isset($company['results'])? $company['results'] : "";
        $company_id = $result['id'];
        return $company_id;
    }
    catch (Exception $e){
        return "";
    }
}


