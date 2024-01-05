<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;


class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // ここに認可ロジックを記述するか、または単にtrueを返す
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'user_name' => ['required', 'string', 'min:6', 'max:40'],
            'login_id' => ['required', 'string', 'min:6', 'max:22', Rule::unique('users', 'login_id')->ignore($this->user),],
            'user_type_id' => ['required', 'exists:user_types,id'],
            'group_id' => ['required', 'exists:groups,id'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ];
    }
}
