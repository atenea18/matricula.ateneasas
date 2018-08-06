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
        return $this->hasOne(Student::class, 'address_id');
    }

    /**
     * Obtiene la relacion que hay entre identificacion y la sede
     */
    public function manager()
    {
        return $this->hasOne(Manager::class, 'address_id');
    }

    /**
     * Obtiene la relacion que hay entre identificacion y la sede
     */
    public function headquarter()
    {
        return $this->hasOne(Headquarter::class, 'address_id');
    }

    /**
     * Obtiene la relacion que hay entre identificacion y el acudiente
     */
    public function family()
    {
        return $this->hasOne(Family::class, 'address_id');
    }

    /**
     * Obtiene la relacion que hay entre identificacion y la zona rural
     */
    public function zone()
    {
        return $this->belongsTo(Zone::class, 'zone_id');
    }

    /**
     * Obtiene la relacion que hay entre identificacion y la Ciudad
     */
    public function city()
    {
        return $this->belongsTo(City::class, 'id_city_address');
    }
}
