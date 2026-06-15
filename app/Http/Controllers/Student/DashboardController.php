<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\quiz_attempt;
use App\Models\quiz_questions;

class DashboardController extends Controller
{
    public function view()
    {
        $studentId = auth()->id();

        // Count available quizzes
        $totalquizzes = quiz_questions::count();

        // Count quizzes this student has actually attempted
        $attemptedCount = quiz_attempt::where('student_id', $studentId)
            ->distinct('quiz_id')
            ->count();

        // Fetch the 5 recent quiz
        $recentAttempts = quiz_attempt::with(['quiz'])
            ->where('student_id', $studentId)
            ->latest('submitted_at')
            ->latest('created_at')
            ->take(5)
            ->get();

        return view('student.dashboard', compact('totalquizzes', 'attemptedCount', 'recentAttempts'));
    }
}
