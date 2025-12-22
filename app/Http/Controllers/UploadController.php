<?php

namespace App\Http\Controllers;

use App\Models\CaseInvitation;
use App\Models\CaseLog34;
use App\Models\CaseLog5;
use App\Models\CaseLog6;
use App\Models\Cases;
use App\Models\InvitationNextTime;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    /**
     * Config for upload types
     */
    private function getUploadConfig()
    {
        return [
            // url_opt => [title, prefix, folder callback, model, column, log6?, extra update]
            1 => [
                'title' => 'Upload លិខិត',
                'prefix' => 'case_file',
                'folder' => fn($type, $year) => $type == 3 ? "case_doc/collectives/$year/" : "case_doc/form1/$year/",
                'model' => Cases::class,
                'column' => 'case_file'
            ],
            3 => [
                'title' => 'Upload លិខិត',
                'prefix' => 'employee_inv',
                'folder' => fn($type, $year) => $type == 3 ? "collectives_invitation/$year/" : "invitation/$year/",
                'model' => CaseInvitation::class,
                'column' => 'invitation_file'
            ],
            33 => [
                'title' => 'Upload លិខិតលើកពេល ដែលមានចុះហត្ថលេខាទទួល',
                'prefix' => 'invitation_next',
                'folder' => fn($type, $year) => $type == 3 ? "collectives_invitation/next/$year/" : "invitation/next/$year/",
                'model' => InvitationNextTime::class,
                'column' => 'letter'
            ],
            4 => [
                'title' => 'Upload កំណត់ហេតុដែលបានចុះហត្ថលេខា ផ្ដិតមេដៃ និងវាយត្រាឈ្មោះ',
                'prefix' => 'employee_log',
                'folder' => fn($type, $year) => $type == 3 ? "case_doc/collectives/log34/$year/" : "case_doc/log34/$year/",
                'model' => CaseLog34::class,
                'column' => 'log_file'
            ],
            5 => [
                'title' => 'Upload លិខិត',
                'prefix' => 'company_inv',
                'folder' => fn($type, $year) => $type == 3 ? "collectives_invitation/$year/" : "invitation/$year/",
                'model' => CaseInvitation::class,
                'column' => 'invitation_file'
            ],
            55 => [
                'title' => 'Upload លិខិតលើកពេល ដែលមានចុះហត្ថលេខាទទួល',
                'prefix' => 'invitation_next',
                'folder' => fn($type, $year) => $type == 3 ? "collectives_invitation/next/$year/" : "invitation/next/$year/",
                'model' => InvitationNextTime::class,
                'column' => 'letter'
            ],
            6 => [
                'title' => 'Upload កំណត់ហេតុដែលបានចុះហត្ថលេខា ផ្ដិតមេដៃ និងវាយត្រាឈ្មោះ',
                'prefix' => 'company_log',
                'folder' => fn($type, $year) => $type == 3 ? "case_doc/collectives/log5/$year/" : "case_doc/log5/$year/",
                'model' => CaseLog5::class,
                'column' => 'log_file'
            ],
            7 => [
                'title' => 'Upload លិខិតអញ្ជើញដែលមានចុះហត្ថលេខាទទួល',
                'prefix' => 'invitation_both',
                'folder' => fn($type, $year) => $type == 3 ? "collectives_invitation/$year/" : "invitation/$year/",
                'model' => CaseInvitation::class,
                'column' => 'invitation_file'
            ],
            77 => [
                'title' => 'Upload លិខិតអញ្ជើញដែលមានចុះហត្ថលេខាទទួល',
                'prefix' => 'invitation_next',
                'folder' => fn($type, $year) => $type == 3 ? "collectives_invitation/next/$year/" : "invitation/next/$year/",
                'model' => InvitationNextTime::class,
                'column' => 'letter'
            ],
            8 => [
                'title' => 'Upload កំណត់ហេតុដែលបានចុះហត្ថលេខា ផ្ដិតមេដៃ និងវាយត្រាឈ្មោះ',
                'prefix' => 'conflict_log',
                'folder' => fn($type, $year) => $type == 3 ? "case_doc/collectives/log6/$year/" : "case_doc/log6/$year/",
                'model' => CaseLog6::class,
                'column' => 'log_file'
            ],
            81 => [
                'title' => 'Upload កំណត់ហេតុដែលបានចុះហត្ថលេខា ផ្ដិតមេដៃ និងវាយត្រាឈ្មោះ',
                'prefix' => 'status_letter',
                'folder' => fn($type, $year) => $type == 3 ? "case_doc/collectives/log6/status_letter/$year/" : "case_doc/log6/status_letter/$year/",
                'model' => CaseLog6::class,
                'column' => 'status_letter'
            ],
            82 => [
                'title' => 'ការសុំផ្សះផ្សាឡើងវិញ',
                'prefix' => 'reopen_letter',
                'folder' => fn($type, $year) => $type == 3 ? "case_doc/collectives/log6/status_letter/$year/" : "case_doc/log6/status_letter/$year/",
                'model' => CaseLog6::class,
                'column' => 'status_letter',
                'extra' => fn($req) => [
                    'reopen_status' => 1,
                    'status_date' => date2DB($req->status_date),
                    'status_time' => $req->status_time
                ],
                'log6' => true
            ],
            83 => [
                'title' => 'កែប្រែព័ត៌មានសុំលើកពេលផ្សះផ្សា',
                'model' => CaseLog6::class,
                'extra' => fn($req) => [
                    'reopen_status' => 0,
                    'status_date' => date2DB($req->status_date),
                    'status_time' => $req->status_time
                ],
                'log6' => true
            ],
            84 => [
                'title' => 'កែប្រែព័ត៌មានសុំផ្សះផ្សាឡើងវិញ',
                'model' => CaseLog6::class,
                'extra' => fn($req) => [
                    'reopen_status' => 1,
                    'status_date' => date2DB($req->status_date),
                    'status_time' => $req->status_time
                ],
                'log6' => true
            ]
        ];
    }

    /**
     * Form display
     */
    public function formUploadFileAll($url_opt, $case_id, $id)
    {
        $config = $this->getUploadConfig()[$url_opt] ?? null;
        if (!$config) abort(404);

        $caseData = Cases::findOrFail($case_id);
        $data = [
            'pagetitle' => $config['title'],
            'case_id' => $case_id,
            'case_type' => $caseData->case_type_id,
            'case_year' => !empty($caseData->case_date) ? date2Display($caseData->case_date, 'Y') : myDate('Y'),
            'id' => $id,
            'url' => url("uploads/$case_id"),
            'url_opt' => $url_opt,
            'file_name' => $config['prefix'] ?? ''
        ];

        if (!empty($config['log6'])) {
            $data['log6'] = CaseLog6::find($id);
        }

        $view = $config['view'] ?? 'case.form_upload_file_all';
        return view($view, ['adata' => $data]);
    }

    /**
     * Handle upload
     */
    public function update(Request $request, string $id)
    {
        $caseYear = $request->case_year;
        $caseType = $request->case_type;
        $urlOpt = $request->url_opt;
        $config = $this->getUploadConfig()[$urlOpt] ?? null;
        if (!$config) abort(404);

        // Skip validation for edit-only options
        // 83: កែប្រែព័ត៌មានលើកពេលផ្សះផ្សា
        // 84: កែប្រែព័ត៌មានសុំផ្សះផ្សាឡើងវិញ
        if (!in_array($urlOpt, [83, 84])) {
            $request->validate([
                'file' => 'required|mimes:pdf,PDF|max:15360',
            ]);

            $file = $request->file('file');
            $prefix = $config['prefix'] ?? time();
            $fileName = "{$request->id}_{$prefix}." . $file->getClientOriginalExtension();
            $folder = pathToUploadFile(($config['folder'])($caseType, $caseYear));

            $this->saveFileAndUpdate(
                $file,
                $folder,
                $fileName,
                $config['model'],
                $request->id,
                $config['column'] ?? null,
                $config['extra']['closure'] ?? ($config['extra'] ?? null),
                $request
            );
        } else {
            // Edit-only cases
            ($config['model'])::where("id", $request->id)->update(($config['extra'])($request));
        }

        // Notify Telegram about the successful upload (uses helper in app/Helpers/helpers_soklay.php)
        try {
            $caseId = $request->case_id ?? null;
            $uploadedFile = $fileName ?? null;
            $msg = 'Uploaded file: ' . ($uploadedFile ?? 'n/a');
            if ($caseId) {
                // caseStatusTelegramNotification accepts case (id or model) and a message
                caseStatusTelegramNotification($caseId, $msg);
            }
        } catch (\Throwable $e) {
            // don't break the upload flow for notification errors; log for debugging
            \Log::error('Telegram notify after upload failed: ' . $e->getMessage());
        }

        // Redirect or JSON
        if ($request->form_upload === "normal") {
            return redirect(($caseType == 3 ? "collective_cases/" : "cases/") . $request->case_id)
                ->with("message", sweetalert()->addSuccess("ជោគជ័យ"));
        }
        return response()->json(['message' => 'Upload ជោគជ័យ']);
    }

    /**
     * File save + DB update helper
     */
    private function saveFileAndUpdate($file, $folder, $fileName, $model, $id, $column = null, $extra = null, $request = null)
    {
        // If $folder is already an absolute filesystem path, use it as-is.
        // Otherwise, resolve it relative to the public path.
        $isAbsolute = false;
        if (!empty($folder)) {
            // unix-style absolute (/...)
            if ($folder[0] === '/' || $folder[0] === '\\') {
                $isAbsolute = true;
            }
            // windows-style drive letter (C:\ or C:/)
            elseif (strlen($folder) >= 3 && ctype_alpha($folder[0]) && $folder[1] === ':' && ($folder[2] === '\\' || $folder[2] === '/')) {
                $isAbsolute = true;
            }
        }

        $fullPath = $isAbsolute ? $folder : public_path($folder);

        /** If Directory is not Found, create it (robustly) */
        if (!is_dir($fullPath)) {
            if (!mkdir($fullPath, 0777, true) && !is_dir($fullPath)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $fullPath));
            }
        }

        $file->move($fullPath, $fileName);

        $updateData = $column ? [$column => $fileName] : [];
        if ($extra instanceof \Closure) {
            $updateData = array_merge($updateData, $extra($request));
        } elseif (is_array($extra)) {
            $updateData = array_merge($updateData, $extra);
        }

        $model::where("id", $id)->update($updateData);
    }
}
