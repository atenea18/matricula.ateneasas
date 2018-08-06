<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VictimOfConflict extends Model
{
    protected $table = 'victim_of_conflict';

    protected $fillabled =
    [
    	'name'
    ];	

    /**
     * Obtiene la relacion que hay entre la información de desplazamiento y victimas del conflicto armando
     */
    public function displacement()
    {
        return $this->hasMany('App\Displacement', 'victim_of_conflict_id');
    }
}
