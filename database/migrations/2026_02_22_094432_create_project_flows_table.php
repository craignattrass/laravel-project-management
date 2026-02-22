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
        Schema::create('project_flows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->nullable()->constrained('project_modules')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('mermaid_diagram'); // Mermaid.js syntax
            $table->string('type')->default('flowchart'); // flowchart, sequence, class, state, etc
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_flows');
    }
};
