<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'city';

    protected $fillable = 
    [
    	'name',
    	'id_province'
    ];

    /**
     * Obtiene la relacion que hay entre identificacion y la zona rural
     */
    public function address()
    {
        return $this->hasMany('App\Address');
    }

    /**
     * Obtiene la relacion que hay entre la ciudad y la provincia/Departamento
     */
    public function province()
    {
        return $this->belongsTo('App\Province');
    }
}
