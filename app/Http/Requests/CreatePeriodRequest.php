<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePeriodRequest extends FormRequest
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
            'period_id'         =>  'required',
            'working_day_id'    =>  'required',
            'period_state_id'   =>  'required',
            'school_year_id'    =>  'required',
            'percent'           =>  'integer',
            'start_date'        =>  'required',
            'end_date'          =>  'required',
        ];
    }

    public function messages()
    {
        return [
            'period_id.required'         =>  'El periodo es requerido',
            'working_day_id.required'    =>  'La jornada es requerida',
            'period_state_id.required'   =>  'El estado es requerido',
            'school_year_id.required'    =>  'El año lectivo es requerido',
            'percent.integer'            =>  'El porcentaje debe ser númerico',
            'start_date.required'        =>  'La fecha de inicio es requerida',
            'end_date.required'          =>  'La fecha de culminación es requerida',
        ];
    }
}
