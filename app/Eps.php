<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Eps extends Model
{
  	protected $table = 'eps';

    protected $fillabled =
    [
    	'code',
    	'name'
    ];	

    /**
     * Obtiene la relacion que hay entre la información medica y la eps
     */
    public function medicalInformation()
    {
        return $this->hasMny('App\MedicalInformation', 'eps_id');
    }
}
