<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Asignature extends Model
{
    
	protected $fillable = [
		'name', 'abbreviation', 'subjects_type_id'
	];

	/**
     * Obtiene la relacion que hay entre el docente y los grupos la asignatura (Pensum)
     */
    public function pensums()
    {
        return $this->belongsTo(GroupPensum::class, 'asignature_id'); 
    }

    public function evaluationPeriods()
    {
        return $this->hasMany(EvaluationPeriod::class, 'asignatures_id');
    }
}
