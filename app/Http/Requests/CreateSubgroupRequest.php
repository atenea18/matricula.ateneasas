<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSubgroupRequest extends FormRequest
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

    public function messages()
    {
        return [
            'headquarter_id.required'    =>  'La sede es requerida',
            'grade_id.required'          =>  'El grado es requerido',
            'name.required'              =>  'El nombre del grupo es requerido',
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
            'headquarter_id'    =>  'required',
            'grade_id'          =>  'required',
            'name'              =>  'required',
        ];
    }
}
