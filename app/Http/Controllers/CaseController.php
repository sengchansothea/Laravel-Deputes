<?php

namespace App\Http\Controllers;

use App\Models\CaseCompany;
use App\Models\CaseDisputant;
use App\Models\CaseInvitation;
use App\Models\CaseLog;
use App\Models\CaseLog34;
use App\Models\CaseLog5;
use App\Models\CaseLog5Union1;
use App\Models\CaseLog6;
use App\Models\CaseLog620;
use App\Models\CaseLog621;
use App\Models\CaseLogAttendant;
use App\Models\CaseOfficer;
use App\Models\Cases;
use App\Models\Company;
use App\Models\CompanyApi;
use App\Models\Disputant;
use App\Models\InvitationNextTime;
use App\Models\Officer;
use App\Models\OfficerRole;
use App\Models\User;
use App\Services\TelegramService;
use Carbon\Carbon;
use Carbon\Traits\Date;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CaseController extends Controller
{
    public $currentStep = 1;

    private int $totalRecord = 0;
    private int $perPage = 10;
    /**
     * Display a listing of the resource.
     */

    // public function index(Request $request)
    // {
    //     //        dd($request->all());
    //     // ✅ Export Excel mode
    //     if ($request->has('export_excel')) {
    //         //            dd("Hello");
    //         $exportController = new ExportExcelController();
    //         return $exportController->exportCasesList($request);
    //     }

    //     $user = auth()->user();
    //     $cases = $this->getOrSearchEloquent($user);
    //     $caseIDs = $cases->pluck('id')->toArray(); // All visible case IDs
    //     //        dd($caseIDs);

    //     // Fetch officer IDs for all case IDs in one query
    //     $officerIDsByCase = CaseOfficer::whereIn('case_id', $caseIDs)
    //         ->get()
    //         ->groupBy('case_id')
    //         ->map(fn($group) => $group->pluck('officer_id')->toArray());

    //     // Eager load the officer to prevent N+1
    //     $caseOfficers = CaseOfficer::with('officer')
    //         ->whereIn('case_id', $caseIDs)
    //         ->whereIn('attendant_type_id', [6, 8]) // 6:CaseOfficer , 8:Noter
    //         ->orderByDesc('id')
    //         ->get()
    //         ->groupBy(fn($item) => "{$item->case_id}_{$item->attendant_type_id}");


    //     $userID = $user->id ?? 0;
    //     $userOfficerID = $user->officer_id ?? 0;
    //     $kCategory = (int) $user->k_category ?? 0;
    //     $entryUserID = $cases->user_created ?? 0;
    //     $officerRoleID = getOfficerRoleID($userOfficerID);

    //     // Precompute allow access
    //     $allowAccess = allowAccess($userID, $kCategory, $entryUserID, $officerRoleID);
    //     $data = [
    //         'opt_search' => request('opt_search') ? request('opt_search') : "quick",
    //         'pagetitle' => "បញ្ជីពាក្យបណ្តឹង",
    //         'cases' => $cases,
    //         'user' => $user,
    //         'userID' => $userID,
    //         'userOfficerID' => $userOfficerID,
    //         'totalRecord' => $cases->total(),
    //         'allowAccess' => $allowAccess,
    //         'officerIDsByCase' => $officerIDsByCase,
    //         'caseOfficers' => $caseOfficers,
    //     ];

    //     $view = "case.list_case";
    //     if (request("json_opt") == 1) { //if request from app
    //         return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    //     }

    //     return view($view, ["adata" => $data]);
    // }

    public function index(Request $request)
    {
        $user = Auth::user();

        // 1️⃣ Fetch cases with optional search (your existing search/query logic)
        $cases = $this->getOrSearchEloquent($user);
        $caseIDs = $cases->pluck('id')->toArray();

        // 2️⃣ Preload officer IDs by case
        $officerIDsByCase = CaseOfficer::whereIn('case_id', $caseIDs)
            ->get()
            ->groupBy('case_id')
            ->map(fn($group) => $group->pluck('officer_id')->toArray());

        // 3️⃣ Preload case officers (attendant types: 6 = Case Officer, 8 = Noter)
        $caseOfficers = CaseOfficer::with('officer')
            ->whereIn('case_id', $caseIDs)
            ->whereIn('attendant_type_id', [6, 8])
            ->orderByDesc('id')
            ->get()
            ->groupBy(fn($item) => "{$item->case_id}_{$item->attendant_type_id}");

        // 4️⃣ User info
        $userID = $user->id ?? 0;
        $userOfficerID = $user->officer_id ?? 0;
        $kCategory = (int) ($user->k_category ?? 0);
        $entryUserID = optional($cases->first())->user_created ?? 0;
        $officerRoleID = getOfficerRoleID($userOfficerID); // Your helper function

        // 5️⃣ Compute allow access (boolean)
        $allowAccess = allowAccess($userID, $kCategory, $entryUserID, $officerRoleID);

        // 6️⃣ Prepare data for Blade
        $adata = [
            'opt_search'        => $request->input('opt_search', 'quick'),
            'pagetitle'         => 'បញ្ជីពាក្យបណ្ដឹង',
            'cases'             => $cases,
            'user'              => $user,
            'userID'            => $userID,
            'userOfficerID'     => $userOfficerID,
            'totalRecord'       => $cases->total(),
            'allowAccess'       => $allowAccess,
            'officerIDsByCase'  => $officerIDsByCase,
            'caseOfficers'      => $caseOfficers,
        ];

        // 7️⃣ Optional JSON API response
        if ($request->input('json_opt') == 1) {
            return response()->json([
                'status' => 200,
                'message' => 'success',
                'data' => $adata
            ]);
        }

        // 8️⃣ Return Blade view (works with refactored Blade)
        return view('case.list_case1', compact('adata'));
    }



    public function showTemplateFiles()
    {
        $data['pagetitle'] = "ទម្រង់លិខិត";
        $view = "case.update_template_files";

        if (request("json_opt") == 1) { //if request from app
            return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
        }
        return view($view, ["adata" => $data]);
    }

    public function updateTemplate(Request $request)
    {
        //        dd($request->all());
        if (auth()->user()->k_category > 2) {
            return back()->with("message", sweetalert()->addWarning("អ្នកមិនមានសិទ្ធិប្រើ Feature នេះទេ!"));
        }
        DB::beginTransaction();
        try {

            /** =============== Update All Template Files ====================== */
            //            $pathToUpload = ("storage/doc_template/");
            $pathToUpload = pathToUploadFile("doc_template/");

            //            Case Template
            $caseTemplateNew = myUploadFileTemplate($request, $pathToUpload, "case_template", "1_case_report");
            //            $caseTemplate = !empty($caseTemplateNew)? $caseTemplateNew : $request->case_template_old;

            //            //Invitation Template
            $invTemplateNew = myUploadFileTemplate($request, $pathToUpload, "inv_template", "invitation_letter");
            //            $invTemplate = !empty($invTemplateNew)? $invTemplateNew : $request->inv_template_old;

            //Invitation Reconcilation Tempalte
            $invReconcilTemplateNew = myUploadFileTemplate($request, $pathToUpload, "inv_reconcil_template", "invitation_reconcilation");
            //            $invReconcilTemplate = !empty($invReconcilTemplateNew)? $invReconcilTemplateNew : $request->defendant_file_old;

            //Log34 Employee Template
            $log34TemplateNew = myUploadFileTemplate($request, $pathToUpload, "log34_template", "3_log34_employee_info");
            //            $log34Template = !empty($log34TemplateNew)? $log34TemplateNew : $request->log34_template_old;

            //Log5 Company Template
            $log5TemplateNew = myUploadFileTemplate($request, $pathToUpload, "log5_template", "5_log5_company_info");
            //            $log5Template = !empty($log5TemplateNew)? $log5TemplateNew : $request->log5_template_old;

            //Log6 Template
            $log6TemplateNew = myUploadFileTemplate($request, $pathToUpload, "log6_template", "6_log6");
            //            $log6Template = !empty($log6TemplateNew)? $log6TemplateNew : $request->log6_template_old;

            //Test Template
            $testTemplateNew = myUploadFileTemplate($request, $pathToUpload, "test_template", "test_template");

            DB::commit();
            return redirect("template/")->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return back()->with("message", sweetalert()->addWarning("បរាជ័យ"));
        }
    }
    function getOrSearchEloquent($user)
    {
        //        dd(request()->all());
        //        dd(request('domainID'));

        /** In Case We Need To Sort By Disputant_Name  */
        /*
         $cases = Cases::select('tbl_case.*')
            ->join('tbl_disputant', 'tbl_disputant.id', '=', 'tbl_case.disputant_id')
            ->where('tbl_case.case_type_id', 1)
            ->orderBy('tbl_disputant.name', 'asc')
            ->with([
                'caseDomain',
                'entryUser.officerRole',
                'invitationAll',
                'log34Detail',
                'log5Detail',
                'invitationDisputant',
                'invitationCompany',
                'invitationForConcilation',
                'latestLog6Detail',
                'company',
                'disputant',
                'caseType',
                'caseClosedStep',
                'caseCompany'
            ]);
         */

        $cases = Cases::with([
            'caseDomain',
            'entryUser.officerRole',
            'invitationAll',
            'log34Detail',
            'log5Detail',
            'invitationDisputant',
            'invitationCompany',
            'invitationForConcilation',
            'latestLog6Detail',
            'latestLog6Detail.status',
            'company',
            'disputant',
            'caseType',
            'caseClosedStep',
            'caseCompany',

        ])->where('case_type_id', 1);

        // Assign request parameters to variables to avoid multiple calls
        $domainID      = request('domainID');
        $inOutDomain   = request('inOutDomain');
        $stepID        = request('stepID');
        $statusID      = request('statusID');
        $year          = request('year');
        $search        = request('search');
        $chkUserIdentity = chkUserIdentity();

        // Advanced Search Request Parameters
        $busActivity = request('business_activity');
        $companyTypeID = request('company_type_id');
        $cSIC          = collect([
            request('csic1'),
            request('csic2'),
            request('csic3'),
            request('csic4'),
            request('csic5')
        ])->filter();
        $provinceID = request('province_id');
        $districtID = request('district_id');
        $communeID = request('commune_id');

        /**
         * ===========================
         * 🔹 FILTER BLOCKS
         * ===========================
         */

        // Filter by user office domain
        if (in_array($chkUserIdentity, [31, 32, 33, 34])) {
            $domainMap = [31 => 1, 32 => 2, 33 => 3, 34 => 4];
            $cases->whereHas('entryUser.officerRole', function ($q) use ($chkUserIdentity, $domainMap) {
                $q->where('domain_id', $domainMap[$chkUserIdentity]);
            });
        } elseif ($chkUserIdentity == 4) {
            $cases->whereHas('entryUser', fn($q) => $q->where('k_province', $user->k_province));
        }

        // Filter by domain
        $cases->when(
            $domainID,
            fn($q) =>
            $q->whereHas('entryUser.officerRole', fn($sub) => $sub->where('domain_id', $domainID))
        );

        // Filter In Or Out Domain
        $cases->when($inOutDomain, fn($q) => $q->where('in_out_domain', $inOutDomain));

        // Filter by statusID
        // 0:បណ្តឹងទាំងអស់, 1:កំពុងដំណើរការ, 2:បញ្ចប់
        $cases->when($statusID, function ($q) use ($statusID) {
            if ($statusID == 1) {
                $q->where('case_closed', 0) // Filter only open cases
                    ->where(function ($query) {
                        $query->whereHas('latestLog6Detail', function ($subQuery) {
                            $subQuery->where('status_id', '<>', 2); // Include only cases where status_id ≠ 2
                        })->orWhereDoesntHave('latestLog6Detail'); // Include cases that have no latestLog6Detail
                    });
            } elseif ($statusID == 2) {
                $q->where(function ($query) {
                    $query->whereHas('latestLog6Detail', function ($subQuery) {
                        $subQuery->where('status_id', '=', 2);
                    })->orWhere('case_closed', 1);
                });
            }
        });

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

        // Filter by year (only if selected)
        $cases->when($year && $year != 0, fn($q) => $q->whereYear('case_date', $year));

        // Keyword Search
        $cases->when($search, function ($q) use ($search) {
            $q->where(function ($query) use ($search) {
                $query->whereRelation('company', function ($sub) use ($search) {
                    //                    $sub->where(DB::raw("CONCAT_WS('', company_id, company_name_khmer, COALESCE(company_name_latin, ''), COALESCE(company_register_number, ''), COALESCE(company_tin, ''))"), 'LIKE', "%{$search}%");
                    $sub->where(DB::raw("CONCAT('x',company_id,'x', company_name_khmer,'', COALESCE(company_name_latin, 'NULL'), COALESCE(company_register_number, 'NULL'), COALESCE(company_tin, 'NULL') )"), "LIKE", "%" . $search . "%");
                })->orWhereRelation('disputant', function ($sub) use ($search) {
                    //                        $sub->where(DB::raw("CONCAT_WS('', id, name, COALESCE(name_latin, ''), COALESCE(id_number, ''))"), 'LIKE', "%{$search}%");
                    $sub->where(DB::raw("CONCAT('x',id,'x', name,'', COALESCE(name_latin, 'NULL'), COALESCE(id_number, 'NULL') )"), "LIKE", "%" . $search . "%");
                });
            });
        });

        /** Advanced Search Filter Block */
        $cases->when(
            $busActivity,
            fn($q) =>
            $q->whereHas('caseCompany', fn($sub) =>
            $sub->where('log5_business_activity', $busActivity))
        )->when(
            $companyTypeID,
            fn($q) =>
            $q->whereHas('caseCompany', fn($sub) =>
            $sub->where('log5_company_type_id', $companyTypeID))
        )->when($provinceID, function ($q) use ($provinceID, $districtID, $communeID) {
            $q->whereHas('caseCompany', function ($sub) use ($provinceID, $districtID, $communeID) {
                $sub->where('log5_province_id', $provinceID);
                if ($districtID) $sub->where('log5_district_id', $districtID);
                if ($communeID) $sub->where('log5_commune_id', $communeID);
            });
        })->when($cSIC->isNotEmpty(), function ($q) use ($cSIC) {
            $q->whereHas('caseCompany', function ($sub) use ($cSIC) {
                foreach ($cSIC->values() as $index => $value) {
                    $sub->where("log5_csic_" . ($index + 1), $value);
                }
            });
        });

        /**
         * ===========================
         * 🔹 SORTING & PAGINATION
         * ===========================
         */

        $cases = $cases->orderByDesc('id')->paginate($this->perPage);
        $cases->appends([
            'json_opt'    => request('json_opt'),
            'search'      => $search,
            'inOutDomain' => $inOutDomain,
            'domainID'    => $domainID,
            'statusID'    => $statusID,
            'stepID'      => $stepID,
            'year'        => $year,
        ]);

        return $cases;
    }
    function uploadCaseFile(Request $request)
    {
        //dd($request->all());
        $case_id = $request->case_id;
        /** ===============Upload File ======================== */
        $path_to_upload = pathToUploadFile("case_doc/form1");
        $case_file = uploadFileOnly($request, $path_to_upload, "case_file", $case_id);
        Cases::where("id", $case_id)->update([
            "case_file" => $case_file
        ]);

        return back()->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
    }
    function assignOfficer(Request $request)
    {
        //dd($request->all());
        /** ===============BlogG: Assign Officer ======================== */
        $this->updateOrCreateOfficer($request->case_id, $request->officer_id);
        return back()->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
    }

    function assignOfficer22(Request $request)
    {
        dd($request->all());
        /** ===============BlogG: Assign Officer ======================== */
        $this->updateOrCreateOfficer($request->case_id, $request->officer_id);
        return back()->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'pagetitle' => "បង្កើតពាក្យបណ្ដឹងថ្មី",
            'caseNumber' => $this->generateCaseNumber(),
            'arrCaseType' => arrCaseType(1),
            'arrSector' => myArraySector(1),
            'arrCompanyType' => arrayCompanyType(1),
            'arrProvince' => arrayProvince(1),
            'arrNationality' => arrayNationality(1),
            'arrObjectiveCase' => arrayObjectiveCase(1),
            'arrContractType' => [
                1 => "កំណត់",
                2 => "មិនកំណត់",
                3 => "សាកល្បង"
            ],
            'arrNightWork' => [
                1 => "ធ្លាប់ធ្វើ",
                2 => "ម្ដងម្កាល",
                3 => "មិនធ្លាប់ធ្វើ"
            ],
            'arrHolidayWeek' => [
                1 => "ឈប់តាមប្រកាស",
                2 => "ម្ដងម្កាល",
                3 => "មិនធ្លាប់បានឈប់"
            ],
            'arrHolidayYear' => [
                1 => "ឈប់តាមប្រកាស",
                2 => "ធ្លាប់បានឈប់ម្ដងម្កាល",
                3 => "មិនធ្លាប់បានឈប់"
            ],
            'arrOfficersInHand' => $this->getOfficersInHand()
        ];
        $view = "case.create_case1";
        if (request("json_opt") == 1) { //if request from app
            return response()->json(['status' => 200, 'result' => $data]);
        }
        return view($view, ["adata" => $data]);
    }
    public function createStep1()
    {
        $data = [
            'pagetitle' => "បង្កើតពាក្យបណ្ដឹងថ្មី (ទម្រង់ថ្មី)",
            'caseNumber' => $this->generateCaseNumber(),
            'arrCaseType' => arrCaseType(1),
            'arrSector' => myArraySector(1),
            'arrCompanyType' => arrayCompanyType(1),
            'arrProvince' => myArrProvince(0, 1, "សូមជ្រើសរើស", 1),
            //            'arrProvince' => arrayProvince(1),
            'case' => $case ?? null,
        ];
        $view = "case.case_step1";
        if (request("json_opt") == 1) { //if request from app
            return response()->json(['status' => 200, 'result' => $data]);
        }
        return view($view, ["adata" => $data]);
    }
    public function editStep1($caseID)
    {
        $case = Cases::with([
            'caseCompany',
            'disputant',
            'caseDisputant',
        ])->findOrFail($caseID);

        $company = $case->company;
        $caseCom = $case->caseCompany;
        $provinceID = $caseCom->log5_province_id ?? 0;
        $districtID = $caseCom->log5_district_id ?? 0;
        $communeID = $caseCom->log5_commune_id ?? 0;
        $villageID = $caseCom->log5_village_id ?? 0;
        $arrayDistrictID = $districtID > 0 ? arrayDistrict($provinceID, 1, "") : array();
        $arrayCommuneID = $communeID > 0 ? arrayCommune($districtID, 1, "") : array();
        $arrayVillageID = arrayVillage($communeID, 1, "");
        //        $arrayVillageID = $villageID > 0 ? arrayVillage($communeID, 1, ""): array();
        //        dd($provinceID, $districtID, $communeID, $villageID);
        //        dd($arrayDistrictID, $arrayCommuneID, $arrayVillageID);
        //        dd($arrayVillageID);
        $data = [
            'pagetitle' => "កែប្រែពាក្យបណ្ដឹងថ្មី (ទម្រង់ថ្មី)",
            'caseNumber' => $case->case_num_str,
            'arrCaseType' => arrCaseType(1),
            'arrSector' => myArraySector(1),
            'arrCompanyType' => arrayCompanyType(1),
            'arrProvince' => myArrProvince(0, 1, "សូមជ្រើសរើស", 1),
            'case' => $case,
            'company' => $company,
            'caseCom' => $caseCom,
            'provinceID' => $provinceID,
            'districtID' => $districtID,
            'communeID' => $communeID,
            'villageID' => $villageID,
            'arrayDistrictID' => $arrayDistrictID,
            'arrayCommuneID' => $arrayCommuneID,
            'arrayVillageID' => $arrayVillageID,
        ];
        $view = "case.case_step1";
        if (request("json_opt") == 1) { //if request from app
            return response()->json(['status' => 200, 'result' => $data]);
        }
        return view($view, ["adata" => $data]);
    }

    public function processStep2($caseID)
    {
        $case = Cases::with([
            'company',
            'disputant',
            'caseDisputant',
        ])->findOrFail($caseID);
        $company = $case->company;
        $data = [
            'pagetitle' => "ពាក្យបណ្ដឹងថ្មី (ទម្រង់ថ្មី)",
            'arrProvince' => myArrProvince(0, 1, "សូមជ្រើសរើស", 1),
            'arrNationality' => arrayNationality(1),
            'case' => $case,
            'company' => $company,
        ];
        $view = "case.case_step2";
        if (request("json_opt") == 1) { //if request from app
            return response()->json(['status' => 200, 'result' => $data]);
        }
        return view($view, ["adata" => $data]);
    }
    public function processStep3()
    {
        $data = [
            'pagetitle' => "បង្កើតពាក្យបណ្ដឹងថ្មី (ទម្រង់ថ្មី)",
            'arrCaseType' => arrCaseType(1),
            'arrSector' => myArraySector(1),
            'arrCompanyType' => arrayCompanyType(1),
            'arrProvince' => arrayProvince(1),
            'arrNationality' => arrayNationality(1),
            'arrObjectiveCase' => arrayObjectiveCase(1),
            'arrContractType' => [
                1 => "កំណត់",
                2 => "មិនកំណត់",
                3 => "សាកល្បង"
            ],
            'arrNightWork' => [
                1 => "ធ្លាប់ធ្វើ",
                2 => "ម្ដងម្កាល",
                3 => "មិនធ្លាប់ធ្វើ"
            ],
            'arrHolidayWeek' => [
                1 => "ឈប់តាមប្រកាស",
                2 => "ម្ដងម្កាល",
                3 => "មិនធ្លាប់បានឈប់"
            ],
            'arrHolidayYear' => [
                1 => "ឈប់តាមប្រកាស",
                2 => "ធ្លាប់បានឈប់ម្ដងម្កាល",
                3 => "មិនធ្លាប់បានឈប់"
            ],
            'arrOfficersInHand' => $this->getOfficersInHand()
        ];
        $view = "case.create_case_step3";
        if (request("json_opt") == 1) { //if request from app
            return response()->json(['status' => 200, 'result' => $data]);
        }
        return view($view, ["adata" => $data]);
    }

    private function generateCaseNumber(): string
    {
        $caseCount = Cases::count();
        return sprintf('%03d', $caseCount + 1);
    }

    private function getOfficersInHand(): array
    {
        $domainID = Auth::user()->officerRole->domain_id ?? 0;
        return arrayOfficerCaseInHandByDomain($domainID, 1);
    }

    public function storeStep1(Request $request)
    {
        //        dd($request->all());
        $caseID = $request->case_id ?? 0;
        $companyIDAuto = $request->company_id_auto;
        // ✅ 1. Validation
        $validator = Validator::make($request->all(), [
            'case_number' => 'required|string|min:3',
            'company_name_khmer' => 'required|string|min:5',
            'company_name_latin' => 'required|string|min:5',
            'sector_id' => 'required|integer|min:1',
            'company_type_id' => 'required|integer|min:1',
            'province_id' => 'required|integer|min:1',
            'district_id' => 'required|integer|min:1',
            'commune_id' => 'required|integer|min:1',
            'company_phone_number' => 'required|string|min:9',
        ], [
            //            'company_name_khmer.required' => 'សូមបញ្ចូលឈ្មោះសហគ្រាសជាភាសាខ្មែរ។',
            //            'company_name_latin.required' => 'សូមបញ្ចូលឈ្មោះសហគ្រាសជាភាសាឡាតាំង។',
            //            'sector_id.required' => 'សូមជ្រើសរើសវិស័យ។',
            //            'company_type_id.required' => 'សូមជ្រើសរើសប្រភេទសហគ្រាស។',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // ✅ 2. Initialize constants and base variables
        $dateCreated = myDateTime();
        $companyOption = (int) $request->company_option;
        $companyID = $request->company_id ?? 0;
        $companyNameKhmer = $request->company_name_khmer;

        // ✅ 3. Case suffix map
        $caseSuffixMap = [1 => '/វប', 2 => '/វស', 3 => '/វរ'];
        $caseSuffix = $caseSuffixMap[$request->case_type_id] ?? '';

        $caseNumStr = Num2Unicode($request->case_number)
            . "/" . Num2Unicode(date2Display($request->case_date_entry ?? now(), 'y'))
            . $caseSuffix;

        DB::beginTransaction();
        try {
            // ✅ 4. Build company data array (common fields)
            $companyData = $this->prepareCompanyData($request, $dateCreated);
            //            dd($companyData);

            // ✅ 5. Create or update company based on option
            if ($companyOption == 1) { // found in tbl_company_api (LACMS), then Insert in to tbl_company
                $companyData = array_merge($companyData, [
                    'company_id_lacms' => $request->company_id_lacms ?? 0,
                    'company_option' => $companyOption,
                ]);
            } elseif ($companyOption == 2) { // Exist in tbl_company, update info tbl_company
                Company::where('company_id', $companyID)->update($companyData);
            } else { // Insert Or Update New Company Info
                $companyData['company_option'] = 2;
            }

            if ($companyOption != 2) {
                $resultCompany = Company::updateOrCreate(
                    ['company_name_khmer' => $companyNameKhmer],
                    $companyData
                );
                $companyID = $resultCompany->id;
                Company::where('id', $companyID)->update(['company_id' => $companyID]);
            }

            // ✅ 6. Update or create the case (tbl_case)
            $case = Cases::updateOrCreate(
                ['id' => $caseID],
                [
                    'company_id' => $companyID,
                    "case_number" => $request->case_number,
                    'case_num_str' => $caseNumStr,
                    'company_option' => $companyOption,
                    "company_type_id" => $request->company_type_id,
                    "sector_id" => $request->sector_id,
                    'user_created' => Auth::id(),
                    'date_created' => $dateCreated,
                ]
            );
            $caseID = $case->id; // Get case ID

            // ✅ 7. Link case and company (tbl_case_company)
            //dd($request->case_id, $companyIDAuto);

            $shortCaseCompanyData = (
                ($request->case_id > 0 && $companyIDAuto == 0) ||
                ($request->case_id == 0 && $companyIDAuto == 0)
            );

            $caseCompanyData = $shortCaseCompanyData
                ? $this->prepareCaseCompanyShortData($request, $dateCreated)
                : $this->prepareCaseCompanyData($request, $dateCreated);
            $caseCompanyData['company_id'] = $companyID;
            //            dd($caseCompanyData);
            CaseCompany::updateOrCreate(
                ['case_id' => $caseID],
                $caseCompanyData
            );

            // ✅ 8. Find The Domain ID and Update domain_id in tbl_case_company
            $domainID = getCaseDomainControl($caseID);
            CaseCompany::where('case_id', $caseID)->update(['domain_id' => $domainID]);

            DB::commit();

            return redirect()
                ->route('cases.edit.step1', ['case_id' => $caseID])
                ->with('success', 'ព័ត៌មានសហគ្រាស គ្រឹះស្ថាន ត្រូវបានរក្សាទុកដោយជោគជ័យ!')
                ->withInput(['case_id' => $caseID]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('storeStep1 Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->with('error', 'មានបញ្ហា ក្នុងការរក្សាទុកព័ត៌មាន។ សូមព្យាយាមម្តងទៀត។');
        }
    }

    public function storeStep2(Request $request)
    {
        dd($request->all());
        $dateCreated = myDateTime();
        $caseID = $request->case_id ?? 0;
        $companyIDAuto = $request->company_id_auto;

        // ✅ Step 1️⃣: Validation rules
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|min:5',
            'gender'        => 'required|in:1,2',
            'nationality'   => 'required|integer',
            'dob'           => 'required|date_format:d-m-Y',
            'occupation'    => 'required|string|max:100',
            'phone_number'  => ['required', 'regex:/^(0)[1-9][0-9]{7,8}$/'],
            'pob_country_id' => 'required|integer',

            // Current Address
            'province'      => 'required|integer|min:1',
            'district'      => 'required|integer|min:1',
            'commune'       => 'required|integer|min:1',
            //            'village'       => 'nullable|integer',
            //            'addr_house_no' => 'nullable|string|max:50',
            //            'addr_street'   => 'nullable|string|max:100',
        ], [
            'name.required'          => 'សូមបញ្ចូលឈ្មោះអ្នកប្ដឹង',
            'gender.required'        => 'សូមជ្រើសរើសភេទ',
            'dob.required'           => 'សូមបញ្ចូលថ្ងៃខែឆ្នាំកំណើត',
            'occupation.required'    => 'សូមបញ្ចូលមុខងារ',
            'phone_number.required'  => 'សូមបញ្ចូលលេខទូរស័ព្ទ',
            'province.required'      => 'សូមជ្រើសរើស រាជធានី-ខេត្ត',
            'district.required'      => 'សូមជ្រើសរើស ក្រុង/ស្រុក/ខណ្ឌ',
            'commune.required'       => 'សូមជ្រើសរើស ឃុំ/សង្កាត់',
            'pob_province_id.required' => 'សូមជ្រើសរើស រាជធានី/ខេត្ត',
        ]);

        // ✅ Conditionally require pob_province_id only if POB Country is Cambodia (ID = 33)
        $validator->sometimes('pob_province_id', 'required|integer|min:1', function ($input) {
            return (int)$input->pob_country_id === 33;
        });

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // ✅ Step 2️⃣: Build Disputant data
        $isCambodian = (int)$request->pob_country_id === 33;

        $commonData = [
            "gender"         => $request->gender,
            "nationality"    => $request->nationality,
            "occupation"     => $request->occupation,
            "phone_number2"  => $request->phone_number2,
            "house_no"       => $request->addr_house_no,
            "street"         => $request->addr_street,
            "group_name"     => $request->group_name,
            "village"        => $request->village,
            "commune"        => $request->commune,
            "district"       => $request->district,
            "province"       => $request->province,
            "pob_commune_id" => $isCambodian ? ($request->pob_commune_id ?? 0) : 0,
            "pob_district_id" => $isCambodian ? ($request->pob_district_id ?? 0) : 0,
            "pob_province_id" => $isCambodian ? ($request->pob_province_id ?? 0) : 0,
            "pob_country_id" => $isCambodian ? 0 : $request->pob_country_id,
            "user_created"   => Auth::id(),
            "user_updated"   => Auth::id(),
            "date_created"   => $dateCreated,
            "date_updated"   => $dateCreated,
        ];

        if (!empty($request->id_number)) {
            $searchDisputant = ["id_number" => $request->id_number];
            $adataDisputant = array_merge($commonData, [
                "name"        => $request->name,
                "dob"         => date2DB($request->dob),
                "id_number"   => $request->id_number,
                "phone_number" => $request->phone_number,
            ]);
        } else {
            $searchDisputant = [
                "name"         => $request->name,
                "dob"          => date2DB($request->dob),
                "phone_number" => $request->phone_number,
            ];
            $adataDisputant = array_merge($commonData, []);
        }

        $resultDisputant = Disputant::updateOrCreate($searchDisputant, $adataDisputant);
        $disputantID = $resultDisputant->id ?? 0;

        // ✅ Step 3️⃣: Assign Disputant Type by case_type_id
        $disputantTypeMap = [
            1 => 1, // Labor dispute
            2 => 3, // Example type
            3 => 0, // Example type
        ];
        $disputantTypeID = $disputantTypeMap[$request->case_type_id] ?? 0;

        // ✅ Step 4️⃣: Create CaseDisputant record
        CaseDisputant::create([
            "case_id"         => $caseID,
            "disputant_id"    => $disputantID,
            "attendant_type_id" => $disputantTypeID,
            "house_no"        => $request->addr_house_no,
            "street"          => $request->addr_street,
            "village"         => $request->village,
            "commune"         => $request->commune,
            "district"        => $request->district,
            "province"        => $request->province,
            "phone_number"    => $request->phone_number,
            "phone_number2"   => $request->phone_number2,
            "occupation"      => $request->occupation,
            "user_created"    => Auth::id(),
            "date_created"    => $dateCreated,
        ]);

        // ✅ Optionally redirect or continue to next step
        return redirect()->route('cases.create.step3', ['case_id' => $caseID])
            ->with('success', 'Step 2 completed successfully.');
    }



    /**
     * Prepare company data from request
     */
    private function prepareCompanyData(Request $request, string $dateCreated): array
    {
        return [
            'company_id_lacms' => $request->company_id_lacms ?? 0,
            'company_name_khmer' => $request->company_name_khmer,
            'company_name_latin' => $request->company_name_latin,
            'nssf_number' => $request->nssf_number,
            'company_tin' => $request->company_tin,
            'company_register_number' => $request->company_register_number,
            'registration_date' => $request->registration_date,
            'company_type_id' => $request->company_type_id,
            'first_business_act' => $request->first_business_act,
            'article_of_company' => $request->article_of_company,
            'sector_id' => $request->sector_id,
            "csic_1" => $request->csic_1,
            "csic_2" => $request->csic_2,
            "csic_3" => $request->csic_3,
            "csic_4" => $request->csic_4,
            "csic_5" => $request->csic_5,
            "business_activity" => $request->business_activity,
            "business_activity1" => $request->business_activity1,
            "business_activity2" => $request->business_activity2,
            "business_activity3" => $request->business_activity3,
            "business_activity4" => $request->business_activity4,
            "single_id" => $request->single_id,
            "operation_status" => $request->operation_status,
            'building_no' => $request->building_no,
            'street_no' => $request->street_no,
            'village_id' => $request->village_id,
            'commune_id' => $request->commune_id,
            'district_id' => $request->district_id,
            'province_id' => $request->province_id,
            'company_phone_number' => $request->company_phone_number,
            'company_phone_number2' => $request->company_phone_number2,
            'user_created' => Auth::id(),
            'user_updated' => Auth::id(),
            'date_created' => $dateCreated,
            'date_updated' => $dateCreated,
        ];
    }
    private function prepareCompanyShortData(Request $request, string $dateCreated): array
    {
        return [
            'company_name_khmer' => $request->company_name_khmer,
            'company_name_latin' => $request->company_name_latin,
            'company_type_id' => $request->company_type_id,
            'sector_id' => $request->sector_id,
            'building_no' => $request->building_no,
            'street_no' => $request->street_no,
            'village_id' => $request->village_id,
            'commune_id' => $request->commune_id,
            'district_id' => $request->district_id,
            'province_id' => $request->province_id,
            'company_phone_number' => $request->company_phone_number,
            'company_phone_number2' => $request->company_phone_number2,
            'user_created' => Auth::id(),
            'user_updated' => Auth::id(),
            'date_created' => $dateCreated,
            'date_updated' => $dateCreated,
        ];
    }

    /**
     * Prepare case-company data
     */
    private function prepareCaseCompanyData(Request $request, string $dateCreated): array
    {
        return [
            'log5_first_business_act' => $request->first_business_act,
            'log5_article_of_company' => $request->article_of_company,
            'log5_company_type_id' => $request->company_type_id,
            'log5_sector_id' => $request->sector_id,
            'log5_csic_1' => $request->csic_1,
            'log5_csic_2' => $request->csic_2,
            'log5_csic_3' => $request->csic_3,
            'log5_csic_4' => $request->csic_4,
            'log5_csic_5' => $request->csic_5,
            'log5_business_activity' => $request->business_activity,
            'log5_business_activity1' => $request->business_activity1,
            'log5_business_activity2' => $request->business_activity2,
            'log5_business_activity3' => $request->business_activity3,
            'log5_business_activity4' => $request->business_activity4,
            'log5_company_phone_number' => $request->company_phone_number,
            'log5_company_phone_number2' => $request->company_phone_number2,
            'log5_building_no' => $request->building_no,
            'log5_street_no' => $request->street_no,
            'log5_village_id' => $request->village_id,
            'log5_commune_id' => $request->commune_id,
            'log5_district_id' => $request->district_id,
            'log5_province_id' => $request->province_id,
            'user_created' => Auth::id(),
            'date_created' => $dateCreated,
        ];
    }
    private function prepareCaseCompanyShortData(Request $request, string $dateCreated): array
    {
        return [
            'log5_company_type_id' => $request->company_type_id,
            'log5_sector_id' => $request->sector_id,
            'log5_company_phone_number' => $request->company_phone_number,
            'log5_company_phone_number2' => $request->company_phone_number2,
            'log5_building_no' => $request->building_no,
            'log5_street_no' => $request->street_no,
            'log5_village_id' => $request->village_id,
            'log5_commune_id' => $request->commune_id,
            'log5_district_id' => $request->district_id,
            'log5_province_id' => $request->province_id,
            'user_created' => Auth::id(),
            'date_created' => $dateCreated,
        ];
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, TelegramService $telegramService)
    {
        //        dd($request->all());

        $dateCreated = myDateTime();
        $companyID = $request->company_id;
        $company_option = $request->company_option;
        $company_name_khmer = $request->company_name_khmer;

        $searchCompany = ["company_name_khmer" => $company_name_khmer];

        if ($request->case_type_id == 1) {
            $caseSuffix = "/វប";
        } elseif ($request->case_type_id == 2) {
            $caseSuffix = "/វស";
        } elseif ($request->case_type_id == 3) {
            $caseSuffix = "/វរ";
        }

        $caseNumStr = Num2Unicode($request->case_number) . "/" . Num2Unicode(date2Display($request->case_date_entry, 'y')) . $caseSuffix;

        $validator = Validator::make(
            $request->all(),
            [
                'dob' => 'required|date_format:d-m-Y', // Validates that the input is a valid date
            ],
            [
                'dob.date_format' => 'ថ្ងៃខែឆ្នាំកំណើតមិនត្រឹមត្រូវ'
            ]
        );
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            /**
             * A.Insert/Update Disputant
             * B.Insert Case
             * C.Insert/Update Company
             * 1.Check Exist Company (company_option=1) or New Company (company_option=2) IN tbl_company_api
             * 2.Exists Company
             * 2.1.
             * 3.New Company
             * 3.1
             * D.Update Remain Case Data
             * E.Insert new Record to tbl_case_company
             * F.Insert new Record to tbl_case_disputant
             * G: Find the Domain ID by Case ID and Update domain_id in tbl_case_company */

            /** ===============BlogA: Create or Update Disputant (Employee) ======================== */
            if (!empty($request->id_number)) {
                $searchDisputant = ["id_number" => $request->id_number];
                $adataDisputant = [
                    "name" => $request->name,
                    "gender" => $request->gender,
                    "dob" => date2DB($request->dob),
                    "nationality" => $request->nationality,
                    //"id_number" => $request->id_number,
                    "phone_number" => $request->phone_number,
                    "phone_number2" => $request->phone_number2,
                    'occupation' => $request->occupation,

                    "house_no" => $request->addr_house_no,
                    "street" => $request->addr_street,
                    "group_name" => $request->group_name,
                    "village" => $request->village,
                    "commune" => $request->commune,
                    "district" => $request->district,
                    "province" => $request->province,

                    "pob_commune_id" => $request->nationality == 33 ? $request->pob_commune_id ?? 0 : 0,
                    "pob_district_id" => $request->nationality == 33 ? $request->pob_district_id ?? 0 : 0,
                    "pob_province_id" => $request->nationality == 33 ? $request->pob_province_id ?? 0 : 0,
                    "pob_country_id" => $request->nationality == 33 ? 0 : $request->pob_country_id,

                    "user_created" => Auth::user()->id,
                    "user_updated" => Auth::user()->id,
                    "date_created" =>  $dateCreated,
                    "date_updated" =>  $dateCreated,
                ];
            } else {
                $searchDisputant = [
                    "name" => $request->name,
                    "dob" => date2DB($request->dob),
                    "phone_number" => $request->phone_number,
                ];
                $adataDisputant = [
                    //                    "name" => $request->name,
                    "gender" => $request->gender,
                    //                    "dob" => date2DB($request->dob),
                    "nationality" => $request->nationality,
                    //"id_number" => $request->id_number,
                    "phone_number2" => $request->phone_number2,
                    'occupation' => $request->occupation,
                    "house_no" => $request->addr_house_no,
                    "street" => $request->addr_street,
                    "group_name" => $request->group_name,
                    "village" => $request->village,
                    "commune" => $request->commune,
                    "district" => $request->district,
                    "province" => $request->province,

                    "pob_commune_id" => $request->nationality == 33 ? $request->pob_commune_id ?? 0 : 0,
                    "pob_district_id" => $request->nationality == 33 ? $request->pob_district_id ?? 0 : 0,
                    "pob_province_id" => $request->nationality == 33 ? $request->pob_province_id ?? 0 : 0,
                    "pob_country_id" => $request->nationality == 33 ? 0 : $request->pob_country_id,

                    "user_created" => Auth::user()->id,
                    "user_updated" => Auth::user()->id,
                    "date_created" =>  $dateCreated,
                    "date_updated" =>  $dateCreated,
                ];
            }
            $resultDisputant = Disputant::updateOrCreate($searchDisputant, $adataDisputant);
            $disputantID = !empty($resultDisputant) ? $resultDisputant->id : 0; //get DisputantID

            /** ===============BlogB: Create New Case ======================== */
            $adata = [
                "case_number" => $request->case_number,
                "case_num_str" => $caseNumStr,
                "case_type_id" => $request->case_type_id,
                "disputant_id" => $disputantID,
                //"company_id" => $request->case_type_id,
                //"company_option" => $request->case_type_id,
                "company_type_id" => $request->company_type_id,
                "sector_id" => $request->sector_id,
                "case_objective_id" => $request->case_objective_id,
                "case_ojective_other" => $request->case_ojective_other,
                "terminated_contract_date" => date2DB($request->terminated_contract_date),
                "terminated_contract_time" => $request->terminated_contract_time,
                "case_objective_des" => $request->case_objective_des,
                "disputant_sdate_work" => date2DB($request->disputant_sdate_work),
                "disputant_contract_type" => $request->disputant_contract_type,
                "disputant_work_hour_day" => $request->disputant_work_hour_day,
                "disputant_work_hour_week" => $request->disputant_work_hour_week,
                "disputant_salary" => $request->disputant_salary,
                "disputant_night_work" => $request->disputant_night_work,
                "disputant_holiday_week" => $request->disputant_holiday_week,
                "disputant_holiday_year" => $request->disputant_holiday_year,
                "case_first_reason" => $request->case_first_reason,
                "disputant_request" => $request->disputant_request,
                "case_date" => date2DB($request->case_date),
                "case_date_entry" => date2DB($request->case_date_entry),

                "user_created" => Auth::user()->id,
                "date_created" =>  $dateCreated,
            ];

            $resultCase = Cases::create($adata);
            $caseYear = date2Display($resultCase->case_date, "Y");
            $caseID = $resultCase->id;//get caseID

            //            dd($company_option);
            /** ===============BlogC: Create or Update Company ======================== */
            if ($company_option == 1) { // found in tbl_company_api (LACMS), then Insert in to tbl_company
                $adataCompanyInside = [
                    "company_id_lacms" => $companyID,
                    "company_option" => 1,
                    //"company_name_khmer" => $company_name_khmer,
                    "company_name_latin" => $request->company_name_latin,
                    "nssf_number" => $request->nssf_number,
                    "company_tin" => $request->company_tin,
                    "company_register_number" => $request->company_register_number,
                    "registration_date" => $request->registration_date,
                    "company_type_id" => $request->company_type_id,
                    "first_business_act" => $request->first_business_act,
                    "article_of_company" => $request->article_of_company,
                    "sector_id" => $request->sector_id,
                    "csic_1" => $request->csic_1,
                    "csic_2" => $request->csic_2,
                    "csic_3" => $request->csic_3,
                    "csic_4" => $request->csic_4,
                    "csic_5" => $request->csic_5,
                    "business_activity" => $request->business_activity,
                    "business_activity1" => $request->business_activity1,
                    "business_activity2" => $request->business_activity2,
                    "business_activity3" => $request->business_activity3,
                    "business_activity4" => $request->business_activity4,
                    "single_id" => $request->single_id,
                    "operation_status" => $request->operation_status,
                    "building_no" => $request->building_no,
                    "street_no" => $request->street_no,
                    "village_id" => $request->village_id,
                    "commune_id" => $request->commune_id,
                    "district_id" => $request->district_id,
                    "province_id" => $request->province_id,
                    "company_phone_number" => $request->company_phone_number,
                    "company_phone_number2" => $request->company_phone_number2,

                    "user_created" => Auth::user()->id,
                    "user_updated" => Auth::user()->id,
                    "date_created" =>  $dateCreated,
                    "date_updated" =>  $dateCreated,
                ];

                $resultCompany = Company::updateOrCreate($searchCompany, $adataCompanyInside);
                $companyID = !empty($resultCompany) ? $resultCompany->id : 0;
                Company::where("id", $companyID)->update(["company_id" => $companyID]); //Update company_id
                //            CompanyApi::where("company_id", $company_id_lacms)->update(["ready_add_2case" => 1]);
            } elseif ($company_option == 2) { // found in tbl_company, update info tbl_company
                $adataCompanyInside = [
                    "company_name_khmer" => $company_name_khmer,
                    "company_name_latin" => $request->company_name_latin,
                    "nssf_number" => $request->nssf_number,
                    "company_tin" => $request->company_tin,
                    "company_register_number" => $request->company_register_number,
                    "registration_date" => $request->registration_date,
                    "company_type_id" => $request->company_type_id,
                    "first_business_act" => $request->first_business_act,
                    "article_of_company" => $request->article_of_company,
                    "sector_id" => $request->sector_id,
                    "csic_1" => $request->csic_1,
                    "csic_2" => $request->csic_2,
                    "csic_3" => $request->csic_3,
                    "csic_4" => $request->csic_4,
                    "csic_5" => $request->csic_5,
                    "business_activity" => $request->business_activity,
                    "business_activity1" => $request->business_activity1,
                    "business_activity2" => $request->business_activity2,
                    "business_activity3" => $request->business_activity3,
                    "business_activity4" => $request->business_activity4,
                    "single_id" => $request->single_id,
                    "operation_status" => $request->operation_status,

                    "street_no" => $request->street_no,
                    "village_id" => $request->village_id,
                    "commune_id" => $request->commune_id,
                    "district_id" => $request->district_id,
                    "province_id" => $request->province_id,
                    "company_phone_number" => $request->company_phone_number,

                    "user_updated" => Auth::user()->id,
                    "date_updated" =>  $dateCreated,
                ];
                Company::where("company_id", $companyID)->update($adataCompanyInside); //update company info
                //dd("Update");
            } else { // Insert New Company
                $company_option = 2;
                $adataCompanyInside = [
                    "company_option" => 2, // from lacms or inside this system
                    //                    "company_name_khmer" => $company_name_khmer,
                    "company_name_latin" => $request->company_name_latin,
                    "sector_id" => $request->sector_id,
                    "building_no" => $request->building_no,
                    "street_no" => $request->street_no,
                    "village_id" => $request->village_id,
                    "commune_id" => $request->commune_id,
                    "district_id" => $request->district_id,
                    "province_id" => $request->province_id,
                    "company_phone_number" => $request->company_phone_number,

                    "user_created" => Auth::user()->id,
                    "user_updated" => Auth::user()->id,
                    "date_created" =>  $dateCreated,
                    "date_updated" =>  $dateCreated,
                ];
                $resultCompany = Company::updateOrCreate($searchCompany, $adataCompanyInside);
                $companyID = !empty($resultCompany) ? $resultCompany->id : 0;
                Company::where("id", $companyID)->update(["company_id" => $companyID]); //Update company_id
            }
            /** ===============BlogD: Update Remain Case Data and Upload File ======================== */
            $pathToUpload = pathToUploadFile("case_doc/form1/" . $caseYear . "/");
            $caseFile = myUploadFileOnly($request, $pathToUpload, "case_file", $caseID, "case_file");
            //            $case_file = uploadFileOnly($request, $path_to_upload, "case_file", $case_id);
            Cases::where("id", $caseID)->update([
                "company_id" => $companyID,
                "company_option" => $company_option,
                "case_file" => $caseFile
            ]);

            /** ===============BlogE: Insert new Record to tbl_case_company ======================== */
            $adataCaseCompany = [
                "case_id" => $caseID,
                "company_id" => $companyID,
                "log5_company_phone_number" => $request->company_phone_number,
                "log5_company_phone_number2" => $request->company_phone_number2,
                "log5_building_no" => $request->building_no,
                "log5_street_no" => $request->street_no,
                "log5_village_id" => $request->village_id,
                "log5_commune_id" => $request->commune_id,
                "log5_district_id" => $request->district_id,
                "log5_province_id" => $request->province_id,

                "user_created" => Auth::user()->id,
                "date_created" =>  $dateCreated,
            ];
            CaseCompany::create($adataCaseCompany);

            /** =============Find The Domain ID and Update domain_id in tbl_case_company */
            $domainID = getCaseDomainControl($caseID);
            CaseCompany::where('case_id', $caseID)->update(['domain_id' => $domainID]);

            /** ===============BlogG: Insert new Record to tbl_case_disputant ======================== */
            if ($request->case_type_id == 1) {
                $disputantTypeID = 1;
            }
            if ($request->case_type_id == 2) { // not yet do
                $disputantTypeID = 3;
            }
            if ($request->case_type_id == 3) { // not yet do
                $disputantTypeID = 0;
            }
            $adataCaseDisputant = [
                "case_id" => $caseID,
                "disputant_id" => $disputantID,
                "attendant_type_id" => $disputantTypeID,
                "house_no" => $request->addr_house_no,
                "street" => $request->addr_street,
                "village" => $request->village,
                "commune" => $request->commune,
                "district" => $request->district,
                "province" => $request->province,
                "phone_number" => $request->phone_number,
                "phone_number2" => $request->phone_number2,
                "occupation" => $request->occupation,

                "user_created" => Auth::user()->id,
                "date_created" =>  $dateCreated,
            ];
            CaseDisputant::create($adataCaseDisputant);

            /** ===============BlogH: Assign Officer ======================== */
            $this->updateOrCreateOfficer($caseID, $request->officer_id, 6);
            $this->updateOrCreateOfficer($caseID, $request->officer_id8, 8);

            /**=================== Telegram Bot ============================  */
            $todayCases = DB::table('tbl_case')
                ->whereDate('date_created', Carbon::today())
                ->count();
            $totalCases = DB::table('tbl_case')->where('case_type_id', 1)->count();
            $currentCase = Cases::where("id", $caseID)->first();

            $msg2Telegram = "<b>" . "📢 ពាក្យបណ្តឹងថ្មី !!!" . "</b>" . "\n"
                . "===========================" . "\n"
                . "📌 សំណុំរឿងលេខ៖ " . "<b>" . ($currentCase->case_num_str ?? 'N/A') . "</b>\n\n"
                . "👤 អ្នកបញ្ចូលពាក្យបណ្តឹង៖ " . "<b>" . ($currentCase->entryUser->k_fullname ?? 'N/A') . "</b>" . "\n\n"
                . "📌 ប្រភេទបណ្តឹង៖ " . "<b>" . ($currentCase->caseType->case_type_name ?? 'N/A') . "</b>" . "\n\n"
                . "👤 ដើមបណ្តឹង៖ " . "<b>" . ($currentCase->disputant->name ?? 'N/A') . "</b>" . "\n\n"
                . "👤 ចុងបណ្តឹង៖ " . "<b>" . ($currentCase->company->company_name_khmer ?? 'N/A') . "</b>" . "\n\n"
                . "🧨 អង្គហេតុនៃវិវាទ៖ " . "<b>" . (Str::limit($currentCase->case_objective_des) ?? 'N/A') . "</b>" . "\n\n"
                . "🎈 មូលហេតុចម្បងនៃវិវាទ៖ " . "<b>" . (Str::limit($currentCase->case_first_reason) ?? 'N/A') . "</b>" . "\n\n"
                . "🙏🏻 សំណូមពររបស់អ្នកប្ដឹង៖ " . "<b>" . (Str::limit($currentCase->disputant_request) ?? 'N/A') . "</b>" . "\n\n"
                . "📆 កាលបរិច្ឆេទបណ្តឹង៖ " . "<b>" . (date2Display($currentCase->case_date) ?? 'N/A') . "</b>" . "\n\n"
                . "🗓️ កាលបរិច្ឆេទប្តឹងទៅអធិការការងារ៖ " . "<b>" . (date2Display($currentCase->case_date_entry) ?? 'N/A') . "</b>" . "\n\n"
                . "👤 អ្នកផ្សះផ្សា៖ " . "<b>" . ($currentCase->latestCaseOfficer->officer->officer_name_khmer ?? 'N/A') . "</b>" . "\n\n"
                . "👤 អ្នកកត់ត្រា៖ " . "<b>" . ($currentCase->caseNoter->officer->officer_name_khmer ?? 'N/A') . "</b>" . "\n"
                . "===========================" . "\n"
                . "#️⃣ សំណុំរឿងដែលបានបញ្ចូលថ្ងៃនេះចំនួន៖ " . "<b>" . number2KhmerNumber($todayCases) . "</b>" . " បណ្តឹង\n\n"
                . "#️⃣ សំណុំរឿងសរុបទាំងអស់ចំនួន៖ " . "<b>" . number2KhmerNumber($totalCases) . "</b>" . " បណ្តឹង\n\n";

            $telegramService->sendMessage($msg2Telegram);

            DB::commit();

            //            return redirect("cases");
            return redirect("cases")->with("message", sweetalert()->addSuccess("រក្សាទុកជោគជ័យ"));
        } catch (\Exception $e) {
            DB::rollback();

            // Log the actual error for developers
            Log::error('Case creation failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
            ]);

            if (app()->environment('local')) {
                dd($e->getMessage());
            }
            return back()->with("message", sweetalert()->addWarning("បរាជ័យ"));
        }
    }


    /** Closing Case */
    public function closingCase(Request $request, TelegramService $telegramService)
    {
        //        dd($request->all());
        $caseID = $request->case_id;
        $caseYear = $request->case_year;
        $companyID = $request->company_id;

        $validator = Validator::make(
            $request->all(),
            [
                'case_closed_date' => 'required|date_format:d-m-Y', // Validates that the input is a valid date
            ],
            [
                'case_closed_date.date_format' => 'ថ្ងៃខែឆ្នាំកំណើតមិនត្រឹមត្រូវ'
            ]
        );
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {

            /** =============== Upload All Reference Files AND Update Case table: tbl_case ====================== */
            $path_to_upload = pathToUploadFile("case_doc/closed/" . $caseYear . "/");

            //Case Close File
            //            $case_file_new = uploadFileOnly($request, $path_to_upload, "case_closed_file", $case_id);
            $case_file_new = myUploadFileOnly($request, $path_to_upload, "case_closed_file", $caseID, "closed_file");
            $case_file = !empty($case_file_new) ? $case_file_new : $request->case_closed_file_old;

            //Case Close Plaintiff File
            //            $plaintiff_file_new = uploadFileOnly($request, $path_to_upload, "case_closed_plaintiff_file", $case_id, "plaintiff");
            $plaintiff_file_new = myUploadFileOnly($request, $path_to_upload, "case_closed_plaintiff_file", $caseID, "plaintiff");
            $plaintiff_file = !empty($plaintiff_file_new) ? $plaintiff_file_new : $request->plaintiff_file_old;

            //Case Close Defendant File
            //            $defendant_file_new = uploadFileOnly($request, $path_to_upload, "case_closed_defendant_file", $case_id, "defendant");
            $defendant_file_new = myUploadFileOnly($request, $path_to_upload, "case_closed_defendant_file", $caseID, "defendant");
            $defendant_file = !empty($defendant_file_new) ? $defendant_file_new : $request->defendant_file_old;

            //            dd($case_file);
            $adata = [
                "case_closed" => $request->case_closed,
                "case_closed_date" => date2DB($request->case_closed_date),
                "case_closed_step_id" => $request->case_closed_step_id,
                "case_cause_id" => $request->case_cause_id,
                "case_cause_other" => $request->case_cause_id == 11 ? $request->case_cause_other : "",
                "case_solution_id" => $request->case_solution_id,
                "case_closed_description" => $request->case_closed_description,
                "case_closed_result" => $request->case_closed_result,
                "case_closed_file" => $case_file,
                "case_closed_plaintiff_file" => $plaintiff_file,
                "case_closed_defendant_file" => $defendant_file,
                "user_updated" => Auth::user()->id,
                "date_updated" =>  myDateTime(),
            ];
            //            dd($adata);
            $result = Cases::where("id", $caseID)->update($adata); // return 1 if update success
            //            dd($result);

            $arrCaseCompanyCond = ['company_id' => $companyID, 'case_id' => $caseID];
            $caseCompanyData = [
                //                'log5_business_activity' => $request->business_activity,
                'log5_csic_1' => $request->csic_1,
                'log5_csic_2' => $request->csic_2,
                'log5_csic_3' => $request->csic_3,
                'log5_csic_4' => $request->csic_4,
                'log5_csic_5' => $request->csic_5,
                'user_updated' => Auth::user()->id,
                'date_updated' => myDate(),

            ];
            //        dd($caseCompanyData);
            CaseCompany::where($arrCaseCompanyCond)->update($caseCompanyData);

            /**=================== Telegram Bot ============================  */
            //            $caseCloseMsg = "បានបិទបញ្ចប់ពាក្យបណ្តឹង";
            $todayCases = DB::table('tbl_case')
                ->whereDate('date_created', Carbon::today())
                ->count();
            $totalCases = DB::table('tbl_case')->where('case_type_id', 1)->count();
            $currentCase = Cases::where("id", $caseID)->first();
            $caseClosedResult = $currentCase->case_closed_result;
            if ($caseClosedResult == 1) {
                $caseClosedResultMsg = "សះជា";
            } elseif ($caseClosedResult == 2) {
                $caseClosedResultMsg = "មិនសះជា";
            } elseif ($caseClosedResult == 3) {
                $caseClosedResultMsg = "មោឃៈ";
            }
            $caseStep = $currentCase->caseClosedStep->step ?? "N/A";
            $caseCause = $currentCase->caseClosedCause->cause_name ?? "N/A";
            $caseSolution = $currentCase->caseClosedSolution->solution_name ?? "N/A";

            $msg2Telegram = "<b>" . "📢 បិទបញ្ចប់ពាក្យបណ្តឹង !!!" . "</b>" . "\n"
                . "===========================" . "\n"
                . "📌 សំណុំរឿងលេខ៖ " . "<b>" . ($currentCase->case_num_str ?? 'N/A') . "</b>\n\n"
                //                . "📌 បច្ចុប្បន្នភាពពាក្យបណ្តឹង៖ " . "<b>" . ($caseCloseMsg ?? 'N/A') . "</b>\n\n"
                . "👤 អ្នកបញ្ចូលពាក្យបណ្តឹង៖ " . "<b>" . ($currentCase->entryUser->k_fullname ?? 'N/A') . "</b>" . "\n\n"
                . "👤 ធ្វើបច្ចុប្បន្នភាពដោយ៖ " . "<b>" . (Auth::user()->k_fullname ?? 'N/A') . "</b>" . "\n\n"
                . "📌 ប្រភេទបណ្តឹង៖ " . "<b>" . ($currentCase->caseType->case_type_name ?? 'N/A') . "</b>" . "\n\n"
                . "👤 ដើមបណ្តឹង៖ " . "<b>" . ($currentCase->disputant->name ?? 'N/A') . "</b>" . "\n\n"
                . "👤 ចុងបណ្តឹង៖ " . "<b>" . ($currentCase->company->company_name_khmer ?? 'N/A') . "</b>" . "\n\n"
                . "📌 បិទបញ្ចប់នៅដំណាក់កាល៖ " . "<b>" . $caseStep . "</b>" . "\n\n"
                . "🧨 មូលហេតុសំខាន់នៃវិវាទ៖ " . "<b>" . $caseCause . "</b>" . "\n\n"
                . "🧨 ដំណោះស្រាយនៃវិវាទ៖ " . "<b>" . $caseSolution . "</b>" . "\n\n"
                . "🎈 បរិយាយនៃវិវាទ៖ " . "<b>" . (Str::limit($currentCase->case_closed_description) ?? 'N/A') . "</b>" . "\n\n"
                . "📌 លទ្ធផល៖ " . "<b>" . ($caseClosedResultMsg ?? 'N/A') . "</b>\n\n"
                . "📆 កាលបរិច្ឆេទបណ្តឹង៖ " . "<b>" . (date2Display($currentCase->case_date) ?? 'N/A') . "</b>" . "\n\n"
                . "🗓️ កាលបរិច្ឆេទប្តឹងទៅអធិការការងារ៖ " . "<b>" . (date2Display($currentCase->case_date_entry) ?? 'N/A') . "</b>" . "\n\n"
                . "🗓️ កាលបរិច្ឆេទបិទបញ្ចប់សំណុំរឿង៖ " . "<b>" . (date2Display($currentCase->case_closed_date) ?? 'N/A') . "</b>" . "\n\n"
                . "👤 អ្នកផ្សះផ្សា៖ " . "<b>" . ($currentCase->latestCaseOfficer->officer->officer_name_khmer ?? 'N/A') . "</b>" . "\n\n"
                . "👤 អ្នកកត់ត្រា៖ " . "<b>" . ($currentCase->caseNoter->officer->officer_name_khmer ?? 'N/A') . "</b>" . "\n"
                . "===========================" . "\n"
                . "#️⃣ សំណុំរឿងដែលបានបញ្ចូលថ្ងៃនេះចំនួន៖ " . "<b>" . number2KhmerNumber($todayCases) . "</b>" . " បណ្តឹង\n\n"
                . "#️⃣ សំណុំរឿងសរុបទាំងអស់ចំនួន៖ " . "<b>" . number2KhmerNumber($totalCases) . "</b>" . " បណ្តឹង\n\n";

            // Push message to Telegram
            $telegramService->sendMessage($msg2Telegram);

            DB::commit();
            if (request("json_opt") == 1) { //if request from app
                //$data = getDataForAllMenu($inspection_id, $this->menu);
                //return response()->json(['status' => 200, 'message' => 'success', 'data'=> $data]);
            }
            //            return back()->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
            return redirect("cases/" . request('case_id'))->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
        } catch (\Exception $e) {
            DB::rollback();

            // Log the actual error for developers
            Log::error('Case creation failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
            ]);

            if (app()->environment('local')) {
                dd($e->getMessage());
            }
            return back()->with("message", sweetalert()->addWarning("បរាជ័យ"));
        }
    }

    /**
     * Closing Case Form
     */
    public function closeCaseForm(string $caseID)
    {
        $data['pagetitle'] = "បិទបញ្ចប់សំណុំរឿង";
        $case = Cases::where("id", $caseID)->first();
        $data['case'] = $case;
        $company = Company::where('company_id', $case->company_id)->first();
        $data['company'] = $company;
        $data['caseCom'] = $case->caseCompany;
        $data['companyAPI'] = null;
        if (!empty($company->company_name_latin)) {
            $data['companyAPI'] = CompanyApi::where('company_name_latin', $company->company_name_latin)->first();
        }
        //        dd($data['companyAPI']);
        $view = "case.create_case_closed";
        if (request("json_opt") == 1) { //if request from app
            return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
        }
        return view($view, ["adata" => $data]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        if (Request()->in_out_domain) {
            DB::table('tbl_case')
                ->where('id', $id)
                ->update(['in_out_domain' => Request()->in_out_domain]);
            return redirect("cases/" . $id)->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
        }

        // Eager load all related models to reduce N+1
        $case = Cases::with([
            'caseType',
            'disputant',
            'caseDisputant',
            'company',
            'caseCompany',
            'caseCompany.domainCommune',
            'caseCompany.domainDistrict',
            'caseCompany.domainProvince',
            'caseDomain',
            'entryUser.officerRole',
            'invitationDisputant',
            'invitationCompany',
            'invitationCompany.invitationType',
            'log34',
            //            'log34.detail34',
            'log5',
            //            'log5.detail5',
            'invitationForConcilation',
            'invitationForConcilationEmployee',
            'invitationForConcilationCompany',
            'log6',
        ])->findOrFail($id);

        $user = auth()->user();
        $userID = $user->id ?? 0;
        $userOfficerID = $user->officer_id ?? 0;
        $kCategory = (int) $user->k_category ?? 0;
        $entryUserID = $case->user_created ?? 0;
        $officerRoleID = getOfficerRoleID($userOfficerID);

        // Preload case officer IDs once
        $caseOfficerIDs = CaseOfficer::where('case_id', $id)->pluck('officer_id')->toArray();

        // Precompute domain checks
        $caseDomain = $case->caseDomain->domain_id ?? null;
        $domainOfficer = $case->entryUser->officerRole->domain_id ?? null;

        //        $lastOfficer = getLastOfficer($id, 6); // Case Officer
        //        $lastOfficerInfo = $lastOfficer->officer;
        //        $lastNoter = getLastOfficer($id, 8); // Case Noter

        $attendants = getLastAttendants($id);
        $lastOfficer = $attendants[6] ?? 0; // Case Officer
        $lastOfficerInfo = $lastOfficer->officer;
        $lastNoter = $attendants[8] ?? 0; // Case Noter

        // Precompute allow access
        $allowAccess = allowAccess($userID, $kCategory, $entryUserID, $officerRoleID);

        //Get Case Domain ID
        $domainID = $case->caseDomain->domain_id ?? getCaseDomainControl($id);

        $caseOfficerList = arrayOfficerCaseInHandByDomainCtrlOptimized($domainID);

        // Prepare Invitation Employee Data
        $invEmployee = showInvitationEmployee($case);

        // Prepare Log34 Data
        $log34Data = showCaseLog34($case);

        // Prepare Invitation Company Data
        $invCompany = showInvitationCompany($case);

        //Prepare Log5 Data
        $log5Data = showCaseLog5($case);

        //Prepare Invitation For Both Employee & Company
        $invitationBoth = showInvitationBoth($case);

        //Prepare Log6 Data
        $log6Data = showCaseLog6($case);

        $data = [
            'pagetitle' => "ដំណើរការបណ្ដឹង",
            'case' => $case,
            'caseDomain' => $caseDomain,
            'domainOfficer' => $domainOfficer,
            'userOfficerID' => $userOfficerID,
            'entryUserID' => $entryUserID,
            'caseOfficerIDs' => $caseOfficerIDs,
            'lastOfficer' => $lastOfficer,
            'lastOfficerInfo' => $lastOfficerInfo,
            'lastNoter' => $lastNoter,
            'allowAccess' => $allowAccess,
            'domainID' => $domainID,
            'caseOfficerList' => $caseOfficerList,
            'invEmployeeData' => $invEmployee,
            'log34Data' => $log34Data,
            'invCompanyData' => $invCompany,
            'log5Data' => $log5Data,
            'invitationBoth' => $invitationBoth,
            'log6Data' => $log6Data,

        ];

        $view = "case.show_case";
        if (request("json_opt") == 1) { //if request from app
            return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
        }
        return view($view, ["adata" => $data]);
    }

    public function ajaxDeleteFile(Request $request)
    {
        $path = str_replace('__', '/', $request->path); // decode path
        $filePath = public_path($path . '/' . $request->file_name);

        if (File::exists($filePath)) {
            File::delete($filePath);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }



    // public function listStep()
    // {
    //     return view('case.show_case');
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //        $case = Cases::with(['caseCompany','disputant', 'caseDisputant'])->where("id", $id)->first();\
        $domainID = Auth::user()->officerRole->domain_id ?? 0;
        // Eager load all necessary relationships
        $case = Cases::with([
            'caseCompany',
            'disputant',
            'caseDisputant',
            //            'caseCompany.province', 'caseCompany.district', 'caseCompany.commune', 'caseCompany.village',
            //            'caseDisputant.province', 'caseDisputant.district', 'caseDisputant.commune', 'caseDisputant.village',
        ])->findOrFail($id);


        $arrOfficers = arrayOfficerCaseInHandByDomain($domainID);
        $lastOfficers = getLastOfficerID($id, 6);
        $arrNoters = arrayOfficerCaseInHandByDomain(0, 1);
        $lastNoter = getLastOfficerID($id, 8);


        $caseCompany = $case->caseCompany;
        $disputant = $case->disputant;
        $caseDisputant = $case->caseDisputant;
        $provinceID = $caseCompany->log5_province_id;
        $districtID = $caseCompany->log5_district_id;
        $communeID = $caseCompany->log5_commune_id;
        $villageID = $caseCompany->log5_village_id;
        $arrayDistrictID = $districtID > 0 ? arrayDistrict($provinceID, 1, "") : array();
        $arrayCommuneID = $communeID > 0 ? arrayCommune($districtID, 1, "") : array();
        $arrayVillageID = $villageID > 0 ? arrayVillage($communeID, 1, "") : array();

        $pobCountryID = $disputant->pob_country_id;
        $pobProvinceID = $disputant->pob_province_id;
        $pobDistrictID = $disputant->pob_district_id;
        $pobCommuneID = $disputant->pob_commune_id;

        $arrPOBDistrictID = $pobDistrictID > 0 ? arrayDistrict($pobProvinceID, 1, "") : array();
        $arrPOBCommuneID = $pobCommuneID > 0 ? arrayCommune($pobDistrictID, 1, "") : array();


        $caseDisputantProvince = $caseDisputant->province;
        $caseDisputantDistrict = $caseDisputant->district;
        $caseDisputantCommune = $caseDisputant->commune;
        $caseDisputantVillage = $caseDisputant->village;
        $caseDisputantHouseNo = $caseDisputant->house_no;
        $caseDisputantStreetNo = $caseDisputant->street;
        $arrCaseDisputantDistrict = $caseDisputantDistrict > 0 ? arrayDistrict($caseDisputantProvince, 1, "") : array();
        $arrCaseDisputantCommune = $caseDisputantCommune > 0 ? arrayCommune($caseDisputantDistrict, 1, "") : array();
        $arrCaseDisputantVillage = $caseDisputantVillage > 0 ? arrayVillage($caseDisputantCommune, 1, "") : array();



        $data = [
            'pagetitle' => "កែប្រែពាក្យបណ្ដឹង",
            'case' => $case,
            'arrOfficers' => $arrOfficers,
            'lastOfficers' => $lastOfficers,
            'arrNoters' => $arrNoters,
            'lastNoter' => $lastNoter,
            'arrCaseType' => myArrayCaseType(),
            'arrSector' => myArraySector(1),
            'arrCompanyType' => arrayCompanyType(1),
            'arrNationality' => arrayNationality(1),
            'arrObjectiveCase' => arrayObjectiveCase(1),
            'provinceID' => $provinceID,
            'districtID' => $districtID,
            'communeID' => $communeID,
            'villageID' => $villageID,
            'pobCountryID' => $pobCountryID,
            'pobProvinceID' => $pobProvinceID,
            'pobDistrictID' => $pobDistrictID,
            'pobCommuneID' => $pobCommuneID,
            'companyPhone' => $caseCompany->log5_company_phone_number,
            'companyPhone2' => $caseCompany->log5_company_phone_number2,
            'disputantName' => $disputant->name,
            'disputantGender' => $disputant->gender,
            'disputantNationality' => $disputant->nationality,
            'disputantDOB' => $disputant->dob,
            'disputantIDNumber' => $disputant->id_number,
            'caseDisputantOccupation' => $caseDisputant->occupation,
            'caseDisputantPhoneNumber' => $caseDisputant->phone_number,
            'caseDisputantPhoneNumber2' => $caseDisputant->phone_number2,
            'arrProvinceID' => arrayProvince(1),
            'arrayDistrictID' => $arrayDistrictID,
            'arrayCommuneID' => $arrayCommuneID,
            'arrayVillageID' => $arrayVillageID,
            'arrPOBDistrictID' => $arrPOBDistrictID,
            'arrPOBCommuneID' => $arrPOBCommuneID,
            'buildingNO' => $caseCompany->log5_building_no,
            'streetNo' => $caseCompany->log5_street_no,
            'caseDisputantProvince' => $caseDisputantProvince,
            'caseDisputantDistrict' => $caseDisputantDistrict,
            'caseDisputantCommune' => $caseDisputantCommune,
            'caseDisputantVillage' => $caseDisputantVillage,
            'arrCaseDisputantDistrict' => $arrCaseDisputantDistrict,
            'arrCaseDisputantCommune' => $arrCaseDisputantCommune,
            'arrCaseDisputantVillage' => $arrCaseDisputantVillage,
            'caseDisputantHouseNo' => $caseDisputantHouseNo,
            'caseDisputantStreetNo' => $caseDisputantStreetNo,
        ];

        $view = "case.update_case1";
        if (request("json_opt") == 1) { //if request from app
            return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
        }
        return view($view, ["adata" => $data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TelegramService $telegramService, string $id)
    {
        //        dd($request->all());
        //dd($request->input());
        $dateCreated = myDateTime();
        $caseID = $id;
        //        $disputant_id = $request->disputant_id;
        $companyID = $request->company_id;

        $validator = Validator::make(
            $request->all(),
            [
                'dob' => 'required|date_format:d-m-Y', // Validates that the input is a valid date
            ],
            [
                'dob.date_format' => 'ថ្ងៃខែឆ្នាំកំណើតមិនត្រឹមត្រូវ'
            ]
        );
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }


        //dd($request->village_id);


        DB::beginTransaction();
        try {

            /** ===============BlogA: Update Disputant (Employee) ======================== */
            if (!empty($request->id_number)) {
                $searchDisputant = ["id_number" => $request->id_number];
                $adataDisputant = [
                    "name" => $request->name,
                    "gender" => $request->gender,
                    "dob" => date2DB($request->dob),
                    "nationality" => $request->nationality,
                    //                    "id_number" => $request->id_number,
                    "phone_number" => $request->phone_number,
                    "phone_number2" => $request->phone_number2,
                    "occupation" => $request->occupation,

                    "house_no" => $request->addr_house_no,
                    "street" => $request->addr_street,
                    "group_name" => $request->group_name,
                    "village" => $request->village,
                    "commune" => $request->commune,
                    "district" => $request->district,
                    "province" => $request->province,

                    "pob_commune_id" => $request->nationality == 33 ? $request->pob_commune_id : 0,
                    "pob_district_id" => $request->nationality == 33 ? $request->pob_district_id : 0,
                    "pob_province_id" => $request->nationality == 33 ? $request->pob_province_id : 0,
                    "pob_country_id" => $request->nationality == 33 ? 0 : $request->pob_country_id,

                    "user_created" => Auth::user()->id,
                    "user_updated" => Auth::user()->id,
                    "date_created" =>  $dateCreated,
                    "date_updated" =>  $dateCreated,
                ];
            } else {
                $searchDisputant = [
                    "name" => $request->name,
                    "dob" => date2DB($request->dob),
                    "phone_number" => $request->phone_number,
                ];
                $adataDisputant = [
                    //                    "name" => $request->name,
                    "gender" => $request->gender,
                    //                    "dob" => date2DB($request->dob),
                    "nationality" => $request->nationality,
                    "id_number" => $request->id_number,
                    "phone_number2" => $request->phone_number2,
                    "occupation" => $request->occupation,

                    "house_no" => $request->addr_house_no,
                    "street" => $request->addr_street,
                    "group_name" => $request->group_name,
                    "village" => $request->village,
                    "commune" => $request->commune,
                    "district" => $request->district,
                    "province" => $request->province,

                    "pob_commune_id" => $request->pob_country_id == 33 ? $request->pob_commune_id : 0,
                    "pob_district_id" => $request->pob_country_id == 33 ? $request->pob_district_id : 0,
                    "pob_province_id" => $request->pob_country_id == 33 ? $request->pob_province_id : 0,
                    "pob_country_id" => $request->nationality == 33 ? 0 : $request->pob_country_id,

                    "user_created" => Auth::user()->id,
                    "user_updated" => Auth::user()->id,
                    "date_created" =>  $dateCreated,
                    "date_updated" =>  $dateCreated,
                ];
            }
            //            dd($searchDisputant);
            $resultDisputant = Disputant::updateOrCreate($searchDisputant, $adataDisputant);
            //            dd($result);

            $disputantID = !empty($resultDisputant) ? $resultDisputant->id : 0;//get disputant_id
        //            dd($disputant_id);
            /** ===============BlogB: Update Main Table: tbl_case ======================== */
            $adata = [
                "case_number" => $request->case_number,
                "case_type_id" => $request->case_type_id,
                "disputant_id" => $disputantID,
                //"company_id" => $request->case_type_id,
                //"company_option" => $request->case_type_id,
                "company_type_id" => $request->company_type_id,
                "sector_id" => $request->sector_id,
                "case_objective_id" => $request->case_objective_id,
                "case_ojective_other" => $request->case_ojective_other,
                "terminated_contract_date" => date2DB($request->terminated_contract_date),
                "terminated_contract_time" => $request->terminated_contract_time,
                "case_objective_des" => $request->case_objective_des,
                "disputant_sdate_work" => date2DB($request->disputant_sdate_work),
                "disputant_contract_type" => $request->disputant_contract_type,
                "disputant_work_hour_day" => $request->disputant_work_hour_day,
                "disputant_work_hour_week" => $request->disputant_work_hour_week,
                "disputant_salary" => $request->disputant_salary,
                "disputant_night_work" => $request->disputant_night_work,
                "disputant_holiday_week" => $request->disputant_holiday_week,
                "disputant_holiday_year" => $request->disputant_holiday_year,
                "case_first_reason" => $request->case_first_reason,
                //                "disputant_terminated_contract" => $request->disputant_terminated_contract,
                "disputant_request" => $request->disputant_request,
                "case_date" => date2DB($request->case_date),
                "case_date_entry" => date2DB($request->case_date_entry),

                "user_updated" => Auth::user()->id,
                "date_updated" =>  $dateCreated,
            ];
            //            dd($adata);
            Cases::where("id", $caseID)->update($adata); // return 1 if update success

            $caseYear = date2Display($request->case_date, "Y");

            /** ===============BlogC: Update Company ======================== */
            $adataCompany = [
                //                "company_name_khmer" => $request->company_name_khmer,
                //                "company_name_latin" => $request->company_name_latin,
                "company_type_id" => $request->company_type_id,
                "sector_id" => $request->sector_id,
                "building_no" => $request->building_no,
                "street_no" => $request->street_no,
                "village_id" => $request->village_id,
                "commune_id" => $request->commune_id,
                "district_id" => $request->district_id,
                "province_id" => $request->province_id,
                "company_phone_number" => $request->company_phone_number,
                "company_phone_number2" => $request->company_phone_number2,
                "user_updated" => Auth::user()->id,
                "date_updated" =>  $dateCreated,
            ];
            //dd($adataCompany);
            Company::where("company_id", $companyID)->update($adataCompany);// return 1 if update success
            //dd($result);
            /** ===============BlogD: Upload File and Save file name to DB ======================== */
            $pathToUpload = pathToUploadFile("case_doc/form1/" . $caseYear . "/");
            $caseFileNew = myUploadFileOnly($request, $pathToUpload, "case_file", $caseID, "case_file");
            //            $case_file_new = uploadFileOnly($request, $path_to_upload, "case_file", $case_id);
            $caseFile = !empty($caseFileNew) ? $caseFileNew : $request->case_file_old;
            Cases::where("id", $caseID)->update(["case_file" => $caseFile]);
            /** ===============BlogE: Update data in tbl_case_company ======================== */
            $adataCaseCompany = [
                //"case_id" => $case_id,
                //"company_id" => $company_id,
                "log5_company_phone_number" => $request->company_phone_number,
                "log5_company_phone_number2" => $request->company_phone_number2,
                "log5_building_no" => $request->building_no,
                "log5_street_no" => $request->street_no,
                "log5_village_id" => $request->village_id,
                "log5_commune_id" => $request->commune_id,
                "log5_district_id" => $request->district_id,
                "log5_province_id" => $request->province_id,

                "user_updated" => Auth::user()->id,
                "date_updated" =>  $dateCreated,
            ];
            CaseCompany::where("case_id", $caseID)
                ->where("company_id", $companyID)->update($adataCaseCompany);

            /** =============Find The Domain ID and Update domain_id in tbl_case_company */
            $domainID = getCaseDomainControl($caseID);
            CaseCompany::where('case_id', $caseID)->update(['domain_id' => $domainID]);

            /** ===============BlogF: Update data in tbl_case_disputant ======================== */
            $adataSearchCaseDisputant = [
                "case_id" => $caseID,
                "disputant_id" => $disputantID,
                "attendant_type_id" => 1, //ដើមបណ្តឹង
            ];
            $adataCaseDisputant = [
                "house_no" => $request->addr_house_no,
                "street" => $request->addr_street,
                "village" => $request->village,
                "commune" => $request->commune,
                "district" => $request->district,
                "province" => $request->province,
                "phone_number" => $request->phone_number,
                "phone_number2" => $request->phone_number2,
                "occupation" => $request->occupation,

                "user_updated" => Auth::user()->id,
                "date_updated" =>  $dateCreated,
            ];
            CaseDisputant::updateOrCreate($adataSearchCaseDisputant, $adataCaseDisputant);

            /** ===============BlogG: Assign Officer ======================== */
            $this->updateOrCreateOfficer($caseID, $request->officer_id, 6);
            $this->updateOfficer8($caseID, $request->officer_id8, 8);

            /**=================== Telegram Bot ============================  */
            $todayCases = DB::table('tbl_case')
                ->whereDate('date_created', Carbon::today())
                ->count();
            $totalCases = DB::table('tbl_case')->where('case_type_id', 1)->count();
            $currentCase = Cases::where("id", $caseID)->first();
            $msg2Telegram = "<b>" . "📢 កែប្រែពាក្យបណ្តឹង" . "</b>" . "\n"
                . "===========================" . "\n"
                . "📌 សំណុំរឿងលេខ៖ " . "<b>" . ($currentCase->case_num_str ?? 'N/A') . "</b>\n\n"
                . "👤 អ្នកកែប្រែពាក្យបណ្តឹង៖ " . "<b>" . ($currentCase->entryUpdatedUser->k_fullname ?? 'N/A') . "</b>" . "\n\n"
                . "📆 កាលបរិច្ឆេទបញ្ចូលពាក្យបណ្តឹង៖ " . "<b>" . (date2Display($currentCase->date_created) ?? 'N/A') . "</b>" . "\n\n"
                . "📌 ប្រភេទបណ្តឹង៖ " . "<b>" . ($currentCase->caseType->case_type_name ?? 'N/A') . "</b>" . "\n\n"
                . "👤 ដើមបណ្តឹង៖ " . "<b>" . ($currentCase->disputant->name ?? 'N/A') . "</b>" . "\n\n"
                . "👤 ចុងបណ្តឹង៖ " . "<b>" . ($currentCase->company->company_name_khmer ?? 'N/A') . "</b>" . "\n\n"
                . "🧨 អង្គហេតុនៃវិវាទ៖ " . "<b>" . (Str::limit($currentCase->case_objective_des) ?? 'N/A') . "</b>" . "\n\n"
                //                . "🎈 មូលហេតុចម្បងនៃវិវាទ៖ " ."<b>". ($currentCase->case_first_reason ?? 'N/A') ."</b>". "\n\n"
                //                . "🙏🏻 សំណូមពររបស់អ្នកប្ដឹង៖ " ."<b>". ($currentCase->disputant_request ?? 'N/A') ."</b>". "\n\n"
                . "📆 កាលបរិច្ឆេទបណ្តឹង៖ " . "<b>" . (date2Display($currentCase->case_date) ?? 'N/A') . "</b>" . "\n\n"
                . "🗓️ កាលបរិច្ឆេទប្តឹងទៅអធិការការងារ៖ " . "<b>" . (date2Display($currentCase->case_date_entry) ?? 'N/A') . "</b>" . "\n\n"
                . "👤 អ្នកផ្សះផ្សា៖ " . "<b>" . ($currentCase->latestCaseOfficer->officer->officer_name_khmer ?? 'N/A') . "</b>" . "\n\n"
                . "👤 អ្នកកត់ត្រា៖ " . "<b>" . ($currentCase->caseNoter->officer->officer_name_khmer ?? 'N/A') . "</b>" . "\n"
                . "===========================" . "\n"
                . "#️⃣ សំណុំរឿងដែលបានបញ្ចូលថ្ងៃនេះចំនួន៖ " . "<b>" . number2KhmerNumber($todayCases) . "</b>" . " បណ្តឹង\n\n"
                . "#️⃣ សំណុំរឿងសរុបទាំងអស់ចំនួន៖ " . "<b>" . number2KhmerNumber($totalCases) . "</b>" . " បណ្តឹង\n\n";

            $telegramService->sendMessage($msg2Telegram);

            DB::commit();
            if (request("json_opt") == 1) { //if request from app
                //$data = getDataForAllMenu($inspection_id, $this->menu);
                //return response()->json(['status' => 200, 'message' => 'success', 'data'=> $data]);
            }
            return back()->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return back()->with("message", sweetalert()->addWarning("បរាជ័យ"));
        }
    }

    /** Daily Case Report */
    public function caseDailyReport2Telegram()
    {

        $telegram = new TelegramService();

        // Get today's cases
        $todayCases = Cases::whereDate('date_created', Carbon::today())->get();
        //        $todayCases = Cases::whereMonth('date_created', Carbon::now()->month)
        //            ->whereYear('date_created', Carbon::now()->year)->get();
        //            ->count();

        $totalCases = DB::table('tbl_case')->where('case_type_id', 1)->count();

        if ($todayCases->isEmpty()) {
            $telegram->sendMessage("📢 មិនមានសំណុំរឿង ដែលបញ្ចូលក្នុងថ្ងៃនេះទេ!");
            return;
        }

        // Count total cases
        $todayCasesCount = $todayCases->count();

        //        dd($totalCases);

        // Group cases by user
        $casesByUser = $todayCases->groupBy('user_created')->map(function ($cases, $userId) {
            return ['userID' => $userId, 'caseCount' => count($cases)];
        })->sortByDesc('caseCount');

        //        dd($casesByUser);

        // Build the report message
        $reportMessage = "📢📢📢 របាយការណ៌បូកសរុបសំណុំរឿងប្រចាំថ្ងៃ (" . date2Display(Carbon::today()->toFormattedDateString()) . ")\n\n"
            . "==============================================" . "\n\n"
            . "#️⃣ សំណុំរឿងដែលបានបញ្ចូលថ្ងៃនេះ៖ " . "<b>" . number2KhmerNumber($todayCasesCount) . "</b>" . " បណ្តឹង\n\n"
            . "#️⃣ សំណុំរឿងសរុបទាំងអស់៖ " . "<b>" . number2KhmerNumber($totalCases) . "</b>" . " បណ្តឹង\n\n"
            . "==============================================" . "\n";

        //        dd($reportMessage);

        foreach ($casesByUser as $userCase) {
            $user = User::find($userCase['userID']);
            $username = $user ? $user->k_fullname : "UserID={$userCase['userID']}";
            $caseCount = $userCase['caseCount'] ? number2KhmerNumber($userCase['caseCount']) : 0;
            $reportMessage .= "👤<b> $username </b> បានបញ្ចូល: <b> $caseCount</b> បណ្តឹង\n";
        }

        //        dd($reportMessage);

        // Send report to telegram
        $telegram->sendMessage($reportMessage);
    }

    /** កំណត់អ្នកផ្សះផ្សា (attendant_type_id=6) និង អ្នកកត់ត្រា (attendant_type_id=8) */
    private function updateOrCreateOfficer($case_id, $officer_id = 0, $attendant_type_id = 6)
    {
        //dd($officer_id);
        if ($officer_id > 0) {
            $date_created = myDateTime();
            $officer = Officer::where("id", $officer_id)->first();
            //dd($officer);
            //dd($officer->officer_role);
            $search = ["case_id" => $case_id, "officer_id" => $officer_id, "attendant_type_id" => $attendant_type_id];
            $adata = [
                //"attendant_type_id" => $attendant_type_id,
                "officer_role_id" => $officer->officer_role_id,
                "officer_role" => $officer->officer_role,

                "user_updated" => Auth::user()->id,
                "date_updated" =>  $date_created
            ];
            //dd($adata);
            $result = CaseOfficer::updateOrCreate($search, $adata);
            //dd($result);
            if ($result->wasRecentlyCreated) {
                $arrayCreate = [
                    "user_created" => Auth::user()->id,
                    "date_created" =>  $date_created
                ];
                CaseOfficer::where($search)->update($arrayCreate);
                // The record was just created
                //echo 'Record was created';
            }
            //dd($result);
        }
    }
    private function updateOfficer8($caseID, $officerID = 0, $attendantTypeID = 8)
    {
        $dateCreated = myDateTime();
        $officer = Officer::where("id", $officerID)->first();
        $search = ["case_id" => $caseID, "attendant_type_id" => $attendantTypeID];
        $adata = [
            "officer_id" => $officerID,
            "officer_role_id" => $officer->officer_role_id ?? 0,
            "officer_role" => $officer->officer_role ?? '',

            "user_updated" => Auth::user()->id,
            "date_updated" =>  $dateCreated
        ];

        //            CaseOfficer::where("case_id", $case_id)->where("attendant_type_id", 8)->update($adata);
        CaseOfficer::updateOrCreate($search, $adata);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, TelegramService $telegramService)
    {

        $case = Cases::where("id", $id)->first();
        $caseID = $case->id;
        //        $companyID = $case->company_id;
        //        $disputantID = $case->disputant_id;
        $caseFile = $case->case_file;

        DB::beginTransaction();
        try {
            /**
             * 1.Delete Case: tbl_case : 1 record
             * 2.Delete CaseCompany: tbl_case_company: 1 record
             * 3.Delete CaseDisputant: tbl_case_disputant: 1 record
             * 4.Delete CaseInvitation: tbl_case_invitation: 1 or many records
             * 5.Delete CaseLog: tbl_case_log: 1 or many records
             * 6.Delete CaseLog34: tbl_case_log34: 1 record
             * 7.Delete CaseLog5: tbl_case_log5: 1 record
             * 8.Delete CaseLog6: tbl_case_log6: 1 record
             * 8.1.Delete CaseLog6_20agree: tbl_case_log6_20agree: 1 or many record
             * 8.1.Delete CaseLog6_21disagree: tbl_case_log6_20disagree: 1 or many record
             * 9.Delete CaseLogAttedant: tbl_case_log_attendant: 1 or many records
             * 10.Delete CaseOfficer: tbl_case_officer: 1 or many records
             * 11.Delete File
             */

            /** 2.Delete CaseCompany: tbl_case_company: 1 record */
            CaseCompany::where("case_id", $caseID)->delete();
            /** 3.Delete CaseDisputant: tbl_case_disputant: 1 record */
            CaseDisputant::where("case_id", $caseID)->delete();
            /** 4.Delete CaseInvitation: tbl_case_invitation: 1 or many records */
            $caseInvitation = CaseInvitation::where("case_id", $caseID)->get();
            foreach ($caseInvitation as $inv) {
                if (!empty($inv->invitation_file)) {
                    $nextResult = InvitationNextTime::where("id", $inv->id)->get();
                    foreach ($nextResult as $next) {
                        if (!empty($next->letter)) {
                            deleteFile($next->letter, pathToUploadFile("invitation/next/")); //delete invitation_file
                        }
                    }
                    deleteFile($inv->invitation_file, pathToUploadFile("invitation/")); //delete invitation_file
                }
            }
            CaseInvitation::where("case_id", $caseID)->delete();

            /** 6.Delete CaseLog34: tbl_case_log34: 1 record */
            if ($case->log34->count() > 0) {
                foreach ($case->log34 as $row) {
                    CaseLog34::where("case_id", $caseID)->where("log_id", $row->id)->delete();
                }
            }
            /** 7.Delete CaseLog5: tbl_case_log5: 1 record and Union1 for Log5 */
            if ($case->log5->count() > 0) {
                foreach ($case->log5 as $row) {
                    CaseLog5::where("case_id", $caseID)->where("log_id", $row->id)->delete();
                    CaseLog5Union1::where("case_id", $caseID)->where("log_id", $row->id)->delete();
                }
            }
            /** 8.Delete CaseLog6: tbl_case_log6: 1 record, 8.1, and 8.2 */
            if ($case->log6->count() > 0) {
                foreach ($case->log6 as $row) {
                    CaseLog620::where("case_id", $caseID)->where("log_id", $row->id)->delete();
                    CaseLog621::where("case_id", $caseID)->where("log_id", $row->id)->delete();
                    CaseLog6::where("case_id", $caseID)->where("log_id", $row->id)->delete();
                }
            }
            /** 5.Delete CaseLog: tbl_case_log: 1 or many records */
            CaseLog::where("case_id", $caseID)->delete();

            /** 9.Delete CaseLogAttedant: tbl_case_log_attendant: 1 or many records */
            CaseLogAttendant::where("case_id", $caseID)->delete();
            /** 10.Delete CaseOfficer: tbl_case_officer: 1 or many records */
            CaseOfficer::where("case_id", $caseID)->delete();
            /** 11.Delete File: case_file */
            //$path_to_delete = "storage/assets/images/letter_file";
            $pathToDeleted = pathToUploadFile("case_doc/form1/");
            deleteFile($caseFile, $pathToDeleted);//delete case file form 1







            /**=================== Telegram Bot ============================  */
            $todayCases = DB::table('tbl_case')
                ->whereDate('date_created', Carbon::today())
                ->count();
            $totalCases = DB::table('tbl_case')->where('case_type_id', 1)->count();
            $deletedBy = Auth::user()->k_fullname ?? 'N/A';

            $msg2Telegram = "<b>" . "📢 លុបពាក្យបណ្តឹង !!!" . "</b>" . "\n"
                . "===========================" . "\n"
                . "📌 សំណុំរឿងលេខ៖ " . "<b>" . ($case->case_num_str ?? 'N/A') . "</b>\n\n"
                . "👤 អ្នកបញ្ចូលពាក្យបណ្តឹង៖ " . "<b>" . ($case->entryUser->k_fullname ?? 'N/A') . "</b>" . "\n\n"
                . "👤 អ្នកលុបពាក្យបណ្តឹង៖ <b>" . $deletedBy . "</b>" . "\n\n"
                . "📌 ប្រភេទបណ្តឹង៖ " . "<b>" . ($case->caseType->case_type_name ?? 'N/A') . "</b>" . "\n\n"
                . "👤 ដើមបណ្តឹង៖ " . "<b>" . ($case->disputant->name ?? 'N/A') . "</b>" . "\n\n"
                . "👤 ចុងបណ្តឹង៖ " . "<b>" . ($case->company->company_name_khmer ?? 'N/A') . "</b>" . "\n\n"
                . "🧨 អង្គហេតុនៃវិវាទ៖ " . "<b>" . (Str::limit($case->case_objective_des) ?? 'N/A') . "</b>" . "\n\n"
                . "🎈 មូលហេតុចម្បងនៃវិវាទ៖ " . "<b>" . (Str::limit($case->case_first_reason) ?? 'N/A') . "</b>" . "\n\n"
                . "🙏🏻 សំណូមពររបស់អ្នកប្ដឹង៖ " . "<b>" . (Str::limit($case->disputant_request) ?? 'N/A') . "</b>" . "\n\n"
                . "📆 កាលបរិច្ឆេទបណ្តឹង៖ " . "<b>" . (date2Display($case->case_date) ?? 'N/A') . "</b>" . "\n\n"
                . "🗓️ កាលបរិច្ឆេទប្តឹងទៅអធិការការងារ៖ " . "<b>" . (date2Display($case->case_date_entry) ?? 'N/A') . "</b>" . "\n\n"
                . "👤 អ្នកផ្សះផ្សា៖ " . "<b>" . ($case->latestCaseOfficer->officer->officer_name_khmer ?? 'N/A') . "</b>" . "\n\n"
                . "👤 អ្នកកត់ត្រា៖ " . "<b>" . ($case->caseNoter->officer->officer_name_khmer ?? 'N/A') . "</b>" . "\n"
                . "===========================" . "\n"
                . "#️⃣ សំណុំរឿងដែលបានបញ្ចូលថ្ងៃនេះចំនួន៖ " . "<b>" . number2KhmerNumber($todayCases) . "</b>" . " បណ្តឹង\n\n"
                . "#️⃣ សំណុំរឿងសរុបទាំងអស់ចំនួន៖ " . "<b>" . number2KhmerNumber($totalCases) . "</b>" . " បណ្តឹង\n\n";

            /** 1.Delete Case: tbl_case : 1 record */
            Cases::where("id", $id)->delete();

            // Push notification to telegram
            $telegramService->sendMessage($msg2Telegram);

            DB::commit();
            if (request("json_opt") == 1) { //if request from app
                //$data = getDataForAllMenu($inspection_id, $this->menu);
                //return response()->json(['status' => 200, 'message' => 'success', 'data'=> $data]);
            }
            return back()->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return back()->with("message", sweetalert()->addWarning("បរាជ័យ"));
        }
    }
}
