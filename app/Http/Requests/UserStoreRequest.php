<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
            'user.required' => 'User name is required',
            'user.alpha_num' => 'User name must contain Alpha Numeric',
            'user.unique' => 'User name must unique',
            'full_name.required' => 'Full Name is required',
            'pass.required' => 'User password is required',
            'pass.max' => 'User password maximum 8 digits',
            'email.required' => 'User email is required',
            'email.email' => 'User email must contain email',
            'email.unique' => 'User email must unique',
            'agentcall_manual.required' => 'Agent call manual is required',
            'agentcall_manual.in' => 'Agent call manual type not matched',
            'user_group.required' => 'User group is required',
            'phone_login.required' => 'Phone ID is required',
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
            'user' => 'required|alpha_num|unique:vicidial_users',
            'full_name' => 'required',
            'pass' => 'required|max:8',
            'email' => 'required|email|unique:vicidial_users',
            'agentcall_manual' => 'required|in:0,1',
            'user_group' => 'required',
            'phone_login' => 'required',
        ];
    }
}
