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
    public function groups()
    {
        return $this->hasMany(Group::class, 'grade_id');
    }

    /**
     * Obtiene la relacion que hay entre el subgrupo y el grado
     */
    public function subgroups()
    {
        return $this->hasMany(Subgroup::class, 'grade_id');
    }


    /**
     * Obtiene la relacion que hay entre el grado y la matricula
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'grade_id');
    }


}
