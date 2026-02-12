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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('section_id')->nullable();
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('set null');
            $table->integer('queue_number')->nullable();
            $table->string('full_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->enum('client_type', ['priority', 'regular', 'deferred'])->nullable();
            $table->integer('step_id')->nullable();
            $table->integer('window_id')->nullable();
            $table->integer('recall_count')->nullable();
            $table->enum('ticket_status', ['issued', 'cancelled'])->nullable();
            $table->enum('queue_status', ['waiting', 'pending', 'deferred', 'serving', 'completed'])->default('waiting');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
