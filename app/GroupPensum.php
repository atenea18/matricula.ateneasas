<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupPensum extends Model
{
	protected $table = "group_pensum";

	// protected $primaryKey = 'code_group_pensum';

    protected $fillable = [
    	'code_group_pensum', 'percent', 'description', 'ihs', 'order', 'group_id', 'asignatures_id', 'areas_id', 'subjects_type_id', 'teacher_id', 'schoolyear_id'
    ];

    /**
     * Obtiene la relacion que hay entre el docente y los grupos la asignatura (Pensum)
     */
    public function teachers()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id'); 
    }

    /**
     * Obtiene la relacion que hay entre el pensum y el grupo
     */
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id'); 
    }

    /**
     * Obtiene la relacion que hay entre el pensum y la asignatura
     */
    public function asignature()
    {
        return $this->belongsTo(Asignature::class, 'asignatures_id'); 
    }

    /**
     * Obtiene la relacion que hay entre el pensum y el tipo de la asignatura
     */
    public function subjectType()
    {
        return $this->belongsTo(SubjectType::class, 'subjects_type_id'); 
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
}
