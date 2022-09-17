<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherRequest extends FormRequest
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
            'email' => 'required|email|unique:users,email|max:255',
            'firstname' => 'required|string|min:2,max:255',
            'lastname' => 'required|string|min:2,max:255',
            'sex' => 'required|string|in:m,v,o',
            'phone_number' => 'required|max:255',
            'date_of_birth' => 'required|date',
            'country' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'zipcode' => 'required|max:6',
            'street' => 'required|max:255',
            'password' => 'nullable|required_if:generatePassword,0|min:8|max:255',
            'generatePassword' => 'required|boolean',
            'sendEmail' => 'required|boolean',
            'abbreviation' => 'required|min:2|max:3|unique:teachers,abbreviation',
            'student_name' => 'required|min:2|max:255'
        ];
    }
}
