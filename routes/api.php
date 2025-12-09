<?php

use App\Http\Controllers\API\ApiController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ExportWordController;
use App\Http\Controllers\Garment\Inspection10Controller;
use App\Http\Controllers\Garment\Inspection1Controller;
use App\Http\Controllers\Garment\Inspection2Controller;
use App\Http\Controllers\Garment\Inspection3Controller;
use App\Http\Controllers\Garment\Inspection4Controller;
use App\Http\Controllers\Garment\Inspection5Controller;
use App\Http\Controllers\Garment\Inspection6Controller;
use App\Http\Controllers\Garment\Inspection7Controller;
use App\Http\Controllers\Garment\Inspection8Controller;
use App\Http\Controllers\Garment\Inspection9Controller;
use App\Http\Controllers\Other\InspectionOther10Controller;
use App\Http\Controllers\Other\InspectionOther1Controller;
use App\Http\Controllers\Other\InspectionOther2Controller;
use App\Http\Controllers\Other\InspectionOther3Controller;
use App\Http\Controllers\Other\InspectionOther4Controller;
use App\Http\Controllers\Other\InspectionOther5Controller;
use App\Http\Controllers\Other\InspectionOther6Controller;
use App\Http\Controllers\Other\InspectionOther7Controller;
use App\Http\Controllers\Other\InspectionOther8Controller;
use App\Http\Controllers\Other\InspectionOther9Controller;
use App\Http\Controllers\Self\Inspection11SelfController;
use App\Http\Controllers\Self\Inspection12SelfController;
use App\Http\Controllers\Self\InspectionOther11SelfController;
use App\Http\Controllers\Self\InspectionOther12SelfController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::get('unauthorized', function () {
    return response()->json(['status' => 401, 'message' => 'Unauthorized.'], 401);
})->name('api.unauthorized');

Route::group(['middleware' => 'auth:sanctum'], function(){
    //All secure URL's
    Route::get("/user", [ApiController::class, "getEmployee"]);
    Route::get("/testdata", [ApiController::class, "getTestData"]);
    Route::get("lacms", [ApiController::class, "from_lacms"]);
    Route::get("test2", [ApiController::class, "getData"]);
    Route::get("/khmerdate/{mydate?}", [ApiController::class, "khmerDate"]);
    //Route::post("khmerdate/{mydate?}", [ApiController::class, "khmerDate"]);
    //Route::post('/me', [AuthController::class, 'me']);
    //Route::get("getdata/{date?}", [ApiController::class, "test"]);
    Route::get("/company/list",[CompanyController::class,"list"]);//

    Route::post('/logout', [AuthController::class, 'logoutCompany']);







    Route::get('self/garment/export_word/self_declaration/{inspection_id}/{json_opt?}', [ExportWordController::class, 'exportSelfDeclarationGarmentCategory']);//ទាញយករបាយការណ៍ស្តីពីអធិការកិច្ចការងារ normal inspection
    Route::get('self/other/export_word/self_declaration/{inspection_id}/{json_opt?}', [ExportWordController::class, 'exportSelfDeclarationOtherCategory']);//ទាញយករបាយការណ៍ស្តីពីអធិការកិច្ចការងារ normal inspection


});



/** ============== Below Route No Need Login ================ */
Route::any("login/app/company",[AuthController::class,"loginCompany"]);// login from sicms app
//Route::post('/login', [AuthController::class, 'loginCompany']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/update', [AuthController::class, 'update']);

Route::get("/khmerdate/{mydate?}", [ApiController::class, "khmerDate"]);



//Route::get("/khmerdate/{mydate?}", [ApiController::class, "khmerDate"]);
//Route::get("/testdata", [ApiController::class, "getTestData"]);
//Route::post("login", [UserController::class, 'login']);
