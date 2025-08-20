<?php

namespace admin\quizzes\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use admin\quizzes\Requests\QuizCreateRequest;
use admin\quizzes\Requests\QuizUpdateRequest;
use admin\quizzes\Models\Quiz;
use admin\courses\Models\Course;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class QuizManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware('admincan_permission:quizzes_manager_list')->only(['index']);
        $this->middleware('admincan_permission:quizzes_manager_create')->only(['create', 'store']);
        $this->middleware('admincan_permission:quizzes_manager_edit')->only(['edit', 'update']);
        $this->middleware('admincan_permission:quizzes_manager_view')->only(['show']);
        $this->middleware('admincan_permission:quizzes_manager_delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        try {
            $quizzes = Quiz::filter($request->query('keyword'))
                ->filterByStatus($request->query('status'))
                ->sortable()
                ->latest()
                ->paginate(Quiz::getPerPageLimit())
                ->withQueryString();

            return view('quiz::admin.quiz.index', compact('quizzes'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load quizzes: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $courses = Course::where(['status' => 'approved'])->pluck('title', 'id')->toArray();
            return view('quiz::admin.quiz.createOrEdit', compact('courses'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load quiz create form: ' . $e->getMessage());
        }
    }

    public function store(QuizCreateRequest $request)
    {
        try {
            $requestData = $request->validated();

            Quiz::create($requestData);
            return redirect()->route('admin.quizzes.index')->with('success', 'Quiz created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create quiz: ' . $e->getMessage());
        }
    }

    public function show(Quiz $quiz)
    {
        try {
            return view('quiz::admin.quiz.show', compact('quiz'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load quiz details: ' . $e->getMessage());
        }
    }

    public function edit(Quiz $quiz)
    {
        try {
            $courses = Course::where(['status' => 'approved'])->pluck('title', 'id')->toArray();
            return view('quiz::admin.quiz.createOrEdit', compact('quiz', 'courses'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load quiz for editing: ' . $e->getMessage());
        }
    }

    public function update(QuizUpdateRequest $request, Quiz $quiz)
    {
        try {
            $requestData = $request->validated();

            $quiz->update($requestData);
            return redirect()->route('admin.quizzes.index')->with('success', 'Quiz updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update quiz: ' . $e->getMessage());
        }
    }

    public function destroy(Quiz $quiz)
    {
        try {
            if (Schema::hasTable('quiz_questions')) {
                $isAssigned =  DB::table('quiz_questions')
                    ->where('quiz_id', $quiz->id)
                    ->count();

                if ($isAssigned > 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Sorry, you cannot delete because this quiz is associated with one or more questions.'
                    ], 400);
                }
            }
            $quiz->delete();
            return response()->json(['success' => true, 'message' => 'Record deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete record.', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $quiz = Quiz::findOrFail($request->id);
            $quiz->status = $request->status; // expects 'active' | 'inactive' | 'draft'
            $quiz->save();

            $isActive = $quiz->status === 'active';
            $dataStatus = $isActive ? 'inactive' : 'active';
            $label = ucfirst($quiz->status);
            $btnClass = $isActive ? 'btn-success' : 'btn-warning';
            $tooltip = $isActive ? 'Click to change status to inactive' : 'Click to change status to active';

            $strHtml = '<a href="javascript:void(0)"'
                . ' data-toggle="tooltip"'
                . ' data-placement="top"'
                . ' title="' . $tooltip . '"'
                . ' data-url="' . route('admin.quizzes.updateStatus') . '"'
                . ' data-method="POST"'
                . ' data-status="' . $dataStatus . '"'
                . ' data-id="' . $quiz->id . '"'
                . ' class="btn ' . $btnClass . ' btn-sm update-status">' . $label . '</a>';

            return response()->json(['success' => true, 'message' => 'Status updated to ' . $label, 'strHtml' => $strHtml]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update status.', 'error' => $e->getMessage()], 500);
        }
    }
}
