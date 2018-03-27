<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PeriodState extends Model
{

	protected $table = "periods_state"; 

    protected $fillable = [
    	'name'
    ]; 

    public function periods()
    {
    	return $this->hasMany(PeriodWorkingday::class, 'periods_state_id'); 
    }
}
