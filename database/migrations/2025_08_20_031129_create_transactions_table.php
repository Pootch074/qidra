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
            $table->integer('queue_number');
            $table->enum('client_type', ['priority', 'regular']);

            //section_id foreign key
            $table->unsignedBigInteger('section_id'); 
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');

            $table->integer('step_id');
            $table->integer('window_id');
            $table->enum('queue_status', ['waiting', 'pending', 'serving']);

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
