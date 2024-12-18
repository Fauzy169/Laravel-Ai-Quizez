<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\QuizController;

Route::get('/quiz-form', function() {
    return view('quiz_form');
});

Route::post('/generate-quiz', [QuizController::class, 'generateQuiz']);