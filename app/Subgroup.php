<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subgroup extends Model
{
    protected $table = 'sub_group';

    protected $fillable = [
    	'name', 'grade_id', 'headquarter_id'
    ];

    /**
     * Obtiene la relacion que hay entre el subgrupo y la sede
     */
    public function headquarter()
    {
        return $this->belongsTo(Headquarter::class, 'headquarter_id');
    }

    /**
     * Obtiene la relacion que hay entre el subgrupo y el grado
     */
    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }

     /**
     * Obtiene la relacion que hay entre el subgrupo y la matricula
     */
    public function enrollments()
    {
        return $this->belongsToMany(Enrollment::class, 'sub_group_assignments', 'subgroup_id', 'enrollment_id');
    }

    /**
     * Obtiene la relacion que hay entre el sungrupo y el director de subgrupo
     */
    public function director()
    {
        return $this->belongsToMany(Teacher::class, 'sub_group_directors', 'sub_group_id', 'teacher_id')
                ->withPivot('created_at', 'updated_at');
    }
}
