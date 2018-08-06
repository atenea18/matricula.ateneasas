<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Displacement extends Model
{
    protected $table = 'displacement';

    protected $fillable=
    [
        'id',
    	'expulsion_date',
    	'certificate',
    	'student_id',
    	'victim_of_conflict_id'
    ];

    /**
     * Obtiene la relacion que hay entre el estudiante y la información de desplazamiento
     */
    public function student()
    {
        return $this->belongsTo('App\Student', 'student_id');
    }

    /**
     * Obtiene la relacion que hay entre la información de desplazamiento y victimas del conflicto armando
     */
    public function victimOfConflict()
    {
        return $this->belongsTo('App\VictimOfConflict', 'victim_of_conflict_id');
    }
}
