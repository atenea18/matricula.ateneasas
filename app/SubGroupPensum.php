<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubGroupPensum extends Model
{
    protected $table = 'sub_group_pensum';

    /**
     * Obtiene la relacion que hay entre el pensum y la asignatura
     */
    public function asignature()
    {
        return $this->belongsTo(Asignature::class, 'asignatures_id');
    }

    /**
     * Obtiene la relacion que hay entre el pensum y el area
     */
    public function area()
    {
        return $this->belongsTo(Area::class, 'areas_id');
    }

    /**
     * Obtiene la relacion que hay entre el pensum y el area
     */
    public function schoolYear()
    {
        return $this->belongsTo(Schoolyear::class, 'schoolyear_id');

    }

    public function subgroup()
    {
        return $this->belongsTo(Subgroup::class, 'sub_group_id');
    }


    public function subjectType()
    {
        return $this->belongsTo(SubjectType::class, 'subjects_type_id');
    }

    public static function find($group_id)
    {
        return self::select(
            'asignatures.abbreviation', 'asignatures.name',
            'sub_group_pensum.asignatures_id', 'sub_group_pensum.ihs', 'sub_group_pensum.order', 'sub_group_pensum.percent')
            ->join('asignatures', 'asignatures.id', '=', 'sub_group_pensum.asignatures_id')
            ->where('sub_group_pensum.sub_group_id', '=', $group_id)
            ->get();

        // return $asignatures;
    }
}
