<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    protected $table = 'family';

    protected $fillable =[
    	'name',
    	'last_name',
    	// 'student_id',
    	'identification_id',
    	'address_id',
    ];

    /**
     * Obtiene la relacion que hay entre el estudiante y los familiares
     */
    public function students()
    {
        return  $this->belongsToMany('App\Student', 'family_relationship_student')
                ->withPivot('relationship_id');
    }

    /**
     * Obtiene la relacion que hay entre los familiares y los parentesco
     */
    public function relationship()
    {
        return  $this->belongsToMany('App\RelationshipFamily', 'family_relationship_student')
                ->withPivot('student_id');
    }

    /**
     * Obtiene la relacion que hay entre el familiar y la direccion
     */
    public function address()
    {
        return $this->belongsTo('App\Address', 'address_id');
    }

    /**
     * Obtiene la relacion que hay entre identificacion y el familiar
     */
    public function identification()
    {
        return $this->belongsTo(Identification::class, 'identification_id');
    }
}
