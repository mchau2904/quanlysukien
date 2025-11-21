<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'created_by' => 'required|exists:users,user_id',
            'type'       => 'required|in:Theo sự kiện,Theo khoa,Theo lớp,Theo sinh viên',
            'parameters' => 'nullable|string',
            'result_url' => 'nullable|url|max:255',
        ];
    }
}
