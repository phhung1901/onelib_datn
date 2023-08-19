<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'current_password.required' => 'Password cannot be left blank',
            'new_password.required' => 'Password cannot be left blank',
            'new_password.min' => 'Password minimum 6 characters',
            'new_password.confirmed' => 'Password confirm is incorrect'
        ];
    }
}
