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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // bigint pk
            $table->string('first_name', 255);
            $table->string('last_name', 255);
            $table->bigInteger('employee_id')->unique(); // employee ID (unique)
            $table->string('user_type', 255);
            $table->unsignedBigInteger('window_id')->nullable(); // FK to windows.id
            $table->string('position', 255)->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            // Foreign key to windows table
            $table->foreign('window_id')->references('id')->on('windows')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
