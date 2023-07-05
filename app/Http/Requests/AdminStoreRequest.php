<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminStoreRequest extends FormRequest
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
            'name.required' => 'Full name is required',
            'name.string' => 'Full name must string',
            'name.max' => 'Full name maximum 50 characters',
            'password.required' => 'Password is required',
            'password.min' => 'User password minimum 4 characters',
            'email.required' => 'Email is required',
            'email.email' => 'Email must contain email',
            'email.unique' => 'Email must unique',
            'status.required' => 'Status is required',
            'status.in' => 'Status not matched',
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
            'name' => 'required|string|max:50',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:4',
            'status' => 'required|in:0,1',
        ];
    }
}
