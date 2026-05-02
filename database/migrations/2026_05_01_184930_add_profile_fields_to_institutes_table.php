<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('institutes', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('slug');
            $table->string('contact_email')->nullable()->after('phone');
            $table->string('website')->nullable()->after('contact_email');
            $table->text('description')->nullable()->after('website');
            $table->text('address')->nullable()->after('description');
            $table->string('city')->nullable()->after('address');
            $table->string('state')->nullable()->after('city');
            $table->string('pincode', 10)->nullable()->after('state');
            $table->string('logo_url')->nullable()->after('pincode');
            $table->string('brand_color', 7)->nullable()->default('#4f46e5')->after('logo_url');
            $table->year('established_year')->nullable()->after('brand_color');
        });
    }

    public function down(): void
    {
        Schema::table('institutes', function (Blueprint $table) {
            $table->dropColumn([
                'phone','contact_email','website','description','address',
                'city','state','pincode','logo_url','brand_color','established_year',
            ]);
        });
    }
};
