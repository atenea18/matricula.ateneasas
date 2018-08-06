<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BloodType extends Model
{
    protected $table = 'blood_type';

    protected $fillabled =
    [
    	'blood_type'
    ];	

    /**
     * Obtiene la relacion que hay entre la información medica y el tipo de sangre
     */
    public function medicalInformation()
    {
        return $this->hasMany('App\medicalInformation', 'blood_type_id');
    }
}
