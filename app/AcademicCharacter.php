<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AcademicCharacter extends Model
{
    protected $table = 'academic_character';

    protected $fillabled =
    [
    	'name'
    ];

    /**
     * Obtiene la relacion que hay entre el Caracter Academica y la información academica
     */
    public function academicInformation()
    {
        return $this->hasMany('App\AcademicInformation', 'academic_character_id');
    }
}
