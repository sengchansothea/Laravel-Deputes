<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TelegramController extends Controller
{
    public function notifyUpload(Request $request, $case)
    {
        $caseStepMsg = 'អ្នកប្រើបាន upload ឯកសារ';

        $result = caseStatusTelegramNotification($case, $caseStepMsg);

        return response()->json([
            'ok' => (bool) $result,
        ]);
    }
}
