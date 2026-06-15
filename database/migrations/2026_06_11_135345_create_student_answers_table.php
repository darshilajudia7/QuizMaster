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
        Schema::create('student_answers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('attempt_id')
                ->constrained('quiz_attempts')
                ->onDelete('cascade');

            $table->foreignId('question_id')
                ->constrained('quiz_options')
                ->onDelete('cascade');

            $table->enum('selected_option', ['A', 'B', 'C', 'D'])->nullable();

            $table->boolean('is_correct')->default(false);

            $table->timestamps();

            $table->unique(['attempt_id', 'question_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_answers');
    }
};
