@extends('admin::admin.layouts.master')

@section('title', 'Answer Details')

@section('page-title', 'Answer Details')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.quizzes.index') }}">Quiz Manager</a></li>
<li class="breadcrumb-item active" aria-current="page">Answer</li>
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
                                <th>Answer</th>
                                <td>{{ $answer->answer_text }}</td>
                            </tr>
                            <tr>
                                <th>Correct</th>
                                <td>{{ $answer->is_correct ? 'Yes' : 'No' }}</td>
                            </tr>
                            <tr>
                                <th>Question ID</th>
                                <td>{{ $answer->question_id }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <a href="{{ route('admin.questions.answers.index', $answer->question_id) }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

