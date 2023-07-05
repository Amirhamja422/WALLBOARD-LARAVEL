<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuxRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'start_date.required' => 'Start date is required',
            'end_date.required' => 'End date is required',
            'end_date.after_or_equal' => 'End date must smaller then start date',
            'search_type' => 'Search type is required',
            'search_type.in' => 'Search type is invalid',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'start_date' => 'required',
            'end_date' => 'required|after_or_equal:start_date',
            'search_type' => 'required|in:single_day,sum_day',
            'agent' => 'required',
        ];
    }
}
