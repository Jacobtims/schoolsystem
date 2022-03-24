<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeacherRequest extends FormRequest
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
            'id' => 'required|integer',
            'email' => 'required|email|max:255',
            'firstname' => 'required|string|min:2,max:255',
            'lastname' => 'required|string|min:2,max:255',
            'phone_number' => 'required|max:255',
            'date_of_birth' => 'required|date',
            'country' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'zipcode' => 'required|max:6',
            'street' => 'required|max:255',
            'abbreviation' => 'required|min:2|max:3',
            'student_name' => 'required|min:2|max:255'
        ];
    }
}
