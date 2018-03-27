<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PeriodWorkingday extends Model
{
	protected $table = "period_working_day";

    protected $fillable = [
    	'code', 'percent', 'start_date', 'end_date', 'working_day_id', 'period_id', 'period_state_id', 'institution_id', 'school_year_id'
    ];

    public function institution()
    {
        return $this->belongsTo(Institution::class, 'institution_id');
    }

    public function period()
    {
    	return $this->belongsTo(Period::class, 'period_id');
    }

    public function state()
    {
    	return $this->belongsTo(PeriodState::class, 'period_state_id');
    }

    public function schoolyear()
    {
    	return $this->belongsTo(Schoolyear::class, 'school_year_id');
    }

    public function workingday()
    {
    	return $this->belongsTo(Workingday::class, 'working_day_id');
    }

    public function generalObservations()
    {
        return $this->belongsTo(GeneralObservation::class, 'period_working_day_id');
    }
}
