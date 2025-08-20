@extends('admin::admin.layouts.master')

@section('title', 'Quizzes Management')

@section('page-title', 'Quiz Manager')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Quiz Manager</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-body">
                <h4 class="card-title">Filter</h4>
                <form action="{{ route('admin.quizzes.index') }}" method="GET" id="filterForm">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" name="keyword" id="keyword" class="form-control"
                                    value="{{ app('request')->query('keyword') }}" placeholder="Enter title">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control select2">
                                    <option value="">All</option>
                                    <option value="inactive" {{ app('request')->query('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="active" {{ app('request')->query('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="draft" {{ app('request')->query('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-auto mt-1 text-right">
                            <div class="form-group">
                                <label for="created_at">&nbsp;</label>
                                <button type="submit" form="filterForm" class="btn btn-primary mt-4">Filter</button>
                                <a href="{{ route('admin.quizzes.index') }}" class="btn btn-secondary mt-4">Reset</a>
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
                    @admincan('quizzes_manager_create')
                    <div class="text-right">
                        <a href="{{ route('admin.quizzes.create') }}" class="btn btn-primary mb-3">Create New Quiz</a>
                    </div>
                    @endadmincan

                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th>S. No.</th>
                                    <th>Course</th>
                                    <th>@sortablelink('title', 'Title', [], ['class' => 'text-dark'])</th>
                                    <th>Difficulty</th>
                                    <th>Passing Score</th>
                                    <th>Time Limit</th>
                                    <th>@sortablelink('status', 'Status', [], ['class' => 'text-dark'])</th>
                                    <th>@sortablelink('created_at', 'Created At', [], ['class' => 'text-dark'])</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($quizzes as $quiz)
                                <tr>
                                    <th scope="row">{{ ($quizzes->currentPage() - 1) * $quizzes->perPage() + $loop->iteration }}</th>
                                    <td>{{ $quiz->course->title ?? '—' }}</td>
                                    <td>{{ $quiz->title }}</td>
                                    <td>{{ $quiz->difficulty }}</td>
                                    <td>{{ $quiz->passing_score }}%</td>
                                    <td>{{ $quiz->time_limit ? $quiz->time_limit . ' min' : '—' }}</td>
                                    <td>
                                        @php
                                        $isActive = $quiz->status === 'active';
                                        $toggleTo = $isActive ? 'inactive' : 'active';
                                        $label = ucfirst($quiz->status);
                                        $btnClass = $isActive ? 'btn-success' : 'btn-warning';
                                        $tooltip = $isActive
                                        ? 'Click to change status to inactive'
                                        : 'Click to change status to active';
                                        @endphp
                                        <a href="javascript:void(0)" data-toggle="tooltip" data-placement="top"
                                            title="{{ $tooltip }}"
                                            data-url="{{ route('admin.quizzes.updateStatus') }}"
                                            data-method="POST" data-status="{{ $toggleTo }}"
                                            data-id="{{ $quiz->id }}"
                                            class="btn {{ $btnClass }} btn-sm update-status">{{ $label }}</a>
                                    </td>
                                    <td>
                                        {{ $quiz->created_at ? $quiz->created_at->format(config('GET.admin_date_time_format') ?? 'Y-m-d H:i:s') : '—' }}
                                    </td>
                                    <td style="width: 14%;">
                                        <a href="{{ route('admin.quizzes.questions.index', $quiz) }}"
                                            data-toggle="tooltip" data-placement="top" title="List Questions"
                                            class="btn btn-dark btn-sm"><i class="mdi mdi-format-list-bulleted"></i></a>
                                        @admincan('quizzes_manager_view')
                                        <a href="{{ route('admin.quizzes.show', $quiz) }}"
                                            data-toggle="tooltip" data-placement="top" title="View this record"
                                            class="btn btn-warning btn-sm"><i class="mdi mdi-eye"></i></a>
                                        @endadmincan
                                        @admincan('quizzes_manager_edit')
                                        <a href="{{ route('admin.quizzes.edit', $quiz) }}"
                                            data-toggle="tooltip" data-placement="top" title="Edit this record"
                                            class="btn btn-success btn-sm"><i class="mdi mdi-pencil"></i></a>
                                        @endadmincan
                                        @admincan('quizzes_manager_delete')
                                        <a href="javascript:void(0)" data-toggle="tooltip"
                                            data-placement="top" title="Delete this record"
                                            data-url="{{ route('admin.quizzes.destroy', $quiz) }}"
                                            data-text="Are you sure you want to delete this record?"
                                            data-method="DELETE"
                                            class="btn btn-danger btn-sm delete-record"><i
                                                class="mdi mdi-delete"></i></a>
                                        @endadmincan
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No records found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                        @if ($quizzes->count() > 0)
                        {{ $quizzes->links('admin::pagination.custom-admin-pagination') }}
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End quiz Content -->
</div>
@endsection