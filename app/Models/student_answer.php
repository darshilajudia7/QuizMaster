<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class student_answer extends Model
{
    protected $fillable = [
        'attempt_id',
        'question_id',
        'selected_option',
        'is_correct',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(quiz_attempt::class, 'attempt_id');
    }

    public function question(): BelongsTo
    {
        // Named 'QuizOption' based on your migra-2 constraint pointing to 'quiz_options'
        return $this->belongsTo(quiz_options::class, 'question_id');
    }
}
