<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotesParameters extends Model
{
	protected $fillable = [
		'code_notes_parameters', 'name', 'percent', 'evaluation_parameter_id', 'notes_type_id'
	];

    public function evaluationParameters()
    {
        return $this->belongsTo(EvaluationParameter::class, 'evaluation_parameter_id');
    }

    public function notes()
    {
    	return $this->hasMany(Note::class, 'notes_parameters_id');
    }

    public function notePerformances()
    {
    	return $this->hasMany(NotesParametersPerformances::class, 'notes_parameters_id');
    }
}
