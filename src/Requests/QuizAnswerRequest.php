<?php

namespace admin\quizzes\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuizAnswerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'answer_text' => 'required|string|min:1|max:65535',
            'is_correct' => 'required|boolean',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

