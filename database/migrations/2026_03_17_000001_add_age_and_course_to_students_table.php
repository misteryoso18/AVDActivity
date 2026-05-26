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
            if (!Schema::hasColumn('students', 'age')) {
                $table->integer('age')->nullable()->after('contact_no');
            }
            if (!Schema::hasColumn('students', 'course')) {
                $table->string('course')->nullable()->after('age');
            }
            if (!Schema::hasColumn('students', 'degree_id')) {
                $table->unsignedBigInteger('degree_id')->nullable()->after('course');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'degree_id')) {
                $table->dropColumn('degree_id');
            }
            if (Schema::hasColumn('students', 'course')) {
                $table->dropColumn('course');
            }
            if (Schema::hasColumn('students', 'age')) {
                $table->dropColumn('age');
            }
        });
    }
};
