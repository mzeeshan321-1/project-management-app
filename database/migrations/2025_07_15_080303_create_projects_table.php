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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('tanent_id')->constrained('tanents')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('deadline')->nullable();
            $table->integer('client_budget')->nullable();
            $table->integer('expert_budget')->nullable();
            $table->enum('type', ['internal', 'external'])->default('internal');
            $table->enum('status', ['planned', 'in_progress', 'completed', 'on_hold', 'cancelled'])->default('planned');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
