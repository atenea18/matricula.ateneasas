<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Workingday extends Model
{
    protected $table = 'working_day';

    protected $fillable = [

    	'name',
    ];	


    /**
     * Obtiene la relacion que hay entre el grupo y la jornada
     */
    public function groups()
    {
        return $this->hasMany('App\Group', 'working_day_id');
    }

    /**
     * Obtiene la relacion que hay entre el periodo y la jornada
     */
    public function periods()
    {
        return $this->hasMany(PeriodWorkingday::class, 'working_day_id');
    }
}
