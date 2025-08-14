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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignid('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('reciever_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['debit', 'credit', 'return']);
            $table->integer('amount')->default(0);
            $table->string('upload_invoice')->nullable();
            $table->text('note')->nullable();
            $table->enum('status', ['pending', 'received', 'returned'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
