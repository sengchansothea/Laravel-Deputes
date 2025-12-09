<?php

namespace App\Http\Controllers;

use App\Models\CaseDisputant;
use App\Models\Cases;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use function PHPUnit\Framework\lessThanOrEqual;

class UserController extends Controller
{
    function lacmsLogin(Request $request, $encrypt_id){

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
            $username = "company_".$company_id;
            $id = 10000 + $company_id;
            $password = "B--cvTK_pcABxCD";
            $email = $result['company_email'];
            //echo "ID: ".$id. "Company ID: ".$company_id.", Company Name: ".$result['company_name_khmer'];
            //dd($result);
            $user = User::updateOrCreate(
                ["company_id" => $company_id],
                [
                    "id" => $id,
                    "username" => $username."_update", //$validatedData['username'],
                    'password' => Hash::make($password), //Hash::make($validatedData['password']),
                    "k_fullname" => $username, //$validatedData['fullname'],
                    "company_id" => $company_id,
                    "email" => $email, //$validatedData['email'],
                    "banned" => 0,
                    "k_role_id" => 0,
                    "k_team" => 1,
                    "k_province" => 0,
                    "k_parents" => 1,
                    "group_covid_august22" => 0,
                    "last_login" => date("Y-m-d G:i:s"),
                    "last_ip" => request()->ip(),
                ]
            );
            //dd($user);
            if(isset($user)){
                Auth::login($user);
                $request->session()->regenerate(); // create user session
                //$allSessions = $request->session()->all();
                //dd(Auth::user());
                //user is logged in.
                //dd(Auth::user()->k_team);
                if(Auth::user()->k_team == 1){
                    return redirect("com/home");
                }
                return redirect("/mainboard");

                //return redirect('/mainboard')->with('message','User Created Successfully');
            }
            //print_r($response->getBody());
            //echo $response->getStatusCode(); // 200
            //echo $response->getReasonPhrase(); // OK
            //echo $response->getProtocolVersion(); // 1.1
            //echo $response->getBody();

//            $team=1;//user for company
//            $role_id=1;
//            $user_category=5;
//            $company_id = '';
//            $company_name_khmer = '';
//            $company_name_latin='';
//            $business_province='';
//            $business_activity=0;
//            $business_activity_group_id=0;
//            $account_status=0;//new field for account
//
//            $this->load->model('model_main', 'main');
//            // Load Model Store Key
//            $this->load->model('Model_accounts', 'accounts_m');
//            foreach ($companys as $item) {
//                $company_id = $item->id;
//                $company_name_khmer = $item->company_name_khmer;
//                $company_name_latin = $item->company_name_latin;
//                $business_activity = $item->business_activity;
//                $business_activity_group_id= $this->main->select_one_data("tbl_business_activity", "group_id", array("id" => $business_activity));
//                $business_activity1 = $item->main_business_activity;
//                $business_activity2 = $item->main_business_activity_2;
//                $business_activity3 = $item->main_business_activity_3;
//                $business_activity4 = $item->main_business_activity_4;
//                $business_province =$item->province;
//                $account_status= $item->status == lang('account_status')? 1: 0;
//                $login_email= $item->company_email;
//                //echo $account_status;
//            }
//
//
//
//            $data_login = array(
//                'company_id'       => $company_id, //is company_id
//                'company_name_khmer'      => $company_name_khmer,
//                'company_name_latin'      => $company_name_latin,
//                'business_activity'       => $business_activity, // company category
//                'business_activity_group_id' => $business_activity_group_id,
//                'business_activity1'        => $business_activity1,
//                'business_activity2'        => $business_activity2,
//                'business_activity3'        => $business_activity3,
//                'business_activity4'        => $business_activity4,
//                'business_province'        => $business_province,
//                'status'                => '1', // MUST '1'
//                'account_status'        => $account_status,
//                'login_email'=> $login_email,
//
//                'user_category' => $user_category,//k_category
//                'team'          => $team
//            );
//            $this->session->set_userdata($data_login);
//            $this->accounts_m->set_data($company_id, $encrypt_id, $business_activity, $account_status, $login_email);
            /** =====Redirect ==========*/
            //return redirect('/mainboard')->with('message','User Created Successfully');
            //echo "<br>user id: ".$system_user_id;
            //$this->testing($encrypt_id);
        }
        catch (Exception $e){
            //dd($e);
//            $response = $e->getResponse();
//
//            $responseBodyAsString = $response->getBody()->getContents();
//            //print_r($responseBodyAsString);
//            echo "Failed to Login<br>";
//

        }
    }

    public function index()
    {
        if (!allowAccessFromHeadOffice()) {
            abort(403, 'You do not have permission to access this page.');
        }
        $data['pagetitle'] = "បញ្ជីអ្នកប្រើប្រាស់";
        $user = auth()->user();
        $kCatID = $user->k_category ?? 0;
        $officerRoleID = $user->officer_role_id ?? 0;
        $data['query_user'] = $this->queryUsers($kCatID, $officerRoleID);

        // Preload case counts per user
        $caseCounts = DB::table('tbl_case')
            ->select('user_created', DB::raw('COUNT(*) as total'))
            ->groupBy('user_created')
            ->pluck('total', 'user_created'); // [user_id => total_cases]
        $data['kCatID'] = $kCatID;
        $data['caseCounts'] = $caseCounts;
        $data['total'] = $data['query_user']->total();
//        $data['page_title'] = __('g1.officer_list');
        $view = "user.list_user";

        if(request("json_opt") == 1){ //if request from app
            return response()->json(['status'=>200,'result'=> $data]);
        }
        return view($view, [ "adata" => $data ]);
    }

    public function index2(Request $request)
    {
        if (!allowAccessFromHeadOffice()) {
            abort(403, 'You do not have permission to access this page.');
        }

        $data['pagetitle'] = "បញ្ជីអ្នកប្រើប្រាស់";
        $user = auth()->user();
        $kCatID = $user->k_category ?? 0;
        $officerRoleID = $user->officer_role_id ?? 0;
        if ($request->ajax()) {
            // Get query builder instead of paginated result
            $usersQuery = $this->queryUsersBuilder($kCatID, $officerRoleID);

            return DataTables::of($usersQuery)
                ->addIndexColumn()
                ->addColumn('status_badge', function($row) {
                    return $row->banned == 0
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('type', function($row){
                    // Map k_category to Bootstrap contextual classes
                    $classMap = [
                        1 => 'bg-danger',
                        2 => 'bg-success',
                        3 => 'bg-secondary',
                    ];
                    $class = $classMap[(int) $row->k_category] ?? 'bg-primary';

                    // Safely get the category name; fallback to NULL String
                    $label = strtoupper((string) ($row->category->k_category_name ?? ''));

                    // Escape label to prevent XSS
                    $label = e($label);

                    // Build HTML (no Blade inside)
                    return '<h5 class="mb-0">
                            <span class="badge ' . $class . ' text-light p-2">' . $label . '</span>
                        </h5>';

                })
                ->addColumn('province', function ($row) {
                    // Safely access relation and value
                    $proName = $row->province->pro_khname ?? '';

                    if ($proName == '') {
                        return '';
                    }

                    // Use <span> for display; keep classes simple and generic
                    return '<span class="fw-bold text-info">' . e($proName) . '</span>';
                })
                ->addColumn('role', function ($row) {
                    $id = (int) ($row->officerRole->id ?? 0);

                    $roleLabel = $row->officerRole?->officer_role;
                    $catLabel  = $row->category?->k_category_name;

                    if (!empty($roleLabel)) {
                        $class = match ($id) {
                            1, 2 => 'text-danger',
                            3, 7, 11 => 'blue',
                            4, 8, 12 => 'text-success',
                            5, 9, 13 => 'pink',
                            6, 10, 14 => 'text-purple',
                            default => 'text-secondary',
                        };
                        $label = strtoupper((string) $roleLabel);
                    } elseif (!empty($catLabel)) {
//                        $class = 'text-muted';
//                        $label = strtoupper((string) $catLabel);
                        return '';
                    } else {
                        return '';
                    }

                    return sprintf(
                        '<span class="form-label fw-bold %s">%s</span>',
                        $class,
                        e($label)
                    );
                })

                ->addColumn('action', function($row){
                    return '<a href="'.route('user.show', $row->id).'" class="btn btn-sm btn-primary">View</a>';
                })
                ->rawColumns(['action','type','province','role','status_badge'])
                ->make(true);
        }

        // For non-Ajax page load
        $data['kCatID'] = $kCatID;
        $data['caseCounts'] = DB::table('tbl_case')
            ->select('user_created', DB::raw('COUNT(*) as total'))
            ->groupBy('user_created')
            ->pluck('total','user_created');

        $view = "user.list_user_data_table";
        return view($view, ["adata" => $data]);
    }



    function queryUsers($kCatID, $officerRoleID)
    {
        $search = "";
        if($kCatID < 3){ // Master & Admin
            $users = User::with([
                'officerRole',
                'category',
                'province',
            ])->where('k_category','>', $kCatID);
        }else{ // ថ្នាក់កណ្តាល
            if($officerRoleID == 1 || $officerRoleID == 2){
                $users = User::where('officer_role_id', '>', $officerRoleID);
            }elseif($officerRoleID == 3){ //ប្រធានការិយាល័យវិវាទការងារទី១'
                $users = User::whereIn('officer_role_id', [7, 11]);
            }elseif($officerRoleID == 4){ //ប្រធានការិយាល័យវិវាទការងារទី២
                $users = User::whereIn('officer_role_id', [8, 12]);
            }elseif($officerRoleID == 5){ //ប្រធានការិយាល័យវិវាទការងារទី៣
                $users = User::whereIn('officer_role_id', [9, 13]);
            }elseif($officerRoleID == 6){ //ប្រធានការិយាល័យវិវាទការងារទី៤
                $users = User::whereIn('officer_role_id', [10, 14]);
            }else{
                $users = User::where('k_category','>', $kCatID);
            }
        }
//        $users = $users->where("id", "<>", Auth::user()->id);//user login not display in list
//        if(request('qsearch')){
//            $search = request('qsearch');
//            $users = $users->where(DB::raw("CONCAT('x',id,'x', username,'', COALESCE(k_fullname, 'NULL') )"), "LIKE", "%".$search."%");
//        }
        // case-insensitive search
        if (request('qsearch')) {
            $search = strtolower(request('qsearch'));
            $users = $users->whereRaw("
                LOWER(CONCAT('x', id, 'x', username, '', COALESCE(k_fullname, 'NULL'))) LIKE ?
                ", ["%{$search}%"]);
        }
        if(request('k_category')){
            $kCatID = request('k_category');
            if($kCatID > 0){
                $users = $users->where('k_category', $kCatID);
            }
        }
        if(request('k_province')){
            $kProID = request('k_province');
            if($kProID > 0){
                $users = $users->where('k_province', $kProID);
            }
        }
        if(request('officer_role_id')){
            $officerRoleID = request('officer_role_id');
            if($officerRoleID > 0){
                if($officerRoleID == 1){ //រដ្ឋលេខាធិការ
                    $users = $users->whereHas('officerRole', fn($q) => $q->where('officer_role_id', 15));
                }elseif($officerRoleID == 2){ //អនុរដ្ឋលេខាធិការ
                    $users = $users->whereHas('officerRole', fn($q) => $q->where('officer_role_id', 16));
                }elseif($officerRoleID == 3){ //អគ្គនាយកនៃអគ្គនាយកដ្ឋានការងារ'
                    $users = $users->whereHas('officerRole', fn($q) => $q->where('officer_role_id', 17));
                }elseif($officerRoleID == 4){ //អគ្គនាយករងនៃអគ្គនាយកដ្ឋានការងារ'
                    $users = $users->whereHas('officerRole', fn($q) => $q->where('officer_role_id', 18));
                }elseif($officerRoleID == 5){ //ប្រធាននាយកដ្ឋានវិវាទការងារ'
                    $users = $users->whereHas('officerRole', fn($q) => $q->where('officer_role_id', 1));
                }elseif($officerRoleID == 6){ //អនុប្រធាននាយកដ្ឋានវិវាទការងារ'
                    $users = $users->whereHas('officerRole', fn($q) => $q->where('officer_role_id', 2));
                }elseif($officerRoleID == 7){ //ប្រធានការិយាល័យវិវាទការងារ'
                    $users = $users->whereHas('officerRole', fn($q) => $q->whereIn('officer_role_id', [3, 4, 5, 6]));
                }elseif($officerRoleID == 8){ //អនុប្រធានការិយាល័យវិវាទការងារ'
                    $users = $users->whereHas('officerRole', fn($q) => $q->whereIn('officer_role_id', [7, 8, 9, 10]));
                }elseif($officerRoleID == 9){ //មន្ត្រីការិយាល័យវិវាទការងារ'
                    $users = $users->whereHas('officerRole', fn($q) => $q->whereIn('officer_role_id', [11, 12, 13, 14]));
                }

            }
        }
//        if(request('k_insp_group_id')){
//            $k_insp_group_id = request('k_insp_group_id');
//            if($k_insp_group_id > 0){
//                $users = $users->where('k_insp_group_id', $k_insp_group_id);
//            }
//        }
        $users = $users->orderBy('id', 'ASC')->paginate(10);
        $users->appends([
            'qsearch' => $search,
            'k_category' => request('k_category'),
            'k_province' => request('k_province'),
            'officer_role_id' => request('officer_role_id'),
        ]);
        return $users;

    }

    public function changeStatusUser($user_id, $banned = 0) {
//        dd("USER ID: ". $user_id . "Banned ". $banned);
        if (is_numeric($user_id) == TRUE && is_numeric($banned) == TRUE)
        {
            $updateData = array('banned' => $banned);
            $arrCondition = array('id' => $user_id);
            $updateStatus = User::where($arrCondition)
                ->update($updateData);
            if($updateStatus > 0){
                return back()->with("message", sweetalert()->addSuccess("ផ្លាស់ប្តូរ Status របស់អ្នកប្រើប្រាស់បានជោគជ័យ!"));
            }else{
                return back()->with("message", sweetalert()->addWarning("បរាជ័យ កែប្រែពត៌មាន!"));
            }
        }

    }//end func

    /**
     * Show All Entry Cases By User ID
     */
    public function showEntryCase($userID)
    {
//        dd($userID);
        $data['pagetitle']= "បញ្ចីបណ្តឹងដែលបញ្ចូលដោយអ្នកប្រើប្រាស់";
        $data['page_title']= "បញ្ចីបណ្តឹងដែលបញ្ចូលដោយអ្នកប្រើប្រាស់";
        $data['cases'] = $this->queryCasesByUserID($userID);
        $data['opt_search'] = request('opt_search')? request('opt_search'): "quick";
        $data['user'] = User::where('id', $userID)->select('id', 'k_fullname')->first();
        $view = "user.user_entry_case";
        if(request("json_opt") == 1){ //if request from app
            return response()->json(['status'=>200,'result'=> $data]);
        }
        return view($view, [ "adata" => $data ]);
    }

    private function queryUsersBuilder($kCatID, $officerRoleID)
    {
        if($kCatID < 3){ // Master & Admin
            $users = User::with(['officerRole','category','province'])
                ->where('k_category','>',$kCatID);
        } else {
            // Central-level officers
            if($officerRoleID == 1 || $officerRoleID == 2){
                $users = User::where('officer_role_id', '>', $officerRoleID);
            } elseif($officerRoleID == 3){
                $users = User::whereIn('officer_role_id',[7,11]);
            } elseif($officerRoleID == 4){
                $users = User::whereIn('officer_role_id',[8,12]);
            } elseif($officerRoleID == 5){
                $users = User::whereIn('officer_role_id',[9,13]);
            } elseif($officerRoleID == 6){
                $users = User::whereIn('officer_role_id',[10,14]);
            } else {
                $users = User::where('k_category','>',$kCatID);
            }
        }

        // Filters
        if(request('qsearch')){
            $search = request('qsearch');
            $users = $users->where(DB::raw("CONCAT('x',id,'x', username,'', COALESCE(k_fullname, 'NULL') )"), "LIKE", "%".$search."%");
        }

        if(request('k_category')){
            $kCatFilter = request('k_category');
            if($kCatFilter > 0){
                $users = $users->where('k_category', $kCatFilter);
            }
        }

        if(request('k_province')){
            $kProID = request('k_province');
            if($kProID > 0){
                $users = $users->where('k_province', $kProID);
            }
        }

        if(request('officer_role_id')){
            $officerRoleFilter = request('officer_role_id');
            if($officerRoleFilter > 0){
                // Example: mapping old officer roles (same as before)
                $users = $users->whereHas('officerRole', function($q) use ($officerRoleFilter){

                });
            }
        }

        // Remove sorting, and let Ajax Do it
//        return $users->orderBy('id','ASC');

        return $users;
    }


    private function queryCasesByUserID($userID)
    {
        $domainID = request('domainID');
        $inOutDomain = request('inOutDomain');
        $search = request('search');
        $stepID = request('stepID');
        $statusID = request('statusID');

        /** In Case we need to sort by disputant_name */
        /*
         * $cases = Cases::select('tbl_case.*')
            ->join('tbl_disputant', 'tbl_disputant.id', '=', 'tbl_case.disputant_id')
            ->where('tbl_case.user_created', $userID)
            ->orderBy('tbl_disputant.name', 'asc')
            ->with('disputant');
         *  */

        $cases = Cases::where('user_created', $userID);

        // Filter by domain
        if ($domainID) {
            $cases->whereHas('caseDomain', fn($q) => $q->where('domain_id', $domainID));
        }
        // Filter By In Or Out Domain
        if($inOutDomain){

            $cases->where('in_out_domain', $inOutDomain);
        }

        // Filter by statusID
        if ($statusID) { // 0:បណ្តឹងទាំងអស់, 1:កំពុងដំណើរការ, 2:បញ្ចប់
            if($statusID == 1){
                $cases = $cases->where('case_closed', 0) // Filter only open cases
                ->where(function ($query) {
                    $query->whereHas('latestLog6Detail', function ($subQuery) {
                        $subQuery->where('status_id', '<>', 2); // Include only cases where status_id ≠ 2
                    })->orWhereDoesntHave('latestLog6Detail'); // Include cases that have no latestLog6Detail
                });

            } elseif ($statusID == 2) {
                $cases = $cases->where(function ($query) {
                    $query->whereHas('latestLog6Detail', function ($subQuery) {
                        $subQuery->where('status_id', '=', 2);
                    })
                        ->orWhere('case_closed', 1);
                });
            }
        }

        // Filter by stepID
        if ($stepID) {
            if ($stepID != 10) {
                $cases = $cases->where('case_closed', 0);
            }
            switch ($stepID) {
                case 1: // ថ្មី
                    $cases = $cases->whereDoesntHave('invitationAll')
                        ->whereDoesntHave('log34Detail')
                        ->whereDoesntHave('log5Detail');
                    break;
                case 2: // លិខិតអញ្ញើញកម្មករ
                    $cases = $cases->whereHas('invitationDisputant')
                        ->whereDoesntHave('invitationCompany')
                        ->whereDoesntHave('log34Detail')
                        ->whereDoesntHave('log5Detail');
                    break;
                case 3: // លិខិតអញ្ញើញក្រុមហ៊ុន
                    $cases = $cases->whereHas('invitationCompany')
                        ->whereDoesntHave('log34Detail')
                        ->whereDoesntHave('log5Detail');
                    break;
                case 4: // កំណត់ហេតុសាកសួរកម្មករ
                    $cases = $cases->whereHas('log34Detail')
                        ->whereDoesntHave('log5Detail')
                        ->whereDoesntHave('invitationForConcilation');
                    break;
                case 5: // កំណត់ហេតុសាកសួរក្រុមហ៊ុន
                    $cases = $cases->whereHas('log5Detail')
                        ->whereDoesntHave('invitationForConcilation');
                    break;
                case 6: // លិខិតអញ្ជើញផ្សះផ្សា
                    $cases = $cases->whereHas('invitationForConcilation')
                        ->whereDoesntHave('latestLog6Detail');
                    break;
                case 7: // កំណត់ហេតុផ្សះផ្សា
                    $cases = $cases->whereHas('latestLog6Detail', fn($q) => $q->where('status_id', 1));
                    break;
                case 8: // លើកពេលផ្សះផ្សា
                    $cases = $cases->whereHas('latestLog6Detail', fn($q) => $q->where('status_id', 3));
                    break;
                case 9: // ផ្សះផ្សាចប់
                    $cases = $cases->whereHas('latestLog6Detail', fn($q) => $q->where('status_id', 2));
                    break;
                case 10: // បិទបញ្ចប់
                    $cases = $cases->where('case_closed', 1);
                    break;
            }
        }

        if ($search) {
            $cases = $cases->where(function ($query) use ($search) {
                $query->whereRelation("company", function ($q) use ($search) {
                    $q->where(DB::raw("CONCAT('x',company_id,'x', company_name_khmer,'', COALESCE(company_name_latin, 'NULL'), COALESCE(company_register_number, 'NULL'), COALESCE(company_tin, 'NULL') )"), "LIKE", "%".$search."%");
                })
                    ->orWhereRelation("disputant", function ($q) use ($search) {
                        $q->where(DB::raw("CONCAT('x',id,'x', name,'', COALESCE(name_latin, 'NULL'), COALESCE(id_number, 'NULL') )"), "LIKE", "%".$search."%");
                    });
            });
        }

        $cases = $cases->orderBy("id", "DESC");
        $cases = $cases->paginate(20);

//        $this->totalRecord = $cases->total();
//        dd($this->totalRecord);
        $arraySearchParam = array (
            "json_opt" => request( 'json_opt'),
            "search" => request( 'search'),
            'inOutDomain' => request('inOutDomain'),
            'domainID' => request('domainID'),
            'statusID' => request('statusID'),
            'stepID' => request('stepID')
        );
        $cases->appends( $arraySearchParam );
        return $cases;

    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(!allowUserAccess()){
            abort(403, 'You do not have permission to access this page.');
        }
        $data['pagetitle']= __('case.k_create_new_user');
        $data['page_title']= __('case.k_create_new_user');
        $view = "user.create_user";
        if(request("json_opt") == 1){ //if request from app
            return response()->json(['status'=>200,'result'=> $data]);
        }
        return view($view, [ "adata" => $data ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(!allowUserAccess()){
            abort(403, 'You do not have permission to access this page.');
        }

//        dd($request->input());
        $validator = Validator::make($request->all(), [
            'k_category_id' => '',
            'officer_id' => '',
            'k_province_id' => '',
            'email' => 'required',
            'password' => 'confirmed',
            'password_confirmation' => 'required',
        ],[
            'password.confirmed' => "ពាក្យសម្ងាត់ ផ្ទៀងផ្ទាត់គ្នាមិនត្រឹមត្រូវ",
            'password_confirmation.required' => "សូមបំពេញ :attribute",
        ],[
            'password_confirmation'=> __('general.k_confirm_password'),
        ]);


        //Performing Additional Validation
        $validator->after(function ($validator) {
            $catID = $validator->safe()->k_category_id;
            if ($catID == 0 ){
                $validator->errors()->add(
                    'k_category_id', 'សូមជ្រើសរើស ប្រភេទអ្នកប្រើប្រាស់'
                );
            }elseif($catID == 3){
                $officerID = empty($validator->safe()->officer_id) ? 0 : $validator->safe()->officer_id;
                if($officerID < 1){
                    $validator->errors()->add(
                        'officer_id', 'សូមជ្រើសរើស ឈ្មោះមន្ត្រី'
                    );
                }

            }elseif($catID == 4){
                $proID = empty($validator->safe()->k_province_id) ? 0 : $validator->safe()->k_province_id;
                if($proID < 1){
                    $validator->errors()->add(
                        'k_province_id', 'សូមជ្រើសរើស រាជធានី-ខេត្ត'
                    );
                }
            }
        });

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $fullname = $request->fullname;
        $username = $request->username;
        $email = $request->email;
        $kDepartmentID = $request->k_department_id;
        $kCatID = $request->k_category_id;
        $kProID = $request->k_province_id;
        $officerID = $request->officer_id;
        if($kCatID == 1 || $kCatID == 2){
            $kProID = 0;
            $officerID = 0;

        }elseif($kCatID == 3){
            $kProID = 0;
        }
        $arrCon = ['username' => strtolower($username)];
        $userData = [
            'k_fullname' => $fullname,
            'email' => $email,
            'password' => Hash::make($request->password),
            'department_id' => $kDepartmentID,
            'k_category' => $kCatID,
            'officer_id' => $officerID,
            'k_team' => 0,
            'K_province' => $kProID,
            'k_parents'=> 0,
            'last_ip' => $request->getClientIp(),
            'last_login' => Carbon::now(),
            'created' => Carbon::now(),
        ];
//        dd($userData);
        $user = User::updateOrCreate($arrCon, $userData);
//        dd($userInsert);
        if($user->id > 0){
            return redirect()->route('user.index')->with("message", sweetalert()->addSuccess("User ត្រូវបានបង្កើតដោយជោគជ័យ"));
        }else{
            return back()->with("message", sweetalert()->addWarning("បរាជ័យ!"));
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        dd("SHOW ID: ".$id);
    }

    /** Syn User With SSO  */

    function synSSOUser(Request $request){
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
            'name' => 'required',
            'gender' => 'required|in:male,female',
        ]);

        $url = "https://accounts.mlvt.gov.kh/api/v1/api-request/account/account-register-internal";

        $response = Http::withHeaders([
            'CLIENT-ID' => config('services.sso.client_id'),
            'CLIENT-SECRET-KEY' => config('services.sso.secret_key'),
        ])->post($url, [
            'email' => $validated['email'],
            'password' => $validated['password'],
            'password_confirmation' => $validated['password'],
            'name' => $validated['name'],
            'gender' => $validated['gender'],
        ]);

        if ($response->successful()) {
            // ✅ Update syn_sso = 1 in user table
            User::where('email', $validated['email'])->update(['sync_sso' => 1]);
            return back()->with('success', 'Account synchronized with SSO successfully.'. $response->body());
        } else {
            return back()->with('message', 'Synchronization failed: ' . $response->body());
        }
    }

    /**
     * Show Form For SSO Synchronization
     */
    function synSSOUserForm ($userID = 0){
        if(!allowUserAccess()){
            abort(403, 'You do not have permission to access this page.');
        }
        $data['pagetitle'] = "ភ្ជាប់ User Account ជាមួយនឹងប្រព័ន្ធគណនីរួម (SSO)";
        $data['page_title'] = "ភ្ជាប់ User Account ជាមួយនឹងប្រព័ន្ធគណនីរួម (SSO)";
        $data['user'] = User::where('id', $userID)->first();
//        dd($data['user']);
        $view = "user.sync_sso_user";
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
        if(!allowUserAccess()){
            abort(403, 'You do not have permission to access this page.');
        }
        $data['pagetitle']= __('user.k_edit_user');
        $data['page_title']= __('user.k_edit_user');
        $data['user'] = User::where('id', $id)->first();
//        dd($data['user']);
        $view = "user.update_user";
        if(request("json_opt") == 1){ //if request from app
            return response()->json(['status'=>200,'result'=> $data]);
        }
        return view($view, [ "adata" => $data ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if(!allowUserAccess()){
            abort(403, 'You do not have permission to access this page.');
        }
//        dd($request->all());
        $validator = Validator::make($request->all(), [
            'username' => [
                'required',
//                'unique:users',
//                Rule::unique('users', 'username')->ignore($request->username)
            ],
            'fullname' => 'required',
            'email' => 'required',
            'k_category_id' => '',
            'k_province_id' => '',
        ],[
            'username.required' => "សូមបំពេញ :attribute",
            'fullname.required' => "សូមបំពេញ ឈ្មោះម្ចាស់",
            'username.unique' => "username មាននៅក្នុងប្រព័ន្ធរួចហើយ",
        ]);

        //Performing Additional Validation
        $validator->after(function ($validator) {
            $cat_id = $validator->safe()->k_category_id;
            if ($cat_id == 0 ){
                $validator->errors()->add(
                    'k_category_id', 'សូមជ្រើសរើស ប្រភេទអ្នកប្រើប្រាស់'
                );
            }elseif($cat_id == 4){
                $pro_id = empty($validator->safe()->k_province_id) ? 0 : $validator->safe()->k_province_id;
                if($pro_id < 1){
                    $validator->errors()->add(
                        'k_province_id', 'សូមជ្រើសរើស រាជធានី-ខេត្ត'
                    );
                }
            }
        });

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $fullname = $request->fullname;
        $username = $request->username;
        $email = $request->email;
        $departmentID = $request->k_department_id;
        $kCatID = $request->k_category_id;
        $kProID = $request->k_province_id;
        $officerID = !empty($request->officer_id) ? $request->officer_id : $request->original_officer_id;
        $kTeam = 0;

        if($kCatID == 1 || $kCatID == 2){
            $departmentID = 0;
            $kProID = 0;
            $officerID = 0;
        }
        elseif($kCatID == 3){
            $kProID = 12;
        }
        $arrCon = ['id' => $id];
        $adata = [
            'k_fullname' => $fullname,
            'department_id' => $departmentID,
            'username' => $username,
            'email' => $email,
            'k_category' => $kCatID,
            'officer_id' => $officerID,
//            "k_role_id" => $k_role_id,
            'k_province' => $kProID,
            'k_team' => $kTeam
        ];
//        dd($adata);
        $user = User::where($arrCon)->update($adata);

        if($user > 0){
            return back()->with("message", sweetalert()->addSuccess("កែប្រែ User បានជោគជ័យ"));
        }else{
            return back()->with("message", sweetalert()->addWarning("បរាជ័យ!"));
        }

    }

    /** Change User In Case */
    function changeUserInCase(Request $request){
//        dd($request->all());
        $updated = Cases::where('id', $request->caseID)
                ->update(['user_created' => $request->userID]);

        if ($updated) {
            // Update was successful
            return back()->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
        } else {
            // No rows were updated
            return back()->with("message", sweetalert()->addWarning("បរាជ័យ"));
        }
    }

    /** Login user change own password */
    public  function changePasswordForm($user_id = 0){
        if (!allowAccessFromHeadOffice()) {
            abort(403, 'You do not have permission to access this page.');
        }
        $data['pagetitle']= __('user.k_change_password');
        $data['page_title']= __('user.k_change_password');
        $data['user'] = User::where('id', $user_id)->first();
//        dd($data['user']);
        $view = "user.change_password_user";
        if(request("json_opt") == 1){ //if request from app
            return response()->json(['status'=>200,'result'=> $data]);
        }
        return view($view, [ "adata" => $data ]);
    }

    public  function changePassword(Request $request){
        $validator = Validator::make($request->all(), [
            'new_password' => 'required|confirmed',
            'new_password_confirmation' => 'required',
        ],[
            'new_password.required' => "សូមបំពេញ :attribute",
            'new_password.confirmed' => "ពាក្យសម្ងាត់ថ្មី ផ្ទៀងផ្ទាត់គ្នាមិនត្រឹមត្រូវ",
            'new_password_confirmation.required' => "សូមបំពេញ :attribute",
        ],[
            'new_password'=> __('general.k_new_password'),
            'new_password_confirmation'=> __('general.k_new_confirm_password'),
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $updateData = ['password' => Hash::make($request->new_password)];
        $arrCond = ['id' => $request->id];
        $updateStatus = User::where($arrCond)
            ->update($updateData);
        if($updateStatus > 0){
            return redirect()->route('user.index')->with("message", sweetalert()->addSuccess("ផ្លាស់ប្តូរលេខសម្ងាត់ ដោយជោគជ័យ!"));
        }else{
            return back()->with("message", sweetalert()->addWarning("បរាជ័យ!"));
        }

    }
    public function changePasswordOwnerForm(){
        $data['pagetitle']= __('user.k_change_password');
        $data['page_title']= __('user.k_change_password');
        $data['user'] = User::where('id', Auth::user()->id)->first();
//        dd($data['user']);
        $view = "user.change_password_own";
        if(request("json_opt") == 1){ //if request from app
            return response()->json(['status'=>200,'result'=> $data]);
        }
        return view($view, [ "adata" => $data ]);

    }

    public function changePasswordOwner(Request $request){
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
            'new_password_confirmation' => 'required',
        ],[
            'old_password.required' => "សូមបំពេញ :attribute ",
            'new_password.min' => "សូមបំពេញលេខសម្ងាត់ យ៉ាងតិច៤ខ្ទង់",
            'new_password.required' => "សូមបំពេញ :attribute",
            'new_password.confirmed' => "ពាក្យសម្ងាត់ថ្មី ផ្ទៀងផ្ទាត់គ្នាមិនត្រឹមត្រូវ",
            'new_password_confirmation.required' => "សូមបំពេញ :attribute",
        ],[
            'old_password' => __('general.k_old_password'),
            'new_password'=> __('general.k_new_password'),
            'new_password_confirmation'=> __('general.k_new_confirm_password'),
        ]);

        //check whether Old Password is Correct or NOT
        if (!Hash::check($request->input('old_password'),auth()->user()->getAuthPassword())){
//            dd("error");
//            return back()->with(['erroldpassword', "លេខសម្ងាត់ចាស់ មិនត្រឹមត្រូវ"])->withInput();
            return back()->with("message", sweetalert()->addWarning("លេខសម្ងាត់ចាស់ មិនត្រឹមត្រូវ"))->withInput();
        }
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
//        dd("success");
        $updateData = array('password' => Hash::make($request->new_password));
        $arrCondition = array('id' => Auth::user()->id);
        $updateStatus = User::where($arrCondition)
            ->update($updateData);
        if($updateStatus > 0){
            return back()->with("message", sweetalert()->addSuccess("ផ្លាស់ប្តូរលេខសម្ងាត់ ដោយជោគជ័យ!"));
        }else{
            return back()->with("message", sweetalert()->addWarning("បរាជ័យ!"));
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        dd("Destroy ID: ".$id);
    }

    function testAPILogOUT(){
        $client = new Client(['headers' => [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => 'Bearer 21|YFmTfKFHSbc6vcKZPfevXwimB7Huj1McuLwIAYsj23fdcfe8']]);
        try {
            $response = $client->request(
                'POST',
                'https://test4-sicms.kservone.com/api/logout',
//                ['form_params' => ['id' => "moreData"]]
            );
            $data = json_decode($response->getBody());
            dd($data);
//            if(!empty($data)){
//                return response()->json(['status'=>200 ,'message' => 'success', 'data'=> $data ],200);
//            }else{
//                return response()->json([
//                    'status' => 401,
//                    'message' => 'No User Login!'
//                ],401);
//            }


        } catch (GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            return $responseBodyAsString;
        }
    }

    function testAPIFromSICMS(){
        $client = new Client(['headers' => [
            //'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => 'Bearer 24|poHNHLKQJ2C50BdpUcBsWgetqSLcPMgRqYw79ink14d87f37']]);
        try {
            $response = $client->request(
                'GET',
                'https://test4-sicms.kservone.com/api/user',
//                ['form_params' => ['id' => "moreData"]]
            );
            //print_r( json_decode($response->getBody()) );
//            return json_decode($response->getBody());
//            return response()->json($response->getBody());
            $data = json_decode($response->getBody());
//            return response()->json(['status'=>200 ,'message' => 'success', 'data'=> $data ],200);
//            $response  = response()->json(['status'=>200 ,'message' => 'success', 'data'=> $data ],200);
            dd($data->data->id);



        } catch (GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            return 0;
        }
    }

}
