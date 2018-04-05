<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    protected $fillable = [
    	'name', 'period',
    ];

    public function periods()
    {
        return $this->hasMany(PeriodWorkingday::class, 'periods_id');
    }
}
