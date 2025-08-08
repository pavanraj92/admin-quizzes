@extends('admin::admin.layouts.master')

@section('title', 'Quiz Answer')

@section('page-title', isset($answer) ? 'Edit Answer' : 'Add Answer')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.quizzes.index') }}">Quiz Manager</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.questions.answers.index', $answer->question_id ?? $question) }}">Answers</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ isset($answer) ? 'Edit' : 'Create' }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-body">
                @if (isset($answer))
                    <form action="{{ route('admin.answers.update', $answer) }}" method="POST" id="answerForm">
                @else
                    <form action="{{ route('admin.questions.answers.store', $question) }}" method="POST" id="answerForm">
                @endif                
                    @csrf
                    @if (isset($answer))
                        @method('PUT')
                    @endif

                    <div class="form-group">
                        <label>Answer Text<span class="text-danger">*</span></label>
                        <textarea name="answer_text" class="form-control" rows="3" required>{{ $answer->answer_text ?? old('answer_text') }}</textarea>
                        @error('answer_text')<div class="text-danger validation-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label>Is Correct?<span class="text-danger">*</span></label>
                        <select name="is_correct" class="form-control select2" required>
                            @php $is = isset($answer) ? (int)$answer->is_correct : (int)old('is_correct', 0); @endphp
                            <option value="0" {{ $is===0 ? 'selected' : '' }}>No</option>
                            <option value="1" {{ $is===1 ? 'selected' : '' }}>Yes</option>
                        </select>
                        @error('is_correct')<div class="text-danger validation-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" id="saveBtn">{{ isset($answer) ? 'Update' : 'Save' }}</button>
                        <a href="{{ route('admin.questions.answers.index', $answer->question_id ?? $question) }}" class="btn btn-secondary">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

