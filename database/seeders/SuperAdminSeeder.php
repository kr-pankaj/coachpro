<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'superadmin@coachpro.app'],
            [
                'name'         => 'Super Admin',
                'email'        => 'superadmin@coachpro.app',
                'password'     => Hash::make('superadmin123'),
                'role'         => 'superadmin',
                'institute_id' => null,
            ]
        );

        $this->command->info('Super admin created:');
        $this->command->line('  Email:    superadmin@coachpro.app');
        $this->command->line('  Password: superadmin123');
        $this->command->warn('  ⚠ Change the password immediately after first login!');
    }
}
