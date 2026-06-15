<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\quiz_attempt;

class LockActivePage
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */

    protected array $allowed = [
        'student.quiz.attempt',
        'student.quiz.save',
        'student.quiz.submit',
        'student.quiz.result',
        'logout',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $studentId = Auth::id() ?? session('user_id');

        if ($studentId) {
            $activeAttempt = quiz_attempt::where('student_id', $studentId)
                ->whereNull('submitted_at')
                ->first();

            if ($activeAttempt && !in_array($request->route()?->getName(), $this->allowed)) {
                return redirect()
                    ->route('student.quiz.attempt', [
                        'attemptId' => $activeAttempt->id,
                        'questionNumber' => 1,
                    ])
                    ->with('error', 'Please finish or submit your current quiz before continuing.');
            }
        }
        
        return $next($request);
    }
}
