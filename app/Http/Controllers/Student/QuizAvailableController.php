<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\quiz_questions;

class QuizAvailableController extends Controller
{
    public function view()
    {
        try {
            // Date
            $today = Carbon::today()->toDateString();

            // Filter
            $quizzes = quiz_questions::where('start_date', '<=', $today)
                ->where('end_date', '>=', $today)
                ->orderBy('start_date', 'desc')
                ->paginate(6);

            // calculate time
            $quizzes->getCollection()->transform(function ($quiz) {
                $totalSeconds = $quiz->total_questions * 30;

                $quiz->total_seconds = $totalSeconds;

                $quiz->total_minutes = $totalSeconds / 60;

                return $quiz;
            });

            return view('student.quiz_available', compact('quizzes'));

        } catch (\Exception $e) {
            Log::error('Quiz Matrix Retrieval Failure: ' . $e->getMessage());

            return view('student.quiz_available', [
                'quizzes' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 6)
            ])->with('error', 'Unable to retrieve quiz records at this moment.');
        }
    }
}
