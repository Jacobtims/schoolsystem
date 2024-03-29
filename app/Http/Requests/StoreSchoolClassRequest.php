<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSchoolClassRequest extends FormRequest
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
            'mentor_abbreviation' => 'required|string|max:255|exists:teachers,abbreviation',
            'class_name' => 'required|unique:school_classes,name|min:2|max:255',
            'students' => 'array',
            'students.*.id' => 'integer|exists:students,id'
        ];
    }
}
