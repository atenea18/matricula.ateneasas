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
            'name.required'         =>  'El nombre es requerido',
            'abbreviation.required' =>  'La Abreviación es requerida',
            'rank_start.required'   =>  'El rango inicial es requerido',
            'rank_start.integer'    =>  'El rango inicial debe ser númerico',
            'rank_end.required'     =>  'El rango final es requerido',
            'rank_end.integer'      =>  'El rango inicial debe ser númerico',
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
            'rank_start'                =>  'required|integer',
            'rank_end'                  =>  'required|integer',
            'school_year_id'            =>  'required',
            'words_expressions_id'      =>  'required'
        ];
    }
}
