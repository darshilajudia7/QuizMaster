<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\quiz_questions;
use App\Models\quiz_options;
use App\Models\quiz_attempt;
use App\Models\student_answer;

class QuizAttempsController extends Controller
{
    public function start(Request $request, $quizId)
    {
        $quiz = quiz_questions::findOrFail($quizId);
        $studentId = Auth::id() ?? session('user_id');

        if (!$studentId) {
            return redirect('/login')->with('error', 'Please log in to start the quiz.');
        }

        $existing = quiz_attempt::where('student_id', $studentId)
            ->where('quiz_id', $quizId)
            ->whereNull('submitted_at')
            ->first();

        $attempt = $existing ?? quiz_attempt::create([
            'student_id' => $studentId,
            'quiz_id' => $quizId,
            'correct_answers' => 0,
            'total_marks' => 0,
            'score_percentage' => 0.00,
            'time_taken_seconds' => 0,
        ]);

        return redirect()->route('student.quiz.attempt', [
            'attemptId' => $attempt->id,
            'questionNumber' => 1,
        ]);
    }

    public function show(Request $request, $attemptId, $questionNumber)
    {
        $attempt = quiz_attempt::findOrFail($attemptId);

        if ($attempt->submitted_at) {
            return redirect()->route('student.quiz.result', ['attemptId' => $attempt->id]);
        }

        $quiz = quiz_questions::findOrFail($attempt->quiz_id);
        $questionNumber = (int) $questionNumber;
        $allQuestions = quiz_options::where('quiz_id', $quiz->id)->orderBy('id')->get();

        if ($allQuestions->isEmpty() || $questionNumber < 1 || $questionNumber > $allQuestions->count()) {
            return redirect()->route('student.quiz.available');
        }

        $allocatedSeconds = $quiz->total_questions * 30;
        $elapsed = Carbon::parse($attempt->created_at)->diffInSeconds(Carbon::now());
        $timeLeft = max(0, $allocatedSeconds - $elapsed);

        if ($timeLeft <= 0) {
            return $this->submit(new Request(['force_submit' => 1]), $attempt->id);
        }

        $question = $allQuestions->get($questionNumber - 1);
        $savedAnswer = student_answer::where('attempt_id', $attempt->id)
            ->where('question_id', $question->id)
            ->first();

        $answeredQuestionIds = student_answer::where('attempt_id', $attempt->id)
            ->whereNotNull('selected_option')
            ->pluck('question_id')
            ->toArray();

        return response()
            ->view('student.quiz_attempt', compact(
                'attempt',
                'quiz',
                'question',
                'questionNumber',
                'allQuestions',
                'savedAnswer',
                'answeredQuestionIds',
                'timeLeft'
            ))
            ->withHeaders([
                'Cache-Control' => 'no-store, no-cache, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]);
    }

    public function saveAnswer(Request $request, $attemptId)
    {
        $attempt = quiz_attempt::findOrFail($attemptId);
        $question = quiz_options::findOrFail($request->question_id);
        $action = $request->action;

        if ($request->has('selected_option') && $action !== 'clear') {
            student_answer::updateOrCreate(
                ['attempt_id' => $attempt->id, 'question_id' => $question->id],
                [
                    'selected_option' => $request->selected_option,
                    'is_correct' => $request->selected_option === $question->correct_option,
                ]
            );
        }

        if ($action === 'save') {
            return response()->json(['success' => true]);
        }

        $currentNum = (int) $request->current_number;
        $nextNumber = match ($action) {
            'next' => $currentNum + 1,
            'previous' => max(1, $currentNum - 1),
            'jump' => (int) $request->target_question,
            default => $currentNum,
        };

        $redirectUrl = route('student.quiz.attempt', [
            'attemptId' => $attempt->id,
            'questionNumber' => $nextNumber,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'redirect_url' => $redirectUrl]);
        }

        return redirect($redirectUrl);
    }

    public function submit(Request $request, $attemptId)
    {
        $attempt = quiz_attempt::findOrFail($attemptId);
        $questionIds = quiz_options::where('quiz_id', $attempt->quiz_id)->pluck('id')->toArray();
        $totalQuestions = count($questionIds);

        $answers = student_answer::where('attempt_id', $attempt->id)
            ->whereIn('question_id', $questionIds)
            ->whereNotNull('selected_option')
            ->get();

        $isForced = $request->boolean('force_submit');

        if (!$isForced && $answers->count() < 1) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please answer at least one question before submitting.',
                ], 422);
            }
            return redirect()->back()->with('error', 'Please answer at least one question before submitting.');
        }

        $correctCount = $answers->where('is_correct', true)->count();
        $allocatedSeconds = $totalQuestions * 30;
        $elapsed = min(
            Carbon::parse($attempt->created_at)->diffInSeconds(Carbon::now()),
            $allocatedSeconds
        );

        $attempt->update([
            'correct_answers' => $correctCount,
            'total_marks' => $correctCount,
            'score_percentage' => $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100, 2) : 0,
            'time_taken_seconds' => $elapsed,
            'submitted_at' => Carbon::now(),
        ]);

        $resultUrl = route('student.quiz.result', ['attemptId' => $attempt->id]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'redirect_url' => $resultUrl,
                'message' => 'Quiz submitted successfully.',
            ]);
        }

        return redirect($resultUrl)->with('status', 'Quiz submitted successfully!');
    }

    public function showResult($attemptId)
    {
        $attempt = quiz_attempt::findOrFail($attemptId);
        $quiz = quiz_questions::findOrFail($attempt->quiz_id);

        $totalQuestions = $quiz->total_questions;
        $correctAnswers = $attempt->correct_answers;
        $incorrectAnswers = max(0, $totalQuestions - $correctAnswers);
        $percentage = $attempt->score_percentage;

        $seconds = $attempt->time_taken_seconds;
        $timeTakenFormatted = floor($seconds / 60) . 'm ' . ($seconds % 60) . 's';

        $performance = match (true) {
            $percentage >= 90 => [
                'heading' => 'Outstanding Performance!',
                'message' => "Amazing job! You've demonstrated a deep understanding of the concepts covered in this assessment. Keep up the brilliant work!",
                'icon_color' => 'var(--amber)',
            ],
            $percentage >= 75 => [
                'heading' => 'Excellent Work!',
                'message' => 'Great tracking! You have scored remarkably well and mastered a major portion of the quiz constraints.',
                'icon_color' => 'var(--indigo)',
            ],
            $percentage >= 50 => [
                'heading' => 'Good Attempt!',
                'message' => 'You passed! Review the areas where you missed a choice to tighten up your performance metrics next time around.',
                'icon_color' => 'var(--mint, #10b981)',
            ],
            default => [
                'heading' => 'Keep Improving!',
                'message' => 'Do not worry! Continuous structural iteration builds perfection. Review the study resources and try again.',
                'icon_color' => 'var(--coral)',
            ],
        };

        // Circumference = 2 * π * 80 ≈ 502.65
        $strokeDashoffset = 502.65 - ($percentage / 100) * 502.65;

        return view('student.quiz_result', compact(
            'attempt',
            'quiz',
            'totalQuestions',
            'correctAnswers',
            'incorrectAnswers',
            'percentage',
            'timeTakenFormatted',
            'performance',
            'strokeDashoffset'
        ));
    }
}