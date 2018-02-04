<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    // protected $table = 'zone';

    protected $fillable = 
    [
    	'name'
    ];

    /**
     * Obtiene la relacion que hay entre identificacion y la zona rural
     */
    public function address()
    {
        return $this->hasMany('App\Address');
    }
}
