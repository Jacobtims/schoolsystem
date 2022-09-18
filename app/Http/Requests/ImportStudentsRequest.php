<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportStudentsRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'file' => 'required|mimes:xlsx,xls,csv|max:8192',
            'password' => 'nullable|required_if:generatePassword,0|min:8|max:255',
            'generatePassword' => 'required|boolean',
            'sendEmail' => 'required|boolean'
        ];
    }
}
