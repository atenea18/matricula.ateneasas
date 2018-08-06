<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RelationshipFamily extends Model
{
    protected $table = 'relationship';

    protected $fillabled =
    [
    	'type'
    ];	

    /**
     * Obtiene la relacion que hay entre el estudiante y los acudientes
     */
    public function students()
    {
        return  $this->belongsToMany('App\Student', 'family_relationship_student')
                ->withPivot('family_id');
    }

    /**
     * Obtiene la relacion que hay entre el estudiante y los familiares
     */
    public function family()
    {
        return  $this->belongsToMany('App\Family', 'family_relationship_student')
                ->withPivot('student_id');
    }
}
