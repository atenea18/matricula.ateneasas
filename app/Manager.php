<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Manager extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'name', 
    	'last_name', 
    	'username',
        'password', 
    	'picture', 
    	'state_manager_id',
    	'identification_id',
    	'address_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getFullNameAttribute()
    {
        return "{$this->name} {$this->last_name}";
    }

    public function getFullNameInverseAttribute()
    {
        return "{$this->last_name} {$this->name}";
    }

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
