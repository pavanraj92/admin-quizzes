@extends('admin::admin.layouts.master')

@section('title', 'Quizzes Management')

@section('page-title', isset($quiz) ? 'Edit Quiz' : 'Create Quiz')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.quizzes.index') }}">Quiz Manager</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{isset($quiz) ? 'Edit Quiz' : 'Create Quiz'}}></li>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-body">
                    <form action="{{ isset($quiz) ? route('admin.quizzes.update', $quiz->id) : route('admin.quizzes.store') }}"
                        method="POST" id="quizForm">
                        @if (isset($quiz))
                            @method('PUT')
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Course <span class="text-danger">*</span></label>
                                    <select name="course_id" class="form-control select2">  
                                        <option value="">Select</option>
                                        @foreach ($courses as $courseId => $courseTitle)
                                            <option value="{{ $courseId }}" {{ (($quiz?->course_id ?? old('course_id')) == $courseId) ? 'selected' : '' }}>{{ $courseTitle }}</option>
                                        @endforeach
                                    </select>
                                    @error('course_id')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Title<span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control"
                                        value="{{ $quiz?->title ?? old('title') }}" required>
                                    @error('title')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                           
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Time Limit (minutes)</label>
                                    <input type="number" name="time_limit" class="form-control" min="1" max="1440"
                                        value="{{ $quiz?->time_limit ?? old('time_limit') }}">
                                    @error('time_limit')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>                           
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Passing Score (%)<span class="text-danger">*</span></label>
                                    <input type="number" name="passing_score" class="form-control" min="0" max="100"
                                        value="{{ $quiz?->passing_score ?? old('passing_score', 0) }}" required>
                                    @error('passing_score')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Difficulty Level<span class="text-danger">*</span></label>
                                    <select name="difficulty" class="form-control select2">
                                        <option value="">Select</option>
                                        <option value="easy" {{ (($quiz?->difficulty ?? old('difficulty')) == 'easy') ? 'selected' : '' }}>Easy</option>
                                        <option value="medium" {{ (($quiz?->difficulty ?? old('difficulty')) == 'medium') ? 'selected' : '' }}>Medium</option>
                                        <option value="hard" {{ (($quiz?->difficulty ?? old('difficulty')) == 'hard') ? 'selected' : '' }}>Hard</option>
                                    </select>
                                    @error('difficulty')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status<span class="text-danger">*</span></label>
                                    <select name="status" class="form-control select2" required>
                                        <option value="active" {{ (($quiz?->status ?? old('status')) == 'active') ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ (($quiz?->status ?? old('status')) == 'inactive') ? 'selected' : '' }}>Inactive</option>
                                        <option value="draft" {{ (($quiz?->status ?? old('status')) == 'draft') ? 'selected' : '' }}>Draft</option>
                                    </select>
                                    @error('status')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" id="description" class="form-control desc-editor">{{ $quiz?->description ?? old('description') }}</textarea>
                            @error('description')
                                <div class="text-danger validation-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" id="saveBtn">{{isset($quiz) ? 'Update' : 'Save'}}</button>
                            <a href="{{ route('admin.quizzes.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.0.0/ckeditor5.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('backend/custom.css') }}">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/41.2.1/classic/ckeditor.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
    let ckEditorInstance;
    ClassicEditor
    .create(document.querySelector('#description'))
    .then(editor => {
        ckEditorInstance = editor;
        editor.ui.view.editable.element.style.minHeight = '250px';
        editor.ui.view.editable.element.style.maxHeight = '250px';
        editor.ui.view.editable.element.style.overflowY = 'auto';
        editor.model.document.on('change:data', () => {
            const val = editor.getData();
            $('#description').val(val);
            $('#description').trigger('keyup');
        });
    })
    .catch(error => { console.error(error); });
    </script>

    <script>
        $(document).ready(function() {
            $('.select2').select2();
            $('#quizForm').validate({
                ignore: [],
                rules: {
                    course_id: { required: true },
                    title: { required: true, minlength: 3 },
                    passing_score: { required: true, min: 0, max: 100 },
                    difficulty: { required: true },
                    status: { required: true },
                },
                messages: {
                    title: { required: 'Please enter a title', minlength: 'Title must be at least 3 characters long' },
                    course_id: { required: 'Please select a course' },
                    passing_score: { required: 'Please enter a passing score', min: 'Passing score must be between 0 and 100', max: 'Passing score must be between 0 and 100' },
                    difficulty: { required: 'Please select a difficulty level' },
                    status: { required: 'Please select a status' },
                },
                submitHandler: function(form) {
                    if (ckEditorInstance) {
                        $('#description').val(ckEditorInstance.getData());
                    }
                    const $btn = $('#saveBtn');
                    if ($btn.text().trim().toLowerCase() === 'update') {
                        $btn.prop('disabled', true).text('Updating...');
                    } else {
                        $btn.prop('disabled', true).text('Saving...');
                    }
                    form.submit();
                },
                errorElement: 'div',
                errorClass: 'text-danger custom-error',
                errorPlacement: function(error, element) {
                    $('.validation-error').hide();
                    if (element.attr('id') === 'description') {
                        error.insertAfter($('.ck-editor'));
                    } else if (element.hasClass('select2-hidden-accessible')) {
                        // Place error after select2's generated container
                        error.insertAfter(element.next('.select2'));
                    } else {
                        error.insertAfter(element);
                    }
                }
            });
        });
    </script>
@endpush

