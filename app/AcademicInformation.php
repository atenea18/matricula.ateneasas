<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AcademicInformation extends Model
{
    protected $table = 'academic_information';

    protected $fillable =
    [
    	'has_subsidy',
    	'student_id',
    	'academic_character_id',
    	'academic_specialty_id',
    ];

    /**
     * Obtiene la relacion que hay entre el estudiante y la información academica
     */
    public function student()
    {
        return $this->belongsTo('App\Student', 'student_id');
    }

    /**
     * Obtiene la relacion que hay entre el Caracter Academica y la información academica
     */
    public function academicCharacter()
    {
        return $this->belongsTo('App\AcademicCharacter', 'academic_character_id ');
    }

    /**
     * Obtiene la relacion que hay entre la Especialidad Academica y la información academica
     */
    public function academicSpecialty()
    {
        return $this->belongsTo('App\AcademicSpecialty', 'academic_specialty_id');
    }
}
