<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ];
    }

    public function messages()
    {
        return [
            'string' => 'You must type string',
            'max' => 'Characters too long',
            'name.required' => 'Name cannot be left blank',
            'email.required' => 'Email cannot be left blank',
            'password.required' => 'Password cannot be left blank',
            'password.min' => 'Password minimum 6 characters',
            'password.confirmed' => 'Password confirm is incorrect'
        ];
    }
}
