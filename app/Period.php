<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    protected $fillable = [
    	'period',
    ];

    public function periods()
    {
        return $this->hasMany(PeriodWorkingday::class, 'period_id');
    }
}
