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
        Schema::create('quiz_options', function (Blueprint $table) {
            $table->id();


            $table->foreignId('quiz_id')
                ->constrained('quiz_questions')
                ->onDelete('cascade');

            $table->text('question_text');
            $table->string('option_a', 255);
            $table->string('option_b', 255);
            $table->string('option_c', 255);
            $table->string('option_d', 255);

            $table->enum('correct_option', ['A', 'B', 'C', 'D']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_options');
    }
};
