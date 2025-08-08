@extends('admin::admin.layouts.master')

@section('title', 'Quiz Question Details')

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
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th>Question</th>
                                <td>{{ $question->question_text }}</td>
                            </tr>
                            <tr>
                                <th>Type</th>
                                <td>{{ str_replace('_',' ', ucfirst($question->question_type)) }}</td>
                            </tr>
                            <tr>
                                <th>Points</th>
                                <td>{{ $question->points }}</td>
                            </tr>
                            <tr>
                                <th>Explanation</th>
                                <td>{{ $question->explanation ?: '—' }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <a href="{{ route('admin.quizzes.questions.index', $quiz) }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

