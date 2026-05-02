<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('email')->nullable()->after('name');
            $table->date('date_of_birth')->nullable()->after('phone');
            $table->string('gender')->nullable()->after('date_of_birth'); // Male/Female/Other
            $table->string('school_college')->nullable()->after('gender');
            $table->string('standard_class')->nullable()->after('school_college'); // Class 8, 9, 10...
            $table->string('photo_url')->nullable()->after('standard_class');
            $table->text('notes')->nullable()->after('photo_url');
            $table->string('status')->default('active')->after('notes'); // active/inactive
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'email','date_of_birth','gender','school_college',
                'standard_class','photo_url','notes','status',
            ]);
        });
    }
};
