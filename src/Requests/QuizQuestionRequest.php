<?php

namespace admin\quizzes\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuizQuestionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'question_text' => 'required|string|min:3|max:65535',
            'question_type' => 'required|in:multiple_choice,true_false,fill_in_blank,text',
            'explanation' => 'nullable|string|max:65535',
            'points' => 'required|integer|min:0|max:1000',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

