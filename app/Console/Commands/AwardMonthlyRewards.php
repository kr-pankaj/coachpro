<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use App\Models\MonthlyReward;
use App\Models\ExperienceTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AwardMonthlyRewards extends Command
{
    protected $signature = 'rewards:award-monthly {month?}';
    protected $description = 'Analyze performance and award top students for the month';

    public function handle()
    {
        $monthStr = $this->argument('month') ?? Carbon::now()->subMonth()->format('Y-m');
        $this->info("Awarding rewards for month: {$monthStr}");

        $start = Carbon::parse($monthStr)->startOfMonth();
        $end = Carbon::parse($monthStr)->endOfMonth();

        // Get Top XP Earners per Institute
        $topEarners = ExperienceTransaction::whereBetween('created_at', [$start, $end])
            ->select('student_id', DB::raw('SUM(amount) as total_xp'))
            ->groupBy('student_id')
            ->orderByDesc('total_xp')
            ->get();

        foreach ($topEarners as $index => $earner) {
            $student = Student::find($earner->student_id);
            if (!$student) continue;

            // Only award Top 3
            if ($index < 3) {
                MonthlyReward::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'institute_id' => $student->institute_id,
                        'month' => $monthStr,
                        'reward_type' => 'top_performer'
                    ],
                    [
                        'rank' => $index + 1,
                        'meta_data' => [
                            'xp_earned' => $earner->total_xp,
                        ]
                    ]
                );
                
                $this->info("Awarded Top " . ($index + 1) . " to {$student->name} with {$earner->total_xp} XP");
            }
        }

        $this->info('Monthly rewards awarded successfully.');
    }
}
