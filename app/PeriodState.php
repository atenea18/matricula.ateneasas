<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PeriodState extends Model
{
    protected $fillable = [
    	'state'
    ]; 

    public function periods()
    {
    	return $this->hasMany(PeriodWorkingday::class, 'period_state_id'); 
    }
}
