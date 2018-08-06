<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $table = 'province';

    protected $fillable =
    [
    	'name'
    ];

    /**
     * Obtiene la relacion que hay entre la ciudad y la provincia/Departamento
     */
    public function province()
    {
        return $this->hasMany('App\City');
    }
}
