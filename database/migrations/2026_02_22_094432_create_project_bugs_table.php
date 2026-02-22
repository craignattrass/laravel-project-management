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
        Schema::create('project_bugs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->nullable()->constrained('project_modules')->nullOnDelete();
            $table->string('title');
            $table->text('description');
            $table->string('severity')->default('medium'); // low, medium, high, critical
            $table->string('status')->default('open'); // open, investigating, in_progress, resolved, wont_fix
            $table->string('type')->default('bug'); // bug, issue, error
            $table->text('steps_to_reproduce')->nullable();
            $table->text('expected_behavior')->nullable();
            $table->text('actual_behavior')->nullable();
            $table->string('file_path')->nullable();
            $table->integer('line_number')->nullable();
            $table->text('stack_trace')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
            $table->index(['status', 'severity']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_bugs');
    }
};
