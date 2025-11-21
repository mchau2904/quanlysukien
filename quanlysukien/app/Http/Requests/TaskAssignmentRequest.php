<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'task_id' => 'required|exists:tasks,task_id',
            'user_id' => 'required|exists:users,user_id',
            'status'  => 'required|in:Chưa làm,Đang làm,Hoàn thành',
        ];
    }

    public function messages(): array
    {
        return [
            'task_id.required' => 'Vui lòng chọn nhiệm vụ.',
            'user_id.required' => 'Vui lòng chọn người được giao.',
            'status.required'  => 'Vui lòng chọn trạng thái.',
        ];
    }
}
