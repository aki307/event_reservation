<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;


class CreateEventRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:80'],
            'start_date_and_time' => ['required', 'date_format:Y-m-d H:i:s'],
            'end_date_and_time' => ['date_format:Y-m-d H:i:s', 'after:start_and_date_time'],
            'location' => ['required', 'string', 'max:80'],
            'description' => ['nullable', 'string'],
            'group_id' => ['required', 'exists:groups,id'],
        ];
    }
}
