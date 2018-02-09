<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateStudentEnrollmentRequest extends FormRequest
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
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'eps_id.required'                   =>  'La Eps es requerida, en caso de no tener seleccione No posee | Sección Inf. Médica',
            'school_year_id.required'            =>  'El año lectivo es requerido | Sección Inf. Académica',
            'has_subsidy.required'              =>  'El tipo de subsidio es requerido | Sección Inf. Académica',
            'academic_character_id.required'    =>  'El caracter academico es requerido, en caso de no tener seleccione No aplica | Sección Inf. Académica',
            'academic_specialty_id.required'    =>  'La especialidad academica es requerida, en caso de no tener seleccione No aplica | Sección Inf. Académica',
            // 'stratum_id.required'               =>  'El estrato es requerido | Sección Inf. Economía',
            'victim_of_conflict_id.required'    =>  'Seleccione un tipo de victima de conflico, en caso de no tener seleccione No aplica | Sección Inf. Desplazamiento',
            // 'group_id.required'                 =>  'Seleccione el grupo | Seccion Inf. Académica',
            // 'workingday_id.required'            =>  'Seleccione la jornada | Sección Inf. Académica',
            'headquarter_id.required'           =>  'Seleccione la sede | Sección Inf. Académica',
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
            'academic_character_id'         =>  'required',
            'academic_specialty_id'         =>  'required',
            'school_year_id'                 =>  'required',
            'has_subsidy'                   =>  'required',
            // 'headquarter_id'                =>  'required',
            // 'workingday_id'                 =>  'required',
            // 'group_id'                      =>  'required',
            'eps_id'                        =>  'required',
            'victim_of_conflict_id'         =>  'required',
            // 'stratum_id'                    =>  'required',
        ];
    }
}
