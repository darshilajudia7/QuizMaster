<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class quiz_questions extends Model
{

    protected $table = 'quiz_questions';
    protected $fillable = [
        'teacher_id',
        'title',
        'desc',
        'category',
        'total_questions',
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'total_questions' => 'integer'
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(quiz_options::class, 'quiz_id', 'id');
    }

}
