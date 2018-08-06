<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stratum extends Model
{
    protected $table = 'stratum';

    protected $fillabled =
    [
    	'stratum'
    ];	

    /**
     * Obtiene la relacion que hay entre la información de socioeconomica y el estrato
     */
    public function socioeconomic()
    {
        return $this->hasMany('App\SocioeconomicInformation', 'stratum_id');
    }
}
