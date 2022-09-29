<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGradesRequest extends FormRequest
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
            'grades' => 'required|array',
            'grades.*' => 'array',
            'grades.*.*' => 'array',
            'grades.*.*.number' => 'nullable|integer|min:1|max:10'
        ];
    }
}
