<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Institute;
use App\Models\User;
use App\Models\Batch;
use App\Models\Student;
use App\Models\Enquiry;
use App\Models\Attendance;
use App\Models\Fee;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\QuizOption;
use App\Models\QuizAttempt;
use App\Models\Announcement;
use App\Models\StudyMaterial;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // 0. Cleanup existing data (optional but good for a fresh start)
        // Note: migrate:fresh already handles this, so we don't need it here.

        $institutesData = [
            [
                'name' => 'QuonixAI Elite Academy',
                'city' => 'Mumbai',
                'admin_email' => 'admin@quonixai.com',
                'color' => 'indigo'
            ],
            [
                'name' => 'Starlight Coaching Hub',
                'city' => 'Delhi',
                'admin_email' => 'admin@starlight.com',
                'color' => 'emerald'
            ],
            [
                'name' => 'Global Science Center',
                'city' => 'Bangalore',
                'admin_email' => 'admin@global.com',
                'color' => 'rose'
            ],
            [
                'name' => 'Future Stars Institute',
                'city' => 'Pune',
                'admin_email' => 'admin@future.com',
                'color' => 'amber'
            ]
        ];

        $firstNames = ['Arjun', 'Sanya', 'Vikram', 'Ananya', 'Rohan', 'Ishani', 'Kabir', 'Zoya', 'Aarav', 'Kiara', 'Ishaan', 'Diya', 'Aryan', 'Myra', 'Advait', 'Saanvi', 'Reyansh', 'Anvi', 'Dev', 'Prisha'];
        $lastNames = ['Kapoor', 'Reddy', 'Malhotra', 'Bose', 'Gupta', 'Joshi', 'Singh', 'Deshmukh', 'Patel', 'Nair', 'Sharma', 'Verma', 'Iyer', 'Khan', 'Das', 'Chatterjee', 'Mishra', 'Yadav', 'Kulkarni', 'Mehta'];

        foreach ($institutesData as $idx => $instData) {
            $this->command->info("Seeding Institute: {$instData['name']}");
            
            $createdAt = now()->subMonths(12); // Established 1 year ago
            
            // 1. Create Institute
            $institute = Institute::create([
                'name' => $instData['name'],
                'slug' => Str::slug($instData['name']),
                'phone' => '91' . rand(10000000, 99999999),
                'contact_email' => $instData['admin_email'],
                'description' => $instData['name'] . ' is a premier coaching center specializing in advanced competitive exams, powered by the QuonixAI management system.',
                'city' => $instData['city'],
                'is_lifetime_free' => ($idx === 0),
                'created_at' => $createdAt,
            ]);

            // 2. Create Admin
            User::create([
                'name' => $instData['name'] . ' Admin',
                'email' => $instData['admin_email'],
                'password' => Hash::make('password'),
                'role' => 'admin',
                'institute_id' => $institute->id,
            ]);

            // 3. Create Teachers
            $teachers = [];
            $subjectsList = ['Physics', 'Mathematics', 'Chemistry', 'Biology', 'Computer Science', 'English'];
            foreach ($subjectsList as $sIdx => $subjectName) {
                $teachers[] = User::create([
                    'name' => "Professor {$firstNames[$sIdx]} ({$subjectName})",
                    'email' => strtolower($subjectName) . ".{$idx}@quonixai.com",
                    'password' => Hash::make('password'),
                    'role' => 'teacher',
                    'institute_id' => $institute->id,
                ]);
            }

            // 4. Create Batches
            $batches = [];
            foreach ($subjectsList as $sIdx => $subjectName) {
                // Create 2 batches per subject (Morning & Evening)
                foreach (['Morning', 'Evening'] as $timePref) {
                    $batches[] = Batch::create([
                        'institute_id' => $institute->id,
                        'name' => "{$subjectName} {$timePref} Batch",
                        'subject' => $subjectName,
                        'time_slot' => ($timePref === 'Morning' ? '08:00 AM - 10:00 AM' : '04:00 PM - 06:00 PM'),
                        'teacher_id' => $teachers[$sIdx]->id,
                    ]);
                }
            }

            // 5. Announcements
            $announcementTypes = ['urgent', 'update', 'event'];
            foreach ($announcementTypes as $type) {
                Announcement::create([
                    'institute_id' => $institute->id,
                    'title' => ucfirst($type) . ": Important Notice for " . $institute->name,
                    'content' => "This is a sample {$type} announcement content. Please stay updated with the latest news from the institute.",
                    'type' => $type,
                    'is_active' => true,
                    'created_at' => now()->subDays(rand(1, 10)),
                ]);
            }

            // 6. Study Materials
            foreach ($batches as $batch) {
                StudyMaterial::create([
                    'institute_id' => $institute->id,
                    'batch_id' => $batch->id,
                    'title' => "Introduction to " . $batch->subject,
                    'description' => "Foundational notes for the " . $batch->subject . " course.",
                    'file_url' => 'demo/sample.pdf',
                    'type' => 'notes',
                    'created_at' => now()->subMonths(2),
                ]);
            }

            // 7. Students (50 per institute)
            $this->command->comment("  -> Creating 50 students for {$institute->name}...");
            for ($i = 0; $i < 50; $i++) {
                $firstName = $firstNames[array_rand($firstNames)];
                $lastName = $lastNames[array_rand($lastNames)];
                $fullName = "{$firstName} {$lastName}";
                // Ensure some students join in the last 7 days for the chart
                $joinedAt = ($i < 10) ? now()->subDays(rand(0, 6)) : now()->subMonths(rand(1, 8));

                $user = User::create([
                    'name' => $fullName,
                    'email' => strtolower($firstName . $lastName) . ".{$idx}.{$i}@quonix.io",
                    'password' => Hash::make('password'),
                    'role' => 'student',
                    'institute_id' => $institute->id,
                    'created_at' => $joinedAt,
                ]);

                $student = Student::create([
                    'institute_id' => $institute->id,
                    'user_id' => $user->id,
                    'name' => $fullName,
                    'phone' => '9' . rand(100000000, 999999999),
                    'batch_id' => $batches[array_rand($batches)]->id,
                    'joined_date' => $joinedAt->toDateString(),
                    'created_at' => $joinedAt,
                ]);

                // 8. Historical Fees (6 months)
                for ($m = 0; $m < 6; $m++) {
                    $month = now()->subMonths($m);
                    if ($joinedAt->isAfter($month->endOfMonth())) continue;

                    $total = 6500;
                    // Current month (index 0) might be pending/partial, others likely paid
                    $statusRoll = rand(1, 10);
                    if ($m === 0) {
                        $paid = ($statusRoll > 7) ? 6500 : (($statusRoll > 4) ? 3000 : 0);
                    } else {
                        $paid = ($statusRoll > 2) ? 6500 : 0;
                    }
                    
                    Fee::create([
                        'institute_id' => $institute->id,
                        'student_id' => $student->id,
                        'total_amount' => $total,
                        'paid_amount' => $paid,
                        'due_amount' => $total - $paid,
                        'discount_amount' => 0,
                        'month_year' => $month->format('F Y'), // Controller expects 'May 2026'
                        'status' => ($paid >= $total) ? 'paid' : ($paid > 0 ? 'partial' : 'pending'),
                        'payment_date' => ($paid > 0) ? $month->copy()->startOfMonth()->addDays(rand(1, 15))->toDateString() : null,
                        'created_at' => $month,
                    ]);
                }

                // 9. Attendance (Last 90 days)
                // Note: To keep it fast, we skip Sundays
                for ($d = 0; $d < 90; $d++) {
                    $date = now()->subDays($d);
                    if ($date->isSunday() || $joinedAt->isAfter($date)) continue;
                    
                    Attendance::create([
                        'institute_id' => $institute->id,
                        'student_id' => $student->id,
                        'batch_id' => $student->batch_id,
                        'date' => $date->toDateString(),
                        'status' => (rand(1, 100) > 15) ? 'present' : 'absent', // 15% absenteeism
                    ]);
                }
            }

            // 10. Quizzes and Attempts
            foreach ($batches as $batch) {
                $quiz = Quiz::create([
                    'institute_id' => $institute->id,
                    'batch_id' => $batch->id,
                    'title' => "Monthly {$batch->subject} Assessment",
                    'total_marks' => 100,
                    'passing_marks' => 40,
                    'time_limit_minutes' => 60,
                ]);

                // Create attempts for students in this batch
                $batchStudents = Student::where('batch_id', $batch->id)->get();
                foreach ($batchStudents as $student) {
                    if (rand(0, 1)) { // 50% participation
                        $score = rand(20, 98);
                        QuizAttempt::create([
                            'quiz_id' => $quiz->id,
                            'student_id' => $student->id,
                            'score' => $score,
                            'total_marks' => 100,
                            'total_questions' => 20,
                            'correct_answers' => ceil($score / 5),
                            'completed_at' => now()->subDays(rand(1, 20)),
                            'status' => 'completed',
                        ]);
                    }
                }
            }

            // 11. Leads (Enquiries)
            $leadStatuses = ['new', 'contacted', 'demo_scheduled', 'converted', 'lost'];
            for ($i = 0; $i < 30; $i++) {
                Enquiry::create([
                    'institute_id' => $institute->id,
                    'student_name' => $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)],
                    'phone' => '9' . rand(100000000, 999999999),
                    'email' => "lead.{$idx}.{$i}@gmail.com",
                    'course_interested' => 'IIT-JEE Foundation',
                    'status' => $leadStatuses[array_rand($leadStatuses)],
                    'created_at' => now()->subDays(rand(1, 60)),
                ]);
            }
        }

        $this->command->info('ULTIMATE v1.0.6.0 Dataset Seeded Successfully!');
    }
}
