<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocioeconomicInformation extends Model
{
    protected $table = 'socioeconomic_information';

    protected $fillable =
    [
    	'sisben_number',
    	'sisben_level',
    	'amcf',
    	'bhdmcf',
    	'bvfp',
    	'bhn',
    	'student_id',
    	'stratum_id',
    ];

    /**
     * Obtiene la relacion que hay entre el estudiante y la información de socioeconomica
     */
    public function student()
    {
        return $this->belongsTo('App\Student', 'student_id');
    }

    /**
     * Obtiene la relacion que hay entre la información de socioeconomica y el estrato
     */
    public function stratum()
    {
        return $this->belongsTo('App\Stratum', 'stratum_id');
    }
}
