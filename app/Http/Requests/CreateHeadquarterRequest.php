<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateHeadquarterRequest extends FormRequest
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
            'zone_id.required'          =>  'La zona es requerida',
            'id_city_address.required'  =>  'La ciudad es requerida',
            'name.required'             =>  'El nombre de la sede es requerida',
            'name.unique'               =>  'El nombre de la sede esta duplicado',
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
            
            'name'              =>  'required|unique:headquarter',
            'id_city_address'   =>  'required',
            'zone_id'           =>  'required'
        ];
    }
}
