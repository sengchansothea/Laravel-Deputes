<?php

namespace App\Http\Controllers;

use App\Models\CaseCompany;
use App\Models\AllowCompanySelfInsp;
use App\Models\DomainCommune;
use App\Models\DomainDistrict;
use App\Models\DomainName;
use App\Models\DomainProvince;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DomainController extends Controller
{

    /** Update all domain_id field in tbl_case_company by related case_id*/

    function updateDomainIDByCaseID(){
        $caseComs = CaseCompany::select('id', 'case_id')->get();
        foreach ($caseComs as $caseCom){
            $domainID = getCaseDomainControl($caseCom->case_id);
            CaseCompany::where('case_id', $caseCom->case_id)->update(['domain_id' => $domainID]);
        }
    }

    function editDisComDomain($domainID, $proID){
//        echo "DomainID: ". $domainID. "PrvinceID: ". $proID;
        $data['domain'] = DomainName::where('id',$domainID)->get();
        $data['proID'] = $proID;
        $data['pagetitle'] = "តារាងបែងចែកដែនសមត្ថកិច្ចក្នុងការិយាល័យទី".Num2Unicode($domainID);
        $view = "case.officer.officer_domain_list";
//        $view = "case.officer.domain_dis_com_list";

        if(request("json_opt") == 1){ //if request from app
            return response()->json(['status'=>200,'result'=> $data]);
        }
        return view($view, [ "adata" => $data ]);
    }

//    {domainID}/{proID}/{disID}

    function addCommuneToDomain($domainID, $proID, $disID){
//        $disPlay = "DomainID: ".$domainID. " ProID: ". $proID." DisID: ".$disID;

//        dd(request('k_commune_'.$disID));
        $comIDs = request('comname');

        if(empty($comIDs)){
            return back();
        }

        DB::beginTransaction();
        try{
            foreach ($comIDs as $comID){
                $domainComData = [
                    'domain_id' =>  $domainID,
                    'province_id' =>  $proID,
                    'district_id' => $disID,
                    'commune_id' => $comID
                ];

                // Insert Province Into Domain
                DomainCommune::create($domainComData);
            }

            DB::commit();

        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return back()->with("message", sweetalert()->addWarning("បរាជ័យ"));
        }
        return redirect()->route('domain.index')->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
    }
    function addCommuneToDomainForm($domainID, $proID, $disID){
        $display = "DomainID: ". $domainID. " ProID: ".$proID." DisID: ".$disID;
        $data['domainID'] = $domainID;
        $data['proName'] = DB::table('camdx_province')->where('id', $proID)->first()->pro_khname;
        $data['disName'] = DB::table('camdx_district')->where('dis_id', $disID)->first()->dis_khname;
        $data['proID'] = $proID ;
        $data['disID'] = $disID ;
        $data['arrComs'] = arrayCommuneExcludedByDisID($disID , getAllComIDInDomain($proID, $disID));

        $data['pagetitle'] = "បន្ថែមឃុំ/សង្កាត់";
        $view = "case.officer.add_commune_to_domain";

        if(request("json_opt") == 1){ //if request from app
            return response()->json(['status'=>200,'result'=> $data]);
        }
        return view($view, [ "adata" => $data ]);
    }

    function addDistrictToDomain($domainID, $proID){

//        dd(request()->all());
        $disIDs = request('distname');


        if(empty($disIDs)){
            return back();
        }
//        dd($disIDs);



//        dd($domainDisData);
        DB::beginTransaction();
        try{

            foreach ($disIDs as $disID){
                $domainDisData = [
                    'domain_id' =>  $domainID,
                    'province_id' =>  $proID,
                    'district_id' => $disID
                ];

                // Insert Province Into Domain
                DomainDistrict::create($domainDisData);

            }
            DB::commit();

        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return back()->with("message", sweetalert()->addWarning("បរាជ័យ"));
        }
        return redirect()->route('domain.index')->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
    }

    function addDistrictToDomainForm($domainID, $proID){
        $display = "DomainID: ". $domainID. " ProID: ".$proID;

        $data['domainID'] = $domainID;
        $data['proName'] = DB::table('camdx_province')->where('id', $proID)->first()->pro_khname;

        $data['arrDists'] = arrayDistrictsExcludedByDisID($proID, getAllDisIDInDomain($proID));
        $data['proID'] = $proID ;

        $data['pagetitle'] = "បន្ថែមស្រុក/ខណ្ឌ/ក្រុង";
        $view = "case.officer.add_district_to_domain";

        if(request("json_opt") == 1){ //if request from app
            return response()->json(['status'=>200,'result'=> $data]);
        }
        return view($view, [ "adata" => $data ]);
    }

    function addProvinceToDomain($domainID){
        $proID = !empty(request('k_province_'.$domainID)) ? request('k_province_'.$domainID) : 0;
//        dd(request()->all());
        if($proID == 0){
            return back();
        }

        $domainProData = [
            'domain_id' =>  $domainID,
            'province_id' =>  $proID,
        ];

//        dd($domainProData);



        DB::beginTransaction();
        try{

            //Search Existing Record Before Insertion
            $searchRecord = DB::table('tbl_domain_province')->where($domainProData)->first();
            if(empty($searchRecord)){ //If No Record Founded, Let's Insert the New One
                // Insert Province Into Domain
                $domainProStatus = DomainProvince::create($domainProData);
            }
            else{
                return back()->with("message", sweetalert()->addInfo("ខេត្ត/រាជធានី មានរួចហើយ!"));
            }

            DB::commit();

        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return back()->with("message", sweetalert()->addWarning("បរាជ័យ"));
        }
        return back()->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
    }

    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $domainID = request("domain", 0); // Set Default DomainID == 0
        $data['domain'] = DomainName::with([
            'domainProvince',
            'domainProvince.province',
        ])->when($domainID > 0, fn($q) => $q->where('id', $domainID))
            ->get();

        // 👇 Load once in controller, not in Blade
        $data['excludedProIDs'] = getAllProIDInDomain();
        $data['pagetitle'] = "តារាងបែងចែកដែនសមត្ថកិច្ចក្នុងនាយកដ្ឋាន";

//        $view = "case.officer.officer_domain_list";
        $view = "case.officer.domain_dis_com_list";

        if(request("json_opt") == 1){ //if request from app
            return response()->json(['status'=>200,'result'=> $data]);
        }
        return view($view, [ "adata" => $data ]);
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
        dd("Edit: ".$id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }


    /**
     * Remove commune from specific domain
     */
    function deleteCommuneFromDomain($domainID, $proID, $disID, $comID){
        DB::beginTransaction();
        try{
            // Fetch the record by ID
            $record = DomainCommune::where('domain_id', $domainID)
                ->where('province_id', $proID)
                ->where('district_id', $disID)
                ->where('commune_id', $comID)
                ->first();

            if (!$record) {
                throw new \Exception('Record not found');
            }

            // Let's remove Commune from Domain
            $record->delete();

            DB::commit();

            return back()->with("message", sweetalert()->addSuccess("ជោគជ័យ"));

        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return back()->with("message", sweetalert()->addWarning("បរាជ័យ"));
        }
    }

    /**
     * Remove destrict from specific domain
     */
    function deleteDestrictFromDomain($domainID, $proID, $disID){
        DB::beginTransaction();
        try{
            // Fetch the record by ID
            $record = DomainDistrict::where('domain_id', $domainID)
                ->where('province_id', $proID)
                ->where('district_id', $disID)
                ->first();

            if (!$record) {
                throw new \Exception('Record not found');
            }

            // Check whether District got Sub(Commune) in DomainCommune
            if(count($record->domainCommune) > 0){
                return back()->with("message", sweetalert()->addInfo("សូមលុប សង្កាត់/ឃុំ ជាមុនសិន!"));
            }

            // Let's remove District from Domain
            $record->delete();

            DB::commit();

            return back()->with("message", sweetalert()->addSuccess("ជោគជ័យ"));

        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return back()->with("message", sweetalert()->addWarning("បរាជ័យ"));
        }

    }

    /**
     * Remove province from specific domain
     */
    function deleteProvinceFromDomain($domainID, $proID){
        DB::beginTransaction();
        try{
            // Fetch the record by DomainID and ProID
            $record = DomainProvince::where('domain_id', $domainID)->where('province_id', $proID)->first();

            if (!$record) {
                throw new \Exception('Record not found');
            }

            // Check whether Province got Sub(District) in DomainDistrct
            if (count($record->domainDistrict) > 0) {
                return back()->with("message", sweetalert()->addInfo("សូមលុប ខណ្ឌ/ក្រុង/ស្រុក ជាមុនសិន!"));
            }


            // Let's remove province from domain
            $record->delete();

            DB::commit();

            return back()->with("message", sweetalert()->addSuccess("ជោគជ័យ"));

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
        //

    }
}
