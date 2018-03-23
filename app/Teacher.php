<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
    	'code',
    	'manager_id',
    	'institution_id',
    	'school_year_id',
    ];

    /**
     * Obtiene la relacion que hay entre el ente administrativo y el docente
     */
    public function manager()
    {
    	return $this->belongsTo(Manager::class, 'manager_id');
    }

    /**
     * Obtiene la relacion que hay entre el ente la institución y el docente
     */
    public function institution()
    {
        return $this->belongsTo(Institution::class, 'institution_id');
    }

    /**
     * Obtiene la relacion que hay entre el ente el año lectivo y el docente
     */
    public function schoolyear()
    {
        return $this->belongsTo(Schoolyear::class, 'school_year_id');
    }

    /**
     * Obtiene la relacion que hay entre el grupo y el director de grupo
     */
    public function groupDirector()
    {
        return $this->belongsToMany(Group::class, 'group_directors', 'teacher_id', 'group_id')
                ->withPivot('created_at', 'updated_at');    
    }

    /**
     * Obtiene la relacion que hay entre el docente y los grupos la asignatura (Pensum)
     */
    public function pensums()
    {
        return $this->hasMany(GroupPensum::class, 'teacher_id'); 
    }

    public function isDirector()
    {
        return ( count($this->groupDirector()->get()) > 0 );
    }
}
