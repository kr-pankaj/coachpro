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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $institutesData = [
            [
                'name' => 'Elite Academy of Science',
                'city' => 'Mumbai',
                'admin_email' => 'admin@elite.com',
                'color' => 'indigo'
            ],
            [
                'name' => 'Global Coaching Center',
                'city' => 'Delhi',
                'admin_email' => 'admin@global.com',
                'color' => 'emerald'
            ],
            [
                'name' => 'Future Stars Institute',
                'city' => 'Bangalore',
                'admin_email' => 'admin@future.com',
                'color' => 'rose'
            ]
        ];

        foreach ($institutesData as $idx => $instData) {
            // Stagger creation dates: 0 -> 5 months ago, 1 -> 3 months ago, 2 -> 1 month ago
            $createdAt = now()->subMonths(5 - ($idx * 2));
            
            // 1. Create Institute
            $institute = Institute::create([
                'name' => $instData['name'],
                'slug' => Str::slug($instData['name']) . '-' . Str::random(5),
                'phone' => '9' . rand(100000000, 999999999),
                'contact_email' => $instData['admin_email'],
                'description' => $instData['name'] . ' is a premium educational center.',
                'address' => rand(1, 999) . ', Metro Park',
                'city' => $instData['city'],
                'state' => 'State Name',
                'pincode' => '40000' . $idx,
                'is_lifetime_free' => ($idx === 0), // First one is elite
                'created_at' => $createdAt,
            ]);

            // 2. Create Admin
            User::create([
                'name' => 'Manager ' . ($idx + 1),
                'email' => $instData['admin_email'],
                'password' => Hash::make('password'),
                'role' => 'admin',
                'institute_id' => $institute->id,
            ]);

            // 3. Create Teachers
            $teachers = [];
            for ($i = 1; $i <= 3; $i++) {
                $teachers[] = User::create([
                    'name' => 'Prof. Teacher ' . $i . ' (' . $instData['name'] . ')',
                    'email' => 'teacher' . $i . '.' . $idx . '@coachpro.com',
                    'password' => Hash::make('password'),
                    'role' => 'teacher',
                    'institute_id' => $institute->id,
                ]);
            }

            // 4. Create Batches
            $subjects = ['Physics', 'Mathematics', 'Chemistry', 'Biology', 'English'];
            $batches = [];
            foreach ($subjects as $sIdx => $subject) {
                $batches[] = Batch::create([
                    'institute_id' => $institute->id,
                    'name' => $subject . ' - Grade ' . (10 + ($sIdx % 3)),
                    'subject' => $subject,
                    'time_slot' => (8 + $sIdx) . ':00 AM - ' . (10 + $sIdx) . ':00 AM',
                    'teacher_id' => $teachers[$sIdx % 3]->id,
                ]);
            }

            // 5. Create Students with historical joining dates
            $firstNames = ['Arjun', 'Sanya', 'Vikram', 'Ananya', 'Rohan', 'Ishani', 'Kabir', 'Zoya', 'Aarav', 'Kiara', 'Ishaan', 'Diya', 'Aryan', 'Myra', 'Advait'];
            $lastNames = ['Kapoor', 'Reddy', 'Malhotra', 'Bose', 'Gupta', 'Joshi', 'Singh', 'Deshmukh', 'Patel', 'Nair', 'Sharma', 'Verma', 'Iyer', 'Khan', 'Das'];

            $students = [];
            for ($i = 0; $i < 50; $i++) {
                $firstName = $firstNames[array_rand($firstNames)];
                $lastName = $lastNames[array_rand($lastNames)];
                $fullName = $firstName . ' ' . $lastName;
                
                // Weight joining dates: 50% in last 30 days, 50% older
                if (rand(0, 1)) {
                    $joinedAt = now()->subDays(rand(0, 30));
                } else {
                    $joinedAt = now()->subDays(rand(31, 180));
                }

                $user = User::create([
                    'name' => $fullName,
                    'email' => strtolower($firstName . $lastName . rand(100, 999)) . '@inst' . $idx . '.com',
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
                $students[] = $student;

                // 6. Generate Fees for this student for the last 6 months
                for ($m = 0; $m < 6; $m++) {
                    $feeMonth = now()->subMonths($m);
                    if ($joinedAt->isBefore($feeMonth->endOfMonth())) {
                        // Current month is likely pending, others likely paid
                        $isCurrentMonth = ($m === 0);
                        $isPaid = $isCurrentMonth ? (rand(1, 10) > 7) : (rand(1, 10) > 2);
                        
                        Fee::create([
                            'institute_id' => $institute->id,
                            'student_id' => $student->id,
                            'amount' => rand(3000, 8000),
                            'month_year' => $feeMonth->format('F Y'),
                            'status' => $isPaid ? 'paid' : 'pending',
                            'payment_date' => $isPaid ? $feeMonth->copy()->startOfMonth()->addDays(rand(0, 20))->toDateString() : null,
                            'created_at' => $feeMonth,
                        ]);
                    }
                }
            }

            // 7. Attendance for last 30 days
            foreach ($batches as $batch) {
                $batchStudents = Student::where('batch_id', $batch->id)->get();
                for ($d = 0; $d < 30; $d++) {
                    $date = now()->subDays($d);
                    if ($date->isSunday()) continue;

                    foreach ($batchStudents as $student) {
                        Attendance::create([
                            'institute_id' => $institute->id,
                            'student_id' => $student->id,
                            'batch_id' => $batch->id,
                            'date' => $date->toDateString(),
                            'status' => (rand(1, 10) > 1) ? 'present' : 'absent',
                            'created_at' => $date,
                        ]);
                    }
                }
            }

            // 8. Leads (Enquiries) with historical creation
            $leadStatuses = ['new', 'contacted', 'demo_scheduled', 'converted', 'lost'];
            for ($i = 0; $i < 20; $i++) {
                $createdAt = now()->subDays(rand(0, 90));
                Enquiry::create([
                    'institute_id' => $institute->id,
                    'student_name' => $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)],
                    'phone' => '7' . rand(100000000, 999999999),
                    'email' => 'lead' . $i . '@test.com',
                    'course_interested' => 'IIT Foundation',
                    'status' => $leadStatuses[array_rand($leadStatuses)],
                    'created_at' => $createdAt,
                ]);
            }
        }

        $this->command->info('Multi-Institute Elite Data Seeded Successfully!');
    }
}
