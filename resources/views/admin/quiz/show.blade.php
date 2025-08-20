@extends('admin::admin.layouts.master')

@section('title', 'Quiz Details - ' . ($quiz?->title ?? 'N/A'))
@section('page-title', 'Quiz Details')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.quizzes.index') }}">Quiz Manager</a></li>
    <li class="breadcrumb-item active" aria-current="page">Quiz Details</li>
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
                            {{ $quiz?->title ?? 'N/A' }}
                            <span class="badge {{ $quiz?->status === 'active' ? 'badge-success' : 'badge-secondary' }}">
                                {{ ucfirst($quiz?->status ?? 'inactive') }}
                            </span>
                        </h4>
                        <div>
                            <a href="{{ route('admin.quizzes.index') }}" class="btn btn-secondary ml-2">Back</a>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-8">
                            <div class="card mb-3">
                                <div class="card-header bg-primary">
                                    <h5 class="mb-0 text-white font-bold">Quiz Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Course:</label>
                                                <p>{{ $quiz?->course->title ?? 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Difficulty:</label>
                                                <p>{{ ucfirst($quiz?->difficulty ?? 'N/A') }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Passing Score:</label>
                                                <p>{{ $quiz?->passing_score ?? 0 }}%</p>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Time Limit:</label>
                                                <p>{{ $quiz?->time_limit ? $quiz->time_limit . ' min' : '—' }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Description:</label>
                                                <p>{!! $quiz?->description ?? 'N/A' !!}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Created At:</label>
                                                <p>{{ $quiz?->created_at ? $quiz->created_at->format(config('GET.admin_date_time_format') ?? 'M d, Y h:i A') : 'N/A' }}</p>
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
                                    @admincan('quizzes_manager_edit')
                                    <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="btn btn-warning mb-2">
                                        <i class="mdi mdi-pencil"></i> Edit Quiz
                                    </a>
                                    @endadmincan

                                    @admincan('quizzes_manager_delete')
                                    <button type="button" class="btn btn-danger delete-btn delete-record"
                                        title="Delete this quiz"
                                        data-url="{{ route('admin.quizzes.destroy', $quiz) }}"
                                        data-redirect="{{ route('admin.quizzes.index') }}"
                                        data-text="Are you sure you want to delete this quiz?"
                                        data-method="DELETE">
                                        <i class="mdi mdi-delete"></i> Delete Quiz
                                    </button>
                                    @endadmincan
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
