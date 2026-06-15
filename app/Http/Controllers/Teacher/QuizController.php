<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\quiz_questions;
use App\Models\quiz_options;

use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;

class QuizController extends Controller
{
    // View Function
    public function view(Request $request)
    {
        // Get logged-in teacher ID
        $teacherId = Session::get('user_id');
        $now = Carbon::now()->startOfDay();

        // Fetch all quizzes
        $allTeacherQuizzes = DB::table('quiz_questions')
            ->where('teacher_id', $teacherId)
            ->get();

        // Initialize status counters
        $totalCount = $allTeacherQuizzes->count();
        $activeCount = 0;
        $upcomingCount = 0;
        $closedCount = 0;

        // Calculate quiz status
        foreach ($allTeacherQuizzes as $q) {
            $start = $q->start_date ? Carbon::parse($q->start_date)->startOfDay() : null;
            $end = $q->end_date ? Carbon::parse($q->end_date)->endOfDay() : null;

            if ($end && $now->gt($end)) {
                $closedCount++;
            } elseif ($start && $now->lt($start)) {
                $upcomingCount++;
            } else {
                $activeCount++;
            }
        }

        // Filter
        $search = $request->input('search');
        $categoryFilter = $request->input('category', 'all');

        $query = quiz_questions::where('teacher_id', $teacherId);

        // Search Filter
        if (!empty($search)) {
            $query->where('title', 'LIKE', '%' . $search . '%');
        }

        // Category Filter
        if ($categoryFilter !== 'all' && !empty($categoryFilter)) {
            $query->where('category', $categoryFilter);
        }

        // paginated results
        $quizzes = $query->orderBy('created_at', 'desc')->paginate(2)->withQueryString();

        // Categories
        $categories = ['Mathematics', 'Science', 'History', 'General Knowledge', 'Programming'];

        return view('teacher.quiz', compact(
            'quizzes',
            'categories',
            'totalCount',
            'activeCount',
            'upcomingCount',
            'closedCount',
            'search',
            'categoryFilter'
        ));
    }

    // Insert Functions
    public function insert(Request $request)
    {
        // Session ID 
        $request->merge(['teacher_id' => Session::get('user_id')]);

        // Validation
        $validatedData = $request->validate([
            'teacher_id' => 'required|exists:registration,id',
            'title' => 'required|string|max:255',
            'desc' => 'string|max:50',
            'category' => 'required|string|in:Mathematics,Science,History,General Knowledge,Programming',
            'total_questions' => 'required|integer|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'questions' => 'required|array',
            'questions.*.question_text' => 'required|string',
            'questions.*.option_a' => 'required|string|max:255',
            'questions.*.option_b' => 'required|string|max:255',
            'questions.*.option_c' => 'required|string|max:255',
            'questions.*.option_d' => 'required|string|max:255',
            'questions.*.correct_option' => 'required|in:A,B,C,D',
        ]);

        DB::beginTransaction();
        try {

            // Insert Query In quiz_questions
            $quiz = quiz_questions::create([
                'teacher_id' => $validatedData['teacher_id'],
                'title' => $validatedData['title'],
                'desc' => $validatedData['desc'],
                'category' => $validatedData['category'],
                'total_questions' => $validatedData['total_questions'],
                'start_date' => $validatedData['start_date'],
                'end_date' => $validatedData['end_date'],
            ]);

            // Inser Query In Quiz_options
            foreach ($validatedData['questions'] as $qBlock) {
                quiz_options::create([
                    'quiz_id' => $quiz->id,
                    'question_text' => $qBlock['question_text'],
                    'option_a' => $qBlock['option_a'],
                    'option_b' => $qBlock['option_b'],
                    'option_c' => $qBlock['option_c'],
                    'option_d' => $qBlock['option_d'],
                    'correct_option' => $qBlock['correct_option'],
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Quiz saved successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Database operation aborted: ' . $e->getMessage());
        }
    }

    // Edit Function
    public function edit($id)
    {
        // Session with logged-in teacher
        $quiz = DB::table('quiz_questions')->where('id', $id)->where('teacher_id', Session::get('user_id'))->first();

        // Check Quiz Is Exists
        if (!$quiz) {
            return response()->json(['success' => false, 'message' => 'Quiz not found or unauthorized.'], 404);
        }

        // Fetch all questions for selected quiz
        $questions = DB::table('quiz_options')->where('quiz_id', $id)->get();

        return response()->json(['success' => true, 'quiz' => $quiz, 'questions' => $questions]);
    }

    // Update Function
    public function update(Request $request, $id)
    {
        // logged-in teacher ID wirh session
        $request->merge(['teacher_id' => Session::get('user_id')]);

        // Validation
        $request->validate([
            'teacher_id' => 'required|exists:registration,id',
            'title' => 'required|string|max:255',
            'desc' => 'string|max:50',
            'category' => 'required|in:Mathematics,Science,History,General Knowledge,Programming',
            'total_questions' => 'required|integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'questions' => 'nullable|array',
            'questions.*.question_text' => 'required|string',
            'questions.*.option_a' => 'required|string|max:255',
            'questions.*.option_b' => 'required|string|max:255',
            'questions.*.option_c' => 'required|string|max:255',
            'questions.*.option_d' => 'required|string|max:255',
            'questions.*.correct_option' => 'required|in:A,B,C,D',
        ]);

        DB::beginTransaction();
        try {

            // Update Query In quiz_questions
            DB::table('quiz_questions')->where('id', $id)->where('teacher_id', Session::get('user_id'))->update([
                'title' => $request->title,
                'desc' => $request->desc,
                'category' => $request->category,
                'total_questions' => $request->total_questions,
                'start_date' => $request->start_date ?: null,
                'end_date' => $request->end_date ?: null,
                'updated_at' => now(),
            ]);

            DB::table('quiz_options')->where('quiz_id', $id)->delete();

            // Update Query In quiz_options
            if (!empty($request->questions)) {
                foreach ($request->questions as $q) {
                    DB::table('quiz_options')->insert([
                        'quiz_id' => $id,
                        'question_text' => $q['question_text'],
                        'option_a' => $q['option_a'],
                        'option_b' => $q['option_b'],
                        'option_c' => $q['option_c'],
                        'option_d' => $q['option_d'],
                        'correct_option' => $q['correct_option'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Quiz updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Database update rollback triggered: ' . $e->getMessage());
        }
    }

    // Delete Function
    public function destroy($id)
    {
        try {
            // Session with logged-in teacher
            $deleted = DB::table('quiz_questions')->where('id', $id)->where('teacher_id', Session::get('user_id'))->delete();

            // Check Quiz Is Exists then drop
            if ($deleted) {
                return response()->json(['success' => true, 'message' => 'Quiz dropped successfully.']);
            }

            return response()->json(['success' => false, 'message' => 'Quiz target row not found or unauthorized.'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'System failure: ' . $e->getMessage()], 500);
        }
    }
}