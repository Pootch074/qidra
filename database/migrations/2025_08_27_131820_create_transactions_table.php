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
            $table->id(); // bigint pk
            $table->integer('queue_number');
            $table->enum('client_type', ['priority', 'regular']);
            $table->unsignedBigInteger('window_id'); // FK to windows
            $table->enum('queue_status', ['waiting', 'pending', 'serving', 'cancelled']);
            $table->timestamps();
            $table->timestamp('served_at')->nullable();

            // Foreign key constraint
            $table->foreign('window_id')
                  ->references('id')
                  ->on('windows')
                  ->onDelete('cascade'); // delete transactions if window is deleted
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
