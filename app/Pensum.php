<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pensum extends Model
{

	protected $table = "pensum";
	
    protected $fillable = [
    	'code_pensum', 'percent', 'description', 'ihs', 'order', 'grade_id', 'areas_id', 'subjects_type_id', 'asignatures_id', 'institution_id'
    ];

    /**
     * Obtiene la relacion que hay entre el grupo los pensums
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_pensum', 'pensum_id', 'group_id');
    }
}
