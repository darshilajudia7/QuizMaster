<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class quiz_options extends Model
{
    protected $table = 'quiz_options';

    protected $fillable = [
        'quiz_id',
        'question_text',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_option'
    ];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(quiz_questions::class, 'quiz_id', 'id');
    }

}
