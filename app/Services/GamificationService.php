<?php

namespace App\Services;

use App\Models\Student;
use App\Models\ExperienceTransaction;
use App\Models\Badge;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GamificationService
{
    /**
     * Award XP to a student and check for level ups.
     */
    public function awardXp(Student $student, int $amount, string $reason, $reference = null)
    {
        // Prevent duplicate awards for the same reference
        if ($reference && $student->experienceTransactions()
            ->where('reference_type', get_class($reference))
            ->where('reference_id', $reference->id)
            ->exists()) {
            return null;
        }

        return DB::transaction(function () use ($student, $amount, $reason, $reference) {
            // 1. Record the transaction
            $transaction = $student->experienceTransactions()->create([
                'amount' => $amount,
                'reason' => $reason,
                'reference_type' => $reference ? get_class($reference) : null,
                'reference_id' => $reference ? $reference->id : null,
            ]);

            // 2. Update student total
            $student->increment('xp_total', $amount);

            // 3. Update streak
            $this->updateStreak($student);

            // 4. Check for level up
            $newLevel = $this->calculateLevel($student->xp_total);
            if ($newLevel > $student->level) {
                $student->update(['level' => $newLevel]);
                // TODO: Fire level-up event
            }

            // 5. Check for badge eligibility
            $this->checkBadgeEligibility($student);

            return $transaction;
        });
    }

    /**
     * Calculate level based on XP.
     * Formula: Level = floor(sqrt(XP / 100)) + 1
     */
    public function calculateLevel(int $xp): int
    {
        if ($xp <= 0) return 1;
        return floor(sqrt($xp / 100)) + 1;
    }

    /**
     * Get XP required for the next level.
     */
    public function xpForLevel(int $level): int
    {
        if ($level <= 1) return 0;
        return pow($level - 1, 2) * 100;
    }

    /**
     * Update student's daily streak.
     */
    public function updateStreak(Student $student)
    {
        $now = Carbon::now();
        $lastActivity = $student->last_activity_at;

        if (!$lastActivity) {
            $student->update([
                'current_streak' => 1,
                'longest_streak' => 1,
                'last_activity_at' => $now,
            ]);
            return;
        }

        $lastActivity = Carbon::parse($lastActivity);

        if ($lastActivity->isToday()) {
            return; // Already updated today
        }

        if ($lastActivity->isYesterday()) {
            // Consecutive day
            $newStreak = $student->current_streak + 1;
            $student->update([
                'current_streak' => $newStreak,
                'longest_streak' => max($newStreak, $student->longest_streak),
                'last_activity_at' => $now,
            ]);
        } else {
            // Streak broken
            $student->update([
                'current_streak' => 1,
                'last_activity_at' => $now,
            ]);
        }
    }

    /**
     * Check if the student has earned any new badges.
     */
    public function checkBadgeEligibility(Student $student)
    {
        $eligibleBadges = Badge::whereNotIn('id', $student->badges()->pluck('badges.id'))
            ->get()
            ->filter(function ($badge) use ($student) {
                switch ($badge->requirement_type) {
                    case 'xp_total':
                        return $student->xp_total >= $badge->requirement_value;
                    case 'quizzes_count':
                        return $student->attempts()->count() >= $badge->requirement_value;
                    case 'streak_days':
                        return $student->longest_streak >= $badge->requirement_value;
                    case 'attendance_count':
                        return $student->attendances()->where('status', 'present')->count() >= $badge->requirement_value;
                    default:
                        return false;
                }
            });

        if ($eligibleBadges->isNotEmpty()) {
            $student->badges()->attach($eligibleBadges->pluck('id'));
            // TODO: Fire badge earned event
        }
    }
}
