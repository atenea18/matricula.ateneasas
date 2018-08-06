<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Capacity extends Model
{
    protected $table = 'capacity';

    protected $fillabled =
    [
    	'name'
    ];	

    /**
     * Obtiene la relacion que hay entre el estudiante y las capacidades
     */
    public function students()
    {
        return $this->belongsToMany('App\Student');
    }
}
