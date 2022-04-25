<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssignmentRequest extends FormRequest
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
            'subject' => 'required|exists:subjects,name',
            'school_class' => 'required|exists:school_classes,name',
            'name' => 'required|string|max:255',
            'weighting' => 'required|integer',
            'description' => 'required|string|max:255'
        ];
    }
}
