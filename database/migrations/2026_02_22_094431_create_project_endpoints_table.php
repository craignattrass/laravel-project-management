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
        Schema::create('project_endpoints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->nullable()->constrained('project_modules')->nullOnDelete();
            $table->string('method'); // GET, POST, PUT, DELETE
            $table->string('uri');
            $table->string('name')->nullable(); // route name
            $table->string('controller')->nullable();
            $table->string('action')->nullable();
            $table->text('description')->nullable();
            $table->json('parameters')->nullable(); // request parameters
            $table->json('response_example')->nullable();
            $table->string('middleware')->nullable();
            $table->boolean('requires_auth')->default(false);
            $table->boolean('is_documented')->default(false);
            $table->timestamp('last_scanned_at')->nullable();
            $table->timestamps();
            $table->index(['method', 'uri']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_endpoints');
    }
};
