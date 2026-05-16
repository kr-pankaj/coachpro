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
        Schema::table('students', function (Blueprint $table) {
            $table->bigInteger('xp_total')->default(0)->after('user_id');
            $table->integer('level')->default(1)->after('xp_total');
            $table->integer('current_streak')->default(0)->after('level');
            $table->integer('longest_streak')->default(0)->after('current_streak');
            $table->timestamp('last_activity_at')->nullable()->after('longest_streak');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['xp_total', 'level', 'current_streak', 'longest_streak', 'last_activity_at']);
        });
    }
};
