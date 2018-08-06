<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discapacity extends Model
{
    protected $table = 'discapacity';

    protected $fillabled =
    [
    	'name'
    ];	

    /**
     * Obtiene la relacion que hay entre el estudiante y las discapacidades
     */
    public function students()
    {
        return $this->belongsToMany('App\Student');
    }
}
