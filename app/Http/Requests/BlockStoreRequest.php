<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlockStoreRequest extends FormRequest
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
            'phone_number.required' => 'Phone number is required',
            'phone_number.digits' => 'Phone number must 13 digits',
            'phone_number.unique' => 'Phone number must unique',
            'reason.required' => 'Block reason is required',
            'reason.max' => 'Block reason maximum 50 characters',
            'reason.string' => 'Block reason expects string',
            'filter_phone_group_id.required' => 'Block Group is required',
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
            'phone_number' => 'required|number|unique:vicidial_filter_phone_numbers|digits:13',
            'reason' => 'required|string|max:50',
            'filter_phone_group_id' => 'required',
        ];
    }
}
