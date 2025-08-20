<?php

namespace admin\quizzes\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use admin\quizzes\Models\QuizQuestion;
use admin\quizzes\Models\QuizAnswer;
use admin\quizzes\Requests\QuizAnswerRequest;
use admin\quizzes\Models\Quiz;

class QuizAnswerController extends Controller
{
    public function __construct()
    {
        $this->middleware('admincan_permission:quizzes_manager_view')->only(['index', 'show']);
        $this->middleware('admincan_permission:quizzes_manager_edit')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    public function index(Request $request, QuizQuestion $question)
    {
        try {
            $answers = $question->answers()
                ->filter($request->query('keyword'))
                ->filterByStatus($request->query('status'))
                ->sortable()
                ->latest()
                ->paginate(QuizAnswer::getPerPageLimit())
                ->withQueryString();

            return view('quiz::admin.answer.index', compact('question', 'answers'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load answers: ' . $e->getMessage());
        }
    }

    public function create(QuizQuestion $question)
    {
        return view('quiz::admin.answer.createOrEdit', compact('question'));
    }

    public function store(QuizAnswerRequest $request, QuizQuestion $question)
    {
        $question->answers()->create($request->validated());
        return redirect()->route('admin.questions.answers.index', $question)->with('success', 'Answer created successfully.');
    }

    public function show(QuizAnswer $answer)
    {
        return view('quiz::admin.answer.show', compact('answer'));
    }

    public function edit(QuizAnswer $answer)
    {
        return view('quiz::admin.answer.createOrEdit', compact('answer'));
    }

    public function update(QuizAnswerRequest $request, QuizAnswer $answer)
    {
        $answer->update($request->validated());
        return redirect()->route('admin.questions.answers.index', $answer->question_id)->with('success', 'Answer updated successfully.');
    }

    public function destroy(QuizAnswer $answer)
    {
        $answer->delete();
        return response()->json(['success' => true, 'message' => 'Record deleted successfully.']);
    }
}
