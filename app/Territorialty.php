<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Territorialty extends Model
{
    protected $table = 'territorialty';

    protected $fillable = 
    [
    	'guard',
    	'ethnicity',
    	'student_id',
    ];

    /**
     * Obtiene la relacion que hay entre el estudiante y la territorialidad
     */
    public function student()
    {
        return $this->belongsTo('App\Student', 'student_id');
    }
}
