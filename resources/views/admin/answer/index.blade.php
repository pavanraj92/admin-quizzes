@extends('admin::admin.layouts.master')

@section('title', 'Quiz Answers')

@section('page-title', 'Answers')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.quizzes.index') }}">Quiz Manager</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.quizzes.questions.index', $question->quiz_id) }}">Questions</a></li>
<li class="breadcrumb-item active" aria-current="page">Answers</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-body">
                <h4 class="card-title">Filter</h4>
                <form action="{{ route('admin.questions.answers.index', $question) }}" method="GET" id="filterForm">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="question">Keyword</label>
                                <input type="text" name="keyword" id="keyword" class="form-control"
                                    value="{{ app('request')->query('keyword') }}" placeholder="Enter question">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status">Is Correct</label>
                                <select name="status" id="status" class="form-control select2">
                                    <option value="">All</option>
                                    <option value="1" {{ app('request')->query('status') == '1' ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ app('request')->query('status') == '0' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-auto mt-1 text-right">
                            <div class="form-group">
                                <label for="created_at">&nbsp;</label>
                                <button type="submit" form="filterForm" class="btn btn-primary mt-4">Filter</button>
                                <a href="{{ route('admin.questions.answers.index', $question) }}" class="btn btn-secondary mt-4">Reset</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @admincan('quizzes_manager_edit')
                    <div class="text-right">
                        <a href="{{ route('admin.questions.answers.create', $question) }}" class="btn btn-primary mb-3">Add Answer</a>
                    </div>
                    @endadmincan

                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th>S. No.</th>
                                    <th>@sortablelink('answer_text', 'Answer', [], ['class' => 'text-dark'])</th>
                                    <th>@sortablelink('is_correct', 'Is Correct', [], ['class' => 'text-dark'])</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($answers as $i => $answer)
                                <tr>
                                    <td>{{ ($answers->currentPage() - 1) * $answers->perPage() + $i + 1 }}</td>
                                    <td>{{ Str::limit($answer->answer_text, 120) }}</td>
                                    <td>
                                        @if($answer->is_correct)
                                        <span class="badge badge-success">Yes</span>
                                        @else
                                        <span class="badge badge-secondary">No</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.answers.show', $answer) }}" class="btn btn-warning btn-sm" title="View"><i class="mdi mdi-eye"></i></a>
                                        <a href="{{ route('admin.answers.edit', $answer) }}" class="btn btn-success btn-sm" title="Edit"><i class="mdi mdi-pencil"></i></a>
                                        <a href="javascript:void(0)"
                                            data-url="{{ route('admin.answers.destroy', $answer) }}"
                                            data-text="Are you sure you want to delete this record?"
                                            data-method="DELETE"
                                            class="btn btn-danger btn-sm delete-record" title="Delete">
                                            <i class="mdi mdi-delete"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No records found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                        {{ $answers->links('admin::pagination.custom-admin-pagination') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection