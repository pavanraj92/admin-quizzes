@extends('admin::admin.layouts.master')

@section('title', 'Quizzes Management')

@section('page-title', 'Quiz Details')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.quizzes.index') }}">Quiz Manager</a></li>
    <li class="breadcrumb-item active" aria-current="page">Quiz Details</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="table-responsive">
                         <div class="card-body">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <th scope="row">Course</th>
                                        <td scope="col">{{ $quiz->course->title ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Title</th>
                                        <td scope="col">{{ $quiz->title ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Description</th>
                                        <td scope="col">{!! $quiz->description ?? 'N/A' !!}</td>
                                    </tr>                                    
                                    <tr>
                                        <th scope="row">Difficulty</th>
                                        <td scope="col">{{ ucfirst($quiz->difficulty ?? 'N/A') }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Passing Score</th>
                                        <td scope="col">{{ $quiz->passing_score ?? 0 }}%</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Time Limit</th>
                                        <td scope="col">{{ $quiz->time_limit ? $quiz->time_limit . ' min' : '—' }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Status</th>
                                        <td scope="col"> {{ ucfirst($quiz->status) }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Created At</th>
                                        <td scope="col">{{ $quiz->created_at
                                            ? $quiz->created_at->format(config('GET.admin_date_time_format') ?? 'Y-m-d H:i:s')
                                            : '—' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <a href="{{ route('admin.quizzes.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

