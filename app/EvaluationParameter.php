<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EvaluationParameter extends Model
{
    protected $fillable = [
		'parameter', 'abbreviation', 'percent', 'institution_id', 'school_year_id'    	
    ];

    public function institution()
    {
    	return $this->belongsTo(Institution::class, 'institution_id');
    }

    public function criterias()
    {
    	return $this->hasMany(Criteria::class, 'evaluation_parameter_id');
    }

    /**
     * Obtiene la relacion que hay entre la matricula y el año lectivo
     */
    public function schoolYear()
    {
        return $this->belongsTo(Schoolyear::class, 'school_year_id');
    }

    public function  notesParameter()
    {
        return $this->hasMany(NotesParameters::class, '');
    }


}
