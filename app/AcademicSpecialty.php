<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AcademicSpecialty extends Model
{
    protected $table = 'academic_specialty';

    protected $fillabled =
    [
    	'name'
    ];	

     /**
     * Obtiene la relacion que hay entre la Especialidad Academica y la información academica
     */
    public function academicInformation()
    {
        return $this->hasMany('App\AcademicInformation', 'academic_specialty_id');
    }
}
