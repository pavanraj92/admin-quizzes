@extends('admin::admin.layouts.master')

@section('title', 'Quiz Questions')

@section('page-title', 'Quiz Questions')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.quizzes.index') }}">Quiz Manager</a></li>
<li class="breadcrumb-item active" aria-current="page">Questions</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @admincan('quizzes_manager_edit')
                    <div class="text-right">
                        <a href="{{ route('admin.quizzes.questions.create', $quiz) }}" class="btn btn-primary mb-3">Add Question</a>
                    </div>
                    @endadmincan

                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Question</th>
                                    <th>Type</th>
                                    <th>Points</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($questions as $i => $question)
                                    <tr>
                                        <td>{{ ($questions->currentPage() - 1) * $questions->perPage() + $i + 1 }}</td>
                                        <td>{{ Str::limit($question->question_text, 80) }}</td>
                                        <td>{{ str_replace('_',' ', ucfirst($question->question_type)) }}</td>
                                        <td>{{ $question->points }}</td>
                                        <td>
                                            <a href="{{ route('admin.questions.answers.index', $question) }}" class="btn btn-dark btn-sm" title="List Answers"><i class="mdi mdi-format-list-bulleted"></i></a>
                                            <a href="{{ route('admin.quizzes.questions.show', [$quiz, $question]) }}" class="btn btn-warning btn-sm" title="View"><i class="mdi mdi-eye"></i></a>
                                            @admincan('quizzes_manager_edit')
                                            <a href="{{ route('admin.quizzes.questions.edit', [$quiz, $question]) }}" class="btn btn-success btn-sm" title="Edit"><i class="mdi mdi-pencil"></i></a>                                            
                                            <a href="javascript:void(0)"
                                               data-url="{{ route('admin.quizzes.questions.destroy', [$quiz, $question]) }}"
                                               data-text="Are you sure you want to delete this record?"
                                               data-method="DELETE"
                                               class="btn btn-danger btn-sm delete-record" title="Delete">
                                                <i class="mdi mdi-delete"></i>
                                            </a>
                                            @endadmincan
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center">No records found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>

                        {{ $questions->links('admin::pagination.custom-admin-pagination') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

