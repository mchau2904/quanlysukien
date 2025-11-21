<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRegistrationRequest extends FormRequest
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
            'status'   => 'required|in:Đã đăng ký,Đã tham gia,Đã hủy',
            'note'     => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'event_id.required' => 'Vui lòng chọn sự kiện.',
            'user_id.required'  => 'Vui lòng chọn người dùng.',
            'status.required'   => 'Vui lòng chọn trạng thái.',
        ];
    }
}
