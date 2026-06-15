<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('registration', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password')->nullable();

            $table->string('google_id')->nullable()->unique();
            $table->text('google_token')->nullable();

            $table->enum('role', ['teacher', 'student'])->default('student');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registration', function (Blueprint $table) {
        });
    }
};
