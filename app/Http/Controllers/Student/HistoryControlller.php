<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\quiz_attempt;

class HistoryControlller extends Controller
{
    public function index()
    {
        $studentId = auth()->id();

        // Fetch tudent attempts 
        $attempts = quiz_attempt::with(['quiz'])
            ->where('student_id', $studentId)
            ->latest()
            ->paginate(6);

        // ggregate summary 
        $metrics = DB::table('quiz_attempts')
            ->where('student_id', $studentId)
            ->selectRaw('
                COUNT(*) as total_attempts,
                AVG(score_percentage) as avg_score,
                MAX(score_percentage) as max_score,
                MIN(score_percentage) as min_score
            ')
            ->first();

        return view('student.history', [
            'attempts' => $attempts,
            'totalAttempts' => $metrics->total_attempts ?? 0,
            'avgScore' => round($metrics->avg_score ?? 0, 1),
            'highestScore' => round($metrics->max_score ?? 0, 1),
            'lowestScore' => round($metrics->min_score ?? 0, 1)
        ]);
    }
}
