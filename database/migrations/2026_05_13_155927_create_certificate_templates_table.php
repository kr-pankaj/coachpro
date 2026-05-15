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
        Schema::create('certificate_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institute_id')->constrained()->onDelete('cascade');
            $table->string('background_image')->nullable();
            $table->string('title')->default('Certificate of Excellence');
            $table->text('body_text')->nullable(); // Default: "This is to certify that {student_name} has successfully completed the {course_name} course at {institute_name} on {date}."
            $table->string('authorized_signatory_name')->nullable();
            $table->string('authorized_signatory_designation')->nullable();
            $table->string('signature_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificate_templates');
    }
};
