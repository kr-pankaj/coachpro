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
        // 1. Create a Premium Demo Institute
        $institute = Institute::create([
            'name' => 'Elite Academy of Science',
            'slug' => 'elite-academy-' . Str::random(5),
            'phone' => '9876543210',
            'contact_email' => 'contact@eliteacademy.com',
            'description' => 'Elite Academy is a premier coaching institute specializing in IIT-JEE and NEET preparation.',
            'address' => '123, Education Hub',
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'pincode' => '400001',
            'logo_url' => null,
            'is_lifetime_free' => false,
        ]);

        // 2. Create Admin for the Institute
        $admin = User::create([
            'name' => 'Aditya Sharma',
            'email' => 'admin@elite.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'institute_id' => $institute->id,
        ]);

        // 3. Create Teachers
        $teacherNames = ['Prof. Rajesh Khanna', 'Dr. Meera Iyer', 'Prof. Sunil Verma'];
        $teachers = [];
        foreach ($teacherNames as $name) {
            $teachers[] = User::create([
                'name' => $name,
                'email' => strtolower(str_replace([' ', '.'], '', $name)) . '@elite.com',
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'institute_id' => $institute->id,
            ]);
        }

        // 4. Create Batches
        $batchData = [
            ['name' => 'PCM - Batch A', 'subject' => 'Physics', 'slot' => '08:00 AM - 10:00 AM', 'teacher' => $teachers[0]],
            ['name' => 'Mathematics Pro', 'subject' => 'Maths', 'slot' => '10:30 AM - 12:30 PM', 'teacher' => $teachers[1]],
            ['name' => 'Chemistry Foundation', 'subject' => 'Chemistry', 'slot' => '02:00 PM - 04:00 PM', 'teacher' => $teachers[2]],
        ];

        $batches = [];
        foreach ($batchData as $data) {
            $batches[] = Batch::create([
                'institute_id' => $institute->id,
                'name' => $data['name'],
                'subject' => $data['subject'],
                'time_slot' => $data['slot'],
                'teacher_id' => $data['teacher']->id,
            ]);
        }

        // 5. Create Students
        $studentFirstNames = ['Arjun', 'Sanya', 'Vikram', 'Ananya', 'Rohan', 'Ishani', 'Kabir', 'Zoya', 'Aarav', 'Kiara'];
        $studentLastNames = ['Kapoor', 'Reddy', 'Malhotra', 'Bose', 'Gupta', 'Joshi', 'Singh', 'Deshmukh', 'Patel', 'Nair'];

        $allStudents = [];
        foreach ($batches as $bIndex => $batch) {
            for ($i = 0; $i < 5; $i++) {
                $firstName = $studentFirstNames[array_rand($studentFirstNames)];
                $lastName = $studentLastNames[array_rand($studentLastNames)];
                $fullName = $firstName . ' ' . $lastName;
                
                // Create User for Student
                $user = User::create([
                    'name' => $fullName,
                    'email' => strtolower($firstName . $lastName . rand(100, 999)) . '@student.com',
                    'password' => Hash::make('password'),
                    'role' => 'student',
                    'institute_id' => $institute->id,
                ]);

                $student = Student::create([
                    'institute_id' => $institute->id,
                    'user_id' => $user->id,
                    'name' => $fullName,
                    'phone' => '9' . rand(100000000, 999999999),
                    'parent_phone' => '8' . rand(100000000, 999999999),
                    'address' => 'Shanti Nagar, Mumbai',
                    'batch_id' => $batch->id,
                    'joined_date' => now()->subMonths(rand(1, 6))->toDateString(),
                ]);
                $allStudents[] = $student;
            }
        }

        // 6. Create Enquiries (Leads)
        $leadStatuses = ['new', 'contacted', 'demo_scheduled', 'converted', 'lost'];
        for ($i = 0; $i < 8; $i++) {
            $firstName = $studentFirstNames[array_rand($studentFirstNames)];
            $lastName = $studentLastNames[array_rand($studentLastNames)];
            Enquiry::create([
                'institute_id' => $institute->id,
                'student_name' => $firstName . ' ' . $lastName,
                'phone' => '7' . rand(100000000, 999999999),
                'email' => strtolower($firstName . $lastName) . '@gmail.com',
                'course_interested' => 'IIT-JEE Foundation',
                'status' => $leadStatuses[array_rand($leadStatuses)],
                'next_follow_up_date' => now()->addDays(rand(1, 10))->toDateString(),
                'notes' => 'Looking for physics classes.',
            ]);
        }

        // 7. Create Attendance for the last 5 days
        foreach ($batches as $batch) {
            $batchStudents = Student::where('batch_id', $batch->id)->get();
            for ($d = 0; $d < 5; $d++) {
                $date = now()->subDays($d);
                if ($date->isSunday()) continue;

                foreach ($batchStudents as $student) {
                    Attendance::create([
                        'institute_id' => $institute->id,
                        'student_id' => $student->id,
                        'batch_id' => $batch->id,
                        'date' => $date->toDateString(),
                        'status' => (rand(1, 10) > 2) ? 'present' : 'absent',
                    ]);
                }
            }
        }

        // 8. Create Fees
        $months = [
            Carbon::now()->format('F Y'),
            Carbon::now()->subMonth()->format('F Y'),
        ];
        foreach ($allStudents as $student) {
            foreach ($months as $month) {
                Fee::create([
                    'institute_id' => $institute->id,
                    'student_id' => $student->id,
                    'amount' => rand(5000, 10000),
                    'month_year' => $month,
                    'status' => (rand(0, 1) === 1) ? 'paid' : 'pending',
                    'payment_date' => (rand(0, 1) === 1) ? now()->subDays(rand(1, 20))->toDateString() : null,
                ]);
            }
        }

        // 9. Create a Quiz
        $quiz = Quiz::create([
            'institute_id' => $institute->id,
            'batch_id' => $batches[0]->id,
            'title' => 'Weekly Physics Quiz - Motion',
            'description' => 'Test your knowledge on kinematics and dynamics.',
            'time_limit_minutes' => 20,
            'is_active' => true,
        ]);

        $questionsData = [
            [
                'text' => 'What is the SI unit of acceleration?',
                'options' => ['m/s', 'm/s²', 'km/h', 'N'],
                'correct' => 1
            ],
            [
                'text' => 'A body starts from rest and moves with uniform acceleration of 2 m/s². What is its velocity after 5 seconds?',
                'options' => ['5 m/s', '10 m/s', '20 m/s', '2.5 m/s'],
                'correct' => 1
            ],
        ];

        foreach ($questionsData as $i => $qData) {
            $question = Question::create([
                'quiz_id' => $quiz->id,
                'question_text' => $qData['text'],
                'marks' => 2,
                'order' => $i,
            ]);

            foreach ($qData['options'] as $j => $optText) {
                QuizOption::create([
                    'question_id' => $question->id,
                    'option_text' => $optText,
                    'is_correct' => ($j === $qData['correct']),
                ]);
            }
        }

        $this->command->info('Demo Data seeded successfully!');
        $this->command->line('-------------------------------');
        $this->command->line('Admin Login: admin@elite.com / password');
        $this->command->line('Teacher Login: ' . $teachers[0]->email . ' / password');
        $this->command->line('Student Login: ' . $allStudents[0]->user->email . ' / password');
        $this->command->line('-------------------------------');
    }
}
