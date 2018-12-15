<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schoolyear extends Model
{
    protected $fillable = [
        'year'
    ];

    /**
     * Obtiene la relacion que hay entre la matricula y el año lectivo
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'school_year_id');
    }

     /**
     * Obtiene la relacion que hay entre el pensum y el año lectivo
     */
    public function pensums()
    {
        return $this->hasMany(GroupPensum::class, 'schoolyear_id'); 
    }

    /**
     * Obtiene la relacion que hay entre el ente el año lectivo y el docente
     */
    public function teachers()
    {
        return $this->belongsTo(Teacher::class, 'school_year_id');
    }

    /**
     * Obtiene la relacion que hay entre la matricula y el año lectivo
     */
    public function evaluationParameter()
    {
        return $this->hasMany(EvaluationParameter::class, 'school_year_id');
    }

    /**
     * Obtiene la relacion que hay entre el periodo y el año lectivo
     */
    public function periods()
    {
        return $this->hasMany(PeriodWorkingday::class, 'school_year_id');
    }

    /**
     * Obtiene la relacion que hay entre la escala valorativa y el año lectivo
     */
    public function scaleEvaluations()
    {
        return $this->hasMany(ScaleEvaluation::class, 'school_year_id');
    }
}
