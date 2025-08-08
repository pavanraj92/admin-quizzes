@extends('admin::admin.layouts.master')

@section('title', 'Quiz Questions')

@section('page-title', isset($question) ? 'Edit Question' : 'Add Question')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.quizzes.index') }}">Quiz Manager</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.quizzes.questions.index', $quiz) }}">Questions</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ isset($question) ? 'Edit' : 'Create' }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-body">
                <form action="{{ isset($question) ? route('admin.quizzes.questions.update', [$quiz, $question]) : route('admin.quizzes.questions.store', $quiz) }}" method="POST" id="questionForm">
                    @csrf
                    @if (isset($question))
                        @method('PUT')
                    @endif

                    <div class="form-group">
                        <label>Question Text<span class="text-danger">*</span></label>
                        <textarea name="question_text" class="form-control" rows="4" required>{{ $question->question_text ?? old('question_text') }}</textarea>
                        @error('question_text')<div class="text-danger validation-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Question Type<span class="text-danger">*</span></label>
                                <select name="question_type" class="form-control select2" required>
                                    @php $type = $question->question_type ?? old('question_type'); @endphp
                                    <option value="multiple_choice" {{ $type==='multiple_choice' ? 'selected' : '' }}>Multiple choice</option>
                                    <option value="true_false" {{ $type==='true_false' ? 'selected' : '' }}>True / False</option>
                                    <option value="fill_in_blank" {{ $type==='fill_in_blank' ? 'selected' : '' }}>Fill in the blank</option>
                                    <option value="text" {{ $type==='text' ? 'selected' : '' }}>Text</option>
                                </select>
                                @error('question_type')<div class="text-danger validation-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Points<span class="text-danger">*</span></label>
                                <input type="number" min="0" name="points" class="form-control" value="{{ $question->points ?? old('points', 1) }}" required>
                                @error('points')<div class="text-danger validation-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Explanation</label>
                        <textarea name="explanation" class="form-control" rows="3">{{ $question->explanation ?? old('explanation') }}</textarea>
                        @error('explanation')<div class="text-danger validation-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" id="saveBtn">{{ isset($question) ? 'Update' : 'Save' }}</button>
                        <a href="{{ route('admin.quizzes.questions.index', $quiz) }}" class="btn btn-secondary">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

