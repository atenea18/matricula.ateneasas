<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GeneralReport extends Model
{
   	protected $fillable = [
   		'code', 'enrollment_id', 'period_working_day_id', 'report'
   	];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id');
    }

    public function periodWorkingday()
    {
    	return $this->belongsTo(PeriodWorkingday::class, 'period_working_day_id');
    }
}
