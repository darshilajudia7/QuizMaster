<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\quiz_attempt;
use App\Models\quiz_questions;
use Illuminate\Support\Facades\Auth;

class ResultController extends Controller
{
    public function view(Request $request)
    {
        $teacherId = Auth::id();

        // Fetch filters data
        $categories = quiz_questions::where('teacher_id', $teacherId)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');

        $quizzes = quiz_questions::where('teacher_id', $teacherId)
            ->select('id', 'title', 'category')
            ->get();

        // Filter query
        $query = quiz_attempt::with(['student:id,name', 'quiz:id,title,category'])
            ->whereHas('quiz', function ($q) use ($teacherId) {
                $q->where('teacher_id', $teacherId);
            });

        if ($request->filled('category') && $request->category !== 'all') {
            $query->whereHas('quiz', function ($q) use ($request) {
                $q->where('category', $request->category);
            });
        }

        if ($request->filled('quiz_id') && $request->quiz_id !== 'all') {
            $query->where('quiz_id', $request->quiz_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        $allFilteredAttempts = $query->get();

        $totalStudents = $allFilteredAttempts->count();
        $maximum = 0;
        $minimum = $totalStudents > 0 ? 100 : 0;
        $runningSum = 0;

        foreach ($allFilteredAttempts as $attempt) {
            $pct = (float) $attempt->score_percentage;
            if ($pct > $maximum)
                $maximum = $pct;
            if ($pct < $minimum)
                $minimum = $pct;
            $runningSum += $pct;
        }

        $averageCalculated = $totalStudents > 0 ? round($runningSum / $totalStudents) : 0;

        $metrics = [
            'total_students' => $totalStudents,
            'highest_mark' => $maximum,
            'minimum_mark' => $minimum,
            'average_mark' => $averageCalculated,
        ];

        // Pagination
        $attempts = $query->paginate(5)->withQueryString();

        $attempts->getCollection()->transform(function ($attempt) {
            $pct = (float) $attempt->score_percentage;

            if ($pct >= 80) {
                $badgeClass = 'badge-success';
            } elseif ($pct >= 50) {
                $badgeClass = 'badge-warning';
            } else {
                $badgeClass = 'badge-danger';
            }

            $categoryName = $attempt->quiz->category ?? 'default';
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $categoryName)));

            return [
                'student_name' => $attempt->student->name ?? 'N/A',
                'quiz_title' => $attempt->quiz->title ?? 'Deleted Quiz',
                'category' => $attempt->quiz->category ?? 'General',
                'category_slug' => $slug,
                'obtained' => $attempt->correct_answers,
                'total' => $attempt->total_marks,
                'percentage' => $pct,
                'badge_class' => $badgeClass
            ];
        });

        return view('teacher.result', compact('categories', 'quizzes', 'attempts', 'metrics'));
    }
}