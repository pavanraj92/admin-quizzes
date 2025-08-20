@extends('admin::admin.layouts.master')

@section('title', 'Question Details - ' . ($question?->question_text ?? 'N/A'))
@section('page-title', 'Question Details')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.quizzes.index') }}">Quiz Manager</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.quizzes.questions.index', $quiz) }}">Questions</a></li>
<li class="breadcrumb-item active" aria-current="page">Details</li>
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
                            {{ Str::limit($question?->question_text, 50) ?? 'N/A' }}
                            <span class="badge badge-info">
                                {{ ucfirst(str_replace('_',' ', $question?->question_type ?? 'N/A')) }}
                            </span>
                        </h4>
                        <div>
                            <a href="{{ route('admin.quizzes.questions.index', $quiz) }}" class="btn btn-secondary ml-2">Back</a>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-8">
                            <div class="card mb-3">
                                <div class="card-header bg-primary">
                                    <h5 class="mb-0 text-white font-bold">Question Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Points:</label>
                                                <p><span class="badge badge-success">{{ $question?->points ?? 0 }}</span></p>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Type:</label>
                                                <p>{{ str_replace('_',' ', ucfirst($question?->question_type)) }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Explanation:</label>
                                                <p>{{ $question?->explanation ?: '—' }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Created At:</label>
                                                <p>{{ $question?->created_at ? $question->created_at->format(config('GET.admin_date_time_format') ?? 'M d, Y h:i A') : 'N/A' }}</p>
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
                                    <a href="{{ route('admin.quizzes.questions.edit', [$quiz, $question]) }}" class="btn btn-warning mb-2">
                                        <i class="mdi mdi-pencil"></i> Edit Question
                                    </a>

                                    <button type="button" class="btn btn-danger delete-btn delete-record"
                                        title="Delete this question"
                                        data-url="{{ route('admin.quizzes.questions.destroy', [$quiz, $question]) }}"
                                        data-redirect="{{ route('admin.quizzes.questions.index', $quiz) }}"
                                        data-text="Are you sure you want to delete this question?"
                                        data-method="DELETE">
                                        <i class="mdi mdi-delete"></i> Delete Question
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