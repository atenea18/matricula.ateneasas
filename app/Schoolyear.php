<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schoolyear extends Model
{
    /**
     * Obtiene la relacion que hay entre la matricula y el año lectivo
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'school_year_id');
    }
}
