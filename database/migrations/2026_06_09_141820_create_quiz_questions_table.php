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
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('teacher_id')
                ->constrained('registration')
                ->onDelete('cascade');

            $table->string('title', 255);
            $table->enum('category', ['Mathematics', 'Science', 'History', 'General Knowledge', 'Programming']);
            $table->integer('total_questions')->default(0);

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->timestamps();

            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_questions');
    }
};
