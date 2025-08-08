<?php

namespace admin\quizzes\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use admin\quizzes\Models\Quiz;
use admin\quizzes\Models\QuizQuestion;
use admin\quizzes\Requests\QuizQuestionRequest;

class QuizQuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('admincan_permission:quizzes_manager_view')->only(['index', 'show']);
        $this->middleware('admincan_permission:quizzes_manager_edit')->only(['create','store','edit','update','destroy']);
    }

    public function index(Quiz $quiz)
    {
        $questions = $quiz->questions()->latest()->paginate(20)->withQueryString();
        return view('quiz::admin.question.index', compact('quiz', 'questions'));
    }

    public function create(Quiz $quiz)
    {
        return view('quiz::admin.question.createOrEdit', compact('quiz'));
    }

    public function store(QuizQuestionRequest $request, Quiz $quiz)
    {
        $quiz->questions()->create($request->validated());
        return redirect()->route('admin.quizzes.questions.index', $quiz)->with('success', 'Question created successfully.');
    }

    public function show(Quiz $quiz, QuizQuestion $question)
    {
        return view('quiz::admin.question.show', compact('quiz', 'question'));
    }

    public function edit(Quiz $quiz, QuizQuestion $question)
    {
        return view('quiz::admin.question.createOrEdit', compact('quiz', 'question'));
    }

    public function update(QuizQuestionRequest $request, Quiz $quiz, QuizQuestion $question)
    {
        $question->update($request->validated());
        return redirect()->route('admin.quizzes.questions.index', $quiz)->with('success', 'Question updated successfully.');
    }

    public function destroy(Quiz $quiz, QuizQuestion $question)
    {
        $question->delete();
        return response()->json(['success' => true, 'message' => 'Record deleted successfully.']);
    }
}

