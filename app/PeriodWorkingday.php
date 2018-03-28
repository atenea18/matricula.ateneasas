<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PeriodWorkingday extends Model
{
	protected $table = "working_day_periods";

    protected $fillable = [
    	'code_working_day_periods', 'percent', 'start_date', 'end_date', 'working_day_id', 'periods_id', 'periods_state_id', 'institution_id', 'school_year_id'
    ];

    public function institution()
    {
        return $this->belongsTo(Institution::class, 'institution_id');
    }

    public function period()
    {
    	return $this->belongsTo(Period::class, 'periods_id');
    }

    public function state()
    {
    	return $this->belongsTo(PeriodState::class, 'periods_state_id');
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
        return $this->hasMany(GeneralObservation::class, 'period_working_day_id');
    }

    public function generalReport()
    {
        return $this->hasMany(GeneralReport::class, 'period_working_day_id');
    }
}
