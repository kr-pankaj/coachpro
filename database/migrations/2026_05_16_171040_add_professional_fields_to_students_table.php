<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $blueprint) {
            $blueprint->text('bio')->nullable();
            $blueprint->json('skills')->nullable();
            $blueprint->json('notable_achievements')->nullable();
            $blueprint->string('github_url')->nullable();
            $blueprint->string('linkedin_url')->nullable();
            $blueprint->boolean('show_attendance_on_portfolio')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $blueprint) {
            $blueprint->dropColumn([
                'bio', 'skills', 'notable_achievements', 
                'github_url', 'linkedin_url', 'show_attendance_on_portfolio'
            ]);
        });
    }
};
