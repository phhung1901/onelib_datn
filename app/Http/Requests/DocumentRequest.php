<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class DocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|min:5|max:255|regex:/[a-zA-Z]/i',
            'source_url' => 'mimes:pdf,doc,docx,ppt,pptx,txt',
//            'page_number' => 'required|min:1',
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'required' => 'This field can not empty',
            'source_url.mimes' => 'Formats accepted: doc, docx, odt, pdf, ppt, pptx, txt',
            'regex' => 'Title must be at least 5 letters'
        ];
    }
}
