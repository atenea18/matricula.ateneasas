<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Manager extends Model
{
    protected $fillable = [
    	'name', 
    	'last_name', 
    	'username', 
    	'picture', 
    	'state_manager_id',
    	'identification_id',
    	'address_id'
    ];

    /**
     * Obtiene la relacion que hay entre el ente administrativo y el docente
     */
    public function teachers()
    {
        return $this->hasMany(Teacher::class, 'manager_id');
    }

    /**
     * Obtiene la relacion que hay entre identificacion y ente administrativo
     */
    public function identification()
    {
        return $this->belongsTo(Identification::class, 'identification_id');
    }

    /**
     * Obtiene la relacion que hay entre direccion y ente administrativo
     */
    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }
}
