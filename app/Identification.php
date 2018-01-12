<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Identification extends Model
{
    protected $table = 'identification';

    protected $fillable = 
    [
    	'state', 
    	'identification_type_id', 
    	'identification_number',
    	'id_city_expedition',
    	'gender_id',
    	'birthdate',
    	'id_city_of_birth',
   	];

   	/**
     * Obtiene la relacion que hay entre identificacion y estudiante
     */
    public function student()
    {
        return $this->hasOne('App\Student', 'identification_id');
    }

    /**
     * Obtiene la relacion que hay entre identificacion y el familiar
     */
    public function family()
    {
        return $this->hasOne('App\Family', 'identification_id');
    }

    /**
     * Obtiene la relacion que hay entre identificacion y el tipo de identificacion
     */
    public function identification_type()
    {
        return $this->belongsTo('App\Identification_type', 'identification_type_id');
    }

    /**
     * Obtiene la relacion que hay entre identificacion y el genero
     */
    public function gender()
    {
        return $this->belongsTo('App\Gender', 'gender_id');
    }

    /**
     * Obtiene la relacion que hay entre identificacion y la ciudad de expedición
     */
    public function city_expedition()
    {
        return $this->belongsTo('App\City', 'id_city_expedition');
    }

    /**
     * Obtiene la relacion que hay entre identificacion y la ciudad de nacimiento
     */
    public function city_birth()
    {
        return $this->belongsTo('App\City', 'id_city_of_birth');
    }
}
