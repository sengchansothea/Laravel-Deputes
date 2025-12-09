<?php

namespace App\Http\Controllers;

use App\Http\Requests\DisputantRequest;
use App\Models\Disputant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DisputantController extends Controller
{

    private int $perPage = 10;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data['opt_search'] = request('opt_search')? request('opt_search'): "quick";
        $data['disputants'] = $this->getOrSearchEloquent(1);
        $data['pagetitle'] = "បញ្ជីគូវិវាទ";
        $data['total'] = $data['disputants']->total();
        $view = "case.disputant.disputant_list";

        if(request("json_opt") == 1){ //if request from app
            return response()->json(['status'=>200,'result'=> $data]);
        }
        return view($view, [ "adata" => $data ]);
    }

    function getOrSearchEloquent()
    {
        $search = request('search');
        $chkUserIdentity = chkUserIdentity();

        $domainMapping = [
            31 => 1, // ការិយាល័យទី១
            32 => 2, // ការិយាល័យទី២
            33 => 3, // ការិយាល័យទី៣
            34 => 4, // ការិយាល័យទី៤
        ];

        if ($chkUserIdentity <= 3) {
            $query = Disputant::with([
                'case',
                'disNationality',
                'pobProvince',
                'pobDistrict',
                'pobCommune'

            ]);
        } elseif (array_key_exists($chkUserIdentity, $domainMapping)) {
            $domainId = $domainMapping[$chkUserIdentity];
            $query = Disputant::whereHas('case.casesCompany', function ($q) use ($domainId) {
                $q->where('domain_id', $domainId);
            })->distinct();
        } elseif ($chkUserIdentity == 4) {
            // TODO: Handle national-level (មន្ទីរការងារ) logic here
            $query = Disputant::query(); // or whatever logic is needed
        }
        if ($search) {
            $query->where(
                DB::raw("CONCAT('x', id, 'x', name, '', COALESCE(name_latin, 'NULL'), COALESCE(id_number, 'NULL'))"),
                'LIKE',
                "%{$search}%"
            );
        }
        $disputants = $query->orderBy('name', 'ASC')
            ->paginate($this->perPage)
            ->appends([
                'json_opt' => request('json_opt'),
                'search' => $search,
            ]);

        return $disputants;
    }


    function getOrSearchEloquentX()
    {
        $disputants = Disputant::query();
        if (request("search")) {
            $search = request("search");
            $disputants = $disputants->where(DB::raw("CONCAT('x',id,'x', name,'', COALESCE(name_latin, 'NULL'), id_number )"), "LIKE", "%".$search."%");
        }
        $disputants = $disputants->orderBy("id", "ASC");
        $disputants = $disputants->paginate($this->perPage);
        $arraySearchParam = array(
            "json_opt" => request('json_opt'),
            "search" => request('search'),

        );
        $disputants->appends($arraySearchParam);
        return $disputants;

    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        dd("Create");
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
        $data['disputant'] = Disputant::where('id', $id)->first();
//        dd($data['disputant']->case);
        $data['pagetitle'] = "កំណត់ត្រាគូវិវាទ";
        $view = "case.disputant.dispute_history_list";

        if(request("json_opt") == 1){ //if request from app
            return response()->json(['status'=>200,'result'=> $data]);
        }
        return view($view, [ "adata" => $data ]);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['disputant'] = Disputant::where('id', $id)->first();
//        dd($data['disputant']);
        $data['pagetitle'] = "កែប្រែពត៌មានគូវិវាទ";
        $view = "case.disputant.disputant_edit";

        if(request("json_opt") == 1){ //if request from app
            return response()->json(['status'=>200,'result'=> $data]);
        }
        return view($view, [ "adata" => $data ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(DisputantRequest $request, string $id)
    {
//        dd($request->disputant_nationality);
        $arrDisputantCond = ['id' => $id ];
        $disputantData = [
            'name' =>  $request->disputant_name,
            'gender' => $request->disputant_gender,
            'dob' => date2DB($request->disputant_dob),
            'nationality' => $request->disputant_nationality,
            'id_number' => $request->disputant_id_number,
            'pob_commune_id' => $request->disputant_nationality == 33 ? $request->disputant_pob_commune : 0,
            'pob_district_id' => $request->disputant_nationality == 33 ? $request->disputant_pob_district : 0,
            'pob_province_id' => $request->disputant_nationality == 33 ? $request->disputant_pob_province : 0,
            'pob_address_abroad' => $request->disputant_nationality != 33 ? $request->disputant_address_abroad : "",
            'user_updated' => Auth::user()->id,
            'date_updated' => myDate(),

        ];
//        dd($arrDisputantCond);

        DB::beginTransaction();
        try{
            // Update Disputant All Data
            $disputantStatus = Disputant::where($arrDisputantCond)->update($disputantData);
            DB::commit();
            return back()->with("message", sweetalert()->addSuccess("ទិន្នន័យគូវិវាទ បានកែប្រែដោយជោគជ័យ"));
//            if($disputantStatus > 0){
//
//            }else{
//                return back()->with("message", sweetalert()->addWarning("មិនមានអ្វីកែប្រែឡើយ!"));
//            }

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
