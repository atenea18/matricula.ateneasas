<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'address';

    protected $fillable = 
    [
        'id',
    	'address',
    	'neighborhood',
    	'id_city_address',
    	'zone_id',
    	'phone',
    	'mobil',
    	'email'
    ];

    /**
     * Obtiene la relacion que hay entre identificacion y estudiante
     */
    public function student()
    {
        return $this->hasOne('App\Student', 'address_id');
    }

    /**
     * Obtiene la relacion que hay entre identificacion y la sede
     */
    public function headquarter()
    {
        return $this->hasOne('App\Headquarter', 'address_id');
    }

    /**
     * Obtiene la relacion que hay entre identificacion y el acudiente
     */
    public function family()
    {
        return $this->hasOne('App\Family', 'address_id');
    }

    /**
     * Obtiene la relacion que hay entre identificacion y la zona rural
     */
    public function zone()
    {
        return $this->belongsTo('App\Zone', 'zone_id');
    }

    /**
     * Obtiene la relacion que hay entre identificacion y la Ciudad
     */
    public function city()
    {
        return $this->belongsTo('App\City', 'id_city_address');
    }
}
