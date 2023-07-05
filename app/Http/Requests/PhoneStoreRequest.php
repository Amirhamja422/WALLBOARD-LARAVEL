<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PhoneStoreRequest extends FormRequest
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
            'extension.required' => 'Phone extension is required',
            'extension.max' => 'Phone extension maximum 8 digits',
            'extension.unique' => 'Phone extension must unique',
            'conf_secret.required' => 'Registration password is required',
            'conf_secret.max' => 'Registration password maximum 8 digits',
            'is_webphone.required' => 'Web RTC is required',
            'is_webphone.in' => 'Web RTC type not matched',
            'pass.required' => 'Login password is required',
            'pass.numeric' => 'Login password required numeric value',
            'pass.digits' => 'Login password maximum 4 digits',
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
            'extension' => 'required|max:8|unique:phones',
            'conf_secret' => 'required|max:10',
            'is_webphone' => 'required|in:Y,N',
            'pass' => 'required|digits:4',
        ];
    }
}
