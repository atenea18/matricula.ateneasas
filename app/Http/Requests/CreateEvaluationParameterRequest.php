<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEvaluationParameterRequest extends FormRequest
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
            'parameter.required'    =>  'El parametro de evaluación es requerido',
            'abbreviation.required'    =>  'La abreviación es requerida',
            'percent.required'    =>  'El porcentaje es requerido',
            'school_year_id.required'    =>  'El año lectivo es requerido',
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
            'parameter' =>  'required',
            'abbreviation' =>  'required',
            'percent' =>  'required',
            'school_year_id'    =>  'required',
        ];
    }
}
