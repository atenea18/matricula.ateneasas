<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EvaluationPeriod extends Model
{
    protected $fillable = [
    	'code_evaluation_periods', 'enrollment_id', 'periods_id', 'asignatures_id'
    ];

    public function enrollment()
    {
    	return $this->belongsTo(Enrollment::class, 'enrollment_id');
    }

    public function notes()
    {
    	return $this->hasMany(Note::class, 'evaluation_periods_id');
    }

    public function noAttendances()
    {
        return $this->hasMany(NoAttendance::class, 'evaluation_periods_id');
    }

    public function noteFinal()
    {
    	return $this->hasOne(NotesFinal::class, 'evaluation_periods_id');
    }

    public function asignature()
    {
    	return $this->belongsTo(Asignature::class, 'asignatures_id');
    }
}
