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
}
