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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('tanent_id')->constrained('tanents')->onDelete('cascade');
            $table->timestamp('last_login')->nullable();
            $table->string('contact info')->nullable();
            $table->string('address')->nullable();
            $table->string('company_name')->nullable();
            $table->string('billing_address')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended', 'onboard'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
