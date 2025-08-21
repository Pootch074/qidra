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
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('employee_id')->unique();

            $table->enum('user_type', ['admin', 'preasses', 'encoding', 'assessment', 'releasing']);

            // window_id (nullable)
            $table->unsignedBigInteger('window_id')->nullable();
            $table->foreign('window_id')->references('id')->on('windows')->onDelete('cascade');

            // assigned_category (nullable)
            $table->enum('assigned_category', ['priority', 'regular'])->nullable();

            $table->string('position');
            $table->string('password');
            $table->timestamps();
        });


        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('users');
    }
};
