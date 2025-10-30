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
        Schema::create('project_relations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by_id')->constrained('users')->nullOnDelete()->nullable();
            $table->morphs('source');
            $table->morphs('target');
            $table->string('relation_type');
            $table->timestamps();

            $table->unique(['project_id', 'source_type', 'source_id', 'target_type', 'target_id', 'relation_type'], 'unique_project_relation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_relations');
    }
};
