<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gender extends Model
{
    protected $table = 'gender';

    protected $fillable = 
    [
    	'gender',
    	'prefix'
    ];

    /**
     * Obtiene la relacion que hay entre identificacion y el genero
     */
    public function gender()
    {
        return $this->hasMany('App\Identification');
    }
}
