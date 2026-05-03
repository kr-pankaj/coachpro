<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add Soft Deletes to core tables
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('students', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('batches', function (Blueprint $table) {
            $table->softDeletes();
            $table->boolean('is_active')->default(true)->after('institute_id');
        });

        Schema::table('fees', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('enquiries', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('batches', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn('is_active');
        });

        Schema::table('fees', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('enquiries', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
