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
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('profile_picture')->nullable();
            $table->string('phone')->nullable();
            $table->string('nationality')->nullable();
            $table->string('address')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('age')->nullable();
            $table->boolean('is_admin')->default(false); 
            $table->decimal('outstanding_fees')->default(0.00); 
            $table->string('department')->nullable();
            $table->string('qualification')->nullable();
            $table->string('student_id')->unique()->nullable();
            $table->string('lecturer_id')->unique()->nullable();
            $table->integer('year_of_study')->nullable();
            $table->string('guardian_contact')->nullable();
            $table->date('date_of_admission')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });


        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
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
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
