<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTeacherRequest extends FormRequest
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
            'address.required'                  => 'La dirección es requerida',
            'gender_id.required'                =>  'El genero es requerido',
            'neighborhood.required'             =>  'El barrio es requerido',
            'id_city_of_birth.required'         =>  'La ciudad de nacimiento es requerida',
            'id_city_expedition.required'       =>  'La ciudad de expedición es requerida',
            'id_city_address.required'          =>  'La ciudad de la dirección es requerida',
            'identification_type_id.required'   =>  'El tipo de identificacion es requerido',
            'identification_number.required'    =>  'El número de identificación es requerido',
            // 'identification_number.unique'      =>  'El número de identificación debe ser unica',
            'identification_number.min:7'         =>  'El número de identificación debe contener por lo menos 7 digitos',
            'last_name.required'                =>  'El apellido es requerido',
            'name.required'                     =>  'El nombre es requerido',
            'zone_id.required'                  =>  'La zona rural es requerida'
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
            'name'                      =>  'required|min:2',
            'last_name'                 =>  'required|min:2',
            'identification_type_id'    =>  'required|min:1',
            'identification_number'     =>  'required|min:7',
            'id_city_expedition'        =>  'required|min:1',
            'id_city_of_birth'          =>  'required|min:1',
            'gender_id'                 =>  'required',
            'address'                   =>  'required',
            'neighborhood'              =>  'required',
            'id_city_address'           =>  'required',
            'zone_id'                   =>  'required'
        ];
    }
}
