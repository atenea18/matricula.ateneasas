<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $table = 'notes';

    protected $fillable = [
    	'code_notes', 'value', 'overcoming', 'evaluation_periods_id', 'notes_parameters_id'
    ];

    public function evaluationPeriod()
    {
    	return $this->belongsTo(EvaluationPeriod::class, 'evaluation_periods_id');
    }

    public function noteParameter()
    {
    	return $this->belongsTo(NotesParameters::class, 'notes_parameters_id');
    }
}
