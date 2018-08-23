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

    public static function getAsignaturesById($asignature_id)
    {
        return $asignatures = self::select(
            'asignatures.id', 'asignatures.abbreviation', 'asignatures.name', 'asignatures.subjects_type_id',
            'asignatures.created_at', 'asignatures.updated_at')
            ->join('pensum', 'asignatures.id', '=', 'pensum.asignatures_id')
            ->where('asignatures.id', '=', $asignature_id)
            ->groupBy('pensum.asignatures_id')
            ->get()->first();
    }
}
