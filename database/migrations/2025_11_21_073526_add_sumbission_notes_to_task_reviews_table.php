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
        Schema::table('task_reviews', function (Blueprint $table) {
            $table->foreignId('submitted_by_id')->after('task_id')->constrained('users')->nullOnDelete();
            $table->text('submission_notes')->nullable()->after('submitted_by_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_reviews', function (Blueprint $table) {
            $table->dropForeign('submitted_by_id');
            $table->dropColumn('submitted_by_id');
            $table->dropColumn('submission_notes');
        });
    }
};
