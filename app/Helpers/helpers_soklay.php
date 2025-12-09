<?php
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\File;
use App\Models\BusinessActivity;
use App\Models\CaseCompany;
use App\Models\CaseOfficer;
use App\Models\Cases;
use App\Models\CaseType;
use App\Models\CollectivesCause;
use App\Models\Commune;
use App\Models\CompanyType;
use App\Models\District;
use App\Models\Role;
use App\Models\RolekParents;
use App\Models\Tracking;
use App\Models\AllowCompanySelfInsp;
use App\Models\DomainCommune;
use App\Models\DomainDistrict;
use App\Models\DomainName;
use App\Models\DomainProvince;
use App\Models\Officer;
use App\Models\OfficerRole;
use App\Models\Province;
use App\Models\Sector;
use App\Models\User;
use App\Services\TelegramService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;



/**
 * Classifies the authenticated user into a specific identity category.
 *
 * Categories:
 *  - 1: Master
 *  - 2: Admin
 *  - 3: Ministry High Level (Central Level Officer)
 *  - 31: Office 1
 *  - 32: Office 2
 *  - 33: Office 3
 *  - 34: Office 4
 *  - 4: Provincial Level (Default fallback)
 *
 * @author SoklayCr7
 * @date 2025-04-28
 *
 * @return int Returns the determined user category code.
 */
function chkUserIdentity()
{
    $userOfficerID = auth()->user()->officer_id;
    $userOfficerRole = getOfficerRoleID($userOfficerID);
    $kCatID = auth()->user()->k_category;
    $roleMap = [
        1 => 3,
        2 => 3,
        15 => 3,
        16 => 3,
        17 => 3,
        18 => 3, //Ministry High Level (Central Level Officer)
        3 => 31,
        7 => 31,
        11 => 31, //Office 1
        4 => 32,
        8 => 32,
        12 => 32, //Office 2
        5 => 33,
        9 => 33,
        13 => 33, //Office 3
        6 => 34,
        10 => 34,
        14 => 34 //Office 4
    ];
    if ($kCatID < 3) {
        $userCat = $kCatID;
    } elseif ($kCatID == 3) {
        $userCat = $roleMap[$userOfficerRole] ?? 4;
    } else {
        $userCat = 4;
    }
    return $userCat;
}

/** Push Notification About Case Status Action Via Telegram */
function caseStatusTelegramNotification($case, $caseStepMsg)
{
//    dd($case, $caseStepMsg, $caseStepObj);
    /**=================== Telegram Bot ============================  */
    $todayCases = DB::table('tbl_case')
        ->whereDate('date_created', Carbon::today())
        ->count();
    $totalCases = DB::table('tbl_case')->where('case_type_id', 1)->count();
    //    $currentCase = Cases::where("id", $case)->first();

    //    dd($caseStepMsg);
    $msg2Telegram = "<b>" . "📢 ពាក្យបណ្តឹងត្រូវបានធ្វើបច្ចប្បន្នភាព !!!" . "</b>" . "\n"
        . "===========================" . "\n"
        . "📌 សំណុំរឿងលេខ៖ " . "<b>" . ($case->case_num_str ?? 'N/A') . "</b>\n\n"
        . "📌 បច្ចុប្បន្នភាពពាក្យបណ្តឹង៖ " . "<b>" . ($caseStepMsg ?? 'N/A') . "</b>\n\n"
        . "👤 ធ្វើបច្ចុប្បន្នភាពដោយ៖ " . "<b>" . (Auth::user()->k_fullname ?? 'N/A') . "</b>" . "\n\n"
        //        . "📌 ប្រភេទបណ្តឹង៖ " ."<b>". ($case->caseType->case_type_name ?? 'N/A') ."</b>". "\n\n"
        . "👤 ដើមបណ្តឹង៖ " . "<b>" . ($case->disputant->name ?? 'N/A') . "</b>" . "\n\n"
        . "👤 ចុងបណ្តឹង៖ " . "<b>" . ($case->company->company_name_khmer ?? 'N/A') . "</b>" . "\n\n"
        . "📆 កាលបរិច្ឆេទបណ្តឹង៖ " . "<b>" . (date2Display($case->case_date) ?? 'N/A') . "</b>" . "\n\n"
        . "🗓️ កាលបរិច្ឆេទប្តឹងទៅអធិការការងារ៖ " . "<b>" . (date2Display($case->case_date_entry) ?? 'N/A') . "</b>" . "\n\n"
        . "👤 អ្នកផ្សះផ្សា៖ " . "<b>" . ($case->latestCaseOfficer->officer->officer_name_khmer ?? 'N/A') . "</b>" . "\n\n"
        . "👤 អ្នកកត់ត្រា៖ " . "<b>" . ($case->caseNoter->officer->officer_name_khmer ?? 'N/A') . "</b>" . "\n"
        . "===========================" . "\n"
        . "#️⃣ សំណុំរឿងដែលបានបញ្ចូលថ្ងៃនេះចំនួន៖ " . "<b>" . number2KhmerNumber($todayCases) . "</b>" . " បណ្តឹង\n\n"
        . "#️⃣ សំណុំរឿងសរុបទាំងអស់ចំនួន៖ " . "<b>" . number2KhmerNumber($totalCases) . "</b>" . " បណ្តឹង\n\n";


    //    dd($msg2Telegram);
    // Resolve  TelegramService
    $telegramService = app(TelegramService::class);
    $telegramService->sendMessage($msg2Telegram);
}

/**
 * Get all officer IDs related to a specific case.
 *
 * @param int $caseId
 * @return array
 */
function getCaseOfficerIDs($caseId)
{
    return CaseOfficer::where('case_id', $caseId)
        ->pluck('officer_id')
        ->toArray();
}

function allowDeleteCase()
{
    $user = auth()->user();
    if (!$user->officer_id && $user->k_category < 3) { // Master & Admin
        return 1;
    }

    // Officer ID is required beyond this point
    $officerRoleID = getOfficerRoleID($user->officer_id);
    $allowedRoleIDs = [1, 2, 3, 4, 5, 6, 15, 16, 17, 18]; //កម្រិតចាប់ពីថ្នាក់ប្រធានការិយាល័យឡើងទៅ

    return in_array($officerRoleID, $allowedRoleIDs) ? 1 : 0;
}


/**
 * Checking Accessibility Of Case
 * @return void
 */

function allowAccess(int $userId, int $kCategory, int $entryUserId, int $officerRoleId): int
{
    if (!$userId) {
        return 0;
    }

    // 1. If user created the case
    if ($entryUserId && $userId === $entryUserId) {
        return 1;
    }

    // 2. Master or Admin (category < 3)
    if ($kCategory < 3) {
        return 1;
    }

    // 3. Role logic
    $deniedRoleIDs = [1, 2, 15, 16, 17, 18]; // ចាប់ពីអនុប្រធាននាយកដ្ឋាន ដល់ អគ្គនាយក
    if (in_array($officerRoleId, $deniedRoleIDs)) {
        return 0;
    }

    $allowedRoleIDs = [3, 4, 5, 6]; //កម្រិតប្រធានការិយាល័យទាំង៤
    return in_array($officerRoleId, $allowedRoleIDs) ? 1 : 0;
}

function allowAccessFromHeadOffice($caseID = 0): int
{
    $user = auth()->user();

    if (!$user) {
        return 0;
    }

    // Check if user created the case
    if ($caseID > 0) {
        $case = Cases::select('user_created')->find($caseID);
        if ($case && $user->id === $case->user_created) {
            return 1;
        }
    }

    // Master & Admin category
    if ((int) $user->k_category < 3) {
        return 1;
    }

    $officerRoleID = getOfficerRoleID($user->officer_id);

    $deniedRoleIDs = [1, 2, 15, 16, 17, 18]; // ចាប់ពីអនុប្រធាននាយកដ្ឋាន ដល់ អគ្គនាយក
    if (in_array($officerRoleID, $deniedRoleIDs)) {
        return 0;
    }

    $allowedRoleIDs = [3, 4, 5, 6]; //កម្រិតប្រធានការិយាល័យទាំង៤
    return in_array($officerRoleID, $allowedRoleIDs) ? 1 : 0;
}

function allowOfficersInvolvedCase($caseID = 0): int
{
    $user = auth()->user();
    if (!$user || !$caseID) {
        return 0;
    }

    $userOfficerID = $user->officer_id;
    $arrOfficerIDs = getCaseOfficerIDs($caseID);

    // Make sure it's an array before checking
    if (!is_array($arrOfficerIDs)) {
        return 0;
    }

    return in_array($userOfficerID, $arrOfficerIDs, true) ? 1 : 0;
}

/** Allow Users To Do Any Actions */
function allowUserAccess($caseID = 0): int
{
    $user = auth()->user();

    if (!$user) {
        return 0;
    }

    // 1. If caseID given, check if user created the case, let's allow them!
    if ($caseID > 0) {
        $case = Cases::select('user_created')->find($caseID);
        if ($case && $user->id === $case->user_created) {
            return 1;
        }
    }

    // 2. Master & Admin category check
    if ((int) $user->k_category < 3) {
        return 1;
    }

    // 3. Check officer role restrictions
    $officerRoleID = getOfficerRoleID($user->officer_id);

    $deniedRoleIDs = [1, 2, 15, 16, 17, 18]; // Denied roles
    if (in_array($officerRoleID, $deniedRoleIDs)) {
        return 0;
    }

    $allowedRoleIDs = [3, 4, 5, 6]; // Allowed head office roles
    if (in_array($officerRoleID, $allowedRoleIDs)) {
        return 1;
    }

    // 4. Finally, check if user officer is involved in the case
    if ($caseID > 0) {
        $arrOfficerIDs = getCaseOfficerIDs($caseID);
        if (is_array($arrOfficerIDs) && in_array($user->officer_id, $arrOfficerIDs, true)) {
            return 1;
        }
    }

    return 0;
}

function getOfficerRoleID($officerID)
{
    static $cache = [];

    if (empty($officerID) || $officerID == 0) {
        return 0;
    }

    if (isset($cache[$officerID])) {
        return $cache[$officerID]; // Return from cache
    }

    $officer = Officer::with('officerRole')->find($officerID);
    $roleID = ($officer && $officer->officerRole) ? $officer->officerRole->id : 0;

    $cache[$officerID] = $roleID; // Save in cache
    return $roleID;
}

function getOfficerRoleIDX($officerID)
{
    if (empty($officerID) || $officerID == 0) {
        return 0;
    }
    $officer = Officer::with('officerRole')->find($officerID);
    if ($officer && $officer->officerRole) {
        return $officer->officerRole->id;
    }
    return 0; // return empty array if officer or role not found
}
function arrCSIC1($showDefault = 0, $defValue = "0", $defLabel = "សូមជ្រើសរើស")
{
    $data = DB::table('csic')
        ->select(DB::raw("csic_1 AS id, CONCAT('(', csic_1, ') ', description_kh) AS name"))
        ->whereNull('csic_2')
        ->orderBy('id')
        ->pluck("name", "id")
        ->prepend('សូមជ្រើសរើស', '0')
        ->toArray();

    if ($showDefault > 0) {
        $result = array($defValue => $defLabel);
        $result += $data;
        $data = $result;
    }
    return $data;
}
function arrCSIC2($csic1, $showDefault = 0, $defValue = 0, $defLabel = "សូមជ្រើសរើស")
{
    $data = DB::table('csic')
        ->select(DB::raw("csic_2 AS id, CONCAT('(', csic_2, ') ', description_kh) AS name"))
        ->where('csic_1', $csic1)
        ->whereNotNull('csic_2')
        ->whereNull('csic_3')
        ->orderBy('id')
        ->pluck("name", "id") // Noted: key = id, value = name
        //        ->prepend('សូមជ្រើសរើស', '0')
        ->toArray();

    if ($showDefault > 0) {
        $data = array_merge([$defValue => $defLabel], $data); // Merge default option
    }
    return $data;
}
function arrCSIC3($csic1, $csic2)
{
    return DB::table('csic')
        ->select(DB::raw("csic_3 AS id, CONCAT('(', csic_3, ') ', description_kh) AS name"))
        ->where('csic_1', $csic1)
        ->where('csic_2', $csic2)
        ->whereNotNull('csic_3')
        ->whereNull('csic_4')
        ->orderBy('id')
        ->pluck("name", "id")
        //        ->prepend('សូមជ្រើសរើស', '0')
        ->toArray();
}
function arrCSIC4($csic1, $csic2, $csic3)
{
    return DB::table('csic')
        ->select(DB::raw("csic_4 AS id, CONCAT('(', csic_4, ') ', description_kh) AS name"))
        ->where('csic_1', $csic1)
        ->where('csic_2', $csic2)
        ->where('csic_3', $csic3)
        ->whereNotNull('csic_4')
        ->whereNull('csic_5')
        ->orderBy('id')
        ->pluck('name', 'id') // Keep 'id' as key and 'name' as value
        //        ->prepend('សូមជ្រើសរើស', "0") // Add '0' => 'សូមជ្រើសរើស' at the beginning
        ->toArray(); // Convert to PHP array
}
function arrCSIC5($csic1, $csic2, $csic3, $csic4)
{
    return DB::table('csic')
        ->select(DB::raw("csic_5 AS id, CONCAT('(', csic_5, ') ', description_kh) AS name"))
        ->where('csic_1', $csic1)
        ->where('csic_2', $csic2)
        ->where('csic_3', $csic3)
        ->where('csic_4', $csic4)
        ->whereNotNull('csic_5')
        ->orderBy('id')
        ->pluck('name', 'id')
        //        ->prepend('សូមជ្រើសរើស', '0')
        ->toArray(); // Convert to PHP array
}

/** Get All Cases COunt By Case_Type */
function getCasesCountByType($caseTypeID = 1)
{
    return Cases::where('case_type_id', $caseTypeID)->count();
}

/** Get All Cases Statistics By Year Including Case_Count, Resolved_Count and Unresolved_Count */
function getCaseStatisticByYear()
{
    $casesData = [];
    // Get the case counts by year
    $yearlyCases = Cases::selectRaw('YEAR(case_date) as year, COUNT(*) as case_count')
        ->groupBy('year')
        ->orderBy('year') // Sorting by year in ascending order for x-axis
        ->get()
        ->map(function ($case) {
            return [
                'year' => intval($case->year),
                'case_count' => $case->case_count,
            ];
        });

    // Loop through the yearly cases and calculate resolved and unresolved counts for each year
    foreach ($yearlyCases as $case) {
        $resolvedCases = Cases::whereYear('case_date', $case['year'])
            ->where(function ($query) {
                $query->whereHas('latestLog6Detail', function ($subQuery) {
                    $subQuery->where('status_id', 2); // Resolved cases
                })->orWhere('case_closed', 1); // Manually closed cases
            })
            ->count();
        $unresolvedCases = Cases::whereYear('case_date', $case['year'])
            ->where('case_closed', 0) // Filter only open cases
            ->where(function ($query) {
                $query->whereHas('latestLog6Detail', function ($subQuery) {
                    $subQuery->where('status_id', '<>', 2); // Cases where status_id ≠ 2
                })->orWhereDoesntHave('latestLog6Detail'); // Cases without logs
            })
            ->count();

        // Add the resolved and unresolved counts to the case data
        $casesData[] = [
            'year' => (string) $case['year'],
            'case_count' => $case['case_count'],
            'resolved_count' => $resolvedCases,
            'unresolved_count' => $unresolvedCases,
        ];
    }

    return $casesData;
}
/** Get All Cases Count By Year */
function getCasesCountByYear()
{
    return Cases::selectRaw('YEAR(case_date) as year, COUNT(*) as case_count')
        ->groupBy('year')
        ->orderBy('year', 'desc')
        ->get()->map(function ($case) {
            return [
                //                'id' => $case->entryUser->id, // User ID
                'year' => $case->year, // Username
                'case_count' => $case->case_count // Total cases created by this user
            ];
        })
        ->toArray();
}


/** Get All Cases By Each Domain */
function getCasesByDomain()
{
    return CaseCompany::select('tbl_domain_name.domain_name', DB::raw('COUNT(tbl_case_company.case_id) as case_count'))
        ->join('tbl_domain_name', 'tbl_case_company.domain_id', '=', 'tbl_domain_name.id')
        ->join('tbl_case', 'tbl_case_company.case_id', '=', 'tbl_case.id')
        ->groupBy('tbl_domain_name.id', 'tbl_domain_name.domain_name')
        ->get()->map(function ($case) {
            return [
                //                'id' => $case->entryUser->id, // User ID
                'domain_name' => $case->domain_name, // Username
                'case_count' => $case->case_count // Total cases created by this user
            ];
        })
        ->toArray();
}

// Assuming you have the following models: CaseCompany, Case, DomainName

/** Get All Users With Cases Count */
function getUsersWithCaseCount()
{
    return Cases::with('entryUser:id,username,k_fullname') // Eager load user details (id, username)
        ->selectRaw('user_created, COUNT(id) as case_count') // Count cases per user
        ->whereNotNull('user_created') // Ensure valid user IDs
        ->groupBy('user_created') // Group by user ID
        ->orderByDesc('case_count') // Sort by highest case count
        ->get()
        ->map(function ($case) {
            return [
                //                'id' => $case->entryUser->id, // User ID
                'username' => $case->entryUser->k_fullname, // Username
                'case_count' => $case->case_count // Total cases created by this user
            ];
        })
        ->toArray();
}

/** Get Top 5 Officers With Most Cases Assigned */
function getTop5Officers()
{
    $officers = Officer::withCount([
        'casesOfficers as case_count' => function ($query) {
            $query->where('attendant_type_id', 6);
        },
        'casesOfficers as unresolved_count' => function ($query) {
            $query->where('attendant_type_id', 6)
                ->whereHas('case', function ($caseQuery) {
                    $caseQuery->where('case_closed', 0)
                        ->where(function ($subQuery) {
                            $subQuery->WhereHas('latestLog6Detail', function ($logQuery) {
                                $logQuery->where('status_id', '!=', 2);
                            })->orWhereDoesntHave('latestLog6Detail');
                        });
                });
        },
        'casesOfficers as resolved_count' => function ($query) {
            $query->where('attendant_type_id', 6)
                ->whereHas('case', function ($caseQuery) {
                    $caseQuery->whereHas('latestLog6Detail', function ($logQuery) {
                        $logQuery->where('status_id', 2);
                    })->orWhere('case_closed', 1);
                });
        }
    ])
        ->orderByDesc('case_count')
        ->limit(5)
        ->get(['id', 'officer_name_khmer']);

    return $officers->map(function ($officer) {
        //        return [
        //            'id' => $officer->id,
        //            'officer_name' => $officer->officer_name_khmer,
        //            'resolved_count' => $officer->resolved_count,
        //            'unresolved_count' => $officer->unresolved_count,
        //            'case_count' => $officer->case_count
        //        ];

        return [
            'x' => $officer->officer_name_latin,
            'y' => $officer->resolved_count,
            'z' => $officer->unresolved_count,
            //            't' => $officer->case_count,
        ];
    })->toArray();
}

function getTop5OfficersX()
{
    $officers = Officer::with(['casesOfficers.case.log6Latest'])
        ->get();
    $officerCases = [];
    foreach ($officers as $officer) {
        /** @var Officer $officer */
        // Filter only cases where the officer is a solver (attendant_type_id == 6)
        $solverCases = $officer->casesOfficers->filter(function ($caseOfficer) {
            return $caseOfficer->attendant_type_id == 6;
        });

        // Total cases handled by the officer
        $totalCases = $solverCases->count();

        // Count unresolved cases
        $unresolvedCount = $solverCases->filter(function ($caseOfficer) {
            return $caseOfficer->case &&
                $caseOfficer->case->case_closed == 0 &&
                (empty($caseOfficer->case->log6Latest) ||
                    $caseOfficer->case->log6Latest->detail6->status_id != 2);
        })->count();

        // Count resolved cases
        $resolvedCount = $solverCases->filter(function ($caseOfficer) {
            return $caseOfficer->case &&
                ($caseOfficer->case->case_closed == 1 ||
                    (!empty($caseOfficer->case->log6Latest) &&
                        $caseOfficer->case->log6Latest->detail6->status_id == 2));
        })->count();

        if ($totalCases > 0) {
            //            $officerCases[] = [
            ////                'id' => $officer->id,
            //                'officer_name' => $officer->officer_name_khmer,
            //                'resolved_count' => $resolvedCount,
            //                'unresolved_count' => $unresolvedCount,
            //                'case_count' => $totalCases
            //            ];

            $officerCases[] = [
                'x' => $officer->officer_name_khmer,  // Short month name (e.g., "Jan")
                'y' => $resolvedCount,  // Resolved cases
                'z' => $unresolvedCount  // Unresolved cases
            ];
        }
    }

    // Sort by total case count in descending order
    usort($officerCases, function ($a, $b) {
        return $b['case_count'] <=> $a['case_count'];
    });

    return array_slice($officerCases, 0, 5);
}

/** Get Number Of Cases For Each Month (Last 6 Months) */
function getCasesLastSixMonths()
{
    $casesData = [];

    for ($i = 5; $i >= 0; $i--) {
        //        $monthYear = Carbon::now()->subMonths($i)->format('F Y'); // "March 2024"
        //        $monthName = Carbon::now()->subMonths($i)->format('F'); // "March"
        $monthShort = Carbon::now()->subMonths($i)->format('M'); // "Jan", "Feb", etc.
        $year = Carbon::now()->subMonths($i)->year;
        $month = Carbon::now()->subMonths($i)->month;

        // Count total cases in the month
        //        $totalCases = Cases::whereYear('case_date', $year)
        //                        ->whereMonth('case_date', $month)
        //                        ->count();

        // Get resolved cases using the provided logic
        $resolvedCases = Cases::whereYear('case_date', $year)
            ->whereMonth('case_date', $month)
            ->where(function ($query) {
                $query->whereHas('latestLog6Detail', function ($subQuery) {
                    $subQuery->where('status_id', 2); // Resolved cases
                })->orWhere('case_closed', 1); // Also include cases that are manually closed
            })
            ->count();


        // Get unresolved cases using the provided logic
        $inProgress = Cases::whereYear('case_date', $year)
            ->whereMonth('case_date', $month)
            ->where('case_closed', 0) // Filter only open cases
            ->where(function ($query) {
                $query->whereHas('latestLog6Detail', function ($subQuery) {
                    $subQuery->where('status_id', '<>', 2); // Cases where status_id ≠ 2
                })->orWhereDoesntHave('latestLog6Detail'); // Include cases without logs
            })
            ->count();


        // Store data in array
        //        $casesData[] = [
        //            'month' => $monthName,
        //            'total' => $totalCases,
        //            'resolved' => $resolvedCases,
        //            'inProgress' => $inProgress
        //        ];

        // Store data in required format
        $casesData[] = [
            'x' => $monthShort,  // Short month name (e.g., "Jan")
            'y' => $resolvedCases,  // Resolved cases
            'z' => $inProgress  // Unresolved cases
        ];
    }

    return $casesData;
}

/** Count All Tody Cases */
function countTodayCases()
{
    return DB::table('tbl_case')
        ->whereDate('date_created', Carbon::today())
        ->count();
}

/** Count All New Cases */
function countNewCases()
{
    $cases = Cases::with([
        'invitationAll',
        'log34Detail',
        'log5Detail'
    ])->where('case_type_id', 1);
    $cases->whereDoesntHave('invitationAll')
        ->whereDoesntHave('log34Detail')
        ->whereDoesntHave('log5Detail')
        ->where('case_closed', 0);

    return $cases->count() ?? 0;
}

/** Count All Cases In Progress For Current Year */

function countInProgressCasesCurrentYear()
{
    $currentYear = Carbon::now()->year;
    $cases = Cases::with([
        'latestLog6Detail'
    ])
        ->where('case_type_id', 1)
        ->where('case_closed', 0) // Filter only open cases
        ->whereYear('case_date_entry', $currentYear) // Filter by current year
        ->where(function ($query) {
            $query->whereHas('latestLog6Detail', function ($subQuery) {
                $subQuery->where('status_id', '<>', 2); // Include only cases where status_id ≠ 2
            })->orWhereDoesntHave('latestLog6Detail'); // Include cases that have no latestLog6Detail
        });
    return $cases->count() ?? 0;
}

/** Count All Cases In Progress */
function countInProgressCases()
{
    $cases = Cases::with([
        'latestLog6Detail'
    ])
        ->where('case_type_id', 1)
        ->where('case_closed', 0) // Filter only open cases
        ->where(function ($query) {
            $query->whereHas('latestLog6Detail', function ($subQuery) {
                $subQuery->where('status_id', '<>', 2); // Include only cases where status_id ≠ 2
            })->orWhereDoesntHave('latestLog6Detail'); // Include cases that have no latestLog6Detail
        });
    return $cases->count() ?? 0;
}


/** Count All Resolved Cases */
function countResolvedCases()
{
    $cases = Cases::with([
        'latestLog6Detail'
    ])->where('case_type_id', 1);
    $cases = $cases->whereHas('latestLog6Detail', function ($subQuery) {
        $subQuery->where('status_id', '=', 2);
    })->orwhere('case_closed', 1);

    return $cases->count() ?? 0;
}

/** Get Specific User By Case */
function getUserByCaseID($caseID)
{
    return DB::table('tbl_case')
        ->where('id', $caseID)
        ->select('user_created')
        ->first()->user_created;
}

/** Get Case Officer Name Or IDs [Last and Former Officers] */
function getCaseOfficerDisplay($caseID = 0, $getLastID = 0, $attendantTypeID = 6, $preSign = "-", $preloaded = [])
{
    if (!$caseID || !$attendantTypeID) {
        return $getLastID ? 0 : "គ្មាន<br/>";
    }

    $key = "{$caseID}_{$attendantTypeID}";
    $results = $preloaded[$key] ?? collect();

    if ($getLastID) {
        return $results->first()->officer_id ?? 0;
    }

    if ($results->isEmpty()) {
        return "គ្មាន<br/>";
    }

    $output = '';
    $total = $results->count();

    foreach ($results as $index => $row) {
        $output .= $preSign . " " . ($row->officer->officer_name_khmer ?? 'គ្មាន');
        if ($attendantTypeID == 6 && $index == 0 && $total > 1) {
            $output .= " [ចុងក្រោយ]";
        }
        $output .= "<br/>";
    }

    return $output;
}



/** Get Case Officer [Last and Former Officers] */

function getCaseOfficerName($caseID = 0, $getLastID = 0, $attendantTypeID = 6, $preSign = "-", $preloaded = [])
{
    if (!$caseID || !$attendantTypeID) {
        return "គ្មាន";
    }

    $key = "{$caseID}_{$attendantTypeID}";
    $results = $preloaded[$key] ?? collect();

    if ($getLastID == 0) {
        if ($results->isEmpty()) {
            return "គ្មាន";
        }

        // If there's only one result
        if ($results->count() == 1) {
            return $preSign . " " . ($results->first()->officer->officer_name_khmer ?? 'គ្មាន') . "<br/>";
        }

        // Multiple results
        $output = '';
        foreach ($results as $index => $row) {
            $output .= $preSign . " " . ($row->officer->officer_name_khmer ?? 'គ្មាន');
            if ($attendantTypeID == 6 && $index == 0) {
                $output .= " [ចុងក្រោយ]";
            }
            $output .= "<br/>";
        }

        return $output;
    }

    // Only get last officer's ID
    return $results->first()->officer_id ?? "គ្មាន";
}

function getCaseOfficerNameZ($caseID = 0, $getLastID = 0, $attendantTypeID = 6, $preSign = "-")
{
    // Validate inputs
    if (!$caseID || !$attendantTypeID) {
        return "គ្មាន";
    }
    if ($getLastID == 0) {
        // Retrieve all matching officers

        $results = CaseOfficer::with('officer')
            ->where("case_id", $caseID)
            ->where("attendant_type_id", $attendantTypeID)
            ->orderByDesc("id");
        //            ->get();



        // Check if results are empty
        if (empty($results)) {
            return "គ្មាន";
        }

        // If there's only one result
        if ($results->count() == 1) {
            return $preSign . " " . $results->first()->officer->officer_name_khmer . "<br/>";
        }

        // Multiple results: Output officer details
        foreach ($results->get() as $index => $row) {
            echo $preSign . " " . $row->officer->officer_name_khmer;
            // Add "[ចុងក្រោយ]" to the first officer when $attendantTypeID is 6
            if ($attendantTypeID == 6 && $index == 0) {
                echo " [ចុងក្រោយ]";
            }
            echo "<br>";
        }
    } else {
        // Retrieve only the last officer's ID
        return CaseOfficer::where("case_id", $caseID)
            ->where("attendant_type_id", $attendantTypeID)
            ->orderByDesc("id")
            ->value("officer_id") ?? "គ្មាន"; // Use `value` for efficiency
    }
}

function getCaseOfficerNameX($caseID = 0, $getLastID = 0, $attendantTypeID = 6)
{
    if ($getLastID == 0) {
        $result = CaseOfficer::where("case_id", $caseID)->where("attendant_type_id", $attendantTypeID)
            ->orderBy("id", "DESC")->get();
        if ($result->count() > 0) {
            $i = 1;
            foreach ($result as $row) {
                echo "- " . $row->officer->officer_name_khmer;
                if ($attendantTypeID == 6) {
                    if ($i == 1) {
                        echo " [ចុងក្រោយ]";
                    }
                }

                echo "<br>";
                $i++;
            }
        } else {
            return "";
        }
    } else {
        $caseOfficer = CaseOfficer::select("officer_id")->where("case_id", $caseID)->where("attendant_type_id", $attendantTypeID)
            ->orderBy("id", "DESC")->first();
        if (!empty($caseOfficer)) {
            return $caseOfficer->officer_id;
        } else {
            return "";
        }
    }
}

/** Count Case Entry By User */
function countCasesByUser($userID)
{
    //    return $userID;
    return DB::table('tbl_case')
        ->where('user_created', $userID)
        ->count();
}

/** Count Case For Solver and Noter in Officer List */
function countCases($cases)
{ // Improving Version
    return collect($cases)->filter(function ($rowCase) {
        $case = $rowCase->case ?? null;
        if (!$case || $case->case_closed == 1) {
            return false;
        }
        $log6 = $case->log6Latest ?? null;
        $detail6 = $log6->detail6 ?? null;
        if ($log6 && ($detail6->status_id ?? 0) == 2) {
            return false;
        }
        return true;
    })->count();
}
function countCasesZ($cases)
{ // Working as Well
    $counter = 0;
    foreach ($cases as $rowCase) {
        $case = $rowCase->case ?? null;
        if ($case && $case->case_closed == 0) {
            $log6 = $case->log6Latest ?? null;
            if ($log6) {
                $detail6 = $log6->detail6 ?? null;
                if ($detail6 && $detail6->status_id != 2) {
                    $counter++;
                }
            } else {
                $counter++;
            }
        }
    }
    return $counter;
}

function myUploadMultiFilesOnly(Request $request, $pathToUpload = "", $field_name = "", $key_id = [], $name = "")
{
    $result = [];
    /** If Directory is not Found, Let's create the new ONE */
    if (!is_dir($pathToUpload)) {
        mkdir($pathToUpload, 0777, true);  // Creates the folder with appropriate permissions
    }

    for ($i = 0; $i < count($key_id); $i++) {
        echo $key_id[$i];
        if (isset($request->file($field_name)[$i])) {

            $file = $request->file($field_name)[$i];
            $tmp = !empty($name) ? $name : time();
            $fileName = $key_id[$i] . "_" . $tmp . "." . $file->getClientOriginalExtension();
            //            $file->storeAs($pathToUpload, $fileName);
            $file->move(public_path($pathToUpload), $fileName);
            $result[$i] = $fileName;
        } else {
            $result[$i] = "";
        }
    }
    return $result;
}



function showFile($html_id, $file_name, $path, $delete_option, $table, $key_find
    , $key_value, $field = "", $label = "បញ្ចូលឯកសារជោគជ័យ: ", $labelFileName = ""
) {
    if (empty($file_name)) {
        return '';
    }

    // Encode file name for display if needed
    $displayFileName = $labelFileName ?: $file_name;

    // Build file URL
    $fileUrl = url($path . $file_name);
    $fileLink = "<a href='{$fileUrl}' title='ទាញយក' target='_blank'>{$displayFileName}</a>";

    // Delete button logic
    $deleteButton = '';
    if ($delete_option === 'delete') {
        $encodedPath = str_replace('/', '__', $path);
        $deleteUrl = url('ajaxDeleteFile');
        $onClick = "comfirm_delete_file_steetalert2(" .
            "'{$html_id}', " .
            "'{$deleteUrl}', " .
            "'{$file_name}', " .
            "'{$encodedPath}', " .
            "'{$table}', " .
            "'{$key_find}', " .
            "'{$key_value}', " .
            "'{$field}', " .
            "'Are_You_Sure?'" .
            ")";
        $deleteButton = "<button type='button' class='btn btn-danger p-1' onClick=\"{$onClick}\" title='Delete File'><i data-feather='trash-2'></i></button>";
    }
    return "<b>{$label}</b>{$fileLink} {$deleteButton}";
}




function getOfficerRoleName($officerID, $roleNameONLY = 0)
{
    $officer = Officer::with(['officerRole'])->where('id', $officerID)->first();
    if ($roleNameONLY > 0) {
        return $officer->officerRole->officer_role;
    }
    return [
        //Return
        $officer->officerRole->id => $officer->officerRole->officer_role
    ];
}

function arrOfficerWithoutUser($defValue = 0, $defLabel = "សូមជ្រើសរើស")
{
    $data = array();
    // list officers who don't have user accounts yet
    $query = Officer::whereDoesntHave('user')->orderBy('officer_name_khmer');


    // Add the default option if $showDefault is true
    if ($defValue > 0) {
        $data['0'] = $defLabel;
    }
    // Convert the query result to an associative array
    $data += $query->pluck("officer_name_khmer", "id")->toArray();

    return $data;
}

function myArrOfficerExcept($officer_ids = [], $showDefault = false, $defValue = "0", $defLabel = "សូមជ្រើសរើស")
{
    // Fetch officer data and exclude specified officer IDs
    $data = Officer::orderby("id", "ASC")
        ->select(DB::raw("officer_name_khmer AS name, id AS id"));

    // Apply exclusion condition if $officer_ids is not empty
    if (!empty($officer_ids)) {
        $data = $data->whereNotIn("id", (array) $officer_ids);
    }

    // Convert the query result to an associative array
    $data = $data->pluck("name", "id")->toArray();

    // Add the default option if $showDefault is true
    if ($showDefault) {
        $data = [$defValue => $defLabel] + $data;
    }

    return $data;
}

function myShowFileOnly($htmlID, $fileName, $filePath, $deleteOption, $table, $keyFind, $keyValue, $field = "", $label = "បញ្ចូលឯកសារជោគជ័យ: ", $labelFileName = "")
{
    $delete = "";
    $str = "";
    if ($deleteOption == "delete") {
        $tmpPath = str_replace("/", "__", $filePath);
        $delURLAjax = url("ajaxDeleteFileOnly");
        //dd($del_url_ajax);
        //$del_url_ajax= url('ajaxDeleteFile/'.$file_name.'/'.$tmp_path.'/'.$table.'/'.$key_find.'/'.$key_value.'/'.$field);
        $onClick2 = "comfirm_delete_file_steetalert2('" . $htmlID . "', '" . $delURLAjax . "', '" . $fileName . "', '" . $tmpPath . "', '" . $table . "', '" . $keyFind . "', '" . $keyValue . "', '" . $field . "', 'Are_You_Sure?')";
        $delete = ' <button type="button" class="btn btn-danger p-1" onClick="' . $onClick2 . '" title="Delete File"><i data-feather="trash-2"></i></button>';
    }
    if ($fileName != '') {
        if ($labelFileName == "")
            $labelFileName = $fileName;
        $str = "<b>" . $label . "</b><a class='' href='" . url($filePath . $fileName) . "' title='ទាញយក' target='_blank'>" . $labelFileName . "</a>" . $delete;
    }
    return $str;
}


function myUploadFileOnly(Request $request, $pathToUpload, $field_name, $key_id, $name = "")
{
    // Check if file request exists
    if ($request->file($field_name)) {
        // Ensure the upload folder exists
//        dd(public_path($pathToUpload));

        /** If Directory is not Found, Let's create the new ONE */
        if (!is_dir($pathToUpload)) {
            mkdir($pathToUpload, 0777, true);  // Creates the folder with appropriate permissions
        }

        $file = $request->file($field_name);
        $timestamp = $name ?: time();
        //        $timestamp = $name ? $name . "_" . time() : time();
        $fileName = $key_id . "_" . $timestamp . "." . $file->getClientOriginalExtension();

        // Move file to the specified public directory
        $file->move(public_path($pathToUpload), $fileName);

        return $fileName;
    }
    return null;
}

function myUploadFileTemplate(Request $request, $pathToUpload, $fieldName, $name = "")
{
    //    dd($request->all());
    // Check if file request exists
    if ($request->file($fieldName)) {
        // Ensure the upload folder exists

        /** If Directory is not Found, Let's create the new ONE */
        if (!is_dir($pathToUpload)) {
            mkdir($pathToUpload, 0777, true);  // Creates the folder with appropriate permissions
        }
        $file = $request->file($fieldName);
        $fileName = $name . "." . $file->getClientOriginalExtension();

        // Move file to the specified public directory
        $file->move(public_path($pathToUpload), $fileName);

        return $fileName;
    }
    return null;
}


/** កំណត់អ្នកផ្សះផ្សា (attendant_type_id = 6) និង អ្នកកត់ត្រា (attendant_type_id = 8) */
function updateOrCreateOfficer($case_id, $officer_id = 0, $attendant_type_id = 6)
{
    //dd($officer_id);
    if ($officer_id > 0) {
        $date_created = myDateTime();
        $officer = Officer::where("id", $officer_id)->first();

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
        //        dd($result);
    }
}

function myShowTextarea($name, $val = "", $row = 4, $required = "")
{
    return '<textarea rows="' . $row . '" name="' . $name . '" id="' . $name . '" class="form-control" ' . $required . '>' . $val . '</textarea>';
}

function arrayDistrictCustom($proID = 0, $disID = 0, $showDefault = 0, $defValue = 0, $defLabel = "សូមជ្រើសរើស")
{
    $data = AllowCompanySelfInsp::orderby("dis_khname", "ASC");
    if (!empty($proID)) {
        $data = $data->where("province_id", $proID);
    }
    if (!empty($disID)) {
        $data = $data->where("dis_id", $disID);
    }
    $data = $data->select(
        DB::raw("dis_khname AS name, dis_id AS id")
    )->pluck("name", "id")->toArray();
    if ($showDefault > 0) {
        $result = array($defValue => $defLabel);
        $result += $data;
        $data = $result;
    }
    //    dd($data);
    return $data;
}

function arrayProvinceCustom($proID = 0, $showDefault = 0, $defValue = 0, $defLabel = "សូមជ្រើសរើស")
{
    $data = Province::orderby("pro_khname", "ASC");
    if (!empty($proID)) {
        $data = $data->where('id', $proID);
    }
    $data = $data->select(
        DB::raw("pro_khname AS name, pro_id AS id")
    )
        //->limit(1000)
        ->pluck("name", "id")->toArray();
    if ($showDefault > 0) {
        $result = array($defValue => $defLabel);
        $result += $data;
        $data = $result;
    }
    return $data;
}

function arrCollectivesCause($causeID = 0, $showDefault = 0, $defValue = "0", $defLabel = "ជ្រើសរើសដើមហេតុនៃវិវាទ")
{
    $data = CollectivesCause::orderby("id", "ASC");
    if (!empty($causeID)) {
        $data = $data->where('id', $causeID);
    }
    $data = $data->select(
        DB::raw("cause_name AS name, id AS id")
    )
        //->limit(1000)
        ->pluck("name", "id")->toArray();
    if ($showDefault > 0) {
        $result = array($defValue => $defLabel);
        $result += $data;
        $data = $result;
    }
    //    dd($data);
    return $data;
}

function arrayCompanyTypeCustom($comTypeID = 0, $showDefault = 0, $defValue = 0, $defLabel = "សូមជ្រើសរើស")
{
    $data = CompanyType::orderby("id", "ASC");
    if (!empty($comTypeID)) {
        $data = $data->where('id', $comTypeID);
    }
    $data = $data->select(
        DB::raw("company_type_name AS name, id AS id")
    )
        //->limit(1000)
        ->pluck("name", "id")->toArray();
    if ($showDefault > 0) {
        $result = array($defValue => $defLabel);
        $result += $data;
        $data = $result;
    }
    //    dd($data);
    return $data;
}

function myArrBusinessActivity($busActivityID = 0, $showDefault = 0, $defValue = "0", $defLabel = "សូមជ្រើសរើស")
{
    $data = BusinessActivity::orderby("id", "ASC");
    if (!empty($busActivityID)) {
        $data = $data->where('id', $busActivityID);
    }
    $data = $data->select(
        DB::raw("bus_khmer_name AS name, id AS id")
    )
        ->pluck("name", "id")->toArray();
    if ($showDefault > 0) {
        $result = array($defValue => $defLabel);
        $result += $data;
        $data = $result;
    }
    return $data;
}

function myArraySectorCustom($sectorID = 0, $showDefault = 0, $defValue = 0, $defLabel = "សូមជ្រើសរើស")
{
    $data = Sector::orderby("id", "ASC");
    if (!empty($sectorID)) {
        $data = $data->where('id', $sectorID);
    }
    $data = $data->select(
        DB::raw("sector_name AS name, id AS id")
    )
        //->limit(1000)
        ->pluck("name", "id")->toArray();
    if ($showDefault > 0) {
        $result = array($defValue => $defLabel);
        $result += $data;
        $data = $result;
    }
    //    dd($data);
    return $data;
}
function myArrUserType($catID = 0, $defValue = 0, $defLabel = "សូមជ្រើសរើស")
{
    $data = array();
    $loginCatID = auth()->user()->k_category; // Get k_category from current login user
    $query = RolekParents::query();
    if ($loginCatID < 3) {
        //        dd('hee');
        $query = $query->where('id', '>', $loginCatID);
    } elseif ($loginCatID == 3) {
        $query = $query->where('id', '>=', $loginCatID);
    } else {
        return $data;
    }

    if (!empty($catID)) {
        $query = $query->where('id', $catID);
    }
    if ($defValue > 0) {
        $data['0'] = $defLabel;
    }
    $data += $query->pluck("k_category_name", 'id')->toArray();
    return $data;
}
function myArrOfficerRole($roleID = 0, $defValue = 0, $defLabel = "សូមជ្រើសរើស")
{
    $data = array();
    $query = OfficerRole::orderBy('id');
    if (!empty($roleID)) {
        $query = $query->where('id', $roleID);
    }
    if ($defValue > 0) {
        $data['0'] = $defLabel;
    }
    $data += $query->pluck("officer_role", 'id')->toArray();
    return $data;
}
function myArrOfficerRoleX($catID = 0, $defValue = 0, $defLabel = "សូមជ្រើសរើស")
{
    $data = array();
    $query = OfficerRole::orderBy('id');
    if ($catID == 0) {
        $query = $query->query();
    } elseif ($catID == 3) {
        $loginOfficerRole = auth()->user()->officer_role_id; // Get role from current login user
        if ($loginOfficerRole == 3) { //ប្រធានការិយាល័យវិវាទការងារទី១'
            $query = $query->whereIn('id', [7, 11]);
        } elseif ($loginOfficerRole == 4) { //ប្រធានការិយាល័យវិវាទការងារទី២
            $query = $query->whereIn('id', [8, 12]);
        } elseif ($loginOfficerRole == 5) { //ប្រធានការិយាល័យវិវាទការងារទី៣
            $query = $query->whereIn('id', [9, 13]);
        } elseif ($loginOfficerRole == 6) { //ប្រធានការិយាល័យវិវាទការងារទី៤
            $query = $query->whereIn('id', [10, 14]);
        }
    } else {
        return $data;
    }
    if ($defValue > 0) {
        $data['0'] = $defLabel;
    }
    $data += $query->pluck("officer_role", 'id')->toArray();
    return $data;
}
function myArrUserRole($catID = 0, $defValue = 0, $defLabel = "សូមជ្រើសរើស")
{
    $data = array();
    $query = Role::query();
    if ($catID < 3) {
        $query = $query->where('id', $catID);
    } elseif ($catID == 3) {
        $loginUserRole = auth()->user()->role_id; // Get role from current login user
        if ($loginUserRole < 3) { // Master, Admin
            $query = $query->where('id', '>=', 3);
        } else {
            $query = $query->where('id', '>', $loginUserRole);
        }
    } else {
        return $data;
    }


    if ($defValue > 0) {
        $data['0'] = $defLabel;
    }
    $data += $query->pluck("name", 'id')->toArray();
    return $data;
}
function myArrProvince($proID = 0, $defValue = 0, $defLabel = "សូមជ្រើសរើស", $provinceONLY = 0)
{
    $data = array();
    $query = Province::query();

    if (!empty($proID)) {
        $query = $query->where('id', $proID);
    }
    if ($provinceONLY == 1) {
        $query = $query->whereBetween('id', [1, 25]);
    }
    if ($defValue > 0) {
        $data['0'] = $defLabel;
    }
    $data += $query->pluck("pro_khname", 'id')->toArray();
    return $data;
}


function myArraySector($showDefault = 0, $defValue = "0", $defLabel = "សូមជ្រើសរើស")
{
    $data = Sector::orderby("id", "ASC")
        ->select(
            DB::raw("sector_name AS name, id AS id")
        )
        //->limit(1000)
        ->pluck("name", "id")->toArray();
    if ($showDefault > 0) {
        $result = array($defValue => $defLabel);
        $result += $data;
        $data = $result;
    }
    return $data;
}
function arrCaseType($caseType = 0, $showDefault = 0, $defValue = "0", $defLabel = "សូមជ្រើសរើស")
{
    $data = CaseType::orderby("id", "ASC");
    if (!empty($caseType)) {
        $data = $data->where('id', $caseType);
    }
    $data = $data->select(
        DB::raw("case_desc AS name, id AS id")
    )
        //->limit(1000)
        ->pluck("name", "id")->toArray();
    if ($showDefault > 0) {
        $result = array($defValue => $defLabel);
        $result += $data;
        $data = $result;
    }
    return $data;
}
function myArrayCaseType($showDefault = 0, $defValue = "0", $defLabel = "សូមជ្រើសរើស")
{
    $data = CaseType::orderby("id", "ASC")
        ->select(
            DB::raw("case_desc AS name, id AS id")
        )
        //->limit(1000)
        ->pluck("name", "id")->toArray();
    if ($showDefault > 0) {
        $result = array($defValue => $defLabel);
        $result += $data;
        $data = $result;
    }
    return $data;
}
function myToolTip($text = "មិនទាន់មានពត៌មានលម្អិត", $header_text = "សេចក្តីពន្យល់", $btnColor = "")
{
    $id = Str::random(5);
    $btnClass = "label-success";
    if ($btnColor == "red") {
        $btnClass = "label-danger";
    }
    return <<<HTML
            <button class="badge $btnClass border-0" type="button" data-bs-toggle="modal" data-bs-target="#$id">?</button>
            <div class="modal fade" id="$id" tabindex="-1" role="dialog" aria-labelledby="grid-modal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-purple fw-bold">$header_text</h5>
                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body grid-showcase mb-2">
                            <div class="container-fluid bd-example-row">
                                <div class="row">
                                    <div class="col-12 text-black text-hanuman-17" style="line-height: 29px;">$text</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        HTML;
}
function arrayOfficerCaseInHandByDomainCtrlOptimized($domainCtrlID, $showDefault = 0, $defValue = "0", $defLabel = "សូមជ្រើសរើស")
{
    $officers = OfficerRole::with([
        'officers.casesOfficers.case.log6Latest.detail6'
    ])->where('domain_id', $domainCtrlID)->orderBy('id')->get();

    $otherOfficers = OfficerRole::with([
        'officers.casesOfficers.case.log6Latest.detail6'
    ])->where('domain_id', '<>', $domainCtrlID)->orderBy('id')->get();

    $data = processOfficersOptimized($officers, $domainCtrlID, 1);
    $data += processOfficersOptimized($otherOfficers, $domainCtrlID, 2);

    if ($showDefault > 0) {
        $data = [$defValue => $defLabel] + $data;
    }

    return $data;
}
function arrayOfficerCaseInHandByDomainCtrl($domainCtrlID, $showDefault = 0, $defValue = "0", $defLabel = "សូមជ្រើសរើស"): array
{
    $data = [];
    $officers = OfficerRole::where('domain_id', $domainCtrlID)->orderBy("id", "ASC")->get();
    $otherOfficers = OfficerRole::where('domain_id', '<>', $domainCtrlID)->orderBy("id", "ASC")->get();

    //    dd($otherOfficers);
    $data = processOfficers($officers, $domainCtrlID, 1);
    $data +=  processOfficers($otherOfficers, $domainCtrlID, 2);
    //        dd($data);
    //$processOfficers($otherOfficers, 2);
    //Use Anonymous Functions as Sub Procedure for Another Calls
    //    $processOfficers = function ($officers) use (&$data, $opt) {
    //
    //    };

    if ($showDefault > 0) {
        $result = array($defValue => $defLabel);
        $result += $data;
        $data = $result;
    }

    return $data;
}

function processOfficersOptimized($officerRoles, $domainCtrl, $opt = 1)
{
    $data = [];
    $domainLabel = $opt == 1 ? " (ក្នុងដែនទី" . Num2Unicode($domainCtrl) . ")" : "";

    foreach ($officerRoles as $role) {
        foreach ($role->officers as $officer) {
            $counter = 0;
            foreach ($officer->casesOfficers as $rowCase) {
                if ($rowCase->attendant_type_id == 6 && $rowCase->case && $rowCase->case->case_closed == 0) {
                    $log6 = $rowCase->case->log6Latest;
                    if (!empty($log6)) {
                        $log6StatusID = $log6->detail6->status_id ?? null;
                        if (!empty($log6StatusID) && $log6StatusID <> 2) {
                            $counter++;
                        }
                    } else {
                        $counter++;
                    }
                }
            }
            $label = $counter == 0
                ? " (គ្មានបណ្តឹងក្នុងដៃ)"
                : " (កំពុងកាន់ " . Num2Unicode($counter) . " បណ្តឹង)";
            $data[$officer->id] = $officer->officer_name_khmer . $label . $domainLabel;
        }
    }

    return $data;
}


function processOfficers($officers, $domainCtrl, $opt = 1)
{
    //    dd($officers);
    $data = [];
    $domainLabel = $opt == 1 ? " (ក្នុងដែនទី" . Num2Unicode($domainCtrl) . ")" : "";
    foreach ($officers as $row) {
        $counter = 0;
        foreach ($row->officers as $officer) {
            if (count($officer->casesOfficers) > 0) {
                foreach ($officer->casesOfficers as $rowCase) {
                    if ($rowCase->attendant_type_id == 6 && $rowCase->case && $rowCase->case->case_closed == 0) {
                        $log6 = $rowCase->case->log6Latest;
                        if (!empty($log6)) {
                            $log6Detail = $rowCase->case->log6Latest->detail6;
                            if ($log6Detail->status_id <> 2) {
                                $counter++;
                            }
                        } else {
                            $counter++;
                        }
                    }
                }
            }
            if ($counter == 0) {
                $data[$officer->id] = $officer->officer_name_khmer . " (គ្មានបណ្តឹងក្នុងដៃ)" . $domainLabel;
            } else {
                $data[$officer->id] = $officer->officer_name_khmer . " (កំពុងកាន់ " . Num2Unicode($counter) . " បណ្តឹង)" . $domainLabel;
            }
            $counter = 0;
        }
    }
    return $data;
}

//Delete Data from one Table
function delete($tbl, $arr_condition)
{
    return DB::table($tbl)
        ->where($arr_condition)
        ->delete();
    //    try {
    //        DB::beginTransaction();
    //        DB::table($tbl)
    //            ->where($arr_condition)
    //            ->delete();
    //        DB::commit();
    //        return TRUE;
    //
    //    } catch (Exception $e) {
    //        DB::rollback();
    //        throw $e;
    //        return FALSE;
    //    }
} //end function

function getAllDisIDinCommune($domainID = 0, $proID = 0)
{
    $disIDs = array();
    $data = DomainCommune::where('province_id', $proID)->where('domain_id', $domainID)->select('district_id')->distinct();
    foreach ($data->get() as $dis) {
        $disIDs[] = $dis->district_id;
    }
    return $disIDs;
}

function getAllDisIDInDomain($proID = 0, $domainID = 0)
{
    $disIDs = array();
    $arrComs = array();
    $data = DomainDistrict::with(['domainCommune', 'commune']);
    if (!empty($proID)) {
        $data = $data->where('province_id', $proID);
    }
    if (!empty($domainID)) {
        $data = $data->where('domain_id', $domainID);
    }
    //    $disList = District::where('province_id', $proID)->pluck('dis_id')->toArray();
    foreach ($data->get() as $dDis) {
        /** @var DomainDistrict $dDis */
        //        $comList = Commune::where('district_id', $dDis->district_id)->pluck('com_id')->toArray();
        $comList = $dDis->commune->pluck('com_id')->toArray();

        if (count($dDis->domainCommune) == 0) {
            $disIDs[] = $dDis->district_id;
        } else {
            //            $arrComs1 = DomainCommune::where('district_id', $dDis->district_id)->pluck('commune_id')->toArray();
            $arrComs = $dDis->domainCommune->pluck('commune_id')->toArray();
        }
        $arrComCompare = array_diff($comList, $arrComs);

        if (empty($arrComCompare)) {
            $disIDs[] = $dDis->district_id;
        }
    }
    //    dd($disIDs);
    return $disIDs;
}
function getAllProIDInDomain($domainID = 0)
{

    $proIDs = array();
    $disIDs = array();
    $data = DomainProvince::with(['domainDistrict']);
    if (!empty($domainID)) {
        $data = $data->where('domain_id', $domainID);
    }
    foreach ($data->get() as $dPro) { //loop all provinces in each domain
        /** @var DomainProvince $dPro */
        $subPro = $dPro->domainDistrict;
        //        $subPro = DomainDistrict::where('province_id', $dPro->province_id)->get(); //Verify in DistrictDomain
        if ($subPro->count() == 0) { //check if province got NO sub (domainDistrict)
            $proIDs[] = $dPro->province_id;
            //            dd($subPro->count());
            //            if (count($subPro) == 0) {
            //                $proIDs[] = $dPro->province_id;
            //            }
        } else { //domainProvince got SUB (domainDistrict)
            //Retrive all districts from specific Province
            //            $disList = District::where('province_id', $dPro->province_id)->pluck('dis_id')->toArray();
            $disList = $dPro->district->pluck('dis_id')->toArray();
            $arrComs = array();
            foreach ($dPro->domainDistrict as $dDis) { //domainDistrict got SUB (domainCommune)
                /** @var DomainDistrict $dDis */
                //                $comList = Commune::where('district_id', $dDis->district_id)->pluck('com_id')->toArray();
                $comList = $dDis->commune->pluck('com_id')->toArray();
                if (count($dDis->domainCommune) == 0) { // No sub in DomainCommune
                    $disIDs[] = $dDis->district_id;
                } else { //Have Sub in DomainCommune
                    //                    $arrComs = DomainCommune::where('district_id', $dDis->district_id)->pluck('commune_id')->toArray();
                    $arrComs = $dDis->domainCommune->pluck('commune_id')->toArray();
                }
                $arrComCompare = array_diff($comList, $arrComs);
                if (empty($arrComCompare)) {
                    $disIDs[] = $dDis->district_id;
                }
            }
            //                dd($disIDs);
            $arrDisCompare = array_diff($disList, $disIDs);
            if (empty($arrDisCompare)) {
                $proIDs[] = $dPro->province_id;
            }
        }
    }
    //    dd($proIDs);
    return $proIDs;
}
function getAllDisIDInProDomain($proID = 0, $domainID = 0)
{
    $disIDs = array();
    $data = DB::table('tbl_domain_district');
    if (!empty($proID)) {
        $data = $data->where('province_id', $proID);
    }

    if (!empty($domainID)) {
        $data = $data->where('domain_id', $domainID);
    }

    foreach ($data->get() as $dis) {
        /** @var \stdClass $dis */
        $disIDs[] = $dis->district_id;
    }
    return $disIDs;
}

function getAllComIDInDomain($proID = 0, $disID = 0, $domainID = 0)
{
    $comIDs = array();
    //    $data = DB::table('tbl_domain_commune');
    $data = DomainCommune::with([]);
    if (!empty($proID)) {
        $data = $data->where('province_id', $proID);
    }
    if (!empty($domainID)) {
        $data = $data->where('domain_id', $domainID);
    }
    if (!empty($disID)) {
        $data = $data->where('district_id', $disID);
    }
    foreach ($data->get() as $com) {
        /** @var DomainCommune $com */
        $comIDs[] = $com->commune_id;
    }
    return $comIDs;
}

function arrayCommuneExcludedByDisID($disID = 0, $excludedComID = array(0), $showDefault = 0, $defValue = "0", $defLabel = "សូមជ្រើសរើស")
{
    $data = DB::table('commune')->orderby("com_khname", "ASC")
        ->select(
            DB::raw("com_khname AS name, com_id AS id")
        )
        ->where('district_id', $disID)
        ->whereNotIn('com_id', $excludedComID)
        //->limit(1000)
        ->pluck("name", "id")->toArray();
    if ($showDefault > 0) {
        $result = array($defValue => $defLabel);
        $result += $data;
        $data = $result;
    }
    return $data;
}

function arrayDistrictsExcludedByDisID($proID = 0, $excludedDisID = array(), $showDefault = 0, $defValue = "0", $defLabel = "សូមជ្រើសរើស")
{
    //    dd($excludedDisID);
    $data = DB::table('district')->orderBy("dis_khname", "ASC")
        ->select(
            DB::raw("dis_khname AS name, dis_id AS id")
        )
        ->where('province_id', $proID)
        ->whereNotIn('dis_id', $excludedDisID)
        //->limit(1000)
        ->pluck("name", "id")->toArray();
    if ($showDefault > 0) {
        $result = array($defValue => $defLabel);
        $result += $data;
        $data = $result;
    }
    return $data;
}

function arrayProvincesExcludedByProID1($excludedProID = array(0), $showDefault = 0, $defValue = "0", $defLabel = "សូមជ្រើសរើស")
{
    $data = DB::table('province')->orderBy("pro_khname", "ASC")
        ->select(
            DB::raw("pro_khname AS name, pro_id AS id")
        )
        ->whereNotIn('pro_id', $excludedProID)
        //->limit(1000)
        ->pluck("name", "id")->toArray();
    if ($showDefault > 0) {
        $result = array($defValue => $defLabel);
        $result += $data;
        $data = $result;
    }
    return $data;
}
function arrayProvincesExcludedByProID(
    $excludedProID = [0],
    $showDefault = 0,
    $defValue = "0",
    $defLabel = "សូមជ្រើសរើស",
    $provinceONLY = 0
) {
    $query = DB::table('province')
        ->orderBy("pro_khname", "ASC")
        ->select(DB::raw("pro_khname AS name, pro_id AS id"))
        ->whereNotIn('pro_id', $excludedProID);

    // 🔹 Add province-only filter if enabled
    if ($provinceONLY == 1) {
        $query->whereBetween('pro_id', [1, 25]);
    }

    // 🔹 Get data
    $data = $query->pluck("name", "id")->toArray();

    // 🔹 Add default option if required
    if ($showDefault > 0) {
        $result = [$defValue => $defLabel];
        $data = $result + $data;
    }

    return $data;
}



function updateOrInsert($tblName, $data)
{
    return DB::table($tblName)
        ->updateOrInsert($data);
}
/**
 * Insert Data into Table
 *
 */
function insert($tblName, $data)
{
    try {
        DB::beginTransaction();
        $id = DB::table($tblName)->insertGetId($data);
        return $id;
    } catch (Exception $e) {
        DB::rollback();
        throw $e;
        return false;
    }
}
/**
 * Update Data in Table
 *
 */
function update($tblName, $data, $arr_condtion)
{
    $affectedRow = DB::table($tblName)
        ->where($arr_condtion)
        ->update($data);
    return $affectedRow;
}

/**
 * Get result as Array
 * @param string $table
 * @param string $fields
 * @param array $where
 * @param string $orderBy
 */
function queryArrOneTable($table, $field, $where = false, $orderBy = false, $defFirstKey = false, $firstKeyLabel = "")
{
    $query = DB::table($table);
    if ($where) {
        $query->where($where);
    }
    if ($orderBy) {
        $query->orderBy($orderBy, 'asc');
    }
    if ($defFirstKey) {
        if ($defFirstKey == "0" || $defFirstKey == "true" || is_numeric($defFirstKey)) {
            $arr_result['0'] = $firstKeyLabel;
        } else {
            $arr_result[$defFirstKey] = $firstKeyLabel;
        }
    } else {
        $arr_result = array();
    }

    $arr_result += $query->pluck($field, 'id')->toArray();

    //$arr_result[$defFirstKey]= $firstKeyLabel;

    //        $query = DB::table($table)->select('*');
    //        if($where){
    //            $query->where($where);
    //        }
    //        if($orderBy){
    //            $query->orderBy($orderBy, 'asc');
    //        }
    //        if($defFirstKey){
    //            $arr_result['null']= $firstKeyLabel;
    //        }
    //        foreach ($query->get() as $row){
    //            $arr_result[$row->id] = $row->$field;
    //        }

    return $arr_result;
}




/**
 * Get query result from ONE table
 * @param string $table
 * @param string $fields
 * @param array $where
 * @return \Illuminate\Support\Collection
 */
function queryOneTable(string $table, string $fields, array $where)
{
    $query = DB::table($table)
        ->where($where)
        ->select($fields)
        ->get();
    return $query;
}

/**
 * Query only ONE record from table
 * @param string $table
 * @param string $fields
 * @param array $where
 */
function queryOneRecord(string $table, string $fields, array $where)
{
    $query = DB::table($table)
        ->where($where)
        ->select($fields)
        ->first();
    return $query;
}

/**
 * Count number of record
 * @param string $table
 * @param array $where
 */

function count_record($table, $where = false)
{
    $query = DB::table($table);
    if ($where) $query->where($where);
    return $query->count();
}

/**
 * Select only ONE record from given Table
 * @param string $table
 * @param string $fields
 * @param array $where
 * @param string $orderBy
 */

function select_one_record($table, $fields, $where = false, $orderBy = false)
{
    $query = DB::table($table)->select($fields);
    if ($where) $query->where($where);
    if ($orderBy) $query->orderBy($orderBy);
    return $query->first()->$fields;
}
function arrayOfficerCaseInHandByDomainCtrl1($domainCtrlID, $showDefault = 0, $defValue = "0", $defLabel = "សូមជ្រើសរើស")
{
    $data = [];
    $officers = OfficerRole::where('domain_id', $domainCtrlID)->orderBy("id", "ASC")->get();
    $otherOfficers = OfficerRole::where('domain_id', '<>', $domainCtrlID)->orderBy("id", "ASC")->get();

    //Use Anonymous Functions as Sub Procedure for Another Calls
    $processOfficers = function ($officers) use (&$data) {
        foreach ($officers as $row) {
            $counter = 0;
            foreach ($row->officers as $officer) {
                if (count($officer->casesOfficers) > 0) {
                    foreach ($officer->casesOfficers as $rowCase) {
                        if (!empty($rowCase->case->log6Latest)) {
                            if ($rowCase->case->log6Latest->detail6->status_id <> 2) {
                                $counter++;
                            }
                        } else {
                            $counter++;
                        }
                    }
                }
                if ($counter == 0) {
                    $data[$officer->id] = $officer->officer_name_khmer . " ( គ្មានបណ្តឹងក្នុងដៃ )";
                } else {
                    $data[$officer->id] = $officer->officer_name_khmer . " (កំពុងកាន់ " . Num2Unicode($counter) . " បណ្តឹង )";
                }
            }
        }
    };
    $processOfficers($officers);
    $processOfficers($otherOfficers);
    return $data;
}



function showCaseDomainID($caseID)
{
    $caseCom = CaseCompany::where('case_id', $caseID)->select('domain_id')->first();
    return $caseCom->domain_id ?? 0;
}
/** Get Domain ID By CaseID */
function getCaseDomainControl($caseID)
{
    $case = Cases::where('id', $caseID)
        ->select('id', 'company_id') // Specify the columns we want
        ->first();

    // Let's filter some columns in eloquent relation
    $comCase = $case->caseCompany()->select('id', 'log5_province_id', 'log5_district_id', 'log5_commune_id')->first();
    if (empty($comCase)) {
        return 0;
    }
    $domainPro = $comCase->domainProvince;
    $domainDis = $comCase->domainDistrict;
    $domainCom = $comCase->domainCommune;
    $domainID = 0;

    if (count($domainPro) > 0) {
        foreach ($domainPro as $dPro) {
            if (count($domainPro) == 1) {
                $domainID = $dPro->domain_id;
            } elseif (count($domainPro) > 1 && count($domainDis) > 0) {
                foreach ($domainDis as $dDis) {
                    if (count($domainDis) == 1) {
                        $domainID = $dDis->domain_id;
                    } elseif (count($domainDis) > 1 && count($domainCom) > 0) {
                        foreach ($domainCom as $dCom) {
                            $domainID = $dCom->domain_id;
                        }
                    }
                }
            }
        }
    }
    return $domainID;
}

/** Get Domain ID by Given CaseID */
function getCaseDomainControlX($caseID)
{
    //    $domainControl = 0;
    //    $case = Cases::where('id', $caseID)->first();
    $case = Cases::where('id', $caseID)
        ->select('id', 'company_id') // Specify the columns we want
        ->first();

    //    $caseCom = $case->caseCompany;


    // Let's filter some columns in eloquent relation
    $caseCom = $case->caseCompany()->select('id', 'log5_province_id', 'log5_district_id', 'log5_commune_id')->first();


    $proID = $caseCom->log5_province_id ?? 0;
    $disID = $caseCom->log5_district_id ?? 0;
    $comID = $caseCom->log5_commune_id ?? 0;



    //Domain Controls
    //    $provinces = [];
    //    $districts = [];
    //    $communes = [];

    //Add All Provinces + Districts + Communes Into Domain 1 To 4
    for ($i = 1; $i <= 4; $i++) {
        $provinces[] = DomainProvince::where('domain_id', $i)->pluck('province_id')->toArray();
        $districts[] = DomainDistrict::where('domain_id', $i)->pluck('district_id')->toArray();
        $communes[] = DomainCommune::where('domain_id', $i)->pluck('commune_id')->toArray();
    }

    if ($proID == 12) {
        foreach ($districts as $disINDEX => $district) {
            if (in_array($disID, $district)) {
                //                dd($districts);
                $domainControl = $disINDEX + 1;
                //                dd($domainControl);
                break;
            } else {
                foreach ($communes as $comINDEX => $commune) {
                    if (in_array($comID, $commune)) {
                        $domainControl = $comINDEX + 1;
                        break;
                    }
                }
            }
        }
    } else {
        foreach ($provinces as $proINDEX => $province) {
            if (in_array($proID, $province)) {
                $domainControl = $proINDEX + 1;
                break;
            }
        }
    }


    return $domainControl;
}
function getCaseDomainControl1($caseID): int
{
    $office = 0;
    $case = Cases::where('id', $caseID)->first();
    $provinces = [
        [7, 8, 9, 21, 22, 23], //7 កំពត  //8 កណ្ដាល  //9 កោះកុង  //21 តាកែវ  //22 ឧត្ដរមានជ័យ //23 កែប
        [2, 4, 13, 16, 17, 24], //2 បាត់ដំបង   //4 កំពង់ឆ្នាំង  //13  ព្រះវិហារ  //16  រតនគិរី  //17 សៀមរាប  //24 ប៉ៃលិន
        [3, 14, 18, 19, 20, 25], //14 ព្រៃវែង  //18 ព្រះសីហនុ  //19 ស្ទឹងត្រែង  //20 ស្វាយរៀង  //25 ត្បូងឃ្មុំ
        [1, 5, 6, 10, 11, 15] //5 កំពង់ស្ពឺ  //6  កំពង់ធំ  //10  ក្រចេះ  //11 មណ្ឌលគិរី  //15  ពោធិ៍សាត់
    ];

    $districts = [
        [1201, 1202, 1206, 1212], //1201 ចំការមន //1202 ដូនពេញ //1206 មានជ័យ //1212 ច្បារអំពៅ
        [1210, 1213], //1210 ជ្រោយចង្វារ //1213 បឹងកេងកង
        [1204, 1205, 1207, 1208], //1204 ទួលគោក //1205 ដង្កោ //1207 ឫស្សីកែវ //1208 សែនសុខ
        [1203, 1211] //1203 ៧មករា  //1211 ព្រែកព្នៅ
    ];


    $proID = $case->caseCompany->log5_province_id ?? 0;
    $disID = $case->caseCompany->log5_district_id ?? 0;
    $comID = $case->caseCompany->log5_commune_id ?? 0;
    if ($proID == 12) {
        foreach ($districts as $index => $district) {
            if (in_array($disID, $district)) {
                $office = $index + 1;
                break;
            }
        }
        if (!empty($comID) && $disID == 1209) { //1209 ពោធិ៍សែនជ័យ

            $com02 = [120901, 120906, 120917, 120918];
            $com04 = [120914, 120915, 120916];

            if (in_array($comID, $com02)) {
                $office = 2;
            } elseif (in_array($comID, $com04)) {
                $office = 4;
            }
        } elseif (!empty($comID) && $disID == 1214) {  //1214  ខណ្ឌកំបូល

            $com22 = [121401, 121403, 121404];
            $com44 = [121402, 121405, 121406, 121407];

            if (in_array($comID, $com22)) {
                $office = 2;
            } elseif (in_array($comID, $com44)) {
                $office = 4;
            }
        }
    } else {
        foreach ($provinces as $index => $province) {
            if (in_array($proID, $province)) {
                $office = $index + 1;
                break;
            }
        }
    }

    return $office;
}

function getCaseDomainControl2($caseID): int
{
    $case = Cases::where('id', $caseID)->first();
    //    dd($case->caseCompany);
    $pro01 = array(7, 8, 9, 21, 22, 23);  //7 កំពត  //8 កណ្ដាល  //9 កោះកុង  //21 តាកែវ  //22 ឧត្ដរមានជ័យ //23 កែប
    $pro02 = array(2, 4, 13, 16, 17, 24);  //2 បាត់ដំបង   //4 កំពង់ឆ្នាំង  //13  ព្រះវិហារ  //16  រតនគិរី  //17 សៀមរាប  //24 ប៉ៃលិន
    $pro03 = array(3, 14, 18, 19, 20, 25);  //3 កំពង់ចាម  //14 ព្រៃវែង  //18 ព្រះសីហនុ  //19 ស្ទឹងត្រែង  //20 ស្វាយរៀង  //25 ត្បូងឃ្មុំ
    $pro04 = array(1, 5, 6, 10, 11, 15);  //1 បន្ទាយមានជ័យ  //5 កំពង់ស្ពឺ  //6  កំពង់ធំ  //10  ក្រចេះ  //11 មណ្ឌលគិរី  //15  ពោធិ៍សាត់

    $dis01 = array(1201, 1202, 1206, 1212);  //1201 ចំការមន //1202 ដូនពេញ //1206 មានជ័យ //1212 ច្បារអំពៅ
    $dis02 = array(1210, 1213);  //1210 ជ្រោយចង្វារ //1213 បឹងកេងកង
    $dis03 = array(1204, 1205, 1207, 1208);  //1204 ទួលគោក //1205 ដង្កោ //1207 ឫស្សីកែវ //1208 សែនសុខ
    $dis04 = array(1203, 1211); //1203 ៧មករា //1209 ពោធិ៍សែនជ័យ //1211 ព្រែកព្នៅ

    $office = 0;
    $proID = !empty($case->caseCompany->log5_province_id) ? $case->caseCompany->log5_province_id : 0;
    $disID = !empty($case->caseCompany->log5_district_id) ? $case->caseCompany->log5_district_id : 0;
    $comID = !empty($case->caseCompany->log5_commune_id) ? $case->caseCompany->log5_commune_id : 0;
    if ($proID == 12) {
        if (in_array($disID, $dis01)) {
            $office = 1;
        } elseif (in_array($disID, $dis02)) {
            $office = 2;
        } elseif (in_array($disID, $dis03)) {
            $office = 3;
        } elseif (in_array($disID, $dis04)) {
            $office = 4;
        } elseif ($disID == 1209) { //1209 ពោធិ៍សែនជ័យ
            if (!empty($comID)) {
                //ពោធិសែនជ័យ (ខាងជើងផ្លូវជាតិលេខ ៤)
                $com02 = array(120901, 120906, 120917, 120918);   //120901 ត្រពាំងក្រសាំង  //120906  សំរោងក្រោម  //120917  កាកាបទី១   //120918   កាកាបទី២

                //ពោធិសែនជ័យ (ខាងត្បូងផ្លូវជាតិលេខ ៤ និងតំបន់ផ្លូវវេងស្រេង)
                $com04 = array(120914, 120915, 120916);   //120914  ចោមចៅទី១  //120915  ចោមចៅទី២   //120916  ចោមចៅទី៣

                if (in_array($comID, $com02)) {
                    $office = 2;
                } elseif (in_array(($comID), $com04)) {
                    $office = 4;
                }
            }
        } elseif ($disID == 1214) { //1214  ខណ្ឌកំបូល
            if (!empty($comID)) {
                //ខណ្ឌកំបូល (ខាងជើងផ្លូវជាតិលេខ ៤)
                $com22 = array(121401, 121403, 121404);  //121401  កំបូល  //121403  ឪឡោក  //121404  ស្នោរ

                //កំបូល (ខាងត្បូងផ្លូវជាតិលេខ ៤)
                $com44 = array(121402, 121405, 121406, 121407);  //121402  កន្ទោក  //121405  ភ្លើងឆេះរទេះ   //121406  បឹងធំ   //121407  ប្រទះឡាង

                if (in_array($comID, $com22)) {
                    $office = 2;
                } elseif (in_array($comID, $com44)) {
                    $office = 4;
                }
            }
        }
    } else {
        if (in_array($proID, $pro01)) {
            $office = 1;
        } elseif (in_array($proID, $pro02)) {
            $office = 2;
        } elseif (in_array($proID, $pro03)) {
            $office = 3;
        } elseif (in_array($proID, $pro04)) {
            $office = 4;
        }
    }
    //    echo "ខេត្ត/រាជធានី: ". $case->caseCompany->province->pro_khname." (".$case->caseCompany->log5_province_id.")"."<br/>"."ស្រុក/ខណ្ណ: ".$case->caseCompany->district->dis_khname. " (".$case->caseCompany->log5_district_id.")" ."<br/>". "ឃុំ/សង្កាត់: ". $case->caseCompany->commune->com_khname. " (".$case->caseCompany->log5_commune_id. ")";
    //Return Domain Control Office
    return $office;
}

function arrayOfficerRole($showDefault = 0, $defValue = "0", $defLabel = "សូមជ្រើសរើស"): array
{
    $data = OfficerRole::orderby("sort_by", "ASC")
        ->select(
            DB::raw("officer_role AS name, id AS id")
        )
        //->limit(1000)
        ->pluck("name", "id")->toArray();

    if ($showDefault > 0) {
        $result = array($defValue => $defLabel);
        $result += $data;
        $data = $result;
    }
    return $data;
}
