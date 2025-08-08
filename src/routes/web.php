<?php

use Illuminate\Support\Facades\Route;
use admin\quizzes\Controllers\QuizManagerController;
use admin\quizzes\Controllers\QuizQuestionController;
use admin\quizzes\Controllers\QuizAnswerController;

Route::name('admin.')->middleware(['web','admin.auth'])->group(function () {
    Route::resource('quizzes', QuizManagerController::class);
    Route::post('quizzes/updateStatus', [QuizManagerController::class, 'updateStatus'])->name('quizzes.updateStatus');

    // Nested resources for quiz questions and answers
    Route::resource('quizzes.questions', QuizQuestionController::class);
    Route::resource('questions.answers', QuizAnswerController::class)->shallow();
});

