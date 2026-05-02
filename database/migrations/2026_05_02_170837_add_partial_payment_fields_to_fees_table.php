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
        Schema::table('fees', function (Blueprint $table) {
            $table->renameColumn('amount', 'total_amount');
        });

        Schema::table('fees', function (Blueprint $table) {
            $table->decimal('paid_amount', 10, 2)->after('total_amount')->default(0);
            $table->decimal('due_amount', 10, 2)->after('paid_amount')->default(0);
            $table->decimal('discount_amount', 10, 2)->after('due_amount')->default(0);
            $table->text('remarks')->nullable()->after('status');
        });

        // Migrate existing data
        \DB::table('fees')->where('status', 'paid')->update([
            'paid_amount' => \DB::raw('total_amount'),
            'due_amount' => 0
        ]);

        \DB::table('fees')->where('status', 'pending')->update([
            'paid_amount' => 0,
            'due_amount' => \DB::raw('total_amount')
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fees', function (Blueprint $table) {
            $table->dropColumn(['total_amount', 'paid_amount', 'due_amount', 'discount_amount', 'remarks']);
        });
    }
};
