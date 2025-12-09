<?php

namespace App\Http\Controllers;

use App\Models\Cases;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpWord\Shared\ZipArchive;
use Symfony\Component\HttpFoundation\StreamedResponse;



class ExportExcelController extends Controller
{

    function queryCasesEloquent(){
        $user = auth()->user();
        return $this->getOrSearchEloquent($user);
    }
    function getOrSearchEloquent($user){
//        dd(request()->all());
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
            request('csic1'), request('csic2'), request('csic3'),
            request('csic4'), request('csic5')
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
        $cases->when($domainID, fn($q) =>
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
                    $sub->where(DB::raw("CONCAT('x',company_id,'x', company_name_khmer,'', COALESCE(company_name_latin, 'NULL'), COALESCE(company_register_number, 'NULL'), COALESCE(company_tin, 'NULL') )"), "LIKE", "%".$search."%");
                })->orWhereRelation('disputant', function ($sub) use ($search) {
//                        $sub->where(DB::raw("CONCAT_WS('', id, name, COALESCE(name_latin, ''), COALESCE(id_number, ''))"), 'LIKE', "%{$search}%");
                    $sub->where(DB::raw("CONCAT('x',id,'x', name,'', COALESCE(name_latin, 'NULL'), COALESCE(id_number, 'NULL') )"), "LIKE", "%".$search."%");
                });
            });
        });

        /** Advanced Search Filter Block */
        $cases->when($busActivity, fn($q) =>
        $q->whereHas('caseCompany', fn($sub) =>
        $sub->where('log5_business_activity', $busActivity))
        )->when($companyTypeID, fn($q) =>
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

        $cases = $cases->orderByDesc('id');
//        $cases->appends([
//            'json_opt'    => request('json_opt'),
//            'search'      => $search,
//            'inOutDomain' => $inOutDomain,
//            'domainID'    => $domainID,
//            'statusID'    => $statusID,
//            'stepID'      => $stepID,
//            'year'        => $year,
//        ]);

        return $cases;
    }
    function queryCases(){
        $sql = "SELECT c.* "
            . ", c_type.case_type_name " // ប្រភេទបណ្តឹង
            . ", dis.name as disputant_name " //កម្មករនិយោជិត
            . ", com.company_name_khmer as company_name " //រោងចក្រ សហគ្រាស Khmer
            . ", com.company_name_latin as company_name_latin " //រោងចក្រ សហគ្រាស Lain
            . ", pro.pro_khname as province_name"
            . ", dist.dis_khname as district_name"
            . ", comm.com_khname as commune_name"
            . ", officer6.officer_name_khmer AS officer_name " // មន្ត្រីផ្សះផ្សារ
            . ", officer8.officer_name_khmer AS officer_noter " // លេខាកត់ត្រា
            . ", sector.sector_name " // វិស័យ
            . ", com_type.company_type_name as com_type_name " // ប្រភេទសហគ្រាស
            . ", csic1.description_kh as csic1_name"
            . ", csic2.description_kh as csic2_name"
            . ", csic3.description_kh as csic3_name"
            . ", csic3.description_kh as csic4_name"
            . ", csic5.description_kh as csic5_name"
            . ", case_obj.objective_name as case_objective "

            . " FROM  tbl_case AS c "
            . " LEFT JOIN tbl_case_type as c_type ON c_type.id = c.case_type_id "

            // Disputant Info
            . " LEFT JOIN tbl_case_disputant as c_dis ON c_dis.case_id = c.id
                AND c.disputant_id = c_dis.disputant_id AND c_dis.attendant_type_id = 1 "
            . " LEFT JOIN tbl_disputant as dis ON dis.id = c_dis.disputant_id "

            // Company Info
            . " LEFT JOIN tbl_case_company as case_com ON case_com.case_id = c.id
                AND case_com.company_id = c.company_id
            "
            . " LEFT JOIN tbl_company as com ON com.company_id = case_com.company_id "

            // Company Province
            ." LEFT JOIN province as pro ON pro.id = case_com.log5_province_id "
            // Company District
            ." LEFT JOIN district as dist ON dist.dis_id = case_com.log5_district_id "
            // Company Commune
            ." LEFT JOIN commune as comm ON comm.com_id = case_com.log5_commune_id "

            // Officer In Case (Get The Latest Officer)
            ."
            LEFT JOIN (
                SELECT t1.*
                FROM tbl_case_officer t1
                INNER JOIN (
                    SELECT case_id, MAX(id) AS max_id
                    FROM tbl_case_officer
                    WHERE attendant_type_id = 6
                    GROUP BY case_id
                ) t2 ON t1.id = t2.max_id
            ) AS c_officer6 ON c_officer6.case_id = c.id
            LEFT JOIN tbl_officer AS officer6 ON officer6.id = c_officer6.officer_id
            "
            // Officer as Noter
            . " LEFT JOIN tbl_case_officer as c_officer8 ON c_officer8.case_id = c.id
                AND c_officer8.attendant_type_id = 8 "
            . " LEFT JOIN tbl_officer as officer8 ON officer8.id = c_officer8.officer_id "

            // Sector
            . " LEFT JOIN tbl_sector as sector ON sector.id = c.sector_id "
            // Company Type
            . " LEFT JOIN tbl_company_type as com_type ON com_type.id = c.company_type_id "


//            . " LEFT JOIN csic AS csic1
//            ON csic1.csic_1 = case_com.log5_csic_1 AND csic1.csic_2 IS NULL "

            // ✅ CSIC joins (each alias filters level) and fix collation error
            . "LEFT JOIN csic AS csic1
                  ON csic1.csic_1 COLLATE utf8mb4_unicode_ci = case_com.log5_csic_1 COLLATE utf8mb4_unicode_ci
                  AND csic1.csic_2 IS NULL"
            // ✅ CSIC Level 2 (safe join with subquery)
            . "           
                LEFT JOIN (
                  SELECT MIN(id) AS id, csic_2
                  FROM csic
                  WHERE csic_3 IS NULL
                  GROUP BY csic_2
                ) AS csic2_map ON csic2_map.csic_2 COLLATE utf8mb4_unicode_ci = case_com.log5_csic_2 COLLATE utf8mb4_unicode_ci
                LEFT JOIN csic AS csic2 ON csic2.id = csic2_map.id
            "
            // ✅ CSIC Level 3
            . "
                LEFT JOIN (
                  SELECT MIN(id) AS id, csic_3
                  FROM csic
                  WHERE csic_4 IS NULL
                  GROUP BY csic_3
                ) AS csic3_map ON csic3_map.csic_3 COLLATE utf8mb4_unicode_ci = case_com.log5_csic_3 COLLATE utf8mb4_unicode_ci
                LEFT JOIN csic AS csic3 ON csic3.id = csic3_map.id
            "
            // ✅ CSIC Level 4
            . "
                LEFT JOIN (
                  SELECT MIN(id) AS id, csic_4
                  FROM csic
                  WHERE csic_5 IS NULL
                  GROUP BY csic_4
                ) AS csic4_map ON csic4_map.csic_4 COLLATE utf8mb4_unicode_ci = case_com.log5_csic_4 COLLATE utf8mb4_unicode_ci
                LEFT JOIN csic AS csic4 ON csic4.id = csic4_map.id
            "
            // ✅ CSIC Level 5
            . "LEFT JOIN csic AS csic5
                  ON csic5.csic_5 COLLATE utf8mb4_unicode_ci = case_com.log5_csic_5 COLLATE utf8mb4_unicode_ci
                  "
//            ." LEFT JOIN csic AS csic2
//                    ON csic2.csic_2 COLLATE utf8mb4_unicode_ci = case_com.log5_csic_2 COLLATE utf8mb4_unicode_ci
//                   AND csic2.csic_3 IS NULL "

//            . " LEFT JOIN csic AS csic2
//                ON csic2.csic_2 COLLATE utf8mb4_unicode_ci = case_com.log5_csic_2 COLLATE utf8mb4_unicode_ci
//                AND csic2.csic_3 IS NULL "
//            . " LEFT JOIN csic AS csic3
//                ON csic3.csic_3 COLLATE utf8mb4_unicode_ci = case_com.log5_csic_3 COLLATE utf8mb4_unicode_ci
//                AND csic3.csic_4 IS NULL "

            // Case Objective
            . " LEFT JOIN tbl_objective_case as case_obj ON case_obj.id = c.case_objective_id ";


        $where = "WHERE c.case_type_id = 1 ";
        $oderBy = " ORDER BY c.id DESC ";
        $sql .= $where . $oderBy;
        $query = DB::select($sql);

        return $query;
    }
    // Using Eloquent
    public function exportCasesList(Request $request)
    {
//        dd($request->all());
//        $this->loadRunUnlimitedTime();
        $today = date('Y-m-d');
        $todayKH = date2Display($today);

        // Create a new spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        //dd($opt, $lvl_cd, $nea_position_agency);
        /** ============================================================================= */
        $header_row = 5;
        $header_row_m1 = $header_row - 1;
        $header_row_m2 = $header_row - 2;
        $row1 = $header_row + 1;
        $start_row = $row1;
        $i = 1;
        $endCol = "AC";
        /** Set Style Border */
        $styleArray = array(
            'borders' => array(
                'outline' => array(
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => array('argb' => '00000000'),
                ),
            ),
        );
        $Moul10Center = [
            'font' => [
//                'bold' => true,
                'size' => 10,
                'name' => 'Khmer OS Muol Light'
            ],
            'alignment' => [
//                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ]
        ];

        /** ============================ Table Title ================================ */
        $sheet->mergeCells('A' . $header_row_m2 . ':' . 'J' . $header_row_m2);
        $sheet->getStyle('A' . $header_row_m2)->applyFromArray($Moul10Center);
        $query = $this->queryCasesEloquent()->get();
        $total = $query->count();

        $titleSheet = "បញ្ជីពាក្យបណ្តឹង សរុបចំនួន៖ ".number2KhmerNumber($total)."  (គិតត្រឹមកាលបរិច្ឆេទ៖ " . $todayKH.")";
        $sheet->setCellValue('A' . $header_row_m2, $titleSheet);
        /** ============================ set table header ============================ */
        //exportBaseColumn($spreadsheet, $query, $header_row, $endCol);
        $sheet->setCellValue('A' . $header_row, "ល.រ");
        $sheet->setCellValue('B' . $header_row, "ប្រភេទពាក្យបណ្ដឹង");
        $sheet->setCellValue('C' . $header_row, "លេខសំណុំរឿង");
        $sheet->setCellValue('D' . $header_row, "ដើមបណ្តឹង");
        $sheet->setCellValue('E' . $header_row, "ចុងបណ្តឹង");
        $sheet->setCellValue('F' . $header_row, "ក្នុង-ក្រៅដែន");
        $sheet->setCellValue('G' . $header_row, "ស្ថិតក្នុងដែនការិយាល័យ");
        $sheet->setCellValue('H' . $header_row, "អ្នកផ្សះផ្សា");
        $sheet->setCellValue('I' . $header_row, "លេខាកត់ត្រា");
        $sheet->setCellValue('J' . $header_row, "វិស័យ");
        $sheet->setCellValue('K' . $header_row, "ប្រភេទសហគ្រាស");
        $sheet->setCellValue('L' . $header_row, "សកម្មភាពសេដ្ឋកិច្ចកម្រិតទី១");
        $sheet->setCellValue('M' . $header_row, "សកម្មភាពសេដ្ឋកិច្ចកម្រិតទី២");
        $sheet->setCellValue('N' . $header_row, "សកម្មភាពសេដ្ឋកិច្ចកម្រិតទី៣");
        $sheet->setCellValue('O' . $header_row, "សកម្មភាពសេដ្ឋកិច្ចកម្រិតទី៤");
        $sheet->setCellValue('P' . $header_row, "សកម្មភាពសេដ្ឋកិច្ចកម្រិតទី៥");
        $sheet->setCellValue('Q' . $header_row, "ខេត្ត-រាជធានី");
        $sheet->setCellValue('R' . $header_row, "ក្រុង-ស្រុក-ខ័ណ្ឌ");
        $sheet->setCellValue('S' . $header_row, "ឃុំ-សង្កាត់");
        $sheet->setCellValue('T' . $header_row, "កម្មវត្ថុបណ្ដឹង");
        $sheet->setCellValue('U' . $header_row, "អង្គហេតុនៃវិវាទ");
        $sheet->setCellValue('V' . $header_row, "ថ្ងៃខែឆ្នាំចូលបម្រើការងារ");
        $sheet->setCellValue('W' . $header_row, "ប្រភេទកិច្ចសន្យាការងារ");
        $sheet->setCellValue('X' . $header_row, "ប្រាក់ឈ្នួលប្រចាំខែ (ដុល្លារ)");
        $sheet->setCellValue('Y' . $header_row, "មូលហេតុចម្បងនៃវិវាទ");
        $sheet->setCellValue('Z' . $header_row, "សំណូមពររបស់អ្នកប្ដឹង");
        $sheet->setCellValue('AA' . $header_row, "កាលបរិច្ឆេទធ្វើបណ្ដឹង");
        $sheet->setCellValue('AB' . $header_row, "កាលបរិច្ឆេទប្តឹងទៅអធិការការងារ");
        $sheet->setCellValue('AC' . $header_row, "ស្ថានភាពពាក្យបណ្ដឹង");
//        $sheet->setCellValue('AD' . $header_row, "ឯកសារពាក្យបណ្ដឹង");

        $sheet->getColumnDimension('C')->setWidth(20);
        $excelCol = $this->excelCol();
        for ($k = 4; $k < 30; $k++) {
            $sheet->getColumnDimension($excelCol[$k])->setWidth(22);
        }

        $sheet->getStyle('A' . $header_row . ':' . $endCol . $header_row)->applyFromArray($this->tableCellStyle(10, true));
        $spreadsheet->getActiveSheet()->getRowDimension($start_row)->setRowHeight(20); //set cell height

        /** ======================== Condition ===================== */
        foreach ($query as $row) {
            $sheet->getStyle('A' . $start_row . ':' . $endCol . $start_row)->applyFromArray($this->tableCellStyle());
            $spreadsheet->getActiveSheet()->getRowDimension($start_row)->setRowHeight(20); //cell height

            $companyName = $row->company->company_name_khmer. " (".$row->company->company_name_latin." )";
//            dd($row->company->company_name_latin);
            // Case Status
            $caseStatus = generateCaseStatus($row);
            //Get Case Domain ID
            $domainID = $row->caseDomain->domain_id;
            // Case Officer
            $caseOfficer = $row->latestCaseOfficer->officer->officer_name_khmer ?? "";
            //Case Noter
            $caseNoter = $row->caseNoter->officer->officer_name_khmer ?? "";

            if($caseStatus['status'] == 0){
                $statusLabel = $caseStatus['name'];
            }else{
                $statusLabel = $caseStatus['status_label']. " (".$caseStatus['name'].")";
            }

//            dd($row->caseObjective->objective_name);

            /** ============ Export Base Column Data =====================*/

            /** =========================== Menu 7 Garment Checklist ========================= */
            $sheet->setCellValue('A' . $start_row, $i);
//            $sheet->setCellValue('A' . $start_row, $row->id);
            $sheet->setCellValue('B' . $start_row, $row->caseType->case_type_name ?? "");//
            $sheet->setCellValue('C' . $start_row, $row->case_num_str);
            $sheet->setCellValue('D' . $start_row, $row->disputant->name);
            $sheet->setCellValue('E' . $start_row, $companyName);

            $sheet->setCellValueExplicit('F' . $start_row, $row->in_out_domain == 1 ? "ក្នុងដែន" : "ក្រៅដែន", DataType::TYPE_STRING);
            $sheet->setCellValue('G' . $start_row, "ដែនការិយាល័យទី".number2KhmerNumber($domainID));
            $sheet->setCellValue('H' . $start_row, $caseOfficer);//
            $sheet->setCellValue('I' . $start_row, $caseNoter);//
            $sheet->setCellValue('J' . $start_row, $row->caseSector->sector_name ?? "");//
            $sheet->setCellValue('K' . $start_row, $row->companyType->company_type_name ?? "");//
            $sheet->setCellValue('L' . $start_row, $row->caseCompany->csic1->description_kh ?? "");
            $sheet->setCellValue('M' . $start_row, $row->caseCompany->csic2->description_kh ?? "");
            $sheet->setCellValue('N' . $start_row, $row->caseCompany->csic3->description_kh ?? "");
            $sheet->setCellValue('O' . $start_row, $row->caseCompany->csic4->description_kh ?? "");
            $sheet->setCellValue('P' . $start_row, $row->caseCompany->csic5->description_kh ?? "");
            $sheet->setCellValue('Q' . $start_row, $row->caseCompany->province->pro_khname ?? "");
            $sheet->setCellValue('R' . $start_row, $row->caseCompany->district->dis_khname ?? "");
            $sheet->setCellValue('S' . $start_row, $row->caseCompany->commune->com_khname ?? "");
            $sheet->setCellValue('T' . $start_row, $row->caseObjective->objective_name ?? "");
            $sheet->setCellValue('U' . $start_row, $row->case_objective_des);
            $sheet->setCellValue('V' . $start_row, date2Display($row->disputant_sdate_work));
            $sheet->setCellValue(
                'W' . $start_row,
                $row->disputant_contract_type == 1 ? 'កំណត់'
                    : ($row->disputant_contract_type == 2 ? 'មិនកំណត់'
                    : ($row->disputant_contract_type == 3 ? 'សាកល្បង' : ''))
            );
            $sheet->setCellValue('X' . $start_row, $row->disputant_salary);
            $sheet->setCellValue('Y' . $start_row, $row->case_first_reason);
            $sheet->setCellValue('Z' . $start_row, $row->disputant_request);
            $sheet->setCellValue('AA' . $start_row, date2Display($row->case_date));
            $sheet->setCellValue('AB' . $start_row, date2Display($row->case_date_entry));
            $sheet->setCellValue('AC' . $start_row, $statusLabel);
            $start_row++;
            $i++;
        }
        /** ==================== Table Footer ============================================= */
        //$sheet ->getStyle('A'.$start_row.':'.$endCol.$start_row)->applyFromArray($this->tableCellStyle());
        //$sheet->setCellValue('B'.$start_row, "សរុប:");//ចុះឈ្មោះសរុប
        /** End Export data */

        /** ========================= Save to File =================================== */
        $name = "export_employee_";
        $filename = $name . date("d_m_Y") . "_" . time() . '.xlsx';
        //$filename = storage_path('app/exports/' . $name."_".myDate()."_".time().'.xlsx');
        // Generate the Excel file
        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);
        //Delete File after Downloading
        return response()->download($filename)->deleteFileAfterSend();
    }

    // Using Raw Query
    public function exportCasesList1(Request $request)
    {
//        dd($request->all());
        $this->loadRunUnlimitedTime();
        $today = date('Y-m-d');
        $todayKH = date2Display($today);

        // Create a new spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        //dd($opt, $lvl_cd, $nea_position_agency);
        /** ============================================================================= */
        $header_row = 5;
        $header_row_m1 = $header_row - 1;
        $header_row_m2 = $header_row - 2;
        $row1 = $header_row + 1;
        $start_row = $row1;
        $i = 1;
        $endCol = "AD";
        /** Set Style Border */
        $styleArray = array(
            'borders' => array(
                'outline' => array(
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => array('argb' => '00000000'),
                ),
            ),
        );
        $Moul10Center = [
            'font' => [
//                'bold' => true,
                'size' => 10,
                'name' => 'Khmer OS Muol Light'
            ],
            'alignment' => [
//                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ]
        ];

        /** ============================ Table Title ================================ */
        $sheet->mergeCells('A' . $header_row_m2 . ':' . 'J' . $header_row_m2);
        $sheet->getStyle('A' . $header_row_m2)->applyFromArray($Moul10Center);
        $query = $this->queryCases();
        $total = count($query);

        $titleSheet = "បញ្ជីពាក្យបណ្តឹង សរុបចំនួន៖ ".number2KhmerNumber($total)."  (គិតត្រឹមកាលបរិច្ឆេទ៖ " . $todayKH.")";
        $sheet->setCellValue('A' . $header_row_m2, $titleSheet);
        /** ============================ set table header ============================ */
        //exportBaseColumn($spreadsheet, $query, $header_row, $endCol);
        $sheet->setCellValue('A' . $header_row, "ល.រ");
        $sheet->setCellValue('B' . $header_row, "ប្រភេទពាក្យបណ្ដឹង");
        $sheet->setCellValue('C' . $header_row, "លេខសំណុំរឿង");
        $sheet->setCellValue('D' . $header_row, "ដើមបណ្តឹង");
        $sheet->setCellValue('E' . $header_row, "ចុងបណ្តឹង");
        $sheet->setCellValue('F' . $header_row, "ក្នុង-ក្រៅដែន");
        $sheet->setCellValue('G' . $header_row, "ស្ថិតក្នុងដែនការិយាល័យ");
        $sheet->setCellValue('H' . $header_row, "អ្នកផ្សះផ្សា");
        $sheet->setCellValue('I' . $header_row, "លេខាកត់ត្រា");
        $sheet->setCellValue('J' . $header_row, "វិស័យ");
        $sheet->setCellValue('K' . $header_row, "ប្រភេទសហគ្រាស");
        $sheet->setCellValue('L' . $header_row, "សកម្មភាពសេដ្ឋកិច្ចកម្រិតទី១");
        $sheet->setCellValue('M' . $header_row, "សកម្មភាពសេដ្ឋកិច្ចកម្រិតទី២");
        $sheet->setCellValue('N' . $header_row, "សកម្មភាពសេដ្ឋកិច្ចកម្រិតទី៣");
        $sheet->setCellValue('O' . $header_row, "សកម្មភាពសេដ្ឋកិច្ចកម្រិតទី៤");
        $sheet->setCellValue('P' . $header_row, "សកម្មភាពសេដ្ឋកិច្ចកម្រិតទី៥");
        $sheet->setCellValue('Q' . $header_row, "ខេត្ត-រាជធានី");
        $sheet->setCellValue('R' . $header_row, "ក្រុង-ស្រុក-ខ័ណ្ឌ");
        $sheet->setCellValue('S' . $header_row, "ឃុំ-សង្កាត់");
        $sheet->setCellValue('T' . $header_row, "កម្មវត្ថុបណ្ដឹង");
        $sheet->setCellValue('U' . $header_row, "អង្គហេតុនៃវិវាទ");
        $sheet->setCellValue('V' . $header_row, "ថ្ងៃខែឆ្នាំចូលបម្រើការងារ");
        $sheet->setCellValue('W' . $header_row, "ប្រភេទកិច្ចសន្យាការងារ");
        $sheet->setCellValue('X' . $header_row, "ប្រាក់ឈ្នួលប្រចាំខែ (ដុល្លារ)");
        $sheet->setCellValue('Y' . $header_row, "មូលហេតុចម្បងនៃវិវាទ");
        $sheet->setCellValue('Z' . $header_row, "សំណូមពររបស់អ្នកប្ដឹង");
        $sheet->setCellValue('AA' . $header_row, "កាលបរិច្ឆេទធ្វើបណ្ដឹង");
        $sheet->setCellValue('AB' . $header_row, "កាលបរិច្ឆេទប្តឹងទៅអធិការការងារ");
        $sheet->setCellValue('AC' . $header_row, "ស្ថានភាពពាក្យបណ្ដឹង");
        $sheet->setCellValue('AD' . $header_row, "ឯកសារពាក្យបណ្ដឹង");

        $sheet->getColumnDimension('C')->setWidth(20);
        $excelCol = $this->excelCol();
        for ($k = 4; $k < 30; $k++) {
            $sheet->getColumnDimension($excelCol[$k])->setWidth(22);
        }

        $sheet->getStyle('A' . $header_row . ':' . $endCol . $header_row)->applyFromArray($this->tableCellStyle(10, true));
        $spreadsheet->getActiveSheet()->getRowDimension($start_row)->setRowHeight(20); //set cell height

        /** ======================== Condition ===================== */
        foreach ($query as $row) {
            $sheet->getStyle('A' . $start_row . ':' . $endCol . $start_row)->applyFromArray($this->tableCellStyle());
            $spreadsheet->getActiveSheet()->getRowDimension($start_row)->setRowHeight(20); //cell height

            $companyName = $row->company_name. " (".$row->company_name_latin." )";
            // Case Status
            $case = Cases::find($row->id);
            $caseStatus = generateCaseStatus($case);

            //Get Case Domain ID
            $domainID = $case->caseDomain->domain_id;
//            $domainID = $case->caseDomain->domain_id ?? getCaseDomainControl($row->id);

//            $caseStatus = generateCaseStatus($row);
//            dd($caseStatus);
            $statusLabel = "";
            if($caseStatus['status'] == 0){
                $statusLabel = $caseStatus['name'];
            }else{
                $statusLabel = $caseStatus['status_label']. " (".$caseStatus['name'].")";
            }
//            dd($statusLabel);

            /** ============ Export Base Column Data =====================*/

            /** =========================== Menu 7 Garment Checklist ========================= */
            $sheet->setCellValue('A' . $start_row, $i);
//            $sheet->setCellValue('A' . $start_row, $row->id);
            $sheet->setCellValue('B' . $start_row, $row->case_type_name);//
            $sheet->setCellValue('C' . $start_row, $row->case_num_str);
            $sheet->setCellValue('D' . $start_row, $row->disputant_name);
            $sheet->setCellValue('E' . $start_row, $companyName);

            $sheet->setCellValueExplicit('F' . $start_row, $row->in_out_domain == 1 ? "ក្នុងដែន" : "ក្រៅដែន", DataType::TYPE_STRING);
            $sheet->setCellValue('G' . $start_row, "ដែនការិយាល័យទី".number2KhmerNumber($domainID));
            $sheet->setCellValue('H' . $start_row, $row->officer_name);//
            $sheet->setCellValue('I' . $start_row, $row->officer_noter);//
            $sheet->setCellValue('J' . $start_row, $row->sector_name);//
            $sheet->setCellValue('K' . $start_row, $row->com_type_name);//
            $sheet->setCellValue('L' . $start_row, $row->csic1_name);
            $sheet->setCellValue('M' . $start_row, $row->csic2_name);
            $sheet->setCellValue('N' . $start_row, $row->csic3_name);
            $sheet->setCellValue('O' . $start_row, $row->csic4_name);
            $sheet->setCellValue('P' . $start_row, $row->csic5_name);
            $sheet->setCellValue('Q' . $start_row, $row->province_name);
            $sheet->setCellValue('R' . $start_row, $row->district_name);
            $sheet->setCellValue('S' . $start_row, $row->commune_name);
            $sheet->setCellValue('T' . $start_row, $row->case_objective);
            $sheet->setCellValue('U' . $start_row, $row->case_objective_des);
            $sheet->setCellValue('V' . $start_row, date2Display($row->disputant_sdate_work));
            $sheet->setCellValue(
                'W' . $start_row,
                $row->disputant_contract_type == 1 ? 'កំណត់'
                    : ($row->disputant_contract_type == 2 ? 'មិនកំណត់'
                    : ($row->disputant_contract_type == 3 ? 'សាកល្បង' : ''))
            );
            $sheet->setCellValue('X' . $start_row, $row->disputant_salary);
            $sheet->setCellValue('Y' . $start_row, $row->case_first_reason);
            $sheet->setCellValue('Z' . $start_row, $row->disputant_request);
            $sheet->setCellValue('AA' . $start_row, date2Display($row->case_date));
            $sheet->setCellValue('AB' . $start_row, date2Display($row->case_date_entry));
            $sheet->setCellValue('AC' . $start_row, $statusLabel);
            $start_row++;
            $i++;
        }
        /** ==================== Table Footer ============================================= */
        //$sheet ->getStyle('A'.$start_row.':'.$endCol.$start_row)->applyFromArray($this->tableCellStyle());
        //$sheet->setCellValue('B'.$start_row, "សរុប:");//ចុះឈ្មោះសរុប
        /** End Export data */

        /** ========================= Save to File =================================== */
        $name = "export_employee_";
        $filename = $name . date("d_m_Y") . "_" . time() . '.xlsx';
        //$filename = storage_path('app/exports/' . $name."_".myDate()."_".time().'.xlsx');
        // Generate the Excel file
        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);
        //Delete File after Downloading
        return response()->download($filename)->deleteFileAfterSend();
    }
    public function readTemplateFile()
    {
        // Set memory limit to unlimited
        ini_set('memory_limit', '-1'); // -1 means no memory limit

        // Path to your template Excel file
        $filePath = storage_path("doc_template/scoresheet.xlsx");

        // Load the template Excel file once
        $originalSpreadsheet = IOFactory::load($filePath);

        // Initialize ZipArchive
        $zip = new ZipArchive();
        $zipFileName = storage_path('doc_template/modified_files.zip');

        // Open zip archive for writing, if file exists let's overwrite it, if no file found let's create it
        if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $fileCount = 1;

            for ($i = 8; $i <= 99; $i++) {
                // Clone the original spreadsheet for each iteration
                $spreadsheet = $originalSpreadsheet->copy();
                $sheet = $spreadsheet->getActiveSheet();

                // Modify the spreadsheet content
                $sheet->setCellValue('H' . $i, 'Value H' . $i);
                $sheet->setCellValue('I' . $i, 'Value I' . $i);
                $sheet->setCellValue('J' . $i, 'Value J' . $i);

                // Write directly to zip using memory stream
                $tempMemory = fopen('php://memory', 'r+');
                $writer = new Xlsx($spreadsheet);
                $writer->save($tempMemory);

                //add content of memory stream to the zip archive
                rewind($tempMemory);
                $zip->addFromString('edited_file_' . $fileCount . '.xlsx', stream_get_contents($tempMemory));

                fclose($tempMemory);


//                // Save each modified file to a temporary path
//                $tempFilePath = storage_path("doc_template/edited_file_" . $fileCount . ".xlsx");
//                $writer = new Xlsx($spreadsheet);
//                $writer->save($tempFilePath);
//
//                // Add the file to the zip archive
//                $zip->addFile($tempFilePath, 'edited_file_' . $fileCount . '.xlsx');

                $fileCount++;
            }

            // Close zip archive after adding all files
            $zip->close();

            // Return the zip file for download
            return response()->download($zipFileName)->deleteFileAfterSend(true);
        } else {
            return "Failed to create ZIP file";
        }
    }

    public function readTemplateFileZ()
    {
        // Path to your existing Excel file
        $filePath = storage_path("doc_template/scoresheet.xlsx");

        // Initialize ZipArchive
        $zip = new ZipArchive();
        $zipFileName = storage_path('doc_template/modified_files.zip');

        // Open zip archive for writing
        if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $fileCount = 1;
            $j = 1;
            for ($i = 8; $i <= 99; $i++) {
                // Load the existing Excel file
                $spreadsheet = IOFactory::load($filePath);
                $sheet = $spreadsheet->getActiveSheet();

                // Modify the spreadsheet - change different content for each file
                $sheet->setCellValue('H' . $i, "Value H".$i." = ".$j);
                $sheet->setCellValue('I' . $i, "Value I".$i." = ".$j + 1);
                $sheet->setCellValue('J' . $i, "Value J".$i." = ".$j + 2);

                $j++;

                // Save each modified file to a temporary path
                $tempFilePath = storage_path("doc_template/edited_file_" . $fileCount . ".xlsx");
                $writer = new Xlsx($spreadsheet);
                $writer->save($tempFilePath);

                // Add the file to the zip archive
                $zip->addFile($tempFilePath, 'edited_file_' . $fileCount . '.xlsx');

                $fileCount++;
            }

            // Close zip archive after adding all files
            $zip->close();

            // Return the zip file for download
            return response()->download($zipFileName)->deleteFileAfterSend(true);
        } else {
            return "Failed to create ZIP file";
        }
    }

    public function readTemplateFileY()
    {
        // Path to your existing Excel file
        $filePath = storage_path("doc_template/scoresheet.xlsx");

        // Initialize ZipArchive
        $zip = new ZipArchive();
        $zipFileName = storage_path('doc_template/modified_files.zip');

        // Open zip archive for writing
        if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $j = 1;
            for ($i = 8; $i <= 99; $i++) {
                // Load the existing Excel file
                $spreadsheet = IOFactory::load($filePath);
                $sheet = $spreadsheet->getActiveSheet();

                // Modify the spreadsheet
                $sheet->setCellValue('H' . $i, $j);
                $sheet->setCellValue('I' . $i, $j + 1);
                $sheet->setCellValue('J' . $i, $j + 2);
                $j++;

                // Save each modified file to a temporary path
                $tempFilePath = storage_path("doc_template/edited_file_" . $j . ".xlsx");
                $writer = new Xlsx($spreadsheet);
                $writer->save($tempFilePath);

                // Add the file to the zip archive
                $zip->addFile($tempFilePath, 'edited_file_' . $j . '.xlsx');
            }

            // Close zip archive after adding all files
            $zip->close();

            // Return the zip file for download
            return response()->download($zipFileName)->deleteFileAfterSend(true);

        } else {
            return "Failed to create ZIP file";
        }
    }

    public function readTemplateFileX(){

        // Path to your existing Excel file
        $filePath = storage_path("doc_template/scoresheet.xlsx");
        //load spreadsheet
        $spreadsheet = IOFactory::load($filePath);
        $writer = new Xlsx($spreadsheet);
        $newFilePath = storage_path("doc_template/edited_file.xlsx");
        //change it
        $sheet = $spreadsheet->getActiveSheet();
        $j = 1;
        for($i = 8; $i <= 99; $i++){
            $sheet->setCellValue('H'.$i, $j);
            $sheet->setCellValue('I'.$i, $j+1);
            $sheet->setCellValue('J'.$i, $j+2);
            $j++;
//            $newFilePath = storage_path("doc_template/edited_file_".$j.".xlsx");
//            $writer->save($newFilePath);
            // Download the modified file without saving it
            return new StreamedResponse(function() use ($spreadsheet) {
                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output'); // Output directly to the browser
            }, 200, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment;filename="modified_scoresheet.xlsx"',
                'Cache-Control' => 'max-age=0',
            ]);
        }

    }
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

    function excelCol()
    {
        return [
            "A",
            "B",
            "C",
            "D",
            "E",
            "F",
            "G",
            "H",
            "I",
            "J",
            "K",
            "L",
            "M",
            "N",
            "O",
            "P",
            "Q",
            "R",
            "S",
            "T",
            "U",
            "V",
            "W",
            "X",
            "Y",
            "Z",
            "AA",
            "AB",
            "AC",
            "AD",
            "AE",
            "AF",
            "AG",
            "AH",
            "AI",
            "AJ",
            "AK",
            "AL",
            "AM",
            "AN",
            "AO",
            "AP",
            "AQ",
            "AR",
            "AS",
            "AT",
            "AU",
            "AV",
            "AW",
            "AX",
            "AY",
            "AZ",
            "BA",
            "BB",
            "BC",
            "BD",
            "BE",
            "BF",
            "BG",
            "BH",
            "BI",
            "BJ",
            "BK",
            "BL",
            "BM",
            "BN",
            "BO",
            "BP",
            "BQ",
            "BR",
            "BS",
            "BT",
            "BU",
            "BV",
            "BW",
            "BX",
            "BY",
            "BZ",
            "CA",
            "CB",
            "CC",
            "CD",
            "CE",
            "CF",
            "CG",
            "CH",
            "CI",
            "CJ",
            "CK",
            "CL",
            "CM",
            "CN",
            "CO",
            "CP",
            "CQ",
            "CR",
            "CS",
            "CT",
            "CU",
            "CV",
            "CW",
            "CX",
            "CY",
            "CZ",
            "DA",
            "DB",
            "DC",
            "DD",
            "DE",
            "DF",
            "DG",
            "DH",
            "DI",
            "DJ",
            "DK",
            "DL",
            "DM",
            "DN",
            "DO",
            "DP",
            "DQ",
            "DR",
            "DS",
            "DT",
            "DU",
            "DV",
            "DW",
            "DX",
            "DY",
            "DZ",
            "EA",
            "EB",
            "EC",
            "ED",
            "EE",
            "EF",
            "EG",
            "EH",
            "EI",
            "EJ",
            "EK",
            "EL",
            "EM",
            "EN",
            "EO",
            "EP",
            "EQ",
            "ER",
            "ES",
            "ET",
            "EU",
            "EV",
            "EW",
            "EX",
            "EY",
            "EZ"
        ];
    }

    function tableCellStyle($font_size = 10, $bold = false, $font_name = 'Khmer OS Siemreap')
    {
        return [
            'font' => [
                'bold' => $bold,
                'size' => $font_size,
                'name' => $font_name
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
//                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ]
        ];
    }
    function tableCellStyle2($font_size = 10, $bold = false, $font_name = 'Khmer OS Siemreap')
    {
        return [
            'font' => [
                'bold' => $bold,
                'size' => $font_size,
                'name' => $font_name
            ],
            'alignment' => [
//                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
//                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ]
        ];
    }

    function loadRunUnlimitedTime()
    {
        ini_set('memory_limit', -1);
        ini_set('MAX_EXECUTION_TIME', -1);
        set_time_limit(0);
    }

    function myDateKhmer($date) {
        $khmerNumbers = [
            '0' => '០',
            '1' => '១',
            '2' => '២',
            '3' => '៣',
            '4' => '៤',
            '5' => '៥',
            '6' => '៦',
            '7' => '៧',
            '8' => '៨',
            '9' => '៩'
        ];
        $khmerMonths = [
            'January' => 'មករា',
            'February' => 'កុម្ភៈ',
            'March' => 'មីនា',
            'April' => 'មេសា',
            'May' => 'ឧសភា',
            'June' => 'មិថុនា',
            'July' => 'កក្កដា',
            'August' => 'សីហា',
            'September' => 'កញ្ញា',
            'October' => 'តុលា',
            'November' => 'វិច្ឆិកា',
            'December' => 'ធ្នូ',
        ];
        $dateObj = new DateTime($date);
        $day = $dateObj->format('d');
        $month = $dateObj->format('F');
        $year = $dateObj->format('Y');
        $khmerDay = '';
        foreach (str_split($day) as $digit) {
            $khmerDay .= $khmerNumbers[$digit];
        }
        $khmerMonth = $khmerMonths[$month];

        $khmerYear = '';
        foreach (str_split($year) as $digit) {
            $khmerYear .= $khmerNumbers[$digit];
        }
        return $khmerDay . ' ' . $khmerMonth . ' ' . $khmerYear;
    }
}
