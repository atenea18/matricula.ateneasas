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
}
