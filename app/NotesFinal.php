<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotesFinal extends Model
{
    protected $table = 'notes_final';

    protected $fillable = [
    	'code_notes_final', 'value', 'overcoming', 'evaluation_periods_id'
    ]; 

    public function evaluationPeriod()
    {
    	return $this->belongsTo(EvaluationPeriod::class, 'evaluation_periods_id');
    }
}
