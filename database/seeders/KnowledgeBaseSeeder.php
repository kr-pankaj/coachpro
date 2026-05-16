<?php

namespace Database\Seeders;

use App\Models\KbArticle;
use App\Models\KbCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KnowledgeBaseSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Getting Started',
                'icon' => '🚀',
                'description' => 'Everything you need to know to set up your institute on QuonixAI.',
                'articles' => [
                    [
                        'title' => 'Quick Start Guide for Institute Admins',
                        'content' => '<h2>Welcome to QuonixAI!</h2><p>This guide will help you set up your institute in less than 10 minutes.</p><h3>Step 1: Branding</h3><p>Go to <strong>Settings</strong> and upload your logo and choose your brand colors. This will reflect on your unique portal URL.</p><h3>Step 2: Create Batches</h3><p>Navigate to <strong>Class Batches</strong> and create your first batch (e.g., Physics Morning Batch). Assign a faculty member if needed.</p><h3>Step 3: Add Students</h3><p>You can add students manually or share your <strong>Registration Link</strong> (found in Settings) to let students register themselves.</p>'
                    ],
                    [
                        'title' => 'Setting Up Your Custom Portal URL',
                        'content' => '<h2>Branded Portal Access</h2><p>Every institute gets a unique sub-path on QuonixAI. For example: <code>quonixai.com/your-institute-name</code>.</p><p>You can share this URL with your students and staff for direct login access. To change your slug, contact support or update it in <strong>Platform Settings</strong> (subject to availability).</p>'
                    ]
                ]
            ],
            [
                'name' => 'Finance & Fees',
                'icon' => '💳',
                'description' => 'Manage student fees, expenses, and financial reporting.',
                'articles' => [
                    [
                        'title' => 'How to Track Student Fees',
                        'content' => '<h2>Fee Management 101</h2><p>Managing fees is the core of QuonixAI. Here is how to do it efficiently:</p><ul><li><strong>Generate Fees:</strong> Go to the Finance Hub and click "Create Fee". Select a student or a whole batch.</li><li><strong>Partial Payments:</strong> QuonixAI supports partial payments. When a student pays some amount, click "Add Payment" on their fee record.</li><li><strong>Digital Receipts:</strong> Once a payment is recorded, you can download a professional PDF receipt to share via WhatsApp or Email.</li></ul>'
                    ],
                    [
                        'title' => 'Using the Expense Manager',
                        'content' => '<h2>Track Your Institute Expenses</h2><p>Keep your books clean by recording every rupee spent. Navigate to <strong>Expenses</strong> to add records for Rent, Electricity, Staff Salaries, or Marketing.</p><blockquote><strong>Pro Tip:</strong> Regularly recording expenses allows the system to show you your true net profitability at the end of the month.</blockquote>'
                    ]
                ]
            ],
            [
                'name' => 'Academic Features',
                'icon' => '🎓',
                'description' => 'Attendance, Quizzes, and Study Materials.',
                'articles' => [
                    [
                        'title' => 'Taking Attendance via Mobile',
                        'content' => '<h2>One-Tap Attendance</h2><p>Marking attendance is fast and easy:</p><ol><li>Open the sidebar and click <strong>Attendance</strong>.</li><li>Select the <strong>Batch</strong> and <strong>Date</strong>.</li><li>Tap the status for each student (Present, Absent, or Late).</li><li>Click <strong>Save</strong>. Parents will receive an automatic notification if their ward is absent.</li></ol>'
                    ],
                    [
                        'title' => 'Creating AI-Generated Quizzes',
                        'content' => '<h2>Smart Test Creation</h2><p>Don\'t spend hours writing questions. Use our AI tool!</p><p>Go to <strong>Assessments</strong> -> <strong>Create Quiz</strong>. You will see an "AI Generate" button. Simply provide a topic (e.g. "Laws of Motion") and the system will suggest high-quality MCQs for you.</p>'
                    ]
                ]
            ]
        ];

        foreach ($categories as $catData) {
            $articles = $catData['articles'];
            unset($catData['articles']);
            
            $catData['slug'] = Str::slug($catData['name']);
            $category = KbCategory::create($catData);

            foreach ($articles as $artData) {
                $artData['kb_category_id'] = $category->id;
                $artData['slug'] = Str::slug($artData['title']);
                $artData['is_published'] = true;
                $artData['is_internal_only'] = false;
                KbArticle::create($artData);
            }
        }
    }
}
