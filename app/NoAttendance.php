<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NoAttendance extends Model
{
    protected $table = 'no_attendance';

    protected $fillable = [
    	'quantity', 'evaluation_periods_id'
    ];

    public function evaluationPeriod()
    {
        return $this->belongsTo(EvaluationPeriod::class, 'evaluation_periods_id');
    }
}
