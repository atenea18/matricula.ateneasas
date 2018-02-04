<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentState extends Model
{
    protected = $fillable = [
    	'state'
    ];

    /**
     * Obtiene la relacion que hay entre el estado y estudiante
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'state_id');
    }
}
