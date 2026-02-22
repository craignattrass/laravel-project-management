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
        Schema::create('project_cli_commands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->nullable()->constrained('project_modules')->nullOnDelete();
            $table->string('signature'); // full artisan signature
            $table->string('name'); // command name
            $table->text('description')->nullable();
            $table->string('class_name')->nullable(); // full class path
            $table->json('arguments')->nullable();
            $table->json('options')->nullable();
            $table->text('example_usage')->nullable();
            $table->string('schedule')->nullable(); // if scheduled
            $table->boolean('is_documented')->default(false);
            $table->timestamp('last_scanned_at')->nullable();
            $table->timestamps();
            $table->unique('signature');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_cli_commands');
    }
};
