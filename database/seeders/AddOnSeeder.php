<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddOnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $addOns = [
            [
                'name' => 'Advanced SMS Gateway',
                'description' => 'Send automated SMS notifications to parents for attendance and fee reminders.',
                'slug' => 'advanced-sms',
                'price' => 1999.00,
                'is_active' => true,
                'is_promoted' => true,
            ],
            [
                'name' => 'Lifetime Financial Archiving',
                'description' => 'Keep your fee receipt history forever instead of just 6 months.',
                'slug' => 'lifetime-archiving',
                'price' => 999.00,
                'is_active' => true,
                'is_promoted' => false,
            ],
            [
                'name' => 'Verified Portal Badge',
                'description' => 'Show a verified checkmark on your student login portal to build trust.',
                'slug' => 'verified-badge',
                'price' => 499.00,
                'is_active' => true,
                'is_promoted' => false,
            ],
        ];

        foreach ($addOns as $addOn) {
            \App\Models\AddOn::updateOrCreate(['slug' => $addOn['slug']], $addOn);
        }
    }
}
