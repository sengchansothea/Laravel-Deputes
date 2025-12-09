<?php

namespace App\Http\Controllers;

use App\Http\Requests\OfficerRequest;
use App\Models\DomainName;
use App\Models\Officer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OfficerController extends Controller
{
    private int $perPage = 20;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        if(!allowUserAccess()){
            abort(403, 'You do not have permission to access this page.');
        }
        $data['opt_search'] = request('opt_search')? request('opt_search'): "quick";
        $data['officers'] = $this->getOrSearchEloquent();
        $data['pagetitle'] = "បញ្ជីអ្នកផ្សះផ្សារ";
        $data['k_category'] = auth()->user()->k_category ?? 0;
        $data['total'] = $data['officers']->total();
        $view = "case.officer.officer_list";

        if(request("json_opt") == 1){ //if request from app
            return response()->json(['status'=>200,'result'=> $data]);
        }
        return view($view, [ "adata" => $data ]);
    }
    public function getOrSearchEloquent1()
    {
        $officers = Officer::with([
            'officerRole',
            'caseOfficerSolvers',
            'caseOfficerNoters',
            'caseOfficerSolvers.case.log6Latest.detail6',
            'caseOfficerNoters.case.log6Latest.detail6'
        ])->distinct();

        $chkUserIdentity = chkUserIdentity();

        // Mapping user identity to role IDs
        $roleMap = [
            31 => [3, 7, 11], // ការិយាល័យទី១
            32 => [4, 8, 12], // ការិយាល័យទី២
            33 => [5, 9, 13], // ការិយាល័យទី៣
            34 => [6, 10, 14], // ការិយាល័យទី៤
        ];
//        dd($chkUserIdentity);

        // Apply role filter if user is domain-level
        if (array_key_exists($chkUserIdentity, $roleMap)) {
//            $officers->whereHas('OfficerRole', fn($q) => $q->whereIn('id', $roleMap[$chkUserIdentity]));
            $officers->whereIn('officer_role_id', $roleMap[$chkUserIdentity]);
        }

        // Apply search filter
        if ($search = request('search')) {
            $officers->where(DB::raw("
            CONCAT('x', id, 'x', officer_name_khmer, '', COALESCE(officer_name_latin, ''), officer_id2)
        "), 'LIKE', "%{$search}%");
        }

//        if(request('officer_role_id')){
//            $officerRoleID = request('officer_role_id');
//            if($officerRoleID > 0){
//                if($officerRoleID == 1){ //រដ្ឋលេខាធិការ
//                    $officers = $officers->where('officer_role_id', 15);
//                }elseif($officerRoleID == 2){ //អនុរដ្ឋលេខាធិការ
//                    $officers = $officers->where('officer_role_id', 16);
//                }elseif($officerRoleID == 3){ //អគ្គនាយកនៃអគ្គនាយកដ្ឋានការងារ'
//                    $officers = $officers->where('officer_role_id', 17);
//                }elseif($officerRoleID == 4){ //អគ្គនាយករងនៃអគ្គនាយកដ្ឋានការងារ'
//                    $officers = $officers->where('officer_role_id', 18);
//                }elseif($officerRoleID == 5){ //ប្រធាននាយកដ្ឋានវិវាទការងារ'
//                    $officers = $officers->where('officer_role_id', 1);
//                }elseif($officerRoleID == 6){ //អនុប្រធាននាយកដ្ឋានវិវាទការងារ'
//                    $officers = $officers->where('officer_role_id', 2);
//                }elseif($officerRoleID == 7){ //ប្រធានការិយាល័យវិវាទការងារ'
//                    $officers = $officers->whereIn('officer_role_id', [3, 4, 5, 6]);
//                }elseif($officerRoleID == 8){ //អនុប្រធានការិយាល័យវិវាទការងារ'
//                    $officers = $officers->whereIn('officer_role_id', [7, 8, 9, 10]);
//                }elseif($officerRoleID == 9){ //មន្ត្រីការិយាល័យវិវាទការងារ'
//                    $officers = $officers->whereIn('officer_role_id', [11, 12, 13, 14]);
//                }
//
//            }
//        }

        if($officerRoleID = request('officer_role_id')){
            $roleFilterMap = [
                1 => [15],                      // រដ្ឋលេខាធិការ
                2 => [16],                      // អនុរដ្ឋលេខាធិការ
                3 => [17],                      // អគ្គនាយកនៃអគ្គនាយកដ្ឋានការងារ
                4 => [18],                      // អគ្គនាយករងនៃអគ្គនាយកដ្ឋានការងារ
                5 => [1],                       // ប្រធាននាយកដ្ឋានវិវាទការងារ
                6 => [2],                       // អនុប្រធាននាយកដ្ឋានវិវាទការងារ
                7 => [3, 4, 5, 6],              // ប្រធានការិយាល័យ
                8 => [7, 8, 9, 10],             // អនុប្រធានការិយាល័យ
                9 => [11, 12, 13, 14],          // មន្ត្រីការិយាល័យ
            ];
            if (isset($roleFilterMap[$officerRoleID])) {
                $officers->whereIn('officer_role_id', $roleFilterMap[$officerRoleID]);
            }
        }

        // Final ordering and pagination
        $officers = $officers->orderBy('officer_role_id')->paginate($this->perPage);

        $officers->appends([
            'json_opt' => request('json_opt'),
            'search'   => request('search'),
            'officer_role_id' => request('officer_role_id'),
        ]);

        return $officers;
    }
        public function getOrSearchEloquent()
    {
        // Step 0️⃣ — Base query with filters but no relationships yet
        $baseQuery = Officer::query();

        $chkUserIdentity = chkUserIdentity();

        // 🔹 Mapping user identity to role IDs
        $roleMap = [
            31 => [3, 7, 11], // ការិយាល័យទី១
            32 => [4, 8, 12], // ការិយាល័យទី២
            33 => [5, 9, 13], // ការិយាល័យទី៣
            34 => [6, 10, 14], // ការិយាល័យទី៤
        ];

        // 🔹 Apply domain-level filter
        if (array_key_exists($chkUserIdentity, $roleMap)) {
            $baseQuery->whereIn('officer_role_id', $roleMap[$chkUserIdentity]);
        }

//        if ($search = request('search')) {
//            $baseQuery->where(DB::raw("
//            CONCAT('x', id, 'x', officer_name_khmer, '', COALESCE(officer_name_latin, ''), officer_id2)
//        "), 'LIKE', "%{$search}%");
//        }
        // 🔹 Apply case-insensitive search filter
        if ($search = request('search')) {
            $search = strtolower($search);
            $baseQuery->where(function ($q) use ($search) {
                  $q->whereRaw('LOWER(officer_name_khmer) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(officer_name_latin) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(officer_id2) LIKE ?', ["%{$search}%"]);
            });
        }

        // 🔹 Apply officer role filter
        if ($officerRoleID = request('officer_role_id')) {
            $roleFilterMap = [
                1 => [15],
                2 => [16],
                3 => [17],
                4 => [18],
                5 => [1],
                6 => [2],
                7 => [3, 4, 5, 6],
                8 => [7, 8, 9, 10],
                9 => [11, 12, 13, 14],
            ];

            if (isset($roleFilterMap[$officerRoleID])) {
                $baseQuery->whereIn('officer_role_id', $roleFilterMap[$officerRoleID]);
            }
        }

        // Step 1️⃣ — Get distinct officer names with representative ID
        $distinctNames = $baseQuery
            ->select('officer_name_khmer', DB::raw('MIN(id) as id'))
            ->groupBy('officer_name_khmer')
            ->orderBy('officer_name_khmer')
            ->paginate($this->perPage);

        // Step 2️⃣ — Load full officer records for those IDs
        $officers = Officer::with([
            'officerRole',
            'caseOfficerSolvers',
            'caseOfficerNoters',
            'caseOfficerSolvers.case.log6Latest.detail6',
            'caseOfficerNoters.case.log6Latest.detail6'
        ])
            ->whereIn('id', $distinctNames->pluck('id'))
            ->orderBy('officer_name_khmer')
            ->get();

        // Step 3️⃣ — Replace pagination collection
        $distinctNames->setCollection($officers);

        // Step 4️⃣ — Append filters to pagination URLs
        $distinctNames->appends([
            'json_opt' => request('json_opt'),
            'search'   => request('search'),
            'officer_role_id' => request('officer_role_id'),
        ]);

        // Step 5️⃣ — Return paginated result
        return $distinctNames;
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(!allowUserAccess()){
            abort(403, 'You do not have permission to access this page.');
        }

        $data['pagetitle'] = "បញ្ចូលពត៌មានមន្ត្រីផ្សះផ្សាវិវាទ";
        $view = "case.officer.officer_create";
        if(request("json_opt") == 1){ //if request from app
            return response()->json(['status'=>200,'result'=> $data]);
        }
        return view($view, [ "adata" => $data ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OfficerRequest $request)
    {
//        dd($request->all());
        if(!allowUserAccess()){
            abort(403, 'You do not have permission to access this page.');
        }

        DB::beginTransaction();
        try{
            $officerData = [
                'officer_name_khmer' =>  $request->officer_name_khmer,
                'officer_name_latin' =>  $request->officer_name_latin,
                'officer_id2' =>  $request->officer_id2,
                'phone_number' => $request->phone_number,
                'sex' =>  $request->sex,
                'province_id' => 12,
                'officer_role_id' => $request->officer_role,
//            'officer_role' =>  $request->officer_role,
                'department_id' => 6,
                'officer_department' => "នាយកដ្ឋានវិវាទការងារ",
                'user_created' => Auth::user()->id,
                'user_updated' => Auth::user()->id,
                'date_created' => myDate(),
                'date_updated' => myDate(),

            ];

            // Update Disputant All Data
            $officerStatus = Officer::create($officerData);

            DB::commit();

            if($officerStatus->id > 0){
                return redirect()->route('officer.index')->with("message", sweetalert()->addSuccess("ពត៌មានមន្ត្រី ត្រូវបានបញ្ចូលជោគជ័យ"));
            }else{
                return back()->with("message", sweetalert()->addWarning("មិនមានអ្វីកែប្រែឡើយ!"));
            }

        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return back()->with("message", sweetalert()->addWarning("បរាជ័យ"));
        }


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data['officer'] = Officer::with([

        ])->where('id', $id)->first();
        $data['pagetitle'] = "កំណត់ត្រាផ្សះផ្សារវិវាទ";
        $view = "case.officer.officer_history_list";

        if(request("json_opt") == 1){ //if request from app
            return response()->json(['status'=>200,'result'=> $data]);
        }
        return view($view, [ "adata" => $data ]);
    }

    public function showOfficers(int $id, int $officerType = 0)
    {
        $casesMap = [
            0 => 'casesOfficers',
            1 => 'caseOfficerSolvers',
            2 => 'caseOfficerNoters',
        ];
        $relation = $casesMap[$officerType] ?? 'casesOfficers';
        $officer = Officer::with([
            $relation,
            $relation . '.case' => function ($query) {
                $query->with([
                    'caseClosedStep',
                    'invitationAll',
                    'invitationDisputant',
                    'invitationCompany',
                    'log34Detail',
                    'log5Detail',
                    'invitationForConcilation',
                    'latestLog6Detail.status',
                    'company',
                    'caseDisputant.disputant',
                ]);
            },
        ])
            ->select('id', 'officer_name_khmer', 'officer_name_latin')
            ->findOrFail($id);

        $cases = $officer->{$relation};

        $data = [
            'officer' => $officer,
            'officerType' => $officerType,
            'cases' => $cases,
            'pagetitle' => 'កំណត់ត្រាអន្តរាគមន៍ក្នុងវិវាទ',
        ];

        return request("json_opt") == 1
            ? response()->json(['status' => 200, 'result' => $data])
            : view("case.officer.officer_history_list", ["adata" => $data]);
    }

    public function showOfficersX (int $id, int $officerType = 0){ //$officerType: 0 For Officers, 1 For Solver and 2 For Noter
        $officer = Officer::with([
        ])
            ->where('id', $id)
            ->select('id','officer_name_khmer', 'officer_name_latin')
            ->first();


        // Map officer types to their respective relationships
        $casesMap = [
            0 => 'casesOfficers',
            1 => 'caseOfficerSolvers',
            2 => 'caseOfficerNoters',
        ];

        // Get cases based on officer type
        $cases = $officer->{$casesMap[$officerType] ?? 'casesOfficers'};

        // Prepare data
        $data = [
            'officer' => $officer,
            'officerType' => $officerType,
            'cases' => $cases,
            'pagetitle' => 'កំណត់ត្រាអន្តរាគមន៍ក្នុងវិវាទ',
        ];
        $view = "case.officer.officer_history_list";

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
        //
        if(!allowUserAccess()){
            abort(403, 'You do not have permission to access this page.');
        }
        $data['officer'] = Officer::where('id', $id)->first();
//        dd($data['officer']);
        $data['pagetitle'] = "កែប្រែពត៌មានមន្ត្រីផ្សះផ្សាវិវាទ";
        $view = "case.officer.officer_edit";

        if(request("json_opt") == 1){ //if request from app
            return response()->json(['status'=>200,'result'=> $data]);
        }
        return view($view, [ "adata" => $data ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OfficerRequest $request, string $id)
    {
        //
        if(!allowUserAccess()){
            abort(403, 'You do not have permission to access this page.');
        }

        DB::beginTransaction();
        try{

            $arrOfficerCond = ['id' => $id ];
            $officerData = [
                'officer_name_khmer' =>  $request->officer_name_khmer,
                'officer_name_latin' =>  $request->officer_name_latin,
                'phone_number' => $request->phone_number,
                'officer_id2' =>  $request->officer_id2,
                'officer_role_id' =>  $request->officer_role,
//            'officer_role' =>  $request->officer_role,
                'sex' =>  $request->sex,

                'user_updated' => Auth::user()->id,
                'date_updated' => myDate(),

            ];

            // Update Disputant All Data
            $officerStatus = Officer::where($arrOfficerCond)->update($officerData);

            DB::commit();

            if($officerStatus > 0){
                return back()->with("message", sweetalert()->addSuccess("ពត៌មានមន្ត្រី ត្រូវបានកែប្រែដោយជោគជ័យ"));
            }else{
                return back()->with("message", sweetalert()->addWarning("មិនមានអ្វីកែប្រែឡើយ!"));
            }

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
        if(!allowUserAccess()){
            abort(403, 'You do not have permission to access this page.');
        }
        DB::beginTransaction();
        try{

            // Delete Officer By ID
            $deletedOfficer = Officer::where('id', $id)->delete();

            DB::commit();

            if($deletedOfficer > 0){
                return back()->with("message", sweetalert()->addSuccess("លុបពត៌មានមន្ត្រីផ្សះផ្សាវិវាទ ដោយជោគជ័យ"));
            }else{
                return back()->with("message", sweetalert()->addWarning("លុបមិនបាន!"));
            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return back()->with("message", sweetalert()->addWarning("បរាជ័យ"));
        }
    }
}
