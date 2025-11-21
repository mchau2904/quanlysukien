<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'event_id' => 'required|exists:events,event_id',
            'task_name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'required_number' => 'nullable|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'event_id.required' => 'Vui lòng chọn sự kiện.',
            'task_name.required' => 'Vui lòng nhập tên nhiệm vụ.',
            'required_number.integer' => 'Số lượng yêu cầu phải là số.',
        ];
    }
}
