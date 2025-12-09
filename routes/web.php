<?php

use App\Http\Controllers\AjaxController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Auth\CustomLogoutController;
use App\Http\Controllers\CaseController;
use App\Http\Controllers\CollectiveCaseController;
use App\Http\Controllers\CollectiveInvitationController;
use App\Http\Controllers\CollectivesLog34Controller;
use App\Http\Controllers\CollectivesLog5Controller;
use App\Http\Controllers\CollectivesLog6Controller;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CpesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DisputantController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\ExportExcelController;
use App\Http\Controllers\ExportWordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImportExcelController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\JointCaseController;
use App\Http\Controllers\Log34Controller;
use App\Http\Controllers\Log5Controller;
use App\Http\Controllers\Log6Controller;
use App\Http\Controllers\NoAccessController;
use App\Http\Controllers\OfficerController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('throttle:60,1')->group(function () {

    //Route::get('/', function () {
    //    return view('welcome');
    //});



    Route::get('/clear-cache', function () {
        //    return "All Cache was cleared";
        Artisan::call('config:cache');
        Artisan::call('optimize:clear');
        echo Artisan::output();
    });
    Route::get('/clear-all', function () {
        // Clear all relevant Laravel caches
        Artisan::call('cache:clear');        // Application cache
        Artisan::call('config:clear');       // Config cache
        Artisan::call('route:clear');        // Route cache
        Artisan::call('view:clear');         // View cache
        Artisan::call('event:clear');        // Event cache (if used)
        Artisan::call('optimize:clear');     // Clears all of the above

        // Optional: Rebuild config and route caches for performance
        Artisan::call('config:cache');
        Artisan::call('route:cache');

        return response()->json([
            'status' => 'success',
            'message' => 'All Laravel caches have been cleared and rebuilt where necessary.',
            'output' => Artisan::output()
        ]);
    });

    // Handle SSO redirect for login
    // Route::get('/login', function () {
    //    $redirectUri = urlencode(config('app.url') . 'mainboard');
    //    $ssoLoginUrl = rtrim(config('services.sso.base_url'), '/') . '/login?redirect_uri=' . $redirectUri;
    //    return redirect()->away($ssoLoginUrl);
    // })->name('login');

    Route::middleware(['sso', 'auth'])->group(function () {
        Route::get('/testing_sso', [HomeController::class, 'testingSSO']);
    });

    Route::get('/unauthorized', function () {
        return view('errors.unauthorized');
    })->name('unauthorized');


    Route::get('/', function () {
        if (Auth::check()) { //login success
            /** @var \App\Models\User $user */
            $user = Auth::user();

            //dd($user->isSuperUser());
            if ($user->isSuperUser() || $user->isDisputeUser()) {
                return redirect("/mainboard");
            } elseif ($user->isDoshUser()) { // DOSH
                return redirect("/dosh/home");
            } elseif ($user->isInspectorUser()) { //Dispute
                return redirect("/mainboard");
                //                return redirect("/dispute/home");
            } elseif ($user->isEmploymentUser()) { //Employment
                return redirect("/employment/home");
            }
        }
        return view('auth.login'); //auth.home
        //    return redirect()->route('login'); // Redirect to SSO
    });
    //    Route::group(['middleware'=> ['sso', 'auth'] ], function(){ // user login can access this blog
    Route::group(['middleware' => ['auth']], function () { // user login can access this blog
        Route::get("/noaccess", [NoAccessController::class, "noAccess"]);//

        /** Block A: Department of Labour Dispute */
        Route::group(['middleware' => ['isDispute']], function () {
            /** Block A.2: All Route for Officer Only */
            Route::group(['middleware' => ['isOfficer']], function () { // call by middleware

                //Testing Import Excel
                Route::get("excel/import", [ImportExcelController::class, "import_company_list"]);

                Route::get('/find_company_autocomplete', [AjaxController::class, 'findCompanyAutocomplete']);
                Route::get('/get-details', [AjaxController::class, 'getDetails']);

                Route::get('/find_employee_autocomplete/{company_id?}', [AjaxController::class, 'findEmployeeAutocomplete']);
                Route::get('autocomplete/get_employee_detail/{company_id?}', [AjaxController::class, 'getEmployeeDetail']);

                /** Left Menu Home */
                Route::any("/dashboard/{opt?}", [HomeController::class, "index"]); //
                Route::any("/mainboard/{opt?}", [HomeController::class, "index"]);//

                /** Upload Controller */
                Route::resource("uploads", UploadController::class);
                Route::get("uploads/all/{url_opt}/{case_id}/{id}", [UploadController::class, "formUploadFileAll"]);
                /** Left Menu Case */
                Route::resource("cases", CaseController::class);

                Route::post('/ajaxDeleteFile', [CaseController::class, 'ajaxDeleteFile'])->name('ajaxDeleteFile');
                // routes/web.php

                Route::get('cases/create/step1', [CaseController::class, 'createStep1'])->name('cases.create.step1');
                Route::get('cases/edit/step1/{case_id}', [CaseController::class, 'editStep1'])->name('cases.edit.step1');
                Route::post('cases/save/step1', [CaseController::class, 'storeStep1'])->name('cases.save.step1');

                Route::get('cases/edit/step2/{case_id}', [CaseController::class, 'processStep2'])->name('cases.edit.step2');
                Route::post('cases/save/step2', [CaseController::class, 'storeStep2'])->name('cases.save.step2');

                Route::get('cases/create/step3', [CaseController::class, 'createStep3'])->name('cases.create.step3');
                Route::post('cases/create/step3', [CaseController::class, 'storeStep3'])->name('cases.store.step3');


                Route::put("case/upload/file", [CaseController::class, "uploadCaseFile"]); //
                Route::any("assign/officer", [CaseController::class, "assignOfficer"]); //
                Route::any("assign/officer22", [CaseController::class, "assignOfficer22"]); //
                //Route::post("casetesting",[CaseController::class,"assignOfficer"]);//
                Route::any("case/upload/case_file", [CaseController::class, "uploadFile"]);
                //Closing Case
                Route::get("close/case/{case_id}", [CaseController::class, "closeCaseForm"]);
                Route::put("close/case", [CaseController::class, "closingCase"]);
                Route::get('daily/case/report', [CaseController::class, "caseDailyReport2Telegram"]);

                /** Update Template Files */
                Route::get("template", [CaseController::class, "showTemplateFiles"]);
                Route::put("template/update", [CaseController::class, "updateTemplate"]);


                /** Left Menu CaseInvitation */
                Route::resource("invitations", InvitationController::class);
                Route::get("invitation/create/{case_id}/{invitation_type}", [InvitationController::class, "create"]); //
                Route::get("invitation/create_both/{case_id}/{invitation_type_employee}/{invitation_type_company}", [InvitationController::class, "createBoth"]); //
                Route::post("invitation/store_both", [InvitationController::class, "storeBoth"]); //
                Route::get("invitation/edit_both/{case_id}/{id}/{id_pair}", [InvitationController::class, "editBoth"]); //
                Route::post("invitation/update_both", [InvitationController::class, "updateBoth"]); //

                Route::get("invitation/delete/next/{id}/", [InvitationController::class, "deleteInvitationNext"]);
                Route::any("invitation/upload/file", [InvitationController::class, "uploadFile"]);
                Route::any("invitation/upload/next/file", [InvitationController::class, "uploadNextFile"]);

                /** Left Menu Collective Cases */
                Route::resource("collective_cases", CollectiveCaseController::class);
                Route::get("collectives/delete/representative/{id}/", [CollectiveCaseController::class, "deleteCollectivesRepresentative"]);
                Route::get("collectives/delete/issue/{id}/", [CollectiveCaseController::class, "deleteCollectivesIssue"]);

                /** Left Menu Collective Invitation */
                Route::resource("collectives_invitations", CollectiveInvitationController::class);
                Route::get("collectives_invitation/create/{case_id}/{invitation_type}", [CollectiveInvitationController::class, "create"]); //

                Route::get("collectives_invitation/create_both/{case_id}/{invitation_type_employee}/{invitation_type_company}", [CollectiveInvitationController::class, "createBoth"]); //
                Route::post("collectives_invitation/store_both", [CollectiveInvitationController::class, "storeBoth"]); //
                Route::get("collectives_invitation/edit_both/{case_id}/{id}/{id_pair}", [CollectiveInvitationController::class, "editBoth"]); //
                Route::post("collectives_invitation/update_both", [CollectiveInvitationController::class, "updateBoth"]); //
                Route::get("collectives_invitation/delete/next/{id}/", [CollectiveInvitationController::class, "deleteInvitationNext"]);

                /** Left Menu Collectives Log34 */
                Route::get("collectives/log34/create/{case_id}/{invitation_id}", [CollectivesLog34Controller::class, "create"]);
                Route::get("collectives/log34/delete/issue/{id}/", [CollectivesLog34Controller::class, "deleteCollectivesLog34Issue"]);
                Route::get("collectives/log34/delete/representative/{id}/", [CollectivesLog34Controller::class, "deleteCollectivesRepresentative"]);
                Route::get("collectives/log34/delete/sub_representative/{id}/", [CollectivesLog34Controller::class, "deleteCollectivesLog34Attendant"]);
                Route::get("collectives/log34/delete/attendant/{id}/", [CollectivesLog34Controller::class, "deleteCollectivesLog34Attendant"]);
                Route::resource("collectives_log34", CollectivesLog34Controller::class);
                Route::any("collectives/log34/upload/file", [CollectivesLog34Controller::class, "uploadFile"]);

                /** Left Menu Collectives Log5 */
                Route::get("collectives/log5/create/{caseID}/{invID}", [CollectivesLog5Controller::class, "create"]);
                Route::get("collectives/log5/delete/representative/{id}/", [CollectivesLog5Controller::class, "deleteCollectivesRepresentativeCompany"]);
                Route::get("collectives/log5/delete/sub_representative/{id}/", [CollectivesLog5Controller::class, "deleteCollectivesLog5Attendant"]);
                Route::get("collectives/log5/delete/attendant/{id}/", [CollectivesLog5Controller::class, "deleteCollectivesLog5Attendant"]);
                Route::get("collectives/log5/delete/union1/{id}/", [CollectivesLog5Controller::class, "deleteUnion1"]);
                Route::resource("collectives_log5", CollectivesLog5Controller::class);
                Route::any("collectives/log5/upload/file", [CollectivesLog5Controller::class, "uploadFile"]);

                /** Left Menu Collective Log6 */
                Route::get("collectives/log6/create/{case_id}/{invitation_id_employee}/{invitation_id_company}", [CollectivesLog6Controller::class, "create"]);
                Route::get("collectives/log6/delete/log620/{id}/", [CollectivesLog6Controller::class, "deleteLog620"]);
                Route::get("collectives/log6/delete/log621/{id}/", [CollectivesLog6Controller::class, "deleteLog621"]);
                Route::get("collectives/log6/delete/log_attendant/{id}/", [CollectivesLog6Controller::class, "deleteLogAttendant"]);
                Route::resource("collectives_log6", CollectivesLog6Controller::class);
                Route::get("collectives/log6/generate/new/log/{log6_id}/{log6_status_id}", [CollectivesLog6Controller::class, "generateNewLog"]);
                Route::any("collectives/log6/reopen/insert", [CollectivesLog6Controller::class, "reopenInsert"]);
                Route::any("collectives/log6/reopen/update", [CollectivesLog6Controller::class, "reopenUpdate"]);
                Route::any("collectives/log6/reopen/upload/file", [CollectivesLog6Controller::class, "reopenUploadFile"]);
                Route::get("collectives/log6/reopen/request/cancel/{case_id}/{log6_id}", [CollectivesLog6Controller::class, "reopenRequestCancel"]);
                Route::any("collectives/log6/upload/file", [CollectivesLog6Controller::class, "uploadFile"]);


                /** Left Menu Joint Cases */
                Route::resource("joint_cases", JointCaseController::class);
//                Route::get("joint_cases/create/",[JointCaseController::,"create"]);
//                Route::put("case/upload/file",[JointCaseController::class,"uploadCaseFile"]);//


                /** Left Menu Log34 */
                Route::get("log34/create/{case_id}/{invitation_id}", [Log34Controller::class, "create"]);
                Route::get("log34/delete/sub_disputant/{id}/", [Log34Controller::class, "deleteSubDisputant"]);
                Route::resource("log34", Log34Controller::class);
                Route::any("log34/upload/file", [Log34Controller::class, "uploadFile"]);
                /** Left Menu Log5 */
                Route::get("log5/create/{case_id}/{invitation_id}", [Log5Controller::class, "create"]);
                Route::get("log5/delete/represent_company/{id}/", [Log5Controller::class, "deleteRepresentCompany"]);
                Route::get("log5/delete/union1/{id}/", [Log5Controller::class, "deleteUnion1"]);
                Route::resource("log5", Log5Controller::class);
                Route::any("log5/upload/file", [Log5Controller::class, "uploadFile"]);

                /** Left Menu Log6 */
                Route::get("log6/create/{case_id}/{invitation_id_employee}/{invitation_id_company}", [Log6Controller::class, "create"]);
                Route::get("log6/delete/log620/{id}/", [Log6Controller::class, "deleteLog620"]);
                Route::get("log6/delete/log621/{id}/", [Log6Controller::class, "deleteLog621"]);
                Route::get("log6/delete/log_attendant/{id}/", [Log6Controller::class, "deleteLogAttendant"]);
                Route::resource("log6", Log6Controller::class);
                Route::get("log6/generate/new/log/{log6_id}/{log6_status_id}", [Log6Controller::class, "generateNewLog"]);
                Route::any("log6/reopen/insert", [Log6Controller::class, "reopenInsert"]);
                Route::any("log6/reopen/update", [Log6Controller::class, "reopenUpdate"]);
                Route::any("log6/reopen/upload/file", [Log6Controller::class, "reopenUploadFile"]);
                Route::get("log6/reopen/request/cancel/{case_id}/{log6_id}", [Log6Controller::class, "reopenRequestCancel"]);
                Route::any("log6/upload/file", [Log6Controller::class, "uploadFile"]);
//            Route::get("log6/create/{case_id}/{invitation_id}",[Log6Controller::class,"create"]);
//            Route::resource("log6", Log6Controller::class);
                /** Left Menu Disputant */
                Route::resource("disputant", DisputantController::class);

                /** Left Menu Company */
                Route::resource("company", CompanyController::class);
                Route::get("/company/list/{province_id?}", [CompanyController::class, "index"]); //

                //Route::get("/company/list_json",[CompanyController::class,"list_json"]);//
                //Route::any("/company/frm_insert_google_map/{company_id}/{google_map_link?}",[CompanyController::class,"frm_insert_google_map"]);//
                Route::get("/company/get_company_from_lacms/{page?}", [CompanyController::class, "getCompanyFromLacms"]); //
                Route::get("/company/refresh_company_info_from_lacms/{company_id}", [CompanyController::class, "refreshCompanyInfoFromLacms"]);//


                /** Left Menu Inspection Officer */
                //Route::get("/officer/list",[OfficerController::class,"index"]);//
                Route::resource("officer", OfficerController::class);
                Route::get("officer/show/{id}/{officerType?}", [OfficerController::class, "showOfficers"]);//
                /** Left Menu Report */
                Route::get("/report", [ReportController::class, "index"]);//


                /** Left Menu Domain Controller Officer */
                Route::resource("domain", DomainController::class);
                Route::get('domain/province/add/{domainID}', [DomainController::class, "addProvinceToDomain"]);
                Route::get('domain/distict/add/{domainID}/{proID}', [DomainController::class, "addDistrictToDomain"]);
                Route::get('domain/distict/add/form/{domainID}/{proID}', [DomainController::class, "addDistrictToDomainForm"]);
                Route::get('domain/commune/add/{domainID}/{proID}/{disID}', [DomainController::class, "addCommuneToDomain"]);
                Route::get('domain/commune/add/form/{domainID}/{proID}/{disID}', [DomainController::class, "addCommuneToDomainForm"]);
                Route::get('domain/province/delete/{domainID}/{proID}', [DomainController::class, "deleteProvinceFromDomain"]);
                Route::get('domain/distict/delete/{domainID}/{proID}/{disID}', [DomainController::class, "deleteDestrictFromDomain"]);
                Route::get('domain/commune/delete/{domainID}/{proID}/{disID}/{comID}', [DomainController::class, "deleteCommuneFromDomain"]);
                Route::get('domain/discom/edit/{domainID}/{proID}', [DomainController::class, "editDisComDomain"]);

                /** Update all domain_id in tbl_case_company */

                Route::get("update/domain_id/case/company", [DomainController::class, "updateDomainIDByCaseID"]);

                /** Left Menu User */
                Route::resource("user", UserController::class);
                Route::get("user/case/entry/{userID}", [UserController::class, "showEntryCase"]);
                Route::get("user/sync/sso/{userID}", [UserController::class, "synSSOUserForm"]);
                Route::post("user/sync_sso", [UserController::class, "synSSOUser"]);
                Route::get("user/change_status/{user_id}/{banned}", [UserController::class, "changeStatusUser"]);
                Route::get("user/change_password/owner", [UserController::class, "changePasswordOwnerForm"]);
                Route::post("user/change_password/owner", [UserController::class, "changePasswordOwner"]);
                Route::get("user/change_password/{user_id}", [UserController::class, "changePasswordForm"]);
                Route::post("user/change_password", [UserController::class, "changePassword"]);

                Route::any("change/user/case", [UserController::class, "changeUserInCase"]);//

                /** User Data Table */
//                Route::get("users",[UserController::class,"index"])->name("users.index");;

                /** Left Menu Setting */
                Route::get("/setting", [SettingController::class, "index"]);//

                /** Left Menu Report */
                Route::resource("cpes", CpesController::class);

                /** Other Blog: Start Export Word */
                Route::get('export/word/case/{case_id}', [ExportWordController::class, 'exportCaseReport']);
                Route::get('export/word/invitation/{invitation_id}/{type?}', [ExportWordController::class, 'exportInvitation']);
                Route::get('export/word/case/log34/{log_id}', [ExportWordController::class, 'exportLog34']);
                Route::get('export/word/case/log5/{log_id}', [ExportWordController::class, 'exportLog5']);
                Route::get('export/word/case/log6/{log_id}', [ExportWordController::class, 'exportLog6']);
                /** Other Blog: Start Export Word */

                /** Other Blog: Start Export Excel */
                Route::get('export/excel/load_template', [ExportExcelController::class, 'readTemplateFile']);
                Route::get('export/list/case', [ExportExcelController::class, 'exportCasesList']);

                /** Other Blog: Start Ajax Route */
                Route::get('/ajaxGetInspGroup/{province_id}', [AjaxController::class, 'getInspectionGroup']);
                Route::get('/ajaxGetRole/{officerID}', [AjaxController::class, 'getRole']);
                Route::get('/ajaxGetProvince', [AjaxController::class, 'getProvince']);
                Route::get('/ajaxGetDistrict/{id}', [AjaxController::class, 'getDistrict']);
                Route::get('/ajaxGetCommune/{id}', [AjaxController::class, 'getCommune']);
                Route::get('/ajaxGetVillage/{id}', [AjaxController::class, 'getVillage']);

                Route::get('/ajaxGetBusinessActivity2/{business_activity_id1}', [AjaxController::class, 'getBusinessActivity2']);
                Route::get('/ajaxGetBusinessActivity3/{business_activity_id1}/{business_activity_id2}', [AjaxController::class, 'getBusinessActivity3']);
                Route::get('/ajaxGetBusinessActivity4/{business_activity_id1}/{business_activity_id2}/{business_activity_id3}', [AjaxController::class, 'getBusinessActivity4']);

                Route::get('/ajaxGetCSIC2/{csic1}', [AjaxController::class, 'getCSIC2']);
                Route::get('/ajaxGetCSIC3/{csic1}/{csic2}', [AjaxController::class, 'getCSIC3']);
                Route::get('/ajaxGetCSIC4/{csic1}/{csic2}/{csic3}', [AjaxController::class, 'getCSIC4']);
                Route::get('/ajaxGetCSIC5/{csic1}/{csic2}/{csic3}/{csic4}', [AjaxController::class, 'getCSIC5']);



                Route::get('/ajaxGetNewRow121/{count}', [AjaxController::class, 'getNewRow121']);
                Route::get('/ajaxGetNewRow122/{count}', [AjaxController::class, 'getNewRow122']);

                Route::get('/ajaxDeleteKarey/{id}', [AjaxController::class, 'deleteKarey']);
                Route::get('/ajaxDeleteComposition/{id}', [AjaxController::class, 'deleteComposition']);

                Route::get('/ajaxDeleteFile/{filename}/{path}/{table?}/{key_find?}/{key_value?}/{field?}', [AjaxController::class, 'deleteFile']);
                Route::get('/ajaxDeleteFileOnly/{filename}/{path}/{table?}/{key_find?}/{key_value?}/{field?}', [AjaxController::class, 'deleteFileOnly']);


                /**
                Route::get('items', 'index')->name('items.index');
                Route::post('items', 'store')->name('items.store');
                Route::get('items/create', 'create')->name('items.create');
                Route::get('items/{item}', 'show')->name('items.show');
                Route::put('items/{item}', 'update')->name('items.update');
                Route::delete('items/{item}', 'destroy')->name('items.destroy');
                Route::get('items/{item}/edit', 'edit')->name('items.edit');
                 */
                /** Inspection Garment Category: Resource Route */
            });
        });


        /** Block B: for Department of OSH */
        Route::group(['middleware' => ['isDosh']], function () {
            Route::get("/dosh/home", [HomeController::class, "homeDosh"]); //

        });
        Route::get('storage/{path}', function ($path) {
            // Only allow authenticated users
            if (!auth()->check()) {
                abort(403);
            }

            // Normalize request path
            $requested = ltrim($path, '/');

            // Candidate absolute locations to check (common Laravel/htdocs locations)
            $candidates = [
                realpath(base_path('storage/' . $requested)),
                realpath(storage_path('app/' . $requested)),
                realpath(storage_path('app/public/' . $requested)),
                realpath(public_path('storage/' . $requested)),
                realpath(public_path($requested)),
            ];

            // Allowed roots (real paths)
            $allowedRoots = array_filter([
                realpath(base_path('storage')),
                realpath(storage_path('app')),
                realpath(storage_path('app/public')),
                realpath(public_path('storage')),
                realpath(public_path()),
            ]);

            foreach ($candidates as $candidate) {
                if (!$candidate) {
                    continue;
                }
                // Ensure the file is inside an allowed root to prevent traversal
                foreach ($allowedRoots as $root) {
                    if ($root && strpos($candidate, $root) === 0 && is_file($candidate) && is_readable($candidate)) {
                        return response()->file($candidate);
                    }
                }
            }

            abort(404);
        })->where('path', '.*')->name('storage.serve')->middleware('auth');
    });
});
