<?php

namespace admin\quizzes\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuizCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => 'nullable|integer',            
            'course_id' => 'required|integer',
            'title' => 'required|string|min:3|max:255|unique:quizzes,title',
            'description' => 'nullable|string|max:65535',
            'difficulty' => 'required|in:easy,medium,hard',
            'passing_score' => 'required|integer|min:0|max:100',
            'time_limit' => 'nullable|decimal:2|min:1|max:1440',
            'status' => 'required|in:active,inactive,draft',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

