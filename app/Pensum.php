<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pensum extends Model
{

    protected $table = "pensum";

    protected $fillable = [
        'code_pensum', 'percent', 'description', 'ihs', 'order', 'grade_id', 'areas_id', 'subjects_type_id', 'asignatures_id', 'institution_id'
    ];

    /**
     * Obtiene la relacion que hay entre el grupo los pensums
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_pensum', 'pensum_id', 'group_id');
    }

    public static function getAreaByAsignatureOfPensum($grade_id, $asignature_id, $institution_id)
    {
        return $asignatures = self::select(
            'areas.id', 'areas.name')
            ->join('asignatures', 'asignatures.id', '=', 'pensum.asignatures_id')
            ->join('areas', 'areas.id', '=', 'pensum.areas_id')
            ->where('pensum.grade_id', '=', $grade_id)
            ->where('pensum.asignatures_id', '=', $asignature_id)
            ->where('pensum.institution_id', '=', $institution_id)
            ->groupBy('pensum.asignatures_id')
            ->get()->first();
    }

    public static function getAsignaturesByAreaPensum($grade_id, $area_id, $institution_id)
    {
        return $asignatures = self::select(
            'pensum.id as pensum_id',
            'asignatures.id as asignatures_id', 'asignatures.abbreviation', 'asignatures.name', 'asignatures.subjects_type_id',
            'asignatures.created_at', 'asignatures.updated_at')
            ->join('asignatures', 'asignatures.id', '=', 'pensum.asignatures_id')
            ->join('areas', 'areas.id', '=', 'pensum.areas_id')
            ->where('pensum.grade_id', '=', $grade_id)
            ->where('pensum.institution_id', '=', $institution_id)
            ->where('pensum.areas_id', '=', $area_id)
            ->groupBy('pensum.asignatures_id')
            ->get();
    }


}
