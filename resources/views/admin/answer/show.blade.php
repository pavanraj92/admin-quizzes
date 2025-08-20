@extends('admin::admin.layouts.master')

@section('title', 'Answer Details - ' . ($answer?->id ?? 'N/A'))
@section('page-title', 'Answer Details')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.quizzes.index') }}">Quiz Manager</a></li>
    <li class="breadcrumb-item active" aria-current="page">Answer Details</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <!-- Header -->
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h4 class="card-title mb-0">
                            Answer #{{ $answer?->id ?? 'N/A' }}
                            <span class="badge {{ $answer?->is_correct ? 'badge-success' : 'badge-danger' }}">
                                {{ $answer?->is_correct ? 'Correct' : 'Incorrect' }}
                            </span>
                        </h4>
                        <div>
                            <a href="{{ route('admin.questions.answers.index', $answer->question_id) }}" 
                               class="btn btn-secondary ml-2">Back</a>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-8">
                            <div class="card mb-3">
                                <div class="card-header bg-primary">
                                    <h5 class="mb-0 text-white font-bold">Answer Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Answer Text:</label>
                                                <p>{{ $answer?->answer_text ?? 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Correct:</label>
                                                <p>
                                                    {!! $answer?->is_correct 
                                                        ? '<span class="badge bg-success">Yes</span>' 
                                                        : '<span class="badge bg-danger">No</span>' !!}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Related Question:</label>
                                                <p>
                                                    <a href="{{ route('admin.quizzes.questions.show', [$answer->question->quiz_id, $answer->question_id]) }}" 
                                                       class="text-primary">
                                                        Question #{{ $answer?->question_id }}
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header bg-primary">
                                    <h5 class="mb-0 text-white font-bold">Quick Actions</h5>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <a href="{{ route('admin.answers.edit', $answer) }}" 
                                       class="btn btn-warning mb-2">
                                        <i class="mdi mdi-pencil"></i> Edit Answer
                                    </a>

                                    <button type="button" class="btn btn-danger delete-btn delete-record"
                                        title="Delete this answer"
                                        data-url="{{ route('admin.answers.destroy', $answer) }}"
                                        data-redirect="{{ route('admin.questions.answers.index', $answer->question_id) }}"
                                        data-text="Are you sure you want to delete this answer?"
                                        data-method="DELETE">
                                        <i class="mdi mdi-delete"></i> Delete Answer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div><!-- card-body -->
            </div><!-- card -->
        </div><!-- col -->
    </div><!-- row -->
</div>
@endsection
