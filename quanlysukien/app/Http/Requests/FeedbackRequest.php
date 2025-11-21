<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeedbackRequest extends FormRequest
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
            'content'  => 'required|string|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'event_id.required' => 'Vui lòng chọn sự kiện.',
            'user_id.required'  => 'Vui lòng chọn người gửi phản hồi.',
            'content.required'  => 'Vui lòng nhập nội dung phản hồi.',
        ];
    }
}
