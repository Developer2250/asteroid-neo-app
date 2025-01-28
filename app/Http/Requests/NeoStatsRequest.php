<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NeoStatsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date|after_or_equal:today',
        ];
    }

    public function messages()
    {
        return [
            'start_date.required' => 'The start date is required.',
            'start_date.date' => 'Please provide a valid start date.',
            'end_date.required' => 'The end date is required.',
            'end_date.date' => 'Please provide a valid end date.',
            'end_date.after_or_equal' => 'The end date must be the same as or later than the start date.',
            'end_date.after_or_equal' => 'The end date must be today or a future date.',
        ];
    }
}
