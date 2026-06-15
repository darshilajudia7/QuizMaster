<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class quiz_attempt extends Model
{
    protected $table = "quiz_attempts";

    protected $fillable = [
        'student_id',
        'quiz_id',
        'correct_answers',
        'total_marks',
        'score_percentage',
        'time_taken_seconds',
        'submitted_at',
    ];

    protected $casts = [
        'score_percentage' => 'decimal:2',
        'time_taken_seconds' => 'integer',
        'correct_answers' => 'integer',
        'total_marks' => 'integer',
        'submitted_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(registration::class, 'student_id');
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(quiz_questions::class, 'quiz_id');
    }

    public function studentAnswers(): HasMany
    {
        return $this->hasMany(student_answer::class, 'attempt_id');
    }
}
