<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $table = 'grade';

 	protected $fillable = [
 		'name',
 		'academic_level',
 	];	

 	/**
     * Obtiene la relacion que hay entre el grupo y el grado
     */
    public function group()
    {
        return $this->belongsTo(Group::class, 'grade_id');
    }

    /**
     * Obtiene la relacion que hay entre el grado y la matricula
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'grade_id');
    }
}
