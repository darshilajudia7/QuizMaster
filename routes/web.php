<?php

// index page
Route::view('/', 'index');



// Registration page 
use App\Http\Controllers\RegisterController;

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'view');
    Route::post('register', 'reg');
    Route::get('/otp-resend', 'resendOTP');
    Route::post('/otp-verify', 'VerifyOTP');
});

// OTP display page
Route::view('/otp', 'verify_otp');

// For Outh connections 
use App\Http\Controllers\AuthController;

Route::controller(AuthController::class)->group(function () {
    Route::get('/auth/google', 'redirectToGoogle')->name('auth.google');
    Route::get('/auth/google/callback', 'handleGoogleCallback');
});

// Login page
use App\Http\Controllers\LoginController;

Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'view');
    Route::post('login', 'login');
});


// Reset password page 
Route::view('/reset_password', 'reset_password');


// Forgot password page
use App\Http\Controllers\ForgotPasswordController;

Route::controller(ForgotPasswordController::class)->group(function () {
    Route::get('/forgot_password', 'view');
    Route::post('reset', 'sendlink');

});


// Reset Password Page
use App\Http\Controllers\ResetPasswordContoller;

Route::controller(ResetPasswordContoller::class)->group(function () {
    Route::get('reset_password', 'show');
    Route::post('reset_password', 'updatepassword');
});


// Logout 
use App\Http\Controllers\LogoutController;
Route::post('/logout', [LogoutController::class, 'logout']);



// Teacher Dashboard

use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\QuizController;
use App\Http\Controllers\Teacher\ResultController;

Route::middleware(['role:teacher'])->group(function () {

    // Dashboard
    Route::get('/teacher', [TeacherDashboardController::class, 'view'])->name('dashboard');

    // Quizzes
    Route::controller(QuizController::class)->group(function () {
        Route::get('/quizzes', 'view')->name('quizzes.view');
        Route::post('/quizzes', 'insert')->name('quizzes.insert');
        Route::get('/quizzes/{id}/edit', 'edit')->name('quizzes.edit');
        Route::put('/quizzes/{id}', 'update')->name('quizzes.update');
        Route::delete('/quizzes/{id}', 'destroy')->name('quizzes.destroy');
    });

    // Result
    Route::get('/result', [ResultController::class, 'view'])->name('resultpage');
    Route::get('/result/filter', [ResultController::class, 'ShowData'])->name('filter');
});




// Student Routes

use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\QuizAvailableController;
use App\Http\Controllers\Student\QuizAttempsController;
use App\Http\Controllers\Student\HistoryControlller;


Route::middleware(['role:student'])->group(function () {

    Route::middleware(['lock.quiz'])->group(function () {

        // Dashboard
        Route::get('student', [StudentDashboardController::class, 'view'])
            ->name('student.dashboard');

        // Quiz available
        Route::get('quiz', [QuizAvailableController::class, 'view'])
            ->name('student.quiz.available');

        // History
        Route::get('history', [HistoryControlller::class, 'index'])
            ->name('student.history');
    });


    // Quiz Attempts
    Route::get(
        '/student/attempt/{attemptId}/question/{questionNumber}',
        [QuizAttempsController::class, 'show']
    )->name('student.quiz.attempt');

    Route::post(
        '/student/quiz/{quizId}/start',
        [QuizAttempsController::class, 'start']
    )->name('student.quiz.start');

    Route::post('/student/attempt/{attemptId}/save', [QuizAttempsController::class, 'saveAnswer'])
        ->name('student.quiz.save');

    Route::get('/quiz/result/{attemptId}', [QuizAttempsController::class, 'showResult'])
        ->name('student.quiz.result');

    Route::post('/student/attempt/{attemptId}/submit', [QuizAttempsController::class, 'submit'])
        ->name('student.quiz.submit');
});
