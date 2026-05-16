<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class GamificationSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            [
                'name' => 'First Steps',
                'slug' => 'first-steps',
                'description' => 'Complete your first quiz.',
                'icon' => '🐣',
                'color' => 'emerald',
                'requirement_type' => 'quizzes_count',
                'requirement_value' => 1,
            ],
            [
                'name' => 'Study Warrior',
                'slug' => 'study-warrior',
                'description' => 'Complete 10 quizzes.',
                'icon' => '⚔️',
                'color' => 'rose',
                'requirement_type' => 'quizzes_count',
                'requirement_value' => 10,
            ],
            [
                'name' => 'Unstoppable',
                'slug' => 'unstoppable',
                'description' => 'Reach a 7-day study streak.',
                'icon' => '🔥',
                'color' => 'orange',
                'requirement_type' => 'streak_days',
                'requirement_value' => 7,
            ],
            [
                'name' => 'Elite Mind',
                'slug' => 'elite-mind',
                'description' => 'Reach Level 10.',
                'icon' => '👑',
                'color' => 'amber',
                'requirement_type' => 'xp_total',
                'requirement_value' => 8100, // Level 10 requires 8100 XP (floor(sqrt(8100/100))+1 = 10)
            ],
            [
                'name' => 'Early Bird',
                'slug' => 'early-bird',
                'description' => 'Attend your first 5 classes.',
                'icon' => '🌅',
                'color' => 'sky',
                'requirement_type' => 'attendance_count', // Logic to be added later or mapped
                'requirement_value' => 5,
            ]
        ];

        foreach ($badges as $badge) {
            Badge::updateOrCreate(['slug' => $badge['slug']], $badge);
        }
    }
}
