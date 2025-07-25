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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignid('tanent_id')->constrained('tanents')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('due_date')->nullable();

            $table->timestamp('completed_at')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed', 'on_hold', 'cancelled'])->default('pending');

            $table->enum('priority', ['low', 'medium', 'high'])->default('low');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
