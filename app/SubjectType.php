<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubjectType extends Model
{
	protected $table = "subjects_type";

    protected $fillable = [
    	'name'
    ];

    /**
     * Obtiene la relacion que hay entre el docente y los grupos la asignatura (Pensum)
     */
    public function pensums()
    {
        return $this->hasMany(GroupPensum::class, 'subjects_type_id'); 
    }
}
