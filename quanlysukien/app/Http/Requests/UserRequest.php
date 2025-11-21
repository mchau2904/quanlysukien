<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Có thể kiểm tra quyền ở middleware
    }

    public function rules(): array
    {
        $userId = $this->route('id'); // Lấy id nếu đang update

        return [
            'username'   => 'required|string|max:50|unique:users,username,' . $userId . ',user_id',
            'password'   => $userId ? 'nullable|string|min:4' : 'required|string|min:4',
            'full_name'  => 'required|string|max:100',
            'dob'        => 'nullable|date',
            'gender'     => 'nullable|in:Nam,Nữ,Khác',
            'phone'      => 'nullable|string|max:15',
            'email'      => 'nullable|email|max:100',
            'class'      => 'nullable|string|max:50',
            'faculty'    => 'nullable|string|max:100',
            'role'       => 'required|in:admin,student',
        ];
    }
}
