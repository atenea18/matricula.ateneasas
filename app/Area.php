<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = [

    ];

    /**
     * Obtiene la relacion que hay entre el pensum y el area
     */
    public function pensums()
    {
        return $this->hasMany(GroupPensum::class, 'areas_id'); 
    }
}
