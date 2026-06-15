<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\quiz_questions;

class DashboardController extends Controller
{
    public function view()
    {
        $teacherId = Session::get('user_id');

        $quizzes = quiz_questions::where('teacher_id', $teacherId)->get();

        $totalQuizzes = quiz_questions::where('teacher_id', $teacherId)->count();

        return view('teacher.dashboard', compact('quizzes', 'totalQuizzes'));
    }

}
