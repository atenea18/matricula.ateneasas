<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateScaleEvaluationRequest extends FormRequest
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
            'name.required'                 =>  'El nombre es requerido',
            'abbreviation.required'         =>  'La Abreviación es requerida',
            'rank_start.required'           =>  'El rango inicial es requerido',
            'rank_start.numeric'            =>  'El rango inicial debe ser númerico',
            'rank_end.required'             =>  'El rango final es requerido',
            'rank_end.numeric'              =>  'El rango final debe ser númerico',
            'words_expressions_id.required' =>  'La expresión es requerida',
            'school_year_id.required'       =>  'El año lectivo es requerido'
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
            'name'                      =>  'required',
            'abbreviation'              =>  'required',
            'rank_start'                =>  'required|numeric',
            'rank_end'                  =>  'required|numeric',
            'school_year_id'            =>  'required',
            'words_expressions_id'      =>  'required'
        ];
    }
}
