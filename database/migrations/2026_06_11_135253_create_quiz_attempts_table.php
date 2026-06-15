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
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                ->constrained('registration')
                ->onDelete('cascade');

            $table->foreignId('quiz_id')
                ->constrained('quiz_questions')
                ->onDelete('cascade');

            $table->integer('correct_answers')->default(0);
            $table->integer('total_marks')->default(0); 
            $table->decimal('score_percentage', 5, 2)->default(0.00); 

            $table->integer('time_taken_seconds')->nullable();

            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
    }
};
