<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFamilyRequest extends FormRequest
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
            'relationship_id.required'          =>  'El parentesco es requerido',
            'name.required'                     =>  'El nombre es requerido',
            'last_name.required'                =>  'El apellido es requerido',
            'identification_type_id.required'   =>  'El tipo de identificacion es requerido',
            'identification_number.required'    =>  'El número de identificación es requerido',
            'identification_number.min:7'       =>  'El número de identificación debe contener por lo menos 7 digitos',
            'id_city_expedition.required'       =>  'La ciudad de expedición es requerida',
            'id_city_of_birth.required'         =>  'La ciudad de nacimiento es requerida',
            'address.required'                  => 'La dirección es requerida',
            'neighborhood.required'             =>  'El barrio es requerido',
            'gender_id.required'                =>  'El genero es requerido',
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
            'relationship_id'           =>  'required',
            'name'                      =>  'required|min:2',
            'last_name'                 =>  'required|min:2',
            'identification_type_id'    =>  'required|min:1',
            'identification_number'     =>  'required|min:7',
            'id_city_expedition'        =>  'required|min:1',
            'id_city_of_birth'          =>  'required|min:1',
            'gender_id'                 =>  'required',
            'address'                   =>  'required',
            'neighborhood'              =>  'required',
        ];
    }
}
