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
        Schema::create('divisions', function (Blueprint $table) {
            $table->id(); // bigint pk
            $table->string('division_name', 255);
            $table->unsignedBigInteger('office_id'); // FK to offices
            $table->timestamps();

            // foreign key constraint
            $table->foreign('office_id')
                  ->references('id')
                  ->on('offices')
                  ->onDelete('cascade'); // if an office is deleted, its divisions are deleted
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('divisions');
    }
};
