<?php

namespace App\Console\Commands;

use App\Models\Cases;
use App\Models\User;
use App\Services\TelegramService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DisputesMLVTCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:daily-cases';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate and send daily case report via Telegram!';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info("✅ report:daily-cases command started at " . now());

        try {
            $this->caseDailyReport2Telegram();
        } catch (\Throwable $e) {
            Log::error("❌ report:daily-cases failed: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /** Daily Case Report */
    private function caseDailyReport2Telegram(){

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
        $reportMessage = "<b>"."📢 របាយការណ៌បូកសរុបសំណុំរឿងប្រចាំថ្ងៃ (".date2Display(Carbon::today()->toFormattedDateString())."</b>".")\n"
            . "===========================". "\n\n"
            . "#️⃣ សំណុំរឿងដែលបានបញ្ចូលថ្ងៃនេះ៖ "."<b>".number2KhmerNumber($todayCasesCount)."</b>". " បណ្តឹង\n\n"
            . "#️⃣ សំណុំរឿងសរុបទាំងអស់៖ "."<b>".number2KhmerNumber($totalCases)."</b>". " បណ្តឹង\n\n"
            . "===========================". "\n\n";

//        dd($reportMessage);

        foreach ($casesByUser as $userCase) {
            $user = User::find($userCase['userID']);
            $username = $user ? $user->k_fullname : "UserID={$userCase['userID']}";
            $caseCount = $userCase['caseCount'] ? number2KhmerNumber($userCase['caseCount']) : 0;
            $reportMessage .= "👤<b> $username</b>៖ <b> $caseCount</b> បណ្តឹង\n\n";
        }

//        dd($reportMessage);

        // Send report to telegram
        $telegram->sendMessage($reportMessage);

    }
}
