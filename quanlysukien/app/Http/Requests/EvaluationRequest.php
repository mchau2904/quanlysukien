<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EvaluationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'event_id' => 'required|exists:events,event_id',
            'user_id'  => 'required|exists:users,user_id',
            'score'    => 'nullable|numeric|min:0|max:10',
            'comment'  => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'event_id.required' => 'Vui lòng chọn sự kiện.',
            'user_id.required'  => 'Vui lòng chọn người dùng.',
            'score.numeric'     => 'Điểm phải là số hợp lệ.',
        ];
    }
}
