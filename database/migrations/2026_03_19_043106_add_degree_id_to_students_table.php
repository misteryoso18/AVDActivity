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
            // Only add the column if it doesn't exist (in case it was added in previous migration)
            if (!Schema::hasColumn('students', 'degree_id')) {
                $table->unsignedBigInteger('degree_id')->nullable()->after('contact_no');
            }
            // Add foreign key constraint
            try {
                $table->foreign('degree_id')->references('id')->on('degrees')->onDelete('set null');
            } catch (\Exception $e) {
                // Foreign key might already exist
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['degree_id']);
            $table->dropColumn('degree_id');
        });
    }
};
