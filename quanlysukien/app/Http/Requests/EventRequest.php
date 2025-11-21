<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Có thể đổi tuỳ quyền user
    }

    public function rules(): array
    {
        return [
            'event_code' => 'nullable|string|max:20|unique:events,event_code,' . $this->id . ',event_id',
            'event_name' => 'required|string|max:150',
            'organizer' => 'nullable|string|max:100',
            'manager_id' => 'nullable|integer|exists:users,user_id',
            'level' => 'nullable|in:Cấp trường,Cấp khoa,Cấp đơn vị',
            'semester' => 'nullable|string|max:10',
            'academic_year' => 'nullable|string|max:15',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after_or_equal:start_time',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'max_participants' => 'nullable|integer|min:1',
        ];
    }
}
