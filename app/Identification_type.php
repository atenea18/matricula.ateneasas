<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Identification_type extends Model
{
    protected $table = 'identification_type';

    protected $fillable = 
    [
    	'name',
    	'abbreviation'
    ];

    /**
     * Obtiene la relacion que hay entre identificacion y el tipo de identificacion
     */
    public function identification_type()
    {
        return $this->hasMany('App\Identification');
    }
}
